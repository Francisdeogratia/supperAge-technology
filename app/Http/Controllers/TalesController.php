<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\TalesExten;
use App\Models\Comment;
use App\Models\User;
use App\Models\UserRecord;
use Illuminate\Support\Facades\Session;
use App\Models\Like;
use App\Models\View;

class TalesController extends Controller
{
    // zonetime
    public function setTimezone(Request $request)
    {
        session(['user_timezone' => $request->timezone]);
        return response()->json(['status' => 'ok']);
    }

    public function viewTale($id)
    {
        $mainTale = TalesExten::where('tales_id', $id)->firstOrFail();

        $users = UserRecord::all();

        $userTales = TalesExten::where('username', $mainTale->username)
            ->where('created_at', '>=', now()->subDay())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($t) {
                $t->likes = $t->likes ?? 0;
                $t->shares = $t->shares ?? 0;
                $t->comments = $t->comments()->where('created_at', '>=', now()->subDay())->get();
                $user = UserRecord::where('specialcode', $t->specialcode)->first();
                $t->profileimg = $user->profileimg ?? null;
                $t->name = $user->name ?? null;
                $t->badge_status = $user->badge_status ?? null;
                $t->lastLoginSession = $user->lastLoginSession ?? null;
                return $t;
            });

        return view('viewtales', [
            'userTales' => $userTales,
            'users' => $users,
            'tale' => $mainTale,
            'taskId' => request()->query('task_id')
        ]);
    }

    // fetch tales
    public function fetchTales(Request $request)
{
    $tales = TalesExten::where('type', 'tales')
        ->where('created_at', '>=', now()->subDay())
        ->orderBy('created_at', 'desc')
        ->get()
        ->groupBy('specialcode');

    $html = '';

    foreach ($tales as $userTales) {
        $latestTale = $userTales->first();
        $totalCount = $userTales->count();

        $html .= '<a href="' . url('/viewtales/' . $latestTale->tales_id) . '" style="text-decoration:none;">';
        $html .= '<div class="cards" style="color:' . $latestTale->text_color . '; background-color:' . $latestTale->bgnd_color . ';">';

        // 1. Handle Media (Video/Image)
        if (Str::endsWith($latestTale->files_talesexten, ['.mp4', '.webm', '.ogg'])) {
            $html .= '<video autoplay muted loop poster="' . asset('images/best3.png') . '">';
            $html .= '<source src="' . $latestTale->files_talesexten . '" type="video/mp4">';
            $html .= '</video>';
        } elseif (Str::endsWith($latestTale->files_talesexten, ['.jpg', '.jpeg', '.png', '.gif'])) {
            $html .= '<img src="' . $latestTale->files_talesexten . '" alt="' . $latestTale->username . '">';
        }

        // 2. Handle Text Content
        if (!empty($latestTale->tales_content)) {
            $html .= '<i class="p" style="color:' . $latestTale->text_color . ';background-color:' . $latestTale->bgnd_color . ';">' . htmlspecialchars($latestTale->tales_content) . '</i>';
        }

        // 3. Handle Link Preview (The part you wanted to add)
        if (!empty($latestTale->link_preview)) {
            $linkData = json_decode($latestTale->link_preview, true);
            
            if ($linkData) {
                $html .= '<div class="link-preview-card-display" style="background: rgba(255,255,255,0.1); border-radius: 8px; margin: 5px; overflow: hidden; font-size: 10px; border: 1px solid rgba(255,255,255,0.2);">';
                
                if (!empty($linkData['image'])) {
                    $html .= '<div class="link-preview-image" style="width:100%; height:60px; overflow:hidden;">';
                    $html .= '<img src="' . $linkData['image'] . '" style="width:100%; height:100%; object-fit:cover;">';
                    $html .= '</div>';
                }

                $html .= '<div class="link-preview-content" style="padding: 5px;">';
                $html .= '<div class="link-preview-site" style="display:flex; align-items:center; gap:5px; margin-bottom:3px;">';
                $html .= '<img src="' . $linkData['favicon'] . '" class="link-preview-favicon" style="width:12px; height:12px;">';
                $html .= '<span style="font-weight:bold; font-size:9px;">' . htmlspecialchars($linkData['site_name']) . '</span>';
                $html .= '</div>';
                
                $html .= '<div class="link-preview-title" style="font-weight:bold; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">' . htmlspecialchars($linkData['title']) . '</div>';
                
                $html .= '</div>'; // close content
                $html .= '</div>'; // close card
            }
        }

        // 4. Handle Footer (Count and Username)
        $html .= '<p class="" style="position:absolute; bottom:50px; color:white; border-radius:50%; width:40px; height:40px; background:rgba(0,0,0,0.5); text-align:center; padding-top:10px; left:10px; font-size:14px;">' . $totalCount . '</p>';
        $html .= '<p style="font-size:12px; margin-left:10px;">@' . htmlspecialchars($latestTale->username) . '</p>';
        $html .= '</div>';
        $html .= '</a>';
    }

    return response()->json(['html' => $html]);
}

    // ✅ NEW: Fetch Link Preview for Tales
    public function fetchLinkPreview(Request $request)
    {
        $url = $request->input('url');
        
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return response()->json(['error' => 'Invalid URL'], 400);
        }
        
        try {
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
        
        $title = $this->getMetaContent($xpath, 'og:title') 
            ?? $this->getMetaContent($xpath, 'twitter:title')
            ?? $this->getTitleTag($dom);
        
        $description = $this->getMetaContent($xpath, 'og:description')
            ?? $this->getMetaContent($xpath, 'twitter:description')
            ?? $this->getMetaContent($xpath, 'description');
        
        $image = $this->getMetaContent($xpath, 'og:image')
            ?? $this->getMetaContent($xpath, 'twitter:image');
        
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

    // ✅ UPDATED: Store method with link preview support
    public function store(Request $request)
    {
        $specialcode = Session::get('specialcode');

        if (!$specialcode) {
            return response()->json(['error' => 'Access denied. Please log in first.'], 401);
        }
        
        try {
            $request->validate([
                'tales_msg' => 'nullable|string|required_without:tales_file_url', 
                'tales_file_url' => 'nullable|url|required_without:tales_msg', 
                'tales_cat' => 'required|string',
                'colorpicker' => 'nullable|string',
                'bgColorPicker' => 'nullable|string',
            ], [
                'tales_msg.required_without' => 'Please write a tale or upload a file.',
                'tales_file_url.required_without' => 'Please write a tale or upload a file.',
            ]);

            $tale = new TalesExten();
            $tale->specialcode = $specialcode;
            
            $talesMsg = $request->input('tales_msg');
            $tale->tales_content = empty($talesMsg) ? null : $talesMsg;

            $tale->tales_datetime = now();
            $tale->tales_types = $request->input('tales_cat');
            $tale->username = Session::get('username', 'guest');
            $tale->files_talesexten = $request->input('tales_file_url');
            $tale->text_color = $request->input('colorpicker', '#eaf1ecff');
            $tale->bgnd_color = $request->input('bgColorPicker', '#2af50fff');
            $tale->type = 'tales';
            
            // ✅ NEW: Save link preview data
            $linkPreview = $request->input('link_preview');
            $tale->link_preview = $linkPreview ? json_encode(json_decode($linkPreview)) : null;
            
            $tale->save();

            return response()->json([
                'message' => '@' . $tale->username . ' : shared tales/story successfully!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Tale submission failed', ['error' => $e->getMessage(), 'request_data' => $request->all()]);
            return response()->json(['error' => 'Server error. Please try again.'], 500);
        }
    }

    // insert tales comment 
    public function postComment(Request $request, $taleId)
    {
        $validator = \Validator::make($request->all(), [
            'comment' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $username = Session::get('username', 'guest');

        Comment::create([
            'tale_id' => $taleId,
            'username' => $username,
            'comment' => $request->comment,
        ]);

        $commentCount = Comment::where('tale_id', $taleId)->count();

        return response()->json([
            'message' => 'Comment posted successfully!',
            'username' => $username,
            'comment' => $request->comment,
            'comment_count' => $commentCount,
        ]);
    }

    // like
    public function likeTale(Request $request, $taleId)
    {
        $username = Session::get('username', 'guest');

        $alreadyLiked = Like::where('tale_id', $taleId)->where('username', $username)->exists();
        if ($alreadyLiked) {
            return response()->json(['error' => 'Already liked'], 409);
        }

        Like::create([
            'tale_id' => $taleId,
            'username' => $username,
        ]);

        TalesExten::where('tales_id', $taleId)->increment('likes');

        return response()->json(['likes' => TalesExten::find($taleId)->likes]);
    }

    // share to a user
    public function shareTaleTo(Request $request, $id)
    {
        $request->validate([
            'target' => 'required|string',
        ]);

        $tale = TalesExten::findOrFail($id);
        $senderId = Session::get('id');
        if (!$senderId) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        if (Str::startsWith($request->target, 'group_')) {
            $groupId = Str::after($request->target, 'group_');
            \DB::table('shared_tales')->insert([
                'tale_id' => $id,
                'sender_id' => $senderId,
                'group_id' => $groupId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $recipientId = $request->target;
            \DB::table('shared_tales')->insert([
                'tale_id' => $id,
                'sender_id' => $senderId,
                'recipient_id' => $recipientId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $tale->increment('shares');

        return response()->json(['message' => 'Tale shared successfully!', 'shares' => $tale->shares]);
    }

    public function edit($id) {
        $tale = TalesExten::findOrFail($id);
        if (Session::get('username') !== $tale->username) {
            abort(403, 'Unauthorized');
        }
        return view('tale.edit', compact('tale'));
    }

    public function destroy($id) {
        $tale = TalesExten::findOrFail($id);
        if (Session::get('username') !== $tale->username) {
            abort(403, 'Unauthorized');
        }

        $tale->delete();
        return redirect()->back()->with('success', 'Tale deleted successfully.');
    }

    public function update(Request $request, $id)
    {
        $tale = TalesExten::findOrFail($id);

        if (Session::get('username') !== $tale->username) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'tales_content' => 'required|string|max:1000',
            'cloudinary_url' => 'nullable|url',
        ]);

        $tale->tales_content = $request->tales_content;

        if ($request->filled('cloudinary_url')) {
            $tale->files_talesexten = $request->cloudinary_url;
        }

        $tale->save();

        return redirect()->route('view.tale', $tale->tales_id)->with('success', 'Tale updated successfully.');
    }

    public function registerView(Request $request, $taleId)
    {
        $username = Session::get('username', 'guest');

        $tale = TalesExten::where('tales_id', $taleId)->first();

        if (!$tale) {
            return response()->json(['error' => 'Tale not found'], 404);
        }

        if ($username === $tale->username) {
            return response()->json(['message' => 'Owner view ignored']);
        }

        $alreadyViewed = View::where('tale_id', $taleId)->where('username', $username)->exists();
        if ($alreadyViewed) {
            return response()->json(['message' => 'Already viewed']);
        }

        View::create([
            'tale_id' => $taleId,
            'username' => $username,
        ]);

        TalesExten::where('tales_id', $taleId)->increment('views');

        return response()->json([
            'message' => 'View registered',
            'views' => TalesExten::where('tales_id', $taleId)->value('views')
        ]);
    }

    public function getViewers($taleId)
    {
        $tale = TalesExten::where('tales_id', $taleId)->first();

        $users = View::where('tale_id', $taleId)
            ->where('views.username', '!=', $tale->username)
            ->join('users_record', 'views.username', '=', 'users_record.username')
            ->select(
                'users_record.id',
                'users_record.username',
                'users_record.profileimg',
                \DB::raw("CONCAT('" . asset('') . "', users_record.badge_status) as badge_status")
            )
            ->get();

        return response()->json($users);
    }

    public function getLikers($taleId)
    {
        $users = Like::where('tale_id', $taleId)
            ->join('users_record', 'likes.username', '=', 'users_record.username')
            ->select(
                'users_record.id',
                'users_record.username',
                'users_record.profileimg',
                \DB::raw("CASE
                            WHEN users_record.badge_status IS NOT NULL AND users_record.badge_status != ''
                            THEN CONCAT('" . asset('') . "', users_record.badge_status)
                            ELSE NULL
                          END as badge_status")
            )
            ->get();

        return response()->json($users);
    }

    public function getSharers($taleId)
    {
        $users = \DB::table('shared_tales')
            ->where('tale_id', $taleId)
            ->join('users_record', 'shared_tales.sender_id', '=', 'users_record.id')
            ->select(
                'users_record.username',
                'users_record.profileimg',
                \DB::raw("CASE 
                            WHEN users_record.badge_status IS NOT NULL AND users_record.badge_status != '' 
                            THEN CONCAT('" . asset('') . "', users_record.badge_status) 
                            ELSE NULL 
                          END as badge_status")
            )
            ->get();

        return response()->json($users);
    }

    public function getViewCount($taleId)
    {
        $views = TalesExten::where('tales_id', $taleId)->value('views');
        return response()->json(['views' => $views]);
    }
}