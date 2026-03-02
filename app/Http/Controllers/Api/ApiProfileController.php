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

        return response()->json([
            'user'            => $this->formatUser($user),
            'followers_count' => $followersCount,
            'following_count' => $followingCount,
            'posts_count'     => $postsCount,
            'is_following'    => $isFollowing,
            'is_own_profile'  => $authUserId === (int) $id,
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

        Notification::create([
            'user_id'  => $id,
            'from_id'  => $authUser->id,
            'type'     => 'follow',
            'message'  => $authUser->name . ' started following you',
            'is_read'  => 0,
        ]);

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
        $userId = $request->user()->id;

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
        $userId = $request->user()->id;

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

        return response()->json([
            'users' => $users->map(fn($u) => $this->formatUser($u)),
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
            'badge_status' => $user->badge_status,
            'number_followers' => $user->number_followers ?? 0,
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
