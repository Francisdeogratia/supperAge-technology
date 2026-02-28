<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>{{ $stream->title }} — Live</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Agora RTC Web SDK 4.x -->
    <script src="https://download.agora.io/sdk/release/AgoraRTC_N-4.21.0.js"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: #000;
            color: #fff;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            height: 100dvh;
            overflow: hidden;
            touch-action: none;
        }

        /* ── Layout ── */
        #liveWrap {
            position: relative;
            width: 100vw;
            height: 100dvh;
            background: #111;
        }

        /* ── Video ── */
        #videoContainer {
            position: absolute;
            inset: 0;
            background: #000;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #videoContainer video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        #noVideoMsg {
            color: #aaa;
            font-size: 15px;
            text-align: center;
            padding: 20px;
        }
        #noVideoMsg .avatar-big {
            width: 90px; height: 90px; border-radius: 50%;
            object-fit: cover; margin-bottom: 12px;
            border: 3px solid #e91e8c;
        }

        /* ── Gradients for readability ── */
        .grad-top {
            position: absolute; top: 0; left: 0; right: 0; height: 120px;
            background: linear-gradient(to bottom, rgba(0,0,0,.7), transparent);
            z-index: 10; pointer-events: none;
        }
        .grad-bottom {
            position: absolute; bottom: 0; left: 0; right: 0; height: 200px;
            background: linear-gradient(to top, rgba(0,0,0,.8), transparent);
            z-index: 10; pointer-events: none;
        }

        /* ── Top bar ── */
        #topBar {
            position: absolute; top: 0; left: 0; right: 0;
            display: flex; align-items: center; gap: 10px;
            padding: 14px 16px;
            z-index: 20;
        }
        #backBtn {
            background: rgba(0,0,0,.45); border: none; color: #fff;
            width: 36px; height: 36px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 16px; flex-shrink: 0;
            text-decoration: none;
        }
        #streamInfo { flex: 1; min-width: 0; }
        #creatorRow {
            display: flex; align-items: center; gap: 8px; margin-bottom: 3px;
        }
        #creatorAvatar {
            width: 32px; height: 32px; border-radius: 50%;
            object-fit: cover; border: 2px solid #e91e8c; flex-shrink: 0;
        }
        #creatorName { font-weight: 700; font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        #liveBadge {
            background: #e91e8c; color: #fff; font-size: 11px; font-weight: 800;
            padding: 2px 8px; border-radius: 4px; letter-spacing: 1px; flex-shrink: 0;
        }
        #streamTitle {
            font-size: 12px; color: rgba(255,255,255,.8);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        #viewerBadge {
            display: flex; align-items: center; gap: 5px;
            background: rgba(0,0,0,.5); border-radius: 20px;
            padding: 5px 10px; font-size: 13px; font-weight: 600;
            flex-shrink: 0;
        }
        #viewerBadge i { color: #e91e8c; }

        /* ── Status pill ── */
        #statusPill {
            position: absolute; top: 60px; left: 50%; transform: translateX(-50%);
            background: rgba(0,0,0,.6); border-radius: 20px;
            padding: 5px 14px; font-size: 12px; z-index: 20;
            display: none; text-align: center;
        }

        /* ── Floating comments ── */
        #commentsFloat {
            position: absolute; left: 12px; bottom: 100px; right: 180px;
            z-index: 20;
            display: flex; flex-direction: column; justify-content: flex-end;
            gap: 6px;
            max-height: 45vh;
            overflow: hidden;
            pointer-events: none;
        }
        .float-comment {
            display: flex; align-items: flex-start; gap: 8px;
            animation: fadeSlideUp .3s ease;
            pointer-events: auto;
        }
        .float-comment img {
            width: 28px; height: 28px; border-radius: 50%;
            object-fit: cover; flex-shrink: 0;
        }
        .float-comment .bubble {
            background: rgba(0,0,0,.55);
            backdrop-filter: blur(6px);
            border-radius: 14px; padding: 6px 10px;
            font-size: 13px; line-height: 1.4; max-width: 100%;
            word-break: break-word;
        }
        .float-comment .bubble strong { color: #e91e8c; font-size: 12px; display: block; margin-bottom: 1px; }
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Reactions ── */
        #reactionsArea {
            position: absolute; right: 16px; bottom: 110px;
            z-index: 20; display: flex; flex-direction: column; align-items: center; gap: 6px;
            pointer-events: none;
        }
        .reaction-heart {
            font-size: 24px; animation: floatHeart 2s ease forwards;
            pointer-events: none; position: absolute;
        }
        @keyframes floatHeart {
            0%   { opacity: 1; transform: translateY(0) scale(1); }
            100% { opacity: 0; transform: translateY(-120px) scale(1.4); }
        }

        /* ── Bottom controls ── */
        #bottomBar {
            position: absolute; bottom: 0; left: 0; right: 0;
            z-index: 20;
            padding: 10px 10px calc(16px + env(safe-area-inset-bottom, 0px));
            display: flex; align-items: center; gap: 8px;
        }
        #commentInputWrap {
            flex: 1; min-width: 0; display: flex; align-items: center;
            background: rgba(255,255,255,.15); border-radius: 24px;
            padding: 8px 10px; gap: 6px;
            backdrop-filter: blur(4px);
            overflow: hidden;
        }
        #commentInputWrap input {
            flex: 1; min-width: 0; background: none; border: none; outline: none;
            color: #fff; font-size: 14px;
        }
        #commentInputWrap input::placeholder { color: rgba(255,255,255,.6); }
        #sendCommentBtn {
            background: #e91e8c; border: none; color: #fff;
            width: 30px; height: 30px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 13px; flex-shrink: 0;
        }

        .ctrl-btn {
            background: rgba(255,255,255,.15); border: none; color: #fff;
            width: 44px; height: 44px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 17px; flex-shrink: 0;
            backdrop-filter: blur(4px); transition: background .2s;
        }
        .ctrl-btn:hover { background: rgba(255,255,255,.3); }
        .ctrl-btn.danger { background: rgba(220,53,69,.8); }
        .ctrl-btn.muted  { background: rgba(220,53,69,.6); }

        #likeBtn { position: relative; }
        #likeCount {
            position: absolute; top: -6px; right: -6px;
            background: #e91e8c; color: #fff;
            font-size: 10px; font-weight: 700;
            border-radius: 10px; padding: 1px 4px; min-width: 16px; text-align: center;
        }

        /* ── End button (creator) ── */
        #endBtn {
            background: rgba(220,53,69,.85); border: none; color: #fff;
            border-radius: 20px; padding: 8px 16px; font-size: 13px; font-weight: 700;
            cursor: pointer; flex-shrink: 0; white-space: nowrap;
        }

        /* ── Stream ended overlay ── */
        #endedOverlay {
            display: none; position: absolute; inset: 0;
            background: rgba(0,0,0,.85); z-index: 50;
            flex-direction: column; align-items: center; justify-content: center; gap: 16px;
        }
        #endedOverlay.show { display: flex; }
        #endedOverlay h2 { font-size: 22px; font-weight: 800; }
        #endedOverlay p  { color: #aaa; font-size: 14px; text-align: center; }
        #endedOverlay .stats-grid {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 16px; text-align: center; margin: 8px 0;
        }
        #endedOverlay .stat-val { font-size: 24px; font-weight: 800; color: #e91e8c; }
        #endedOverlay .stat-lbl { font-size: 11px; color: #aaa; }

        /* ── Reward popup ── */
        #rewardPopup {
            display: none; position: absolute; top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            background: linear-gradient(135deg, #e91e8c, #ff6b35);
            border-radius: 20px; padding: 24px 32px; text-align: center; z-index: 40;
        }
        #rewardPopup h3 { font-size: 20px; font-weight: 800; }
        #rewardPopup p  { font-size: 14px; opacity: .9; margin-top: 6px; }

        /* ── Loading spinner ── */
        #loadingOverlay {
            position: absolute; inset: 0; background: #000;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center; gap: 14px;
            z-index: 30;
        }
        #loadingOverlay .spinner {
            width: 48px; height: 48px; border: 4px solid rgba(255,255,255,.2);
            border-top-color: #e91e8c; border-radius: 50%;
            animation: spin .8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        #loadingOverlay p { color: #aaa; font-size: 14px; }

        /* ── Duration ── */
        #durationBadge {
            position: absolute; top: 14px; right: 16px;
            background: rgba(0,0,0,.5); border-radius: 12px;
            padding: 4px 10px; font-size: 12px; font-weight: 600; z-index: 21;
        }

        /* ── Mobile ── */
        @media (max-width: 480px) {
            #commentsFloat { right: 60px; bottom: 90px; }
            .ctrl-btn { width: 38px; height: 38px; font-size: 14px; }
            #endBtn { padding: 7px 10px; font-size: 12px; }
            #commentInputWrap input { font-size: 13px; }
        }
    </style>
</head>
<body>
<div id="liveWrap">

    <!-- Loading overlay -->
    <div id="loadingOverlay">
        <div class="spinner"></div>
        <p id="loadingMsg">Connecting to live stream…</p>
    </div>

    <!-- Video area -->
    <div id="videoContainer"></div>

    <!-- No video placeholder (outside videoContainer so Agora doesn't delete it) -->
    <div id="noVideoMsg" style="display:none; position:absolute; inset:0; z-index:5;
         flex-direction:column; align-items:center; justify-content:center; background:#111;">
        <img src="{{ $stream->creator->profileimg ?? asset('images/best3.png') }}" class="avatar-big" alt="">
        <p style="color:#aaa; font-size:15px; margin-top:10px;">
            {{ $stream->creator->name ?? 'Host' }} is preparing the stream…
        </p>
    </div>

    <!-- Gradients -->
    <div class="grad-top"></div>
    <div class="grad-bottom"></div>

    <!-- Top bar -->
    <div id="topBar">
        <a href="{{ route('live.index') }}" id="backBtn" onclick="handleBack()"><i class="fa fa-chevron-left"></i></a>
        <div id="streamInfo">
            <div id="creatorRow">
                <img src="{{ $stream->creator->profileimg ?? asset('images/best3.png') }}" id="creatorAvatar" alt="">
                <span id="creatorName">{{ $stream->creator->name ?? 'Host' }}</span>
                <span id="liveBadge">LIVE</span>
            </div>
            <div id="streamTitle">{{ $stream->title }}</div>
        </div>
        <div id="viewerBadge"><i class="fa fa-eye"></i> <span id="viewerCount">{{ $stream->viewer_count }}</span></div>
    </div>

    <!-- Duration -->
    <div id="durationBadge">00:00</div>

    <!-- Status pill -->
    <div id="statusPill"></div>

    <!-- Floating comments -->
    <div id="commentsFloat"></div>

    <!-- Floating reactions -->
    <div id="reactionsArea"></div>

    <!-- Bottom bar -->
    <div id="bottomBar">
        @if($isCreator)
            <!-- Mic toggle -->
            <button class="ctrl-btn" id="micBtn" onclick="toggleMic()" title="Mute/Unmute">
                <i class="fas fa-microphone" id="micIcon"></i>
            </button>
            <!-- Camera toggle -->
            <button class="ctrl-btn" id="camBtn" onclick="toggleCam()" title="Camera on/off">
                <i class="fas fa-video" id="camIcon"></i>
            </button>
            <!-- Flip camera (mobile) -->
            <button class="ctrl-btn" id="flipBtn" onclick="flipCamera()" title="Flip camera">
                <i class="fas fa-sync-alt"></i>
            </button>
        @endif

        <!-- Comment input -->
        <div id="commentInputWrap">
            <input type="text" id="commentInput" placeholder="Say something…" maxlength="200"
                   onkeydown="if(event.key==='Enter') sendComment()">
            <button id="sendCommentBtn" onclick="sendComment()"><i class="fa fa-paper-plane"></i></button>
        </div>

        <!-- Like -->
        <button class="ctrl-btn" id="likeBtn" onclick="toggleLike()">
            <i class="fa fa-heart" id="likeIcon"></i>
            <span id="likeCount">{{ $stream->like_count }}</span>
        </button>

        @if($isCreator)
            <button id="endBtn" onclick="confirmEndStream()">End Live</button>
        @endif
    </div>

    <!-- Stream ended overlay -->
    <div id="endedOverlay">
        <div style="font-size:48px;">🎬</div>
        <h2>Stream Ended</h2>
        <p id="endedMsg">The live stream has ended.</p>
        <div class="stats-grid" id="endStats" style="display:none;">
            <div>
                <div class="stat-val" id="statViews">0</div>
                <div class="stat-lbl">Total Views</div>
            </div>
            <div>
                <div class="stat-val" id="statLikes">0</div>
                <div class="stat-lbl">Likes</div>
            </div>
            <div>
                <div class="stat-val" id="statDuration">0:00</div>
                <div class="stat-lbl">Duration</div>
            </div>
        </div>
        <a href="{{ route('live.index') }}" style="
            background:#e91e8c; color:#fff; border-radius:24px;
            padding:12px 32px; font-weight:700; text-decoration:none; margin-top:8px;">
            Back to Home
        </a>
    </div>

    <!-- Reward popup -->
    <div id="rewardPopup">
        <div style="font-size:36px;">🎉</div>
        <h3>You've earned ₦15,000!</h3>
        <p>Congratulations! You reached 1,000 views.</p>
    </div>

</div>

<script>
// ── Constants ──────────────────────────────────────────────────────────────────
const STREAM_ID   = {{ $stream->id }};
const IS_CREATOR  = {{ $isCreator ? 'true' : 'false' }};
const CSRF        = document.querySelector('meta[name="csrf-token"]').content;
const CREATOR_ID  = {{ $stream->creator_id }};

// ── State ──────────────────────────────────────────────────────────────────────
let agoraClient   = null;
let localAudioTrack = null;
let localVideoTrack = null;
let micMuted      = false;
let camOff        = false;
let facingMode    = 'user';
let userLiked     = false;
let lastCommentId = 0;
let streamSeconds = 0;
let durationTimer = null;
let statsTimer    = null;
let commentTimer  = null;
let streamActive  = true;

// ── Init ───────────────────────────────────────────────────────────────────────
window.addEventListener('DOMContentLoaded', initStream);

async function initStream() {
    showStatus('Fetching stream credentials…');
    try {
        const res  = await fetch(`/live/stream/${STREAM_ID}/token`);
        if (!res.ok) { showEnded('Could not connect to the stream. (HTTP ' + res.status + ')'); return; }
        const data = await res.json();
        if (data.error) { showEnded('Stream not available: ' + data.error); return; }
        if (!data.appId || !data.token) { showEnded('Stream configuration missing. Check AGORA_APP_ID in server .env'); return; }
        document.getElementById('statusPill').style.display = 'none';
        await joinAgora(data);
    } catch(e) {
        console.error(e);
        showEnded('Could not connect to the stream. Please try again.');
    }
}

async function joinAgora({ token, uid, appId, channel }) {
    AgoraRTC.setLogLevel(4); // errors only

    agoraClient = AgoraRTC.createClient({ mode: 'live', codec: 'vp8' });

    // Viewer count via subscription events
    agoraClient.on('user-published', handleUserPublished);
    agoraClient.on('user-unpublished', handleUserUnpublished);

    if (IS_CREATOR) {
        await agoraClient.setClientRole('host');
    } else {
        // level 1 = low latency audience
        await agoraClient.setClientRole('audience', { level: 1 });
    }
    await agoraClient.join(appId, channel, token, uid);

    if (IS_CREATOR) {
        await startBroadcast();
    } else {
        hideLoading();
        showNoVideo();
        showStatus('Waiting for host to start video…', 3000);
    }

    startTimers();
    loadRecentComments();
}

// ── Creator: start broadcast ───────────────────────────────────────────────────
async function startBroadcast() {
    showStatus('Starting camera & microphone…');
    hideRetryBtn();
    try {
        // Use simple config for best mobile compatibility
        [localAudioTrack, localVideoTrack] = await AgoraRTC.createMicrophoneAndCameraTracks(
            {},
            { facingMode }
        );
        ensureVideoDiv('localVideoDiv');
        localVideoTrack.play('localVideoDiv');

        await agoraClient.publish([localAudioTrack, localVideoTrack]);
        hideLoading();
        hideNoVideo();
        showStatus('You are LIVE! 🔴', 2000);
    } catch(e) {
        console.error('startBroadcast error:', e);
        hideLoading();
        let msg = 'Camera/mic error.';
        if (e.code === 'PERMISSION_DENIED' || (e.message && e.message.includes('Permission'))) {
            msg = 'Camera/mic permission denied. Please allow access and retry.';
        } else if (e.message) {
            msg = 'Error: ' + e.message;
        }
        showStatus(msg);
        showRetryBtn();
    }
}

function showRetryBtn() {
    let btn = document.getElementById('retryBroadcastBtn');
    if (!btn) {
        btn = document.createElement('button');
        btn.id = 'retryBroadcastBtn';
        btn.textContent = '🔄 Retry Camera';
        btn.style.cssText = 'position:absolute;bottom:120px;left:50%;transform:translateX(-50%);z-index:40;background:#e91e8c;color:#fff;border:none;border-radius:24px;padding:12px 28px;font-size:15px;font-weight:700;cursor:pointer;';
        btn.onclick = startBroadcast;
        document.getElementById('liveWrap').appendChild(btn);
    }
    btn.style.display = 'block';
}
function hideRetryBtn() {
    const btn = document.getElementById('retryBroadcastBtn');
    if (btn) btn.style.display = 'none';
}

// ── Viewer: receive stream ─────────────────────────────────────────────────────
async function handleUserPublished(user, mediaType) {
    await agoraClient.subscribe(user, mediaType);

    if (mediaType === 'video') {
        ensureVideoDiv('remoteVideoDiv');
        user.videoTrack.play('remoteVideoDiv');
        hideLoading();
        hideNoVideo();
    }
    if (mediaType === 'audio') {
        user.audioTrack.play();
    }
}

function handleUserUnpublished(user, mediaType) {
    if (mediaType === 'video' && !IS_CREATOR) {
        showNoVideo();
    }
}

// ── Creator controls ───────────────────────────────────────────────────────────
function toggleMic() {
    if (!localAudioTrack) return;
    micMuted = !micMuted;
    localAudioTrack.setEnabled(!micMuted);
    document.getElementById('micIcon').className = micMuted ? 'fas fa-microphone-slash' : 'fas fa-microphone';
    document.getElementById('micBtn').classList.toggle('muted', micMuted);
    showStatus(micMuted ? 'Microphone muted' : 'Microphone on', 1500);
}

function toggleCam() {
    if (!localVideoTrack) return;
    camOff = !camOff;
    localVideoTrack.setEnabled(!camOff);
    document.getElementById('camIcon').className = camOff ? 'fas fa-video-slash' : 'fas fa-video';
    document.getElementById('camBtn').classList.toggle('muted', camOff);
    showStatus(camOff ? 'Camera off' : 'Camera on', 1500);
}

async function flipCamera() {
    if (!localVideoTrack) return;
    facingMode = facingMode === 'user' ? 'environment' : 'user';
    await localVideoTrack.stop();
    await agoraClient.unpublish([localVideoTrack]);
    localVideoTrack.close();
    localVideoTrack = await AgoraRTC.createCameraVideoTrack({ encoderConfig: '720p_2', facingMode });
    localVideoTrack.play('localVideoDiv');
    await agoraClient.publish([localVideoTrack]);
}

// ── Comments ───────────────────────────────────────────────────────────────────
function sendComment() {
    const input = document.getElementById('commentInput');
    const text  = input.value.trim();
    if (!text) return;
    input.value = '';

    fetch(`/live/stream/${STREAM_ID}/comment`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ comment: text })
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) addFloatingComment(d.comment.user_name, d.comment.user_avatar, text, d.comment.id);
    })
    .catch(console.error);
}

function loadRecentComments() {
    fetch(`/live/stream/${STREAM_ID}/comments`)
        .then(r => r.json())
        .then(d => {
            const comments = [...d.comments].reverse();
            comments.forEach(c => addFloatingComment(c.user_name, c.user_avatar, c.comment, c.id));
        })
        .catch(console.error);
}

function pollComments() {
    fetch(`/live/stream/${STREAM_ID}/comments?after=${lastCommentId}`)
        .then(r => r.json())
        .then(d => {
            const newComments = d.comments.filter(c => c.id > lastCommentId);
            [...newComments].reverse().forEach(c => addFloatingComment(c.user_name, c.user_avatar, c.comment, c.id));
        })
        .catch(console.error);
}

function addFloatingComment(name, avatar, text, id) {
    if (id && id > lastCommentId) lastCommentId = id;
    const wrap = document.getElementById('commentsFloat');
    const div  = document.createElement('div');
    div.className = 'float-comment';
    div.innerHTML = `
        <img src="${avatar || '{{ asset("images/best3.png") }}'}" onerror="this.src='{{ asset("images/best3.png") }}'">
        <div class="bubble"><strong>${escHtml(name)}</strong>${escHtml(text)}</div>`;
    wrap.appendChild(div);
    // Keep max 8 visible
    while (wrap.children.length > 8) wrap.removeChild(wrap.firstChild);
    // Fade out after 12s
    setTimeout(() => div.remove(), 12000);
}

// ── Like ───────────────────────────────────────────────────────────────────────
function toggleLike() {
    fetch(`/live/stream/${STREAM_ID}/like`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF }
    })
    .then(r => r.json())
    .then(d => {
        userLiked = d.liked;
        document.getElementById('likeCount').textContent = d.like_count;
        document.getElementById('likeIcon').style.color = userLiked ? '#e91e8c' : '#fff';
        if (userLiked) spawnHearts();
    })
    .catch(console.error);
}

function spawnHearts() {
    const area = document.getElementById('reactionsArea');
    for (let i = 0; i < 5; i++) {
        setTimeout(() => {
            const h = document.createElement('span');
            h.className = 'reaction-heart';
            h.style.left = (Math.random() * 40 - 20) + 'px';
            h.style.bottom = '0';
            h.textContent = ['❤️','💕','💖','💗','💓'][Math.floor(Math.random()*5)];
            area.appendChild(h);
            setTimeout(() => h.remove(), 2000);
        }, i * 200);
    }
}

// ── Stats polling ──────────────────────────────────────────────────────────────
function pollStats() {
    fetch(`/live/stream/${STREAM_ID}/viewers`)
        .then(r => r.json())
        .then(d => {
            document.getElementById('viewerCount').textContent = d.viewer_count || 0;
            document.getElementById('likeCount').textContent   = d.like_count || 0;
            if (d.user_liked !== undefined) {
                userLiked = d.user_liked;
                document.getElementById('likeIcon').style.color = userLiked ? '#e91e8c' : '#fff';
            }
            if (d.show_reward) showReward();
        })
        .catch(console.error);
}

// ── Duration timer ─────────────────────────────────────────────────────────────
function startTimers() {
    durationTimer = setInterval(() => {
        streamSeconds++;
        document.getElementById('durationBadge').textContent = formatDuration(streamSeconds);
    }, 1000);

    statsTimer   = setInterval(pollStats,   5000);
    commentTimer = setInterval(pollComments, 2000);
}

function formatDuration(s) {
    const h = Math.floor(s / 3600);
    const m = Math.floor((s % 3600) / 60);
    const sec = s % 60;
    if (h > 0) return `${h}:${pad(m)}:${pad(sec)}`;
    return `${pad(m)}:${pad(sec)}`;
}
function pad(n) { return String(n).padStart(2, '0'); }

// ── End stream ─────────────────────────────────────────────────────────────────
function confirmEndStream() {
    if (!confirm('End your live stream?')) return;
    endStream();
}

async function endStream() {
    clearIntervals();
    if (localAudioTrack) { localAudioTrack.stop(); localAudioTrack.close(); }
    if (localVideoTrack) { localVideoTrack.stop(); localVideoTrack.close(); }
    if (agoraClient)     { await agoraClient.leave(); }

    fetch(`/live/stream/${STREAM_ID}/end`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF }
    })
    .then(r => r.json())
    .then(d => showEndedOverlay(d))
    .catch(() => showEndedOverlay({}));
}

function showEndedOverlay(data) {
    document.getElementById('endedMsg').textContent = IS_CREATOR
        ? 'Your live stream has ended. Here are your stats:'
        : 'This live stream has ended.';

    if (IS_CREATOR && data.stats) {
        const s = data.stats;
        document.getElementById('statViews').textContent   = s.total_views   || 0;
        document.getElementById('statLikes').textContent   = s.like_count    || 0;
        document.getElementById('statDuration').textContent = formatDuration(streamSeconds);
        document.getElementById('endStats').style.display  = 'grid';
    }
    document.getElementById('endedOverlay').classList.add('show');
    streamActive = false;
}

// ── Viewer: detect stream ended ────────────────────────────────────────────────
if (!IS_CREATOR) {
    setInterval(() => {
        if (!streamActive) return;
        fetch(`/live/stream/${STREAM_ID}/viewers`)
            .then(r => r.json())
            .catch(() => {})
            .then(d => {
                // If 404 / error, stream ended
                if (!d || d.error) showEnded('This stream has ended.');
            });
    }, 8000);
}

// ── Leave on unload ────────────────────────────────────────────────────────────
window.addEventListener('beforeunload', cleanup);
async function cleanup() {
    if (!IS_CREATOR && streamActive) {
        navigator.sendBeacon(`/live/stream/${STREAM_ID}/leave`,
            new Blob([JSON.stringify({ _token: CSRF })], { type: 'application/json' }));
    }
}

function handleBack() {
    cleanup();
    if (IS_CREATOR) endStream();
}

// ── Reward popup ───────────────────────────────────────────────────────────────
function showReward() {
    const p = document.getElementById('rewardPopup');
    p.style.display = 'block';
    setTimeout(() => p.style.display = 'none', 5000);
}

// ── Helpers ────────────────────────────────────────────────────────────────────
function showStatus(msg, autohide = 0) {
    const p = document.getElementById('statusPill');
    p.textContent = msg;
    p.style.display = 'block';
    if (autohide) setTimeout(() => p.style.display = 'none', autohide);
}
function hideLoading() {
    document.getElementById('loadingOverlay').style.display = 'none';
}
function ensureVideoDiv(id) {
    let el = document.getElementById(id);
    if (!el) {
        el = document.createElement('div');
        el.id = id;
        el.style.cssText = 'width:100%;height:100%;';
        document.getElementById('videoContainer').appendChild(el);
    }
    return el;
}
function showNoVideo() {
    const el = document.getElementById('noVideoMsg');
    if (el) el.style.display = 'flex';
}
function hideNoVideo() {
    const el = document.getElementById('noVideoMsg');
    if (el) el.style.display = 'none';
}
function showEnded(msg) {
    hideLoading();
    clearIntervals();
    document.getElementById('endedMsg').textContent = msg;
    document.getElementById('endedOverlay').classList.add('show');
}
function clearIntervals() {
    clearInterval(durationTimer);
    clearInterval(statsTimer);
    clearInterval(commentTimer);
}
function escHtml(str) {
    return String(str)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>
</body>
</html>
