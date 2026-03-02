<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\WalletTransaction;
use App\Models\UserRecord;
use App\Models\Notification;
use Illuminate\Support\Str;

class ApiWalletController extends Controller
{
    public function balance(Request $request)
    {
        $userId = $request->user()->id;

        $transactions = WalletTransaction::where('wallet_owner_id', $userId)
            ->where('status', 'successful')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $balance = WalletTransaction::where('wallet_owner_id', $userId)
            ->where('status', 'successful')
            ->sum('amount');

        return response()->json([
            'balance'      => number_format((float) $balance, 2),
            'currency'     => 'NGN',
            'transactions' => $transactions->map(fn($t) => $this->formatTransaction($t)),
            'total'        => $transactions->total(),
            'current_page' => $transactions->currentPage(),
            'last_page'    => $transactions->lastPage(),
        ]);
    }

    public function fund(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount'         => 'required|numeric|min:100',
            'currency'       => 'nullable|string|max:5',
            'transaction_id' => 'required|string',
            'tx_ref'         => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();

        // Check if transaction already processed
        $exists = WalletTransaction::where('transaction_id', $request->transaction_id)->exists();
        if ($exists) {
            return response()->json(['message' => 'Transaction already processed'], 409);
        }

        $transaction = WalletTransaction::create([
            'wallet_owner_id' => $user->id,
            'payer_id'        => $user->id,
            'transaction_id'  => $request->transaction_id,
            'tx_ref'          => $request->tx_ref,
            'amount'          => $request->amount,
            'currency'        => $request->currency ?? 'NGN',
            'status'          => 'successful',
            'type'            => 'fund',
            'description'     => 'Wallet funding via Flutterwave',
        ]);

        return response()->json([
            'transaction' => $this->formatTransaction($transaction),
            'message'     => 'Wallet funded successfully',
        ], 201);
    }

    public function transfer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recipient_id' => 'required|integer',
            'amount'       => 'required|numeric|min:50',
            'description'  => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user      = $request->user();
        $recipient = UserRecord::find($request->recipient_id);

        if (!$recipient) {
            return response()->json(['message' => 'Recipient not found'], 404);
        }

        if ($recipient->id === $user->id) {
            return response()->json(['message' => 'Cannot transfer to yourself'], 422);
        }

        // Check balance
        $balance = WalletTransaction::where('wallet_owner_id', $user->id)
            ->where('status', 'successful')
            ->sum('amount');

        if ($balance < $request->amount) {
            return response()->json(['message' => 'Insufficient balance'], 422);
        }

        $ref = 'TXF_' . Str::random(16);

        DB::transaction(function () use ($user, $recipient, $request, $ref) {
            // Debit sender
            WalletTransaction::create([
                'wallet_owner_id' => $user->id,
                'payer_id'        => $user->id,
                'transaction_id'  => $ref . '_debit',
                'tx_ref'          => $ref,
                'amount'          => -abs($request->amount),
                'currency'        => 'NGN',
                'status'          => 'successful',
                'type'            => 'transfer',
                'description'     => 'Transfer to ' . $recipient->name . ': ' . ($request->description ?? ''),
            ]);

            // Credit recipient
            WalletTransaction::create([
                'wallet_owner_id' => $recipient->id,
                'payer_id'        => $user->id,
                'transaction_id'  => $ref . '_credit',
                'tx_ref'          => $ref,
                'amount'          => abs($request->amount),
                'currency'        => 'NGN',
                'status'          => 'successful',
                'type'            => 'transfer',
                'description'     => 'Transfer from ' . $user->name . ': ' . ($request->description ?? ''),
            ]);

            Notification::create([
                'user_id'  => $recipient->id,
                'from_id'  => $user->id,
                'type'     => 'wallet',
                'message'  => $user->name . ' sent you ₦' . number_format($request->amount, 2),
                'is_read'  => 0,
            ]);
        });

        return response()->json(['message' => 'Transfer successful']);
    }

    public function withdraw(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount'       => 'required|numeric|min:500',
            'bank_name'    => 'required|string',
            'account_no'   => 'required|string',
            'account_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user    = $request->user();
        $balance = WalletTransaction::where('wallet_owner_id', $user->id)
            ->where('status', 'successful')
            ->sum('amount');

        if ($balance < $request->amount) {
            return response()->json(['message' => 'Insufficient balance'], 422);
        }

        $ref = 'WDR_' . Str::random(16);

        WalletTransaction::create([
            'wallet_owner_id' => $user->id,
            'payer_id'        => $user->id,
            'transaction_id'  => $ref,
            'tx_ref'          => $ref,
            'amount'          => -abs($request->amount),
            'currency'        => 'NGN',
            'status'          => 'pending',
            'type'            => 'withdraw',
            'description'     => 'Withdrawal to ' . $request->bank_name . ' - ' . $request->account_no,
        ]);

        return response()->json(['message' => 'Withdrawal request submitted. Processing within 24 hours.']);
    }

    private function formatTransaction(WalletTransaction $t): array
    {
        return [
            'id'             => $t->id,
            'amount'         => (float) $t->amount,
            'currency'       => $t->currency,
            'type'           => $t->type,
            'status'         => $t->status,
            'description'    => $t->description,
            'transaction_id' => $t->transaction_id,
            'tx_ref'         => $t->tx_ref,
            'formatted'      => $t->formatted_amount,
            'is_credit'      => $t->is_credit,
            'created_at'     => $t->created_at,
        ];
    }
}
