<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\UserRecord;

class BroadcastAuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $channelName = $request->input('channel_name');
        $socketId = $request->input('socket_id');
        $sessionUserId = Session::get('id');
        
        Log::info('🎯 Custom Broadcast Auth Hit', [
            'channel_name' => $channelName,
            'socket_id' => $socketId,
            'session_user_id' => $sessionUserId,
            'has_session' => Session::has('id'),
        ]);
        
        // Check if user is authenticated
        if (!$sessionUserId) {
            Log::warning('❌ No session user ID found');
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Get the user
        $user = UserRecord::find($sessionUserId);
        if (!$user) {
            Log::warning('❌ User not found', ['user_id' => $sessionUserId]);
            return response()->json(['error' => 'User not found'], 403);
        }
        
        // Handle user channels (e.g., private-users.1)
        if (preg_match('/private-users\.(\d+)/', $channelName, $matches)) {
            $channelUserId = (int) $matches[1];
            
            Log::info('🔍 User Channel Authorization Check', [
                'channel_user_id' => $channelUserId,
                'session_user_id' => $sessionUserId,
                'match' => $channelUserId === (int) $sessionUserId
            ]);
            
            if ($channelUserId !== (int) $sessionUserId) {
                Log::warning('❌ User ID mismatch');
                return response()->json(['error' => 'Unauthorized for this channel'], 403);
            }
        }
        
        // Handle call channels (e.g., private-calls.106)
        if (preg_match('/private-calls\.(\d+)/', $channelName, $matches)) {
            $callId = (int) $matches[1];
            
            Log::info('🔍 Call Channel Authorization Check', [
                'call_id' => $callId,
                'session_user_id' => $sessionUserId,
            ]);
            
            // Verify the user is part of this call
            $call = DB::table('calls')->where('id', $callId)->first();
            
            if (!$call) {
                Log::warning('❌ Call not found', ['call_id' => $callId]);
                return response()->json(['error' => 'Call not found'], 403);
            }
            
            if ((int) $call->caller_id !== (int) $sessionUserId && 
                (int) $call->receiver_id !== (int) $sessionUserId) {
                Log::warning('❌ User not part of call', [
                    'user_id' => $sessionUserId,
                    'caller_id' => $call->caller_id,
                    'receiver_id' => $call->receiver_id
                ]);
                return response()->json(['error' => 'Not authorized for this call'], 403);
            }
            
            Log::info('✅ User is part of call', [
                'user_id' => $sessionUserId,
                'call_id' => $callId
            ]);
        }
        
        // Generate Pusher auth signature
        try {
            $pusher = new \Pusher\Pusher(
                config('broadcasting.connections.pusher.key'),
                config('broadcasting.connections.pusher.secret'),
                config('broadcasting.connections.pusher.app_id'),
                config('broadcasting.connections.pusher.options')
            );
            
            $auth = $pusher->socket_auth($channelName, $socketId);
            
            Log::info('✅ Authorization Success', [
                'user_id' => $user->id,
                'channel' => $channelName
            ]);
            
            return response()->json(json_decode($auth, true));
            
        } catch (\Exception $e) {
            Log::error('❌ Pusher auth error', [
                'error' => $e->getMessage(),
                'channel' => $channelName
            ]);
            return response()->json(['error' => 'Auth generation failed'], 500);
        }
    }
}