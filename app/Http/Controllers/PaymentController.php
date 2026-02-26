<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\UserRecord;
use Carbon\Carbon;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{

    public function startPayment(Request $request)
{
    // Get user from session
    $username = Session::get('username');
    $user = UserRecord::where('username', $username)->first();

    if (!$user) {
        return redirect()->back()->withErrors(['error' => 'Please log in first.']);
    }

    // ✅ Base fee in NGN
    $baseFeeNgn = 5000;

    // ✅ Detect user country (from profile or IP)
    $userCountry = $user->country ?? 'NG'; // fallback Nigeria
    $defaultCurrency = match($userCountry) {
        'NG' => 'NGN',
        'US' => 'USD',
        'GB' => 'GBP',
        'FR','DE','IT','ES' => 'EUR',
        default => 'NGN'
    };

    // ✅ Allow override from dropdown
    $selectedCurrency = $request->input('currency', $defaultCurrency);

    // ✅ Fetch live rates from Flutterwave
    $rateResponse = Http::withToken(env('FLW_SECRET_KEY'))
        ->get('https://api.flutterwave.com/v3/rates', [
            'from' => 'NGN',
            'to'   => $selectedCurrency,
            'amount' => $baseFeeNgn
        ])
        ->json();

    if (!isset($rateResponse['status']) || $rateResponse['status'] !== 'success') {
        return redirect()->back()->withErrors(['error' => 'Unable to fetch exchange rates.']);
    }
    // ✅ Convert NGN fee into selected currency
    $convertedAmount = $rateResponse['data']['rate'] 
        ? round($baseFeeNgn * $rateResponse['data']['rate'], 2)
        : $baseFeeNgn;

    // ✅ Prepare payload for Flutterwave
    $payload = [
        'tx_ref'       => uniqid('tx_'),
        'amount'       => $convertedAmount,
        'currency'     => $selectedCurrency,
        'redirect_url' => route('payment.success', ['currency' => $selectedCurrency]),
        'customer'     => [
            'email' => $user->email,
            'name'  => $user->name,
        ],
        'customizations' => [
            'title'       => 'SupperAge Badge Application',
            'description' => "{$selectedCurrency} {$convertedAmount} application fee",
        ],
    ];
      // Call Flutterwave API
    $response = Http::withToken(env('FLW_SECRET_KEY'))
        ->post('https://api.flutterwave.com/v3/payments', $payload)
        ->json();

    if (!isset($response['data']['link'])) {
        return redirect()->back()->withErrors(['error' => 'Unable to start payment.']);
    }
    // Redirect to Flutterwave checkout
    return redirect($response['data']['link']);
}

    

    public function paymentSuccess(Request $request)
    {
        $status        = $request->query('status');
        $txRef         = $request->query('tx_ref');
        $transactionId = $request->query('transaction_id');
        $currency      = $request->query('currency', 'NGN');

        if (!$transactionId) {
            return redirect()->route('blue-badge')->with('error', 'No transaction ID found.');
        }

        // Verify payment with Flutterwave
        $verifyResponse = Http::withToken(env('FLW_SECRET_KEY'))
            ->get("https://api.flutterwave.com/v3/transactions/{$transactionId}/verify")
            ->json();

        if (
            isset($verifyResponse['status']) &&
            $verifyResponse['status'] === 'success' &&
            isset($verifyResponse['data']['status']) &&
            $verifyResponse['data']['status'] === 'successful' &&
            $verifyResponse['data']['currency'] === $currency
        ) {
            $username = Session::get('username');
            $user = $username
                ? UserRecord::where('username', $username)->first()
                : null;

            if ($user) {
                // ✅ Set badge + expiry date
                $user->badge_status = 'images/pending2.png';
                $user->badge_expires_at = Carbon::now()->addDays(30);
                $user->save();

                $fullName = $user->name;
                $profileImg = $user->profileimg ?: asset('images/default-avatar.png');

                return view('pay.success', compact(
                    'status',
                    'txRef',
                    'transactionId',
                    'fullName',
                    'profileImg',
                     'user'  // ✅ ADD THIS
                ));
            }

            return redirect()->route('blue-badge')->with('error', 'User not found.');
        }

        return redirect()->route('blue-badge')->with('error', 'Payment verification failed.');
    }

    // ✅ Webhook for extra safety
    public function handleWebhook(Request $request)
    {
        $payload = $request->all();

        // Verify secret hash
        $secretHash = env('FLW_SECRET_HASH');
        if ($request->header('verif-hash') !== $secretHash) {
            return response()->json(['error' => 'Invalid hash'], 401);
        }

        if (
            isset($payload['data']['status']) &&
            $payload['data']['status'] === 'successful'
        ) {
            $email = $payload['data']['customer']['email'] ?? null;

            if ($email) {
                $user = UserRecord::where('email', $email)->first();
                if ($user) {
                    $user->badge_status = 'images/pending2.png';
                    $user->badge_expires_at = Carbon::now()->addDays(30);
                    $user->save();
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }

    public function verifyFromWallet(Request $request)
{
    $username = Session::get('username');
    $user = UserRecord::where('username', $username)->first();

    if (!$user) {
        return redirect()->back()->withErrors(['error' => 'Please log in first.']);
    }

    // ✅ Base fee in NGN
    $baseFeeNgn = 5000;

    // ✅ Currency chosen by user (default NGN)
    $selectedCurrency = $request->input('currency', 'NGN');

    // ✅ If not NGN, fetch live conversion from Flutterwave
    $deductionAmount = $baseFeeNgn;
    if ($selectedCurrency !== 'NGN') {
        $rateResponse = Http::withToken(env('FLW_SECRET_KEY'))
            ->get('https://api.flutterwave.com/v3/rates', [
                'from' => 'NGN',
                'to'   => $selectedCurrency,
                'amount' => $baseFeeNgn
            ])
            ->json();

        if (isset($rateResponse['status']) && $rateResponse['status'] === 'success') {
            $deductionAmount = round($rateResponse['data']['rate'] * $baseFeeNgn, 2);
        }
    }

    // ✅ Check wallet balance in that currency
    $walletBalance = WalletTransaction::where('wallet_owner_id', $user->id)
        ->where('currency', $selectedCurrency)
        ->where('status', 'successful')
        ->sum('amount');

    if ($walletBalance < $deductionAmount) {
        return redirect()->back()->withErrors(['error' => "Insufficient {$selectedCurrency} wallet balance."]);
    }

    DB::beginTransaction();
    try {
        // ✅ Deduct from wallet
        WalletTransaction::create([
            'wallet_owner_id' => $user->id,
            'payer_id'        => $user->id,
            'transaction_id'  => uniqid('wallet_badge_'),
            'tx_ref'          => uniqid('badge_'),
            'amount'          => -$deductionAmount,
            'currency'        => $selectedCurrency,
            'status'          => 'successful',
            'description'     => "Blue Badge Verification Fee ({$selectedCurrency} Wallet)",
        ]);

        DB::commit();

        return view('pay.success', [
            'user'          => $user,  // ✅ ADD THIS LINE
            'status'        => 'successful',
            'txRef'         => 'WALLET-' . strtoupper(uniqid()),
            'transactionId' => strtoupper(uniqid('TXN_')),
            'fullName'      => $user->name,
            'profileImg'    => $user->profileimg ?: asset('images/default-avatar.png')
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withErrors(['error' => 'Something went wrong. Please try again.']);
    }
}


public function getRate(Request $request)
{
    $currency = $request->query('currency', 'NGN');
    $baseFeeNgn = 5000;

    if ($currency === 'NGN') {
        return response()->json([
            'success' => true,
            'convertedAmount' => $baseFeeNgn
        ]);
    }

    $rateResponse = Http::withToken(env('FLW_SECRET_KEY'))
        ->get('https://api.flutterwave.com/v3/rates', [
            'from' => 'NGN',
            'to'   => $currency,
            'amount' => $baseFeeNgn
        ])
        ->json();

    if (isset($rateResponse['status']) && $rateResponse['status'] === 'success') {
        return response()->json([
            'success' => true,
            'convertedAmount' => round($rateResponse['data']['rate'] * $baseFeeNgn, 2)
        ]);
    }

    return response()->json(['success' => false]);
}



}
