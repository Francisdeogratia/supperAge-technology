<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Message;
use App\Models\UserRecord;
use App\Models\FriendRequest;
use App\Models\Notification;

class ApiMessageController extends Controller
{
    public function conversations(Request $request)
    {
        $userId = $request->user()->id;

        $friendships = FriendRequest::where(function ($q) use ($userId) {
            $q->where('sender_id', $userId)->orWhere('receiver_id', $userId);
        })->where('status', 'accepted')->get();

        $friendIds = $friendships->map(function ($f) use ($userId) {
            return $f->sender_id == $userId ? $f->receiver_id : $f->sender_id;
        });

        $conversations = [];

        foreach ($friendIds as $friendId) {
            $lastMsg = Message::where(function ($q) use ($userId, $friendId) {
                $q->where('sender_id', $userId)->where('receiver_id', $friendId);
            })->orWhere(function ($q) use ($userId, $friendId) {
                $q->where('sender_id', $friendId)->where('receiver_id', $userId);
            })->orderBy('created_at', 'desc')->first();

            $unreadCount = Message::where('sender_id', $friendId)
                ->where('receiver_id', $userId)
                ->where('seen', 0)
                ->count();

            $friend = UserRecord::find($friendId);

            if ($friend) {
                $conversations[] = [
                    'friend'       => $this->formatUser($friend),
                    'last_message' => $lastMsg ? $this->formatMessage($lastMsg) : null,
                    'unread_count' => $unreadCount,
                    'last_at'      => $lastMsg ? $lastMsg->created_at : null,
                ];
            }
        }

        usort($conversations, fn($a, $b) => strcmp(
            (string) ($b['last_at'] ?? ''),
            (string) ($a['last_at'] ?? '')
        ));

        return response()->json(['conversations' => $conversations]);
    }

    public function messages(Request $request, $friendId)
    {
        $userId = $request->user()->id;
        $page   = $request->get('page', 1);

        $messages = Message::where(function ($q) use ($userId, $friendId) {
            $q->where('sender_id', $userId)->where('receiver_id', $friendId);
        })->orWhere(function ($q) use ($userId, $friendId) {
            $q->where('sender_id', $friendId)->where('receiver_id', $userId);
        })->orderBy('created_at', 'desc')->paginate(30, ['*'], 'page', $page);

        // Mark messages from friend as read
        Message::where('sender_id', $friendId)->where('receiver_id', $userId)
            ->where('seen', 0)
            ->update(['seen' => 1]);

        return response()->json([
            'messages'    => $messages->map(fn($m) => $this->formatMessage($m)),
            'total'       => $messages->total(),
            'current_page'=> $messages->currentPage(),
            'last_page'   => $messages->lastPage(),
        ]);
    }

    public function send(Request $request, $friendId)
    {
        $validator = Validator::make($request->all(), [
            'message'     => 'nullable|string|max:5000',
            'file_path'   => 'nullable|string',
            'reply_to_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();

        $message = Message::create([
            'sender_id'   => $user->id,
            'receiver_id' => (int) $friendId,
            'content'     => $request->message,
            'file_path'   => $request->file_path,
            'reply_to_id' => $request->reply_to_id,
            'seen'        => 0,
        ]);

        return response()->json(['message' => $this->formatMessage($message)], 201);
    }

    public function markRead(Request $request, $id)
    {
        $userId  = $request->user()->id;
        $message = Message::find($id);

        if (!$message || $message->receiver_id !== $userId) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $message->update(['seen' => 1]);

        return response()->json(['message' => 'Marked as read']);
    }

    public function editMessage(Request $request, $id)
    {
        $user    = $request->user();
        $message = Message::find($id);

        if (!$message) return response()->json(['message' => 'Not found'], 404);
        if ($message->sender_id !== $user->id) return response()->json(['message' => 'Unauthorized'], 403);

        $request->validate(['message' => 'required|string|max:5000']);

        $message->update([
            'content'   => $request->message,
            'is_edited' => 1,
        ]);

        return response()->json(['message' => $this->formatMessage($message->fresh())]);
    }

    public function deleteMessage(Request $request, $id)
    {
        $user    = $request->user();
        $message = Message::find($id);

        if (!$message) return response()->json(['message' => 'Not found'], 404);
        if ($message->sender_id !== $user->id) return response()->json(['message' => 'Unauthorized'], 403);

        $message->delete();

        return response()->json(['message' => 'Message deleted']);
    }

    public function react(Request $request, $id)
    {
        $user    = $request->user();
        $request->validate(['emoji' => 'required|string|max:10']);

        $message = Message::find($id);
        if (!$message) return response()->json(['message' => 'Not found'], 404);

        $reactions = json_decode($message->reactions ?? '{}', true) ?? [];
        $emoji     = $request->emoji;
        $userId    = (string) $user->id;

        // Toggle: if user already reacted with this emoji, remove it
        if (isset($reactions[$emoji]) && in_array($userId, $reactions[$emoji])) {
            $reactions[$emoji] = array_values(array_filter($reactions[$emoji], fn($id) => $id !== $userId));
            if (empty($reactions[$emoji])) unset($reactions[$emoji]);
        } else {
            // Remove any other emoji from this user first
            foreach ($reactions as $e => $users) {
                $reactions[$e] = array_values(array_filter($users, fn($id) => $id !== $userId));
                if (empty($reactions[$e])) unset($reactions[$e]);
            }
            $reactions[$emoji][] = $userId;
        }

        $message->update(['reactions' => json_encode($reactions)]);

        return response()->json(['reactions' => $reactions]);
    }

    public function block(Request $request)
    {
        $request->validate(['user_id' => 'required|integer']);
        $authId = $request->user()->id;
        $targetId = $request->user_id;

        DB::table('blocked_users')->updateOrInsert(
            ['blocker_id' => $authId, 'blocked_id' => $targetId],
            ['created_at' => now(), 'updated_at' => now()]
        );

        return response()->json(['message' => 'User blocked']);
    }

    public function unblock(Request $request)
    {
        $request->validate(['user_id' => 'required|integer']);
        $authId = $request->user()->id;

        DB::table('blocked_users')
            ->where('blocker_id', $authId)
            ->where('blocked_id', $request->user_id)
            ->delete();

        return response()->json(['message' => 'User unblocked']);
    }

    public function report(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'reason'  => 'required|string|max:500',
        ]);

        DB::table('user_reports')->insert([
            'reporter_id' => $request->user()->id,
            'reported_id' => $request->user_id,
            'reason'      => $request->reason,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return response()->json(['message' => 'Report submitted']);
    }

    private function formatMessage(Message $msg): array
    {
        return [
            'id'          => $msg->id,
            'sender_id'   => $msg->sender_id,
            'receiver_id' => $msg->receiver_id,
            'message'     => $msg->content,
            'file_path'   => $msg->file_path,
            'voice_note'  => $msg->voice_note,
            'reply_to_id' => $msg->reply_to_id,
            'seen'        => (bool) $msg->seen,
            'is_edited'   => (bool) ($msg->is_edited ?? false),
            'created_at'  => $msg->created_at,
        ];
    }

    private function formatUser(UserRecord $user): array
    {
        return [
            'id'         => $user->id,
            'name'       => $user->name,
            'username'   => $user->username,
            'profileimg' => $user->profileimg ? (filter_var($user->profileimg, FILTER_VALIDATE_URL) ? $user->profileimg : url($user->profileimg)) : null,
            'is_online'  => $user->is_online,
            'last_seen'  => $user->last_seen,
        ];
    }
}
