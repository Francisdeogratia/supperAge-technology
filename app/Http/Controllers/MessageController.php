<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use DOMDocument;
use DOMXPath;
use App\Models\UserRecord;
use App\Models\Message;
use App\Models\FriendRequest;
use App\Models\Notification;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    // Show list of friends to message
    public function index()
{
    $userId = Session::get('id');
    $username = Session::get('username');
    
    if (!$userId) {
        return redirect('/login')->with('error', 'You must be logged in.');
    }
    
    $user = UserRecord::find($userId);

    // --- 1. GET ARCHIVED & BLOCKED IDS/COUNTS ---
    
    // Get IDs of blocked users by the current user
    $blockedUserIds = DB::table('blocked_users')
        ->where('blocker_id', $userId)
        ->pluck('blocked_id')
        ->toArray();
    $blockedCount = count($blockedUserIds);

    // Get IDs of archived chats by the current user
    $archivedFriendIds = DB::table('archived_chats')
        ->where('user_id', $userId)
        ->pluck('friend_id')
        ->toArray();
    $archivedCount = count($archivedFriendIds);
    
    // --- 2. FILTER FRIEND IDs (Excluding Blocked & Archived) ---
    
    // Get all accepted friends (potential chat participants)
    $friendIds = FriendRequest::where(function($query) use ($userId) {
        $query->where('sender_id', $userId)
              ->orWhere('receiver_id', $userId);
    })
    ->where('status', 'accepted')
    ->get()
    ->map(function($request) use ($userId) {
        // Get the ID of the friend (the one who is not the current user)
        return $request->sender_id == $userId ? $request->receiver_id : $request->sender_id;
    })
    ->reject(function($friendId) use ($blockedUserIds, $archivedFriendIds) {
        // Filter out IDs that are blocked OR archived
        return in_array($friendId, $blockedUserIds) || in_array($friendId, $archivedFriendIds); 
    })
    ->toArray();
    
    // --- 3. FETCH FRIEND DATA & MESSAGES ---
    
    // Get remaining friends with last message and unread count
    $friends = UserRecord::with('lastLoginSession')
        ->whereIn('id', $friendIds)
        ->get()
        ->map(function($friend) use ($userId) {
            // Get last message between users
            $lastMessage = Message::where(function($query) use ($userId, $friend) {
                $query->where('sender_id', $userId)->where('receiver_id', $friend->id);
            })->orWhere(function($query) use ($userId, $friend) {
                $query->where('sender_id', $friend->id)->where('receiver_id', $userId);
            })
            ->orderByDesc('created_at')
            ->first();
            
            // Count unread messages from this friend
            $unreadCount = Message::where('sender_id', $friend->id)
                ->where('receiver_id', $userId)
                ->where('status', '!=', 'read')
                ->count();
            
            // Check if friend is typing
            $isTyping = DB::table('typing_indicators')
                ->where('user_id', $friend->id)
                ->where('typing_to_user_id', $userId)
                ->where('last_typed_at', '>', now()->subSeconds(3))
                ->exists();
            
            $friend->last_message = $lastMessage;
            $friend->unread_count = $unreadCount;
            $friend->is_typing = $isTyping;
            
            return $friend;
        })
        ->sortByDesc(function($friend) {
            return $friend->last_message ? $friend->last_message->created_at : null;
        });
    
    // --- 4. FINAL COUNT & RETURN ---

    // Calculate the final count of visible friends
    $friendCount = $friends->count(); 
    
    // Pass the data to the view
    return view('messages.index', compact('user', 'friends', 'archivedCount', 'blockedCount', 'friendCount'));
}
    // Show chat with specific user
    public function chat($friendId)
    {
        $userId = Session::get('id');
        $username = Session::get('username');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'You must be logged in.');
        }
        
        // Check if user is blocked
        $isBlocked = DB::table('blocked_users')
            ->where(function($query) use ($userId, $friendId) {
                $query->where('blocker_id', $userId)->where('blocked_id', $friendId);
            })
            ->orWhere(function($query) use ($userId, $friendId) {
                $query->where('blocker_id', $friendId)->where('blocked_id', $userId);
            })
            ->exists();
        
        if ($isBlocked) {
            return redirect()->route('messages.index')->with('error', 'You cannot message this user.');
        }
        
        $user = UserRecord::find($userId);
        $friend = UserRecord::with('lastLoginSession')->findOrFail($friendId);
        
        // Check if they are friends
        $areFriends = FriendRequest::where(function($query) use ($userId, $friendId) {
                $query->where('sender_id', $userId)->where('receiver_id', $friendId);
            })->orWhere(function($query) use ($userId, $friendId) {
                $query->where('sender_id', $friendId)->where('receiver_id', $userId);
            })
            ->where('status', 'accepted')
            ->exists();
        
        if (!$areFriends) {
            return redirect()->route('messages.index')->with('error', 'You can only message friends.');
        }

        // --- NEW: Fetch Pinned Message ---
    $pinKey = $this->getPinKey($userId, $friendId);

    $pinnedMessageRecord = DB::table('pinned_messages')
        ->where($pinKey)
        ->first();
    
    $pinnedMessage = null;
    if ($pinnedMessageRecord) {
        $pinnedMessage = Message::with('replyTo', 'sender') // Load relationships for display
            ->find($pinnedMessageRecord->message_id);
    }
    // --- END NEW ---
        
        // Get messages between users
        $messages = Message::with('replyTo')
            ->where(function($query) use ($userId, $friendId) {
                $query->where('sender_id', $userId)->where('receiver_id', $friendId);
            })
            ->orWhere(function($query) use ($userId, $friendId) {
                $query->where('sender_id', $friendId)->where('receiver_id', $userId);
            })
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Mark messages as read
        Message::where('sender_id', $friendId)
            ->where('receiver_id', $userId)
            ->where('status', '!=', 'read')
            ->update(['status' => 'read']);
        
        // Pass the pinned message to the view
    return view('messages.chat', compact('user', 'friend', 'messages', 'pinnedMessage'));
    }
    
    // ✅ UPDATE: Send method to include link preview
public function send(Request $request, $friendId)
{
    $userId = Session::get('id');
    $username = Session::get('username');
    
    if (!$userId) {
        return response()->json(['error' => 'Not logged in'], 401);
    }
    
    $request->validate([
        'message' => 'nullable|string|max:5000',
        'reply_to_id' => 'nullable|integer',
        'files' => 'nullable|array', 
        'files.*' => 'string',
        'voice_note' => 'nullable|string',
        'voice_duration' => 'nullable|integer',
        'link_preview' => 'nullable|string', // ✅ NEW
    ]);
    
    $fileUrls = array_filter((array)$request->input('files', []));
    
    $message = Message::create([
        'sender_id' => $userId,
        'receiver_id' => $friendId,
        'message' => $request->message ?? '',
        'file_path' => !empty($fileUrls) ? json_encode($fileUrls) : null,
        'voice_note' => $request->voice_note,
        'voice_duration' => $request->voice_duration,
        'link_preview' => $request->link_preview, // ✅ NEW
        'reply_to_id' => $request->reply_to_id,
        'status' => 'sent',
        'is_forwarded' => false,
        'is_edited' => false,
    ]);
    
    $notificationMessage = $request->voice_note 
        ? "{$username} sent you a voice message" 
        : "{$username} sent you a message";
    
    $messagePreview = $request->voice_note 
        ? '🎤 Voice message' 
        : Str::limit($request->message ?? 'Sent an attachment', 50);
    
    Notification::create([
        'user_id' => $userId,
        'message' => $notificationMessage,
        'link' => route('messages.chat', $userId),
        'notification_reciever_id' => $friendId,
        'read_notification' => 'no',
        'type' => 'new_message',
        'notifiable_type' => Message::class,
        'notifiable_id' => $message->id,
        'data' => json_encode([
            'sender_id' => $userId,
            'message_preview' => $messagePreview,
        ]),
    ]);
    
    $message->load(['sender', 'replyTo']);
    
    return response()->json([
        'success' => true,
        'message' => $message,
    ]);
}
    // Send message with file upload
    // Send message with file upload
//     public function send(Request $request, $friendId)
// {
//     $userId = Session::get('id');
//     $username = Session::get('username');
    
//     if (!$userId) {
//         return response()->json(['error' => 'Not logged in'], 401);
//     }
    
//     $request->validate([
//         'message' => 'nullable|string|max:5000',
//         'reply_to_id' => 'nullable|integer',
//         'files' => 'nullable|array', 
//         'files.*' => 'string',
//         'voice_note' => 'nullable|string',      // NEW
//         'voice_duration' => 'nullable|integer', // NEW
//     ]);
    
//     $fileUrls = array_filter((array)$request->input('files', []));
    
//     $message = Message::create([
//         'sender_id' => $userId,
//         'receiver_id' => $friendId,
//         'message' => $request->message ?? '',
//         'file_path' => !empty($fileUrls) ? json_encode($fileUrls) : null,
//         'voice_note' => $request->voice_note,           // NEW
//         'voice_duration' => $request->voice_duration,   // NEW
//         'reply_to_id' => $request->reply_to_id,
//         'status' => 'sent',
//         'is_forwarded' => false,
//         'is_edited' => false,
//     ]);
    
//     // Determine notification message
//     $notificationMessage = $request->voice_note 
//         ? "{$username} sent you a voice message" 
//         : "{$username} sent you a message";
    
//     $messagePreview = $request->voice_note 
//         ? '🎤 Voice message' 
//         : Str::limit($request->message ?? 'Sent an attachment', 50);
    
//     Notification::create([
//         'user_id' => $userId,
//         'message' => $notificationMessage,
//         'link' => route('messages.chat', $userId),
//         'notification_reciever_id' => $friendId,
//         'read_notification' => 'no',
//         'type' => 'new_message',
//         'notifiable_type' => Message::class,
//         'notifiable_id' => $message->id,
//         'data' => json_encode([
//             'sender_id' => $userId,
//             'message_preview' => $messagePreview,
//         ]),
//     ]);
    
//     $message->load(['sender', 'replyTo']);
    
//     return response()->json([
//         'success' => true,
//         'message' => $message,
//     ]);
// }
    
    // Get new messages (for polling)
    public function getNewMessages($friendId, $lastMessageId = 0)
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return response()->json(['error' => 'Not logged in'], 401);
        }
        
        $messages = Message::with('sender')
            ->where('sender_id', $friendId)
            ->where('receiver_id', $userId)
            ->where('id', '>', $lastMessageId)
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Mark as read
        Message::where('sender_id', $friendId)
            ->where('receiver_id', $userId)
            ->where('status', '!=', 'read')
            ->update(['status' => 'read']);
        
        // Check if friend is typing
        $isTyping = DB::table('typing_indicators')
            ->where('user_id', $friendId)
            ->where('typing_to_user_id', $userId)
            ->where('last_typed_at', '>', now()->subSeconds(3))
            ->exists();
        
        return response()->json([
            'messages' => $messages,
            'is_typing' => $isTyping,
        ]);
    }
    
    // Update typing indicator
    public function updateTyping($friendId)
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return response()->json(['error' => 'Not logged in'], 401);
        }
        
        DB::table('typing_indicators')->updateOrInsert(
            ['user_id' => $userId, 'typing_to_user_id' => $friendId],
            ['last_typed_at' => now(), 'updated_at' => now()]
        );
        
        return response()->json(['success' => true]);
    }
    
    // Edit message
    public function edit(Request $request, $messageId)
    {
        $userId = Session::get('id');
        
        $message = Message::findOrFail($messageId);
        
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
    
   // Delete message
public function delete($messageId)
{
    $userId = Session::get('id');
    $message = Message::findOrFail($messageId);

    if ($message->sender_id != $userId) {
        return response()->json(['error' => 'Unauthorized to delete this message'], 403);
    }

    // Clear all message content including voice notes
    $message->update([
        'is_deleted_by_sender' => true,
        'message' => '🚫 This message was deleted',
        'file_path' => null,
        'voice_note' => null,      // ✅ Clear voice note
        'voice_duration' => null,  // ✅ Clear duration
    ]);

    return response()->json(['success' => true]);
}

    
    // Get friends for forwarding
    public function getFriends()
    {
        $userId = Session::get('id');
        
        $blockedUserIds = DB::table('blocked_users')
            ->where('blocker_id', $userId)
            ->pluck('blocked_id')
            ->toArray();
        
        $friendIds = FriendRequest::where(function($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->orWhere('receiver_id', $userId);
            })
            ->where('status', 'accepted')
            ->get()
            ->map(function($request) use ($userId) {
                return $request->sender_id == $userId ? $request->receiver_id : $request->sender_id;
            })
            ->reject(function($friendId) use ($blockedUserIds) {
                return in_array($friendId, $blockedUserIds);
            });
        
        $friends = UserRecord::whereIn('id', $friendIds)
            ->select('id', 'name', 'username', 'profileimg')
            ->get();
        
        return response()->json(['friends' => $friends]);
    }
    
    // Forward message
    public function forward(Request $request, $messageId)
    {
        $userId = Session::get('id');
        $username = Session::get('username');
        
        $originalMessage = Message::findOrFail($messageId);
        $friendIds = $request->input('friend_ids', []);
        
        foreach ($friendIds as $friendId) {
            $newMessage = Message::create([
                'sender_id' => $userId,
                'receiver_id' => $friendId,
                'message' => $originalMessage->message,
                'file_path' => $originalMessage->file_path,
                'is_forwarded' => true,
                'status' => 'sent',
            ]);
            
            Notification::create([
                'user_id' => $userId,
                'message' => "{$username} sent you a message",
                'link' => route('messages.chat', $userId),
                'notification_reciever_id' => $friendId,
                'read_notification' => 'no',
                'type' => 'new_message',
                'notifiable_type' => Message::class,
                'notifiable_id' => $newMessage->id,
                'data' => json_encode([
                    'sender_id' => $userId,
                    'message_preview' => 'Forwarded message',
                ]),
            ]);
        }
        
        return response()->json(['success' => true]);
    }
    
    // Block user
    public function blockUser(Request $request)
    {
        $userId = Session::get('id');
        $blockedUserId = $request->input('blocked_user_id');
        
        DB::table('blocked_users')->insert([
            'blocker_id' => $userId,
            'blocked_id' => $blockedUserId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return response()->json(['success' => true]);
    }
    
    // Unblock user
    public function unblockUser(Request $request)
    {
        $userId = Session::get('id');
        $blockedUserId = $request->input('blocked_user_id');
        
        DB::table('blocked_users')
            ->where('blocker_id', $userId)
            ->where('blocked_id', $blockedUserId)
            ->delete();
        
        return response()->json(['success' => true]);
    }
    
    // Report user
    public function reportUser(Request $request)
    {
        $userId = Session::get('id');
        
        DB::table('user_reports')->insert([
            'reporter_id' => $userId,
            'reported_user_id' => $request->input('reported_user_id'),
            'reason' => $request->input('reason'),
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return response()->json(['success' => true]);
    }
    
    // Archive chat
    public function archiveChat(Request $request)
    {
        $userId = Session::get('id');
        $friendId = $request->input('friend_id');
        
        DB::table('archived_chats')->insert([
            'user_id' => $userId,
            'friend_id' => $friendId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return response()->json(['success' => true]);
    }
    
    // Unarchive chat
    public function unarchiveChat(Request $request)
    {
        $userId = Session::get('id');
        $friendId = $request->input('friend_id');
        
        DB::table('archived_chats')
            ->where('user_id', $userId)
            ->where('friend_id', $friendId)
            ->delete();
        
        return response()->json(['success' => true]);
    }
    
    // React to message
    public function reactToMessage(Request $request, $messageId)
    {
        $userId = Session::get('id');
        $emoji = $request->input('emoji');
        
        $message = Message::findOrFail($messageId);
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


// Inside MessageController.php

/**
 * Gets the unique key components for the pinned_messages table.
 * Ensures the order is always (lower_id, higher_id).
 */
protected function getPinKey($userId, $friendId)
{
    return [
        'user_1_id' => min($userId, $friendId),
        'user_2_id' => max($userId, $friendId),
    ];
}

// Inside MessageController.php

// Inside MessageController.php

public function pinMessage($friendId, $messageId)
{
    // Ensure all IDs are treated as integers for a strict comparison
    $userId = (int) Session::get('id');
    $friendId = (int) $friendId;
    $messageId = (int) $messageId;

    // 1. Validate Message Ownership (Safety Check)
    $message = Message::findOrFail($messageId);
    
    // Ensure message sender/receiver IDs are also integers from the database model
    $messageSenderId = (int) $message->sender_id;
    $messageReceiverId = (int) $message->receiver_id;
    
    // Check if the message belongs to the conversation between $userId and $friendId
    $isUserMessage = ($messageSenderId === $userId && $messageReceiverId === $friendId);
    $isFriendMessage = ($messageSenderId === $friendId && $messageReceiverId === $userId);
    
    if (!$isUserMessage && !$isFriendMessage) {
        return response()->json(['success' => false, 'message' => 'Message not in current chat.'], 403);
    }

    // ... rest of your controller code which should now execute ...
    $pinKey = $this->getPinKey($userId, $friendId);

    DB::table('pinned_messages')->updateOrInsert(
        $pinKey,
        ['message_id' => $messageId, 'updated_at' => now()]
    );
    
    return response()->json(['success' => true, 'message' => 'Message pinned successfully.']);
}

// Inside MessageController.php

// Inside MessageController.php

public function unpinMessage($friendId)
{
    // FIX: Cast IDs to ensure consistency with how pinMessage creates the pinKey
    $userId = (int) Session::get('id');
    $friendId = (int) $friendId; // Ensure the route parameter is an integer
    
    // NOTE: Your getPinKey function will rely on these being numbers
    $pinKey = $this->getPinKey($userId, $friendId);
    
    // Delete the record from the pinned_messages table
    $deleted = DB::table('pinned_messages')
        ->where($pinKey)
        ->delete();
    
    if ($deleted) {
        return response()->json(['success' => true, 'message' => 'Message unpinned successfully.']);
    }

    return response()->json(['success' => false, 'message' => 'No message was pinned.'], 404);
}


// In MessageController.php

// In MessageController.php

// In App\Http\Controllers\MessageController.php

public function archivedChats()
{
    $userId = Session::get('id');

    // 1. Check for login (good practice)
    if (!$userId) {
        return redirect('/login')->with('error', 'You must be logged in.');
    }
    
    // 2. Fetch the logged-in user (THE FIX)
    $user = UserRecord::find($userId);
    
    // 3. Fetch the archived friends
    $archivedFriends = DB::table('archived_chats')
        ->where('user_id', $userId)
        ->join('users_record', 'users_record.id', '=', 'archived_chats.friend_id') 
        ->select('users_record.*', 'archived_chats.created_at as archived_at')
        ->get();

    // 4. Pass both $archivedFriends AND $user
    return view('messages.archived', compact('archivedFriends', 'user'));
}

public function blockedUsers()
{
    $userId = Session::get('id');
     // 2. Fetch the logged-in user (THE FIX)
    $user = UserRecord::find($userId);
    
    $blockedPeople = DB::table('blocked_users')
        ->where('blocker_id', $userId)
        // JOIN on your confirmed table name: users_record
        ->join('users_record', 'users_record.id', '=', 'blocked_users.blocked_id')
        // SELECT columns from your confirmed table name
        ->select('users_record.*', 'blocked_users.created_at as blocked_at')
        ->get();

    return view('users.blocked', compact('blockedPeople','user'));
}

// Add at the top with other use statements

// ✅ NEW: Fetch Link Preview Method
public function fetchLinkPreview(Request $request)
{
    $url = $request->input('url');

    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return response()->json(['error' => 'Invalid URL'], 400);
    }

    // Handle own-site post URLs directly from DB (avoids auth + OG tag issues)
    $localPreview = $this->tryLocalPostPreview($url);
    if ($localPreview) {
        return response()->json($localPreview);
    }

    try {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            CURLOPT_HTTPHEADER => [
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language: en-US,en;q=0.5',
            ],
        ]);
        
        $html = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200 || !$html) {
            return response()->json(['error' => 'Failed to fetch URL'], 400);
        }
        
        $preview = $this->parseMetaTags($html, $url);
        
        return response()->json($preview);
        
    } catch (\Exception $e) {
        \Log::error('Link preview error: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to generate preview'], 500);
    }
}

private function tryLocalPostPreview($url)
{
    // Match /posts/{id} on any host (handles both localhost and production)
    $path = parse_url($url, PHP_URL_PATH);
    if (!preg_match('#^/posts/(\d+)$#', $path, $m)) {
        return null;
    }

    $postId = (int) $m[1];
    $post = \DB::table('sample_posts')
        ->where('id', $postId)
        ->whereNull('deleted_at')
        ->first();

    if (!$post) {
        return null;
    }

    // Get author
    $author = \DB::table('users_record')->where('id', $post->user_id)->first();
    $authorName = $author->name ?? $post->username ?? 'SupperAge';

    // Title: author name + first line of content
    $content = strip_tags($post->post_content ?? '');
    $title = $authorName . (strlen($content) ? ': ' . \Illuminate\Support\Str::limit($content, 80) : '\'s post');

    // Description
    $description = strlen($content) > 80 ? \Illuminate\Support\Str::limit($content, 200) : '';

    // Image: first file if it's an image
    $image = null;
    $files = json_decode($post->file_path, true);
    if (is_array($files) && count($files) > 0) {
        $first = $files[0];
        $ext = strtolower(pathinfo(parse_url($first, PHP_URL_PATH), PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $image = $first;
        }
    }

    $appUrl = config('app.url');

    return [
        'url'         => $url,
        'title'       => $title,
        'description' => $description,
        'image'       => $image,
        'site_name'   => 'SupperAge',
        'favicon'     => $appUrl . '/favicon.ico',
    ];
}

private function parseMetaTags($html, $url)
{
    $dom = new DOMDocument();
    @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
    $xpath = new DOMXPath($dom);
    
    $title = $this->getMetaContent($xpath, 'og:title') 
        ?? $this->getMetaContent($xpath, 'twitter:title')
        ?? $this->getTitleTag($dom);
    
    $description = $this->getMetaContent($xpath, 'og:description')
        ?? $this->getMetaContent($xpath, 'twitter:description')
        ?? $this->getMetaContent($xpath, 'description');
    
    $image = $this->getMetaContent($xpath, 'og:image')
        ?? $this->getMetaContent($xpath, 'twitter:image');
    
    if ($image && !filter_var($image, FILTER_VALIDATE_URL)) {
        $parsedUrl = parse_url($url);
        $base = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
        $image = $base . '/' . ltrim($image, '/');
    }
    
    $siteName = $this->getMetaContent($xpath, 'og:site_name') ?? parse_url($url, PHP_URL_HOST);
    
    return [
        'url' => $url,
        'title' => $title ? Str::limit($title, 100) : 'No title',
        'description' => $description ? Str::limit($description, 200) : '',
        'image' => $image,
        'site_name' => $siteName,
        'favicon' => $this->getFavicon($url)
    ];
}

private function getMetaContent($xpath, $property)
{
    $nodes = $xpath->query("//meta[@property='$property']/@content");
    if ($nodes->length > 0) {
        return $nodes->item(0)->nodeValue;
    }
    
    $nodes = $xpath->query("//meta[@name='$property']/@content");
    if ($nodes->length > 0) {
        return $nodes->item(0)->nodeValue;
    }
    
    return null;
}

private function getTitleTag($dom)
{
    $titles = $dom->getElementsByTagName('title');
    return $titles->length > 0 ? $titles->item(0)->nodeValue : null;
}

private function getFavicon($url)
{
    $parsedUrl = parse_url($url);
    $base = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
    return $base . '/favicon.ico';
}

public function transcribeVoice(Request $request)
{
    $userId = Session::get('id');
    if (!$userId) {
        return response()->json(['error' => 'Not logged in'], 401);
    }

    $request->validate([
        'voice_url' => 'required|string|url',
        'message_id' => 'required|integer',
    ]);

    $voiceUrl = $request->input('voice_url');
    $apiKey = config('services.assemblyai.api_key');

    if (!$apiKey) {
        return response()->json([
            'success' => false,
            'error' => 'Transcription service not configured. Please add ASSEMBLYAI_API_KEY to your .env file.'
        ]);
    }

    try {
        // Step 1: Submit transcription request to AssemblyAI with the Cloudinary URL directly
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://api.assemblyai.com/v2/transcript',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'audio_url' => $voiceUrl,
            ]),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . $apiKey,
                'Content-Type: application/json',
            ],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            \Log::error('AssemblyAI submit error', ['code' => $httpCode, 'response' => $response]);
            $errorData = json_decode($response, true);
            return response()->json([
                'success' => false,
                'error' => $errorData['error'] ?? 'Failed to start transcription'
            ]);
        }

        $result = json_decode($response, true);
        $transcriptId = $result['id'] ?? null;

        if (!$transcriptId) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to start transcription'
            ]);
        }

        // Step 2: Poll for completion (max 60 seconds)
        $maxAttempts = 30;
        $attempt = 0;
        $transcription = '';

        while ($attempt < $maxAttempts) {
            $attempt++;
            usleep(2000000); // Wait 2 seconds between polls

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => 'https://api.assemblyai.com/v2/transcript/' . $transcriptId,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER => [
                    'Authorization: ' . $apiKey,
                ],
            ]);

            $pollResponse = curl_exec($ch);
            $pollCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($pollCode !== 200) {
                continue;
            }

            $pollResult = json_decode($pollResponse, true);
            $status = $pollResult['status'] ?? '';

            if ($status === 'completed') {
                $transcription = $pollResult['text'] ?? '';
                break;
            } elseif ($status === 'error') {
                \Log::error('AssemblyAI transcription error', ['response' => $pollResult]);
                return response()->json([
                    'success' => false,
                    'error' => $pollResult['error'] ?? 'Transcription failed'
                ]);
            }
            // status is 'queued' or 'processing' — keep polling
        }

        if (empty($transcription)) {
            return response()->json([
                'success' => false,
                'error' => 'Could not transcribe this voice note'
            ]);
        }

        return response()->json([
            'success' => true,
            'transcription' => $transcription,
        ]);

    } catch (\Exception $e) {
        \Log::error('Transcription error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Transcription failed: ' . $e->getMessage()
        ]);
    }
}

}