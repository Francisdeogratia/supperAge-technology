
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

    <title>wallet</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">

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
    <link rel="stylesheet" href="{{ asset('css/allbtns.css') }}">
    

    <!-- Scripts -->
    <style>

        </style>
    
</head>
<body>
<header class="fb-header mb-1">  
  <div class="fb-left">
    <a href="#"> 
      <img src="{{ asset('images/best3.png') }}" style="border-radius:50%;width: 50px;height: 50px;" class="justhide">
    </a>
    <div class="fb-logo"><span style="margin-left: 5px;">Supperage</span></div>

    <input type="text" name="search_user" class="search-bar search_user form-control input-sm" placeholder="Search..." autocomplete="off" /> 

    <div class="icon search" title="Search"><i class="fa fa-search crc"></i></div>
  </div>

  <div class="fb-icons">
    <div class="icon home" title="Home">
      <a href="{{ url('update') }}"><img src="{{ asset('images/home.png') }}" class="crc" alt="Home"/><span class="hss">Home</span></a>
    </div>

    <div class="icon watch" title="friend">
      <a href="{{ url('gotofrndpage') }}">
        <i class="fa fa-users crc"></i><span class="hss">Friends</span>
      </a>
    </div>

    <div class="icon market" title="message">
      <a href="{{ url('gotofrndpage') }}">
        <i class="fa fa-comment crc"></i><span class="hss">Messages</span>
             @if($unseenMessageCount > 0 || $unseenFileCount > 0)
    <span class="newmsg">{{ $unseenMessageCount + $unseenFileCount }}</span> 
   @else
    <span class="newmsg" style="display:none;">0</span>
@endif
      </a>
    </div>

    <div class="icon notifications" title="Notifications">
      <a href="{{ url('all_notify') }}">
        <i class="fa fa-bell crc"></i>
        <span class="hss">Notification</span>
         @if($totalNotifications > 0)
    <span class="badge">{{ $totalNotifications }}</span>
    @endif
      </a>
    </div>

    <div class="icon profile" title="Profile">
  @if($user->profileimg)
    <!-- <img 
      src="{{ str_replace('/upload/', '/upload/w_60,h_60,c_fill,r_max,q_70/', $user->profileimg) }}" 
      alt="Profile Image" 
      style="border-radius:50%; width:30px; height:30px;"
    /> -->
    <div class="profile-label" style="width:50px; position:relative;">
      <img 
        src="{{ str_replace('/upload/', '/upload/w_30,h_30,c_fill,r_max,q_70/', $user->profileimg) }}" 
        alt="Profile" 
        style="border-radius:50%; width:30px; height:30px;"
      >
       @if($user->badge_status)
        <img src="{{ asset($user->badge_status) }}" 
             alt="Verified" 
             title="Verified User" 
             style="width:19px;height:19px;vertical-align:middle;position:absolute;margin-left:14px;top:16px;">
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
        <p><a href="{{ url('settings2') }}">Settings</a></p>
        <p><a href="{{ url('logout') }}">Logout</a></p>
        </div>
      </div>
    </div>

    <!-- 🌙 Dark Mode Toggle -->
    <div class="icon dark-toggle" title="Dark Mode">
      <!-- <i class="fa fa-moon" style="color:darkblue;"></i> -->
      <i class="fa fa-sun" style="color:blue;"></i>
  </div>
  </div>
  
</header>

@php
  $loginSession = $walletOwner->lastLoginSession ?? null;
  $isOnline = $loginSession && $loginSession->logout_at === null;
  $lastSeen = $loginSession && $loginSession->logout_at
      ? \Carbon\Carbon::parse($loginSession->logout_at)->diffForHumans()
      : 'Online now';
@endphp
<div style="display:flex; justify-content:center; align-items:center;">
    <div style="text-align:center;position:relative;">
        <h4 style="color:lightgray; font-size:small;">Fund {{ $walletOwner->name }}'s Wallet</h4>
        <div>
        <img src="{{ $walletOwner->profileimg ?? asset('images/default-avatar.png') }}" 
             alt="Profile" width="80" height="80" style="border-radius:50%;">
                 @if($walletOwner->badge_status)
        <img src="{{ asset($walletOwner->badge_status) }}" 
             alt="Verified" 
             title="Verified User" 
             style="width:40px;height:40px;position:absolute;top:80px;left:53%;">
    @endif
    </div>
    <small style="font-size: small;font-weight:bold;color:blue;">{{ $isOnline ? 'Online now' : 'Last seen : ' . $lastSeen }}</small>
    <p style="font-size:small;">You are  about to fund {{ $walletOwner->name }}'s Wallet </p>
        <form action="{{ route('wallet.fund.process') }}" method="POST" style="margin-top:20px;">
            @csrf
            <input type="hidden" name="wallet_owner_id" value="{{ $walletOwner->id }}">

            <div class="form-group">
                <label for="currency">Select Currency:</label>
                <select name="currency" id="currency" class="form-control" required>
                    @foreach($currencies as $code => $name)
                        <option value="{{ $code }}">{{ $name }} ({{ $code }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="amount" class="mt-2">Enter Amount:</label>
                <input type="number" name="amount" id="amount" class="form-control" required min="1" step="0.01">
            </div>
              <div class="wrap">
                <button type="submit" class="button mt-2">Proceed to Payment</button>
              </div>
            
        </form>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/js/all.min.js"></script> -->
   
    
<script src="{{ asset('myjs/more_lesstext.js') }}"></script>
<script src="{{ asset('myjs/menu_pop_up_post.js') }}"></script>

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

</script>

<script src="{{ asset('myjs/allpost.js') }}"></script>
<script src="{{ asset('myjs/tales.js') }}"></script>

@include('partials.global-calls')
</body>
</html>