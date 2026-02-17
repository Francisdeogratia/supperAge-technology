<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\UserRecord;
use App\Models\Advertisement;
use App\Models\AdImpression;
use App\Models\AdClick;
use App\Models\AdAction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AdvertisingController extends Controller
{
    private $flutterwaveSecretKey;

    // Supported currencies with their conversion rates to NGN
    private $supportedCurrencies = [
        'NGN' => 1,
        'USD' => 1650,
        'GBP' => 2100,
        'EUR' => 1800,
        'GHS' => 110,
        'KES' => 12,
        'ZAR' => 90,
    ];

    public function __construct()
    {
        $this->flutterwaveSecretKey = env('FLW_SECRET_KEY');
    }

    /**
     * Show ad manager dashboard
     */
    public function index()
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'You must be logged in.');
        }
        
        $user = UserRecord::find($userId);
        
        $ads = Advertisement::where('advertiser_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $totalAds = Advertisement::where('advertiser_id', $userId)->count();
        $activeAds = Advertisement::where('advertiser_id', $userId)->where('status', 'active')->count();
        $totalSpent = Advertisement::where('advertiser_id', $userId)->sum('spent');
        $totalImpressions = Advertisement::where('advertiser_id', $userId)->sum('impressions');
        $totalClicks = Advertisement::where('advertiser_id', $userId)->sum('clicks');
        
        return view('advertising.index', compact(
            'user', 
            'ads', 
            'totalAds', 
            'activeAds', 
            'totalSpent', 
            'totalImpressions', 
            'totalClicks'
        ));
    }
    
    /**
     * Show create ad form
     */
    public function create()
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'You must be logged in.');
        }
        
        $user = UserRecord::find($userId);
        
        // Get user's wallet balances
        $balances = $this->getUserWalletBalances($userId);
        
        // Supported currencies
        $currencies = [
            'NGN' => 'Nigerian Naira',
            'USD' => 'US Dollar',
            'GBP' => 'British Pound',
            'EUR' => 'Euro',
            'GHS' => 'Ghanaian Cedi',
            'KES' => 'Kenyan Shilling',
            'TZS' => 'Tanzanian Shilling',
            'UGX' => 'Ugandan Shilling',
            'ZAR' => 'South African Rand',
            'XAF' => 'Central African CFA franc',
            'XOF' => 'West African CFA franc'
        ];
        
        return view('advertising.create', compact('user', 'balances', 'currencies'));
    }
    
    /**
     * Get user's wallet balances in all currencies
     */
    private function getUserWalletBalances($userId)
    {
        $user = UserRecord::find($userId);
        
        // Get balances from WalletTransaction table grouped by currency
        $balances = \App\Models\WalletTransaction::where('wallet_owner_id', $userId)
            ->where('status', 'successful')
            ->select('currency', DB::raw('SUM(amount) as total'))
            ->groupBy('currency')
            ->pluck('total', 'currency')
            ->toArray();
        
        return $balances;
    }
    
    /**
 * Get conversion rate - FINAL UNIFIED VERSION
 * This will give you correct live rates from Flutterwave
 */
public function getConversionRate(Request $request)
{
    try {
        $currency = strtoupper($request->input('currency', 'NGN'));
        $baseAmountNGN = floatval($request->input('amount', 2500));
        
        Log::info('Conversion rate requested', [
            'currency' => $currency,
            'amount' => $baseAmountNGN
        ]);
        
        // Validate currency
        $supportedCurrencies = ['NGN', 'USD', 'GBP', 'EUR', 'GHS', 'KES', 'ZAR', 'TZS', 'UGX', 'XAF', 'XOF'];
        if (!in_array($currency, $supportedCurrencies)) {
            return response()->json([
                'success' => false,
                'message' => 'Unsupported currency: ' . $currency
            ], 400);
        }
        
        // If NGN, return immediately
        if ($currency === 'NGN') {
            return response()->json([
                'success' => true,
                'currency' => $currency,
                'convertedAmount' => $baseAmountNGN,
                'rate' => 1
            ]);
        }
        
        // Try live API first
        try {
            $convertedAmount = $this->convertFromNGN($baseAmountNGN, $currency);
            
            Log::info('Conversion successful (live API)', [
                'from' => 'NGN',
                'to' => $currency,
                'amount' => $baseAmountNGN,
                'converted' => $convertedAmount
            ]);
            
            return response()->json([
                'success' => true,
                'currency' => $currency,
                'convertedAmount' => round($convertedAmount, 2),
                'rate' => round($convertedAmount / $baseAmountNGN, 6),
                'using_fallback' => false
            ]);
            
        } catch (\Exception $apiError) {
            // API failed, use fallback rates
            Log::warning('Flutterwave API failed, using fallback rates', [
                'currency' => $currency,
                'error' => $apiError->getMessage()
            ]);
            
            // Static fallback rates (update these periodically)
            $fallbackRates = [
                'USD' => 0.00061,  // 1 NGN = 0.00061 USD (~1640 NGN per USD)
                'GBP' => 0.00048,  // 1 NGN = 0.00048 GBP (~2083 NGN per GBP)
                'EUR' => 0.00056,  // 1 NGN = 0.00056 EUR (~1785 NGN per EUR)
                'GHS' => 0.0091,   // 1 NGN = 0.0091 GHS (~110 NGN per GHS)
                'KES' => 0.083,    // 1 NGN = 0.083 KES (~12 NGN per KES)
                'ZAR' => 0.011,    // 1 NGN = 0.011 ZAR (~90 NGN per ZAR)
                'TZS' => 1.65,     // 1 NGN = 1.65 TZS (~0.6 NGN per TZS)
                'UGX' => 2.4,      // 1 NGN = 2.4 UGX (~0.42 NGN per UGX)
                'XAF' => 0.37,     // 1 NGN = 0.37 XAF (~2.7 NGN per XAF)
                'XOF' => 0.37,     // 1 NGN = 0.37 XOF (~2.7 NGN per XOF)
            ];
            
            if (isset($fallbackRates[$currency])) {
                $convertedAmount = $baseAmountNGN * $fallbackRates[$currency];
                
                return response()->json([
                    'success' => true,
                    'currency' => $currency,
                    'convertedAmount' => round($convertedAmount, 2),
                    'rate' => $fallbackRates[$currency],
                    'using_fallback' => true,
                    'message' => 'Using estimated exchange rate'
                ]);
            }
            
            throw $apiError;
        }
        
    } catch (\Exception $e) {
        Log::error('Conversion rate error', [
            'currency' => $request->input('currency'),
            'amount' => $request->input('amount'),
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Unable to get conversion rate. Please try again or select NGN.',
            'error' => config('app.debug') ? $e->getMessage() : 'Conversion service unavailable'
        ], 500);
    }
}

/**
 * Convert NGN to any currency - FINAL VERSION
 * This uses Flutterwave's live exchange rates
 */
private function convertFromNGN($amountInNGN, $toCurrency)
{
    if ($toCurrency === 'NGN') {
        return $amountInNGN;
    }
    
    // Check if API key exists
    if (empty($this->flutterwaveSecretKey)) {
        Log::error('Flutterwave API key not configured');
        throw new \Exception("Flutterwave API key not configured");
    }
    
    try {
        Log::info('Calling Flutterwave API', [
            'from' => 'NGN',
            'to' => $toCurrency,
            'amount' => $amountInNGN
        ]);
        
        $response = Http::timeout(10)
            ->retry(2, 200) // Retry twice with 200ms delay
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->flutterwaveSecretKey,
                'Content-Type' => 'application/json',
            ])
            ->get('https://api.flutterwave.com/v3/rates', [
                'from' => 'NGN',
                'to' => $toCurrency,
                'amount' => $amountInNGN
            ]);
        
        if (!$response->successful()) {
            Log::error('Flutterwave API HTTP error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            throw new \Exception("Flutterwave API returned status " . $response->status());
        }
        
        $data = $response->json();
        
        Log::info('Flutterwave API response', [
            'response' => $data
        ]);
        
        if (isset($data['status']) && $data['status'] === 'success') {
            // Try different response formats
            if (isset($data['data']['to']['rate'])) {
                // Format 1: Nested structure
                $rate = floatval($data['data']['to']['rate']);
                return round($amountInNGN * $rate, 2);
            } 
            elseif (isset($data['data']['rate'])) {
                // Format 2: Direct rate
                $rate = floatval($data['data']['rate']);
                return round($rate * $amountInNGN, 2);
            }
            elseif (isset($data['data']['to']['amount'])) {
                // Format 3: Direct amount
                return round(floatval($data['data']['to']['amount']), 2);
            }
        }
        
        throw new \Exception("Invalid Flutterwave API response: " . json_encode($data));
        
    } catch (\Illuminate\Http\Client\ConnectionException $e) {
        Log::error('Flutterwave connection failed', [
            'error' => $e->getMessage(),
            'from' => 'NGN',
            'to' => $toCurrency
        ]);
        throw new \Exception("Failed to connect to currency service");
        
    } catch (\Illuminate\Http\Client\RequestException $e) {
        Log::error('Flutterwave request failed', [
            'error' => $e->getMessage(),
            'response' => $e->response ? $e->response->body() : null
        ]);
        throw new \Exception("Currency service request failed");
    }
}

/**
 * Convert any currency to NGN - FINAL VERSION
 * Used when processing wallet payments in foreign currencies
 */
private function convertToNGN($amount, $fromCurrency)
{
    if ($fromCurrency === 'NGN') {
        return $amount;
    }
    
    if (empty($this->flutterwaveSecretKey)) {
        throw new \Exception("Flutterwave API key not configured");
    }
    
    try {
        Log::info('Converting to NGN', [
            'from' => $fromCurrency,
            'amount' => $amount
        ]);
        
        $response = Http::timeout(10)
            ->retry(2, 200)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->flutterwaveSecretKey,
                'Content-Type' => 'application/json',
            ])
            ->get('https://api.flutterwave.com/v3/rates', [
                'from' => $fromCurrency,
                'to' => 'NGN',
                'amount' => $amount
            ]);
        
        if (!$response->successful()) {
            throw new \Exception("API returned status " . $response->status());
        }
        
        $data = $response->json();
        
        if (isset($data['status']) && $data['status'] === 'success') {
            if (isset($data['data']['to']['rate'])) {
                return round($amount * floatval($data['data']['to']['rate']), 2);
            } 
            elseif (isset($data['data']['rate'])) {
                return round($data['data']['rate'] * $amount, 2);
            }
            elseif (isset($data['data']['to']['amount'])) {
                return round(floatval($data['data']['to']['amount']), 2);
            }
        }
        
        throw new \Exception("Invalid API response");
        
    } catch (\Exception $e) {
        Log::error('Currency to NGN conversion failed', [
            'from' => $fromCurrency,
            'amount' => $amount,
            'error' => $e->getMessage()
        ]);
        throw $e;
    }
}
    
    /**
     * Pay from wallet
     */
    /**
 * Pay from wallet - FIXED VERSION
 */
/**
 * Pay from wallet - FIXED VERSION with detailed logging
 */
public function payFromWallet(Request $request)
{
    // STEP 1: Check authentication
    $userId = Session::get('id');
    
    if (!$userId) {
        Log::error('Wallet payment: User not logged in');
        return response()->json([
            'success' => false,
            'error' => 'You must be logged in to make payments'
        ], 401);
    }
    
    Log::info('Wallet payment started', [
        'user_id' => $userId,
        'request_data' => $request->all()
    ]);
    
    // STEP 2: Validate request
    try {
        $request->validate([
            'currency' => 'required|string',
            'ad_data' => 'required|string',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Wallet payment validation failed', [
            'errors' => $e->errors()
        ]);
        
        return response()->json([
            'success' => false,
            'error' => 'Invalid request data',
            'errors' => $e->errors()
        ], 422);
    }
    
    // STEP 3: Get user and decode ad data
    $user = UserRecord::find($userId);
    $currency = strtoupper($request->currency);
    
    $adData = json_decode($request->ad_data, true);
    
    if (!$adData) {
        Log::error('Wallet payment: Invalid ad data JSON', [
            'ad_data_raw' => $request->ad_data
        ]);
        
        return response()->json([
            'success' => false,
            'error' => 'Invalid advertisement data format'
        ], 400);
    }
    
    // STEP 4: Get budget
    $budgetNGN = floatval($adData['budget'] ?? 0);
    
    if ($budgetNGN < 2500) {
        Log::warning('Wallet payment: Budget too low', [
            'budget' => $budgetNGN
        ]);
        
        return response()->json([
            'success' => false,
            'error' => 'Minimum budget is ₦2,500. You entered: ₦' . number_format($budgetNGN, 2)
        ], 400);
    }
    
    Log::info('Budget validated', [
        'budget_ngn' => $budgetNGN,
        'currency' => $currency
    ]);
    
    try {
        // STEP 5: Calculate required amount in selected currency
        $requiredAmount = $budgetNGN;
        
        if ($currency !== 'NGN') {
            try {
                $requiredAmount = $this->convertFromNGN($budgetNGN, $currency);
                
                Log::info('Currency conversion successful', [
                    'from' => 'NGN',
                    'to' => $currency,
                    'ngn_amount' => $budgetNGN,
                    'converted_amount' => $requiredAmount
                ]);
            } catch (\Exception $e) {
                Log::error('Currency conversion failed', [
                    'currency' => $currency,
                    'error' => $e->getMessage()
                ]);
                
                return response()->json([
                    'success' => false,
                    'error' => 'Unable to calculate payment amount in ' . $currency . '. Please try NGN or refresh the page.'
                ], 400);
            }
        }
        
        // STEP 6: Get wallet balance
        $walletBalance = \App\Models\WalletTransaction::where('wallet_owner_id', $userId)
            ->where('currency', $currency)
            ->where('status', 'successful')
            ->sum('amount');
        
        Log::info('Wallet balance checked', [
            'currency' => $currency,
            'balance' => $walletBalance,
            'required' => $requiredAmount
        ]);
        
        // STEP 7: Check sufficient balance
        if ($walletBalance < $requiredAmount) {
            $symbols = [
                'NGN' => '₦', 'USD' => '$', 'GBP' => '£', 'EUR' => '€',
                'GHS' => '₵', 'KES' => 'KSh', 'ZAR' => 'R', 'TZS' => 'TSh',
                'UGX' => 'USh', 'XAF' => 'FCFA', 'XOF' => 'CFA'
            ];
            
            $symbol = $symbols[$currency] ?? $currency . ' ';
            
            Log::warning('Insufficient wallet balance', [
                'user_id' => $userId,
                'currency' => $currency,
                'balance' => $walletBalance,
                'required' => $requiredAmount
            ]);
            
            return response()->json([
                'success' => false,
                'error' => "Insufficient {$currency} balance. You have {$symbol}" . 
                          number_format($walletBalance, 2) . 
                          " but need {$symbol}" . 
                          number_format($requiredAmount, 2)
            ], 400);
        }
        
        // STEP 8: Process payment
        DB::beginTransaction();
        
        try {
            // Deduct from wallet
            $transaction = \App\Models\WalletTransaction::create([
                'wallet_owner_id' => $userId,
                'payer_id'        => $userId,
                'transaction_id'  => 'ad_wallet_' . uniqid(),
                'tx_ref'          => 'ad_' . time() . '_' . uniqid(),
                'amount'          => -$requiredAmount, // Negative to deduct
                'currency'        => $currency,
                'status'          => 'successful',
                'description'     => "Advertisement payment from wallet ({$currency})"
            ]);
            
            Log::info('Wallet deduction successful', [
                'transaction_id' => $transaction->transaction_id,
                'amount' => $requiredAmount,
                'currency' => $currency
            ]);
            
            // Create advertisement
            $ad = $this->createAdvertisement($userId, $adData, $currency, $requiredAmount);
            
            Log::info('Advertisement created', [
                'ad_id' => $ad->id,
                'title' => $ad->title
            ]);
            
            DB::commit();
            
            // Get remaining balance
            $remainingBalance = \App\Models\WalletTransaction::where('wallet_owner_id', $userId)
                ->where('currency', $currency)
                ->where('status', 'successful')
                ->sum('amount');
            
            $symbols = [
                'NGN' => '₦', 'USD' => '$', 'GBP' => '£', 'EUR' => '€',
                'GHS' => '₵', 'KES' => 'KSh', 'ZAR' => 'R', 'TZS' => 'TSh',
                'UGX' => 'USh', 'XAF' => 'FCFA', 'XOF' => 'CFA'
            ];
            
            Log::info('Wallet payment completed successfully', [
                'user_id' => $userId,
                'ad_id' => $ad->id,
                'amount_charged' => $requiredAmount,
                'currency' => $currency,
                'remaining_balance' => $remainingBalance
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Ad created successfully using wallet payment!',
                'ad' => [
                    'id' => $ad->id,
                    'title' => $ad->title,
                    'status' => $ad->status
                ],
                'remaining_balance' => ($symbols[$currency] ?? $currency . ' ') . number_format($remainingBalance, 2)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Transaction failed, rolling back', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            throw $e;
        }
        
    } catch (\Exception $e) {
        DB::rollBack();
        
        Log::error('Wallet payment failed', [
            'user_id' => $userId,
            'currency' => $currency,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'error' => 'Payment processing failed: ' . $e->getMessage()
        ], 500);
    }
}
    
    /**
     * Create advertisement (refactored)
     */
    private function createAdvertisement($userId, $adData, $paymentCurrency, $paidAmount)
    {
        // Normalize targeting data
        $targetCountries = isset($adData['target_countries']) ? 
            array_map('strtoupper', array_map('trim', $adData['target_countries'])) : null;
        
        $targetGender = isset($adData['target_gender']) ? 
            array_map('strtolower', array_map('trim', $adData['target_gender'])) : null;
        
        $targetAgeRange = null;
        if (isset($adData['min_age']) || isset($adData['max_age'])) {
            $targetAgeRange = [
                'min' => isset($adData['min_age']) ? (int) $adData['min_age'] : null,
                'max' => isset($adData['max_age']) ? (int) $adData['max_age'] : null,
            ];
        }
        
        // Create ad
        return Advertisement::create([
            'advertiser_id' => $userId,
            'title' => $adData['title'],
            'description' => $adData['description'],
            'ad_type' => $adData['ad_type'],
            'media_url' => $adData['media_url'],
            'media_type' => $adData['media_type'],
            'cta_text' => $adData['cta_text'],
            'cta_link' => $adData['cta_link'],
            'status' => 'pending',
            'payment_status' => 'paid',
            'payment_reference' => 'WALLET-' . time(),
            'flutterwave_tx_ref' => null,
            'paid_at' => now(),
            'target_countries' => $targetCountries,
            'target_age_range' => $targetAgeRange,
            'target_gender' => $targetGender,
            'target_interests' => $adData['target_interests'] ?? null,
            'budget' => (float) $adData['budget'],
            'daily_budget' => isset($adData['daily_budget']) ? (float) $adData['daily_budget'] : null,
            'currency' => 'NGN', // Always store in NGN
            'start_date' => $adData['start_date'],
            'end_date' => $adData['end_date'],
            'cost_per_click' => 50.00,
            'cost_per_impression' => 2.50,
            'cost_per_action' => 400.00,
            'cost_per_mille' => 2500.00,
        ]);
    }
    
    /**
     * Store new ad with PAYMENT INTEGRATION
     */
    public function store(Request $request)
    {
        $userId = Session::get('id');
        if (!$userId) {
            return response()->json(['error' => 'Not logged in'], 401);
        }

        $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'required|string|max:1000',
            'ad_type' => 'required|in:banner,sponsored_post,video',
            'media_url' => 'required|string',
            'media_type' => 'required|in:image,video',
            'cta_text' => 'required|string|max:50',
            'cta_link' => 'required|url',
            'budget' => 'required|numeric|min:2500',
            'daily_budget' => 'nullable|numeric|min:100',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'target_countries' => 'nullable|array',
            'min_age' => 'nullable|integer|min:13|max:100',
            'max_age' => 'nullable|integer|min:13|max:100|gte:min_age',
            'target_gender' => 'nullable|array',
            'target_interests' => 'nullable|array',
            'flutterwave_tx_ref' => 'required|string'
        ]);

        // Verify Flutterwave Payment
        $paymentVerified = $this->verifyFlutterwavePayment($request->flutterwave_tx_ref, $request->budget);
        
        if (!$paymentVerified['success']) {
            return response()->json([
                'success' => false,
                'error' => 'Payment verification failed: ' . $paymentVerified['message']
            ], 400);
        }

        try {
            $ad = $this->createAdvertisement($userId, $request->all(), 'NGN', $request->budget);
            
            $ad->update([
                'payment_reference' => $paymentVerified['reference'],
                'flutterwave_tx_ref' => $request->flutterwave_tx_ref,
            ]);

            Log::info('Advertisement created successfully', [
                'ad_id' => $ad->id,
                'advertiser_id' => $userId,
                'budget' => $request->budget,
                'payment_ref' => $paymentVerified['reference']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ad created successfully! Your payment has been received. Awaiting admin approval.',
                'ad' => $ad
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to create advertisement: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to create ad. Please contact support with reference: ' . $request->flutterwave_tx_ref
            ], 500);
        }
    }
    
    /**
     * Verify Flutterwave payment
     */
    private function verifyFlutterwavePayment($transactionId, $expectedAmount)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->flutterwaveSecretKey,
                'Content-Type' => 'application/json',
            ])->get("https://api.flutterwave.com/v3/transactions/{$transactionId}/verify");

            $data = $response->json();

            if ($data['status'] === 'success' && 
                $data['data']['status'] === 'successful' && 
                $data['data']['amount'] >= $expectedAmount &&
                $data['data']['currency'] === 'NGN') {
                
                return [
                    'success' => true,
                    'reference' => $data['data']['flw_ref'],
                    'amount' => $data['data']['amount'],
                    'customer' => $data['data']['customer']
                ];
            }

            return [
                'success' => false,
                'message' => 'Payment not successful or amount mismatch'
            ];

        } catch (\Exception $e) {
            Log::error('Flutterwave verification failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Payment verification error'
            ];
        }
    }

    // ... [Keep all other existing methods like show, update, destroy, trackImpression, trackClick, trackAction, etc.]

    
    /**
     * Show single ad details
     */
    public function show($adId)
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'You must be logged in.');
        }
        
        $user = UserRecord::find($userId);
        $ad = Advertisement::where('advertiser_id', $userId)->findOrFail($adId);
        
        $performanceData = $this->getPerformanceData($adId, 7);
        
        $topHours = AdClick::where('ad_id', $adId)
            ->selectRaw('HOUR(clicked_at) as hour, COUNT(*) as clicks')
            ->groupBy('hour')
            ->orderBy('clicks', 'desc')
            ->limit(5)
            ->get();
        
        return view('advertising.show', compact('user', 'ad', 'performanceData', 'topHours'));
    }
    
    /**
     * Update ad
     */
    public function update(Request $request, $adId)
    {
        $userId = Session::get('id');
        $ad = Advertisement::where('advertiser_id', $userId)->findOrFail($adId);
        
        $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'required|string|max:1000',
            'cta_text' => 'required|string|max:50',
            'cta_link' => 'required|url',
            'status' => 'required|in:draft,active,paused'
        ]);
        
        $ad->update($request->only([
            'title', 'description', 'cta_text', 'cta_link', 'status'
        ]));
        
        return response()->json([
            'success' => true,
            'message' => 'Ad updated successfully!'
        ]);
    }
    
    /**
     * Delete ad
     */
    public function destroy($adId)
    {
        $userId = Session::get('id');
        $ad = Advertisement::where('advertiser_id', $userId)->findOrFail($adId);
        
        $ad->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Ad deleted successfully!'
        ]);
    }
    
    /**
     * Track ad impression
     */
    public function trackImpression(Request $request, $adId)
    {
        $userId = Session::get('id');
        $ad = Advertisement::findOrFail($adId);
        
        if (!$ad->isActive()) {
            return response()->json(['success' => false]);
        }
        
        // Check daily budget limit
        if ($ad->daily_budget) {
            $todaySpent = AdImpression::where('ad_id', $adId)
                ->whereDate('viewed_at', today())
                ->count() * $ad->cost_per_impression;
            
            if ($todaySpent >= $ad->daily_budget) {
                return response()->json(['success' => false, 'message' => 'Daily budget reached']);
            }
        }
        
        AdImpression::create([
            'ad_id' => $adId,
            'user_id' => $userId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'country' => $request->input('country'),
            'device_type' => $this->detectDeviceType($request->userAgent()),
            'viewed_at' => now()
        ]);
        
        $ad->increment('impressions');
        $cost = (float) $ad->cost_per_impression;
        $ad->increment('spent', $cost);
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Track ad click
     */
    public function trackClick(Request $request, $adId)
    {
        $userId = Session::get('id');
        $ad = Advertisement::findOrFail($adId);
        
        AdClick::create([
            'ad_id' => $adId,
            'user_id' => $userId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'country' => $request->input('country'),
            'clicked_at' => now()
        ]);
        
        $ad->increment('clicks');
        $cost = (float) $ad->cost_per_click;
        $ad->increment('spent', $cost);
        
        if ($ad->spent >= $ad->budget) {
            $ad->update(['status' => 'completed']);
        }
        
        return response()->json([
            'success' => true,
            'redirect_url' => $ad->cta_link
        ]);
    }
    
    /**
     * Get performance data
     */
    private function getPerformanceData($adId, $days)
    {
        $dates = [];
        $impressions = [];
        $clicks = [];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dates[] = Carbon::parse($date)->format('M d');
            
            $impressions[] = AdImpression::where('ad_id', $adId)
                ->whereDate('viewed_at', $date)
                ->count();
            
            $clicks[] = AdClick::where('ad_id', $adId)
                ->whereDate('clicked_at', $date)
                ->count();
        }
        
        return [
            'dates' => $dates,
            'impressions' => $impressions,
            'clicks' => $clicks
        ];
    }
    
    /**
     * Detect device type
     */
    private function detectDeviceType($userAgent)
    {
        if (preg_match('/mobile|android|iphone/i', $userAgent)) {
            return 'mobile';
        } elseif (preg_match('/tablet|ipad/i', $userAgent)) {
            return 'tablet';
        }
        return 'desktop';
    }


    /**
 * Track ad action (conversion)
 */
// public function trackAction(Request $request, $adId)
// {
//     $userId = Session::get('id');
//     $ad = Advertisement::findOrFail($adId);
    
//     // Validate action type
//     $request->validate([
//         'action_type' => 'required|string|in:signup,purchase,download,form_submit,contact,lead,trial,other',
//         'value' => 'nullable|numeric',
//         'meta_data' => 'nullable|array',
//     ]);
    
//     // Create action record
//     AdAction::create([
//         'ad_id' => $adId,
//         'user_id' => $userId,
//         'action_type' => $request->action_type,
//         'ip_address' => $request->ip(),
//         'user_agent' => $request->userAgent(),
//         'country' => $request->input('country'),
//         'device_type' => $this->detectDeviceType($request->userAgent()),
//         'value' => $request->input('value', 0),
//         'meta_data' => $request->input('meta_data'),
//         'action_at' => now(),
//     ]);
    
//     // Increment action count and charge CPA
//     $ad->increment('actions');
//     $cost = (float) $ad->cost_per_action;
//     $ad->increment('spent', $cost);
    
//     // Check if budget exceeded
//     if ($ad->spent >= $ad->budget) {
//         $ad->update(['status' => 'completed']);
//     }
    
//     return response()->json([
//         'success' => true,
//         'message' => 'Action tracked successfully',
//         'total_actions' => $ad->actions,
//     ]);
// }


/**
 * Track conversion action (CPA)
 * Route: POST /advertising/{ad}/action
 * This method is called by external websites using the tracking script
 */
public function trackAction(Request $request, Advertisement $ad)
{
    try {
        // Validate the request
        $validated = $request->validate([
            'action_type' => 'required|string|in:signup,purchase,download,form_submit,contact,lead,trial,other',
            'value' => 'nullable|numeric|min:0',
            'meta_data' => 'nullable|array',
        ]);

        // Check if ad is active
        if (!$ad->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'Ad is not active'
            ], 403);
        }

        // Check daily budget if set
        if ($ad->daily_budget) {
            $todaySpent = DB::table('ad_actions')
                ->where('ad_id', $ad->id)
                ->whereDate('action_at', today())
                ->count() * $ad->cost_per_action;

            if ($todaySpent >= $ad->daily_budget) {
                return response()->json([
                    'success' => false,
                    'message' => 'Daily budget limit reached'
                ], 429);
            }
        }

        // Get user info
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();
        
        // Detect device type
        $deviceType = $this->detectDeviceType($userAgent);
        
        // Get country from IP
        $country = $this->getCountryFromIP($ipAddress);

        // Create action record
        $action = AdAction::create([
            'ad_id' => $ad->id,
            'user_id' => Session::get('id', null), // null if not logged in
            'action_type' => $validated['action_type'],
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'country' => $country,
            'device_type' => $deviceType,
            'value' => $validated['value'] ?? 0,
            'meta_data' => json_encode($validated['meta_data'] ?? []),
            'action_at' => now(),
        ]);

        // Update ad statistics
        $ad->increment('actions');
        
        // Calculate cost and update spent
        $actionCost = $ad->cost_per_action;
        $ad->increment('spent', $actionCost);

        // Check if budget exceeded
        if ($ad->spent >= $ad->budget) {
            $ad->update(['status' => 'completed']);
        }

        // Log for debugging
        Log::info('Ad Action Tracked', [
            'ad_id' => $ad->id,
            'action_type' => $validated['action_type'],
            'cost' => $actionCost,
            'ip' => $ipAddress
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Action tracked successfully',
            'data' => [
                'action_id' => $action->id,
                'action_type' => $action->action_type,
                'tracked_at' => $action->action_at->toISOString(),
            ]
        ], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
        
    } catch (\Exception $e) {
        Log::error('Ad Action Tracking Failed', [
            'ad_id' => $ad->id,
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to track action'
        ], 500);
    }
}

/**
 * Get country from IP address
 * You can integrate with services like ipapi.co, ip-api.com, or MaxMind
 */
private function getCountryFromIP($ip)
{
    try {
        // Skip for localhost
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost'])) {
            return 'LOCAL';
        }

        // Simple example using ip-api.com (free, but rate limited)
        // For production, consider using MaxMind GeoIP2 or similar
        $response = @file_get_contents("http://ip-api.com/json/{$ip}?fields=countryCode");
        if ($response) {
            $data = json_decode($response, true);
            return $data['countryCode'] ?? 'UNKNOWN';
        }
        
        return 'UNKNOWN';
    } catch (\Exception $e) {
        return 'UNKNOWN';
    }
}



}