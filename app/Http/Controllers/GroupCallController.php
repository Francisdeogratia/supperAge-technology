<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Group;
use App\Models\GroupCall;
use App\Models\GroupCallParticipant;
use App\Models\UserRecord;
use App\Models\GroupMessage;
use App\Models\Notification;
use App\Events\GroupCallInitiated;

class GroupCallController extends Controller
{
    // public function initiateCall(Request $request)
    // {
    //     $userId = Session::get('id');
    //     $username = Session::get('username');
        
    //     if (!$userId) {
    //         return response()->json(['error' => 'Not logged in'], 401);
    //     }
        
    //     $request->validate([
    //         'group_id' => 'required|integer',
    //         'call_type' => 'required|in:audio,video'
    //     ]);
        
    //     $groupId = $request->group_id;
    //     $group = Group::findOrFail($groupId);
        
    //     // Check if user is a member
    //     if (!$group->isMember($userId)) {
    //         return response()->json(['error' => 'Not a member'], 403);
    //     }
        
    //     // Create group call
    //     $call = GroupCall::create([
    //         'group_id' => $groupId,
    //         'initiated_by' => $userId,
    //         'call_type' => $request->call_type,
    //         'status' => 'ringing',
    //         'started_at' => now()
    //     ]);
        
    //     // Add all group members as participants
    //     $members = $group->members;
    //     foreach ($members as $member) {
    //         GroupCallParticipant::create([
    //             'call_id' => $call->id,
    //             'user_id' => $member->user_id,
    //             'status' => $member->user_id == $userId ? 'joined' : 'ringing'
    //         ]);
            
    //         // Send notification to all members except initiator
    //         if ($member->user_id != $userId) {
    //             Notification::create([
    //                 'user_id' => $userId,
    //                 'message' => "{$username} started a {$request->call_type} call in {$group->name}",
    //                 'link' => route('group-calls.show', $call->id),
    //                 'notification_reciever_id' => $member->user_id,
    //                 'read_notification' => 'no',
    //                 'type' => 'group_call',
    //                 'notifiable_type' => GroupCall::class,
    //                 'notifiable_id' => $call->id,
    //                 'data' => json_encode([
    //                     'call_id' => $call->id,
    //                     'group_id' => $groupId,
    //                     'call_type' => $request->call_type
    //                 ])
    //             ]);
    //         }
    //     }
        
    //     // Broadcast to all group members
    //     broadcast(new GroupCallInitiated(
    //         $call->id,
    //         $groupId,
    //         $group->name,
    //         $request->call_type,
    //         $userId,
    //         $username
    //     ));
        
    //     return response()->json([
    //         'success' => true,
    //         'call_id' => $call->id
    //     ]);
    // }

public function initiateCall(Request $request)
{
    $userId = Session::get('id');
    $username = Session::get('username');
    
    if (!$userId) {
        return response()->json(['error' => 'Not logged in'], 401);
    }
    
    $request->validate([
        'group_id' => 'required|integer|exists:groups,id',
        'call_type' => 'required|in:audio,video'
    ]);
    
    $groupId = $request->input('group_id');
    $callType = $request->input('call_type');
    
    // Check if user is member
    $group = Group::findOrFail($groupId);
    $isMember = $group->members()->where('user_id', $userId)->exists();
    
    if (!$isMember) {
        return response()->json(['error' => 'You are not a member of this group'], 403);
    }
    
    // ✅ FIX: Check for existing active OR ongoing call
    $existingCall = GroupCall::where('group_id', $groupId)
        ->whereIn('status', ['ringing', 'ongoing'])
        ->first();
    
    if ($existingCall) {
        // Join existing call instead
        return response()->json([
            'success' => true,
            'call_id' => $existingCall->id,
            'message' => 'Joining existing call'
        ]);
    }
    
    // Create new call with 'ringing' status
    $call = GroupCall::create([
        'group_id' => $groupId,
        'initiated_by' => $userId,
        'call_type' => $callType,
        'status' => 'ringing', // ✅ Start as 'ringing'
        'started_at' => now()
    ]);
    
    // Add all group members as participants
    $members = $group->members()->get();
    
    foreach ($members as $member) {
        GroupCallParticipant::create([
            'call_id' => $call->id,
            'user_id' => $member->user_id,
            'status' => $member->user_id == $userId ? 'joined' : 'ringing',
            'joined_at' => $member->user_id == $userId ? now() : null
        ]);


        // After creating call and participants...

            // ✅ CREATE CALL MESSAGE IN CHAT
            GroupMessage::create([
                'group_id' => $groupId,
                'sender_id' => $userId,
                'message' => "{$username} started a {$callType} call",
                'message_type' => 'call',
                'call_type' => $callType,
                'call_id' => $call->id,
                'status' => 'sent',
            ]);

        // Send notification to all members except initiator
            if ($member->user_id != $userId) {
                Notification::create([
                    'user_id' => $userId,
                    'message' => "{$username} started a {$request->call_type} call in {$group->name}",
                    'link' => route('group-calls.show', $call->id),
                    'notification_reciever_id' => $member->user_id,
                    'read_notification' => 'no',
                    'type' => 'group_call',
                    'notifiable_type' => GroupCall::class,
                    'notifiable_id' => $call->id,
                    'data' => json_encode([
                        'call_id' => $call->id,
                        'group_id' => $groupId,
                        'call_type' => $request->call_type
                    ])
                ]);
            }
    }
    
    // Broadcast to all group members
    broadcast(new GroupCallInitiated(
        $call->id,
        $groupId,
        $group->name,
        $callType,
        $userId,
        $username
    ));
    
    return response()->json([
        'success' => true,
        'call_id' => $call->id,
        'agora_channel' => 'group_call_' . $call->id
    ]);
}
    
    public function show($callId)
    {
        $userId = Session::get('id');
    
    if (!$userId) { // ✅ Correct: Check for logged in user at start
        return redirect('/login')->with('error', 'You must be logged in.');
    }
        
        $user = UserRecord::find($userId);
        $call = GroupCall::with(['group', 'initiator', 'participants.user'])->findOrFail($callId);
        
        // Check if user is a participant
        $participant = $call->participants()->where('user_id', $userId)->first();
        
        if (!$participant) {
            return redirect()->route('groups.index')->with('error', 'You are not a participant in this call.');
        }
        
        $agoraChannel = 'group_call_' . $call->id;
        return view('group-calls.show', compact('user', 'call', 'participant', 'agoraChannel'));
    }
    
    // public function join($callId)
    // {
    //     $userId = Session::get('id');
        
    //     if (!$userId) {
    //         return response()->json(['error' => 'Not logged in'], 401);
    //     }
        
    //     $participant = GroupCallParticipant::where('call_id', $callId)
    //         ->where('user_id', $userId)
    //         ->firstOrFail();
        
    //     $participant->update([
    //         'status' => 'joined',
    //         'joined_at' => now()
    //     ]);
        
    //     $call = GroupCall::findOrFail($callId);
    //     $call->update(['status' => 'ongoing']);
        
    //     return response()->json(['success' => true]);
    // }

    public function join($callId)
{
    $userId = Session::get('id');
    
    $call = GroupCall::findOrFail($callId);
    $call->update(['status' => 'ongoing']);
    
    // Update participant status
    GroupCallParticipant::where('call_id', $callId)
        ->where('user_id', $userId)
        ->update([
            'status' => 'joined',
            'joined_at' => now()
        ]);
    
    return response()->json(['success' => true]);
}
    
    public function decline($callId)
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return response()->json(['error' => 'Not logged in'], 401);
        }
        
        $participant = GroupCallParticipant::where('call_id', $callId)
            ->where('user_id', $userId)
            ->firstOrFail();
        
        $participant->update([
            'status' => 'declined'
        ]);
        
        return response()->json(['success' => true]);
    }
    
    // public function leave($callId)
    // {
    //     $userId = Session::get('id');
        
    //     if (!$userId) {
    //         return response()->json(['error' => 'Not logged in'], 401);
    //     }
        
    //     $participant = GroupCallParticipant::where('call_id', $callId)
    //         ->where('user_id', $userId)
    //         ->firstOrFail();
        
    //     $participant->update([
    //         'status' => 'left',
    //         'left_at' => now()
    //     ]);
        
    //     // Check if all participants have left
    //     $call = GroupCall::findOrFail($callId);
    //     $activeParticipants = $call->participants()->where('status', 'joined')->count();
        
    //     if ($activeParticipants == 0) {
    //         $call->update([
    //             'status' => 'ended',
    //             'ended_at' => now(),
    //             'duration' => now()->diffInSeconds($call->started_at)
    //         ]);
    //     }
        
    //     return response()->json(['success' => true]);
    // }

    public function leave($callId)
{
    $userId = Session::get('id');

    if (!$userId) {
            return response()->json(['error' => 'Not logged in'], 401);
         }
    
    // Update participant status
    GroupCallParticipant::where('call_id', $callId)
        ->where('user_id', $userId)
        ->update([
            'status' => 'left',
            'left_at' => now()
        ]);
    
    // Check if any participants are still in the call
    $activeParticipants = GroupCallParticipant::where('call_id', $callId)
        ->where('status', 'joined')
        ->count();
    
    // If no one is left, end the call
    if ($activeParticipants === 0) {
    $call = GroupCall::findOrFail($callId);
    $duration = now()->diffInSeconds($call->started_at);
    
    $call->update([
        'status' => 'ended',
        'ended_at' => now(),
        'duration' => $duration
    ]);
    
    // ✅ UPDATE CALL MESSAGE WITH DURATION
    GroupMessage::where('call_id', $callId)
        ->where('message_type', 'call')
        ->update(['call_duration' => $duration]);
}
    
    return response()->json(['success' => true]);
}
    
    public function end($callId)
    {
        $userId = Session::get('id');
        $call = GroupCall::findOrFail($callId);
        
        // Only initiator can end call
        if ($call->initiated_by != $userId) {
            return response()->json(['error' => 'Only initiator can end call'], 403);
        }
        
        $call->update([
            'status' => 'ended',
            'ended_at' => now(),
            'duration' => now()->diffInSeconds($call->started_at)
        ]);
        
        // Update all participants
        $call->participants()->where('status', 'joined')->update([
            'status' => 'left',
            'left_at' => now()
        ]);
        
        return response()->json(['success' => true]);
    }
}