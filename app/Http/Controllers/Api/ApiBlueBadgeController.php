<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ApiBlueBadgeController extends Controller
{
    private const FEE_NEW_NGN   = 5000;
    private const FEE_RENEW_NGN = 8000;

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function feeNgn($user): int
    {
        $isExpired = $user->badge_status === 'expired'
            || (
                $user->badge_expires_at
                && now()->gte($user->badge_expires_at)
                && $user->badge_status
                && $user->badge_status !== 'images/pending2.png'
            );

        return $isExpired ? self::FEE_RENEW_NGN : self::FEE_NEW_NGN;
    }

    private function isRenewal($user): bool
    {
        return $this->feeNgn($user) === self::FEE_RENEW_NGN;
    }

    private function convertFromNgn(int $ngn, string $currency): float
    {
        if ($currency === 'NGN') return (float) $ngn;

        $resp = Http::withToken(env('FLW_SECRET_KEY'))
            ->get('https://api.flutterwave.com/v3/rates', [
                'from'   => 'NGN',
                'to'     => $currency,
                'amount' => $ngn,
            ])->json();

        if (($resp['status'] ?? '') === 'success') {
            return round($resp['data']['rate'] * $ngn, 2);
        }

        return (float) $ngn; // fallback: return NGN value unchanged
    }

    // ── Endpoints ─────────────────────────────────────────────────────────────

    public function getInfo(Request $request)
    {
        $user = $request->user();

        $wallets = WalletTransaction::where('wallet_owner_id', $user->id)
            ->where('status', 'successful')
            ->select('currency', DB::raw('SUM(amount) as total'))
            ->groupBy('currency')
            ->get()
            ->map(fn($r) => ['currency' => $r->currency, 'balance' => (float) $r->total])
            ->values();

        return response()->json([
            'badge_status'     => $user->badge_status,
            'badge_expires_at' => $user->badge_expires_at,
            'is_renewal'       => $this->isRenewal($user),
            'fee_ngn'          => $this->feeNgn($user),
            'wallets'          => $wallets,
        ]);
    }

    public function getRate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currency' => 'required|string|max:5',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user   = $request->user();
        $feeNgn = $this->feeNgn($user);

        return response()->json([
            'fee_ngn'          => $feeNgn,
            'currency'         => $request->currency,
            'converted_amount' => $this->convertFromNgn($feeNgn, $request->currency),
        ]);
    }

    public function payFromWallet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currency' => 'required|string|max:5',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user     = $request->user();
        $currency = $request->currency;
        $feeNgn   = $this->feeNgn($user);
        $amount   = $this->convertFromNgn($feeNgn, $currency);
        $label    = $this->isRenewal($user) ? 'Renewal' : 'Verification';

        $balance = WalletTransaction::where('wallet_owner_id', $user->id)
            ->where('currency', $currency)
            ->where('status', 'successful')
            ->sum('amount');

        if ($balance < $amount) {
            return response()->json([
                'message' => "Insufficient {$currency} balance. You need "
                    . number_format($amount, 2) . " {$currency} but only have "
                    . number_format($balance, 2) . " {$currency}.",
            ], 422);
        }

        WalletTransaction::create([
            'wallet_owner_id' => $user->id,
            'payer_id'        => $user->id,
            'transaction_id'  => 'BADGE_' . Str::random(16),
            'tx_ref'          => 'badge_' . Str::random(12),
            'amount'          => -abs($amount),
            'currency'        => $currency,
            'status'          => 'successful',
            'type'            => 'badge',
            'description'     => "Blue Badge {$label} Fee ({$currency} Wallet)",
        ]);

        return response()->json([
            'message'    => 'Payment successful',
            'amount'     => $amount,
            'currency'   => $currency,
            'is_renewal' => $this->isRenewal($user),
        ]);
    }

    public function initiatePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currency' => 'required|string|max:5',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user     = $request->user();
        $currency = $request->currency;
        $feeNgn   = $this->feeNgn($user);
        $amount   = $this->convertFromNgn($feeNgn, $currency);
        $txRef    = 'BADGE_' . $user->id . '_' . time();
        $label    = $this->isRenewal($user) ? 'Renewal' : 'Verification';

        $resp = Http::withToken(env('FLW_SECRET_KEY'))
            ->post('https://api.flutterwave.com/v3/payments', [
                'tx_ref'         => $txRef,
                'amount'         => $amount,
                'currency'       => $currency,
                'redirect_url'   => 'https://www.supperage.com/payment-success?currency=' . $currency,
                'customer'       => ['email' => $user->email, 'name' => $user->name],
                'customizations' => [
                    'title'       => 'SupperAge Blue Badge',
                    'description' => "Blue Badge {$label} Fee",
                ],
            ])->json();

        if (($resp['status'] ?? '') !== 'success') {
            return response()->json(['message' => 'Failed to initiate payment. Please try again.'], 502);
        }

        return response()->json([
            'payment_link' => $resp['data']['link'],
            'tx_ref'       => $txRef,
            'amount'       => $amount,
            'currency'     => $currency,
        ]);
    }

    public function verifyPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|string',
            'tx_ref'         => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $resp = Http::withToken(env('FLW_SECRET_KEY'))
            ->get("https://api.flutterwave.com/v3/transactions/{$request->transaction_id}/verify")
            ->json();

        if (($resp['status'] ?? '') !== 'success') {
            return response()->json(['message' => 'Payment verification failed'], 422);
        }

        $txData = $resp['data'];

        if (($txData['status'] ?? '') !== 'successful') {
            return response()->json(['message' => 'Payment was not completed on Flutterwave'], 422);
        }

        // Only check tx_ref if it was supplied
        if ($request->filled('tx_ref') && $txData['tx_ref'] !== $request->tx_ref) {
            return response()->json(['message' => 'Transaction reference mismatch'], 422);
        }

        $user = $request->user();

        // Prevent duplicate processing
        $exists = WalletTransaction::where('transaction_id', (string) $txData['id'])->exists();
        if ($exists) {
            return response()->json([
                'message'      => 'Payment already processed',
                'already_paid' => true,
                'amount'       => $txData['amount'],
                'currency'     => $txData['currency'],
            ]);
        }

        // Record the payment as a wallet debit
        WalletTransaction::create([
            'wallet_owner_id' => $user->id,
            'payer_id'        => $user->id,
            'transaction_id'  => (string) $txData['id'],
            'tx_ref'          => $txData['tx_ref'],
            'amount'          => -abs($txData['amount']),
            'currency'        => $txData['currency'],
            'status'          => 'successful',
            'type'            => 'badge',
            'description'     => 'Blue Badge Fee (Flutterwave)',
        ]);

        return response()->json([
            'message'  => 'Payment verified and recorded',
            'amount'   => $txData['amount'],
            'currency' => $txData['currency'],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'gov_id'      => 'required|file|max:5120',
            'profile_pic' => 'required|file|max:3072',
        ]);

        $user = $request->user();

        $govIdPath      = $request->file('gov_id')->store('badge_verification', 'public');
        $profilePicPath = $request->file('profile_pic')->store('badge_verification', 'public');

        DB::table('badge_verifications')->insert([
            'user_id'          => $user->id,
            'full_name'        => $user->name,
            'gov_id_path'      => $govIdPath,
            'profile_pic_path' => $profilePicPath,
            'status'           => 'pending',
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        $user->badge_status     = 'images/pending2.png';
        $user->badge_expires_at = Carbon::now()->addDays(30);
        $user->save();

        return response()->json([
            'message' => 'Verification submitted. We will review it within 3-5 business days.',
        ]);
    }
}
