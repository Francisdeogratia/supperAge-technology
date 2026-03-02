<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class ApiNotificationController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $page   = $request->get('page', 1);

        $notifications = Notification::with('actor')
            ->where('notification_reciever_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(30, ['*'], 'page', $page);

        $unreadCount = Notification::where('notification_reciever_id', $userId)
            ->where('read_notification', 0)
            ->count();

        return response()->json([
            'notifications' => $notifications->map(fn($n) => $this->formatNotification($n)),
            'total'         => $notifications->total(),
            'unread_count'  => $unreadCount,
            'current_page'  => $notifications->currentPage(),
            'last_page'     => $notifications->lastPage(),
        ]);
    }

    public function markRead(Request $request, $id)
    {
        $userId       = $request->user()->id;
        $notification = Notification::where('id', $id)
            ->where('notification_reciever_id', $userId)
            ->first();

        if (!$notification) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $notification->update(['read_notification' => 1]);

        return response()->json(['message' => 'Marked as read']);
    }

    public function markAllRead(Request $request)
    {
        $userId = $request->user()->id;
        Notification::where('notification_reciever_id', $userId)
            ->where('read_notification', 0)
            ->update(['read_notification' => 1]);

        return response()->json(['message' => 'All notifications marked as read']);
    }

    private function formatNotification(Notification $n): array
    {
        return [
            'id'           => $n->id,
            'type'         => $n->type,
            'message'      => $n->message,
            'post_id'      => $n->post_id,
            'link'         => $n->link,
            'is_read'      => (bool) $n->read_notification,
            'created_at'   => $n->created_at,
            'actor'        => $n->actor ? [
                'id'         => $n->actor->id,
                'name'       => $n->actor->name,
                'username'   => $n->actor->username,
                'profileimg' => $n->actor->profileimg ? (filter_var($n->actor->profileimg, FILTER_VALIDATE_URL) ? $n->actor->profileimg : url($n->actor->profileimg)) : null,
            ] : null,
        ];
    }
}
