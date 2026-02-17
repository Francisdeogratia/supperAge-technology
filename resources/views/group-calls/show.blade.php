<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Group Call - {{ $call->group->name }}</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: #0a0a0a;
            overflow: hidden;
            height: 100vh;
        }

        /* Sound Permission Banner */
        .sound-permission-banner {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #00a884 0%, #008069 100%);
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 9999;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { transform: translateY(-100%); }
            to { transform: translateY(0); }
        }

        .sound-permission-banner.hidden {
            display: none;
        }

        .sound-permission-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .sound-permission-icon {
            font-size: 24px;
        }

        .sound-enable-btn {
            background: white;
            color: #00a884;
            border: none;
            padding: 10px 25px;
            border-radius: 20px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .sound-enable-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(255,255,255,0.3);
        }

        /* Call Container */
        .call-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: relative;
            padding-top: 0;
        }

        .call-header {
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            backdrop-filter: blur(10px);
            z-index: 100;
        }

        .call-header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .call-header h2 {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }

        .call-duration {
            font-size: 13px;
            color: #aaa;
            font-family: 'Courier New', monospace;
        }

        .call-header-right {
            display: flex;
            gap: 10px;
        }

        .header-btn {
            background: rgba(255,255,255,0.1);
            border: none;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .header-btn:hover {
            background: rgba(255,255,255,0.2);
        }

        /* Participants Grid */
        .participants-grid {
            flex: 1;
            display: grid;
            gap: 8px;
            padding: 8px;
            overflow-y: auto;
            background: #1a1a1a;
        }

        /* Dynamic grid based on participant count */
        .participants-grid[data-count="1"] {
            grid-template-columns: 1fr;
        }

        .participants-grid[data-count="2"] {
            grid-template-columns: repeat(2, 1fr);
        }

        .participants-grid[data-count="3"],
        .participants-grid[data-count="4"] {
            grid-template-columns: repeat(2, 1fr);
        }

        .participants-grid[data-count="5"],
        .participants-grid[data-count="6"] {
            grid-template-columns: repeat(3, 1fr);
        }

        .participant-video {
            position: relative;
            background: #2a2a2a;
            border-radius: 12px;
            overflow: hidden;
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .participant-video video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .participant-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .participant-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #00a884 0%, #008069 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 12px;
            border: 3px solid rgba(255,255,255,0.2);
        }

        .participant-name {
            position: absolute;
            bottom: 12px;
            left: 12px;
            background: rgba(0,0,0,0.75);
            color: white;
            padding: 6px 12px;
            border-radius: 16px;
            font-size: 13px;
            font-weight: 500;
            backdrop-filter: blur(10px);
        }

        .participant-status {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }

        .status-ringing {
            background: rgba(255, 193, 7, 0.9);
            color: #000;
        }

        .status-joined {
            background: rgba(0, 168, 132, 0.9);
            color: white;
        }

        .status-declined {
            background: rgba(220, 53, 69, 0.9);
            color: white;
        }

        /* Call Controls */
        .call-controls {
            background: rgba(0,0,0,0.9);
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            backdrop-filter: blur(20px);
        }

        .call-btn {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(0,0,0,0.4);
            position: relative;
        }

        .call-btn:active {
            transform: scale(0.95);
        }

        .btn-mute,
        .btn-video,
        .btn-screen-share {
            background: rgba(60,60,60,1);
            color: white;
        }

        .btn-mute:hover,
        .btn-video:hover,
        .btn-screen-share:hover {
            background: rgba(80,80,80,1);
        }

        .btn-mute.active,
        .btn-video.active {
            background: #dc3545;
        }

        .btn-screen-share.active {
            background: #00a884;
        }

        .btn-end-call {
            background: #dc3545;
            color: white;
            width: 64px;
            height: 64px;
            font-size: 24px;
        }

        .btn-end-call:hover {
            background: #c82333;
        }

        /* Screen Share Overlay */
        .screen-share-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #000;
            z-index: 1000;
            display: none;
        }

        .screen-share-overlay.active {
            display: flex;
            flex-direction: column;
        }

        .screen-share-video {
            flex: 1;
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .screen-share-header {
            background: rgba(0,0,0,0.9);
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stop-share-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 600;
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            .call-header h2 {
                font-size: 16px;
            }

            .participants-grid {
                grid-template-columns: 1fr !important;
                gap: 6px;
                padding: 6px;
            }

            .participant-video {
                min-height: 180px;
            }

            .participant-avatar {
                width: 60px;
                height: 60px;
                font-size: 24px;
            }

            .call-btn {
                width: 52px;
                height: 52px;
                font-size: 18px;
            }

            .btn-end-call {
                width: 60px;
                height: 60px;
                font-size: 22px;
            }

            .call-controls {
                padding: 15px 10px;
                gap: 12px;
            }
        }

        /* Minimized Participant View */
        .minimized-participants {
            position: fixed;
            bottom: 100px;
            right: 20px;
            display: none;
            flex-direction: column;
            gap: 10px;
            z-index: 999;
        }

        .minimized-participants.active {
            display: flex;
        }

        .mini-participant {
            width: 120px;
            height: 160px;
            background: #2a2a2a;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.6);
        }

        .mini-participant video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Make sure call button is visible above other elements */
.active-call-btn {
    position: fixed;
    top: 80px;
    right: 20px;
    background: linear-gradient(135deg, #00a884 0%, #008069 100%);
    color: white;
    padding: 12px 20px;
    border-radius: 30px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    z-index: 10000; /* ✅ Increased z-index */
    box-shadow: 0 4px 20px rgba(0, 168, 132, 0.4);
    animation: callBtnBounce 2s infinite;
    transition: all 0.3s;
}
    </style>
</head>
<body>
    <!-- Sound Permission Banner -->
    <div class="sound-permission-banner" id="soundBanner">
        <div class="sound-permission-content">
            <i class="fa fa-volume-up sound-permission-icon"></i>
            <div>
                <strong>Enable Call Sounds</strong>
                <div style="font-size: 12px; opacity: 0.9;">Click to enable notification sounds for this call</div>
            </div>
        </div>
        <button class="sound-enable-btn" onclick="enableSound()">
            <i class="fa fa-check"></i> Enable Sound
        </button>
    </div>

    <!-- Screen Share Overlay -->
    <div class="screen-share-overlay" id="screenShareOverlay">
        <div class="screen-share-header">
            <div>
                <strong id="sharerName">Someone</strong> is sharing screen
            </div>
            <button class="stop-share-btn" onclick="stopScreenShare()">
                <i class="fa fa-stop"></i> Stop Sharing
            </button>
        </div>
        <video id="screenShareVideo" class="screen-share-video" autoplay playsinline></video>
    </div>

    <!-- Minimized Participants (when screen sharing) -->
    <div class="minimized-participants" id="miniParticipants">
        <!-- Will be populated dynamically -->
    </div>

    <div class="call-container">
        <div class="call-header">
            <div class="call-header-left">
                <div>
                    <h2>{{ $call->group->name }}</h2>
                    <div class="call-duration" id="callDuration">00:00</div>
                </div>
            </div>
            <div class="call-header-right">
                <button class="header-btn" onclick="toggleParticipantsList()" title="Participants">
                    <i class="fa fa-users"></i>
                </button>
            </div>
        </div>

        <div class="participants-grid" id="participantsGrid" data-count="{{ $call->participants->count() }}">
            {{-- Local video (current user) --}}
            <div class="participant-video" id="localParticipant">
                <div id="localVideo" style="width:100%;height:100%;"></div>
                <div class="participant-placeholder" id="localPlaceholder">
                    <div class="participant-avatar">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <p>You</p>
                </div>
                <div class="participant-name">You</div>
                <div class="participant-status status-joined">Joined</div>
            </div>

            {{-- Remote participants will be added dynamically by Agora --}}
        </div>

        <div class="call-controls">
            <button class="call-btn btn-mute" id="muteBtn" onclick="toggleMute()" title="Mute">
                <i class="fa fa-microphone"></i>
            </button>

            @if($call->call_type === 'video')
            <button class="call-btn btn-video" id="videoBtn" onclick="toggleVideo()" title="Camera">
                <i class="fa fa-video"></i>
            </button>
            @endif

            <button class="call-btn btn-end-call" onclick="endCall()" title="End Call">
                <i class="fa fa-phone-slash"></i>
            </button>

        </div>
    </div>

    <script src="https://download.agora.io/sdk/release/AgoraRTC_N-4.22.0.js"></script>
    <script>
        const callId = {{ $call->id }};
        const userId = {{ $user->id }};
        const callType = '{{ $call->call_type }}';
        const agoraChannel = '{{ $agoraChannel }}';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        let agoraClient = null;
        let localAudioTrack = null;
        let localVideoTrack = null;
        let isMuted = false;
        let isVideoOff = false;
        let soundEnabled = false;
        let callStartTime = new Date('{{ $call->started_at }}');
        let remoteUsers = {};

        // Sound functions
        function enableSound() {
            soundEnabled = true;
            document.getElementById('soundBanner').classList.add('hidden');
            playJoinSound();
        }

        function playJoinSound() {
            if (!soundEnabled) return;
            const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBTGH0fPTgjMGHm7A7+OZSA==');
            audio.play().catch(e => console.log('Sound play error:', e));
        }

        function playLeaveSound() {
            if (!soundEnabled) return;
            const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBTGH0fPTgjMGHm7A7+OZSA==');
            audio.volume = 0.7;
            audio.play().catch(e => console.log('Sound play error:', e));
        }

        function updateParticipantCount() {
            const grid = document.getElementById('participantsGrid');
            const count = grid.children.length;
            grid.setAttribute('data-count', count);
        }

        // Initialize call with Agora
        async function initializeCall() {
            try {
                // Join the call on the server
                await fetch(`/group-calls/${callId}/join`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                });

                // Get Agora token
                const tokenResponse = await fetch('/agora/token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ channel: agoraChannel })
                });
                const tokenData = await tokenResponse.json();

                if (!tokenData.success) {
                    console.error('Failed to get Agora token');
                    alert('Failed to connect to call.');
                    return;
                }

                // Create Agora client
                agoraClient = AgoraRTC.createClient({ mode: 'rtc', codec: 'vp8' });

                // Handle remote user published
                agoraClient.on('user-published', async (user, mediaType) => {
                    await agoraClient.subscribe(user, mediaType);
                    console.log('Subscribed to remote user:', user.uid, mediaType);
                    handleRemoteUser(user, mediaType);
                });

                agoraClient.on('user-unpublished', (user, mediaType) => {
                    console.log('Remote user unpublished:', user.uid, mediaType);
                    if (mediaType === 'video') {
                        const videoContainer = document.querySelector(`#remote-video-${user.uid}`);
                        if (videoContainer) videoContainer.innerHTML = '';
                    }
                });

                agoraClient.on('user-left', (user) => {
                    console.log('Remote user left:', user.uid);
                    handleRemoteLeft(user);
                    playLeaveSound();
                });

                // Join channel
                await agoraClient.join(tokenData.app_id, agoraChannel, tokenData.token, tokenData.uid);
                console.log('Joined Agora channel:', agoraChannel);

                // Create and publish local tracks
                if (callType === 'video') {
                    [localAudioTrack, localVideoTrack] = await AgoraRTC.createMicrophoneAndCameraTracks(
                        {}, { encoderConfig: '480p_1' }
                    );
                    const localContainer = document.getElementById('localVideo');
                    localVideoTrack.play(localContainer);
                    document.getElementById('localPlaceholder').style.display = 'none';
                    await agoraClient.publish([localAudioTrack, localVideoTrack]);
                } else {
                    localAudioTrack = await AgoraRTC.createMicrophoneAudioTrack();
                    await agoraClient.publish([localAudioTrack]);
                }

                console.log('Published local tracks');

                // Start timer
                updateCallDuration();
                setInterval(updateCallDuration, 1000);

            } catch (error) {
                console.error('Error initializing call:', error);
                alert('Failed to access camera/microphone. Please check permissions.');
            }
        }

        function handleRemoteUser(user, mediaType) {
            const uid = user.uid;
            let participantDiv = document.getElementById(`remote-${uid}`);

            if (!participantDiv) {
                participantDiv = document.createElement('div');
                participantDiv.id = `remote-${uid}`;
                participantDiv.className = 'participant-video';
                participantDiv.innerHTML = `
                    <div id="remote-video-${uid}" style="width:100%;height:100%;"></div>
                    <div class="participant-placeholder" id="remote-placeholder-${uid}">
                        <div class="participant-avatar">U</div>
                        <p>User ${uid}</p>
                    </div>
                    <div class="participant-name">User ${uid}</div>
                    <div class="participant-status status-joined">Joined</div>
                `;
                document.getElementById('participantsGrid').appendChild(participantDiv);
                remoteUsers[uid] = user;
                updateParticipantCount();
                playJoinSound();
            }

            if (mediaType === 'video') {
                const videoContainer = document.getElementById(`remote-video-${uid}`);
                user.videoTrack.play(videoContainer);
                document.getElementById(`remote-placeholder-${uid}`).style.display = 'none';
            }
            if (mediaType === 'audio') {
                user.audioTrack.play();
            }
        }

        function handleRemoteLeft(user) {
            const uid = user.uid;
            const participantDiv = document.getElementById(`remote-${uid}`);
            if (participantDiv) {
                participantDiv.remove();
            }
            delete remoteUsers[uid];
            updateParticipantCount();
        }

        function updateCallDuration() {
            const now = new Date();
            const diff = Math.floor((now - callStartTime) / 1000);
            const minutes = Math.floor(diff / 60);
            const seconds = diff % 60;
            document.getElementById('callDuration').textContent =
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }

        function toggleMute() {
            if (!localAudioTrack) return;
            isMuted = !isMuted;
            localAudioTrack.setEnabled(!isMuted);

            const btn = document.getElementById('muteBtn');
            const icon = btn.querySelector('i');

            if (isMuted) {
                btn.classList.add('active');
                icon.className = 'fa fa-microphone-slash';
            } else {
                btn.classList.remove('active');
                icon.className = 'fa fa-microphone';
            }
        }

        function toggleVideo() {
            if (!localVideoTrack) return;
            isVideoOff = !isVideoOff;
            localVideoTrack.setEnabled(!isVideoOff);

            const btn = document.getElementById('videoBtn');
            const icon = btn.querySelector('i');

            if (isVideoOff) {
                btn.classList.add('active');
                icon.className = 'fa fa-video-slash';
            } else {
                btn.classList.remove('active');
                icon.className = 'fa fa-video';
            }
        }

        async function endCall() {
            if (confirm('Leave the call?')) {
                // Cleanup Agora
                if (localAudioTrack) {
                    localAudioTrack.close();
                    localAudioTrack = null;
                }
                if (localVideoTrack) {
                    localVideoTrack.close();
                    localVideoTrack = null;
                }
                if (agoraClient) {
                    try { await agoraClient.leave(); } catch (e) {}
                    agoraClient = null;
                }

                await fetch(`/group-calls/${callId}/leave`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                });

                playLeaveSound();
                window.location.href = '/groups/{{ $call->group_id }}';
            }
        }

        function toggleParticipantsList() {
            const modal = document.getElementById('participantsModal');
            modal.style.display = modal.style.display === 'none' ? 'flex' : 'none';
        }

        function closeParticipantsList() {
            document.getElementById('participantsModal').style.display = 'none';
        }

        document.getElementById('participantsModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeParticipantsList();
            }
        });

        window.addEventListener('load', initializeCall);

        window.addEventListener('beforeunload', () => {
            if (localAudioTrack) localAudioTrack.close();
            if (localVideoTrack) localVideoTrack.close();
            if (agoraClient) { try { agoraClient.leave(); } catch (e) {} }
        });
    </script>

    <!-- Participants List Modal -->
<div class="participants-modal" id="participantsModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 15px; padding: 20px; max-width: 400px; width: 90%; max-height: 70vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0; color: #333;">Participants ({{ $call->participants->count() }})</h3>
            <button onclick="closeParticipantsList()" style="background: none; border: none; font-size: 28px; cursor: pointer; color: #999;">&times;</button>
        </div>
        
        @foreach($call->participants as $participant)
        <div style="display: flex; align-items: center; gap: 12px; padding: 12px; border-bottom: 1px solid #f0f0f0;">
            <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #00a884 0%, #008069 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 16px;">
                {{ strtoupper(substr($participant->user->name, 0, 1)) }}
            </div>
            <div style="flex: 1;">
                <div style="font-weight: 600; color: #333;">
                    {{ $participant->user->name }}
                    @if($participant->user_id == $user->id)
                    <span style="color: #00a884; font-size: 12px;">(You)</span>
                    @endif
                </div>
                <div style="font-size: 12px; color: #666;">
                    @if($participant->status === 'joined')
                        <span style="color: #00a884;">● In call</span>
                    @elseif($participant->status === 'ringing')
                        <span style="color: #ffc107;">● Ringing...</span>
                    @elseif($participant->status === 'declined')
                        <span style="color: #dc3545;">● Declined</span>
                    @else
                        <span style="color: #999;">● Left</span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
</body>
</html>