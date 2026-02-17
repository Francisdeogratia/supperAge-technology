<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MarketplaceStore;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckStoreSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'marketplace:check-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update marketplace store subscriptions status and send notifications';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting subscription check...');

        // 1. Check for expired subscriptions
        $this->checkExpiredSubscriptions();

        // 2. Send reminders for subscriptions expiring in 7 days
        $this->sendSevenDayReminders();

        // 3. Send reminders for subscriptions expiring in 3 days
        $this->sendThreeDayReminders();

        // 4. Send reminders for subscriptions expiring in 1 day
        $this->sendOneDayReminders();

        $this->info('Subscription check completed!');

        // NEW CODE (Correct way to reference the inherited constant):
return parent::SUCCESS;
    }

    /**
     * Check and mark expired subscriptions
     */
    protected function checkExpiredSubscriptions()
    {
        $expiredStores = MarketplaceStore::where('subscription_status', 'active')
            ->where('subscription_expires_at', '<=', now())
            ->get();

        foreach ($expiredStores as $store) {
            $store->update([
                'subscription_status' => 'expired',
                'status' => 'suspended'
            ]);

            // Create notification
            Notification::create([
                'user_id' => $store->owner_id,
                'message' => "⚠️ Your store '{$store->store_name}' subscription has expired. Please renew to continue selling.",
                'link' => route('marketplace.renew-subscription'),
                'notification_reciever_id' => $store->owner_id,
                'read_notification' => 'no',
                'type' => 'marketplace_subscription_expired',
                'notifiable_type' => MarketplaceStore::class,
                'notifiable_id' => $store->id
            ]);

            $this->info("Expired: {$store->store_name}");
            Log::info("Store subscription expired: {$store->store_name} (ID: {$store->id})");
        }

        $this->info("Processed {$expiredStores->count()} expired subscriptions");
    }

    /**
     * Send 7-day expiry reminders
     */
    protected function sendSevenDayReminders()
    {
        $sevenDaysFromNow = now()->addDays(7);
        
        $stores = MarketplaceStore::where('subscription_status', 'active')
            ->whereDate('subscription_expires_at', $sevenDaysFromNow->toDateString())
            ->get();

        foreach ($stores as $store) {
            // Check if reminder already sent today
            $reminderExists = Notification::where('notification_reciever_id', $store->owner_id)
                ->where('type', 'marketplace_subscription_reminder_7')
                ->where('notifiable_id', $store->id)
                ->whereDate('created_at', now()->toDateString())
                ->exists();

            if (!$reminderExists) {
                Notification::create([
                    'user_id' => $store->owner_id,
                    'message' => "⏰ Reminder: Your store '{$store->store_name}' subscription expires in 7 days. Renew now to avoid interruption.",
                    'link' => route('marketplace.renew-subscription'),
                    'notification_reciever_id' => $store->owner_id,
                    'read_notification' => 'no',
                    'type' => 'marketplace_subscription_reminder_7',
                    'notifiable_type' => MarketplaceStore::class,
                    'notifiable_id' => $store->id,
                    'data' => json_encode([
                        'expires_at' => $store->subscription_expires_at->format('Y-m-d H:i:s'),
                        'days_remaining' => 7
                    ])
                ]);

                $this->info("7-day reminder sent: {$store->store_name}");
            }
        }

        $this->info("Processed {$stores->count()} 7-day reminders");
    }

    /**
     * Send 3-day expiry reminders
     */
    protected function sendThreeDayReminders()
    {
        $threeDaysFromNow = now()->addDays(3);
        
        $stores = MarketplaceStore::where('subscription_status', 'active')
            ->whereDate('subscription_expires_at', $threeDaysFromNow->toDateString())
            ->get();

        foreach ($stores as $store) {
            $reminderExists = Notification::where('notification_reciever_id', $store->owner_id)
                ->where('type', 'marketplace_subscription_reminder_3')
                ->where('notifiable_id', $store->id)
                ->whereDate('created_at', now()->toDateString())
                ->exists();

            if (!$reminderExists) {
                Notification::create([
                    'user_id' => $store->owner_id,
                    'message' => "⚠️ Urgent: Your store '{$store->store_name}' subscription expires in 3 days! Renew now.",
                    'link' => route('marketplace.renew-subscription'),
                    'notification_reciever_id' => $store->owner_id,
                    'read_notification' => 'no',
                    'type' => 'marketplace_subscription_reminder_3',
                    'notifiable_type' => MarketplaceStore::class,
                    'notifiable_id' => $store->id,
                    'data' => json_encode([
                        'expires_at' => $store->subscription_expires_at->format('Y-m-d H:i:s'),
                        'days_remaining' => 3
                    ])
                ]);

                $this->info("3-day reminder sent: {$store->store_name}");
            }
        }

        $this->info("Processed {$stores->count()} 3-day reminders");
    }

    /**
     * Send 1-day expiry reminders
     */
    protected function sendOneDayReminders()
    {
        $tomorrow = now()->addDay();
        
        $stores = MarketplaceStore::where('subscription_status', 'active')
            ->whereDate('subscription_expires_at', $tomorrow->toDateString())
            ->get();

        foreach ($stores as $store) {
            $reminderExists = Notification::where('notification_reciever_id', $store->owner_id)
                ->where('type', 'marketplace_subscription_reminder_1')
                ->where('notifiable_id', $store->id)
                ->whereDate('created_at', now()->toDateString())
                ->exists();

            if (!$reminderExists) {
                Notification::create([
                    'user_id' => $store->owner_id,
                    'message' => "🚨 Critical: Your store '{$store->store_name}' subscription expires tomorrow! Renew immediately.",
                    'link' => route('marketplace.renew-subscription'),
                    'notification_reciever_id' => $store->owner_id,
                    'read_notification' => 'no',
                    'type' => 'marketplace_subscription_reminder_1',
                    'notifiable_type' => MarketplaceStore::class,
                    'notifiable_id' => $store->id,
                    'data' => json_encode([
                        'expires_at' => $store->subscription_expires_at->format('Y-m-d H:i:s'),
                        'days_remaining' => 1
                    ])
                ]);

                $this->info("1-day reminder sent: {$store->store_name}");
            }
        }

        $this->info("Processed {$stores->count()} 1-day reminders");
    }
}