<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />

    {{-- SEO Title --}}
    <title>@yield('seo_title', 'SupperAge - Chat, Share, Earn & Shop | Social-Financial Platform')</title>

    {{-- SEO Description --}}
    <meta name="description" content="@yield('seo_description', 'SupperAge is the social-financial app where you can chat, share, earn, shop, create stores, fund wallets, and withdraw money. A complete ecosystem combining social networking with financial services.')">

    {{-- SEO Keywords --}}
    <meta name="keywords" content="@yield('seo_keywords', 'SupperAge, social financial app, earn money online, chat and earn, online marketplace, digital wallet, social networking, e-commerce platform, fund wallet, withdraw money')">

    <meta name="author" content="SupperAge">
    <meta name="robots" content="@yield('seo_robots', 'index, follow')">
    <link rel="canonical" href="@yield('seo_canonical', url()->current())">

    {{-- Open Graph (Facebook, LinkedIn) --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="SupperAge">
    <meta property="og:title" content="@yield('seo_title', 'SupperAge - Chat, Share, Earn & Shop | Social-Financial Platform')">
    <meta property="og:description" content="@yield('seo_description', 'SupperAge is the social-financial app where you can chat, share, earn, shop, create stores, fund wallets, and withdraw money.')">
    <meta property="og:url" content="@yield('seo_canonical', url()->current())">
    <meta property="og:image" content="@yield('og_image', asset('images/apple-touch-icon.png'))">
    <meta property="og:locale" content="en_US">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('seo_title', 'SupperAge - Chat, Share, Earn & Shop | Social-Financial Platform')">
    <meta name="twitter:description" content="@yield('seo_description', 'SupperAge is the social-financial app where you can chat, share, earn, shop, create stores, fund wallets, and withdraw money.')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/apple-touch-icon.png'))">

    {{-- Favicon --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('head')
</head>
<body>
    <div class="container">
        @yield('content')
   <!-- #region -->
    @if(session('success'))
    <div class="alert alert-success text-center mt-2">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger text-center">{{ session('error') }}</div>
@endif
@if(session('reward'))
    <div class="alert alert-info text-center">{{ session('reward') }}</div>
@endif


    </div>


   <!-- @if(Session::get('role') === 'admin')
    <a href="{{ route('admin.dashboard.now') }}" class="btn btn-danger">
        <i class="fas fa-shield-alt"></i> Admin Panel
    </a> -->
@endif

{{-- ========== GLOBAL INCOMING CALL SYSTEM ========== --}}
@if(Session::get('id'))
@php $globalUserId = Session::get('id'); @endphp

{{-- Incoming Call Overlay (visible on ALL pages) --}}
<div id="globalCallOverlay" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; z-index:99999; background:linear-gradient(180deg, #0B3D35 0%, #0A1E1A 100%); flex-direction:column; align-items:center; justify-content:center; padding:40px 20px;">
    <div style="text-align:center;">
        <div style="width:100px; height:100px; border-radius:50%; border:3px solid #00A884; margin:0 auto 15px; overflow:hidden; animation:globalCallPulse 2s infinite; background:#1a3a33; display:flex; align-items:center; justify-content:center;">
            <i class="fa fa-user" style="font-size:40px; color:#00A884;"></i>
        </div>
        <h2 id="globalCallCallerName" style="color:white; margin:0 0 8px; font-size:24px;"></h2>
        <p id="globalCallStatus" style="color:#8696A0; margin:0; font-size:16px;">Incoming call...</p>
    </div>
    <div style="display:flex; gap:40px; margin-top:50px;">
        <div style="text-align:center;">
            <button id="globalDeclineBtn" style="width:65px; height:65px; border-radius:50%; background:#f44336; border:none; color:white; font-size:24px; cursor:pointer;">
                <i class="fa fa-phone-slash"></i>
            </button>
            <p style="color:#8696A0; font-size:12px; margin-top:8px;">Decline</p>
        </div>
        <div style="text-align:center;">
            <button id="globalAcceptBtn" style="width:65px; height:65px; border-radius:50%; background:#4CAF50; border:none; color:white; font-size:24px; cursor:pointer;">
                <i class="fa fa-phone"></i>
            </button>
            <p style="color:#8696A0; font-size:12px; margin-top:8px;">Accept</p>
        </div>
    </div>
</div>

<style>
@keyframes globalCallPulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(0, 168, 132, 0.4); }
    50% { box-shadow: 0 0 0 20px rgba(0, 168, 132, 0); }
}
</style>

<script>
(function() {
    // Skip on the chat page — chat.blade.php has its own call handler
    if (document.getElementById('callOverlay')) return;

    const globalUserId = {{ $globalUserId }};
    let gAudioCtx = null;
    let gOscillator = null;
    let gGainNode = null;
    let gRingtoneTimer = null;
    let gCurrentCallId = null;
    let gCurrentNotification = null;

    function gPlayRingtone() {
        gStopRingtone();
        try {
            gAudioCtx = new (window.AudioContext || window.webkitAudioContext)();
            function beep() {
                if (!gAudioCtx) return;
                gOscillator = gAudioCtx.createOscillator();
                gGainNode = gAudioCtx.createGain();
                gOscillator.connect(gGainNode);
                gGainNode.connect(gAudioCtx.destination);
                gOscillator.frequency.value = 800;
                gGainNode.gain.value = 0.3;
                gOscillator.start();
                gOscillator.stop(gAudioCtx.currentTime + 0.3);
                gRingtoneTimer = setTimeout(beep, 800);
            }
            beep();
        } catch (e) { console.error('Ringtone error:', e); }
    }

    function gStopRingtone() {
        if (gRingtoneTimer) { clearTimeout(gRingtoneTimer); gRingtoneTimer = null; }
        try {
            if (gOscillator) { gOscillator.stop(); gOscillator.disconnect(); gOscillator = null; }
        } catch (e) {}
        if (gGainNode) { try { gGainNode.disconnect(); } catch(e) {} gGainNode = null; }
        if (gAudioCtx) { try { gAudioCtx.close(); } catch(e) {} gAudioCtx = null; }
    }

    function gShowNotification(callerName, callType) {
        if ('Notification' in window && Notification.permission === 'granted') {
            gCurrentNotification = new Notification('Incoming ' + callType + ' Call', {
                body: callerName + ' is calling you',
                icon: '{{ asset("images/favicon-32x32.png") }}',
                tag: 'incoming-call',
                requireInteraction: true
            });
            gCurrentNotification.onclick = function() { window.focus(); gCurrentNotification.close(); };
        }
    }

    function gHandleIncoming(callId, callerName, callType, callerId) {
        gCurrentCallId = callId;
        document.getElementById('globalCallCallerName').textContent = callerName;
        document.getElementById('globalCallStatus').textContent = 'Incoming ' + callType + ' call...';
        document.getElementById('globalCallOverlay').style.display = 'flex';
        gPlayRingtone();
        gShowNotification(callerName, callType);

        // Auto-timeout after 45 seconds
        setTimeout(function() {
            if (document.getElementById('globalCallOverlay').style.display === 'flex' && gCurrentCallId === callId) {
                gDecline();
            }
        }, 45000);
    }

    function gAccept() {
        gStopRingtone();
        if (gCurrentNotification) gCurrentNotification.close();
        document.getElementById('globalCallOverlay').style.display = 'none';
        // Navigate to the call page to handle the WebRTC connection
        if (gCurrentCallId) {
            window.location.href = '/calls/' + gCurrentCallId;
        }
    }

    function gDecline() {
        gStopRingtone();
        if (gCurrentNotification) gCurrentNotification.close();
        document.getElementById('globalCallOverlay').style.display = 'none';
        if (gCurrentCallId) {
            fetch('/calls/' + gCurrentCallId + '/decline', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            }).catch(function(e) { console.error('Decline error:', e); });
            gCurrentCallId = null;
        }
    }

    // Wire up buttons
    document.getElementById('globalAcceptBtn').addEventListener('click', gAccept);
    document.getElementById('globalDeclineBtn').addEventListener('click', gDecline);

    // Request notification permission
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }

    // Listen for incoming calls via Echo
    function initGlobalCallListener() {
        if (typeof window.Echo === 'undefined') {
            // Echo may not be loaded yet, retry
            setTimeout(initGlobalCallListener, 1000);
            return;
        }
        console.log('Global call listener active for user', globalUserId);
        window.Echo.private('users.' + globalUserId)
            .listen('IncomingCallEvent', function(e) {
                console.log('Global incoming call:', e);
                gHandleIncoming(e.callId, e.callerName, e.callType, e.callerId);
            });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initGlobalCallListener);
    } else {
        initGlobalCallListener();
    }

    // Cleanup on page leave
    window.addEventListener('beforeunload', gStopRingtone);
})();
</script>
@endif
{{-- ========== END GLOBAL INCOMING CALL ========== --}}

</body>
</html>
