
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

    <title>Notifications</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">

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


    <!-- Scripts -->
    <style>
  .notification-card.unread {
    background-color: #f5faff;
    border-left: 4px solid #007bff;
  }
  .hover-card {
  position: absolute;
  background: white;
  border: 1px solid #ccc;
  padding: 10px;
  z-index: 10;
  display: none;
  width: 250px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  opacity: 0;
  transform: translateY(10px);
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.sender-name:hover + .hover-card {
  display: block;
  opacity: 1;
  transform: translateY(0);
}




.notification-card.unread {
  background-color: #f0f8ff;
  border-left: 4px solid #007bff;
}

.notification-card {
  transition: all 0.3s ease;
}

.notification-card:hover {
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>


    
</head>
<body>
  @extends('layouts.app')
<!-- Your notify  navbar content  -->
    @include('layouts.navbar')



@php
  use Jenssegers\Agent\Agent;
@endphp



@php
  use App\Helpers\GeoHelper;
@endphp





@section('content')
<div class="container position-relative">
  <h4>Your Notifications</h4>

  {{-- Loop through notifications --}}
  @forelse($notifications as $note)
    @php
      $sender = $note->sender;
      $loginSession = $sender ? $sender->loginSession : null;
      $isOnline = $sender ? $sender->isOnline : false;
      $lastSeen = $sender ? $sender->lastSeen : 'Never';
      $statusColor = $isOnline ? 'Online' : 'Offline';
      $device = $loginSession ? $loginSession->device : 'Unknown';
      $location = $loginSession ? GeoHelper::lookup($loginSession->ip_address ?? '') : 'Unknown';
      $isUnread = $note->read_notification === 'no';
      
      
// Check notification type
      $isFriendNotification = in_array($note->type, ['friend_request', 'friend_request_accepted', 'friend_request_rejected']);
      $isMessageNotification = $note->type === 'new_message';
      $isMissedCallNotification = $note->type === 'missed_call';

// Get related data based on type
      if ($isFriendNotification) {
          $friendRequest = \App\Models\FriendRequest::find($note->notifiable_id);
      } elseif ($isMissedCallNotification) {
          $callData = json_decode($note->data, true);
          $callType = $callData['call_type'] ?? 'audio';
          $callReason = $callData['reason'] ?? 'no_answer';
      } elseif (!$isMessageNotification) {
          $post = \App\Models\SamplePost::find($note->notifiable_id);
          $files = $post ? json_decode($post->file_path, true) : [];
          $firstFile = $files[0] ?? null;
      }
      
      $isMobile = $loginSession ? $loginSession->isMobile : false;
    @endphp

    <div class="card mb-3 notification-card {{ $isUnread ? 'unread' : '' }} {{ $isMissedCallNotification ? 'missed-call-notification' : '' }}">
      <div class="card-body d-flex flex-column flex-md-row align-items-start">
        {{-- Sender avatar --}}
        <div class="position-relative me-3 mb-3 mb-md-0">
          <img src="{{ $sender && $sender->profileimg ? $sender->profileimg : asset('images/best3.png') }}"
               alt="Profile Image"
               class="rounded-circle"
               style="width: 50px; height: 50px; object-fit: cover; border-radius:50%;"
               title="{{ $isOnline ? 'Online now' : 'Last seen ' . $lastSeen }}">
          @if($isMobile)
            <span style="position: absolute; top: -5px; left: -5px; background-color: #007bff; color: white; font-size: 10px; padding: 2px 4px; border-radius: 3px;">📱</span>
          @else
            <span style="position: absolute; top: 5px; left: 5px; background-color: #28a745; color: white; font-size: 10px; padding: 2px 4px; border-radius: 3px;">🖥️</span>
          @endif
        </div>

        {{-- Notification details --}}
        <div style="flex: 1; position: relative;">
          <strong class="sender-name" style="cursor: pointer;">
            @if($sender && $sender->exists)
              <a href="{{ url('/profile/' . $sender->id) }}">{{ $sender->username }}</a>
              <i class="text-muted">{{ $statusColor }}</i>
            @else
              <span class="text-muted">Unknown User (deleted account)</span>
            @endif
          </strong>

          <div class="hover-card">
            @if($sender)
              <strong>{{ $sender->name }}</strong><br>
              <small>{{ $sender->bio }}</small><br>
              <small>{{ $sender->country }}, {{ $sender->state }}</small><br>
              <small>Role: {{ ucfirst($sender->role) }}</small><br>
              @if($sender->badge_status === 'verified')
                <span style="color: green;">✔ Verified</span>
              @endif
              <small>Gender: {{ ucfirst($sender->gender) }}</small><br>
              <small>DOB: {{ \Carbon\Carbon::parse($sender->dob)->format('M d, Y') }}</small><br>
              <small>Status: {{ $isOnline ? 'Online now' : 'Last seen ' . $lastSeen }}</small><br>
              <small>Location: {{ $location }}</small><br>
              <small>Device: {{ $device }}</small><br>
            @else
              <small>User no longer exists</small><br>
            @endif
          </div>

         <p class="mb-1 mt-2">
    @if($note->type === 'friend_request')
        👥 {{ $note->message }}
    @elseif($note->type === 'friend_request_accepted')
        ✅ {{ $note->message }}
    @elseif($note->type === 'friend_request_rejected')
        ❌ {{ $note->message }}
    @elseif($note->type === 'new_message')
        💬 {{ $note->message }}
    @elseif($note->type === 'group_message')
        💬 {{ $note->message }}
    @elseif($note->type === 'group_added')
        👥 {{ $note->message }}
    @elseif($note->type === 'group_joined')
        👥 {{ $note->message }}
    @elseif($note->type === 'missed_call')
        @php
            $callIcon = $callType === 'video' ? '📹' : '📞';
            $reasonIcon = $callReason === 'no_answer' ? '⏰' : '❌';
        @endphp
        <span class="missed-call-badge">
            {{ $reasonIcon }} {{ $callIcon }} {{ $note->message }}
        </span>
    @else
        {{ $note->message }}
    @endif
</p>
         
          {{-- Thumbnail preview for post notifications --}}
          @if(!$isFriendNotification && !$isMessageNotification && !$isMissedCallNotification && isset($firstFile))
            @if(Str::contains($firstFile, ['image', 'jpg', 'jpeg', 'png', 'gif']))
                <img src="{{ $firstFile }}" alt="Shared Image" class="img-fluid rounded" style="max-width: 50%;">
            @elseif(Str::contains($firstFile, ['video']))
                <video src="{{ $firstFile }}" controls class="img-fluid rounded" style="max-width: 50%;"></video>
            @endif
          @endif

         {{-- REPLACE the entire action buttons section in your notifications.blade.php --}}
{{-- Action buttons based on notification type --}}
@if($note->type === 'new_message')
    <a href="{{ route('messages.chat', $note->user_id) }}" 
       class="btn btn-sm btn-primary mt-2 notification-link"
       data-notification-id="{{ $note->id }}">
        <i class="fa fa-reply"></i> Reply to Message
    </a>

@elseif($note->type === 'group_message')
    @php
        $data = json_decode($note->data, true);
        $groupId = $data['group_id'] ?? null;
    @endphp
    @if($groupId)
        <a href="{{ route('groups.show', $groupId) }}" 
           class="btn btn-sm btn-success mt-2 notification-link"
           data-notification-id="{{ $note->id }}">
            <i class="fa fa-comments"></i> View Group Chat
        </a>
    @endif

@elseif($note->type === 'group_added' || $note->type === 'group_joined')
    @php
        $data = json_decode($note->data, true);
        $groupId = $data['group_id'] ?? null;
    @endphp
    @if($groupId)
        <a href="{{ route('groups.show', $groupId) }}" 
           class="btn btn-sm btn-primary mt-2 notification-link"
           data-notification-id="{{ $note->id }}">
            <i class="fa fa-users"></i> View Group
        </a>
    @endif

@elseif($note->type === 'missed_call')
    <div class="mt-2">
        <a href="{{ route('messages.chat', $note->user_id) }}" 
           class="btn btn-sm btn-success notification-link"
           data-notification-id="{{ $note->id }}">
            <i class="fa fa-phone"></i> Call Back
        </a>
        <a href="{{ route('messages.chat', $note->user_id) }}" 
           class="btn btn-sm btn-primary ms-2 notification-link"
           data-notification-id="{{ $note->id }}">
            <i class="fa fa-comment"></i> Send Message
        </a>
    </div>

@elseif($note->type === 'friend_request' && isset($friendRequest))
    <div class="mt-2">
        <button class="btn btn-sm btn-success accept-friend-btn" 
                data-request-id="{{ $friendRequest->id }}"
                data-notification-id="{{ $note->id }}">
            ✓ Accept
        </button>
        <button class="btn btn-sm btn-danger reject-friend-btn ms-2" 
                data-request-id="{{ $friendRequest->id }}"
                data-notification-id="{{ $note->id }}">
            ✗ Reject
        </button>
        <a href="{{ route('friends.index') }}" 
           class="btn btn-sm btn-primary ms-2 notification-link"
           data-notification-id="{{ $note->id }}">
            View Friends Page
        </a>
    </div>

@elseif($isFriendNotification)
    <a href="{{ route('friends.index') }}" 
       class="btn btn-sm btn-primary mt-2 notification-link"
       data-notification-id="{{ $note->id }}">
        View Friends Page
    </a>

@elseif($note->type === 'live_started')
    <a href="{{ $note->link }}" 
        class="btn btn-sm btn-danger mt-2 notification-link"
        data-notification-id="{{ $note->id }}">
        <i class="fas fa-video"></i> Watch Live Stream
    </a>

{{-- 🎉 EVENT NOTIFICATIONS --}}
@elseif($note->type === 'event_rsvp')
    @php
        $data = json_decode($note->data, true);
        $eventId = $data['event_id'] ?? null;
    @endphp
    @if($eventId)
        <a href="{{ route('events.show', $eventId) }}" 
           class="btn btn-sm btn-info mt-2 notification-link"
           data-notification-id="{{ $note->id }}">
            <i class="fas fa-calendar-check"></i> View Event
        </a>
    @endif

@elseif($note->type === 'event_created')
    @php
        $data = json_decode($note->data, true);
        $eventId = $data['event_id'] ?? null;
    @endphp
    @if($eventId)
        <a href="{{ route('events.show', $eventId) }}" 
           class="btn btn-sm btn-success mt-2 notification-link"
           data-notification-id="{{ $note->id }}">
            <i class="fas fa-calendar-plus"></i> View New Event
        </a>
    @endif

@elseif($note->type === 'event_reminder')
    @php
        $data = json_decode($note->data, true);
        $eventId = $data['event_id'] ?? null;
    @endphp
    @if($eventId)
        <a href="{{ route('events.show', $eventId) }}" 
           class="btn btn-sm btn-warning mt-2 notification-link"
           data-notification-id="{{ $note->id }}">
            <i class="fas fa-bell"></i> View Event Details
        </a>
    @endif

@elseif($note->type === 'event_updated')
    @php
        $data = json_decode($note->data, true);
        $eventId = $data['event_id'] ?? null;
    @endphp
    @if($eventId)
        <a href="{{ route('events.show', $eventId) }}" 
           class="btn btn-sm btn-primary mt-2 notification-link"
           data-notification-id="{{ $note->id }}">
            <i class="fas fa-sync"></i> View Updated Event
        </a>
    @endif

@elseif($note->type === 'event_cancelled')
    @php
        $data = json_decode($note->data, true);
        $eventId = $data['event_id'] ?? null;
    @endphp
    @if($eventId)
        <a href="{{ route('events.index') }}" 
           class="btn btn-sm btn-danger mt-2 notification-link"
           data-notification-id="{{ $note->id }}">
            <i class="fas fa-times-circle"></i> Browse Other Events
        </a>
    @endif

{{-- 🛒 MARKETPLACE NOTIFICATIONS --}}
@elseif($note->type === 'marketplace_store')
    <a href="{{ route('marketplace.my-store') }}" 
       class="btn btn-sm btn-success mt-2 notification-link"
       data-notification-id="{{ $note->id }}">
        <i class="fas fa-store"></i> View My Store
    </a>

@elseif($note->type === 'marketplace_order')
    @php
        $data = json_decode($note->data, true);
        $orderNumber = $data['order_number'] ?? null;
    @endphp
    @if($orderNumber)
        <a href="{{ route('marketplace.order-details', $orderNumber) }}" 
           class="btn btn-sm btn-warning mt-2 notification-link"
           data-notification-id="{{ $note->id }}">
            <i class="fas fa-shopping-cart"></i> View Order Details
        </a>
    @else
        <a href="{{ route('marketplace.my-store') }}" 
           class="btn btn-sm btn-warning mt-2 notification-link"
           data-notification-id="{{ $note->id }}">
            <i class="fas fa-shopping-cart"></i> View Orders
        </a>
    @endif

@elseif($note->type === 'marketplace_order_placed')
    <a href="{{ route('marketplace.my-orders') }}" 
       class="btn btn-sm btn-success mt-2 notification-link"
       data-notification-id="{{ $note->id }}">
        <i class="fas fa-receipt"></i> View My Orders
    </a>

@elseif($note->type === 'marketplace_order_update')
    @php
        $data = json_decode($note->data, true);
        $orderNumber = $data['order_number'] ?? null;
    @endphp
    @if($orderNumber)
        <a href="{{ route('marketplace.order-details', $orderNumber) }}" 
           class="btn btn-sm btn-info mt-2 notification-link"
           data-notification-id="{{ $note->id }}">
            <i class="fas fa-box"></i> Track Order
        </a>
    @endif

@elseif($note->type === 'marketplace_new_product')
    @php
        $data = json_decode($note->data, true);
        $productSlug = $data['product_slug'] ?? null;
        $storeId = $data['store_id'] ?? null;
    @endphp
    @if($productSlug)
        <a href="{{ route('marketplace.view-product', $productSlug) }}" 
           class="btn btn-sm btn-primary mt-2 notification-link"
           data-notification-id="{{ $note->id }}">
            <i class="fas fa-box-open"></i> View New Product
        </a>
    @elseif($storeId)
        <a href="{{ route('marketplace.view-store', $storeId) }}" 
           class="btn btn-sm btn-primary mt-2 notification-link"
           data-notification-id="{{ $note->id }}">
            <i class="fas fa-store"></i> View Store
        </a>
    @endif

@elseif(in_array($note->type, ['marketplace_subscription_expired', 'marketplace_subscription_reminder_7', 'marketplace_subscription_reminder_3', 'marketplace_subscription_reminder_1']))
    <a href="{{ route('marketplace.renew-subscription') }}" 
       class="btn btn-sm btn-danger mt-2 notification-link"
       data-notification-id="{{ $note->id }}">
        <i class="fas fa-exclamation-triangle"></i> Renew Subscription
    </a>

{{-- 📢 ADVERTISEMENT NOTIFICATIONS --}}
@elseif($note->type === 'new_advertisement')
    @php
        $data = json_decode($note->data, true);
        $adId = $data['ad_id'] ?? null;
    @endphp
    @if($adId)
        <a href="{{ $note->link }}" 
           class="btn btn-sm btn-primary mt-2 notification-link"
           data-notification-id="{{ $note->id }}"
           target="_blank">
            <i class="fas fa-external-link-alt"></i> View Advertisement
        </a>
    @endif

@elseif($note->type === 'ad_approved')
    <a href="{{ route('advertising.show', $note->notifiable_id) }}" 
       class="btn btn-sm btn-success mt-2 notification-link"
       data-notification-id="{{ $note->id }}">
        <i class="fas fa-check-circle"></i> View Ad Campaign
    </a>

@elseif($note->type === 'ad_rejected')
    <a href="{{ route('advertising.show', $note->notifiable_id) }}" 
       class="btn btn-sm btn-danger mt-2 notification-link"
       data-notification-id="{{ $note->id }}">
        <i class="fas fa-times-circle"></i> View Rejection Reason
    </a>

{{-- Default fallback for post notifications --}}
@else 
    <a href="{{ route('notifications.markAndRedirect', $note->notifiable_id) }}" 
        class="btn btn-sm btn-primary mt-2 notification-link"
        data-notification-id="{{ $note->id }}">
        View Post
    </a>
@endif


        </div>

        <small class="text-muted ms-3">{{ \Carbon\Carbon::parse($note->created_at)->diffForHumans() }}</small>
      </div>
    </div>
@empty
    <p>No notifications yet.</p>
@endforelse

<style>
.missed-call-notification {
    border-left: 4px solid #ff9800 !important;
    background-color: #fff3e0 !important;
}

.missed-call-notification.unread {
    background-color: #ffe0b2 !important;
}

.missed-call-badge {
    font-weight: 600;
    color: #e65100;
}
</style>

  {{-- Pagination --}}
  <div class="d-flex justify-content-center mt-4">
    {{ $notifications->links() }}
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
<script src="{{ asset('myjs/bar.js') }}"></script> <!-- this should come last -->
<!-- <script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@4.6.1/dist/emoji-button.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/js/all.min.js"></script> -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>

    
<script src="{{ asset('myjs/more_lesstext.js') }}"></script>
<script src="{{ asset('myjs/menu_pop_up_post.js') }}"></script>
<script src="{{ asset('myjs/allpost.js') }}"></script>
<script src="{{ asset('myjs/tales.js') }}"></script>
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
<script src="{{ asset('myjs/searchuser.js') }}"></script>

<script>


document.addEventListener('DOMContentLoaded', function() {
  // Get CSRF token
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
  
  // ========================================
  // MARK NOTIFICATION AS READ (Generic)
  // ========================================
  function markNotificationAsRead(notificationId, callback) {
    fetch(`/notifications/${notificationId}/mark-read`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Update the notification badge count
        updateNotificationBadge();
        
        // Execute callback if provided
        if (callback) callback();
      }
    })
    .catch(error => {
      console.error('Error marking notification as read:', error);
      // Still execute callback even if marking fails
      if (callback) callback();
    });
  }
  
  // ========================================
  // UPDATE NOTIFICATION BADGE COUNT
  // ========================================
  function updateNotificationBadge() {
    const badges = document.querySelectorAll('.icon.notifications .badge, .nav-item .nav-badge');
    badges.forEach(badge => {
      let count = parseInt(badge.textContent) || 0;
      if (count > 0) {
        count--;
        if (count === 0) {
          badge.style.display = 'none';
        } else {
          badge.textContent = count;
        }
      }
    });
  }
  
  // ========================================
  // HANDLE MESSAGE/CALL NOTIFICATION CLICKS
  // ========================================
  document.querySelectorAll('.notification-link').forEach(link => {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      const notificationId = this.getAttribute('data-notification-id');
      const targetUrl = this.getAttribute('href');
      
      // Mark as read, then redirect
      markNotificationAsRead(notificationId, () => {
        window.location.href = targetUrl;
      });
    });
  });
  
  // ========================================
  // FRIEND REQUEST: ACCEPT
  // ========================================
  document.querySelectorAll('.accept-friend-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const requestId = this.getAttribute('data-request-id');
      const notificationId = this.getAttribute('data-notification-id');
      const button = this;
      
      // Disable button to prevent double clicks
      button.disabled = true;
      button.textContent = 'Processing...';
      
      fetch(`/friends/accept/${requestId}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Mark notification as read
          markNotificationAsRead(notificationId, () => {
            button.closest('.notification-card').classList.remove('unread');
            button.closest('.notification-card').classList.add('bg-success-subtle');
            button.parentElement.innerHTML = '<span class="badge bg-success">✓ Friend request accepted</span>';
            alert('Friend request accepted!');
          });
        } else {
          alert(data.error || 'Failed to accept friend request');
          button.disabled = false;
          button.textContent = '✓ Accept';
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while accepting the friend request');
        button.disabled = false;
        button.textContent = '✓ Accept';
      });
    });
  });

  // ========================================
  // FRIEND REQUEST: REJECT
  // ========================================
  document.querySelectorAll('.reject-friend-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const requestId = this.getAttribute('data-request-id');
      const notificationId = this.getAttribute('data-notification-id');
      const button = this;
      
      if (confirm('Are you sure you want to reject this friend request?')) {
        button.disabled = true;
        button.textContent = 'Processing...';
        
        fetch(`/friends/reject/${requestId}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Mark notification as read
            markNotificationAsRead(notificationId, () => {
              button.closest('.notification-card').classList.remove('unread');
              button.closest('.notification-card').classList.add('bg-danger-subtle');
              button.parentElement.innerHTML = '<span class="badge bg-danger">✗ Friend request rejected</span>';
              alert('Friend request rejected');
            });
          } else {
            alert(data.error || 'Failed to reject friend request');
            button.disabled = false;
            button.textContent = '✗ Reject';
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('An error occurred while rejecting the friend request');
          button.disabled = false;
          button.textContent = '✗ Reject';
        });
      }
    });
  });
});





// document.addEventListener('DOMContentLoaded', function() {
//   // Get CSRF token
//   const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
  
//   // Accept friend request from notification
//   document.querySelectorAll('.accept-friend-btn').forEach(btn => {
//     btn.addEventListener('click', function() {
//       const requestId = this.getAttribute('data-request-id');
//       const notificationId = this.getAttribute('data-notification-id');
//       const button = this;
      
//       // Disable button to prevent double clicks
//       button.disabled = true;
//       button.textContent = 'Processing...';
      
//       fetch(`/friends/accept/${requestId}`, {
//         method: 'POST',
//         headers: {
//           'Content-Type': 'application/json',
//           'X-CSRF-TOKEN': csrfToken
//         }
//       })
//       .then(response => {
//         console.log('Response status:', response.status);
//         return response.json();
//       })
//       .then(data => {
//         console.log('Response data:', data);
//         if (data.success) {
//           // Mark notification as read
//           fetch(`/notifications/${notificationId}/mark-read`, {
//             method: 'POST',
//             headers: {
//               'Content-Type': 'application/json',
//               'X-CSRF-TOKEN': csrfToken
//             }
//           });
          
//           button.closest('.notification-card').classList.add('bg-success-subtle');
//           button.parentElement.innerHTML = '<span class="badge bg-success">✓ Friend request accepted</span>';
//           alert('Friend request accepted!');
//         } else {
//           alert(data.error || 'Failed to accept friend request');
//           button.disabled = false;
//           button.textContent = '✓ Accept';
//         }
//       })
//       .catch(error => {
//         console.error('Error:', error);
//         alert('An error occurred while accepting the friend request');
//         button.disabled = false;
//         button.textContent = '✓ Accept';
//       });
//     });
//   });

//   // Reject friend request from notification
//   document.querySelectorAll('.reject-friend-btn').forEach(btn => {
//     btn.addEventListener('click', function() {
//       const requestId = this.getAttribute('data-request-id');
//       const notificationId = this.getAttribute('data-notification-id');
//       const button = this;
      
//       if (confirm('Are you sure you want to reject this friend request?')) {
//         // Disable button
//         button.disabled = true;
//         button.textContent = 'Processing...';
        
//         fetch(`/friends/reject/${requestId}`, {
//           method: 'POST',
//           headers: {
//             'Content-Type': 'application/json',
//             'X-CSRF-TOKEN': csrfToken
//           }
//         })
//         .then(response => {
//           console.log('Response status:', response.status);
//           return response.json();
//         })
//         .then(data => {
//           console.log('Response data:', data);
//           if (data.success) {
//             // Mark notification as read
//             fetch(`/notifications/${notificationId}/mark-read`, {
//               method: 'POST',
//               headers: {
//                 'Content-Type': 'application/json',
//                 'X-CSRF-TOKEN': csrfToken
//               }
//             });
            
//             button.closest('.notification-card').classList.add('bg-danger-subtle');
//             button.parentElement.innerHTML = '<span class="badge bg-danger">✗ Friend request rejected</span>';
//             alert('Friend request rejected');
//           } else {
//             alert(data.error || 'Failed to reject friend request');
//             button.disabled = false;
//             button.textContent = '✗ Reject';
//           }
//         })
//         .catch(error => {
//           console.error('Error:', error);
//           alert('An error occurred while rejecting the friend request');
//           button.disabled = false;
//           button.textContent = '✗ Reject';
//         });
//       }
//     });
//   });
// });
</script>

</body>
</html>