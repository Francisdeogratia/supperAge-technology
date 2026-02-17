<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\UserRecord;
use App\Models\SamplePost;
use App\Models\UserMonetization;
use App\Models\PostPerformanceTracking;
use App\Models\MonetizationActionsLog;
use App\Models\Notification;
use Carbon\Carbon;

class MonetizationController extends Controller
{
    public function index(Request $request)
    {
        $adminId = Session::get('id');
        $admin = UserRecord::find($adminId);
        
        if (!$admin || !$admin->is_admin) {
            return redirect('/')->with('error', 'Unauthorized access');
        }
        
        $search = $request->input('search');
        $statusFilter = $request->input('status');
        
        // Get users with their monetization status
        $users = UserRecord::select('users_record.*')
            ->leftJoin('user_monetization', 'users_record.id', '=', 'user_monetization.user_id')
            ->with(['monetization'])
            ->withCount(['posts as total_posts'])
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('users_record.name', 'like', "%{$search}%")
                      ->orWhere('users_record.username', 'like', "%{$search}%")
                      ->orWhere('users_record.email', 'like', "%{$search}%");
                });
            })
            ->when($statusFilter, function($query) use ($statusFilter) {
                if ($statusFilter === 'not_monetized') {
                    $query->whereNull('user_monetization.id');
                } else {
                    $query->where('user_monetization.status', $statusFilter);
                }
            })
            ->orderByDesc('users_record.created_at')
            ->paginate(20);
        
        // Get stats
        $stats = [
            'total_users' => UserRecord::count(),
            'monetized_users' => UserMonetization::where('status', 'approved')->count(),
            'pending_requests' => UserMonetization::where('status', 'pending')->count(),
            'total_earnings' => UserMonetization::where('status', 'approved')->sum('total_earnings')
        ];
        
        // Pass $admin as $user for navbar
        $user = $admin;
        
        return view('admin.monetization.index', compact('users', 'admin', 'user', 'stats'));
    }
    
    public function userDetails($userId)
    {
        $adminId = Session::get('id');
        $admin = UserRecord::find($adminId);
        
        if (!$admin || !$admin->is_admin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        try {
            $user = UserRecord::with(['monetization'])->findOrFail($userId);
            
            // Get all posts
            $allPosts = SamplePost::where('user_id', $userId)
                ->withCount(['likesRelation', 'comments', 'repostsRelation', 'sharesRelation', 'viewsRelation'])
                ->get();
            
            // Get posts from today
            $todayPosts = SamplePost::where('user_id', $userId)
                ->whereDate('created_at', today())
                ->withCount(['likesRelation', 'comments', 'repostsRelation', 'sharesRelation', 'viewsRelation'])
                ->get();
            
            // Get top performing post
            $topPost = SamplePost::where('user_id', $userId)
                ->withCount(['likesRelation', 'comments', 'repostsRelation', 'sharesRelation', 'viewsRelation'])
                ->get()
                ->sortByDesc(function($post) {
                    return ($post->likes_relation_count * 1) + 
                           ($post->comments_count * 2) + 
                           ($post->reposts_relation_count * 3) + 
                           ($post->shares_relation_count * 3) + 
                           ($post->views_relation_count * 0.1);
                })
                ->first();
            
            // Get viral posts (posts within 3 days that meet criteria)
            $viralPosts = SamplePost::where('user_id', $userId)
                ->where('created_at', '>=', now()->subDays(3))
                ->withCount(['likesRelation', 'comments', 'repostsRelation', 'sharesRelation', 'viewsRelation'])
                ->get()
                ->filter(function($post) {
                    return $post->likes_relation_count >= 1000 &&
                           $post->comments_count >= 500 &&
                           $post->reposts_relation_count >= 100 &&
                           $post->shares_relation_count >= 100 &&
                           $post->views_relation_count >= 2000;
                });
            
            // Calculate total engagement
            $totalLikes = $allPosts->sum('likes_relation_count');
            $totalComments = $allPosts->sum('comments_count');
            $totalReposts = $allPosts->sum('reposts_relation_count');
            $totalShares = $allPosts->sum('shares_relation_count');
            $totalViews = $allPosts->sum('views_relation_count');
            
            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name ?? 'Unknown',
                    'username' => $user->username ?? 'unknown',
                    'profileimg' => $user->profileimg,
                    'badge_status' => $user->badge_status,
                    'email' => $user->email ?? 'N/A',
                    'country' => $user->country ?? 'Unknown',
                    'created_at' => $user->created_at ? $user->created_at->format('M d, Y') : 'N/A', // ✅ FIXED
                    'monetization_status' => $user->monetization?->status ?? 'not_monetized',
                    'total_earnings' => $user->monetization?->total_earnings ?? 0
                ],
                'stats' => [
                    'total_posts' => $allPosts->count(),
                    'today_posts' => $todayPosts->count(),
                    'viral_posts' => $viralPosts->count(),
                    'total_likes' => $totalLikes,
                    'total_comments' => $totalComments,
                    'total_reposts' => $totalReposts,
                    'total_shares' => $totalShares,
                    'total_views' => $totalViews,
                    'engagement_score' => ($totalLikes + ($totalComments * 2) + ($totalReposts * 3) + ($totalShares * 3) + ($totalViews * 0.1))
                ],
                'top_post' => $topPost ? [
                    'id' => $topPost->id,
                    'content' => \Str::limit($topPost->post_content ?? 'No content', 100),
                    'likes' => $topPost->likes_relation_count ?? 0,
                    'comments' => $topPost->comments_count ?? 0,
                    'reposts' => $topPost->reposts_relation_count ?? 0,
                    'shares' => $topPost->shares_relation_count ?? 0,
                    'views' => $topPost->views_relation_count ?? 0,
                    'created_at' => $topPost->created_at ? $topPost->created_at->diffForHumans() : 'Recently',
                    'engagement_score' => ($topPost->likes_relation_count ?? 0) + 
                                         (($topPost->comments_count ?? 0) * 2) + 
                                         (($topPost->reposts_relation_count ?? 0) * 3) + 
                                         (($topPost->shares_relation_count ?? 0) * 3) + 
                                         (($topPost->views_relation_count ?? 0) * 0.1)
                ] : null,
                'viral_posts' => $viralPosts->map(function($post) {
                    return [
                        'id' => $post->id,
                        'content' => \Str::limit($post->post_content ?? 'No content', 100),
                        'likes' => $post->likes_relation_count ?? 0,
                        'comments' => $post->comments_count ?? 0,
                        'reposts' => $post->reposts_relation_count ?? 0,
                        'shares' => $post->shares_relation_count ?? 0,
                        'views' => $post->views_relation_count ?? 0,
                        'created_at' => $post->created_at ? $post->created_at->diffForHumans() : 'Recently'
                    ];
                })->values()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Monetization userDetails error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'error' => 'Failed to load user details',
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }
    
    public function approve(Request $request, $userId)
    {
        $adminId = Session::get('id');
        $admin = UserRecord::find($adminId);
        
        if (!$admin || !$admin->is_admin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $user = UserRecord::findOrFail($userId);
        
        $monetization = UserMonetization::updateOrCreate(
            ['user_id' => $userId],
            [
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => $adminId,
                'notes' => $request->input('notes')
            ]
        );
        
        // Log action
        MonetizationActionsLog::create([
            'admin_id' => $adminId,
            'user_id' => $userId,
            'action' => 'approved',
            'message' => $request->input('notes')
        ]);
        
        // Send notification to user
        Notification::create([
            'user_id' => $adminId,
            'message' => '🎉 Congratulations! Your account has been approved for monetization!',
            'link' => route('profile.show', $userId),
            'notification_reciever_id' => $userId,
            'read_notification' => 'no',
            'type' => 'monetization_approved',
            'notifiable_type' => UserMonetization::class,
            'notifiable_id' => $monetization->id,
            'data' => json_encode([
                'approved_by' => $admin->name,
                'approved_at' => now()->toDateTimeString()
            ])
        ]);
        
        return response()->json([
            'success' => true,
            'message' => "{$user->name} has been approved for monetization!"
        ]);
    }
    
    public function suspend($userId)
    {
        $adminId = Session::get('id');
        $admin = UserRecord::find($adminId);
        
        if (!$admin || !$admin->is_admin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $monetization = UserMonetization::where('user_id', $userId)->first();
        
        if (!$monetization) {
            return response()->json(['error' => 'User not monetized'], 404);
        }
        
        $monetization->update(['status' => 'suspended']);
        
        // Log action
        MonetizationActionsLog::create([
            'admin_id' => $adminId,
            'user_id' => $userId,
            'action' => 'suspended'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'User monetization suspended'
        ]);
    }
    
    public function sendMessage(Request $request, $userId)
    {
        $adminId = Session::get('id');
        $admin = UserRecord::find($adminId);
        
        if (!$admin || !$admin->is_admin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);
        
        $user = UserRecord::findOrFail($userId);
        
        // Create notification
        Notification::create([
            'user_id' => $adminId,
            'message' => '💬 Message from SupperAge Technology: ' . $request->message,
            'link' => route('messages.index') . '?user=' . $adminId,
            'notification_reciever_id' => $userId,
            'read_notification' => 'no',
            'type' => 'admin_message',
            'notifiable_type' => UserRecord::class,
            'notifiable_id' => $adminId,
            'data' => json_encode([
                'from' => $admin->name,
                'message' => $request->message
            ])
        ]);
        
        // Log action
        MonetizationActionsLog::create([
            'admin_id' => $adminId,
            'user_id' => $userId,
            'action' => 'message_sent',
            'message' => $request->message
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully!'
        ]);
    }
}