<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class UpdateUserActivity
{
    public function handle($request, Closure $next)
    {
        if (Session::has('id')) {
            $loginDetailsId = Session::get('login_details_id');
            
            // Update last activity time
            if ($loginDetailsId) {
                DB::table('login_details')
                    ->where('id', $loginDetailsId)
                    ->update(['updated_at' => now()]);
            }
        }
        
        return $next($request);
    }
}