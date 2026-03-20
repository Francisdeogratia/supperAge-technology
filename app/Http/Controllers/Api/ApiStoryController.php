<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\TalesExten;
use App\Models\UserRecord;
use App\Services\NotificationService;
use Carbon\Carbon;

class ApiStoryController extends Controller
{
    public function index(Request $request)
    {
        $user     = $request->user();
        $username = $user->username;

        // Show ALL users' stories from last 24 hours (same as website behaviour)
        $stories = TalesExten::where('created_at', '>=', Carbon::now()->subHours(24))
            ->orderBy('created_at', 'desc')
            ->get();

        $userMap = UserRecord::whereIn('specialcode', $stories->pluck('specialcode')->unique())
            ->get()
            ->keyBy('specialcode');

        // IDs of people the current user follows (to sort their stories first)
        $followingCodes = DB::table('follow_tbl')
            ->where('sender_id', $user->id)
            ->join('users_record', 'follow_tbl.receiver_id', '=', 'users_record.id')
            ->pluck('users_record.specialcode')
            ->toArray();
        $followingCodes[] = $user->specialcode; // own stories always first-priority

        $mySpecialcode = $user->specialcode; // capture before closure to avoid auth() issues

        $grouped = $stories->groupBy('specialcode')->map(function ($userStories) use ($username, $userMap, $followingCodes, $mySpecialcode) {
            $code   = $userStories->first()->specialcode;
            $author = $userMap[$code] ?? null;

            // Use existing 'views' table (tale_id, username)
            $viewedTaleIds = DB::table('views')
                ->where('username', $username)
                ->whereIn('tale_id', $userStories->pluck('tales_id'))
                ->pluck('tale_id');

            return [
                'user'        => $author ? $this->formatUser($author) : null,
                'stories'     => $userStories->map(fn($s) => $this->formatStory($s, $viewedTaleIds->contains($s->tales_id)))->values(),
                'all_viewed'  => $viewedTaleIds->count() >= $userStories->count(),
                'is_own'      => $code === $mySpecialcode,   // Bug fix: use captured variable, not auth()
                '_sort_weight'=> in_array($code, $followingCodes) ? 0 : 1,
            ];
        })->values()
          ->sortBy('_sort_weight')
          ->values()
          ->map(fn($g) => array_diff_key($g, ['_sort_weight' => '']));

        return response()->json(['stories' => $grouped]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tales_content' => 'nullable|string|max:2000',
            'file_url'      => 'nullable|url',
            'text_color'    => 'nullable|string',
            'bgnd_color'    => 'nullable|string',
            'type'          => 'nullable|string',
            'link_preview'  => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (!$request->tales_content && !$request->file_url) {
            return response()->json(['message' => 'Please add text or an image.'], 422);
        }

        $user = $request->user();

        $story = TalesExten::create([
            'specialcode'      => $user->specialcode,
            'tales_content'    => $request->tales_content ?? '',
            'files_talesexten' => $request->file_url,
            'tales_datetime'   => now(),
            'username'         => $user->username,
            'text_color'       => $request->text_color ?? '#ffffff',
            'bgnd_color'       => $request->bgnd_color ?? '#000000',
            'type'             => $request->file_url ? ($request->type ?? 'image') : 'text',
            'tales_types'      => 'story',
            'link_preview'     => $request->link_preview,
        ]);

        // Notify followers (fire-and-forget)
        try {
            NotificationService::notifyFollowers($user, 'story', $story->tales_id);
        } catch (\Throwable $e) {}

        return response()->json(['story' => $this->formatStory($story, false)], 201);
    }

    public function show(Request $request, $id)
    {
        $user  = $request->user();
        $story = TalesExten::find($id);

        if (!$story) {
            return response()->json(['message' => 'Story not found'], 404);
        }

        $viewed = DB::table('views')
            ->where('tale_id', $id)
            ->where('username', $user->username)
            ->exists();

        $author = UserRecord::where('specialcode', $story->specialcode)->first();

        return response()->json([
            'story' => $this->formatStory($story, $viewed),
            'user'  => $author ? $this->formatUser($author) : null,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user  = $request->user();
        $story = TalesExten::find($id);

        if (!$story) {
            return response()->json(['message' => 'Story not found'], 404);
        }

        if ($story->specialcode !== $user->specialcode) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'tales_content' => 'nullable|string|max:2000',
            'text_color'    => 'nullable|string',
            'bgnd_color'    => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $story->update([
            'tales_content' => $request->has('tales_content') ? ($request->tales_content ?? '') : $story->tales_content,
            'text_color'    => $request->text_color  ?? $story->text_color,
            'bgnd_color'    => $request->bgnd_color  ?? $story->bgnd_color,
        ]);

        return response()->json(['story' => $this->formatStory($story->fresh(), false)]);
    }

    public function destroy(Request $request, $id)
    {
        $user  = $request->user();
        $story = TalesExten::find($id);

        if (!$story) {
            return response()->json(['message' => 'Story not found'], 404);
        }

        if ($story->specialcode !== $user->specialcode) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $story->delete();

        return response()->json(['message' => 'Story deleted']);
    }

    public function like(Request $request, $id)
    {
        $user  = $request->user();
        $story = TalesExten::find($id);

        if (!$story) {
            return response()->json(['message' => 'Story not found'], 404);
        }

        // Use existing 'likes' table (tale_id, username)
        $existing = DB::table('likes')
            ->where('tale_id', $id)
            ->where('username', $user->username)
            ->first();

        if ($existing) {
            DB::table('likes')
                ->where('tale_id', $id)
                ->where('username', $user->username)
                ->delete();
            $story->decrement('likes');
            return response()->json(['liked' => false, 'likes' => $story->fresh()->likes]);
        }

        DB::table('likes')->insert([
            'tale_id'    => $id,
            'username'   => $user->username,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $story->increment('likes');

        return response()->json(['liked' => true, 'likes' => $story->fresh()->likes]);
    }

    public function view(Request $request, $id)
    {
        $user  = $request->user();
        $story = TalesExten::find($id);

        if (!$story) {
            return response()->json(['message' => 'Story not found'], 404);
        }

        // Use existing 'views' table (tale_id, username)
        $exists = DB::table('views')
            ->where('tale_id', $id)
            ->where('username', $user->username)
            ->exists();

        if (!$exists) {
            DB::table('views')->insert([
                'tale_id'    => $id,
                'username'   => $user->username,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $story->increment('views');
        }

        return response()->json(['message' => 'View recorded']);
    }

    public function viewers(Request $request, $id)
    {
        $story = TalesExten::find($id);

        if (!$story) {
            return response()->json(['message' => 'Story not found'], 404);
        }

        // Only the story owner can see the viewers list
        if ($story->specialcode !== $request->user()->specialcode) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $rows = DB::table('views')
            ->where('tale_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        $usernames = $rows->pluck('username')->unique();
        $userMap   = UserRecord::whereIn('username', $usernames)->get()->keyBy('username');

        $viewers = $rows->map(function ($row) use ($userMap) {
            $u = $userMap[$row->username] ?? null;
            return [
                'id'         => $u?->id,
                'name'       => $u?->name ?? $row->username,
                'username'   => $row->username,
                'profileimg' => $u?->profileimg
                    ? (filter_var($u->profileimg, FILTER_VALIDATE_URL) ? $u->profileimg : url($u->profileimg))
                    : null,
                'viewed_at'  => $row->created_at,
            ];
        });

        return response()->json(['viewers' => $viewers, 'count' => $viewers->count()]);
    }

    public function comments(Request $request, $id)
    {
        $story = TalesExten::find($id);

        if (!$story) {
            return response()->json(['message' => 'Story not found'], 404);
        }

        // Use existing 'comments' table (tale_id, username, comment)
        $rows = DB::table('comments')
            ->where('tale_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Attach profile images from users_record
        $usernames = $rows->pluck('username')->unique();
        $userMap   = UserRecord::whereIn('username', $usernames)->get()->keyBy('username');

        $comments = $rows->map(function ($row) use ($userMap) {
            $author = $userMap[$row->username] ?? null;
            return [
                'id'         => $row->id,
                'user_id'    => $author?->id,
                'username'   => $row->username,
                'name'       => $author?->name ?? $row->username,
                'profileimg' => $author?->profileimg
                    ? (filter_var($author->profileimg, FILTER_VALIDATE_URL) ? $author->profileimg : url($author->profileimg))
                    : null,
                'comment'    => $row->comment,
                'created_at' => $row->created_at,
            ];
        });

        return response()->json(['comments' => $comments]);
    }

    public function addComment(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user  = $request->user();
        $story = TalesExten::find($id);

        if (!$story) {
            return response()->json(['message' => 'Story not found'], 404);
        }

        // Use existing 'comments' table (tale_id, username, comment)
        DB::table('comments')->insert([
            'tale_id'    => $id,
            'username'   => $user->username,
            'comment'    => $request->content,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Notify the story creator — wrapped in try/catch so a notification
        // failure never causes a 500 on the comment endpoint
        try {
            $creator = UserRecord::where('specialcode', $story->specialcode)->first();
            if ($creator && $creator->id !== $user->id) {
                DB::table('notifications')->insert([
                    'id'                       => Str::uuid(),
                    'type'                     => 'story_comment',
                    'user_id'                  => $user->id,
                    'message'                  => $user->username . ' commented on your story',
                    'notification_reciever_id' => (string) $creator->id,
                    'read_notification'        => 'no',   // Bug fix: ENUM('yes','no'), not 0/1
                    'notifiable_type'          => 'App\Models\TalesExten',
                    'notifiable_id'            => $id,
                    'data'                     => json_encode(['story_id' => $id, 'comment' => $request->content]),
                    'created_at'               => now(),
                    'updated_at'               => now(),
                ]);
            }
        } catch (\Exception $e) {
            // Notification failure should not break the comment response
        }

        return response()->json(['message' => 'Comment added']);
    }

    private function formatStory(TalesExten $story, bool $viewed): array
    {
        $file = $story->files_talesexten;
        if ($file && !filter_var($file, FILTER_VALIDATE_URL)) {
            $file = url($file);
        }

        return [
            'id'           => $story->tales_id,
            'content'      => $story->tales_content,
            'file'         => $file,
            'type'         => $story->type,
            'text_color'   => $story->text_color,
            'bgnd_color'   => $story->bgnd_color,
            'likes'        => (int) ($story->likes ?? 0),
            'views'        => (int) ($story->views ?? 0),
            'viewed'       => $viewed,
            'link_preview' => $story->link_preview,
            'created_at'   => $story->created_at,
            'expires_at'   => $story->created_at ? Carbon::parse($story->created_at)->addHours(24) : null,
        ];
    }

    private function formatUser(UserRecord $user): array
    {
        return [
            'id'         => $user->id,
            'name'       => $user->name,
            'username'   => $user->username,
            'profileimg' => $user->profileimg
                ? (filter_var($user->profileimg, FILTER_VALIDATE_URL) ? $user->profileimg : url($user->profileimg))
                : null,
        ];
    }
}
