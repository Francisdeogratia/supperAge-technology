<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\UserHelper;
use App\Models\UserRecord;
use App\Models\Follow;
use App\Models\Advertisement;
use Carbon\Carbon;

class UpdateController extends Controller
{
    public function showMyFollowers()
    {
        $loggedInUserId = Session::get('id');
        $user = UserRecord::find($loggedInUserId);

        $followers = UserRecord::whereIn('id', function ($query) use ($loggedInUserId) {
            $query->select('sender_id')
                  ->from('follow_tbl')
                  ->where('receiver_id', $loggedInUserId);
        })->get();

        $followers->transform(function ($follower) use ($loggedInUserId) {
            $follower->isFollowing = Follow::where('sender_id', $loggedInUserId)
                                           ->where('receiver_id', $follower->id)
                                           ->exists();
            return $follower;
        });

        $following = UserRecord::whereIn('id', function ($query) use ($loggedInUserId) {
            $query->select('receiver_id')
                  ->from('follow_tbl')
                  ->where('sender_id', $loggedInUserId);
        })->get();

        return view('my_followers', compact('user', 'followers', 'following'));
    }

    public function show()
    {
        // ADD THESE LINES FOR DEBUGGING
    // dd('Controller is being called!'); // This will stop execution and show message

        if (!Session::has('specialcode') || !Session::has('id')) {
            return redirect('/account');
        }

        $specialcode = Session::get('specialcode');
        $user = UserRecord::where('specialcode', $specialcode)->firstOrFail();

        $totalNotifications = UserHelper::countNotifications($user->specialcode);
        $no_of_followers = $user->number_followers;

        $otherUsers = UserRecord::where('id', '!=', $user->id)
            ->withCount(['followers'])
            ->limit(5)
            ->get();

        $isFollowing = Follow::where('sender_id', Session::get('id'))
            ->where('receiver_id', $user->id)
            ->exists();

        $posts = DB::table('sample_posts')
            ->where('specialcode', $specialcode)
            ->orderBy('created_at', 'desc')
            ->get();

        $hashtags = collect();

        // ===== AD TARGETING LOGIC (FIXED FOR MARIADB) =====
        
        // ✅ Calculate user age
        // $userAge = null;
        // if ($user->dob) {
        //     try {
        //         $userAge = Carbon::parse($user->dob)->age;
        //     } catch (\Exception $e) {
        //         Log::warning('Invalid DOB for user ' . $user->id);
        //     }
        // }

        // ✅ Map country names to ISO codes
        // $countryMap = [
        //     'nigeria' => 'NG',
        //     'united states' => 'US',
        //     'usa' => 'US',
        //     'united kingdom' => 'GB',
        //     'uk' => 'GB',
        //     'canada' => 'CA',
        //     'ghana' => 'GH',
        //     'kenya' => 'KE',
        //     'south africa' => 'ZA',
        //     'india' => 'IN',
        //     'australia' => 'AU',
        // ];
        
        // $userCountry = strtolower(trim($user->country ?? ''));
        // $userCountryCode = $countryMap[$userCountry] ?? strtoupper(substr($userCountry, 0, 2));
        // $userGender = strtolower(trim($user->gender ?? ''));

        // Log::info('User Ad Targeting', [
        //     'user_id' => $user->id,
        //     'country_code' => $userCountryCode,
        //     'gender' => $userGender,
        //     'age' => $userAge
        // ]);

        // ===== BUILD AD QUERY (MARIADB COMPATIBLE) =====
        // $adsQuery = Advertisement::where('status', 'active')
        //     ->where('start_date', '<=', now())
        //     ->where('end_date', '>=', now())
        //     ->whereColumn('spent', '<', 'budget');

        // Country targeting - MariaDB compatible
        // if ($userCountryCode) {
        //     $adsQuery->where(function ($q) use ($userCountryCode) {
        //         $q->whereNull('target_countries')
        //           ->orWhereJsonLength('target_countries', 0)
        //           ->orWhereRaw("JSON_CONTAINS(target_countries, '\"$userCountryCode\"')");
        //     });
        // }

        // Gender targeting - MariaDB compatible
        // if ($userGender && in_array($userGender, ['male', 'female', 'other'])) {
        //     $adsQuery->where(function ($q) use ($userGender) {
        //         $q->whereNull('target_gender')
        //           ->orWhereJsonLength('target_gender', 0)
        //           ->orWhereRaw("JSON_CONTAINS(target_gender, '\"$userGender\"')");
        //     });
        // }

        // Age targeting - MariaDB compatible (using JSON_EXTRACT and JSON_UNQUOTE)
        // if ($userAge) {
        //     $adsQuery->where(function ($q) use ($userAge) {
        //         $q->whereNull('target_age_range')
        //           ->orWhereJsonLength('target_age_range', 0)
        //           ->orWhereRaw("
        //               (
        //                   (JSON_UNQUOTE(JSON_EXTRACT(target_age_range, '$.min')) IS NULL 
        //                    OR JSON_UNQUOTE(JSON_EXTRACT(target_age_range, '$.min')) = ''
        //                    OR CAST(JSON_UNQUOTE(JSON_EXTRACT(target_age_range, '$.min')) AS UNSIGNED) <= ?)
        //                   AND
        //                   (JSON_UNQUOTE(JSON_EXTRACT(target_age_range, '$.max')) IS NULL 
        //                    OR JSON_UNQUOTE(JSON_EXTRACT(target_age_range, '$.max')) = ''
        //                    OR CAST(JSON_UNQUOTE(JSON_EXTRACT(target_age_range, '$.max')) AS UNSIGNED) >= ?)
        //               )
        //           ", [$userAge, $userAge]);
        //     });
        // }

        // ✅ Get matching ads
        // $activeAds = $adsQuery->inRandomOrder()->take(3)->get();

        // Log::info('Ad Query Results', [
        //     'user_id' => $user->id,
        //     'ads_found' => $activeAds->count(),
        //     'ad_ids' => $activeAds->pluck('id')->toArray()
        // ]);

        // ✅ Fallback: Untargeted ads
        // if ($activeAds->isEmpty()) {
        //     $activeAds = Advertisement::where('status', 'active')
        //         ->where('start_date', '<=', now())
        //         ->where('end_date', '>=', now())
        //         ->whereColumn('spent', '<', 'budget')
        //         ->whereNull('target_countries')
        //         ->whereNull('target_gender')
        //         ->whereNull('target_age_range')
        //         ->inRandomOrder()
        //         ->take(3)
        //         ->get();
            
        //     Log::info('Using untargeted ads', ['count' => $activeAds->count()]);
        // }

        // ✅ Final fallback: ANY active ad
        // if ($activeAds->isEmpty()) {
        //     $activeAds = Advertisement::where('status', 'active')
        //         ->where('start_date', '<=', now())
        //         ->where('end_date', '>=', now())
        //         ->whereColumn('spent', '<', 'budget')
        //         ->inRandomOrder()
        //         ->take(3)
        //         ->get();
            
        //     Log::warning('Using ANY active ads', ['count' => $activeAds->count()]);
        // }
// At the bottom of the show() method, BEFORE return view()
// dd([
//     'activeAds_count' => $activeAds->count(),
//     'activeAds_ids' => $activeAds->pluck('id')->toArray(),
//     'all_variables' => compact('user', 'posts', 'hashtags', 'totalNotifications', 'no_of_followers', 'otherUsers', 'isFollowing', 'activeAds')
// ]);
        return view('update', compact(
            'user',
            'posts',
            'hashtags',
            'totalNotifications',
            'no_of_followers',
            'otherUsers',
            'isFollowing',
            // 'activeAds'
        ));
    }

    public function showMutualFollowers($id)
    {
        $loggedInUser = UserRecord::findOrFail(Session::get('id'));
        $otherUser = UserRecord::findOrFail($id);

        $mutualFollowers = $loggedInUser->mutualFollowers($otherUser);

        $mutualFollowers->transform(function ($follower) use ($loggedInUser) {
            $follower->isFollowing = Follow::where('sender_id', $loggedInUser->id)
                                           ->where('receiver_id', $follower->id)
                                           ->exists();
            return $follower;
        });

        $mutualCount = $mutualFollowers->count();

        return view('mutual_followers', [
            'user' => $loggedInUser,
            'otherUser' => $otherUser,
            'mutualFollowers' => $mutualFollowers,
            'mutualCount' => $mutualCount
        ]);
    }

    public function showAllSuggestions()
    {
        $user = UserRecord::findOrFail(Session::get('id'));

        $otherUsers = UserRecord::where('id', '!=', $user->id)
            ->whereNotIn('id', function ($query) use ($user) {
                $query->select('receiver_id')
                      ->from('follow_tbl')
                      ->where('sender_id', $user->id);
            })
            ->withCount(['followers'])
            ->get();

        return view('all_suggestions', compact('user', 'otherUsers'));
    }

    public function showFollowing()
    {
        $loggedInUserId = Session::get('id');
        $user = UserRecord::find($loggedInUserId);

        $following = UserRecord::whereIn('id', function ($query) use ($loggedInUserId) {
            $query->select('receiver_id')
                  ->from('follow_tbl')
                  ->where('sender_id', $loggedInUserId);
        })->get();

        return view('my_following', compact('user', 'following'));
    }
}