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

@section('title', 'Contact Us - SupperAge')
@section('seo_title', 'Contact Us - SupperAge Support')
@section('seo_description', 'Get in touch with the SupperAge team. We are here to help with any questions, feedback, or support you need on your SupperAge journey.')

@section('content')
<div class="contact-container">
    <div class="contact-header">
        <h1>Contact Us</h1>
        <p>We'd love to hear from you! Send us a message and we'll respond as soon as possible.</p>
    </div>

    <div class="contact-content">
        <div class="contact-form-section">
            <h2>Send Us a Message</h2>
            
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('contact.submit') }}" method="POST" class="contact-form" id="contactForm">
                @csrf
                
                <div class="form-group">
                    <label for="name">
                        <i class="fas fa-user"></i> Full Name
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           class="form-control" 
                           placeholder="Enter your full name"
                           value="{{ old('name', auth()->user()->name ?? '') }}" 
                           required>
                </div>

                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Email Address
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-control" 
                           placeholder="Enter your email address"
                           value="{{ old('email', auth()->user()->email ?? '') }}" 
                           required>
                </div>

                <div class="form-group">
                    <label for="subject">
                        <i class="fas fa-tag"></i> Subject
                    </label>
                    <select id="subject" name="subject" class="form-control" required>
                        <option value="">Select a subject</option>
                        <option value="General Inquiry" {{ old('subject') == 'General Inquiry' ? 'selected' : '' }}>General Inquiry</option>
                        <option value="Technical Support" {{ old('subject') == 'Technical Support' ? 'selected' : '' }}>Technical Support</option>
                        <option value="Payment Issue" {{ old('subject') == 'Payment Issue' ? 'selected' : '' }}>Payment Issue</option>
                        <option value="Account Issue" {{ old('subject') == 'Account Issue' ? 'selected' : '' }}>Account Issue</option>
                        <option value="Marketplace" {{ old('subject') == 'Marketplace' ? 'selected' : '' }}>Marketplace</option>
                        <option value="Partnership" {{ old('subject') == 'Partnership' ? 'selected' : '' }}>Partnership</option>
                        <option value="Feedback" {{ old('subject') == 'Feedback' ? 'selected' : '' }}>Feedback</option>
                        <option value="Other" {{ old('subject') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="message">
                        <i class="fas fa-comment-dots"></i> Message
                    </label>
                    <textarea id="message" 
                              name="message" 
                              class="form-control" 
                              rows="6" 
                              placeholder="Tell us how we can help you..."
                              required>{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="submit-btn" id="submitBtn">
                    <span class="btn-text">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </span>
                    <span class="btn-spinner" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i> Sending...
                    </span>
                </button>
            </form>
        </div>

        <div class="contact-info-section">
            <h2>Get In Touch</h2>
            
            <div class="contact-info-card">
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h3>Email</h3>
                        <a href="mailto:info@supperage.com">info@supperage.com</a>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h3>Location</h3>
                        <p>Connecting Africa and the World</p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <h3>Response Time</h3>
                        <p>We typically respond within 24-48 hours</p>
                    </div>
                </div>
            </div>

            <div class="social-links">
                <h3>Follow Us</h3>
                <div class="social-icons">
                    <a href="#" class="social-icon facebook" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-icon twitter" title="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="social-icon instagram" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="social-icon linkedin" title="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>

            <div class="faq-link">
                <i class="fas fa-question-circle"></i>
                <div>
                    <h3>Have a quick question?</h3>
                    <p>Check out our <a href="{{ route('faq') }}">FAQ section</a> for instant answers</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.contact-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.contact-header {
    text-align: center;
    margin-bottom: 50px;
}

.contact-header h1 {
    font-size: 2.5em;
    color: #2c3e50;
    margin-bottom: 10px;
}

.contact-header p {
    font-size: 1.1em;
    color: #666;
}

.contact-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
}

.contact-form-section h2,
.contact-info-section h2 {
    font-size: 1.8em;
    color: #2c3e50;
    margin-bottom: 25px;
}

.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert i {
    font-size: 1.3em;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert ul {
    margin: 0;
    padding-left: 20px;
}

.contact-form {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 8px;
    font-size: 1em;
}

.form-group label i {
    color: #1DA1F2;
    margin-right: 5px;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 1em;
    transition: border-color 0.3s ease;
    font-family: inherit;
}

.form-control:focus {
    outline: none;
    border-color: #1DA1F2;
}

textarea.form-control {
    resize: vertical;
    min-height: 120px;
}

.submit-btn {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1.1em;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
}

.submit-btn i {
    margin-right: 8px;
}

.contact-info-section {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.contact-info-card {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

.info-item {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    padding: 20px 0;
    border-bottom: 1px solid #f0f0f0;
}

.info-item:last-child {
    border-bottom: none;
}

.info-item i {
    font-size: 2em;
    color: #1DA1F2;
    min-width: 40px;
}

.info-item h3 {
    font-size: 1.1em;
    color: #2c3e50;
    margin-bottom: 5px;
}

.info-item p,
.info-item a {
    color: #666;
    text-decoration: none;
    font-size: 1em;
}

.info-item a:hover {
    color: #1DA1F2;
}

.social-links {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 30px;
    border-radius: 15px;
    text-align: center;
}

.social-links h3 {
    color: white;
    margin-bottom: 20px;
    font-size: 1.3em;
}

.social-icons {
    display: flex;
    justify-content: center;
    gap: 15px;
}

.social-icon {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    color: white;
    font-size: 1.3em;
    transition: all 0.3s ease;
    text-decoration: none;
}

.social-icon:hover {
    background: white;
    transform: translateY(-5px);
}

.social-icon.facebook:hover { color: #3b5998; }
.social-icon.twitter:hover { color: #1DA1F2; }
.social-icon.instagram:hover { color: #E1306C; }
.social-icon.linkedin:hover { color: #0077b5; }

.faq-link {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    gap: 20px;
}

.faq-link i {
    font-size: 2.5em;
    color: #1DA1F2;
}

.faq-link h3 {
    color: #2c3e50;
    margin-bottom: 5px;
}

.faq-link p {
    color: #666;
    margin: 0;
}

.faq-link a {
    color: #1DA1F2;
    text-decoration: none;
    font-weight: 600;
}

.faq-link a:hover {
    text-decoration: underline;
}

@media (max-width: 968px) {
    .contact-content {
        grid-template-columns: 1fr;
    }
    
    .contact-header h1 {
        font-size: 2em;
    }
}

@media (max-width: 480px) {
    .contact-container {
        padding: 20px 10px;
    }
    
    .contact-form {
        padding: 20px;
    }
    
    .social-icons {
        flex-wrap: wrap;
    }
}

.submit-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.btn-spinner {
    display: none;
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnSpinner = submitBtn.querySelector('.btn-spinner');
    
    form.addEventListener('submit', function(e) {
        // Show spinner
        btnText.style.display = 'none';
        btnSpinner.style.display = 'inline-block';
        submitBtn.disabled = true;
        
        // The form will submit normally
        // Spinner will show until page reloads
    });
});
</script>
@endsection