<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Services\NotificationService;
use App\Models\SamplePost;
use App\Models\PostLike;
use App\Models\PostComment;
use App\Models\PostRepost;
use App\Models\SavedPost;
use App\Models\UserRecord;
use Carbon\Carbon;

class ApiPostController extends Controller
{
    public function feed(Request $request)
    {
        $userId = $request->user()->id;
        $page   = max(1, (int) $request->get('page', 1));
        $limit  = 20;

        $followingIds   = DB::table('follow_tbl')
            ->where('sender_id', $userId)
            ->pluck('receiver_id')
            ->toArray();
        $followingIds[] = $userId;

        // ── Fetch original posts ──────────────────────────────────────────────
        $originalPosts = SamplePost::with('user')
            ->whereIn('user_id', $followingIds)
            ->where('status', 'published')
            ->whereNull('deleted_at')
            ->latest()
            ->limit(80)
            ->get();

        // ── Fetch reposts (keyed by post_id, sorted by repost time) ──────────
        $repostRows = DB::table('post_reposts as pr')
            ->join('users_record as u', 'u.id', '=', 'pr.user_id')
            ->whereIn('pr.user_id', $followingIds)
            ->select('pr.post_id', 'pr.created_at as repost_time',
                     'u.name as reposter_name', 'u.username as reposter_username')
            ->latest('pr.created_at')
            ->limit(80)
            ->get()
            ->keyBy('post_id');

        $repostedPosts = SamplePost::with('user')
            ->whereIn('id', $repostRows->keys()->toArray())
            ->where('status', 'published')
            ->whereNull('deleted_at')
            ->get();

        // ── Batch per-user queries (avoid N+1) ────────────────────────────────
        $allIds = $originalPosts->merge($repostedPosts)->unique('id')->pluck('id')->toArray();

        $likedSet    = array_flip(PostLike::whereIn('post_id', $allIds)->where('user_id', $userId)->pluck('post_id')->toArray());
        $savedSet    = array_flip(SavedPost::whereIn('post_id', $allIds)->where('user_id', $userId)->pluck('post_id')->toArray());
        $repostedSet = array_flip(PostRepost::whereIn('post_id', $allIds)->where('user_id', $userId)->pluck('post_id')->toArray());
        $commentCounts = PostComment::whereIn('post_id', $allIds)->whereNull('parent_id')
            ->groupBy('post_id')->selectRaw('post_id, count(*) as cnt')
            ->pluck('cnt', 'post_id');

        // ── Build feed items ──────────────────────────────────────────────────
        $feedItems = collect();

        foreach ($originalPosts as $p) {
            $item              = $this->formatPostFeed($p, $likedSet, $savedSet, $repostedSet, $commentCounts);
            $item['sort_time'] = $p->created_at->timestamp;
            $item['feed_key']  = 'post-' . $p->id;
            $feedItems->push($item);
        }

        foreach ($repostedPosts as $p) {
            if (!$repostRows->has($p->id)) continue;
            $row               = $repostRows->get($p->id);
            $item              = $this->formatPostFeed($p, $likedSet, $savedSet, $repostedSet, $commentCounts);
            $item['repost_info'] = ['name' => $row->reposter_name, 'username' => $row->reposter_username];
            $item['sort_time'] = strtotime($row->repost_time);
            $item['feed_key']  = 'repost-' . $p->id;
            $feedItems->push($item);
        }

        // ── Sort by time desc, paginate ───────────────────────────────────────
        $sorted = $feedItems->sortByDesc('sort_time')->values();
        $total  = $sorted->count();
        $items  = $sorted->forPage($page, $limit)->values();

        return response()->json([
            'posts'        => $items,
            'total'        => $total,
            'current_page' => $page,
            'last_page'    => max(1, (int) ceil($total / $limit)),
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
            'post_content' => $request->post_content ?? '',
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

        // Notify followers (fire-and-forget — never fail the post creation)
        try {
            NotificationService::notifyFollowers($user, 'post', $post->id);
        } catch (\Throwable $e) {}

        return response()->json(['post' => $this->formatPost($post, $user->id)], 201);
    }

    public function show(Request $request, $id)
    {
        $userId = $request->user()->id;
        $post   = SamplePost::with('user')->find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        // Only count one view per user
        $alreadyViewed = DB::table('post_views')
            ->where('post_id', $id)
            ->where('user_id', $userId)
            ->exists();

        if (!$alreadyViewed) {
            DB::table('post_views')->insert([
                'post_id'    => $id,
                'user_id'    => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $post->increment('views');
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

        if ((int)$post->user_id !== (int)$user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->only(['post_content', 'text_color', 'bgnd_color']);
        if ($request->has('file_path')) {
            $data['file_path'] = $request->file_path; // null clears media
        }
        $post->update($data);
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

        if ((int)$post->user_id !== (int)$user->id) {
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
            try {
                DB::table('notifications')->insert([
                    'id'                       => \Illuminate\Support\Str::uuid()->toString(),
                    'notification_reciever_id' => (string) $post->user_id,
                    'actor_id'                 => $userId,
                    'post_id'                  => $id,
                    'type'                     => 'like',
                    'message'                  => $request->user()->name . ' liked your post',
                    'read_notification'        => 'no',
                    'created_at'               => now(),
                    'updated_at'               => now(),
                ]);
            } catch (\Exception $e) { /* notification failure must not break the response */ }
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
            try {
                DB::table('notifications')->insert([
                    'id'                       => \Illuminate\Support\Str::uuid()->toString(),
                    'notification_reciever_id' => (string) $post->user_id,
                    'actor_id'                 => $user->id,
                    'post_id'                  => $id,
                    'type'                     => 'comment',
                    'message'                  => $user->name . ' commented on your post',
                    'read_notification'        => 'no',
                    'created_at'               => now(),
                    'updated_at'               => now(),
                ]);
            } catch (\Exception $e) { /* notification failure must not break the response */ }
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
            $post->decrement('shares');
            return response()->json(['reposted' => false]);
        }

        PostRepost::create(['post_id' => $id, 'user_id' => $userId]);
        $post->increment('shares');

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

    public function fileStats(Request $request, $id)
    {
        $userId    = $request->user()->id;
        $post      = SamplePost::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $filePaths = json_decode($post->file_path, true) ?? [];
        $count     = count($filePaths);

        if ($count === 0) {
            return response()->json(['stats' => []]);
        }

        $viewCounts = DB::table('post_file_views')
            ->where('post_id', $id)
            ->select('file_index', DB::raw('count(*) as cnt'))
            ->groupBy('file_index')
            ->pluck('cnt', 'file_index');

        $likeCounts = DB::table('post_file_likes')
            ->where('post_id', $id)
            ->select('file_index', DB::raw('count(*) as cnt'))
            ->groupBy('file_index')
            ->pluck('cnt', 'file_index');

        $userLiked = DB::table('post_file_likes')
            ->where('post_id', $id)
            ->where('user_id', $userId)
            ->pluck('file_index')
            ->flip()
            ->all();

        $stats = [];
        for ($i = 0; $i < $count; $i++) {
            $stats[] = [
                'index'    => $i,
                'views'    => (int) ($viewCounts[$i] ?? 0),
                'likes'    => (int) ($likeCounts[$i] ?? 0),
                'is_liked' => isset($userLiked[$i]),
            ];
        }

        return response()->json(['stats' => $stats]);
    }

    public function fileView(Request $request, $id, $index)
    {
        $userId = $request->user()->id;
        $post   = SamplePost::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $filePaths = json_decode($post->file_path, true) ?? [];
        $index     = (int) $index;

        if ($index < 0 || $index >= count($filePaths)) {
            return response()->json(['message' => 'Invalid file index'], 422);
        }

        $exists = DB::table('post_file_views')
            ->where('post_id', $id)
            ->where('file_index', $index)
            ->where('user_id', $userId)
            ->exists();

        if (!$exists) {
            DB::table('post_file_views')->insert([
                'post_id'    => $id,
                'file_index' => $index,
                'user_id'    => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $views = DB::table('post_file_views')
            ->where('post_id', $id)
            ->where('file_index', $index)
            ->count();

        return response()->json(['views' => $views]);
    }

    public function fileLike(Request $request, $id, $index)
    {
        $userId = $request->user()->id;
        $post   = SamplePost::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $filePaths = json_decode($post->file_path, true) ?? [];
        $index     = (int) $index;

        if ($index < 0 || $index >= count($filePaths)) {
            return response()->json(['message' => 'Invalid file index'], 422);
        }

        $existing = DB::table('post_file_likes')
            ->where('post_id', $id)
            ->where('file_index', $index)
            ->where('user_id', $userId)
            ->exists();

        if ($existing) {
            DB::table('post_file_likes')
                ->where('post_id', $id)
                ->where('file_index', $index)
                ->where('user_id', $userId)
                ->delete();
            $liked = false;
        } else {
            DB::table('post_file_likes')->insert([
                'post_id'    => $id,
                'file_index' => $index,
                'user_id'    => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $liked = true;
        }

        $likes = DB::table('post_file_likes')
            ->where('post_id', $id)
            ->where('file_index', $index)
            ->count();

        return response()->json(['liked' => $liked, 'likes' => $likes]);
    }

    private function formatPostFeed(SamplePost $post, array $likedSet, array $savedSet, array $repostedSet, $commentCounts): array
    {
        $filePaths = json_decode($post->file_path, true);
        return [
            'id'             => $post->id,
            'content'        => $post->post_content,
            'file_path'      => $filePaths,
            'text_color'     => $post->text_color,
            'bgnd_color'     => $post->bgnd_color,
            'link_preview'   => $post->link_preview,
            'likes'          => (int) ($post->likes   ?? 0),
            'views'          => (int) ($post->views   ?? 0),
            'shares'         => (int) ($post->shares  ?? 0),
            'created_at'     => $post->created_at,
            'repost_info'    => null,
            'user'           => $post->user ? [
                'id'           => $post->user->id,
                'name'         => $post->user->name,
                'username'     => $post->user->username,
                'profileimg'   => $post->user->profileimg
                    ? (filter_var($post->user->profileimg, FILTER_VALIDATE_URL)
                        ? $post->user->profileimg
                        : url($post->user->profileimg))
                    : null,
                'badge_status' => $post->user->badge_status,
            ] : null,
            'is_liked'       => isset($likedSet[$post->id]),
            'is_saved'       => isset($savedSet[$post->id]),
            'is_reposted'    => isset($repostedSet[$post->id]),
            'comments_count' => (int) ($commentCounts[$post->id] ?? 0),
        ];
    }

    private function formatPost(SamplePost $post, int $userId, $repostMap = null): array
    {
        $filePaths  = json_decode($post->file_path, true);
        $repostInfo = null;
        if ($repostMap && $repostMap->has($post->id)) {
            $r = $repostMap->get($post->id);
            $repostInfo = ['name' => $r->reposter_name, 'username' => $r->reposter_username];
        }

        return [
            'id'             => $post->id,
            'content'        => $post->post_content,
            'file_path'      => $filePaths,
            'text_color'     => $post->text_color,
            'bgnd_color'     => $post->bgnd_color,
            'link_preview'   => $post->link_preview,
            'likes'          => (int) ($post->likes ?? 0),
            'views'          => (int) ($post->views ?? 0),
            'shares'         => (int) ($post->shares ?? 0),
            'created_at'     => $post->created_at,
            'repost_info'    => $repostInfo,
            'user'           => $post->user ? [
                'id'           => $post->user->id,
                'name'         => $post->user->name,
                'username'     => $post->user->username,
                'profileimg'   => $post->user->profileimg ? (filter_var($post->user->profileimg, FILTER_VALIDATE_URL) ? $post->user->profileimg : url($post->user->profileimg)) : null,
                'badge_status' => $post->user->badge_status,
            ] : null,
            'is_liked'       => PostLike::where('post_id', $post->id)->where('user_id', $userId)->exists(),
            'is_saved'       => SavedPost::where('post_id', $post->id)->where('user_id', $userId)->exists(),
            'is_reposted'    => PostRepost::where('post_id', $post->id)->where('user_id', $userId)->exists(),
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
