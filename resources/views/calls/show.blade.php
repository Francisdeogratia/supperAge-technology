
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ ucfirst($call->call_type) }} Call with {{ $friend->name }}</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @vite(['resources/js/app.js'])
    
    <style>
        /* === RESET === */
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }
        
        html {
            width: 100%;
            height: 100%;
            overflow: hidden;
        }
        
        body { 
            width: 100%;
            height: 100%;
            overflow: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex; 
            justify-content: center; 
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }
        
        /* === DESKTOP STYLES === */
        .call-container { 
            text-align: center; 
            background: rgba(255,255,255,0.1); 
            backdrop-filter: blur(10px);
            padding: 40px; 
            border-radius: 20px; 
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 90%;
        }
        
        .friend-info { 
            margin-bottom: 30px; 
        }
        
        .friend-info img { 
            width: 120px; 
            height: 120px; 
            border-radius: 50%; 
            object-fit: cover; 
            border: 4px solid rgba(255,255,255,0.3);
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }
        
        .friend-info h2 { 
            margin: 15px 0 5px 0; 
            font-size: 28px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .remote-status {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            margin-left: 15px;
            padding: 8px 12px;
            background: rgba(0,0,0,0.2);
            border-radius: 20px;
        }
        
        .remote-status i {
            font-size: 20px;
            transition: all 0.3s ease;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
        }
        
        .status-badge { 
            display: inline-block; 
            padding: 8px 20px; 
            border-radius: 25px; 
            font-weight: bold; 
            margin: 15px 0;
            background: rgba(255,255,255,0.2);
            animation: pulse 2s infinite;
            transition: all 0.3s ease;
        }
        
        .status-badge.ringing {
            background: rgba(76, 175, 80, 0.3);
            animation: pulse 1s infinite;
        }
        
        .status-badge.calling {
            background: rgba(255, 193, 7, 0.3);
        }
        
        .status-badge.connected {
            background: rgba(76, 175, 80, 0.4);
            animation: none;
        }
        
        .status-badge.disconnected {
            background: rgba(244, 67, 54, 0.4);
            animation: pulse 1.5s infinite;
        }
        
        .call-timer {
            font-size: 32px;
            font-weight: bold;
            margin: 20px 0;
            display: none;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        #videoContainer { 
            width: 100%; 
            height: 350px; 
            background: #000; 
            margin: 20px 0; 
            border-radius: 15px;
            overflow: hidden;
            position: relative;
            display: none;
        }
        
        #localVideo, #remoteVideo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        #localVideo {
            position: absolute;
            width: 150px;
            height: 150px;
            bottom: 20px;
            right: 20px;
            border-radius: 10px;
            border: 2px solid white;
            z-index: 10;
        }
        
        .call-buttons { 
            display: flex; 
            justify-content: center; 
            gap: 20px; 
            margin-top: 30px;
        }
        
        .call-buttons button { 
            background: rgba(255,255,255,0.3); 
            border: none; 
            padding: 18px; 
            border-radius: 50%; 
            color: white; 
            font-size: 24px; 
            cursor: pointer; 
            width: 65px;
            height: 65px;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .call-buttons button:hover { 
            transform: scale(1.1);
            background: rgba(255,255,255,0.4);
        }
        
        .call-buttons .end-call { 
            background: #f44336;
            width: 80px;
            height: 80px;
            font-size: 28px;
        }
        
        .call-buttons .end-call:hover { 
            background: #d32f2f;
            transform: scale(1.15);
        }
        
        .call-buttons button.muted,
        .call-buttons button.video-off {
            background: rgba(244, 67, 54, 0.7);
        }
        
        .accept-call {
            background: #4CAF50 !important;
        }
        
        .accept-call:hover {
            background: #45a049 !important;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
    </style>
    
    <!-- MOBILE CSS IN SEPARATE STYLE TAG TO AVOID CONFLICTS -->
    <style media="screen and (max-width: 768px)">
        html, body {
            width: 100vw !important;
            height: 100vh !important;
            max-width: 100vw !important;
            max-height: 100vh !important;
            overflow: hidden !important;
            position: fixed !important;
        }
        
        .call-container {
            width: 100vw !important;
            height: 100vh !important;
            max-width: 100vw !important;
            max-height: 100vh !important;
            border-radius: 0 !important;
            padding: 15px !important;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            box-shadow: none !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            backdrop-filter: none !important;
        }
        
        .friend-info {
            margin-bottom: 10px !important;
            flex-shrink: 0 !important;
        }
        
        .friend-info img {
            width: 100px !important;
            height: 100px !important;
            border: 3px solid rgba(255,255,255,0.5) !important;
        }
        
        .friend-info h2 {
            font-size: 32px !important;
            font-weight: 800 !important;
            margin: 12px 0 8px 0 !important;
            letter-spacing: 0.5px !important;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.4) !important;
        }
        
        .remote-status {
            display: inline-flex !important;
            gap: 12px !important;
            padding: 10px 15px !important;
            margin: 8px 0 0 0 !important;
            background: rgba(0,0,0,0.3) !important;
        }
        
        .remote-status i {
            font-size: 22px !important;
        }
        
        .status-badge {
            padding: 12px 24px !important;
            font-size: 18px !important;
            font-weight: 800 !important;
            margin: 15px 0 !important;
            border-radius: 30px !important;
        }
        
        .call-timer {
            font-size: 48px !important;
            font-weight: 900 !important;
            margin: 20px 0 !important;
            letter-spacing: 2px !important;
            text-shadow: 4px 4px 8px rgba(0,0,0,0.5) !important;
        }
        
        #videoContainer {
            width: 100% !important;
            height: 55vh !important;
            min-height: 200px !important;
            max-height: 450px !important;
            margin: 15px 0 !important;
            border-radius: 12px !important;
            flex: 1 !important;
        }
        
        #localVideo {
            width: 120px !important;
            height: 160px !important;
            bottom: 15px !important;
            right: 15px !important;
            border-radius: 10px !important;
            border: 3px solid white !important;
        }
        
        .call-buttons {
            gap: 25px !important;
            margin-top: 20px !important;
            margin-bottom: 10px !important;
            flex-shrink: 0 !important;
            padding: 0 !important;
        }
        
        .call-buttons button {
            width: 70px !important;
            height: 70px !important;
            font-size: 26px !important;
            padding: 0 !important;
            flex-shrink: 0 !important;
        }
        
        .call-buttons .end-call {
            width: 85px !important;
            height: 85px !important;
            font-size: 30px !important;
        }
        
        .call-buttons button:active {
            transform: scale(0.9) !important;
        }
    </style>
    
    <!-- EXTRA SMALL PHONES -->
    <style media="screen and (max-width: 400px)">
        .friend-info img {
            width: 85px !important;
            height: 85px !important;
        }
        
        .friend-info h2 {
            font-size: 28px !important;
        }
        
        .remote-status i {
            font-size: 20px !important;
        }
        
        .status-badge {
            padding: 10px 20px !important;
            font-size: 16px !important;
        }
        
        .call-timer {
            font-size: 42px !important;
        }
        
        #videoContainer {
            height: 50vh !important;
        }
        
        #localVideo {
            width: 100px !important;
            height: 135px !important;
        }
        
        .call-buttons button {
            width: 65px !important;
            height: 65px !important;
            font-size: 24px !important;
        }
        
        .call-buttons .end-call {
            width: 75px !important;
            height: 75px !important;
            font-size: 28px !important;
        }
    </style>
</head>
<body>

<div class="call-container">
    <div class="friend-info">
        <img src="{{ asset($friend->profileimg ?? 'images/best3.png') }}" alt="{{ $friend->name }}">
        <h2>
            {{ $friend->name }}
            <span class="remote-status">
                <i id="remoteAudioIcon" class="fa fa-microphone" style="color: #4CAF50;"></i>
                @if ($call->call_type == 'video')
                    <i id="remoteVideoIcon" class="fa fa-video" style="color: #4CAF50;"></i>
                @endif
            </span>
        </h2>

        <span class="status-badge" id="callStatus">
            <i class="fa fa-bell"></i> <span id="statusText">Calling...</span>
        </span>
        <div class="call-timer" id="callTimer">00:00</div>
    </div>

    <div id="videoContainer">
        <div id="remoteVideo" style="width:100%; height:100%;"></div>
        <div id="localVideo"></div>
    </div>

    <div class="call-buttons">
        <div id="receiverButtons" style="display: {{ $call->caller_id != $user->id ? 'flex' : 'none' }}; gap: 20px;">
            <button class="end-call" id="declineBtn" title="Decline Call">
                <i class="fa fa-times"></i>
            </button>
            <button class="accept-call end-call" id="acceptBtn" title="Accept Call">
                <i class="fa fa-phone"></i>
            </button>
        </div>

        <div id="callerButtons" style="display: {{ $call->caller_id == $user->id ? 'flex' : 'none' }}; gap: 20px;">
            <button id="muteBtn" title="Mute/Unmute">
                <i class="fa fa-microphone"></i>
            </button>

            @if ($call->call_type == 'video')
                <button id="videoBtn" title="Turn Video Off/On">
                    <i class="fa fa-video"></i>
                </button>
            @endif

            <button class="end-call" id="endCallBtn" title="End Call">
                <i class="fa fa-phone-slash"></i>
            </button>
        </div>
    </div>
</div>

<script src="https://download.agora.io/sdk/release/AgoraRTC_N-4.22.0.js"></script>
<script>
    const CONFIG = {
        callId: {{ $call->id }},
        currentUserId: {{ $user->id }},
        friendId: {{ $friend->id }},
        isCaller: {{ $call->caller_id == $user->id ? 'true' : 'false' }},
        callType: '{{ $call->call_type }}',
        csrfToken: document.querySelector('meta[name="csrf-token"]').content,
        agoraChannel: 'call_{{ $call->id }}'
    };

    console.log('🎬 VIDEO CALL - Agora Version 1.0', CONFIG);

    const CallApp = {
        agoraClient: null,
        localAudioTrack: null,
        localVideoTrack: null,
        isMuted: false,
        isVideoOff: false,
        callTimer: null,
        callSeconds: 0,
        audioContext: null,
        ringbackInterval: null,
        isCallActive: false,
        friendOnline: false,
        onlineStatusInterval: null,
        callAccepted: false,
        callTimeout: null,

        async init() {
            console.log('🚀 Initializing call...');
            this.setupEventListeners();
            this.subscribeToChannel();
            this.checkFriendOnlineStatus();

            if (CONFIG.isCaller) {
                this.playRingback();
                // Set call timeout (60 seconds)
                this.callTimeout = setTimeout(() => {
                    if (!this.isCallActive) {
                        console.log('⏰ Call timeout - no answer');
                        this.endCallDueToTimeout();
                    }
                }, 60000);
            } else {
                // Auto-accept if coming from global call overlay
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.get('auto_accept') === '1') {
                    window.history.replaceState({}, '', window.location.pathname);
                    this.acceptCall();
                } else {
                    this.updateStatusBadge('Incoming Call...', 'ringing', 'fa-phone-volume');
                }
            }
        },

        setupEventListeners() {
            const acceptBtn = document.getElementById('acceptBtn');
            const declineBtn = document.getElementById('declineBtn');
            const endCallBtn = document.getElementById('endCallBtn');
            const muteBtn = document.getElementById('muteBtn');
            const videoBtn = document.getElementById('videoBtn');

            if (acceptBtn) acceptBtn.onclick = () => this.acceptCall();
            if (declineBtn) declineBtn.onclick = () => this.declineCall();
            if (endCallBtn) endCallBtn.onclick = () => this.endCall();
            if (muteBtn) muteBtn.onclick = () => this.toggleMute();
            if (videoBtn) videoBtn.onclick = () => this.toggleVideo();
        },

        subscribeToChannel() {
            if (!window.Echo) {
                console.error('❌ Echo not available');
                return;
            }

            console.log('📡 Subscribing to calls.' + CONFIG.callId);

            window.Echo.private(`calls.${CONFIG.callId}`)
                .listen('.CallAccepted', async (e) => {
                    console.log('✅ CallAccepted received', e);
                    if (this.callTimeout) {
                        clearTimeout(this.callTimeout);
                    }
                    this.stopRingback();
                    // Caller joins Agora after receiver accepts
                    if (!this.agoraClient) {
                        await this.joinAgoraChannel();
                    }
                })
                .listen('.ToggleMediaEvent', (e) => {
                    if (e.senderId !== CONFIG.currentUserId) {
                        this.updateRemoteStatus(e.type, e.state);
                    }
                })
                .listen('.CallEnded', (e) => {
                    console.log('📴 Call ended:', e.reason);
                    this.handleCallEnded(e.reason);
                })
                .listen('.CallDeclined', (e) => {
                    console.log('📴 Call declined:', e);
                    this.handleCallEnded('declined');
                });

            this.onlineStatusInterval = setInterval(() => {
                this.checkFriendOnlineStatus();
            }, 3000);
        },

        checkFriendOnlineStatus() {
            fetch(`/api/users/${CONFIG.friendId}/online-status`)
                .then(res => res.json())
                .then(data => {
                    const wasOnline = this.friendOnline;
                    this.friendOnline = data.online;

                    if (CONFIG.isCaller && !this.isCallActive && wasOnline !== this.friendOnline) {
                        this.updateCallingStatus();
                    }

                    if (wasOnline === undefined) {
                        this.updateCallingStatus();
                    }
                })
                .catch(err => console.error('❌ Online status error:', err));
        },

        updateCallingStatus() {
            if (CONFIG.isCaller && !this.isCallActive) {
                if (this.friendOnline) {
                    this.updateStatusBadge('Ringing...', 'ringing', 'fa-bell');
                } else {
                    this.updateStatusBadge('Calling... (Offline)', 'calling', 'fa-bell');
                }
            }
        },

        updateStatusBadge(text, statusClass, iconClass) {
            const statusBadge = document.getElementById('callStatus');
            const statusText = document.getElementById('statusText');
            const icon = statusBadge?.querySelector('i');

            if (statusText) statusText.textContent = text;
            if (icon) icon.className = `fa ${iconClass}`;
            if (statusBadge) statusBadge.className = `status-badge ${statusClass}`;

            console.log(`📊 Status: ${text}`);
        },

        updateRemoteStatus(type, state) {
            if (type === 'audio') {
                const icon = document.getElementById('remoteAudioIcon');
                if (icon) {
                    icon.className = state ? 'fa fa-microphone-slash' : 'fa fa-microphone';
                    icon.style.color = state ? '#f44336' : '#4CAF50';
                }
            } else if (type === 'video') {
                const icon = document.getElementById('remoteVideoIcon');
                if (icon) {
                    icon.className = state ? 'fa fa-video-slash' : 'fa fa-video';
                    icon.style.color = state ? '#f44336' : '#4CAF50';
                }
            }
        },

        // ---- Join Agora Channel ----
        async joinAgoraChannel() {
            try {
                console.log('🔗 Joining Agora channel:', CONFIG.agoraChannel);
                this.updateStatusBadge('Connecting...', 'calling', 'fa-sync-alt');

                // Get token from server
                const tokenResponse = await fetch('/agora/token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CONFIG.csrfToken
                    },
                    body: JSON.stringify({ channel: CONFIG.agoraChannel })
                });
                const tokenData = await tokenResponse.json();

                if (!tokenData.success) {
                    console.error('❌ Failed to get Agora token');
                    this.handleCallEnded('connection_error');
                    return;
                }

                // Create Agora client
                this.agoraClient = AgoraRTC.createClient({ mode: 'rtc', codec: 'vp8' });

                // Handle remote user published
                this.agoraClient.on('user-published', async (user, mediaType) => {
                    await this.agoraClient.subscribe(user, mediaType);
                    console.log('✅ Subscribed to remote user:', user.uid, 'mediaType:', mediaType);

                    if (mediaType === 'video') {
                        const remoteContainer = document.getElementById('remoteVideo');
                        remoteContainer.innerHTML = '';
                        user.videoTrack.play(remoteContainer);
                        document.getElementById('videoContainer').style.display = 'block';
                    }
                    if (mediaType === 'audio') {
                        user.audioTrack.play();
                    }

                    if (!this.isCallActive) this.onCallAnswered();
                });

                this.agoraClient.on('user-unpublished', (user, mediaType) => {
                    console.log('📴 Remote user unpublished:', user.uid, mediaType);
                    if (mediaType === 'video') {
                        const remoteContainer = document.getElementById('remoteVideo');
                        remoteContainer.innerHTML = '';
                    }
                });

                this.agoraClient.on('user-left', (user) => {
                    console.log('📴 Remote user left:', user.uid);
                    if (this.isCallActive) {
                        this.handleCallEnded('disconnected');
                    }
                });

                // Join channel
                await this.agoraClient.join(tokenData.app_id, CONFIG.agoraChannel, tokenData.token, tokenData.uid);
                console.log('✅ Joined Agora channel successfully');

                // Create and publish local tracks
                if (CONFIG.callType === 'video') {
                    [this.localAudioTrack, this.localVideoTrack] = await AgoraRTC.createMicrophoneAndCameraTracks(
                        {}, { encoderConfig: '480p_1' }
                    );
                    const localContainer = document.getElementById('localVideo');
                    localContainer.innerHTML = '';
                    this.localVideoTrack.play(localContainer);
                    document.getElementById('videoContainer').style.display = 'block';
                    await this.agoraClient.publish([this.localAudioTrack, this.localVideoTrack]);
                } else {
                    this.localAudioTrack = await AgoraRTC.createMicrophoneAudioTrack();
                    await this.agoraClient.publish([this.localAudioTrack]);
                }

                console.log('✅ Published local tracks');

                // If we haven't connected yet via remote-published, mark as connected after a short delay
                setTimeout(() => {
                    if (!this.isCallActive && this.agoraClient) {
                        this.onCallAnswered();
                    }
                }, 2000);

            } catch (error) {
                console.error('❌ Agora join error:', error);
                this.handleCallEnded('connection_error');
            }
        },

        async acceptCall() {
            if (this.callAccepted) {
                console.warn('⚠️ Call already accepted');
                return;
            }
            this.callAccepted = true;

            console.log('📞 Accepting call...');
            this.updateStatusBadge('Connecting...', 'calling', 'fa-sync-alt');

            // Update UI
            document.getElementById('receiverButtons').style.display = 'none';
            document.getElementById('callerButtons').style.display = 'flex';

            try {
                // Accept on backend
                const response = await fetch(`/calls/${CONFIG.callId}/accept`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CONFIG.csrfToken,
                        'Content-Type': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to accept call on server');
                }

                console.log('✅ Call accepted on server');

                // Join Agora channel
                await this.joinAgoraChannel();

            } catch (error) {
                console.error('❌ Accept error:', error);
                alert('Failed to accept call. Please try again.');
                this.handleCallEnded('network_error');
            }
        },

        async declineCall(askConfirm = true) {
            if (askConfirm && !confirm("Decline this call?")) return;

            this.cleanup();

            try {
                await fetch(`/calls/${CONFIG.callId}/decline`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CONFIG.csrfToken,
                        'Content-Type': 'application/json'
                    }
                });
            } catch (error) {
                console.error('❌ Decline error:', error);
            }

            window.location.href = `/messages/chat/${CONFIG.friendId}`;
        },

        async endCall() {
            if (!confirm("End this call?")) return;

            this.cleanup();

            try {
                await fetch(`/calls/${CONFIG.callId}/end`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CONFIG.csrfToken,
                        'Content-Type': 'application/json'
                    }
                });
            } catch (error) {
                console.error('❌ End error:', error);
            }

            window.location.href = `/messages/chat/${CONFIG.friendId}`;
        },

        async endCallDueToTimeout() {
            console.log('⏰ Ending call due to timeout...');
            this.cleanup();

            try {
                await fetch(`/calls/${CONFIG.callId}/timeout`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CONFIG.csrfToken,
                        'Content-Type': 'application/json'
                    }
                });

                alert('No answer - call timeout');
                window.location.href = `/messages/chat/${CONFIG.friendId}`;
            } catch (error) {
                console.error('❌ Timeout error:', error);
                alert('No answer - call timeout');
                window.location.href = `/messages/chat/${CONFIG.friendId}`;
            }
        },

        onCallAnswered() {
            if (this.isCallActive) return;

            console.log('✅ Call connected!');
            this.isCallActive = true;
            this.stopRingback();
            this.updateStatusBadge('Connected', 'connected', 'fa-phone');
            this.startTimer();

            if (CONFIG.callType === 'video') {
                document.getElementById('videoContainer').style.display = 'block';
            }

            document.getElementById('receiverButtons').style.display = 'none';
            document.getElementById('callerButtons').style.display = 'flex';
        },

        handleCallEnded(reason) {
            this.cleanup();

            let message = 'Call ended';
            if (reason === 'declined') message = 'Call was declined';
            else if (reason === 'no_answer') message = 'No answer - call timeout';
            else if (reason === 'disconnected') message = 'Connection lost';
            else if (reason === 'media_error') message = 'Could not access camera/microphone';
            else if (reason === 'connection_error') message = 'Connection failed';

            alert(message);
            window.location.href = `/messages/chat/${CONFIG.friendId}`;
        },

        toggleMute() {
            if (!this.localAudioTrack) return;

            this.isMuted = !this.isMuted;
            this.localAudioTrack.setEnabled(!this.isMuted);

            const btn = document.getElementById('muteBtn');
            const icon = btn?.querySelector('i');

            if (icon) {
                icon.className = this.isMuted ? 'fa fa-microphone-slash' : 'fa fa-microphone';
            }
            if (btn) {
                if (this.isMuted) btn.classList.add('muted');
                else btn.classList.remove('muted');
            }

            this.sendMediaToggle('audio', this.isMuted);
        },

        toggleVideo() {
            if (!this.localVideoTrack) return;

            this.isVideoOff = !this.isVideoOff;
            this.localVideoTrack.setEnabled(!this.isVideoOff);

            const btn = document.getElementById('videoBtn');
            const icon = btn?.querySelector('i');

            if (icon) {
                icon.className = this.isVideoOff ? 'fa fa-video-slash' : 'fa fa-video';
            }
            if (btn) {
                if (this.isVideoOff) btn.classList.add('video-off');
                else btn.classList.remove('video-off');
            }

            this.sendMediaToggle('video', this.isVideoOff);
        },

        async sendMediaToggle(type, state) {
            try {
                await fetch(`/calls/${CONFIG.callId}/toggle-media`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CONFIG.csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        type,
                        state,
                        senderId: CONFIG.currentUserId
                    })
                });
            } catch (error) {
                console.error('❌ Media toggle error:', error);
            }
        },

        playRingback() {
            try {
                this.audioContext = new (window.AudioContext || window.webkitAudioContext)();

                const playBeep = () => {
                    if (!this.audioContext) return;
                    const osc = this.audioContext.createOscillator();
                    const gain = this.audioContext.createGain();
                    osc.connect(gain);
                    gain.connect(this.audioContext.destination);
                    osc.frequency.value = 440;
                    gain.gain.value = 0.2;
                    osc.start();
                    osc.stop(this.audioContext.currentTime + 0.4);
                };

                this.ringbackInterval = setInterval(playBeep, 2000);
                playBeep();
            } catch (error) {
                console.error('Ringback error:', error);
            }
        },

        stopRingback() {
            if (this.ringbackInterval) clearInterval(this.ringbackInterval);
            if (this.audioContext) {
                this.audioContext.close();
                this.audioContext = null;
            }
        },

        startTimer() {
            document.getElementById('callTimer').style.display = 'block';
            document.getElementById('callStatus').style.display = 'none';

            this.callTimer = setInterval(() => {
                this.callSeconds++;
                const minutes = Math.floor(this.callSeconds / 60);
                const seconds = this.callSeconds % 60;
                const timerEl = document.getElementById('callTimer');
                if (timerEl) {
                    timerEl.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                }
            }, 1000);
        },

        cleanup() {
            console.log('🧹 Cleaning up call resources');
            this.stopRingback();

            if (this.callTimer) clearInterval(this.callTimer);
            if (this.onlineStatusInterval) clearInterval(this.onlineStatusInterval);
            if (this.callTimeout) clearTimeout(this.callTimeout);

            // Close Agora tracks and client
            if (this.localAudioTrack) {
                this.localAudioTrack.close();
                this.localAudioTrack = null;
            }
            if (this.localVideoTrack) {
                this.localVideoTrack.close();
                this.localVideoTrack = null;
            }
            if (this.agoraClient) {
                try { this.agoraClient.leave(); } catch (e) {}
                this.agoraClient = null;
            }
        }
    };

    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => CallApp.init());
    } else {
        CallApp.init();
    }

    window.addEventListener('beforeunload', () => CallApp.cleanup());
</script>

</body>
</html>

<!-- version 0ne b11111111 -->

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ ucfirst($call->call_type) }} Call with {{ $friend->name }}</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @vite(['resources/js/app.js'])
    
      <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh;
            overflow: hidden;
        }
        
        .call-container { 
            text-align: center; 
            background: rgba(255,255,255,0.1); 
            backdrop-filter: blur(10px);
            padding: 10px; 
            border-radius: 20px; 
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            max-width: 800px;
            width: 100%;
        }
        
        .friend-info { margin-bottom: 30px; }
        .friend-info img { 
            width: 120px; 
            height: 120px; 
            border-radius: 50%; 
            object-fit: cover; 
            border: 4px solid rgba(255,255,255,0.3);
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }
        .friend-info h2 { 
            margin: 15px 0 5px 0; 
            font-size: 28px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .remote-status {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            margin-left: 15px;
            padding: 8px 12px;
            background: rgba(0,0,0,0.2);
            border-radius: 20px;
        }
        
        .remote-status i {
            font-size: 20px;
            transition: all 0.3s ease;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
        }
        
        .status-badge { 
            display: inline-block; 
            padding: 8px 20px; 
            border-radius: 25px; 
            font-weight: bold; 
            margin: 15px 0;
            background: rgba(255,255,255,0.2);
            animation: pulse 2s infinite;
            transition: all 0.3s ease;
        }
        
        .status-badge.ringing {
            background: rgba(76, 175, 80, 0.3);
            animation: pulse 1s infinite;
        }
        
        .status-badge.calling {
            background: rgba(255, 193, 7, 0.3);
        }
        
        .status-badge.connected {
            background: rgba(76, 175, 80, 0.4);
            animation: none;
        }
        
        .status-badge.disconnected {
            background: rgba(244, 67, 54, 0.4);
            animation: pulse 1.5s infinite;
        }
        
        .call-timer {
            font-size: 32px;
            font-weight: bold;
            margin: 20px 0;
            display: none;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        #videoContainer { 
            width: 100%; 
            height: 500px; 
            background: #000; 
            margin: 20px 0; 
            border-radius: 15px;
            overflow: hidden;
            position: relative;
            display: none;
        }
        
        #localVideo, #remoteVideo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        #localVideo {
            position: absolute;
            width: 200px;
            height: 250px;
            bottom: 20px;
            right: 20px;
            border-radius: 10px;
            border: 2px solid white;
            z-index: 10;
        }
        
        .call-buttons { 
            display: flex; 
            justify-content: center; 
            gap: 20px; 
            margin-top: 30px;
        }
        
        .call-buttons button { 
            background: rgba(255,255,255,0.3); 
            border: none; 
            padding: 18px; 
            border-radius: 50%; 
            color: white; 
            font-size: 24px; 
            cursor: pointer; 
            width: 65px;
            height: 65px;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .call-buttons button:hover { 
            transform: scale(1.1);
            background: rgba(255,255,255,0.4);
        }
        
        .call-buttons .end-call { 
            background: #f44336;
            width: 80px;
            height: 80px;
            font-size: 28px;
        }
        
        .call-buttons .end-call:hover { 
            background: #d32f2f;
            transform: scale(1.15);
        }
        
        .call-buttons button.muted,
        .call-buttons button.video-off {
            background: rgba(244, 67, 54, 0.7);
        }
        
        .accept-call {
            background: #4CAF50 !important;
        }
        
        .accept-call:hover {
            background: #45a049 !important;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
   
    html, body {
    min-height: 100%;
} 


</style>
</head>
<body>

<div class="call-container">
    <div class="friend-info">
        <img src="{{ asset($friend->profileimg ?? 'images/best3.png') }}" alt="{{ $friend->name }}">
        <h2>
            {{ $friend->name }}
            <span class="remote-status">
                <i id="remoteAudioIcon" class="fa fa-microphone" style="color: #4CAF50;"></i>
                @if ($call->call_type == 'video')
                    <i id="remoteVideoIcon" class="fa fa-video" style="color: #4CAF50;"></i>
                @endif
            </span>
        </h2>

        <span class="status-badge" id="callStatus">
            <i class="fa fa-bell"></i> <span id="statusText">Calling...</span>
        </span>
        <div class="call-timer" id="callTimer">00:00</div>
    </div>

    <div id="videoContainer">
        <div id="remoteVideo" style="width:100%; height:100%;"></div>
        <div id="localVideo"></div>
    </div>

    <div class="call-buttons">
        <div id="receiverButtons" style="display: {{ $call->caller_id != $user->id ? 'flex' : 'none' }}; gap: 20px;">
            <button class="end-call" id="declineBtn" title="Decline Call">
                <i class="fa fa-times"></i>
            </button>
            <button class="accept-call end-call" id="acceptBtn" title="Accept Call">
                <i class="fa fa-phone"></i>
            </button>
        </div>

        <div id="callerButtons" style="display: {{ $call->caller_id == $user->id ? 'flex' : 'none' }}; gap: 20px;">
            <button id="muteBtn" title="Mute/Unmute">
                <i class="fa fa-microphone"></i>
            </button>

            @if ($call->call_type == 'video')
                <button id="videoBtn" title="Turn Video Off/On">
                    <i class="fa fa-video"></i>
                </button>
            @endif

            <button class="end-call" id="endCallBtn" title="End Call">
                <i class="fa fa-phone-slash"></i>
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/simple-peer@9/simplepeer.min.js"></script>
<script>
    const CONFIG = {
        callId: {{ $call->id }},
        currentUserId: {{ $user->id }},
        friendId: {{ $friend->id }},
        isCaller: {{ $call->caller_id == $user->id ? 'true' : 'false' }},
        callType: '{{ $call->call_type }}',
        csrfToken: document.querySelector('meta[name="csrf-token"]').content
    };

    console.log('🎬 VIDEO CALL - Robust Version 3.0', CONFIG);

    const CallApp = {
        localStream: null,
        peer: null,
        isMuted: false,
        isVideoOff: false,
        callTimer: null,
        callSeconds: 0,
        audioContext: null,
        ringbackInterval: null,
        isCallActive: false,
        friendOnline: false,
        onlineStatusInterval: null,
        callAccepted: false,
        pendingSignals: [], // Queue for signals received before peer is ready
        callTimeout: null,
        mediaAcquisitionInProgress: false,

        async init() {
            console.log('🚀 Initializing call...');
            this.setupEventListeners();
            this.subscribeToChannel();
            this.checkFriendOnlineStatus();

            if (CONFIG.isCaller) {
                await this.initializeMedia();
                if (this.localStream) {
                    // Show video container immediately for caller if it's a video call
                    if (CONFIG.callType === 'video') {
                        document.getElementById('videoContainer').style.display = 'block';
                        console.log('📹 Showing caller video preview');
                    }
                    
                    this.playRingback();
                    // Wait a bit before initializing peer to ensure backend is ready
                    setTimeout(() => this.initializePeer(), 1000);
                    // Set call timeout (60 seconds)
                    this.callTimeout = setTimeout(() => {
                        if (!this.isCallActive) {
                            console.log('⏰ Call timeout - no answer');
                            this.handleCallEnded('no_answer');
                        }
                    }, 60000);
                }
            } else {
                const _urlP = new URLSearchParams(window.location.search);
                if (_urlP.get('auto_accept') === '1') {
                    window.history.replaceState({}, '', window.location.pathname);
                    this.acceptCall();
                } else {
                    this.updateStatusBadge('Incoming Call...', 'ringing', 'fa-phone-volume');
                }
            }
        },

        setupEventListeners() {
            const acceptBtn = document.getElementById('acceptBtn');
            const declineBtn = document.getElementById('declineBtn');
            const endCallBtn = document.getElementById('endCallBtn');
            const muteBtn = document.getElementById('muteBtn');
            const videoBtn = document.getElementById('videoBtn');

            if (acceptBtn) acceptBtn.onclick = () => this.acceptCall();
            if (declineBtn) declineBtn.onclick = () => this.declineCall();
            if (endCallBtn) endCallBtn.onclick = () => this.endCall();
            if (muteBtn) muteBtn.onclick = () => this.toggleMute();
            if (videoBtn) videoBtn.onclick = () => this.toggleVideo();
        },

        subscribeToChannel() {
            if (!window.Echo) {
                console.error('❌ Echo not available');
                return;
            }

            console.log('📡 Subscribing to calls.' + CONFIG.callId);

            window.Echo.private(`calls.${CONFIG.callId}`)
                .listen('.CallAccepted', (e) => {
                    console.log('✅ CallAnsweredEvent received', e);
                    if (this.callTimeout) {
                        clearTimeout(this.callTimeout);
                    }
                    this.onCallAnswered();
                })
                .listen('.CallSignal', (e) => {
                    if (e.from_user_id !== CONFIG.currentUserId) {
                        const sig = typeof e.signal === 'string' ? JSON.parse(e.signal) : e.signal;
                        console.log('📥 Signal received:', sig?.type || 'unknown');

                        if (this.peer) {
                            try {
                                this.peer.signal(sig);
                            } catch (error) {
                                console.error('❌ Error processing signal:', error);
                            }
                        } else {
                            console.log('📦 Queuing signal for later');
                            this.pendingSignals.push(sig);
                        }
                    }
                })
                .listen('.ToggleMediaEvent', (e) => {
                    if (e.senderId !== CONFIG.currentUserId) {
                        this.updateRemoteStatus(e.type, e.state);
                    }
                })
                .listen('.CallEnded', (e) => {
                    console.log('📴 Call ended:', e.reason);
                    this.handleCallEnded(e.reason);
                })
                .listen('.CallDeclined', (e) => {
                    console.log('📴 Call declined:', e);
                    this.handleCallEnded('declined');
                });

            this.onlineStatusInterval = setInterval(() => {
                this.checkFriendOnlineStatus();
            }, 3000);
        },

        checkFriendOnlineStatus() {
            fetch(`/api/users/${CONFIG.friendId}/online-status`)
                .then(res => res.json())
                .then(data => {
                    const wasOnline = this.friendOnline;
                    this.friendOnline = data.online;

                    if (CONFIG.isCaller && !this.isCallActive && wasOnline !== this.friendOnline) {
                        this.updateCallingStatus();
                    }

                    if (wasOnline === undefined) {
                        this.updateCallingStatus();
                    }
                })
                .catch(err => console.error('❌ Online status error:', err));
        },

        updateCallingStatus() {
            if (CONFIG.isCaller && !this.isCallActive) {
                if (this.friendOnline) {
                    this.updateStatusBadge('Ringing...', 'ringing', 'fa-bell');
                } else {
                    this.updateStatusBadge('Calling... (Offline)', 'calling', 'fa-bell');
                }
            }
        },

        updateStatusBadge(text, statusClass, iconClass) {
            const statusBadge = document.getElementById('callStatus');
            const statusText = document.getElementById('statusText');
            const icon = statusBadge?.querySelector('i');

            if (statusText) statusText.textContent = text;
            if (icon) icon.className = `fa ${iconClass}`;
            if (statusBadge) statusBadge.className = `status-badge ${statusClass}`;

            console.log(`📊 Status: ${text}`);
        },

        updateRemoteStatus(type, state) {
            if (type === 'audio') {
                const icon = document.getElementById('remoteAudioIcon');
                if (icon) {
                    icon.className = state ? 'fa fa-microphone-slash' : 'fa fa-microphone';
                    icon.style.color = state ? '#f44336' : '#4CAF50';
                }
            } else if (type === 'video') {
                const icon = document.getElementById('remoteVideoIcon');
                if (icon) {
                    icon.className = state ? 'fa fa-video-slash' : 'fa fa-video';
                    icon.style.color = state ? '#f44336' : '#4CAF50';
                }
            }
        },

        async initializeMedia() {
            if (this.mediaAcquisitionInProgress || this.localStream) {
                console.warn('⚠️ Media acquisition already in progress or stream exists');
                return;
            }

            this.mediaAcquisitionInProgress = true;

            try {
                console.log('📹 Requesting media permissions...');
                const constraints = {
                    audio: true,
                    video: CONFIG.callType === 'video' ? { 
                        width: { ideal: 640 },
                        height: { ideal: 480 },
                        facingMode: 'user'
                    } : false
                };

                this.localStream = await navigator.mediaDevices.getUserMedia(constraints);

                if (CONFIG.callType === 'video') {
                    const localVideo = document.getElementById('localVideo');
                    if (localVideo) {
                        localVideo.srcObject = this.localStream;
                    }
                }

                console.log('✅ Media initialized successfully');
                this.mediaAcquisitionInProgress = false;
                return true;

            } catch (error) {
                this.mediaAcquisitionInProgress = false;
                console.error('❌ Media error:', error.name, error.message);
                
                let errorMessage = 'Could not access camera/microphone.';
                
                if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
                    errorMessage = 'Camera/Microphone permission denied. Please allow access and try again.';
                } else if (error.name === 'NotFoundError') {
                    errorMessage = 'No camera/microphone found on your device.';
                } else if (error.name === 'NotReadableError') {
                    errorMessage = 'Camera/Microphone is already in use by another application.';
                }
                
                alert(errorMessage);
                
                // Don't immediately end call - give user chance to fix permissions
                if (CONFIG.isCaller) {
                    this.handleCallEnded('media_error');
                } else {
                    // Receiver should decline the call
                    await this.declineCall(false);
                }
                
                return false;
            }
        },

        initializePeer() {
            if (this.peer) {
                console.warn('⚠️ Peer already exists');
                return;
            }

            if (!this.localStream) {
                console.error('❌ Cannot initialize peer without local stream');
                return;
            }

            console.log('🔗 Initializing peer as', CONFIG.isCaller ? 'CALLER (initiator)' : 'RECEIVER (responder)');

            this.peer = new SimplePeer({
                initiator: CONFIG.isCaller,
                stream: this.localStream,
                trickle: true,
                config: {
                    iceServers: [
                        { urls: 'stun:stun.l.google.com:19302' },
                        { urls: 'stun:stun1.l.google.com:19302' },
                        { urls: 'turn:openrelay.metered.ca:80', username: 'openrelayproject', credential: 'openrelayproject' },
                        { urls: 'turn:openrelay.metered.ca:443', username: 'openrelayproject', credential: 'openrelayproject' }
                    ]
                }
            });

            // Process any pending signals
            if (this.pendingSignals.length > 0) {
                console.log(`📦 Processing ${this.pendingSignals.length} queued signals`);
                this.pendingSignals.forEach(signal => {
                    try {
                        this.peer.signal(signal);
                    } catch (error) {
                        console.error('❌ Error processing queued signal:', error);
                    }
                });
                this.pendingSignals = [];
            }

            this.peer.on('signal', (data) => {
                console.log('📤 Sending signal:', data.type);
                this.sendSignal(data);
            });

            this.peer.on('stream', (remoteStream) => {
                console.log('✅ Remote stream received');
                const remoteVideo = document.getElementById('remoteVideo');
                if (remoteVideo) {
                    remoteVideo.srcObject = remoteStream;
                }
                
                if (CONFIG.callType === 'video') {
                    document.getElementById('videoContainer').style.display = 'block';
                }
            });

            this.peer.on('connect', () => {
                console.log('🔗 Peer connected!');
                if (!this.isCallActive) {
                    this.onCallAnswered();
                }
            });

            this.peer.on('close', () => {
                console.log('❌ Peer closed');
                if (this.isCallActive) {
                    this.handleCallEnded('disconnected');
                }
            });

            this.peer.on('error', (err) => {
                console.error('❌ Peer error:', err);
                if (!this.isCallActive) {
                    // Only end call if we haven't connected yet
                    this.handleCallEnded('connection_error');
                }
            });
        },

        async sendSignal(signalData) {
            try {
                await fetch(`/calls/${CONFIG.callId}/signal`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CONFIG.csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        signal: JSON.stringify(signalData),
                        from_user_id: CONFIG.currentUserId
                    })
                });
            } catch (error) {
                console.error('❌ Signal send error:', error);
            }
        },

        async acceptCall() {
            if (this.callAccepted) {
                console.warn('⚠️ Call already accepted');
                return;
            }
            this.callAccepted = true;

            console.log('📞 Accepting call - requesting media...');
            this.updateStatusBadge('Requesting permissions...', 'calling', 'fa-video');

            // Get media first
            const mediaSuccess = await this.initializeMedia();
            
            if (!mediaSuccess || !this.localStream) {
                console.error('❌ Failed to get media, cannot accept call');
                return;
            }

            // Update UI
            document.getElementById('receiverButtons').style.display = 'none';
            document.getElementById('callerButtons').style.display = 'flex';
            this.updateStatusBadge('Connecting...', 'calling', 'fa-sync-alt');

            try {
                // Accept on backend
                const response = await fetch(`/calls/${CONFIG.callId}/accept`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CONFIG.csrfToken,
                        'Content-Type': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to accept call on server');
                }

                console.log('✅ Call accepted on server');

                // Small delay to ensure backend has processed
                setTimeout(() => {
                    this.initializePeer();
                }, 300);

            } catch (error) {
                console.error('❌ Accept error:', error);
                alert('Failed to accept call. Please try again.');
                this.handleCallEnded('network_error');
            }
        },

        async declineCall(askConfirm = true) {
            if (askConfirm && !confirm("Decline this call?")) return;

            this.cleanup();

            try {
                await fetch(`/calls/${CONFIG.callId}/decline`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CONFIG.csrfToken,
                        'Content-Type': 'application/json'
                    }
                });
            } catch (error) {
                console.error('❌ Decline error:', error);
            }

            window.location.href = `/messages/chat/${CONFIG.friendId}`;
        },

        async endCall() {
            if (!confirm("End this call?")) return;

            this.cleanup();

            try {
                await fetch(`/calls/${CONFIG.callId}/end`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CONFIG.csrfToken,
                        'Content-Type': 'application/json'
                    }
                });
            } catch (error) {
                console.error('❌ End error:', error);
            }

            window.location.href = `/messages/chat/${CONFIG.friendId}`;
        },

        onCallAnswered() {
            if (this.isCallActive) return;

            console.log('✅ Call connected!');
            this.isCallActive = true;
            this.stopRingback();
            this.updateStatusBadge('Connected', 'connected', 'fa-phone');
            this.startTimer();

            if (CONFIG.callType === 'video') {
                document.getElementById('videoContainer').style.display = 'block';
            }

            document.getElementById('receiverButtons').style.display = 'none';
            document.getElementById('callerButtons').style.display = 'flex';
        },

        handleCallEnded(reason) {
            this.cleanup();

            let message = 'Call ended';
            if (reason === 'declined') message = 'Call was declined';
            else if (reason === 'no_answer') message = 'No answer - call timeout';
            else if (reason === 'disconnected') message = 'Connection lost';
            else if (reason === 'media_error') message = 'Could not access camera/microphone';
            else if (reason === 'connection_error') message = 'Connection failed';

            alert(message);
            window.location.href = `/messages/chat/${CONFIG.friendId}`;
        },

        toggleMute() {
            if (!this.localStream) return;

            const audioTrack = this.localStream.getAudioTracks()[0];
            if (!audioTrack) return;

            audioTrack.enabled = !audioTrack.enabled;
            this.isMuted = !audioTrack.enabled;

            const btn = document.getElementById('muteBtn');
            const icon = btn?.querySelector('i');

            if (icon) {
                icon.className = this.isMuted ? 'fa fa-microphone-slash' : 'fa fa-microphone';
            }
            if (btn) {
                if (this.isMuted) btn.classList.add('muted');
                else btn.classList.remove('muted');
            }

            this.sendMediaToggle('audio', this.isMuted);
        },

        toggleVideo() {
            if (!this.localStream) return;

            const videoTrack = this.localStream.getVideoTracks()[0];
            if (!videoTrack) return;

            videoTrack.enabled = !videoTrack.enabled;
            this.isVideoOff = !videoTrack.enabled;

            const btn = document.getElementById('videoBtn');
            const icon = btn?.querySelector('i');

            if (icon) {
                icon.className = this.isVideoOff ? 'fa fa-video-slash' : 'fa fa-video';
            }
            if (btn) {
                if (this.isVideoOff) btn.classList.add('video-off');
                else btn.classList.remove('video-off');
            }

            this.sendMediaToggle('video', this.isVideoOff);
        },

        async sendMediaToggle(type, state) {
            try {
                await fetch(`/calls/${CONFIG.callId}/toggle-media`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CONFIG.csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        type,
                        state,
                        senderId: CONFIG.currentUserId
                    })
                });
            } catch (error) {
                console.error('❌ Media toggle error:', error);
            }
        },

        playRingback() {
            try {
                this.audioContext = new (window.AudioContext || window.webkitAudioContext)();

                const playBeep = () => {
                    if (!this.audioContext) return;
                    const osc = this.audioContext.createOscillator();
                    const gain = this.audioContext.createGain();
                    osc.connect(gain);
                    gain.connect(this.audioContext.destination);
                    osc.frequency.value = 440;
                    gain.gain.value = 0.2;
                    osc.start();
                    osc.stop(this.audioContext.currentTime + 0.4);
                };

                this.ringbackInterval = setInterval(playBeep, 2000);
                playBeep();
            } catch (error) {
                console.error('Ringback error:', error);
            }
        },

        stopRingback() {
            if (this.ringbackInterval) clearInterval(this.ringbackInterval);
            if (this.audioContext) {
                this.audioContext.close();
                this.audioContext = null;
            }
        },

        startTimer() {
            document.getElementById('callTimer').style.display = 'block';
            document.getElementById('callStatus').style.display = 'none';

            this.callTimer = setInterval(() => {
                this.callSeconds++;
                const minutes = Math.floor(this.callSeconds / 60);
                const seconds = this.callSeconds % 60;
                const timerEl = document.getElementById('callTimer');
                if (timerEl) {
                    timerEl.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                }
            }, 1000);
        },

        cleanup() {
            console.log('🧹 Cleaning up call resources');
            this.stopRingback();
            
            if (this.callTimer) clearInterval(this.callTimer);
            if (this.onlineStatusInterval) clearInterval(this.onlineStatusInterval);
            if (this.callTimeout) clearTimeout(this.callTimeout);
            
            if (this.localStream) {
                this.localStream.getTracks().forEach(track => {
                    track.stop();
                    console.log('🛑 Stopped track:', track.kind);
                });
                this.localStream = null;
            }
            
            if (this.peer) {
                this.peer.destroy();
                this.peer = null;
            }
            
            this.pendingSignals = [];
        }
    };

    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => CallApp.init());
    } else {
        CallApp.init();
    }

    window.addEventListener('beforeunload', () => CallApp.cleanup());
</script>

</body>
</html> -->









<!-- second version 2222222222222222222222222222222 -->
<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ ucfirst($call->call_type) }} Call with {{ $friend->name }}</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @vite(['resources/js/app.js'])
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh;
            overflow: hidden;
        }
        
        .call-container { 
            text-align: center; 
            background: rgba(255,255,255,0.1); 
            backdrop-filter: blur(10px);
            padding: 40px; 
            border-radius: 20px; 
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 90%;
        }
        
        .friend-info { margin-bottom: 30px; }
        .friend-info img { 
            width: 120px; 
            height: 120px; 
            border-radius: 50%; 
            object-fit: cover; 
            border: 4px solid rgba(255,255,255,0.3);
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }
        .friend-info h2 { 
            margin: 15px 0 5px 0; 
            font-size: 28px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .remote-status {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            margin-left: 15px;
            padding: 8px 12px;
            background: rgba(0,0,0,0.2);
            border-radius: 20px;
        }
        
        .remote-status i {
            font-size: 20px;
            transition: all 0.3s ease;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
        }
        
        .status-badge { 
            display: inline-block; 
            padding: 8px 20px; 
            border-radius: 25px; 
            font-weight: bold; 
            margin: 15px 0;
            background: rgba(255,255,255,0.2);
            animation: pulse 2s infinite;
            transition: all 0.3s ease;
        }
        
        .status-badge.ringing {
            background: rgba(76, 175, 80, 0.3);
            animation: pulse 1s infinite;
        }
        
        .status-badge.calling {
            background: rgba(255, 193, 7, 0.3);
        }
        
        .status-badge.connected {
            background: rgba(76, 175, 80, 0.4);
            animation: none;
        }
        
        .status-badge.disconnected {
            background: rgba(244, 67, 54, 0.4);
            animation: pulse 1.5s infinite;
        }
        
        .call-timer {
            font-size: 32px;
            font-weight: bold;
            margin: 20px 0;
            display: none;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        #videoContainer { 
            width: 100%; 
            height: 350px; 
            background: #000; 
            margin: 20px 0; 
            border-radius: 15px;
            overflow: hidden;
            position: relative;
            display: none;
        }
        
        #localVideo, #remoteVideo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        #localVideo {
            position: absolute;
            width: 150px;
            height: 150px;
            bottom: 20px;
            right: 20px;
            border-radius: 10px;
            border: 2px solid white;
            z-index: 10;
        }
        
        .call-buttons { 
            display: flex; 
            justify-content: center; 
            gap: 20px; 
            margin-top: 30px;
        }
        
        .call-buttons button { 
            background: rgba(255,255,255,0.3); 
            border: none; 
            padding: 18px; 
            border-radius: 50%; 
            color: white; 
            font-size: 24px; 
            cursor: pointer; 
            width: 65px;
            height: 65px;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .call-buttons button:hover { 
            transform: scale(1.1);
            background: rgba(255,255,255,0.4);
        }
        
        .call-buttons .end-call { 
            background: #f44336;
            width: 80px;
            height: 80px;
            font-size: 28px;
        }
        
        .call-buttons .end-call:hover { 
            background: #d32f2f;
            transform: scale(1.15);
        }
        
        .call-buttons button.muted,
        .call-buttons button.video-off {
            background: rgba(244, 67, 54, 0.7);
        }
        
        .accept-call {
            background: #4CAF50 !important;
        }
        
        .accept-call:hover {
            background: #45a049 !important;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
    </style>
</head>
<body>

<div class="call-container">
    <div class="friend-info">
        <img src="{{ asset($friend->profileimg ?? 'images/best3.png') }}" alt="{{ $friend->name }}">
        <h2>
            {{ $friend->name }}
            <span class="remote-status">
                <i id="remoteAudioIcon" class="fa fa-microphone" style="color: #4CAF50;"></i>
                @if ($call->call_type == 'video')
                    <i id="remoteVideoIcon" class="fa fa-video" style="color: #4CAF50;"></i>
                @endif
            </span>
        </h2>

        <span class="status-badge" id="callStatus">
            <i class="fa fa-bell"></i> <span id="statusText">Calling...</span>
        </span>
        <div class="call-timer" id="callTimer">00:00</div>
    </div>

    <div id="videoContainer">
        <div id="remoteVideo" style="width:100%; height:100%;"></div>
        <div id="localVideo"></div>
    </div>

    <div class="call-buttons">
        <div id="receiverButtons" style="display: {{ $call->caller_id != $user->id ? 'flex' : 'none' }}; gap: 20px;">
            <button class="end-call" id="declineBtn" title="Decline Call">
                <i class="fa fa-times"></i>
            </button>
            <button class="accept-call end-call" id="acceptBtn" title="Accept Call">
                <i class="fa fa-phone"></i>
            </button>
        </div>

        <div id="callerButtons" style="display: {{ $call->caller_id == $user->id ? 'flex' : 'none' }}; gap: 20px;">
            <button id="muteBtn" title="Mute/Unmute">
                <i class="fa fa-microphone"></i>
            </button>

            @if ($call->call_type == 'video')
                <button id="videoBtn" title="Turn Video Off/On">
                    <i class="fa fa-video"></i>
                </button>
            @endif

            <button class="end-call" id="endCallBtn" title="End Call">
                <i class="fa fa-phone-slash"></i>
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/simple-peer@9/simplepeer.min.js"></script>
<script>
    const CONFIG = {
        callId: {{ $call->id }},
        currentUserId: {{ $user->id }},
        friendId: {{ $friend->id }},
        isCaller: {{ $call->caller_id == $user->id ? 'true' : 'false' }},
        callType: '{{ $call->call_type }}',
        csrfToken: document.querySelector('meta[name="csrf-token"]').content
    };
    

    console.log('🎬 VIDEO CALL - Robust Version 3.0', CONFIG);

    const CallApp = {
        localStream: null,
        peer: null,
        isMuted: false,
        isVideoOff: false,
        callTimer: null,
        callSeconds: 0,
        audioContext: null,
        ringbackInterval: null,
        isCallActive: false,
        friendOnline: false,
        onlineStatusInterval: null,
        callAccepted: false,
        pendingSignals: [], // Queue for signals received before peer is ready
        callTimeout: null,
        mediaAcquisitionInProgress: false,

        async init() {
            console.log('🚀 Initializing call...');
            this.setupEventListeners();
            this.subscribeToChannel();
            this.checkFriendOnlineStatus();

            if (CONFIG.isCaller) {
                await this.initializeMedia();
                if (this.localStream) {
                    this.playRingback();
                    // Wait a bit before initializing peer to ensure backend is ready
                    setTimeout(() => this.initializePeer(), 1000);
                    // Set call timeout (60 seconds)
                    this.callTimeout = setTimeout(() => {
                        if (!this.isCallActive) {
                            console.log('⏰ Call timeout - no answer');
                            this.handleCallEnded('no_answer');
                        }
                    }, 60000);
                }
            } else {
                const _urlP = new URLSearchParams(window.location.search);
                if (_urlP.get('auto_accept') === '1') {
                    window.history.replaceState({}, '', window.location.pathname);
                    this.acceptCall();
                } else {
                    this.updateStatusBadge('Incoming Call...', 'ringing', 'fa-phone-volume');
                }
            }
        },

        setupEventListeners() {
            const acceptBtn = document.getElementById('acceptBtn');
            const declineBtn = document.getElementById('declineBtn');
            const endCallBtn = document.getElementById('endCallBtn');
            const muteBtn = document.getElementById('muteBtn');
            const videoBtn = document.getElementById('videoBtn');

            if (acceptBtn) acceptBtn.onclick = () => this.acceptCall();
            if (declineBtn) declineBtn.onclick = () => this.declineCall();
            if (endCallBtn) endCallBtn.onclick = () => this.endCall();
            if (muteBtn) muteBtn.onclick = () => this.toggleMute();
            if (videoBtn) videoBtn.onclick = () => this.toggleVideo();
        },

        subscribeToChannel() {
            if (!window.Echo) {
                console.error('❌ Echo not available');
                return;
            }

            console.log('📡 Subscribing to calls.' + CONFIG.callId);

            window.Echo.private(`calls.${CONFIG.callId}`)
                .listen('.CallAccepted', (e) => {
                    console.log('✅ CallAnsweredEvent received', e);
                    if (this.callTimeout) {
                        clearTimeout(this.callTimeout);
                    }
                    this.onCallAnswered();
                })
                .listen('.CallSignal', (e) => {
                    if (e.from_user_id !== CONFIG.currentUserId) {
                        const sig = typeof e.signal === 'string' ? JSON.parse(e.signal) : e.signal;
                        console.log('📥 Signal received:', sig?.type || 'unknown');

                        if (this.peer) {
                            try {
                                this.peer.signal(sig);
                            } catch (error) {
                                console.error('❌ Error processing signal:', error);
                            }
                        } else {
                            console.log('📦 Queuing signal for later');
                            this.pendingSignals.push(sig);
                        }
                    }
                })
                .listen('.ToggleMediaEvent', (e) => {
                    if (e.senderId !== CONFIG.currentUserId) {
                        this.updateRemoteStatus(e.type, e.state);
                    }
                })
                .listen('.CallEnded', (e) => {
                    console.log('📴 Call ended:', e.reason);
                    this.handleCallEnded(e.reason);
                })
                .listen('.CallDeclined', (e) => {
                    console.log('📴 Call declined:', e);
                    this.handleCallEnded('declined');
                });

            this.onlineStatusInterval = setInterval(() => {
                this.checkFriendOnlineStatus();
            }, 3000);
        },

        checkFriendOnlineStatus() {
            fetch(`/api/users/${CONFIG.friendId}/online-status`)
                .then(res => res.json())
                .then(data => {
                    const wasOnline = this.friendOnline;
                    this.friendOnline = data.online;

                    if (CONFIG.isCaller && !this.isCallActive && wasOnline !== this.friendOnline) {
                        this.updateCallingStatus();
                    }

                    if (wasOnline === undefined) {
                        this.updateCallingStatus();
                    }
                })
                .catch(err => console.error('❌ Online status error:', err));
        },

        updateCallingStatus() {
            if (CONFIG.isCaller && !this.isCallActive) {
                if (this.friendOnline) {
                    this.updateStatusBadge('Ringing...', 'ringing', 'fa-bell');
                } else {
                    this.updateStatusBadge('Calling... (Offline)', 'calling', 'fa-bell');
                }
            }
        },

        updateStatusBadge(text, statusClass, iconClass) {
            const statusBadge = document.getElementById('callStatus');
            const statusText = document.getElementById('statusText');
            const icon = statusBadge?.querySelector('i');

            if (statusText) statusText.textContent = text;
            if (icon) icon.className = `fa ${iconClass}`;
            if (statusBadge) statusBadge.className = `status-badge ${statusClass}`;

            console.log(`📊 Status: ${text}`);
        },

        updateRemoteStatus(type, state) {
            if (type === 'audio') {
                const icon = document.getElementById('remoteAudioIcon');
                if (icon) {
                    icon.className = state ? 'fa fa-microphone-slash' : 'fa fa-microphone';
                    icon.style.color = state ? '#f44336' : '#4CAF50';
                }
            } else if (type === 'video') {
                const icon = document.getElementById('remoteVideoIcon');
                if (icon) {
                    icon.className = state ? 'fa fa-video-slash' : 'fa fa-video';
                    icon.style.color = state ? '#f44336' : '#4CAF50';
                }
            }
        },

        async initializeMedia() {
            if (this.mediaAcquisitionInProgress || this.localStream) {
                console.warn('⚠️ Media acquisition already in progress or stream exists');
                return;
            }

            this.mediaAcquisitionInProgress = true;

            try {
                console.log('📹 Requesting media permissions...');
                const constraints = {
                    audio: true,
                    video: CONFIG.callType === 'video' ? { 
                        width: { ideal: 640 },
                        height: { ideal: 480 },
                        facingMode: 'user'
                    } : false
                };

                this.localStream = await navigator.mediaDevices.getUserMedia(constraints);

                if (CONFIG.callType === 'video') {
                    const localVideo = document.getElementById('localVideo');
                    if (localVideo) {
                        localVideo.srcObject = this.localStream;
                    }
                }

                console.log('✅ Media initialized successfully');
                this.mediaAcquisitionInProgress = false;
                return true;

            } catch (error) {
                this.mediaAcquisitionInProgress = false;
                console.error('❌ Media error:', error.name, error.message);
                
                let errorMessage = 'Could not access camera/microphone.';
                
                if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
                    errorMessage = 'Camera/Microphone permission denied. Please allow access and try again.';
                } else if (error.name === 'NotFoundError') {
                    errorMessage = 'No camera/microphone found on your device.';
                } else if (error.name === 'NotReadableError') {
                    errorMessage = 'Camera/Microphone is already in use by another application.';
                }
                
                alert(errorMessage);
                
                // Don't immediately end call - give user chance to fix permissions
                if (CONFIG.isCaller) {
                    this.handleCallEnded('media_error');
                } else {
                    // Receiver should decline the call
                    await this.declineCall(false);
                }
                
                return false;
            }
        },

        initializePeer() {
            if (this.peer) {
                console.warn('⚠️ Peer already exists');
                return;
            }

            if (!this.localStream) {
                console.error('❌ Cannot initialize peer without local stream');
                return;
            }

            console.log('🔗 Initializing peer as', CONFIG.isCaller ? 'CALLER (initiator)' : 'RECEIVER (responder)');

            this.peer = new SimplePeer({
                initiator: CONFIG.isCaller,
                stream: this.localStream,
                trickle: true,
                config: {
                    iceServers: [
                        { urls: 'stun:stun.l.google.com:19302' },
                        { urls: 'stun:stun1.l.google.com:19302' },
                        { urls: 'turn:openrelay.metered.ca:80', username: 'openrelayproject', credential: 'openrelayproject' },
                        { urls: 'turn:openrelay.metered.ca:443', username: 'openrelayproject', credential: 'openrelayproject' }
                    ]
                }
            });

            // Process any pending signals
            if (this.pendingSignals.length > 0) {
                console.log(`📦 Processing ${this.pendingSignals.length} queued signals`);
                this.pendingSignals.forEach(signal => {
                    try {
                        this.peer.signal(signal);
                    } catch (error) {
                        console.error('❌ Error processing queued signal:', error);
                    }
                });
                this.pendingSignals = [];
            }

            this.peer.on('signal', (data) => {
                console.log('📤 Sending signal:', data.type);
                this.sendSignal(data);
            });

            this.peer.on('stream', (remoteStream) => {
                console.log('✅ Remote stream received');
                const remoteVideo = document.getElementById('remoteVideo');
                if (remoteVideo) {
                    remoteVideo.srcObject = remoteStream;
                }
                
                if (CONFIG.callType === 'video') {
                    document.getElementById('videoContainer').style.display = 'block';
                }
            });

            this.peer.on('connect', () => {
                console.log('🔗 Peer connected!');
                if (!this.isCallActive) {
                    this.onCallAnswered();
                }
            });

            this.peer.on('close', () => {
                console.log('❌ Peer closed');
                if (this.isCallActive) {
                    this.handleCallEnded('disconnected');
                }
            });

            this.peer.on('error', (err) => {
                console.error('❌ Peer error:', err);
                if (!this.isCallActive) {
                    // Only end call if we haven't connected yet
                    this.handleCallEnded('connection_error');
                }
            });
        },

        async sendSignal(signalData) {
            try {
                await fetch(`/calls/${CONFIG.callId}/signal`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CONFIG.csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        signal: JSON.stringify(signalData),
                        from_user_id: CONFIG.currentUserId
                    })
                });
            } catch (error) {
                console.error('❌ Signal send error:', error);
            }
        },

        async acceptCall() {
            if (this.callAccepted) {
                console.warn('⚠️ Call already accepted');
                return;
            }
            this.callAccepted = true;

            console.log('📞 Accepting call - requesting media...');
            this.updateStatusBadge('Requesting permissions...', 'calling', 'fa-video');

            // Get media first
            const mediaSuccess = await this.initializeMedia();
            
            if (!mediaSuccess || !this.localStream) {
                console.error('❌ Failed to get media, cannot accept call');
                return;
            }

            // Update UI
            document.getElementById('receiverButtons').style.display = 'none';
            document.getElementById('callerButtons').style.display = 'flex';
            this.updateStatusBadge('Connecting...', 'calling', 'fa-sync-alt');

            try {
                // Accept on backend
                const response = await fetch(`/calls/${CONFIG.callId}/accept`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CONFIG.csrfToken,
                        'Content-Type': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to accept call on server');
                }

                console.log('✅ Call accepted on server');

                // Small delay to ensure backend has processed
                setTimeout(() => {
                    this.initializePeer();
                }, 300);

            } catch (error) {
                console.error('❌ Accept error:', error);
                alert('Failed to accept call. Please try again.');
                this.handleCallEnded('network_error');
            }
        },

        async declineCall(askConfirm = true) {
            if (askConfirm && !confirm("Decline this call?")) return;

            this.cleanup();

            try {
                await fetch(`/calls/${CONFIG.callId}/decline`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CONFIG.csrfToken,
                        'Content-Type': 'application/json'
                    }
                });
            } catch (error) {
                console.error('❌ Decline error:', error);
            }

            window.location.href = `/messages/chat/${CONFIG.friendId}`;
        },

        async endCall() {
            if (!confirm("End this call?")) return;

            this.cleanup();

            try {
                await fetch(`/calls/${CONFIG.callId}/end`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CONFIG.csrfToken,
                        'Content-Type': 'application/json'
                    }
                });
            } catch (error) {
                console.error('❌ End error:', error);
            }

            window.location.href = `/messages/chat/${CONFIG.friendId}`;
        },

        onCallAnswered() {
            if (this.isCallActive) return;

            console.log('✅ Call connected!');
            this.isCallActive = true;
            this.stopRingback();
            this.updateStatusBadge('Connected', 'connected', 'fa-phone');
            this.startTimer();

            if (CONFIG.callType === 'video') {
                document.getElementById('videoContainer').style.display = 'block';
            }

            document.getElementById('receiverButtons').style.display = 'none';
            document.getElementById('callerButtons').style.display = 'flex';
        },

        handleCallEnded(reason) {
            this.cleanup();

            let message = 'Call ended';
            if (reason === 'declined') message = 'Call was declined';
            else if (reason === 'no_answer') message = 'No answer - call timeout';
            else if (reason === 'disconnected') message = 'Connection lost';
            else if (reason === 'media_error') message = 'Could not access camera/microphone';
            else if (reason === 'connection_error') message = 'Connection failed';

            alert(message);
            window.location.href = `/messages/chat/${CONFIG.friendId}`;
        },

        toggleMute() {
            if (!this.localStream) return;

            const audioTrack = this.localStream.getAudioTracks()[0];
            if (!audioTrack) return;

            audioTrack.enabled = !audioTrack.enabled;
            this.isMuted = !audioTrack.enabled;

            const btn = document.getElementById('muteBtn');
            const icon = btn?.querySelector('i');

            if (icon) {
                icon.className = this.isMuted ? 'fa fa-microphone-slash' : 'fa fa-microphone';
            }
            if (btn) {
                if (this.isMuted) btn.classList.add('muted');
                else btn.classList.remove('muted');
            }

            this.sendMediaToggle('audio', this.isMuted);
        },

        toggleVideo() {
            if (!this.localStream) return;

            const videoTrack = this.localStream.getVideoTracks()[0];
            if (!videoTrack) return;

            videoTrack.enabled = !videoTrack.enabled;
            this.isVideoOff = !videoTrack.enabled;

            const btn = document.getElementById('videoBtn');
            const icon = btn?.querySelector('i');

            if (icon) {
                icon.className = this.isVideoOff ? 'fa fa-video-slash' : 'fa fa-video';
            }
            if (btn) {
                if (this.isVideoOff) btn.classList.add('video-off');
                else btn.classList.remove('video-off');
            }

            this.sendMediaToggle('video', this.isVideoOff);
        },

        async sendMediaToggle(type, state) {
            try {
                await fetch(`/calls/${CONFIG.callId}/toggle-media`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CONFIG.csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        type,
                        state,
                        senderId: CONFIG.currentUserId
                    })
                });
            } catch (error) {
                console.error('❌ Media toggle error:', error);
            }
        },

        playRingback() {
            try {
                this.audioContext = new (window.AudioContext || window.webkitAudioContext)();

                const playBeep = () => {
                    if (!this.audioContext) return;
                    const osc = this.audioContext.createOscillator();
                    const gain = this.audioContext.createGain();
                    osc.connect(gain);
                    gain.connect(this.audioContext.destination);
                    osc.frequency.value = 440;
                    gain.gain.value = 0.2;
                    osc.start();
                    osc.stop(this.audioContext.currentTime + 0.4);
                };

                this.ringbackInterval = setInterval(playBeep, 2000);
                playBeep();
            } catch (error) {
                console.error('Ringback error:', error);
            }
        },

        stopRingback() {
            if (this.ringbackInterval) clearInterval(this.ringbackInterval);
            if (this.audioContext) {
                this.audioContext.close();
                this.audioContext = null;
            }
        },

        startTimer() {
            document.getElementById('callTimer').style.display = 'block';
            document.getElementById('callStatus').style.display = 'none';

            this.callTimer = setInterval(() => {
                this.callSeconds++;
                const minutes = Math.floor(this.callSeconds / 60);
                const seconds = this.callSeconds % 60;
                const timerEl = document.getElementById('callTimer');
                if (timerEl) {
                    timerEl.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                }
            }, 1000);
        },

        cleanup() {
            console.log('🧹 Cleaning up call resources');
            this.stopRingback();
            
            if (this.callTimer) clearInterval(this.callTimer);
            if (this.onlineStatusInterval) clearInterval(this.onlineStatusInterval);
            if (this.callTimeout) clearTimeout(this.callTimeout);
            
            if (this.localStream) {
                this.localStream.getTracks().forEach(track => {
                    track.stop();
                    console.log('🛑 Stopped track:', track.kind);
                });
                this.localStream = null;
            }
            
            if (this.peer) {
                this.peer.destroy();
                this.peer = null;
            }
            
            this.pendingSignals = [];
        }
    };

    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => CallApp.init());
    } else {
        CallApp.init();
    }

    window.addEventListener('beforeunload', () => CallApp.cleanup());
</script>

</body>
</html> -->



<!-- 333333333333333333333333333333333333333333 -->

<!-- the third version now  333333333-->

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ ucfirst($call->call_type) }} Call with {{ $friend->name }}</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @vite(['resources/js/app.js'])
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh;
            overflow: hidden;
        }
        
        .call-container { 
            text-align: center; 
            background: rgba(255,255,255,0.1); 
            backdrop-filter: blur(10px);
            padding: 40px; 
            border-radius: 20px; 
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 90%;
        }
        
        .friend-info { margin-bottom: 30px; }
        .friend-info img { 
            width: 120px; 
            height: 120px; 
            border-radius: 50%; 
            object-fit: cover; 
            border: 4px solid rgba(255,255,255,0.3);
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }
        .friend-info h2 { 
            margin: 15px 0 5px 0; 
            font-size: 28px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .remote-status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-left: 10px;
            font-size: 24px;
        }
        
        .remote-status i {
            transition: all 0.3s ease;
        }
        
        .status-badge { 
            display: inline-block; 
            padding: 8px 20px; 
            border-radius: 25px; 
            font-weight: bold; 
            margin: 15px 0;
            background: rgba(255,255,255,0.2);
            animation: pulse 2s infinite;
        }
        
        .call-timer {
            font-size: 32px;
            font-weight: bold;
            margin: 20px 0;
            display: none;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        #videoContainer { 
            width: 100%; 
            height: 350px; 
            background: #000; 
            margin: 20px 0; 
            border-radius: 15px;
            overflow: hidden;
            position: relative;
            display: none;
        }
        
        #localVideo, #remoteVideo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        #localVideo {
            position: absolute;
            width: 150px;
            height: 150px;
            bottom: 20px;
            right: 20px;
            border-radius: 10px;
            border: 2px solid white;
            z-index: 10;
        }
        
        .call-buttons { 
            display: flex; 
            justify-content: center; 
            gap: 20px; 
            margin-top: 30px;
        }
        
        .call-buttons button { 
            background: rgba(255,255,255,0.3); 
            border: none; 
            padding: 18px; 
            border-radius: 50%; 
            color: white; 
            font-size: 24px; 
            cursor: pointer; 
            width: 65px;
            height: 65px;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .call-buttons button:hover { 
            transform: scale(1.1);
            background: rgba(255,255,255,0.4);
        }
        
        .call-buttons .end-call { 
            background: #f44336;
            width: 80px;
            height: 80px;
            font-size: 28px;
        }
        
        .call-buttons .end-call:hover { 
            background: #d32f2f;
            transform: scale(1.15);
        }
        
        .call-buttons button.muted,
        .call-buttons button.video-off {
            background: rgba(244, 67, 54, 0.7);
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        @keyframes flash {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.3); }
        }
    </style>
</head>
<body>

<div class="call-container">
    <div class="friend-info">
        <img src="{{ asset($friend->profileimg ?? 'images/best3.png') }}" alt="{{ $friend->name }}">
        <h2>
            {{ $friend->name }}
            <span class="remote-status">
                <i id="remoteAudioIcon" class="fa fa-microphone" style="color: #4CAF50;" title="Remote Audio"></i>
                @if ($call->call_type == 'video')
                    <i id="remoteVideoIcon" class="fa fa-video" style="color: #4CAF50;" title="Remote Video"></i>
                @endif
            </span>
        </h2>
        
        <div style="font-size: 12px; opacity: 0.7; margin: 5px 0;">
            You: User {{ $user->id }} | Friend: User {{ $friend->id }}
        </div>

        <span class="status-badge" id="callStatus">
            <i class="fa fa-bell"></i> <span id="statusText">Calling...</span>
        </span>
        <div class="call-timer" id="callTimer">00:00</div>
    </div>

    <div id="videoContainer">
        <div id="remoteVideo" style="width:100%; height:100%;"></div>
        <div id="localVideo"></div>
    </div>

    <div class="call-buttons">
        <button id="muteBtn" title="Mute/Unmute">
            <i class="fa fa-microphone"></i>
        </button>

        @if ($call->call_type == 'video')
            <button id="videoBtn" title="Turn Video Off/On">
                <i class="fa fa-video"></i>
            </button>
        @endif

        <button class="end-call" id="endCallBtn" title="End Call">
            <i class="fa fa-phone-slash"></i>
        </button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/simple-peer@9/simplepeer.min.js"></script>
<script>
    // Enable Pusher logging
    if (window.Pusher) {
        Pusher.logToConsole = true;
    }

    // Configuration
    // Configuration
const CONFIG = {
    // Using the null-coalescing operator (??) in Blade as a safeguard
    callId: {{ $call->id ?? 0 }},
    currentUserId: {{ $user->id ?? 0 }},
    friendId: {{ $friend->id ?? 0 }},
    
    // Outputs the raw JS boolean 'true' or 'false'
    isCaller: {{ $call->caller_id == $user->id ? 'true' : 'false' }},
    
    // String value must be quoted
    callType: '{{ $call->call_type }}', 
    
    csrfToken: document.querySelector('meta[name="csrf-token"]').content
};

console.log('🎬 CALL PAGE LOADED - Version 2.0', CONFIG);
    // Call Application
    const CallApp = {
        localStream: null,
        peer: null,
        isMuted: false,
        isVideoOff: false,
        callTimer: null,
        callSeconds: 0,
        audioContext: null,
        ringbackInterval: null,
        isCallActive: false,

        async init() {
            console.log('🚀 Initializing call...');
            this.setupEventListeners();
            this.subscribeToChannel();
            
            if (CONFIG.isCaller) {
                this.playRingback();
                this.updateStatus('Calling...');
            } else {
                await this.acceptCall();
                this.onCallAnswered();
            }
            
            await this.initializeMedia();
        },

        setupEventListeners() {
            document.getElementById('muteBtn').onclick = () => this.toggleMute();
            document.getElementById('endCallBtn').onclick = () => this.endCall();
            
            const videoBtn = document.getElementById('videoBtn');
            if (videoBtn) {
                videoBtn.onclick = () => this.toggleVideo();
            }
        },

        subscribeToChannel() {
            if (!window.Echo) {
                console.error('❌ Echo not available');
                return;
            }

            console.log('📡 Subscribing to calls.' + CONFIG.callId);
            
            window.Echo.private(`calls.${CONFIG.callId}`)
                .listen('.CallAccepted', (e) => {
                    console.log('✅ Call answered');
                    this.onCallAnswered();
                })
                .listen('.CallSignal', (e) => {
                    if (e.from_user_id !== CONFIG.currentUserId && this.peer) {
                        const sig = typeof e.signal === 'string' ? JSON.parse(e.signal) : e.signal;
                        console.log('📥 Signal received:', sig?.type || 'unknown');
                        this.peer.signal(sig);
                    }
                })
                .listen('.ToggleMediaEvent', (e) => {
                    console.log('📡 ToggleMediaEvent received:', {
                        senderId: e.senderId,
                        type: e.type,
                        state: e.state,
                        isFromOther: e.senderId !== CONFIG.currentUserId
                    });
                    
                    if (e.senderId !== CONFIG.currentUserId) {
                        console.log('✅ UPDATING REMOTE STATUS NOW!');
                        this.updateRemoteStatus(e.type, e.state);
                    } else {
                        console.log('⏭️ Skipping own event');
                    }
                })
                .listen('.CallEnded', (e) => {
                    console.log('📴 Call ended');
                    this.handleCallEnded(e.reason);
                })
                .listen('.CallDeclined', (e) => {
                    console.log('📴 Call declined');
                    this.handleCallEnded('declined');
                });
        },

        updateRemoteStatus(type, state) {
            console.log('🎨 UPDATE FUNCTION CALLED!', type, state);
            
            if (type === 'audio') {
                const icon = document.getElementById('remoteAudioIcon');
                console.log('🎨 Icon element:', icon);
                
                if (!icon) {
                    console.error('❌ ICON NOT FOUND!');
                    return;
                }
                
                if (state) {
                    // MUTED
                    icon.className = 'fa fa-microphone-slash';
                    icon.style.color = '#ff0000';
                    icon.style.fontSize = '28px';
                    icon.style.animation = 'flash 0.5s';
                    console.log('🔴 SET TO RED (MUTED)');
                } else {
                    // UNMUTED
                    icon.className = 'fa fa-microphone';
                    icon.style.color = '#00ff00';
                    icon.style.fontSize = '28px';
                    icon.style.animation = 'flash 0.5s';
                    console.log('🟢 SET TO GREEN (UNMUTED)');
                }
                
                console.log('✅ Icon updated. New class:', icon.className, 'New color:', icon.style.color);
            }
            
            if (type === 'video') {
                const icon = document.getElementById('remoteVideoIcon');
                if (icon) {
                    if (state) {
                        icon.className = 'fa fa-video-slash';
                        icon.style.color = '#ff0000';
                    } else {
                        icon.className = 'fa fa-video';
                        icon.style.color = '#00ff00';
                    }
                }
            }
        },

        async initializeMedia() {
            try {
                const constraints = {
                    audio: true,
                    video: CONFIG.callType === 'video' ? { width: 640, height: 480 } : false
                };
                
                this.localStream = await navigator.mediaDevices.getUserMedia(constraints);
                
                if (CONFIG.callType === 'video') {
                    document.getElementById('videoContainer').style.display = 'block';
                    document.getElementById('localVideo').srcObject = this.localStream;
                }
                
                console.log('✅ Media initialized');
                this.initializePeer();
            } catch (error) {
                console.error('❌ Media error:', error);
                alert('Could not access camera/microphone: ' + error.message);
            }
        },

        initializePeer() {
            console.log('🔗 Initializing peer...');
            
            this.peer = new SimplePeer({
                initiator: CONFIG.isCaller,
                stream: this.localStream,
                trickle: true,
                config: {
                    iceServers: [
                        { urls: 'stun:stun.l.google.com:19302' }
                    ]
                }
            });
            
            this.peer.on('signal', (data) => {
                console.log('📤 Sending signal:', data.type);
                this.sendSignal(data);
            });
            
            this.peer.on('stream', (remoteStream) => {
                console.log('✅ Remote stream received');
                if (CONFIG.callType === 'video') {
                    document.getElementById('remoteVideo').srcObject = remoteStream;
                }
            });
            
            this.peer.on('connect', () => {
                console.log('🔗 Peer connected');
                this.onCallAnswered();
            });
            
            this.peer.on('close', () => {
                console.log('❌ Peer closed');
                this.handleCallEnded('disconnected');
            });
            
            this.peer.on('error', (err) => {
                console.error('❌ Peer error:', err);
            });
        },

        async sendSignal(signalData) {
            try {
                await fetch(`/calls/${CONFIG.callId}/signal`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CONFIG.csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        signal: JSON.stringify(signalData),
                        from_user_id: CONFIG.currentUserId
                    })
                });
            } catch (error) {
                console.error('❌ Signal error:', error);
            }
        },

        async sendMediaToggle(type, state) {
            try {
                console.log(`📡 Sending ${type} toggle:`, state);
                await fetch(`/calls/${CONFIG.callId}/toggle-media`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CONFIG.csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ 
                        type, 
                        state, 
                        senderId: CONFIG.currentUserId 
                    })
                });
                console.log('✅ Media toggle sent');
            } catch (error) {
                console.error('❌ Media toggle error:', error);
            }
        },

        async acceptCall() {
            try {
                await fetch(`/calls/${CONFIG.callId}/accept`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CONFIG.csrfToken,
                        'Content-Type': 'application/json'
                    }
                });
                console.log('✅ Call accepted');
            } catch (error) {
                console.error('❌ Accept error:', error);
            }
        },

        toggleMute() {
            if (!this.localStream) return;
            
            const audioTrack = this.localStream.getAudioTracks()[0];
            if (!audioTrack) return;
            
            audioTrack.enabled = !audioTrack.enabled;
            this.isMuted = !audioTrack.enabled;
            
            const btn = document.getElementById('muteBtn');
            const icon = btn.querySelector('i');
            
            if (this.isMuted) {
                icon.className = 'fa fa-microphone-slash';
                btn.classList.add('muted');
                console.log('🔇 Local muted');
            } else {
                icon.className = 'fa fa-microphone';
                btn.classList.remove('muted');
                console.log('🔊 Local unmuted');
            }
            
            this.sendMediaToggle('audio', this.isMuted);
        },

        toggleVideo() {
            if (!this.localStream) return;
            
            const videoTrack = this.localStream.getVideoTracks()[0];
            if (!videoTrack) return;
            
            videoTrack.enabled = !videoTrack.enabled;
            this.isVideoOff = !videoTrack.enabled;
            
            const btn = document.getElementById('videoBtn');
            const icon = btn.querySelector('i');
            
            if (this.isVideoOff) {
                icon.className = 'fa fa-video-slash';
                btn.classList.add('video-off');
                console.log('📵 Local video off');
            } else {
                icon.className = 'fa fa-video';
                btn.classList.remove('video-off');
                console.log('📹 Local video on');
            }
            
            this.sendMediaToggle('video', this.isVideoOff);
        },

        async endCall() {
            if (!confirm("End this call?")) return;
            
            this.cleanup();
            
            try {
                await fetch(`/calls/${CONFIG.callId}/end`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CONFIG.csrfToken,
                        'Content-Type': 'application/json'
                    }
                });
                window.location.href = `/messages/chat/${CONFIG.friendId}`;
            } catch (error) {
                console.error('❌ End call error:', error);
            }
        },

        onCallAnswered() {
            if (this.isCallActive) return;
            
            console.log('✅ Call connected');
            this.isCallActive = true;
            this.stopRingback();
            this.updateStatus('Connected');
            this.startTimer();
        },

        handleCallEnded(reason) {
            this.cleanup();
            alert(reason === 'declined' ? 'Call declined' : 'Call ended');
            window.location.href = `/messages/chat/${CONFIG.friendId}`;
        },

        playRingback() {
            try {
                this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
                
                const playBeep = () => {
                    if (!this.audioContext) return;
                    const osc = this.audioContext.createOscillator();
                    const gain = this.audioContext.createGain();
                    osc.connect(gain);
                    gain.connect(this.audioContext.destination);
                    osc.frequency.value = 440;
                    gain.gain.value = 0.2;
                    osc.start();
                    osc.stop(this.audioContext.currentTime + 0.4);
                };
                
                this.ringbackInterval = setInterval(playBeep, 2000);
                playBeep();
            } catch (error) {
                console.error('Ringback error:', error);
            }
        },

        stopRingback() {
            if (this.ringbackInterval) clearInterval(this.ringbackInterval);
            if (this.audioContext) this.audioContext.close();
        },

        startTimer() {
            document.getElementById('callTimer').style.display = 'block';
            document.getElementById('callStatus').style.display = 'none';
            
            this.callTimer = setInterval(() => {
                this.callSeconds++;
                const minutes = Math.floor(this.callSeconds / 60);
                const seconds = this.callSeconds % 60;
                document.getElementById('callTimer').textContent = 
                    `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            }, 1000);
        },

        updateStatus(text) {
            document.getElementById('statusText').textContent = text;
        },

        cleanup() {
            this.stopRingback();
            if (this.callTimer) clearInterval(this.callTimer);
            if (this.localStream) {
                this.localStream.getTracks().forEach(track => track.stop());
            }
            if (this.peer) this.peer.destroy();
        }
    };

    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => CallApp.init());
    } else {
        CallApp.init();
    }

    window.addEventListener('beforeunload', () => CallApp.cleanup());
</script>

</body>
</html> -->