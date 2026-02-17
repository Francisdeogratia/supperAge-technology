<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\UserRecord;
use Illuminate\Support\Facades\DB;
use App\Models\SamplePost;
use App\Models\PostLike;
use App\Models\PostView;
use App\Models\FriendRequest;
use Carbon\Carbon;

class EachProfileController extends Controller
{
    /**
     * Show a specific user's profile with all their posts
     */
    public function show($id)
    {
        $currentUserId = Session::get('id');
        
        if (!$currentUserId) {
            return redirect('/login')->with('error', 'You must be logged in.');
        }
        
        $currentUser = UserRecord::find($currentUserId);
        
        // Use 'user' instead of 'profileUser' to match your blade variable name
        $user = UserRecord::withCount('followers')->findOrFail($id);
        $profileUser = $user; // Keep both for compatibility
        
        // Check if viewing own profile
        $isOwnProfile = ($currentUserId == $id);
        
        // Check if current user is following this profile
        $isFollowing = DB::table('follow_tbl')
            ->where('sender_id', $currentUserId)
            ->where('receiver_id', $id)
            ->exists();
        
        // Get profile user's posts with all relations
        $posts = SamplePost::where('user_id', $id)
            ->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('scheduled_at')
                  ->orWhere('scheduled_at', '<=', now());
            })
            ->with(['likesRelation', 'comments', 'repostsRelation', 'sharesRelation', 'viewsRelation'])
            ->withCount(['likesRelation', 'comments', 'repostsRelation', 'sharesRelation', 'viewsRelation'])
            ->orderByDesc('created_at')
            ->paginate(10);
        
        // Get followers count
        $followersCount = DB::table('follow_tbl')
            ->where('receiver_id', $id)
            ->count();
        
        // Also update user's follower count if it's outdated
        $no_of_followers = $followersCount;
        
        // Get following count
        $followingCount = DB::table('follow_tbl')
            ->where('sender_id', $id)
            ->count();
        
        // ✅ Get friends count (accepted friend requests both ways)
        $friendsCount = FriendRequest::where(function($query) use ($id) {
                $query->where('sender_id', $id)
                      ->orWhere('receiver_id', $id);
            })
            ->where('status', 'accepted')
            ->count();
        
        // ✅ Check friend request status between current user and profile user
        $friendRequestStatus = 'none'; // none, pending_sent, pending_received, friends
        $friendRequestId = null;
        $areFriends = false;
        
        if (!$isOwnProfile) {
            $friendRequest = FriendRequest::where(function($query) use ($currentUserId, $id) {
                    $query->where(function($q) use ($currentUserId, $id) {
                        $q->where('sender_id', $currentUserId)
                          ->where('receiver_id', $id);
                    })->orWhere(function($q) use ($currentUserId, $id) {
                        $q->where('sender_id', $id)
                          ->where('receiver_id', $currentUserId);
                    });
                })
                ->first();
            
            if ($friendRequest) {
                $friendRequestId = $friendRequest->id;
                
                if ($friendRequest->status === 'accepted') {
                    $friendRequestStatus = 'friends';
                    $areFriends = true;
                } elseif ($friendRequest->status === 'pending') {
                    if ($friendRequest->sender_id == $currentUserId) {
                        $friendRequestStatus = 'pending_sent';
                    } else {
                        $friendRequestStatus = 'pending_received';
                    }
                }
            }
        }
        
        // Get total likes received on all posts
        $totalLikes = PostLike::whereIn('post_id', function($query) use ($id) {
            $query->select('id')
                  ->from('sample_posts')
                  ->where('user_id', $id);
        })->count();
        
        // Get total post views
        $totalViews = PostView::whereIn('post_id', function($query) use ($id) {
            $query->select('id')
                  ->from('sample_posts')
                  ->where('user_id', $id);
        })->count();
        
        // Check online status
        $loginSession = $profileUser->lastLoginSession ?? null;
        $isOnline = false;
        $lastSeen = 'never logged in';
        
        if ($loginSession) {
            $isOnline = is_null($loginSession->logout_at) 
                        && $loginSession->updated_at 
                        && Carbon::parse($loginSession->updated_at)->isAfter(now()->subMinutes(30));
            
            if (!$isOnline) {
                if ($loginSession->logout_at) {
                    $lastSeen = Carbon::parse($loginSession->logout_at)->diffForHumans();
                } else {
                    $lastSeen = $loginSession->updated_at 
                        ? Carbon::parse($loginSession->updated_at)->diffForHumans()
                        : 'recently';
                }
            }
        }
        
        // Get mutual friends (people both users follow)
        $mutualFriends = [];
        if (!$isOwnProfile) {
            $mutualFriends = DB::table('follow_tbl as f1')
                ->join('follow_tbl as f2', 'f1.receiver_id', '=', 'f2.receiver_id')
                ->join('users_record as u', 'f1.receiver_id', '=', 'u.id')
                ->where('f1.sender_id', $currentUserId)
                ->where('f2.sender_id', $id)
                ->select('u.id', 'u.name', 'u.username', 'u.profileimg', 'u.badge_status')
                ->limit(5)
                ->get();
        }
        
         // ✅ ADD THIS BLOCK before "return view()"
    
    // Get friends count (accepted friend requests both ways)
    $friendsCount = FriendRequest::where(function($query) use ($id) {
            $query->where('sender_id', $id)
                  ->orWhere('receiver_id', $id);
        })
        ->where('status', 'accepted')
        ->count();
    
    // Check friend request status between current user and profile user
    $friendRequestStatus = 'none'; // none, pending_sent, pending_received, friends
    $friendRequestId = null;
    $areFriends = false;
    
    if (!$isOwnProfile) {
        $friendRequest = FriendRequest::where(function($query) use ($currentUserId, $id) {
                $query->where(function($q) use ($currentUserId, $id) {
                    $q->where('sender_id', $currentUserId)
                      ->where('receiver_id', $id);
                })->orWhere(function($q) use ($currentUserId, $id) {
                    $q->where('sender_id', $id)
                      ->where('receiver_id', $currentUserId);
                });
            })
            ->first();
        
        if ($friendRequest) {
            $friendRequestId = $friendRequest->id;
            
            if ($friendRequest->status === 'accepted') {
                $friendRequestStatus = 'friends';
                $areFriends = true;
            } elseif ($friendRequest->status === 'pending') {
                if ($friendRequest->sender_id == $currentUserId) {
                    $friendRequestStatus = 'pending_sent';
                } else {
                    $friendRequestStatus = 'pending_received';
                }
            }
        }
    }
    
    // ✅ UPDATE your existing return statement to include these new variables
    return view('profile', compact(
        'currentUser',
        'user',
        'profileUser',
        'posts',
        'isOwnProfile',
        'isFollowing',
        'followersCount',
        'followingCount',
        'no_of_followers',
        'totalLikes',
        'totalViews',
        'isOnline',
        'lastSeen',
        'mutualFriends',
        'friendsCount',           // ✅ ADD THIS
        'friendRequestStatus',    // ✅ ADD THIS
        'friendRequestId',        // ✅ ADD THIS
        'areFriends'              // ✅ ADD THIS
    ));
}



}