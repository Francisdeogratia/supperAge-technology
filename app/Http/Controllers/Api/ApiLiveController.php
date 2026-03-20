<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\AgoraTokenBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\LiveStream;
use App\Models\LiveStreamComment;
use App\Models\LiveStreamLike;
use App\Models\LiveStreamViewer;

class ApiLiveController extends Controller
{
    public function index(Request $request)
    {
        $streams = LiveStream::with('creator')
            ->where('status', 'live')
            ->orderBy('viewer_count', 'desc')
            ->get();

        return response()->json([
            'streams' => $streams->map(fn($s) => $this->formatStream($s)),
        ]);
    }

    public function start(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();

        // End any existing active stream by this user
        LiveStream::where('creator_id', $user->id)->where('status', 'live')->update([
            'status'   => 'ended',
            'ended_at' => now(),
        ]);

        $stream = LiveStream::create([
            'creator_id'  => $user->id,
            'title'       => $request->title,
            'description' => $request->description,
            'stream_key'  => Str::random(32),
            'status'      => 'live',
            'started_at'  => now(),
        ]);

        $stream->load('creator');

        return response()->json(['stream' => $this->formatStream($stream)], 201);
    }

    public function token(Request $request, $id)
    {
        $stream = LiveStream::find($id);

        if (!$stream || $stream->status !== 'live') {
            return response()->json(['message' => 'Stream not found or ended'], 404);
        }

        $userId  = $request->user()->id;
        $appId   = config('services.agora.app_id', '');
        $appCert = config('services.agora.app_certificate', '');
        $channel = 'stream_' . $id;
        $role    = ($stream->creator_id === $userId) ? 1 : 2; // 1=publisher, 2=subscriber

        // Record viewer
        LiveStreamViewer::firstOrCreate([
            'stream_id' => $id,
            'user_id'   => $userId,
        ]);

        if ($stream->creator_id !== $userId) {
            $stream->increment('viewer_count');
        }

        $token = '';
        if ($appId && $appCert) {
            $expireTs = time() + 3600;
            $agoraRole = $role === 1 ? AgoraTokenBuilder::ROLE_PUBLISHER : AgoraTokenBuilder::ROLE_SUBSCRIBER;
            $token = AgoraTokenBuilder::buildTokenWithUid(
                $appId, $appCert, $channel, $userId, $agoraRole, $expireTs
            );
        }

        return response()->json([
            'token'      => $token,
            'app_id'     => $appId,
            'channel'    => $channel,
            'uid'        => $userId,
            'role'       => $role,
            'stream_key' => $stream->stream_key,
        ]);
    }

    public function comments(Request $request, $id)
    {
        $stream = LiveStream::find($id);

        if (!$stream) {
            return response()->json(['message' => 'Stream not found'], 404);
        }

        $since    = $request->get('since'); // ISO timestamp for polling
        $query    = LiveStreamComment::with('user')->where('stream_id', $id);

        if ($since) {
            $query->where('created_at', '>', $since);
        }

        $comments = $query->orderBy('created_at', 'asc')->limit(50)->get();

        return response()->json([
            'comments' => $comments->map(fn($c) => [
                'id'         => $c->id,
                'comment'    => $c->comment,
                'created_at' => $c->created_at,
                'user'       => $c->user ? [
                    'id'         => $c->user->id,
                    'name'       => $c->user->name,
                    'profileimg' => $c->user->profileimg ? (filter_var($c->user->profileimg, FILTER_VALIDATE_URL) ? $c->user->profileimg : url($c->user->profileimg)) : null,
                ] : null,
            ]),
        ]);
    }

    public function addComment(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user   = $request->user();
        $stream = LiveStream::find($id);

        if (!$stream || $stream->status !== 'live') {
            return response()->json(['message' => 'Stream not available'], 404);
        }

        $comment = LiveStreamComment::create([
            'stream_id' => $id,
            'user_id'   => $user->id,
            'comment'   => $request->comment,
        ]);

        return response()->json(['comment' => [
            'id'         => $comment->id,
            'comment'    => $comment->comment,
            'created_at' => $comment->created_at,
            'user'       => ['id' => $user->id, 'name' => $user->name, 'profileimg' => $user->profileimg],
        ]], 201);
    }

    public function like(Request $request, $id)
    {
        $userId = $request->user()->id;
        $stream = LiveStream::find($id);

        if (!$stream) {
            return response()->json(['message' => 'Stream not found'], 404);
        }

        LiveStreamLike::firstOrCreate(['stream_id' => $id, 'user_id' => $userId]);

        return response()->json(['liked' => true, 'likes' => LiveStreamLike::where('stream_id', $id)->count()]);
    }

    public function end(Request $request, $id)
    {
        $user   = $request->user();
        $stream = LiveStream::find($id);

        if (!$stream || $stream->creator_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $stream->update(['status' => 'ended', 'ended_at' => now()]);

        return response()->json(['message' => 'Stream ended']);
    }

    private function formatStream(LiveStream $stream): array
    {
        return [
            'id'           => $stream->id,
            'title'        => $stream->title,
            'description'  => $stream->description,
            'status'       => $stream->status,
            'viewer_count' => (int) $stream->viewer_count,
            'stream_key'   => $stream->stream_key,
            'started_at'   => $stream->started_at,
            'creator'      => $stream->creator ? [
                'id'         => $stream->creator->id,
                'name'       => $stream->creator->name,
                'username'   => $stream->creator->username,
                'profileimg' => $stream->creator->profileimg ? (filter_var($stream->creator->profileimg, FILTER_VALIDATE_URL) ? $stream->creator->profileimg : url($stream->creator->profileimg)) : null,
            ] : null,
        ];
    }
}
