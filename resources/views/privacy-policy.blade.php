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

    <title>SupperAge all post and updates</title>

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
    <link rel="stylesheet" href="{{ asset('css/talemodel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">
   
    <!-- google ads -->
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2892124627300694"
     crossorigin="anonymous"></script>
    </head>

    <body>
  
{{-- Top Header (Image, Search Input, Search Icon) --}}
{{-- Desktop Header --}}
<header class="fb-header mb-1">  
    <div class="fb-left">
        <a href="#"> 
            <img src="{{ asset('images/best3.png') }}" style="border-radius:50%;width: 50px;height: 50px;" >
        </a>
        <div class="fb-logo "><span class="mr-3" >Supperage</span></div>
<!-- search users -->
        <div class="search-container" style="position: relative; flex: 1; max-width: 400px;">
    <input 
        type="text" 
        name="search_user" 
        class="search-bar search_user form-control input-sm" 
        placeholder="Search users..." 
        autocomplete="off"
    />
    <div id="searchResultsDropdown" class="search-results-dropdown"></div>
</div>

       
      {{-- AGE AI Link --}}
        <a href="{{ route('age-ai.chat') }}" class="age-ai-icon ml-4" id="ageAiIcon" title="Chat with AGE AI">
            AGE AI
        </a>


        {{-- Plus Icon with Dropdown --}}
        <div class="plus-menu-trigger" style="margin-left: 10px;margin-right: 10px" id="plusMenuTrigger" onclick="togglePlusDropdown()" title="Quick Actions">
            <i class="fas fa-plus-circle"></i>
            <div class="plus-dropdown" id="plusDropdown">
                <!-- AI & Communication -->
                <a href="{{ route('age-ai.chat') }}" class="plus-dropdown-item">
                    <i class="fas fa-robot" style="color: #8A2BE2;"></i>
                    <span>Ask AGE AI</span>
                </a>
                <a href="{{ route('events.index') }}" class="plus-dropdown-item">
                        <i class="fas fa-calendar-alt" style="color: #FF4500;"></i>
                        <span>Create Event</span>
                    </a> 
                 <a href="{{ route('live.index') }}" class="plus-dropdown-item">
                    <i class="fas fa-video" style="color: #FF0000;"></i>
                    <span>Go Live</span>
                </a> 

                <!-- Groups & Friends -->
                <div class="plus-dropdown-section">
                  <a href="{{ route('my.followers') }}" class="plus-dropdown-item">
                      <i class="fas fa-user-plus mr-2" style="color: #00BFFF;"></i>
                      <span>Follow People</span>
                  </a>
                    <a href="{{ route('groups.index') }}" class="plus-dropdown-item">
                        <i class="fas fa-users-cog" style="color: #00BFFF;"></i>
                        <span>Create Group</span>
                    </a>

                    <a href="{{ route('groups.index') }}" class="plus-dropdown-item">
                        <i class="fas fa-users" style="color: #1DA1F2;"></i>
                        <span>View Groups</span>
                    </a>
                    <a href="{{ route('friends.index') }}" class="plus-dropdown-item">
                        <i class="fas fa-user-plus" style="color: #28a745;"></i>
                        <span>Add Friends</span>
                    </a>
                    <a href="{{ route('messages.index') }}" class="plus-dropdown-item">
                        <i class="fas fa-comment-dots" style="color: #1DA1F2;"></i>
                        <span>Chat Now</span>
                    </a>
                </div>

                <!-- Wallet & Finance -->
                <div class="plus-dropdown-section">
                    <a href="{{ route('mywallet') }}" class="plus-dropdown-item">
                        <i class="fas fa-wallet" style="color: #28a745;"></i>
                        <span>Fund Wallet</span>
                    </a>
                    <a href="{{ route('wallet.transfer.page') }}" class="plus-dropdown-item">
                        <i class="fas fa-exchange-alt" style="color: #FF9800;"></i>
                        <span>Do Transfer</span>
                    </a>
                    <a href="{{ route('wallet.withdraw') }}" class="plus-dropdown-item">
                        <i class="fas fa-money-bill-wave" style="color: #dc3545;"></i>
                        <span>Withdraw Cash</span>
                    </a>
                    <a href="{{ route('tasks.leaderboard') }}" class="plus-dropdown-item">
                        <i class="fas fa-chart-line" style="color: #007bff;"></i>
                        <span>Check Daily Balance</span>
                    </a>
                    <a href="{{ route('mywallet') }}" class="plus-dropdown-item">
                        <i class="fas fa-history" style="color: #6c757d;"></i>
                        <span>Transaction History</span>
                    </a>
                </div>

                <!-- Earn & Tasks -->
                <div class="plus-dropdown-section">
                   <a href="{{ route('tasks.index') }}" class="plus-dropdown-item">
                        <i class="fas fa-tasks" style="color: #FFD700;"></i>
                        <span>Complete Tasks & Earn</span>
                    </a>
                    <a href="{{ route('invite', ['task' => 'app']) }}" class="plus-dropdown-item">
                        <i class="fas fa-share-nodes" style="color: #1DA1F2;"></i>
                        <span>Share Link & Earn</span>
                    </a>
                    <a href="{{ route('invite', ['task' => 'invite']) }}" class="plus-dropdown-item">
                        <i class="fas fa-user-group" style="color: #28a745;"></i>
                        <span>Invite Friends & Earn</span>
                    </a>
                </div>

                <!-- Business & Services -->
                <div class="plus-dropdown-section">
                     <a href="{{ route('marketplace.index') }}" class="plus-dropdown-item">
                        <i class="fas fa-store" style="color: #FF9800;"></i>
                        <span>Marketplace → Create Store</span>
                    </a>
                    <a href="{{ route('task.center') }}" class="plus-dropdown-item">
                        <i class="fas fa-bullhorn" style="color: #FF4500;"></i>
                        <span>Promote Your Account</span>
                    </a>
                     <a href="{{ route('advertising.index') }}" class="plus-dropdown-item">
                        <i class="fas fa-ad" style="color: #8A2BE2;"></i>
                        <span>Advertise Products/Services</span>
                    </a>
                    <a href="{{ route('task.center') }}" class="plus-dropdown-item">
                        <i class="fas fa-clipboard-list" style="color: #00BFFF;"></i>
                        <span>Add Task & Earn</span>
                    </a>
                </div>

                <!-- Verification & App -->
                <div class="plus-dropdown-section">
                    <a href="{{ url('/premium') }}" class="plus-dropdown-item">
                        <i class="fas fa-check-circle" style="color: #1DA1F2;"></i>
                        <span>Apply for Blue Badge</span>
                    </a>
                    <a href="{{ url('/premium') }}" class="plus-dropdown-item">
                        <i class="fas fa-shield-alt" style="color: #28a745;"></i>
                        <span>Verify Your Account</span>
                    </a>
                     <a href="{{ route('tasks.index') }}" class="plus-dropdown-item">
                        <i class="fas fa-mobile-alt" style="color: #007bff;"></i>
                        <span>Download Our App & Earn</span>
                    </a>
                </div>

                <!-- Info -->
                <div class="plus-dropdown-section">
                   <a href="{{ route('about') }}" class="plus-dropdown-item">
                        <i class="fas fa-info-circle" style="color: #6c757d;"></i>
                        <span>About Us</span>
                    </a>
                    <a href="{{ route('contact') }}" class="plus-dropdown-item">
                      <i class="fas fa-envelope" style="color: #1DA1F2;"></i>
                      <span>Contact Us</span>
                  </a>
                </div>
            </div>
        </div>

        

        <!-- <div class="plus-menu-trigger ">
        <i class="fas fa-plus-circle"></i>
      </div> -->



        <div class="icon search ml-3"  id="searchIcon" title="Search" onclick="toggleAgeAiIcon()">
            <i class="fa fa-search crc"></i>
        </div>
    </div>


  {{-- Desktop Only Icons (Hidden on Mobile) --}}
  <div class="fb-icons desktop-icons">
    <div class="icon home" title="Home">
      <a href="{{ url('update') }}"><img src="{{ asset('images/home.png') }}" class="crc" alt="Home"/><span class="hss">Home</span></a>
    </div>

    <div class="icon watch" title="friend">
  <a href="{{ route('friends.index') }}">
    <i class="fa fa-users crc"></i><span class="hss">Friends</span>
    @php
      $userId = Session::get('id');
      $friendRequestCount = \App\Models\FriendRequest::where('receiver_id', $userId)
          ->where('status', 'pending')
          ->count();
    @endphp
    @if($friendRequestCount > 0)
      <span class="badge bg-danger">{{ $friendRequestCount }}</span>
    @endif
  </a>
</div>

    <div class="icon market" title="message">
    <a href="{{ route('messages.index') }}">
        <i class="fa fa-comment crc"></i><span class="hss">Messages</span>
        @php
            $userId = Session::get('id');
            $unreadMessageCount = \App\Models\Message::where('receiver_id', $userId)
                ->where('status', '!=', 'read')
                ->count();
        @endphp
        @if($unreadMessageCount > 0)
            <span class="newmsg">{{ $unreadMessageCount }}</span>
        @else
            <span class="newmsg" style="display:none;">0</span>
        @endif
    </a>
</div>

    @php 
      $userId = Session::get('id');
      $totalNotifications = \DB::table('notifications')
          ->where('notification_reciever_id', $userId)
          ->where('read_notification', 'no')
          ->count();
    @endphp

    <div class="icon notifications" title="Notifications">
      <a href="{{ route('user.notifications') }}">
        <i class="fa fa-bell crc"></i>
        <span class="hss">Notification</span>
        @if($totalNotifications > 0)
          <span class="badge">{{ $totalNotifications }}</span>
        @endif
      </a>
    </div>
    </div>

    
  </div>
</header>


{{-- Mobile Bottom Navigation (Fixed at Bottom) --}}

{{-- Mobile Bottom Navigation --}}
<nav class="mobile-bottom-nav">
    <a href="{{ url('update') }}" class="nav-item active">
        <i class="fa fa-home"></i>
        <span>Home</span>
    </a>
    
    <a href="{{ route('friends.index') }}" class="nav-item">
        <i class="fa fa-users"></i>
        <span>Friends</span>
        @if($friendRequestCount > 0)
            <span class="nav-badge">{{ $friendRequestCount }}</span>
        @endif
    </a>
    
    <a href="{{ route('messages.index') }}" class="nav-item">
        <i class="fa fa-comment"></i>
        <span>Messages</span>
        @if($unreadMessageCount > 0)
            <span class="nav-badge">{{ $unreadMessageCount }}</span>
        @endif
    </a>

    {{-- NEW: Groups Nav Item --}}
    <a href="{{ route('groups.index') }}" class="nav-item">
        <i class="fa fa-users"></i>
        <span>Groups</span>
        @php
            $unreadGroupsCount = 0; // Calculate unread groups count from your logic
        @endphp
        @if($unreadGroupsCount > 0)
            <span class="nav-badge">{{ $unreadGroupsCount }}</span>
        @endif
    </a>

    <a href="{{ route('user.notifications') }}" class="nav-item">
    <i class="fa fa-bell"></i>
    <span>Alerts</span>
    @if($totalNotifications > 0)
      <span class="nav-badge">{{ $totalNotifications }}</span>
    @endif
  </a>
    
    {{-- Plus Icon for Mobile --}}
    <div class="nav-item" onclick="toggleMobilePlusDropdown()">
        <i class="fas fa-plus-circle"></i>
        <span>More</span>
        @if($totalNotifications > 0)
      <span class="nav-badge">{{ $totalNotifications }}</span>
    @endif
    </div>
</nav>

{{-- Mobile Plus Dropdown --}}
<div class="mobile-plus-dropdown" id="mobilePlusDropdown">
    {{-- Same dropdown items as desktop --}}
    <a href="{{ route('age-ai.chat') }}" class="plus-dropdown-item">
        <i class="fas fa-robot" style="color: #8A2BE2;"></i>
        <span>Ask AGE AI</span>
    </a>

    <a href="{{ route('profile.edit') }}" class="mobile-menu-item">
          <i class="fa fa-user"></i>
          <span>Edit your profile</span>
        </a>
        
        <a href="{{ url('settings2') }}" class="mobile-menu-item">
          <i class="fa fa-cog"></i>
          <span>Settings & Policy</span>
        </a>
        

    <div class="mobile-menu-item" onclick="toggleDarkMode(event)">
          <i class="fa fa-moon dark-mode-icon"></i>
          <span>Dark Mode</span>
          <span class="toggle-switch" id="darkModeToggle">
            <span class="toggle-slider"></span>
          </span>
        </div>

    <a href="{{ url('logout') }}" class="mobile-menu-item logout-item">
          <i class="fa fa-sign-out"></i>
          <span>Logout</span>
        </a>


    <a href="{{ route('user.notifications') }}" class="plus-dropdown-item">
    <i class="fa fa-bell"></i>
    <span>Alerts</span>
    @if($totalNotifications > 0)
      <span class="" style="background-color: red; font-size:10px; padding:6px; border-radius: 50%; color: white; width: 30px; height: 25px; display: flex; align-items: center; justify-content: center;">{{ $totalNotifications }}</span>
    @endif
  </a>
    <a href="{{ route('events.index') }}" class="plus-dropdown-item">
        <i class="fas fa-calendar-alt" style="color: #FF4500;"></i>
        <span>Create Event</span>
    </a>
   <a href="{{ route('live.index') }}" class="plus-dropdown-item">
        <i class="fas fa-video" style="color: #FF0000;"></i>
        <span>Go Live</span>
    </a>
    
    <div class="plus-dropdown-section">
      <a href="{{ route('my.followers') }}" class="plus-dropdown-item">
          <i class="fas fa-user-plus mr-2" style="color: #00BFFF;"></i>
          <span>Follow People</span>
      </a>
        <a href="{{ route('groups.index') }}" class="plus-dropdown-item">
            <i class="fas fa-users-cog" style="color: #00BFFF;"></i>
            <span>Create Group</span>
        </a>
        <a href="{{ route('groups.index') }}" class="plus-dropdown-item">
            <i class="fas fa-users" style="color: #1DA1F2;"></i>
            <span>View Groups</span>
        </a>
        <a href="{{ route('friends.index') }}" class="plus-dropdown-item">
            <i class="fas fa-user-plus" style="color: #28a745;"></i>
            <span>Add Friends</span>
        </a>
        <a href="{{ route('messages.index') }}" class="plus-dropdown-item">
            <i class="fas fa-comment-dots" style="color: #1DA1F2;"></i>
            <span>Chat Now</span>
        </a>
    </div>
    
    <div class="plus-dropdown-section">
         <a href="{{ route('mywallet') }}" class="plus-dropdown-item">
            <i class="fas fa-wallet" style="color: #28a745;"></i>
            <span>Fund Wallet</span>
        </a>
        <a href="{{ route('wallet.transfer.page') }}" class="plus-dropdown-item">
            <i class="fas fa-exchange-alt" style="color: #FF9800;"></i>
            <span>Do Transfer</span>
        </a>
        <a href="{{ route('wallet.withdraw') }}" class="plus-dropdown-item">
            <i class="fas fa-money-bill-wave" style="color: #dc3545;"></i>
            <span>Withdraw Cash</span>
        </a>
        <a href="{{ route('tasks.leaderboard') }}" class="plus-dropdown-item">
            <i class="fas fa-chart-line" style="color: #007bff;"></i>
            <span>Check Daily Balance</span>
        </a>
        <a href="{{ route('mywallet') }}" class="plus-dropdown-item">
            <i class="fas fa-history" style="color: #6c757d;"></i>
            <span>Transaction History</span>
        </a>
    </div>
    
    <div class="plus-dropdown-section">
        <a href="{{ route('tasks.index') }}" class="plus-dropdown-item">
            <i class="fas fa-tasks" style="color: #FFD700;"></i>
            <span>Complete Tasks & Earn</span>
        </a>
        <a href="{{ route('invite', ['task' => 'app']) }}" class="plus-dropdown-item">
            <i class="fas fa-share-nodes" style="color: #1DA1F2;"></i>
            <span>Share Link & Earn</span>
        </a>
        <a href="{{ route('invite', ['task' => 'invite']) }}" class="plus-dropdown-item">
            <i class="fas fa-user-group" style="color: #28a745;"></i>
            <span>Invite Friends & Earn</span>
        </a>
    </div>
    
    <div class="plus-dropdown-section">
       <a href="{{ route('marketplace.index') }}" class="plus-dropdown-item">
            <i class="fas fa-store" style="color: #FF9800;"></i>
            <span>Sell Products & Services</span>
        </a>
        <a href="{{ route('task.center') }}" class="plus-dropdown-item">
            <i class="fas fa-bullhorn" style="color: #FF4500;"></i>
            <span>Promote Your Account</span>
        </a>
          <a href="{{ route('advertising.index') }}" class="plus-dropdown-item">
            <i class="fas fa-ad" style="color: #8A2BE2;"></i>
            <span>Advertise Products/Services</span>
        </a>
        <a href="{{ route('task.center') }}" class="plus-dropdown-item">
            <i class="fas fa-clipboard-list" style="color: #00BFFF;"></i>
            <span>Add Task & Earn</span>
        </a>
    </div>
    
    <div class="plus-dropdown-section">
         <a href="{{ url('/premium') }}" class="plus-dropdown-item">
            <i class="fas fa-check-circle" style="color: #1DA1F2;"></i>
            <span>Apply for Blue Badge</span>
        </a>
        <a href="{{ url('/premium') }}" class="plus-dropdown-item">
            <i class="fas fa-shield-alt" style="color: #28a745;"></i>
            <span>Verify Your Account</span>
        </a>
        <a href="{{ route('tasks.index') }}" class="plus-dropdown-item">
            <i class="fas fa-mobile-alt" style="color: #007bff;"></i>
            <span>Download Our App & Earn</span>
        </a>
    </div>
    
    <div class="plus-dropdown-section">
       <a href="{{ route('about') }}" class="plus-dropdown-item">
                        <i class="fas fa-info-circle" style="color: #6c757d;"></i>
                        <span>About Us</span>
                    </a>
        <a href="{{ route('contact') }}" class="plus-dropdown-item">
    <i class="fas fa-envelope" style="color: #1DA1F2;"></i>
    <span>Contact Us</span>
</a>
    </div>
</div>

{{-- Overlay --}}
<div class="dropdown-overlay" id="dropdownOverlay" onclick="closeAllDropdowns()"></div>

@extends('layouts.app')

@section('title', 'Settings & Policy - SupperAge')

@section('content')
<div class="settings-policy-container">
    <div class="page-header">
        <h1>Settings & Policy</h1>
        <p>Everything you need to know about using SupperAge safely and responsibly</p>
    </div>

    <div class="policy-navigation">
        <button class="policy-nav-btn active" data-policy="privacy">Privacy Policy</button>
        <button class="policy-nav-btn" data-policy="terms">Terms & Conditions</button>
        <button class="policy-nav-btn" data-policy="refund">Refund Policy</button>
        <button class="policy-nav-btn" data-policy="community">Community Guidelines</button>
        <button class="policy-nav-btn" data-policy="user-agreement">User Agreement</button>
        <button class="policy-nav-btn" data-policy="advertising">Advertising Policy</button>
        <button class="policy-nav-btn" data-policy="sponsorship">Sponsorship Policy</button>
        <button class="policy-nav-btn" data-policy="cookie">Cookie Policy</button>
        <button class="policy-nav-btn" data-policy="disclaimer">Disclaimer</button>
        <button class="policy-nav-btn" data-policy="anti-fraud">Anti-Fraud Policy</button>
        <button class="policy-nav-btn" data-policy="marketplace">Marketplace Terms</button>
        
        
        
        <button class="policy-nav-btn" data-policy="child-safety">
  🛡️ Child Safety Policy
</button>

    </div>

    <div class="policy-content">
        <!-- Privacy Policy -->
        <div class="policy-section active" id="privacy">
            <h2>SupperAge Privacy Policy</h2>
            <p class="last-updated">Last Updated: January 1, 2026</p>
            
            <p>SupperAge ("we", "our", "us") operates a social-financial platform that allows users to chat, share content, complete tasks, earn rewards, fund wallets, send and receive payments, create stores, and buy or sell products and services.</p>
            
            <p>This Privacy Policy explains how we collect, use, share, and protect your information.</p>

            <h3>1. Information We Collect</h3>
            
            <h4>1.1 Personal Information</h4>
            <p>We may collect the following personal data:</p>
            <ul>
                <li>Full name</li>
                <li>Username</li>
                <li>Email address</li>
                <li>Phone number</li>
                <li>Profile photo</li>
                <li>Date of birth (optional)</li>
            </ul>

            <h4>1.2 Communication Data</h4>
            <ul>
                <li>Chat messages</li>
                <li>Posts, comments, likes, shares</li>
                <li>Stories, photos, and videos</li>
                <li>Live-stream interactions</li>
            </ul>

            <h4>1.3 Financial Information</h4>
            <ul>
                <li>Wallet balance</li>
                <li>Payment transactions</li>
                <li>Bank details (for withdrawals)</li>
                <li>Currency used</li>
                <li>Funding or transfer history</li>
            </ul>

            <h4>1.4 Device Information</h4>
            <ul>
                <li>IP address</li>
                <li>Device model</li>
                <li>Operating system</li>
                <li>Browser</li>
                <li>App usage data</li>
                <li>Location (optional)</li>
            </ul>

            <h3>2. How We Use Your Information</h3>
            <p>We use your information to:</p>
            <ul>
                <li>Create and manage your SupperAge account</li>
                <li>Provide chatting, posting, live-streaming, and story features</li>
                <li>Process wallet funding, withdrawals, and transfers</li>
                <li>Verify payments and prevent fraud</li>
                <li>Personalize your experience</li>
                <li>Improve app performance and user experience</li>
                <li>Provide customer support</li>
                <li>Send important notifications</li>
            </ul>

            <h3>3. How We Share Your Information</h3>
            <p><strong>We do not sell your data.</strong></p>
            <p>We may share information only with:</p>
            <ul>
                <li>Payment processors</li>
                <li>Banks</li>
                <li>Third-party service providers</li>
                <li>Law enforcement (if required by law)</li>
            </ul>
            <p>All partners follow strict privacy rules.</p>

            <h3>4. Cookies & Tracking Technologies</h3>
            <p>We use cookies to:</p>
            <ul>
                <li>Keep you logged in</li>
                <li>Measure website traffic</li>
                <li>Save your preferences</li>
                <li>Improve performance</li>
            </ul>

            <h3>5. User Content</h3>
            <p>Anything you post—including photos, stories, videos, comments—may be visible to other users depending on your privacy settings.</p>

            <h3>6. Your Rights</h3>
            <p>You can:</p>
            <ul>
                <li>Edit your profile information</li>
                <li>Delete posts</li>
                <li>Request account deletion</li>
                <li>Request your data</li>
                <li>Control notifications</li>
                <li>Change privacy settings</li>
            </ul>

            <h3>7. Data Security</h3>
            <p>We use:</p>
            <ul>
                <li>Encryption</li>
                <li>Secure servers</li>
                <li>Two-factor authentication (optional)</li>
                <li>Fraud monitoring</li>
            </ul>
            <p>However, no system is 100% secure.</p>

            <h3>8. Children's Privacy</h3>
            <p>“SupperAge is not intended for children under 13 years old, and we actively remove underage accounts when identified.”.</p>

            <h3>9. Changes to This Policy</h3>
            <p>We may update this policy at any time. We will notify you of major changes.</p>

            <h3>10. Contact Us</h3>
            <p>For questions or requests:</p>
            <p><strong>SupperAge Support Team</strong><br>
            Email: <a href="mailto:info@supperage.com">info@supperage.com</a></p>
        </div>

        <!-- Terms & Conditions -->
        <div class="policy-section" id="terms">
            <h2>SupperAge Terms & Conditions</h2>
            <p class="last-updated">Last Updated: January 1, 2026</p>
            
            <p>Welcome to SupperAge. These Terms & Conditions ("Terms") govern your access and use of the SupperAge website, apps, wallet services, and marketplace.</p>
            <p><strong>By using SupperAge, you agree to these Terms.</strong></p>

            <h3>1. Your Account</h3>
            <ul>
                <li>You must provide accurate information when creating an account.</li>
                <li>You are responsible for keeping your login details secure.</li>
                <li>We may suspend your account for violating our policies.</li>
            </ul>

            <h3>2. Acceptable Use</h3>
            <p>Users must not:</p>
            <ul>
                <li>Use SupperAge for illegal activities</li>
                <li>Harass or harm other users</li>
                <li>Post violent, hateful, sexual, or harmful content</li>
                <li>Scam, defraud, or impersonate anyone</li>
                <li>Use bots, scripts, or fake accounts</li>
            </ul>

            <h3>3. Wallet & Payments</h3>
            <ul>
                <li>You can fund your wallet, send money, receive funds, and withdraw to your bank.</li>
                <li>SupperAge may charge fees for certain transactions (withdrawals, currency exchange, etc).</li>
                <li>Funds sent or received are non-refundable unless required by law.</li>
                <li>Suspicious transactions may be temporarily held for verification.</li>
            </ul>

            <h3>4. Marketplace</h3>
            <ul>
                <li>Users may create stores and sell goods or services.</li>
                <li>Sellers are responsible for delivery, quality, and customer service.</li>
                <li>SupperAge is not liable for disputes between buyers and sellers, but we may intervene if needed.</li>
            </ul>

            <h3>5. Earnings & Tasks</h3>
            <ul>
                <li>SupperAge provides tasks (likes, shares, comments, referrals, group joins) to earn rewards.</li>
                <li>Earnings depend on task availability and your performance.</li>
                <li>SupperAge may change or remove tasks anytime.</li>
            </ul>

            <h3>6. User Content</h3>
            <ul>
                <li>When you post anything on SupperAge, you give us permission to display it inside the platform.</li>
                <li>You retain full ownership of your content.</li>
            </ul>

            <h3>7. Termination</h3>
            <p>We may suspend or terminate your account if you violate any policy or participate in fraud.</p>

            <h3>8. Limitation of Liability</h3>
            <p>SupperAge is not responsible for:</p>
            <ul>
                <li>Loss of data</li>
                <li>Loss of funds caused by user error</li>
                <li>Service downtime</li>
                <li>Internet or device issues</li>
            </ul>

            <h3>9. Changes to Terms</h3>
            <p>We may update these Terms anytime. Continued use means you accept the changes.</p>

            <h3>10. Contact</h3>
            <p>Email: <a href="mailto:info@supperage.com">info@supperage.com</a></p>
        </div>

        <!-- Refund Policy -->
        <div class="policy-section" id="refund">
            <h2>SupperAge Refund Policy</h2>
            <p class="last-updated">Last Updated: January 1, 2026</p>

            <h3>1. Wallet Funding</h3>
            <p>All wallet funding transactions are non-refundable, except:</p>
            <ul>
                <li>Duplicate payments</li>
                <li>Unauthorized transactions (after investigation)</li>
            </ul>

            <h3>2. Payments to Users</h3>
            <p>Money sent to another user cannot be reversed unless both users agree.</p>

            <h3>3. Marketplace Purchases</h3>
            <ul>
                <li>Refunds depend on the seller's policy, not SupperAge.</li>
                <li>Disputes may be reviewed but final responsibility is on the seller.</li>
            </ul>

            <h3>4. Task Earnings</h3>
            <p>Task earnings are non-refundable and cannot be exchanged except through official withdrawal channels.</p>

            <h3>5. Live Event Support</h3>
            <p>Gifts, tips, or support sent during live streams cannot be refunded.</p>

            <h3>6. App Purchases</h3>
            <p>If you buy through Google Play or Apple Store, their refund rules apply.</p>

            <h3>7. Contact</h3>
            <p>Email: <a href="mailto:info@supperage.com">info@supperage.com</a></p>
        </div>

        <!-- Community Guidelines -->
        <div class="policy-section" id="community">
            <h2>SupperAge Community Guidelines</h2>
            <p class="last-updated">To keep SupperAge safe, users must follow these rules:</p>

            <div class="guideline-card danger">
                <h3>🚫 NO HARMFUL CONTENT</h3>
                <ul>
                    <li>Violence</li>
                    <li>Terrorism</li>
                    <li>Abuse</li>
                    <li>Hate speech</li>
                    <li>Self-harm content</li>
                </ul>
            </div>

            <div class="guideline-card danger">
                <h3>🚫 NO ADULT CONTENT</h3>
                <ul>
                    <li>Nudity</li>
                    <li>Pornography</li>
                    <li>Sexual chats</li>
                </ul>
            </div>

            <div class="guideline-card danger">
                <h3>🚫 NO SCAM OR FRAUD</h3>
                <ul>
                    <li>Fake giveaways</li>
                    <li>Phishing</li>
                    <li>Fake profiles</li>
                    <li>Investment scams</li>
                </ul>
            </div>

            <div class="guideline-card danger">
                <h3>🚫 NO ILLEGAL ACTIVITIES</h3>
                <ul>
                    <li>Drugs</li>
                    <li>Weapons</li>
                    <li>Human trafficking</li>
                </ul>
            </div>

            <div class="guideline-card danger">
                <h3>🚫 NO SPAM</h3>
                <ul>
                    <li>Repeated reposting</li>
                    <li>Fake engagement</li>
                    <li>Bots</li>
                </ul>
            </div>

            <p class="warning-text"><strong>⚠️ Accounts violating these rules may be suspended permanently.</strong></p>
        </div>

        <!-- User Agreement -->
        <div class="policy-section" id="user-agreement">
            <h2>SupperAge User Agreement</h2>
            <p class="last-updated">By creating an account, you agree to:</p>

            <ul>
                <li>Provide real, accurate information</li>
                <li>Use SupperAge legally</li>
                <li>Accept that your posts are visible according to your privacy settings</li>
                <li>Understand wallet features and risk of digital transactions</li>
                <li>Follow all community guidelines</li>
                <li>Be responsible for your marketplace activities</li>
            </ul>
        </div>

        <!-- Advertising Policy -->
        <div class="policy-section" id="advertising">
            <h2>SupperAge Advertising Policy</h2>
            
            <h3>Allowed Ad Types</h3>
            <ul>
                <li>Sponsored posts</li>
                <li>Banner ads</li>
                <li>Profile boosts</li>
                <li>Story ads</li>
                <li>Marketplace ads</li>
            </ul>

            <h3>Disallowed Ads</h3>
            <ul>
                <li>Drugs</li>
                <li>Adult content</li>
                <li>Fake investment schemes</li>
                <li>Scams</li>
                <li>Hate content</li>
                <li>Political ads (optional)</li>
            </ul>

            <h3>Ad Pricing (Example Structure)</h3>
            <div class="pricing-table">
                <table>
                    <thead>
                        <tr>
                            <th>Ad Type</th>
                            <th>Duration</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Banner Ad</td>
                            <td>user can set  days</td>
                            <td>NGN 50 PER CLICK</td>
                            <td>NGN 400 cpa</td>
                            <td>NGN 2500 cpm</td>
                        </tr>
                        <tr>
                            <td>Banner Ad</td>
                            <td>user can set  days</td>
                            <td>NGN 50 PER CLICK</td>
                            <td>NGN 400 cpa</td>
                            <td>NGN 2500 cpm</td>
                        </tr>
                        <tr>
                            <td>Sponsored Post</td>
                            <td>user can set  days</td>
                            <td>NGN 50 PER CLICK</td>
                            <td>NGN 400 cpa</td>
                            <td>NGN 2500 cpm</td>
                        </tr>
                        <tr>
                            <td>Profile Boost</td>
                            <td>user can set  days</td>
                            <td>NGN 50 PER CLICK</td>
                            <td>NGN 400 cpa</td>
                            <td>NGN 2500 cpm</td>
                        </tr>
                        <tr>
                            <td>Story Ad</td>
                            <td>user can set  days</td>
                            <td>NGN 50 PER CLICK</td>
                            <td>NGN 400 cpa</td>
                            <td>NGN 2500 cpm</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h3>How Users Pay</h3>
            <p>Users can pay using:</p>
            <ul>
                <li>Wallet balance</li>
                <li>Bank transfer</li>
                <li>Card payments</li>
            </ul>
            <p>Once paid, ad goes live automatically.</p>
        </div>

        <!-- Sponsorship Policy -->
        <div class="policy-section" id="sponsorship">
            <h2>SupperAge Sponsorship Policy</h2>
            
            <h3>Users may sponsor:</h3>
            <ul>
                <li>Posts</li>
                <li>Live streams</li>
                <li>Events</li>
                <li>Profiles</li>
                <li>Marketplace stores</li>
            </ul>

            <h3>Rules</h3>
            <ul>
                <li>Content must follow Community Guidelines</li>
                <li>Sponsorship payments are final</li>
                <li>Sponsored content will be labeled "Sponsored"</li>
            </ul>
        </div>

        <!-- Cookie Policy -->
        <div class="policy-section" id="cookie">
            <h2>SupperAge Cookie Policy</h2>
            <p class="last-updated">Last Updated: January 1, 2026</p>
            
            <p>SupperAge uses cookies to improve your experience.</p>

            <h3>What We Use Cookies For</h3>
            <ul>
                <li>Keep you logged in</li>
                <li>Remember your preferences</li>
                <li>Analyze traffic</li>
                <li>Improve app performance</li>
                <li>Personalize content</li>
            </ul>

            <h3>Types of Cookies</h3>
            <ul>
                <li><strong>Essential Cookies</strong> (for login & security)</li>
                <li><strong>Analytics Cookies</strong> (usage statistics)</li>
                <li><strong>Advertising Cookies</strong> (optional)</li>
                <li><strong>Preference Cookies</strong> (language, dark mode)</li>
            </ul>

            <h3>Your Control</h3>
            <p>You can disable cookies in your browser settings, but some features may not work.</p>
        </div>

        <!-- Disclaimer -->
        <div class="policy-section" id="disclaimer">
            <h2>SupperAge Disclaimer</h2>
            
            <p>SupperAge provides social, marketplace, and wallet services "as is." We do NOT guarantee:</p>
            <ul>
                <li>Uninterrupted uptime</li>
                <li>100% accurate information posted by users</li>
                <li>Delivery of marketplace items (seller is responsible)</li>
                <li>Earnings or income</li>
                <li>Protection from all fraud attempts</li>
            </ul>

            <p><strong>Users must interact responsibly and verify any information before acting on it.</strong></p>
        </div>

        <!-- Anti-Fraud Policy -->
        <div class="policy-section" id="anti-fraud">
            <h2>SupperAge Anti-Fraud Policy</h2>
            
            <h3>We Monitor:</h3>
            <ul>
                <li>Suspicious wallet transactions</li>
                <li>Fake accounts</li>
                <li>Marketplace scams</li>
                <li>Rapid task farming</li>
                <li>Multiple accounts from one device</li>
                <li>Chargeback or refund abuse</li>
            </ul>

            <h3>Immediate Actions We Take</h3>
            <ul>
                <li>Freeze wallet temporarily</li>
                <li>Block withdrawals</li>
                <li>Suspend account</li>
                <li>Request ID verification</li>
                <li>Report to authorities (if required)</li>
            </ul>

            <h3>User Responsibilities</h3>
            <p>Users must:</p>
            <ul>
                <li>Avoid sharing OTP or password</li>
                <li>Report suspicious users</li>
                <li>Avoid sending money to strangers without verification</li>
            </ul>
        </div>

        <!-- Marketplace Terms -->
        <div class="policy-section" id="marketplace">
            <h2>SupperAge Marketplace Seller Terms</h2>
            
            <h3>1. Seller Responsibilities</h3>
            <ul>
                <li>Deliver items or services as described</li>
                <li>Provide accurate prices</li>
                <li>Respond to buyers promptly</li>
                <li>Ensure products are legal and safe</li>
            </ul>

            <h3>2. Prohibited Products</h3>
            <ul>
                <li>Weapons</li>
                <li>Drugs</li>
                <li>Adult items</li>
                <li>Counterfeit goods</li>
                <li>Stolen items</li>
                <li>Financial schemes</li>
            </ul>

            <h3>3. Payments</h3>
            <ul>
                <li>Payment goes to seller's wallet</li>
                <li>SupperAge may charge service fees</li>
                <li>Withdrawals follow platform rules</li>
            </ul>

            <h3>4. Disputes</h3>
            <p>SupperAge may review disputes but final responsibility is on the seller.</p>

            <h3>5. Store Suspension</h3>
            <p>We may suspend stores for:</p>
            <ul>
                <li>Fraud</li>
                <li>Scams</li>
                <li>Poor delivery record</li>
                <li>Illegal products</li>
                <li>Repeated complaints</li>
            </ul>
        </div>
        
        
        <div class="policy-section" id="child-safety">

  <h2>🛡️ Child Safety & Protection Policy</h2>

  <p>
    <strong>SupperAge</strong> is committed to protecting children and preventing
    child sexual abuse and exploitation (CSAE).
  </p>

  <h3>1. Age Restriction</h3>
  <ul>
    <li>SupperAge is not intended for children under 13 years old</li>
    <li>Some features (wallet, marketplace, live streaming) are restricted to 18+ users</li>
    <li>We actively remove accounts found to belong to underage users</li>
  </ul>

  <h3>2. Zero-Tolerance Policy for CSAE</h3>
  <p>SupperAge has zero tolerance for:</p>
  <ul>
    <li>Child sexual abuse material (CSAM)</li>
    <li>Sexual exploitation of minors</li>
    <li>Grooming or solicitation of minors</li>
    <li>Any sexual content involving a minor</li>
  </ul>
  <p><strong>Any such content results in immediate account termination.</strong></p>

  <h3>3. Content Moderation</h3>
  <ul>
    <li>Automated content monitoring</li>
    <li>User reporting tools</li>
    <li>Manual review by moderators</li>
    <li>Immediate removal of violating content</li>
  </ul>

  <h3>4. Reporting & Blocking</h3>
  <ul>
    <li>Report accounts, posts, chats, or media</li>
    <li>Block other users instantly</li>
    <li>Report suspected child exploitation directly to our team</li>
  </ul>

  <h3>5. Law Enforcement Cooperation</h3>
  <ul>
    <li>Local and international law enforcement</li>
    <li>Regulatory authorities</li>
    <li>Child protection organizations</li>
  </ul>
  <p>We report confirmed CSAE cases as required by law.</p>

  <h3>6. Contact for Child Safety</h3>
  <p>
    <!--📧 <strong>child-safety@supperage.com</strong><br>-->
    📧 <strong><a href="mailto:info@supperage.com">info@supperage.com</a></strong>
  </p>

</div>

        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
    </div>
</div>

<style>
.settings-policy-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 40px 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.page-header {
    text-align: center;
    margin-bottom: 40px;
}

.page-header h1 {
    font-size: 2.5em;
    color: #2c3e50;
    margin-bottom: 10px;
}

.page-header p {
    font-size: 1.1em;
    color: #666;
}

.policy-navigation {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    margin-bottom: 40px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 15px;
}

.policy-nav-btn {
    padding: 12px 20px;
    background: white;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    color: #2c3e50;
    font-size: 0.95em;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.policy-nav-btn:hover {
    border-color: #1DA1F2;
    color: #1DA1F2;
    transform: translateY(-2px);
}

.policy-nav-btn.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: transparent;
}

.policy-content {
    background: white;
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

.policy-section {
    display: none;
}

.policy-section.active {
    display: block;
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.policy-section h2 {
    font-size: 2em;
    color: #2c3e50;
    margin-bottom: 10px;
    border-bottom: 3px solid #1DA1F2;
    padding-bottom: 10px;
}

.last-updated {
    font-style: italic;
    color: #666;
    margin-bottom: 20px;
}

.policy-section h3 {
    font-size: 1.5em;
    color: #2c3e50;
    margin-top: 25px;
    margin-bottom: 15px;
}

.policy-section h4 {
    font-size: 1.2em;
    color: #2c3e50;
    margin-top: 20px;
    margin-bottom: 10px;
}

.policy-section p {
    font-size: 1.05em;
    line-height: 1.8;
    color: #333;
    margin-bottom: 15px;
}

.policy-section ul {
    margin: 15px 0;
    padding-left: 30px;
}

.policy-section ul li {
    font-size: 1.05em;
    line-height: 1.8;
    color: #333;
    margin-bottom: 8px;
}

.policy-section a {
    color: #1DA1F2;
    text-decoration: none;
    font-weight: 600;
}

.policy-section a:hover {
    text-decoration: underline;
}

.guideline-card {
    background: #fff3cd;
    border-left: 5px solid #ffc107;
    padding: 20px;
    margin: 20px 0;
    border-radius: 8px;
}

.guideline-card.danger {
    background: #f8d7da;
    border-left-color: #dc3545;
}

.guideline-card h3 {
    margin-top: 0;
    color: #721c24;
}

.guideline-card ul {
    margin-bottom: 0;
}

.warning-text {
    background: #fff3cd;
    border: 2px solid #ffc107;
    padding: 15px;
    border-radius: 8px;
    text-align: center;
    font-size: 1.1em;
    margin-top: 30px;
}

.pricing-table {
    overflow-x: auto;
    margin: 20px 0;
}

.pricing-table table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

.pricing-table th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px;
    text-align: left;
    font-weight: 600;
}

.pricing-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #e0e0e0;
}

.pricing-table tr:hover {
    background: #f8f9fa;
}

@media (max-width: 768px) {
    .settings-policy-container {
        padding: 20px 10px;
    }

    .page-header h1 {
        font-size: 2em;
    }

    .policy-content {
        padding: 20px;
    }

    .policy-section h2 {
        font-size: 1.5em;
    }

    .policy-nav-btn {
        font-size: 0.85em;
        padding: 10px 15px;
    }

    .pricing-table {
        font-size: 0.9em;
    }
}
</style>

<style>
.policy-nav-btn {
  padding: 10px 16px;
  margin: 6px 0;
  border: none;
  background: #1a73e8;
  color: #fff;
  cursor: pointer;
  border-radius: 6px;
  font-weight: 600;
}

.policy-nav-btn:hover {
  background: #1558b0;
}

.policy-section {
  margin-top: 20px;
  line-height: 1.6;
}
</style>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('myjs/bar.js') }}"></script> <!-- this should come last -->

<script src="{{ asset('myjs/allpost.js') }}"></script>
<script src="{{ asset('myjs/tales.js') }}"></script>
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
<script src="{{ asset('myjs/searchuser.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const navButtons = document.querySelectorAll('.policy-nav-btn');
    const sections = document.querySelectorAll('.policy-section');
    
    navButtons.forEach(button => {
        button.addEventListener('click', function() {
            const policyId = this.getAttribute('data-policy');
            
            // Remove active class from all buttons and sections
            navButtons.forEach(btn => btn.classList.remove('active'));
            sections.forEach(section => section.classList.remove('active'));
            
            // Add active class to clicked button and corresponding section
            this.classList.add('active');
            document.getElementById(policyId).classList.add('active');
            
            // Scroll to top of policy content
            document.querySelector('.policy-content').scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });
        });
    });
});
</script>











@endsection