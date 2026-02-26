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

    <title>Mutual Followers</title>

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
    .mutual-page {
        max-width: 680px;
        margin: 20px auto;
        padding: 0 16px;
    }

    /* Header card */
    .mutual-header {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .mutual-header-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1877f2, #42a5f5);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .mutual-header-icon i {
        color: #fff;
        font-size: 20px;
    }
    .mutual-header-text h4 {
        font-size: 16px;
        font-weight: 700;
        color: #050505;
        margin: 0 0 4px 0;
    }
    .mutual-header-text .mutual-count-badge {
        display: inline-block;
        background: #e7f3ff;
        color: #1877f2;
        font-size: 12px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 10px;
    }

    /* Search */
    .mutual-search-wrap {
        position: relative;
        margin-bottom: 12px;
    }
    .mutual-search-wrap i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #65676b;
        font-size: 14px;
    }
    .mutual-search {
        width: 100%;
        padding: 10px 14px 10px 40px;
        border: none;
        border-radius: 20px;
        background: #f0f2f5;
        font-size: 14px;
        outline: none;
        transition: background 0.2s, box-shadow 0.2s;
    }
    .mutual-search:focus {
        background: #fff;
        box-shadow: 0 0 0 2px #1877f2;
    }

    /* User card */
    .mutual-card {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        background: #fff;
        border-radius: 10px;
        margin-bottom: 8px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        transition: background 0.15s;
    }
    .mutual-card:hover { background: #f0f2f5; }

    .mutual-avatar-wrap {
        position: relative;
        flex-shrink: 0;
        margin-right: 12px;
    }
    .mutual-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e4e6eb;
    }
    .mutual-online-dot {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #31a24c;
        border: 2px solid #fff;
    }
    .mutual-offline-dot {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #bec3c9;
        border: 2px solid #fff;
    }

    .mutual-info {
        flex: 1;
        min-width: 0;
    }
    .mutual-name-row {
        display: flex;
        align-items: center;
        gap: 5px;
        min-width: 0;
    }
    .mutual-name {
        font-size: 14px;
        font-weight: 600;
        color: #050505;
        text-decoration: none;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .mutual-name:hover { color: #1877f2; text-decoration: none; }
    .mutual-badge {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
        vertical-align: middle;
    }
    .mutual-status {
        font-size: 12px;
        color: #65676b;
        margin-top: 2px;
    }
    .mutual-status.online {
        color: #31a24c;
        font-weight: 600;
    }

    .mutual-actions { flex-shrink: 0; margin-left: 10px; }
    .btn-mutual {
        padding: 7px 20px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-mutual-primary { background: #1877f2; color: #fff; }
    .btn-mutual-primary:hover { background: #1565c0; transform: scale(1.03); }
    .btn-mutual-secondary { background: #e4e6eb; color: #050505; }
    .btn-mutual-secondary:hover { background: #d8dadf; }

    .mutual-empty {
        text-align: center;
        padding: 40px 20px;
        color: #65676b;
    }
    .mutual-empty i {
        font-size: 48px;
        color: #bec3c9;
        margin-bottom: 12px;
        display: block;
    }

    /* Toast */
    .mutual-toast {
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
    .mutual-toast.show {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }
    .mutual-toast.error { background: #e4405f; }

    /* Dark mode */
    body.dark-mode .mutual-header { background: #242526; }
    body.dark-mode .mutual-header-text h4 { color: #E4E6EB; }
    body.dark-mode .mutual-header-text .mutual-count-badge { background: #263c5a; color: #2D88FF; }
    body.dark-mode .mutual-search { background: #3A3B3C; color: #E4E6EB; }
    body.dark-mode .mutual-search:focus { background: #242526; box-shadow: 0 0 0 2px #2D88FF; }
    body.dark-mode .mutual-search-wrap i { color: #B0B3B8; }
    body.dark-mode .mutual-card { background: #242526; }
    body.dark-mode .mutual-card:hover { background: #3A3B3C; }
    body.dark-mode .mutual-avatar { border-color: #3E4042; }
    body.dark-mode .mutual-online-dot { border-color: #242526; }
    body.dark-mode .mutual-offline-dot { border-color: #242526; background: #4E4F50; }
    body.dark-mode .mutual-name { color: #E4E6EB; }
    body.dark-mode .mutual-name:hover { color: #2D88FF; }
    body.dark-mode .mutual-status { color: #B0B3B8; }
    body.dark-mode .btn-mutual-secondary { background: #3A3B3C; color: #E4E6EB; }
    body.dark-mode .btn-mutual-secondary:hover { background: #4E4F50; }
    body.dark-mode .mutual-empty { color: #B0B3B8; }
    body.dark-mode .mutual-empty i { color: #4E4F50; }

    @media (max-width: 600px) {
        .mutual-page { margin: 10px auto; padding: 0 10px; }
        .mutual-card { padding: 10px 12px; }
        .mutual-avatar { width: 42px; height: 42px; }
        .btn-mutual { padding: 6px 14px; font-size: 12px; }
        .mutual-header { padding: 16px; }
    }
    </style>
</head>
<body>
    @include('layouts.navbar')

<div class="mutual-page">

    <!-- Header -->
    <div class="mutual-header">
        <div class="mutual-header-icon">
            <i class="fas fa-people-arrows"></i>
        </div>
        <div class="mutual-header-text">
            <h4>Mutual Followers with {{ $otherUser->name }}</h4>
            <span class="mutual-count-badge">
                <i class="fas fa-users"></i> {{ $mutualCount }} {{ Str::plural('Follower', $mutualCount) }}
            </span>
        </div>
    </div>

    <!-- Search -->
    <div class="mutual-search-wrap">
        <i class="fas fa-search"></i>
        <input type="text" id="searchMutual" class="mutual-search" placeholder="Search mutual followers...">
    </div>

    <!-- List -->
    @if($mutualFollowers->isEmpty())
        <div class="mutual-empty">
            <i class="fas fa-user-friends"></i>
            <p>No mutual followers yet</p>
        </div>
    @else
        <div id="mutualList">
            @foreach($mutualFollowers as $follower)
            @php
                $isOnline = $follower->is_online;
                $lastSeen = $isOnline ? null : $follower->last_seen;
            @endphp
            <div class="mutual-card" data-name="{{ strtolower($follower->name) }}">
                <div class="mutual-avatar-wrap">
                    <img src="{{ $follower->profileimg ?? asset('images/best3.png') }}" alt="{{ $follower->name }}" class="mutual-avatar">
                    <div class="{{ $isOnline ? 'mutual-online-dot' : 'mutual-offline-dot' }}"></div>
                </div>
                <div class="mutual-info">
                    <div class="mutual-name-row">
                        <a href="{{ url('/profile/' . $follower->id) }}" class="mutual-name">{{ $follower->name }}</a>
                        @if($follower->badge_status)
                            <img src="{{ asset($follower->badge_status) }}" class="mutual-badge" alt="Verified" title="Verified">
                        @endif
                    </div>
                    <div class="mutual-status {{ $isOnline ? 'online' : '' }}">
                        {{ $isOnline ? 'Online now' : ($lastSeen && $lastSeen !== 'Never' ? 'Last seen ' . $lastSeen : 'Offline') }}
                    </div>
                </div>
                @if($follower->id !== Session::get('id'))
                <div class="mutual-actions">
                    <button type="button"
                        class="btn-mutual {{ $follower->isFollowing ? 'btn-mutual-secondary' : 'btn-mutual-primary' }} follow-action-btn"
                        data-user-id="{{ $follower->id }}"
                        data-action="{{ $follower->isFollowing ? 'unfollow' : 'follow' }}">
                        {{ $follower->isFollowing ? 'Following' : 'Follow' }}
                    </button>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    @endif

</div>

<!-- Toast -->
<div id="mutualToast" class="mutual-toast"></div>

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
    // Search
    $('#searchMutual').on('keyup', function() {
        const term = $(this).val().toLowerCase();
        $('#mutualList .mutual-card').each(function() {
            $(this).toggle($(this).data('name').includes(term));
        });
    });

    // Toast helper
    function showToast(msg, isError) {
        const toast = $('#mutualToast');
        toast.text(msg).removeClass('error');
        if (isError) toast.addClass('error');
        toast.addClass('show');
        setTimeout(() => toast.removeClass('show'), 2500);
    }

    // Follow/Unfollow
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
                       .removeClass('btn-mutual-primary')
                       .addClass('btn-mutual-secondary')
                       .data('action', 'unfollow');
                } else {
                    btn.text('Follow')
                       .removeClass('btn-mutual-secondary')
                       .addClass('btn-mutual-primary')
                       .data('action', 'follow');
                }
                showToast(data.message || 'Done!', false);
            } else {
                showToast(data.error || 'Something went wrong', true);
            }
        })
        .catch(() => showToast('Request failed. Try again.', true));
    });
});
</script>

@include('partials.global-calls')
</body>
</html>
