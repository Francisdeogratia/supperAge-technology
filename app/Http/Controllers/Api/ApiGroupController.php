<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\GroupMessage;
use App\Models\UserRecord;

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
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|string',
            'member_ids'  => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user  = $request->user();
        $group = Group::create([
            'name'          => $request->name,
            'description'   => $request->description,
            'profile_image' => $request->profile_image,
            'user_id'       => $user->id,
            'privacy'       => 'private',
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

        $messages = GroupMessage::with('sender')
            ->where('group_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(30, ['*'], 'page', $page);

        return response()->json([
            'messages'    => $messages->map(fn($m) => $this->formatGroupMessage($m)),
            'total'       => $messages->total(),
            'current_page'=> $messages->currentPage(),
            'last_page'   => $messages->lastPage(),
        ]);
    }

    public function sendMessage(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'message'   => 'nullable|string|max:5000',
            'file_path' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();

        if (!GroupMember::where('group_id', $id)->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Not a member'], 403);
        }

        $message = GroupMessage::create([
            'group_id'  => $id,
            'sender_id' => $user->id,
            'message'   => $request->message,
            'file_path' => $request->file_path,
            'status'    => 'sent',
        ]);

        $message->load('sender');
        Group::where('id', $id)->touch();

        return response()->json(['message' => $this->formatGroupMessage($message)], 201);
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

    private function formatGroup(Group $group, int $userId): array
    {
        $lastMsg = GroupMessage::where('group_id', $group->id)->orderBy('created_at', 'desc')->first();

        return [
            'id'            => $group->id,
            'name'          => $group->name,
            'description'   => $group->description,
            'icon'          => $group->profile_image ? (filter_var($group->profile_image, FILTER_VALIDATE_URL) ? $group->profile_image : url($group->profile_image)) : null,
            'created_by'    => $group->user_id,
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
            'my_role'      => $group->members->firstWhere('user_id', $userId)?->role ?? 'member',
            'last_message' => $lastMsg ? ['id' => $lastMsg->id, 'message' => $lastMsg->message, 'sender_id' => $lastMsg->sender_id, 'created_at' => $lastMsg->created_at] : null,
            'created_at'   => $group->created_at,
            'updated_at'   => $group->updated_at,
        ];
    }

    private function formatGroupMessage(GroupMessage $msg): array
    {
        return [
            'id'         => $msg->id,
            'group_id'   => $msg->group_id,
            'sender_id'  => $msg->sender_id,
            'message'    => $msg->message,
            'file_path'  => $msg->file_path,
            'created_at' => $msg->created_at,
            'sender'     => $msg->sender ? [
                'id'         => $msg->sender->id,
                'name'       => $msg->sender->name,
                'username'   => $msg->sender->username,
                'profileimg' => $msg->sender->profileimg ? (filter_var($msg->sender->profileimg, FILTER_VALIDATE_URL) ? $msg->sender->profileimg : url($msg->sender->profileimg)) : null,
            ] : null,
        ];
    }
}
