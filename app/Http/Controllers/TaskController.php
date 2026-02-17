<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\UserRecord;
use Illuminate\Support\Str;
use App\Models\TaskCenter;
use Illuminate\Support\Facades\DB;
class TaskController extends Controller
{
    // Show Task Center

public function index()
{
    $tasks = Task::orderBy('category')->get()->groupBy('category');

    $userId = Session::get('id');
    $user = $userId ? UserRecord::find($userId) : null;

    // ✅ Fetch tasks the user added to the center
    $userTasks = TaskCenter::where('is_active', true)
    ->orderByDesc('created_at')
    ->get();

$completedTaskIds = DB::table('task_user')
    ->where('user_id', $user->id)
    ->where('is_completed', true)
    ->pluck('task_id')
    ->toArray();


    return view('tasks.index', compact('tasks', 'user', 'userTasks', 'completedTaskIds'));

}






    // Mark a task as completed
    
public function complete(Task $task)
{
    $user = UserRecord::findOrFail(Session::get('id'));

    // Check if already completed
    if ($user->tasks()->where('task_id', $task->id)->wherePivot('is_completed', true)->exists()) {
        return back()->with('info', 'You already completed this task.');
    }

    // Mark as completed
    $user->tasks()->attach($task->id, [
        'is_completed' => true,
        'completed_at' => now(),
    ]);

    // Credit wallet
   WalletTransaction::create([
    'wallet_owner_id' => $user->id,
    'payer_id'        => $user->id,
    'amount'          => $task->reward_points,
    'status'          => 'successful',
    'type'            => 'task_reward',
    'currency'        => 'NGN',   // 👈 force NGN always
    'description'     => "Reward for completing task: {$task->title}",
    'transaction_id'  => uniqid('txn_from_points_'),
    'tx_ref'          => Str::uuid(),
]);





    return back()->with('success', "Task completed! You earned {$task->reward_points} NGN.");
}


public function leaderboard(Request $request)
{
    $period = $request->query('period', 'all');

    $userId = Session::get('id');
    $user = $userId ? UserRecord::find($userId) : null;

    $leaders = UserRecord::withSum(['walletTransactions as total_task_rewards' => function ($query) use ($period) {
        $query->where('status', 'successful')->where('type', 'task_reward');

        if ($period === 'today') {
            $query->whereDate('created_at', today());
        } elseif ($period === 'week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        }
    }], 'amount')
    ->orderByDesc('total_task_rewards')
    ->take(10)
    ->get();

    $myRank = null;
    $myPoints = 0;

    if ($user) {
        $myPoints = $user->walletTransactions()
            ->where('type', 'task_reward')
            ->where('status', 'successful')
            ->when($period === 'today', fn($q) => $q->whereDate('created_at', today()))
            ->when($period === 'week', fn($q) => $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]))
            ->sum('amount');

        $myRank = UserRecord::withSum(['walletTransactions as total_task_rewards' => function ($query) use ($period) {
                $query->where('status', 'successful')->where('type', 'task_reward');

                if ($period === 'today') {
                    $query->whereDate('created_at', today());
                } elseif ($period === 'week') {
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                }
            }], 'amount')
            ->having('total_task_rewards', '>', $myPoints)
            ->count() + 1;
    }

    // 👇 Add this block here
    $todayEarnings = $user
        ? $user->walletTransactions()
            ->where('type', 'task_reward')
            ->where('status', 'successful')
            ->whereDate('created_at', today())
            ->sum('amount')
        : 0;

    $dailyGoal = 100; // or whatever your target is

    return view('tasks.leaderboard', compact(
        'leaders',
        'period',
        'myRank',
        'myPoints',
        'user',
        'todayEarnings',
        'dailyGoal'
    ));
}



}
