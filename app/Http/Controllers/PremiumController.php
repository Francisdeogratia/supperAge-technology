<?php

namespace App\Http\Controllers;

use App\Models\UserRecord;
use App\Models\PremiumTask;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PremiumController extends Controller
{
    /**
     * Show the Premium Tasks page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function premiumPage()
    {
        $userId = session('id'); // from Session::put in login()
        $user = UserRecord::find($userId);

        if (!$user) {
            return redirect('/account')->with('error', 'Please log in first.');
        }

        $tasks = PremiumTask::all();
        $completedCount = $user->premiumTasks()->wherePivot('completed', true)->count();
        $totalCount = $tasks->count();
        $progress = $totalCount > 0 ? ($completedCount / $totalCount) * 100 : 0;

        return view('premium', compact('tasks', 'progress', 'completedCount', 'totalCount'));
    }

    /**
     * Show the Blue Badge Verification page.
     *
     * @return \Illuminate\View\View
     */
    public function blueBadgePage()
{
    $user = null;
    $walletBalance = 0;

    // ✅ Get logged-in user from session
    if (Session::has('id')) {
        $user = UserRecord::find(Session::get('id'));

        if ($user) {
            // ✅ Clear expired badge
            if ($user->badge_expires_at && now()->gte($user->badge_expires_at)) {
                $user->badge_status = null;
                $user->badge_expires_at = null;
                $user->save();
            }

            // ✅ Calculate wallet balance
            $walletBalance = WalletTransaction::where('wallet_owner_id', $user->id)
                ->where('status', 'successful')
                ->sum('amount');
        }
    }

    // ✅ Supported currencies
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

    // ✅ Base fee in NGN
    $baseFeeNgn = 4000;

    // ✅ Detect default currency (based on user country if available)
    $userCountry = $user->country ?? 'NG';
    $defaultCurrency = match($userCountry) {
        'NG' => 'NGN',
        'US' => 'USD',
        'GB' => 'GBP',
        'FR','DE','IT','ES' => 'EUR',
        default => 'NGN'
    };

    // ✅ Initial values
    $selectedCurrency = $defaultCurrency;
    $convertedAmount = $baseFeeNgn;

    // ✅ Fetch live conversion from Flutterwave if not NGN
    if ($selectedCurrency !== 'NGN') {
        try {
            $rateResponse = Http::withToken(env('FLW_SECRET_KEY'))
                ->get('https://api.flutterwave.com/v3/rates', [
                    'from' => 'NGN',
                    'to'   => $selectedCurrency,
                    'amount' => $baseFeeNgn
                ])
                ->json();

            if (isset($rateResponse['status']) && $rateResponse['status'] === 'success') {
                $convertedAmount = round($rateResponse['data']['rate'] * $baseFeeNgn, 2);
            }
        } catch (\Exception $e) {
            // fallback to NGN if API fails
            $convertedAmount = $baseFeeNgn;
        }
    }

    return view('blue-badge', compact(
        'user',
        'walletBalance',
        'currencies',
        'defaultCurrency',
        'selectedCurrency',
        'convertedAmount'
    ));
}


// public function verifyFromWallet()
// {
//     $user = UserRecord::find(Session::get('id'));

//     if (!$user) {
//         return redirect()->back()->withErrors(['error' => 'Please log in first.']);
//     }

//     // Calculate wallet balance
//     $walletBalance = WalletTransaction::where('wallet_owner_id', $user->id)
//         ->where('status', 'successful')
//         ->sum('amount');

//     if ($walletBalance < 1500) {
//         return redirect()->back()->withErrors(['error' => 'Insufficient wallet balance.']);
//     }

//     DB::beginTransaction();
//     try {
//         // Deduct NGN 1,500
//         WalletTransaction::create([
//             'wallet_owner_id' => $user->id,
//             'payer_id'        => $user->id,
//             'transaction_id'  => uniqid('wallet_badge_'),
//             'tx_ref'          => uniqid('badge_'),
//             'amount'          => -1500,
//             'status'          => 'successful',
//             'description'     => 'Blue Badge Verification Fee (Wallet)',
//         ]);

//         // Update badge status
//         $user->badge_status = 'images/pending2.png';
//         $user->badge_expires_at = Carbon::now()->addDays(30);
//         $user->save();

//         DB::commit();

//         // ✅ Redirect to the same success view as online payment
//         return view('success', [
//             'status'        => 'successful',
//             'txRef'         => 'WALLET-' . strtoupper(uniqid()),
//             'transactionId' => strtoupper(uniqid('TXN_')),
//             'fullName'      => $user->name,
//             'profileImg'    => $user->profileimg ?: asset('images/default-avatar.png')
//         ]);

//     } catch (\Exception $e) {
//         DB::rollBack();
//         return redirect()->back()->withErrors(['error' => 'Something went wrong. Please try again.']);
//     }
// }

}
