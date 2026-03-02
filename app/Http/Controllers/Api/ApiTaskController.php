<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApiTaskController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $tasks = DB::table('tasks')
            ->orderBy('category')
            ->orderBy('id')
            ->get();

        $completedIds = DB::table('task_user')
            ->where('user_id', $user->id)
            ->where('is_completed', true)
            ->pluck('task_id')
            ->toArray();

        return response()->json([
            'tasks'         => $tasks,
            'completed_ids' => $completedIds,
        ]);
    }

    public function complete(Request $request, $id)
    {
        $user = $request->user();

        $task = DB::table('tasks')->where('id', $id)->first();
        if (!$task) {
            return response()->json(['message' => 'Task not found.'], 404);
        }

        // Already completed?
        $already = DB::table('task_user')
            ->where('user_id', $user->id)
            ->where('task_id', $id)
            ->where('is_completed', true)
            ->exists();

        if ($already) {
            return response()->json(['message' => 'You already completed this task.'], 422);
        }

        // Mark completed
        DB::table('task_user')->insert([
            'user_id'      => $user->id,
            'task_id'      => $id,
            'is_completed' => true,
            'completed_at' => now(),
        ]);

        // Credit wallet
        DB::table('wallet_transactions')->insert([
            'wallet_owner_id' => $user->id,
            'payer_id'        => $user->id,
            'amount'          => $task->reward_points,
            'status'          => 'successful',
            'type'            => 'task_reward',
            'currency'        => 'NGN',
            'description'     => "Reward for completing task: {$task->title}",
            'transaction_id'  => uniqid('txn_task_'),
            'tx_ref'          => Str::uuid(),
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        return response()->json([
            'message' => "Task completed! You earned {$task->reward_points} NGN.",
            'reward'  => $task->reward_points,
        ]);
    }
}
