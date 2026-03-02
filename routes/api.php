<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiPostController;
use App\Http\Controllers\Api\ApiStoryController;
use App\Http\Controllers\Api\ApiMessageController;
use App\Http\Controllers\Api\ApiGroupController;
use App\Http\Controllers\Api\ApiLiveController;
use App\Http\Controllers\Api\ApiWalletController;
use App\Http\Controllers\Api\ApiNotificationController;
use App\Http\Controllers\Api\ApiProfileController;

/*
|--------------------------------------------------------------------------
| API Routes — SupperAge Mobile
|--------------------------------------------------------------------------
*/

// ── Public Auth ──────────────────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('login',    [ApiAuthController::class, 'login']);
    Route::post('register', [ApiAuthController::class, 'register']);
});

// ── Authenticated Routes ──────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::prefix('auth')->group(function () {
        Route::post('logout',          [ApiAuthController::class, 'logout']);
        Route::get('me',               [ApiAuthController::class, 'me']);
        Route::put('profile',          [ApiAuthController::class, 'updateProfile']);
        Route::put('password',         [ApiAuthController::class, 'updatePassword']);
    });

    // Posts / Feed
    Route::prefix('posts')->group(function () {
        Route::get('/',                [ApiPostController::class, 'feed']);
        Route::post('/',               [ApiPostController::class, 'store']);
        Route::get('saved',            [ApiPostController::class, 'saved']);
        Route::get('{id}',             [ApiPostController::class, 'show']);
        Route::put('{id}',             [ApiPostController::class, 'update']);
        Route::delete('{id}',          [ApiPostController::class, 'destroy']);
        Route::post('{id}/like',       [ApiPostController::class, 'like']);
        Route::get('{id}/comments',    [ApiPostController::class, 'comments']);
        Route::post('{id}/comments',   [ApiPostController::class, 'addComment']);
        Route::post('{id}/repost',     [ApiPostController::class, 'repost']);
        Route::post('{id}/save',       [ApiPostController::class, 'save']);
    });

    // Stories / Tales
    Route::prefix('stories')->group(function () {
        Route::get('/',                [ApiStoryController::class, 'index']);
        Route::post('/',               [ApiStoryController::class, 'store']);
        Route::get('{id}',             [ApiStoryController::class, 'show']);
        Route::delete('{id}',          [ApiStoryController::class, 'destroy']);
        Route::post('{id}/like',       [ApiStoryController::class, 'like']);
        Route::post('{id}/view',       [ApiStoryController::class, 'view']);
    });

    // Direct Messages
    Route::prefix('messages')->group(function () {
        Route::get('/',                [ApiMessageController::class, 'conversations']);
        Route::get('{friendId}',       [ApiMessageController::class, 'messages']);
        Route::post('{friendId}',      [ApiMessageController::class, 'send']);
        Route::post('{id}/read',       [ApiMessageController::class, 'markRead']);
    });

    // Groups
    Route::prefix('groups')->group(function () {
        Route::get('/',                    [ApiGroupController::class, 'index']);
        Route::post('/',                   [ApiGroupController::class, 'store']);
        Route::get('{id}',                 [ApiGroupController::class, 'show']);
        Route::get('{id}/messages',        [ApiGroupController::class, 'messages']);
        Route::post('{id}/messages',       [ApiGroupController::class, 'sendMessage']);
        Route::post('{id}/members',        [ApiGroupController::class, 'addMember']);
        Route::delete('{id}/members/{uid}',[ApiGroupController::class, 'removeMember']);
        Route::post('{id}/members/{uid}/make-admin', [ApiGroupController::class, 'makeAdmin']);
    });

    // Live Streams
    Route::prefix('live')->group(function () {
        Route::get('/',                [ApiLiveController::class, 'index']);
        Route::post('start',           [ApiLiveController::class, 'start']);
        Route::get('{id}/token',       [ApiLiveController::class, 'token']);
        Route::get('{id}/comments',    [ApiLiveController::class, 'comments']);
        Route::post('{id}/comments',   [ApiLiveController::class, 'addComment']);
        Route::post('{id}/like',       [ApiLiveController::class, 'like']);
        Route::post('{id}/end',        [ApiLiveController::class, 'end']);
    });

    // Wallet
    Route::prefix('wallet')->group(function () {
        Route::get('/',                [ApiWalletController::class, 'balance']);
        Route::post('fund',            [ApiWalletController::class, 'fund']);
        Route::post('transfer',        [ApiWalletController::class, 'transfer']);
        Route::post('withdraw',        [ApiWalletController::class, 'withdraw']);
    });

    // Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/',                [ApiNotificationController::class, 'index']);
        Route::post('{id}/read',       [ApiNotificationController::class, 'markRead']);
        Route::post('read-all',        [ApiNotificationController::class, 'markAllRead']);
    });

    // Profiles & Social
    Route::get('profile/{id}',         [ApiProfileController::class, 'show']);
    Route::post('follow/{id}',         [ApiProfileController::class, 'follow']);
    Route::post('unfollow/{id}',       [ApiProfileController::class, 'unfollow']);
    Route::get('followers',            [ApiProfileController::class, 'followers']);
    Route::get('following',            [ApiProfileController::class, 'following']);
    Route::get('search/users',         [ApiProfileController::class, 'searchUsers']);

    // Link preview helper
    Route::post('link-preview',        function (Request $request) {
        $url = $request->input('url');
        if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
            return response()->json(['error' => 'Invalid URL'], 422);
        }
        // Basic response; full implementation reads OG tags
        return response()->json(['url' => $url, 'title' => '', 'description' => '', 'image' => '']);
    });
});
