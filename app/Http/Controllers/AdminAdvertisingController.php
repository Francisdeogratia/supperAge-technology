<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\UserRecord;
use App\Models\Advertisement;
use App\Models\Notification;

class AdminAdvertisingController extends Controller
{
    /**
     * Show admin ad management dashboard
     */
    public function index(Request $request)
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'You must be logged in.');
        }
        
        $user = UserRecord::find($userId);
        
        // ✅ Fix: Check role column instead of is_admin
        if ($user->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized access.');
        }
        
        // Filter ads by status
        $status = $request->get('status', 'pending');
        
        // ✅ Fix: Use 'user' relationship instead of 'advertiser'
        $ads = Advertisement::with('user')
            ->when($status !== 'all', function($query) use ($status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Get statistics
        $stats = [
            'pending' => Advertisement::where('status', 'pending')->count(),
            'active' => Advertisement::where('status', 'active')->count(),
            'rejected' => Advertisement::where('status', 'rejected')->count(),
            'completed' => Advertisement::where('status', 'completed')->count(),
            'paused' => Advertisement::where('status', 'paused')->count(),
            'total_revenue' => Advertisement::sum('spent') // ✅ Use 'spent' column
        ];
        
        return view('admin.advertising.index', compact('user', 'ads', 'stats', 'status'));
    }
    
    /**
     * Show single ad for review
     */
    public function show($adId)
    {
        $userId = Session::get('id');
        $user = UserRecord::find($userId);
        
        if ($user->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized access.');
        }
        
        $ad = Advertisement::with('user')->findOrFail($adId);
        
        return view('admin.advertising.show', compact('user', 'ad'));
    }
    
    /**
     * Approve ad
     */
    public function approve($adId)
    {
        $userId = Session::get('id');
        $user = UserRecord::find($userId);
        
        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $ad = Advertisement::findOrFail($adId);
        
        $ad->update([
            'status' => 'active'
        ]);
        
        // Send notification to advertiser
        Notification::create([
            'user_id' => $userId,
            'message' => "Your ad '{$ad->title}' has been approved and is now live!",
            'link' => route('advertising.show', $ad->id),
            'notification_reciever_id' => $ad->advertiser_id, // ✅ This matches your DB column
            'read_notification' => 'no',
            'type' => 'ad_approved',
            'notifiable_type' => Advertisement::class,
            'notifiable_id' => $ad->id,
            'data' => json_encode([
                'ad_id' => $ad->id,
                'ad_title' => $ad->title,
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // ✅ Notify targeted users
        $this->notifyTargetedUsers($ad);
        
        return response()->json([
            'success' => true,
            'message' => 'Ad approved successfully!'
        ]);
    }
    
    /**
     * Reject ad
     */
    public function reject(Request $request, $adId)
    {
        $userId = Session::get('id');
        $user = UserRecord::find($userId);
        
        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);
        
        $ad = Advertisement::findOrFail($adId);
        
        $ad->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason
        ]);
        
        // Send notification to advertiser
        Notification::create([
            'user_id' => $userId,
            'message' => "Your ad '{$ad->title}' was rejected. Reason: {$request->reason}",
            'link' => route('advertising.show', $ad->id),
            'notification_reciever_id' => $ad->advertiser_id,
            'read_notification' => 'no',
            'type' => 'ad_rejected',
            'notifiable_type' => Advertisement::class,
            'notifiable_id' => $ad->id,
            'data' => json_encode([
                'ad_id' => $ad->id,
                'ad_title' => $ad->title,
                'reason' => $request->reason,
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Ad rejected successfully!'
        ]);
    }
    
    /**
     * Pause ad
     */
    public function pause($adId)
    {
        $userId = Session::get('id');
        $user = UserRecord::find($userId);
        
        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $ad = Advertisement::findOrFail($adId);
        
        $ad->update(['status' => 'paused']);
        
        return response()->json([
            'success' => true,
            'message' => 'Ad paused successfully!'
        ]);
    }
    
    /**
     * Delete ad (admin only)
     */
    public function destroy($adId)
    {
        $userId = Session::get('id');
        $user = UserRecord::find($userId);
        
        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $ad = Advertisement::findOrFail($adId);
        $ad->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Ad deleted successfully!'
        ]);
    }
    
    /**
     * ✅ Notify targeted users when ad is approved
     */
    private function notifyTargetedUsers($ad)
    {
        $query = UserRecord::query();
        
        // Apply country filter
        if (!empty($ad->target_countries)) {
            $countries = is_array($ad->target_countries) 
                ? $ad->target_countries 
                : json_decode($ad->target_countries, true);
            
            if (!empty($countries)) {
                $query->whereIn('country', $countries);
            }
        }
        
        // Apply gender filter
        if (!empty($ad->target_gender)) {
            $genders = is_array($ad->target_gender) 
                ? $ad->target_gender 
                : json_decode($ad->target_gender, true);
            
            if (!empty($genders)) {
                $query->whereIn('gender', $genders);
            }
        }
        
        // Apply age filter
        if (!empty($ad->target_age_range)) {
            $ageRange = is_array($ad->target_age_range) 
                ? $ad->target_age_range 
                : json_decode($ad->target_age_range, true);
            
            if (!empty($ageRange)) {
                $query->where(function($q) use ($ageRange) {
                    $q->whereNotNull('dob');
                    
                    if (!empty($ageRange['min'])) {
                        $maxDate = now()->subYears($ageRange['min'])->format('Y-m-d');
                        $q->where('dob', '<=', $maxDate);
                    }
                    
                    if (!empty($ageRange['max'])) {
                        $minDate = now()->subYears($ageRange['max'] + 1)->format('Y-m-d');
                        $q->where('dob', '>', $minDate);
                    }
                });
            }
        }
        
        // Get random 50 users to avoid spam
        $targetUsers = $query->inRandomOrder()->take(50)->get();
        
        foreach ($targetUsers as $targetUser) {
            Notification::create([
                'user_id' => $ad->advertiser_id,
                'message' => "🎯 New: {$ad->title}",
                'link' => $ad->cta_link,
                'notification_reciever_id' => $targetUser->id,
                'read_notification' => 'no',
                'type' => 'new_advertisement',
                'notifiable_type' => Advertisement::class,
                'notifiable_id' => $ad->id,
                'data' => json_encode([
                    'ad_id' => $ad->id,
                    'ad_title' => $ad->title,
                    'ad_description' => $ad->description,
                    'media_url' => $ad->media_url,
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}