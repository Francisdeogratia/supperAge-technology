
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
    <meta name="description" content="SupperAge notifications">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <meta http-equiv="X-UA-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Notifications – SupperAge</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/finebtn.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">

<style>
/* ── Page wrapper ── */
.notif-page {
    max-width: 720px;
    margin: 20px auto;
    padding: 0 14px 80px;
}

/* ── Header bar ── */
.notif-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 18px;
}
.notif-header h4 {
    font-size: 20px;
    font-weight: 700;
    color: #1c1e21;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.notif-header h4 .notif-count {
    background: #1877f2;
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
}
.notif-header-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.btn-notif-action {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-read-all  { background: #e7f3ff; color: #1877f2; }
.btn-read-all:hover  { background: #1877f2; color: #fff; }
.btn-delete-all { background: #fff0f0; color: #e74c3c; }
.btn-delete-all:hover { background: #e74c3c; color: #fff; }

/* ── Notification card ── */
.notif-card {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    background: #fff;
    border-radius: 12px;
    padding: 14px 16px;
    margin-bottom: 10px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.07);
    border-left: 4px solid transparent;
    transition: box-shadow 0.2s, background 0.2s;
    position: relative;
}
.notif-card:hover { box-shadow: 0 4px 14px rgba(0,0,0,0.1); }
.notif-card.unread {
    background: #f0f7ff;
    border-left-color: #1877f2;
}
.notif-card.missed-call {
    background: #fff8f0;
    border-left-color: #ff9800;
}
.notif-card.missed-call.unread {
    background: #ffe8cc;
}

/* ── Avatar ── */
.notif-avatar-wrap {
    position: relative;
    flex-shrink: 0;
}
.notif-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e4e6eb;
}
.notif-device-badge {
    position: absolute;
    top: -4px;
    left: -4px;
    font-size: 10px;
    line-height: 1;
}

/* ── Body ── */
.notif-body {
    flex: 1;
    min-width: 0;
}
.notif-sender {
    font-weight: 700;
    font-size: 14px;
    color: #1c1e21;
}
.notif-sender a { color: #1877f2; text-decoration: none; }
.notif-sender a:hover { text-decoration: underline; }
.notif-sender .status-text { font-weight: 400; color: #65676b; font-size: 12px; margin-left: 4px; }
.notif-message { font-size: 14px; color: #3e4150; margin: 4px 0 8px; line-height: 1.45; }
.missed-call-badge { font-weight: 600; color: #e65100; }
.notif-time { font-size: 12px; color: #8a8d91; }
.notif-actions { margin-top: 8px; display: flex; flex-wrap: wrap; gap: 6px; }

/* ── Delete btn ── */
.notif-delete-btn {
    position: absolute;
    top: 10px;
    right: 12px;
    background: none;
    border: none;
    color: #bec3c9;
    font-size: 15px;
    cursor: pointer;
    padding: 4px 6px;
    border-radius: 6px;
    line-height: 1;
    transition: color 0.15s, background 0.15s;
}
.notif-delete-btn:hover { color: #e74c3c; background: #fff0f0; }

/* ── Empty state ── */
.notif-empty {
    text-align: center;
    padding: 60px 20px;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.07);
}
.notif-empty i { font-size: 52px; color: #d0d3d9; margin-bottom: 14px; display: block; }
.notif-empty p { font-size: 16px; color: #65676b; font-weight: 500; }

/* ── Thumbnail ── */
.notif-thumb { max-width: 120px; border-radius: 8px; margin-top: 6px; }

/* ── Modern pagination ── */
.notif-pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    flex-wrap: wrap;
    margin-top: 28px;
    padding-bottom: 20px;
}
.notif-pagination a,
.notif-pagination span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 38px;
    height: 38px;
    padding: 0 10px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
}
.notif-pagination a {
    background: #fff;
    color: #1877f2;
    border: 1.5px solid #d8dadf;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
}
.notif-pagination a:hover {
    background: #1877f2;
    color: #fff;
    border-color: #1877f2;
    box-shadow: 0 4px 10px rgba(24,119,242,0.25);
}
.notif-pagination span.current {
    background: linear-gradient(135deg, #1877f2, #42a5f5);
    color: #fff;
    border: none;
    box-shadow: 0 4px 12px rgba(24,119,242,0.35);
}
.notif-pagination span.dots {
    background: none;
    border: none;
    color: #8a8d91;
    box-shadow: none;
    font-size: 16px;
}

/* ── Toast ── */
.notif-toast {
    position: fixed;
    bottom: 28px;
    left: 50%;
    transform: translateX(-50%) translateY(80px);
    background: #1c1e21;
    color: #fff;
    padding: 11px 22px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    z-index: 9999;
    opacity: 0;
    transition: all 0.3s ease;
    white-space: nowrap;
    pointer-events: none;
}
.notif-toast.show { transform: translateX(-50%) translateY(0); opacity: 1; }
.notif-toast.error { background: #e74c3c; }

/* Dark mode */
body.dark-mode .notif-header h4 { color: #E4E6EB; }
body.dark-mode .notif-card { background: #242526; border-left-color: transparent; }
body.dark-mode .notif-card.unread { background: #1c2a3a; border-left-color: #2D88FF; }
body.dark-mode .notif-sender { color: #E4E6EB; }
body.dark-mode .notif-message { color: #B0B3B8; }
body.dark-mode .notif-pagination a { background: #3A3B3C; color: #2D88FF; border-color: #4E4F50; }
body.dark-mode .notif-pagination a:hover { background: #2D88FF; color: #fff; border-color: #2D88FF; }

@media (max-width: 480px) {
    .notif-card { padding: 12px 12px 12px 14px; }
    .notif-avatar { width: 40px; height: 40px; }
    .btn-notif-action { padding: 6px 11px; font-size: 12px; }
    .notif-header h4 { font-size: 17px; }
}
</style>
</head>
<body>
@extends('layouts.app')
@include('layouts.navbar')

@php
  use Jenssegers\Agent\Agent;
  use App\Helpers\GeoHelper;
@endphp

@section('content')
<div class="notif-page">

    {{-- Header --}}
    <div class="notif-header">
        <h4>
            <i class="fas fa-bell" style="color:#1877f2;"></i>
            Notifications
            @php $unreadCount = $notifications->where('read_notification','no')->count(); @endphp
            @if($unreadCount > 0)
                <span class="notif-count">{{ $unreadCount }} new</span>
            @endif
        </h4>
        @if($notifications->count() > 0)
        <div class="notif-header-actions">
            <button class="btn-notif-action btn-read-all" id="readAllBtn">
                <i class="fas fa-check-double"></i> Read All
            </button>
            <button class="btn-notif-action btn-delete-all" id="deleteAllBtn">
                <i class="fas fa-trash-alt"></i> Delete All
            </button>
        </div>
        @endif
    </div>

    {{-- Notifications list --}}
    @forelse($notifications as $note)
    @php
        $sender       = $note->sender;
        $loginSession = $sender ? $sender->loginSession : null;
        $isOnline     = $sender ? $sender->isOnline : false;
        $lastSeen     = $sender ? $sender->lastSeen : 'Never';
        $isMobile     = $loginSession ? $loginSession->isMobile : false;
        $isUnread     = $note->read_notification === 'no';

        $isFriendNotification  = in_array($note->type, ['friend_request','friend_request_accepted','friend_request_rejected']);
        $isMessageNotification = $note->type === 'new_message';
        $isMissedCall          = $note->type === 'missed_call';

        if ($isFriendNotification) {
            $friendRequest = \App\Models\FriendRequest::find($note->notifiable_id);
        } elseif ($isMissedCall) {
            $callData   = json_decode($note->data, true);
            $callType   = $callData['call_type'] ?? 'audio';
            $callReason = $callData['reason'] ?? 'no_answer';
        } elseif (!$isMessageNotification) {
            $post      = \App\Models\SamplePost::find($note->notifiable_id);
            $files     = $post ? json_decode($post->file_path, true) : [];
            $firstFile = $files[0] ?? null;
        }
    @endphp

    <div class="notif-card {{ $isUnread ? 'unread' : '' }} {{ $isMissedCall ? 'missed-call' : '' }}"
         id="notif-{{ $note->id }}">

        {{-- Delete btn --}}
        <button class="notif-delete-btn" data-id="{{ $note->id }}" title="Delete">
            <i class="fas fa-times"></i>
        </button>

        {{-- Avatar --}}
        <div class="notif-avatar-wrap">
            <img src="{{ $sender && $sender->profileimg ? $sender->profileimg : asset('images/best3.png') }}"
                 class="notif-avatar"
                 alt="avatar"
                 title="{{ $isOnline ? 'Online now' : 'Last seen '.$lastSeen }}">
            <span class="notif-device-badge">{{ $isMobile ? '📱' : '🖥️' }}</span>
        </div>

        {{-- Body --}}
        <div class="notif-body">
            <div class="notif-sender">
                @if($sender && $sender->exists)
                    <a href="{{ url('/profile/'.$sender->id) }}">{{ $sender->username }}</a>
                    @if($sender->badge_status)
                        <img src="{{ asset($sender->badge_status) }}"
                             alt="Verified" title="Verified"
                             style="width:14px;height:14px;border-radius:50%;vertical-align:middle;margin-left:3px;">
                    @endif
                    <span class="status-text">· {{ $isOnline ? 'Online' : 'Offline' }}</span>
                @else
                    <span class="text-muted">Unknown User</span>
                @endif
            </div>

            <div class="notif-message">
                @if($note->type === 'friend_request')              👥 {{ $note->message }}
                @elseif($note->type === 'friend_request_accepted') ✅ {{ $note->message }}
                @elseif($note->type === 'friend_request_rejected') ❌ {{ $note->message }}
                @elseif($note->type === 'new_message')             💬 {{ $note->message }}
                @elseif($note->type === 'group_message')           💬 {{ $note->message }}
                @elseif($note->type === 'group_added')             👥 {{ $note->message }}
                @elseif($note->type === 'group_joined')            👥 {{ $note->message }}
                @elseif($isMissedCall)
                    @php $callIcon=$callType==='video'?'📹':'📞'; $reasonIcon=$callReason==='no_answer'?'⏰':'❌'; @endphp
                    <span class="missed-call-badge">{{ $reasonIcon }} {{ $callIcon }} {{ $note->message }}</span>
                @else
                    {{ $note->message }}
                @endif
            </div>

            {{-- Thumbnail --}}
            @if(!$isFriendNotification && !$isMessageNotification && !$isMissedCall && isset($firstFile))
                @if(Str::contains($firstFile,['image','jpg','jpeg','png','gif']))
                    <img src="{{ $firstFile }}" class="notif-thumb" alt="media">
                @elseif(Str::contains($firstFile,['video']))
                    <video src="{{ $firstFile }}" controls class="notif-thumb"></video>
                @endif
            @endif

            {{-- Action buttons --}}
            <div class="notif-actions">
            @if($note->type === 'new_message')
                <a href="{{ route('messages.chat',$note->user_id) }}" class="btn btn-sm btn-primary notification-link" data-notification-id="{{ $note->id }}">
                    <i class="fa fa-reply"></i> Reply
                </a>
            @elseif($note->type === 'group_message')
                @php $data=json_decode($note->data,true); $groupId=$data['group_id']??null; @endphp
                @if($groupId)
                <a href="{{ route('groups.show',$groupId) }}" class="btn btn-sm btn-success notification-link" data-notification-id="{{ $note->id }}">
                    <i class="fa fa-comments"></i> View Group
                </a>
                @endif
            @elseif($note->type==='group_added'||$note->type==='group_joined')
                @php $data=json_decode($note->data,true); $groupId=$data['group_id']??null; @endphp
                @if($groupId)
                <a href="{{ route('groups.show',$groupId) }}" class="btn btn-sm btn-primary notification-link" data-notification-id="{{ $note->id }}">
                    <i class="fa fa-users"></i> View Group
                </a>
                @endif
            @elseif($isMissedCall)
                <a href="{{ route('messages.chat',$note->user_id) }}" class="btn btn-sm btn-success notification-link" data-notification-id="{{ $note->id }}">
                    <i class="fa fa-phone"></i> Call Back
                </a>
                <a href="{{ route('messages.chat',$note->user_id) }}" class="btn btn-sm btn-primary notification-link" data-notification-id="{{ $note->id }}">
                    <i class="fa fa-comment"></i> Message
                </a>
            @elseif($note->type==='friend_request' && isset($friendRequest))
                <button class="btn btn-sm btn-success accept-friend-btn" data-request-id="{{ $friendRequest->id }}" data-notification-id="{{ $note->id }}">✓ Accept</button>
                <button class="btn btn-sm btn-danger reject-friend-btn" data-request-id="{{ $friendRequest->id }}" data-notification-id="{{ $note->id }}">✗ Reject</button>
                <a href="{{ route('friends.index') }}" class="btn btn-sm btn-outline-secondary notification-link" data-notification-id="{{ $note->id }}">Friends Page</a>
            @elseif($isFriendNotification)
                <a href="{{ route('friends.index') }}" class="btn btn-sm btn-primary notification-link" data-notification-id="{{ $note->id }}">View Friends</a>
            @elseif($note->type==='live_started')
                <a href="{{ $note->link }}" class="btn btn-sm btn-danger notification-link" data-notification-id="{{ $note->id }}"><i class="fas fa-video"></i> Watch Live</a>
            @elseif($note->type==='event_rsvp'||$note->type==='event_created'||$note->type==='event_reminder'||$note->type==='event_updated'||$note->type==='event_cancelled')
                @php $data=json_decode($note->data,true); $eventId=$data['event_id']??null; @endphp
                @if($eventId)
                <a href="{{ route('events.show',$eventId) }}" class="btn btn-sm btn-info notification-link" data-notification-id="{{ $note->id }}"><i class="fas fa-calendar"></i> View Event</a>
                @endif
            @elseif($note->type==='marketplace_store')
                <a href="{{ route('marketplace.my-store') }}" class="btn btn-sm btn-success notification-link" data-notification-id="{{ $note->id }}"><i class="fas fa-store"></i> My Store</a>
            @elseif($note->type==='marketplace_order'||$note->type==='marketplace_order_update')
                @php $data=json_decode($note->data,true); $orderNumber=$data['order_number']??null; @endphp
                @if($orderNumber)
                <a href="{{ route('marketplace.order-details',$orderNumber) }}" class="btn btn-sm btn-warning notification-link" data-notification-id="{{ $note->id }}"><i class="fas fa-shopping-cart"></i> View Order</a>
                @endif
            @elseif($note->type==='marketplace_order_placed')
                <a href="{{ route('marketplace.my-orders') }}" class="btn btn-sm btn-success notification-link" data-notification-id="{{ $note->id }}"><i class="fas fa-receipt"></i> My Orders</a>
            @elseif(in_array($note->type,['marketplace_subscription_expired','marketplace_subscription_reminder_7','marketplace_subscription_reminder_3','marketplace_subscription_reminder_1']))
                <a href="{{ route('marketplace.renew-subscription') }}" class="btn btn-sm btn-danger notification-link" data-notification-id="{{ $note->id }}"><i class="fas fa-exclamation-triangle"></i> Renew</a>
            @elseif($note->type==='ad_approved'||$note->type==='ad_rejected')
                <a href="{{ route('advertising.show',$note->notifiable_id) }}" class="btn btn-sm btn-primary notification-link" data-notification-id="{{ $note->id }}">View Ad</a>
            @else
                <a href="{{ route('notifications.markAndRedirect',$note->notifiable_id) }}" class="btn btn-sm btn-primary notification-link" data-notification-id="{{ $note->id }}">View Post</a>
            @endif
            </div>

            <div class="notif-time">{{ \Carbon\Carbon::parse($note->created_at)->diffForHumans() }}</div>
        </div>
    </div>
    @empty
    <div class="notif-empty">
        <i class="fas fa-bell-slash"></i>
        <p>You have no notifications yet</p>
    </div>
    @endforelse

    {{-- Modern Pagination --}}
    @if($notifications->hasPages())
    <nav class="notif-pagination">
        {{-- Previous --}}
        @if($notifications->onFirstPage())
            <span style="opacity:.4;cursor:default;background:#f0f2f5;color:#8a8d91;border:1.5px solid #d8dadf;min-width:38px;height:38px;display:inline-flex;align-items:center;justify-content:center;border-radius:10px;">
                <i class="fas fa-chevron-left"></i>
            </span>
        @else
            <a href="{{ $notifications->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
        @endif

        {{-- Page numbers --}}
        @foreach($notifications->getUrlRange(1, $notifications->lastPage()) as $page => $url)
            @if($page == $notifications->currentPage())
                <span class="current">{{ $page }}</span>
            @elseif($page == 1 || $page == $notifications->lastPage() || abs($page - $notifications->currentPage()) <= 2)
                <a href="{{ $url }}">{{ $page }}</a>
            @elseif(abs($page - $notifications->currentPage()) == 3)
                <span class="dots">···</span>
            @endif
        @endforeach

        {{-- Next --}}
        @if($notifications->hasMorePages())
            <a href="{{ $notifications->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
        @else
            <span style="opacity:.4;cursor:default;background:#f0f2f5;color:#8a8d91;border:1.5px solid #d8dadf;min-width:38px;height:38px;display:inline-flex;align-items:center;justify-content:center;border-radius:10px;">
                <i class="fas fa-chevron-right"></i>
            </span>
        @endif
    </nav>
    @endif

</div>

<div id="notifToast" class="notif-toast"></div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="{{ asset('myjs/bar.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="{{ asset('myjs/more_lesstext.js') }}"></script>
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
<script src="{{ asset('myjs/allpost.js') }}"></script>
<script src="{{ asset('myjs/tales.js') }}"></script>
<script src="{{ asset('myjs/searchuser.js') }}"></script>

<script>
const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function showToast(msg, isError) {
    const t = document.getElementById('notifToast');
    t.textContent = msg;
    t.className = 'notif-toast show' + (isError ? ' error' : '');
    setTimeout(() => { t.className = 'notif-toast'; }, 2600);
}

function removeCard(id) {
    const el = document.getElementById('notif-' + id);
    if (!el) return;
    el.style.transition = 'opacity 0.3s, transform 0.3s';
    el.style.opacity = '0';
    el.style.transform = 'translateX(40px)';
    setTimeout(() => el.remove(), 300);
}

// ── Delete single notification ──
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.notif-delete-btn');
    if (!btn) return;
    const id = btn.dataset.id;
    fetch(`/notifications/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) { removeCard(id); showToast('Notification deleted'); }
        else showToast(d.error || 'Failed', true);
    })
    .catch(() => showToast('Error deleting', true));
});

// ── Read All ──
document.getElementById('readAllBtn')?.addEventListener('click', function() {
    fetch('/notifications/read-all', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            document.querySelectorAll('.notif-card.unread').forEach(c => c.classList.remove('unread'));
            const badge = document.querySelector('.notif-count');
            if (badge) badge.remove();
            showToast('All notifications marked as read');
        } else showToast(d.error || 'Failed', true);
    })
    .catch(() => showToast('Error', true));
});

// ── Delete All ──
document.getElementById('deleteAllBtn')?.addEventListener('click', function() {
    if (!confirm('Delete all notifications? This cannot be undone.')) return;
    fetch('/notifications', {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            document.querySelectorAll('.notif-card').forEach(c => {
                c.style.transition = 'opacity 0.3s';
                c.style.opacity = '0';
                setTimeout(() => c.remove(), 300);
            });
            document.querySelector('.notif-header-actions')?.remove();
            document.querySelector('.notif-count')?.remove();
            showToast('All notifications deleted');
            setTimeout(() => {
                const page = document.querySelector('.notif-page');
                if (page && !page.querySelector('.notif-card')) {
                    page.insertAdjacentHTML('beforeend',
                        '<div class="notif-empty"><i class="fas fa-bell-slash"></i><p>You have no notifications yet</p></div>'
                    );
                }
            }, 400);
        } else showToast(d.error || 'Failed', true);
    })
    .catch(() => showToast('Error', true));
});

// ── Mark read on link click ──
document.querySelectorAll('.notification-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const id = this.dataset.notificationId;
        const url = this.href;
        fetch(`/notifications/${id}/mark-read`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf }
        }).finally(() => { window.location.href = url; });
    });
});

// ── Accept friend request ──
document.querySelectorAll('.accept-friend-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const requestId = this.dataset.requestId;
        const notifId   = this.dataset.notificationId;
        this.disabled = true;
        this.textContent = '...';
        fetch(`/friends/accept/${requestId}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                fetch(`/notifications/${notifId}/mark-read`, { method:'POST', headers:{'X-CSRF-TOKEN':csrf} });
                const card = document.getElementById('notif-' + notifId);
                if (card) card.classList.remove('unread');
                this.closest('.notif-actions').innerHTML = '<span class="badge badge-success">✓ Accepted</span>';
                showToast('Friend request accepted!');
            } else { showToast(d.error || 'Failed', true); this.disabled=false; this.textContent='✓ Accept'; }
        })
        .catch(() => { showToast('Error', true); this.disabled=false; this.textContent='✓ Accept'; });
    });
});

// ── Reject friend request ──
document.querySelectorAll('.reject-friend-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        if (!confirm('Reject this friend request?')) return;
        const requestId = this.dataset.requestId;
        const notifId   = this.dataset.notificationId;
        this.disabled = true;
        this.textContent = '...';
        fetch(`/friends/reject/${requestId}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                fetch(`/notifications/${notifId}/mark-read`, { method:'POST', headers:{'X-CSRF-TOKEN':csrf} });
                this.closest('.notif-actions').innerHTML = '<span class="badge badge-danger">✗ Rejected</span>';
                showToast('Friend request rejected');
            } else { showToast(d.error || 'Failed', true); this.disabled=false; this.textContent='✗ Reject'; }
        })
        .catch(() => { showToast('Error', true); this.disabled=false; this.textContent='✗ Reject'; });
    });
});
</script>

@endsection
</body>
</html>
