<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\AgoraTokenBuilder;
use App\Models\UserRecord;
use App\Models\Message;
use App\Events\IncomingCallEvent;
use App\Events\CallAcceptedEvent;
use App\Events\CallDeclined;
use App\Events\CallEndedEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiCallController extends Controller
{
    public function initiate(Request $request)
    {
        $userId = $request->user()->id;

        $validated = $request->validate([
            'receiver_id' => 'required|integer|exists:users_record,id',
            'call_type'   => 'required|in:audio,video',
        ]);

        // Block check — don't allow calls between blocked users
        $isBlocked = DB::table('blocked_users')
            ->where(function ($q) use ($userId, $validated) {
                $q->where('blocker_id', $validated['receiver_id'])->where('blocked_id', $userId);
            })
            ->orWhere(function ($q) use ($userId, $validated) {
                $q->where('blocker_id', $userId)->where('blocked_id', $validated['receiver_id']);
            })
            ->exists();

        if ($isBlocked) {
            return response()->json(['success' => false, 'message' => 'Cannot call this user'], 403);
        }

        try {
            $callId = DB::table('calls')->insertGetId([
                'caller_id'   => $userId,
                'receiver_id' => $validated['receiver_id'],
                'call_type'   => $validated['call_type'],
                'status'      => 'ringing',
                'started_at'  => now(),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            $agoraChannel = 'call_' . $callId;

            event(new IncomingCallEvent(
                $callId,
                $validated['receiver_id'],
                $validated['call_type'],
                $userId
            ));

            return response()->json([
                'success'       => true,
                'call_id'       => $callId,
                'agora_channel' => $agoraChannel,
            ]);
        } catch (\Exception $e) {
            Log::error('ApiCallController::initiate error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function accept(Request $request, $callId)
    {
        $userId = $request->user()->id;

        $call = DB::table('calls')->where('id', $callId)->first();
        if (!$call) {
            return response()->json(['success' => false, 'message' => 'Call not found'], 404);
        }
        if ($call->receiver_id != $userId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        DB::table('calls')->where('id', $callId)->update([
            'status'      => 'active',
            'answered_at' => now(),
            'updated_at'  => now(),
        ]);

        event(new CallAcceptedEvent($callId, $call->caller_id, $call->receiver_id));

        return response()->json([
            'success'       => true,
            'call_id'       => $callId,
            'agora_channel' => 'call_' . $callId,
        ]);
    }

    public function decline(Request $request, $callId)
    {
        $userId = $request->user()->id;

        $call = DB::table('calls')->where('id', $callId)->first();
        if (!$call) {
            return response()->json(['success' => false, 'message' => 'Call not found'], 404);
        }

        if (in_array($call->status, ['ended', 'no_answer', 'declined'])) {
            return response()->json(['success' => true, 'message' => 'Already ended']);
        }

        DB::table('calls')->where('id', $callId)->update([
            'status'     => 'declined',
            'ended_at'   => now(),
            'updated_at' => now(),
        ]);

        $this->saveCallMessage($call, 'declined');
        event(new CallDeclined($callId, $userId));

        return response()->json(['success' => true]);
    }

    public function end(Request $request, $callId)
    {
        $userId = $request->user()->id;

        $call = DB::table('calls')->where('id', $callId)->first();
        if (!$call) {
            return response()->json(['success' => false, 'message' => 'Call not found'], 404);
        }

        if (in_array($call->status, ['ended', 'no_answer', 'declined'])) {
            return response()->json(['success' => true, 'message' => 'Already ended']);
        }

        $duration = $call->answered_at ? now()->diffInSeconds($call->answered_at) : null;

        DB::table('calls')->where('id', $callId)->update([
            'status'     => 'ended',
            'ended_at'   => now(),
            'duration'   => $duration,
            'updated_at' => now(),
        ]);

        $this->saveCallMessage($call, 'ended', $duration);
        event(new CallEndedEvent($callId, $userId, 'ended'));

        return response()->json(['success' => true, 'duration' => $duration]);
    }

    public function timeout(Request $request, $callId)
    {
        $userId = $request->user()->id;

        $call = DB::table('calls')->where('id', $callId)->first();
        if (!$call) {
            return response()->json(['success' => false, 'message' => 'Call not found'], 404);
        }

        if ($call->caller_id != $userId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if (in_array($call->status, ['ended', 'no_answer', 'declined', 'active'])) {
            return response()->json(['success' => true]);
        }

        DB::table('calls')->where('id', $callId)->update([
            'status'     => 'no_answer',
            'ended_at'   => now(),
            'updated_at' => now(),
        ]);

        $this->saveCallMessage($call, 'no_answer');
        event(new CallEndedEvent($callId, $userId, 'no_answer'));

        return response()->json(['success' => true]);
    }

    public function generateToken(Request $request)
    {
        $userId = $request->user()->id;

        $validated = $request->validate([
            'channel' => 'required|string',
        ]);

        $appId          = config('services.agora.app_id');
        $appCertificate = config('services.agora.app_certificate');
        $uid            = (int) $userId;
        $expireTs       = time() + 3600;

        $token = AgoraTokenBuilder::buildTokenWithUid(
            $appId,
            $appCertificate,
            $validated['channel'],
            $uid,
            AgoraTokenBuilder::ROLE_PUBLISHER,
            $expireTs
        );

        return response()->json([
            'success' => true,
            'token'   => $token,
            'uid'     => $uid,
            'channel' => $validated['channel'],
            'app_id'  => $appId,
        ]);
    }

    /**
     * Poll for an incoming call directed at the authenticated user.
     * Returns the most recent ringing call or null.
     */
    public function poll(Request $request)
    {
        $userId = $request->user()->id;

        $call = DB::table('calls')
            ->where('receiver_id', $userId)
            ->where('status', 'ringing')
            ->where('created_at', '>=', now()->subSeconds(60))
            ->orderByDesc('id')
            ->first();

        if (!$call) {
            return response()->json(['incoming' => null]);
        }

        $caller = UserRecord::find($call->caller_id);

        return response()->json([
            'incoming' => [
                'call_id'       => $call->id,
                'call_type'     => $call->call_type,
                'agora_channel' => 'call_' . $call->id,
                'caller_id'     => $call->caller_id,
                'caller_name'   => $caller?->name ?? 'Unknown',
                'caller_avatar' => $caller?->profileimg ?? null,
            ],
        ]);
    }

    /**
     * Check the current status of a call (used by outgoing caller to detect accept/decline).
     */
    public function status(Request $request, $callId)
    {
        $call = DB::table('calls')->where('id', $callId)->first();
        if (!$call) {
            return response()->json(['status' => 'not_found'], 404);
        }
        return response()->json(['status' => $call->status]);
    }

    private function saveCallMessage($call, string $status, ?int $duration = null): void
    {
        $icon    = $call->call_type === 'video' ? '📹' : '📞';
        $statusIcon = ['ended' => '✅', 'declined' => '❌', 'no_answer' => '⏰'][$status] ?? '📞';
        $durText = '';
        if ($duration !== null) {
            $durText = sprintf(' • %02d:%02d', intdiv($duration, 60), $duration % 60);
        }
        $text = "{$statusIcon} {$icon} " . ucfirst($call->call_type) . ' Call - ' . ucfirst(str_replace('_', ' ', $status)) . $durText;

        Message::create([
            'sender_id'   => $call->caller_id,
            'receiver_id' => $call->receiver_id,
            'message'     => $text,
            'status'      => 'sent',
            'is_forwarded'=> false,
            'is_edited'   => false,
        ]);
    }
}
