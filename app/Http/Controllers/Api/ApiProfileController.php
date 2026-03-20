<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UserRecord;
use App\Models\SamplePost;
use App\Models\Follow;
use App\Models\Notification;

class ApiProfileController extends Controller
{
    public function show(Request $request, $id)
    {
        $authUserId = $request->user()->id;
        $user       = UserRecord::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $followersCount = Follow::where('receiver_id', $id)->count();
        $followingCount = Follow::where('sender_id', $id)->count();
        $postsCount     = SamplePost::where('user_id', $id)->where('status', 'published')->whereNull('deleted_at')->count();
        $isFollowing    = Follow::where('sender_id', $authUserId)->where('receiver_id', $id)->exists();

        $posts = SamplePost::with('user')
            ->where('user_id', $id)
            ->where('status', 'published')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $taskPoints = ($authUserId === (int) $id)
            ? DB::table('wallet_transactions')
                ->where('wallet_owner_id', $id)
                ->where('type', 'task_reward')
                ->where('status', 'successful')
                ->sum('amount')
            : 0;

        return response()->json([
            'user'            => $this->formatUser($user),
            'followers_count' => $followersCount,
            'following_count' => $followingCount,
            'posts_count'     => $postsCount,
            'is_following'    => $isFollowing,
            'is_own_profile'  => $authUserId === (int) $id,
            'task_points'     => (float) $taskPoints,
            'posts'           => $posts->map(fn($p) => $this->formatPost($p, $authUserId)),
        ]);
    }

    public function follow(Request $request, $id)
    {
        $authUser = $request->user();

        if ($authUser->id == $id) {
            return response()->json(['message' => 'Cannot follow yourself'], 422);
        }

        $target = UserRecord::find($id);
        if (!$target) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $existing = Follow::where('sender_id', $authUser->id)->where('receiver_id', $id)->first();
        if ($existing) {
            return response()->json(['message' => 'Already following'], 409);
        }

        Follow::create(['sender_id' => $authUser->id, 'receiver_id' => $id]);

        try {
            Notification::create([
                'notification_reciever_id' => $id,
                'actor_id'                 => $authUser->id,
                'type'                     => 'follow',
                'message'                  => $authUser->name . ' started following you',
                'read_notification'        => 'no',
            ]);
        } catch (\Exception $e) {
            // Notification failed — follow already completed, continue
        }

        return response()->json(['message' => 'Followed', 'is_following' => true]);
    }

    public function unfollow(Request $request, $id)
    {
        $authUserId = $request->user()->id;

        Follow::where('sender_id', $authUserId)->where('receiver_id', $id)->delete();

        return response()->json(['message' => 'Unfollowed', 'is_following' => false]);
    }

    public function followers(Request $request)
    {
        $userId = $request->get('user_id', $request->user()->id);

        $followers = Follow::with('sender')
            ->where('receiver_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'followers'   => $followers->map(fn($f) => $f->sender ? $this->formatUser($f->sender) : null)->filter()->values(),
            'total'       => $followers->total(),
            'current_page'=> $followers->currentPage(),
            'last_page'   => $followers->lastPage(),
        ]);
    }

    public function following(Request $request)
    {
        $userId = $request->get('user_id', $request->user()->id);

        $following = Follow::with('receiver')
            ->where('sender_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'following'   => $following->map(fn($f) => $f->receiver ? $this->formatUser($f->receiver) : null)->filter()->values(),
            'total'       => $following->total(),
            'current_page'=> $following->currentPage(),
            'last_page'   => $following->lastPage(),
        ]);
    }

    public function myPosts(Request $request)
    {
        $userId = $request->user()->id;

        // Own posts
        $ownPosts = SamplePost::where('user_id', $userId)
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($p) => array_merge($this->formatPost($p, $userId), [
                'comments_count' => DB::table('post_comments')->where('post_id', $p->id)->count(),
                'is_repost'      => false,
            ]))->toArray();

        // Reposted posts (by this user)
        $repostedPostIds = DB::table('post_reposts')
            ->where('user_id', $userId)
            ->pluck('post_id')
            ->toArray();

        $repostedPosts = SamplePost::whereIn('id', $repostedPostIds)
            ->whereNull('deleted_at')
            ->get()
            ->map(fn($p) => array_merge($this->formatPost($p, $userId), [
                'comments_count' => DB::table('post_comments')->where('post_id', $p->id)->count(),
                'is_repost'      => true,
            ]))->toArray();

        // Merge and sort by created_at descending
        $all = array_merge($ownPosts, $repostedPosts);
        usort($all, fn($a, $b) => strtotime($b['created_at']) - strtotime($a['created_at']));

        return response()->json(['posts' => $all]);
    }

    public function searchUsers(Request $request)
    {
        $query  = $request->get('q', '');
        $userId = $request->user()->id;

        if (strlen($query) < 2) {
            return response()->json(['users' => []]);
        }

        $users = UserRecord::where(function ($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%')
              ->orWhere('username', 'like', '%' . $query . '%');
        })
        ->where('id', '!=', $userId)
        ->where('status', 'active')
        ->limit(20)
        ->get();

        if ($users->isEmpty()) {
            return response()->json(['users' => []]);
        }

        $userIds = $users->pluck('id');

        // IDs the current user follows (among results)
        $followingIds = Follow::where('sender_id', $userId)
            ->whereIn('receiver_id', $userIds)
            ->pluck('receiver_id')
            ->toArray();

        // IDs that follow the current user back (among results) — mutual = friend
        $followerIds = Follow::where('receiver_id', $userId)
            ->whereIn('sender_id', $userIds)
            ->pluck('sender_id')
            ->toArray();

        return response()->json([
            'users' => $users->map(function ($u) use ($followingIds, $followerIds) {
                $isFollowing = in_array($u->id, $followingIds);
                $isFriend    = $isFollowing && in_array($u->id, $followerIds);
                return array_merge($this->formatUser($u), [
                    'is_following' => $isFollowing,
                    'is_friend'    => $isFriend,
                ]);
            }),
        ]);
    }

    public function suggestedUsers(Request $request)
    {
        $userId = $request->user()->id;
        $limit  = max(6, (int) $request->get('limit', 20));

        $users = UserRecord::where('id', '!=', $userId)
            ->where('status', 'active')
            ->inRandomOrder()
            ->limit($limit)
            ->get();

        if ($users->isEmpty()) {
            return response()->json(['users' => []]);
        }

        $userIds = $users->pluck('id');

        $followingIds = Follow::where('sender_id', $userId)
            ->whereIn('receiver_id', $userIds)
            ->pluck('receiver_id')
            ->toArray();

        $followerIds = Follow::where('receiver_id', $userId)
            ->whereIn('sender_id', $userIds)
            ->pluck('sender_id')
            ->toArray();

        return response()->json([
            'users' => $users->map(function ($u) use ($followingIds, $followerIds) {
                $isFollowing = in_array($u->id, $followingIds);
                $isFriend    = $isFollowing && in_array($u->id, $followerIds);
                return array_merge($this->formatUser($u), [
                    'is_following' => $isFollowing,
                    'is_friend'    => $isFriend,
                ]);
            }),
        ]);
    }

    private function formatUser(UserRecord $user): array
    {
        return [
            'id'         => $user->id,
            'name'       => $user->name,
            'username'   => $user->username,
            'bio'        => $user->bio,
            'profileimg' => $user->profileimg ? (filter_var($user->profileimg, FILTER_VALIDATE_URL) ? $user->profileimg : url($user->profileimg)) : null,
            'bgimg'      => $user->bgimg ? (filter_var($user->bgimg, FILTER_VALIDATE_URL) ? $user->bgimg : url($user->bgimg)) : null,
            'country'    => $user->country,
            'badge_status'     => $user->badge_status,
            'number_followers' => $user->number_followers ?? 0,
            'is_online'        => (bool) $user->is_online,
            'last_seen'        => $user->last_seen,
        ];
    }

    private function formatPost(SamplePost $post, int $userId): array
    {
        $filePaths = json_decode($post->file_path, true);
        return [
            'id'         => $post->id,
            'content'    => $post->post_content,
            'file_path'  => $filePaths,
            'text_color' => $post->text_color,
            'bgnd_color' => $post->bgnd_color,
            'likes'      => (int) ($post->likes ?? 0),
            'views'      => (int) ($post->views ?? 0),
            'created_at' => $post->created_at,
        ];
    }
}
