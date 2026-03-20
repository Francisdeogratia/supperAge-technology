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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ApiWalletController extends Controller
{
    public function balance(Request $request)
    {
        $userId = $request->user()->id;

        $transactions = WalletTransaction::with('payer')
            ->where('wallet_owner_id', $userId)
            ->where('status', 'successful')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // For debit transfers, batch-load recipient names by tx_ref
        $debitRefs = $transactions->getCollection()
            ->filter(fn($t) => in_array($t->type, ['transfer', 'general', 'debit']) && $t->amount < 0)
            ->pluck('tx_ref')
            ->filter()
            ->values();

        $recipientsByRef = [];
        if ($debitRefs->isNotEmpty()) {
            WalletTransaction::with('walletOwner')
                ->whereIn('tx_ref', $debitRefs)
                ->where('amount', '>', 0)
                ->get()
                ->each(function ($t) use (&$recipientsByRef) {
                    if ($t->walletOwner) {
                        $recipientsByRef[$t->tx_ref] = $t->walletOwner->name;
                    }
                });
        }

        // Per-currency balances (grouped), matching the website display
        $balances = WalletTransaction::where('wallet_owner_id', $userId)
            ->where('status', 'successful')
            ->select('currency', DB::raw('SUM(amount) as total'))
            ->groupBy('currency')
            ->get()
            ->map(fn($row) => [
                'currency' => $row->currency,
                'total'    => number_format((float) $row->total, 2),
                'raw'      => (float) $row->total,
            ])
            ->values();

        return response()->json([
            'balances'     => $balances,
            'transactions' => $transactions->map(fn($t) => $this->formatTransaction($t, $recipientsByRef)),
            'total'        => $transactions->total(),
            'current_page' => $transactions->currentPage(),
            'last_page'    => $transactions->lastPage(),
        ]);
    }

    public function initiate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount'   => 'required|numeric|min:100',
            'currency' => 'nullable|string|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user     = $request->user();
        $txRef    = 'FUND_' . $user->id . '_' . time();
        $currency = $request->currency ?? 'NGN';

        $response = Http::withToken(env('FLW_SECRET_KEY'))
            ->post('https://api.flutterwave.com/v3/payments', [
                'tx_ref'       => $txRef,
                'amount'       => $request->amount,
                'currency'     => $currency,
                'redirect_url' => 'https://www.supperage.com/wallet/success',
                'customer'     => [
                    'email' => $user->email,
                    'name'  => $user->name,
                ],
                'customizations' => [
                    'title'       => 'SupperAge Wallet',
                    'description' => 'Fund your SupperAge wallet',
                ],
            ]);

        $data = $response->json();

        if (!$response->successful() || ($data['status'] ?? '') !== 'success') {
            return response()->json(['message' => 'Failed to initiate payment. Please try again.'], 502);
        }

        return response()->json([
            'payment_link' => $data['data']['link'],
            'tx_ref'       => $txRef,
        ]);
    }

    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|string',
            'tx_ref'         => 'required|string',
            'status'         => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $exists = WalletTransaction::where('transaction_id', $request->transaction_id)->exists();
        if ($exists) {
            return response()->json(['message' => 'Transaction already processed'], 409);
        }

        $response = Http::withToken(env('FLW_SECRET_KEY'))
            ->get("https://api.flutterwave.com/v3/transactions/{$request->transaction_id}/verify");

        $data = $response->json();

        if (!$response->successful() || ($data['status'] ?? '') !== 'success') {
            return response()->json(['message' => 'Payment verification failed'], 422);
        }

        $txData = $data['data'];
        $user   = $request->user();

        // Use Flutterwave's own status from the verify response (not the redirect URL param)
        if (($txData['status'] ?? '') !== 'successful') {
            return response()->json(['message' => 'Payment was not completed on Flutterwave'], 422);
        }

        if ($txData['tx_ref'] !== $request->tx_ref) {
            return response()->json(['message' => 'Transaction reference mismatch'], 422);
        }

        $transaction = WalletTransaction::create([
            'wallet_owner_id' => $user->id,
            'payer_id'        => $user->id,
            'transaction_id'  => (string) $txData['id'],
            'tx_ref'          => $txData['tx_ref'],
            'amount'          => $txData['amount'],
            'currency'        => $txData['currency'],
            'status'          => 'successful',
            'type'            => 'fund',
            'description'     => 'Wallet funding via Flutterwave',
        ]);

        return response()->json([
            'transaction' => $this->formatTransaction($transaction),
            'message'     => 'Wallet funded successfully',
        ], 201);
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

        // Verify with Flutterwave before crediting
        $flwResponse = Http::withToken(env('FLW_SECRET_KEY'))
            ->get("https://api.flutterwave.com/v3/transactions/{$request->transaction_id}/verify");
        $flwData = $flwResponse->json();

        if (!$flwResponse->successful() || ($flwData['status'] ?? '') !== 'success') {
            return response()->json(['message' => 'Payment verification failed'], 422);
        }

        $txData = $flwData['data'];

        if (($txData['status'] ?? '') !== 'successful') {
            return response()->json(['message' => 'Payment was not completed on Flutterwave'], 422);
        }

        if ($txData['tx_ref'] !== $request->tx_ref) {
            return response()->json(['message' => 'Transaction reference mismatch'], 422);
        }

        $transaction = WalletTransaction::create([
            'wallet_owner_id' => $user->id,
            'payer_id'        => $user->id,
            'transaction_id'  => (string) $txData['id'],
            'tx_ref'          => $txData['tx_ref'],
            'amount'          => $txData['amount'],
            'currency'        => $txData['currency'],
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

        // Only money movement inside the transaction — notification is outside
        // so a notification failure can never roll back a completed transfer
        DB::transaction(function () use ($user, $recipient, $request, $ref) {
            WalletTransaction::create([
                'wallet_owner_id' => $user->id,
                'payer_id'        => $user->id,
                'transaction_id'  => $ref . '_debit',
                'tx_ref'          => $ref,
                'amount'          => -abs($request->amount),
                'currency'        => 'NGN',
                'status'          => 'successful',
                'type'            => 'transfer',
                'description'     => 'Transfer to ' . $recipient->name . ($request->description ? ': ' . $request->description : ''),
            ]);

            WalletTransaction::create([
                'wallet_owner_id' => $recipient->id,
                'payer_id'        => $user->id,
                'transaction_id'  => $ref . '_credit',
                'tx_ref'          => $ref,
                'amount'          => abs($request->amount),
                'currency'        => 'NGN',
                'status'          => 'successful',
                'type'            => 'transfer',
                'description'     => 'Transfer from ' . $user->name . ($request->description ? ': ' . $request->description : ''),
            ]);
        });

        // Notify recipient — silently skip if this fails
        try {
            Notification::create([
                'notification_reciever_id' => $recipient->id,
                'user_id'                 => $user->id,
                'actor_id'                => $user->id,
                'type'                    => 'wallet',
                'message'                 => $user->name . ' sent you ₦' . number_format($request->amount, 2),
                'read_notification'       => 'no',
            ]);
        } catch (\Exception $e) {
            // Notification failed — transfer already completed, continue
        }

        return response()->json(['message' => 'Transfer successful']);
    }

    public function withdrawUrl(Request $request)
    {
        return $this->walletWebUrl($request);
    }

    public function walletWebUrl(Request $request)
    {
        $page = $request->get('page', 'withdraw');

        $redirectMap = [
            'fund'     => 'mywallet',
            'transfer' => 'wallet/transfer',
            'withdraw' => 'wallet/withdraw',
            'badge'    => 'blue-badge',
        ];

        $redirect = $redirectMap[$page] ?? 'mywallet';

        $user  = $request->user();
        $token = Str::random(64);

        Cache::put('app_autologin_' . $token, [
            'user_id'  => $user->id,
            'redirect' => $redirect,
        ], now()->addMinutes(15));

        $url = url('/app/autologin?token=' . $token);

        return response()->json(['url' => $url]);
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

    private function formatTransaction(WalletTransaction $t, array $recipientsByRef = []): array
    {
        $description = $t->description ?: null;

        // Build a meaningful description when none is stored
        if (!$description) {
            if (in_array($t->type, ['transfer', 'general', 'debit'])) {
                if ($t->amount > 0 && $t->payer) {
                    $description = 'Transfer from ' . $t->payer->name;
                } elseif ($t->amount < 0 && isset($recipientsByRef[$t->tx_ref])) {
                    $description = 'Transfer to ' . $recipientsByRef[$t->tx_ref];
                } else {
                    $description = $t->amount > 0 ? 'Transfer received' : 'Transfer sent';
                }
            } elseif ($t->type === 'fund') {
                $description = 'Wallet Funding';
            } elseif ($t->type === 'withdraw') {
                $description = 'Withdrawal Request';
            } elseif ($t->type === 'task_reward') {
                $description = 'Task Reward';
            } else {
                $description = ucfirst($t->type ?? 'Transaction');
            }
        }

        return [
            'id'             => $t->id,
            'amount'         => (float) $t->amount,
            'currency'       => $t->currency,
            'type'           => $t->type,
            'status'         => $t->status,
            'description'    => $description,
            'transaction_id' => $t->transaction_id,
            'tx_ref'         => $t->tx_ref,
            'formatted'      => $t->formatted_amount,
            'is_credit'      => $t->is_credit,
            'created_at'     => $t->created_at,
        ];
    }
}
