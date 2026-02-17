<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\UserRecord;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
*/

/**
 * User private channel - for incoming call notifications
 */
Broadcast::channel('users.{id}', function ($user, $id) {
    $sessionUserId = Session::get('id');
    
    Log::info('🔐 Auth attempt - User channel', [
        'channel' => "users.{$id}",
        'session_user' => $sessionUserId,
    ]);
    
    // Check if session user matches the requested channel
    if ($sessionUserId && (int) $sessionUserId === (int) $id) {
        $authorizedUser = UserRecord::find($sessionUserId);
        
        if ($authorizedUser) {
            Log::info('✅ Auth success - User channel', ['user_id' => $authorizedUser->id]);
            return $authorizedUser;
        }
    }
    
    Log::warning('❌ Auth failed - User channel');
    return false;
});

/**
 * User presence channel - for online/offline status
 */
Broadcast::channel('presence-users.{id}', function ($user, $id) {
    $sessionUserId = Session::get('id');
    
    // Anyone can join to check if user is online, but only return data for the actual user
    if ($sessionUserId) {
        $authorizedUser = UserRecord::find($sessionUserId);
        
        if ($authorizedUser) {
            return [
                'id' => $authorizedUser->id,
                'name' => $authorizedUser->name,
                'online' => true
            ];
        }
    }
    
    return false;
});

/**
 * Call private channel - for call events (answered, ended, signals, media toggles)
 */
Broadcast::channel('calls.{callId}', function ($user, $callId) {
    $sessionUserId = Session::get('id');
    
    Log::info('🔐 Auth attempt - Call channel', [
        'channel' => "calls.{$callId}",
        'session_user' => $sessionUserId,
    ]);
    
    // Get the call
    $call = DB::table('calls')->where('id', $callId)->first();
    
    // Check if user is part of this call (either caller or receiver)
    if ($call && $sessionUserId && 
        ((int) $call->caller_id === (int) $sessionUserId || 
         (int) $call->receiver_id === (int) $sessionUserId)) {
        
        $authorizedUser = UserRecord::find($sessionUserId);
        
        if ($authorizedUser) {
            Log::info('✅ Auth success - Call channel', [
                'user_id' => $authorizedUser->id,
                'call_id' => $callId,
            ]);
            return $authorizedUser;
        }
    }
    
    Log::warning('❌ Auth failed - Call channel', [
        'session_user' => $sessionUserId,
        'call_id' => $callId,
    ]);
    
    return false;
});

Broadcast::channel('group.{groupId}', function ($user, $groupId) {
    // Check if user is a member of the group
    $group = \App\Models\Group::find($groupId);
    return $group && $group->isMember($user->id);
});