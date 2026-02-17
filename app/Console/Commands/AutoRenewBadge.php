<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserRecord;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use DB;
use App\Notifications\BadgeRenewalNotification; // ✅ make sure you import this

class AutoRenewBadge extends Command
{
    protected $signature = 'badge:renew';
    protected $description = 'Automatically renew badges if wallet balance is sufficient';

    public function handle()
    {
        $baseFeeNgn = 7500;

        $users = UserRecord::whereNotNull('badge_expires_at')
            ->where('badge_expires_at', '<=', Carbon::now())
            ->get();

        foreach ($users as $user) {
            $renewed = false; // ✅ track if renewal succeeded

            // Try to deduct from NGN first, then USD, then EUR, etc.
            $currencies = ['NGN','USD','EUR','GBP','GHS','KES','ZAR'];

            foreach ($currencies as $currency) {
                $walletBalance = WalletTransaction::where('wallet_owner_id', $user->id)
                    ->where('currency', $currency)
                    ->where('status', 'successful')
                    ->sum('amount');

                // Convert ₦1500 into this currency
                $deductionAmount = $baseFeeNgn;
                if ($currency !== 'NGN') {
                    $rateResponse = Http::withToken(env('FLW_SECRET_KEY'))
                        ->get('https://api.flutterwave.com/v3/rates', [
                            'from' => 'NGN',
                            'to'   => $currency,
                            'amount' => $baseFeeNgn
                        ])
                        ->json();

                    if (isset($rateResponse['status']) && $rateResponse['status'] === 'success') {
                        $deductionAmount = round($rateResponse['data']['rate'] * $baseFeeNgn, 2);
                    }
                }

                if ($walletBalance >= $deductionAmount) {
                    DB::transaction(function () use ($user, $currency, $deductionAmount) {
                        WalletTransaction::create([
                            'wallet_owner_id' => $user->id,
                            'payer_id'        => $user->id,
                            'transaction_id'  => uniqid('auto_badge_'),
                            'tx_ref'          => uniqid('badge_auto_'),
                            'amount'          => -$deductionAmount,
                            'currency'        => $currency,
                            'status'          => 'successful',
                            'description'     => "Auto-Renew Blue Badge ({$currency} Wallet)",
                        ]);

                        $user->badge_status = 'images/pending2.png';
                        $user->badge_expires_at = Carbon::now()->addDays(30);
                        $user->save();

                        // ✅ Success notification
                        $user->notify(new BadgeRenewalNotification('success', $currency, $deductionAmount));
                    });

                    $this->info("Badge auto-renewed for {$user->name} using {$currency}");
                    $renewed = true;
                    break; // stop checking other currencies
                }
            }

            // ✅ If no wallet had enough balance, send failure notification
            if (!$renewed) {
                $user->notify(new BadgeRenewalNotification('failed', 'NGN', $baseFeeNgn));
                $this->warn("Auto-renew failed for {$user->name} (insufficient balance).");
            }
        }
    }
}
