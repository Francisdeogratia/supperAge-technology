<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\UserRecord;
use App\Models\FriendRequest;
use App\Models\Notification;

class FriendController extends Controller
{
    public function index()
    {
        $userId = Session::get('id');
        $username = Session::get('username');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'You must be logged in.');
        }
        
        $user = UserRecord::find($userId);
        
        // Get all users except current user
        $allUsers = UserRecord::with('lastLoginSession')
            ->where('id', '!=', $userId)
            ->orderBy('name')
            ->paginate(20);
        
        // Get friend request IDs (sent and received)
        $sentRequestIds = FriendRequest::where('sender_id', $userId)
            ->where('status', 'pending')
            ->pluck('receiver_id')
            ->toArray();
        
        $receivedRequestIds = FriendRequest::where('receiver_id', $userId)
            ->where('status', 'pending')
            ->pluck('sender_id')
            ->toArray();
        
        $acceptedFriendIds = FriendRequest::where(function($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->orWhere('receiver_id', $userId);
            })
            ->where('status', 'accepted')
            ->get()
            ->map(function($request) use ($userId) {
                return $request->sender_id == $userId ? $request->receiver_id : $request->sender_id;
            })
            ->toArray();
        
        // Get pending friend requests
        $pendingRequests = FriendRequest::where('receiver_id', $userId)
            ->where('status', 'pending')
            ->with('sender')
            ->get();
        
        return view('friends.index', compact(
            'user',
            'allUsers',
            'sentRequestIds',
            'receivedRequestIds',
            'acceptedFriendIds',
            'pendingRequests'
        ));
    }
    
    public function sendRequest($receiverId)
    {
        try {
            $userId = Session::get('id');
            $username = Session::get('username');

            if (!$userId) {
                return response()->json(['success' => false, 'error' => 'Not logged in'], 401);
            }

            // Cannot send request to yourself
            if ($userId == $receiverId) {
                return response()->json(['success' => false, 'error' => 'Cannot send friend request to yourself'], 400);
            }

            // Check if receiver exists
            $receiver = UserRecord::find($receiverId);
            if (!$receiver) {
                return response()->json(['success' => false, 'error' => 'User not found'], 404);
            }

            // Check if request already exists
            $existingRequest = FriendRequest::where(function($query) use ($userId, $receiverId) {
                $query->where('sender_id', $userId)->where('receiver_id', $receiverId);
            })->orWhere(function($query) use ($userId, $receiverId) {
                $query->where('sender_id', $receiverId)->where('receiver_id', $userId);
            })->first();

            if ($existingRequest) {
                if ($existingRequest->status === 'accepted') {
                    return response()->json(['success' => false, 'error' => 'You are already friends'], 400);
                }
                return response()->json(['success' => false, 'error' => 'Friend request already exists'], 400);
            }

            // Create friend request
            $friendRequest = FriendRequest::create([
                'sender_id' => $userId,
                'receiver_id' => $receiverId,
                'status' => 'pending',
            ]);

            // Send notification
            Notification::create([
                'user_id' => $userId,
                'message' => "{$username} sent you a friend request",
                'link' => route('friends.index'),
                'notification_reciever_id' => $receiverId,
                'read_notification' => 'no',
                'type' => 'friend_request',
                'notifiable_type' => FriendRequest::class,
                'notifiable_id' => $friendRequest->id,
                'data' => json_encode([
                    'sender_id' => $userId,
                    'sender_name' => $username,
                ]),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Friend request sent successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Friend request error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to send friend request. Please try again.'
            ], 500);
        }
    }
    
    public function acceptRequest($requestId)
    {
        $userId = Session::get('id');
        $username = Session::get('username');
        
        $request = FriendRequest::findOrFail($requestId);
        
        if ($request->receiver_id != $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $request->update(['status' => 'accepted']);
        
        // Notify sender
        Notification::create([
            'user_id' => $userId,
            'message' => "{$username} accepted your friend request",
            'link' => route('profile.show', $userId),
            'notification_reciever_id' => $request->sender_id,
            'read_notification' => 'no',
            'type' => 'friend_request_accepted',
            'notifiable_type' => FriendRequest::class,
            'notifiable_id' => $requestId,
            'data' => json_encode([]), // ✅ provide at least empty JSON
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Friend request accepted'
        ]);
    }
    
    public function rejectRequest($requestId)
    {
        $userId = Session::get('id');
        
        $request = FriendRequest::findOrFail($requestId);
        
        if ($request->receiver_id != $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $request->update(['status' => 'rejected']);
        
        return response()->json([
            'success' => true,
            'message' => 'Friend request rejected'
        ]);
    }
    
    public function cancelRequest($requestId)
    {
        $userId = Session::get('id');
        
        $request = FriendRequest::findOrFail($requestId);
        
        if ($request->sender_id != $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $request->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Friend request cancelled'
        ]);
    }
}
