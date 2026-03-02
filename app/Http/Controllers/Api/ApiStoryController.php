<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\TalesExten;
use App\Models\UserRecord;
use Carbon\Carbon;

class ApiStoryController extends Controller
{
    public function index(Request $request)
    {
        $user   = $request->user();
        $userId = $user->id;

        $followingCodes = DB::table('follow_tbl')
            ->where('sender_id', $userId)
            ->join('users_record', 'follow_tbl.receiver_id', '=', 'users_record.id')
            ->pluck('users_record.specialcode')
            ->toArray();

        $followingCodes[] = $user->specialcode;

        $stories = TalesExten::whereIn('specialcode', $followingCodes)
            ->whereNull('deleted_at')
            ->where('created_at', '>=', Carbon::now()->subHours(24))
            ->orderBy('created_at', 'desc')
            ->get();

        // Group by specialcode, attach user from users_record
        $userMap = UserRecord::whereIn('specialcode', $stories->pluck('specialcode')->unique())
            ->get()
            ->keyBy('specialcode');

        $grouped = $stories->groupBy('specialcode')->map(function ($userStories) use ($userId, $userMap) {
            $code   = $userStories->first()->specialcode;
            $author = $userMap[$code] ?? null;

            $viewedIds = DB::table('tale_views')
                ->where('viewer_id', $userId)
                ->whereIn('tale_id', $userStories->pluck('tales_id'))
                ->pluck('tale_id');

            return [
                'user'      => $author ? $this->formatUser($author) : null,
                'stories'   => $userStories->map(fn($s) => $this->formatStory($s, $viewedIds->contains($s->tales_id)))->values(),
                'all_viewed'=> $viewedIds->count() >= $userStories->count(),
            ];
        })->values();

        return response()->json(['stories' => $grouped]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tales_content'    => 'nullable|string|max:2000',
            'files_talesexten' => 'nullable|string',
            'text_color'       => 'nullable|string',
            'bgnd_color'       => 'nullable|string',
            'type'             => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();

        $story = TalesExten::create([
            'specialcode'      => $user->specialcode,
            'tales_content'    => $request->tales_content,
            'files_talesexten' => $request->files_talesexten,
            'tales_datetime'   => now(),
            'username'         => $user->username,
            'text_color'       => $request->text_color ?? '#ffffff',
            'bgnd_color'       => $request->bgnd_color ?? '#000000',
            'type'             => $request->type ?? 'text',
        ]);

        return response()->json(['story' => $this->formatStory($story, false)], 201);
    }

    public function show(Request $request, $id)
    {
        $userId = $request->user()->id;
        $story  = TalesExten::find($id);

        if (!$story) {
            return response()->json(['message' => 'Story not found'], 404);
        }

        $viewed = DB::table('tale_views')->where('viewer_id', $userId)->where('tale_id', $id)->exists();

        return response()->json(['story' => $this->formatStory($story, $viewed)]);
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
        $userId = $request->user()->id;
        $story  = TalesExten::find($id);

        if (!$story) {
            return response()->json(['message' => 'Story not found'], 404);
        }

        $existing = DB::table('tale_likes')->where('tale_id', $id)->where('user_id', $userId)->first();

        if ($existing) {
            DB::table('tale_likes')->where('tale_id', $id)->where('user_id', $userId)->delete();
            $story->decrement('likes');
            return response()->json(['liked' => false, 'likes' => $story->fresh()->likes]);
        }

        DB::table('tale_likes')->insert([
            'tale_id'    => $id,
            'user_id'    => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $story->increment('likes');

        return response()->json(['liked' => true, 'likes' => $story->fresh()->likes]);
    }

    public function view(Request $request, $id)
    {
        $userId = $request->user()->id;
        $story  = TalesExten::find($id);

        if (!$story) {
            return response()->json(['message' => 'Story not found'], 404);
        }

        $exists = DB::table('tale_views')->where('tale_id', $id)->where('viewer_id', $userId)->exists();

        if (!$exists) {
            DB::table('tale_views')->insert([
                'tale_id'    => $id,
                'viewer_id'  => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $story->increment('views');
        }

        return response()->json(['message' => 'View recorded']);
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

        DB::table('tale_comments')->insert([
            'tale_id'    => $id,
            'user_id'    => $user->id,
            'comment'    => $request->content,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Comment added']);
    }

    private function formatStory(TalesExten $story, bool $viewed): array
    {
        return [
            'id'         => $story->tales_id,
            'content'    => $story->tales_content,
            'file'       => $story->files_talesexten,
            'type'       => $story->type,
            'text_color' => $story->text_color,
            'bgnd_color' => $story->bgnd_color,
            'likes'      => (int) ($story->likes ?? 0),
            'views'      => (int) ($story->views ?? 0),
            'viewed'     => $viewed,
            'created_at' => $story->created_at,
            'expires_at' => $story->created_at ? Carbon::parse($story->created_at)->addHours(24) : null,
        ];
    }

    private function formatUser(UserRecord $user): array
    {
        return [
            'id'         => $user->id,
            'name'       => $user->name,
            'username'   => $user->username,
            'profileimg' => $user->profileimg ? (filter_var($user->profileimg, FILTER_VALIDATE_URL) ? $user->profileimg : url($user->profileimg)) : null,
        ];
    }
}
