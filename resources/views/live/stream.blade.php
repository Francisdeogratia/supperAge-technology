<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>{{ $stream->title }} - Live</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    



<!-- Favicon -->

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
    <style>
        * {
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            margin: 0;
            background: #000;
            overflow: hidden;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        .stream-container {
            height: 100vh;
            display: flex;
            flex-direction: row;
            position: relative;
        }

        .video-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .video-area {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            z-index: 1;
        }

        #localVideo, #remoteVideo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            background: #1a1a1a;
        }

        .video-placeholder {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
            z-index: 1;
        }

        .video-placeholder i {
            font-size: 60px;
            margin-bottom: 15px;
            color: #FF0000;
        }

        .video-placeholder h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .live-indicator {
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(255, 0, 0, 0.9);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
            z-index: 10;
            backdrop-filter: blur(10px);
        }

        .live-dot {
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
            animation: blink 1.5s infinite;
        }

        @keyframes blink {
            0%, 50%, 100% { opacity: 1; }
            25%, 75% { opacity: 0.3; }
        }

        .viewer-count {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            z-index: 10;
            backdrop-filter: blur(10px);
        }

        .stats-overlay {
            position: absolute;
            bottom: 180px;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.5), transparent);
            padding: 20px 15px 15px;
            display: flex;
            justify-content: space-around;
            z-index: 5;
            pointer-events: none;
        }

        .stat-item {
            text-align: center;
            color: white;
        }

        .stat-value {
            font-size: 18px;
            font-weight: bold;
            display: block;
        }

        .stat-label {
            font-size: 11px;
            opacity: 0.8;
        }

        .stream-controls {
            background: transparent;
            padding: 15px;
            border-top: none;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 50;
        }

        .stream-info {
            color: white;
            margin-bottom: 12px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.8);
        }

        .stream-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .stream-creator {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            opacity: 0.95;
        }

        .creator-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            object-fit: cover;
        }

        .interaction-bar {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
            margin-bottom: 12px;
        }

        .interaction-btn {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 10px 6px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            user-select: none;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .interaction-btn i {
            font-size: 16px;
        }

        .interaction-btn span {
            font-size: 10px;
        }

        .interaction-btn:active {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(0.95);
        }

        .interaction-btn.liked {
            background: rgba(255, 0, 0, 0.8);
            color: white;
            border-color: rgba(255, 0, 0, 0.9);
        }

        .btn-end-stream, .btn-secondary {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 13px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            cursor: pointer;
            user-select: none;
            text-align: center;
            display: block;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .btn-end-stream {
            background: rgba(255, 0, 0, 0.7);
            color: white;
        }

        .btn-end-stream:active {
            background: rgba(204, 0, 0, 0.8);
            transform: scale(0.98);
        }

        .btn-secondary {
            background: rgba(51, 51, 51, 0.7);
            color: white;
            text-decoration: none;
        }

        .btn-secondary:active {
            background: rgba(68, 68, 68, 0.8);
            transform: scale(0.98);
        }

        /* Desktop Chat Sidebar */
        .chat-sidebar {
            width: 350px;
            background: #1a1a1a;
            border-left: 1px solid #333;
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 10;
        }

        .chat-header {
            padding: 15px;
            background: #2a2a2a;
            color: white;
            font-weight: bold;
            border-bottom: 1px solid #333;
            font-size: 15px;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 12px;
            display: flex;
            flex-direction: column-reverse;
        }

        .chat-message {
            margin-bottom: 12px;
            animation: slideIn 0.3s;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .chat-message-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 4px;
        }

        .chat-avatar {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            object-fit: cover;
        }

        .chat-username {
            color: #FF6B6B;
            font-weight: 600;
            font-size: 13px;
        }

        .chat-time {
            color: #888;
            font-size: 10px;
            margin-left: auto;
        }

        .chat-text {
            color: white;
            font-size: 13px;
            padding-left: 34px;
            word-wrap: break-word;
            line-height: 1.4;
        }

        .chat-input-area {
            padding: 12px;
            background: #2a2a2a;
            border-top: 1px solid #333;
        }

        .chat-input-form {
            display: flex;
            gap: 8px;
        }

        .chat-input {
            flex: 1;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 10px 12px;
            border-radius: 20px;
            font-size: 13px;
        }

        .chat-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .chat-input:focus {
            outline: none;
            border-color: #FF0000;
            background: rgba(255, 255, 255, 0.15);
        }

        .chat-send-btn {
            background: #FF0000;
            color: white;
            border: none;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chat-send-btn:active {
            background: #CC0000;
            transform: scale(0.95);
        }

        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }

        .chat-messages::-webkit-scrollbar-track {
            background: #1a1a1a;
        }

        .chat-messages::-webkit-scrollbar-thumb {
            background: #444;
            border-radius: 3px;
        }

        /* Floating Live Comments */
        .floating-comments {
            position: absolute;
            left: 20px;
            bottom: 200px;
            right: 20px;
            max-width: 400px;
            pointer-events: none;
            z-index: 20;
        }

        .floating-comment {
            background: rgba(0, 0, 0, 0.75);
            color: white;
            padding: 10px 15px;
            border-radius: 20px;
            margin-bottom: 10px;
            backdrop-filter: blur(10px);
            animation: slideInLeft 0.3s ease, fadeOut 0.5s ease 4.5s;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .floating-comment-user {
            font-weight: bold;
            color: #FF6B6B;
            margin-right: 8px;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
            }
        }

        /* Viewer List Modal */
        .viewer-list-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.9);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .viewer-list-modal.active {
            display: flex;
        }

        .viewer-list-content {
            background: #1a1a1a;
            border-radius: 15px;
            max-width: 400px;
            width: 100%;
            max-height: 70vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .viewer-list-header {
            padding: 20px;
            border-bottom: 1px solid #333;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .viewer-list-title {
            color: white;
            font-weight: bold;
            font-size: 18px;
        }

        .viewer-list-close {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
        }

        .viewer-list-body {
            padding: 20px;
            overflow-y: auto;
            flex: 1;
        }

        .viewer-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px;
            color: white;
            border-radius: 8px;
            margin-bottom: 8px;
            background: rgba(255, 255, 255, 0.05);
        }

        .viewer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .viewer-name {
            font-weight: 600;
        }

        .viewer-badge {
            margin-left: auto;
            background: #FF0000;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
        }
        .mobile-comment-btn {
            display: none;
        }

        @media (max-width: 768px) {
            .mobile-comment-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                position: fixed;
                bottom: 180px;
                right: 20px;
                width: 56px;
                height: 56px;
                background: rgba(255, 0, 0, 0.9);
                color: white;
                border: none;
                border-radius: 50%;
                font-size: 22px;
                box-shadow: 0 4px 15px rgba(255, 0, 0, 0.5);
                z-index: 100;
                cursor: pointer;
                backdrop-filter: blur(10px);
            }

            .mobile-comment-btn:active {
                transform: scale(0.9);
            }
        }

        .mobile-comment-btn:active {
            transform: scale(0.9);
        }

        /* Mobile Comment Modal */
        .comment-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.95);
            z-index: 1000;
            flex-direction: column;
        }

        .comment-modal.active {
            display: flex;
        }

        .modal-header {
            padding: 15px;
            background: #1a1a1a;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #333;
        }

        .modal-title {
            font-weight: bold;
            font-size: 16px;
        }

        .modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 28px;
            cursor: pointer;
            padding: 5px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:active {
            transform: scale(0.9);
        }

        .modal-body {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
        }

        .modal-footer {
            padding: 15px;
            background: #1a1a1a;
            border-top: 1px solid #333;
        }

        /* Reward Popup */
        .reward-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            z-index: 9999;
            box-shadow: 0 0 40px rgba(255, 215, 0, 0.5);
            opacity: 0;
            transition: all 0.5s ease;
            max-width: 90%;
        }

        .reward-popup.show {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
        }

        .reward-popup i {
            font-size: 60px;
            color: #FF0000;
            margin-bottom: 15px;
            animation: bounce 1s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        .reward-text {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }

        .reward-amount {
            font-size: 36px;
            font-weight: bold;
            color: #FF0000;
            margin-bottom: 8px;
        }

        .reward-message {
            font-size: 14px;
            color: #555;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .stream-container {
                flex-direction: column;
            }

            .video-section {
                height: 100vh;
                width: 100vw;
            }

            .video-area {
                position: fixed;
                height: 100vh;
            }

            .chat-sidebar {
                display: none;
            }

            .mobile-comment-btn {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .stats-overlay {
                bottom: 180px;
                padding: 15px 10px 10px;
                background: linear-gradient(to top, rgba(0,0,0,0.6), transparent);
            }

            .stat-value {
                font-size: 16px;
            }

            .stat-label {
                font-size: 10px;
            }

            .stream-controls {
                padding: 15px;
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: transparent;
                /* background: rgba(26, 26, 26, 0.95); */
                backdrop-filter: blur(15px);
                border: none;
                z-index: 50;
            }

            .interaction-bar {
                flex-wrap: nowrap;
                gap: 10px;
            }

            .interaction-btn {
                flex: 1;
                min-width: 70px;
                font-size: 12px;
                padding: 12px 6px;
            }

            .live-indicator, .viewer-count {
                font-size: 12px;
                padding: 6px 12px;
            }

            .video-placeholder i {
                font-size: 40px;
            }

            .video-placeholder h3 {
                font-size: 16px;
            }

            .stream-title {
                font-size: 15px;
            }

            .stream-creator {
                font-size: 12px;
            }

            .btn-end-stream, .btn-secondary {
                padding: 16px;
                font-size: 16px;
                touch-action: manipulation;
            }
        }

        @media (max-width: 480px) {
            .interaction-btn span {
                display: none;
            }

            .interaction-btn {
                padding: 12px;
            }

            .interaction-btn i {
                font-size: 18px;
            }

            .mobile-comment-btn {
                bottom: 160px;
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    
    <div class="stream-container">
        <div class="video-section">
            <div class="video-area">
                @if($isCreator)
                    <video id="localVideo" autoplay muted playsinline></video>
                    <div class="video-placeholder" id="videoPlaceholder">
                        <i class="fas fa-camera"></i>
                        <h3>Starting camera...</h3>
                        <p style="font-size: 14px;">Please allow camera access</p>
                    </div>
                @else
                    <video id="remoteVideo" autoplay playsinline></video>
                    <div class="video-placeholder" id="videoPlaceholder">
                        <i class="fas fa-broadcast-tower"></i>
                        <h3>{{ $stream->title }}</h3>
                        <p style="font-size: 14px;">Connecting to stream...</p>
                    </div>
                @endif

                <div class="live-indicator">
                    <div class="live-dot"></div>
                    LIVE
                </div>

                <div class="viewer-count">
                    <i class="fas fa-eye"></i> <span id="viewerNumber">{{ $stream->viewer_count }}</span>
                </div>

                <div class="stats-overlay">
                    <div class="stat-item">
                        <span class="stat-value" id="totalViews">{{ $stream->total_views }}</span>
                        <span class="stat-label">Views</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value" id="likeCount">{{ $stream->like_count ?? 0 }}</span>
                        <span class="stat-label">Likes</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value" id="peakViewers">{{ $stream->peak_viewers }}</span>
                        <span class="stat-label">Peak</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value" id="duration">0:00</span>
                        <span class="stat-label">Time</span>
                    </div>
                </div>

                <!-- Floating Live Comments -->
                <div class="floating-comments" id="floatingComments"></div>
            </div>

            <div class="stream-controls">
                <div class="stream-info">
                    <div class="stream-title">{{ $stream->title }}</div>
                    <div class="stream-creator">
                        <img src="{{ $stream->creator->profileimg ?? asset('images/best3.png') }}" 
                             alt="{{ $stream->creator->name }}" 
                             class="creator-avatar">
                        <span>{{ $stream->creator->name }}</span>
                        <span>•</span>
                        <span>{{ $stream->started_at->diffForHumans() }}</span>
                    </div>
                </div>

                @if(!$isCreator)
                <div class="interaction-bar">
                    <button class="interaction-btn" id="likeBtn" onclick="toggleLike()">
                        <i class="fas fa-heart"></i>
                        <span id="likeBtnText">Like</span>
                    </button>
                    <button class="interaction-btn" onclick="openCommentModal()">
                        <i class="fas fa-comment"></i>
                        <span>Comment</span>
                    </button>
                    <button class="interaction-btn" onclick="copyStreamLink()">
                        <i class="fas fa-link"></i>
                        <span>Copy</span>
                    </button>
                    <button class="interaction-btn" onclick="shareStream()">
                        <i class="fas fa-share"></i>
                        <span>Share</span>
                    </button>
                    <button class="interaction-btn" onclick="showViewerList()">
                        <i class="fas fa-users"></i>
                        <span id="viewerListCount">{{ $stream->viewer_count }}</span>
                    </button>
                </div>
                @else
                <div class="interaction-bar">
                    <button class="interaction-btn" onclick="openCommentModal()">
                        <i class="fas fa-comment"></i>
                        <span>Comment</span>
                    </button>
                    <button class="interaction-btn" onclick="copyStreamLink()">
                        <i class="fas fa-link"></i>
                        <span>Copy</span>
                    </button>
                    <button class="interaction-btn" onclick="shareStream()">
                        <i class="fas fa-share"></i>
                        <span>Share</span>
                    </button>
                    <button class="interaction-btn" onclick="showViewerList()">
                        <i class="fas fa-users"></i>
                        <span id="viewerListCountCreator">{{ $stream->viewer_count }}</span>
                    </button>
                    <button class="interaction-btn" style="grid-column: span 1;">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </button>
                </div>
                @endif

                @if($isCreator)
                <button class="btn-end-stream" onclick="endStream()" type="button">
                    <i class="fas fa-stop-circle"></i> End Stream
                </button>
                @else
                <a href="{{ route('live.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Streams
                </a>
                @endif
            </div>
        </div>

        <!-- Desktop Chat Sidebar -->
        <div class="chat-sidebar">
            <div class="chat-header">
                <i class="fas fa-comments"></i> Live Chat
            </div>
            
            <div class="chat-messages" id="chatMessages"></div>

            <div class="chat-input-area">
                <form class="chat-input-form" id="chatForm" onsubmit="return sendComment(event)">
                    <input type="text" 
                           class="chat-input" 
                           id="chatInput" 
                           placeholder="Say something..."
                           maxlength="500"
                           required>
                    <button type="submit" class="chat-send-btn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Mobile Comment Button - Show for both creator and viewer -->
    <button class="mobile-comment-btn" onclick="openCommentModal()" type="button" style="display: none;">
        <i class="fas fa-comment"></i>
    </button>

    <!-- Viewer List Modal -->
    <div class="viewer-list-modal" id="viewerListModal">
        <div class="viewer-list-content">
            <div class="viewer-list-header">
                <div class="viewer-list-title">
                    <i class="fas fa-users"></i> Live Viewers
                </div>
                <button class="viewer-list-close" onclick="closeViewerList()" type="button">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="viewer-list-body" id="viewerListBody">
                <p style="color: #888; text-align: center;">Loading viewers...</p>
            </div>
        </div>
    </div>

    <!-- Mobile Comment Modal -->
    <div class="comment-modal" id="commentModal">
        <div class="modal-header">
            <div class="modal-title">
                <i class="fas fa-comments"></i> Live Chat
            </div>
            <button class="modal-close" onclick="closeCommentModal()" type="button">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <div class="chat-messages" id="mobileChatMessages"></div>
        </div>

        <div class="modal-footer">
            <form class="chat-input-form" id="mobileCommentForm" onsubmit="return sendMobileComment(event)">
                <input type="text" 
                       class="chat-input" 
                       id="mobileCommentInput" 
                       placeholder="Say something..."
                       maxlength="500"
                       required>
                <button type="submit" class="chat-send-btn">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="reward-popup" id="rewardPopup">
        <i class="fas fa-trophy"></i>
        <div class="reward-text">🎉 CONGRATULATIONS! 🎉</div>
        <div class="reward-amount">₦15,000</div>
        <div class="reward-message">You've reached 1,000 views!</div>
    </div>

    <audio id="rewardSound" preload="auto">
        <source src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBTGH0fPTgjMGHm7A7+OZSAoVYrjr765dFAw=" type="audio/wav">
    </audio>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    
    <script>
        const csrfToken = '{{ csrf_token() }}';
        const streamId = {{ $stream->id }};
        const isCreator = {{ $isCreator ? 'true' : 'false' }};
        const creatorId = {{ $stream->creator_id }};
        let startTime = new Date('{{ $stream->started_at }}');
        let rewardShown = {{ $stream->reward_claimed ? 'true' : 'false' }};
        let userLiked = false;
        let localStream = null;
        let peerConnection = null;

        // Initialize Pusher
        const pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
            cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
            encrypted: true
        });

        const streamChannel = pusher.subscribe(`live-stream-${streamId}`);

        // Mobile modal functions
        function openCommentModal() {
            document.getElementById('commentModal').classList.add('active');
            loadComments('mobileChatMessages');
        }

        function closeCommentModal() {
            document.getElementById('commentModal').classList.remove('active');
        }

        // Initialize camera for creator
        if (isCreator) {
            initializeCamera();
        } else {
            initializeViewer();
        }

        async function initializeCamera() {
            try {
                const constraints = {
                    video: {
                        width: { ideal: 1280 },
                        height: { ideal: 720 },
                        facingMode: 'user'
                    },
                    audio: true
                };

                localStream = await navigator.mediaDevices.getUserMedia(constraints);
                const videoElement = document.getElementById('localVideo');
                videoElement.srcObject = localStream;
                
                videoElement.onloadedmetadata = () => {
                    document.getElementById('videoPlaceholder').style.display = 'none';
                };

                setupBroadcast();
            } catch (error) {
                console.error('Camera access error:', error);
                document.getElementById('videoPlaceholder').innerHTML = `
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Camera Access Denied</h3>
                    <p style="font-size: 14px;">Please allow camera access to go live</p>
                `;
            }
        }

        function setupBroadcast() {
            streamChannel.bind('viewer-joined', async (data) => {
                if (data.viewerId) {
                    createPeerConnection(data.viewerId);
                }
            });

            streamChannel.bind('ice-candidate', async (data) => {
                if (peerConnection && data.candidate) {
                    // Wait for remote description to be set
                    if (peerConnection.remoteDescription) {
                        try {
                            await peerConnection.addIceCandidate(new RTCIceCandidate(data.candidate));
                        } catch (e) {
                            console.error('Error adding ICE candidate:', e);
                        }
                    } else {
                        // Queue ICE candidates if remote description not set yet
                        setTimeout(async () => {
                            if (peerConnection.remoteDescription) {
                                try {
                                    await peerConnection.addIceCandidate(new RTCIceCandidate(data.candidate));
                                } catch (e) {
                                    console.error('Error adding queued ICE candidate:', e);
                                }
                            }
                        }, 100);
                    }
                }
            });
        }

        async function createPeerConnection(viewerId) {
            peerConnection = new RTCPeerConnection({
                iceServers: [
                    { urls: 'stun:stun.l.google.com:19302' },
                    { urls: 'stun:stun1.l.google.com:19302' },
                    { urls: 'stun:stun2.l.google.com:19302' }
                ],
                iceCandidatePoolSize: 10,
                bundlePolicy: 'max-bundle',
                rtcpMuxPolicy: 'require'
            });

            localStream.getTracks().forEach(track => {
                peerConnection.addTrack(track, localStream);
            });

            peerConnection.onicecandidate = (event) => {
                if (event.candidate) {
                    $.ajax({
                        url: '/live/pusher/ice-candidate',
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            _token: csrfToken,
                            streamId: streamId,
                            viewerId: viewerId,
                            candidate: event.candidate.toJSON()
                        })
                    });
                }
            };

            try {
                const offer = await peerConnection.createOffer({
                    offerToReceiveAudio: false,
                    offerToReceiveVideo: false
                });
                
                await peerConnection.setLocalDescription(offer);

                $.ajax({
                    url: '/live/pusher/offer',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        _token: csrfToken,
                        streamId: streamId,
                        viewerId: viewerId,
                        offer: peerConnection.localDescription.toJSON()
                    })
                });
            } catch (error) {
                console.error('Error creating offer:', error);
            }
        }

        function initializeViewer() {
            $.ajax({
                url: '/live/pusher/viewer-join',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    _token: csrfToken,
                    streamId: streamId
                })
            });

            streamChannel.bind('stream-offer', async (data) => {
                if (data.offer) {
                    await handleOffer(data.offer);
                }
            });

            streamChannel.bind('ice-candidate', async (data) => {
                if (peerConnection && data.candidate) {
                    try {
                        await peerConnection.addIceCandidate(new RTCIceCandidate(data.candidate));
                    } catch (e) {
                        console.error('Error adding ICE candidate:', e);
                    }
                }
            });
        }

        async function handleOffer(offer) {
            try {
                peerConnection = new RTCPeerConnection({
                    iceServers: [
                        { urls: 'stun:stun.l.google.com:19302' },
                        { urls: 'stun:stun1.l.google.com:19302' },
                        { urls: 'stun:stun2.l.google.com:19302' }
                    ],
                    iceCandidatePoolSize: 10,
                    bundlePolicy: 'max-bundle',
                    rtcpMuxPolicy: 'require',
                    sdpSemantics: 'unified-plan'
                });

                peerConnection.ontrack = (event) => {
                    const videoElement = document.getElementById('remoteVideo');
                    if (videoElement.srcObject !== event.streams[0]) {
                        videoElement.srcObject = event.streams[0];
                        document.getElementById('videoPlaceholder').style.display = 'none';
                    }
                };

                peerConnection.onicecandidate = (event) => {
                    if (event.candidate) {
                        $.ajax({
                            url: '/live/pusher/ice-candidate',
                            method: 'POST',
                            contentType: 'application/json',
                            data: JSON.stringify({
                                _token: csrfToken,
                                streamId: streamId,
                                candidate: event.candidate.toJSON()
                            })
                        });
                    }
                };

                // Try setting the offer directly first
                console.log('Attempting direct connection...');
                
                try {
                    await peerConnection.setRemoteDescription(new RTCSessionDescription(offer));
                } catch (sdpError) {
                    console.log('Direct connection failed, trying cleaned SDP...');
                    
                    // If direct fails, try cleaning the SDP
                    let cleanedSdp = offer.sdp.split('\n').filter(line => {
                        // Remove lines with msid that cause issues
                        if (line.includes('a=ssrc:') && line.includes('msid:')) {
                            return false;
                        }
                        return true;
                    }).join('\n');
                    
                    const cleanedOffer = {
                        type: offer.type,
                        sdp: cleanedSdp
                    };
                    
                    await peerConnection.setRemoteDescription(new RTCSessionDescription(cleanedOffer));
                }
                
                const answer = await peerConnection.createAnswer();
                await peerConnection.setLocalDescription(answer);

                $.ajax({
                    url: '/live/pusher/answer',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        _token: csrfToken,
                        streamId: streamId,
                        answer: peerConnection.localDescription.toJSON()
                    })
                });
                
                console.log('Stream connection established');
            } catch (error) {
                console.error('Connection error:', error);
                document.getElementById('videoPlaceholder').innerHTML = `
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Connection Error</h3>
                    <p style="font-size: 14px;">Refreshing in 3 seconds...</p>
                `;
                
                setTimeout(() => location.reload(), 3000);
            }
        }

        // Real-time comments via Pusher
        streamChannel.bind('new-comment', (data) => {
            addCommentToUI(data.comment);
            addFloatingComment(data.comment);
        });

        function addCommentToUI(comment) {
            const html = `
                <div class="chat-message">
                    <div class="chat-message-header">
                        <img src="${comment.user_avatar}" class="chat-avatar">
                        <span class="chat-username">${comment.user_name}</span>
                        <span class="chat-time">${comment.created_at}</span>
                    </div>
                    <div class="chat-text">${escapeHtml(comment.comment)}</div>
                </div>
            `;
            
            $('#chatMessages').prepend(html);
            $('#mobileChatMessages').prepend(html);
        }

        function addFloatingComment(comment) {
            const floatingDiv = $(`
                <div class="floating-comment">
                    <span class="floating-comment-user">${escapeHtml(comment.user_name)}:</span>
                    ${escapeHtml(comment.comment)}
                </div>
            `);
            
            $('#floatingComments').append(floatingDiv);
            
            // Remove after 5 seconds
            setTimeout(() => {
                floatingDiv.remove();
            }, 5000);
            
            // Keep only last 3 comments visible
            const allComments = $('#floatingComments .floating-comment');
            if (allComments.length > 3) {
                allComments.first().remove();
            }
        }

        function showViewerList() {
            $('#viewerListModal').addClass('active');
            loadViewers();
        }

        function closeViewerList() {
            $('#viewerListModal').removeClass('active');
        }

        function loadViewers() {
            $.get(`/live/stream/${streamId}/viewers-list`, function(data) {
                const viewerListBody = $('#viewerListBody');
                viewerListBody.empty();
                
                if (data.viewers && data.viewers.length > 0) {
                    data.viewers.forEach(function(viewer) {
                        viewerListBody.append(`
                            <div class="viewer-item">
                                <img src="${viewer.avatar}" class="viewer-avatar">
                                <div class="viewer-name">${viewer.name}</div>
                                ${viewer.is_creator ? '<span class="viewer-badge">HOST</span>' : ''}
                            </div>
                        `);
                    });
                } else {
                    viewerListBody.html('<p style="color: #888; text-align: center;">No viewers yet</p>');
                }
            }).fail(function() {
                $('#viewerListBody').html('<p style="color: #888; text-align: center;">Failed to load viewers</p>');
            });
        }

        // Update duration
        setInterval(function() {
            const now = new Date();
            const diff = Math.floor((now - startTime) / 1000);
            const hours = Math.floor(diff / 3600);
            const minutes = Math.floor((diff % 3600) / 60);
            const seconds = diff % 60;
            
            let durationText = hours > 0 
                ? `${hours}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
                : `${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            $('#duration').text(durationText);
        }, 1000);

        // Poll viewer count
        setInterval(function() {
            $.get(`/live/stream/${streamId}/viewers`, function(data) {
                $('#viewerNumber').text(data.viewer_count);
                $('#totalViews').text(data.total_views);
                $('#likeCount').text(data.like_count);
                $('#viewerListCount').text(data.viewer_count);
                $('#viewerListCountCreator').text(data.viewer_count);
                userLiked = data.user_liked;
                updateLikeButton();
                
                if (data.show_reward && !rewardShown) {
                    showReward();
                    rewardShown = true;
                }
            });
        }, 3000);

        // Load comments
        function loadComments(containerId = 'chatMessages') {
            $.get(`/live/stream/${streamId}/comments`, function(data) {
                const chatMessages = $('#' + containerId);
                chatMessages.empty();
                
                data.comments.forEach(function(comment) {
                    chatMessages.prepend(`
                        <div class="chat-message">
                            <div class="chat-message-header">
                                <img src="${comment.user_avatar}" class="chat-avatar">
                                <span class="chat-username">${comment.user_name}</span>
                                <span class="chat-time">${comment.created_at}</span>
                            </div>
                            <div class="chat-text">${escapeHtml(comment.comment)}</div>
                        </div>
                    `);
                });
            });
        }

        loadComments();
        setInterval(() => loadComments(), 5000);

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function sendComment(e) {
            e.preventDefault();
            const input = $('#chatInput');
            const comment = input.val().trim();
            
            if (!comment) return false;
            
            $.ajax({
                url: `/live/stream/${streamId}/comment`,
                method: 'POST',
                data: { _token: csrfToken, comment: comment },
                success: function(response) {
                    if (response.success) {
                        input.val('');
                    }
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.error || 'Failed to send comment');
                }
            });
            
            return false;
        }

        function sendMobileComment(e) {
            e.preventDefault();
            const input = $('#mobileCommentInput');
            const comment = input.val().trim();
            
            if (!comment) return false;
            
            $.ajax({
                url: `/live/stream/${streamId}/comment`,
                method: 'POST',
                data: { _token: csrfToken, comment: comment },
                success: function(response) {
                    if (response.success) {
                        input.val('');
                        closeCommentModal();
                    }
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.error || 'Failed to send comment');
                }
            });
            
            return false;
        }

        function toggleLike() {
            $.ajax({
                url: `/live/stream/${streamId}/like`,
                method: 'POST',
                data: { _token: csrfToken },
                success: function(response) {
                    if (response.success) {
                        userLiked = response.liked;
                        $('#likeCount').text(response.like_count);
                        updateLikeButton();
                    }
                }
            });
        }

        function updateLikeButton() {
            const btn = $('#likeBtn');
            if (btn.length && userLiked) {
                btn.addClass('liked');
                $('#likeBtnText').text('Liked');
            } else if (btn.length) {
                btn.removeClass('liked');
                $('#likeBtnText').text('Like');
            }
        }

        function copyStreamLink() {
            const link = window.location.href;
            if (navigator.clipboard) {
                navigator.clipboard.writeText(link).then(() => {
                    alert('Link copied to clipboard!');
                }).catch(() => {
                    fallbackCopy(link);
                });
            } else {
                fallbackCopy(link);
            }
        }

        function fallbackCopy(text) {
            const dummy = document.createElement('input');
            document.body.appendChild(dummy);
            dummy.value = text;
            dummy.select();
            document.execCommand('copy');
            document.body.removeChild(dummy);
            alert('Link copied to clipboard!');
        }

        function shareStream() {
            const shareData = {
                title: '{{ $stream->title }}',
                text: 'Watch {{ $stream->creator->name }} live on Supperage!',
                url: window.location.href
            };
            
            if (navigator.share) {
                navigator.share(shareData).catch(() => copyStreamLink());
            } else {
                copyStreamLink();
            }
        }

        function showReward() {
            try {
                document.getElementById('rewardSound').play();
            } catch(e) {
                console.log('Audio play failed:', e);
            }
            
            $('#rewardPopup').addClass('show');
            setTimeout(() => $('#rewardPopup').removeClass('show'), 5000);
        }

        function endStream() {
            if (!confirm('Are you sure you want to end this live stream?')) {
                return;
            }
            
            if (localStream) {
                localStream.getTracks().forEach(track => track.stop());
            }
            
            $.ajax({
                url: `/live/stream/${streamId}/end`,
                method: 'POST',
                data: { _token: csrfToken },
                success: function(response) {
                    if (response.success) {
                        alert(`Stream Ended Successfully!\n\nTotal Views: ${response.stats.total_views}\nPeak Viewers: ${response.stats.peak_viewers}\nDuration: ${response.stats.duration}`);
                        window.location.href = '{{ route("live.index") }}';
                    }
                },
                error: function() {
                    alert('Failed to end stream. Please try again.');
                }
            });
        }

        // Leave stream on exit (for viewers)
        if (!isCreator) {
            window.addEventListener('beforeunload', function() {
                navigator.sendBeacon(`/live/stream/${streamId}/leave`, new URLSearchParams({ _token: csrfToken }));
            });
        }

        // Prevent accidental refresh during streaming
        if (isCreator) {
            window.addEventListener('beforeunload', function(e) {
                e.preventDefault();
                e.returnValue = 'You are currently live. Are you sure you want to leave?';
                return e.returnValue;
            });
        }
    </script>
</body>
</html>