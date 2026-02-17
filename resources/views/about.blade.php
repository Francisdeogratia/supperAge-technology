<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
  
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/finebtn.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">
  </head>  
<body>

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

@section('title', 'About Us - SupperAge')
@section('seo_title', 'About SupperAge - Our Mission & Vision')
@section('seo_description', 'Learn about SupperAge, the social-financial platform where you can chat, share, earn, shop, create stores, fund wallets, and withdraw money. All in one app.')

@section('content')
<div class="about-container">
    <div class="about-header">
        <h1>About SupperAge</h1>
        <p class="tagline">Connect. Share. Earn.</p>
    </div>

    <section class="about-section">
        <h2>Company History</h2>
        <p>
            SupperAge was officially launched on <strong>June 5th, 2025</strong>, by <strong>Omoha Ekenedilichukwu Francis</strong>, 
            a passionate entrepreneur with a clear vision: to connect and combines social networking with financial services.
        </p>
        <p>
            With a strong background in technology and social media, Omoha Francis ekene together with his 
            elder brother Ugochukwu Emmanuel Omoha  recognized the growing need for a dedicated platform 
            where you can chat, share, earn, shop, create stores, fund wallets, and withdraw money. 
            It's a complete ecosystem that combines social networking with financial services and e-commerce.
        </p>
        <p>
            From its inception, SupperAge has grown rapidly, attracting a vibrant and engaged community of users from across 
            Africa and the World. Today, SupperAge stands as a go-to destination where you can Earn, connect with friends, share updates, 
            go Live, and send or receive money directly into your wallet—no matter where you are in the world.
        </p>
        <p>
            With innovation at its core and community at its heart, SupperAge continues to evolve — proudly promoting and assisting students, 
            workers and all husttlers to earn and support themselves.
            
        </p>
    </section>

    <section class="founder-section">
        <h2 class="founders-heading">Meet Our Founders</h2>
        <div class="founders-grid">
            <div class="founder-card">
                <img src="{{ asset('images/ceo-omoha-francis-ekene.jpg') }}" 
                     alt="Omoha Francis Ekenedilichukwu, CEO Of SupperAge" 
                     class="founder-image">
                <h3>Omoha Francis Ekenedilichukwu</h3>
                <p class="founder-title">C.E.O / Founder</p>
            </div>
            
            <div class="founder-card">
                <img src="{{ asset('images/co-founder-ugochukwu-emmanuel.jpeg') }}" 
                     alt="Ugochukwu Emmanuel Omoha, Co-Founder Of SupperAge" 
                     class="founder-image">
                <h3>Ugochukwu Emmanuel Omoha</h3>
                <p class="founder-title">Co-Founder & Product/Commercial Lead</p>
            </div>
        </div>
    </section>

    <section class="about-section">
        <h2>Our Vision</h2>
        <p>
            To be the world's leading social-financial platform where people can connect, share, earn, and trade without borders — 
            empowering communities with freedom, opportunities, and financial inclusion.
        </p>
    </section>

    <section class="about-section">
        <h2>Our Mission</h2>
        <p>
            Our mission is to connect people globally through social interaction, creativity, and commerce, while giving them 
            the power to fund, earn, and manage money in any currency. We aim to provide a secure platform where users can chat, 
            share stories, build businesses, complete tasks, and achieve financial independence — all in one ecosystem.
        </p>
    </section>

    <section class="about-section features-section">
        <h2>What Makes SupperAge Special</h2>
        <div class="features-grid">
            <div class="feature-card">
                <i class="fas fa-users"></i>
                <h3>Social Connection</h3>
                <p>Chat, share stories, go live, and connect with friends and followers worldwide.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-wallet"></i>
                <h3>Financial Freedom</h3>
                <p>Send, receive, and withdraw money globally in any currency with our secure wallet system.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-tasks"></i>
                <h3>Earn Rewards</h3>
                <p>Complete simple tasks to earn money instantly — like, share, comment, and get paid.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-store"></i>
                <h3>Marketplace</h3>
                <p>Create your own store to sell products and services, or shop from trusted sellers.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-hand-holding-usd"></i>
                <h3>Fund & Get Funded</h3>
                <p>Anyone can fund your wallet from anywhere in the world, and you can support others too.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-globe-africa"></i>
                <h3>Global Community</h3>
                <p>Connect without borders and celebrate African culture, heritage, and identity.</p>
            </div>
        </div>
    </section>

    <section class="about-section task-center-section">
        <h2>Task Center - Earn While You Connect</h2>
        <p>
            Our Task Center is where users can discover, complete, and track tasks to earn rewards. 
            Complete simple tasks like liking posts, sharing stories, referring friends, or opening a store — 
            and get rewarded instantly.
        </p>
        <div class="task-categories">
            <div class="task-category">
                <h4>Engagement Tasks</h4>
                <p>Like, comment, share, watch videos, and join communities.</p>
            </div>
            <div class="task-category">
                <h4>Content Creation</h4>
                <p>Upload posts, share stories, go live, and create trending content.</p>
            </div>
            <div class="task-category">
                <h4>Community Tasks</h4>
                <p>Invite friends, answer polls, and review products.</p>
            </div>
            <div class="task-category">
                <h4>Marketplace Tasks</h4>
                <p>Open stores, list products, and support sellers.</p>
            </div>
        </div>
    </section>

    <section class="about-section cta-section">
        <h2>Join the SupperAge Community Today</h2>
        <p>Your world. Your wallet. Your freedom.</p>
        <a href="{{ route('register') }}" class="cta-button">Get Started Now</a>
    </section>
</div>

<style>
.about-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #333;
}

.about-header {
    text-align: center;
    margin-bottom: 50px;
}

.about-header h1 {
    font-size: 2.5em;
    color: #2c3e50;
    margin-bottom: 10px;
}

.tagline {
    font-size: 1.3em;
    color: #1DA1F2;
    font-weight: 600;
}

.about-section {
    margin-bottom: 50px;
}

.about-section h2 {
    font-size: 2em;
    color: #2c3e50;
    margin-bottom: 20px;
    border-bottom: 3px solid #1DA1F2;
    padding-bottom: 10px;
}

.about-section p {
    font-size: 1.1em;
    line-height: 1.8;
    margin-bottom: 15px;
    text-align: justify;
}

.founder-section {
    text-align: center;
    margin: 60px 0;
}

.founders-heading {
    font-size: 2em;
    color: #2c3e50;
    margin-bottom: 40px;
    border-bottom: 3px solid #1DA1F2;
    padding-bottom: 10px;
    display: inline-block;
}

/* ✅ NEW: Grid layout for two founders side by side */
.founders-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 40px;
    max-width: 900px;
    margin: 0 auto;
}

.founder-card {
    padding: 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.founder-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
}

.founder-image {
    width: 250px;
    height: 250px;
    border-radius: 50%;
    border: 5px solid white;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
    margin-bottom: 20px;
    object-fit: cover;
}

.founder-card h3 {
    font-size: 1.5em;
    color: white;
    margin-bottom: 5px;
}

.founder-title {
    font-style: italic;
    color: #f0f0f0;
    font-size: 1.1em;
}

.features-section .features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
    margin-top: 30px;
}

.feature-card {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.feature-card i {
    font-size: 3em;
    color: #1DA1F2;
    margin-bottom: 15px;
}

.feature-card h3 {
    font-size: 1.3em;
    color: #2c3e50;
    margin-bottom: 10px;
}

.feature-card p {
    font-size: 1em;
    color: #666;
    text-align: center;
}

.task-center-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 40px;
    border-radius: 20px;
    color: white;
}

.task-center-section h2 {
    color: white;
    border-bottom-color: white;
}

.task-center-section p {
    color: white;
}

.task-categories {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

.task-category {
    background: rgba(255, 255, 255, 0.1);
    padding: 20px;
    border-radius: 10px;
    backdrop-filter: blur(10px);
}

.task-category h4 {
    font-size: 1.2em;
    margin-bottom: 10px;
}

.cta-section {
    text-align: center;
    background: #f8f9fa;
    padding: 50px;
    border-radius: 15px;
}

.cta-section h2 {
    border: none;
    color: #2c3e50;
}

.cta-button {
    display: inline-block;
    padding: 15px 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-decoration: none;
    border-radius: 50px;
    font-size: 1.2em;
    font-weight: 600;
    margin-top: 20px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.cta-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    color: white;
    text-decoration: none;
}

/* ✅ Mobile Responsive */
@media (max-width: 768px) {
    .about-header h1 {
        font-size: 2em;
    }
    
    .about-section h2 {
        font-size: 1.5em;
    }
    
    .founders-grid {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .founder-image {
        width: 200px;
        height: 200px;
    }
    
    .features-grid {
        grid-template-columns: 1fr;
    }
    
    .founder-card {
        padding: 20px;
    }
    
    .founder-card h3 {
        font-size: 1.3em;
    }
    
    .founder-title {
        font-size: 1em;
    }
}

@media (max-width: 480px) {
    .founder-image {
        width: 180px;
        height: 180px;
    }
    
    .founder-card {
        padding: 15px;
    }
}
</style>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('myjs/bar.js') }}"></script>
    <script src="{{ asset('myjs/more_lesstext.js') }}"></script>
    <script src="{{ asset('myjs/menu_pop_up_post.js') }}"></script>
    <script src="{{ asset('myjs/allpost.js') }}"></script>
    <script src="{{ asset('myjs/tales.js') }}"></script>
    <script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
    <script src="{{ asset('myjs/searchuser.js') }}"></script>

@endsection