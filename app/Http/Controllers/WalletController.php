<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\UserRecord;
use App\Models\WalletTransaction; // We'll create this model
use Carbon\Carbon;

class WalletController extends Controller
{
    // Show wallet page
   public function show(Request $request)
{
    $user = UserRecord::findOrFail(Session::get('id'));

    // Header counts
    $totalNotifications = \App\Helpers\UserHelper::countNotifications($user->specialcode);
    // $unseenMessageCount = \App\Helpers\UserHelper::countUnseenMessages($user->id, 'message');
    // $unseenFileCount    = \App\Helpers\UserHelper::countUnseenMessages($user->id, 'file');

    // ✅ Calculate total balance
    $balances = WalletTransaction::where('wallet_owner_id', $user->id)
    ->where('status', 'successful')
    ->select('currency', DB::raw('SUM(amount) as total'))
    ->groupBy('currency')
    ->pluck('total', 'currency')
    ->toArray();


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

    // ✅ Filter logic
    $filter = $request->query('filter', 'all');

    $transactionsQuery = WalletTransaction::with('payer')
        ->where('status', 'successful')
        ->orderBy('created_at', 'desc');

    if ($filter === 'received') {
        $transactionsQuery->where('wallet_owner_id', $user->id);
    } elseif ($filter === 'sent') {
        $transactionsQuery->where('payer_id', $user->id);
    } else {
        $transactionsQuery->where(function ($q) use ($user) {
            $q->where('wallet_owner_id', $user->id)
              ->orWhere('payer_id', $user->id);
        });
    }

    $transactions = $transactionsQuery->paginate(10);

   return view('wallet.mywallet', compact(
    'user',
    'transactions',
    'balances',
    'currencies',
    'filter',
    'totalNotifications',
    // 'unseenMessageCount',
    // 'unseenFileCount'
));

}


    // Start payment process
   public function processPayment(Request $request)
{
    $request->validate([
        'amount'   => 'required|numeric|min:1',
        'currency' => 'required|string'
    ]);

    $walletOwner = UserRecord::findOrFail(Session::get('id'));

    $payload = [
        'tx_ref'       => uniqid('wallet_'),
        'amount'       => $request->amount,
        'currency'     => strtoupper($request->currency), // ✅ use selected currency
        'redirect_url' => route('wallet.success'),
        'customer'     => [
            'email' => $walletOwner->email,
            'name'  => $walletOwner->name,
        ],
        'customizations' => [
            'title'       => 'Wallet Funding',
            'description' => 'Funding wallet for ' . $walletOwner->name,
        ],
    ];

    $response = Http::withToken(env('FLW_SECRET_KEY'))
        ->post('https://api.flutterwave.com/v3/payments', $payload)
        ->json();

    if (!isset($response['data']['link'])) {
        return back()->withErrors(['error' => 'Unable to start payment.']);
    }

    return redirect($response['data']['link']);
}

    // Handle payment success
    public function paymentSuccess(Request $request)
{
    $status        = $request->query('status');
    $txRef         = $request->query('tx_ref');
    $transactionId = $request->query('transaction_id');

    if (!$transactionId) {
        return redirect()->route('mywallet')->with('error', 'No transaction ID found.');
    }

    $verifyResponse = Http::withToken(env('FLW_SECRET_KEY'))
        ->get("https://api.flutterwave.com/v3/transactions/{$transactionId}/verify")
        ->json();

    if (
        isset($verifyResponse['status']) &&
        $verifyResponse['status'] === 'success' &&
        isset($verifyResponse['data']['status']) &&
        $verifyResponse['data']['status'] === 'successful' &&
        $verifyResponse['data']['currency'] === 'NGN'
    ) {
        $walletOwnerId = $verifyResponse['data']['meta']['wallet_owner_id'] ?? Session::get('id');
  $payerId = $verifyResponse['data']['meta']['payer_id'] ?? Session::get('id');

WalletTransaction::create([
    'wallet_owner_id' => $walletOwnerId,
    'payer_id'        => $payerId,
    'transaction_id'  => $transactionId,
    'tx_ref'          => $txRef,
    'amount'          => $verifyResponse['data']['amount'],
    'currency'        => $verifyResponse['data']['currency'], // 👈 save currency from Flutterwave response
    'status'          => 'successful',
    'description'     => "Wallet funding in {$verifyResponse['data']['currency']}"
]);



        // ✅ Store details in session
        return redirect()->route('mywallet')->with([
            'payment_success' => true,
            'txRef'           => $txRef,
            'transactionId'   => $transactionId,
            'amount'          => $verifyResponse['data']['amount'],
        ]);
    }

    return redirect()->route('mywallet')->with('error', 'Payment verification failed.');
}

public function loadTransactions(Request $request)
{
    $user = UserRecord::findOrFail(Session::get('id'));

    $transactions = WalletTransaction::where('wallet_owner_id', $user->id)
        ->where('status', 'successful')
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    if ($request->ajax()) {
        return view('wallet.partials.transactions_list', compact('transactions'))->render();
    }

    return redirect()->route('mywallet');
}

public function fundWallet($id)
{
    $walletOwner = UserRecord::findOrFail($id);

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
    
    $user = UserRecord::find(Session::get('id')); // ✅ logged-in user

    return view('wallet.fund_wallet', compact('walletOwner', 'currencies', 'user'));
    // return view('wallet.fund_wallet', compact('walletOwner', 'currencies'));
}

public function processFunding(Request $request)
{
    $request->validate([
        'wallet_owner_id' => 'required|exists:users_record,id',
        'amount'          => 'required|numeric|min:1',
        'currency'        => 'required|string'
    ]);

    $walletOwner = UserRecord::findOrFail($request->wallet_owner_id);
    $payer       = UserRecord::findOrFail(Session::get('id')); // logged-in user

    $payload = [
        'tx_ref'       => uniqid('wallet_'),
        'amount'       => $request->amount,
        'currency'     => strtoupper($request->currency),
        'redirect_url' => route('wallet.success'),
        'customer'     => [
            'email' => $payer->email,
            'name'  => $payer->name,
        ],
        'customizations' => [
            'title'       => 'Funding ' . $walletOwner->name . '\'s Wallet',
            'description' => 'Wallet funding via SupperAge',
        ],
        // Optional: pass wallet_owner_id in meta so you know who to credit
        'meta' => [
            'wallet_owner_id' => $walletOwner->id,
            'payer_id'        => $payer->id
        ]
    ];

    $response = Http::withToken(env('FLW_SECRET_KEY'))
        ->post('https://api.flutterwave.com/v3/payments', $payload)
        ->json();

    if (!isset($response['data']['link'])) {
        return back()->withErrors(['error' => 'Unable to start payment.']);
    }

    return redirect($response['data']['link']);
}

public function filterTransactions(Request $request)
{
    $user = UserRecord::findOrFail(Session::get('id'));
    $filter = $request->query('filter', 'all');
    $currencyFilter = $request->query('currency', 'all');
    $search = $request->query('search', '');

    $transactionsQuery = WalletTransaction::with('payer')
        ->where('status', 'successful')
        ->orderBy('created_at', 'desc');

    if ($filter === 'received') {
        $transactionsQuery->where('wallet_owner_id', $user->id);
    } elseif ($filter === 'sent') {
        $transactionsQuery->where('payer_id', $user->id);
    } else {
        $transactionsQuery->where(function ($q) use ($user) {
            $q->where('wallet_owner_id', $user->id)
              ->orWhere('payer_id', $user->id);
        });
    }

    if ($currencyFilter !== 'all') {
        $transactionsQuery->where('currency', $currencyFilter);
    }

    if ($search) {
        $transactionsQuery->where(function ($q) use ($search) {
            $q->where('transaction_id', 'like', "%{$search}%")
              ->orWhere('tx_ref', 'like', "%{$search}%")
              ->orWhere('created_at', 'like', "%{$search}%")
              ->orWhereHas('payer', function ($q2) use ($search) {
                  $q2->where('name', 'like', "%{$search}%")
                     ->orWhere('username', 'like', "%{$search}%");
              })
              ->orWhereHas('walletOwner', function ($q2) use ($search) {
                  $q2->where('name', 'like', "%{$search}%")
                     ->orWhere('username', 'like', "%{$search}%");
              });
        });
    }

    $transactions = $transactionsQuery->paginate(10);

    // ✅ calculate balances
    $balances = WalletTransaction::where('wallet_owner_id', $user->id)
        ->where('status', 'successful')
        ->select('currency', DB::raw('SUM(amount) as total'))
        ->groupBy('currency')
        ->pluck('total', 'currency')
        ->toArray();

    // ✅ supported currencies (same as in show())
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

    if ($request->ajax()) {
        return view('wallet.partials.transactions_list', compact('transactions', 'user'))->render();
    }

    return view('wallet.mywallet', compact(
        'transactions',
        'user',
        'filter',
        'currencyFilter',
        'balances',
        'currencies'
    ));
}


public function showTransferPage()
{
    $userId = session('id');
    $user = UserRecord::find($userId);

    if (!$user) {
        return redirect()->route('account')->with('error', 'Please log in first.');
    }

    // ✅ Get balances grouped by currency
    $balances = WalletTransaction::where('wallet_owner_id', $user->id)
        ->where('status', 'successful')
        ->select('currency', DB::raw('SUM(amount) as total'))
        ->groupBy('currency')
        ->pluck('total', 'currency')
        ->toArray();

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

    $allUsers = UserRecord::where('id', '!=', $user->id)->get();

    return view('wallet.transfer', compact('user', 'balances', 'currencies', 'allUsers'));
}


public function processMultiTransfer(Request $request)
{
    $userId = session('id');
    $sender = UserRecord::find($userId);

    if (!$sender) {
        return redirect()->route('account')->with('error', 'Please log in first.');
    }

    // ✅ Get balances grouped by currency
    $balances = WalletTransaction::where('wallet_owner_id', $sender->id)
        ->where('status', 'successful')
        ->select('currency', DB::raw('SUM(amount) as total'))
        ->groupBy('currency')
        ->pluck('total', 'currency')
        ->toArray();

    // ✅ Validate per-currency before starting DB transaction
    foreach ($request->recipients as $id => $data) {
        $amount   = isset($data['amount']) && $data['amount'] !== '' ? (float)$data['amount'] : 0;
        $currency = isset($data['currency']) && $data['currency'] !== '' ? $data['currency'] : 'NGN';

        if (!empty($data['selected']) && $amount > 0) {
            if (!isset($balances[$currency]) || $balances[$currency] < $amount) {
                return back()->withErrors([
                    'error' => "Insufficient {$currency} balance to send {$amount}."
                ]);
            }
        }
    }

    // ✅ If validation passes, process transfers
    DB::beginTransaction();
    try {
        foreach ($request->recipients as $id => $data) {
            $amount   = isset($data['amount']) && $data['amount'] !== '' ? (float)$data['amount'] : 0;
            $currency = isset($data['currency']) && $data['currency'] !== '' ? $data['currency'] : 'NGN';

            if (!empty($data['selected']) && $amount > 0) {
                // Deduct from sender
                // Debit record (sender)
WalletTransaction::create([
    'wallet_owner_id' => $sender->id,   // 👈 the one whose balance decreases
    'payer_id'        => $sender->id,
    'transaction_id'  => uniqid('debit_'),
    'tx_ref'          => uniqid('tx_'),
    'amount'          => -$amount,
    'currency'        => $currency,
    'status'          => 'successful',
]);

                // Credit to receiver
                // Credit record (receiver)
WalletTransaction::create([
    'wallet_owner_id' =>$id, // 👈 the one whose balance increases
    'payer_id'        => $sender->id,   // 👈 still the sender
    'transaction_id'  => uniqid('credit_'),
    'tx_ref'          => uniqid('tx_'),
    'amount'          => $amount,
    'currency'        => $currency,
    'status'          => 'successful',
]);
                // ✅ Update sender’s balance in memory
                $balances[$currency] -= $amount;
            }
        }

        DB::commit();
        return redirect()->route('mywallet')->with('success', 'Transfers completed successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Transfer failed. Please try again.']);
    }
}



}

