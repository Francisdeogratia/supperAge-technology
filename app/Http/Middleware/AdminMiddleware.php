<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use App\Models\UserRecord;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Basic Session Check: Do they have the admin role?
        if (!Session::has('role') || Session::get('role') !== 'admin') {
            return redirect('/account')->with('error', 'Admin access required.');
        }

        // 2. Security Check: Do they have the special login code?
        if (!Session::has('specialcode')) {
            return redirect('/account')->with('error', 'Special verification required.');
        }

        // 3. Database Check: Does this user actually exist and is an admin?
        $userId = Session::get('id');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'Please login first.');
        }

        $user = UserRecord::find($userId);

        // Check if user exists and if their 'is_admin' column is true (1)
        if (!$user || $user->is_admin != 1) {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        // Everything is fine, proceed to the admin page
        return $next($request);
    }
}