<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UserRecord;
use App\Models\Notification;

class ApiFriendController extends Controller
{
    // List friends + incoming/outgoing requests
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $friends = DB::table('friend_requests')
            ->where(function ($q) use ($userId) {
                $q->where('sender_id', $userId)->orWhere('receiver_id', $userId);
            })
            ->where('status', 'accepted')
            ->get();

        $friendIds = $friends->map(function ($f) use ($userId) {
            return $f->sender_id === $userId ? $f->receiver_id : $f->sender_id;
        });

        $friendUsers = UserRecord::whereIn('id', $friendIds)->get()
            ->map(fn($u) => $this->formatUser($u));

        $incoming = DB::table('friend_requests')
            ->join('users_record', 'friend_requests.sender_id', '=', 'users_record.id')
            ->where('friend_requests.receiver_id', $userId)
            ->where('friend_requests.status', 'pending')
            ->select('friend_requests.id as request_id', 'users_record.*')
            ->get()
            ->map(fn($u) => array_merge($this->formatUserRaw($u), ['request_id' => $u->request_id]));

        $outgoing = DB::table('friend_requests')
            ->join('users_record', 'friend_requests.receiver_id', '=', 'users_record.id')
            ->where('friend_requests.sender_id', $userId)
            ->where('friend_requests.status', 'pending')
            ->select('friend_requests.id as request_id', 'users_record.*')
            ->get()
            ->map(fn($u) => array_merge($this->formatUserRaw($u), ['request_id' => $u->request_id]));

        return response()->json([
            'friends'  => $friendUsers,
            'incoming' => $incoming,
            'outgoing' => $outgoing,
        ]);
    }

    public function sendRequest(Request $request, $userId)
    {
        $authUser = $request->user();

        if ($authUser->id == $userId) {
            return response()->json(['message' => 'Cannot send request to yourself'], 422);
        }

        $target = UserRecord::find($userId);
        if (!$target) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $existing = DB::table('friend_requests')
            ->where(function ($q) use ($authUser, $userId) {
                $q->where('sender_id', $authUser->id)->where('receiver_id', $userId);
            })
            ->orWhere(function ($q) use ($authUser, $userId) {
                $q->where('sender_id', $userId)->where('receiver_id', $authUser->id);
            })
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Request already exists'], 409);
        }

        $requestId = DB::table('friend_requests')->insertGetId([
            'sender_id'   => $authUser->id,
            'receiver_id' => $userId,
            'status'      => 'pending',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // Notify receiver
        try {
            Notification::create([
                'user_id'    => $userId,
                'from_id'    => $authUser->id,
                'type'       => 'friend_request',
                'message'    => $authUser->name . ' sent you a friend request',
                'is_read'    => 0,
            ]);
        } catch (\Exception $e) {}

        return response()->json(['message' => 'Friend request sent', 'request_id' => $requestId]);
    }

    public function acceptRequest(Request $request, $requestId)
    {
        $userId = $request->user()->id;

        $req = DB::table('friend_requests')
            ->where('id', $requestId)
            ->where('receiver_id', $userId)
            ->first();

        if (!$req) {
            return response()->json(['message' => 'Request not found'], 404);
        }

        DB::table('friend_requests')->where('id', $requestId)->update([
            'status'     => 'accepted',
            'updated_at' => now(),
        ]);

        try {
            Notification::create([
                'user_id' => $req->sender_id,
                'from_id' => $userId,
                'type'    => 'friend_accepted',
                'message' => $request->user()->name . ' accepted your friend request',
                'is_read' => 0,
            ]);
        } catch (\Exception $e) {}

        return response()->json(['message' => 'Friend request accepted']);
    }

    public function rejectRequest(Request $request, $requestId)
    {
        $userId = $request->user()->id;

        DB::table('friend_requests')
            ->where('id', $requestId)
            ->where('receiver_id', $userId)
            ->delete();

        return response()->json(['message' => 'Request rejected']);
    }

    public function cancelRequest(Request $request, $requestId)
    {
        $userId = $request->user()->id;

        DB::table('friend_requests')
            ->where('id', $requestId)
            ->where('sender_id', $userId)
            ->delete();

        return response()->json(['message' => 'Request cancelled']);
    }

    public function unfriend(Request $request, $userId)
    {
        $authId = $request->user()->id;

        DB::table('friend_requests')
            ->where(function ($q) use ($authId, $userId) {
                $q->where('sender_id', $authId)->where('receiver_id', $userId);
            })
            ->orWhere(function ($q) use ($authId, $userId) {
                $q->where('sender_id', $userId)->where('receiver_id', $authId);
            })
            ->delete();

        return response()->json(['message' => 'Unfriended']);
    }

    public function status(Request $request, $userId)
    {
        $authId = $request->user()->id;

        $req = DB::table('friend_requests')
            ->where(function ($q) use ($authId, $userId) {
                $q->where('sender_id', $authId)->where('receiver_id', $userId);
            })
            ->orWhere(function ($q) use ($authId, $userId) {
                $q->where('sender_id', $userId)->where('receiver_id', $authId);
            })
            ->first();

        if (!$req) return response()->json(['status' => 'none']);

        if ($req->status === 'accepted') {
            $status = 'friends';
        } elseif ($req->status === 'pending') {
            $status = $req->sender_id === $authId ? 'pending_sent' : 'pending_received';
        } else {
            $status = 'none';
        }

        return response()->json([
            'status'     => $status,
            'request_id' => $req->id,
            'is_sender'  => $req->sender_id === $authId,
        ]);
    }

    private function formatUser(UserRecord $u): array
    {
        return [
            'id'         => $u->id,
            'name'       => $u->name,
            'username'   => $u->username,
            'profileimg' => $u->profileimg ? (filter_var($u->profileimg, FILTER_VALIDATE_URL) ? $u->profileimg : url($u->profileimg)) : null,
            'bio'        => $u->bio,
            'badge_status' => $u->badge_status,
        ];
    }

    private function formatUserRaw($u): array
    {
        return [
            'id'         => $u->id,
            'name'       => $u->name,
            'username'   => $u->username,
            'profileimg' => isset($u->profileimg) && $u->profileimg ? (filter_var($u->profileimg, FILTER_VALIDATE_URL) ? $u->profileimg : url($u->profileimg)) : null,
            'bio'        => $u->bio ?? null,
            'badge_status' => $u->badge_status ?? null,
        ];
    }
}
