<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\UserRecord;
use App\Models\LiveStream;
use App\Models\LiveStreamViewer;
use App\Models\WalletTransaction;
use App\Models\Notification;
use Illuminate\Support\Str;
use App\Models\LiveStreamLike;
use App\Models\LiveStreamComment;
use App\Helpers\AgoraTokenBuilder;
use Pusher\Pusher;

class LiveStreamController extends Controller
{
    /**
     * Show live streams page
     */
    public function index()
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'You must be logged in.');
        }
        
        $user = UserRecord::find($userId);
        
        // Get active live streams
        $liveStreams = LiveStream::with(['creator'])
            ->where('status', 'live')
            ->orderBy('viewer_count', 'desc')
            ->get();
        
        // Check if user has active stream
        $userActiveStream = LiveStream::where('creator_id', $userId)
            ->where('status', 'live')
            ->first();
        
        return view('live.index', compact('user', 'liveStreams', 'userActiveStream'));
    }
    
    /**
     * Start a new live stream
     */
    public function start(Request $request)
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return response()->json(['error' => 'Not logged in'], 401);
        }
        
        // Check if user already has active stream
        $existingStream = LiveStream::where('creator_id', $userId)
            ->where('status', 'live')
            ->first();
        
        if ($existingStream) {
            return response()->json(['error' => 'You already have an active stream'], 400);
        }
        
        $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string|max:500'
        ]);
        
        // Create live stream
        $stream = LiveStream::create([
            'creator_id' => $userId,
            'title' => $request->title,
            'description' => $request->description,
            'stream_key' => Str::random(32),
            'status' => 'live',
            'started_at' => now(),
            'viewer_count' => 0,
            'total_views' => 0,
            'peak_viewers' => 0,
            'reward_claimed' => false
        ]);
        
        // Notify followers
        $this->notifyFollowers($userId, $stream->id);
        
        return response()->json([
            'success' => true,
            'stream' => $stream,
            'stream_url' => route('live.stream', $stream->id)
        ]);
    }
    
    /**
     * Show live stream broadcast page
     */
    public function stream($streamId)
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'You must be logged in.');
        }
        
        $user = UserRecord::find($userId);
        $stream = LiveStream::with('creator')->findOrFail($streamId);
        
        if ($stream->status !== 'live') {
            return redirect()->route('live.index')->with('error', 'This stream has ended.');
        }
        
        $isCreator = $stream->creator_id == $userId;
        
        // If viewer, record view
        if (!$isCreator) {
            $this->recordView($streamId, $userId);
        }
        
        return view('live.stream', compact('user', 'stream', 'isCreator'));
    }
    
    /**
     * Record viewer join
     */
    /**
 * Record viewer join - ONE VIEW PER USER
 */

private function recordView($streamId, $userId)
{
    // Check if user has EVER viewed this stream (even if they left)
    $hasViewed = LiveStreamViewer::where('stream_id', $streamId)
        ->where('user_id', $userId)
        ->exists();
    
    // Only count as new view if they've never viewed before
    if (!$hasViewed) {
        $stream = LiveStream::findOrFail($streamId);
        $stream->increment('total_views');
        
        // Check for milestone after incrementing views
        $this->checkMilestone($stream->fresh());
    }
    
    // Check if currently viewing (for viewer count)
    $currentlyViewing = LiveStreamViewer::where('stream_id', $streamId)
        ->where('user_id', $userId)
        ->whereNull('left_at')
        ->first();
    
    if (!$currentlyViewing) {
        // Create new viewing session
        LiveStreamViewer::create([
            'stream_id' => $streamId,
            'user_id' => $userId,
            'joined_at' => now()
        ]);
        
        $stream = LiveStream::findOrFail($streamId);
        $stream->increment('viewer_count');
        
        // Update peak viewers
        if ($stream->viewer_count > $stream->peak_viewers) {
            $stream->update(['peak_viewers' => $stream->viewer_count]);
        }
    }
}
    /**
     * Check and reward 1000 views milestone
     */
    private function checkMilestone($stream)
    {
        if ($stream->total_views >= 1000 && !$stream->reward_claimed) {
            $reward = 15000;
            
            // Using the WalletTransaction model
            WalletTransaction::create([
                'wallet_owner_id' => $stream->creator_id,
                'payer_id' => $stream->creator_id,
                'transaction_id' => 'LIVE_REWARD_' . time(),
                'tx_ref' => 'LIVE_1K_' . $stream->id,
                'amount' => $reward,
                'type' => 'credit',
                'description' => '1K Live Views Reward',
                'status' => 'completed'
            ]);
            
            // Update user balance
            DB::table('users_record')
                ->where('id', $stream->creator_id)
                ->increment('balance', $reward);
            
            // Mark reward as claimed
            $stream->update(['reward_claimed' => true]);
            
            // Create notification
            Notification::create([
                'user_id' => $stream->creator_id,
                'message' => "🎉 Congratulations! You earned NGN 15,000 for reaching 1,000 live views!",
                'link' => route('mywallet'),
                'notification_reciever_id' => $stream->creator_id,
                'read_notification' => 'no',
                'type' => 'live_reward',
                'notifiable_type' => LiveStream::class,
                'notifiable_id' => $stream->id,
                'data' => json_encode([
                    'reward' => $reward,
                    'milestone' => 1000
                ])
            ]);
        }
    }

    /**
     * Like/Unlike a stream
     */
    public function likeStream($streamId)
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return response()->json(['error' => 'Not logged in'], 401);
        }
        
        $stream = LiveStream::findOrFail($streamId);
        
        $existingLike = LiveStreamLike::where('stream_id', $streamId)
            ->where('user_id', $userId)
            ->first();
        
        if ($existingLike) {
            // Unlike
            $existingLike->delete();
            $stream->decrement('like_count');
            $liked = false;
        } else {
            // Like
            LiveStreamLike::create([
                'stream_id' => $streamId,
                'user_id' => $userId
            ]);
            $stream->increment('like_count');
            $liked = true;
        }
        
        return response()->json([
            'success' => true,
            'liked' => $liked,
            'like_count' => $stream->fresh()->like_count
        ]);
    }

    /**
     * Add comment to stream with Pusher broadcast
     */
    public function addComment(Request $request, $streamId)
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return response()->json(['error' => 'Not logged in'], 401);
        }
        
        $request->validate([
            'comment' => 'required|string|max:500'
        ]);
        
        $stream = LiveStream::findOrFail($streamId);
        
        if ($stream->status !== 'live') {
            return response()->json(['error' => 'Stream has ended'], 400);
        }
        
        $comment = LiveStreamComment::create([
            'stream_id' => $streamId,
            'user_id' => $userId,
            'comment' => $request->comment
        ]);
        
        $user = UserRecord::find($userId);
        
        $commentData = [
            'id' => $comment->id,
            'user_name' => $user->name,
            'user_avatar' => $user->profileimg ?? asset('images/best3.png'),
            'comment' => $comment->comment,
            'created_at' => $comment->created_at->diffForHumans()
        ];
        
        // Broadcast via Pusher
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            ['cluster' => env('PUSHER_APP_CLUSTER')]
        );

        $pusher->trigger(
            'live-stream-' . $streamId,
            'new-comment',
            ['comment' => $commentData]
        );
        
        return response()->json([
            'success' => true,
            'comment' => $commentData
        ]);
    }

    /**
     * Get comments for stream
     */
    public function getLiveToken($streamId)
    {
        $userId = Session::get('id');
        if (!$userId) {
            return response()->json(['error' => 'Not logged in'], 401);
        }

        $stream = LiveStream::findOrFail($streamId);
        $isCreator = $stream->creator_id == $userId;
        $role = $isCreator ? AgoraTokenBuilder::ROLE_PUBLISHER : AgoraTokenBuilder::ROLE_SUBSCRIBER;
        $channel = 'live-' . $streamId;
        $expire  = time() + 7200; // 2 hours

        $token = AgoraTokenBuilder::buildTokenWithUid(
            env('AGORA_APP_ID'),
            env('AGORA_APP_CERTIFICATE'),
            $channel,
            (int) $userId,
            $role,
            $expire
        );

        return response()->json([
            'token'   => $token,
            'uid'     => (int) $userId,
            'appId'   => env('AGORA_APP_ID'),
            'channel' => $channel,
        ]);
    }

    public function getComments(Request $request, $streamId)
    {
        $after = (int) $request->query('after', 0);
        $query = LiveStreamComment::with('user')
            ->where('stream_id', $streamId);
        if ($after > 0) {
            $query->where('id', '>', $after);
        }
        $comments = $query->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function($comment) {
                return [
                    'id' => $comment->id,
                    'user_name' => $comment->user->name,
                    'user_avatar' => $comment->user->profileimg ?? asset('images/best3.png'),
                    'comment' => $comment->comment,
                    'created_at' => $comment->created_at->diffForHumans()
                ];
            });
        
        return response()->json(['comments' => $comments]);
    }
    
    /**
     * Get current viewer count
     */
    public function getViewerCount($streamId)
    {
        $userId = Session::get('id');
        $stream = LiveStream::findOrFail($streamId);
        
        $userLiked = false;
        if ($userId) {
            $userLiked = LiveStreamLike::where('stream_id', $streamId)
                ->where('user_id', $userId)
                ->exists();
        }
        
        return response()->json([
            'viewer_count' => $stream->viewer_count,
            'total_views' => $stream->total_views,
            'like_count' => $stream->like_count,
            'user_liked' => $userLiked,
            'reward_claimed' => $stream->reward_claimed,
            'show_reward' => $stream->total_views >= 1000 && !$stream->reward_claimed
        ]);
    }
    
    /**
     * Record viewer leave
     */
    public function leaveStream($streamId)
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return response()->json(['error' => 'Not logged in'], 401);
        }
        
        $viewer = LiveStreamViewer::where('stream_id', $streamId)
            ->where('user_id', $userId)
            ->where('left_at', null)
            ->first();
        
        if ($viewer) {
            $viewer->update(['left_at' => now()]);
            
            $stream = LiveStream::findOrFail($streamId);
            $stream->decrement('viewer_count');
        }
        
        return response()->json(['success' => true]);
    }
    
    /**
     * End live stream
     */
    public function endStream($streamId)
    {
        $userId = Session::get('id');
        $stream = LiveStream::findOrFail($streamId);
        
        if ($stream->creator_id != $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $stream->update([
            'status' => 'ended',
            'ended_at' => now()
        ]);
        
        // Mark all viewers as left
        LiveStreamViewer::where('stream_id', $streamId)
            ->whereNull('left_at')
            ->update(['left_at' => now()]);
        
        return response()->json([
            'success' => true,
            'message' => 'Stream ended successfully',
            'stats' => [
                'total_views' => $stream->total_views,
                'peak_viewers' => $stream->peak_viewers,
                'duration' => $stream->started_at->diffForHumans($stream->ended_at, true)
            ]
        ]);
    }
    
    /**
     * Notify followers about new stream
     */
    private function notifyFollowers($userId, $streamId)
    {
        $user = UserRecord::find($userId);
        
        $followerIds = DB::table('follow_tbl')
            ->where('receiver_id', $userId) 
            ->pluck('sender_id');
        
        foreach ($followerIds as $followerId) {
            Notification::create([
                'user_id' => $userId,
                'message' => "{$user->name} is now live!",
                'link' => route('live.stream', $streamId),
                'notification_reciever_id' => $followerId,
                'read_notification' => 'no',
                'type' => 'live_started',
                'notifiable_type' => LiveStream::class,
                'notifiable_id' => $streamId,
                'data' => json_encode(['creator_id' => $userId])
            ]);
        }
    }

    /**
     * Pusher: Viewer joined
     */
    public function pusherViewerJoin(Request $request)
    {
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            ['cluster' => env('PUSHER_APP_CLUSTER')]
        );

        $pusher->trigger(
            'live-stream-' . $request->streamId,
            'viewer-joined',
            ['viewerId' => Session::get('id')]
        );

        return response()->json(['success' => true]);
    }

    /**
     * Pusher: Send offer
     */
    public function pusherOffer(Request $request)
    {
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            ['cluster' => env('PUSHER_APP_CLUSTER')]
        );

        $pusher->trigger(
            'live-stream-' . $request->streamId,
            'stream-offer',
            ['offer' => $request->offer, 'viewerId' => $request->viewerId]
        );

        return response()->json(['success' => true]);
    }

    /**
     * Pusher: Send answer
     */
    public function pusherAnswer(Request $request)
    {
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            ['cluster' => env('PUSHER_APP_CLUSTER')]
        );

        $pusher->trigger(
            'live-stream-' . $request->streamId,
            'stream-answer',
            ['answer' => $request->answer]
        );

        return response()->json(['success' => true]);
    }

    /**
     * Pusher: Send ICE candidate
     */
    public function pusherIceCandidate(Request $request)
    {
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            ['cluster' => env('PUSHER_APP_CLUSTER')]
        );

        $pusher->trigger(
            'live-stream-' . $request->streamId,
            'ice-candidate',
            ['candidate' => $request->candidate]
        );

        return response()->json(['success' => true]);
    }



    /**
 * Get list of current viewers
 */
public function getViewersList($streamId)
{
    $stream = LiveStream::findOrFail($streamId);
    
    $viewers = LiveStreamViewer::with('user')
        ->where('stream_id', $streamId)
        ->whereNull('left_at')
        ->get()
        ->map(function($viewer) use ($stream) {
            return [
                'name' => $viewer->user->name,
                'avatar' => $viewer->user->profileimg ?? asset('images/best3.png'),
                'is_creator' => $viewer->user_id == $stream->creator_id
            ];
        });
    
    return response()->json(['viewers' => $viewers]);
}
}