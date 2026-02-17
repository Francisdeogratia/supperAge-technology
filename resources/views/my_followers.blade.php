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

    <title>My Followers</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <!-- Your Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/finebtn.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">
    <link rel="stylesheet" href="{{ asset('css/darkmode.css') }}">

    <style>
    .followers-page {
        max-width: 680px;
        margin: 20px auto;
        padding: 0 16px;
    }

    /* Tabs */
    .followers-tabs {
        display: flex;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 16px;
        overflow: hidden;
    }
    .followers-tab {
        flex: 1;
        padding: 14px 16px;
        text-align: center;
        font-size: 14px;
        font-weight: 600;
        color: #65676b;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        transition: all 0.2s;
        background: none;
        border-top: none;
        border-left: none;
        border-right: none;
    }
    .followers-tab:hover { background: #f0f2f5; }
    .followers-tab.active {
        color: #1877f2;
        border-bottom-color: #1877f2;
    }
    .followers-tab i { margin-right: 6px; }
    .followers-tab .tab-count {
        display: inline-block;
        background: #e4e6eb;
        color: #050505;
        font-size: 12px;
        padding: 2px 8px;
        border-radius: 10px;
        margin-left: 6px;
    }
    .followers-tab.active .tab-count {
        background: #e7f3ff;
        color: #1877f2;
    }

    /* Search */
    .followers-search-wrap {
        position: relative;
        margin-bottom: 12px;
    }
    .followers-search-wrap i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #65676b;
        font-size: 14px;
    }
    .followers-search {
        width: 100%;
        padding: 10px 14px 10px 40px;
        border: none;
        border-radius: 20px;
        background: #f0f2f5;
        font-size: 14px;
        outline: none;
        transition: background 0.2s, box-shadow 0.2s;
    }
    .followers-search:focus {
        background: #fff;
        box-shadow: 0 0 0 2px #1877f2;
    }

    /* Section panels */
    .followers-panel {
        display: none;
    }
    .followers-panel.active {
        display: block;
    }

    /* User card */
    .follower-card {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        background: #fff;
        border-radius: 10px;
        margin-bottom: 8px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        transition: background 0.15s;
    }
    .follower-card:hover {
        background: #f0f2f5;
    }

    /* Avatar with online indicator */
    .follower-avatar-wrap {
        position: relative;
        flex-shrink: 0;
        margin-right: 12px;
    }
    .follower-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e4e6eb;
    }
    .follower-online-dot {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #31a24c;
        border: 2px solid #fff;
    }
    .follower-offline-dot {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #bec3c9;
        border: 2px solid #fff;
    }

    /* User info */
    .follower-info {
        flex: 1;
        min-width: 0;
    }
    .follower-name {
        font-size: 14px;
        font-weight: 600;
        color: #050505;
        text-decoration: none;
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .follower-name:hover { color: #1877f2; text-decoration: none; }
    .follower-status {
        font-size: 12px;
        color: #65676b;
        margin-top: 2px;
    }
    .follower-status.online {
        color: #31a24c;
        font-weight: 600;
    }

    /* Action buttons */
    .follower-actions {
        flex-shrink: 0;
        margin-left: 10px;
    }
    .btn-follow-modern {
        padding: 7px 20px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-follow-primary {
        background: #1877f2;
        color: #fff;
    }
    .btn-follow-primary:hover {
        background: #1565c0;
        transform: scale(1.03);
    }
    .btn-follow-secondary {
        background: #e4e6eb;
        color: #050505;
    }
    .btn-follow-secondary:hover {
        background: #d8dadf;
    }
    .btn-follow-danger {
        background: #ffece8;
        color: #e4405f;
    }
    .btn-follow-danger:hover {
        background: #fdd;
    }

    /* Empty state */
    .followers-empty {
        text-align: center;
        padding: 40px 20px;
        color: #65676b;
    }
    .followers-empty i {
        font-size: 48px;
        color: #bec3c9;
        margin-bottom: 12px;
        display: block;
    }
    .followers-empty p {
        font-size: 15px;
        font-weight: 500;
    }

    /* Toast popup */
    .follower-toast {
        position: fixed;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%) translateY(100px);
        background: #050505;
        color: #fff;
        padding: 12px 24px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        z-index: 9999;
        opacity: 0;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    .follower-toast.show {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }
    .follower-toast.error { background: #e4405f; }

    /* Dark mode */
    body.dark-mode .followers-tabs { background: #242526; }
    body.dark-mode .followers-tab { color: #B0B3B8; }
    body.dark-mode .followers-tab:hover { background: #3A3B3C; }
    body.dark-mode .followers-tab.active { color: #2D88FF; border-bottom-color: #2D88FF; }
    body.dark-mode .followers-tab .tab-count { background: #3A3B3C; color: #E4E6EB; }
    body.dark-mode .followers-tab.active .tab-count { background: #263c5a; color: #2D88FF; }
    body.dark-mode .followers-search { background: #3A3B3C; color: #E4E6EB; }
    body.dark-mode .followers-search:focus { background: #242526; box-shadow: 0 0 0 2px #2D88FF; }
    body.dark-mode .followers-search-wrap i { color: #B0B3B8; }
    body.dark-mode .follower-card { background: #242526; }
    body.dark-mode .follower-card:hover { background: #3A3B3C; }
    body.dark-mode .follower-avatar { border-color: #3E4042; }
    body.dark-mode .follower-online-dot { border-color: #242526; }
    body.dark-mode .follower-offline-dot { border-color: #242526; background: #4E4F50; }
    body.dark-mode .follower-name { color: #E4E6EB; }
    body.dark-mode .follower-name:hover { color: #2D88FF; }
    body.dark-mode .follower-status { color: #B0B3B8; }
    body.dark-mode .btn-follow-secondary { background: #3A3B3C; color: #E4E6EB; }
    body.dark-mode .btn-follow-secondary:hover { background: #4E4F50; }
    body.dark-mode .btn-follow-danger { background: #3A2020; color: #ff6b6b; }
    body.dark-mode .btn-follow-danger:hover { background: #4a2525; }
    body.dark-mode .followers-empty { color: #B0B3B8; }
    body.dark-mode .followers-empty i { color: #4E4F50; }

    @media (max-width: 600px) {
        .followers-page { margin: 10px auto; padding: 0 10px; }
        .follower-card { padding: 10px 12px; }
        .follower-avatar { width: 42px; height: 42px; }
        .btn-follow-modern { padding: 6px 14px; font-size: 12px; }
        .followers-tab { font-size: 13px; padding: 12px 10px; }
    }
    </style>
</head>
<body>
    @include('layouts.navbar')

<div class="followers-page">

    <!-- Tabs -->
    <div class="followers-tabs">
        <button class="followers-tab active" data-panel="followers-panel">
            <i class="fas fa-user-friends"></i> Followers
            <span class="tab-count">{{ $followers->count() }}</span>
        </button>
        <button class="followers-tab" data-panel="following-panel">
            <i class="fas fa-user-plus"></i> Following
            <span class="tab-count">{{ $following->count() }}</span>
        </button>
    </div>

    <!-- Followers Panel -->
    <div id="followers-panel" class="followers-panel active">
        <div class="followers-search-wrap">
            <i class="fas fa-search"></i>
            <input type="text" id="searchFollowers" class="followers-search" placeholder="Search followers...">
        </div>

        @if($followers->isEmpty())
            <div class="followers-empty">
                <i class="fas fa-user-friends"></i>
                <p>You have no followers yet</p>
            </div>
        @else
            <div data-list="followers">
                @foreach($followers as $follower)
                @php
                    $isOnline = $follower->is_online;
                    $lastSeen = $isOnline ? null : $follower->last_seen;
                @endphp
                <div class="follower-card" data-name="{{ strtolower($follower->name) }}">
                    <div class="follower-avatar-wrap">
                        <img src="{{ $follower->profileimg ?? asset('images/best3.png') }}" alt="{{ $follower->name }}" class="follower-avatar">
                        <div class="{{ $isOnline ? 'follower-online-dot' : 'follower-offline-dot' }}"></div>
                    </div>
                    <div class="follower-info">
                        <a href="{{ url('/profile/' . $follower->id) }}" class="follower-name">{{ $follower->name }}</a>
                        <div class="follower-status {{ $isOnline ? 'online' : '' }}">
                            {{ $isOnline ? 'Online now' : ($lastSeen && $lastSeen !== 'Never' ? 'Last seen ' . $lastSeen : 'Offline') }}
                        </div>
                    </div>
                    <div class="follower-actions">
                        @if($follower->isFollowing)
                            <button type="button"
                                class="btn-follow-modern btn-follow-secondary follow-action-btn"
                                data-user-id="{{ $follower->id }}"
                                data-action="unfollow">
                                Following
                            </button>
                        @else
                            <button type="button"
                                class="btn-follow-modern btn-follow-primary follow-action-btn"
                                data-user-id="{{ $follower->id }}"
                                data-action="follow">
                                Follow Back
                            </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Following Panel -->
    <div id="following-panel" class="followers-panel">
        <div class="followers-search-wrap">
            <i class="fas fa-search"></i>
            <input type="text" id="searchFollowing" class="followers-search" placeholder="Search following...">
        </div>

        @if($following->isEmpty())
            <div class="followers-empty">
                <i class="fas fa-user-plus"></i>
                <p>You're not following anyone yet</p>
            </div>
        @else
            <div data-list="following">
                @foreach($following as $followed)
                @php
                    $isOnline = $followed->is_online;
                    $lastSeen = $isOnline ? null : $followed->last_seen;
                @endphp
                <div class="follower-card" data-name="{{ strtolower($followed->name) }}">
                    <div class="follower-avatar-wrap">
                        <img src="{{ $followed->profileimg ?? asset('images/best3.png') }}" alt="{{ $followed->name }}" class="follower-avatar">
                        <div class="{{ $isOnline ? 'follower-online-dot' : 'follower-offline-dot' }}"></div>
                    </div>
                    <div class="follower-info">
                        <a href="{{ url('/profile/' . $followed->id) }}" class="follower-name">{{ $followed->name }}</a>
                        <div class="follower-status {{ $isOnline ? 'online' : '' }}">
                            {{ $isOnline ? 'Online now' : ($lastSeen && $lastSeen !== 'Never' ? 'Last seen ' . $lastSeen : 'Offline') }}
                        </div>
                    </div>
                    <div class="follower-actions">
                        <button type="button"
                            class="btn-follow-modern btn-follow-danger follow-action-btn"
                            data-user-id="{{ $followed->id }}"
                            data-action="unfollow">
                            Unfollow
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

<!-- Toast -->
<div id="followerToast" class="follower-toast"></div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
<script src="{{ asset('myjs/bar.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script src="{{ asset('myjs/more_lesstext.js') }}"></script>
<script src="{{ asset('myjs/menu_pop_up_post.js') }}"></script>
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>

<script>
$.ajaxSetup({
  headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
});
</script>

<script src="{{ asset('myjs/allpost.js') }}"></script>
<script src="{{ asset('myjs/tales.js') }}"></script>
<script src="{{ asset('myjs/searchuser.js') }}"></script>

<script>
$(document).ready(function() {
    // Tab switching
    $('.followers-tab').on('click', function() {
        $('.followers-tab').removeClass('active');
        $(this).addClass('active');
        $('.followers-panel').removeClass('active');
        $('#' + $(this).data('panel')).addClass('active');
    });

    // Search followers
    $('#searchFollowers').on('keyup', function() {
        const term = $(this).val().toLowerCase();
        $('[data-list="followers"] .follower-card').each(function() {
            $(this).toggle($(this).data('name').includes(term));
        });
    });

    // Search following
    $('#searchFollowing').on('keyup', function() {
        const term = $(this).val().toLowerCase();
        $('[data-list="following"] .follower-card').each(function() {
            $(this).toggle($(this).data('name').includes(term));
        });
    });

    // Toast helper
    function showToast(msg, isError) {
        const toast = $('#followerToast');
        toast.text(msg).removeClass('error');
        if (isError) toast.addClass('error');
        toast.addClass('show');
        setTimeout(() => toast.removeClass('show'), 2500);
    }

    // Follow/Unfollow actions
    $(document).on('click', '.follow-action-btn', function(e) {
        e.preventDefault();
        const btn = $(this);
        const userId = btn.data('user-id');
        const action = btn.data('action');
        const url = action === 'follow' ? `/follow/${userId}` : `/unfollow/${userId}`;

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success || data.status === 'success') {
                if (action === 'follow') {
                    btn.text('Following')
                       .removeClass('btn-follow-primary')
                       .addClass('btn-follow-secondary')
                       .data('action', 'unfollow');
                } else {
                    btn.text('Follow Back')
                       .removeClass('btn-follow-secondary btn-follow-danger')
                       .addClass('btn-follow-primary')
                       .data('action', 'follow');
                }
                showToast(data.message || 'Done!', false);

                const audio = new Audio("{{ asset('sounds/mixkit-fantasy-game-success-notification-270.wav') }}");
                audio.play().catch(() => {});
            } else {
                showToast(data.error || 'Something went wrong', true);
            }
        })
        .catch(() => showToast('Request failed. Try again.', true));
    });
});
</script>
</body>
</html>
