<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Message;
use App\Models\UserRecord;
use App\Models\FriendRequest;
use App\Models\Notification;
use App\Services\PushNotificationService;

class ApiMessageController extends Controller
{
    // ── Conversations list (excludes archived) ─────────────────────────────────
    public function conversations(Request $request)
    {
        $userId = $request->user()->id;

        $archivedIds = DB::table('archived_chats')
            ->where('user_id', $userId)
            ->pluck('friend_id')
            ->toArray();

        // Users blocked by me OR who have blocked me — exclude from conversations
        $blockedIds = DB::table('blocked_users')
            ->where('blocker_id', $userId)
            ->pluck('blocked_id')
            ->merge(DB::table('blocked_users')->where('blocked_id', $userId)->pluck('blocker_id'))
            ->unique()
            ->toArray();

        $friendships = FriendRequest::where(function ($q) use ($userId) {
            $q->where('sender_id', $userId)->orWhere('receiver_id', $userId);
        })->where('status', 'accepted')->get();

        $friendIds = $friendships->map(function ($f) use ($userId) {
            return $f->sender_id == $userId ? $f->receiver_id : $f->sender_id;
        })->unique()->values()->reject(
            fn($id) => in_array($id, $archivedIds) || in_array($id, $blockedIds)
        )->values();

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

    // ── Messages in a conversation ─────────────────────────────────────────────
    public function messages(Request $request, $friendId)
    {
        $userId = $request->user()->id;
        $page   = $request->get('page', 1);

        // Mark messages from friend as read BEFORE fetching so response reflects updated state
        Message::where('sender_id', $friendId)->where('receiver_id', $userId)
            ->where('seen', 0)
            ->update(['seen' => 1, 'status' => 'read']);

        // Clear any unread DM message notifications from this friend
        DB::table('notifications')
            ->where('notification_reciever_id', (string) $userId)
            ->where('user_id', (int) $friendId)
            ->where('type', 'message')
            ->where('read_notification', 'no')
            ->update(['read_notification' => 'yes', 'read_at' => now(), 'updated_at' => now()]);

        $messages = Message::where(function ($q) use ($userId, $friendId) {
            $q->where('sender_id', $userId)->where('receiver_id', $friendId);
        })->orWhere(function ($q) use ($userId, $friendId) {
            $q->where('sender_id', $friendId)->where('receiver_id', $userId);
        })->orderBy('created_at', 'desc')->paginate(30, ['*'], 'page', $page);

        return response()->json([
            'messages'     => $messages->map(fn($m) => $this->formatMessage($m)),
            'total'        => $messages->total(),
            'current_page' => $messages->currentPage(),
            'last_page'    => $messages->lastPage(),
        ]);
    }

    // ── Send message ───────────────────────────────────────────────────────────
    public function send(Request $request, $friendId)
    {
        $validator = Validator::make($request->all(), [
            'message'        => 'nullable|string|max:5000',
            'file_path'      => 'nullable|string',
            'voice_note'     => 'nullable|string',
            'voice_duration' => 'nullable|integer',
            'reply_to_id'    => 'nullable|integer',
            'link_preview'   => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();

        $linkPreview = null;
        if ($request->has('link_preview') && is_array($request->link_preview)) {
            $linkPreview = json_encode($request->link_preview);
        }

        $message = Message::create([
            'sender_id'      => $user->id,
            'receiver_id'    => (int) $friendId,
            'message'        => $request->message ?? '',
            'file_path'      => $request->file_path,
            'voice_note'     => $request->voice_note,
            'voice_duration' => $request->voice_duration,
            'reply_to_id'    => $request->reply_to_id,
            'link_preview'   => $linkPreview,
            'seen'           => 0,
        ]);

        // ── Mention notifications (fire-and-forget — never fail the send) ────────
        try {
            $this->dispatchMentionNotificationsDM($request->message ?? '', $user, (int) $friendId, $message->id);
        } catch (\Throwable $e) { }

        // ── DM message notification ───────────────────────────────────────────
        try {
            $senderName = $user->name ?? $user->username ?? 'Someone';
            if ($request->voice_note) {
                $preview = '🎤 Voice note';
            } elseif ($request->file_path) {
                $preview = '📎 Attachment';
            } else {
                $preview = mb_substr($request->message ?? '', 0, 60);
            }
            $notifMsg = "{$senderName}: {$preview}";

            // Delete old unread notification → new ID so banner re-fires on each message
            DB::table('notifications')
                ->where('notification_reciever_id', (string) (int) $friendId)
                ->where('user_id', $user->id)
                ->where('type', 'message')
                ->where('read_notification', 'no')
                ->delete();

            $pushData = [
                'type'        => 'message',
                'context'     => 'dm',
                'friend_id'   => $user->id,
                'friend_name' => $senderName,
            ];

            DB::table('notifications')->insert([
                'user_id'                  => $user->id,
                'notification_reciever_id' => (string) (int) $friendId,
                'type'                     => 'message',
                'message'                  => $notifMsg,
                'notifiable_type'          => 'App\\Models\\UserRecord',
                'notifiable_id'            => (int) $friendId,
                'data'                     => json_encode($pushData),
                'read_notification' => 'no',
                'read_at'           => null,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            // Push notification — shows on lock screen even when app is closed
            PushNotificationService::sendToUser(
                (int) $friendId,
                $senderName,
                $preview,
                $pushData,
                'messages'  // high-priority Android channel
            );
        } catch (\Throwable $e) { }

        return response()->json(['message' => $this->formatMessage($message)], 201);
    }

    // ── Dispatch mention notifications for a DM ────────────────────────────────
    private function dispatchMentionNotificationsDM(string $text, UserRecord $sender, int $receiverId, int $messageId): void
    {
        if (!$text) return;
        preg_match_all('/@(\w+)/', $text, $matches);
        $usernames = array_unique($matches[1] ?? []);
        if (empty($usernames)) return;

        $senderName = $sender->name ?? $sender->username ?? 'Someone';

        foreach ($usernames as $username) {
            if (strtolower($username) === 'all') {
                $targetId = $receiverId;
            } else {
                $target = UserRecord::where('username', $username)->first();
                if (!$target || $target->id !== $receiverId) continue;
                $targetId = $target->id;
            }
            if ($targetId === $sender->id) continue;

            $notifMsg = "{$senderName} mentioned you in a message";
            DB::table('notifications')->insert([
                'user_id'                  => $sender->id,
                'notification_reciever_id' => (string) $targetId,
                'type'                     => 'mention',
                'message'                  => $notifMsg,
                'notifiable_type'          => 'App\\Models\\UserRecord',
                'notifiable_id'            => $targetId,
                'data'                     => json_encode([
                    'context'    => 'dm',
                    'friend_id'  => $sender->id,
                    'friend_name'=> $senderName,
                    'message_id' => $messageId,
                ]),
                'read_notification'        => 'no',
                'read_at'                  => null,
                'created_at'               => now(),
                'updated_at'               => now(),
            ]);
        }
    }

    // ── Mark read ──────────────────────────────────────────────────────────────
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

    // ── Edit message ───────────────────────────────────────────────────────────
    public function editMessage(Request $request, $id)
    {
        $user    = $request->user();
        $message = Message::find($id);

        if (!$message) return response()->json(['message' => 'Not found'], 404);
        if ((int)$message->sender_id !== (int)$user->id) return response()->json(['message' => 'Unauthorized'], 403);

        $request->validate(['message' => 'required|string|max:5000']);

        $message->update([
            'message'   => $request->message,
            'is_edited' => 1,
        ]);

        return response()->json(['message' => $this->formatMessage($message->fresh())]);
    }

    // ── Delete message ─────────────────────────────────────────────────────────
    public function deleteMessage(Request $request, $id)
    {
        $user    = $request->user();
        $message = Message::find($id);

        if (!$message) return response()->json(['message' => 'Not found'], 404);
        if ((int)$message->sender_id !== (int)$user->id) return response()->json(['message' => 'Unauthorized'], 403);

        // Soft-delete: mark as deleted so receiver still sees "message deleted" placeholder
        $message->update([
            'is_deleted_by_sender' => true,
            'message'              => '',
            'file_path'            => null,
            'voice_note'           => null,
        ]);

        return response()->json(['message' => $this->formatMessage($message->fresh())]);
    }

    // ── React to message ───────────────────────────────────────────────────────
    public function react(Request $request, $id)
    {
        $user    = $request->user();
        $request->validate(['emoji' => 'required|string|max:10']);

        $message = Message::find($id);
        if (!$message) return response()->json(['message' => 'Not found'], 404);

        $reactions = json_decode($message->reactions ?? '{}', true) ?? [];
        $emoji     = $request->emoji;
        $userId    = (string) $user->id;

        if (isset($reactions[$emoji]) && in_array($userId, $reactions[$emoji])) {
            $reactions[$emoji] = array_values(array_filter($reactions[$emoji], fn($id) => $id !== $userId));
            if (empty($reactions[$emoji])) unset($reactions[$emoji]);
        } else {
            foreach ($reactions as $e => $users) {
                $reactions[$e] = array_values(array_filter($users, fn($id) => $id !== $userId));
                if (empty($reactions[$e])) unset($reactions[$e]);
            }
            $reactions[$emoji][] = $userId;
        }

        $message->update(['reactions' => json_encode($reactions)]);

        return response()->json(['reactions' => $reactions]);
    }

    // ── Forward message ────────────────────────────────────────────────────────
    public function forwardMessage(Request $request, $messageId)
    {
        $user = $request->user();
        $request->validate(['friend_ids' => 'required|array|min:1']);

        $original = Message::findOrFail($messageId);

        foreach ($request->friend_ids as $friendId) {
            Message::create([
                'sender_id'    => $user->id,
                'receiver_id'  => (int) $friendId,
                'message'      => $original->message,
                'file_path'    => $original->file_path,
                'is_forwarded' => true,
                'seen'         => 0,
            ]);
        }

        return response()->json(['success' => true]);
    }

    // ── Typing indicator ───────────────────────────────────────────────────────
    public function updateTyping(Request $request, $friendId)
    {
        $userId = $request->user()->id;

        DB::table('typing_indicators')->updateOrInsert(
            ['user_id' => $userId, 'typing_to_user_id' => (int) $friendId],
            ['last_typed_at' => now(), 'updated_at' => now()]
        );

        return response()->json(['success' => true]);
    }

    public function checkTyping(Request $request, $friendId)
    {
        $userId    = $request->user()->id;
        $indicator = DB::table('typing_indicators')
            ->where('user_id', (int) $friendId)
            ->where('typing_to_user_id', $userId)
            ->first();

        $isTyping = false;
        if ($indicator && isset($indicator->last_typed_at)) {
            $isTyping = Carbon::parse($indicator->last_typed_at)->diffInSeconds(now()) <= 4;
        }

        return response()->json(['is_typing' => $isTyping]);
    }

    // ── Pin / Unpin message ────────────────────────────────────────────────────
    public function pinMessage(Request $request, $friendId, $messageId)
    {
        $userId  = (int) $request->user()->id;
        $fid     = (int) $friendId;
        $mid     = (int) $messageId;
        $message = Message::findOrFail($mid);

        $senderId    = (int) $message->sender_id;
        $receiverId  = (int) $message->receiver_id;
        $inChat      = ($senderId === $userId && $receiverId === $fid)
                    || ($senderId === $fid    && $receiverId === $userId);

        if (!$inChat) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $key = $this->pinKey($userId, $fid);
        DB::table('pinned_messages')->updateOrInsert(
            $key,
            ['message_id' => $mid, 'updated_at' => now()]
        );

        return response()->json(['success' => true]);
    }

    public function unpinMessage(Request $request, $friendId)
    {
        $userId = (int) $request->user()->id;
        $fid    = (int) $friendId;
        $key    = $this->pinKey($userId, $fid);

        DB::table('pinned_messages')->where($key)->delete();

        return response()->json(['success' => true]);
    }

    public function getPinnedMessage(Request $request, $friendId)
    {
        $userId = (int) $request->user()->id;
        $fid    = (int) $friendId;
        $key    = $this->pinKey($userId, $fid);

        $pin = DB::table('pinned_messages')->where($key)->first();

        if (!$pin) {
            return response()->json(['pinned_message' => null]);
        }

        $msg = Message::find($pin->message_id);

        return response()->json([
            'pinned_message' => $msg ? $this->formatMessage($msg) : null,
        ]);
    }

    // ── Archive / Unarchive ────────────────────────────────────────────────────
    public function archiveChat(Request $request, $friendId)
    {
        $userId = $request->user()->id;

        DB::table('archived_chats')->updateOrInsert(
            ['user_id' => $userId, 'friend_id' => (int) $friendId],
            ['created_at' => now(), 'updated_at' => now()]
        );

        return response()->json(['success' => true]);
    }

    public function unarchiveChat(Request $request, $friendId)
    {
        $userId = $request->user()->id;

        DB::table('archived_chats')
            ->where('user_id', $userId)
            ->where('friend_id', (int) $friendId)
            ->delete();

        return response()->json(['success' => true]);
    }

    public function archivedChats(Request $request)
    {
        $userId = $request->user()->id;

        $archivedFriendIds = DB::table('archived_chats')
            ->where('user_id', $userId)
            ->pluck('friend_id');

        $conversations = [];

        foreach ($archivedFriendIds as $friendId) {
            $lastMsg = Message::where(function ($q) use ($userId, $friendId) {
                $q->where('sender_id', $userId)->where('receiver_id', $friendId);
            })->orWhere(function ($q) use ($userId, $friendId) {
                $q->where('sender_id', $friendId)->where('receiver_id', $userId);
            })->orderBy('created_at', 'desc')->first();

            $friend = UserRecord::find($friendId);

            if ($friend) {
                $conversations[] = [
                    'friend'       => $this->formatUser($friend),
                    'last_message' => $lastMsg ? $this->formatMessage($lastMsg) : null,
                    'unread_count' => 0,
                    'last_at'      => $lastMsg ? $lastMsg->created_at : null,
                ];
            }
        }

        return response()->json(['conversations' => $conversations]);
    }

    // ── Block / Unblock / Report ───────────────────────────────────────────────
    public function block(Request $request)
    {
        $request->validate(['user_id' => 'required|integer']);
        $authId   = $request->user()->id;
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

    public function blockedUsers(Request $request)
    {
        $userId     = $request->user()->id;
        $blockedIds = DB::table('blocked_users')
            ->where('blocker_id', $userId)
            ->pluck('blocked_id');

        $users = UserRecord::whereIn('id', $blockedIds)->get();

        return response()->json(['users' => $users->map(fn($u) => $this->formatUser($u))]);
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

    // ── Link preview ──────────────────────────────────────────────────────────
    public function fetchLinkPreview(Request $request)
    {
        $url = $request->input('url');
        if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
            return response()->json(['error' => 'Invalid URL'], 422);
        }

        // Use cURL for better compatibility (works even when allow_url_fopen is off)
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 5,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_ENCODING       => '',   // auto-decompresses gzip/deflate/br responses
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4,
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
            CURLOPT_HTTPHEADER     => [
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language: en-US,en;q=0.9',
                'Cache-Control: no-cache',
            ],
        ]);
        $html     = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL) ?: $url;
        curl_close($ch);

        if (!$html || $httpCode >= 400) {
            return response()->json(['url' => $url, 'title' => '', 'description' => '', 'image' => '']);
        }

        $title = $description = $image = '';

        // Normalise whitespace so multi-line meta tags are handled as a single line
        $flat = preg_replace('/\s+/', ' ', $html);

        // Helper: extract meta content regardless of attribute order, works on the flattened HTML
        $getMeta = function (string $key, string $attr = 'property') use ($flat): string {
            $k = preg_quote($key, '/');
            // attr before content
            if (preg_match(
                '/<meta\s[^>]*' . $attr . '\s*=\s*["\']' . $k . '["\'][^>]*content\s*=\s*["\']([^"\']+)["\'][^>]*\/?>/i',
                $flat, $m
            )) return $m[1];
            // content before attr
            if (preg_match(
                '/<meta\s[^>]*content\s*=\s*["\']([^"\']+)["\'][^>]*' . $attr . '\s*=\s*["\']' . $k . '["\'][^>]*\/?>/i',
                $flat, $m
            )) return $m[1];
            return '';
        };

        // og:title → fallback to <title>
        $title = $getMeta('og:title');
        if ($title === '') {
            if (preg_match('/<title[^>]*>([^<]+)<\/title>/i', $flat, $m))
                $title = $m[1];
        }

        // og:description → fallback to name=description
        $description = $getMeta('og:description');
        if ($description === '')
            $description = $getMeta('description', 'name');

        // og:image → og:image:secure_url → twitter:image fallbacks
        $image = $getMeta('og:image');
        if ($image === '') $image = $getMeta('og:image:secure_url');
        if ($image === '') $image = $getMeta('twitter:image');
        if ($image === '') $image = $getMeta('twitter:image:src');

        // Decode HTML entities in ALL fields (important: image URLs often contain &amp; etc.)
        $cleanTitle = trim(html_entity_decode($title,       ENT_QUOTES | ENT_HTML5));
        $cleanDesc  = trim(html_entity_decode($description, ENT_QUOTES | ENT_HTML5));
        $cleanImage = trim(html_entity_decode($image,       ENT_QUOTES | ENT_HTML5));

        // Resolve relative image URLs to absolute
        if ($cleanImage && !preg_match('/^https?:\/\//i', $cleanImage)) {
            $parts      = parse_url($finalUrl);
            $base       = ($parts['scheme'] ?? 'https') . '://' . ($parts['host'] ?? '');
            $cleanImage = $base . '/' . ltrim($cleanImage, '/');
        }

        // If the fetched page is a login/error wall, return empty so no misleading preview is shown
        $loginPatterns = ['log in', 'log into', 'sign in', 'sign up', 'create an account', 'access denied', '403 forbidden', '404 not found'];
        $titleLower = strtolower($cleanTitle);
        foreach ($loginPatterns as $pat) {
            if (str_contains($titleLower, $pat)) {
                return response()->json(['url' => $url, 'title' => '', 'description' => '', 'image' => '']);
            }
        }

        return response()->json([
            'url'         => $url,
            'title'       => $cleanTitle,
            'description' => $cleanDesc,
            'image'       => $cleanImage,
        ]);
    }

    // ── Image upload ───────────────────────────────────────────────────────────
    public function uploadImage(Request $request)
    {
        $request->validate(['image' => 'required|image|max:10240']);

        $dir = public_path('uploads/messages');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $file     = $request->file('image');
        $filename = 'msg_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $filename);
        $url = url('uploads/messages/' . $filename);

        return response()->json(['url' => $url]);
    }

    // ── Voice note upload ──────────────────────────────────────────────────────
    public function uploadVoice(Request $request)
    {
        $request->validate(['voice' => 'required|file|max:20480']);

        $dir = public_path('uploads/messages');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $file     = $request->file('voice');
        $ext      = $file->getClientOriginalExtension() ?: 'm4a';
        $filename = 'voice_' . time() . '_' . uniqid() . '.' . $ext;
        $file->move($dir, $filename);
        $url = url('uploads/messages/' . $filename);

        return response()->json(['url' => $url]);
    }

    // ── Document / file upload ────────────────────────────────────────────────
    public function uploadFile(Request $request)
    {
        $request->validate(['file' => 'required|file|max:51200']); // 50 MB

        $dir = public_path('uploads/messages');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $file     = $request->file('file');
        $ext      = $file->getClientOriginalExtension() ?: 'bin';
        $origName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $origName);
        $filename = 'file_' . time() . '_' . $safeName . '.' . $ext;
        $file->move($dir, $filename);
        $url = url('uploads/messages/' . $filename);

        return response()->json([
            'url'      => $url,
            'filename' => $file->getClientOriginalName(),
        ]);
    }

    // ── Voice note transcription ───────────────────────────────────────────────
    public function transcribeVoice(Request $request)
    {
        $request->validate(['voice_url' => 'required|string|url']);

        $apiKey = config('services.assemblyai.api_key');
        if (!$apiKey) {
            return response()->json(['success' => false, 'error' => 'Transcription not configured'], 503);
        }

        $voiceUrl = $request->input('voice_url');

        // Submit to AssemblyAI
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => 'https://api.assemblyai.com/v2/transcript',
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode(['audio_url' => $voiceUrl]),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER     => ['Authorization: ' . $apiKey, 'Content-Type: application/json'],
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return response()->json(['success' => false, 'error' => 'Failed to start transcription'], 500);
        }

        $transcriptId = json_decode($response, true)['id'] ?? null;
        if (!$transcriptId) {
            return response()->json(['success' => false, 'error' => 'No transcript ID returned'], 500);
        }

        // Poll for result (max 60s)
        for ($i = 0; $i < 30; $i++) {
            usleep(2000000);
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL            => 'https://api.assemblyai.com/v2/transcript/' . $transcriptId,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 15,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER     => ['Authorization: ' . $apiKey],
            ]);
            $poll = json_decode(curl_exec($ch), true);
            curl_close($ch);

            if (($poll['status'] ?? '') === 'completed') {
                return response()->json(['success' => true, 'transcription' => $poll['text'] ?? '']);
            }
            if (($poll['status'] ?? '') === 'error') {
                return response()->json(['success' => false, 'error' => $poll['error'] ?? 'Transcription failed'], 500);
            }
        }

        return response()->json(['success' => false, 'error' => 'Transcription timed out'], 504);
    }

    // ── Private helpers ────────────────────────────────────────────────────────
    private function pinKey(int $userId, int $friendId): array
    {
        $ids = [$userId, $friendId];
        sort($ids);
        return ['user_1_id' => $ids[0], 'user_2_id' => $ids[1]];
    }

    private function formatMessage(Message $msg): array
    {
        $replyTo = null;
        if ($msg->reply_to_id) {
            $parent = Message::find($msg->reply_to_id);
            if ($parent) {
                $replyTo = [
                    'id'          => $parent->id,
                    'sender_id'   => $parent->sender_id,
                    'receiver_id' => $parent->receiver_id,
                    'message'     => $parent->message,
                    'file_path'   => $parent->file_path,
                    'voice_note'  => $parent->voice_note,
                    'created_at'  => $parent->created_at,
                ];
            }
        }

        $reactions = [];
        if ($msg->reactions) {
            $decoded   = json_decode($msg->reactions, true);
            $reactions = is_array($decoded) ? $decoded : [];
        }

        $linkPreview = null;
        if (!empty($msg->link_preview)) {
            $lp = json_decode($msg->link_preview, true);
            $linkPreview = is_array($lp) ? $lp : null;
        }

        return [
            'id'             => $msg->id,
            'sender_id'      => (int) $msg->sender_id,
            'receiver_id'    => (int) $msg->receiver_id,
            'message'        => $msg->message,
            'file_path'      => $msg->file_path,
            'voice_note'     => $msg->voice_note,
            'voice_duration' => $msg->voice_duration,
            'reply_to_id'    => $msg->reply_to_id,
            'reply_to'     => $replyTo,
            'reactions'    => $reactions,
            'link_preview' => $linkPreview,
            'is_forwarded' => (bool) ($msg->is_forwarded ?? false),
            'is_deleted'   => (bool) ($msg->is_deleted_by_sender ?? false),
            'seen'         => (bool) $msg->seen || $msg->status === 'read',
            'is_edited'    => (bool) ($msg->is_edited ?? false),
            'created_at'   => $msg->created_at,
        ];
    }

    private function formatUser(UserRecord $user): array
    {
        return [
            'id'           => $user->id,
            'name'         => $user->name,
            'username'     => $user->username,
            'profileimg'   => $user->profileimg
                ? (filter_var($user->profileimg, FILTER_VALIDATE_URL)
                    ? $user->profileimg
                    : url($user->profileimg))
                : null,
            'badge_status' => $user->badge_status,
            'is_online'    => $user->is_online,
            'last_seen'    => $user->last_seen,
        ];
    }
}
