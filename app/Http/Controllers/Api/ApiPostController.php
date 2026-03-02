<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\SamplePost;
use App\Models\PostLike;
use App\Models\PostComment;
use App\Models\PostRepost;
use App\Models\SavedPost;
use App\Models\Notification;
use App\Models\UserRecord;
use Carbon\Carbon;

class ApiPostController extends Controller
{
    public function feed(Request $request)
    {
        $userId = $request->user()->id;
        $page   = $request->get('page', 1);
        $limit  = $request->get('limit', 20);

        $followingIds = DB::table('follow_tbl')
            ->where('sender_id', $userId)
            ->pluck('receiver_id')
            ->toArray();

        $followingIds[] = $userId;

        $posts = SamplePost::with(['user'])
            ->whereIn('user_id', $followingIds)
            ->where('status', 'published')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'posts'       => $posts->items() ? array_map(fn($p) => $this->formatPost($p, $userId), $posts->items()) : [],
            'total'       => $posts->total(),
            'current_page'=> $posts->currentPage(),
            'last_page'   => $posts->lastPage(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_content' => 'nullable|string|max:5000',
            'file_path'    => 'nullable|string',
            'text_color'   => 'nullable|string',
            'bgnd_color'   => 'nullable|string',
            'link_preview' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();

        $post = SamplePost::create([
            'post_content' => $request->post_content,
            'file_path'    => $request->file_path,
            'specialcode'  => $user->specialcode,
            'username'     => $user->username,
            'user_id'      => $user->id,
            'text_color'   => $request->text_color ?? '#000000',
            'bgnd_color'   => $request->bgnd_color ?? '#ffffff',
            'status'       => 'published',
            'link_preview' => $request->link_preview,
        ]);

        $post->load('user');

        return response()->json(['post' => $this->formatPost($post, $user->id)], 201);
    }

    public function show(Request $request, $id)
    {
        $userId = $request->user()->id;
        $post   = SamplePost::with('user')->find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return response()->json(['post' => $this->formatPost($post, $userId)]);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        $post = SamplePost::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        if ($post->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->update($request->only(['post_content', 'text_color', 'bgnd_color']));
        $post->load('user');

        return response()->json(['post' => $this->formatPost($post, $user->id)]);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $post = SamplePost::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        if ($post->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted']);
    }

    public function like(Request $request, $id)
    {
        $userId = $request->user()->id;
        $post   = SamplePost::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $existing = PostLike::where('post_id', $id)->where('user_id', $userId)->first();

        if ($existing) {
            $existing->delete();
            $post->decrement('likes');
            return response()->json(['liked' => false, 'likes' => $post->fresh()->likes]);
        }

        PostLike::create(['post_id' => $id, 'user_id' => $userId]);
        $post->increment('likes');

        if ($post->user_id !== $userId) {
            Notification::create([
                'user_id'    => $post->user_id,
                'from_id'    => $userId,
                'type'       => 'like',
                'message'    => $request->user()->name . ' liked your post',
                'reference_id' => $id,
                'is_read'    => 0,
            ]);
        }

        return response()->json(['liked' => true, 'likes' => $post->fresh()->likes]);
    }

    public function comments(Request $request, $id)
    {
        $post = SamplePost::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $comments = PostComment::with('user')
            ->where('post_id', $id)
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'comments'    => $comments->map(fn($c) => $this->formatComment($c)),
            'total'       => $comments->total(),
            'current_page'=> $comments->currentPage(),
            'last_page'   => $comments->lastPage(),
        ]);
    }

    public function addComment(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'content'   => 'required|string|max:1000',
            'parent_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        $post = SamplePost::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $comment = PostComment::create([
            'post_id'   => $id,
            'user_id'   => $user->id,
            'comment'   => $request->content,
            'parent_id' => $request->parent_id,
        ]);

        $comment->load('user');

        if ($post->user_id !== $user->id) {
            Notification::create([
                'user_id'      => $post->user_id,
                'from_id'      => $user->id,
                'type'         => 'comment',
                'message'      => $user->name . ' commented on your post',
                'reference_id' => $id,
                'is_read'      => 0,
            ]);
        }

        return response()->json(['comment' => $this->formatComment($comment)], 201);
    }

    public function repost(Request $request, $id)
    {
        $userId = $request->user()->id;
        $post   = SamplePost::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $existing = PostRepost::where('post_id', $id)->where('user_id', $userId)->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['reposted' => false]);
        }

        PostRepost::create(['post_id' => $id, 'user_id' => $userId]);

        return response()->json(['reposted' => true]);
    }

    public function save(Request $request, $id)
    {
        $userId = $request->user()->id;
        $post   = SamplePost::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $existing = SavedPost::where('post_id', $id)->where('user_id', $userId)->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['saved' => false]);
        }

        SavedPost::create(['post_id' => $id, 'user_id' => $userId]);

        return response()->json(['saved' => true]);
    }

    public function saved(Request $request)
    {
        $userId = $request->user()->id;

        $savedPostIds = SavedPost::where('user_id', $userId)->pluck('post_id');

        $posts = SamplePost::with('user')
            ->whereIn('id', $savedPostIds)
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'posts'       => $posts->map(fn($p) => $this->formatPost($p, $userId)),
            'total'       => $posts->total(),
            'current_page'=> $posts->currentPage(),
            'last_page'   => $posts->lastPage(),
        ]);
    }

    private function formatPost(SamplePost $post, int $userId): array
    {
        $filePaths = json_decode($post->file_path, true);

        return [
            'id'           => $post->id,
            'content'      => $post->post_content,
            'file_path'    => $filePaths,
            'text_color'   => $post->text_color,
            'bgnd_color'   => $post->bgnd_color,
            'link_preview' => $post->link_preview,
            'likes'        => (int) ($post->likes ?? 0),
            'views'        => (int) ($post->views ?? 0),
            'shares'       => (int) ($post->shares ?? 0),
            'created_at'   => $post->created_at,
            'user'         => $post->user ? [
                'id'         => $post->user->id,
                'name'       => $post->user->name,
                'username'   => $post->user->username,
                'profileimg' => $post->user->profileimg ? (filter_var($post->user->profileimg, FILTER_VALIDATE_URL) ? $post->user->profileimg : url($post->user->profileimg)) : null,
                'badge_status' => $post->user->badge_status,
            ] : null,
            'is_liked'     => PostLike::where('post_id', $post->id)->where('user_id', $userId)->exists(),
            'is_saved'     => SavedPost::where('post_id', $post->id)->where('user_id', $userId)->exists(),
            'is_reposted'  => PostRepost::where('post_id', $post->id)->where('user_id', $userId)->exists(),
            'comments_count' => PostComment::where('post_id', $post->id)->whereNull('parent_id')->count(),
        ];
    }

    private function formatComment(PostComment $comment): array
    {
        return [
            'id'         => $comment->id,
            'content'    => $comment->comment,
            'parent_id'  => $comment->parent_id,
            'created_at' => $comment->created_at,
            'user'       => $comment->user ? [
                'id'         => $comment->user->id,
                'name'       => $comment->user->name,
                'username'   => $comment->user->username,
                'profileimg' => $comment->user->profileimg ? (filter_var($comment->user->profileimg, FILTER_VALIDATE_URL) ? $comment->user->profileimg : url($comment->user->profileimg)) : null,
            ] : null,
        ];
    }
}
