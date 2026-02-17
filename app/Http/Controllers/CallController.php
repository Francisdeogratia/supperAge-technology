<?php

namespace App\Http\Controllers;

use App\Events\IncomingCallEvent;
use App\Events\CallAcceptedEvent;
use App\Events\CallEndedEvent;
// use App\Events\CallSignal;
use App\Events\CallDeclined;
use App\Events\UserMutedEvent;
use App\Events\UserVideoToggledEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\UserRecord;
use App\Models\Message;
use App\Models\Notification;
use App\Helpers\AgoraTokenBuilder;

class CallController extends Controller
{
    public function initiate(Request $request)
    {
        $userId = Session::get('id');
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Not logged in'], 401);
        }

        $validated = $request->validate([
            'receiver_id' => 'required|integer|exists:users_record,id',
            'call_type' => 'required|in:audio,video',
        ]);

        try {
            $callId = DB::table('calls')->insertGetId([
                'caller_id' => $userId,
                'receiver_id' => $validated['receiver_id'],
                'call_type' => $validated['call_type'],
                'status' => 'ringing',
                'started_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            event(new IncomingCallEvent(
                $callId, 
                $validated['receiver_id'], 
                $validated['call_type'], 
                $userId
            ));

            $agoraChannel = 'call_' . $callId;

            Log::info('✅ Call initiated', ['call_id' => $callId, 'agora_channel' => $agoraChannel]);

            return response()->json([
                'success' => true,
                'call_id' => $callId,
                'agora_channel' => $agoraChannel,
                'message' => 'Call initiated successfully.',
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Call initiation error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false, 
                'message' => 'Error initiating call: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showCallPage($callId)
    {
        $userId = Session::get('id');

        if (!$userId) {
            return redirect('/login')->with('error', 'You must be logged in.');
        }

        $call = DB::table('calls')->where('id', $callId)->first();

        if (!$call) {
            return redirect()->route('messages.index')->with('error', 'Call not found.');
        }

        if ($call->caller_id != $userId && $call->receiver_id != $userId) {
            return redirect()->route('messages.index')->with('error', 'Unauthorized access to call.');
        }

        $friendId = ($call->caller_id == $userId) ? $call->receiver_id : $call->caller_id;
        $user = UserRecord::find($userId);
        $friend = UserRecord::find($friendId);

        return view('calls.show', compact('user', 'friend', 'call'));
    }

    public function accept($callId)
    {
        try {
            $userId = Session::get('id');

            if (!$userId) {
                return response()->json(['success' => false, 'message' => 'Not logged in'], 401);
            }

            $call = DB::table('calls')->where('id', $callId)->first();

            if (!$call) {
                return response()->json(['success' => false, 'message' => 'Call not found'], 404);
            }

            if ($call->receiver_id != $userId) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            DB::table('calls')->where('id', $callId)->update([
                'status' => 'active',
                'answered_at' => now(),
                'updated_at' => now()
            ]);

            // Broadcast call accepted event
            event(new CallAcceptedEvent($callId, $call->caller_id, $call->receiver_id));
            
            Log::info('✅ Call accepted', ['call_id' => $callId]);

            return response()->json([
                'success' => true, 
                'message' => 'Call accepted',
                'call_id' => $callId
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Accept call error', [
                'call_id' => $callId,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'Error accepting call: ' . $e->getMessage()
            ], 500);
        }
    }

    public function decline($callId)
    {
        try {
            $userId = Session::get('id');

            if (!$userId) {
                return response()->json(['success' => false, 'message' => 'Not logged in'], 401);
            }

            $call = DB::table('calls')->where('id', $callId)->first();

            if (!$call) {
                return response()->json(['success' => false, 'message' => 'Call not found'], 404);
            }

            DB::table('calls')->where('id', $callId)->update([
                'status' => 'declined',
                'ended_at' => now(),
                'updated_at' => now()
            ]);

            $this->createCallMessage($call, 'declined');

            if ($call->caller_id != $userId) {
                $this->createMissedCallNotification($call, 'declined');
            }

            // ✅ BROADCAST DECLINED EVENT
            event(new CallDeclined($callId, $userId));

            Log::info('✅ Call declined', ['call_id' => $callId]);

            return response()->json(['success' => true, 'message' => 'Call declined']);

        } catch (\Exception $e) {
            Log::error('❌ Decline call error', [
                'call_id' => $callId,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false, 
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function end($callId)
    {
        try {
            $userId = Session::get('id');

            if (!$userId) {
                return response()->json(['success' => false, 'message' => 'Not logged in'], 401);
            }

            $call = DB::table('calls')->where('id', $callId)->first();

            if (!$call) {
                return response()->json(['success' => false, 'message' => 'Call not found'], 404);
            }

            // Guard: skip if call is already ended/no_answer/declined
            if (in_array($call->status, ['ended', 'no_answer', 'declined'])) {
                return response()->json(['success' => true, 'message' => 'Call already ended']);
            }

            $duration = null;
            if ($call->answered_at) {
                $duration = now()->diffInSeconds($call->answered_at);
            }

            DB::table('calls')->where('id', $callId)->update([
                'status' => 'ended',
                'ended_at' => now(),
                'duration' => $duration,
                'updated_at' => now()
            ]);

            $this->createCallMessage($call, 'ended', $duration);

            // ✅ BROADCAST ENDED EVENT
            event(new CallEndedEvent($callId, $userId, 'ended'));

            Log::info('✅ Call ended', [
                'call_id' => $callId,
                'duration' => $duration
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'Call ended', 
                'duration' => $duration
            ]);

        } catch (\Exception $e) {
            Log::error('❌ End call error', [
                'call_id' => $callId,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false, 
                'message' => $e->getMessage()
            ], 500);
        }
    }

   

public function sendSignal($callId, Request $request)
{
    try {
        // Accept both field names for compatibility
        $fromUserId = $request->input('from_user_id') ?? $request->input('user_id');

        if (!$fromUserId || !$request->input('signal')) {
            return response()->json(['success' => false, 'message' => 'Missing required fields'], 422);
        }

        $signal = $request->input('signal');

        \Log::info('📤 Signal send', [
            'call_id' => $callId,
            'from_user' => $fromUserId,
            'signal_type' => $signal['type'] ?? 'unknown',
        ]);

        // Verify call exists
        $call = \DB::table('calls')->where('id', $callId)->first();

        if (!$call) {
            return response()->json(['success' => false, 'message' => 'Call not found'], 404);
        }

        // Use CallSignal event (broadcastAs 'CallSignal')
        $event = new \App\Events\CallSignal(
            (int) $callId,
            (int) $fromUserId,
            $signal
        );

        event($event);

        return response()->json([
            'success' => true,
            'message' => 'Signal sent',
        ]);

    } catch (\Exception $e) {
        \Log::error('❌ SIGNAL SEND ERROR: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

public function toggleMedia(Request $request, $callId)
{
    try {
        $validated = $request->validate([
            'type' => 'required|in:audio,video',
            'state' => 'required|boolean',
        ]);

        $userId = Session::get('id');

        event(new \App\Events\ToggleMediaEvent(
            (int) $callId,
            (int) $userId,
            $validated['type'],
            $validated['state']
        ));

        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        Log::error('❌ Toggle media error', [
            'call_id' => $callId,
            'error' => $e->getMessage()
        ]);
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}


    public function timeout($callId)
    {
        try {
            $userId = Session::get('id');

            if (!$userId) {
                return response()->json(['success' => false, 'message' => 'Not logged in'], 401);
            }

            $call = DB::table('calls')->where('id', $callId)->first();

            if (!$call) {
                return response()->json(['success' => false, 'message' => 'Call not found'], 404);
            }

            // Guard: skip if call is already ended/no_answer/declined
            if (in_array($call->status, ['ended', 'no_answer', 'declined'])) {
                return response()->json(['success' => true, 'message' => 'Call already ended']);
            }

            DB::table('calls')->where('id', $callId)->update([
                'status' => 'no_answer',
                'ended_at' => now(),
                'updated_at' => now()
            ]);

            $this->createCallMessage($call, 'no_answer');
            $this->createMissedCallNotification($call, 'no_answer');

            event(new CallEndedEvent($callId, $userId, 'no_answer'));

            Log::info('Call timed out', ['call_id' => $callId]);

            return response()->json(['success' => true, 'message' => 'Call timed out']);

        } catch (\Exception $e) {
            Log::error('Timeout call error', [
                'call_id' => $callId,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function broadcastMute(Request $request, $callId)
    {
        try {
            $validated = $request->validate([
                'muted' => 'required|boolean'
            ]);
            
            $userId = Session::get('id');
            
            event(new UserMutedEvent(
                (int) $callId,
                (int) $userId,
                $validated['muted']
            ));
            
            Log::info('✅ Mute status broadcasted', [
                'call_id' => $callId,
                'user_id' => $userId,
                'muted' => $validated['muted']
            ]);
            
            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            Log::error('❌ Broadcast mute error', [
                'call_id' => $callId,
                'error' => $e->getMessage()
            ]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function broadcastVideo(Request $request, $callId)
    {
        try {
            $validated = $request->validate([
                'enabled' => 'required|boolean'
            ]);
            
            $userId = Session::get('id');
            
            event(new UserVideoToggledEvent(
                (int) $callId,
                (int) $userId,
                $validated['enabled']
            ));
            
            Log::info('✅ Video status broadcasted', [
                'call_id' => $callId,
                'user_id' => $userId,
                'enabled' => $validated['enabled']
            ]);
            
            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            Log::error('❌ Broadcast video error', [
                'call_id' => $callId,
                'error' => $e->getMessage()
            ]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function generateAgoraToken(Request $request)
    {
        $userId = Session::get('id');
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Not logged in'], 401);
        }

        $validated = $request->validate([
            'channel' => 'required|string',
        ]);

        $appId = config('services.agora.app_id');
        $appCertificate = config('services.agora.app_certificate');
        $channelName = $validated['channel'];
        $uid = (int) $userId;
        $expireTimeInSeconds = 3600;
        $currentTimestamp = time();
        $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

        $token = AgoraTokenBuilder::buildTokenWithUid(
            $appId,
            $appCertificate,
            $channelName,
            $uid,
            AgoraTokenBuilder::ROLE_PUBLISHER,
            $privilegeExpiredTs
        );

        return response()->json([
            'success' => true,
            'token' => $token,
            'uid' => $uid,
            'channel' => $channelName,
            'app_id' => $appId,
        ]);
    }

    private function createCallMessage($call, $status, $duration = null)
    {
        $callIcon = $call->call_type === 'video' ? '📹' : '📞';
        $statusIcon = [
            'ended' => '✅',
            'declined' => '❌',
            'no_answer' => '⏰',
            'missed' => '📵'
        ][$status] ?? '📞';

        $durationText = '';
        if ($duration !== null) {
            $minutes = floor($duration / 60);
            $seconds = $duration % 60;
            $durationText = sprintf(' • Duration: %02d:%02d', $minutes, $seconds);
        }

        $messageText = "{$statusIcon} {$callIcon} " . ucfirst($call->call_type) . " Call - " . ucfirst($status) . $durationText;

        Message::create([
            'sender_id' => $call->caller_id,
            'receiver_id' => $call->receiver_id,
            'message' => $messageText,
            'status' => 'sent',
            'is_forwarded' => false,
            'is_edited' => false,
        ]);
    }

    private function createMissedCallNotification($call, $reason)
    {
        $caller = UserRecord::find($call->caller_id);
        $callerName = $caller ? $caller->username : 'Someone';
        
        $callType = ucfirst($call->call_type);
        $reasonText = $reason === 'no_answer' ? 'No answer' : 'Declined';
        
        Notification::create([
            'user_id' => $call->caller_id,
            'message' => "Missed {$callType} Call from {$callerName} - {$reasonText}",
            'link' => route('messages.chat', $call->caller_id),
            'notification_reciever_id' => $call->receiver_id,
            'read_notification' => 'no',
            'type' => 'missed_call',
            'notifiable_type' => 'App\Models\Call',
            'notifiable_id' => $call->id,
            'data' => json_encode([
                'call_id' => $call->id,
                'caller_id' => $call->caller_id,
                'call_type' => $call->call_type,
                'reason' => $reason
            ]),
        ]);
    }
}