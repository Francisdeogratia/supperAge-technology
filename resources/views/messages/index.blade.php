
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
    <meta name="description" content="SupperAge is the social-financial app where you can chat, share, earn, shop, create stores, fund wallets, and withdraw money.">
    <meta name="keywords" content="SupperAge, social financial app, earn money online, chat and earn, online marketplace, digital wallet, social networking, e-commerce platform">
   <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />

    <meta http-equiv="X-UA-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Messages - SupperAge</title>

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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <!-- Your Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
     <link rel="stylesheet" href="{{ asset('css/finebtn.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">

    <!-- google ads -->
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2892124627300694"
     crossorigin="anonymous"></script>

<style>
/* ===== Messages Page - WhatsApp Style ===== */
.msg-wrapper {
    max-width: 700px;
    margin: 0 auto;
    padding: 0 0 100px;
}

/* Top Banner Notification */
.msg-banner {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 18px;
    border-radius: 14px;
    margin: 12px 15px 0;
    font-size: 13px;
    font-weight: 500;
    animation: msgBannerIn 0.4s ease;
}
.msg-banner.error {
    background: linear-gradient(135deg, #fff5f5, #ffe0e0);
    color: #c0392b;
    border: 1px solid #f5c6cb;
}
.msg-banner.success {
    background: linear-gradient(135deg, #f0fff4, #d4edda);
    color: #27ae60;
    border: 1px solid #c3e6cb;
}
.msg-banner.info {
    background: linear-gradient(135deg, #ebf5fb, #d6eaf8);
    color: #2980b9;
    border: 1px solid #bee5eb;
}
.msg-banner i {
    font-size: 18px;
    flex-shrink: 0;
}
.msg-banner .msg-banner-close {
    margin-left: auto;
    background: none;
    border: none;
    font-size: 18px;
    cursor: pointer;
    opacity: 0.5;
    color: inherit;
    padding: 0 4px;
}
.msg-banner .msg-banner-close:hover { opacity: 1; }
@keyframes msgBannerIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Header */
.msg-header {
    padding: 20px 18px 0;
}
.msg-header-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}
.msg-header h2 {
    font-size: 22px;
    font-weight: 700;
    color: #111;
    margin: 0;
}
.msg-header-actions {
    display: flex;
    gap: 8px;
}
.msg-header-actions a {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
}
.msg-action-archive {
    background: #fff3cd;
    color: #856404;
}
.msg-action-archive:hover { background: #ffe69c; color: #856404; text-decoration: none; }
.msg-action-blocked {
    background: #f8d7da;
    color: #721c24;
}
.msg-action-blocked:hover { background: #f5c6cb; color: #721c24; text-decoration: none; }

/* Search Bar */
.msg-search {
    position: relative;
    margin: 0 18px 6px;
}
.msg-search i {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #aaa;
    font-size: 14px;
}
.msg-search input {
    width: 100%;
    padding: 11px 16px 11px 42px;
    border: none;
    border-radius: 12px;
    background: #f0f2f5;
    font-size: 14px;
    color: #333;
    outline: none;
    transition: all 0.2s;
}
.msg-search input:focus {
    background: #e4e6eb;
    box-shadow: 0 0 0 2px rgba(0,168,132,0.15);
}
.msg-search input::placeholder { color: #999; }
.msg-search-count {
    font-size: 11px;
    color: #999;
    padding: 4px 18px 0;
    display: none;
}

/* Chat List */
.msg-list {
    padding: 6px 10px;
}

/* Single Chat Item */
.msg-item {
    display: flex;
    align-items: center;
    padding: 12px 10px;
    border-radius: 14px;
    text-decoration: none;
    color: inherit;
    transition: background 0.15s;
    position: relative;
}
.msg-item:hover {
    background: #f0f2f5;
    text-decoration: none;
    color: inherit;
}
.msg-item:active {
    background: #e4e6eb;
}
.msg-item.unread {
    background: #e7f6ef;
}
.msg-item.unread:hover {
    background: #d4f0e2;
}

/* Avatar */
.msg-avatar-wrap {
    position: relative;
    flex-shrink: 0;
    margin-right: 14px;
}
.msg-avatar {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    object-fit: cover;
}
.msg-online-dot {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 13px;
    height: 13px;
    border-radius: 50%;
    border: 2px solid #fff;
}
.msg-online-dot.online {
    background: #25D366;
}
.msg-online-dot.offline {
    background: #bbb;
    width: 0; height: 0; border: none;
}

/* Content */
.msg-content {
    flex: 1;
    min-width: 0;
    padding-right: 8px;
}
.msg-name-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 3px;
}
.msg-name {
    font-size: 15px;
    font-weight: 600;
    color: #111;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 70%;
}
.msg-name img {
    width: 15px;
    height: 15px;
    margin-left: 4px;
    vertical-align: middle;
}
.msg-time {
    font-size: 11px;
    color: #999;
    flex-shrink: 0;
    white-space: nowrap;
}
.msg-item.unread .msg-time {
    color: #25D366;
    font-weight: 600;
}
.msg-preview-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.msg-preview {
    font-size: 13px;
    color: #777;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
    min-width: 0;
}
.msg-item.unread .msg-preview {
    color: #333;
    font-weight: 600;
}
.msg-typing {
    color: #25D366;
    font-style: italic;
    font-weight: 500;
}
.msg-attachment-icon {
    color: #999;
    margin-right: 4px;
}

/* Unread Badge */
.msg-badge {
    background: #25D366;
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    min-width: 22px;
    height: 22px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 6px;
    flex-shrink: 0;
    margin-left: 8px;
}

/* Empty State */
.msg-empty {
    text-align: center;
    padding: 60px 30px;
}
.msg-empty-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #25D366, #128C7E);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}
.msg-empty-icon i {
    font-size: 36px;
    color: #fff;
}
.msg-empty h4 {
    font-size: 18px;
    font-weight: 700;
    color: #333;
    margin-bottom: 8px;
}
.msg-empty p {
    color: #888;
    font-size: 14px;
    margin-bottom: 20px;
}
.msg-empty a {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #25D366, #128C7E);
    color: #fff;
    padding: 10px 24px;
    border-radius: 24px;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.2s;
}
.msg-empty a:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(37,211,102,0.3);
    color: #fff;
    text-decoration: none;
}

/* Divider between items */
.msg-item + .msg-item {
    border-top: 1px solid #f0f0f0;
}

/* Friend count chip */
.msg-friend-count {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #e7f6ef;
    color: #128C7E;
    font-size: 12px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 12px;
}

/* ===== Dark Mode ===== */
body.dark-mode .msg-header h2 { color: #E4E6EB; }
body.dark-mode .msg-search input {
    background: #3A3B3C;
    color: #E4E6EB;
}
body.dark-mode .msg-search input:focus {
    background: #4a4b4d;
}
body.dark-mode .msg-search input::placeholder { color: #888; }
body.dark-mode .msg-search i { color: #888; }
body.dark-mode .msg-item:hover { background: #3A3B3C; }
body.dark-mode .msg-item:active { background: #4a4b4d; }
body.dark-mode .msg-item.unread { background: #1a2e23; }
body.dark-mode .msg-item.unread:hover { background: #243828; }
body.dark-mode .msg-item + .msg-item { border-top-color: #3E4042; }
body.dark-mode .msg-name { color: #E4E6EB; }
body.dark-mode .msg-time { color: #B0B3B8; }
body.dark-mode .msg-item.unread .msg-time { color: #25D366; }
body.dark-mode .msg-preview { color: #B0B3B8; }
body.dark-mode .msg-item.unread .msg-preview { color: #E4E6EB; }
body.dark-mode .msg-online-dot { border-color: #242526; }
body.dark-mode .msg-empty h4 { color: #E4E6EB; }
body.dark-mode .msg-empty p { color: #B0B3B8; }
body.dark-mode .msg-action-archive { background: #3a3520; color: #f0d060; }
body.dark-mode .msg-action-blocked { background: #3a2020; color: #f08080; }
body.dark-mode .msg-friend-count { background: #1a2e23; color: #25D366; }
body.dark-mode .msg-banner.error {
    background: linear-gradient(135deg, #3a2020, #2e1a1a);
    color: #f08080;
    border-color: #5a3030;
}
body.dark-mode .msg-banner.success {
    background: linear-gradient(135deg, #1a2e23, #1a3020);
    color: #6ddb8a;
    border-color: #2a4a30;
}
body.dark-mode .msg-banner.info {
    background: linear-gradient(135deg, #1a2535, #1a2030);
    color: #6db8db;
    border-color: #2a3a4a;
}
body.dark-mode .msg-search-count { color: #777; }

@media (max-width: 576px) {
    .msg-wrapper { padding-bottom: 80px; }
    .msg-header { padding: 14px 14px 0; }
    .msg-search { margin: 0 14px 4px; }
    .msg-list { padding: 4px 6px; }
    .msg-item { padding: 10px 8px; }
    .msg-avatar { width: 48px; height: 48px; }
    .msg-avatar-wrap { margin-right: 12px; }
    .msg-name { font-size: 14px; }
    .msg-header h2 { font-size: 20px; }
    .msg-header-actions a { padding: 5px 10px; font-size: 11px; }
}
</style>

</head>
<body>

<!-- Navbar -->
 @include('layouts.navbar')

@extends('layouts.app')

@section('seo_title', 'Messages - SupperAge')
@section('seo_description', 'Chat with your friends on SupperAge. Send messages, share media, make calls, and stay connected.')

@section('content')

<div class="msg-wrapper">

    {{-- Top Banner Notifications --}}
    @if(session('error'))
    <div class="msg-banner error">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
        <button class="msg-banner-close" onclick="this.parentElement.remove()">&times;</button>
    </div>
    @endif

    @if(session('success'))
    <div class="msg-banner success">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
        <button class="msg-banner-close" onclick="this.parentElement.remove()">&times;</button>
    </div>
    @endif

    @if(session('info'))
    <div class="msg-banner info">
        <i class="fas fa-info-circle"></i>
        <span>{{ session('info') }}</span>
        <button class="msg-banner-close" onclick="this.parentElement.remove()">&times;</button>
    </div>
    @endif

    {{-- Header --}}
    <div class="msg-header">
        <div class="msg-header-top">
            <div style="display:flex;align-items:center;gap:12px;">
                <h2>Chats</h2>
                @if(isset($friendCount) && $friendCount > 0)
                    <span class="msg-friend-count">
                        <i class="fas fa-users"></i> {{ $friendCount }}
                    </span>
                @endif
            </div>
            <div class="msg-header-actions">
                @if(isset($archivedCount) && $archivedCount > 0)
                    <a href="{{ route('messages.archived') }}" class="msg-action-archive">
                        <i class="fas fa-archive"></i> {{ $archivedCount }}
                    </a>
                @endif
                @if(isset($blockedCount) && $blockedCount > 0)
                    <a href="{{ route('users.blocked') }}" class="msg-action-blocked">
                        <i class="fas fa-ban"></i> {{ $blockedCount }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Search Bar --}}
    <div class="msg-search">
        <i class="fas fa-search"></i>
        <input type="text" id="chatSearch" placeholder="Search friends...">
    </div>
    <div class="msg-search-count" id="searchCount"></div>

    {{-- Chat List --}}
    @if($friends->isEmpty())
        <div class="msg-empty">
            <div class="msg-empty-icon">
                <i class="fas fa-comments"></i>
            </div>
            <h4>No conversations yet</h4>
            <p>Add friends to start chatting with them</p>
            <a href="{{ route('friends.index') }}">
                <i class="fas fa-user-plus"></i> Find Friends
            </a>
        </div>
    @else
        <div class="msg-list" id="chatList">
            @foreach($friends as $friend)
                @php
                    $isOnline = $friend->is_online;
                @endphp

                <a href="{{ route('messages.chat', $friend->id) }}"
                   class="msg-item {{ $friend->unread_count > 0 ? 'unread' : '' }}"
                   data-name="{{ strtolower($friend->name) }} {{ strtolower($friend->username ?? '') }}">

                    {{-- Avatar --}}
                    <div class="msg-avatar-wrap">
                        <img src="{{ $friend->profileimg ?? asset('images/best3.png') }}"
                             alt="{{ $friend->name }}" class="msg-avatar">
                        <span class="msg-online-dot {{ $isOnline ? 'online' : 'offline' }}"></span>
                    </div>

                    {{-- Content --}}
                    <div class="msg-content">
                        <div class="msg-name-row">
                            <div class="msg-name">
                                {{ $friend->name }}
                                @if($friend->badge_status)
                                    <img src="{{ asset($friend->badge_status) }}" alt="Verified">
                                @endif
                            </div>
                            @if($friend->last_message)
                                <span class="msg-time">{{ $friend->last_message->created_at->diffForHumans(null, true, true) }}</span>
                            @endif
                        </div>
                        <div class="msg-preview-row">
                            <div class="msg-preview">
                                @if($friend->is_typing)
                                    <span class="msg-typing">typing...</span>
                                @elseif($friend->last_message)
                                    @php
                                        $lastMsg = $friend->last_message;
                                        $isAttachment = !empty($lastMsg->file_path);
                                        $prefix = ($lastMsg->sender_id == $user->id) ? 'You: ' : '';
                                        $icon = '';
                                        $description = '';

                                        if ($isAttachment) {
                                            $files = json_decode($lastMsg->file_path, true);
                                            $fileToCheck = (is_array($files) && count($files) > 0) ? $files[0] : $lastMsg->file_path;
                                            $path = parse_url($fileToCheck, PHP_URL_PATH);
                                            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

                                            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'])) {
                                                $icon = '<i class="fas fa-image msg-attachment-icon"></i>';
                                                $description = 'Photo';
                                            } elseif (in_array($extension, ['mp4', 'mov', 'avi', 'wmv', 'flv', 'webm', 'ogg'])) {
                                                $icon = '<i class="fas fa-video msg-attachment-icon"></i>';
                                                $description = 'Video';
                                            } elseif (in_array($extension, ['mp3', 'wav', 'ogg'])) {
                                                $icon = '<i class="fas fa-microphone msg-attachment-icon"></i>';
                                                $description = 'Audio';
                                            } else {
                                                $icon = '<i class="fas fa-file msg-attachment-icon"></i>';
                                                $description = 'Document';
                                            }
                                        }
                                    @endphp
                                    @if($isAttachment)
                                        {{ $prefix }}{!! $icon !!} {{ $description }}
                                    @else
                                        {{ $prefix }}{{ \Str::limit($lastMsg->message, 45) }}
                                    @endif
                                @else
                                    <span style="color:#bbb;">Tap to start chatting</span>
                                @endif
                            </div>
                            @if($friend->unread_count > 0)
                                <span class="msg-badge">{{ $friend->unread_count }}</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>

@endsection


<!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<!-- Then other jQuery-based scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
<script src="{{ asset('myjs/bar.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>


<script src="{{ asset('myjs/more_lesstext.js') }}"></script>
<script src="{{ asset('myjs/menu_pop_up_post.js') }}"></script>

<script src="{{ asset('myjs/allpost.js') }}"></script>
<script src="{{ asset('myjs/tales.js') }}"></script>
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
<script src="{{ asset('myjs/searchuser.js') }}"></script>

<!-- Chat Search -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.getElementById('chatSearch');
    var searchCount = document.getElementById('searchCount');
    var chatList = document.getElementById('chatList');
    if (!searchInput || !chatList) return;

    searchInput.addEventListener('input', function() {
        var query = this.value.toLowerCase().trim();
        var items = chatList.querySelectorAll('.msg-item');
        var visible = 0;

        items.forEach(function(item) {
            var name = item.getAttribute('data-name') || '';
            if (!query || name.indexOf(query) !== -1) {
                item.style.display = '';
                visible++;
            } else {
                item.style.display = 'none';
            }
        });

        if (query) {
            searchCount.style.display = 'block';
            searchCount.textContent = visible + ' of ' + items.length + ' chats';
        } else {
            searchCount.style.display = 'none';
        }
    });
});
</script>

<!-- Auto-dismiss banners after 6 seconds -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.msg-banner').forEach(function(banner) {
        setTimeout(function() {
            banner.style.transition = 'opacity 0.4s, transform 0.4s';
            banner.style.opacity = '0';
            banner.style.transform = 'translateY(-10px)';
            setTimeout(function() { banner.remove(); }, 400);
        }, 6000);
    });
});
</script>


<!-- incoming calls alert -->

@if (isset($user) && $user)

<!-- Sound Enable Banner -->
<div id="enableSoundBanner" style="display:none; position:fixed; top:60px; left:0; right:0; background:#ff9800; color:white; padding:15px; text-align:center; z-index:9999; box-shadow:0 2px 10px rgba(0,0,0,0.3);">
    <strong>Enable call ringtone to hear incoming calls</strong>
    <button onclick="enableCallSound()" style="margin-left:15px; padding:8px 20px; background:white; color:#ff9800; border:none; border-radius:5px; font-weight:bold; cursor:pointer;">
        Enable Sound
    </button>
    <button onclick="dismissSoundBanner()" style="margin-left:10px; padding:8px 15px; background:transparent; color:white; border:1px solid white; border-radius:5px; cursor:pointer;">
        Dismiss
    </button>
</div>

<script>
    const currentUserId = {{ $user->id }};

    let ringtone = null;
    let soundEnabled = false;
    let notificationPermission = false;

    // Request notification permission on page load
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission().then(permission => {
            notificationPermission = permission === 'granted';
        });
    } else if ('Notification' in window && Notification.permission === 'granted') {
        notificationPermission = true;
    }

    function enableCallSound() {
        const testAudio = new Audio('{{ asset("sounds/mixkit-game-success-alert-2039.wav") }}');
        testAudio.play()
            .then(() => {
                soundEnabled = true;
                testAudio.pause();
                testAudio.currentTime = 0;
                document.getElementById('enableSoundBanner').style.display = 'none';
                localStorage.setItem('callSoundEnabled', 'true');
            })
            .catch(err => {
                console.error('Could not enable sound:', err);
            });
    }

    function dismissSoundBanner() {
        document.getElementById('enableSoundBanner').style.display = 'none';
        localStorage.setItem('soundBannerDismissed', 'true');
    }

    function playRingtone() {
        if (!soundEnabled) return false;
        try {
            ringtone = new Audio('{{ asset("sounds/mixkit-game-success-alert-2039.wav") }}');
            ringtone.loop = true;
            ringtone.volume = 1.0;
            var playPromise = ringtone.play();
            if (playPromise !== undefined) {
                playPromise.catch(function() {
                    document.getElementById('enableSoundBanner').style.display = 'block';
                });
            }
            return true;
        } catch (error) {
            return false;
        }
    }

    function stopRingtone() {
        if (ringtone) {
            ringtone.pause();
            ringtone.currentTime = 0;
            ringtone = null;
        }
    }

    function showNotification(callerName, callType) {
        if (notificationPermission && 'Notification' in window) {
            var notification = new Notification('Incoming ' + callType + ' Call', {
                body: callerName + ' is calling you',
                icon: '{{ asset("images/favicon-32x32.png") }}',
                tag: 'incoming-call',
                requireInteraction: true
            });
            notification.onclick = function() { window.focus(); notification.close(); };
            return notification;
        }
        return null;
    }

    function handleIncomingCall(callId, callerName, callType, callerId) {
        var soundPlayed = playRingtone();
        var notification = showNotification(callerName, callType);

        var isConfirmed = confirm(
            'Incoming ' + callType.toUpperCase() + ' Call from ' + callerName + '!\n\nClick OK to answer or Cancel to decline.'
        );

        stopRingtone();
        if (notification) notification.close();

        if (isConfirmed) {
            window.location.href = '/calls/' + callId;
        } else {
            fetch('/calls/' + callId + '/decline', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ declined_by: currentUserId })
            }).catch(function(err) { console.error('Failed to notify call decline:', err); });
        }
    }

    // Initialize Echo listener
    document.addEventListener('DOMContentLoaded', function() {
        if (localStorage.getItem('callSoundEnabled') === 'true') {
            soundEnabled = true;
        } else if (localStorage.getItem('soundBannerDismissed') !== 'true') {
            document.getElementById('enableSoundBanner').style.display = 'block';
        }

        if (typeof window.Echo !== 'undefined') {
            window.Echo.private('users.' + currentUserId)
                .listen('IncomingCallEvent', function(e) {
                    handleIncomingCall(e.callId, e.callerName, e.callType, e.callerId);
                })
                .error(function(error) {
                    console.error('Echo Subscription Error:', error);
                });
        }
    });

    window.addEventListener('beforeunload', function() { stopRingtone(); });
</script>
@endif

</body>
</html>
