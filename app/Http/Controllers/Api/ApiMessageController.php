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
