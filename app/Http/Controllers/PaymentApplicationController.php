<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\UserRecord;
use App\Models\WalletTransaction;

class PaymentApplicationController extends Controller
{
    // Show payment application form
    public function show()
    {
        // Check if user is logged in
        if (!Session::has('id')) {
            return redirect('/account')->with('error', 'Please login first');
        }

        $user = UserRecord::findOrFail(Session::get('id'));
        
        // Check if badge exists and is valid
        $badgeExpired = false;
        if ($user->badge_expires_at) {
            $badgeExpired = now()->gt($user->badge_expires_at);
        }
        
        // Get wallet balance
        $balances = WalletTransaction::where('wallet_owner_id', $user->id)
            ->where('status', 'successful')
            ->select('currency', DB::raw('SUM(amount) as total'))
            ->groupBy('currency')
            ->get();
        
        // Check if user already has pending application
        $pendingApp = DB::table('payment_applications')
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        // Get all applications for this user
        $applications = DB::table('payment_applications')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('payment.apply', compact('user', 'pendingApp', 'applications', 'badgeExpired', 'balances'));
    }

    // Submit payment application
    public function apply(Request $request)
{
    // Check if user is logged in
    if (!Session::has('id')) {
        return redirect('/account')->with('error', 'Please login first');
    }

    $userId = Session::get('id');
    $user = UserRecord::findOrFail($userId);
    
    // Check if badge is valid
    if (!$user->badge_status) {
        return back()->withErrors(['error' => 'You need to verify your account before applying for payment']);
    }
    
    if ($user->badge_expires_at && now()->gt($user->badge_expires_at)) {
        return back()->withErrors(['error' => 'Your verification badge has expired. Please renew it.']);
    }

    $request->validate([
        'bank_name' => 'nullable|string|required_if:payment_method,bank_transfer',
        'account_number' => 'nullable|string|min:10|max:10|required_if:payment_method,bank_transfer',
        'account_name' => 'nullable|string|required_if:payment_method,bank_transfer',
        'amount_requested' => 'required|numeric|min:1',
        'currency' => 'required|string',
        'payment_method' => 'required|in:bank_transfer,paypal,flutterwave',
        'paypal_email' => 'nullable|email|required_if:payment_method,paypal',
        'reason' => 'nullable|string|max:500'
    ]);

    // Check if user has pending application
    $pending = DB::table('payment_applications')
        ->where('user_id', $userId)
        ->where('status', 'pending')
        ->exists();

    if ($pending) {
        return back()->withErrors(['error' => 'You already have a pending payment application']);
    }

    // Check wallet balance
    $balance = WalletTransaction::where('wallet_owner_id', $userId)
        ->where('status', 'successful')
        ->where('currency', $request->currency)
        ->sum('amount');

    // Calculate maximum withdrawable (50% of balance)
    $maxWithdrawable = $balance * 0.50;

    if ($request->amount_requested > $maxWithdrawable) {
        return back()->withErrors([
            'error' => "You can only withdraw up to 50% of your balance. Maximum: " .
                      number_format($maxWithdrawable, 2) . " " . $request->currency
        ]);
    }

    if ($balance < $request->amount_requested) {
        return back()->withErrors(['error' => 'Insufficient wallet balance']);
    }

    // Calculate amounts
    $requestedAmount = $request->amount_requested;
    $userReceives = $requestedAmount * 0.50;
    $platformFee = $requestedAmount * 0.50;

    // ✅ Fix: Convert "null" strings to actual NULL
    DB::table('payment_applications')->insert([
        'user_id' => $userId,
        'bank_name' => $request->payment_method === 'bank_transfer' ? $request->bank_name : null,
        'account_number' => $request->payment_method === 'bank_transfer' ? $request->account_number : null,
        'account_name' => $request->payment_method === 'bank_transfer' ? $request->account_name : null,
        'amount_requested' => $requestedAmount,
        'amount_to_receive' => $userReceives,
        'platform_fee' => $platformFee,
        'currency' => $request->currency,
        'payment_method' => $request->payment_method,
        'paypal_email' => $request->payment_method === 'paypal' ? $request->paypal_email : null,
        'reason' => $request->reason,
        'status' => 'pending',
        'created_at' => now(),
        'updated_at' => now()
    ]);

    return redirect()->route('payment.apply')->with('success',
        "Payment application submitted successfully! You will receive " .
        number_format($userReceives, 2) . " " . $request->currency .
        " (50% of requested amount). Platform fee: " .
        number_format($platformFee, 2) . " " . $request->currency);
}


    // Cancel application
    public function cancel($id)
    {
        $application = DB::table('payment_applications')->where('id', $id)->first();

        if (!$application || $application->user_id != Session::get('id')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($application->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Cannot cancel this application'], 400);
        }

        DB::table('payment_applications')->where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'Application cancelled successfully']);
    }
}