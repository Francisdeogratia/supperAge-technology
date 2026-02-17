<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Models\MarketplaceStore;
use Illuminate\Support\Facades\Session;

class NotificationHelper
{
    /**
     * Get total unread marketplace notifications for user
     */
    public static function getMarketplaceNotificationCount($userId)
    {
        return Notification::where('notification_reciever_id', $userId)
            ->whereIn('type', [
                'marketplace_order',
                'marketplace_order_placed',
                'marketplace_order_update',
                'marketplace_new_product',
                'marketplace_subscription_expired',
                'marketplace_subscription_reminder_7',
                'marketplace_subscription_reminder_3',
                'marketplace_subscription_reminder_1',
                'marketplace_store'
            ])
            ->where('read_notification', 'no')
            ->count();
    }

    /**
     * Get unread order notifications for store owner
     */
    public static function getStoreOrderNotifications($userId)
    {
        return Notification::where('notification_reciever_id', $userId)
            ->whereIn('type', ['marketplace_order', 'marketplace_order_update'])
            ->where('read_notification', 'no')
            ->count();
    }

    /**
     * Get unread new product notifications
     */
    public static function getNewProductNotifications($userId)
    {
        return Notification::where('notification_reciever_id', $userId)
            ->where('type', 'marketplace_new_product')
            ->where('read_notification', 'no')
            ->count();
    }

    /**
     * Get subscription reminder notifications
     */
    public static function getSubscriptionNotifications($userId)
    {
        return Notification::where('notification_reciever_id', $userId)
            ->whereIn('type', [
                'marketplace_subscription_expired',
                'marketplace_subscription_reminder_7',
                'marketplace_subscription_reminder_3',
                'marketplace_subscription_reminder_1'
            ])
            ->where('read_notification', 'no')
            ->count();
    }

    /**
     * Get all marketplace notifications with counts by type
     */
    public static function getMarketplaceNotificationsSummary($userId)
    {
        $notifications = Notification::where('notification_reciever_id', $userId)
            ->whereIn('type', [
                'marketplace_order',
                'marketplace_order_placed',
                'marketplace_order_update',
                'marketplace_new_product',
                'marketplace_subscription_expired',
                'marketplace_subscription_reminder_7',
                'marketplace_subscription_reminder_3',
                'marketplace_subscription_reminder_1',
                'marketplace_store'
            ])
            ->where('read_notification', 'no')
            ->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get();

        return [
            'total' => $notifications->sum('count'),
            'orders' => $notifications->whereIn('type', ['marketplace_order', 'marketplace_order_update'])->sum('count'),
            'new_products' => $notifications->where('type', 'marketplace_new_product')->first()->count ?? 0,
            'subscription' => $notifications->whereIn('type', [
                'marketplace_subscription_expired',
                'marketplace_subscription_reminder_7',
                'marketplace_subscription_reminder_3',
                'marketplace_subscription_reminder_1'
            ])->sum('count'),
            'breakdown' => $notifications
        ];
    }

    /**
     * Mark notification as read
     */
    public static function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            $notification->update(['read_notification' => 'yes']);
            return true;
        }
        return false;
    }

    /**
     * Mark all marketplace notifications as read for user
     */
    public static function markAllMarketplaceAsRead($userId)
    {
        return Notification::where('notification_reciever_id', $userId)
            ->whereIn('type', [
                'marketplace_order',
                'marketplace_order_placed',
                'marketplace_order_update',
                'marketplace_new_product',
                'marketplace_subscription_expired',
                'marketplace_subscription_reminder_7',
                'marketplace_subscription_reminder_3',
                'marketplace_subscription_reminder_1',
                'marketplace_store'
            ])
            ->where('read_notification', 'no')
            ->update(['read_notification' => 'yes']);
    }

    /**
     * Get stores with new products (for navbar dropdown)
     */
    public static function getStoresWithNewProducts($userId, $limit = 10)
    {
        $notifications = Notification::where('notification_reciever_id', $userId)
            ->where('type', 'marketplace_new_product')
            ->where('read_notification', 'no')
            ->with(['notifiable' => function($query) {
                $query->with('store');
            }])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return $notifications->groupBy(function($notification) {
            return $notification->notifiable->store->id ?? null;
        })->map(function($storeNotifications) {
            $firstNotification = $storeNotifications->first();
            $store = $firstNotification->notifiable->store ?? null;
            
            if (!$store) return null;

            return [
                'store' => $store,
                'new_products_count' => $storeNotifications->count(),
                'latest_notification' => $firstNotification,
                'notifications' => $storeNotifications
            ];
        })->filter()->values();
    }

    /**
     * Delete old read notifications (cleanup)
     */
    public static function cleanupOldNotifications($days = 90)
    {
        return Notification::where('created_at', '<', now()->subDays($days))
            ->where('read_notification', 'yes')
            ->whereIn('type', [
                'marketplace_order',
                'marketplace_order_placed',
                'marketplace_order_update',
                'marketplace_new_product',
                'marketplace_subscription_reminder_7',
                'marketplace_subscription_reminder_3',
                'marketplace_subscription_reminder_1',
                'marketplace_store'
            ])
            ->delete();
    }
}