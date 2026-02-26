@php
    $currentPath = request()->path();
@endphp

{{-- Dark Mode Stylesheet --}}
<link rel="stylesheet" href="{{ asset('css/darkmode.css') }}">

{{-- Top Header (Image, Search Input, Search Icon) --}}
{{-- Desktop Header --}}
<header class="fb-header mb-1">  
    <div class="fb-left">
        <a href="#"> 
            <img src="{{ asset('images/best3.png') }}" style="border-radius:50%;width: 50px;height: 50px;" >
        </a>
        <div class="fb-logo "><span class="mr-3" >SupperAge</span></div>
<!-- search users -->
        <div class="search-container" style="position: relative; flex: 1; max-width: 400px;">
    <input 
        type="text" 
        name="search_user" 
        class="search-bar search_user form-control input-sm" 
        placeholder="Search users...or Ask AGE AI" 
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

            <!-- Add this to your main site navbar/header -->
<!-- This button will only show for admin users -->

@if(Session::get('role') === 'admin')
    <!-- Option 1: Simple Button -->
    <a href="{{ route('admin.dashboard.now') }}" class="btn btn-danger">
        <i class="fas fa-shield-alt"></i> Admin Panel
    </a>

    {{-- - <a href="{{ route('admin.dashboard.now') }}">Dashboard</a>
<a href="{{ route('admin.users.now') }}">Users</a>
<a href="{{ route('admin.payment-applications.now') }}">Payments</a>--}}
@endif

<!-- CSS for Floating Action Button -->
<style>
.admin-fab {
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.admin-fab:hover {
    transform: scale(1.1);
    box-shadow: 0 10px 25px rgba(220, 53, 69, 0.5) !important;
}
</style>



                <!-- AI & Communication -->
                <a href="{{ route('age-ai.chat') }}" class="plus-dropdown-item">
                    <i class="fas fa-robot" style="color: #8A2BE2;"></i>
                    <span>Ask AGE AI</span>
                </a>
                
                <a href="{{ route('account.settings') }}" class="nav-link">
                    <i class="fas fa-cog"></i> Account Settings
                    </a>
                    
                    <a href="{{ route('child.safety.policy') }}" class="nav-link">
    <i class="fas fa-shield-alt"></i> Child Safety
</a>
                    
                    
                    
                <a href="{{ route('events.index') }}" class="plus-dropdown-item">
                        <i class="fas fa-calendar-alt" style="color: #FF4500;"></i>
                        <span>Create Event</span>
                    </a> 
                 <a href="{{ route('live.index') }}" class="plus-dropdown-item">
                    <i class="fas fa-video" style="color: #FF0000;"></i>
                    <span>Go Live</span>
                </a> 

                <a href="{{ route('inbox.index') }}">
    <i class="fa fa-star ml-4"></i>
    <span>Check Inbox</span>
    @php
        $unreadCount = DB::table('admin_messages')
            ->where('user_id', Session::get('id'))
            ->where('is_read', 0)
            ->count();
    @endphp
    @if($unreadCount > 0)
        <span id="inbox-badge" style="background: red; color: white; padding: 2px 8px; border-radius: 50%; font-size: 11px; margin-left: 5px;">
            {{ $unreadCount }}
        </span>
    @endif
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
                        <span>Promote Your Account,post,tales etc</span>
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
                    <a href="{{ route('payment.apply') }}" class="btn btn-success">
                        <i class="fas fa-money-check-alt"></i> Apply for payment
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
    <div class="icon home {{ $currentPath == 'update' ? 'active' : '' }}" title="Home">
      <a href="{{ url('update') }}"><img src="{{ asset('images/home.png') }}" class="crc" alt="Home"/><span class="hss">Home</span></a>
    </div>

    <div class="icon watch {{ str_starts_with($currentPath, 'friends') ? 'active' : '' }}" title="friend">
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

    <div class="icon market {{ str_starts_with($currentPath, 'messages') ? 'active' : '' }}" title="message">
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

    <div class="icon notifications {{ str_starts_with($currentPath, 'notifications') ? 'active' : '' }}" title="Notifications">
      <a href="{{ route('user.notifications') }}">
        <i class="fa fa-bell crc"></i>
        <span class="hss">Notification</span>
        @if($totalNotifications > 0)
          <span class="badge">{{ $totalNotifications }}</span>
        @endif
      </a>
    </div>

    <div class="icon profile" title="Profile">
      @if($user->profileimg)
        <div class="profile-label" style="width:50px; position:relative;">
          <img 
            src="{{ str_replace('/upload/', '/upload/w_30,h_30,c_fill,r_max,q_70/', $user->profileimg) }}" 
            alt="Profile" 
            style="border-radius:50%; width:30px; height:30px;"
          >
          @if($user->badge_expired)
            <i class="fas fa-clock" title="Badge Expired – Please Renew" style="color:#e74c3c;font-size:16px;margin-right:10px;"></i>
          @elseif($user->badge_status)
            <img src="{{ asset($user->badge_status) }}"
                 alt="Verified"
                 title="Verified User"
                 style="width:19px;height:19px;margin-right:10px;">
          @endif
          <span style="margin-left: 6px;"></span>
        </div>
      @else
        <div class="profile-label" style="width:50px;">
          <i class="fa fa-user-circle crc" style="font-size:30px; color:#555;"></i>
          <span style="margin-left: 0px;"></span>
        </div>
      @endif

      <div class="dropdown profile-dropdown">
        <div class="divmenu">
          <p><a href="{{ route('profile.edit') }}">Edit your profile</a></p>
          <p><a href="{{ url('privacy-policy') }}">Settings & Policy</a></p>
          <p><a href="{{ url('logout') }}">Logout</a></p>
          <p><a href="{{ route('child.safety.policy') }}" class="nav-link">
                <i class="fas fa-shield-alt"></i> Child Safety
            </a></p>
          <p><a href="{{ route('account.settings') }}" class="">
                <i class="fas fa-cog"></i> 
                <span>Manage Account</span>
            </a>
            </p>
        </div>
      </div>
    </div>

    {{-- Dark Mode Toggle --}}
    <div class="icon dark-toggle" title="Dark Mode">
      <i class="fa fa-sun" style="color:blue;"></i>
    </div>
  </div>
</header>


{{-- Mobile Bottom Navigation (Fixed at Bottom) --}}

{{-- Mobile Bottom Navigation --}}
<nav class="mobile-bottom-nav">
    <a href="{{ url('update') }}" class="nav-item {{ $currentPath == 'update' ? 'active' : '' }}">
        <i class="fa fa-home"></i>
        <span>Home</span>
    </a>

    <a href="{{ route('friends.index') }}" class="nav-item {{ str_starts_with($currentPath, 'friends') ? 'active' : '' }}">
        <i class="fa fa-users"></i>
        <span>Add Friends</span>
        @if($friendRequestCount > 0)
            <span class="nav-badge">{{ $friendRequestCount }}</span>
        @endif
    </a>

    <a href="{{ route('messages.index') }}" class="nav-item {{ str_starts_with($currentPath, 'messages') ? 'active' : '' }}">
        <i class="fa fa-comment"></i>
        <span>Chats</span>
        @if($unreadMessageCount > 0)
            <span class="nav-badge">{{ $unreadMessageCount }}</span>
        @endif
    </a>

    {{-- Groups Nav Item --}}
    <a href="{{ route('groups.index') }}" class="nav-item {{ str_starts_with($currentPath, 'groups') ? 'active' : '' }}">
        <i class="fa fa-users"></i>
        <span>Groups</span>
        @php
            $unreadGroupsCount = 0;
        @endphp
        @if($unreadGroupsCount > 0)
            <span class="nav-badge">{{ $unreadGroupsCount }}</span>
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

    {{-- Profile with Dropdown --}}
<div class="nav-item profile-menu-wrapper">
    <div class="profile-trigger" onclick="toggleMobileProfileMenu(event)">
      @if($user->profileimg)
        <div style="position: relative; display: inline-block;">
          <img 
            src="{{ str_replace('/upload/', '/upload/w_24,h_24,c_fill,r_max,q_70/', $user->profileimg) }}" 
            alt="Profile" 
            class="nav-profile-img"
          >
          @if($user->badge_expired)
            <i class="fas fa-clock" title="Badge Expired – Please Renew" style="color:#e74c3c;font-size:13px;position:absolute;bottom:-2px;right:-2px;"></i>
          @elseif($user->badge_status)
            <img src="{{ asset($user->badge_status) }}"
                 alt="Verified"
                 title="Verified User"
                 style="width:15px;height:15px;position:absolute;bottom:-2px;right:-2px;border-radius:50%;border:1px solid white;">
          @endif
        </div>
      @else
        <i class="fa fa-user-circle"></i>
      @endif
      <span>Profile</span>
    </div>

    {{-- Mobile Profile Dropdown Menu --}}
    <div class="mobile-profile-dropdown" id="mobileProfileDropdown">
      <div class="mobile-dropdown-header">
        <div style="display: flex; align-items: center; padding: 10px;">
          @if($user->profileimg)
            <img 
              src="{{ str_replace('/upload/', '/upload/w_40,h_40,c_fill,r_max,q_70/', $user->profileimg) }}" 
              alt="Profile" 
              style="width:40px;height:40px;border-radius:50%;margin-right:10px;"
            >
          @else
            <i class="fa fa-user-circle" style="font-size:40px;margin-right:10px;color:#555;"></i>
          @endif
          <div>
            <strong>{{ $user->name }}</strong>
            <small class="d-block text-muted" style="font-size:12px;">{{ '@' . $user->username }}</small>
          </div>
        </div>
      </div>
      
      <div class="mobile-dropdown-menu">
        <a href="{{ route('profile.edit') }}" class="mobile-menu-item">
          <i class="fa fa-user"></i>
          <span>Edit your profile</span>
        </a>
        
        <a href="{{ url('privacy-policy') }}" class="mobile-menu-item">
          <i class="fa fa-cog"></i>
          <span>Settings & Policy</span>
        </a>
        
        <a href="{{ route('account.settings') }}" class="mobile-menu-item">
                <i class="fas fa-cog"></i> 
                <span>Manage Account</span>
            </a>
            
            <a href="{{ route('child.safety.policy') }}" class="nav-link">
    <i class="fas fa-shield-alt"></i> <span>Child Safety</span>
</a>
        
        <div class="mobile-menu-item" onclick="toggleDarkMode(event)">
          <i class="fa fa-moon dark-mode-icon"></i>
          <span>Dark Mode</span>
          <span class="toggle-switch" id="darkModeToggle">
            <span class="toggle-slider"></span>
          </span>
        </div>
        
        <hr style="margin: 5px 0; border-color: #e0e0e0;">
        
        <a href="{{ url('logout') }}" class="mobile-menu-item logout-item">
          <i class="fa fa-sign-out"></i>
          <span>Logout</span>
        </a>
      </div>
    </div>


  </div>
</nav>

{{-- Mobile Plus Dropdown --}}
<div class="mobile-plus-dropdown" id="mobilePlusDropdown">
    {{-- Same dropdown items as desktop --}}
    <a href="{{ route('age-ai.chat') }}" class="plus-dropdown-item">
        <i class="fas fa-robot" style="color: #8A2BE2;"></i>
        <span>Ask AGE AI</span>
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

    <a href="{{ route('inbox.index') }}">
    <i class="fa fa-star ml-4"></i>
    <span>Check Inbox</span>
    @php
        $unreadCount = DB::table('admin_messages')
            ->where('user_id', Session::get('id'))
            ->where('is_read', 0)
            ->count();
    @endphp
    @if($unreadCount > 0)
        <span id="inbox-badge" style="background: red; color: white; padding: 2px 8px; border-radius: 50%; font-size: 11px; margin-left: 5px;">
            {{ $unreadCount }}
        </span>
    @endif
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
            <span>Promote Your Account,post,tales etc</span>
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
        <a href="{{ route('payment.apply') }}" class="btn btn-success">
                        <i class="fas fa-money-check-alt"></i> Apply for payment
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
                    
                    <a href="{{ route('child.safety.policy') }}" class="nav-link">
    <i class="fas fa-shield-alt"></i> Child Safety
</a>
                    
                    
        <a href="{{ route('contact') }}" class="plus-dropdown-item">
    <i class="fas fa-envelope" style="color: #1DA1F2;"></i>
    <span>Contact Us</span>
</a>
    </div>
</div>

{{-- Overlay --}}
<div class="dropdown-overlay" id="dropdownOverlay" onclick="closeAllDropdowns()"></div>


<style>
  .fb-logo span {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 20px;
    font-weight: 700;
    color: #d4eef7ff;
    /* color: #ffffff; White text looks better with a glow */
    display: inline-flex;
    margin-right: 4px;
    /* align-items: center; */
    /* Stronger, more attractive shadow */
    text-shadow: 0 0 10px #1265e0, 0 0 20px rgba(0, 191, 255, 0.4);
  }

  /* The Sparkling Star */
  .fb-logo span::before {
    /* Sparkle character */
    content: url("{{ asset('images/favicon-16x16.png') }}");
    color: #00bfff;
    /* margin-right: 1px; */
    font-size: 5px;
    /* Sparkle glow */
    text-shadow: 0 0 8px #fff;
    /* Subtle twinkle animation */
    animation: twinkle 1.5s infinite alternate;
  }

  @keyframes twinkle {
    from { opacity: 0.5; transform: scale(0.8); }
    to { opacity: 1; transform: scale(1.1); }
  }

  /* Active nav item styles - Mobile */
  .mobile-bottom-nav .nav-item.active {
    color: #1877f2 !important;
    border-top: 3px solid #1877f2;
    background: rgba(24, 119, 242, 0.1);
  }

  .mobile-bottom-nav .nav-item.active i,
  .mobile-bottom-nav .nav-item.active span {
    color: #1877f2 !important;
  }

  /* Active nav item styles - Desktop */
  .fb-icons .icon.active {
    border-bottom: 3px solid #1877f2;
    background: rgba(24, 119, 242, 0.1);
    border-radius: 8px;
  }

  .fb-icons .icon.active a,
  .fb-icons .icon.active i,
  .fb-icons .icon.active .crc {
    color: #1877f2 !important;
  }

  /* Floating Back Button */
  .back-btn-float {
    position: fixed;
    bottom: 80px;
    left: 16px;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #fff;
    color: #333;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    cursor: pointer;
    z-index: 999;
    transition: all 0.2s;
    font-size: 18px;
  }
  .back-btn-float:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 22px rgba(0,0,0,0.22);
    background: #f0f0f0;
  }
  .back-btn-float:active {
    transform: scale(0.95);
  }
  body.dark-mode .back-btn-float {
    background: #3A3B3C;
    color: #E4E6EB;
    box-shadow: 0 4px 16px rgba(0,0,0,0.35);
  }
  body.dark-mode .back-btn-float:hover {
    background: #4a4b4d;
  }
  @media (min-width: 769px) {
    .back-btn-float {
      bottom: 24px;
      left: 24px;
    }
  }
</style>

{{-- Floating Back Button (hidden on home page) --}}
@if(request()->path() !== '/' && request()->path() !== 'home')
<button class="back-btn-float" onclick="window.history.back()" title="Go back">
    <i class="fas fa-arrow-left"></i>
</button>
@endif