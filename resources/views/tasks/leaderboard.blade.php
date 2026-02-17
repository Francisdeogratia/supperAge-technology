

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

    <title>Leaderboard - SupperAge</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">

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

    <style>
        .lb-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px 15px 100px;
        }

        /* Page Header */
        .lb-header {
            text-align: center;
            margin-bottom: 24px;
        }
        .lb-header h2 {
            font-size: 24px;
            font-weight: 700;
            color: #1a1a2e;
            margin: 0;
        }
        .lb-header p {
            color: #888;
            font-size: 14px;
            margin: 4px 0 0;
        }

        /* Daily Progress Card */
        .lb-progress-card {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 16px;
            padding: 20px;
            color: #fff;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }
        .lb-progress-card::after {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 120px;
            height: 120px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
        }
        .lb-progress-card h5 {
            font-size: 14px;
            font-weight: 600;
            opacity: 0.9;
            margin-bottom: 8px;
        }
        .lb-progress-card .lb-amount {
            font-size: 28px;
            font-weight: 700;
        }
        .lb-progress-card .lb-amount span {
            font-size: 14px;
            opacity: 0.8;
            font-weight: 400;
        }
        .lb-progress-bar-wrap {
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            height: 10px;
            margin-top: 14px;
            overflow: hidden;
        }
        .lb-progress-bar-fill {
            background: #fff;
            height: 100%;
            border-radius: 10px;
            transition: width 0.6s ease;
        }

        /* Filter Tabs */
        .lb-filters {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .lb-filter-btn {
            padding: 8px 20px;
            border-radius: 20px;
            border: 2px solid #e0e0e0;
            background: #fff;
            color: #555;
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.2s;
        }
        .lb-filter-btn:hover {
            border-color: #667eea;
            color: #667eea;
            text-decoration: none;
        }
        .lb-filter-btn.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            border-color: transparent;
        }
        .lb-filter-btn.active:hover {
            color: #fff;
        }

        /* Top 3 Podium */
        .lb-podium {
            display: flex;
            justify-content: center;
            align-items: flex-end;
            gap: 12px;
            margin-bottom: 24px;
            padding: 0 10px;
        }
        .lb-podium-item {
            text-align: center;
            flex: 1;
            max-width: 160px;
        }
        .lb-podium-item .lb-podium-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e0e0e0;
            margin-bottom: 8px;
        }
        .lb-podium-item.first .lb-podium-avatar {
            width: 76px;
            height: 76px;
            border-color: #FFD700;
            box-shadow: 0 0 16px rgba(255,215,0,0.4);
        }
        .lb-podium-item.second .lb-podium-avatar {
            width: 64px;
            height: 64px;
            border-color: #C0C0C0;
        }
        .lb-podium-item.third .lb-podium-avatar {
            width: 64px;
            height: 64px;
            border-color: #CD7F32;
        }
        .lb-podium-medal {
            font-size: 22px;
            display: block;
            margin-bottom: 4px;
        }
        .lb-podium-name {
            font-size: 13px;
            font-weight: 600;
            color: #333;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .lb-podium-reward {
            font-size: 12px;
            color: #667eea;
            font-weight: 700;
        }
        .lb-podium-base {
            border-radius: 12px 12px 0 0;
            padding: 10px 8px 8px;
            margin-top: 8px;
        }
        .lb-podium-item.first .lb-podium-base {
            background: linear-gradient(180deg, #fff9e6, #fff3cc);
            min-height: 90px;
        }
        .lb-podium-item.second .lb-podium-base {
            background: linear-gradient(180deg, #f5f5f5, #e8e8e8);
            min-height: 70px;
        }
        .lb-podium-item.third .lb-podium-base {
            background: linear-gradient(180deg, #fdf0e6, #fbe4d0);
            min-height: 60px;
        }

        /* Leaderboard List */
        .lb-list {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        }
        .lb-list-item {
            display: flex;
            align-items: center;
            padding: 14px 18px;
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.2s;
        }
        .lb-list-item:last-child {
            border-bottom: none;
        }
        .lb-list-item:hover {
            background: #f8f9ff;
        }
        .lb-list-item.my-rank {
            background: linear-gradient(135deg, #eef2ff, #e8ecff);
            border-left: 4px solid #667eea;
        }
        .lb-rank {
            width: 36px;
            font-size: 15px;
            font-weight: 700;
            color: #aaa;
            text-align: center;
            flex-shrink: 0;
        }
        .lb-rank.top { color: #667eea; }
        .lb-user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 14px;
            flex-shrink: 0;
            border: 2px solid #e8e8e8;
        }
        .lb-user-info {
            flex: 1;
            min-width: 0;
        }
        .lb-user-name {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a2e;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .lb-user-name img {
            width: 16px;
            height: 16px;
            margin-left: 4px;
            vertical-align: middle;
        }
        .lb-user-status {
            font-size: 11px;
            color: #aaa;
        }
        .lb-user-status .online-dot {
            display: inline-block;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #4caf50;
            margin-right: 4px;
            animation: dotPulse 2s infinite;
        }
        @keyframes dotPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
        .lb-user-status.online {
            color: #4caf50;
        }
        .lb-reward {
            font-size: 14px;
            font-weight: 700;
            color: #333;
            flex-shrink: 0;
            text-align: right;
        }
        .lb-reward small {
            font-weight: 400;
            color: #999;
            font-size: 11px;
        }

        /* Empty state */
        .lb-empty {
            text-align: center;
            padding: 50px 20px;
            color: #aaa;
        }
        .lb-empty i {
            font-size: 48px;
            margin-bottom: 12px;
            display: block;
        }

        /* Dark mode */
        body.dark-mode .lb-header h2 { color: #E4E6EB; }
        body.dark-mode .lb-header p { color: #B0B3B8; }
        body.dark-mode .lb-filter-btn {
            background: #242526;
            border-color: #3E4042;
            color: #B0B3B8;
        }
        body.dark-mode .lb-filter-btn:hover {
            border-color: #667eea;
            color: #667eea;
        }
        body.dark-mode .lb-filter-btn.active {
            border-color: transparent;
            color: #fff;
        }
        body.dark-mode .lb-list {
            background: #242526;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        body.dark-mode .lb-list-item {
            border-bottom-color: #3E4042;
        }
        body.dark-mode .lb-list-item:hover {
            background: #3A3B3C;
        }
        body.dark-mode .lb-list-item.my-rank {
            background: linear-gradient(135deg, #2a2d45, #2d2f48);
            border-left-color: #667eea;
        }
        body.dark-mode .lb-user-name { color: #E4E6EB; }
        body.dark-mode .lb-reward { color: #E4E6EB; }
        body.dark-mode .lb-reward small { color: #B0B3B8; }
        body.dark-mode .lb-rank { color: #666; }
        body.dark-mode .lb-rank.top { color: #667eea; }
        body.dark-mode .lb-podium-name { color: #E4E6EB; }
        body.dark-mode .lb-podium-item.first .lb-podium-base {
            background: linear-gradient(180deg, #3a3520, #2e2a18);
        }
        body.dark-mode .lb-podium-item.second .lb-podium-base {
            background: linear-gradient(180deg, #2e2e2e, #252525);
        }
        body.dark-mode .lb-podium-item.third .lb-podium-base {
            background: linear-gradient(180deg, #362e24, #2c2520);
        }
        body.dark-mode .lb-empty { color: #666; }

        @media (max-width: 576px) {
            .lb-podium { gap: 8px; }
            .lb-podium-item.first .lb-podium-avatar { width: 60px; height: 60px; }
            .lb-podium-item.second .lb-podium-avatar,
            .lb-podium-item.third .lb-podium-avatar { width: 50px; height: 50px; }
            .lb-podium-name { font-size: 11px; }
            .lb-list-item { padding: 12px 14px; }
            .lb-user-avatar { width: 36px; height: 36px; margin: 0 10px; }
        }
    </style>

</head>
<body>
<!-- Your msg  navbar content  -->

 @include('layouts.navbar')


@extends('layouts.app')

@section('content')

<div class="lb-container">
    <div class="lb-header">
        <h2><i class="fas fa-trophy" style="color: #FFD700;"></i> Task Leaderboard</h2>
        <p>See who's earning the most rewards</p>
    </div>

    {{-- Daily Progress --}}
    @if($user)
    <div class="lb-progress-card">
        <h5><i class="fas fa-chart-line"></i> Your Daily Progress</h5>
        <div class="lb-amount">
            {{ $todayEarnings }} <span>/ {{ $dailyGoal }} coins today</span>
        </div>
        <div class="lb-progress-bar-wrap">
            <div class="lb-progress-bar-fill" style="width: {{ min(100, ($todayEarnings / max($dailyGoal, 1)) * 100) }}%;"></div>
        </div>
    </div>
    @endif

    {{-- Filter buttons --}}
    <div class="lb-filters">
        <a href="{{ route('tasks.leaderboard', ['period' => 'all']) }}"
           class="lb-filter-btn {{ $period === 'all' ? 'active' : '' }}">All Time</a>
        <a href="{{ route('tasks.leaderboard', ['period' => 'week']) }}"
           class="lb-filter-btn {{ $period === 'week' ? 'active' : '' }}">This Week</a>
        <a href="{{ route('tasks.leaderboard', ['period' => 'today']) }}"
           class="lb-filter-btn {{ $period === 'today' ? 'active' : '' }}">Today</a>
    </div>

    {{-- Top 3 Podium --}}
    @if($leaders->count() >= 3)
    <div class="lb-podium">
        {{-- 2nd Place --}}
        <div class="lb-podium-item second">
            <span class="lb-podium-medal">&#129352;</span>
            <img src="{{ $leaders[1]->profileimg ?? asset('images/default-avatar.png') }}"
                 alt="{{ $leaders[1]->username ?? $leaders[1]->name }}" class="lb-podium-avatar">
            <div class="lb-podium-base">
                <div class="lb-podium-name">{{ $leaders[1]->username ?? $leaders[1]->name }}</div>
                <div class="lb-podium-reward">{{ $leaders[1]->total_task_rewards ?? 0 }} NGN</div>
            </div>
        </div>
        {{-- 1st Place --}}
        <div class="lb-podium-item first">
            <span class="lb-podium-medal">&#129351;</span>
            <img src="{{ $leaders[0]->profileimg ?? asset('images/default-avatar.png') }}"
                 alt="{{ $leaders[0]->username ?? $leaders[0]->name }}" class="lb-podium-avatar">
            <div class="lb-podium-base">
                <div class="lb-podium-name">{{ $leaders[0]->username ?? $leaders[0]->name }}</div>
                <div class="lb-podium-reward">{{ $leaders[0]->total_task_rewards ?? 0 }} NGN</div>
            </div>
        </div>
        {{-- 3rd Place --}}
        <div class="lb-podium-item third">
            <span class="lb-podium-medal">&#129353;</span>
            <img src="{{ $leaders[2]->profileimg ?? asset('images/default-avatar.png') }}"
                 alt="{{ $leaders[2]->username ?? $leaders[2]->name }}" class="lb-podium-avatar">
            <div class="lb-podium-base">
                <div class="lb-podium-name">{{ $leaders[2]->username ?? $leaders[2]->name }}</div>
                <div class="lb-podium-reward">{{ $leaders[2]->total_task_rewards ?? 0 }} NGN</div>
            </div>
        </div>
    </div>
    @endif

    {{-- Leaderboard List --}}
    <div class="lb-list">
        @forelse($leaders as $index => $leader)
        <div class="lb-list-item {{ $user && $leader->id === $user->id ? 'my-rank' : '' }}">
            <div class="lb-rank {{ $index < 3 ? 'top' : '' }}">
                @if($index === 0) &#129351;
                @elseif($index === 1) &#129352;
                @elseif($index === 2) &#129353;
                @else {{ $index + 1 }}
                @endif
            </div>
            <img src="{{ $leader->profileimg ?? asset('images/default-avatar.png') }}"
                 alt="{{ $leader->username ?? $leader->name }}" class="lb-user-avatar">
            <div class="lb-user-info">
                <div class="lb-user-name">
                    {{ $leader->username ?? $leader->name }}
                    @if($leader->badge_status)
                        <img src="{{ asset($leader->badge_status) }}" alt="Verified">
                    @endif
                </div>
                <div class="lb-user-status {{ $leader->is_online ? 'online' : '' }}">
                    @if($leader->is_online)
                        <span class="online-dot"></span> Online now
                    @else
                        {{ $leader->last_seen ?? 'Offline' }}
                    @endif
                </div>
            </div>
            <div class="lb-reward">
                {{ $leader->total_task_rewards ?? 0 }} <small>NGN</small>
            </div>
        </div>
        @empty
        <div class="lb-empty">
            <i class="fas fa-medal"></i>
            No rewards yet for this period.
        </div>
        @endforelse

        {{-- My Rank Row --}}
        @if($myRank !== null && (!$leaders->contains('id', $user->id)))
        <div class="lb-list-item my-rank">
            <div class="lb-rank top">{{ $myRank }}</div>
            <img src="{{ $user->profileimg ?? asset('images/default-avatar.png') }}"
                 alt="{{ $user->username ?? $user->name }}" class="lb-user-avatar">
            <div class="lb-user-info">
                <div class="lb-user-name">
                    {{ $user->username ?? $user->name }}
                    @if($user->badge_status)
                        <img src="{{ asset($user->badge_status) }}" alt="Verified">
                    @endif
                    <span style="font-size:11px;color:#667eea;font-weight:400;margin-left:6px;">You</span>
                </div>
                <div class="lb-user-status {{ $user->is_online ? 'online' : '' }}">
                    @if($user->is_online)
                        <span class="online-dot"></span> Online now
                    @else
                        {{ $user->last_seen ?? 'Offline' }}
                    @endif
                </div>
            </div>
            <div class="lb-reward">
                {{ $myPoints }} <small>NGN</small>
            </div>
        </div>
        @endif
    </div>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>


<script src="{{ asset('myjs/more_lesstext.js') }}"></script>
<script src="{{ asset('myjs/menu_pop_up_post.js') }}"></script>
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>

<script src="{{ asset('myjs/searchuser.js') }}"></script>

<script>
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
</script>

<script src="{{ asset('myjs/allpost.js') }}"></script>
<script src="{{ asset('myjs/tales.js') }}"></script>
</body>
</html>
