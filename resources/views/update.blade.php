<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-P7ZNRWKS7Z"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-P7ZNRWKS7Z');
    </script>

    <meta charset="utf-8">
    <meta name="author" content="omoha Ekenedilichukwu Francis">
    <meta name="description" content="Step into SupperAge — your global African village online. Forge connections with friends across continents, celebrate culture, and tap into new opportunities designed with you in mind. It’s more than a network — it’s your space to belong, thrive, and grow. 🌍✨">
    <meta name="keywords" content="African digital community, connect Africa online, Afrocentric platform, African diaspora network, Pan-African social app, African youth culture, black excellence online, African influencers, cultural exchange Africa, African innovation hub">
   <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />

    <meta http-equiv="X-UA-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        $ogTitle       = 'SupperAge — Your African Digital Community';
        $ogDescription = 'Step into SupperAge — your global African village online. Forge connections, celebrate culture, and tap into new opportunities.';
        $ogImage       = asset('images/best3.png');
        $ogUrl         = url()->current();

        if (!empty($post)) {
            $postAuthor  = \DB::table('users_record')->where('id', $post->user_id)->first();
            $authorName  = $postAuthor->name ?? ($post->username ?? 'SupperAge');
            $postText    = strip_tags($post->post_content ?? '');
            $ogTitle     = $authorName . (strlen($postText) ? ': ' . \Illuminate\Support\Str::limit($postText, 80) : '\'s post on SupperAge');
            $ogDescription = strlen($postText) ? \Illuminate\Support\Str::limit($postText, 200) : $ogDescription;

            // First image file
            $files = json_decode($post->file_path, true);
            if (is_array($files) && count($files) > 0) {
                $firstFile = $files[0];
                $ext = strtolower(pathinfo(parse_url($firstFile, PHP_URL_PATH), PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $ogImage = $firstFile;
                }
            }

            $ogUrl = route('posts.show', $post->id);
        }
    @endphp

    <!-- Open Graph / Facebook -->
    <meta property="og:type"        content="website">
    <meta property="og:url"         content="{{ $ogUrl }}">
    <meta property="og:title"       content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDescription }}">
    <meta property="og:image"       content="{{ $ogImage }}">
    <meta property="og:site_name"   content="SupperAge">

    <!-- Twitter Card -->
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="{{ $ogTitle }}">
    <meta name="twitter:description" content="{{ $ogDescription }}">
    <meta name="twitter:image"       content="{{ $ogImage }}">

    <title>@if(!empty($post)){{ $ogTitle }} | SupperAge @else SupperAge — Posts & Updates @endif</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>

    <!-- Stylesheets -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <!-- Font Awesome 4.7 (matches your fa fa-user-circle class) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    
    <!-- Your Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/darkmode.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/finebtn.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/talemodel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">
   
    <!-- google ads -->
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2892124627300694"
     crossorigin="anonymous"></script>
    
     
    <style>
/* ===== Modern Sidebar Styles ===== */
.sb-profile-card { background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); overflow: hidden; margin-bottom: 12px; }
.sb-cover { height: 140px; overflow: hidden; cursor: pointer; position: relative; }
.sb-cover img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s; }
.sb-cover:hover img { transform: scale(1.03); }
.sb-cover-placeholder { width: 100%; height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.5); font-size: 32px; }
.sb-profile-info { padding: 0 16px 14px; margin-top: -30px; position: relative; }
.sb-avatar-link { display: inline-block; }
.sb-avatar { width: 64px; height: 64px; border-radius: 50%; border: 3px solid #fff; box-shadow: 0 2px 6px rgba(0,0,0,0.15); object-fit: cover; }
.sb-avatar-placeholder { width: 64px; height: 64px; border-radius: 50%; border: 3px solid #fff; background: #e4e6eb; display: flex; align-items: center; justify-content: center; font-size: 32px; color: #65676b; }
.sb-name-row { margin-top: 6px; }
.sb-name { font-size: 17px; font-weight: 700; color: #050505; margin: 0; display: flex; align-items: center; gap: 4px; }
.sb-badge { width: 18px; height: 18px; }
.sb-username { font-size: 13px; color: #65676b; display: block; margin-bottom: 4px; }
.sb-bio { font-size: 13px; color: #65676b; margin: 4px 0 8px; line-height: 1.4; }
.sb-edit-profile { display: inline-flex; align-items: center; gap: 5px; padding: 6px 14px; background: #e4e6eb; color: #050505; border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none; transition: background 0.2s; }
.sb-edit-profile:hover { background: #d8dadf; text-decoration: none; color: #050505; }
.sb-stats { display: flex; border-top: 1px solid #e4e6eb; border-bottom: 1px solid #e4e6eb; }
.sb-stat { flex: 1; text-align: center; padding: 10px 8px; text-decoration: none; transition: background 0.15s; }
.sb-stat:hover { background: #f0f2f5; text-decoration: none; }
.sb-stat-num { display: block; font-size: 16px; font-weight: 700; color: #1877f2; }
.sb-stat-label { font-size: 11px; color: #65676b; font-weight: 500; }
.sb-links { display: grid; grid-template-columns: 1fr 1fr; gap: 6px; padding: 12px; }
.sb-link-item { display: flex; align-items: center; gap: 8px; padding: 8px 10px; border-radius: 8px; text-decoration: none; color: #050505; font-size: 12px; font-weight: 600; transition: background 0.15s; }
.sb-link-item:hover { background: #f0f2f5; text-decoration: none; color: #050505; }
.sb-link-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }
.sb-wallet-btn { display: flex; align-items: center; justify-content: center; gap: 8px; margin: 0 12px 10px; padding: 10px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; transition: opacity 0.2s; }
.sb-wallet-btn:hover { opacity: 0.9; color: #fff; text-decoration: none; }
.sb-bottom-links { display: flex; gap: 6px; padding: 0 12px 12px; }
.sb-bottom-link { flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 8px; background: #f0f2f5; border-radius: 8px; font-size: 12px; font-weight: 600; color: #050505; text-decoration: none; transition: background 0.15s; position: relative; }
.sb-bottom-link:hover { background: #e4e6eb; text-decoration: none; color: #050505; }
.sb-inbox-badge { background: #e41e3f; color: #fff; padding: 1px 6px; border-radius: 10px; font-size: 10px; font-weight: 700; }
.sb-premium-btn { flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 8px; background: #ffc107; color: #000; border-radius: 8px; font-size: 12px; font-weight: 700; text-decoration: none; transition: background 0.2s; }
.sb-premium-btn:hover { background: #ffb300; text-decoration: none; color: #000; }
.sb-discover-card { background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); padding: 14px; margin-bottom: 12px; }
.sb-discover-title { font-size: 15px; font-weight: 700; color: #050505; margin-bottom: 10px; display: flex; align-items: center; gap: 8px; }
.sb-discover-title i { color: #1877f2; }
.sb-discover-links { display: flex; flex-direction: column; gap: 2px; }
.sb-disc-link { display: flex; align-items: center; gap: 10px; padding: 8px 10px; border-radius: 8px; font-size: 13px; font-weight: 500; color: #050505; text-decoration: none; transition: background 0.15s; }
.sb-disc-link:hover { background: #f0f2f5; text-decoration: none; color: #050505; }
.sb-disc-link i { width: 20px; text-align: center; color: #65676b; }

/* Sidebar Dark Mode */
body.dark-mode .sb-profile-card { background: #242526; box-shadow: 0 1px 3px rgba(0,0,0,0.3); }
body.dark-mode .sb-avatar { border-color: #242526; }
body.dark-mode .sb-avatar-placeholder { background: #3A3B3C; border-color: #242526; color: #B0B3B8; }
body.dark-mode .sb-name { color: #E4E6EB; }
body.dark-mode .sb-username { color: #B0B3B8; }
body.dark-mode .sb-bio { color: #B0B3B8; }
body.dark-mode .sb-edit-profile { background: #3A3B3C; color: #E4E6EB; }
body.dark-mode .sb-edit-profile:hover { background: #4E4F50; color: #E4E6EB; }
body.dark-mode .sb-stats { border-color: #3E4042; }
body.dark-mode .sb-stat:hover { background: #3A3B3C; }
body.dark-mode .sb-stat-num { color: #2D88FF; }
body.dark-mode .sb-stat-label { color: #B0B3B8; }
body.dark-mode .sb-link-item { color: #E4E6EB; }
body.dark-mode .sb-link-item:hover { background: #3A3B3C; color: #E4E6EB; }
body.dark-mode .sb-bottom-link { background: #3A3B3C; color: #E4E6EB; }
body.dark-mode .sb-bottom-link:hover { background: #4E4F50; color: #E4E6EB; }
body.dark-mode .sb-discover-card { background: #242526; box-shadow: 0 1px 3px rgba(0,0,0,0.3); }
body.dark-mode .sb-discover-title { color: #E4E6EB; }
body.dark-mode .sb-disc-link { color: #E4E6EB; }
body.dark-mode .sb-disc-link:hover { background: #3A3B3C; color: #E4E6EB; }
body.dark-mode .sb-disc-link i { color: #B0B3B8; }

/* ===== Other People's Posts - Facebook/Instagram Feed ===== */
.op-section-header { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; padding: 12px 16px; background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
.op-section-header i { font-size: 20px; color: #1877f2; }
.op-section-header h5 { margin: 0; font-size: 18px; font-weight: 700; color: #050505; }
.op-section-header .op-header-sub { margin-left: auto; font-size: 12px; color: #65676b; }

.op-card { background: #fff; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; border: 1px solid #dbdbdb; margin-bottom: 16px; transition: box-shadow 0.2s; }
.op-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); }

/* Header - Instagram style with inline follow */
.op-header { display: flex; align-items: center; padding: 12px 16px; gap: 10px; border-bottom: none; }
.op-header-avatar { flex-shrink: 0; width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #e4e6eb; cursor: pointer; transition: border-color 0.2s; }
.op-header-avatar:hover { border-color: #1877f2; }
.op-author-info { flex: 1; min-width: 0; line-height: 1.3; }
.op-author-name { font-size: 14px; font-weight: 700; color: #050505; display: flex; align-items: center; gap: 4px; }
.op-author-name a { color: inherit; text-decoration: none; }
.op-author-name a:hover { text-decoration: underline; }
.op-author-meta { font-size: 12px; color: #65676b; margin-top: 1px; }
.op-author-loc { font-size: 11px; color: #8e8e8e; margin-top: 1px; }

/* Follow button - Instagram blue pill */
.op-follow-btn {
    padding: 6px 20px;
    background: #1877f2;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
    letter-spacing: 0.2px;
}
.op-follow-btn:hover { background: #1565c0; transform: scale(1.03); }
.op-follow-btn:active { transform: scale(0.97); }

/* Following label - subtle chip */
.op-following-label {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 6px 14px;
    background: #e4e6eb;
    color: #65676b;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

/* Three-dot menu in header */
.op-header .post-options-dropdown { flex-shrink: 0; margin-left: 4px; }

/* Body */
.op-body { padding: 0 16px 10px; min-height: 20px; border: none; }
.op-body p { margin: 0 0 6px; font-size: 15px; line-height: 1.5; word-wrap: break-word; white-space: pre-wrap; color: #050505; }
.op-body .read-more-link, .op-body .read-less-link { font-size: 14px; font-weight: 600; text-decoration: none; color: #1877f2; }
.op-body .read-more-link:hover, .op-body .read-less-link:hover { text-decoration: underline; }

/* Media in card */
.op-card .mb-2 { padding: 0; }
.op-card .first-file-wrapper { border-radius: 0 !important; max-width: 100% !important; width: 100% !important; height: auto !important; max-height: 520px !important; }
.op-card .first-file-wrapper img { width: 100% !important; height: auto !important; max-height: 520px !important; object-fit: cover !important; }
.op-card .first-file-wrapper .video-thumb-wrapper { width: 100% !important; height: auto !important; max-height: 520px !important; }
.op-card .first-file-wrapper .video-thumb-wrapper img { height: auto !important; max-height: 520px !important; }
.op-card .img-fluid { border-radius: 0 !important; margin-bottom: 0 !important; width: 100%; }

/* Hashtags */
.op-card .badge.bg-secondary { background: #e4e6eb !important; color: #1877f2; font-weight: 500; font-size: 12px; padding: 4px 10px; border-radius: 14px; margin: 1px 2px; }

/* Engagement stats row - Facebook style compact */
.op-engagement-row { display: flex; align-items: center; justify-content: space-between; padding: 8px 16px 4px; font-size: 13px; color: #65676b; }
.op-engagement-row .op-likes-summary { display: flex; align-items: center; gap: 6px; }
.op-engagement-row .op-likes-summary i { color: #1877f2; font-size: 14px; }
.op-engagement-row .op-right-stats { display: flex; gap: 12px; }
.op-engagement-row a { color: #65676b; text-decoration: none; }
.op-engagement-row a:hover { text-decoration: underline; color: #050505; }

/* Meta row (reward, views) */
.op-meta-row { display: flex; align-items: center; justify-content: space-between; padding: 2px 16px 6px; font-size: 12px; color: #8e8e8e; }
.op-meta-row .view-count { cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: color 0.15s; }
.op-meta-row .view-count:hover { color: #1877f2 !important; }
.op-card .badge.bg-success { background: #e8f5e9 !important; color: #2e7d32; font-size: 11px; padding: 3px 8px; border-radius: 12px; }

/* Action buttons - Facebook style */
.op-actions { display: flex; border-top: 1px solid #e4e6eb; padding: 2px 8px; margin: 0; }
.op-action-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 4px;
    background: none;
    border: none;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    color: #65676b;
    cursor: pointer;
    transition: background 0.15s, color 0.15s;
    text-decoration: none;
}
.op-action-btn:hover { background: #f0f2f5; }
.op-action-btn.hover-like:hover, .op-action-btn.hover-like.active { color: #1877f2; }
.op-action-btn.hover-comment:hover { color: #44bd63; }
.op-action-btn.hover-repost:hover { color: #1877f2; }
.op-action-btn.hover-share:hover { color: #e67e22; }

/* Who liked/shared detail section */
.op-card .op-social-detail { display: block; padding: 8px 16px; font-size: 12px; line-height: 1.7; color: #65676b; border-top: 1px solid #e4e6eb; }
.op-card .op-social-detail a { color: #050505; font-weight: 600; text-decoration: none; }
.op-card .op-social-detail a:hover { text-decoration: underline; }
.op-card .op-social-detail i { margin-right: 2px; }

/* Comments collapse */
.op-card .collapse, .op-card .collapsing { padding: 0 16px; }

/* ---- Dark mode ---- */
body.dark-mode .op-section-header { background: #242526; box-shadow: 0 1px 3px rgba(0,0,0,0.3); }
body.dark-mode .op-section-header h5 { color: #E4E6EB; }
body.dark-mode .op-section-header i { color: #2D88FF; }
body.dark-mode .op-section-header .op-header-sub { color: #B0B3B8; }
body.dark-mode .op-card { background: #242526; border-color: #3E4042; box-shadow: 0 1px 3px rgba(0,0,0,0.3); }
body.dark-mode .op-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.4); }
body.dark-mode .op-header-avatar { border-color: #3E4042; }
body.dark-mode .op-author-name, body.dark-mode .op-author-name a { color: #E4E6EB; }
body.dark-mode .op-author-meta { color: #B0B3B8; }
body.dark-mode .op-author-loc { color: #B0B3B8; }
body.dark-mode .op-body p { color: #E4E6EB; }
body.dark-mode .op-body:not([style*="background-color"]) { background-color: #242526; color: #E4E6EB; }
body.dark-mode .card-body:not([style*="background-color"]) { background-color: #242526; color: #E4E6EB; }
body.dark-mode .op-body .read-more-link, body.dark-mode .op-body .read-less-link { color: #2D88FF; }
body.dark-mode .op-follow-btn { background: #2D88FF; }
body.dark-mode .op-follow-btn:hover { background: #1A6FE0; }
body.dark-mode .op-following-label { background: #3A3B3C; color: #B0B3B8; }
body.dark-mode .op-engagement-row { color: #B0B3B8; }
body.dark-mode .op-engagement-row a { color: #B0B3B8; }
body.dark-mode .op-engagement-row a:hover { color: #E4E6EB; }
body.dark-mode .op-meta-row { color: #B0B3B8; }
body.dark-mode .op-actions { border-top-color: #3E4042; }
body.dark-mode .op-action-btn { color: #B0B3B8; }
body.dark-mode .op-action-btn:hover { background: #3A3B3C; }
body.dark-mode .op-card .op-social-detail { border-top-color: #3E4042; color: #B0B3B8; }
body.dark-mode .op-card .op-social-detail a { color: #E4E6EB; }
body.dark-mode .op-card .badge.bg-secondary { background: #3A3B3C !important; color: #2D88FF; }

  @keyframes fadeZoomIn {
    from { opacity: 0; transform: scale(0.8); }
    to   { opacity: 1; transform: scale(1); }
  }

  #imageModal img.zoom-img {
    max-width: 90%;
    max-height: 90%;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0,0,0,0.5);
    animation: fadeZoomIn 0.35s ease;
  }
.zoom-hover {
    transition: transform 0.3s ease;
}
.zoom-hover:hover {
    transform: scale(1.05);
}

.video-thumb-wrapper {
  position: relative;
  display: inline-block;
  width: 100%;
  max-height: 150px;
  border-radius: 10px;
  overflow: hidden;
}

.video-thumb-wrapper img {
  width: 100%;
  height: auto;
  display: block;
}

.video-thumb-wrapper .play-icon {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  font-size: 20px;
  color: white;
  background: rgba(0, 0, 0, 0.5);
  border-radius: 50%;
  padding: 10px 15px;
  pointer-events: none;
}


.video-thumb-wrapper:hover img {
  transform: scale(1.05);
  transition: transform 0.3s ease;
}

.video-thumb-wrapper:hover .play-icon {
  background: rgba(255, 255, 255, 0.8);
  color: #000;
}

/* Inline Feed Video Auto-play */
.inline-video-wrapper {
  position: relative;
  width: 100%;
  border-radius: 10px;
  overflow: hidden;
  background: #000;
  cursor: pointer;
}
.inline-feed-video {
  width: 100%;
  max-height: 500px;
  object-fit: contain;
  display: block;
  background: #000;
}
.inline-video-mute-btn {
  position: absolute;
  bottom: 12px;
  right: 12px;
  width: 32px;
  height: 32px;
  background: rgba(0,0,0,0.6);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 14px;
  cursor: pointer;
  z-index: 5;
  transition: background 0.2s;
}
.inline-video-mute-btn:hover {
  background: rgba(0,0,0,0.85);
}
.inline-video-expand-btn {
  position: absolute;
  bottom: 12px;
  right: 52px;
  width: 32px;
  height: 32px;
  background: rgba(0,0,0,0.6);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 14px;
  cursor: pointer;
  z-index: 5;
  transition: background 0.2s;
}
.inline-video-expand-btn:hover {
  background: rgba(0,0,0,0.85);
}
.inline-video-paused-icon {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  font-size: 48px;
  color: rgba(255,255,255,0.8);
  pointer-events: none;
  z-index: 4;
  transition: opacity 0.3s;
}

/* ===== Facebook-style Media Grid ===== */
.fb-media-grid {
  display: grid;
  gap: 3px;
  border-radius: 8px;
  overflow: hidden;
  max-height: 600px;
}
.fb-media-grid.grid-1 {
  grid-template-columns: 1fr;
  max-height: 500px;
}
.fb-media-grid.grid-2 {
  grid-template-columns: 1fr 1fr;
  max-height: 350px;
}
.fb-media-grid.grid-3 {
  grid-template-columns: 1fr 1fr;
  grid-template-rows: 1fr 1fr;
  max-height: 400px;
}
.fb-media-grid.grid-3 .fb-media-item:first-child {
  grid-row: 1 / 3;
}
.fb-media-grid.grid-4,
.fb-media-grid.grid-5plus {
  grid-template-columns: 1fr 1fr;
  grid-template-rows: 1fr 1fr;
  max-height: 400px;
}
.fb-media-item {
  position: relative;
  overflow: hidden;
  cursor: pointer;
  background: #000;
}
.fb-media-item img,
.fb-media-item video {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}
.fb-media-item a {
  display: block;
  width: 100%;
  height: 100%;
}
.fb-media-item .video-thumb-wrapper {
  width: 100%;
  height: 100%;
  position: relative;
}
.fb-media-item .video-thumb-wrapper img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.fb-media-item .fb-play-icon {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  font-size: 36px;
  color: white;
  background: rgba(0, 0, 0, 0.55);
  border-radius: 50%;
  width: 56px;
  height: 56px;
  display: flex;
  align-items: center;
  justify-content: center;
  pointer-events: none;
  z-index: 2;
}
.fb-media-overlay {
  position: absolute;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0, 0, 0, 0.55);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 28px;
  font-weight: 700;
  z-index: 3;
  cursor: pointer;
}

/* Small grid variant for compact cards */
.fb-media-grid.fb-grid-sm {
  max-height: 300px;
}
.fb-media-grid.fb-grid-sm.grid-1 {
  max-height: 250px;
}
.fb-media-grid.fb-grid-sm.grid-2 {
  max-height: 200px;
}
.fb-media-grid.fb-grid-sm.grid-3,
.fb-media-grid.fb-grid-sm.grid-4,
.fb-media-grid.fb-grid-sm.grid-5plus {
  max-height: 250px;
}

/* Dark mode */
body.dark-mode .fb-media-grid {
  border-color: #333;
}
body.dark-mode .fb-media-item {
  background: #1a1a1a;
}
body.dark-mode .fb-media-overlay {
  background: rgba(0, 0, 0, 0.65);
}

/* My Current Posts card sizing */
body.dark-mode .d-flex.flex-nowrap > div[style*="border: 1px solid"] {
  border-color: #333 !important;
}
/* ===== End Media Grid ===== */

#userListModalBody img {
  object-fit: cover;
}

/* Desktop friend badge */
.icon.watch .badge {
  position: absolute;
  top: -5px;
  right: -10px;
  background: #e41e3f;
  color: white;
  font-size: 11px;
  font-weight: bold;
  padding: 2px 6px;
  border-radius: 10px;
  min-width: 18px;
  text-align: center;
}






/* ===== Create Post Modal - Modern Facebook Style ===== */
.modern-modal {
    border-radius: 12px !important;
    border: none !important;
    box-shadow: 0 8px 30px rgba(0,0,0,0.15) !important;
    overflow: hidden;
}

/* Header */
.modal-header-custom {
    background: #1877f2;
    padding: 16px 20px;
    border: none;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.header-text { flex: 1; }

.modal-title-custom {
    color: white;
    font-size: 18px;
    font-weight: 700;
    margin: 0 0 4px 0;
}

.modal-title-custom i { font-size: 16px; margin-right: 8px; }

.location-badge {
    display: inline-block;
    background: rgba(255,255,255,0.2);
    color: white;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    backdrop-filter: blur(10px);
}

.location-badge i { margin-right: 4px; }

.user-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    border: 2px solid rgba(255,255,255,0.8);
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.user-avatar img { width: 100%; height: 100%; object-fit: cover; }
.user-avatar i { font-size: 44px; color: white; }

/* Body */
.modal-body-custom {
    padding: 16px;
    background: #fff;
    max-height: 70vh;
    overflow-y: auto;
}

/* Media Preview - Horizontal scroll */
.media-preview-container {
    margin-bottom: 12px;
    border-radius: 10px;
    overflow: hidden;
}

.media-count-label {
    font-size: 12px;
    font-weight: 600;
    color: #1877f2;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.media-preview-scroll {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding: 6px 2px 10px;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
}

.media-preview-scroll::-webkit-scrollbar { height: 4px; }
.media-preview-scroll::-webkit-scrollbar-thumb { background: #1877f2; border-radius: 4px; }

.media-thumb {
    position: relative;
    flex-shrink: 0;
    width: 100px;
    height: 100px;
    border-radius: 10px;
    overflow: hidden;
    border: 2px solid #e4e6eb;
    scroll-snap-align: start;
    transition: border-color 0.2s;
    background: #f0f2f5;
}

.media-thumb:hover { border-color: #1877f2; }

.media-thumb img, .media-thumb video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.media-thumb-remove {
    position: absolute;
    top: 4px;
    right: 4px;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: rgba(0,0,0,0.6);
    color: #fff;
    border: none;
    font-size: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    opacity: 0;
    transition: opacity 0.2s;
    z-index: 2;
}

.media-thumb:hover .media-thumb-remove { opacity: 1; }
.media-thumb-remove:hover { background: #e4002b; }

.media-thumb-name {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.7));
    color: #fff;
    font-size: 9px;
    padding: 12px 4px 3px;
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.media-thumb-play {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 28px;
    height: 28px;
    background: rgba(0,0,0,0.5);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 10px;
    pointer-events: none;
}

/* Post Input */
.post-input-wrapper {
    position: relative;
    margin-bottom: 14px;
}

.styled-text-overlay {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    padding: 14px;
    border: 1.5px solid transparent;
    border-radius: 10px;
    white-space: pre-wrap;
    word-wrap: break-word;
    color: transparent;
    background-color: transparent;
    pointer-events: none;
    z-index: 1;
}

.post-textarea {
    position: relative;
    width: 100%;
    min-height: 100px;
    padding: 14px;
    border: 1.5px solid #e4e6eb;
    border-radius: 10px;
    background-color: #f0f2f5;
    color: #050505;
    z-index: 2;
    resize: vertical;
    font-size: 15px;
    transition: border-color 0.2s, background 0.2s;
}

.post-textarea:focus {
    outline: none;
    background: #fff;
    border-color: #1877f2;
    box-shadow: 0 0 0 2px rgba(24, 119, 242, 0.1);
}

/* Color Pickers */
.color-picker-section {
    display: flex;
    gap: 10px;
    margin-bottom: 14px;
}

.color-picker-item {
    flex: 1;
    background: #f0f2f5;
    padding: 10px;
    border-radius: 10px;
    text-align: center;
    display: flex;
    align-items: center;
    gap: 10px;
}

.color-picker-item label {
    font-size: 12px;
    font-weight: 600;
    color: #65676b;
    margin: 0;
    white-space: nowrap;
}

.color-picker-item label i { margin-right: 4px; }

.color-picker-item input[type="color"] {
    width: 36px;
    height: 36px;
    border: 2px solid #e4e6eb;
    border-radius: 50%;
    cursor: pointer;
    transition: transform 0.2s;
    padding: 2px;
}

.color-picker-item input[type="color"]:hover { transform: scale(1.1); }

/* Preview Box */
.preview-box { margin-bottom: 14px; }

.preview-label {
    font-size: 12px;
    font-weight: 600;
    color: #1877f2;
    margin-bottom: 6px;
}

.preview-label i { margin-right: 4px; }

.preview-content {
    padding: 12px;
    border-radius: 10px;
    border: 1.5px solid #e4e6eb;
    min-height: 50px;
    background: #f0f2f5;
}

#previewText { margin: 0; font-size: 14px; }

/* Hashtags */
.hashtags-display {
    margin-bottom: 12px;
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.hashtag-badge {
    background: #e7f3ff;
    color: #1877f2;
    padding: 4px 12px;
    border-radius: 14px;
    font-size: 12px;
    font-weight: 600;
}

/* Post Options */
.post-options-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 14px;
}

.option-item label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #65676b;
    margin-bottom: 6px;
}

.option-item label i { margin-right: 4px; }

.custom-select,
.custom-input {
    width: 100%;
    padding: 10px;
    border: 1.5px solid #e4e6eb;
    border-radius: 8px;
    font-size: 13px;
    transition: border-color 0.2s;
    background: #f0f2f5;
}

.custom-select:focus,
.custom-input:focus { outline: none; border-color: #1877f2; background: #fff; }

/* Action Row */
.action-row {
    display: flex;
    gap: 10px;
    margin-bottom: 12px;
}

.media-upload-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 12px;
    background: #f0f2f5;
    border: 1.5px solid #e4e6eb;
    color: #1877f2;
    border-radius: 10px;
    font-weight: 600;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s;
}

.media-upload-btn:hover {
    background: #e7f3ff;
    border-color: #1877f2;
}

.media-upload-btn i { font-size: 16px; }

.post-submit-btn {
    flex: 1.5;
    padding: 12px;
    background: #1877f2;
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 700;
    font-size: 15px;
    cursor: pointer;
    transition: all 0.2s;
}

.post-submit-btn:hover {
    background: #1565c0;
    box-shadow: 0 4px 12px rgba(24, 119, 242, 0.3);
}

.post-submit-btn i { margin-right: 6px; }

/* Progress Bar */
.progress-container {
    display: none;
    margin-top: 12px;
    background: #f0f2f5;
    padding: 8px;
    border-radius: 8px;
}

.progress-bar-fill {
    height: 6px;
    width: 0%;
    background: #1877f2;
    border-radius: 4px;
    transition: width 0.3s;
}

.progress-text {
    display: block;
    margin-top: 4px;
    font-size: 11px;
    color: #1877f2;
    font-weight: 600;
}

/* User Info */
.user-info-display {
    background: #f0f2f5;
    padding: 10px 12px;
    border-radius: 8px;
    font-size: 12px;
    color: #65676b;
    margin-top: 12px;
}

.user-info-display i { color: #1877f2; margin-right: 4px; }

/* Footer */
.modal-footer-custom {
    padding: 14px 20px;
    background: #fff;
    border-top: 1px solid #e4e6eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.status-message { color: #28a745; font-weight: 600; font-size: 13px; }

.loading-spinner {
    display: none;
    font-size: 22px;
    color: #1877f2;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.btn-close-modal {
    background: #e4e6eb;
    color: #050505;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-close-modal:hover { background: #d0d2d6; }
.btn-close-modal i { margin-right: 6px; }

/* Scrollbar */
.modal-body-custom::-webkit-scrollbar { width: 6px; }
.modal-body-custom::-webkit-scrollbar-track { background: transparent; }
.modal-body-custom::-webkit-scrollbar-thumb { background: #bcc0c4; border-radius: 6px; }
.modal-body-custom::-webkit-scrollbar-thumb:hover { background: #1877f2; }

.modal-body-custom::-webkit-scrollbar-thumb:hover {
    background: #764ba2;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .modal-dialog {
        margin: 10px;
    }
    
    .modal-header-custom {
        padding: 15px;
    }
    
    .modal-title-custom {
        font-size: 18px;
    }
    
    .location-badge {
        font-size: 10px;
        padding: 4px 10px;
    }
    
    .user-avatar {
        width: 45px;
        height: 45px;
    }
    
    .modal-body-custom {
        padding: 20px;
    }
    
    .post-options-grid {
        grid-template-columns: 1fr;
    }
    
    .action-row {
        flex-direction: column;
    }
    
    .color-picker-section {
        gap: 10px;
    }
    
    .color-picker-item input[type="color"] {
        width: 50px;
        height: 50px;
    }
}

@media (max-width: 480px) {
    .modal-title-custom {
        font-size: 16px;
    }
    
    .user-avatar {
        width: 40px;
        height: 40px;
    }
    
    .modal-body-custom {
        padding: 15px;
    }
    
    .post-textarea {
        font-size: 14px;
        min-height: 100px;
    }

    .hhnn{
    display: none !important;
  }
}

/* for postlink */

/* Link Preview Styles */
.link-preview-loading {
    padding: 15px;
    text-align: center;
    background: #f8f9fa;
    border-radius: 10px;
    color: #667eea;
}

.link-preview-card {
    position: relative;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    overflow: hidden;
    background: white;
    margin-bottom: 15px;
    transition: all 0.3s ease;
}

.link-preview-card:hover {
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
}

.link-preview-close {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(0, 0, 0, 0.6);
    color: white;
    border: none;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: background 0.2s;
}

.link-preview-close:hover {
    background: rgba(220, 53, 69, 0.9);
}

.link-preview-image {
    width: 100%;
    height: 200px;
    overflow: hidden;
    background: #f5f5f5;
}

.link-preview-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.link-preview-content {
    padding: 15px;
}

.link-preview-site {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
    color: #666;
    font-size: 13px;
}

.link-preview-favicon {
    width: 16px;
    height: 16px;
    border-radius: 3px;
}

.link-preview-title {
    font-size: 16px;
    font-weight: bold;
    color: #333;
    margin-bottom: 8px;
    line-height: 1.3;
}

.link-preview-description {
    font-size: 14px;
    color: #666;
    line-height: 1.5;
    margin-bottom: 10px;
}

.link-preview-url {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 13px;
    color: #667eea;
    text-decoration: none;
    word-break: break-all;
}

.link-preview-url:hover {
    text-decoration: underline;
    color: #5568d3;
}

/* Display version (in posts) */
.link-preview-card-display {
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    overflow: hidden;
    margin: 15px 0;
    background: white;
    transition: all 0.3s ease;
}

.link-preview-card-display:hover {
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.link-preview-card-display .link-preview-image {
    height: 180px;
}

.link-preview-card-display .link-preview-content {
    padding: 12px;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .link-preview-image {
        height: 150px;
    }
    
    .link-preview-card-display .link-preview-image {
        height: 140px;
    }
    
    .link-preview-title {
        font-size: 15px;
    }
    
    .link-preview-description {
        font-size: 13px;
    }
}




/* Custom Icon Hover Effects */
.btn:hover i.fa-thumbs-up, .hover-like:hover i { color: #007bff !important; }    /* Blue */
.btn:hover i.fa-comment, .hover-comment:hover i { color: #28a745 !important; }   /* Green */
.btn:hover i.fa-retweet, .hover-repost:hover i { color: #6f42c1 !important; }   /* Purple */
.btn:hover i.fa-paper-plane, .hover-share:hover i { color: #fd7e14 !important; } /* Orange */
.hover-world:hover i { color: #17a2b8 !important; }                             /* Cyan */

/* Transition for smooth color change */
.btn i, .text-muted i {
    transition: color 0.3s ease;
    margin-right: 3px;
}



/* Smooth transition for icons */
.text-muted i {
    transition: all 0.3s ease;
    margin-right: 5px;
}

/* Specific Hover Colors */
.hover-like:hover i { color: #007bff !important; }    /* Blue */
.hover-repost:hover i { color: #6f42c1 !important; }  /* Purple */
.hover-share:hover i { color: #fd7e14 !important; }   /* Orange */
.hover-users:hover i { color: #20c997 !important; }   /* Teal */
.hover-world:hover i { color: #17a2b8 !important; }   /* Cyan */


/* Copy link hover color (Gold/Yellow) */
.hover-copy:hover i { color: #ffc107 !important; }

/* Smooth transition for the new icon */
.text-muted i.fa-copy {
    transition: all 0.3s ease;
    cursor: pointer;
}




/* Container for the dropdown */
.post-options-dropdown {
    position: relative;
    display: inline-block;
}

/* The Three Dots Button */
.options-btn {
    background: #f0f2f5;
    border: none;
    color: #65676b;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s, color 0.2s;
    outline: none;
}

.options-btn:hover {
    background: #e4e6eb;
    color: #050505;
}

/* The Menu - Hidden by default */
.options-menu {
    position: absolute;
    right: 0;
    top: 40px;
    background: white;
    min-width: 180px;
    border-radius: 10px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    z-index: 1000;
    padding: 8px;
    display: none; /* Hidden */
    flex-direction: column;
    border: 1px solid rgba(0,0,0,0.05);
}

/* Show menu when button is clicked (focused) */
.post-options-dropdown:focus-within .options-menu {
    display: flex;
    animation: slideDown 0.2s ease-out;
}

/* Menu Items */
.options-item {
    display: flex;
    align-items: center;
    padding: 10px 12px;
    color: #1c1e21;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    border-radius: 6px;
    transition: background 0.2s;
}

.options-item:hover {
    background: #f2f2f2;
    text-decoration: none;
    color: #1c1e21;
}

.options-item i {
    width: 20px;
    margin-right: 10px;
    color: #8a8d91;
    text-align: center;
}

/* Divider */
.options-divider {
    height: 1px;
    background: #e5e5e5;
    margin: 6px 0;
}

/* Slide animation */
@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}




/* ✅ ADD THIS CSS - Makes link preview match file attachment size */
.link-preview-card-display {
    position: relative;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    overflow: hidden;
    background: white;
    margin-top: 8px;
    width: 100%;
    height: 150px; /* ✅ Same as your files */
    display: flex;
    flex-direction: column;
}

.link-preview-card-display .link-preview-image {
    width: 100%;
    height: 80px; /* ✅ Smaller image area */
    overflow: hidden;
    background: #f5f5f5;
    flex-shrink: 0;
}

.link-preview-card-display .link-preview-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.link-preview-card-display .link-preview-content {
    padding: 6px 8px;
    overflow: hidden;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.link-preview-card-display .link-preview-site {
    display: flex;
    align-items: center;
    gap: 4px;
    margin-bottom: 3px;
    color: #666;
    font-size: 9px;
    overflow: hidden;
}

.link-preview-card-display .link-preview-favicon {
    width: 10px;
    height: 10px;
    border-radius: 2px;
    flex-shrink: 0;
}

.link-preview-card-display .link-preview-title {
    font-size: 11px;
    font-weight: bold;
    color: #333;
    margin: 0 0 3px 0;
    line-height: 1.2;
    display: -webkit-box;
    -webkit-line-clamp: 1; /* ✅ Only 1 line */
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

.link-preview-card-display .link-preview-description {
    font-size: 9px;
    color: #666;
    line-height: 1.3;
    margin: 0 0 4px 0;
    display: -webkit-box;
    -webkit-line-clamp: 1; /* ✅ Only 1 line */
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

.link-preview-card-display .link-preview-url {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    font-size: 8px;
    color: #667eea;
    text-decoration: none;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    margin-top: auto; /* ✅ Push to bottom */
}

.link-preview-card-display .link-preview-url:hover {
    text-decoration: underline;
}

.link-preview-card-display .link-preview-url i {
    font-size: 7px;
    flex-shrink: 0;
}








/* ✅ BEAUTIFUL POST LINK BUTTONS - Professional Design */

/* Read More/Less Links */
.read-more-link,
.read-less-link {
    display: inline-block;
    margin-left: 5px;
    padding: 4px 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white !important;
    text-decoration: none;
    border-radius: 15px;
    font-size: 11px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    vertical-align: middle;
}

.read-more-link:hover,
.read-less-link:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.5);
    color: white !important;
    text-decoration: none;
}

/* Link Preview URL Button */
.link-preview-card-display .link-preview-url {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 9px;
    color: white !important;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 4px 8px;
    border-radius: 12px;
    text-decoration: none;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    margin-top: auto;
    transition: all 0.3s ease;
    box-shadow: 0 2px 6px rgba(102, 126, 234, 0.3);
    max-width: 100%;
}

.link-preview-card-display .link-preview-url:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    transform: scale(1.02);
    box-shadow: 0 3px 10px rgba(102, 126, 234, 0.5);
    text-decoration: none;
}

.link-preview-card-display .link-preview-url i {
    font-size: 8px;
    flex-shrink: 0;
    color: white;
}

/* "All my posts" Link Button */
h5 small a {
    display: inline-block;
    padding: 5px 15px;
    background: linear-gradient(135deg, #6cd4ff 0%, #667eea 100%);
    color: white !important;
    text-decoration: none;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(108, 212, 255, 0.3);
}

h5 small a:hover {
    background: linear-gradient(135deg, #667eea 0%, #6cd4ff 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(108, 212, 255, 0.5);
    color: white !important;
    text-decoration: none;
}

/* Badge Links (Hashtags) */
.badge.bg-secondary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
    padding: 5px 10px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 600;
    margin-right: 5px;
    margin-bottom: 5px;
    display: inline-block;
    transition: all 0.3s ease;
    box-shadow: 0 2px 6px rgba(102, 126, 234, 0.2);
    cursor: pointer;
}

.badge.bg-secondary:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%) !important;
    transform: translateY(-1px);
    box-shadow: 0 3px 10px rgba(102, 126, 234, 0.4);
}

/* Alert Link (Complete Promoted Post) */
.alert.alert-danger a {
    display: inline-block;
    padding: 8px 20px;
    background: linear-gradient(135deg, #f44336 0%, #e91e63 100%);
    color: white !important;
    text-decoration: none;
    border-radius: 25px;
    font-size: 13px;
    font-weight: 700;
    transition: all 0.3s ease;
    box-shadow: 0 3px 10px rgba(244, 67, 54, 0.3);
    text-align: center;
    width: 100%;
    margin-top: 5px;
}

.alert.alert-danger a:hover {
    background: linear-gradient(135deg, #e91e63 0%, #f44336 100%);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(244, 67, 54, 0.5);
    color: white !important;
    text-decoration: none;
}

/* Optional: Pulse animation for important links */
@keyframes gentle-pulse {
    0%, 100% {
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }
    50% {
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.6);
    }
}

.read-more-link,
.link-preview-url {
    animation: gentle-pulse 2s infinite;
}

/* Smooth transitions for all buttons */
.read-more-link,
.read-less-link,
.link-preview-url,
h5 small a,
.badge.bg-secondary,
.alert.alert-danger a {
    cursor: pointer;
    user-select: none;
}

.read-more-link:active,
.read-less-link:active,
.link-preview-url:active,
h5 small a:active,
.badge.bg-secondary:active,
.alert.alert-danger a:active {
    transform: scale(0.95);
}

/* ✅ SIMPLE FILE COUNT BADGE - Remove fancy design */
.file-count-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.6) !important; /* Simple dark overlay */
    color: white !important;
    font-size: 12px !important;
    font-weight: 600;
    cursor: pointer;
    border-radius: 10px;
    z-index: 10;
    transition: background 0.2s ease;
}

.file-count-overlay:hover {
    background: rgba(0, 0, 0, 0.75) !important; /* Slightly darker on hover */
}

/* ========== Suggestion Cards (People You May Follow) ========== */
.suggestions-section {
    margin: 10px 0;
}

.suggestions-section-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 14px;
    padding: 0 2px;
}

.suggestions-section-header i {
    font-size: 18px;
    color: #1877f2;
}

.suggestions-section-header h5 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: #333;
}

.suggestions-section-header .suggestions-count {
    font-size: 12px;
    color: #65676b;
    margin-left: auto;
}

.sg-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    padding: 14px 16px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: box-shadow 0.2s ease;
    position: relative;
}

.sg-card:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
}

.sg-avatar {
    position: relative;
    flex-shrink: 0;
}

.sg-avatar img.sg-avatar-img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e4e6eb;
}

.sg-avatar .sg-placeholder {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #e4e6eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #65676b;
}

.sg-avatar .sg-badge-icon {
    position: absolute;
    bottom: 0;
    right: -2px;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    border: 2px solid #fff;
}

.sg-avatar .sg-online-dot {
    position: absolute;
    top: 0;
    right: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    background: #31a24c;
}

.sg-info {
    flex: 1;
    min-width: 0;
}

.sg-name {
    font-size: 14px;
    font-weight: 600;
    color: #050505;
    margin: 0 0 2px;
}

.sg-name a {
    color: inherit;
    text-decoration: none;
}

.sg-name a:hover {
    text-decoration: underline;
}

.sg-meta {
    font-size: 12px;
    color: #65676b;
    margin: 0;
    line-height: 1.3;
}

.sg-meta .sg-follower-count {
    color: #1877f2;
    font-weight: 600;
}

.sg-meta .sg-last-seen {
    display: block;
    font-size: 11px;
    color: #90949c;
    margin-top: 1px;
}

.sg-follow-btn {
    flex-shrink: 0;
    padding: 7px 20px;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    background: #1877f2;
    color: #fff;
}

.sg-follow-btn:hover {
    background: #1565c0;
}

.sg-follow-btn:active {
    transform: scale(0.96);
}

.sg-follow-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.sg-follow-btn.sg-following {
    background: #e4e6eb;
    color: #050505;
}

.sg-follow-btn.sg-following:hover {
    background: #d8dadf;
}

.sg-view-all {
    text-align: center;
    margin: 12px 0 5px;
}

.sg-view-all a {
    display: inline-block;
    padding: 8px 28px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    background: #e4e6eb;
    color: #050505;
    text-decoration: none;
    transition: background 0.2s;
}

.sg-view-all a:hover {
    background: #d8dadf;
    text-decoration: none;
    color: #050505;
}

.sg-empty {
    text-align: center;
    padding: 30px 15px;
    color: #65676b;
}

.sg-empty i {
    font-size: 36px;
    color: #bcc0c4;
    margin-bottom: 10px;
    display: block;
}

/* Suggestion toast notification */
.sg-toast {
    position: fixed;
    bottom: 80px;
    left: 50%;
    transform: translateX(-50%) translateY(20px);
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    z-index: 9999;
    opacity: 0;
    transition: all 0.3s ease;
    pointer-events: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    max-width: 90vw;
}

.sg-toast.show {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
}

.sg-toast.success {
    background: #00a400;
    color: #fff;
}

.sg-toast.error {
    background: #e41e3f;
    color: #fff;
}

/* Dark mode overrides for suggestion cards */
body.dark-mode .suggestions-section-header h5 {
    color: #E4E6EB;
}

body.dark-mode .suggestions-section-header .suggestions-count {
    color: #B0B3B8;
}

body.dark-mode .sg-card {
    background: #242526;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
}

body.dark-mode .sg-card:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

body.dark-mode .sg-avatar img.sg-avatar-img {
    border-color: #3A3B3C;
}

body.dark-mode .sg-avatar .sg-placeholder {
    background: #3A3B3C;
    color: #B0B3B8;
}

body.dark-mode .sg-avatar .sg-badge-icon {
    border-color: #242526;
}

body.dark-mode .sg-avatar .sg-online-dot {
    border-color: #242526;
}

body.dark-mode .sg-name {
    color: #E4E6EB;
}

body.dark-mode .sg-name a {
    color: #E4E6EB;
}

body.dark-mode .sg-meta {
    color: #B0B3B8;
}

body.dark-mode .sg-meta .sg-follower-count {
    color: #2D88FF;
}

body.dark-mode .sg-meta .sg-last-seen {
    color: #6d7074;
}

body.dark-mode .sg-follow-btn.sg-following {
    background: #3A3B3C;
    color: #E4E6EB;
}

body.dark-mode .sg-follow-btn.sg-following:hover {
    background: #4E4F50;
}

body.dark-mode .sg-view-all a {
    background: #3A3B3C;
    color: #E4E6EB;
}

body.dark-mode .sg-view-all a:hover {
    background: #4E4F50;
}

body.dark-mode .sg-empty {
    color: #B0B3B8;
}

body.dark-mode .sg-empty i {
    color: #4E4F50;
}

@media (max-width: 600px) {
    .sg-card {
        padding: 12px;
        gap: 10px;
    }

    .sg-avatar img.sg-avatar-img,
    .sg-avatar .sg-placeholder {
        width: 44px;
        height: 44px;
    }

    .sg-name {
        font-size: 13px;
    }

    .sg-follow-btn {
        padding: 6px 14px;
        font-size: 12px;
    }
}

/* Dark mode for post modal */
body.dark-mode .modern-modal { background: #242526; }
body.dark-mode .modal-body-custom { background: #242526; }
body.dark-mode .post-textarea { background: #3A3B3C; color: #E4E6EB; border-color: #3E4042; }
body.dark-mode .post-textarea:focus { border-color: #2D88FF; box-shadow: 0 0 0 2px rgba(45,136,255,0.2); background: #242526; }
body.dark-mode .color-picker-item { background: #3A3B3C; }
body.dark-mode .color-picker-item label { color: #B0B3B8; }
body.dark-mode .color-picker-item input[type="color"] { border-color: #3E4042; }
body.dark-mode .preview-content { background: #3A3B3C; border-color: #3E4042; }
body.dark-mode .preview-label { color: #2D88FF; }
body.dark-mode .media-upload-btn { background: #3A3B3C; border-color: #3E4042; color: #2D88FF; }
body.dark-mode .media-upload-btn:hover { background: #263951; border-color: #2D88FF; }
body.dark-mode .media-thumb { border-color: #3E4042; background: #3A3B3C; }
body.dark-mode .media-thumb:hover { border-color: #2D88FF; }
body.dark-mode .media-count-label { color: #2D88FF; }
body.dark-mode .custom-select, body.dark-mode .custom-input { background: #3A3B3C; border-color: #3E4042; color: #E4E6EB; }
body.dark-mode .custom-select:focus, body.dark-mode .custom-input:focus { border-color: #2D88FF; background: #242526; }
body.dark-mode .option-item label { color: #B0B3B8; }
body.dark-mode .hashtag-badge { background: #263951; color: #2D88FF; }
body.dark-mode .modal-footer-custom { background: #242526; border-top-color: #3E4042; }
body.dark-mode .btn-close-modal { background: #3A3B3C; color: #E4E6EB; }
body.dark-mode .btn-close-modal:hover { background: #4E4F50; }
body.dark-mode .user-info-display { background: #3A3B3C; color: #B0B3B8; }
body.dark-mode .progress-container { background: #3A3B3C; }
body.dark-mode .link-preview-card { background: #242526; border-color: #3E4042; }
body.dark-mode .link-preview-card:hover { border-color: #2D88FF; }
body.dark-mode .link-preview-loading { background: #3A3B3C; }


</style>
    
</head>
<body>
    <!-- Your main navbar content  -->
 @include('layouts.navbar')
  @extends('layouts.app')
@section('seo_title', 'Feed - SupperAge | Posts, Updates & Trending')
@section('seo_description', 'Explore the latest posts, updates, and trending content on SupperAge. Share stories, discover what\'s happening, and earn while you engage.')
@section('content')


<!-- ------------------------------------container biguns --------------------------------------------------->
<div class="allrows">
<div class="row rows">
  <!-- --------------------------------------------first colum-------------------------------------------- -->
<div class="col-lg-3 stickyrows" id="left-column">

<!-- Full image modal -->
<div id="imageModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); justify-content:center; align-items:center; z-index:1000; overflow:hidden;">
  <span onclick="closeImage()" style="position:absolute; top:20px; right:30px; font-size:30px; color:white; cursor:pointer;">&times;</span>
  <img id="fullImage" src="" class="zoom-img">
</div>

<div class="sb-profile-card">
    <!-- Cover Photo -->
    <div class="sb-cover">
        @if($user->bgimg)
            <img src="{{ str_replace('/upload/', '/upload/w_600,h_170,c_fill,q_70/', $user->bgimg) }}" alt="Cover" onclick="showFullImage('{{ asset($user->bgimg) }}')">
        @else
            <div class="sb-cover-placeholder"><i class="fas fa-image"></i></div>
        @endif
    </div>

    <!-- Profile Info -->
    <div class="sb-profile-info">
        <a href="{{ route('profile.show', $user->id) }}" class="sb-avatar-link">
            @if($user->profileimg)
                <img src="{{ str_replace('/upload/', '/upload/w_60,h_60,c_fill,r_max,q_70/', $user->profileimg) }}" alt="Profile" class="sb-avatar">
            @else
                <div class="sb-avatar-placeholder"><i class="fa fa-user-circle"></i></div>
            @endif
        </a>
        <div class="sb-name-row">
            <h3 class="sb-name">{{ $user->name }}
                @if($user->badge_expired)
                    <i class="fas fa-clock" title="Badge Expired – Please Renew" style="color:#e74c3c;font-size:15px;"></i>
                @elseif($user->badge_status)
                    <img src="{{ asset($user->badge_status) }}" alt="Verified" title="Verified User" class="sb-badge">
                @endif
            </h3>
        </div>
        <span class="sb-username">{{ ucfirst(explode(' ', $user->username)[0]) }}</span>
        <p class="sb-bio">{{ \Illuminate\Support\Str::limit($user->bio, 50) }}</p>
        <a href="{{ route('profile.edit') }}" class="sb-edit-profile"><i class="fas fa-pen"></i> Edit Profile</a>
    </div>

    <!-- Stats Row -->
    <div class="sb-stats">
        <a href="{{ route('my.followers') }}" class="sb-stat">
            <span class="sb-stat-num">{{ $no_of_followers }}</span>
            <span class="sb-stat-label">Followers</span>
        </a>
        <a href="{{ route('mutual.followers', $user->id) }}" class="sb-stat">
            <span class="sb-stat-num"><i class="fas fa-user-friends"></i></span>
            <span class="sb-stat-label">Mutual</span>
        </a>
    </div>

    <!-- Quick Links -->
    <div class="sb-links">
        <a href="{{ route('tasks.index') }}" class="sb-link-item">
            <div class="sb-link-icon" style="background:#e8f5e9;color:#2e7d32;"><i class="fas fa-coins"></i></div>
            <span>Tasks & Earn</span>
        </a>
        <a href="{{ route('task.center') }}" class="sb-link-item">
            <div class="sb-link-icon" style="background:#e3f2fd;color:#1565c0;"><i class="fas fa-plus-circle"></i></div>
            <span>Add Task</span>
        </a>
        <a href="{{ route('tasks.leaderboard') }}" class="sb-link-item">
            <div class="sb-link-icon" style="background:#fff3e0;color:#e65100;"><i class="fas fa-trophy"></i></div>
            <span>Points</span>
        </a>
        <a href="{{ route('advertising.index') }}" class="sb-link-item">
            <div class="sb-link-icon" style="background:#fce4ec;color:#c62828;"><i class="fas fa-bullhorn"></i></div>
            <span>Ads Manager</span>
        </a>
    </div>

    <!-- Wallet Button -->
    <a href="{{ route('mywallet') }}" class="sb-wallet-btn">
        <i class="fas fa-wallet"></i> Wallet & Transfers
    </a>

    <!-- Inbox & Premium -->
    <div class="sb-bottom-links">
        <a href="{{ route('inbox.index') }}" class="sb-bottom-link">
            <i class="fas fa-inbox"></i> Inbox
            @php
                $unreadCount = DB::table('admin_messages')
                    ->where('user_id', Session::get('id'))
                    ->where('is_read', 0)
                    ->count();
            @endphp
            @if($unreadCount > 0)
                <span class="sb-inbox-badge">{{ $unreadCount }}</span>
            @endif
        </a>
        <a href="{{ url('/premium') }}" class="sb-premium-btn">
            <i class="fas fa-crown"></i> Try Premium
        </a>
    </div>
</div>

<!-- Discover More -->
<div class="sb-discover-card">
    <div class="sb-discover-title"><i class="fas fa-compass"></i> Discover More</div>
    <div class="sb-discover-links">
        <a href="{{ route('live.index') }}" class="sb-disc-link"><i class="fas fa-video"></i> Go Live</a>
        <a href="{{ route('groups.index') }}" class="sb-disc-link"><i class="fas fa-users"></i> Groups</a>
        <a href="{{ route('events.index') }}" class="sb-disc-link"><i class="fas fa-calendar-alt"></i> Events</a>
        <a href="{{ route('marketplace.index') }}" class="sb-disc-link"><i class="fas fa-store"></i> Stores</a>
        <a href="{{ route('posts.saved') }}" class="sb-disc-link"><i class="fas fa-bookmark"></i> Saved Posts</a>
    </div>
</div>

</div>
<!------------------------------ middle colum-------------------------------------------------- -->
<div class="col-lg-6 main-content">

<style>
  .refresh-btn {
    position: fixed;
    top: 70px;  /* ✅ CHANGED: moved to top */
    right: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);  /* ✅ CHANGED: gradient */
    color: white;
    border: none;
    border-radius: 50%;
    width: 45px;  /* ✅ CHANGED: smaller */
    height: 45px;  /* ✅ CHANGED: smaller */
    cursor: pointer;
    font-size: 18px;  /* ✅ CHANGED: smaller */
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;  /* ✅ CHANGED: lower z-index */
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);  /* ✅ CHANGED: better shadow */
    transition: all 0.3s ease;  /* ✅ ADDED: smooth animation */
  }
  .refresh-btn:hover {
    transform: rotate(180deg) scale(1.1);  /* ✅ ADDED: cool rotation effect */
    box-shadow: 0 6px 16px rgba(102, 126, 234, 0.6);
  }
  .refresh-btn:active {
    transform: rotate(180deg) scale(0.95);  /* ✅ ADDED: press effect */
  }

  /* Responsive positioning */
  @media (max-width: 768px) {
    .refresh-btn {
      top: 450px;
      right: 15px;
      width: 40px;
      height: 40px;
      font-size: 16px;
    }
  }
</style>

<button class="refresh-btn" onclick="window.location.reload();" title="Refresh Page">
  &#8635;
</button>


  <!-- top menu -->
  <div class="card" style="border: none; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); background: #f8f9fa;">
    <div class="card-body" style="display:flex; align-items: center; justify-content: space-around; padding: 15px;">
        
        <button type="button" class="text-white" data-bs-toggle="modal" data-bs-target="#myModal3" 
            style="font-weight: 600; border-radius: 30px; background: linear-gradient(135deg, skyblue, #00bfff); border: 2px solid white; padding: 8px 16px; box-shadow: 0 4px 12px rgba(135, 206, 235, 0.5); font-size: 13px; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-plus-circle"></i> Story
        </button>

        <button type="button" class="text-white showmemore" 
            style="
                width: 55px; 
                height: 55px; 
              
                align-items: center; 
                justify-content: center; 
                font-size: 22px; 
                border-radius: 50%; 
                background: linear-gradient(135deg, skyblue, #00bfff); 
                border: 4px solid white; 
                box-shadow: 0 8px 20px rgba(135, 206, 235, 0.8); 
                cursor: pointer; 
                transition: all 0.3s ease;
                outline: none;
                z-index: 10;
                margin-top: -5px; /* Slight lift */
            ">
            <i class="fas fa-user"></i>
        </button>

        <button type="button" class="text-white" data-bs-toggle="modal" data-bs-target="#myModalh"
            style="font-weight: 600; border-radius: 30px; background: linear-gradient(135deg, skyblue, #00bfff); border: 2px solid white; padding: 8px 16px; box-shadow: 0 4px 12px rgba(135, 206, 235, 0.5); font-size: 13px; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-edit"></i> Post
        </button>
        
    </div>
</div>


    <!-- model pop up  -->
     <!-- The Modal for making post -->
<!-- Update your post creation modal in the blade file -->
<div class="modal fade" id="myModalh">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content modern-modal">
            
            <!-- Modal Header -->
            <div class="modal-header-custom">
                <div class="header-content">
                    <div class="header-text">
                        <h5 class="modal-title-custom">
                            <i class="fas fa-pencil-alt"></i> Create Post
                        </h5>
                        <span class="location-badge">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $user->continent ?? 'N/A' }}, {{ $user->country ?? 'N/A' }}, {{ $user->state ?? 'N/A' }}, {{ $user->city ?? 'N/A' }}
                        </span>
                    </div>
                    
                    <div class="user-avatar">
                        @php
                            $defaultImg = '';
                            $urimg = $user->profileimg;
                        @endphp

                        @if($user->profileimg !== $defaultImg)
                            <img src="{{ asset($urimg) }}" alt="Profile">
                        @else
                            <i class="fa fa-user-circle"></i>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="modal-body-custom">
                <form method="POST" id="post_form" enctype="multipart/form-data">
                    @csrf

                    <!-- Media Preview -->
                    <div class="media-preview-container" id="previewmedia"></div>

                    <!-- Post Content Textarea -->
                    <div class="post-input-wrapper">
                        <div id="styledText" class="styled-text-overlay"></div>
                        <textarea id="post_contents" 
                                  name="post_content" 
                                  placeholder="What's on your mind, {{ $user->name }}? (Paste a link to see preview)" 
                                  maxlength="5000"
                                  class="post-textarea"></textarea>
                    </div>

                    <!-- ✅ NEW: Link Preview Container -->
                    <div id="linkPreviewContainer" style="display: none; margin-bottom: 15px;"></div>


                     <!-- Color Pickers Section -->
                    <div class="color-picker-section">
                        <div class="color-picker-item">
                            <label for="colorpickers">
                                <i class="fas fa-font"></i> Text Color
                            </label>
                            <input type="color" id="colorpickers" name="colorpickers" value="#000000">
                        </div>

                        <div class="color-picker-item">
                            <label for="bgColorPickers">
                                <i class="fas fa-fill-drip"></i> Background
                            </label>
                            <input type="color" id="bgColorPickers" name="bgColorPickers" value="#ffffff">
                        </div>
                    </div>


                    <!-- Live Preview -->
                    <div class="preview-box">
                        <div class="preview-label">
                            <i class="fas fa-eye"></i> Live Preview
                        </div>
                        <div id="colorPreview" class="preview-content" style="background-color: #ffffff; color: #000000;">
                            <p id="previewText" style="color: #000000;">Your post will appear like this...</p>
                        </div>
                    </div>

                    <!-- Hashtags Display -->
                    @if(isset($hashtags) && $hashtags->isNotEmpty())
                        <div class="hashtags-display">
                            @foreach ($hashtags as $tag)
                                <span class="hashtag-badge">#{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif

                    <!-- Post Options -->
                    <div class="post-options-grid">
                        <!-- Status -->
                        <div class="option-item">
                            <label for="dff">
                                <i class="fas fa-toggle-on"></i> Status
                            </label>
                            <select name="status" id="dff" class="custom-select">
                                <option value="published">📤 Publish Now</option>
                                <option value="draft">📝 Save as Draft</option>
                            </select>
                        </div>

                        <!-- Schedule -->
                        <div class="option-item">
                            <label for="vbv">
                                <i class="far fa-clock"></i> Schedule (Optional)
                            </label>
                            <input type="datetime-local" id="vbv" name="scheduled_at" class="custom-input">
                        </div>
                    </div>

                    <!-- Media Upload & Post Button -->
                    <div class="action-row">
                        <label for="media" class="media-upload-btn" id="triggerFileSelect">
                            <i class="fas fa-image"></i> Add Media
                        </label>
                        <input type="file" id="media" name="media[]" accept=".jpg,.png,.mp4" multiple style="display:none;">
                        
                        <button type="submit" name="share_post" id="share_post" class="post-submit-btn">
                            <i class="fas fa-paper-plane"></i> Post Now
                        </button>
                    </div>

                    <!-- Progress Bar -->
                    <div id="progressContainer" class="progress-container">
                        <div id="progressBar" class="progress-bar-fill"></div>
                        <span id="progressText" class="progress-text"></span>
                    </div>

                    <!-- Hidden Fields -->
                    <input type="hidden" name="action" value="insert">
                    <input type="hidden" name="post_type" id="post_type" value="text">
                    <input type="hidden" name="link_preview" id="postLinkPreviewData" value="">
                    
                    <!-- User Name Display -->
                    <div class="user-info-display">
                        <i class="fas fa-user"></i> Posting as: <strong>{{ $user->name }}</strong>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer-custom">
                <span id="postmsg" class="status-message"></span>
                <i class="fa fa-spinner fa-spin loading-spinner" id="loading"></i>
                <button type="button" class="btn-close-modal" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal ends -->

<!-- ✅ Display Link Preview in Posts -->
   <!-- Rest of post content (media, hashtags, etc.)... -->
       



<!-- Modal for uploading short stories that last for 24hrs -->
<div class="modal fade" id="myModal3">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content tales-modal">
            
            <!-- Modal Header -->
            <div class="tales-modal-header">
                <div class="header-layout">
                    <div class="header-info">
                        <h5 class="tales-title">
                            <i class="fas fa-book-open"></i> Share Your Tales
                        </h5>
                        <div class="tales-description">
                            <div class="description-badge">
                                <i class="fas fa-info-circle"></i> What are Tales?
                            </div>
                            <p class="description-text">
                                Exciting stories to listen, watch, or share with loved ones for entertainment, 
                                education, and guidance. <strong>Expires in 24 hours!</strong>
                            </p>
                        </div>
                    </div>
                    
                    <div class="tales-user-avatar">
                        @php
                            $defaultImg = '';
                            $urimg = $user->profileimg;
                        @endphp

                        @if($user->profileimg !== $defaultImg)
                            <img src="{{ asset($urimg) }}" alt="Profile">
                        @else
                            <i class="fa fa-user-circle"></i>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="tales-modal-body">
                <form id="mytales-frm" method="post" enctype="multipart/form-data">
                    @csrf

                    <!-- Category Selection -->
                    <div class="form-section">
                        <label for="tales_cat" class="section-label">
                            <i class="fas fa-list"></i> Story Category
                        </label>
                        <select class="tales-select" name="tales_cat" id="tales_cat">
                            <option value="tales of adventure">🗺️ Tales of Adventure</option>
                            <option value="Folk tales">🏛️ Folk Tales</option>
                            <option value="Fairy tales">✨ Fairy Tales</option>
                            <option value="tales of woe">😢 Tales of Woe</option>
                            <option value="Traditional Wear">👘 Traditional Wear</option>
                            <option value="travel stories">✈️ Travel Stories</option>
                            <option value="fashion">👗 Fashion</option>
                            <option value="food">🍲 Food & Cuisine</option>
                            <option value="lifestyle">🌟 Lifestyle</option>
                            <option value="personal milestones">🎉 Personal Milestones</option>
                            <option value="culinary adventures">🍳 Culinary Adventures</option>
                            <option value="Best African street foods">🌮 Best African Street Foods</option>
                        </select>
                    </div>

                    <!-- Progress Bar -->
                    <div class="progress-section" style="display: none;" id="progressSection">
                        <div class="upload-progress">
                            <div id="uploadProgressBar" class="upload-progress-fill" style="width: 0%;">
                                <span class="progress-percentage">0%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Story Content -->
                    <div class="form-section">
                        <label for="tales_msg" class="section-label">
                            <i class="fas fa-pen-fancy"></i> Your Story
                        </label>
                        <textarea class="tales-textarea" 
                                  id="tales_msg" 
                                  name="tales_msg" 
                                  placeholder="Write your captivating tale here... ✨ (Paste a link to see preview)" 
                                  rows="5"></textarea>
                    </div>

                    <!-- ✅ NEW: Link Preview Container for Tales -->
                    <div id="talesLinkPreviewContainer" style="display: none; margin-bottom: 15px;"></div>

                    <!-- Color Customization -->
                    <div class="color-customization">
                        <div class="color-preview-box">
                            <label class="preview-label">
                                <i class="fas fa-palette"></i> Text Preview
                            </label>
                            <div id="previewArea" class="preview-display">
                                Your text will appear here
                            </div>
                        </div>

                        <div class="color-controls">
                            <div class="color-control-item">
                                <label for="colorpicker" class="color-label">
                                    <i class="fas fa-font"></i> Text
                                </label>
                                <input type="color"
                                       id="colorpicker"
                                       name="colorpicker"
                                       value="#000000"
                                       class="color-input">
                            </div>

                            <div class="color-control-item">
                                <label for="bgColorPicker" class="color-label">
                                    <i class="fas fa-fill-drip"></i> Background
                                </label>
                                <input type="color"
                                       id="bgColorPicker"
                                       name="bgColorPicker"
                                       value="#ffffff"
                                       class="color-input">
                            </div>
                        </div>
                    </div>

                    <!-- Media Upload & Submit -->
                    <div class="action-section">
                        <label for="tales_files" class="tales-upload-btn">
                            <i class="fas fa-cloud-upload-alt"></i> Add Media
                            <span class="upload-hint">Photo, Video or Audio</span>
                        </label>
                        <input type="file" 
                               id="tales_files" 
                               name="tales_files" 
                               accept="audio/*,video/*,image/*" 
                               style="display:none;">

                        <button type="submit" class="tales-submit-btn" id="sharetalesbtn">
                            <i class="fas fa-share-alt"></i> Share Story
                        </button>
                    </div>

                    <input type="hidden" name="action" value="talestype">

                    <!-- Info Banner -->
                    <div class="expiry-banner">
                        <i class="far fa-clock"></i> 
                        <span>This story will automatically expire after 24 hours</span>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="tales-modal-footer">
                <span id="talesmsg" class="tales-status-msg"></span>
                <i class="fa fa-spinner fa-spin tales-loader" id="loader4"></i>
                <button type="button" class="tales-close-btn" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<!--display short stories here that will last for 24hrs then it will expire -->
<h4 class="mb-3 d-flex align-items-center">
    <i class="fas fa-book-open text-primary mr-2"></i> 
    Tales & Stories
    <small class="text-muted ml-auto" style="font-size: 12px;">
        <i class="fas fa-history"></i> Lasts 24h
    </small>
</h4>

<div class="scroll-wrapper">
    <button class="scroll-btn left">&#8592;</button>
    <div class="scroll-container">
    </div>
    <button class="scroll-btn right">&#8594;</button>
</div>
 




  <!-- ✅ Success Popup -->
<div id="successPopup" style="
  display: none;
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: #28a745;
  color: white;
  padding: 20px 30px;
  border-radius: 10px;
  font-size: 18px;
  z-index: 9999;
  box-shadow: 0 0 15px rgba(0,0,0,0.3);
">
  ✅ Shared successfully!
</div>

<!-- ✅ Success Sound -->
<audio id="successSound" src="{{ asset('/sounds/mixkit-fantasy-game-success-notification-270.wav') }}" preload="auto"></audio>

<!-- ---------------photo and video and event gallary--------- -->
<div class="create-post-gallary mt-1">
    <div class="create-post-gall-links mb-2">
        <ul>
            <li><a href="{{ route('live.index') }}">GoLive</a></li>
            <li><a href="{{ route('groups.index') }}">Groups</a></li>
            <li><a href="{{ route('events.index') }}">Events</a></li>
            <li><a href="{{ route('marketplace.index') }}">Stores</a></li>
            <li><a href="#">All post</a></li>
        </ul>
    </div>
</div>



                        
 <!-- ------------------post list section------------------- -->

 {{-- Single Post View (from notification click) --}}
{{-- Single Post View (from notification click) --}}
@if(isset($post) && request()->routeIs('posts.show'))
  <div class="container mb-4">
    <div class="suggestions-section-header">
        <i class="fas fa-bell" style="color: #27ae60;"></i>
        <h5 style="color: #27ae60;">Notification Post</h5>
    </div>

<div class="card shadow-sm">
  @php
    $author = $post->user ?? DB::table('users_record')->where('id', $post->user_id)->first();
    
    // Attachment logic for download button
    $files = json_decode($post->file_path, true);
    $firstFile = (is_array($files) && count($files) > 0) ? $files[0] : null;
  @endphp

  <div class="card-header d-flex align-items-center justify-content-between">
    {{-- Left Side: User Info --}}
    <div class="d-flex align-items-center">
      <a href="{{ route('profile.show', $author->id ?? $post->user_id) }}" style="text-decoration: none;">
        <img src="{{ $author->profileimg ?? asset('images/best3.png') }}" class="rounded-circle me-2" style="width:40px;height:40px; object-fit: cover;">
      </a>
      <div>
        <strong>{{ $author->name ?? 'Unknown' }}</strong>
        @if(isset($author) && $author->badge_expired)
            <i class="fas fa-clock" title="Badge Expired – Please Renew" style="color:#e74c3c;font-size:15px;margin-left:5px;"></i>
        @elseif(isset($author->badge_status) && $author->badge_status)
            <img src="{{ asset($author->badge_status) }}" style="width:18px;height:18px;margin-left:5px;">
        @endif
        <br>
        <span class="text-muted">{{ '@' . ($author->username ?? 'user') }}</span><br>
        <small class="text-muted">
          {{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }}
        </small>
      </div>
    </div>

    {{-- Right Side: Pure CSS Dropdown (No Library) --}}
    <div class="post-options-dropdown">
      <button class="options-btn" type="button" aria-label="Post Options">
        <i class="fa fa-ellipsis-v"></i>
      </button>

      <div class="options-menu">
        @if($firstFile)
        <a class="options-item" href="javascript:void(0)" onclick="downloadFile('{{ $firstFile }}')">
          <i class="fa fa-download"></i> Download file
        </a>
        @endif

        <a class="options-item" href="javascript:void(0)" onclick="copyPostLink('{{ route('posts.show', $post->id) }}')">
          <i class="fa fa-link"></i> Copy post link
        </a>

        <a class="options-item" href="javascript:void(0)" onclick="shareAsTale({{ $post->id }})">
          <i class="fa fa-share"></i> Share as Tale
        </a>

        <div class="options-divider"></div>

        <a class="options-item save-post-btn" href="javascript:void(0)" onclick="toggleSavePost({{ $post->id }}, this)" style="color: {{ in_array($post->id, $savedPostIds ?? []) ? '#dc3545' : '#28a745' }};">
          <i class="fa fa-bookmark" style="color: {{ in_array($post->id, $savedPostIds ?? []) ? '#dc3545' : '#28a745' }};"></i> {{ in_array($post->id, $savedPostIds ?? []) ? 'Unsave post' : 'Save post' }}
        </a>
      </div>
    </div>
  </div>
  
  {{-- Card body and other content follows... --}}


      <div class="card-body" @if($post->bgnd_color && $post->bgnd_color !== '#ffffff') style="color: {{ $post->text_color }}; background-color: {{ $post->bgnd_color }};" @endif>
        @php
          $content = strip_tags($post->post_content);
          $isLong = mb_strlen($content) > 8;
          $shortContent = $isLong ? mb_substr($content, 0, 8) . '...' : $content;
        @endphp

        @if(trim($content) !== '[media-only post]')
        <p class="single-post-content-{{ $post->id }}">
          {!! nl2br(e($shortContent)) !!}
          @if($isLong)
            <a href="#" class="read-more-link text-primary" onclick="toggleSingleContent(event, {{ $post->id }})">Read more</a>
          @endif
        </p>

        <p class="single-post-content-full-{{ $post->id }}" style="display: none;">
          {!! nl2br(e($post->post_content)) !!}
          <a href="#" class="read-less-link text-primary" onclick="toggleSingleContent(event, {{ $post->id }})">Read less</a>
        </p>
        @endif

        {{-- Media --}}
        @php
          $media = json_decode($post->file_path, true);
          $hashtags = DB::table('hashtags')->where('post_id', $post->id)->pluck('tag');

          // Build video gallery for this post
          $videoFilesForGallery = [];
          if (!empty($media)) {
            foreach($media as $mediaFile) {
              $mediaExt = strtolower(pathinfo($mediaFile, PATHINFO_EXTENSION));
              if (in_array($mediaExt, ['mp4', 'webm', 'ogg'])) {
                $videoFilesForGallery[] = [
                  'url' => $mediaFile,
                  'caption' => strip_tags($post->post_content),
                  'username' => $post->username
                ];
              }
            }
          }
          $videoGalleryJsonForPost = json_encode($videoFilesForGallery);
          $videoIndexCounter = 0;
        @endphp

        @if(!empty($media))
          @php
            $mediaCount = count($media);
            $displayCount = min($mediaCount, 4);
            $gridClass = $mediaCount >= 5 ? 'grid-5plus' : 'grid-' . $mediaCount;
            $fancyboxGroupSingle = 'single-gallery-' . $post->id;
          @endphp
          <div class="fb-media-grid {{ $gridClass }} mb-2">
            @for($i = 0; $i < $displayCount; $i++)
              @php
                $file = $media[$i];
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                $isVideo = in_array($ext, ['mp4','webm','ogg']);
              @endphp
              <div class="fb-media-item">
                @if($isImage)
                  <a data-fancybox="{{ $fancyboxGroupSingle }}" href="{{ $file }}" data-caption="{{ $post->post_content }},Posted by -{{ $post->username }}">
                    <img src="{{ $file }}" loading="lazy" alt="Post image" />
                  </a>
                @elseif($isVideo)
                  @php
                    $isCloudinary = Str::contains($file, 'res.cloudinary.com');
                    $cloudinaryThumb = $isCloudinary
                      ? preg_replace('/\.mp4$/', '', str_replace('/upload/', '/upload/so_1/', $file)) . '.jpg'
                      : asset('images/video-placeholder.jpg');
                    $currentVideoIndex = $videoIndexCounter;
                    $videoIndexCounter++;
                  @endphp
                  <div class="inline-video-wrapper" data-post-id="{{ $post->id }}" data-video-gallery='{{ $videoGalleryJsonForPost }}' data-video-index="{{ $currentVideoIndex }}">
                    <video
                      class="inline-feed-video"
                      src="{{ $file }}"
                      poster="{{ $cloudinaryThumb }}"
                      loop
                      muted
                      playsinline
                      preload="metadata"
                      onclick="expandInlineVideo(this.closest('.inline-video-wrapper').querySelector('.inline-video-expand-btn'))"
                    ></video>
                    <div class="inline-video-mute-btn" onclick="toggleInlineMute(event, this)">
                      <i class="fas fa-volume-mute"></i>
                    </div>
                    <div class="inline-video-expand-btn" onclick="expandInlineVideo(this)">
                      <i class="fas fa-expand"></i>
                    </div>
                  </div>
                @endif
                @if($i === $displayCount - 1 && $mediaCount > 4)
                  <div class="fb-media-overlay" onclick="document.querySelector('[data-fancybox=&quot;{{ $fancyboxGroupSingle }}&quot;]').click();">
                    +{{ $mediaCount - 3 }}
                  </div>
                @endif
              </div>
            @endfor
          </div>
          {{-- Hidden fancybox links for remaining images beyond 4 --}}
          @if($mediaCount > 4)
            @foreach(array_slice($media, 4) as $remainingFile)
              @php
                $rExt = strtolower(pathinfo($remainingFile, PATHINFO_EXTENSION));
                $isRemainingImage = in_array($rExt, ['jpg','jpeg','png','gif','webp']);
              @endphp
              @if($isRemainingImage)
                <a data-fancybox="{{ $fancyboxGroupSingle }}" href="{{ $remainingFile }}" data-caption="{{ $post->post_content }},Posted by -{{ $post->username }}" class="d-none"></a>
              @endif
            @endforeach
          @endif
        @endif

        {{-- Hashtags --}}
        @if($hashtags->isNotEmpty())
          <div class="mt-2">
            @foreach($hashtags as $tag)
              <span class="badge bg-secondary">#{{ $tag }}</span>
            @endforeach
          </div>
        @endif
      </div>

      {{-- Action buttons --}}
      <div class="card-footer">
  <div class="d-flex justify-content-between align-items-center">
    {{-- Like --}}
    <button class="btn btn-sm postlike-btn" style="font-weight: bold; color: {{ in_array($post->id, $likedPostIds ?? []) ? '#00bfff' : '#888' }};" data-post-id="{{ $post->id }}">
      <i class="fa-solid fa-thumbs-up"></i> Like <span class="like-count">{{ $post->likes_relation_count ?? 0 }}</span>
    </button>

    {{-- Comments --}}
    <button class="btn btn-sm" style="font-weight: bold;color: #888;" type="button" data-bs-toggle="collapse" data-bs-target="#comments-{{ $post->id }}">
      <i class="fa-solid fa-comment"></i> Comments {{ $post->comments->count() ?? 0 }}
    </button>

    {{-- Repost --}}
    <form method="POST" action="{{ route('posts.repost', $post->id) }}">
      @csrf
      <button type="submit" class="btn btn-sm" style="font-weight: bold;color: #888;">
        <i class="fa-solid fa-retweet"></i> {{ $post->reposts_relation_count ?? 0 }}
      </button>
    </form>

    {{-- Share --}}
    <form method="POST" action="{{ route('posts.share', $post->id) }}">
      @csrf
      <input type="hidden" name="platform" value="direct">
      <button type="submit" class="btn btn-sm" style="font-weight: bold; color: #888;">
        <i class="fa-solid fa-paper-plane"></i> {{ $post->shares_relation_count ?? 0 }}
      </button>
    </form>
  </div>
</div>
    </div>
  </div>

  <script>
  function toggleSingleContent(event, postId) {
    event.preventDefault();
    const shortContent = document.querySelector(`.single-post-content-${postId}`);
    const fullContent = document.querySelector(`.single-post-content-full-${postId}`);
    
    if (shortContent.style.display === 'none') {
      shortContent.style.display = 'block';
      fullContent.style.display = 'none';
    } else {
      shortContent.style.display = 'none';
      fullContent.style.display = 'block';
    }
  }
  </script>
@endif

<hr />


{{-- Then your existing promoted posts section --}}
@php
  if (!isset($posts) && isset($post)) {
    $posts = collect([$post]);
  }
  $limitedPosts = $posts->take(5);
@endphp

<div class="">
  <h5 class="text-primary d-flex align-items-center justify-content-between" style="font-weight: 700;">
  <span class="d-flex align-items-center">
    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2" style="color: #0d6efd;">
      <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"></path>
      <path d="M18 14h-8"></path>
      <path d="M15 18h-5"></path>
      <path d="M10 6h8v4h-8V6Z"></path>
    </svg>
    My Current Posts
  </span>
  
  <small>
    <a href="{{ route('posts.all') }}" class="" style="font-size: 0.8rem; transition: 0.3s;">
      Edit posts &rarr;
    </a>
  </small>
</h5>

  @if($limitedPosts->isNotEmpty())
    <div class="d-flex flex-row flex-nowrap overflow-auto gap-3">
      @foreach($limitedPosts as $post)
        <div style="width: 200px; min-width: 200px; height: 220px; overflow: hidden; border: 1px solid #e4e6eb; border-radius: 10px; padding: 8px; flex-shrink: 0;">
          <div style="height: 100%; overflow: hidden; {{ ($post->bgnd_color && $post->bgnd_color !== '#ffffff') ? 'color: '.$post->text_color.'; background-color: '.$post->bgnd_color.';' : '' }}">
            @php
              $content = strip_tags($post->post_content);
              $isLong = mb_strlen($content) > 10;
              $shortContent = $isLong ? mb_substr($content, 0, 10) . '...' : $content;
            @endphp

            {{-- Post Content --}}
@if(trim($content) !== '[media-only post]')
<p class="post-content-{{ $post->id }}" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 4px; font-size: 13px;">
  {{ $shortContent }}
  @if($isLong)
    <a href="#" class="read-more-link" style="font-size: 12px;" onclick="toggleContent(event, {{ $post->id }})">Read more</a>
  @endif
</p>

<p class="post-content-full-{{ $post->id }}" style="display: none; font-size: 13px; margin-bottom: 4px;">
  {!! nl2br(e($post->post_content)) !!}
  <a href="#" class="read-less-link" style="font-size: 12px;" onclick="toggleContent(event, {{ $post->id }})">Read less</a>
</p>
@endif

{{-- ✅ ADD THIS: Link Preview for Promoted Posts --}}
@if($post->link_preview)
  @php
    $linkData = json_decode($post->link_preview, true);
  @endphp
  <div class="link-preview-card-display">
    @if(!empty($linkData['image']))
      <div class="link-preview-image">
        <img src="{{ $linkData['image'] }}" alt="{{ $linkData['title'] }}">
      </div>
    @endif
    <div class="link-preview-content">
      <div class="link-preview-site">
        @if(!empty($linkData['favicon']))<img src="{{ $linkData['favicon'] }}" class="link-preview-favicon">@endif
        <span>{{ $linkData['site_name'] }}</span>
      </div>
      <h4 class="link-preview-title">{{ $linkData['title'] }}</h4>
      @if(!empty($linkData['description']))
        <p class="link-preview-description">{{ $linkData['description'] }}</p>
      @endif
      <a href="{{ $linkData['url'] }}" class="link-preview-url" target="_blank" rel="noopener">
        <i class="fas fa-external-link-alt"></i> {{ Str::limit($linkData['url'], 50) }}
      </a>
    </div>
  </div>
@endif
{{-- ✅ END Link Preview --}}


            @php
              // --- Attachment Logic Setup ---
              $files = json_decode($post->file_path, true);
              $files = is_array($files) ? $files : [];
              $fileCount = count($files);

              $hiddenFileCount = $fileCount > 0 ? $fileCount - 1 : 0;
              $firstFile = $fileCount > 0 ? $files[0] : null;

              $hashtags = DB::table('hashtags')->where('post_id', $post->id)->pluck('tag');
              // --- End Attachment Logic Setup ---
            @endphp

@if($fileCount > 0)
  @php
    $videoFiles = [];
    $videoIdxSmall = 0;
    foreach($files as $file) {
      $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
      if(in_array($ext, ['mp4', 'webm', 'ogg'])) {
        $videoFiles[] = [
          'url' => $file,
          'caption' => strip_tags($post->post_content),
          'username' => $post->username
        ];
      }
    }
    $videoGalleryJson = json_encode($videoFiles);
    $displayCountSm = min($fileCount, 4);
    $gridClassSm = $fileCount >= 5 ? 'grid-5plus' : 'grid-' . $fileCount;
    $fancyboxGroup = 'gallery-' . $post->id;
  @endphp
  <div class="fb-media-grid fb-grid-sm {{ $gridClassSm }} mt-2">
    @for($k = 0; $k < $displayCountSm; $k++)
      @php
        $file = $files[$k];
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);
        $isVideo = in_array($ext, ['mp4','webm','ogg']);
      @endphp
      <div class="fb-media-item">
        @if($isImage)
          <a data-fancybox="{{ $fancyboxGroup }}" href="{{ $file }}" data-post-id="{{ $post->id }}" data-caption="{{ $post->post_content }},Posted by -{{ $post->username }}">
            <img src="{{ $file }}" loading="lazy" alt="Post image" />
          </a>
        @elseif($isVideo)
          @php
            $isCloudinary = Str::contains($file, 'res.cloudinary.com');
            $cloudinaryThumb = $isCloudinary
              ? preg_replace('/\.mp4$/', '', str_replace('/upload/', '/upload/so_1/', $file)) . '.jpg'
              : asset('images/video-placeholder.jpg');
            $currentVidIdx = $videoIdxSmall;
            $videoIdxSmall++;
          @endphp
          <div class="inline-video-wrapper" data-post-id="{{ $post->id }}" data-video-gallery='{{ $videoGalleryJson }}' data-video-index="{{ $currentVidIdx }}">
            <video
              class="inline-feed-video"
              src="{{ $file }}"
              poster="{{ $cloudinaryThumb }}"
              loop
              muted
              playsinline
              preload="metadata"
              onclick="expandInlineVideo(this.closest('.inline-video-wrapper').querySelector('.inline-video-expand-btn'))"
            ></video>
            <div class="inline-video-mute-btn" onclick="toggleInlineMute(event, this)">
              <i class="fas fa-volume-mute"></i>
            </div>
            <div class="inline-video-expand-btn" onclick="expandInlineVideo(this)">
              <i class="fas fa-expand"></i>
            </div>
          </div>
        @endif
        @if($k === $displayCountSm - 1 && $fileCount > 4)
          <div class="fb-media-overlay" onclick="document.querySelector('[data-fancybox=&quot;{{ $fancyboxGroup }}&quot;]').click();">
            +{{ $fileCount - 3 }}
          </div>
        @endif
      </div>
    @endfor
  </div>
  @if($fileCount > 4)
    @foreach(array_slice($files, 4) as $remainingFile)
      @php
        $rExt = strtolower(pathinfo($remainingFile, PATHINFO_EXTENSION));
        $isRemainingImage = in_array($rExt, ['jpg','jpeg','png','gif','webp']);
      @endphp
      @if($isRemainingImage)
        <a data-fancybox="{{ $fancyboxGroup }}" href="{{ $remainingFile }}" data-post-id="{{ $post->id }}" data-caption="{{ $post->post_content }},Posted by -{{ $post->username }}" class="d-none"></a>
      @endif
    @endforeach
  @endif
@endif

            @if($hashtags->isNotEmpty())
              <div class="mt-2">
                <strong>Tags:</strong>
                @foreach($hashtags as $tag)
                  <span class="badge bg-secondary">#{{ $tag }}</span>
                @endforeach
              </div>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  @else
    <div class="alert alert-danger">
      <a href="{{ route('tasks.index') }}" style="text-decoration: none;">Complete a Promoted Post and Task to earn up to 10k NGN</a>
    </div>
  @endif
 </div>

  <script>
  function toggleContent(event, postId) {
  event.preventDefault();
  const shortContent = document.querySelector(`.post-content-${postId}`);
  const fullContent = document.querySelector(`.post-content-full-${postId}`);
  
  if (shortContent.style.display === 'none') {
    shortContent.style.display = 'block';
    fullContent.style.display = 'none';
  } else {
    shortContent.style.display = 'none';
    fullContent.style.display = 'block';
  }
 }
 </script>



{{--you can add more here--}}
{{-- ✅ Advertisement Section - Horizontal Scroll --}}
@if(isset($activeAds) && $activeAds->isNotEmpty())
<div class="ads-section mt-4 mb-4">
    <h5 class="text-muted mb-3">
        <i class="fas fa-ad"></i> Sponsored Ads
    </h5>

    {{-- Horizontal Scroll Container --}}
    <div class="ads-scroll-container">
        @foreach($activeAds as $ad)
            <div class="ad-card" data-ad-id="{{ $ad->id }}">
                
                {{-- Ad Badge --}}
                <div class="ad-badge">
                    <i class="fas fa-ad mr-1"></i> Sponsored
                </div>

                {{-- Ad Media Thumbnail --}}
                @if($ad->media_url)
                    <div class="ad-thumbnail" onclick="trackAdClick({{ $ad->id }}, '{{ $ad->cta_link }}')">
                        @if($ad->media_type === 'image')
                            <img src="{{ $ad->media_url }}" alt="{{ $ad->title }}">
                        @elseif($ad->media_type === 'video')
                            <video>
                                <source src="{{ $ad->media_url }}" type="video/mp4">
                            </video>
                            <div class="video-play-icon">
                                <i class="fas fa-play-circle"></i>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Ad Content --}}
                <div class="ad-content">
                    <h6 class="ad-title">{{ Str::limit($ad->title, 40) }}</h6>
                    <p class="ad-description">{{ Str::limit($ad->description, 80) }}</p>
                    
                    {{-- CTA Button --}}
                    <a href="#" 
                       onclick="trackAdClick({{ $ad->id }}, '{{ $ad->cta_link }}'); return false;" 
                       class="ad-cta-btn">
                        {{ $ad->cta_text }}
                    </a>

                    {{-- Ad Stats --}}
                    <div class="ad-stats">
                        <small>
                            <i class="fas fa-eye"></i> {{ number_format($ad->impressions) }} • 
                            <i class="fas fa-mouse-pointer"></i> {{ number_format($ad->clicks) }} clicks
                        </small>
                    </div>
                </div>
            </div>

            {{-- Track Impression when ad loads --}}
            <script>
                (function() {
                    const adId = {{ $ad->id }};
                    
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                trackAdImpression(adId);
                                observer.unobserve(entry.target);
                            }
                        });
                    }, { threshold: 0.5 });

                    const adElement = document.querySelector(`[data-ad-id="${adId}"]`);
                    if (adElement) {
                        observer.observe(adElement);
                    }
                })();
            </script>
        @endforeach
    </div>
</div>

 <link rel="stylesheet" href="{{ asset('css/ads.css') }}">

 {{-- Ad Tracking Scripts --}}
<script>
// Track ad impression
function trackAdImpression(adId) {
    fetch(`/advertising/${adId}/impression`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            country: '{{ $user->country ?? "Unknown" }}'
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Ad impression tracked:', adId);
    })
    .catch(error => {
        console.error('Failed to track impression:', error);
    });
}

// Track ad click
function trackAdClick(adId, targetUrl) {
    fetch(`/advertising/${adId}/click`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            country: '{{ $user->country ?? "Unknown" }}'
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Ad click tracked:', adId);
        window.open(data.redirect_url || targetUrl, '_blank');
    })
    .catch(error => {
        console.error('Failed to track click:', error);
        window.open(targetUrl, '_blank');
    });
}

</script>

@endif

<hr />


@include('partials.suggestion-cards')

 <hr />

 @if(isset($otherPosts) && $otherPosts->isNotEmpty())
  <div class="op-section-header" style="margin-top: 16px;">
      <i class="fas fa-globe-africa"></i>
      <h5>Other People's Posts</h5>
  </div>
  <div class="list-group">
    @foreach($otherPosts as $post)
      @php
        $author = $post->user;
        $hashtags = DB::table('hashtags')->where('post_id', $post->id)->pluck('tag');
        
        // --- Attachment Logic Setup (REPLICATED FROM PROMOTED POST) ---
        $files = json_decode($post->file_path, true);
        $files = is_array($files) ? $files : [];
        $fileCount = count($files);

        $hiddenFileCount = $fileCount > 0 ? $fileCount - 1 : 0;
        $firstFile = $fileCount > 0 ? $files[0] : null;
        $fancyboxGroup = 'gallery-' . $post->id;
        // --- End Attachment Logic Setup ---
      @endphp

      <div class="op-card">
        {{-- Header: Avatar + Info + Follow + Menu --}}
        <div class="op-header">
          <a href="{{ route('profile.show', $author->id) }}">
            <img src="{{ $author->profileimg ?? asset('images/best3.png') }}" class="op-header-avatar" alt="">
          </a>
          <div class="op-author-info">
            <div class="op-author-name">
              <a href="{{ route('profile.show', $author->id) }}">{{ $author->name }}</a>
              @if($author->badge_expired)
                <i class="fas fa-clock" title="Badge Expired – Please Renew" style="color:#e74c3c;font-size:14px;"></i>
              @elseif($author->badge_status)
                <img src="{{ asset($author->badge_status) }}" alt="Verified" style="width:16px;height:16px;">
              @endif
            </div>
            <div class="op-author-meta">
              {{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }} &bull; {{ $author->continent }}, {{ $author->country }}
            </div>
          </div>

          {{-- Follow Button - inline in header --}}
          @if($author->id !== $user->id)
            @if(in_array($author->id, $followedUserIds))
              <span class="op-following-label"><i class="fa-solid fa-check" style="font-size:10px;"></i> Following</span>
            @else
              <button class="op-follow-btn postfollow-btn" data-user-id="{{ $author->id }}">Follow</button>
            @endif
          @endif

          {{-- Three-dot menu --}}
          <div class="post-options-dropdown">
            <button class="options-btn" type="button" aria-label="Post Options">
              <i class="fa fa-ellipsis-h"></i>
            </button>
            <div class="options-menu">
              @if($firstFile)
              <a class="options-item" href="javascript:void(0)" onclick="downloadFile('{{ $firstFile }}')">
                <i class="fa fa-download"></i> Download file
              </a>
              @endif
              <a class="options-item" href="javascript:void(0)" onclick="copyPostLink('{{ route('posts.show', $post->id) }}')">
                <i class="fa fa-link"></i> Copy post link
              </a>
              <a class="options-item" href="javascript:void(0)" onclick="shareAsTale({{ $post->id }})">
                <i class="fa fa-share"></i> Share as Tale
              </a>
              <div class="options-divider"></div>
              <a class="options-item save-post-btn" href="javascript:void(0)" onclick="toggleSavePost({{ $post->id }}, this)" style="color: {{ in_array($post->id, $savedPostIds ?? []) ? '#dc3545' : '#1877f2' }};">
                <i class="fa fa-bookmark" style="color: {{ in_array($post->id, $savedPostIds ?? []) ? '#dc3545' : '#1877f2' }};"></i> {{ in_array($post->id, $savedPostIds ?? []) ? 'Unsave post' : 'Save post' }}
              </a>
            </div>
          </div>
        </div>

        {{-- Body --}}
        <div class="op-body" @if($post->bgnd_color && $post->bgnd_color !== '#ffffff') style="color: {{ $post->text_color }}; background-color: {{ $post->bgnd_color }};" @endif>
          @php
            $content = strip_tags($post->post_content);
            $isLong = mb_strlen($content) > 90;
            $shortContent = $isLong ? mb_substr($content, 0, 90) . '...' : $content;
          @endphp

          @if(trim($content) !== '[media-only post]')
          <p class="other-post-content-{{ $post->id }}">
            {!! nl2br(e($shortContent)) !!}
            @if($isLong)
              <a href="#" class="read-more-link" onclick="toggleOtherContent(event, {{ $post->id }})">Read more</a>
            @endif
          </p>

          <p class="other-post-content-full-{{ $post->id }}" style="display: none;">
            {!! nl2br(e($post->post_content)) !!}
            <a href="#" class="read-less-link" onclick="toggleOtherContent(event, {{ $post->id }})">Read less</a>
          </p>
          @endif 

          <!-- ✅ NEW: Display Link Preview -->
            @if($post->link_preview)
                @php
                    $linkData = json_decode($post->link_preview, true);
                @endphp
                <div class="link-preview-card-display">
                    @if(!empty($linkData['image']))
                        <div class="link-preview-image">
                            <img src="{{ $linkData['image'] }}" alt="{{ $linkData['title'] }}">
                        </div>
                    @endif
                    <div class="link-preview-content">
                        <div class="link-preview-site">
                            @if(!empty($linkData['favicon']))<img src="{{ $linkData['favicon'] }}" class="link-preview-favicon">@endif
                            <span>{{ $linkData['site_name'] }}</span>
                        </div>
                        <h4 class="link-preview-title">{{ $linkData['title'] }}</h4>
                        @if(!empty($linkData['description']))
                            <p class="link-preview-description">{{ $linkData['description'] }}</p>
                        @endif
                        <a href="{{ $linkData['url'] }}" class="link-preview-url" target="_blank" rel="noopener">
                            <i class="fas fa-external-link-alt"></i> {{ Str::limit($linkData['url'], 50) }}
                        </a>
                    </div>
                </div>
            @endif

      
          {{-- Media (Facebook-style grid) --}}
@if($fileCount > 0)
  @php
    $videoFiles = [];
    $videoIndexCounter2 = 0;
    foreach($files as $file) {
      $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
      if(in_array($ext, ['mp4', 'webm', 'ogg'])) {
        $videoFiles[] = [
          'url' => $file,
          'caption' => strip_tags($post->post_content),
          'username' => $post->username
        ];
      }
    }
    $videoGalleryJson = json_encode($videoFiles);
    $displayCount2 = min($fileCount, 4);
    $gridClass2 = $fileCount >= 5 ? 'grid-5plus' : 'grid-' . $fileCount;
  @endphp
  <div class="fb-media-grid {{ $gridClass2 }} mb-2">
    @for($j = 0; $j < $displayCount2; $j++)
      @php
        $file = $files[$j];
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);
        $isVideo = in_array($ext, ['mp4','webm','ogg']);
      @endphp
      <div class="fb-media-item">
        @if($isImage)
          <a data-fancybox="{{ $fancyboxGroup }}" href="{{ $file }}" data-post-id="{{ $post->id }}" data-caption="{{ $post->post_content }},Posted by -{{ $post->username }}">
            <img src="{{ $file }}" loading="lazy" alt="Post image" />
          </a>
        @elseif($isVideo)
          @php
            $isCloudinary = Str::contains($file, 'res.cloudinary.com');
            $cloudinaryThumb = $isCloudinary
              ? preg_replace('/\.mp4$/', '', str_replace('/upload/', '/upload/so_1/', $file)) . '.jpg'
              : asset('images/video-placeholder.jpg');
            $currentVideoIdx = $videoIndexCounter2;
            $videoIndexCounter2++;
          @endphp
          <div class="inline-video-wrapper" data-post-id="{{ $post->id }}" data-video-gallery='{{ $videoGalleryJson }}' data-video-index="{{ $currentVideoIdx }}">
            <video
              class="inline-feed-video"
              src="{{ $file }}"
              poster="{{ $cloudinaryThumb }}"
              loop
              muted
              playsinline
              preload="metadata"
              onclick="expandInlineVideo(this.closest('.inline-video-wrapper').querySelector('.inline-video-expand-btn'))"
            ></video>
            <div class="inline-video-mute-btn" onclick="toggleInlineMute(event, this)">
              <i class="fas fa-volume-mute"></i>
            </div>
            <div class="inline-video-expand-btn" onclick="expandInlineVideo(this)">
              <i class="fas fa-expand"></i>
            </div>
          </div>
        @endif
        @if($j === $displayCount2 - 1 && $fileCount > 4)
          <div class="fb-media-overlay" onclick="document.querySelector('[data-fancybox=&quot;{{ $fancyboxGroup }}&quot;]').click();">
            +{{ $fileCount - 3 }}
          </div>
        @endif
      </div>
    @endfor
  </div>
  {{-- Hidden fancybox links for remaining images beyond 4 --}}
  @if($fileCount > 4)
    @foreach(array_slice($files, 4) as $remainingFile)
      @php
        $rExt = strtolower(pathinfo($remainingFile, PATHINFO_EXTENSION));
        $isRemainingImage = in_array($rExt, ['jpg','jpeg','png','gif','webp']);
      @endphp
      @if($isRemainingImage)
        <a data-fancybox="{{ $fancyboxGroup }}" href="{{ $remainingFile }}" data-post-id="{{ $post->id }}" data-caption="{{ $post->post_content }},Posted by -{{ $post->username }}" class="d-none"></a>
      @endif
    @endforeach
  @endif
@endif


          {{-- Hashtags --}}
          @if($hashtags->isNotEmpty())
            <div class="mt-2">
              @foreach($hashtags as $tag)
                <span class="badge bg-secondary">#{{ $tag }}</span>
              @endforeach
            </div>
          @endif

        </div>{{-- end op-body --}}

        {{-- Engagement summary - Facebook style --}}
        <div class="op-engagement-row">
          <div class="op-likes-summary">
            @if($post->likes_relation_count > 0)
              <i class="fa-solid fa-thumbs-up"></i>
              <a href="#" onclick="showUsers('likes', {{ $post->id }}); return false;">{{ $post->likes_relation_count }}</a>
            @endif
            @if(DB::table('post_rewards')->where('post_id', $post->id)->exists())
              <span class="badge bg-success" style="margin-left:6px;">Rewarded</span>
            @endif
          </div>
          <div class="op-right-stats">
            @if($post->comments->count() > 0)
              <span>{{ $post->comments->count() }} comments</span>
            @endif
            @if($post->reposts_relation_count > 0)
              <span>{{ $post->reposts_relation_count }} reposts</span>
            @endif
            @if(($post->shares ?? 0) > 0)
              <span>{{ $post->shares }} shares</span>
            @endif
          </div>
        </div>

        {{-- Meta row --}}
        <div class="op-meta-row">
          <span class="view-count" data-post-id="{{ $post->id }}" onclick="rewardFromPost({{ $post->id }})">
            <i class="fa-solid fa-gift"></i> Reward
          </span>
          <span class="view-count" data-post-id="{{ $post->id }}" onclick="loadViewers({{ $post->id }})">
            <i class="fa-regular fa-eye"></i> {{ $post->views_relation_count }} views
          </span>
        </div>
    

      






{{-- Action buttons --}}
<div class="op-actions">

    {{-- Like --}}
    <button
      class="btn btn-sm postlike-btn op-action-btn hover-like"
      style="{{ in_array($post->id, $likedPostIds ?? []) ? 'color: #00bfff;' : '' }}"
      data-post-id="{{ $post->id }}">
      <i class="fa-solid fa-thumbs-up"></i> Like <span class="like-count">{{ $post->likes_relation_count }}</span>
    </button>

    {{-- Comments --}}
    <button class="btn btn-sm op-action-btn hover-comment"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#comments-{{ $post->id }}">
      <i class="fa-solid fa-comment"></i> Comments {{ $post->comments->count() }}
    </button>

    

    {{-- Repost --}}
    <form method="POST" action="{{ route('posts.repost', $post->id) }}">
      @csrf
      <button type="submit" class="btn btn-sm op-action-btn hover-repost">
        <i class="fa-solid fa-retweet"></i> {{ $post->reposts_relation_count }}
      </button>
    </form>

{{-- Share Button - Opens Modal --}}
@php
    $totalShareActions = $post->shares ?? 0;
    $totalRecipients = $post->shares_relation_count ?? 0;
    $directShares = $post->sharesRelation()->where('platform', 'direct')->count();
    $socialShares = $post->sharesRelation()->where('platform', '!=', 'direct')->count();
@endphp

<button type="button" class="btn btn-sm op-action-btn hover-share"
        data-bs-toggle="modal"
        data-bs-target="#shareModal{{ $post->id }}"
        title="Shared {{ $totalShareActions }} times with {{ $totalRecipients }} recipients ({{ $directShares }} direct, {{ $socialShares }} social)">
  <i class="fa-solid fa-paper-plane"></i> {{ $totalShareActions }}
  @if($totalRecipients > 0)
    <small class="text-muted" style="font-size: 10px;">
      ({{ $totalRecipients }})
    </small>
  @endif
</button>

</div>

<div class="modal fade" id="shareModal{{ $post->id }}" tabindex="-1" aria-labelledby="shareModalLabel{{ $post->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="shareModalLabel{{ $post->id }}"><i class="fa-solid fa-paper-plane"></i> Share Post</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <form method="POST" action="{{ route('posts.share', $post->id) }}" id="shareForm{{ $post->id }}">
        @csrf
        <div class="modal-body">
          
          {{-- Tab Navigation --}}
          <ul class="nav nav-tabs mb-3" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="users-tab{{ $post->id }}" data-bs-toggle="tab" 
                      data-bs-target="#users{{ $post->id }}" type="button" role="tab">
                <i class="fa-solid fa-users"></i> Share to Users
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="social-tab{{ $post->id }}" data-bs-toggle="tab" 
                      data-bs-target="#social{{ $post->id }}" type="button" role="tab">
                <i class="fa-solid fa-globe"></i> Share to Social Media
              </button>
            </li>
          </ul>

          {{-- Tab Content --}}
          <div class="tab-content">
            
            {{-- Users Tab --}}
            <div class="tab-pane fade show active" id="users{{ $post->id }}" role="tabpanel">
              <div class="mb-3">
                <input type="text" class="form-control" placeholder="🔍 Search users..." 
                 id="userSearch{{ $post->id }}" onkeyup="filterUsers{{ $post->id }}()">
              </div>
              
              {{-- Select All checkbox --}}
              <div class="d-flex align-items-center mb-2 p-2" style="background: #f0f2f5; border-radius: 8px;">
                <input type="checkbox" id="selectAllChk{{ $post->id }}"
                       style="width: 20px; height: 20px; min-width: 20px; min-height: 20px; cursor: pointer; margin: 0 10px 0 0; accent-color: #1877f2;"
                       onchange="toggleAllUsers{{ $post->id }}(this.checked)">
                <label class="fw-bold mb-0" for="selectAllChk{{ $post->id }}" style="cursor: pointer;">
                  Select All Users
                </label>
                <span class="ms-auto text-muted" id="selectedCount{{ $post->id }}" style="font-size: 13px;">0 selected</span>
              </div>

              <div class="user-list" style="max-height: 300px; overflow-y: auto;" id="userList{{ $post->id }}">
                @php
                  // Get all users except current user
                  $allUsers = \App\Models\UserRecord::where('id', '!=', $user->id)
                      ->orderBy('name')
                      ->get();
                @endphp

                @forelse($allUsers as $index => $person)
                  <div class="mb-1 user-item-{{ $post->id }} d-flex align-items-center p-2"
                       data-username="{{ strtolower($person->name) }}"
                       data-page="{{ floor($index / 5) }}"
                       style="display: {{ $index < 5 ? 'flex' : 'none' }}; border-radius: 6px; cursor: pointer;"
                       onmouseover="this.style.background='#f0f2f5'" onmouseout="this.style.background=''">
                    <input type="checkbox" name="share_to_users[]"
                           class="share-user-chk-{{ $post->id }}"
                           value="{{ $person->id }}" id="user{{ $post->id }}_{{ $person->id }}"
                           style="width: 18px; height: 18px; min-width: 18px; min-height: 18px; cursor: pointer; margin: 0 10px 0 0; accent-color: #1877f2;"
                           onchange="updateSelectedCount{{ $post->id }}()">
                    <label class="d-flex align-items-center flex-grow-1 mb-0" for="user{{ $post->id }}_{{ $person->id }}" style="cursor: pointer;">
                      <img src="{{ $person->profileimg ?? asset('images/best3.png') }}"
                           class="rounded-circle" style="width:35px; height:35px; object-fit: cover; margin-right: 10px;">
                      <div>
                        <strong>{{ $person->name }}</strong>
                        @if($person->badge_expired)
                          <i class="fas fa-clock" title="Badge Expired – Please Renew" style="color:#e74c3c;font-size:15px;vertical-align:middle;"></i>
                        @elseif($person->badge_status)
                          <img src="{{ asset($person->badge_status) }}" alt="Verified" title="Verified User" style="width:18px; height:18px; vertical-align: middle;">
                        @endif
                        <small class="text-muted d-block">{{ '@' . $person->username }}</small>
                      </div>
                    </label>
                  </div>
                @empty
                  <p class="text-muted">No users available</p>
                @endforelse
              </div>

              {{-- Pagination Controls --}}
              <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="pagination-controls">
                  <button type="button" class="btn btn-sm btn-outline-primary"
                          id="prevBtn{{ $post->id }}" onclick="changePage{{ $post->id }}(-1)">
                    ◀ Prev
                  </button>
                  <span class="mx-2" id="pageInfo{{ $post->id }}">Page 1</span>
                  <button type="button" class="btn btn-sm btn-outline-primary"
                          id="nextBtn{{ $post->id }}" onclick="changePage{{ $post->id }}(1)">
                    Next ▶
                  </button>
                </div>
              </div>
            </div>

            {{-- Social Media Tab --}}
            {{-- Social Media Tab --}}
<div class="tab-pane fade" id="social{{ $post->id }}" role="tabpanel">
  <p class="text-muted mb-3">Choose social media platforms to share this post:</p>
  
  @php
    $postUrl = route('posts.show', $post->id);
    $postText = Str::limit(strip_tags($post->post_content), 100);
    $encodedUrl = urlencode($postUrl);
    $encodedText = urlencode($postText);
  @endphp
  
  <div class="list-group">
    {{-- Facebook --}}
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedUrl }}&quote={{ $encodedText }}" 
       target="_blank" 
       class="list-group-item list-group-item-action d-flex align-items-center social-share-link"
       data-platform="facebook"
       data-post-id="{{ $post->id }}"
       onclick="trackSocialShare(event, {{ $post->id }}, 'facebook')">
      <i class="fab fa-facebook text-primary me-3" style="font-size: 24px;"></i>
      <div>
        <strong>Facebook</strong>
        <small class="d-block text-muted">Share to your timeline</small>
      </div>
    </a>
    
    {{-- Twitter / X --}}
    <a href="https://twitter.com/intent/tweet?url={{ $encodedUrl }}&text={{ $encodedText }}" 
       target="_blank" 
       class="list-group-item list-group-item-action d-flex align-items-center social-share-link"
       data-platform="twitter"
       data-post-id="{{ $post->id }}"
       onclick="trackSocialShare(event, {{ $post->id }}, 'twitter')">
      <i class="fab fa-twitter text-info me-3" style="font-size: 24px;"></i>
      <div>
        <strong>Twitter / X</strong>
        <small class="d-block text-muted">Tweet this post</small>
      </div>
    </a>
    
    {{-- WhatsApp --}}
    <a href="https://api.whatsapp.com/send?text={{ $encodedText }}%20{{ $encodedUrl }}" 
       target="_blank" 
       class="list-group-item list-group-item-action d-flex align-items-center social-share-link"
       data-platform="whatsapp"
       data-post-id="{{ $post->id }}"
       onclick="trackSocialShare(event, {{ $post->id }}, 'whatsapp')">
      <i class="fab fa-whatsapp text-success me-3" style="font-size: 24px;"></i>
      <div>
        <strong>WhatsApp</strong>
        <small class="d-block text-muted">Send via WhatsApp</small>
      </div>
    </a>
    
    {{-- Telegram --}}
    <a href="https://t.me/share/url?url={{ $encodedUrl }}&text={{ $encodedText }}" 
       target="_blank" 
       class="list-group-item list-group-item-action d-flex align-items-center social-share-link"
       data-platform="telegram"
       data-post-id="{{ $post->id }}"
       onclick="trackSocialShare(event, {{ $post->id }}, 'telegram')">
      <i class="fab fa-telegram text-primary me-3" style="font-size: 24px;"></i>
      <div>
        <strong>Telegram</strong>
        <small class="d-block text-muted">Share on Telegram</small>
      </div>
    </a>
    
    {{-- LinkedIn --}}
    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $encodedUrl }}" 
       target="_blank" 
       class="list-group-item list-group-item-action d-flex align-items-center social-share-link"
       data-platform="linkedin"
       data-post-id="{{ $post->id }}"
       onclick="trackSocialShare(event, {{ $post->id }}, 'linkedin')">
      <i class="fab fa-linkedin text-primary me-3" style="font-size: 24px;"></i>
      <div>
        <strong>LinkedIn</strong>
        <small class="d-block text-muted">Share professionally</small>
      </div>
    </a>
    
    {{-- Copy Link --}}
    <a href="#" 
       class="list-group-item list-group-item-action d-flex align-items-center"
       onclick="copyPostLink(event, '{{ $postUrl }}', {{ $post->id }})">
      <i class="fas fa-link text-secondary me-3" style="font-size: 24px;"></i>
      <div>
        <strong>Copy Link</strong>
        <small class="d-block text-muted" id="copyStatus{{ $post->id }}">Copy post URL to clipboard</small>
      </div>
    </a>
    
    {{-- Email --}}
    <a href="mailto:?subject={{ urlencode('Check out this post') }}&body={{ $encodedText }}%20{{ $encodedUrl }}" 
       class="list-group-item list-group-item-action d-flex align-items-center social-share-link"
       data-platform="email"
       data-post-id="{{ $post->id }}"
       onclick="trackSocialShare(event, {{ $post->id }}, 'email')">
      <i class="fas fa-envelope text-danger me-3" style="font-size: 24px;"></i>
      <div>
        <strong>Email</strong>
        <small class="d-block text-muted">Share via email</small>
      </div>
    </a>
  </div>
</div>

<script>
// Track social media share
function trackSocialShare(event, postId, platform) {
  // Don't prevent default - let the link open
  
  // Send AJAX request to track the share
  fetch('{{ route("posts.trackShare") }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({
      post_id: postId,
      platform: platform
    })
  })
  .then(response => response.json())
  .then(data => {
    console.log('Share tracked:', data);
  })
  .catch(error => {
    console.log('Share tracking error:', error);
  });
}

// Copy post link to clipboard
function copyPostLink(event, url, postId) {
  event.preventDefault();
  
  // Copy to clipboard
  navigator.clipboard.writeText(url).then(function() {
    // Update status text
    const statusEl = document.getElementById('copyStatus' + postId);
    const originalText = statusEl.textContent;
    statusEl.textContent = '✅ Link copied!';
    statusEl.style.color = 'green';
    
    // Reset after 3 seconds
    setTimeout(() => {
      statusEl.textContent = originalText;
      statusEl.style.color = '';
    }, 3000);
    
    // Track the copy action
    fetch('{{ route("posts.trackShare") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        post_id: postId,
        platform: 'copy_link'
      })
    });
    
  }).catch(function(err) {
    // Fallback for older browsers
    const textArea = document.createElement('textarea');
    textArea.value = url;
    document.body.appendChild(textArea);
    textArea.select();
    document.execCommand('copy');
    document.body.removeChild(textArea);
    
    const statusEl = document.getElementById('copyStatus' + postId);
    statusEl.textContent = '✅ Link copied!';
    statusEl.style.color = 'green';
    
    setTimeout(() => {
      statusEl.textContent = 'Copy post URL to clipboard';
      statusEl.style.color = '';
    }, 3000);
  });
}
</script>

          </div>

          {{-- Optional Message --}}
          <div class="mt-3">
            <label class="form-label">Optional Message:</label>
            <textarea class="form-control" name="share_message" rows="2" 
                      placeholder="Add a message with your share..."></textarea>
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i> Share Now
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

<script>
(function() {
  const postId = {{ $post->id }};
  let currentPage{{ $post->id }} = 0;
  let isFiltering{{ $post->id }} = false;
  
  // Get all user items
  function getAllUsers{{ $post->id }}() {
    return document.querySelectorAll('.user-item-' + postId);
  }
  
  // Get visible (filtered or all) user items
  function getVisibleUsers{{ $post->id }}() {
    const allUsers = getAllUsers{{ $post->id }}();
    if (!isFiltering{{ $post->id }}) {
      return Array.from(allUsers);
    }
    
    // Return only users that match search
    return Array.from(allUsers).filter(item => {
      const username = item.getAttribute('data-username');
      const searchValue = document.getElementById('userSearch' + postId).value.toLowerCase();
      return username.includes(searchValue);
    });
  }
  
  // Update pagination display
  function updatePagination{{ $post->id }}() {
    const visibleUsers = getVisibleUsers{{ $post->id }}();
    const totalPages = Math.ceil(visibleUsers.length / 5);
    
    // Hide all users first
    getAllUsers{{ $post->id }}().forEach(item => {
      item.style.display = 'none';
    });
    
    // Show only current page users
    const start = currentPage{{ $post->id }} * 5;
    const end = start + 5;
    visibleUsers.slice(start, end).forEach(item => {
      item.style.display = 'block';
    });
    
    // Update page info
    document.getElementById('pageInfo' + postId).textContent = 
      `Page ${currentPage{{ $post->id }} + 1} of ${totalPages || 1}`;
    
    // Update button states
    document.getElementById('prevBtn' + postId).disabled = currentPage{{ $post->id }} === 0;
    document.getElementById('nextBtn' + postId).disabled = 
      currentPage{{ $post->id }} >= totalPages - 1 || totalPages === 0;
  }
  
  // Change page
  window['changePage' + postId] = function(direction) {
    const visibleUsers = getVisibleUsers{{ $post->id }}();
    const totalPages = Math.ceil(visibleUsers.length / 5);
    
    currentPage{{ $post->id }} += direction;
    
    // Bounds checking
    if (currentPage{{ $post->id }} < 0) currentPage{{ $post->id }} = 0;
    if (currentPage{{ $post->id }} >= totalPages) currentPage{{ $post->id }} = totalPages - 1;
    
    updatePagination{{ $post->id }}();
  };
  
  // Filter users by search
  window['filterUsers' + postId] = function() {
    const searchValue = document.getElementById('userSearch' + postId).value.toLowerCase();
    isFiltering{{ $post->id }} = searchValue.length > 0;
    currentPage{{ $post->id }} = 0; // Reset to first page
    updatePagination{{ $post->id }}();
  };
  
  // Toggle all users (across ALL pages)
  window['toggleAllUsers' + postId] = function(checked) {
    const allCheckboxes = document.querySelectorAll('.share-user-chk-' + postId);
    allCheckboxes.forEach(cb => cb.checked = checked);
    window['updateSelectedCount' + postId]();
  };

  // Update selected count display
  window['updateSelectedCount' + postId] = function() {
    const allCheckboxes = document.querySelectorAll('.share-user-chk-' + postId);
    const checkedCount = Array.from(allCheckboxes).filter(cb => cb.checked).length;
    const countEl = document.getElementById('selectedCount' + postId);
    if (countEl) countEl.textContent = checkedCount + ' selected';
    // Sync the "Select All" checkbox
    const selectAllChk = document.getElementById('selectAllChk' + postId);
    if (selectAllChk) selectAllChk.checked = checkedCount === allCheckboxes.length && allCheckboxes.length > 0;
  };
  
  // Initialize on modal open
  document.getElementById('shareModal' + postId).addEventListener('shown.bs.modal', function() {
    currentPage{{ $post->id }} = 0;
    isFiltering{{ $post->id }} = false;
    document.getElementById('userSearch' + postId).value = '';
    updatePagination{{ $post->id }}();
  });
})();
</script>


{{-- Place this AFTER the post display, but BEFORE @endforeach --}}
{{-- Share Modal --}}


{{-- ✅ Collapsible Comments Section --}}
<div class="collapse" id="comments-{{ $post->id }}">
  <div class="card-body border-top">

    {{-- ✍️ New Comment Form --}}
    <form method="POST" action="{{ route('posts.comment', $post->id) }}" 
      class="comment-form d-flex mb-3" 
      data-post-id="{{ $post->id }}" 
      data-parent-id="">
  @csrf
  <input type="text" name="comment" placeholder="Write a comment..." 
         class="form-control form-control-sm me-2" required>
  <button type="submit" class="btn btn-sm btn-primary ml-2">Post</button>
</form>

    {{-- Comments Section --}}
    
  <div id="commentList-{{ $post->id }}">
  @include('partials.comments', ['post' => $post])
</div>




    {{-- Who liked/reposted/shared --}}
    <div class="mt-2">
  <small class="text-muted">
    <span class="hover-like"><i class="fa-solid fa-thumbs-up"></i> Liked by: </span>
@php
    $likes = $post->likesRelation->take(5);
@endphp
@if($likes->isNotEmpty())
    @foreach($likes as $like)
        <a href="{{ route('profile.show', $like->user->id) }}" 
           class="text-primary text-decoration-none" 
           style="font-weight: 500;">
            {{ $like->user->name }}
        </a>@if(!$loop->last), @endif
    @endforeach
@else
    No likes yet
@endif

@if($post->likes_relation_count > 5)
    and <a href="#" onclick="showUsers('likes', {{ $post->id }})">
        ({{ $post->likes_relation_count - 5 }}) others
    </a>
@endif
<br>

<span class="hover-repost"><i class="fa-solid fa-retweet"></i> Reposted by: </span>
@php
    $reposts = $post->repostsRelation->take(5);
@endphp
@if($reposts->isNotEmpty())
    @foreach($reposts as $repost)
        <a href="{{ route('profile.show', $repost->user->id) }}" 
           class="text-primary text-decoration-none" 
           style="font-weight: 500;">
            {{ $repost->user->name }}
        </a>@if(!$loop->last), @endif
    @endforeach
@else
    No reposts yet
@endif

@if($post->reposts_relation_count > 5)
    and <a href="#" onclick="showUsers('reposts', {{ $post->id }})">
        ({{ $post->reposts_relation_count - 5 }}) others
    </a>
@endif
<br>


    @php
    // Get unique people who shared this post
    $sharers = $post->sharesRelation()
        ->with('user')
        ->get()
        ->pluck('user')
        ->filter()
        ->unique('id')
        ->take(5);
    
    $totalSharers = $post->sharesRelation()
        ->distinct('user_id')
        ->count('user_id');
    
    // Get unique people who received this post (direct shares only)
    $recipients = $post->sharesRelation()
        ->where('platform', 'direct')
        ->whereNotNull('recipient_id')
        ->with('recipient')
        ->get()
        ->pluck('recipient')
        ->filter()
        ->unique('id')
        ->take(5);
    
    $totalRecipients = $post->sharesRelation()
        ->where('platform', 'direct')
        ->whereNotNull('recipient_id')
        ->distinct('recipient_id')
        ->count('recipient_id');
    
    // Get social platforms shared to
    $socialPlatforms = $post->sharesRelation()
        ->where('platform', '!=', 'direct')
        ->whereNotNull('platform')
        ->pluck('platform')
        ->unique();
@endphp

<div class="mt-2">
 
    {{-- Show who shared it --}}
    <span class="hover-share"><i class="fa-solid fa-paper-plane"></i> Shared by: </span>
    @if($sharers->isNotEmpty())
        @foreach($sharers as $index => $sharer)
            <a href="{{ route('profile.show', $sharer->id) }}" class="text-primary text-decoration-none" style="font-weight: 500;">
                {{ $sharer->name }}
            </a>{{ $index < $sharers->count() - 1 ? ', ' : '' }}
        @endforeach
        @if($totalSharers > 5)
          and <a href="#" onclick="showUsers('sharers', {{ $post->id }})" class="text-primary">({{ $totalSharers - 5 }}) others</a>
        @endif
    @else
        <span>No one yet</span>
    @endif
    <br>
    
    {{-- Show who received it --}}
    @if($recipients->isNotEmpty())
      <span class="hover-users"><i class="fa-solid fa-users"></i> Shared with: </span>
      @foreach($recipients as $index => $recipient)
          <a href="{{ route('profile.show', $recipient->id) }}" class="text-primary text-decoration-none" style="font-weight: 500;">
              {{ $recipient->name }}
          </a>{{ $index < $recipients->count() - 1 ? ', ' : '' }}
      @endforeach
      @if($totalRecipients > 5)
        and <a href="#" onclick="showUsers('recipients', {{ $post->id }})" class="text-primary">({{ $totalRecipients - 5 }}) others</a>
      @endif
      <br>
    @endif
    
    {{-- Copy Link Action --}}
    <span class="hover-copy" style="cursor: pointer;" onclick="copyToClipboard('{{ route('posts.show', $post->id) }}')">
        <i class="fa-solid fa-copy"></i> Copy Post Link
    </span>
    <br>

    {{-- Show social platforms --}}
    @if($socialPlatforms->isNotEmpty())
      <span class="hover-world"><i class="fa-solid fa-globe"></i> Shared on: </span> {{ $socialPlatforms->map(fn($p) => ucfirst($p))->join(', ') }}
    @endif
 
</div>
  </small>
</div>

    
  </div>
</div>

  
      </div>
    @endforeach
  </div>






  
  <script>
  function toggleOtherContent(event, postId) {
    event.preventDefault();
    const shortContent = document.querySelector(`.other-post-content-${postId}`);
    const fullContent = document.querySelector(`.other-post-content-full-${postId}`);
    
    if (shortContent.style.display === 'none') {
      shortContent.style.display = 'block';
      fullContent.style.display = 'none';
    } else {
      shortContent.style.display = 'none';
      fullContent.style.display = 'block';
    }
  }
  </script>
@else
  <div class="alert alert-info mt-3">No other posts available at the moment.</div>
@endif




{{-- ✅ Tales and stories that last 24hrs --}}

<div class="d-flex align-items-center mb-3">
    <div class="icon-circle bg-purple text-white mr-3" style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: #8A2BE2;">
        <i class="fas fa-feather-alt"></i>
    </div>
    <div>
        <h5 class="mb-0">Daily Tales</h5>
        <small class="text-muted">Stories disappear after 24 hours</small>
    </div>
</div>

<div class="scroll-wrapper">
    <button class="scroll-btn left">&#8592;</button>
    <div class="scroll-container">
        </div>
    <button class="scroll-btn right">&#8594;</button>
</div>

<hr />

<!-- show people that are live  -->
<div>
  <!-- Active Live Streams -->
  @if($liveStreams->count() > 0)
  <div class="mb-5">
      <h3 class="section-title" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
          <span>
              <i class="fas fa-fire" style="color: #FF0000;"></i> Live Now
          </span>
          <span style="font-size: 14px; color: #888; font-weight: normal;">
              {{ $liveStreams->count() }} {{ Str::plural('stream', $liveStreams->count()) }}
          </span>
      </h3>
      
      <!-- Horizontal Scroll Container -->
      <div class="live-streams-scroll-wrapper">
          <button class="scroll-btn-live left" onclick="scrollLiveStreams('left')">
              <i class="fas fa-chevron-left"></i>
          </button>
          
          <div class="live-streams-container" id="liveStreamsContainer">
              @foreach($liveStreams as $stream)
              <div class="stream-card-horizontal">
                  <div class="stream-thumbnail">
                      <i class="fas fa-play-circle"></i>
                      <span class="stream-live-badge live-badge">
                          <span class="live-pulse"></span> LIVE
                      </span>
                      <span class="stream-viewers">
                          <i class="fas fa-eye"></i> {{ $stream->viewer_count }}
                      </span>
                  </div>
                  
                  <div class="stream-content">
                      <h4 class="stream-title">{{ Str::limit($stream->title, 40) }}</h4>
                      
                      <div class="stream-creator">
                          <img src="{{ $stream->creator->profileimg ?? asset('images/best3.png') }}" 
                               alt="{{ $stream->creator->name }}" 
                               class="creator-avatar">
                          <div>
                              <div class="creator-name">{{ $stream->creator->name }}</div>
                              <small class="text-muted">{{ $stream->started_at->diffForHumans() }}</small>
                          </div>
                      </div>

                      @if($stream->description)
                      <p class="text-muted mb-3" style="font-size: 13px; line-height: 1.4;">
                          {{ Str::limit($stream->description, 60) }}
                      </p>
                      @endif

                      <a href="{{ route('live.stream', $stream->id) }}" class="btn btn-watch">
                          <i class="fas fa-play"></i> Watch Now
                      </a>
                  </div>
              </div>
              @endforeach
          </div>
          
          <button class="scroll-btn-live right" onclick="scrollLiveStreams('right')">
              <i class="fas fa-chevron-right"></i>
          </button>
      </div>
  </div>
  @else
  <div class="no-streams">
      <i class="fas fa-video-slash"></i>
      <h4>No Live Streams Right Now</h4>
      <p>Be the first to go live and share your moment!</p>
      <button class="btn btn-primary mt-3" onclick="showGoLiveModal()">
          <i class="fas fa-video"></i> Start Broadcasting
      </button>
  </div>
  @endif
</div>

<style>
/* Live Streams Horizontal Scroll */
.live-streams-scroll-wrapper {
    position: relative;
    margin: 0 -15px;
    padding: 0 45px;
}

.live-streams-container {
    display: flex;
    overflow-x: auto;
    scroll-behavior: smooth;
    gap: 15px;
    padding: 10px 15px;
    scrollbar-width: thin;
    scrollbar-color: #FF0000 #f1f1f1;
    -webkit-overflow-scrolling: touch;
}

/* Hide scrollbar for Chrome, Safari and Opera */
.live-streams-container::-webkit-scrollbar {
    height: 6px;
}

.live-streams-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.live-streams-container::-webkit-scrollbar-thumb {
    background: #FF0000;
    border-radius: 10px;
}

.live-streams-container::-webkit-scrollbar-thumb:hover {
    background: #CC0000;
}

/* Scroll Buttons */
.scroll-btn-live {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.95);
    border: 2px solid #FF0000;
    color: #FF0000;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
}

.scroll-btn-live:hover {
    background: #FF0000;
    color: white;
    transform: translateY(-50%) scale(1.1);
}

.scroll-btn-live:active {
    transform: translateY(-50%) scale(0.95);
}

.scroll-btn-live.left {
    left: 5px;
}

.scroll-btn-live.right {
    right: 5px;
}

/* Stream Card Horizontal */
.stream-card-horizontal {
    min-width: 280px;
    max-width: 280px;
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    transition: all 0.3s;
    flex-shrink: 0;
}

.stream-card-horizontal:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(255, 0, 0, 0.2);
}

.stream-thumbnail {
    width: 100%;
    height: 160px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stream-thumbnail i.fa-play-circle {
    font-size: 48px;
    color: rgba(255, 255, 255, 0.5);
}

.stream-live-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: rgba(255, 0, 0, 0.95);
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: bold;
    display: flex;
    align-items: center;
    gap: 5px;
    backdrop-filter: blur(10px);
}

.live-pulse {
    width: 8px;
    height: 8px;
    background: white;
    border-radius: 50%;
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.3); opacity: 0.7; }
}

.stream-viewers {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: bold;
    backdrop-filter: blur(10px);
}

.stream-content {
    padding: 15px;
}

.stream-title {
    font-size: 15px;
    font-weight: bold;
    margin-bottom: 10px;
    color: #333;
    line-height: 1.3;
    height: 40px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.stream-creator {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
}

.creator-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #FF0000;
}

.creator-name {
    font-weight: 600;
    color: #333;
    font-size: 13px;
}

.stream-content p {
    height: 40px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.btn-watch {
    width: 100%;
    background: linear-gradient(135deg, #FF0000 0%, #FF6B6B 100%);
    color: white;
    border: none;
    padding: 10px;
    border-radius: 10px;
    font-weight: bold;
    font-size: 13px;
    transition: all 0.3s;
    text-align: center;
    display: block;
    text-decoration: none;
}

.btn-watch:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 15px rgba(255, 0, 0, 0.3);
    color: white;
    text-decoration: none;
}

.btn-watch i {
    margin-right: 5px;
}

/* No Streams Message */
.no-streams {
    text-align: center;
    padding: 60px 20px;
    color: #999;
    background: white;
    border-radius: 15px;
    margin: 20px 0;
}

.no-streams i {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.3;
    color: #FF0000;
}

.no-streams h4 {
    color: #666;
    margin-bottom: 10px;
}

.no-streams p {
    color: #999;
    margin-bottom: 20px;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .live-streams-scroll-wrapper {
        padding: 0 35px;
        margin: 0 -10px;
    }
    
    .stream-card-horizontal {
        min-width: 240px;
        max-width: 240px;
    }
    
    .stream-thumbnail {
        height: 140px;
    }
    
    .stream-thumbnail i.fa-play-circle {
        font-size: 40px;
    }
    
    .scroll-btn-live {
        width: 35px;
        height: 35px;
        font-size: 14px;
    }
    
    .scroll-btn-live.left {
        left: 0;
    }
    
    .scroll-btn-live.right {
        right: 0;
    }
}

@media (max-width: 480px) {
    .stream-card-horizontal {
        min-width: 200px;
        max-width: 200px;
    }
    
    .stream-thumbnail {
        height: 120px;
    }
    
    .stream-content {
        padding: 12px;
    }
    
    .stream-title {
        font-size: 14px;
        height: 36px;
    }
    
    .creator-name {
        font-size: 12px;
    }
    
    .no-streams {
        padding: 40px 15px;
    }
    
    .no-streams i {
        font-size: 48px;
    }
}
</style>

<script>
function scrollLiveStreams(direction) {
    const container = document.getElementById('liveStreamsContainer');
    const scrollAmount = 300;
    
    if (direction === 'left') {
        container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
    } else {
        container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    }
}

// Optional: Auto-hide scroll buttons when at edges
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('liveStreamsContainer');
    const leftBtn = document.querySelector('.scroll-btn-live.left');
    const rightBtn = document.querySelector('.scroll-btn-live.right');
    
    if (container && leftBtn && rightBtn) {
        function updateScrollButtons() {
            const isAtStart = container.scrollLeft <= 0;
            const isAtEnd = container.scrollLeft + container.clientWidth >= container.scrollWidth - 5;
            
            leftBtn.style.opacity = isAtStart ? '0.3' : '1';
            leftBtn.style.pointerEvents = isAtStart ? 'none' : 'auto';
            
            rightBtn.style.opacity = isAtEnd ? '0.3' : '1';
            rightBtn.style.pointerEvents = isAtEnd ? 'none' : 'auto';
        }
        
        container.addEventListener('scroll', updateScrollButtons);
        updateScrollButtons(); // Initial check
    }
});
</script>

<!-- Go Live Modal -->
<div class="modal fade" id="goLiveModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #FF0000 0%, #FF6B6B 100%); color: white; border-radius: 15px 15px 0 0;">
                <h5 class="modal-title">
                    <i class="fas fa-broadcast-tower"></i> Start Live Stream
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 30px;">
                <form id="goLiveForm">
                    @csrf
                    <div class="form-group">
                        <label style="font-weight: 600;">Stream Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="title" id="goLiveTitle" 
                               placeholder="What's your stream about?" required
                               style="border-radius: 10px; padding: 12px;">
                    </div>
                    
                    <div class="form-group">
                        <label style="font-weight: 600;">Description (Optional)</label>
                        <textarea class="form-control" name="description" id="goLiveDescription" 
                                  rows="3" placeholder="Tell viewers what to expect..."
                                  style="border-radius: 10px; padding: 12px;"></textarea>
                    </div>

                    <div class="alert alert-info" style="border-radius: 10px;">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Tip:</strong> Engage with your viewers to keep them watching!
                    </div>

                    <button type="submit" class="btn btn-block" id="startLiveBtn"
                            style="background: linear-gradient(135deg, #FF0000 0%, #FF6B6B 100%); color: white; padding: 12px; font-weight: bold; border-radius: 10px; border: none;">
                        <i class="fas fa-video"></i> Go Live Now
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


<hr />

<!-- marketplace -->
<div class="marketplace-section mb-5">
    <div class="marketplace-header">
        <div class="header-content">
            <h2 class="fw-bold mb-1">🛍️ Marketplace</h2>
            <p class="text-muted mb-0">Discover stores and products from our community</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('marketplace.my-store') }}" class="btn btn-primary btn-action">
                <i class="fas fa-store"></i> 
                <span class="btn-text">My Store</span>
            </a>
            <a href="{{ route('marketplace.show-create-store') }}" class="btn btn-success btn-action">
                <i class="fas fa-plus"></i> 
                <span class="btn-text">Create Store</span>
            </a>
        </div>
    </div>

    <!-- Stores Horizontal Scroll -->
    @if($stores->count() > 0)
        <div class="stores-scroll-wrapper position-relative">
            <!-- Left Scroll Button -->
            <button class="scroll-btn-stores left" onclick="scrollStores('left')">
                <i class="fas fa-chevron-left"></i>
            </button>
            
            <!-- Stores Container -->
            <div class="stores-scroll-container" id="storesScrollContainer">
                @foreach($stores as $store)
                    <div class="store-card-horizontal">
                        <!-- Store Banner -->
                        <div class="store-banner position-relative">
                            @if($store->banner)
                                <img src="{{ asset($store->banner) }}" alt="{{ $store->store_name }}">
                            @else
                                <div class="store-banner-placeholder"></div>
                            @endif
                            
                            <!-- Store Logo -->
                            <div class="store-logo-wrapper">
                                @if($store->logo)
                                    <img src="{{ asset($store->logo) }}" class="store-logo" alt="{{ $store->store_name }}">
                                @else
                                    <div class="store-logo store-logo-default">
                                        <i class="fas fa-store fa-2x text-primary"></i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Store Info -->
                        <div class="store-info">
                            <h5 class="store-name">{{ $store->store_name }}</h5>
                            <p class="store-owner">
                                <i class="fas fa-user"></i> {{ $store->owner->name ?? 'Unknown Owner' }}
                            </p>
                            <p class="store-description">
                                {{ Str::limit($store->description, 80) }}
                            </p>

                            <!-- Store Stats -->
                            <div class="store-stats">
                                <span><i class="fas fa-box"></i> {{ $store->active_products_count }}</span>
                                <span><i class="fas fa-shopping-cart"></i> {{ $store->total_orders }}</span>
                                <span><i class="fas fa-eye"></i> {{ $store->total_views }}</span>
                            </div>

                            <!-- Visit Button -->
                            <a href="{{ route('marketplace.view-store', $store->store_slug) }}" class="btn-visit-store">
                                Visit Store <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Right Scroll Button -->
            <button class="scroll-btn-stores right" onclick="scrollStores('right')">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <!-- View All Link -->
        <div class="text-center mt-3">
            <a href="{{ route('marketplace.index') }}" class="btn btn-outline-primary btn-view-all">
                View All Stores <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    @else
        <div class="text-center py-5 empty-state">
            <i class="fas fa-store fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No stores available yet</h4>
            <p class="text-secondary">Be the first to create a store!</p>
            <a href="{{ route('marketplace.show-create-store') }}" class="btn btn-primary mt-3 btn-create-first">
                <i class="fas fa-plus"></i> Create Your Store
            </a>
        </div>
    @endif
</div>

<style>
/* Marketplace Section */
.marketplace-section {
    padding: 20px 0;
}

/* Header Styles */
.marketplace-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 25px;
    gap: 15px;
}

.header-content h2 {
    font-size: 28px;
    margin-bottom: 5px;
}

.header-content p {
    font-size: 14px;
}

.header-actions {
    display: flex;
    gap: 10px;
    flex-shrink: 0;
}

.btn-action {
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
    transition: all 0.3s ease;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
}

.btn-action i {
    font-size: 16px;
}

/* Stores Scroll Wrapper */
.stores-scroll-wrapper {
    position: relative;
    margin: 0 -15px;
    padding: 0 50px;
}

/* Stores Scroll Container */
.stores-scroll-container {
    display: flex;
    overflow-x: auto;
    scroll-behavior: smooth;
    gap: 20px;
    padding: 20px 15px;
    scrollbar-width: thin;
    scrollbar-color: #667eea #f1f1f1;
    -webkit-overflow-scrolling: touch;
}

/* Hide scrollbar for Chrome, Safari and Opera */
.stores-scroll-container::-webkit-scrollbar {
    height: 8px;
}

.stores-scroll-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.stores-scroll-container::-webkit-scrollbar-thumb {
    background: #667eea;
    border-radius: 10px;
}

.stores-scroll-container::-webkit-scrollbar-thumb:hover {
    background: #5568d3;
}

/* Scroll Buttons */
.scroll-btn-stores {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.95);
    border: 2px solid #667eea;
    color: #667eea;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    cursor: pointer;
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
}

.scroll-btn-stores:hover {
    background: #667eea;
    color: white;
    transform: translateY(-50%) scale(1.1);
}

.scroll-btn-stores:active {
    transform: translateY(-50%) scale(0.95);
}

.scroll-btn-stores.left {
    left: 5px;
}

.scroll-btn-stores.right {
    right: 5px;
}

/* Store Card */
.store-card-horizontal {
    min-width: 320px;
    max-width: 320px;
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.store-card-horizontal:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.2);
}

/* Store Banner */
.store-banner {
    width: 100%;
    height: 150px;
    overflow: hidden;
    position: relative;
}

.store-banner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.store-banner-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Store Logo */
.store-logo-wrapper {
    position: absolute;
    bottom: -35px;
    left: 20px;
}

.store-logo {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    border: 4px solid white;
    object-fit: cover;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    background: white;
}

.store-logo-default {
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Store Info */
.store-info {
    padding: 45px 20px 20px 20px;
}

.store-name {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 8px;
    color: #333;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.store-owner {
    font-size: 13px;
    color: #666;
    margin-bottom: 10px;
}

.store-owner i {
    margin-right: 5px;
}

.store-description {
    font-size: 13px;
    color: #777;
    line-height: 1.5;
    height: 60px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    margin-bottom: 15px;
}

/* Store Stats */
.store-stats {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-top: 1px solid #e9ecef;
    border-bottom: 1px solid #e9ecef;
    margin-bottom: 15px;
}

.store-stats span {
    font-size: 12px;
    color: #666;
    font-weight: 600;
}

.store-stats i {
    color: #667eea;
    margin-right: 4px;
}

/* Visit Button */
.btn-visit-store {
    display: block;
    width: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-align: center;
    padding: 12px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
}

.btn-visit-store:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    color: white;
    text-decoration: none;
}

.btn-visit-store i {
    margin-left: 5px;
    transition: margin-left 0.3s ease;
}

.btn-visit-store:hover i {
    margin-left: 10px;
}

.btn-view-all {
    padding: 12px 30px;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-view-all:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
}

/* Empty State */
.empty-state {
    background: white;
    border-radius: 15px;
    padding: 40px 20px !important;
    margin: 20px 0;
}

.btn-create-first {
    padding: 12px 30px;
    border-radius: 10px;
    font-weight: 600;
}

/* Tablet Responsive (768px - 1024px) */
@media (max-width: 1024px) {
    .header-content h2 {
        font-size: 24px;
    }
    
    .btn-action {
        padding: 8px 16px;
        font-size: 13px;
    }
    
    .btn-action i {
        font-size: 14px;
    }
}

/* Mobile Responsive (max-width: 768px) */
@media (max-width: 768px) {
    .marketplace-section {
        padding: 15px 0;
    }
    
    /* Stack header vertically on mobile */
    .marketplace-header {
        flex-direction: column;
        align-items: stretch;
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .header-content h2 {
        font-size: 22px;
    }
    
    .header-content p {
        font-size: 13px;
    }
    
    /* Make action buttons full width on mobile */
    .header-actions {
        flex-direction: row;
        width: 100%;
        gap: 8px;
    }
    
    .btn-action {
        flex: 1;
        justify-content: center;
        padding: 10px 12px;
        font-size: 13px;
    }
    
    .btn-text {
        display: inline;
    }
    
    .stores-scroll-wrapper {
        padding: 0 40px;
        margin: 0 -10px;
    }
    
    .store-card-horizontal {
        min-width: 280px;
        max-width: 280px;
    }
    
    .store-banner {
        height: 130px;
    }
    
    .scroll-btn-stores {
        width: 38px;
        height: 38px;
        font-size: 16px;
    }
    
    .scroll-btn-stores.left {
        left: 0;
    }
    
    .scroll-btn-stores.right {
        right: 0;
    }
    
    .btn-view-all {
        padding: 10px 25px;
        font-size: 14px;
    }
    
    .empty-state {
        padding: 30px 15px !important;
    }
    
    .empty-state h4 {
        font-size: 18px;
    }
    
    .empty-state p {
        font-size: 14px;
    }
}

/* Small Mobile (max-width: 480px) */
@media (max-width: 480px) {
    .marketplace-header {
        gap: 12px;
    }
    
    .header-content h2 {
        font-size: 20px;
    }
    
    .header-content p {
        font-size: 12px;
    }
    
    /* Stack buttons vertically on very small screens */
    .header-actions {
        flex-direction: column;
        gap: 8px;
    }
    
    .btn-action {
        width: 100%;
        padding: 12px 15px;
        font-size: 14px;
    }
    
    .btn-action i {
        font-size: 15px;
    }
    
    .store-card-horizontal {
        min-width: 260px;
        max-width: 260px;
    }
    
    .store-banner {
        height: 120px;
    }
    
    .store-info {
        padding: 40px 15px 15px 15px;
    }
    
    .store-name {
        font-size: 16px;
    }
    
    .store-logo {
        width: 60px;
        height: 60px;
    }
    
    .store-logo-wrapper {
        bottom: -30px;
    }
    
    .store-description {
        font-size: 12px;
        height: 54px;
    }
    
    .store-stats {
        padding: 10px 0;
    }
    
    .store-stats span {
        font-size: 11px;
    }
    
    .btn-visit-store {
        padding: 10px;
        font-size: 13px;
    }
    
    .scroll-btn-stores {
        width: 35px;
        height: 35px;
        font-size: 14px;
    }
    
    .btn-view-all {
        width: 100%;
        padding: 12px 20px;
    }
    
    .btn-create-first {
        width: 100%;
        padding: 12px 20px;
    }
}

/* Extra Small Mobile (max-width: 360px) */
@media (max-width: 360px) {
    .header-content h2 {
        font-size: 18px;
    }
    
    .btn-action {
        padding: 10px 12px;
        font-size: 13px;
    }
    
    .store-card-horizontal {
        min-width: 240px;
        max-width: 240px;
    }
    
    .store-info {
        padding: 35px 12px 12px 12px;
    }
    
    .store-stats span {
        font-size: 10px;
    }
}
</style>

<script>
// Scroll stores horizontally
function scrollStores(direction) {
    const container = document.getElementById('storesScrollContainer');
    const cardWidth = container.querySelector('.store-card-horizontal')?.offsetWidth || 300;
    const gap = 20;
    const scrollAmount = cardWidth + gap;
    
    if (direction === 'left') {
        container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
    } else {
        container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    }
}

// Auto-hide scroll buttons when at edges
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('storesScrollContainer');
    const leftBtn = document.querySelector('.scroll-btn-stores.left');
    const rightBtn = document.querySelector('.scroll-btn-stores.right');
    
    if (container && leftBtn && rightBtn) {
        function updateScrollButtons() {
            const isAtStart = container.scrollLeft <= 0;
            const isAtEnd = container.scrollLeft + container.clientWidth >= container.scrollWidth - 5;
            
            leftBtn.style.opacity = isAtStart ? '0.3' : '1';
            leftBtn.style.pointerEvents = isAtStart ? 'none' : 'auto';
            
            rightBtn.style.opacity = isAtEnd ? '0.3' : '1';
            rightBtn.style.pointerEvents = isAtEnd ? 'none' : 'auto';
        }
        
        container.addEventListener('scroll', updateScrollButtons);
        window.addEventListener('resize', updateScrollButtons);
        updateScrollButtons(); // Initial check
    }
});
</script>

<hr />

<!-- events -->
<div class="events-section mb-5">
    <!-- Events Header -->
    <div class="events-header mb-4">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h3 class="section-title mb-1">
                    <i class="fas fa-fire"></i> Upcoming Events
                </h3>
                <p class="text-muted mb-0">Discover and join amazing events happening around you</p>
            </div>
            <a href="{{ route('events.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Event
            </a>
        </div>
    </div>

    <!-- Events Horizontal Scroll -->
    @if($upcomingEvents->count() > 0)
        <div class="events-scroll-wrapper position-relative">
            <!-- Left Scroll Button -->
            <button class="scroll-btn-events left" onclick="scrollEvents('left')">
                <i class="fas fa-chevron-left"></i>
            </button>
            
            <!-- Events Container -->
            <div class="events-scroll-container" id="eventsScrollContainer">
                @foreach($upcomingEvents as $event)
                    <div class="event-card-horizontal">
                        <!-- Event Image -->
                        <div class="event-image-wrapper position-relative">
                            @if($event->event_image)
                                <img src="{{ $event->event_image }}" alt="{{ $event->title }}" class="event-image">
                            @else
                                <div class="event-image-placeholder">
                                    <i class="fas fa-calendar-alt fa-3x"></i>
                                </div>
                            @endif
                            
                            <!-- Event Type Badge -->
                            <span class="event-type-badge event-type-{{ $event->event_type }}">
                                @if($event->event_type === 'online')
                                    <i class="fas fa-globe"></i> Online
                                @elseif($event->event_type === 'physical')
                                    <i class="fas fa-map-marker-alt"></i> Physical
                                @else
                                    <i class="fas fa-layer-group"></i> Hybrid
                                @endif
                            </span>

                            <!-- Event Category Badge -->
                            <span class="event-category-badge">
                                {{ ucfirst($event->category) }}
                            </span>
                        </div>

                        <!-- Event Content -->
                        <div class="event-content">
                            <h4 class="event-title">{{ $event->title }}</h4>
                            <p class="event-description">
                                {{ Str::limit($event->description, 80) }}
                            </p>
                            
                            <!-- Event Meta Info -->
                            <div class="event-meta-list">
                                <div class="event-meta-item">
                                    <i class="far fa-calendar"></i>
                                    <span>{{ $event->event_date->format('M d, Y') }}</span>
                                </div>
                                <div class="event-meta-item">
                                    <i class="far fa-clock"></i>
                                    <span>{{ \Carbon\Carbon::parse($event->event_time)->format('g:i A') }}</span>
                                </div>
                                @if($event->location)
                                <div class="event-meta-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ Str::limit($event->location, 25) }}</span>
                                </div>
                                @endif
                            </div>

                            <!-- Event Footer -->
                            <div class="event-footer">
                                <div class="event-creator">
                                    <img src="{{ $event->creator->profileimg ?? asset('images/best3.png') }}" 
                                         alt="{{ $event->creator->name }}" 
                                         class="creator-avatar">
                                    <span class="creator-name">{{ $event->creator->name }}</span>
                                </div>
                                <a href="{{ route('events.show', $event->id) }}" class="btn-view-event">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Right Scroll Button -->
            <button class="scroll-btn-events right" onclick="scrollEvents('right')">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <!-- View All Events Link -->
        <div class="text-center mt-3">
            <a href="{{ route('events.index') }}" class="btn btn-outline-primary">
                View All Events <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    @else
        <div class="no-events">
            <i class="fas fa-calendar-times fa-4x"></i>
            <h4>No upcoming events</h4>
            <p>Be the first to create an event!</p>
            <a href="{{ route('events.create') }}" class="btn btn-primary mt-3">
                <i class="fas fa-plus"></i> Create Event
            </a>
        </div>
    @endif
</div>

<style>
/* Events Section */
.events-section {
    padding: 20px 0;
}

.section-title {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    margin: 0;
}

/* Events Scroll Wrapper */
.events-scroll-wrapper {
    position: relative;
    margin: 0 -15px;
    padding: 0 50px;
}

/* Events Scroll Container */
.events-scroll-container {
    display: flex;
    overflow-x: auto;
    scroll-behavior: smooth;
    gap: 20px;
    padding: 20px 15px;
    scrollbar-width: thin;
    scrollbar-color: #FF6B6B #f1f1f1;
    -webkit-overflow-scrolling: touch;
}

/* Hide scrollbar for Chrome, Safari and Opera */
.events-scroll-container::-webkit-scrollbar {
    height: 8px;
}

.events-scroll-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.events-scroll-container::-webkit-scrollbar-thumb {
    background: #FF6B6B;
    border-radius: 10px;
}

.events-scroll-container::-webkit-scrollbar-thumb:hover {
    background: #FF5252;
}

/* Scroll Buttons */
.scroll-btn-events {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.95);
    border: 2px solid #FF6B6B;
    color: #FF6B6B;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    cursor: pointer;
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
}

.scroll-btn-events:hover {
    background: #FF6B6B;
    color: white;
    transform: translateY(-50%) scale(1.1);
}

.scroll-btn-events:active {
    transform: translateY(-50%) scale(0.95);
}

.scroll-btn-events.left {
    left: 5px;
}

.scroll-btn-events.right {
    right: 5px;
}

/* Event Card Horizontal */
.event-card-horizontal {
    min-width: 340px;
    max-width: 340px;
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.event-card-horizontal:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 25px rgba(255, 107, 107, 0.2);
}

/* Event Image */
.event-image-wrapper {
    width: 100%;
    height: 200px;
    overflow: hidden;
    position: relative;
}

.event-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.event-card-horizontal:hover .event-image {
    transform: scale(1.05);
}

.event-image-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

/* Event Type Badge */
.event-type-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: rgba(0, 0, 0, 0.75);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    gap: 5px;
}

.event-type-online {
    background: rgba(0, 123, 255, 0.9);
}

.event-type-physical {
    background: rgba(40, 167, 69, 0.9);
}

.event-type-hybrid {
    background: rgba(255, 193, 7, 0.9);
    color: #333;
}

/* Event Category Badge */
.event-category-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: rgba(255, 255, 255, 0.95);
    color: #FF6B6B;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Event Content */
.event-content {
    padding: 20px;
}

.event-title {
    font-size: 18px;
    font-weight: bold;
    color: #333;
    margin-bottom: 10px;
    line-height: 1.3;
    height: 50px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.event-description {
    font-size: 13px;
    color: #666;
    line-height: 1.5;
    height: 60px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    margin-bottom: 15px;
}

/* Event Meta Info */
.event-meta-list {
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e9ecef;
}

.event-meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #555;
    margin-bottom: 8px;
}

.event-meta-item:last-child {
    margin-bottom: 0;
}

.event-meta-item i {
    color: #FF6B6B;
    width: 16px;
    text-align: center;
}

/* Event Footer */
.event-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

.event-creator {
    display: flex;
    align-items: center;
    gap: 8px;
    flex: 1;
    min-width: 0;
}

.creator-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #FF6B6B;
}

.creator-name {
    font-size: 13px;
    font-weight: 600;
    color: #333;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.btn-view-event {
    background: linear-gradient(135deg, #FF6B6B 0%, #FF8E53 100%);
    color: white;
    padding: 8px 16px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
    transition: all 0.3s ease;
    display: inline-block;
}

.btn-view-event:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(255, 107, 107, 0.3);
    color: white;
    text-decoration: none;
}

/* No Events */
.no-events {
    text-align: center;
    padding: 60px 20px;
    color: #999;
    background: white;
    border-radius: 15px;
    margin: 20px 0;
}

.no-events i {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.3;
    color: #FF6B6B;
}

.no-events h4 {
    color: #666;
    margin-bottom: 10px;
}

.no-events p {
    color: #999;
    margin-bottom: 20px;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .events-scroll-wrapper {
        padding: 0 40px;
        margin: 0 -10px;
    }
    
    .event-card-horizontal {
        min-width: 300px;
        max-width: 300px;
    }
    
    .event-image-wrapper {
        height: 180px;
    }
    
    .scroll-btn-events {
        width: 38px;
        height: 38px;
        font-size: 16px;
    }
    
    .scroll-btn-events.left {
        left: 0;
    }
    
    .scroll-btn-events.right {
        right: 0;
    }
}

@media (max-width: 480px) {
    .event-card-horizontal {
        min-width: 270px;
        max-width: 270px;
    }
    
    .event-image-wrapper {
        height: 160px;
    }
    
    .event-content {
        padding: 15px;
    }
    
    .event-title {
        font-size: 16px;
        height: 45px;
    }
    
    .event-description {
        height: 55px;
    }
    
    .no-events {
        padding: 40px 15px;
    }
    
    .no-events i {
        font-size: 48px;
    }
}
</style>

<script>
// Scroll events horizontally
function scrollEvents(direction) {
    const container = document.getElementById('eventsScrollContainer');
    const scrollAmount = 360; // Card width + gap
    
    if (direction === 'left') {
        container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
    } else {
        container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    }
}

// Optional: Auto-hide scroll buttons when at edges
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('eventsScrollContainer');
    const leftBtn = document.querySelector('.scroll-btn-events.left');
    const rightBtn = document.querySelector('.scroll-btn-events.right');
    
    if (container && leftBtn && rightBtn) {
        function updateScrollButtons() {
            const isAtStart = container.scrollLeft <= 0;
            const isAtEnd = container.scrollLeft + container.clientWidth >= container.scrollWidth - 5;
            
            leftBtn.style.opacity = isAtStart ? '0.3' : '1';
            leftBtn.style.pointerEvents = isAtStart ? 'none' : 'auto';
            
            rightBtn.style.opacity = isAtEnd ? '0.3' : '1';
            rightBtn.style.pointerEvents = isAtEnd ? 'none' : 'auto';
        }
        
        container.addEventListener('scroll', updateScrollButtons);
        updateScrollButtons(); // Initial check
    }
});
</script>
<hr />


@include('partials.suggestion-cards')

<hr />
{{-- second  existing promoted posts section --}}
@php
  if (!isset($posts) && isset($post)) {
    $posts = collect([$post]);
  }
  $limitedPosts = $posts->take(5);
@endphp

<div class="">
  <div class="suggestions-section-header">
      <i class="fas fa-newspaper" style="color: #1877f2;"></i>
      <h5>Promoted Posts</h5>
      <span class="suggestions-count">
          <a href="{{ route('posts.all') }}" style="font-size: 13px;">View all your posts</a>
      </span>
  </div>

  @if($limitedPosts->isNotEmpty())
    <div class="d-flex flex-row flex-nowrap overflow-auto gap-3">
      @foreach($limitedPosts as $post)
        <div style="width: 200px; min-width: 200px; height: 220px; overflow: hidden; border: 1px solid #e4e6eb; border-radius: 10px; padding: 8px; flex-shrink: 0;">
          <div style="height: 100%; overflow: hidden; {{ ($post->bgnd_color && $post->bgnd_color !== '#ffffff') ? 'color: '.$post->text_color.'; background-color: '.$post->bgnd_color.';' : '' }}">
            @php
              $content = strip_tags($post->post_content);
              $isLong = mb_strlen($content) > 10;
              $shortContent = $isLong ? mb_substr($content, 0, 10) . '...' : $content;
            @endphp

            @if(trim($content) !== '[media-only post]')
            <p class="post-content-{{ $post->id }}" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 4px; font-size: 13px;">
              {{ $shortContent }}
              @if($isLong)
                <a href="#" class="read-more-link" style="font-size: 12px;" onclick="toggleContent(event, {{ $post->id }})">Read more</a>
              @endif
            </p>

            <p class="post-content-full-{{ $post->id }}" style="display: none; font-size: 13px; margin-bottom: 4px;">
              {!! nl2br(e($post->post_content)) !!}
              <a href="#" class="read-less-link" style="font-size: 12px;" onclick="toggleContent(event, {{ $post->id }})">Read less</a>
            </p>
            @endif

            @php
              // --- Attachment Logic Setup ---
              $files = json_decode($post->file_path, true);
              $files = is_array($files) ? $files : [];
              $fileCount = count($files);

              $hiddenFileCount = $fileCount > 0 ? $fileCount - 1 : 0;
              $firstFile = $fileCount > 0 ? $files[0] : null;

              $hashtags = DB::table('hashtags')->where('post_id', $post->id)->pluck('tag');
              // --- End Attachment Logic Setup ---
            @endphp

            @if($fileCount > 0)
              @php
                $videoFilesSm2 = [];
                $videoIdxSm2 = 0;
                foreach($files as $file) {
                  $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                  if(in_array($ext, ['mp4', 'webm', 'ogg'])) {
                    $videoFilesSm2[] = [
                      'url' => $file,
                      'caption' => strip_tags($post->post_content),
                      'username' => $post->username
                    ];
                  }
                }
                $videoGalleryJsonSm2 = json_encode($videoFilesSm2);
                $displayCountSm2 = min($fileCount, 4);
                $gridClassSm2 = $fileCount >= 5 ? 'grid-5plus' : 'grid-' . $fileCount;
                $fancyboxGroup = 'gallery-' . $post->id;
              @endphp
              <div class="fb-media-grid fb-grid-sm {{ $gridClassSm2 }} mt-2">
                @for($m = 0; $m < $displayCountSm2; $m++)
                  @php
                    $file = $files[$m];
                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                    $isVideo = in_array($ext, ['mp4','webm','ogg']);
                  @endphp
                  <div class="fb-media-item">
                    @if($isImage)
                      <a data-fancybox="{{ $fancyboxGroup }}" href="{{ $file }}" data-post-id="{{ $post->id }}" data-caption="{{ $post->post_content }},Posted by -{{ $post->username }}">
                        <img src="{{ $file }}" loading="lazy" alt="Post image" />
                      </a>
                    @elseif($isVideo)
                      @php
                        $isCloudinary = Str::contains($file, 'res.cloudinary.com');
                        $cloudinaryThumb = $isCloudinary
                          ? preg_replace('/\.mp4$/', '', str_replace('/upload/', '/upload/so_1/', $file)) . '.jpg'
                          : asset('images/video-placeholder.jpg');
                        $currentVidIdxSm2 = $videoIdxSm2;
                        $videoIdxSm2++;
                      @endphp
                      <div class="inline-video-wrapper" data-post-id="{{ $post->id }}" data-video-gallery='{{ $videoGalleryJsonSm2 }}' data-video-index="{{ $currentVidIdxSm2 }}">
                        <video
                          class="inline-feed-video"
                          src="{{ $file }}"
                          poster="{{ $cloudinaryThumb }}"
                          loop
                          muted
                          playsinline
                          preload="metadata"
                          onclick="expandInlineVideo(this.closest('.inline-video-wrapper').querySelector('.inline-video-expand-btn'))"
                        ></video>
                        <div class="inline-video-mute-btn" onclick="toggleInlineMute(event, this)">
                          <i class="fas fa-volume-mute"></i>
                        </div>
                        <div class="inline-video-expand-btn" onclick="expandInlineVideo(this)">
                          <i class="fas fa-expand"></i>
                        </div>
                      </div>
                    @endif
                    @if($m === $displayCountSm2 - 1 && $fileCount > 4)
                      <div class="fb-media-overlay" onclick="document.querySelector('[data-fancybox=&quot;{{ $fancyboxGroup }}&quot;]').click();">
                        +{{ $fileCount - 3 }}
                      </div>
                    @endif
                  </div>
                @endfor
              </div>
              @if($fileCount > 4)
                @foreach(array_slice($files, 4) as $remainingFile)
                  @php
                    $rExt = strtolower(pathinfo($remainingFile, PATHINFO_EXTENSION));
                    $isRemainingImage = in_array($rExt, ['jpg','jpeg','png','gif','webp']);
                  @endphp
                  @if($isRemainingImage)
                    <a data-fancybox="{{ $fancyboxGroup }}" href="{{ $remainingFile }}" data-post-id="{{ $post->id }}" data-caption="{{ $post->post_content }},Posted by -{{ $post->username }}" class="d-none"></a>
                  @endif
                @endforeach
              @endif
            @endif

            @if($hashtags->isNotEmpty())
              <div class="mt-2">
                <strong>Tags:</strong>
                @foreach($hashtags as $tag)
                  <span class="badge bg-secondary">#{{ $tag }}</span>
                @endforeach
              </div>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  @else
    <div class="alert alert-danger">
      <a href="{{ route('tasks.index') }}" style="text-decoration: none;">Complete a Promoted Post to earn up to 1k NGN</a>
    </div>
  @endif
 </div>

  <script>
  function toggleContent(event, postId) {
  event.preventDefault();
  const shortContent = document.querySelector(`.post-content-${postId}`);
  const fullContent = document.querySelector(`.post-content-full-${postId}`);
  
  if (shortContent.style.display === 'none') {
    shortContent.style.display = 'block';
    fullContent.style.display = 'none';
  } else {
    shortContent.style.display = 'none';
    fullContent.style.display = 'block';
  }
 }
 </script>





<!-- this is the end of col-6 middle div  -->
</div>




<div id="likeMessage" style="
  display: none;
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: #28a745;
  color: white;
  padding: 15px 25px;
  border-radius: 8px;
  font-size: 18px;
  z-index: 9999;
  box-shadow: 0 0 10px rgba(0,0,0,0.3);
">
  👍 You liked the post!
</div>

<audio id="likeSound" src="{{ asset('sounds/mixkit-fantasy-game-success-notification-270.wav') }}" preload="auto"></audio>

<div id="actionMessage" style="
  display: none;
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: #28a745;
  color: white;
  padding: 15px 25px;
  border-radius: 8px;
  font-size: 18px;
  z-index: 9999;
  box-shadow: 0 0 10px rgba(0,0,0,0.3);
">
  ✅ Action successful!
</div>
<audio id="actionSound" src="{{ asset('sounds/mixkit-fantasy-game-success-notification-270.wav') }}" preload="auto"></audio>



{{-- ❌ Error Message --}}
<div id="errorMessage" style="
  display: none;
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: #dc3545;
  color: white;
  padding: 15px 25px;
  border-radius: 8px;
  font-size: 18px;
  z-index: 9999;
  box-shadow: 0 0 10px rgba(0,0,0,0.3);
">
  ❌ Something went wrong!
</div>


<audio id="errorSound" src="{{ asset('sounds/mixkit-electric-fence-fx-2968.wav') }}" preload="auto"></audio>
<!-- show full list  -->
<div class="modal fade" id="userListModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userListModalLabel">Users</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="userListModalBody">
        Loading...
      </div>
      <div class="modal-footer justify-content-between">
        <button class="btn btn-sm btn-outline-secondary" id="prevPageBtn">← Prev</button>
        <button class="btn btn-sm btn-outline-secondary" id="nextPageBtn">Next →</button>
      </div>
    </div>
  </div>
</div>

<!-- show full list of viewers -->
 <!-- Viewers Modal -->
<div class="modal fade" id="viewersModal" tabindex="-1" aria-labelledby="viewersModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewersModalLabel">👁 Viewers</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="viewersList">
        <!-- Viewer list will be injected here -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Reward Modal -->
<div class="modal fade" id="rewardModal" tabindex="-1" aria-labelledby="rewardModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rewardModalLabel">🎁 Post Reward Status</h5>
        <button type="button" class="btn-close btn btn-sm btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
      </div>
      <div class="modal-body">
        <div id="rewardDetails"></div>

        <!-- Breakdown toggle button -->
        <button class="btn btn-sm btn-outline-info mt-3" onclick="toggleBreakdown()">📘 Post Breakdown</button>

        <!-- Hidden breakdown section -->
        <div id="rewardBreakdown" class="mt-3" style="display: none;">
          <div class="alert alert-secondary">
            <ul class="mb-0">
              <li>👍 <strong>Likes:</strong> Once your post reaches <strong>1k likes</strong>, you earn <strong>NGN 1000</strong>.</li>
              <li>💬 <strong>Comments:</strong> Once your post receives <strong>150 comments</strong>, you earn <strong>NGN 1000</strong>.</li>
              <li>🔁 <strong>Reposts:</strong> Once your post is reposted <strong>12 times</strong>, you earn <strong>NGN 500</strong>.</li>
              <li>📤 <strong>Shares:</strong> Once your post is shared <strong>12 times</strong>, you earn <strong>NGN 500</strong>.</li>
              <li>⏳ <strong>Deadline:</strong> All rewards must be earned within <strong>12 days</strong> of posting.</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>






<!----------------------------------------------------rightbar second colum ---------------------------------------------->

<div class="col-lg-3 stickyrows myads hhnn" id="right-column" style="">


{{-- ✅ Advertisement Section - Horizontal Scroll --}}
@if(isset($activeAds) && $activeAds->isNotEmpty())
<div class="ads-section mt-4 mb-4">
    <h5 class="text-muted mb-3">
        <i class="fas fa-ad"></i> Sponsored Ads
    </h5>

    {{-- Horizontal Scroll Container --}}
    <div class="ads-scroll-container">
        @foreach($activeAds as $ad)
            <div class="ad-card" data-ad-id="{{ $ad->id }}">
                
                {{-- Ad Badge --}}
                <div class="ad-badge">
                    <i class="fas fa-ad mr-1"></i> Sponsored
                </div>

                {{-- Ad Media Thumbnail --}}
                @if($ad->media_url)
                    <div class="ad-thumbnail" onclick="trackAdClick({{ $ad->id }}, '{{ $ad->cta_link }}')">
                        @if($ad->media_type === 'image')
                            <img src="{{ $ad->media_url }}" alt="{{ $ad->title }}">
                        @elseif($ad->media_type === 'video')
                            <video>
                                <source src="{{ $ad->media_url }}" type="video/mp4">
                            </video>
                            <div class="video-play-icon">
                                <i class="fas fa-play-circle"></i>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Ad Content --}}
                <div class="ad-content">
                    <h6 class="ad-title">{{ Str::limit($ad->title, 40) }}</h6>
                    <p class="ad-description">{{ Str::limit($ad->description, 80) }}</p>
                    
                    {{-- CTA Button --}}
                    <a href="#" 
                       onclick="trackAdClick({{ $ad->id }}, '{{ $ad->cta_link }}'); return false;" 
                       class="ad-cta-btn">
                        {{ $ad->cta_text }}
                    </a>

                    {{-- Ad Stats --}}
                    <div class="ad-stats">
                        <small>
                            <i class="fas fa-eye"></i> {{ number_format($ad->impressions) }} • 
                            <i class="fas fa-mouse-pointer"></i> {{ number_format($ad->clicks) }}
                        </small>
                    </div>
                </div>
            </div>

            {{-- Track Impression when ad loads --}}
            <script>
                (function() {
                    const adId = {{ $ad->id }};
                    
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                trackAdImpression(adId);
                                observer.unobserve(entry.target);
                            }
                        });
                    }, { threshold: 0.5 });

                    const adElement = document.querySelector(`[data-ad-id="${adId}"]`);
                    if (adElement) {
                        observer.observe(adElement);
                    }
                })();
            </script>
        @endforeach
    </div>
</div>

<style>
/* Horizontal Scroll Container */
.ads-scroll-container {
    display: flex;
    overflow-x: auto;
    gap: 15px;
    padding: 10px 0;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
}

/* Hide scrollbar but keep functionality */
.ads-scroll-container::-webkit-scrollbar {
    height: 6px;
}

.ads-scroll-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.ads-scroll-container::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.ads-scroll-container::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Ad Card */
.ad-card {
    min-width: 280px;
    max-width: 280px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    overflow: hidden;
    flex-shrink: 0;
    transition: transform 0.2s, box-shadow 0.2s;
}

.ad-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.12);
}

/* Ad Badge */
.ad-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: rgba(255, 193, 7, 0.95);
    color: #333;
    padding: 4px 10px;
    border-radius: 15px;
    font-size: 11px;
    font-weight: 600;
    z-index: 10;
    backdrop-filter: blur(5px);
}

/* Ad Thumbnail */
.ad-thumbnail {
    position: relative;
    width: 100%;
    height: 180px;
    overflow: hidden;
    cursor: pointer;
    background: #f5f5f5;
}

.ad-thumbnail img,
.ad-thumbnail video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.ad-thumbnail:hover img,
.ad-thumbnail:hover video {
    transform: scale(1.05);
}

/* Video Play Icon */
.video-play-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 48px;
    color: white;
    text-shadow: 0 2px 10px rgba(0,0,0,0.5);
    pointer-events: none;
}

/* Ad Content */
.ad-content {
    padding: 15px;
}

.ad-title {
    font-weight: bold;
    font-size: 15px;
    margin-bottom: 8px;
    color: #333;
    line-height: 1.3;
}

.ad-description {
    font-size: 13px;
    color: #666;
    margin-bottom: 12px;
    line-height: 1.4;
}

/* CTA Button */
.ad-cta-btn {
    display: block;
    width: 100%;
    background: #007bff;
    color: white;
    text-align: center;
    padding: 10px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    transition: background 0.2s;
}

.ad-cta-btn:hover {
    background: #0056b3;
    text-decoration: none;
    color: white;
}

/* Ad Stats */
.ad-stats {
    margin-top: 10px;
    text-align: center;
    color: #999;
    font-size: 12px;
}

.ad-stats i {
    font-size: 11px;
}

/* Responsive */
@media (max-width: 768px) {
    .ad-card {
        min-width: 250px;
        max-width: 250px;
    }
    
    .myads{
      display:none;
    }
    .ad-thumbnail {
        height: 160px;
    }
}

@media (max-width: 480px) {
    .ad-card {
        min-width: 220px;
        max-width: 220px;
    }
    
    .ad-thumbnail {
        height: 140px;
    }
    
    .ad-content {
        padding: 12px;
    }
}
</style>

{{-- Ad Tracking Scripts --}}
<script>
// Track ad impression
function trackAdImpression(adId) {
    fetch(`/advertising/${adId}/impression`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            country: '{{ $user->country ?? "Unknown" }}'
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Ad impression tracked:', adId);
    })
    .catch(error => {
        console.error('Failed to track impression:', error);
    });
}

// Track ad click
function trackAdClick(adId, targetUrl) {
    fetch(`/advertising/${adId}/click`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            country: '{{ $user->country ?? "Unknown" }}'
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Ad click tracked:', adId);
        window.open(data.redirect_url || targetUrl, '_blank');
    })
    .catch(error => {
        console.error('Failed to track click:', error);
        window.open(targetUrl, '_blank');
    });
}
</script>
@endif
<!-- you can add more here -->


  <div style=""> 
        <a href="{{ route('mutual.followers', $user->id) }}" class="animated-button7 fol">
          <span></span>
          <span></span>
          <span></span>
          <span></span>
          View Mutual Followers
        </a>
    {{-- Your own follow/unfollow button for viewing another profile --}}
    @if(!$isFollowing ?? false)
        <a href="{{ route('my.followers') }}" class="animated-button8 fol">
          <span></span>
          <span></span>
          <span></span>
          <span></span>
        View all my followers
      </a>
            <p>I have {{ $no_of_followers }} {{ Str::plural('Followers', $no_of_followers) }}</p>
    @endif
   </div>
    <hr>

    @include('partials.suggestion-cards')

</div>





<!-- end of 3 colums -->
</div>
</div>

<!-- Success Popup -->
<div id="successPopup" style="
  display: none;
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: #28a745;
  color: white;
  padding: 20px 30px;
  border-radius: 10px;
  box-shadow: 0 0 15px rgba(0,0,0,0.3);
  z-index: 9999;
  font-size: 18px;
  font-weight: bold;
  text-align: center;
">
  ✅ Your post has been shared successfully!
</div>


<!-- Success Sound -->
<audio id="successSound" src="/sounds/mixkit-game-success-alert-2039.wav" preload="auto"></audio>


<!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<!-- Then other jQuery-based scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
<script src="{{ asset('myjs/bar.js') }}"></script> <!-- this should come last -->
<!-- <script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@4.6.1/dist/emoji-button.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/js/all.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>

   
    
<script src="{{ asset('myjs/more_lesstext.js') }}"></script>
<script src="{{ asset('myjs/menu_pop_up_post.js') }}"></script>
<script src="{{ asset('myjs/searchuser.js') }}"></script>

<script>
// $.ajaxSetup({
//   headers: {
//     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//   }
// });

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

function toggleBreakdown() {
  const breakdown = document.getElementById('rewardBreakdown');
  breakdown.style.display = breakdown.style.display === 'none' ? 'block' : 'none';
}


</script>

<script src="{{ asset('myjs/allpost.js') }}"></script>
<script src="{{ asset('myjs/tales.js') }}"></script>

{{-- JavaScript for Active State --}}
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>

<script>

// for copingy postlink
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert("Post link copied to clipboard!");
    }).catch(err => {
        console.error('Failed to copy: ', err);
    });
}

function _showBtnSpinner(el) {
    if (!el) return;
    el._origHTML = el.innerHTML;
    el.style.pointerEvents = 'none';
    el.style.opacity = '0.7';
    el.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Please wait...';
}

function _hideBtnSpinner(el, html) {
    if (!el) return;
    el.style.pointerEvents = '';
    el.style.opacity = '';
    el.innerHTML = html || el._origHTML || '';
}

function copyPostLink(url) {
    var el = event ? event.currentTarget : null;
    _showBtnSpinner(el);
    navigator.clipboard.writeText(url).then(() => {
        if (el) {
            el.innerHTML = '<i class="fa fa-check" style="color:#28a745;"></i> Link copied!';
            el.style.pointerEvents = '';
            el.style.opacity = '';
            setTimeout(() => _hideBtnSpinner(el), 1500);
        }
    }).catch(err => {
        console.error('Error copying link: ', err);
        _hideBtnSpinner(el);
    });
}

function downloadFile(url) {
    var el = event ? event.currentTarget : null;
    _showBtnSpinner(el);
    fetch(url)
        .then(response => response.blob())
        .then(blob => {
            const blobUrl = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = blobUrl;
            const filename = url.split('/').pop().split('?')[0] || 'download';
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(blobUrl);
            _hideBtnSpinner(el);
        })
        .catch(err => {
            console.error('Download failed:', err);
            _hideBtnSpinner(el);
            window.open(url, '_blank');
        });
}

function toggleSavePost(postId, el) {
    _showBtnSpinner(el);
    fetch('{{ route("posts.toggleSave") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ post_id: postId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'saved') {
            _hideBtnSpinner(el, '<i class="fa fa-bookmark" style="color: #dc3545;"></i> Unsave post');
            el.style.color = '#dc3545';
        } else {
            _hideBtnSpinner(el, '<i class="fa fa-bookmark" style="color: #28a745;"></i> Save post');
            el.style.color = '#28a745';
        }
    })
    .catch(err => {
        console.error('Save toggle failed:', err);
        _hideBtnSpinner(el);
    });
}

function shareAsTale(postId) {
    if (!confirm('Share this post as a Tale?')) return;

    var el = event ? event.currentTarget : null;
    _showBtnSpinner(el);
    fetch('{{ route("posts.shareAsTale") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ post_id: postId })
    })
    .then(res => res.json())
    .then(data => {
        _hideBtnSpinner(el);
        if (data.status === 'success') {
            alert(data.message);
        } else {
            alert(data.error || 'Failed to share as tale.');
        }
    })
    .catch(err => {
        console.error('Share as tale failed:', err);
        _hideBtnSpinner(el);
        alert('Something went wrong.');
    });
}

@auth
// Regular heartbeat every 2 minutes
setInterval(function() {
    fetch('{{ route('heartbeat') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }).catch(err => console.log('Heartbeat failed:', err));
}, 120000);

// ✅ NEW: Immediately send heartbeat when user becomes active again
let isIdle = false;
let idleTimer;

// Detect user activity
['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'].forEach(event => {
    document.addEventListener(event, function() {
        if (isIdle) {
            // User was idle, now active - send immediate heartbeat
            fetch('{{ route('heartbeat') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            isIdle = false;
        }
        
        // Reset idle timer
        clearTimeout(idleTimer);
        idleTimer = setTimeout(() => {
            isIdle = true;
        }, 300000); // 5 minutes of no activity = idle
    });
});
@endauth
</script>




<script>
// to follow from post
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.postfollow-btn').forEach(button => {
    button.addEventListener('click', function () {
      const userId = this.getAttribute('data-user-id');
      const btn = this;

      fetch(`/follow/${userId}`, {
  method: 'POST',
  headers: {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    'X-Requested-With': 'XMLHttpRequest',
    'Accept': 'application/json',
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({})
})

      .then(res => {
        if (!res.ok) throw new Error('Request failed');
        return res.json();
      })
      .then(data => {
        btn.outerHTML = `<span class="" style="font-size: small;color:grey;">Thanks for following me</span>`;
      })
      .catch(err => {
        console.error(err);
        alert('Something went wrong. Please try again.');
      });
    });
  });
});




// for paginating
let currentPage = 1;
let currentType = '';
let currentPostId = null;

function showUsers(type, postId) {
  currentPage = 1;
  currentType = type;
  currentPostId = postId;
  fetchUsers();
}

function fetchUsers() {
  fetch(`/posts/${currentPostId}/${currentType}/users?page=${currentPage}`)
    .then(res => res.json())
    .then(data => {
      const label = document.getElementById('userListModalLabel');
      const body = document.getElementById('userListModalBody');
      label.innerText = currentType.charAt(0).toUpperCase() + currentType.slice(1) + ' by';

      if (data.data.length) {
  body.innerHTML = '<ul class="list-unstyled">' + data.data.map(user =>
    `<li class="d-flex align-items-start mb-3">
      <img src="${user.profileimg || '/images/best3.png'}" class="rounded-circle me-2" style="width:35px;height:35px;">
      <div>
        <div>
          <a href="/profile/${user.id}" style="text-decoration: none; font-weight: 600;margin-left:4px;">${user.name}</a>
          ${user.badge ? `<img src="${user.badge}" alt="badge" title="Verified" style="width:18px;height:18px;margin-left:4px;">` : ''}
        </div>
        <small class="text-muted">${user.timestamp}</small>
      </div>
    </li>`
  ).join('') + '</ul>';
} else {
  body.innerHTML = '<p class="text-muted">No users found.</p>';
}


      document.getElementById('prevPageBtn').disabled = !data.prev_page_url;
      document.getElementById('nextPageBtn').disabled = !data.next_page_url;

      const modalEl = document.getElementById('userListModal');
const modal = new bootstrap.Modal(modalEl);
modal.show();

    });
}

document.getElementById('prevPageBtn').addEventListener('click', () => {
  if (currentPage > 1) {
    currentPage--;
    fetchUsers();
  }
});

document.getElementById('nextPageBtn').addEventListener('click', () => {
  currentPage++;
  fetchUsers();
});

</script>


<!-- Toast notification for follow actions -->
<div class="sg-toast" id="sgToast"></div>

<script>
// Suggestion card follow handler
function sgShowToast(message, type) {
    var toast = document.getElementById('sgToast');
    toast.textContent = message;
    toast.className = 'sg-toast ' + type;
    toast.offsetHeight;
    toast.classList.add('show');

    // Play sound
    try {
        var soundFile = type === 'success'
            ? "{{ asset('sounds/mixkit-fantasy-game-success-notification-270.wav') }}"
            : "{{ asset('sounds/mixkit-tech-click-1127.wav') }}";
        var audio = new Audio(soundFile);
        audio.play();
    } catch(e) {}

    setTimeout(function() {
        toast.classList.remove('show');
    }, 2500);
}

function sgFollowUser(btn) {
    var url = btn.getAttribute('data-url');
    var userId = btn.getAttribute('data-user-id');
    var personName = btn.getAttribute('data-person-name') || 'User';

    btn.disabled = true;
    btn.textContent = 'Following...';

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.status === 'error') {
            throw new Error(data.message);
        }

        btn.textContent = 'Following';
        btn.classList.add('sg-following');
        btn.disabled = true;

        // Update all follower counts for this user on the page
        var countEls = document.querySelectorAll('#sg-count-' + userId);
        countEls.forEach(function(el) {
            if (data.followers_count !== undefined) {
                el.textContent = data.followers_count;
            }
        });

        sgShowToast(data.message || 'You followed ' + personName + '!', 'success');
    })
    .catch(function(err) {
        var msg = err.message || 'Something went wrong. Please try again.';

        if (msg.indexOf('already following') !== -1) {
            btn.textContent = 'Following';
            btn.classList.add('sg-following');
            btn.disabled = true;
        } else {
            btn.disabled = false;
            btn.textContent = 'Follow';
        }

        sgShowToast(msg, 'error');
    });
}
</script>




<!-- Daily Login Reward Popup -->
<div id="dailyLoginOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9998; backdrop-filter:blur(3px);"></div>
<div id="dailyLoginPopup" style="display:none;">
    <div class="reward-icon-circle">
        <i class="fas fa-coins"></i>
    </div>
    <div class="reward-title">Daily Login Reward!</div>
    <div class="reward-amount">+20 NGN</div>
    <div class="reward-msg">Welcome back! Your daily check-in bonus has been added to your wallet.</div>
    <div class="reward-progress-wrap">
        <div class="reward-progress-bar" id="rewardProgressBar"></div>
    </div>
</div>

<style>
#dailyLoginPopup {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0.8);
    background: linear-gradient(145deg, #1a1a2e, #16213e);
    color: #fff;
    padding: 30px 36px;
    border-radius: 16px;
    text-align: center;
    z-index: 9999;
    box-shadow: 0 20px 60px rgba(0,0,0,0.5), 0 0 30px rgba(94,156,255,0.15);
    border: 1px solid rgba(255,255,255,0.08);
    opacity: 0;
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    min-width: 280px;
    max-width: 340px;
}
#dailyLoginPopup.show {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1);
}
#dailyLoginPopup.hide {
    opacity: 0;
    transform: translate(-50%, -50%) scale(0.8) translateY(20px);
}
.reward-icon-circle {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f5a623, #f7c948);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    box-shadow: 0 4px 15px rgba(245,166,35,0.4);
    animation: rewardBounce 0.6s ease 0.3s both;
}
.reward-icon-circle i {
    font-size: 28px;
    color: #fff;
}
.reward-title {
    font-size: 13px;
    font-weight: 600;
    color: #999;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 8px;
}
.reward-amount {
    font-size: 36px;
    font-weight: 800;
    background: linear-gradient(135deg, #f5a623, #f7c948);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 10px;
    animation: rewardPulse 1.5s ease infinite;
}
.reward-msg {
    font-size: 13px;
    color: #888;
    line-height: 1.5;
    margin-bottom: 18px;
}
.reward-progress-wrap {
    height: 4px;
    background: rgba(255,255,255,0.08);
    border-radius: 2px;
    overflow: hidden;
}
.reward-progress-bar {
    height: 100%;
    width: 100%;
    background: linear-gradient(90deg, #5e9cff, #7b68ee);
    border-radius: 2px;
    transition: width 4s linear;
}
@keyframes rewardBounce {
    0% { transform: scale(0); }
    60% { transform: scale(1.15); }
    100% { transform: scale(1); }
}
@keyframes rewardPulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}
</style>

@if(Session::has('reward'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    const popup = document.getElementById('dailyLoginPopup');
    const overlay = document.getElementById('dailyLoginOverlay');
    const progressBar = document.getElementById('rewardProgressBar');

    overlay.style.display = 'block';
    popup.style.display = 'block';

    requestAnimationFrame(() => {
        popup.classList.add('show');
        setTimeout(() => {
            progressBar.style.width = '0%';
        }, 100);
    });

    const audio = new Audio("{{ asset('sounds/mixkit-fantasy-game-success-notification-270.wav') }}");
    audio.play().catch(() => {});

    setTimeout(() => {
        popup.classList.remove('show');
        popup.classList.add('hide');
        overlay.style.display = 'none';
        setTimeout(() => {
            popup.style.display = 'none';
        }, 400);
    }, 4500);
});
</script>
@endif


{{-- Popup reward --}}
    <div id="taskPopup" style="
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #28a745;
    color: white;
    padding: 20px 30px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.3);
    font-size: 18px;
    font-weight: bold;
    display: none;
    z-index: 9999;
    text-align: center;
">
    ✅ Task completed! You've earned your reward.
</div>


@php
    $taskId = request()->query('task_id');
    $currentUserId = Session::get('id');
@endphp

@if($taskId && $post && $post->user_id !== $currentUserId)
<script>
document.addEventListener('DOMContentLoaded', function () {
    setTimeout(() => {
        fetch("{{ route('tasks.complete_custom', ['id' => $taskId]) }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "X-Requested-With": "XMLHttpRequest",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            const popup = document.getElementById('taskPopup');
            popup.innerText = data.message || "✅ Task completed!";
            popup.style.display = 'block';

            const audio = new Audio("{{ asset('sounds/mixkit-fantasy-game-success-notification-270.wav') }}");
            audio.play();

            setTimeout(() => {
                popup.style.display = 'none';
            }, 4000);
        })
        .catch(error => {
            console.error("Auto-complete failed:", error);
        });
    }, 5000);


    
});
</script>
@endif

@if($taskId && $post && $post->user_id === $currentUserId)
    <div class="alert alert-info">
        You created this post promotion task. Others can earn from it.
    </div>
@endif


<!-- for viewing and zooming background image  -->
<script>
  function showFullImage(url) {
    const modal = document.getElementById('imageModal');
    document.getElementById('fullImage').src = url;
    modal.style.display = 'flex';

    // Close if clicking outside the image
    modal.addEventListener('click', function(e) {
      if (e.target === modal) {
        closeImage();
      }
    });
  }

  function closeImage() {
    document.getElementById('imageModal').style.display = 'none';
  }
</script>



  <script>
  document.addEventListener('DOMContentLoaded', function () {
  // 👍 Like button logic
  // ✅ Your existing like/comment/share logic goes here...
  // 👍 Like message
  document.querySelectorAll('.postlike-btn').forEach(button => {
    button.addEventListener('click', function () {
      const postId = this.getAttribute('data-post-id');
      const btn = this;
      const countSpan = btn.querySelector('.like-count');

      fetch(`/posts/${postId}/like`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
      })
      .then(res => {
        if (!res.ok) throw new Error('Request failed');
        return res.json();
      })
      .then(data => {
        countSpan.textContent = data.likes_count;

        // Toggle skyblue color on like/unlike
        if (data.status === 'liked') {
          btn.style.color = '#00bfff';
        } else {
          btn.style.color = '#888';
        }

        // ✅ Show message and play sound
        const likeMsg = document.getElementById('likeMessage');
        const likeSound = document.getElementById('likeSound');

        likeMsg.innerText = data.status === 'liked' ? '👍 You liked the post!' : '👎 You unliked the post!';
        likeMsg.style.display = 'block';
        likeSound.play().catch(() => {
          console.warn('Like sound blocked.');
        });

        setTimeout(() => {
          likeMsg.style.display = 'none';
        }, 3000);
      })
      .catch(err => {
        console.error(err);
        const errorMsg = document.getElementById('errorMessage');
        const errorSound = document.getElementById('errorSound');

        errorMsg.innerText = '❌ Something went wrong!';
        errorMsg.style.display = 'block';
        errorSound.play().catch(() => {
          console.warn('Error sound blocked.');
        });

        setTimeout(() => {
          errorMsg.style.display = 'none';
        }, 3000);
      });
    });
  });



    // ✅ Success & error messages for comment, reply, repost, share
    const successMsg = document.getElementById('actionMessage');
    const errorMsg = document.getElementById('errorMessage');
    const successSound = document.getElementById('actionSound');
    const errorSound = document.getElementById('errorSound');

    @if(session('success'))
      let text = {!! json_encode(session('success')) !!}; // ✅ Safe for emojis

      if (text.includes('comment')) {
        successMsg.innerText = '💬 Comment posted!';
      } else if (text.includes('reply')) {
        successMsg.innerText = '↩ Reply added!';
      } else if (text.includes('repost')) {
        successMsg.innerText = '🔁 Post reposted!';
      } else if (text.includes('share')) {
        successMsg.innerText = '📤 Post shared!';
      } else {
        successMsg.innerText = text;
      }

      successMsg.style.display = 'block';
     successSound.play().catch(() => {
  console.warn('Sound blocked by browser autoplay policy.');
});


      setTimeout(() => {
        successMsg.style.display = 'none';
      }, 3000);
    @endif

    @if(session('error'))
      let errorText = {!! json_encode(session('error')) !!}; // ✅ Safe for emojis
      errorMsg.innerText = '❌ ' + errorText;

      errorMsg.style.display = 'block';
      errorSound.play().catch(() => {
  console.warn('Error sound blocked.');
});

      setTimeout(() => {
        errorMsg.style.display = 'none';
      }, 3000);
    @endif

    // ✍️ AJAX for comment/reply
    function bindCommentForms() {
      const forms = document.querySelectorAll('.comment-form');

      forms.forEach(form => {
        form.addEventListener('submit', function (e) {
          e.preventDefault();

          const postId = form.dataset.postId;
          const parentId = form.dataset.parentId;
          const comment = form.querySelector('input[name="comment"]').value;
          const token = form.querySelector('input[name="_token"]').value;

          fetch(`/posts/${postId}/comment`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ comment, parent_id: parentId })
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              successMsg.innerText = data.success;
              successMsg.style.display = 'block';
              successSound.play();
              setTimeout(() => successMsg.style.display = 'none', 3000);
              form.reset();

              // ✅ Refresh comment list
              fetch(`/posts/${postId}/comments`)
                .then(res => res.text())
                .then(html => {
                  document.getElementById(`commentList-${postId}`).innerHTML = html;
                  bindCommentForms(); // ✅ Rebind forms after refresh
                });
            } else if (data.error) {
              errorMsg.innerText = '❌ ' + data.error;
              errorMsg.style.display = 'block';
              errorSound.play();
              setTimeout(() => errorMsg.style.display = 'none', 3000);
            }
          })
          .catch(() => {
            errorMsg.innerText = '❌ Something went wrong.';
            errorMsg.style.display = 'block';
            errorSound.play();
            setTimeout(() => errorMsg.style.display = 'none', 3000);
          });
        });
      });
    }

    bindCommentForms(); // ✅ Initial binding


    // ✅ View tracking logic
    function setupViewTracking() {
  // ✅ Track views on click
  document.querySelectorAll('[data-fancybox]').forEach(el => {
    el.addEventListener('click', function (event) {
      const anchor = event.target.closest('[data-fancybox][data-post-id]');
      const postId = anchor?.getAttribute('data-post-id');

      if (!postId) {
        console.warn('No post ID found for media:', anchor);
        return;
      }

      fetch(`/posts/${postId}/track-view`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
      })
      .then(res => res.json())
      .then(data => {
        console.log('View tracked:', data);
        const viewSpan = document.querySelector(`.view-count[data-post-id="${postId}"]`);
        if (viewSpan) {
          viewSpan.textContent = data.views_count;
        }
      })
      .catch(err => {
        console.error('View tracking failed:', err);
      });
    });
  });

  // ✅ Pause other videos when a new one is revealed
 if (window.Fancybox) {
  Fancybox.bind('[data-fancybox]', {
    on: {
      done: (fancybox, slide) => {
        // ✅ Pause all videos in the document
        document.querySelectorAll('video').forEach(video => {
          if (!video.paused) {
            video.pause();
          }
        });

        console.log('Fancybox slide loaded:', slide.src);
      }
    }
  });
} else {
  console.warn('Fancybox not loaded yet.');
}

}
  // ✅ Delay setup to ensure Fancybox is ready
  setTimeout(setupViewTracking, 500);
});


// loading viewers
// Declare only once, outside the function
if (typeof currentpg === 'undefined') {
  var currentpg = 1;
}

const limit = 10;

function loadViewers(postId, page = 1) {
  currentpg = page;

  fetch(`/posts/${postId}/users/views?page=${page}&limit=${limit}`, {
    method: 'GET',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'application/json'
    }
  })
  .then(res => res.json())
  .then(data => {
    const container = document.getElementById('viewersList');
    container.innerHTML = '';

    if (data.users && data.users.length > 0) {
      const sortLabel = document.createElement('p');
      sortLabel.className = 'text-muted mb-2';
      sortLabel.textContent = 'Sorted by most recent viewers';
      container.appendChild(sortLabel);

      data.users.forEach(user => {
        const flagEmoji = getFlagEmoji(user.country);
        const verifiedBadge = user.verified
          ? `<img src="${user.verified}" title="Verified" style="width:16px;height:16px;margin-left:4px;">`
          : '';

        const item = document.createElement('div');
        item.className = 'd-flex align-items-center mb-3';

        item.innerHTML = `
          <a href="/profile/${user.id}" class="me-2">
            <img src="${user.profileimg || '/images/best3.png'}" class="rounded-circle" style="width:40px;height:40px;">
          </a>
          <div>
            <a href="/profile/${user.id}" style="text-decoration:none;">
              <strong class="ml-2">${user.name}</strong> ${verifiedBadge}<br>
              <small class="text-muted ml-2">@${user.username}</small><br>
              <small class="text-muted ml-2">${flagEmoji} ${user.country} • ${user.created_at}</small>
            </a>
          </div>
        `;
        container.appendChild(item);
      });

      // Pagination controls
      const totalPages = Math.ceil(data.total / data.limit);
      const pagination = document.createElement('div');
      pagination.className = 'mt-3 text-center';

      for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement('button');
        btn.className = `btn btn-sm ${i === data.page ? 'btn-primary' : 'btn-outline-primary'} mx-1`;
        btn.textContent = i;
        btn.onclick = () => loadViewers(postId, i);
        pagination.appendChild(btn);
      }

      container.appendChild(pagination);
    } else {
      container.innerHTML = '<p class="text-muted">No viewers yet.</p>';
    }

    const modal = new bootstrap.Modal(document.getElementById('viewersModal'));
    modal.show();
  })
  .catch(err => {
    console.error('Failed to load viewers:', err);
    alert('Error loading viewers.');
  });
}


function getFlagEmoji(countryName) {
  const countryCodes = {
    Nigeria: 'NG',
    Ghana: 'GH',
    Kenya: 'KE',
    SouthAfrica: 'ZA',
    UnitedStates: 'US',
    Canada: 'CA',
    UnitedKingdom: 'GB',
    Germany: 'DE',
    France: 'FR',
    India: 'IN',
    China: 'CN',
    Japan: 'JP',
    Brazil: 'BR',
    Australia: 'AU'
  };

  const code = countryCodes[countryName.replace(/\s+/g, '')];
  if (!code) return '';

  return code
    .toUpperCase()
    .replace(/./g, char => String.fromCodePoint(127397 + char.charCodeAt()));
}



function rewardFromPost(postId) {
  fetch(`/posts/${postId}/reward-status`, {
    method: 'GET',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'application/json'
    }
  })
  .then(res => res.json())
  .then(data => {
    const container = document.getElementById('rewardDetails');
    container.innerHTML = '';

    if (data.status === 'cancelled') {
      container.innerHTML = `<p class="text-danger">⛔ Reward cancelled — post did not meet criteria within 12 days.</p>`;
    } else {
      container.innerHTML = `
        <p><strong>Post Age:</strong> ${data.age} days</p>
        <ul class="list-group">
          <li class="list-group-item">👍 Likes: ${data.likes} / 1000 → ${data.likes_reward ? '✅ NGN 1000' : '❌'}</li>
          <li class="list-group-item">💬 Comments: ${data.comments} / 150 → ${data.comments_reward ? '✅ NGN 1000' : '❌'}</li>
          <li class="list-group-item">🔁 Reposts: ${data.reposts} / 12 → ${data.reposts_reward ? '✅ NGN 500' : '❌'}</li>
          <li class="list-group-item">📤 Shares: ${data.shares} / 12 → ${data.shares_reward ? '✅ NGN 500' : '❌'}</li>
        </ul>
        <p class="mt-3"><strong>Total Reward:</strong> NGN ${data.total_reward}</p>
      `;
    }

    const modal = new bootstrap.Modal(document.getElementById('rewardModal'));
    modal.show();
  })
  .catch(err => {
    console.error('Failed to load reward status:', err);
    alert('Error loading reward status.');
  });
}




//  for going live
function showGoLiveModal() {
    $('#goLiveModal').modal('show');
}

// Handle Go Live form submission
$(document).ready(function() {
    $('#goLiveForm').on('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = $('#startLiveBtn');
        const originalText = submitBtn.html();
        
        // Disable button and show loading
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Starting...');

        $.ajax({
            url: '{{ route("live.start") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                title: $('#goLiveTitle').val(),
                description: $('#goLiveDescription').val()
            },
            success: function(response) {
                if (response.success && response.stream_url) {
                    // Show success message
                    const popup = document.getElementById('successPopup');
                    popup.innerText = '✅ Going live! Redirecting...';
                    popup.style.display = 'block';
                    
                    // Play success sound
                    const audio = new Audio("{{ asset('sounds/mixkit-fantasy-game-success-notification-270.wav') }}");
                    audio.play().catch(() => console.log('Audio blocked'));
                    
                    // Redirect to stream page
                    setTimeout(() => {
                        window.location.href = response.stream_url;
                    }, 1000);
                } else {
                    throw new Error(response.error || 'Invalid response from server');
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html(originalText);
                
                let errorMessage = 'Failed to start stream. Please try again.';
                
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                } else if (xhr.status === 401) {
                    errorMessage = 'You must be logged in to go live.';
                } else if (xhr.status === 403) {
                    errorMessage = 'You already have an active stream.';
                }
                
                // Show error message
                const errorMsg = document.getElementById('errorMessage');
                const errorSound = document.getElementById('errorSound');
                
                errorMsg.innerText = '❌ ' + errorMessage;
                errorMsg.style.display = 'block';
                
                if (errorSound) {
                    errorSound.play().catch(() => console.log('Error sound blocked'));
                }
                
                setTimeout(() => {
                    errorMsg.style.display = 'none';
                }, 4000);
            }
        });
    });
});
</script>






{{-- Modern Mobile Video Player Styles --}}
<style>
.modern-video-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.95);
    z-index: 9999;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modern-video-container {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 95%;
    max-width: 900px;
    background: #000;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
}

.modern-video-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modern-video-title {
    color: white;
    font-size: 16px;
    font-weight: 600;
    margin: 0;
    flex: 1;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.modern-video-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    font-size: 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.modern-video-close:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

.modern-video-player {
    width: 100%;
    height: auto;
    max-height: 70vh;
    background: #000;
}

.modern-video-footer {
    background: #1a1a1a;
    padding: 15px 20px;
    color: #999;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.modern-video-footer .username {
    color: #667eea;
    font-weight: 600;
}

/* ✅ NEW: Navigation Arrows */
.video-nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    font-size: 24px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    z-index: 1000;
}

.video-nav-btn:hover {
    background: rgba(255, 255, 255, 0.4);
    transform: translateY(-50%) scale(1.1);
}

.video-nav-btn:disabled {
    opacity: 0.3;
    cursor: not-allowed;
}

.video-nav-prev {
    left: 20px;
}

.video-nav-next {
    right: 20px;
}

@media (max-width: 768px) {
    .modern-video-container {
        width: 100%;
        height: 100%;
        max-width: 100%;
        border-radius: 0;
        top: 0;
        left: 0;
        transform: none;
    }

    .modern-video-player {
        max-height: calc(100vh - 120px);
    }

    .modern-video-header {
        padding: 12px 15px;
    }

    .modern-video-title {
        font-size: 14px;
    }

    .video-nav-btn {
        width: 40px;
        height: 40px;
        font-size: 20px;
    }

    .video-nav-prev {
        left: 10px;
    }

    .video-nav-next {
        right: 10px;
    }
}

.video-loading {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 50px;
    height: 50px;
    border: 4px solid rgba(255, 255, 255, 0.3);
    border-top: 4px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: translate(-50%, -50%) rotate(0deg); }
    100% { transform: translate(-50%, -50%) rotate(360deg); }
}
</style>


{{-- Modern Video Player HTML Structure --}}
<div id="modernVideoOverlay" class="modern-video-overlay">
    <div class="modern-video-container">
        <div class="modern-video-header">
            <h3 class="modern-video-title" id="videoPlayerTitle">Video</h3>
            <button class="modern-video-close" onclick="closeModernVideo()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div style="position: relative; background: #000;">
            <!-- ✅ Navigation Arrows -->
            <button class="video-nav-btn video-nav-prev" id="videoPrevBtn" onclick="prevVideo()">
                <i class="fas fa-chevron-left"></i>
            </button>
            
            <div class="video-loading" id="videoLoading"></div>
            <video
                id="modernVideoPlayer"
                class="modern-video-player"
                controls
                controlsList="nodownload"
                playsinline
                preload="metadata"
            >
                Your browser does not support the video tag.
            </video>
            
            <button class="video-nav-btn video-nav-next" id="videoNextBtn" onclick="nextVideo()">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        <div class="modern-video-footer">
            <i class="fas fa-user-circle" style="color: #667eea;"></i>
            <span>Posted by: <span class="username" id="videoPlayerUsername">User</span></span>
        </div>
    </div>
</div>


{{-- Modern Video Player JavaScript --}}
<script>
let currentVideoGallery = [];
let currentVideoIndex = 0;
let currentVideoPostId = null;

function openModernVideo(videoUrl, caption, username, galleryVideos = null, startIndex = 0, postId = null) {
    const overlay = document.getElementById('modernVideoOverlay');
    const player = document.getElementById('modernVideoPlayer');
    const title = document.getElementById('videoPlayerTitle');
    const usernameEl = document.getElementById('videoPlayerUsername');
    const loading = document.getElementById('videoLoading');
    const prevBtn = document.getElementById('videoPrevBtn');
    const nextBtn = document.getElementById('videoNextBtn');

    // ✅ Store post ID for navigation
    if (postId) {
        currentVideoPostId = postId;
    }

    // ✅ Track video view when opened (only on initial open, not on navigation)
    if (postId && startIndex === 0) {
        fetch(`/posts/${postId}/track-view`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(res => res.json())
        .then(data => {
            console.log('Video view tracked:', data);
            const viewSpan = document.querySelector(`.view-count[data-post-id="${postId}"]`);
            if (viewSpan) {
                viewSpan.textContent = data.views_count;
            }
        })
        .catch(err => {
            console.error('Video view tracking failed:', err);
        });
    }

    // ✅ FIX: Parse gallery if it's a string
    if (galleryVideos && typeof galleryVideos === 'string') {
        try {
            galleryVideos = JSON.parse(galleryVideos);
        } catch (e) {
            console.error('Failed to parse gallery:', e);
            galleryVideos = null;
        }
    }

    // Store gallery if provided
    if (galleryVideos && Array.isArray(galleryVideos) && galleryVideos.length > 0) {
        currentVideoGallery = galleryVideos;
        currentVideoIndex = startIndex;
    } else if (videoUrl) {
        // Single video
        currentVideoGallery = [{url: videoUrl, caption: caption || '', username: username || 'Unknown'}];
        currentVideoIndex = 0;
    } else {
        console.error('No video URL or gallery provided');
        return;
    }
    
    // Get current video
    const currentVideo = currentVideoGallery[currentVideoIndex];
    
    // Show/hide navigation buttons
    if (currentVideoGallery.length > 1) {
        prevBtn.style.display = 'flex';
        nextBtn.style.display = 'flex';
        prevBtn.disabled = currentVideoIndex === 0;
        nextBtn.disabled = currentVideoIndex === currentVideoGallery.length - 1;
    } else {
        prevBtn.style.display = 'none';
        nextBtn.style.display = 'none';
    }
    
    overlay.style.display = 'block';
    document.body.style.overflow = 'hidden';
    loading.style.display = 'block';

    // ✅ FIX: Clean URL properly
    let cleanUrl = currentVideo.url;
    if (cleanUrl) {
        cleanUrl = cleanUrl.replace('?autoplay=1', '').replace('&autoplay=1', '');
    }

    // ✅ FIX: Validate URL before setting
    if (!cleanUrl || cleanUrl === 'undefined' || cleanUrl === 'null') {
        console.error('Invalid video URL:', cleanUrl);
        alert('Invalid video URL');
        closeModernVideo();
        return;
    }

    console.log('Loading video:', cleanUrl);

    // ✅ FIX: Properly set video source with type
    player.innerHTML = ''; // Clear existing sources
    const source = document.createElement('source');
    source.src = cleanUrl;

    // Detect video type from URL
    if (cleanUrl.includes('.webm')) {
        source.type = 'video/webm';
    } else if (cleanUrl.includes('.ogg')) {
        source.type = 'video/ogg';
    } else {
        source.type = 'video/mp4'; // Default to mp4
    }

    player.appendChild(source);
    player.load(); // Force reload the video
    
    const shortCaption = currentVideo.caption ? currentVideo.caption.substring(0, 50) + (currentVideo.caption.length > 50 ? '...' : '') : 'Video';
    title.textContent = shortCaption;
    usernameEl.textContent = currentVideo.username || 'Unknown User';
    
    // Update counter if multiple videos
    if (currentVideoGallery.length > 1) {
        title.textContent = `${shortCaption} (${currentVideoIndex + 1}/${currentVideoGallery.length})`;
    }
    
    // ✅ Remove old event listeners before adding new ones
    player.onloadeddata = null;
    player.onerror = null;

    // ✅ IMPROVED: Better error handling
    player.onloadeddata = function() {
        loading.style.display = 'none';
        player.play().catch(function(error) {
            console.log('Autoplay prevented:', error);
            // Don't close, let user manually play
        });
    };

    player.onerror = function(e) {
        loading.style.display = 'none';
        console.error('Video error:', e);

        // ✅ FIX: Get actual error details
        const error = player.error;
        let errorMsg = 'Failed to load video.';

        if (error) {
            switch(error.code) {
                case error.MEDIA_ERR_ABORTED:
                    errorMsg = 'Video loading was aborted.';
                    break;
                case error.MEDIA_ERR_NETWORK:
                    errorMsg = 'Network error occurred.';
                    break;
                case error.MEDIA_ERR_DECODE:
                    errorMsg = 'Video format not supported.';
                    break;
                case error.MEDIA_ERR_SRC_NOT_SUPPORTED:
                    errorMsg = 'Video source not found or not supported.';
                    break;
            }
        }

        console.error('Error details:', errorMsg, cleanUrl);
        alert(errorMsg + '\n\nThe video URL might be invalid or the file was deleted from Cloudinary.');
        // Don't auto-close, let user close manually
    };
}

function closeModernVideo() {
    const overlay = document.getElementById('modernVideoOverlay');
    const player = document.getElementById('modernVideoPlayer');
    const loading = document.getElementById('videoLoading');

    // Properly clean up video
    player.pause();
    player.removeAttribute('src');
    player.innerHTML = ''; // Clear all source elements
    player.load(); // Reset the video element

    // Hide modal and loading
    loading.style.display = 'none';
    overlay.style.display = 'none';
    document.body.style.overflow = '';

    // Reset state
    currentVideoGallery = [];
    currentVideoIndex = 0;
    currentVideoPostId = null;
}

function nextVideo() {
    if (currentVideoIndex < currentVideoGallery.length - 1) {
        currentVideoIndex++;
        openModernVideo(null, null, null, currentVideoGallery, currentVideoIndex, currentVideoPostId);
    }
}

function prevVideo() {
    if (currentVideoIndex > 0) {
        currentVideoIndex--;
        openModernVideo(null, null, null, currentVideoGallery, currentVideoIndex, currentVideoPostId);
    }
}

document.addEventListener('keydown', function(e) {
    const overlay = document.getElementById('modernVideoOverlay');
    if (overlay.style.display === 'block') {
        if (e.key === 'Escape') closeModernVideo();
        if (e.key === 'ArrowRight') nextVideo();
        if (e.key === 'ArrowLeft') prevVideo();
    }
});

document.getElementById('modernVideoOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeModernVideo();
});
</script>

<script>
// ===== Inline Feed Video Auto-Play on Scroll =====
(function() {
    var inlineMuted = true;

    // Auto-play/pause videos based on visibility
    var videoObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            var video = entry.target;
            if (entry.isIntersecting && entry.intersectionRatio >= 0.5) {
                video.play().catch(function() {});
            } else {
                video.pause();
            }
        });
    }, { threshold: 0.5 });

    // Observe all inline feed videos
    function observeInlineVideos() {
        document.querySelectorAll('.inline-feed-video').forEach(function(video) {
            if (!video.dataset.observed) {
                video.dataset.observed = '1';
                videoObserver.observe(video);
            }
        });
    }

    // Initial observe
    observeInlineVideos();

    // Re-observe after new content loads (infinite scroll, AJAX)
    var feedObserver = new MutationObserver(function() {
        observeInlineVideos();
    });
    var feedContainer = document.querySelector('.op-card-area') || document.body;
    feedObserver.observe(feedContainer, { childList: true, subtree: true });

    // Toggle play/pause on tap
    window.toggleInlineVideo = function(video) {
        if (video.paused) {
            video.play().catch(function() {});
        } else {
            video.pause();
        }
    };

    // Toggle mute/unmute
    window.toggleInlineMute = function(e, btn) {
        e.stopPropagation();
        var video = btn.parentElement.querySelector('video');
        var icon = btn.querySelector('i');
        if (video.muted) {
            // Unmute this video, mute all others
            document.querySelectorAll('.inline-feed-video').forEach(function(v) {
                v.muted = true;
            });
            video.muted = false;
            icon.className = 'fas fa-volume-up';
            inlineMuted = false;
        } else {
            video.muted = true;
            icon.className = 'fas fa-volume-mute';
            inlineMuted = true;
        }
        // Update all mute button icons
        document.querySelectorAll('.inline-video-mute-btn i').forEach(function(ic) {
            if (ic !== icon) ic.className = 'fas fa-volume-mute';
        });
    };

    // Expand to full video player modal
    window.expandInlineVideo = function(btn) {
        var wrapper = btn.closest('.inline-video-wrapper');
        var postId = wrapper.dataset.postId;
        var gallery = wrapper.dataset.videoGallery;
        var index = parseInt(wrapper.dataset.videoIndex) || 0;
        // Pause the inline video
        var video = wrapper.querySelector('video');
        if (video) video.pause();
        // Open in the modal player
        openModernVideo(null, null, null, gallery, index, postId);
    };
})();
</script>

<script>
// ── Link preview for Create Post modal & Story modal ──────────────────────────
(function () {
    let postPreviewData   = null;
    let talesPreviewData  = null;
    let postUrlTimeout    = null;
    let talesUrlTimeout   = null;
    const URL_REGEX       = /(https?:\/\/[^\s]+)/gi;

    function buildPreviewHtml(data, clearFn) {
        return `
            <div class="link-preview-card-display" style="margin-top:10px;">
                <button type="button" onclick="${clearFn}()" style="
                    position:absolute;top:6px;right:6px;background:rgba(0,0,0,0.55);
                    color:#fff;border:none;border-radius:50%;width:22px;height:22px;
                    display:flex;align-items:center;justify-content:center;cursor:pointer;
                    font-size:12px;z-index:10;">
                    <i class="fa fa-times"></i>
                </button>
                ${data.image ? `
                <div class="link-preview-image" style="height:140px;overflow:hidden;background:#f5f5f5;">
                    <img src="${data.image}" style="width:100%;height:100%;object-fit:cover;"
                         onerror="this.parentElement.style.display='none'">
                </div>` : ''}
                <div class="link-preview-content" style="padding:10px;">
                    <div class="link-preview-site" style="display:flex;align-items:center;gap:5px;margin-bottom:4px;color:#888;font-size:11px;">
                        <img src="${data.favicon}" style="width:12px;height:12px;border-radius:2px;"
                             onerror="this.style.display='none'">
                        <span>${data.site_name || ''}</span>
                    </div>
                    <h4 class="link-preview-title" style="font-size:13px;font-weight:700;margin:0 0 4px;color:#222;">${data.title}</h4>
                    ${data.description ? `<p class="link-preview-description" style="font-size:11px;color:#666;margin:0 0 6px;">${data.description}</p>` : ''}
                    <a href="${data.url}" target="_blank" rel="noopener"
                       style="font-size:10px;color:#1877f2;word-break:break-all;">
                        <i class="fas fa-external-link-alt"></i> ${data.url}
                    </a>
                </div>
            </div>`;
    }

    function fetchPreview(url, $container, storeFn) {
        $container.html(`
            <div style="text-align:center;padding:12px;background:#f8f9fa;border-radius:8px;
                        color:#0EA5E9;font-size:13px;margin-top:10px;">
                <i class="fa fa-spinner fa-spin"></i> Loading preview…
            </div>
        `).show();

        $.ajax({
            url: '{{ route("posts.fetchLinkPreview") }}',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}', url: url },
            success: function (data) {
                storeFn(data);
            },
            error: function () {
                $container.hide().empty();
                storeFn(null);
            }
        });
    }

    // ── Post modal ──────────────────────────────────────────────────────────────
    function clearPostPreview() {
        postPreviewData = null;
        $('#postLinkPreviewData').val('');
        $('#linkPreviewContainer').hide().empty();
    }
    window.clearPostPreview = clearPostPreview;

    function storePostPreview(data) {
        postPreviewData = data;
        if (data) {
            $('#postLinkPreviewData').val(JSON.stringify(data));
            $('#linkPreviewContainer').html(buildPreviewHtml(data, 'clearPostPreview')).show();
        } else {
            clearPostPreview();
        }
    }

    // Paste: show loading immediately
    document.getElementById('post_contents').addEventListener('paste', function (e) {
        const pasted = (e.clipboardData || window.clipboardData).getData('text');
        const urls   = pasted ? pasted.match(URL_REGEX) : null;
        if (urls && urls.length > 0) {
            clearTimeout(postUrlTimeout);
            $('#linkPreviewContainer').html(`
                <div style="text-align:center;padding:12px;background:#f8f9fa;border-radius:8px;
                            color:#0EA5E9;font-size:13px;margin-top:10px;">
                    <i class="fa fa-spinner fa-spin"></i> Loading preview…
                </div>
            `).show();
            postUrlTimeout = setTimeout(() => {
                fetchPreview(urls[0], $('#linkPreviewContainer'), storePostPreview);
            }, 300);
        }
    });

    // Input: detect URL on typing (debounced)
    document.getElementById('post_contents').addEventListener('input', function () {
        const urls = this.value.match(URL_REGEX);
        clearTimeout(postUrlTimeout);
        if (urls && urls.length > 0) {
            postUrlTimeout = setTimeout(() => {
                fetchPreview(urls[0], $('#linkPreviewContainer'), storePostPreview);
            }, 1000);
        } else if (!postPreviewData) {
            $('#linkPreviewContainer').hide().empty();
        }
    });

    // Clear preview when post form resets / modal closes
    document.addEventListener('click', function (e) {
        if (e.target.closest('[data-bs-dismiss="modal"]') || e.target.closest('.btn-close-modal')) {
            clearPostPreview();
        }
    });

    // ── Story (Tales) modal ─────────────────────────────────────────────────────
    function clearTalesPreview() {
        talesPreviewData = null;
        $('#talesLinkPreviewContainer').hide().empty();
    }
    window.clearTalesPreview = clearTalesPreview;

    function storeTalesPreview(data) {
        talesPreviewData = data;
        if (data) {
            $('#talesLinkPreviewContainer').html(buildPreviewHtml(data, 'clearTalesPreview')).show();
        } else {
            clearTalesPreview();
        }
    }

    const talesEl = document.getElementById('tales_msg');
    if (talesEl) {
        talesEl.addEventListener('paste', function (e) {
            const pasted = (e.clipboardData || window.clipboardData).getData('text');
            const urls   = pasted ? pasted.match(URL_REGEX) : null;
            if (urls && urls.length > 0) {
                clearTimeout(talesUrlTimeout);
                $('#talesLinkPreviewContainer').html(`
                    <div style="text-align:center;padding:12px;background:#f8f9fa;border-radius:8px;
                                color:#0EA5E9;font-size:13px;margin-top:10px;">
                        <i class="fa fa-spinner fa-spin"></i> Loading preview…
                    </div>
                `).show();
                talesUrlTimeout = setTimeout(() => {
                    fetchPreview(urls[0], $('#talesLinkPreviewContainer'), storeTalesPreview);
                }, 300);
            }
        });

        talesEl.addEventListener('input', function () {
            const urls = this.value.match(URL_REGEX);
            clearTimeout(talesUrlTimeout);
            if (urls && urls.length > 0) {
                talesUrlTimeout = setTimeout(() => {
                    fetchPreview(urls[0], $('#talesLinkPreviewContainer'), storeTalesPreview);
                }, 1000);
            } else if (!talesPreviewData) {
                $('#talesLinkPreviewContainer').hide().empty();
            }
        });
    }
})();
</script>

</body>
</html>