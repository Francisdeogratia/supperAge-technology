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

    <title>Add Friends Now - SupperAge</title>

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

    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> -->
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
/* ===== MODERN CLEAN UI - FRIENDS PAGE ===== */

/* Container */
.container {
    max-width: 1200px;
    padding: 20px;
}

/* Page Header */
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 24px 28px;
    border-radius: 16px;
    margin-bottom: 24px;
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.25);
}

.page-header h3 {
    color: white;
    font-size: 24px;
    font-weight: 700;
    margin: 0;
}

/* Pending Requests Card */
.pending-requests-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    margin-bottom: 24px;
    border: none;
}

.pending-requests-card .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 16px 20px;
}

.pending-requests-card .card-header h6 {
    font-size: 16px;
    font-weight: 600;
    margin: 0;
}

.request-item {
    display: flex;
    align-items: center;
    padding: 16px 20px;
    border-bottom: 1px solid #f0f2f5;
    gap: 14px;
}

.request-item:last-child {
    border-bottom: none;
}

.request-avatar-wrapper {
    position: relative;
    flex-shrink: 0;
}

.request-avatar {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #667eea;
}

.online-indicator {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 2px solid white;
}

.request-user-info {
    display: flex;
    align-items: center;
    gap: 14px;
    flex: 1;
    min-width: 0;
}

.request-details {
    flex: 1;
    min-width: 0;
}

.request-details strong {
    font-size: 15px;
    color: #1c1e21;
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.request-details small {
    font-size: 13px;
    color: #65676b;
    display: block;
}

.request-actions {
    display: flex;
    gap: 8px;
    flex-shrink: 0;
}

.accept-request-btn,
.reject-request-btn {
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 13px;
    border: none;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
}

.accept-request-btn {
    background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
    color: white;
}

.reject-request-btn {
    background: linear-gradient(135deg, #d63031 0%, #ff7675 100%);
    color: white;
}

.accept-request-btn:hover,
.reject-request-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* All Users Card */
.all-users-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    border: none;
}

.all-users-card .card-header {
    background: white;
    border-bottom: 1px solid #f0f2f5;
    padding: 20px 24px;
}

.all-users-card .card-header h5 {
    font-size: 20px;
    font-weight: 700;
    color: #1c1e21;
    margin: 0;
}

.all-users-card .card-body {
    padding: 20px;
}

/* ===== USER CARD - MODERN VERTICAL LAYOUT ===== */
.user-card {
    background: white;
    border-radius: 16px;
    border: 1px solid #e8e8e8;
    transition: all 0.3s ease;
    overflow: hidden;
    height: 100%;
}

.user-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(102, 126, 234, 0.18);
    border-color: #667eea;
}

.user-card .card-body {
    padding: 24px 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

/* Profile Image */
.profile-image-container {
    margin-bottom: 16px;
}

.profile-image-wrapper {
    position: relative;
    display: inline-block;
}

.user-profile-image {
    width: 88px;
    height: 88px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #e8e8e8;
    transition: border-color 0.3s;
}

.user-card:hover .user-profile-image {
    border-color: #667eea;
}

/* Status Badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    margin-top: 10px;
}

.status-badge.online {
    background: #d4edda;
    color: #155724;
}

.status-badge.offline {
    background: #f0f2f5;
    color: #65676b;
}

/* User Info Section */
.user-info {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* Name */
.user-name {
    font-size: 17px;
    font-weight: 700;
    color: #1c1e21;
    margin: 0 0 4px 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    word-break: break-word;
}

.verified-badge {
    width: 16px;
    height: 16px;
    flex-shrink: 0;
}

/* Username */
.user-username {
    font-size: 13px;
    color: #65676b;
    margin: 0 0 12px 0;
    word-break: break-word;
}

/* Stats */
.user-stats {
    display: flex;
    justify-content: center;
    gap: 24px;
    width: 100%;
    padding: 12px 0;
    margin: 8px 0;
    border-top: 1px solid #f0f2f5;
    border-bottom: 1px solid #f0f2f5;
}

.stat-item {
    text-align: center;
}

.stat-value {
    display: block;
    font-size: 18px;
    font-weight: 700;
    color: #667eea;
    line-height: 1.2;
}

.stat-label {
    display: block;
    font-size: 11px;
    color: #65676b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 2px;
}

/* Location */
.user-location {
    font-size: 13px;
    color: #65676b;
    margin: 10px 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}

.user-location i {
    color: #667eea;
}

/* Bio */
.user-bio {
    font-size: 13px;
    color: #65676b;
    line-height: 1.5;
    margin: 8px 0 0 0;
    word-break: break-word;
}

/* Action Buttons */
.action-buttons {
    width: 100%;
    margin-top: 16px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.action-buttons .btn {
    width: 100%;
    padding: 10px 16px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: transform 0.2s, box-shadow 0.2s;
    border: none;
}

.send-request-btn,
.action-buttons .btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.action-buttons .btn-success {
    background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
    color: white;
}

.action-buttons .btn-warning {
    background: linear-gradient(135deg, #fdcb6e 0%, #e17055 100%);
    color: white;
}

.action-buttons .btn-info {
    background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
    color: white;
}

.btn-outline-secondary,
.action-buttons .btn-outline-secondary {
    background: white;
    border: 2px solid #e4e6eb !important;
    color: #65676b;
}

.action-buttons .btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.action-buttons .btn:disabled {
    opacity: 0.8;
    cursor: not-allowed;
}

/* Pagination */
.friends-pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    flex-wrap: wrap;
    margin-top: 32px;
    padding-bottom: 8px;
}
.friends-pagination a,
.friends-pagination span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    padding: 0 12px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
    white-space: nowrap;
}
.friends-pagination a {
    background: #fff;
    color: #667eea;
    border: 1.5px solid #d8dadf;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
}
.friends-pagination a:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border-color: transparent;
    box-shadow: 0 4px 12px rgba(102,126,234,0.35);
    transform: translateY(-1px);
}
.friends-pagination span.pg-current {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border: none;
    box-shadow: 0 4px 14px rgba(102,126,234,0.4);
}
.friends-pagination span.pg-disabled {
    background: #f0f2f5;
    color: #b0b3b8;
    border: 1.5px solid #e4e6eb;
    cursor: not-allowed;
}
.friends-pagination span.pg-dots {
    background: none;
    border: none;
    color: #8a8d91;
    box-shadow: none;
    font-size: 17px;
    min-width: 24px;
    padding: 0;
}
body.dark-mode .friends-pagination a { background: #3A3B3C; color: #a78bfa; border-color: #4E4F50; }
body.dark-mode .friends-pagination a:hover { background: linear-gradient(135deg,#667eea,#764ba2); color:#fff; border-color:transparent; }
body.dark-mode .friends-pagination span.pg-disabled { background:#2d2e2f; color:#555; border-color:#3e4042; }

.page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
}

/* ===== TABLET (md) ===== */
@media (max-width: 991px) {
    .user-profile-image {
        width: 76px;
        height: 76px;
    }

    .user-name {
        font-size: 15px;
    }

    .stat-value {
        font-size: 16px;
    }
}

/* ===== MOBILE ===== */
@media (max-width: 767px) {
    .container {
        padding: 12px;
    }

    .page-header {
        padding: 16px 18px;
        margin-bottom: 16px;
        border-radius: 12px;
    }

    .page-header h3 {
        font-size: 18px;
    }

    /* Request Items - Stack vertically on mobile */
    .request-item {
        flex-direction: column;
        text-align: center;
        padding: 16px;
        gap: 12px;
    }

    .request-user-info {
        flex-direction: column;
        gap: 10px;
    }

    .request-details strong,
    .request-details small {
        text-align: center;
    }

    .request-actions {
        width: 100%;
        flex-direction: column;
        gap: 8px;
    }

    .accept-request-btn,
    .reject-request-btn {
        width: 100%;
        padding: 10px;
    }

    /* User Card Mobile */
    .user-card .card-body {
        padding: 20px 16px;
    }

    .user-profile-image {
        width: 72px;
        height: 72px;
    }

    .status-badge {
        font-size: 10px;
        padding: 4px 10px;
    }

    .user-name {
        font-size: 15px;
    }

    .user-username {
        font-size: 12px;
    }

    .user-stats {
        gap: 16px;
        padding: 10px 0;
    }

    .stat-value {
        font-size: 16px;
    }

    .stat-label {
        font-size: 10px;
    }

    .user-location {
        font-size: 12px;
    }

    .user-bio {
        font-size: 12px;
    }

    .action-buttons .btn {
        padding: 10px 14px;
        font-size: 12px;
    }

    .verified-badge {
        width: 14px;
        height: 14px;
    }

    /* Cards grid - 2 columns on mobile */
    .all-users-card .card-body .row > div {
        flex: 0 0 50%;
        max-width: 50%;
        padding: 6px;
    }
}

/* Extra small screens - single column */
@media (max-width: 480px) {
    .all-users-card .card-body .row > div {
        flex: 0 0 100%;
        max-width: 100%;
        padding: 8px 4px;
    }

    .user-profile-image {
        width: 80px;
        height: 80px;
    }

    .user-name {
        font-size: 16px;
    }

    .stat-value {
        font-size: 18px;
    }
}

/* Toast Notifications */
.alert-success-custom {
    background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
    color: white;
    border-radius: 12px;
    padding: 14px 20px;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(0, 184, 148, 0.3);
}

.alert-error-custom {
    background: linear-gradient(135deg, #d63031 0%, #ff7675 100%);
    color: white;
    border-radius: 12px;
    padding: 14px 20px;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(214, 48, 49, 0.3);
}

/* Dark mode search bar */
body.dark-mode #friendSearch {
    background: #3A3B3C;
    border-color: #3E4042;
    color: #E4E6EB;
}
body.dark-mode #friendSearch::placeholder { color: #888; }
body.dark-mode #friendSearchCount { color: #777; }

/* Loading Animation */
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.loading {
    animation: pulse 1.5s ease-in-out infinite;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 48px 20px;
    color: #65676b;
}

.empty-state i {
    font-size: 56px;
    color: #e4e6eb;
    margin-bottom: 16px;
}

/* Smooth button press */
button:active {
    transform: scale(0.98) !important;
}
</style>
    
</head>
<body>
  

<!-- Your friends navbar content  -->
 @include('layouts.navbar')

@extends('layouts.app')

@section('seo_title', 'Friends - SupperAge')
@section('seo_description', 'Find and add friends on SupperAge. Connect with people, chat, share moments, and grow your network.')

@section('content')
<div class="container">
    {{-- Page Header --}}
    <div class="page-header">
        <h3>🌍 Discover Friends</h3>
    </div>
    
    {{-- Pending Friend Requests --}}
    @if($pendingRequests->isNotEmpty())
    <div class="card pending-requests-card">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0">📬 Pending Friend Requests ({{ $pendingRequests->count() }})</h6>
        </div>
        <div class="card-body">
            @foreach($pendingRequests as $request)
                @php
                    $sender = $request->sender;
                    $isOnline = $sender->is_online;
                @endphp
                
                <div class="request-item">
                    <div class="request-user-info">
                        <div class="request-avatar-wrapper">
                            <img src="{{ $sender->profileimg ?? asset('images/best3.png') }}" 
                                 class="request-avatar" 
                                 alt="{{ $sender->name }}">
                            <span class="online-indicator {{ $isOnline ? 'bg-success' : 'bg-secondary' }}"></span>
                        </div>
                        <div class="request-details">
                            <strong>
                                {{ $sender->name }}
                                @if($sender->badge_status)
                                    <img src="{{ asset($sender->badge_status) }}" 
                                         class="verified-badge"
                                         alt="Verified">
                                @endif
                            </strong>
                            <small class="d-block text-muted">{{ '@' . $sender->username }}</small>
                        </div>
                    </div>
                    <div class="request-actions">
                        <button class="btn btn-sm btn-success accept-request-btn" 
                                data-request-id="{{ $request->id }}">
                            ✓ Accept
                        </button>
                        <button class="btn btn-sm btn-danger reject-request-btn" 
                                data-request-id="{{ $request->id }}">
                            ✗ Reject
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
    
    {{-- All Users --}}
    <div class="card all-users-card">
        <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <h5 class="mb-0">
                <i class="fas fa-users"></i> All Users
            </h5>
        </div>

        {{-- Search Bar --}}
        <div style="padding:16px 24px 0;">
            <div style="position:relative;">
                <i class="fas fa-search" style="position:absolute;left:16px;top:50%;transform:translateY(-50%);color:#aaa;font-size:14px;"></i>
                <input type="text" id="friendSearch" placeholder="Search by name, username, or location..."
                       style="width:100%;padding:12px 16px 12px 44px;border:2px solid #e8e8e8;border-radius:14px;font-size:14px;outline:none;transition:border-color 0.2s,box-shadow 0.2s;background:#f9f9fb;"
                       onfocus="this.style.borderColor='#667eea';this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.12)'"
                       onblur="this.style.borderColor='#e8e8e8';this.style.boxShadow='none'">
            </div>
            <div id="friendSearchCount" style="font-size:12px;color:#999;margin-top:6px;display:none;"></div>
        </div>

        <div class="card-body">
            <div class="row" id="friendsGrid">
                @foreach($allUsers as $person)
                    @php
                        $isOnline = $person->is_online;
                        $isFriend = in_array($person->id, $acceptedFriendIds);
                        $requestSent = in_array($person->id, $sentRequestIds);
                        $requestReceived = in_array($person->id, $receivedRequestIds);
                    @endphp

                    <div class="col-6 col-md-6 col-lg-4 mb-4 friend-card-col"
                         data-search="{{ strtolower($person->name) }} {{ strtolower($person->username ?? '') }} {{ strtolower($person->city ?? '') }} {{ strtolower($person->country ?? '') }}">
                        <div class="card user-card">
                            <div class="card-body">
                                {{-- Profile Image --}}
                                <div class="profile-image-container">
                                    <div class="profile-image-wrapper">
                                        <img src="{{ $person->profileimg ?? asset('images/best3.png') }}"
                                             class="user-profile-image"
                                             alt="{{ $person->name }}">
                                    </div>
                                    <span class="status-badge {{ $isOnline ? 'online' : 'offline' }}">
                                        {{ $isOnline ? 'Online' : 'Offline' }}
                                    </span>
                                </div>

                                {{-- User Info --}}
                                <div class="user-info">
                                    {{-- Name --}}
                                    <h6 class="user-name text-muted">
                                        {{ $person->name }}
                                        @if($person->badge_status)
                                            <img src="{{ asset($person->badge_status) }}" class="verified-badge" alt="Verified">
                                        @endif
                                    </h6>

                                    {{-- Username --}}
                                    <p class="user-username">{{ '@' . $person->username }}</p>

                                    {{-- Stats --}}
                                    <div class="user-stats">
                                        <div class="stat-item">
                                            <span class="stat-value">{{ number_format($person->number_followers) }}</span>
                                            <span class="stat-label">Followers</span>
                                        </div>
                                    </div>

                                    {{-- Location --}}
                                    <div class="user-location">
                                        <i class="fa fa-map-marker"></i>
                                        <span>{{ $person->city ?? 'Unknown' }}, {{ $person->country ?? 'Unknown' }}</span>
                                    </div>

                                    {{-- Bio --}}
                                    @if($person->bio)
                                        <p class="user-bio">{{ Str::limit($person->bio, 50) }}</p>
                                    @endif
                                </div>

                                {{-- Action Buttons --}}
                                <div class="action-buttons">
                                    @if($isFriend)
                                        <button class="btn btn-success" disabled>
                                            <i class="fa fa-check"></i> Friends
                                        </button>
                                    @elseif($requestSent)
                                        <button class="btn btn-warning" disabled>
                                            <i class="fa fa-clock-o"></i> Pending
                                        </button>
                                    @elseif($requestReceived)
                                        <button class="btn btn-info" disabled>
                                            <i class="fa fa-envelope"></i> Received
                                        </button>
                                    @else
                                        <button class="btn btn-primary send-request-btn"
                                                data-user-id="{{ $person->id }}"
                                                data-user-name="{{ $person->name }}">
                                            <i class="fa fa-user-plus"></i> Add Friend
                                        </button>
                                    @endif

                                    <a href="{{ route('profile.show', $person->id) }}" class="btn btn-outline-secondary">
                                        <i class="fa fa-eye"></i> View Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            {{-- Pagination --}}
            @if($allUsers->hasPages())
            <nav class="friends-pagination">
                {{-- Prev --}}
                @if($allUsers->onFirstPage())
                    <span class="pg-disabled"><i class="fas fa-chevron-left"></i></span>
                @else
                    <a href="{{ $allUsers->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
                @endif

                {{-- Page numbers --}}
                @foreach($allUsers->getUrlRange(1, $allUsers->lastPage()) as $page => $url)
                    @if($page == $allUsers->currentPage())
                        <span class="pg-current">{{ $page }}</span>
                    @elseif($page == 1 || $page == $allUsers->lastPage() || abs($page - $allUsers->currentPage()) <= 2)
                        <a href="{{ $url }}">{{ $page }}</a>
                    @elseif(abs($page - $allUsers->currentPage()) == 3)
                        <span class="pg-dots">···</span>
                    @endif
                @endforeach

                {{-- Next --}}
                @if($allUsers->hasMorePages())
                    <a href="{{ $allUsers->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
                @else
                    <span class="pg-disabled"><i class="fas fa-chevron-right"></i></span>
                @endif
            </nav>
            @endif
        </div>
    </div>
</div>

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
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/js/all.min.js"></script> -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>

    
<script src="{{ asset('myjs/more_lesstext.js') }}"></script>
<script src="{{ asset('myjs/menu_pop_up_post.js') }}"></script>

<script src="{{ asset('myjs/allpost.js') }}"></script>
<script src="{{ asset('myjs/tales.js') }}"></script>
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
<script src="{{ asset('myjs/searchuser.js') }}"></script>

<!-- Friend Search Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.getElementById('friendSearch');
    var searchCount = document.getElementById('friendSearchCount');
    var grid = document.getElementById('friendsGrid');
    if (!searchInput || !grid) return;

    searchInput.addEventListener('input', function() {
        var query = this.value.toLowerCase().trim();
        var cards = grid.querySelectorAll('.friend-card-col');
        var visible = 0;

        cards.forEach(function(card) {
            var data = card.getAttribute('data-search') || '';
            if (!query || data.indexOf(query) !== -1) {
                card.style.display = '';
                visible++;
            } else {
                card.style.display = 'none';
            }
        });

        if (query) {
            searchCount.style.display = 'block';
            searchCount.textContent = visible + ' of ' + cards.length + ' users found';
        } else {
            searchCount.style.display = 'none';
        }
    });
});
</script>

<style>
/* Modern Toast */
.fr-toast {
    position: fixed;
    top: 24px;
    right: 24px;
    z-index: 10000;
    min-width: 300px;
    max-width: 400px;
    padding: 16px 20px;
    border-radius: 14px;
    color: #fff;
    font-weight: 600;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.18);
    animation: frToastIn 0.4s cubic-bezier(0.34,1.56,0.64,1);
    backdrop-filter: blur(10px);
}
.fr-toast.success {
    background: linear-gradient(135deg, #00b894, #00cec9);
}
.fr-toast.error {
    background: linear-gradient(135deg, #d63031, #e17055);
}
.fr-toast.info {
    background: linear-gradient(135deg, #667eea, #764ba2);
}
.fr-toast i {
    font-size: 20px;
    flex-shrink: 0;
}
.fr-toast-out {
    animation: frToastOut 0.3s ease forwards;
}
.fr-toast .fr-toast-bar {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    background: rgba(255,255,255,0.5);
    border-radius: 0 0 14px 14px;
    animation: frToastBar 3s linear forwards;
}
@keyframes frToastIn {
    from { transform: translateX(120%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
@keyframes frToastOut {
    from { transform: translateX(0); opacity: 1; }
    to { transform: translateX(120%); opacity: 0; }
}
@keyframes frToastBar {
    from { width: 100%; }
    to { width: 0%; }
}

/* Modern Confirm Modal */
.fr-confirm-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.45);
    backdrop-filter: blur(4px);
    z-index: 10001;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: frFadeIn 0.2s ease;
}
.fr-confirm-box {
    background: #fff;
    border-radius: 18px;
    padding: 32px 28px 24px;
    max-width: 380px;
    width: 90%;
    text-align: center;
    box-shadow: 0 20px 60px rgba(0,0,0,0.25);
    animation: frPopIn 0.35s cubic-bezier(0.34,1.56,0.64,1);
}
.fr-confirm-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    font-size: 26px;
}
.fr-confirm-icon.send {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff;
}
.fr-confirm-icon.reject {
    background: linear-gradient(135deg, #d63031, #e17055);
    color: #fff;
}
.fr-confirm-title {
    font-size: 18px;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 8px;
}
.fr-confirm-msg {
    font-size: 14px;
    color: #666;
    margin-bottom: 24px;
    line-height: 1.5;
}
.fr-confirm-btns {
    display: flex;
    gap: 10px;
}
.fr-confirm-btns button {
    flex: 1;
    padding: 12px 16px;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
}
.fr-confirm-btns .fr-btn-cancel {
    background: #f0f0f0;
    color: #555;
}
.fr-confirm-btns .fr-btn-cancel:hover {
    background: #e0e0e0;
}
.fr-confirm-btns .fr-btn-confirm {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff;
}
.fr-confirm-btns .fr-btn-confirm:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(102,126,234,0.4);
}
.fr-confirm-btns .fr-btn-danger {
    background: linear-gradient(135deg, #d63031, #e17055);
    color: #fff;
}
.fr-confirm-btns .fr-btn-danger:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(214,48,49,0.4);
}
@keyframes frFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
@keyframes frPopIn {
    from { transform: scale(0.7); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

/* Dark mode */
body.dark-mode .fr-confirm-box {
    background: #242526;
}
body.dark-mode .fr-confirm-title { color: #E4E6EB; }
body.dark-mode .fr-confirm-msg { color: #B0B3B8; }
body.dark-mode .fr-confirm-btns .fr-btn-cancel {
    background: #3A3B3C;
    color: #E4E6EB;
}
body.dark-mode .fr-confirm-btns .fr-btn-cancel:hover {
    background: #4a4b4d;
}
</style>

<script>
// Modern Toast Notification
function showToast(message, type) {
    // Remove existing toasts
    document.querySelectorAll('.fr-toast').forEach(function(t) { t.remove(); });

    var icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        info: 'fa-info-circle'
    };

    var toast = document.createElement('div');
    toast.className = 'fr-toast ' + (type || 'info');
    toast.innerHTML = '<i class="fas ' + (icons[type] || icons.info) + '"></i>' +
        '<span>' + message + '</span>' +
        '<div class="fr-toast-bar"></div>';

    document.body.appendChild(toast);

    setTimeout(function() {
        toast.classList.add('fr-toast-out');
        setTimeout(function() { toast.remove(); }, 300);
    }, 3000);
}

// Modern Confirm Dialog (returns a Promise)
function showConfirm(title, message, confirmText, type) {
    return new Promise(function(resolve) {
        var iconClass = type === 'danger' ? 'reject' : 'send';
        var iconSymbol = type === 'danger' ? 'fa-user-times' : 'fa-user-plus';
        var btnClass = type === 'danger' ? 'fr-btn-danger' : 'fr-btn-confirm';

        var overlay = document.createElement('div');
        overlay.className = 'fr-confirm-overlay';
        overlay.innerHTML =
            '<div class="fr-confirm-box">' +
                '<div class="fr-confirm-icon ' + iconClass + '">' +
                    '<i class="fas ' + iconSymbol + '"></i>' +
                '</div>' +
                '<div class="fr-confirm-title">' + title + '</div>' +
                '<div class="fr-confirm-msg">' + message + '</div>' +
                '<div class="fr-confirm-btns">' +
                    '<button class="fr-btn-cancel">Cancel</button>' +
                    '<button class="' + btnClass + '">' + confirmText + '</button>' +
                '</div>' +
            '</div>';

        document.body.appendChild(overlay);

        // Cancel
        overlay.querySelector('.fr-btn-cancel').addEventListener('click', function() {
            overlay.remove();
            resolve(false);
        });

        // Confirm
        overlay.querySelector('.' + btnClass).addEventListener('click', function() {
            overlay.remove();
            resolve(true);
        });

        // Click outside to cancel
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                overlay.remove();
                resolve(false);
            }
        });
    });
}

// Send Friend Request
document.querySelectorAll('.send-request-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var userId = this.getAttribute('data-user-id');
        var userName = this.getAttribute('data-user-name');
        var button = this;

        showConfirm(
            'Send Friend Request',
            'Send a friend request to <strong>' + userName + '</strong>?',
            'Send Request',
            'send'
        ).then(function(confirmed) {
            if (!confirmed) return;

            button.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Sending...';
            button.disabled = true;

            fetch('/friends/send-request/' + userId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.success) {
                    button.classList.remove('btn-primary');
                    button.classList.add('btn-warning');
                    button.innerHTML = '<i class="fa fa-clock"></i> Pending';
                    button.disabled = true;
                    showToast('Friend request sent successfully!', 'success');
                } else {
                    showToast(data.error || 'Failed to send friend request', 'error');
                    button.innerHTML = '<i class="fa fa-user-plus"></i> Add Friend';
                    button.disabled = false;
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                showToast('Something went wrong. Please try again.', 'error');
                button.innerHTML = '<i class="fa fa-user-plus"></i> Add Friend';
                button.disabled = false;
            });
        });
    });
});

// Accept Friend Request
document.querySelectorAll('.accept-request-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var requestId = this.getAttribute('data-request-id');
        var parentDiv = this.closest('.request-item');
        var button = this;

        button.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Accepting...';
        button.disabled = true;

        fetch('/friends/accept/' + requestId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.success) {
                parentDiv.style.background = 'linear-gradient(135deg, #00b894 0%, #00cec9 100%)';
                parentDiv.innerHTML =
                    '<div style="padding: 20px; text-align: center; color: white; font-weight: 600;">' +
                        '<i class="fa fa-check-circle" style="font-size: 24px; margin-bottom: 10px;"></i>' +
                        '<div>Friend request accepted!</div>' +
                    '</div>';

                showToast('Friend request accepted!', 'success');

                setTimeout(function() {
                    parentDiv.style.opacity = '0';
                    parentDiv.style.transform = 'scale(0.8)';
                    parentDiv.style.transition = 'all 0.3s ease';
                    setTimeout(function() { parentDiv.remove(); }, 300);
                }, 2000);
            }
        })
        .catch(function(error) {
            console.error('Error:', error);
            showToast('Failed to accept request. Please try again.', 'error');
            button.innerHTML = '<i class="fa fa-check"></i> Accept';
            button.disabled = false;
        });
    });
});

// Reject Friend Request
document.querySelectorAll('.reject-request-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var requestId = this.getAttribute('data-request-id');
        var parentDiv = this.closest('.request-item');
        var button = this;

        showConfirm(
            'Reject Friend Request',
            'Are you sure you want to reject this friend request?',
            'Yes, Reject',
            'danger'
        ).then(function(confirmed) {
            if (!confirmed) return;

            button.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Rejecting...';
            button.disabled = true;

            fetch('/friends/reject/' + requestId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.success) {
                    parentDiv.style.background = 'linear-gradient(135deg, #d63031 0%, #ff7675 100%)';
                    parentDiv.innerHTML =
                        '<div style="padding: 20px; text-align: center; color: white; font-weight: 600;">' +
                            '<i class="fa fa-times-circle" style="font-size: 24px; margin-bottom: 10px;"></i>' +
                            '<div>Friend request rejected</div>' +
                        '</div>';

                    showToast('Friend request rejected', 'error');

                    setTimeout(function() {
                        parentDiv.style.opacity = '0';
                        parentDiv.style.transform = 'scale(0.8)';
                        parentDiv.style.transition = 'all 0.3s ease';
                        setTimeout(function() { parentDiv.remove(); }, 300);
                    }, 2000);
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                showToast('Failed to reject request. Please try again.', 'error');
                button.innerHTML = '<i class="fa fa-times"></i> Reject';
                button.disabled = false;
            });
        });
    });
});
</script>
@endsection

</body>
</html>