<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\UserRecord; // Ensure this is your correct model

use Illuminate\Support\Facades\Log; // <-- Import Log

class CustomSessionAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
{
    Log::info('--- Custom Auth Middleware Started ---');

    if (Session::has('id')) {
        $userId = Session::get('id');
        Log::info('Session ID found:', ['id' => $userId, 'path' => $request->path()]);

        $user = UserRecord::find($userId); 

        if ($user) {
            Auth::login($user);
            Log::info('Caller Authenticated successfully.');
        } else {
            Log::warning('Session ID found but UserRecord not found in DB.', ['id' => $userId]);
        }
    } else {
        Log::warning('Session ID NOT found for path:', ['path' => $request->path()]);
    }

    return $next($request);
}

}