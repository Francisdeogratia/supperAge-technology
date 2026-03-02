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

    public function leaderboard(Request $request)
    {
        $user   = $request->user();
        $period = $request->get('period', 'all');

        $query = function ($q) use ($period) {
            $q->where('status', 'successful')->where('type', 'task_reward');
            if ($period === 'today') $q->whereDate('created_at', today());
            elseif ($period === 'week') $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        };

        $leaders = \App\Models\UserRecord::withSum(['walletTransactions as total_task_rewards' => $query], 'amount')
            ->orderByDesc('total_task_rewards')
            ->take(10)
            ->get()
            ->map(fn($u) => [
                'id'                 => $u->id,
                'name'               => $u->name,
                'username'           => $u->username,
                'profileimg'         => $u->profileimg ? (filter_var($u->profileimg, FILTER_VALIDATE_URL) ? $u->profileimg : url($u->profileimg)) : null,
                'badge_status'       => $u->badge_status,
                'total_task_rewards' => (float) ($u->total_task_rewards ?? 0),
            ]);

        $myPoints = $user->walletTransactions()
            ->where('status', 'successful')->where('type', 'task_reward')
            ->when($period === 'today', fn($q) => $q->whereDate('created_at', today()))
            ->when($period === 'week', fn($q) => $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]))
            ->sum('amount');

        $myRank = \App\Models\UserRecord::withSum(['walletTransactions as total_task_rewards' => $query], 'amount')
            ->having('total_task_rewards', '>', $myPoints)
            ->count() + 1;

        $todayEarnings = $user->walletTransactions()
            ->where('type', 'task_reward')->where('status', 'successful')
            ->whereDate('created_at', today())->sum('amount');

        return response()->json([
            'leaders'        => $leaders,
            'my_rank'        => $myRank,
            'my_points'      => (float) $myPoints,
            'today_earnings' => (float) $todayEarnings,
            'daily_goal'     => 100,
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
