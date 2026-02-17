<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\TaskCenter;
use Illuminate\Support\Str;

class VisitTaskController extends Controller
{
    public function redirect($id)
    {
        $userId = Session::get('id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please log in to complete tasks.');
        }

        $task = TaskCenter::findOrFail($id);

        // ✅ Check if task is still active
        $completedCount = DB::table('task_user')
            ->where('task_id', $task->id)
            ->where('is_completed', true)
            ->count();

        $remainingBudget = $task->total_budget - ($completedCount * $task->price);

        if ($completedCount >= $task->target || $remainingBudget < $task->price || !$task->is_active) {
            // Mark task inactive if exhausted
            $task->is_active = false;
            $task->save();

            return redirect()->back()->with('error', 'This task has reached its completion limit or budget.');
        }

        // ✅ Prevent same user from completing twice
        $alreadyCompleted = DB::table('task_user')
            ->where('user_id', $userId)
            ->where('task_id', $task->id)
            ->where('is_completed', true)
            ->exists();

        if (!$alreadyCompleted) {
            DB::transaction(function () use ($userId, $task, $completedCount, $remainingBudget) {
                // Mark as completed
                DB::table('task_user')->insert([
                    'user_id' => $userId,
                    'task_id' => $task->id,
                    'is_completed' => true,
                    'completed_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Credit wallet
                DB::table('wallet_transactions')->insert([
                    'wallet_owner_id' => $userId,
                    'payer_id'        => $task->creator_id,
                    'amount'          => $task->price,
                    'currency'        => $task->currency,
                    'status'          => 'successful',
                    'type'            => 'task_reward',
                    'description'     => "Reward for visiting site: {$task->task_type}",
                    'transaction_id'  => uniqid('txn_visit_'),
                    'tx_ref'          => Str::uuid(),
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);

                // Update task budget and status
                $task->total_budget -= $task->price;
                $newCompletedCount = $completedCount + 1;

                if ($newCompletedCount >= $task->target || $task->total_budget < $task->price) {
                    $task->is_active = false;
                }

                $task->save();
            });
        }

        // Redirect to the actual site
        return redirect()->away($task->task_content);
    }
}
