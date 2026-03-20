<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\GroupMessage;
use App\Models\GroupCall;
use App\Models\GroupCallParticipant;
use App\Helpers\AgoraTokenBuilder;
use App\Models\UserRecord;
use App\Models\Notification;
use App\Services\PushNotificationService;

class ApiGroupController extends Controller
{
    public function index(Request $request)
    {
        $userId   = $request->user()->id;
        $groupIds = GroupMember::where('user_id', $userId)->pluck('group_id');

        $groups = Group::with(['members.user'])
            ->whereIn('id', $groupIds)
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json([
            'groups' => $groups->map(fn($g) => $this->formatGroup($g, $userId)),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string|max:1000',
            'profile_image' => 'nullable|string',
            'privacy'       => 'nullable|in:public,private',
            'member_ids'    => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user  = $request->user();
        $group = Group::create([
            'name'          => $request->name,
            'description'   => $request->description,
            'group_image'   => $request->profile_image,
            'created_by'    => $user->id,
            'privacy'       => $request->privacy ?? 'private',
        ]);

        GroupMember::create(['group_id' => $group->id, 'user_id' => $user->id, 'role' => 'admin']);

        if ($request->member_ids) {
            foreach ($request->member_ids as $memberId) {
                if ($memberId != $user->id) {
                    GroupMember::create(['group_id' => $group->id, 'user_id' => $memberId, 'role' => 'member']);
                }
            }
        }

        $group->load('members.user');

        return response()->json(['group' => $this->formatGroup($group, $user->id)], 201);
    }

    public function show(Request $request, $id)
    {
        $userId   = $request->user()->id;
        $group    = Group::with(['members.user'])->find($id);

        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }

        if (!GroupMember::where('group_id', $id)->where('user_id', $userId)->exists()) {
            return response()->json(['message' => 'Not a member'], 403);
        }

        return response()->json(['group' => $this->formatGroup($group, $userId)]);
    }

    public function messages(Request $request, $id)
    {
        $userId = $request->user()->id;
        $page   = $request->get('page', 1);

        if (!GroupMember::where('group_id', $id)->where('user_id', $userId)->exists()) {
            return response()->json(['message' => 'Not a member'], 403);
        }

        $messages = GroupMessage::with(['sender', 'replyTo.sender'])
            ->where('group_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(50, ['*'], 'page', $page);

        // Auto-mark as read whenever messages are fetched — no separate API call needed
        try {
            $latestMsgId = GroupMessage::where('group_id', $id)->max('id') ?? 0;
            $now         = now();
            DB::table('group_message_reads')->updateOrInsert(
                ['group_id' => (int) $id, 'user_id' => $userId],
                ['message_id' => $latestMsgId, 'last_read_at' => $now, 'updated_at' => $now, 'created_at' => $now]
            );
        } catch (\Throwable $e) { /* never fail the fetch */ }

        // Clear group message notifications for this user in this group
        try {
            DB::table('notifications')
                ->where('notification_reciever_id', (string) $userId)
                ->where('type', 'message')
                ->where('notifiable_type', 'App\\Models\\Group')
                ->where('notifiable_id', (int) $id)
                ->where('read_notification', 'no')
                ->update(['read_notification' => 'yes', 'read_at' => now(), 'updated_at' => now()]);
        } catch (\Throwable $e) { }

        return response()->json([
            'messages'    => $messages->map(fn($m) => $this->formatGroupMessage($m)),
            'total'       => $messages->total(),
            'current_page'=> $messages->currentPage(),
            'last_page'   => $messages->lastPage(),
        ]);
    }

    public function markRead(Request $request, $id)
    {
        $userId      = $request->user()->id;
        $now         = now();
        $latestMsgId = GroupMessage::where('group_id', $id)->max('id') ?? 0;

        DB::table('group_message_reads')->updateOrInsert(
            ['group_id' => (int) $id, 'user_id' => $userId],
            ['message_id' => $latestMsgId, 'last_read_at' => $now, 'updated_at' => $now, 'created_at' => $now]
        );

        return response()->json(['message' => 'Marked as read']);
    }

    public function sendMessage(Request $request, $id)
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

        if (!GroupMember::where('group_id', $id)->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Not a member'], 403);
        }

        $message = GroupMessage::create([
            'group_id'       => $id,
            'sender_id'      => $user->id,
            'message'        => $request->message,
            'file_path'      => $request->file_path,
            'voice_note'     => $request->voice_note,
            'voice_duration' => $request->voice_duration,
            'reply_to_id'    => $request->reply_to_id,
            'link_preview'   => $request->link_preview ? json_encode($request->link_preview) : null,
            'status'         => 'sent',
        ]);

        $message->load(['sender', 'replyTo.sender']);
        Group::where('id', $id)->touch();

        // ── Mention notifications (fire-and-forget — never fail the send) ────────
        try {
            $this->dispatchGroupMentionNotifications($request->message ?? '', $user, (int) $id, $message->id);
        } catch (\Throwable $e) {
            // Silently ignore notification errors
        }

        // ── Group message notifications ───────────────────────────────────────
        try {
            $this->dispatchGroupMessageNotifications($user, (int) $id, $message);
        } catch (\Throwable $e) { }

        return response()->json(['message' => $this->formatGroupMessage($message)], 201);
    }

    // ── Dispatch message notifications to all group members ───────────────────
    private function dispatchGroupMessageNotifications(UserRecord $sender, int $groupId, GroupMessage $message): void
    {
        $group      = Group::find($groupId);
        $senderName = $sender->name ?? $sender->username ?? 'Someone';
        $groupName  = $group?->name ?? 'Group';

        if ($message->voice_note) {
            $preview = '🎤 Voice note';
        } elseif ($message->file_path) {
            $preview = '📎 Attachment';
        } else {
            $preview = mb_substr($message->message ?? '', 0, 60);
        }
        $notifMsg = "{$senderName}: {$preview}";

        $memberIds = GroupMember::where('group_id', $groupId)
            ->where('user_id', '!=', $sender->id)
            ->pluck('user_id')
            ->toArray();

        if (empty($memberIds)) return;

        // Delete old unread group notifications → new IDs so banner re-fires on each message
        DB::table('notifications')
            ->whereIn('notification_reciever_id', array_map('strval', $memberIds))
            ->where('type', 'message')
            ->where('notifiable_type', 'App\\Models\\Group')
            ->where('notifiable_id', $groupId)
            ->where('read_notification', 'no')
            ->delete();

        $now  = now();
        $data = json_encode([
            'context'    => 'group',
            'group_id'   => $groupId,
            'group_name' => $groupName,
        ]);

        $notifs = [];
        foreach ($memberIds as $memberId) {
            $notifs[] = [
                'user_id'                  => $sender->id,
                'notification_reciever_id' => (string) $memberId,
                'type'                     => 'message',
                'message'                  => $notifMsg,
                'notifiable_type'          => 'App\\Models\\Group',
                'notifiable_id'            => $groupId,
                'data'                     => $data,
                'read_notification'        => 'no',
                'read_at'                  => null,
                'created_at'               => $now,
                'updated_at'               => $now,
            ];
        }

        foreach (array_chunk($notifs, 200) as $chunk) {
            DB::table('notifications')->insert($chunk);
        }

        // Push notification to all group members — shows on lock screen even when app is closed
        PushNotificationService::sendToUsers(
            $memberIds,
            $groupName,
            $notifMsg,
            [
                'type'       => 'message',
                'context'    => 'group',
                'group_id'   => $groupId,
                'group_name' => $groupName,
            ],
            'messages'  // high-priority Android channel
        );
    }

    // ── Dispatch @mention notifications for a group message ────────────────────
    private function dispatchGroupMentionNotifications(string $text, UserRecord $sender, int $groupId, int $messageId): void
    {
        if (!$text) return;
        preg_match_all('/@(\w+)/', $text, $matches);
        $usernames = array_unique($matches[1] ?? []);
        if (empty($usernames)) return;

        $group      = Group::find($groupId);
        $senderName = $sender->name ?? $sender->username ?? 'Someone';
        $groupName  = $group?->name ?? 'a group';

        $memberIds = GroupMember::where('group_id', $groupId)
            ->where('user_id', '!=', $sender->id)
            ->pluck('user_id')
            ->toArray();

        $notifyIds = [];

        foreach ($usernames as $username) {
            if (strtolower($username) === 'all') {
                $notifyIds = array_merge($notifyIds, $memberIds);
            } else {
                $target = UserRecord::where('username', $username)->first();
                if ($target && in_array($target->id, $memberIds)) {
                    $notifyIds[] = $target->id;
                }
            }
        }

        foreach (array_unique($notifyIds) as $receiverId) {
            $msg = "{$senderName} mentioned you in {$groupName}";
            DB::table('notifications')->insert([
                'user_id'                  => $sender->id,
                'notification_reciever_id' => (string) $receiverId,
                'type'                     => 'mention',
                'message'                  => $msg,
                'notifiable_type'          => 'App\\Models\\UserRecord',
                'notifiable_id'            => $receiverId,
                'data'                     => json_encode([
                    'context'    => 'group',
                    'group_id'   => $groupId,
                    'group_name' => $groupName,
                    'message_id' => $messageId,
                ]),
                'read_notification'        => 'no',
                'read_at'                  => null,
                'created_at'               => now(),
                'updated_at'               => now(),
            ]);
        }
    }

    public function addMember(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userId     = $request->user()->id;
        $memberRole = GroupMember::where('group_id', $id)->where('user_id', $userId)->first();

        if (!$memberRole || $memberRole->role !== 'admin') {
            return response()->json(['message' => 'Only admins can add members'], 403);
        }

        $exists = GroupMember::where('group_id', $id)->where('user_id', $request->user_id)->exists();
        if (!$exists) {
            GroupMember::create(['group_id' => $id, 'user_id' => $request->user_id, 'role' => 'member']);
        }

        return response()->json(['message' => 'Member added']);
    }

    public function removeMember(Request $request, $id, $uid)
    {
        $userId     = $request->user()->id;
        $memberRole = GroupMember::where('group_id', $id)->where('user_id', $userId)->first();

        if (!$memberRole || $memberRole->role !== 'admin') {
            return response()->json(['message' => 'Only admins can remove members'], 403);
        }

        GroupMember::where('group_id', $id)->where('user_id', $uid)->delete();

        return response()->json(['message' => 'Member removed']);
    }

    public function makeAdmin(Request $request, $id, $uid)
    {
        $userId     = $request->user()->id;
        $memberRole = GroupMember::where('group_id', $id)->where('user_id', $userId)->first();

        if (!$memberRole || $memberRole->role !== 'admin') {
            return response()->json(['message' => 'Only admins can promote members'], 403);
        }

        GroupMember::where('group_id', $id)->where('user_id', $uid)->update(['role' => 'admin']);

        return response()->json(['message' => 'Member promoted to admin']);
    }

    public function update(Request $request, $id)
    {
        $userId = $request->user()->id;
        $group  = Group::find($id);

        if (!$group) return response()->json(['message' => 'Group not found'], 404);

        $member = GroupMember::where('group_id', $id)->where('user_id', $userId)->first();
        if (!$member || $member->role !== 'admin') {
            return response()->json(['message' => 'Only admins can edit the group'], 403);
        }

        $request->validate([
            'name'        => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'privacy'     => 'nullable|in:public,private',
        ]);

        $fields = [];
        if ($request->has('name'))        $fields['name']        = $request->name;
        if ($request->has('description')) $fields['description'] = $request->description;
        if ($request->has('privacy'))     $fields['privacy']     = $request->privacy;
        if (!empty($fields)) $group->update($fields);

        $group->load('members.user');
        return response()->json(['group' => $this->formatGroup($group, $userId)]);
    }

    public function leave(Request $request, $id)
    {
        $userId = $request->user()->id;
        $member = GroupMember::where('group_id', $id)->where('user_id', $userId)->first();

        if (!$member) return response()->json(['message' => 'Not a member'], 403);

        // If last admin, transfer admin to another member first
        if ($member->role === 'admin') {
            $otherAdmin = GroupMember::where('group_id', $id)->where('user_id', '!=', $userId)->where('role', 'admin')->first();
            if (!$otherAdmin) {
                $nextMember = GroupMember::where('group_id', $id)->where('user_id', '!=', $userId)->first();
                if ($nextMember) $nextMember->update(['role' => 'admin']);
            }
        }

        $member->delete();

        // Delete group if empty
        if (!GroupMember::where('group_id', $id)->exists()) {
            Group::destroy($id);
        }

        return response()->json(['message' => 'Left group']);
    }

    public function destroy(Request $request, $id)
    {
        $userId = $request->user()->id;
        $group  = Group::find($id);

        if (!$group) return response()->json(['message' => 'Group not found'], 404);

        $member = GroupMember::where('group_id', $id)->where('user_id', $userId)->first();
        if (!$member || $member->role !== 'admin') {
            return response()->json(['message' => 'Only admins can delete the group'], 403);
        }

        GroupMember::where('group_id', $id)->delete();
        GroupMessage::where('group_id', $id)->delete();
        $group->delete();

        return response()->json(['message' => 'Group deleted']);
    }

    public function report(Request $request, $id)
    {
        $group = Group::find($id);
        if (!$group) return response()->json(['message' => 'Group not found'], 404);

        \DB::table('group_reports')->insert([
            'group_id'    => $id,
            'reporter_id' => $request->user()->id,
            'reason'      => $request->reason ?? '',
            'details'     => $request->details ?? null,
            'status'      => 'pending',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return response()->json(['message' => 'Report submitted']);
    }

    public function publicGroups(Request $request)
    {
        $userId = $request->user()->id;

        // Groups that are public and the user is NOT a member of
        $joinedIds = GroupMember::where('user_id', $userId)->pluck('group_id');

        $groups = Group::with(['members.user'])
            ->where('privacy', 'public')
            ->whereNotIn('id', $joinedIds)
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json([
            'groups' => $groups->map(fn($g) => array_merge($this->formatGroup($g, $userId), ['is_member' => false])),
        ]);
    }

    public function joinGroup(Request $request, $id)
    {
        $userId = $request->user()->id;
        $group  = Group::find($id);

        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }

        if ($group->privacy !== 'public') {
            return response()->json(['message' => 'This group is private'], 403);
        }

        $already = GroupMember::where('group_id', $id)->where('user_id', $userId)->exists();
        if ($already) {
            return response()->json(['message' => 'Already a member'], 409);
        }

        GroupMember::create(['group_id' => $id, 'user_id' => $userId, 'role' => 'member']);

        $group->load('members.user');
        return response()->json(['message' => 'Joined group', 'group' => $this->formatGroup($group, $userId)]);
    }

    private function formatGroup(Group $group, int $userId): array
    {
        $lastMsg = GroupMessage::where('group_id', $group->id)->orderBy('created_at', 'desc')->first();

        // Count messages sent by others with ID greater than the last read message ID
        $lastReadMsgId = (int) (DB::table('group_message_reads')
            ->where('group_id', $group->id)
            ->where('user_id', $userId)
            ->value('message_id') ?? 0);

        $unreadCount = GroupMessage::where('group_id', $group->id)
            ->where('sender_id', '!=', $userId)
            ->where('id', '>', $lastReadMsgId)
            ->count();

        return [
            'id'            => $group->id,
            'name'          => $group->name,
            'description'   => $group->description,
            'icon'          => $group->group_image ? (filter_var($group->group_image, FILTER_VALIDATE_URL) ? $group->group_image : url($group->group_image)) : null,
            'privacy'       => $group->privacy ?? 'private',
            'created_by'    => $group->created_by,
            'creator_name'  => optional(UserRecord::find($group->created_by))->name,
            'members'       => $group->members->map(fn($m) => [
                'id'   => $m->user_id,
                'role' => $m->role,
                'user' => $m->user ? [
                    'id'         => $m->user->id,
                    'name'       => $m->user->name,
                    'username'   => $m->user->username,
                    'profileimg' => $m->user->profileimg ? (filter_var($m->user->profileimg, FILTER_VALIDATE_URL) ? $m->user->profileimg : url($m->user->profileimg)) : null,
                ] : null,
            ]),
            'my_role'       => $group->members->firstWhere('user_id', $userId)?->role ?? 'member',
            'last_message'  => $lastMsg ? ['id' => $lastMsg->id, 'message' => $lastMsg->message, 'sender_id' => $lastMsg->sender_id, 'created_at' => $lastMsg->created_at] : null,
            'unread_count'  => $unreadCount,
            'created_at'    => $group->created_at,
            'updated_at'    => $group->updated_at,
        ];
    }

    private function formatGroupMessage(GroupMessage $msg): array
    {
        $reactions = [];
        if ($msg->reactions) {
            $decoded = json_decode($msg->reactions, true);
            if (is_array($decoded)) $reactions = $decoded;
        }

        return [
            'id'             => $msg->id,
            'group_id'       => $msg->group_id,
            'sender_id'      => $msg->sender_id,
            'message'        => $msg->is_deleted ? null : $msg->message,
            'file_path'      => $msg->is_deleted ? null : (function() use ($msg) {
                if (!$msg->file_path) return null;
                $decoded = json_decode($msg->file_path, true);
                if (is_array($decoded) && count($decoded) > 0) {
                    $url = $decoded[0];
                    return filter_var($url, FILTER_VALIDATE_URL) ? $url : url('/' . ltrim($url, '/'));
                }
                return $msg->file_path;
            })(),
            'voice_note'     => $msg->is_deleted ? null : $msg->voice_note,
            'voice_duration' => $msg->voice_duration,
            'reply_to_id'    => $msg->reply_to_id,
            'reply_to'       => $msg->replyTo ? [
                'id'      => $msg->replyTo->id,
                'message' => $msg->replyTo->message,
                'file_path'=> $msg->replyTo->file_path,
                'sender'  => $msg->replyTo->sender ? ['name' => $msg->replyTo->sender->name] : null,
            ] : null,
            'reactions'      => $reactions,
            'link_preview'   => $msg->link_preview ? (is_array($msg->link_preview) ? $msg->link_preview : json_decode($msg->link_preview, true)) : null,
            'is_edited'      => (bool) $msg->is_edited,
            'is_deleted'     => (bool) $msg->is_deleted,
            'created_at'     => $msg->created_at,
            'sender'         => $msg->sender ? [
                'id'         => $msg->sender->id,
                'name'       => $msg->sender->name,
                'username'   => $msg->sender->username,
                'profileimg' => $msg->sender->profileimg ? (filter_var($msg->sender->profileimg, FILTER_VALIDATE_URL) ? $msg->sender->profileimg : url($msg->sender->profileimg)) : null,
            ] : null,
        ];
    }

    // ── Group Calls ──────────────────────────────────────────────────────────

    public function initiateGroupCall(Request $request, $id)
    {
        $user    = $request->user();
        $request->validate(['call_type' => 'required|in:audio,video']);

        $group = Group::findOrFail($id);
        if (!GroupMember::where('group_id', $id)->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Not a member'], 403);
        }

        // Return existing active call if any
        $existing = GroupCall::where('group_id', $id)
            ->whereIn('status', ['ringing', 'ongoing'])
            ->first();
        if ($existing) {
            return response()->json([
                'call_id'       => $existing->id,
                'agora_channel' => 'group_call_' . $existing->id,
                'call_type'     => $existing->call_type,
            ]);
        }

        $call = GroupCall::create([
            'group_id'     => $id,
            'initiated_by' => $user->id,
            'call_type'    => $request->call_type,
            'status'       => 'ringing',
            'started_at'   => now(),
        ]);

        // Add all members as participants
        $members = GroupMember::where('group_id', $id)->get();
        foreach ($members as $m) {
            GroupCallParticipant::create([
                'call_id'   => $call->id,
                'user_id'   => $m->user_id,
                'status'    => $m->user_id == $user->id ? 'joined' : 'ringing',
                'joined_at' => $m->user_id == $user->id ? now() : null,
            ]);
        }

        return response()->json([
            'call_id'       => $call->id,
            'agora_channel' => 'group_call_' . $call->id,
            'call_type'     => $call->call_type,
        ], 201);
    }

    public function activeGroupCall(Request $request, $id)
    {
        $userId = $request->user()->id;
        if (!GroupMember::where('group_id', $id)->where('user_id', $userId)->exists()) {
            return response()->json(['active' => null]);
        }
        $call = GroupCall::where('group_id', $id)
            ->whereIn('status', ['ringing', 'ongoing'])
            ->first();
        if (!$call) return response()->json(['active' => null]);
        return response()->json([
            'active' => [
                'call_id'       => $call->id,
                'agora_channel' => 'group_call_' . $call->id,
                'call_type'     => $call->call_type,
                'status'        => $call->status,
                'initiated_by'  => $call->initiated_by,
            ],
        ]);
    }

    public function joinGroupCall(Request $request, $callId)
    {
        $userId = $request->user()->id;
        $call   = GroupCall::findOrFail($callId);
        $call->update(['status' => 'ongoing']);
        GroupCallParticipant::where('call_id', $callId)->where('user_id', $userId)
            ->update(['status' => 'joined', 'joined_at' => now()]);
        return response()->json(['message' => 'Joined']);
    }

    public function leaveGroupCall(Request $request, $callId)
    {
        $userId = $request->user()->id;
        GroupCallParticipant::where('call_id', $callId)->where('user_id', $userId)
            ->update(['status' => 'left', 'left_at' => now()]);
        $active = GroupCallParticipant::where('call_id', $callId)->where('status', 'joined')->count();
        if ($active === 0) {
            $call = GroupCall::find($callId);
            if ($call) {
                $call->update(['status' => 'ended', 'ended_at' => now(),
                    'duration' => now()->diffInSeconds($call->started_at)]);
            }
        }
        return response()->json(['message' => 'Left']);
    }

    public function endGroupCall(Request $request, $callId)
    {
        $userId = $request->user()->id;
        $call   = GroupCall::findOrFail($callId);
        if ($call->initiated_by != $userId) {
            return response()->json(['message' => 'Only initiator can end the call'], 403);
        }
        $call->update(['status' => 'ended', 'ended_at' => now(),
            'duration' => now()->diffInSeconds($call->started_at)]);
        GroupCallParticipant::where('call_id', $callId)->where('status', 'joined')
            ->update(['status' => 'left', 'left_at' => now()]);
        return response()->json(['message' => 'Call ended']);
    }

    public function pollGroupCall(Request $request, $callId)
    {
        $call = GroupCall::with('participants.user')->find($callId);
        if (!$call) return response()->json(['status' => 'not_found'], 404);
        return response()->json([
            'status'       => $call->status,
            'participants' => $call->participants->where('status', 'joined')->map(fn($p) => [
                'user_id' => $p->user_id,
                'name'    => $p->user?->name,
            ])->values(),
        ]);
    }

    public function pollIncomingGroupCall(Request $request)
    {
        $userId = $request->user()->id;

        $participant = GroupCallParticipant::with(['call.group', 'call.initiator'])
            ->where('user_id', $userId)
            ->where('status', 'ringing')
            ->whereHas('call', fn($q) => $q->whereIn('status', ['ringing', 'ongoing']))
            ->latest()
            ->first();

        if (!$participant) {
            return response()->json(['incoming' => null]);
        }

        $call  = $participant->call;
        $group = $call->group;

        return response()->json([
            'incoming' => [
                'call_id'        => $call->id,
                'group_id'       => $call->group_id,
                'group_name'     => $group?->name ?? 'Group Call',
                'group_image'    => $group?->image ?? null,
                'call_type'      => $call->call_type,
                'agora_channel'  => 'group_call_' . $call->id,
                'initiated_by'   => $call->initiated_by,
                'initiator_name' => $call->initiator?->name ?? 'Someone',
            ],
        ]);
    }

    public function reactMessage(\Illuminate\Http\Request $request, $id)
    {
        $user  = $request->user();
        $emoji = $request->input('emoji');
        $msg   = GroupMessage::find($id);
        if (!$msg) return response()->json(['message' => 'Message not found'], 404);

        $reactions = json_decode($msg->reactions ?? '{}', true) ?: [];
        if (!isset($reactions[$emoji])) $reactions[$emoji] = [];
        if (in_array($user->id, $reactions[$emoji])) {
            $reactions[$emoji] = array_values(array_filter($reactions[$emoji], fn($uid) => $uid !== $user->id));
        } else {
            $reactions[$emoji][] = $user->id;
        }
        if (empty($reactions[$emoji])) unset($reactions[$emoji]);

        $msg->reactions = json_encode($reactions);
        $msg->save();

        return response()->json(['reactions' => $reactions]);
    }

    public function editMessage(\Illuminate\Http\Request $request, $id)
    {
        $user = $request->user();
        $msg  = GroupMessage::find($id);
        if (!$msg) return response()->json(['message' => 'Message not found'], 404);
        if ((int)$msg->sender_id !== (int)$user->id) return response()->json(['message' => 'Forbidden'], 403);

        $msg->message   = $request->input('message');
        $msg->is_edited = true;
        $msg->save();

        return response()->json(['message' => 'Message updated', 'data' => $this->formatGroupMessage($msg)]);
    }

    public function deleteMessage(\Illuminate\Http\Request $request, $id)
    {
        $user = $request->user();
        $msg  = GroupMessage::find($id);
        if (!$msg) return response()->json(['message' => 'Message not found'], 404);
        if ((int)$msg->sender_id !== (int)$user->id) return response()->json(['message' => 'Forbidden'], 403);

        $msg->delete();

        return response()->json(['message' => 'Message deleted']);
    }

    public function getPinnedMessage(\Illuminate\Http\Request $request, $id)
    {
        $userId = $request->user()->id;
        if (!GroupMember::where('group_id', $id)->where('user_id', $userId)->exists()) {
            return response()->json(['message' => 'Not a member'], 403);
        }
        $group = Group::find($id);
        if (!$group || !$group->pinned_message_id) {
            return response()->json(['pinned_message' => null]);
        }
        $msg = GroupMessage::with(['sender', 'replyTo.sender'])->find($group->pinned_message_id);
        return response()->json(['pinned_message' => $msg ? $this->formatGroupMessage($msg) : null]);
    }

    public function pinMessage(\Illuminate\Http\Request $request, $id, $msgId)
    {
        $userId = $request->user()->id;
        if (!GroupMember::where('group_id', $id)->where('user_id', $userId)->exists()) {
            return response()->json(['message' => 'Not a member'], 403);
        }
        $msg = GroupMessage::where('group_id', $id)->find($msgId);
        if (!$msg) return response()->json(['message' => 'Message not found'], 404);
        Group::where('id', $id)->update(['pinned_message_id' => $msgId]);
        return response()->json(['message' => 'Message pinned']);
    }

    public function unpinMessage(\Illuminate\Http\Request $request, $id)
    {
        $userId = $request->user()->id;
        if (!GroupMember::where('group_id', $id)->where('user_id', $userId)->exists()) {
            return response()->json(['message' => 'Not a member'], 403);
        }
        Group::where('id', $id)->update(['pinned_message_id' => null]);
        return response()->json(['message' => 'Message unpinned']);
    }

    public function forwardMessage(\Illuminate\Http\Request $request, $msgId)
    {
        $user = $request->user();
        $request->validate(['friend_ids' => 'required|array|min:1']);
        $original = GroupMessage::findOrFail($msgId);
        foreach ($request->friend_ids as $friendId) {
            \App\Models\Message::create([
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
}
