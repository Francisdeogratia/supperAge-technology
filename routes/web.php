<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BroadcastAuthController;
// use Illuminate\Support\Facades\Broadcast;  // ⬅️ ADD THIS
use App\Http\Controllers\AccountController;

use App\Http\Controllers\UpdateController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TalesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PremiumController;
use App\Http\Controllers\AdminBadgeVerificationController;
use App\Http\Controllers\BadgeVerificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\WalletController;

use App\Http\Controllers\EachProfileController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\TaskController;

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\AddTaskController;
use App\Http\Controllers\VisitTaskController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\CallController;

use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupCallController;
use App\Http\Controllers\AgeAIController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LiveStreamController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\MarketplaceNotificationController;
use App\Http\Controllers\AdvertisingController;
// ADD THIS LINE:
use App\Http\Controllers\AdminAdvertisingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PaymentApplicationController;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cache;

use App\Models\UserRecord;
use App\Models\SamplePost;

use App\Http\Controllers\Admin\AdminTransactionController;

/*
|--------------------------------------------------------------------------
| Broadcasting Auth - MUST BE FIRST
|--------------------------------------------------------------------------
*/

Route::post('/broadcasting/auth', [BroadcastAuthController::class, 'authenticate'])
    ->name('broadcasting.auth');

// Test route to confirm it's working
Route::get('/test-broadcast-controller', function() {
    return (new BroadcastAuthController())->authenticate(new Request([
        'channel_name' => 'test-channel',
        'socket_id' => 'test-socket'
    ]));
});


// ========================================
// CALL ROUTES - Complete Call System
// ========================================
Route::middleware(['web'])->group(function () {
    
    // Initiate call
    Route::post('/calls/initiate', [CallController::class, 'initiate'])
        ->name('calls.initiate');
    
    // Show call page
    Route::get('/calls/{callId}', [CallController::class, 'showCallPage'])
        ->name('calls.show');
    
    // Call control actions
    Route::post('/calls/{callId}/accept', [CallController::class, 'accept'])
        ->name('calls.accept');
    
    Route::post('/calls/{callId}/decline', [CallController::class, 'decline'])
        ->name('calls.decline');
    
    Route::post('/calls/{callId}/end', [CallController::class, 'end'])
        ->name('calls.end');

    Route::post('/calls/{callId}/timeout', [CallController::class, 'timeout'])
        ->name('calls.timeout');

    // Agora token
    Route::post('/agora/token', [CallController::class, 'generateAgoraToken'])
        ->name('agora.token');

    // Media controls
    Route::post('/calls/{callId}/mute', [CallController::class, 'broadcastMute'])
        ->name('calls.mute');

    Route::post('/calls/{callId}/video', [CallController::class, 'broadcastVideo'])
        ->name('calls.video');

    Route::post('/calls/{callId}/toggle-media', [CallController::class, 'toggleMedia'])
        ->name('calls.toggleMedia');

});




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    // Logged-in users go to main feed
    if (Session::has('specialcode')) {
        return redirect('update');
    }

    // Returning users (who have registered before) see login page
    if (Cookie::has('has_account') || request()->cookie('has_account')) {
        return redirect('account');
    }

    // First-time visitors see landing page
    return view('index');
});








Route::get('/account', [AccountController::class, 'show']);
// for registration
Route::post('/register', [AccountController::class, 'register']);

// 🔥 CRITICAL: This allows navigation (GET) to the login page.
Route::get('/login', [AccountController::class, 'show'])->name('login'); 

// This handles the form submission.
Route::post('/login', [AccountController::class, 'login']);
// for login
// Route::post('/login', [AccountController::class, 'login']);

// 🌐 Add this line to map the standard /login URL to your show method
// Route::get('/login', [AccountController::class, 'show']);

// for psw forgot 
Route::post('/forgot', [AccountController::class, 'forgot']);
// for psw reset 
Route::view('/reset-password', 'reset-password');
Route::post('/password-update', [AccountController::class, 'updatePassword'])->name('password.update');
Route::view('/reset-password-success', 'reset-password-success');
// for updates
Route::get('/update', [UpdateController::class, 'show']);
// Route::get('/update', [UpdateController::class, 'show'])->name('update');



// ============================================
// ACCOUNT MANAGEMENT ROUTES
// Add these routes to your routes/web.php file
// ============================================



// Account Settings Routes (Protected by Session Check)
Route::middleware(['web'])->group(function () {
    
    // Show account settings page
    Route::get('/account-settings', [AccountController::class, 'showAccountSettings'])
        ->name('account.settings');
    
    // Deactivate account
    Route::post('/account/deactivate', [AccountController::class, 'deactivateAccount'])
        ->name('account.deactivate');
    
    // Activate account
    Route::post('/account/activate', [AccountController::class, 'activateAccount'])
        ->name('account.activate');
    
    // Delete account (soft delete)
    Route::post('/account/delete', [AccountController::class, 'deleteAccount'])
        ->name('account.delete');
    
    // Success page after account actions
    Route::get('/success', function() {
        return view('account-action-success');
    })->name('account.success');
});





Route::middleware(['admin'])->group(function () {
    Route::get('/admin/manage_users', [AdminController::class, 'manageUsers']);
    Route::post('/admin/users/update', [AdminController::class, 'updateUser']);
    Route::get('/admin/logins', [AdminController::class, 'showLogins']);
    
    Route::post('/admin/users/{id}/disable', [AdminController::class, 'disableUser'])->name('admin.users.disable');
    Route::post('/admin/users/{id}/enable-quick', [AdminController::class, 'enableUser'])->name('admin.users.enable'); // ✅ NEW ROUTE
});

Route::get('/logout', [AccountController::class, 'logout'])->name('logout');

// for tales 
Route::post('/share-tale', [TalesController::class, 'store'])->name('tales.store');
Route::post('/fetch-tales', [TalesController::class, 'fetchTales'])->name('fetch.tales');

Route::get('/viewtales/{id}', [TalesController::class, 'viewTale'])->name('view.tale');
Route::post('/tales/{id}/comment', [TalesController::class, 'postComment']);
Route::post('/tales/{id}/like', [TalesController::class, 'likeTale']);
Route::post('/tales/{id}/share', [TalesController::class, 'shareTale']);
Route::post('/tales/{id}/share-to', [TalesController::class, 'shareTaleTo']);


Route::post('/set-timezone', [TalesController::class, 'setTimezone']);
Route::get('/tale/{id}/edit', [TalesController::class, 'edit'])->name('tale.edit');
Route::delete('/tale/{id}', [TalesController::class, 'destroy'])->name('tale.delete');
Route::get('/tales/{taleId}/view-count', [TalesController::class, 'getViewCount']);


Route::post('/tales/{taleId}/view', [TalesController::class, 'registerView']);
Route::get('/tales/{taleId}/viewers', [TalesController::class, 'getViewers']);
Route::get('/tales/{taleId}/likers', [TalesController::class, 'getLikers']);
Route::get('/tales/{taleId}/sharers', [TalesController::class, 'getSharers']);


// for justediting tales
Route::put('/tale/{id}', [TalesController::class, 'update'])->name('tale.update');


Route::post('/submit-post', [PostController::class, 'store'])->name('submit.post');
// Route::get('/admin/manage_users', [AdminController::class, 'manageUsers']);

Route::get('/updateprofile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/updateprofile', [ProfileController::class, 'update'])->name('profile.update');
Route::post('/profile/upload-cover', [ProfileController::class, 'uploadCover'])->name('profile.uploadCover');

// for premuinm
Route::get('/premium', [PremiumController::class, 'premiumPage'])->name('premium');
// blue badge verification
Route::get('/blue-badge', [PremiumController::class, 'blueBadgePage'])->name('blue-badge');



Route::get('/pay/success', [PaymentController::class, 'paymentSuccess'])->name('pay.success');




Route::post('/submit-badge-requirements', [BadgeVerificationController::class, 'store'])
    ->name('submit.badge.requirements');

    Route::post('/admin/badge-verifications/{id}/update', [AdminBadgeVerificationController::class, 'updateStatus'])
    ->name('admin.badge.update');




    Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('verifications', [AdminController::class, 'index'])->name('verifications.index');
    Route::get('verifications/{id}', [AdminController::class, 'show'])->name('verifications.show');
});

// payments
Route::get('/start-payment', [PaymentController::class, 'startPayment'])
    ->name('payment.start');

Route::get('/payment-success', [PaymentController::class, 'paymentSuccess'])
    ->name('payment.success');

// follow and unfollow
Route::get('/my-followers', [UpdateController::class, 'showMyFollowers'])->name('my.followers');
Route::get('/my-following', [UpdateController::class, 'showFollowing']);

Route::post('/follow/{receiver_id}', [FollowController::class, 'follow'])->name('follow');
Route::post('/unfollow/{receiver_id}', [FollowController::class, 'unfollow'])->name('unfollow');
// Route::post('/follow/{receiver_id}', [FollowController::class, 'follow'])->name('follow');
// Route::delete('/unfollow/{receiver_id}', [FollowController::class, 'unfollow'])->name('unfollow');

// mutual followers
Route::get('/mutual-followers/{id}', [UpdateController::class, 'showMutualFollowers'])
     ->name('mutual.followers');

     Route::post('/remove-follower/{id}', [FollowController::class, 'removeFollower'])->name('remove.follower');
// Route::delete('/remove-follower/{id}', [FollowController::class, 'removeFollower'])->name('remove.follower');

Route::get('/people-you-may-follow', [UpdateController::class, 'showAllSuggestions'])->name('people.suggestions');

// wallet
Route::get('/mywallet', [WalletController::class, 'show'])->name('mywallet');
Route::post('/wallet/process', [WalletController::class, 'processPayment'])->name('wallet.process');
Route::get('/wallet/success', [WalletController::class, 'paymentSuccess'])->name('wallet.success');

Route::get('/wallet/transactions', [WalletController::class, 'loadTransactions'])->name('wallet.transactions');

// Show funding form for a specific wallet owner
Route::get('/fund-wallet/{id}', [WalletController::class, 'fundWallet'])->name('wallet.fund');

// Process funding for another user
Route::post('/fund-wallet/process', [WalletController::class, 'processFunding'])->name('wallet.fund.process');
Route::get('/wallet/filter', [WalletController::class, 'filterTransactions'])->name('wallet.filter');
// routes/web.php

Route::get('/profile/{id}', [EachProfileController::class, 'show'])->name('profile.show');

// verify from wallet 
Route::post('/badge/verify-from-wallet', [PaymentController::class, 'verifyFromWallet'])
    ->name('badge.verify.wallet');

    
// transfer money to users
Route::get('/wallet/transfer', [WalletController::class, 'showTransferPage'])
    ->name('wallet.transfer.page');

Route::post('/wallet/transfer', [WalletController::class, 'processMultiTransfer'])
    ->name('wallet.transfer.process');

// withdraw money
Route::get('/wallet/withdraw', [WithdrawalController::class, 'showForm'])
    ->name('wallet.withdraw');

Route::post('/wallet/withdraw', [WithdrawalController::class, 'processWithdrawal'])
    ->name('wallet.withdraw.process');

Route::post('/webhook/flutterwave', [WithdrawalController::class, 'webhook'])
    ->name('wallet.webhook');   // 👈 change here


Route::get('/wallet/banks/{country}', [WithdrawalController::class, 'getBanksByCountry'])
    ->name('wallet.banks.byCountry');

   Route::get('/api/get-rate', [PaymentController::class, 'getRate'])->withoutMiddleware(['auth']);



    // routes/web.php
Route::post('/notifications/read', function () {
    auth()->user()->unreadNotifications->markAsRead();
    return back();
})->name('notifications.read');



Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');

Route::get('/tasks/leaderboard', [TaskController::class, 'leaderboard'])->name('tasks.leaderboard');


Route::get('/register', [RegisterController::class, 'showForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'registertwo'])->name('register');



Route::get('/invite', [ReferralController::class, 'index'])->name('invite');
Route::get('/my-referrals', [ReferralController::class, 'myReferrals'])->name('referral.my');


Route::post('/track-install', [ReferralController::class, 'trackInstall']);
// or Route::post('/track-install', [InstallTrackerController::class, 'trackInstall']);

Route::get('/referral-click/{id}', [ReferralController::class, 'logClick'])->name('referral.click');



Route::get('/task-center', [AddTaskController::class, 'index'])->name('task.center');
Route::post('/task-center/store', [AddTaskController::class, 'store'])->name('task.store');
Route::post('/task-center/complete/{task}', [AddTaskController::class, 'complete'])->name('task.complete');

Route::post('/tasks/complete-custom/{id}', [AddTaskController::class, 'completeCustom'])->name('tasks.complete_custom');
Route::get('/visit-task/{id}', [VisitTaskController::class, 'redirect'])->name('tasks.visit');
// Route::get('/view-tales/{task_id}', [TaleController::class, 'showFromTask'])->name('view.tales');


Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');
// Route::post('/follow/{id}', [UpdateController::class, 'followUser'])->name('follow.user');



Route::post('/follow/{id}', [UpdateController::class, 'followUser'])->name('follow.user');
Route::get('/tasks/engage/{id}', [AddTaskController::class, 'engage'])->name('tasks.engage');
Route::post('/tasks/engage/complete/{id}', [AddTaskController::class, 'completeEngagement'])->name('tasks.engage.complete');
Route::post('/tasks/engage/track/{id}', [AddTaskController::class, 'trackEngagement'])->name('engagement.track');



Route::get('/posts/{id}/view', function ($id) {
    $userId = Session::get('id');

    DB::table('notifications')
        ->where('notification_reciever_id', $userId)
        ->where('notifiable_id', $id)
        ->update([
            'read_notification' => 'yes',
            'read_at' => now(),
        ]);

    return redirect()->route('posts.show', $id);
})->name('notifications.markAndRedirect');

Route::get('/my-posts', [PostController::class, 'all'])->name('posts.all');

// Show edit form
Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');

// Handle delete
Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.delete');

Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update');
Route::delete('/posts/bulk-delete', [PostController::class, 'bulkDelete'])->name('posts.bulkDelete');
Route::patch('/posts/{id}/cancel-schedule', [PostController::class, 'cancelSchedule'])->name('posts.cancelSchedule');
Route::patch('/posts/{id}/restore', [PostController::class, 'restore'])->name('posts.restore');
Route::delete('/posts/{id}/force-delete', [PostController::class, 'forceDelete'])->name('posts.forceDelete');
Route::get('/update', [PostController::class, 'showAllPosts'])->name('update');

// Likes
Route::post('/posts/{id}/like', [PostController::class, 'like'])->name('posts.like');

// Comments
Route::post('/posts/{id}/comment', [PostController::class, 'comment'])->name('posts.comment');

// Reposts
Route::post('/posts/{id}/repost', [PostController::class, 'repost'])->name('posts.repost');

// Shares
Route::post('/posts/{id}/share', [PostController::class, 'share'])->name('posts.share');

// Route::get('/notifications', [PostController::class, 'notifications'])->name('notifications.index');
Route::get('/posts/{id}/comments', [PostController::class, 'fetchComments']);
// Route::get('/posts/{id}/{type}/users', [PostController::class, 'fetchActionUsers']);
Route::get('/posts/{id}/{type}/users', [PostController::class, 'fetchActionUsers']);

// Route::post('/follow/{id}', [FollowController::class, 'followUser'])->name('follow.user');
Route::post('/posts/{id}/track-view', [PostController::class, 'trackView'])->name('posts.trackView');

Route::get('/posts/{id}/users/views', [PostController::class, 'viewers'])->name('posts.viewers');
// Route::get('/posts/{id}/reward-status', [PostController::class, 'rewardStatus'])->name('posts.rewardStatus');
Route::get('/posts/{id}/reward-status', [PostController::class, 'rewardStatus'])->name('posts.rewardStatus');

// routes/web.php
Route::post('/posts/{post}/repost', [PostController::class, 'repost_eachpost'])->name('posts.repost_eachpost');

// routes/web.php
Route::get('/notifications', [PostController::class, 'notifications'])->name('user.notifications');


// In routes/web.php
Route::post('/heartbeat', function() {
    $userId = Session::get('id');
    
    if ($userId) {
        DB::table('login_details')
            ->where('user_record_id', $userId)
            ->whereNull('logout_at')
            ->latest('created_at')
            ->limit(1)
            ->update(['updated_at' => now()]);
    }
    
    return response()->json(['status' => 'ok']);
})->name('heartbeat');


Route::post('/posts/track-share', [PostController::class, 'trackShare'])->name('posts.trackShare');

// In routes/web.php
Route::get('/friends', [FriendController::class, 'index'])->name('friends.index');
Route::post('/friends/send-request/{userId}', [FriendController::class, 'sendRequest'])->name('friends.sendRequest');
Route::post('/friends/accept/{requestId}', [FriendController::class, 'acceptRequest'])->name('friends.accept');
Route::post('/friends/reject/{requestId}', [FriendController::class, 'rejectRequest'])->name('friends.reject');
Route::post('/friends/cancel/{requestId}', [FriendController::class, 'cancelRequest'])->name('friends.cancel');
Route::post('/friends/unfriend/{userId}', [FriendController::class, 'unfriend'])->name('friends.unfriend');




Route::post('/notifications/{id}/mark-read', [PostController::class, 'markNotificationRead'])->name('notifications.markRead');
Route::post('/notifications/read-all', [PostController::class, 'markAllNotificationsRead'])->name('notifications.readAll');
Route::delete('/notifications/{id}', [PostController::class, 'deleteNotification'])->name('notifications.delete');
Route::delete('/notifications', [PostController::class, 'deleteAllNotifications'])->name('notifications.deleteAll');

Route::post('/posts/toggle-save', [PostController::class, 'toggleSave'])->name('posts.toggleSave');
Route::get('/saved-posts', [PostController::class, 'savedPosts'])->name('posts.saved');
Route::post('/posts/share-as-tale', [PostController::class, 'shareAsTale'])->name('posts.shareAsTale');


// In routes/web.php (Cleaned up version)

Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
Route::get('/messages/chat/{friendId}', [MessageController::class, 'chat'])->name('messages.chat');
Route::post('/messages/send/{friendId}', [MessageController::class, 'send'])->name('messages.send');
Route::get('/messages/new/{friendId}/{lastMessageId?}', [MessageController::class, 'getNewMessages'])->name('messages.getNew');
Route::post('/messages/typing/{friendId}', [MessageController::class, 'updateTyping'])->name('messages.typing');

Route::post('/messages/edit/{messageId}', [MessageController::class, 'edit'])->name('messages.edit');
Route::post('/messages/delete/{messageId}', [MessageController::class, 'delete'])->name('messages.delete'); // Keep one definition
Route::get('/messages/friends', [MessageController::class, 'getFriends'])->name('messages.friends');
Route::post('/messages/forward/{messageId}', [MessageController::class, 'forward'])->name('messages.forward'); // Keep one definition
Route::post('/messages/react/{messageId}', [MessageController::class, 'reactToMessage']);

// User actions
Route::post('/users/block', [MessageController::class, 'blockUser']);
Route::post('/users/unblock', [MessageController::class, 'unblockUser']);
Route::post('/users/report', [MessageController::class, 'reportUser']);


    // Chat actions
    Route::post('/messages/archive', [MessageController::class, 'archiveChat']);
    Route::post('/messages/unarchive', [MessageController::class, 'unarchiveChat']);

Route::post('/messages/transcribe-voice', [MessageController::class, 'transcribeVoice'])
    ->name('messages.transcribe');
    
    
  


    // Existing routes...

    // New Pin/Unpin Routes
    Route::post('/messages/pin/{friendId}/{messageId}', [MessageController::class, 'pinMessage'])->name('messages.pin');
    Route::post('/messages/unpin/{friendId}', [MessageController::class, 'unpinMessage'])->name('messages.unpin');

    // Page to manage archived chats and unarchive them
    Route::get('/messages/archived', [MessageController::class, 'archivedChats'])->name('messages.archived'); 

    // Page to manage blocked users and unblock them
    Route::get('/users/blocked', [MessageController::class, 'blockedUsers'])->name('users.blocked'); 



// Duplicate call routes removed (already defined above in lines 79-84)

// Any routes OUTSIDE this group (like custom API endpoints) would need 
// the middleware applied manually.



// Add this test route temporarily
Route::get('/test-broadcasting', function() {
    $userId = Session::get('id');
    
    if (!$userId) {
        return response()->json([
            'error' => 'Not logged in',
            'session_data' => Session::all()
        ]);
    }
    
    return response()->json([
        'user_id' => $userId,
        'broadcast_driver' => config('broadcasting.default'),
        'pusher_key' => config('broadcasting.connections.pusher.key'),
        'pusher_cluster' => config('broadcasting.connections.pusher.options.cluster'),
        'session_active' => true
    ]);
});



// Add this to routes/web.php temporarily for debugging

Route::post('/test-broadcast-auth', function(Request $request) {
    $sessionId = Session::get('id');
    
    return response()->json([
        'session_id' => $sessionId,
        'session_all' => Session::all(),
        'request_channel' => $request->input('channel_name'),
        'csrf_token' => $request->header('X-CSRF-TOKEN'),
        'cookies' => $request->cookies->all(),
        'has_session' => Session::has('id'),
    ]);
});


   






// Get user online status
Route::get('/api/users/{id}/online-status', function ($id) {
    // Check if user has recent activity (within last 5 minutes)
    $lastSeen = Cache::get('user_online_' . $id);
    $isOnline = $lastSeen && now()->diffInMinutes($lastSeen) < 5;
    
    return response()->json([
        'online' => $isOnline,
        'last_seen' => $lastSeen,
        'user_id' => (int) $id
    ]);
})->name('user.online.status');

// Update user online status (call this from your app.js or when user is active)
Route::post('/api/users/update-online-status', function (Request $request) {
    $userId = Session::get('id');
    
    if (!$userId) {
        return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
    }
    
    // Store timestamp of user's last activity
    Cache::put('user_online_' . $userId, now(), now()->addMinutes(10));
    
    return response()->json([
        'success' => true,
        'user_id' => $userId,
        'timestamp' => now()->toIso8601String()
    ]);
})->name('user.update.online');




 // Groups Routes
Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
Route::get('/groups/{id}', [GroupController::class, 'show'])->name('groups.show');
Route::put('/groups/{id}', [GroupController::class, 'update'])->name('groups.update');
Route::delete('/groups/{id}', [GroupController::class, 'delete'])->name('groups.delete');
Route::post('/groups/{id}/join', [GroupController::class, 'join'])->name('groups.join');

// Group Messages Routes
Route::post('/groups/{id}/messages', [GroupController::class, 'sendMessage'])->name('groups.sendMessage');
Route::get('/groups/{id}/messages/new/{lastId}', [GroupController::class, 'getNewMessages'])->name('groups.getNewMessages');
Route::post('/groups/messages/{id}/delete', [GroupController::class, 'deleteMessage'])->name('groups.deleteMessage');
Route::post('/groups/messages/{id}/edit', [GroupController::class, 'editMessage'])->name('groups.editMessage');
Route::post('/groups/messages/{id}/react', [GroupController::class, 'reactToMessage'])->name('groups.reactToMessage');

// Group Members Routes
Route::post('/groups/{id}/members', [GroupController::class, 'addMembers'])->name('groups.addMembers');
Route::delete('/groups/{id}/members/{memberId}', [GroupController::class, 'removeMember'])->name('groups.removeMember');

// Group Call Routes
Route::post('/group-calls/initiate', [GroupCallController::class, 'initiateCall'])->name('group-calls.initiate');
Route::get('/group-calls/{id}', [GroupCallController::class, 'show'])->name('group-calls.show');
Route::post('/group-calls/{id}/join', [GroupCallController::class, 'join'])->name('group-calls.join');
Route::post('/group-calls/{id}/decline', [GroupCallController::class, 'decline'])->name('group-calls.decline');
Route::post('/group-calls/{id}/leave', [GroupCallController::class, 'leave'])->name('group-calls.leave');
Route::post('/group-calls/{id}/end', [GroupCallController::class, 'end'])->name('group-calls.end');
Route::post('/groups/{id}/typing', [GroupController::class, 'updateTyping']); // ✅ NEW
// ✅ NEW: Check for active call in group
Route::get('/group-calls/check-active/{groupId}', [GroupCallController::class, 'checkActiveCall']);

Route::get('/groups/messages/{messageId}/read-count', [GroupController::class, 'getMessageReadCount']);


// Group message forward routes
Route::get('/groups/friends/list', [GroupController::class, 'getFriendsList'])->name('groups.friends.list');
Route::post('/groups/messages/{messageId}/forward', [GroupController::class, 'forwardMessage'])->name('groups.messages.forward');

// Pin/Unpin messages
Route::post('/groups/{groupId}/messages/{messageId}/pin', [GroupController::class, 'pinMessage']);
Route::post('/groups/{groupId}/messages/unpin', [GroupController::class, 'unpinMessage']);

// Report group
Route::post('/groups/{groupId}/report', [GroupController::class, 'reportGroup']);

// Duplicate group-calls.show removed (already defined above)



// AGE AI Routes
Route::get('/age-ai', [AgeAIController::class, 'index'])->name('age-ai.chat');
Route::post('/age-ai/chat', [AgeAIController::class, 'chat'])->name('age-ai.send');
Route::post('/age-ai/clear', [AgeAIController::class, 'clearHistory'])->name('age-ai.clear');

// Event Routes
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
Route::post('/events', [EventController::class, 'store'])->name('events.store');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit'); // 👈 ADD THIS
Route::post('/events/{id}/rsvp', [EventController::class, 'rsvp'])->name('events.rsvp');
Route::post('/events/{id}/cancel-rsvp', [EventController::class, 'cancelRsvp'])->name('events.cancelRsvp');
Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update');
Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');




 // Live Streaming Routes
// use App\Http\Controllers\LiveStreamController;

// Live Streaming Routes
Route::middleware(['user.session'])->group(function () {
    Route::get('/live', [LiveStreamController::class, 'index'])->name('live.index');
    Route::post('/live/start', [LiveStreamController::class, 'start'])->name('live.start');
    Route::get('/live/stream/{streamId}', [LiveStreamController::class, 'stream'])->name('live.stream');
    Route::get('/live/stream/{streamId}/viewers', [LiveStreamController::class, 'getViewerCount']);
    Route::post('/live/stream/{streamId}/leave', [LiveStreamController::class, 'leaveStream']);
    Route::post('/live/stream/{streamId}/end', [LiveStreamController::class, 'endStream']);
    
    // Live stream interactions
    Route::post('/live/stream/{streamId}/like', [LiveStreamController::class, 'likeStream'])->name('live.like');
    Route::post('/live/stream/{streamId}/comment', [LiveStreamController::class, 'addComment'])->name('live.comment');
    Route::get('/live/stream/{streamId}/comments', [LiveStreamController::class, 'getComments']);
});






// Pusher WebRTC signaling routes
Route::post('/live/pusher/viewer-join', [LiveStreamController::class, 'pusherViewerJoin']);
Route::post('/live/pusher/offer', [LiveStreamController::class, 'pusherOffer']);
Route::post('/live/pusher/answer', [LiveStreamController::class, 'pusherAnswer']);
Route::post('/live/pusher/ice-candidate', [LiveStreamController::class, 'pusherIceCandidate']);

Route::get('/live/stream/{streamId}/viewers-list', [LiveStreamController::class, 'getViewersList']);






// Marketplace Routes
// Route::middleware(['auth'])->group(function () {
    
    // Main marketplace page
    Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');
    
    // Store creation flow
    Route::get('/marketplace/create-store', [MarketplaceController::class, 'showCreateStore'])->name('marketplace.show-create-store');
    Route::post('/marketplace/process-payment', [MarketplaceController::class, 'processStorePayment'])->name('marketplace.process-payment');
    Route::post('/marketplace/pay-from-wallet', [MarketplaceController::class, 'payFromWallet'])->name('marketplace.pay-from-wallet');
    Route::get('/marketplace/payment-success', [MarketplaceController::class, 'paymentSuccess'])->name('marketplace.payment-success');
    Route::get('/marketplace/setup-store', [MarketplaceController::class, 'setupStore'])->name('marketplace.setup-store');
    Route::post('/marketplace/create-store', [MarketplaceController::class, 'createStore'])->name('marketplace.create-store');
    
    
    // ADD THIS NEW ROUTE ⬇️

    Route::post('/marketplace/update-store', [MarketplaceController::class, 'updateStore'])->name('marketplace.update-store');
    
    // Store management
    Route::get('/marketplace/my-store', [MarketplaceController::class, 'myStore'])->name('marketplace.my-store');
    Route::get('/marketplace/store/{slug}', [MarketplaceController::class, 'viewStore'])->name('marketplace.view-store');
    Route::get('/marketplace/analytics', [MarketplaceController::class, 'storeAnalytics'])->name('marketplace.analytics');
    
    // Subscription management
    Route::get('/marketplace/renew-subscription', [MarketplaceController::class, 'renewSubscription'])->name('marketplace.renew-subscription');
    Route::post('/marketplace/process-renewal', [MarketplaceController::class, 'processRenewal'])->name('marketplace.process-renewal');
    Route::get('/marketplace/renewal-success', [MarketplaceController::class, 'renewalSuccess'])->name('marketplace.renewal-success');
    
    // Product management (protected by subscription middleware)
    Route::middleware(['check.store.subscription'])->group(function () {
        Route::post('/marketplace/add-product', [MarketplaceController::class, 'addProduct'])->name('marketplace.add-product');
        Route::post('/marketplace/update-product/{id}', [MarketplaceController::class, 'updateProduct'])->name('marketplace.update-product');
        Route::delete('/marketplace/delete-product/{id}', [MarketplaceController::class, 'deleteProduct'])->name('marketplace.delete-product');
    });
    
    // Product viewing
    Route::get('/marketplace/product/{slug}', [MarketplaceController::class, 'viewProduct'])->name('marketplace.view-product');
    
    // Order management
    Route::post('/marketplace/place-order', [MarketplaceController::class, 'placeOrder'])->name('marketplace.place-order');
    Route::get('/marketplace/orders', [MarketplaceController::class, 'myOrders'])->name('marketplace.my-orders');
    Route::get('/marketplace/order/{orderNumber}', [MarketplaceController::class, 'orderDetails'])->name('marketplace.order-details');
    Route::post('/marketplace/update-order-status/{orderNumber}', [MarketplaceController::class, 'updateOrderStatus'])->name('marketplace.update-order-status');
    
    // Notifications
    Route::get('/marketplace/notifications', [MarketplaceNotificationController::class, 'index'])->name('marketplace.notifications');
    Route::post('/marketplace/notifications/{id}/mark-read', [MarketplaceNotificationController::class, 'markAsRead'])->name('marketplace.notification.mark-read');
    Route::get('/marketplace/notifications/mark-all-read', [MarketplaceNotificationController::class, 'markAllAsRead'])->name('marketplace.mark-all-read');
    Route::delete('/marketplace/notifications/{id}', [MarketplaceNotificationController::class, 'delete'])->name('marketplace.notification.delete');
    Route::get('/marketplace/notifications/count', [MarketplaceNotificationController::class, 'getCount'])->name('marketplace.notification.count');
    Route::get('/marketplace/notifications/latest', [MarketplaceNotificationController::class, 'getLatest'])->name('marketplace.notification.latest');
    
    // Utilities
    Route::get('/marketplace/conversion-rate', [MarketplaceController::class, 'getConversionRate'])->name('marketplace.conversion-rate');
// });

// Duplicate marketplace.view-store removed (already defined above)
    
    
   


    // Add these routes to your routes/web.php

// Advertising Routes
Route::get('/advertising', [AdvertisingController::class, 'index'])->name('advertising.index');
Route::get('/advertising/create', [AdvertisingController::class, 'create'])->name('advertising.create');
Route::post('/advertising', [AdvertisingController::class, 'store'])->name('advertising.store');
Route::get('/advertising/{ad}', [AdvertisingController::class, 'show'])->name('advertising.show');
Route::put('/advertising/{ad}', [AdvertisingController::class, 'update'])->name('advertising.update');
Route::delete('/advertising/{ad}', [AdvertisingController::class, 'destroy'])->name('advertising.destroy');
Route::post('/advertising/{ad}/impression', [AdvertisingController::class, 'trackImpression'])->name('advertising.track-impression');
Route::post('/advertising/{ad}/click', [AdvertisingController::class, 'trackClick'])->name('advertising.track-click');


// Admin Advertising Routes (add middleware for admin check)
Route::middleware(['admin'])->prefix('admin')->group(function() {
    Route::get('/advertising', [AdminAdvertisingController::class, 'index'])->name('admin.advertising.index');
    Route::get('/advertising/{ad}', [AdminAdvertisingController::class, 'show'])->name('admin.advertising.show');
    Route::post('/advertising/{ad}/approve', [AdminAdvertisingController::class, 'approve'])->name('admin.advertising.approve');
    Route::post('/advertising/{ad}/reject', [AdminAdvertisingController::class, 'reject'])->name('admin.advertising.reject');
    Route::post('/advertising/{ad}/pause', [AdminAdvertisingController::class, 'pause'])->name('admin.advertising.pause');
    Route::delete('/advertising/{ad}', [AdminAdvertisingController::class, 'destroy'])->name('admin.advertising.destroy');



    
});

// IMPORTANT: Payment routes (MUST be outside admin middleware)
    Route::post('/advertising/pay-from-wallet', [AdvertisingController::class, 'payFromWallet'])->name('advertising.pay-from-wallet');
    Route::get('/advertising/conversion-rate', [AdvertisingController::class, 'getConversionRate'])->name('advertising.conversion-rate');
// For CPA tracking (external websites call this)
Route::post('/advertising/{ad}/action', [AdvertisingController::class, 'trackAction'])
    ->name('advertising.trackAction');


Route::get('/integration-guide', function () {
    return view('integration_guide');
})->name('integration.guide');


// Route::get('/advertising/conversion-rate', [AdvertisingController::class, 'getConversionRate'])
//     ->name('advertising.conversion-rate');


// about page
Route::get('/about', function () {
    return view('about');
})->name('about');



// Contact Page Routes
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');


// Settings & Policy Page
Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy-policy');


// FAQ Page
Route::get('/faq', function () {
    return view('faq');
})->name('faq');

// Sitemap
Route::get('/sitemap.xml', function () {
    $urls = [
        ['loc' => url('/'),                'priority' => '1.0',  'changefreq' => 'daily'],
        ['loc' => url('/account'),         'priority' => '0.9',  'changefreq' => 'monthly'],
        ['loc' => url('/login'),           'priority' => '0.8',  'changefreq' => 'monthly'],
        ['loc' => url('/register'),        'priority' => '0.9',  'changefreq' => 'monthly'],
        ['loc' => url('/about'),           'priority' => '0.7',  'changefreq' => 'monthly'],
        ['loc' => url('/contact'),         'priority' => '0.6',  'changefreq' => 'monthly'],
        ['loc' => url('/faq'),             'priority' => '0.6',  'changefreq' => 'monthly'],
        ['loc' => url('/privacy-policy'),  'priority' => '0.4',  'changefreq' => 'yearly'],
        ['loc' => url('/marketplace'),     'priority' => '0.8',  'changefreq' => 'daily'],
        ['loc' => url('/events'),          'priority' => '0.7',  'changefreq' => 'daily'],
        ['loc' => url('/advertising'),     'priority' => '0.6',  'changefreq' => 'weekly'],
        ['loc' => url('/blue-badge'),      'priority' => '0.5',  'changefreq' => 'monthly'],
        ['loc' => url('/premium'),         'priority' => '0.6',  'changefreq' => 'monthly'],
        ['loc' => url('/integration-guide'), 'priority' => '0.4', 'changefreq' => 'monthly'],
        ['loc' => url('/invite'),          'priority' => '0.5',  'changefreq' => 'monthly'],
    ];

    // Add public user profiles
    $users = UserRecord::select('id', 'updated_at')->limit(500)->get();
    foreach ($users as $u) {
        $urls[] = [
            'loc' => url('/profile/' . $u->id),
            'priority' => '0.5',
            'changefreq' => 'weekly',
            'lastmod' => $u->updated_at ? $u->updated_at->toW3cString() : now()->toW3cString(),
        ];
    }

    // Add public posts
    $posts = SamplePost::select('id', 'updated_at')->orderBy('id', 'desc')->limit(1000)->get();
    foreach ($posts as $p) {
        $urls[] = [
            'loc' => url('/posts/' . $p->id),
            'priority' => '0.6',
            'changefreq' => 'weekly',
            'lastmod' => $p->updated_at ? $p->updated_at->toW3cString() : now()->toW3cString(),
        ];
    }

    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach ($urls as $url) {
        $xml .= "  <url>\n";
        $xml .= "    <loc>" . htmlspecialchars($url['loc']) . "</loc>\n";
        if (isset($url['lastmod'])) {
            $xml .= "    <lastmod>" . $url['lastmod'] . "</lastmod>\n";
        }
        $xml .= "    <changefreq>" . $url['changefreq'] . "</changefreq>\n";
        $xml .= "    <priority>" . $url['priority'] . "</priority>\n";
        $xml .= "  </url>\n";
    }
    $xml .= '</urlset>';

    return response($xml, 200)->header('Content-Type', 'application/xml');
})->name('sitemap');












// ============================================
// REPLACE THESE LINES IN YOUR routes/web.php
// ============================================

// ❌ REMOVE OR COMMENT OUT THESE INCORRECT ROUTES:
// Route::delete('/unfollow/{receiver_id}', [FollowController::class, 'unfollow'])->name('unfollow');
// Route::post('/follow/{id}', [UpdateController::class, 'followUser'])->name('follow.user');
// Route::post('/follow/{id}', [FollowController::class, 'followUser'])->name('follow.user');

// ✅ ADD THESE CORRECT ROUTES:

// Follow/Unfollow Routes (Must be POST, not DELETE)
// Route::post('/follow/{receiver_id}', [FollowController::class, 'follow'])->name('follow');
// Route::post('/unfollow/{receiver_id}', [FollowController::class, 'unfollow'])->name('unfollow');

// Friend Request Routes
// Route::post('/friend/send-request/{receiverId}', [FriendController::class, 'sendRequest'])->name('friend.request.send');
// Route::post('/friend/accept-request/{requestId}', [FriendController::class, 'acceptRequest'])->name('friend.request.accept');
// Route::post('/friend/reject-request/{requestId}', [FriendController::class, 'rejectRequest'])->name('friend.request.reject');
// Route::post('/friend/cancel-request/{requestId}', [FriendController::class, 'cancelRequest'])->name('friend.request.cancel');


use App\Http\Controllers\SearchController;

// User Search Route
Route::get('/search/users', [SearchController::class, 'searchUsers'])->name('search.users');
// Add these routes to routes/web.php

// Profile routes
// Route::get('/profile/{id}', [EachProfileController::class, 'show'])->name('profile.show');
// Route::get('/profile/{id}/edit', [EachProfileController::class, 'edit'])->name('profile.edit');
// Route::post('/profile/{id}/update', [EachProfileController::class, 'update'])->name('profile.update');

// OR if you're using the PostController for profiles:
// Route::get('/profile/{id}', [PostController::class, 'showUserProfile'])->name('profile.show');

// Wallet funding
// Route::get('/wallet/fund/{id}', [WalletController::class, 'showFundForm'])->name('wallet.fund');
// Route::post('/wallet/fund/{id}', [WalletController::class, 'processFund'])->name('wallet.fund.process');


Route::post('/fetch-link-preview', [PostController::class, 'fetchLinkPreview'])->name('posts.fetchLinkPreview');

// Add these routes to your web.php file



// Add this route for tales link preview
Route::post('/tales/fetch-link-preview', [TalesController::class, 'fetchLinkPreview'])->name('tales.fetchLinkPreview');

// Your existing tales routes
// Route::post('/share-tale', [TalesController::class, 'store']);
// Route::post('/fetch-tales', [TalesController::class, 'fetchTales']);
// Route::get('/viewtales/{id}', [TalesController::class, 'viewTale'])->name('view.tale');
// // ... other routes
Route::post('/messages/fetch-link-preview', [MessageController::class, 'fetchLinkPreview'])
    ->name('messages.fetchLinkPreview');



// Add this route to your web.php file
Route::post('/groups/link-preview', [GroupController::class, 'getLinkPreview'])->name('groups.link-preview');


// Admin Monetization Routes
// Route::middleware(['auth'])->group(function () {
//     Route::get('/admin/monetization', [App\Http\Controllers\MonetizationController::class, 'index'])
//         ->name('admin.monetization.index');
    
//     Route::get('/admin/monetization/user/{id}/details', [App\Http\Controllers\MonetizationController::class, 'userDetails'])
//         ->name('admin.monetization.user.details');
    
//     Route::post('/admin/monetization/user/{id}/approve', [App\Http\Controllers\MonetizationController::class, 'approve'])
//         ->name('admin.monetization.approve');
    
//     Route::post('/admin/monetization/user/{id}/suspend', [App\Http\Controllers\MonetizationController::class, 'suspend'])
//         ->name('admin.monetization.suspend');
    
//     Route::post('/admin/monetization/user/{id}/message', [App\Http\Controllers\MonetizationController::class, 'sendMessage'])
//         ->name('admin.monetization.message');
// });


// Monetization Dashboard Routes (place AFTER authentication routes)
Route::get('/admin/monetization', [App\Http\Controllers\MonetizationController::class, 'index'])
    ->name('admin.monetization.index');

Route::get('/admin/monetization/user/{id}/details', [App\Http\Controllers\MonetizationController::class, 'userDetails'])
    ->name('admin.monetization.user.details');

Route::post('/admin/monetization/user/{id}/approve', [App\Http\Controllers\MonetizationController::class, 'approve'])
    ->name('admin.monetization.approve');

Route::post('/admin/monetization/user/{id}/suspend', [App\Http\Controllers\MonetizationController::class, 'suspend'])
    ->name('admin.monetization.suspend');

Route::post('/admin/monetization/user/{id}/message', [App\Http\Controllers\MonetizationController::class, 'sendMessage'])
    ->name('admin.monetization.message');



    






use App\Http\Controllers\GeneralAdminController;

// Admin Routes (protected by middleware in controller)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [GeneralAdminController::class, 'dashboardNow'])->name('dashboard.now');
    
    // Users Management
    Route::get('/users', [GeneralAdminController::class, 'usersNow'])->name('users.now');
    Route::get('/users/{id}/edit', [GeneralAdminController::class, 'editUserNow'])->name('users.edit.now');
    Route::post('/users/{id}/update', [GeneralAdminController::class, 'updateUserNow'])->name('users.update.now');
    Route::post('/users/{id}/delete', [GeneralAdminController::class, 'deleteUserNow'])->name('users.delete.now');
    Route::post('/users/{id}/suspend', [GeneralAdminController::class, 'suspendUserNow'])->name('users.suspend.now');
    Route::post('/users/{id}/enable', [GeneralAdminController::class, 'enableUserNow'])->name('users.enable.now');
    Route::post('/users/{id}/toggle-lock', [GeneralAdminController::class, 'toggleLockNow'])->name('users.toggle-lock.now');
    Route::get('/users/{id}/access', [GeneralAdminController::class, 'accessUserAccountNow'])->name('users.access.now');
    
    // Posts & Tales
    Route::get('/users/{id}/posts', [GeneralAdminController::class, 'userPostsNow'])->name('users.posts.now');
    Route::post('/posts/{id}/delete', [GeneralAdminController::class, 'deletePostNow'])->name('posts.delete.now');
    Route::post('/posts/{id}/suspend', [GeneralAdminController::class, 'suspendPostNow'])->name('posts.suspend.now');
    
    Route::get('/users/{id}/tales', [GeneralAdminController::class, 'userTalesNow'])->name('users.tales.now');
    Route::post('/tales/{id}/delete', [GeneralAdminController::class, 'deleteTaleNow'])->name('tales.delete.now');
    Route::post('/tales/{id}/suspend', [GeneralAdminController::class, 'suspendTaleNow'])->name('tales.suspend.now');
    
    // Messaging
    Route::get('/users/{id}/message', [GeneralAdminController::class, 'messageUserNow'])->name('users.message.now');
    Route::post('/users/{id}/send-message', [GeneralAdminController::class, 'sendMessageNow'])->name('users.send-message.now');
    
    // Payment
    Route::post('/users/{id}/pay', [GeneralAdminController::class, 'payUserNow'])->name('users.pay.now');
    
    // Payment Applications
    Route::get('/payment-applications', [GeneralAdminController::class, 'paymentApplicationsNow'])->name('payment-applications.now');
    Route::post('/payment-applications/{id}/approve', [GeneralAdminController::class, 'approveApplicationNow'])->name('payment-applications.approve.now');
    Route::post('/payment-applications/{id}/reject', [GeneralAdminController::class, 'rejectApplicationNow'])->name('payment-applications.reject.now');
});

// User Payment Application Routes (Protected by session check)
Route::middleware(['web'])->group(function () {
    Route::get('/payment/apply', [PaymentApplicationController::class, 'show'])->name('payment.apply');
    Route::post('/payment/apply', [PaymentApplicationController::class, 'apply'])->name('payment.apply.submit');
    Route::post('/payment/applications/{id}/cancel', [PaymentApplicationController::class, 'cancel'])->name('payment.cancel');
});



// User Inbox Routes
Route::get('/inbox', [App\Http\Controllers\InboxController::class, 'index'])->name('inbox.index');
Route::get('/inbox/{id}', [App\Http\Controllers\InboxController::class, 'show'])->name('inbox.show');
Route::post('/inbox/{id}/mark-read', [App\Http\Controllers\InboxController::class, 'markAsRead'])->name('inbox.mark-read');
Route::post('/inbox/mark-all-read', [App\Http\Controllers\InboxController::class, 'markAllAsRead'])->name('inbox.mark-all-read');
Route::delete('/inbox/{id}', [App\Http\Controllers\InboxController::class, 'destroy'])->name('inbox.destroy');
Route::get('/inbox/unread-count', [App\Http\Controllers\InboxController::class, 'getUnreadCount'])->name('inbox.unread-count');



// Admin payment application details
Route::get('/admin/payment-applications/{id}/details', [GeneralAdminController::class, 'applicationDetailsNow'])
    ->name('admin.payment.application.details');

    Route::get('/admin/users/{id}/pending-application', [GeneralAdminController::class, 'getPendingApplication'])
    ->name('admin.users.pending.application');

// Admin User Reports Routes
Route::get('/admin/user-reports', [GeneralAdminController::class, 'userReportsNow'])->name('admin.user.reports');
Route::post('/admin/user-reports/{id}/warn', [GeneralAdminController::class, 'warnUserNow'])->name('admin.user.warn');
Route::post('/admin/user-reports/{id}/block', [GeneralAdminController::class, 'blockUserNow'])->name('admin.user.block');
Route::post('/admin/user-reports/{id}/delete', [GeneralAdminController::class, 'deleteReportedUserNow'])->name('admin.user.delete.reported');
Route::post('/admin/user-reports/{id}/dismiss', [GeneralAdminController::class, 'dismissReportNow'])->name('admin.report.dismiss');




Route::get('/admin/user-reports/{id}/details', [GeneralAdminController::class, 'reportDetailsNow'])->name('admin.report.details');


Route::get('/child-safety-policy', function () {
    return view('child-safety-policy');
})->name('child.safety.policy');









// Add these routes to your web.php file inside the admin middleware group

Route::prefix('admin')->middleware(['admin'])->group(function () {
    
    // Transaction Management Routes
    Route::get('/transactions', [AdminTransactionController::class, 'index'])
        ->name('admin.transactions.index');
    
    Route::post('/transactions/convert', [AdminTransactionController::class, 'convertCurrency'])
        ->name('admin.transactions.convert');
    
    Route::get('/transactions/filter', [AdminTransactionController::class, 'filterTransactions'])
        ->name('admin.transactions.filter');
    
    Route::get('/transactions/export', [AdminTransactionController::class, 'exportTransactions'])
        ->name('admin.transactions.export');
});









// Add to routes/web.php temporarily
Route::get('/clear-broadcasting-cache', function() {
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    return "Broadcasting cache cleared! Now test your calls.";
})->middleware('web');

// Full cache clear for deployment
Route::get('/clear-all-cache', function() {
    Artisan::call('optimize:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('event:clear');
    return 'All caches cleared successfully! Config, routes, views, cache, events all cleared.';
});




Route::get('/test-session', function() {
    return response()->json([
        'session_id' => Session::get('id'),
        'session_all' => Session::all(),
        'has_session' => Session::has('id'),
        'cookie' => request()->cookie(config('session.cookie'))
    ]);
})->middleware('web');



Route::get('/debug-auth', function() {
    return response()->json([
        'session_id' => Session::get('id'),
        'session_user_id' => Session::get('user_id'),
        'session_userid' => Session::get('userid'),
        'session_all' => Session::all(),
        'auth_check' => Auth::check(),
        'auth_id' => Auth::id(),
        'auth_user' => Auth::user(),
        'cookie_laravel_session' => request()->cookie('laravel_session'),
        'cookie_PHPSESSID' => request()->cookie('PHPSESSID'),
        'all_cookies' => request()->cookies->all()
    ]);
})->middleware('web');



Route::get('/check-broadcast-route', function() {
    $routes = collect(Route::getRoutes())->filter(function($route) {
        return str_contains($route->uri(), 'broadcasting');
    })->map(function($route) {
        return [
            'method' => implode('|', $route->methods()),
            'uri' => $route->uri(),
            'action' => $route->getActionName()
        ];
    });
    
    return response()->json([
        'broadcast_routes' => $routes,
        'total_routes' => count(Route::getRoutes())
    ]);
});



Route::post('/test-broadcast-session', function(Request $request) {
    return response()->json([
        'session_id' => Session::get('id'),
        'request_has_session_cookie' => $request->hasCookie('laravel_session'),
        'session_cookie_value' => $request->cookie('laravel_session'),
        'headers' => [
            'csrf' => $request->header('X-CSRF-TOKEN'),
            'referer' => $request->header('Referer'),
            'origin' => $request->header('Origin')
        ]
    ]);
});


Route::get('/test-pusher-config', function() {
    return response()->json([
        'broadcast_driver' => config('broadcasting.default'),
        'pusher_app_id' => config('broadcasting.connections.pusher.app_id'),
        'pusher_key' => config('broadcasting.connections.pusher.key'),
        'pusher_cluster' => config('broadcasting.connections.pusher.options.cluster'),
        'pusher_secret_set' => !empty(config('broadcasting.connections.pusher.secret')),
    ]);
});







// In routes/web.php - check this line exists:
// Route::get('/update', [UpdateController::class, 'show'])->name('update.show');

// Add this temporarily to routes/web.php to find your update route

// Route::get('/check-routes', function() {
//     $routes = collect(Route::getRoutes())->filter(function($route) {
//         return str_contains($route->uri(), 'update');
//     });
    
//     echo "<h2>Routes containing 'update':</h2>";
    
//     foreach ($routes as $route) {
//         echo "<div style='border:1px solid #ccc; padding:15px; margin:10px 0; background:#f9f9f9;'>";
//         echo "<strong>URI:</strong> " . $route->uri() . "<br>";
//         echo "<strong>Method:</strong> " . implode(', ', $route->methods()) . "<br>";
//         echo "<strong>Name:</strong> " . ($route->getName() ?? 'No name') . "<br>";
//         echo "<strong>Action:</strong> " . $route->getActionName() . "<br>";
        
        // Check if it's using UpdateController
    //     if (str_contains($route->getActionName(), 'UpdateController')) {
    //         echo "<span style='color:green; font-weight:bold;'>✅ Uses UpdateController</span><br>";
    //     } else {
    //         echo "<span style='color:red; font-weight:bold;'>❌ NOT using UpdateController!</span><br>";
    //     }
        
    //     echo "</div>";
    // }
    
//     echo "<hr><h2>Search in web.php for:</h2>";
//     echo "<pre style='background:#fff3cd; padding:10px;'>";
//     echo "Route::get('/update', ...);\n";
//     echo "Route::get('update', ...);\n";
//     echo "Route::any('/update', ...);\n";
//     echo "</pre>";
    
//     echo "<p>Look for these patterns and show me what you find!</p>";
// });

// Add this to routes/web.php temporarily for debugging


// Add this to routes/web.php temporarily for debugging

