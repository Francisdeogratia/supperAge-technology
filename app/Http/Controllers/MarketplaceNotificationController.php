<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Notification;
use App\Helpers\NotificationHelper;

class MarketplaceNotificationController extends Controller
{
    /**
     * Show all marketplace notifications
     */
    public function index()
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'You must be logged in.');
        }

        $user = \App\Models\UserRecord::find($userId);

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
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('marketplace.notifications', compact('user', 'notifications'));
    }

    /**
     * Mark single notification as read
     */
    public function markAsRead($notificationId)
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $notification = Notification::where('id', $notificationId)
            ->where('notification_reciever_id', $userId)
            ->first();

        if (!$notification) {
            return response()->json(['error' => 'Notification not found'], 404);
        }

        $notification->update(['read_notification' => 'yes']);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * Mark all marketplace notifications as read
     */
    public function markAllAsRead()
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'You must be logged in.');
        }

        NotificationHelper::markAllMarketplaceAsRead($userId);

        return redirect()->back()->with('success', 'All notifications marked as read');
    }

    /**
     * Delete notification
     */
    public function delete($notificationId)
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $notification = Notification::where('id', $notificationId)
            ->where('notification_reciever_id', $userId)
            ->first();

        if (!$notification) {
            return response()->json(['error' => 'Notification not found'], 404);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted'
        ]);
    }

    /**
     * Get notification count (for AJAX polling)
     */
    public function getCount()
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return response()->json(['count' => 0]);
        }

        $summary = NotificationHelper::getMarketplaceNotificationsSummary($userId);

        return response()->json([
            'total' => $summary['total'],
            'orders' => $summary['orders'],
            'new_products' => $summary['new_products'],
            'subscription' => $summary['subscription']
        ]);
    }

    /**
     * Get latest notifications (for real-time updates)
     */
    public function getLatest()
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return response()->json(['notifications' => []]);
        }

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
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'message' => $notification->message,
                    'link' => $notification->link,
                    'type' => $notification->type,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'data' => $notification->data ? json_decode($notification->data) : null
                ];
            });

        return response()->json([
            'notifications' => $notifications,
            'count' => $notifications->count()
        ]);
    }
}