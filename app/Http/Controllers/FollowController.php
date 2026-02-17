<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Follow;
use App\Models\UserRecord;
use Illuminate\Support\Facades\Session;
 use Illuminate\Support\Facades\DB;
use App\Models\WalletTransaction;

class FollowController extends Controller
{
    // Follow a user
   

public function follow(Request $request, $receiver_id)
{
    $sender_id = Session::get('id');
    $task_id = $request->input('task_id');

    if ($sender_id == $receiver_id) {
        // ✅ FIXED: Return proper JSON response
        return response()->json([
            'status' => 'error', 
            'message' => 'You cannot follow yourself.'
        ], 400);
    }

    $alreadyFollowing = Follow::where('sender_id', $sender_id)
                              ->where('receiver_id', $receiver_id)
                              ->exists();

    if ($alreadyFollowing) {
        // ✅ FIXED: Return proper JSON response
        return response()->json([
            'status' => 'error', 
            'message' => 'You are already following this user.'
        ], 400);
    }

    Follow::create([
        'sender_id'   => $sender_id,
        'receiver_id' => $receiver_id,
    ]);

    UserRecord::where('id', $receiver_id)->increment('number_followers');
    
    // ✅ ADDED: Get updated follower count
    $updatedFollowerCount = UserRecord::find($receiver_id)->number_followers;

    // Your existing task handling code stays the same
    if ($task_id) {
        try {
            DB::transaction(function () use ($task_id, $sender_id, $receiver_id) {
                $task = DB::table('task_center')->where('id', $task_id)->lockForUpdate()->first();

                if (!$task || $task->task_type !== 'follow_me' || $task->creator_id != $receiver_id) {
                    throw new \Exception('Invalid task.');
                }

                $alreadyRewarded = DB::table('task_user')
                    ->where('user_id', $sender_id)
                    ->where('task_id', $task_id)
                    ->exists();

                if ($alreadyRewarded) {
                    throw new \Exception('You already completed this task.');
                }

                $completedCount = DB::table('task_user')
                    ->where('task_id', $task_id)
                    ->where('is_completed', true)
                    ->count();

                $remainingBudget = $task->total_budget - ($completedCount * $task->price);

                if ($completedCount >= $task->target || $remainingBudget < $task->price || !$task->is_active) {
                    DB::table('task_center')->where('id', $task_id)->update(['is_active' => false]);
                    throw new \Exception('This task has reached its completion limit or budget.');
                }

                DB::table('task_user')->insert([
                    'user_id'      => $sender_id,
                    'task_id'      => $task_id,
                    'is_completed' => true,
                    'completed_at' => now(),
                ]);

                WalletTransaction::create([
                    'wallet_owner_id' => $sender_id,
                    'payer_id'        => $receiver_id,
                    'amount'          => $task->price,
                    'currency'        => $task->currency,
                    'status'          => 'successful',
                    'type'            => 'task_reward',
                    'description'     => "Reward for follow_me task",
                    'transaction_id'  => uniqid('txn_follow_task_'),
                    'tx_ref'          => \Illuminate\Support\Str::uuid(),
                ]);

                $newCompletedCount = $completedCount + 1;
                $newRemainingBudget = $task->total_budget - ($newCompletedCount * $task->price);

                DB::table('task_center')->where('id', $task_id)->update([
                    'is_active' => ($newCompletedCount >= $task->target || $newRemainingBudget < $task->price) ? false : true,
                ]);
            });

            // ✅ FIXED: Return proper JSON with all required fields
            return response()->json([
                'status'           => 'success',
                'success'          => true,
                'message'          => 'Followed and rewarded successfully.',
                'followers_count'  => UserRecord::find($receiver_id)->number_followers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    // ✅ FIXED: This is the key fix - return proper JSON response
    return response()->json([
        'status'          => 'success',
        'success'         => true,
        'message'         => 'You are now following this user.',
        'followers_count' => $updatedFollowerCount
    ]);
}

    // Unfollow a user
    public function unfollow(Request $request, $receiver_id)
{
    $sender_id = Session::get('id');

    $deleted = Follow::where('sender_id', $sender_id)
                     ->where('receiver_id', $receiver_id)
                     ->delete();

    if ($deleted) {
        UserRecord::where('id', $receiver_id)
                  ->where('number_followers', '>', 0)
                  ->decrement('number_followers');
    }

    // ✅ ADDED: Get updated follower count
    $updatedFollowerCount = UserRecord::find($receiver_id)->number_followers;

    // ✅ FIXED: Return proper JSON response (was checking expectsJson before)
    return response()->json([
        'success'         => true,
        'status'          => 'success',
        'message'         => 'You have unfollowed this user.',
        'followers_count' => $updatedFollowerCount
    ]);
}


    public function removeFollower(Request $request, $follower_id)
{
    $myId = Session::get('id');

    // Delete the row where THEY follow ME
    $deleted = Follow::where('sender_id', $follower_id)
                     ->where('receiver_id', $myId)
                     ->delete();

    if ($deleted) {
        UserRecord::where('id', $myId)
                  ->where('number_followers', '>', 0)
                  ->decrement('number_followers');
    }

    if ($request->expectsJson()) {
    return response()->json([
            'success' => true,
            'followers_count' => UserRecord::find($myId)->number_followers
        ]);
    }

    return back()->with('success', 'Follower removed.');
}

public function followUser(Request $request, $id)
{
    $senderId = Session::get('id');

    if (!$senderId || $senderId == $id) {
        return $request->ajax()
            ? response()->json(['error' => 'Invalid request'], 400)
            : redirect()->back()->with('error', 'Invalid request');
    }

    DB::table('follow_tbl')->updateOrInsert([
        'sender_id' => $senderId,
        'receiver_id' => $id
    ]);

    return $request->ajax()
        ? response()->json(['message' => 'Followed successfully'])
        : redirect()->back()->with('success', 'Followed successfully');
}


}
