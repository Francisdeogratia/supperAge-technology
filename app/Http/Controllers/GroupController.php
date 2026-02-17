<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\GroupJoinRequest;
use App\Models\UserRecord;
use App\Models\Notification;
use App\Models\GroupMessage;
use App\Models\GroupCall;
use App\Models\GroupCallParticipant;
use Illuminate\Support\Str;


class GroupController extends Controller
{
    public function index()
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'You must be logged in.');
        }
        
        $user = UserRecord::find($userId);
        
        // Groups created by user with unread count
        $myGroups = Group::where('created_by', $userId)
            ->withCount('members')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($group) use ($userId) {
                $group->unread_count = $this->getUnreadCount($group->id, $userId);
                $group->last_message = $this->getLastMessage($group->id);
                return $group;
            });
        
        // Groups user is a member of with unread count
        $memberGroups = Group::whereHas('members', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('created_by', '!=', $userId)
            ->withCount('members')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($group) use ($userId) {
                $group->unread_count = $this->getUnreadCount($group->id, $userId);
                $group->last_message = $this->getLastMessage($group->id);
                return $group;
            });
        
        // Other public groups
        $otherGroups = Group::where('privacy', 'public')
            ->where('created_by', '!=', $userId)
            ->whereDoesntHave('members', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->withCount('members')
            ->orderBy('member_count', 'desc')
            ->get()
            ->map(function($group) use ($userId) {
                $group->has_pending_request = $group->hasPendingRequest($userId);
                return $group;
            });
        
        return view('groups.index', compact('user', 'myGroups', 'memberGroups', 'otherGroups'));
    }
    
    // ✅ NEW: Get unread message count for a group
    private function getUnreadCount($groupId, $userId)
    {
        // Get the last time user read messages in this group
        $lastRead = DB::table('group_message_reads')
            ->where('group_id', $groupId)
            ->where('user_id', $userId)
            ->value('last_read_at');
        
        if (!$lastRead) {
            // Count all messages if never read
            return GroupMessage::where('group_id', $groupId)
                ->where('sender_id', '!=', $userId)
                ->count();
        }
        
        // Count messages after last read
        return GroupMessage::where('group_id', $groupId)
            ->where('sender_id', '!=', $userId)
            ->where('created_at', '>', $lastRead)
            ->count();
    }
    
    // ✅ NEW: Get last message for preview
    private function getLastMessage($groupId)
    {
        $message = GroupMessage::with('sender')
            ->where('group_id', $groupId)
            ->orderBy('created_at', 'desc')
            ->first();
        
        if (!$message) return null;
        
        return [
            'text' => Str::limit($message->message ?: '📎 Attachment', 50),
            'sender' => $message->sender->name,
            'time' => $message->created_at->diffForHumans()
        ];
    }
    
    public function store(Request $request)
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return response()->json(['error' => 'Not logged in'], 401);
        }
        
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'privacy' => 'required|in:public,private',
            'group_image' => 'nullable|string'
        ]);
        
        $group = Group::create([
            'name' => $request->name,
            'description' => $request->description,
            'privacy' => $request->privacy,
            'group_image' => $request->group_image,
            'created_by' => $userId,
            'member_count' => 1
        ]);
        
        GroupMember::create([
            'group_id' => $group->id,
            'user_id' => $userId,
            'role' => 'admin',
            'joined_at' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'group' => $group,
            'message' => 'Group created successfully!'
        ]);
    }
    
    public function update(Request $request, $groupId)
    {
        $userId = Session::get('id');
        $group = Group::findOrFail($groupId);
        
        if ($group->created_by != $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'privacy' => 'required|in:public,private'
        ]);
        
        $group->update([
            'name' => $request->name,
            'description' => $request->description,
            'privacy' => $request->privacy
        ]);
        
        return response()->json([
            'success' => true,
            'group' => $group,
            'message' => 'Group updated successfully!'
        ]);
    }
    
    public function join($groupId)
    {
        $userId = Session::get('id');
        $username = Session::get('username');
        
        if (!$userId) {
            return response()->json(['error' => 'Not logged in'], 401);
        }
        
        $group = Group::findOrFail($groupId);
        
        if ($group->isMember($userId)) {
            return response()->json(['error' => 'Already a member'], 400);
        }
        
        if ($group->hasPendingRequest($userId)) {
            return response()->json(['error' => 'Join request already pending'], 400);
        }
        
        if ($group->privacy === 'public') {
            GroupMember::create([
                'group_id' => $groupId,
                'user_id' => $userId,
                'role' => 'member',
                'joined_at' => now()
            ]);
            
            $group->increment('member_count');
            
            Notification::create([
                'user_id' => $userId,
                'message' => "{$username} joined your group: {$group->name}",
                'link' => route('groups.show', $groupId),
                'notification_reciever_id' => $group->created_by,
                'read_notification' => 'no',
                'type' => 'group_joined',
                'notifiable_type' => Group::class,
                'notifiable_id' => $groupId,
                'data' => json_encode(['group_id' => $groupId, 'user_id' => $userId])
            ]);
            
            return response()->json([
                'success' => true,
                'status' => 'joined',
                'message' => 'Successfully joined the group!'
            ]);
        } else {
            GroupJoinRequest::create([
                'group_id' => $groupId,
                'user_id' => $userId,
                'status' => 'pending'
            ]);
            
            Notification::create([
                'user_id' => $userId,
                'message' => "{$username} requested to join your group: {$group->name}",
                'link' => route('groups.show', $groupId),
                'notification_reciever_id' => $group->created_by,
                'read_notification' => 'no',
                'type' => 'group_join_request',
                'notifiable_type' => Group::class,
                'notifiable_id' => $groupId,
                'data' => json_encode(['group_id' => $groupId, 'user_id' => $userId])
            ]);
            
            return response()->json([
                'success' => true,
                'status' => 'pending',
                'message' => 'Join request sent!'
            ]);
        }
    }
    
    public function show($groupId)
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'You must be logged in.');
        }
        
        $user = UserRecord::find($userId);
        $group = Group::with(['creator', 'members.user', 'pinnedMessage.sender'])->findOrFail($groupId);
        
        $isMember = $group->isMember($userId);
        $isCreator = $group->created_by == $userId;
        $isAdmin = $group->isAdmin($userId);
        
        if (!$isMember && !$isCreator) {
            return redirect()->route('groups.index')->with('error', 'You must be a member to view this group.');
        }
        
        $messages = GroupMessage::with(['sender', 'replyTo'])
            ->where('group_id', $groupId)
            ->orderBy('created_at', 'asc')
            ->get();

        $activeCall = GroupCall::where('group_id', $groupId)
            ->whereIn('status', ['ringing', 'ongoing'])
            ->whereHas('participants', function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->whereIn('status', ['ringing', 'joined']);
            })
            ->first();
        
        $pinnedMessage = $group->pinnedMessage;
        
        // ✅ FIXED: Mark chat as read (update last_read_at for this user/group combo)
        DB::table('group_message_reads')->updateOrInsert(
            ['group_id' => $groupId, 'user_id' => $userId],
            [
                'last_read_at' => now(), 
                'message_id' => $messages->last()->id ?? null, // Store last message ID viewed
                'read_at' => now(),
                'updated_at' => now()
            ]
        );
        
        // ✅ Update YOUR sent messages to "seen" if others have viewed the chat
        $yourMessageIds = $messages->where('sender_id', $userId)->pluck('id')->toArray();
        if (!empty($yourMessageIds)) {
            // Check how many other members have viewed the chat since your messages
            $readCount = DB::table('group_message_reads')
                ->where('group_id', $groupId)
                ->where('user_id', '!=', $userId)
                ->whereIn('message_id', $yourMessageIds)
                ->orWhere(function($query) use ($groupId, $userId, $messages) {
                    // Or if their last_read_at is after your message timestamp
                    $lastMessageTime = $messages->where('sender_id', $userId)->last()->created_at ?? now();
                    $query->where('group_id', $groupId)
                          ->where('user_id', '!=', $userId)
                          ->where('last_read_at', '>=', $lastMessageTime);
                })
                ->count();
            
            if ($readCount > 0) {
                GroupMessage::whereIn('id', $yourMessageIds)
                    ->where('status', '!=', 'seen')
                    ->update(['status' => 'seen']);
            }
        }
        
        return view('groups.chat', compact('user', 'group', 'messages', 'isMember', 'isAdmin', 'isCreator', 'activeCall', 'pinnedMessage'));
    }
    // ✅ NEW: Mark messages as read
    private function markMessagesAsRead($groupId, $userId)
    {
        DB::table('group_message_reads')->updateOrInsert(
            ['group_id' => $groupId, 'user_id' => $userId],
            ['last_read_at' => now(), 'updated_at' => now()]
        );
    }


    public function sendMessage(Request $request, $groupId)
{
    $userId = Session::get('id');
    $username = Session::get('username');
    
    if (!$userId) {
        return response()->json(['error' => 'Not logged in'], 401);
    }
    
    $group = Group::findOrFail($groupId);
    
    if (!$group->isMember($userId)) {
        return response()->json(['error' => 'Not a member'], 403);
    }
    
    $request->validate([
        'message' => 'nullable|string|max:5000',
        'reply_to_id' => 'nullable|integer',
        'files' => 'nullable|array', 
        'files.*' => 'string',
        'voice_note' => 'nullable|string',
        'voice_duration' => 'nullable|integer',
    ]);
    
    $fileUrls = array_filter((array)$request->input('files', []));
    
    // ✅ Extract and fetch link preview
    $linkPreview = null;
    if ($request->message) {
        $urls = \App\Services\LinkPreviewService::extractUrls($request->message);
        if (!empty($urls)) {
            // Get preview for first URL
            $linkPreview = \App\Services\LinkPreviewService::fetchPreview($urls[0]);
        }
    }
    
    $groupMessage = GroupMessage::create([
        'group_id' => $groupId,
        'sender_id' => $userId,
        'message' => $request->message ?? '',
        'file_path' => !empty($fileUrls) ? json_encode($fileUrls) : null,
        'voice_note' => $request->voice_note,
        'voice_duration' => $request->voice_duration,
        'reply_to_id' => $request->reply_to_id,
        'is_deleted' => false,
        'is_edited' => false,
        'status' => 'sent',
        'link_preview' => $linkPreview ? json_encode($linkPreview) : null,  // ✅ NEW
    ]);
    
    $members = $group->members()->where('user_id', '!=', $userId)->get();
    
    foreach ($members as $member) {
        Notification::create([
            'user_id' => $userId,
            'message' => "{$username} sent a message in {$group->name}",
            'link' => route('groups.show', $groupId),
            'notification_reciever_id' => $member->user_id,
            'read_notification' => 'no',
            'type' => 'group_message',
            'notifiable_type' => GroupMessage::class,
            'notifiable_id' => $groupMessage->id,
            'data' => json_encode([
                'sender_id' => $userId,
                'group_id' => $groupId,
                'message_preview' => Str::limit($request->message ?? 'Sent an attachment', 50),
            ]),
        ]);
    }
    
    $groupMessage->load(['sender', 'replyTo']);
    
    return response()->json([
        'success' => true,
        'message' => $groupMessage,
    ]);
}

    // public function sendMessage(Request $request, $groupId)
    // {
    //     $userId = Session::get('id');
    //     $username = Session::get('username');
        
    //     if (!$userId) {
    //         return response()->json(['error' => 'Not logged in'], 401);
    //     }
        
    //     $group = Group::findOrFail($groupId);
        
    //     if (!$group->isMember($userId)) {
    //         return response()->json(['error' => 'Not a member'], 403);
    //     }
        
    //     $request->validate([
    //         'message' => 'nullable|string|max:5000',
    //         'reply_to_id' => 'nullable|integer',
    //         'files' => 'nullable|array', 
    //         'files.*' => 'string',
    //         'voice_note' => 'nullable|string',
    //         'voice_duration' => 'nullable|integer',
    //     ]);
        
    //     $fileUrls = array_filter((array)$request->input('files', []));
        
    //     $groupMessage = GroupMessage::create([
    //         'group_id' => $groupId,
    //         'sender_id' => $userId,
    //         'message' => $request->message ?? '',
    //         'file_path' => !empty($fileUrls) ? json_encode($fileUrls) : null,
    //         'voice_note' => $request->voice_note,
    //         'voice_duration' => $request->voice_duration,
    //         'reply_to_id' => $request->reply_to_id,
    //         'is_deleted' => false,
    //         'is_edited' => false,
    //         'status' => 'sent', // ✅ ADD THIS - default status
    //     ]);
        
    //     $members = $group->members()->where('user_id', '!=', $userId)->get();
        
    //     foreach ($members as $member) {
    //         Notification::create([
    //             'user_id' => $userId,
    //             'message' => "{$username} sent a message in {$group->name}",
    //             'link' => route('groups.show', $groupId),
    //             'notification_reciever_id' => $member->user_id,
    //             'read_notification' => 'no',
    //             'type' => 'group_message',
    //             'notifiable_type' => GroupMessage::class,
    //             'notifiable_id' => $groupMessage->id,
    //             'data' => json_encode([
    //                 'sender_id' => $userId,
    //                 'group_id' => $groupId,
    //                 'message_preview' => Str::limit($request->message ?? 'Sent an attachment', 50),
    //             ]),
    //         ]);
    //     }
        
    //     $groupMessage->load(['sender', 'replyTo']);
        
    //     return response()->json([
    //         'success' => true,
    //         'message' => $groupMessage,
    //     ]);
    // }


    // ✅ NEW: Update typing indicator
    public function updateTyping(Request $request, $groupId)
{
    $userId = Session::get('id');
    
    if (!$userId) {
        return response()->json(['error' => 'Not logged in'], 401);
    }
    
    $isTyping = $request->input('is_typing', true);
    
    if ($isTyping) {
        // User is typing
        DB::table('group_typing_indicators')->updateOrInsert(
            ['user_id' => $userId, 'group_id' => $groupId],
            ['last_typed_at' => now(), 'updated_at' => now()]
        );
    } else {
        // User stopped typing - DELETE record
        DB::table('group_typing_indicators')
            ->where('user_id', $userId)
            ->where('group_id', $groupId)
            ->delete();
    }
    
    return response()->json(['success' => true]);
}

    public function getNewMessages($groupId, $lastMessageId = 0)
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return response()->json(['error' => 'Not logged in'], 401);
        }
        
        $group = Group::findOrFail($groupId);
        
        if (!$group->isMember($userId)) {
            return response()->json(['error' => 'Not a member'], 403);
        }
        
        // Get new messages
        $messages = GroupMessage::with('sender')
            ->where('group_id', $groupId)
            ->where('id', '>', $lastMessageId)
            ->orderBy('created_at', 'asc')
            ->get();
        
        // ✅ FIXED: Update last_read_at for this user (marks chat as viewed)
        if ($messages->isNotEmpty()) {
            DB::table('group_message_reads')->updateOrInsert(
                ['group_id' => $groupId, 'user_id' => $userId],
                [
                    'last_read_at' => now(),
                    'message_id' => $messages->last()->id,
                    'read_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
        
        // ✅ Check if YOUR old messages were seen by checking other users' last_read_at
        $yourMessages = GroupMessage::where('group_id', $groupId)
            ->where('sender_id', $userId)
            ->where('status', '!=', 'seen')
            ->get();
        
        foreach ($yourMessages as $msg) {
            // Check if any OTHER member has read the chat since this message was sent
            $readCount = DB::table('group_message_reads')
                ->where('group_id', $groupId)
                ->where('user_id', '!=', $userId)
                ->where('last_read_at', '>=', $msg->created_at)
                ->count();
            
            if ($readCount > 0) {
                $msg->update(['status' => 'seen']);
                $msg->status = 'seen'; // Update in current collection
            }
        }
        
        // ✅ Add status to new messages in response
        $messages->each(function($msg) use ($userId, $groupId) {
            if ($msg->sender_id == $userId) {
                // For your own messages, check if others have seen them
                $readCount = DB::table('group_message_reads')
                    ->where('group_id', $groupId)
                    ->where('user_id', '!=', $userId)
                    ->where('last_read_at', '>=', $msg->created_at)
                    ->count();
                
                $msg->status = $readCount > 0 ? 'seen' : 'sent';
            }
        });
        
        // Typing indicator
        $typingUsers = DB::table('group_typing_indicators')
            ->join('users_record', 'users_record.id', '=', 'group_typing_indicators.user_id')
            ->where('group_id', $groupId)
            ->where('user_id', '!=', $userId)
            ->where('last_typed_at', '>', now()->subSeconds(3))
            ->pluck('users_record.name')
            ->toArray();
        
        // Clean up old typing indicators
        DB::table('group_typing_indicators')
            ->where('group_id', $groupId)
            ->where('last_typed_at', '<', now()->subSeconds(5))
            ->delete();
        
        return response()->json([
            'messages' => $messages,
            'typing_users' => $typingUsers
        ]);
    }

     // ✅ NEW: Get message read count
    public function getMessageReadCount($messageId)
    {
        $readCount = DB::table('group_message_reads')
            ->where('message_id', $messageId)
            ->count();
        
        return response()->json([
            'success' => true,
            'read_count' => $readCount
        ]);
    }

    public function deleteMessage($messageId)
    {
        $userId = Session::get('id');
        $message = GroupMessage::findOrFail($messageId);
        
        $group = Group::findOrFail($message->group_id);
        $isAdmin = $group->isAdmin($userId);
        
        if ($message->sender_id != $userId && !$isAdmin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $message->update([
            'is_deleted' => true,
            'message' => '🚫 This message was deleted',
            'file_path' => null,
            'voice_note' => null,
            'voice_duration' => null,
        ]);
        
        return response()->json(['success' => true]);
    }

    public function editMessage(Request $request, $messageId)
    {
        $userId = Session::get('id');
        $message = GroupMessage::findOrFail($messageId);
        
        if ($message->sender_id != $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $message->update([
            'message' => $request->message,
            'is_edited' => true,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function reactToMessage(Request $request, $messageId)
    {
        $userId = Session::get('id');
        $emoji = $request->input('emoji');
        
        $message = GroupMessage::findOrFail($messageId);
        $reactions = json_decode($message->reactions, true) ?? [];
        
        if (!isset($reactions[$emoji])) {
            $reactions[$emoji] = [];
        }
        
        if (in_array($userId, $reactions[$emoji])) {
            $reactions[$emoji] = array_diff($reactions[$emoji], [$userId]);
            if (empty($reactions[$emoji])) {
                unset($reactions[$emoji]);
            }
        } else {
            $reactions[$emoji][] = $userId;
        }
        
        $message->update(['reactions' => json_encode($reactions)]);
        
        return response()->json([
            'success' => true,
            'reactions' => $reactions
        ]);
    }

    // ✅ FIXED: Pin message
    public function pinMessage($groupId, $messageId)
    {
        $userId = Session::get('id');
        $group = Group::findOrFail($groupId);
        
        if (!$group->isAdmin($userId)) {
            return response()->json(['success' => false, 'message' => 'Only admins can pin messages'], 403);
        }
        
        $message = GroupMessage::findOrFail($messageId);
        
        if ($message->group_id != $groupId) {
            return response()->json(['success' => false, 'message' => 'Message not in this group'], 400);
        }
        
        $group->update(['pinned_message_id' => $messageId]);
        
        return response()->json(['success' => true]);
    }

    // ✅ FIXED: Unpin message
    public function unpinMessage($groupId)
    {
        $userId = Session::get('id');
        $group = Group::findOrFail($groupId);
        
        if (!$group->isAdmin($userId)) {
            return response()->json(['success' => false, 'message' => 'Only admins can unpin messages'], 403);
        }
        
        $group->update(['pinned_message_id' => null]);
        
        return response()->json(['success' => true]);
    }

    public function addMembers(Request $request, $groupId)
    {
        $userId = Session::get('id');
        $group = Group::findOrFail($groupId);
        
        if (!$group->isAdmin($userId)) {
            return response()->json(['error' => 'Only admins can add members'], 403);
        }
        
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'integer'
        ]);
        
        $addedCount = 0;
        foreach ($request->user_ids as $newUserId) {
            if (!$group->isMember($newUserId)) {
                GroupMember::create([
                    'group_id' => $groupId,
                    'user_id' => $newUserId,
                    'role' => 'member',
                    'joined_at' => now()
                ]);
                $addedCount++;
                
                $username = Session::get('username');
                Notification::create([
                    'user_id' => $userId,
                    'message' => "{$username} added you to {$group->name}",
                    'link' => route('groups.show', $groupId),
                    'notification_reciever_id' => $newUserId,
                    'read_notification' => 'no',
                    'type' => 'group_added',
                    'notifiable_type' => Group::class,
                    'notifiable_id' => $groupId,
                    'data' => json_encode(['group_id' => $groupId])
                ]);
            }
        }
        
        $group->update(['member_count' => $group->members()->count()]);
        
        return response()->json([
            'success' => true,
            'message' => "{$addedCount} member(s) added successfully!"
        ]);
    }

    public function removeMember($groupId, $memberId)
    {
        $userId = Session::get('id');
        $group = Group::findOrFail($groupId);
        
        if ($userId != $memberId && !$group->isAdmin($userId)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        if ($memberId == $group->created_by) {
            return response()->json(['error' => 'Cannot remove group creator'], 400);
        }
        
        GroupMember::where('group_id', $groupId)
            ->where('user_id', $memberId)
            ->delete();
        
        $group->decrement('member_count');
        
        return response()->json([
            'success' => true,
            'message' => 'Member removed successfully!'
        ]);
    }

    public function getFriendsList()
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return response()->json(['error' => 'Not logged in'], 401);
        }
        
        $blockedUserIds = DB::table('blocked_users')
            ->where('blocker_id', $userId)
            ->pluck('blocked_id')
            ->toArray();
        
        $friendIds = DB::table('friend_requests')
            ->where(function($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->orWhere('receiver_id', $userId);
            })
            ->where('status', 'accepted')
            ->get()
            ->map(function($request) use ($userId) {
                return $request->sender_id == $userId 
                    ? $request->receiver_id 
                    : $request->sender_id;
            })
            ->reject(function($friendId) use ($blockedUserIds) {
                return in_array($friendId, $blockedUserIds);
            })
            ->toArray();
        
        $friends = DB::table('users_record')
            ->whereIn('id', $friendIds)
            ->select('id', 'name', 'username', 'profileimg')
            ->orderBy('name')
            ->get();
        
        return response()->json([
            'success' => true,
            'friends' => $friends
        ]);
    }

    public function forwardMessage(Request $request, $messageId)
    {
        $userId = Session::get('id');
        $username = Session::get('username');
        
        if (!$userId) {
            return response()->json(['error' => 'Not logged in'], 401);
        }
        
        $request->validate([
            'friend_ids' => 'required|array|min:1',
            'friend_ids.*' => 'integer|exists:users_record,id'
        ]);
        
        $originalMessage = DB::table('group_messages')
            ->where('id', $messageId)
            ->first();
        
        if (!$originalMessage) {
            return response()->json(['error' => 'Message not found'], 404);
        }
        
        $friendIds = $request->input('friend_ids');
        $forwardedCount = 0;
        
        foreach ($friendIds as $friendId) {
            $areFriends = DB::table('friend_requests')
                ->where(function($query) use ($userId, $friendId) {
                    $query->where('sender_id', $userId)
                          ->where('receiver_id', $friendId);
                })
                ->orWhere(function($query) use ($userId, $friendId) {
                    $query->where('sender_id', $friendId)
                          ->where('receiver_id', $userId);
                })
                ->where('status', 'accepted')
                ->exists();
            
            if (!$areFriends) {
                continue;
            }
            
            $newMessageId = DB::table('messages')->insertGetId([
                'sender_id' => $userId,
                'receiver_id' => $friendId,
                'message' => $originalMessage->message ?? '',
                'file_path' => $originalMessage->file_path ?? null,
                'voice_note' => $originalMessage->voice_note ?? null,
                'voice_duration' => $originalMessage->voice_duration ?? null,
                'is_forwarded' => true,
                'status' => 'sent',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            DB::table('notifications')->insert([
                'user_id' => $userId,
                'message' => "{$username} forwarded you a message",
                'link' => '/messages/' . $userId,
                'notification_reciever_id' => $friendId,
                'read_notification' => 'no',
                'type' => 'new_message',
                'notifiable_type' => 'App\\Models\\Message',
                'notifiable_id' => $newMessageId,
                'data' => json_encode([
                    'sender_id' => $userId,
                    'message_preview' => $originalMessage->message 
                        ? \Str::limit($originalMessage->message, 50) 
                        : 'Forwarded message',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $forwardedCount++;
        }
        
        return response()->json([
            'success' => true,
            'message' => "Message forwarded to {$forwardedCount} friend(s)",
            'forwarded_count' => $forwardedCount
        ]);
    }

    public function reportGroup(Request $request, $groupId)
    {
        $userId = Session::get('id');
        
        $request->validate([
            'reason' => 'required|string',
            'details' => 'nullable|string|max:1000'
        ]);
        
        DB::table('group_reports')->insert([
            'group_id' => $groupId,
            'reporter_id' => $userId,
            'reason' => $request->reason,
            'details' => $request->details,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return response()->json(['success' => true]);
    }

    public function delete($groupId)
    {
        $userId = Session::get('id');
        $group = Group::findOrFail($groupId);
        
        if ($group->created_by != $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $group->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Group deleted successfully!'
        ]);
    }

public function getLinkPreview(Request $request)
{
    $userId = Session::get('id');
    
    if (!$userId) {
        return response()->json(['error' => 'Not logged in'], 401);
    }
    
    $request->validate([
        'url' => 'required|url'
    ]);
    
    $preview = \App\Services\LinkPreviewService::fetchPreview($request->url);
    
    return response()->json([
        'success' => true,
        'preview' => $preview
    ]);
}
   

}