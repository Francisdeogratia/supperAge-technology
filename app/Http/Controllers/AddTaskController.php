<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\UserRecord;
use App\Models\SamplePost;
use App\Models\TalesExten;
use App\Models\WalletTransaction;
use App\Models\Task;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\TaskCenter;
use Jenssegers\Agent\Agent;
use App\Models\LoginDetail;
  use App\Models\Notification;
class AddTaskController extends Controller
{
    public function index()
    {
        $user = UserRecord::find(Session::get('id'));

        $posts = SamplePost::where('specialcode', $user->specialcode)->get();
        $tales = TalesExten::where('specialcode', $user->specialcode)->get();

        $balances = WalletTransaction::where('wallet_owner_id', $user->id)
    ->where('status', 'successful')
    ->select('currency', DB::raw('SUM(amount) as total'))
    ->groupBy('currency')
    ->pluck('total', 'currency')
    ->toArray();

return view('task_center', compact('user', 'posts', 'tales', 'balances'));

        // return view('task_center', compact('user', 'posts', 'tales'));
    }

    

public function store(Request $request)
{
    $user = UserRecord::findOrFail(Session::get('id'));

    $request->validate([
    'task_type' => 'required|string',
    'task_content' => 'required|string',
    'post_id' => 'nullable|integer', // ✅ Add this
    'price' => 'required|numeric|min:1',
    'currency' => 'required|string',
    'duration' => 'required|integer|min:1',
    'target' => 'required|integer|min:1',
]);


    $currency = strtoupper($request->currency);
    $price = $request->price;
    $target = $request->target;
    $totalBudget = $price * $target;

    // ✅ Check wallet balance
    $balance = WalletTransaction::where('wallet_owner_id', $user->id)
        ->where('status', 'successful')
        ->where('currency', $currency)
        ->sum('amount');

    if ($balance < $totalBudget) {
        return back()->with('error', "Insufficient {$currency} wallet balance for total budget of {$totalBudget}.");
    }

    // ✅ Deduct full budget
    WalletTransaction::create([
        'wallet_owner_id' => $user->id,
        'payer_id'        => $user->id,
        'amount'          => -$totalBudget,
        'currency'        => $currency,
        'status'          => 'successful',
        'type'            => 'task_budget',
        'description'     => "Budget for task: {$request->task_type}",
        'transaction_id'  => uniqid('txn_task_budget_'),
        'tx_ref'          => Str::uuid(),
    ]);

    // ✅ Create task
    // dd($request->all());

    $postId = $request->input('post_id'); // ✅ Get post_id from form

TaskCenter::create([
    'specialcode'      => $user->specialcode,
    'creator_id'       => $user->id,
    'task_type'        => $request->task_type,
    'task_content'     => $request->task_content,
    'post_id'          => $postId, // ✅ Link the task to the post
    'price'            => $price,
    'currency'         => $currency,
    'target'           => $target,
    'duration'         => $request->duration,
    'total_budget'     => $totalBudget,
    'max_participants' => null,
    'is_active'        => true,
]);



    return back()->with('success', 'Task created and full budget deducted.');
}

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
        'payer_id'        => $task->creator_id,
        'amount'          => $task->reward_points,
        'status'          => 'successful',
        'type'            => 'task_reward',
        'currency'        => $task->currency,
        'description'     => "Reward for completing task: {$task->title}",
        'transaction_id'  => uniqid('txn_task_reward_'),
        'tx_ref'          => Str::uuid(),
    ]);

    return back()->with('success', "Task completed! You earned {$task->reward_points} {$task->currency}.");
}


public function completeCustom($id)
{
    $user = UserRecord::findOrFail(Session::get('id'));
    $task = TaskCenter::findOrFail($id);

    if ($task->creator_id === $user->id) {
        return request()->ajax()
            ? response()->json(['status' => 'error', 'message' => 'You cannot complete your own task.'])
            : back()->with('error', 'You cannot complete your own task.');
    }

    // Check if already completed
    $alreadyCompleted = DB::table('task_user')
        ->where('user_id', $user->id)
        ->where('task_id', $task->id)
        ->exists();

    if ($alreadyCompleted) {
        return request()->ajax()
            ? response()->json(['status' => 'info', 'message' => 'You already completed this task.'])
            : back()->with('info', 'You already completed this task.');
    }

    // For follow_me tasks, check if user has followed the creator
    if ($task->task_type === 'follow_me') {
        $hasFollowed = DB::table('follow_tbl')
            ->where('sender_id', $user->id)
            ->where('receiver_id', $task->creator_id)
            ->exists();

        if (!$hasFollowed) {
            return request()->ajax()
                ? response()->json(['status' => 'error', 'message' => 'You must follow the creator to earn this reward.'])
                : back()->with('error', 'You must follow the creator to earn this reward.');
        }
    }

    // Check completions and remaining budget
    $completedCount = DB::table('task_user')
        ->where('task_id', $task->id)
        ->where('is_completed', true)
        ->count();

    if ($completedCount >= $task->target || $task->total_budget < $task->price || !$task->is_active) {
        $task->is_active = false;
        $task->save();

        return request()->ajax()
            ? response()->json(['status' => 'error', 'message' => 'This task has reached its completion limit or budget.'])
            : back()->with('error', 'This task has reached its completion limit or budget.');
    }

    // Mark as completed
    DB::table('task_user')->insert([
        'user_id' => $user->id,
        'task_id' => $task->id,
        'is_completed' => true,
        'completed_at' => now(),
    ]);

    // Credit user
    WalletTransaction::create([
        'wallet_owner_id' => $user->id,
        'payer_id'        => $task->creator_id,
        'amount'          => $task->price,
        'currency'        => $task->currency,
        'status'          => 'successful',
        'type'            => 'task_reward',
        'description'     => "Reward for completing community task: {$task->task_type}",
        'transaction_id'  => uniqid('txn_custom_task_'),
        'tx_ref'          => Str::uuid(),
    ]);

    // Update task budget and status
    $task->total_budget -= $task->price;
    $completedCount += 1;

    if ($completedCount >= $task->target || $task->total_budget < $task->price) {
        $task->is_active = false;
    }

    $task->save();

    if (request()->ajax()) {
        return response()->json([
            'status' => 'success',
            'message' => "Task completed! You earned {$task->price} {$task->currency}."
        ]);
    }

    return back()->with('success', "Task completed! You earned {$task->price} {$task->currency}.");
}

public function engage(Request $request, $id)
{
    $user = UserRecord::findOrFail(Session::get('id'));
    $taskId = $request->query('task_id');
    $task = TaskCenter::findOrFail($taskId);
    $post = SamplePost::findOrFail($id);

    // Optional: Check if user already completed
    $alreadyCompleted = DB::table('task_user')
        ->where('user_id', $user->id)
        ->where('task_id', $task->id)
        ->exists();

    if ($alreadyCompleted) {
        return back()->with('info', 'You already completed this task.');
    }

    return view('tasks.engage', compact('user', 'task', 'post'));
}

public function completeEngagement($id)
{
    $user = UserRecord::findOrFail(Session::get('id'));
    $task = TaskCenter::findOrFail($id);

    // Check if already completed
    $alreadyCompleted = DB::table('task_user')
        ->where('user_id', $user->id)
        ->where('task_id', $task->id)
        ->exists();

    if ($alreadyCompleted) {
        return back()->with('info', 'You already completed this task.');
    }

    // Check budget and target
    $completedCount = DB::table('task_user')
        ->where('task_id', $task->id)
        ->where('is_completed', true)
        ->count();

    if ($completedCount >= $task->target || $task->total_budget < $task->price || !$task->is_active) {
        $task->is_active = false;
        $task->save();
        return back()->with('error', 'This task has reached its completion limit or budget.');
    }

    // Mark as completed
    DB::table('task_user')->insert([
        'user_id' => $user->id,
        'task_id' => $task->id,
        'is_completed' => true,
        'completed_at' => now(),
    ]);

    WalletTransaction::create([
        'wallet_owner_id' => $user->id,
        'payer_id'        => $task->creator_id,
        'amount'          => $task->price,
        'currency'        => $task->currency,
        'status'          => 'successful',
        'type'            => 'task_reward',
        'description'     => "Reward for engagement_post task",
        'transaction_id'  => uniqid('txn_engage_task_'),
        'tx_ref'          => Str::uuid(),
    ]);

    $task->total_budget -= $task->price;
    if ($completedCount + 1 >= $task->target || $task->total_budget < $task->price) {
        $task->is_active = false;
    }
    $task->save();

    // return back()->with('success', "Task completed! You earned {$task->price} {$task->currency}.");
    return redirect()->route('task.center')->with('success', 'Task completed!');

}

public function trackEngagement(Request $request, $id)
{

    $user = UserRecord::findOrFail(Session::get('id'));
    $task = TaskCenter::findOrFail($id);
    $postId = $request->input('post_id');

    // ✅ Handle shared file upload
    $sharedFileUrl = $request->hasFile('shared_file')
        ? $request->file('shared_file')->store('thumbnails', 'public')
        : null;

    $sharedFileUrl = $sharedFileUrl ? asset('storage/' . $sharedFileUrl) : null;

    $liked = $request->has('liked');
    $commented = $request->has('commented');
    $shared = $request->has('shared');


    
    

    // Save like
if ($liked) {
    DB::table('engage_likes')->updateOrInsert([
        'user_id' => $user->id,
        'post_id' => $postId,
    ]);
}

// Save comment
if ($commented && $request->filled('comment_text')) {
    DB::table('engage_comments')->insert([
        'user_id' => $user->id,
        'post_id' => $postId,
        'content' => $request->input('comment_text'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

$sharedWith = $request->input('shared_with', []);

// Save shared recipients
if ($shared && !empty($sharedWith)) {
    foreach ($sharedWith as $recipientId) {
        DB::table('post_engagements')->updateOrInsert([
            'user_id' => $user->id,
            'task_id' => $task->id,
            'shared_to' => $recipientId,
        ], [
            'liked' => $liked,
            'commented' => $commented,
            'shared' => true,
            'is_completed' => ($liked && $commented && $shared),
            'updated_at' => now(),
        ]);

        // Optional: Notify recipient
    DB::table('notifications')->insert([
    'user_id' => $user->id, // sender
    'notification_reciever_id' => $recipientId, // receiver
    'message' => "{$user->username} shared a post with you!",
    'link' => route('posts.show', $postId),
    'type' => 'post_share',
    'notifiable_type' => 'App\Models\SamplePost',
    'notifiable_id' => $postId,
    'data' => json_encode([
        'post_id' => $postId,
        'shared_by' => $user->username,
        'shared_to' => $recipientId,
        'file_url' => $sharedFileUrl ?? null, // ✅ Replace with actual file URL if available
    ]), // ✅ Add this line
    'created_at' => now(),
    'updated_at' => now(),
]);





    }
}



    // Track engagement
    DB::table('post_engagements')->updateOrInsert(
        ['user_id' => $user->id, 'task_id' => $task->id],
        [
            'liked' => $liked,
            'commented' => $commented,
            'shared' => $shared,
            'is_completed' => ($liked && $commented && $shared),
            'updated_at' => now(),
        ]
    );

    // Reward if all completed
    if ($liked && $commented && $shared) {
        $alreadyCompleted = DB::table('task_user')
            ->where('user_id', $user->id)
            ->where('task_id', $task->id)
            ->exists();

        if (!$alreadyCompleted && $task->is_active && $task->total_budget >= $task->price) {
            DB::table('task_user')->insert([
                'user_id' => $user->id,
                'task_id' => $task->id,
                'is_completed' => true,
                'completed_at' => now(),
            ]);

            WalletTransaction::create([
                'wallet_owner_id' => $user->id,
                'payer_id'        => $task->creator_id,
                'amount'          => $task->price,
                'currency'        => $task->currency,
                'status'          => 'successful',
                'type'            => 'task_reward',
                'description'     => "Reward for engagement_post task",
                'transaction_id'  => uniqid('txn_engage_task_'),
                'tx_ref'          => Str::uuid(),
            ]);

            $task->total_budget -= $task->price;
            $task->is_active = ($task->total_budget >= $task->price);
            $task->save();
        }
    }

    return redirect()->route('task.center')->with('success', 'Engagement submitted!');
}

public function showNotifications()
{
    $userId = Session::get('id');
 $user = UserRecord::find($userId); // ✅ Get logged-in user
  

$notifications = Notification::where('notification_reciever_id', $userId)
    ->orderBy('created_at', 'desc')
    ->paginate(10);


    foreach ($notifications as $note) {
        // Get sender
        $sender = UserRecord::with('lastLoginSession')->find($note->user_id);
        $note->sender = $sender;

        // Get login session
        $loginSession = $sender->lastLoginSession ?? null;
        $note->loginSession = $loginSession;

        // Online status
        $note->isOnline = $loginSession && $loginSession->logout_at === null;
        $note->lastSeen = $loginSession && $loginSession->logout_at
            ? \Carbon\Carbon::parse($loginSession->logout_at)->diffForHumans()
            : 'Online now';

        // Device info
        $agent = new Agent();
        $agent->setUserAgent($loginSession->user_agent ?? '');
        $note->device = $agent->platform() . ' - ' . $agent->browser();
        $note->isMobile = $agent->isMobile();

        // ✅ Suspicious login detection — move this inside the loop
        $note->isSuspicious = false;
        $lastLogin = LoginDetail::where('user_record_id', $sender->id)
            ->orderByDesc('created_at')
            ->skip(1)
            ->first();

        if ($lastLogin) {
            $ipChanged = $lastLogin->ip_address !== $loginSession->ip_address;
            $deviceChanged = $lastLogin->user_agent !== $loginSession->user_agent;

            if ($ipChanged || $deviceChanged) {
                $note->isSuspicious = true;
            }
        }
    }
   // ✅ Pass $user to the view
    return view('user.notifications', compact('notifications', 'user'));
    // return view('user.notifications', compact('notifications'));
}

}

