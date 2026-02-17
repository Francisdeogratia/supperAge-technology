<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserRecord;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    /**
     * Search for users by name, username, email
     */
    public function searchUsers(Request $request)
    {
        $query = $request->input('query');
        
        if (empty($query) || strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter at least 2 characters',
                'users' => []
            ]);
        }
        
        // Search users by name, username, or email
        $users = UserRecord::where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('username', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->where('status', 'active') // Only active users
            ->where('unsetacct', '!=', 'yes') // Not deleted accounts
            ->select('id', 'name', 'username', 'profileimg', 'badge_status', 'bio', 'city', 'state', 'country')
            ->limit(10) // Limit to 10 results
            ->get();
        
        // Format the results
        $formattedUsers = $users->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'profileimg' => $user->profileimg 
                    ? str_replace('/upload/', '/upload/w_50,h_50,c_fill,r_max,q_70/', $user->profileimg)
                    : null,
                'badge_status' => $user->badge_status ? asset($user->badge_status) : null,
                'bio' => $user->bio ? \Illuminate\Support\Str::limit($user->bio, 50) : null,
                'location' => trim(implode(', ', array_filter([$user->city, $user->state, $user->country]))),
                'profile_url' => route('profile.show', $user->id)
            ];
        });
        
        return response()->json([
            'success' => true,
            'users' => $formattedUsers,
            'count' => $formattedUsers->count()
        ]);
    }
}