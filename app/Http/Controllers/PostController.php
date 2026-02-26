<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\UserRecord;
use Carbon\Carbon;
use App\Models\SamplePost;
use App\Models\PostLike;
use App\Models\PostComment;
use App\Models\PostRepost;
use App\Models\PostShare;
use App\Models\Notification;
use App\Models\PostView;
use Illuminate\Support\Facades\Validator;
use App\Models\PostReward;
use App\Models\WalletTransaction;
use App\Models\Advertisement;
use App\Models\SavedPost;
use App\Models\TalesExten;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function show($id)
{
    $taskId = request()->query('task_id');
    $post = null;

    if ($taskId) {
        $task = DB::table('task_center')->where('id', $taskId)->first();
        if ($task && $task->post_id) {
            $post = SamplePost::find($task->post_id);
        }
    }

    if (!$post) {
        $post = SamplePost::find($id);
    }

    if (!$post) {
        abort(404, 'Post not found');
    }

    $hashtags = DB::table('hashtags')->where('post_id', $post->id)->pluck('tag');

    $userId = Session::get('id');
    $user = DB::table('users_record')->where('id', $userId)->first();

    PostView::create([
        'user_id' => $userId,
        'post_id' => $post->id
    ]);

    // ✅ Fetch notifications for this user
    $notifications = DB::table('notifications')
        ->where('notification_reciever_id', $userId)
        ->orderByDesc('created_at')
        ->get();

    $no_of_followers = $user->number_followers ?? 0;

    $isFollowing = DB::table('follow_tbl')
        ->where('sender_id', $userId)
        ->where('receiver_id', $user->id)
        ->exists();

    $otherUsers = UserRecord::where('id', '!=', $user->id)
        ->whereNotIn('id', function ($query) use ($user) {
            $query->select('receiver_id')
                  ->from('follow_tbl')
                  ->where('sender_id', $user->id);
        })
        ->withCount(['followers'])
        ->limit(5)
        ->get();

    // ✅ FIXED: Add limit to other posts (LIMITED for performance)
    $otherPosts = SamplePost::where(function ($query) use ($user) {
            $query->whereNull('user_id')
                  ->orWhere('user_id', '!=', $user->id);
        })
        ->where('status', 'published')
        ->where(function ($q) {
            $q->whereNull('scheduled_at')
              ->orWhere('scheduled_at', '<=', now());
        })
        ->orderByDesc('created_at')
        ->limit(20)  // ✅ FIXED: Add limit
        ->get();

    // ✅ FIXED: Fetch current user's own posts (LIMITED for performance)
    $posts = SamplePost::where('user_id', $user->id)
        ->where('status', 'published')
        ->where(function ($q) {
            $q->whereNull('scheduled_at')
              ->orWhere('scheduled_at', '<=', now());
        })
        ->orderByDesc('created_at')
        ->limit(10)  // ✅ FIXED: Add limit
        ->get();

    // ✅ Ensure the clicked post is included
    if ($post && !$posts->contains('id', $post->id)) {
        $posts->prepend($post);
    }

    // ✅ ADD THIS: Get list of users the current user is following
    $followedUserIds = DB::table('follow_tbl')
        ->where('sender_id', $user->id)
        ->pluck('receiver_id')
        ->toArray();

    // ===== FETCH LIVE STREAMS =====
    $liveStreams = \App\Models\LiveStream::with(['creator'])
        ->where('status', 'live')
        ->orderByDesc('started_at')
        ->get();
    // ===== END LIVE STREAMS =====

    // ✅ NEW: Add marketplace stores
    $stores = \App\Models\MarketplaceStore::with('owner')
        ->where('status', 'active')
        ->latest()
        ->paginate(12);

    // ✅ NEW: Add upcoming events
    $upcomingEvents = \App\Models\Event::with(['creator'])
        ->where('event_date', '>=', now())
        ->where('status', 'published')
        ->orderBy('event_date', 'asc')
        ->paginate(12);

    // ✅ NEW: Add active ads (same logic as showAllPosts)
    $userAge = null;
    if ($user->dob) {
        try {
            $userAge = Carbon::parse($user->dob)->age;
        } catch (\Exception $e) {
            \Log::warning('Invalid DOB for user ' . $user->id);
        }
    }

    $countryMap = [
        'nigeria' => 'NG',
        'united states' => 'US',
        'usa' => 'US',
        'united kingdom' => 'GB',
        'uk' => 'GB',
        'canada' => 'CA',
        'ghana' => 'GH',
        'kenya' => 'KE',
        'south africa' => 'ZA',
        'india' => 'IN',
        'australia' => 'AU',
    ];
    
    $userCountry = strtolower(trim($user->country ?? ''));
    $userCountryCode = $countryMap[$userCountry] ?? strtoupper(substr($userCountry, 0, 2));
    $userGender = strtolower(trim($user->gender ?? ''));

    $adsQuery = Advertisement::where('status', 'active')
        ->where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->whereColumn('spent', '<', 'budget');

    if ($userCountryCode) {
        $adsQuery->where(function ($q) use ($userCountryCode) {
            $q->whereNull('target_countries')
              ->orWhereJsonLength('target_countries', 0)
              ->orWhereRaw("JSON_CONTAINS(target_countries, '\"$userCountryCode\"')");
        });
    }

    if ($userGender && in_array($userGender, ['male', 'female', 'other'])) {
        $adsQuery->where(function ($q) use ($userGender) {
            $q->whereNull('target_gender')
              ->orWhereJsonLength('target_gender', 0)
              ->orWhereRaw("JSON_CONTAINS(target_gender, '\"$userGender\"')");
        });
    }

    if ($userAge) {
        $adsQuery->where(function ($q) use ($userAge) {
            $q->whereNull('target_age_range')
              ->orWhereJsonLength('target_age_range', 0)
              ->orWhereRaw("
                  (
                      (JSON_UNQUOTE(JSON_EXTRACT(target_age_range, '$.min')) IS NULL 
                       OR JSON_UNQUOTE(JSON_EXTRACT(target_age_range, '$.min')) = ''
                       OR CAST(JSON_UNQUOTE(JSON_EXTRACT(target_age_range, '$.min')) AS UNSIGNED) <= ?)
                      AND
                      (JSON_UNQUOTE(JSON_EXTRACT(target_age_range, '$.max')) IS NULL 
                       OR JSON_UNQUOTE(JSON_EXTRACT(target_age_range, '$.max')) = ''
                       OR CAST(JSON_UNQUOTE(JSON_EXTRACT(target_age_range, '$.max')) AS UNSIGNED) >= ?)
                  )
              ", [$userAge, $userAge]);
        });
    }

    $activeAds = $adsQuery->inRandomOrder()->take(3)->get();

    if ($activeAds->isEmpty()) {
        $activeAds = Advertisement::where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->whereColumn('spent', '<', 'budget')
            ->whereNull('target_countries')
            ->whereNull('target_gender')
            ->whereNull('target_age_range')
            ->inRandomOrder()
            ->take(3)
            ->get();
    }

    if ($activeAds->isEmpty()) {
        $activeAds = Advertisement::where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->whereColumn('spent', '<', 'budget')
            ->inRandomOrder()
            ->take(3)
            ->get();
    }

    $savedPostIds = SavedPost::where('user_id', $userId)->pluck('post_id')->toArray();
    $likedPostIds = PostLike::where('user_id', $userId)->pluck('post_id')->toArray();

    return view('update', compact(
        'post',
        'hashtags',
        'user',
        'no_of_followers',
        'isFollowing',
        'otherUsers',
        'otherPosts',
        'posts',
        'notifications',
        'followedUserIds',
        'liveStreams',
        'stores',
        'upcomingEvents',
        'activeAds',
        'savedPostIds',
        'likedPostIds'
    ));
}

public function showAllPosts()
{
    $userId = Session::get('id');
    $user = UserRecord::find($userId);

    if (!$user) {
        return redirect('/login')->with('error', 'You must be logged in.');
    }

    $no_of_followers = $user->number_followers ?? 0;

    $isFollowing = DB::table('follow_tbl')
        ->where('sender_id', $userId)
        ->where('receiver_id', $user->id)
        ->exists();

    // ✅ FIXED: Fetch current user's own posts (LIMITED for performance)
    $posts = SamplePost::where('user_id', $user->id)
        ->where('status', 'published')
        ->where(function ($q) {
            $q->whereNull('scheduled_at')
              ->orWhere('scheduled_at', '<=', now());
        })
        ->orderByDesc('created_at')
        ->limit(10)  // ✅ FIXED: Add limit
        ->get();

    // ✅ Get IDs of users you follow
    $followingIds = DB::table('follow_tbl')
        ->where('sender_id', $user->id)
        ->pluck('receiver_id');

    // ✅ FIXED: Fetch posts from followed users (LIMITED for performance)
    $otherPosts = SamplePost::with('user')
        ->whereIn('user_id', $followingIds)
        ->where('status', 'published')
        ->where(function ($q) {
            $q->whereNull('scheduled_at')
              ->orWhere('scheduled_at', '<=', now());
        })
        ->orderByDesc('created_at')
        ->limit(20)  // ✅ FIXED: Add limit to followed posts
        ->get()
        ->groupBy('user_id')
        ->map(function ($posts) {
            return $posts->take(10);
        })
        ->flatten();

    $otherPosts = SamplePost::with(['user', 'comments.user', 'comments.replies.user'])
        ->withCount(['likesRelation', 'repostsRelation', 'sharesRelation', 'viewsRelation'])
        ->orderByDesc('created_at')
        ->limit(20)  // ✅ FIXED: Add limit
        ->get();

    // ✅ Fallback: if no posts from followed users, show 3 latest posts from non-followed users
    if ($otherPosts->isEmpty()) {
        $otherPosts = SamplePost::with('user')
            ->where('user_id', '!=', $user->id)
            ->whereNotIn('user_id', $followingIds)
            ->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('scheduled_at')
                  ->orWhere('scheduled_at', '<=', now());
            })
            ->orderByDesc('created_at')
            ->take(3)
            ->get();
    }

    // ✅ Suggest other users (not followed yet)
    $otherUsers = UserRecord::where('id', '!=', $user->id)
        ->whereNotIn('id', function ($query) use ($user) {
            $query->select('receiver_id')
                  ->from('follow_tbl')
                  ->where('sender_id', $user->id);
        })
        ->withCount(['followers'])
        ->limit(5)
        ->get();

    $followedUserIds = DB::table('follow_tbl')
        ->where('sender_id', $user->id)
        ->pluck('receiver_id')
        ->toArray();

    // ✅ Fetch notifications for this user
    $notifications = DB::table('notifications')
        ->where('notification_reciever_id', $userId)
        ->orderByDesc('created_at')
        ->get();

    // ===== 🎯 AD TARGETING LOGIC (NEW) =====
    
    // Calculate user age
    $userAge = null;
    if ($user->dob) {
        try {
            $userAge = Carbon::parse($user->dob)->age;
        } catch (\Exception $e) {
            \Log::warning('Invalid DOB for user ' . $user->id);
        }
    }

    // Map country names to ISO codes
    $countryMap = [
        'nigeria' => 'NG',
        'united states' => 'US',
        'usa' => 'US',
        'united kingdom' => 'GB',
        'uk' => 'GB',
        'canada' => 'CA',
        'ghana' => 'GH',
        'kenya' => 'KE',
        'south africa' => 'ZA',
        'india' => 'IN',
        'australia' => 'AU',
    ];
    
    $userCountry = strtolower(trim($user->country ?? ''));
    $userCountryCode = $countryMap[$userCountry] ?? strtoupper(substr($userCountry, 0, 2));
    $userGender = strtolower(trim($user->gender ?? ''));

    // Build ad query (MariaDB compatible)
    $adsQuery = Advertisement::where('status', 'active')
        ->where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->whereColumn('spent', '<', 'budget');

    // Country targeting
    if ($userCountryCode) {
        $adsQuery->where(function ($q) use ($userCountryCode) {
            $q->whereNull('target_countries')
              ->orWhereJsonLength('target_countries', 0)
              ->orWhereRaw("JSON_CONTAINS(target_countries, '\"$userCountryCode\"')");
        });
    }

    // Gender targeting
    if ($userGender && in_array($userGender, ['male', 'female', 'other'])) {
        $adsQuery->where(function ($q) use ($userGender) {
            $q->whereNull('target_gender')
              ->orWhereJsonLength('target_gender', 0)
              ->orWhereRaw("JSON_CONTAINS(target_gender, '\"$userGender\"')");
        });
    }

    // Age targeting (MariaDB compatible)
    if ($userAge) {
        $adsQuery->where(function ($q) use ($userAge) {
            $q->whereNull('target_age_range')
              ->orWhereJsonLength('target_age_range', 0)
              ->orWhereRaw("
                  (
                      (JSON_UNQUOTE(JSON_EXTRACT(target_age_range, '$.min')) IS NULL 
                       OR JSON_UNQUOTE(JSON_EXTRACT(target_age_range, '$.min')) = ''
                       OR CAST(JSON_UNQUOTE(JSON_EXTRACT(target_age_range, '$.min')) AS UNSIGNED) <= ?)
                      AND
                      (JSON_UNQUOTE(JSON_EXTRACT(target_age_range, '$.max')) IS NULL 
                       OR JSON_UNQUOTE(JSON_EXTRACT(target_age_range, '$.max')) = ''
                       OR CAST(JSON_UNQUOTE(JSON_EXTRACT(target_age_range, '$.max')) AS UNSIGNED) >= ?)
                  )
              ", [$userAge, $userAge]);
        });
    }

    // Get matching ads
    $activeAds = $adsQuery->inRandomOrder()->take(3)->get();

    // Fallback: Untargeted ads
    if ($activeAds->isEmpty()) {
        $activeAds = Advertisement::where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->whereColumn('spent', '<', 'budget')
            ->whereNull('target_countries')
            ->whereNull('target_gender')
            ->whereNull('target_age_range')
            ->inRandomOrder()
            ->take(3)
            ->get();
    }

    // Final fallback: ANY active ad
    if ($activeAds->isEmpty()) {
        $activeAds = Advertisement::where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->whereColumn('spent', '<', 'budget')
            ->inRandomOrder()
            ->take(3)
            ->get();
    }

    // ===== END AD LOGIC =====

    // ✅ ADD THIS: Fetch marketplace stores
    $stores = \App\Models\MarketplaceStore::with('owner')
        ->where('status', 'active')
       
        ->latest()
        ->paginate(12);

    // ===== FETCH LIVE STREAMS =====
    $liveStreams = \App\Models\LiveStream::with(['creator'])
        ->where('status', 'live')
        ->orderByDesc('started_at')
        ->get();

    // ===== ✅ FETCH UPCOMING EVENTS (FIX FOR YOUR ERROR) =====
    $upcomingEvents = \App\Models\Event::with(['creator'])
        ->where('event_date', '>=', now())
        ->where('status', 'published')
        ->orderBy('event_date', 'asc')
        ->paginate(12);
    // ===== END EVENTS =====

    $savedPostIds = SavedPost::where('user_id', $userId)->pluck('post_id')->toArray();

    return view('update', compact(
        'user',
        'no_of_followers',
        'isFollowing',
        'otherUsers',
        'otherPosts',
        'posts',
        'followedUserIds',
        'notifications',
        'activeAds',
        'liveStreams',
        'stores',
        'upcomingEvents',
        'savedPostIds'
    ));
}


// Add these methods to your PostController.php

public function fetchLinkPreview(Request $request)
{
    $url = $request->input('url');
    
    // Validate URL
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return response()->json(['error' => 'Invalid URL'], 400);
    }
    
    try {
        // Set up HTTP client with headers to mimic browser
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            CURLOPT_HTTPHEADER => [
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language: en-US,en;q=0.5',
            ],
        ]);
        
        $html = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200 || !$html) {
            return response()->json(['error' => 'Failed to fetch URL'], 400);
        }
        
        // Parse HTML for Open Graph tags
        $preview = $this->parseMetaTags($html, $url);
        
        return response()->json($preview);
        
    } catch (\Exception $e) {
        \Log::error('Link preview error: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to generate preview'], 500);
    }
}

private function parseMetaTags($html, $url)
{
    $dom = new \DOMDocument();
    @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
    $xpath = new \DOMXPath($dom);
    
    // Extract metadata
    $title = $this->getMetaContent($xpath, 'og:title') 
        ?? $this->getMetaContent($xpath, 'twitter:title')
        ?? $this->getTitleTag($dom);
    
    $description = $this->getMetaContent($xpath, 'og:description')
        ?? $this->getMetaContent($xpath, 'twitter:description')
        ?? $this->getMetaContent($xpath, 'description');
    
    $image = $this->getMetaContent($xpath, 'og:image')
        ?? $this->getMetaContent($xpath, 'twitter:image');
    
    // Make image URL absolute if relative
    if ($image && !filter_var($image, FILTER_VALIDATE_URL)) {
        $parsedUrl = parse_url($url);
        $base = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
        $image = $base . '/' . ltrim($image, '/');
    }
    
    $siteName = $this->getMetaContent($xpath, 'og:site_name') ?? parse_url($url, PHP_URL_HOST);
    
    return [
        'url' => $url,
        'title' => $title ? Str::limit($title, 100) : 'No title',
        'description' => $description ? Str::limit($description, 200) : '',
        'image' => $image,
        'site_name' => $siteName,
        'favicon' => $this->getFavicon($url)
    ];
}

private function getMetaContent($xpath, $property)
{
    $nodes = $xpath->query("//meta[@property='$property']/@content");
    if ($nodes->length > 0) {
        return $nodes->item(0)->nodeValue;
    }
    
    $nodes = $xpath->query("//meta[@name='$property']/@content");
    if ($nodes->length > 0) {
        return $nodes->item(0)->nodeValue;
    }
    
    return null;
}

private function getTitleTag($dom)
{
    $titles = $dom->getElementsByTagName('title');
    return $titles->length > 0 ? $titles->item(0)->nodeValue : null;
}

private function getFavicon($url)
{
    $parsedUrl = parse_url($url);
    $base = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
    return $base . '/favicon.ico';
}

    // Update your store method to save link preview data
public function store(Request $request)
{
    $specialcode = Session::get('specialcode');
    $username = Session::get('username');

    if (!$specialcode || !$username) {
        return response()->json(['error' => 'You must be logged in to post.'], 401);
    }

    try {
        preg_match_all('/#(\w+)/', $request->input('post_content'), $matches);
        $hashtags = $matches[1];
        $filePaths = json_decode($request->input('file_path'), true);

        $status = $request->input('status', 'published');
        $scheduledAt = $request->input('scheduled_at');
        $scheduledAt = $scheduledAt ? Carbon::parse($scheduledAt) : null;

        if ($status === 'published' && $scheduledAt && $scheduledAt->isFuture()) {
            $status = 'draft';
        }

        // ✅ NEW: Get link preview data
        $linkPreview = $request->input('link_preview');

        $post = SamplePost::create([
            'post_content' => $request->input('post_content'),
            'file_path' => json_encode($filePaths),
            'specialcode' => $specialcode,
            'user_id' => Session::get('id'),
            'username' => $username,
            'status' => $status,
            'scheduled_at' => $scheduledAt,
            'created_at' => now(),
            'updated_at' => now(),
            'text_color' => $request->input('colorpicker', '#000000'),
            'bgnd_color' => $request->input('bgColorPicker', '#ffffff'),
            'link_preview' => $linkPreview ? json_encode(json_decode($linkPreview)) : null, // ✅ NEW
        ]);

        foreach ($hashtags as $tag) {
            DB::table('hashtags')->insert([
                'post_id' => $post->id,
                'tag' => strtolower($tag),
                'created_at' => now(),
            ]);
        }

        return response()->json('Post saved!');
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    public function all(Request $request)
    {
        $specialcode = Session::get('specialcode');
        if (!$specialcode) return redirect('/account');

        $user = DB::table('users_record')->where('specialcode', $specialcode)->first();

        $posts = SamplePost::where('specialcode', $specialcode)
            ->where(function ($q) use ($request) {
                if ($request->input('view') === 'drafts') {
                    $q->where('status', 'draft');
                } else {
                    $q->where('status', 'published')
                      ->where(function ($q2) {
                          $q2->whereNull('scheduled_at')
                             ->orWhere('scheduled_at', '<=', now());
                      });
                }
            })
            ->when($request->filter === 'videos', function ($q) {
                $q->where(function ($q2) {
                    $q2->where('file_path', 'like', '%.mp4%')
                       ->orWhere('file_path', 'like', '%.webm%')
                       ->orWhere('file_path', 'like', '%.ogg%');
                });
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('post_content', 'like', '%' . $request->search . '%');
            })
            ->when($request->sort === 'likes', fn($q) => $q->orderByDesc('likes'))
            ->when($request->sort === 'views', fn($q) => $q->orderByDesc('views'))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->appends($request->all());

        $trashedPosts = SamplePost::onlyTrashed()->where('specialcode', $specialcode)->get();

        return view('all_posts', compact('posts', 'user', 'trashedPosts'));
    }

    public function edit($id)
    {
        $post = SamplePost::find($id);
        if (!$post) {
            abort(404, 'Post not found');
        }

        $user = DB::table('users_record')->where('specialcode', Session::get('specialcode'))->first();

        return view('edit_post', compact('post', 'user'));
    }

    public function destroy($id)
    {
        SamplePost::find($id)?->delete();
        return redirect()->route('posts.all')->with('success', 'Post deleted successfully.');
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'post_content' => 'required|string|max:1000',
        'colorpicker' => 'nullable|string',
        'bgColorPicker' => 'nullable|string',
    ]);

    $post = SamplePost::findOrFail($id);

    $updateData = [
        'post_content' => $request->post_content,
        'text_color' => $request->colorpicker,
        'bgnd_color' => $request->bgColorPicker,
        'updated_at' => now(),
    ];

    // Only update file_path if new media URLs were provided
    if ($request->has('media_urls')) {
        $newFiles = json_decode($request->input('media_urls'), true) ?? [];
        if (!empty($newFiles)) {
            $updateData['file_path'] = json_encode($newFiles);
        }
    }

    $post->update($updateData);

    if ($request->ajax() || $request->wantsJson()) {
        return response()->json(['status' => 'success', 'message' => 'Post updated successfully.']);
    }

    return redirect()->route('posts.all')->with('success', 'Post updated successfully.');
}

 
    public function bulkDelete(Request $request)
    {
        $ids = $request->post_ids ?? [];
        SamplePost::whereIn('id', $ids)->delete();
        return redirect()->route('posts.all')->with('success', 'Selected posts deleted.');
    }

    public function cancelSchedule($id)
    {
        SamplePost::where('id', $id)->update([
            'scheduled_at' => null,
        ]);

        return redirect()->back()->with('success', 'Scheduled post canceled.');
    }

    public function restore($id)
    {
        SamplePost::withTrashed()->where('id', $id)->restore();
        return redirect()->back()->with('success', 'Post restored successfully.');
    }


    public function forceDelete($id)
{
    SamplePost::withTrashed()->where('id', $id)->forceDelete();
    return redirect()->back()->with('success', 'Post permanently deleted.');
}


    // 👍 Like / Unlike
    public function like(Request $request, $id)
{
    $userId = Session::get('id');

    if (!$userId) {
        return $request->expectsJson()
            ? response()->json(['error' => 'Unauthorized'], 401)
            : redirect('/login')->with('error', 'You must be logged in.');
    }

    $alreadyLiked = PostLike::where('user_id', $userId)->where('post_id', $id)->exists();

    if ($alreadyLiked) {
        PostLike::where('user_id', $userId)->where('post_id', $id)->delete();
        $status = 'unliked';
    } else {
        PostLike::create(['user_id' => $userId, 'post_id' => $id]);
        $status = 'liked';
    }

    // ✅ Get updated count from the relationship
    $likesCount = PostLike::where('post_id', $id)->count();

    return $request->expectsJson()
        ? response()->json([
            'status' => $status,
            'likes_count' => $likesCount
        ])
        : back()->with('success', $status === 'liked' ? 'You liked the post.' : 'You unliked the post.');
}



    // 💬 Comment
    public function comment(Request $request, $id)
{
    $userId = Session::get('id');
    $parentId = $request->input('parent_id');

    $validator = Validator::make($request->all(), [
        'comment' => 'required|string|max:500'
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => 'Comment cannot be empty.']);
    }

    if ($parentId) {
        $hasCommented = PostComment::where('user_id', $userId)
            ->where('post_id', $id)
            ->whereNull('parent_id')
            ->exists();

        if (!$hasCommented) {
            return response()->json(['error' => 'You must write a comment before replying.']);
        }
    }

    PostComment::create([
        'user_id' => $userId,
        'post_id' => $id,
        'comment' => $request->input('comment'),
        'parent_id' => $parentId
    ]);

    return response()->json([
        'success' => $parentId ? '↩ Reply added!' : '💬 Comment posted!'
    ]);
}


    // 🔁 Reposts
    public function repost($id)
{
    $userId     = Session::get('id');
    $username   = Session::get('username');
    $specialcode = Session::get('specialcode');

    if (!$userId || !$username || !$specialcode) {
        return back()->with('error', 'You must be logged in to repost.');
    }

    // Check if already reposted
    $alreadyReposted = PostRepost::where('user_id', $userId)
        ->where('post_id', $id)
        ->exists();

    if ($alreadyReposted) {
        return back()->with('info', 'You already reposted this.');
    }

    // Record the repost action
    PostRepost::create([
        'user_id' => $userId,
        'post_id' => $id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Find the original post
    $originalPost = SamplePost::findOrFail($id);

    // Create a new post as the repost
    $repost = SamplePost::create([
        'post_content' => $originalPost->post_content,
        'file_path'    => is_string($originalPost->file_path)
                            ? $originalPost->file_path
                            : json_encode($originalPost->file_path),
        'specialcode'  => $specialcode,
        'user_id'      => $userId,
        'username'     => $username,
        'text_color'   => $originalPost->text_color,
        'bgnd_color'   => $originalPost->bgnd_color,
        'status'       => 'published',
        'created_at'   => now(),
        'updated_at'   => now(),
    ]);

    // Send notification to original post owner
    Notification::create([
        'user_id' => $userId,
        'message' => "{$username} reposted your post",
        'link' => route('posts.show', $repost->id),
        'notification_reciever_id' => $originalPost->user_id,
        'read_notification' => 'no',
        'type' => 'post_repost',
        'notifiable_type' => SamplePost::class,
        'notifiable_id' => $originalPost->id,
        'data' => json_encode([
            'post_id' => $originalPost->id,
            'reposted_by' => $username,
            'thumbnail' => $originalPost->file_path,
        ]),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return back()->with('success', 'Post reposted successfully!');
}


    // 📤 Share
    public function share(Request $request, $id)
{
    $userId = Session::get('id');
    $username = Session::get('username');
    
    if (!$userId || !$username) {
        return back()->with('error', 'You must be logged in to share.');
    }
    
    $post = SamplePost::findOrFail($id);
    
    // Get form data
    $shareToUsers = $request->input('share_to_users', []);
    $socialPlatforms = $request->input('social_platforms', []);
    $shareMessage = $request->input('share_message');
    
    // Validate at least one selection
    if (empty($shareToUsers) && empty($socialPlatforms)) {
        return back()->with('error', 'Please select at least one user or platform to share to.');
    }
    
    $shareCount = 0;
    
    // Share to selected users
    if (!empty($shareToUsers)) {
        foreach ($shareToUsers as $recipientId) {
            // Create share record
            PostShare::create([
                'user_id' => $userId,
                'recipient_id' => $recipientId, // ✅ This will save
                'post_id' => $id,
                'platform' => 'direct',
                'message' => $shareMessage, // ✅ This will save
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Send notification to recipient
            Notification::create([
                'user_id' => $userId,
                'message' => "{$username} shared a post with you!",
                'link' => route('posts.show', $id),
                'notification_reciever_id' => $recipientId,
                'read_notification' => 'no',
                'type' => 'post_share',
                'notifiable_type' => SamplePost::class,
                'notifiable_id' => $id,
                'data' => json_encode([
                    'post_id' => $id,
                    'shared_by' => $username,
                    'message' => $shareMessage,
                    'thumbnail' => $post->file_path,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $shareCount++;
        }
    }
    
    // Share to social platforms
    if (!empty($socialPlatforms)) {
        foreach ($socialPlatforms as $platform) {
            PostShare::create([
                'user_id' => $userId,
                'recipient_id' => null, // ✅ NULL is correct for social media
                'post_id' => $id,
                'platform' => $platform,
                'message' => $shareMessage, // ✅ This will save
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $shareCount++;
        }
    }
    
    // Notify original post owner
    if ($post->user_id != $userId) {
        Notification::create([
            'user_id' => $userId,
            'message' => "{$username} shared your post",
            'link' => route('posts.show', $id),
            'notification_reciever_id' => $post->user_id,
            'read_notification' => 'no',
            'type' => 'post_shared_by_others',
            'notifiable_type' => SamplePost::class,
            'notifiable_id' => $id,
            'data' => json_encode([
                'post_id' => $id,
                'shared_by' => $username,
                'share_count' => $shareCount,
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
    
    // Increment share counter
    // ✅ Increment by 1 (one share action), not by $shareCount
    SamplePost::where('id', $id)->increment('shares', 1);
    // SamplePost::where('id', $id)->increment('shares', $shareCount);
    
    return back()->with('success', "Post shared successfully with {$shareCount} " . Str::plural('recipient', $shareCount) . '!');
}
public function fetchComments($id)
{
    $post = SamplePost::with([
    'comments' => function ($query) {
        $query->latest()->take(10); // ✅ Limit to 10 comments
    },
    'comments.user',
    'comments.replies.user'
])->findOrFail($id);

    return view('partials.comments', compact('post'))->render();
}

public function fetchActionUsers($id, $type)
{
    $post = SamplePost::findOrFail($id);

    switch ($type) {
        case 'likes':
            $paginator = $post->likesRelation()->with('user')->paginate(5);
            break;
        case 'reposts':
            $paginator = $post->repostsRelation()->with('user')->paginate(5);
            break;
        case 'shares':
            $paginator = $post->sharesRelation()->with('user')->paginate(5);
            break;
        default:
            return response()->json([]);
    }

    $users = [];
    foreach ($paginator->items() as $item) {
        if ($item->user) {
            $users[] = [
                'id' => $item->user->id,
                'name' => $item->user->name,
                'profileimg' => $item->user->profileimg ?? asset('images/default.png'),
                'badge' => $item->user->badge_status ?? null,
                'timestamp' => $item->created_at->diffForHumans(),
            ];
        }
    }

    return response()->json([
        'data' => $users,
        'current_page' => $paginator->currentPage(),
        'next_page_url' => $paginator->nextPageUrl(),
        'prev_page_url' => $paginator->previousPageUrl(),
        'total' => $paginator->total()
    ]);
}


public function trackView(Request $request, $id)
{
    $userId = Session::get('id');

    $alreadyViewed = PostView::where('user_id', $userId)->where('post_id', $id)->exists();

    if (!$alreadyViewed) {
        PostView::create([
            'user_id' => $userId,
            'post_id' => $id
        ]);
    }

    $viewsCount = PostView::where('post_id', $id)->count();

    return response()->json([
        'status' => 'view recorded',
        'views_count' => $viewsCount
    ]);
}


public function viewers(Request $request, $id)
{
    $limit = $request->query('limit', 10);
    $page = $request->query('page', 1);
    $offset = ($page - 1) * $limit;

    $viewers = PostView::where('post_id', $id)
        ->whereHas('user')
        ->with('user')
        ->orderByDesc('created_at')
        ->skip($offset)
        ->take($limit)
        ->get()
        ->map(function ($view) {
            return [
                'id' => $view->user->id,
                'name' => $view->user->name,
                'username' => $view->user->username,
                'profileimg' => $view->user->profileimg,
                'country' => $view->user->country,
                'verified' => $view->user->badge_status ? asset($view->user->badge_status) : null,
                'created_at' => $view->created_at->diffForHumans()
            ];
        });

    $total = PostView::where('post_id', $id)->whereHas('user')->count();

    return response()->json([
        'users' => $viewers,
        'total' => $total,
        'page' => $page,
        'limit' => $limit
    ]);
}


public function rewardStatus($id)
{
    // $post = SamplePost::with('comments')->find($id);
    $post = SamplePost::with(['comments', 'likesRelation', 'repostsRelation', 'sharesRelation'])->find($id);


    if (!$post) return response()->json(['error' => 'Post not found'], 404);

    $ageInDays = now()->diffInDays($post->created_at);
    if ($ageInDays > 12) {
        return response()->json(['status' => 'cancelled', 'age' => $ageInDays]);
    }

    $likes = $post->likesRelationCount;
$comments = $post->comments->count();
$reposts = $post->repostsRelationCount;
$shares = $post->sharesRelationCount;


    $rewards = [
    'likes' => ['threshold' => 1000, 'amount' => 1000],       // ✅ Changed from 1000000 to 5
    'comments' => ['threshold' => 150, 'amount' => 1000],
    'reposts' => ['threshold' => 12, 'amount' => 500],
    'shares' => ['threshold' => 12, 'amount' => 500],
];


    $totalReward = 0;
    foreach ($rewards as $type => $rule) {
        $count = $$type;
        $alreadyCredited = PostReward::where('post_id', $post->id)->where('type', $type)->exists();

        if ($count >= $rule['threshold'] && !$alreadyCredited) {
            PostReward::create([
                'post_id' => $post->id,
                'user_id' => $post->user_id,
                'type' => $type,
                'amount' => $rule['amount'],
                'status' => 'active'
            ]);

            WalletTransaction::create([
                'wallet_owner_id' => $post->user_id,
                'payer_id' => null,
                'transaction_id' => 'reward_' . uniqid(),
                'tx_ref' => 'reward_' . uniqid(),
                'amount' => $rule['amount'],
                'currency' => 'NGN',
                'status' => 'successful',
                'description' => "Reward for {$type} milestone on post #{$post->id}"
            ]);
        }

        if ($count >= $rule['threshold']) {
            $totalReward += $rule['amount'];
        }
    }

    return response()->json([
        'status' => 'active',
        'age' => $ageInDays,
        'likes' => $likes,
        'comments' => $comments,
        'reposts' => $reposts,
        'shares' => $shares,
        'likes_reward' => $likes >= $rewards['likes']['threshold'],
        'comments_reward' => $comments >= $rewards['comments']['threshold'],
        'reposts_reward' => $reposts >= $rewards['reposts']['threshold'],
        'shares_reward' => $shares >= $rewards['shares']['threshold'],
        'total_reward' => $totalReward
    ]);
}





 public function notifications()
{
    $userId = Session::get('id');

    if (!$userId) {
        return redirect('/login')->with('error', 'You must be logged in.');
    }

    // ✅ Eager load sender and their login session
    $notifications = Notification::with(['sender', 'sender.loginSession'])
        ->where('notification_reciever_id', $userId)
        ->orderByDesc('created_at')
        ->paginate(10);

    $user = UserRecord::find($userId);

    return view('user.notifications', compact('notifications', 'user'));
}

public function trackShare(Request $request)
{
    $userId = Session::get('id');
    $username = Session::get('username');
    
    if (!$userId) {
        return response()->json(['error' => 'Not logged in'], 401);
    }
    
    $postId = $request->input('post_id');
    $platform = $request->input('platform');
    
    $post = SamplePost::find($postId);
    
    if (!$post) {
        return response()->json(['error' => 'Post not found'], 404);
    }
    
    // Create share record
    PostShare::create([
        'user_id' => $userId,
        'recipient_id' => null, // NULL for social media shares
        'post_id' => $postId,
        'platform' => $platform,
        'message' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    // Increment share count
    SamplePost::where('id', $postId)->increment('shares', 1);
    
    // Notify post owner
    if ($post->user_id != $userId) {
        Notification::create([
            'user_id' => $userId,
            'message' => "{$username} shared your post on " . ucfirst($platform),
            'link' => route('posts.show', $postId),
            'notification_reciever_id' => $post->user_id,
            'read_notification' => 'no',
            'type' => 'post_shared_by_others',
            'notifiable_type' => SamplePost::class,
            'notifiable_id' => $postId,
            'data' => json_encode([
                'post_id' => $postId,
                'shared_by' => $username,
                'platform' => $platform,
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
    
    return response()->json([
        'success' => true,
        'message' => 'Share tracked successfully',
        'platform' => $platform
    ]);
}

public function markNotificationRead($id)
{
    $userId = Session::get('id');

    if (!$userId) {
        return response()->json(['error' => 'Not logged in'], 401);
    }

    $notification = Notification::find($id);

    if (!$notification) {
        return response()->json(['error' => 'Notification not found'], 404);
    }

    if ($notification->notification_reciever_id == $userId) {
        $notification->update([
            'read_notification' => 'yes',
            'read_at' => now()
        ]);

        return response()->json(['success' => true]);
    }

    return response()->json(['error' => 'Unauthorized'], 403);
}

public function markAllNotificationsRead()
{
    $userId = Session::get('id');
    if (!$userId) return response()->json(['error' => 'Not logged in'], 401);

    Notification::where('notification_reciever_id', $userId)
        ->where('read_notification', 'no')
        ->update(['read_notification' => 'yes', 'read_at' => now()]);

    return response()->json(['success' => true]);
}

public function deleteNotification($id)
{
    $userId = Session::get('id');
    if (!$userId) return response()->json(['error' => 'Not logged in'], 401);

    $notification = Notification::find($id);
    if (!$notification) return response()->json(['error' => 'Not found'], 404);
    if ($notification->notification_reciever_id != $userId) return response()->json(['error' => 'Unauthorized'], 403);

    $notification->delete();
    return response()->json(['success' => true]);
}

public function deleteAllNotifications()
{
    $userId = Session::get('id');
    if (!$userId) return response()->json(['error' => 'Not logged in'], 401);

    Notification::where('notification_reciever_id', $userId)->delete();
    return response()->json(['success' => true]);
}

public function toggleSave(Request $request)
{
    $userId = Session::get('id');
    if (!$userId) {
        return response()->json(['error' => 'Not logged in'], 401);
    }

    $postId = $request->input('post_id');
    $existing = SavedPost::where('user_id', $userId)->where('post_id', $postId)->first();

    if ($existing) {
        $existing->delete();
        return response()->json(['status' => 'unsaved']);
    }

    SavedPost::create(['user_id' => $userId, 'post_id' => $postId]);
    return response()->json(['status' => 'saved']);
}

public function savedPosts()
{
    $userId = Session::get('id');
    $user = UserRecord::find($userId);

    if (!$user) {
        return redirect('/login')->with('error', 'You must be logged in.');
    }

    $savedPosts = SavedPost::where('user_id', $userId)
        ->with(['post.user'])
        ->whereHas('post')
        ->orderByDesc('created_at')
        ->paginate(10);

    $notifications = DB::table('notifications')
        ->where('notification_reciever_id', $userId)
        ->orderByDesc('created_at')
        ->get();

    return view('saved_posts', compact('user', 'savedPosts', 'notifications'));
}

public function shareAsTale(Request $request)
{
    $userId = Session::get('id');
    $username = Session::get('username');
    $specialcode = Session::get('specialcode');

    if (!$userId || !$specialcode) {
        return response()->json(['error' => 'Not logged in'], 401);
    }

    $post = SamplePost::find($request->input('post_id'));
    if (!$post) {
        return response()->json(['error' => 'Post not found'], 404);
    }

    $files = json_decode($post->file_path, true);
    $firstFile = (is_array($files) && count($files) > 0) ? $files[0] : null;

    $tale = new TalesExten();
    $tale->specialcode = $specialcode;
    $tale->tales_content = $post->post_content;
    $tale->tales_datetime = now();
    $tale->tales_types = 'post';
    $tale->username = $username;
    $tale->files_talesexten = $firstFile;
    $tale->text_color = $post->text_color ?? '#eaf1ecff';
    $tale->bgnd_color = $post->bgnd_color ?? '#2af50fff';
    $tale->type = 'tales';
    $tale->save();

    return response()->json(['status' => 'success', 'message' => 'Post shared as tale!']);
}

}