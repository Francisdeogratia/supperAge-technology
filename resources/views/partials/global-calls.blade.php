{{-- ========== GLOBAL INCOMING CALL SYSTEM ========== --}}
{{-- Include this partial on ALL pages (layout and standalone) --}}
@if(Session::get('id'))
@php
    $globalUserId = Session::get('id');
    $globalUserGroups = \App\Models\GroupMember::where('user_id', $globalUserId)->pluck('group_id')->toArray();
@endphp

{{-- Load Echo/Vite if not already loaded (standalone pages need this) --}}
@if(empty($__echoLoaded))
@vite(['resources/js/app.js'])
@endif

{{-- Incoming Private Call Overlay --}}
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

{{-- Incoming Group Call Overlay --}}
<div id="globalGroupCallOverlay" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; z-index:99999; background:linear-gradient(180deg, #0B3D35 0%, #0A1E1A 100%); flex-direction:column; align-items:center; justify-content:center; padding:40px 20px;">
    <div style="text-align:center;">
        <div style="width:100px; height:100px; border-radius:50%; border:3px solid #00A884; margin:0 auto 15px; overflow:hidden; animation:globalCallPulse 2s infinite; background:#1a3a33; display:flex; align-items:center; justify-content:center;">
            <i class="fa fa-users" style="font-size:40px; color:#00A884;"></i>
        </div>
        <h2 id="globalGroupCallName" style="color:white; margin:0 0 8px; font-size:24px;"></h2>
        <p id="globalGroupCallStatus" style="color:#8696A0; margin:0; font-size:16px;">Incoming group call...</p>
    </div>
    <div style="display:flex; gap:40px; margin-top:50px;">
        <div style="text-align:center;">
            <button id="globalGroupDeclineBtn" style="width:65px; height:65px; border-radius:50%; background:#f44336; border:none; color:white; font-size:24px; cursor:pointer;">
                <i class="fa fa-phone-slash"></i>
            </button>
            <p style="color:#8696A0; font-size:12px; margin-top:8px;">Decline</p>
        </div>
        <div style="text-align:center;">
            <button id="globalGroupJoinBtn" style="width:65px; height:65px; border-radius:50%; background:#4CAF50; border:none; color:white; font-size:24px; cursor:pointer;">
                <i class="fa fa-phone"></i>
            </button>
            <p style="color:#8696A0; font-size:12px; margin-top:8px;">Join</p>
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
    // Prevent double-initialization if partial is included multiple times
    if (window._globalCallSystemLoaded) return;
    window._globalCallSystemLoaded = true;

    const globalUserId = {{ $globalUserId }};
    const userGroups = @json($globalUserGroups);

    let gAudioCtx = null;
    let gOscillator = null;
    let gGainNode = null;
    let gRingtoneTimer = null;
    let gCurrentCallId = null;
    let gCurrentCallerId = null;
    let gCurrentNotification = null;

    // Group call state
    let gCurrentGroupCallId = null;
    let gCurrentGroupId = null;
    let gCurrentGroupCallType = null;
    let gGroupNotification = null;

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

    function gShowNotification(title, body) {
        if ('Notification' in window && Notification.permission === 'granted') {
            var notif = new Notification(title, {
                body: body,
                icon: '{{ asset("images/favicon-32x32.png") }}',
                tag: 'incoming-call',
                requireInteraction: true
            });
            notif.onclick = function() { window.focus(); notif.close(); };
            return notif;
        }
        return null;
    }

    // ---- Private call handling ----
    function gHandleIncoming(callId, callerName, callType, callerId) {
        if (window._inPrivateChat && window._inPrivateChat == callerId) return;

        gCurrentCallId = callId;
        gCurrentCallerId = callerId;
        document.getElementById('globalCallCallerName').textContent = callerName;
        document.getElementById('globalCallStatus').textContent = 'Incoming ' + callType + ' call...';
        document.getElementById('globalCallOverlay').style.display = 'flex';
        gPlayRingtone();
        gCurrentNotification = gShowNotification('Incoming ' + callType + ' Call', callerName + ' is calling you');

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
        if (gCurrentCallId) {
            window.location.href = '/calls/' + gCurrentCallId + '?auto_accept=1';
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
            gCurrentCallerId = null;
        }
    }

    // ---- Group call handling ----
    function gHandleIncomingGroupCall(e) {
        gCurrentGroupCallId = e.callId;
        gCurrentGroupId = e.groupId;
        gCurrentGroupCallType = e.callType;
        document.getElementById('globalGroupCallName').textContent = e.groupName;
        document.getElementById('globalGroupCallStatus').textContent = e.initiatorName + ' started a ' + e.callType + ' call';
        document.getElementById('globalGroupCallOverlay').style.display = 'flex';
        gPlayRingtone();
        gGroupNotification = gShowNotification('Group ' + e.callType + ' Call', e.initiatorName + ' started a call in ' + e.groupName);

        setTimeout(function() {
            if (document.getElementById('globalGroupCallOverlay').style.display === 'flex' && gCurrentGroupCallId === e.callId) {
                gGroupDecline();
            }
        }, 45000);
    }

    function gGroupJoin() {
        gStopRingtone();
        if (gGroupNotification) gGroupNotification.close();
        document.getElementById('globalGroupCallOverlay').style.display = 'none';
        if (gCurrentGroupCallId && gCurrentGroupId) {
            window.location.href = '/groups/' + gCurrentGroupId + '?join_call=' + gCurrentGroupCallId + '&call_type=' + (gCurrentGroupCallType || 'audio');
        }
    }

    function gGroupDecline() {
        gStopRingtone();
        if (gGroupNotification) gGroupNotification.close();
        document.getElementById('globalGroupCallOverlay').style.display = 'none';
        if (gCurrentGroupCallId) {
            fetch('/group-calls/' + gCurrentGroupCallId + '/decline', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            }).catch(function(e) { console.error('Group decline error:', e); });
            gCurrentGroupCallId = null;
            gCurrentGroupId = null;
            gCurrentGroupCallType = null;
        }
    }

    // Wire up buttons
    document.getElementById('globalAcceptBtn').addEventListener('click', gAccept);
    document.getElementById('globalDeclineBtn').addEventListener('click', gDecline);
    document.getElementById('globalGroupJoinBtn').addEventListener('click', gGroupJoin);
    document.getElementById('globalGroupDeclineBtn').addEventListener('click', gGroupDecline);

    // Request notification permission
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }

    // Listen for incoming calls via Echo
    function initGlobalCallListener() {
        if (typeof window.Echo === 'undefined') {
            setTimeout(initGlobalCallListener, 1000);
            return;
        }
        console.log('Global call listener active for user', globalUserId);

        // Private call listener
        window.Echo.private('users.' + globalUserId)
            .listen('IncomingCallEvent', function(e) {
                console.log('Global incoming call:', e);
                gHandleIncoming(e.callId, e.callerName, e.callType, e.callerId);
            });

        // Group call listeners for all user's groups
        userGroups.forEach(function(gid) {
            window.Echo.private('group.' + gid)
                .listen('.GroupCallInitiated', function(e) {
                    if (window._inGroupChat == e.groupId) return;
                    if (e.initiatorId == globalUserId) return;
                    console.log('Global incoming group call:', e);
                    gHandleIncomingGroupCall(e);
                });
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
