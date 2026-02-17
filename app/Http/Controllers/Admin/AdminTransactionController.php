<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use App\Models\UserRecord;
use App\Services\CurrencyExchangeService;

class AdminTransactionController extends Controller
{
    protected $currencyService;

    /**
     * Constructor - inject currency service
     */
    public function __construct(CurrencyExchangeService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function index(Request $request)
    {
        // Get all transactions with user details
        $transactions = WalletTransaction::with(['walletOwner', 'payer'])
            ->where('status', 'successful')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        // Get user summaries (total per user per currency)
        $userSummaries = WalletTransaction::with('walletOwner')
            ->where('status', 'successful')
            ->select(
                'wallet_owner_id',
                'currency',
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('COUNT(*) as transaction_count')
            )
            ->groupBy('wallet_owner_id', 'currency')
            ->get()
            ->groupBy('wallet_owner_id');

        // Get all users with their profile data
        $users = UserRecord::whereIn('id', $userSummaries->keys())->get()->keyBy('id');

        // Supported currencies
        $currencies = [
            'NGN' => 'Nigerian Naira (₦)',
            'USD' => 'US Dollar ($)',
            'GBP' => 'British Pound (£)',
            'EUR' => 'Euro (€)',
            'GHS' => 'Ghanaian Cedi (₵)',
            'KES' => 'Kenyan Shilling (KSh)',
            'TZS' => 'Tanzanian Shilling (TSh)',
            'UGX' => 'Ugandan Shilling (USh)',
            'ZAR' => 'South African Rand (R)',
            'XAF' => 'Central African CFA franc (FCFA)',
            'XOF' => 'West African CFA franc (CFA)'
        ];

        // Get current exchange rates for display
        $exchangeRates = $this->currencyService->getExchangeRates();

        return view('admin.transactions.index', compact(
            'transactions',
            'userSummaries',
            'users',
            'currencies',
            'exchangeRates'
        ));
    }

    public function convertCurrency(Request $request)
    {
        $targetCurrency = $request->input('target_currency', 'NGN');

        // Get user summaries with conversion using live rates
        $userSummaries = WalletTransaction::where('status', 'successful')
            ->select(
                'wallet_owner_id',
                'currency',
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('COUNT(*) as transaction_count')
            )
            ->groupBy('wallet_owner_id', 'currency')
            ->get()
            ->groupBy('wallet_owner_id');

        // Convert all amounts to target currency using the service
        $convertedSummaries = [];
        foreach ($userSummaries as $userId => $currencies) {
            $totalConverted = 0;
            $details = [];
            
            foreach ($currencies as $currencyData) {
                $amount = $currencyData->total_amount;
                $fromCurrency = $currencyData->currency;
                
                // Use the currency service for conversion
                $convertedAmount = $this->currencyService->convert(
                    $amount, 
                    $fromCurrency, 
                    $targetCurrency
                );
                
                $totalConverted += $convertedAmount;
                $details[] = [
                    'original_currency' => $fromCurrency,
                    'original_amount' => $amount,
                    'converted_amount' => $convertedAmount,
                    'exchange_rate' => $this->currencyService->getRate($fromCurrency)
                ];
            }
            
            $convertedSummaries[$userId] = [
                'total' => $totalConverted,
                'currency' => $targetCurrency,
                'details' => $details
            ];
        }

        // Get all users
        $users = UserRecord::whereIn('id', array_keys($convertedSummaries))->get()->keyBy('id');

        return response()->json([
            'success' => true,
            'conversions' => $convertedSummaries,
            'users' => $users,
            'target_currency' => $targetCurrency,
            'exchange_rates' => $this->currencyService->getExchangeRates(),
            'last_updated' => now()->toDateTimeString()
        ]);
    }

    public function filterTransactions(Request $request)
    {
        $query = WalletTransaction::with(['walletOwner', 'payer'])
            ->where('status', 'successful');

        // Filter by user
        if ($request->has('user_id') && $request->user_id != '') {
            $query->where(function($q) use ($request) {
                $q->where('wallet_owner_id', $request->user_id)
                  ->orWhere('payer_id', $request->user_id);
            });
        }

        // Filter by currency
        if ($request->has('currency') && $request->currency != '') {
            $query->where('currency', $request->currency);
        }

        // Filter by transaction type
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('tx_ref', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(50);

        if ($request->ajax()) {
            return view('admin.transactions.partials.transaction_list', compact('transactions'))->render();
        }

        return redirect()->route('admin.transactions.index');
    }

    public function exportTransactions(Request $request)
    {
        $transactions = WalletTransaction::with(['walletOwner', 'payer'])
            ->where('status', 'successful')
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'transactions_export_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, [
                'ID',
                'Transaction ID',
                'TX Ref',
                'Wallet Owner',
                'Wallet Owner Username',
                'Payer',
                'Payer Username',
                'Amount',
                'Currency',
                'Type',
                'Description',
                'Status',
                'Created At'
            ]);

            // Data rows
            foreach ($transactions as $txn) {
                fputcsv($file, [
                    $txn->id,
                    $txn->transaction_id,
                    $txn->tx_ref,
                    $txn->walletOwner ? $txn->walletOwner->name : 'N/A',
                    $txn->walletOwner ? $txn->walletOwner->username : 'N/A',
                    $txn->payer ? $txn->payer->name : 'N/A',
                    $txn->payer ? $txn->payer->username : 'N/A',
                    $txn->amount,
                    $txn->currency,
                    $txn->type,
                    $txn->description,
                    $txn->status,
                    $txn->created_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Refresh exchange rates manually
     */
    public function refreshExchangeRates()
    {
        $this->currencyService->clearCache();
        $rates = $this->currencyService->getExchangeRates();

        return response()->json([
            'success' => true,
            'message' => 'Exchange rates refreshed successfully',
            'rates' => $rates,
            'updated_at' => now()->toDateTimeString()
        ]);
    }

    /**
     * Get current exchange rates
     */
    public function getExchangeRates()
    {
        $rates = $this->currencyService->getExchangeRates();

        return response()->json([
            'success' => true,
            'rates' => $rates,
            'supported_currencies' => $this->currencyService->getSupportedCurrencies(),
            'symbols' => $this->currencyService->getCurrencySymbols()
        ]);
    }
}
