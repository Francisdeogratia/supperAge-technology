<?php

namespace App\Http\Controllers;

use App\Services\FlutterwaveService;
use Illuminate\Http\Request;
use App\Models\UserRecord;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WithdrawalController extends Controller
{
    protected $flw;

    public function __construct(FlutterwaveService $flw)
    {
        $this->flw = $flw;
    }

    /**
     * Show withdrawal form with bank list
     */
    public function showForm(Request $request)
    {
        $user = UserRecord::find(session('id'));
        if (!$user) {
            return redirect()->route('account')->withErrors(['error' => 'Please log in first.']);
        }

        // Badge expiry check
        $user->badge_expired = !empty($user->badge_expires_at) &&
            \Carbon\Carbon::parse($user->badge_expires_at)->isPast();

        $balances = WalletTransaction::where('wallet_owner_id', $user->id)
            ->where('status', 'successful')
            ->select('currency', DB::raw('SUM(amount) as total'))
            ->groupBy('currency')
            ->pluck('total', 'currency')
            ->toArray();

        // Country list
        $countries = [
            'NG'  => 'Nigeria',
            'GH'  => 'Ghana',
            'KE'  => 'Kenya',
            'ZA'  => 'South Africa',
            'UG'  => 'Uganda',
            'TZ'  => 'Tanzania',
            'CM'  => 'Cameroon',
            'CI'  => 'Côte d\'Ivoire',
            'SN'  => 'Senegal',
            'ZM'  => 'Zambia',
            'MW'  => 'Malawi',
            'SL'  => 'Sierra Leone',
            'GB'  => 'United Kingdom',
            'EU'  => 'SEPA Countries',
            'US'  => 'United States'
        ];

        // Currency list
        $currencies = [
            'NGN' => 'Nigerian Naira',
            'GHS' => 'Ghanaian Cedi',
            'KES' => 'Kenyan Shilling',
            'ZAR' => 'South African Rand',
            'UGX' => 'Ugandan Shilling',
            'TZS' => 'Tanzanian Shilling',
            'XAF' => 'Central African CFA franc',
            'XOF' => 'West African CFA franc',
            'ZMW' => 'Zambian Kwacha',
            'MWK' => 'Malawian Kwacha',
            'SLL' => 'Sierra Leonean Leone',
            'GBP' => 'British Pound',
            'EUR' => 'Euro',
            'USD' => 'US Dollar'
        ];

        $selectedCountry = $request->get('country', 'NG');
        $banks = $this->flw->getBanks($selectedCountry);

        // Fetch live rates from Flutterwave, cached for 30 minutes
        $exchangeRates = cache()->remember('flw_exchange_rates', now()->addMinutes(30), function () use ($currencies) {
            $rates = [];
            foreach (array_keys($currencies) as $currencyCode) {
                if ($currencyCode === 'NGN') {
                    $rates[$currencyCode] = 1;
                    continue;
                }

                $response = Http::withToken(env('FLW_SECRET_KEY'))
                    ->get('https://api.flutterwave.com/v3/transfers/rates', [
                        'amount' => 1,
                        'source_currency' => 'NGN',
                        'destination_currency' => $currencyCode
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['data']['source']['amount']) && $data['data']['source']['amount'] > 0) {
                        $rates[$currencyCode] = 1 / (float) $data['data']['source']['amount'];
                    }
                }
            }
            return $rates;
        });

        return view('wallet.withdraw', [
            'user'            => $user,
            'balances'        => $balances,
            'countries'       => $countries,
            'currencies'      => $currencies,
            'banks'           => $banks['data'] ?? [],
            'selectedCountry' => $selectedCountry,
            'exchangeRates'   => $exchangeRates
        ]);
    }

    /**
     * Process withdrawal with improved error handling
     */
    public function processWithdrawal(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'bank_code' => 'required|string',
            'account_number' => 'required|string|digits:10',
            'currency' => 'required|string'
        ]);

        $user = UserRecord::find(session('id'));
        if (!$user) {
            return redirect()->route('account')->withErrors(['error' => 'Please log in first.']);
        }

        try {
            // Get wallet balances
            $balances = WalletTransaction::where('wallet_owner_id', $user->id)
                ->where('status', 'successful')
                ->select('currency', DB::raw('SUM(amount) as total'))
                ->groupBy('currency')
                ->pluck('total', 'currency')
                ->toArray();

            $currency = $request->input('currency');
            $amountRequestedInCurrency = (float) $request->input('amount');

            // Check if currency balance exists
            if (!isset($balances[$currency])) {
                return back()->withErrors(['error' => "No balance available in {$currency}"]);
            }

            $currentBalance = $balances[$currency];

            // Apply 50% rule - user can only withdraw 50% of balance
            $netAvailable = $currentBalance * 0.50;

            if ($amountRequestedInCurrency > $netAvailable) {
                return back()->withErrors([
                    'error' => "Insufficient balance. You can withdraw up to 50% of your {$currency} balance (Available: {$netAvailable} {$currency})"
                ]);
            }

            // Get exchange rates
            $exchangeRates = cache()->get('flw_exchange_rates', []);
            if (!isset($exchangeRates[$currency])) {
                return back()->withErrors(['error' => 'Exchange rate not available for ' . $currency]);
            }

            // Calculate amounts
            $payoutAmount = $amountRequestedInCurrency * 0.50; // 50% to user
            $trashedAmount = $amountRequestedInCurrency * 0.50; // 50% system charge

            // Convert to NGN for wallet deduction
            $rate = $exchangeRates[$currency];
            $amountInNGN = $amountRequestedInCurrency / $rate;

            // Step 1: Verify account number first
            Log::info('Verifying account number', [
                'account_number' => $request->input('account_number'),
                'bank_code' => $request->input('bank_code')
            ]);

            $verifyResponse = Http::withToken(env('FLW_SECRET_KEY'))
                ->post('https://api.flutterwave.com/v3/accounts/resolve', [
                    'account_number' => $request->input('account_number'),
                    'account_bank' => $request->input('bank_code')
                ]);

            if (!$verifyResponse->successful()) {
                $errorMsg = $verifyResponse->json('message') ?? 'Could not verify account';
                Log::error('Account verification failed', [
                    'response' => $verifyResponse->json(),
                    'status' => $verifyResponse->status()
                ]);
                
                return back()->withErrors(['error' => "Account verification failed: {$errorMsg}"]);
            }

            $accountName = $verifyResponse->json('data.account_name');
            Log::info('Account verified', ['account_name' => $accountName]);

            // Generate unique reference
            $reference = 'WD_' . time() . '_' . $user->id;

            // Step 2: Create pending transaction in wallet
            $transaction = WalletTransaction::create([
                'wallet_owner_id' => $user->id,
                'payer_id'        => $user->id,
                'transaction_id'  => uniqid('wd_'),
                'tx_ref'          => $reference,
                'currency'        => $currency,
                'amount'          => -$amountInNGN, // Negative = deduction in NGN equivalent
                'status'          => 'pending',
                'description'     => "Withdrawal: {$payoutAmount} {$currency} to {$accountName}"
            ]);

            // Step 3: Initiate Flutterwave transfer
            Log::info('Initiating Flutterwave transfer', [
                'amount' => $payoutAmount,
                'currency' => $currency,
                'reference' => $reference
            ]);

            $transferResponse = Http::withToken(env('FLW_SECRET_KEY'))
                ->post('https://api.flutterwave.com/v3/transfers', [
                    'account_bank'     => $request->input('bank_code'),
                    'account_number'   => $request->input('account_number'),
                    'amount'           => $payoutAmount,
                    'currency'         => $currency,
                    'narration'        => 'Wallet Withdrawal',
                    'reference'        => $reference,
                    'callback_url'     => route('wallet.webhook'),
                    'debit_currency'   => 'NGN',
                    'meta'             => [
                        'user_id' => $user->id,
                        'user_name' => $user->name
                    ]
                ]);

            // Log full response for debugging
            Log::info('Flutterwave transfer response', [
                'status' => $transferResponse->status(),
                'body' => $transferResponse->json()
            ]);

            if ($transferResponse->successful()) {
                $transferData = $transferResponse->json('data');
                
                // Update transaction with transfer details
                $transaction->update([
                    'transaction_id' => $transferData['id'] ?? $transaction->transaction_id,
                    'status' => strtolower($transferData['status'] ?? 'pending')
                ]);

                return back()->with('success', 
                    "Withdrawal initiated successfully! Amount: {$currency} {$payoutAmount} to {$accountName}. " .
                    "System charge: {$currency} {$trashedAmount}"
                );

            } else {
                // Handle failure - rollback transaction
                $transaction->delete();

                $errorMessage = $transferResponse->json('message') ?? 'Unknown error';
                $errorData = $transferResponse->json('data') ?? null;
                
                Log::error('Flutterwave transfer failed', [
                    'status' => $transferResponse->status(),
                    'message' => $errorMessage,
                    'data' => $errorData,
                    'full_response' => $transferResponse->body()
                ]);

                // Parse specific error messages
                $userMessage = $this->parseFlutterwaveError($errorMessage, $transferResponse->status());

                return back()->withErrors(['error' => $userMessage]);
            }

        } catch (\Exception $e) {
            Log::error('Withdrawal exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['error' => 'An unexpected error occurred. Please try again or contact support.']);
        }
    }

    /**
     * Parse Flutterwave error messages into user-friendly format
     */
    private function parseFlutterwaveError($message, $statusCode)
    {
        // Common Flutterwave error messages and their meanings
        $errorMap = [
            'cannot be processed' => 'Your transfer request cannot be processed at this time. This usually means:
                • Your Flutterwave account needs to enable transfers
                • Account verification is incomplete
                • Insufficient balance in Flutterwave account
                Please contact support for assistance.',
                
            'Insufficient' => 'Insufficient balance in payment gateway. Please contact support.',
            
            'Invalid account' => 'The provided account number is invalid or does not exist. Please check and try again.',
            
            'authentication' => 'Authentication failed. Please contact support to update API credentials.',
            
            'rate limit' => 'Too many requests. Please wait a few minutes and try again.',
            
            'not enabled' => 'Transfers are not enabled on your merchant account. Please contact Flutterwave support.',
            
            'blacklist' => 'This transaction cannot be processed due to security restrictions. Please contact support.',
        ];

        foreach ($errorMap as $key => $friendlyMessage) {
            if (stripos($message, $key) !== false) {
                return $friendlyMessage;
            }
        }

        // Default message
        return "Withdrawal failed: {$message}. Please contact support if this persists.";
    }

    /**
     * Handle Flutterwave webhook
     */
    public function webhook(Request $request)
    {
        // Verify signature
        if (!$this->flw->verifyWebhook($request)) {
            Log::warning('Invalid webhook signature', ['ip' => $request->ip()]);
            return response('Invalid signature', 401);
        }

        $payload = $request->all();
        Log::info('Webhook received', ['payload' => $payload]);

        // Handle transfer completion
        if (isset($payload['event']) && $payload['event'] === 'transfer.completed') {
            $transferData = $payload['data'];
            $reference = $transferData['reference'] ?? null;
            $status = strtolower($transferData['status'] ?? '');

            if ($reference) {
                $transaction = WalletTransaction::where('tx_ref', $reference)->first();

                if ($transaction) {
                    if ($status === 'successful') {
                        $transaction->status = 'successful';
                        Log::info('Transfer successful', ['reference' => $reference]);
                        
                    } elseif (in_array($status, ['failed', 'rejected'])) {
                        $transaction->status = 'failed';
                        
                        // Refund the amount back to user's wallet
                        WalletTransaction::create([
                            'wallet_owner_id' => $transaction->wallet_owner_id,
                            'payer_id'        => $transaction->wallet_owner_id,
                            'transaction_id'  => uniqid('refund_'),
                            'tx_ref'          => 'REFUND_' . $reference,
                            'currency'        => $transaction->currency,
                            'amount'          => abs($transaction->amount), // Positive refund
                            'status'          => 'successful',
                            'description'     => "Refund for failed withdrawal ({$reference})"
                        ]);
                        
                        Log::info('Transfer failed - refund issued', ['reference' => $reference]);
                    }
                    
                    $transaction->save();
                }
            }
        }

        return response('Webhook processed', 200);
    }

    /**
     * Get banks by country
     */
    public function getBanksByCountry($country)
    {
        $banks = $this->flw->getBanks($country);
        return response()->json($banks['data'] ?? []);
    }
}