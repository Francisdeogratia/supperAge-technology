<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $group->name }} - Group Chat</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>

    
    <style>
/* Chat Container Styles — Matching 1-on-1 Chat */
.chat-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    flex-direction: column;
    background: #E8EFF5;
}

.chat-header {
    background: #0EA5E9;
    color: white;
    padding: 10px 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.15);
    z-index: 10;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px 60px;
    background-color: #E8EFF5;
    background-image: url("data:image/svg+xml,%3Csvg width='400' height='400' xmlns='http://www.w3.org/2000/svg'%3E%3Cdefs%3E%3Cpattern id='p' width='100' height='100' patternUnits='userSpaceOnUse'%3E%3Cpath d='M10 10h5v5h-5z' fill='%23c8d8e8' opacity='.3'/%3E%3Cpath d='M30 20h4v4h-4z' fill='%23c8d8e8' opacity='.2'/%3E%3Cpath d='M60 15h3v6h-3z' fill='%23c8d8e8' opacity='.25'/%3E%3Cpath d='M80 40h5v3h-5z' fill='%23c8d8e8' opacity='.2'/%3E%3Cpath d='M20 60h4v5h-4z' fill='%23c8d8e8' opacity='.3'/%3E%3Cpath d='M50 50h6v3h-6z' fill='%23c8d8e8' opacity='.2'/%3E%3Cpath d='M70 70h3v5h-3z' fill='%23c8d8e8' opacity='.25'/%3E%3Cpath d='M40 85h5v4h-5z' fill='%23c8d8e8' opacity='.2'/%3E%3Cpath d='M85 80h4v4h-4z' fill='%23c8d8e8' opacity='.3'/%3E%3Ccircle cx='15' cy='40' r='2' fill='%23c8d8e8' opacity='.2'/%3E%3Ccircle cx='55' cy='80' r='1.5' fill='%23c8d8e8' opacity='.25'/%3E%3Ccircle cx='90' cy='15' r='2' fill='%23c8d8e8' opacity='.2'/%3E%3C/pattern%3E%3C/defs%3E%3Crect width='400' height='400' fill='url(%23p)'/%3E%3C/svg%3E");
}

.chat-input-area {
    background: #F0F2F5;
    padding: 8px 16px;
    box-shadow: none;
    position: sticky;
    bottom: 0;
    z-index: 100;
}

/* Message Bubbles — Matching 1-on-1 Chat */
.message-bubble {
    max-width: 65%;
    padding: 6px 7px 8px 9px;
    border-radius: 7.5px;
    margin-bottom: 2px;
    position: relative;
    word-wrap: break-word;
    cursor: pointer;
    transition: none;
    box-shadow: 0 1px 0.5px rgba(11,20,26,0.13);
}

.message-bubble:hover {
    transform: none;
    box-shadow: 0 1px 0.5px rgba(11,20,26,0.13);
}

.message-bubble.sent {
    background: #D9FDD3;
    color: #111B21;
    margin-left: auto;
    border-top-right-radius: 0;
}

.message-bubble.sent::after {
    content: '';
    position: absolute;
    top: 0;
    right: -8px;
    width: 0;
    height: 0;
    border-left: 8px solid #D9FDD3;
    border-top: 0px solid transparent;
    border-bottom: 8px solid transparent;
}

.message-bubble.received {
    background: #FFFFFF;
    color: #111B21;
    border-top-left-radius: 0;
    box-shadow: 0 1px 0.5px rgba(11,20,26,0.13);
}

.message-bubble.received::before {
    content: '';
    position: absolute;
    top: 0;
    left: -8px;
    width: 0;
    height: 0;
    border-right: 8px solid #FFFFFF;
    border-top: 0px solid transparent;
    border-bottom: 8px solid transparent;
}

.message-bubble.highlighted {
    background: #fff3cd !important;
    border: 2px solid #ffc107;
}

.message-sender-name {
    font-size: 12.5px;
    font-weight: 600;
    color: #06CF9C;
    margin-bottom: 2px;
}

.reply-reference {
    background: rgba(0,0,0,0.05);
    font-size: 12px;
    margin-bottom: 5px;
    padding: 5px 10px;
    border-left: 3px solid #06CF9C;
    border-radius: 4px;
    font-style: italic;
    opacity: 1;
    cursor: pointer;
}

.message-bubble.sent .reply-reference {
    background: rgba(0,0,0,0.06);
    border-left-color: #06CF9C;
}

.message-time {
    font-size: 11px;
    color: #667781;
    float: right;
    margin-left: 8px;
    margin-top: 4px;
    opacity: 1;
}

.message-bubble.sent .message-time {
    color: #667781;
}

.message-status {
    font-size: 12px;
    margin-left: 3px;
}

/* Message Actions Menu — WhatsApp Style */
.message-actions {
    position: fixed;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.18);
    padding: 6px 0;
    z-index: 1000;
    display: none;
    min-width: 200px;
    max-height: 400px;
    overflow-y: auto;
}

.message-actions.show {
    display: block;
}

.message-action-item {
    padding: 10px 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: background 0.15s;
    font-size: 14.5px;
    color: #111B21;
}

.message-action-item:hover {
    background: #F0F2F5;
}

/* Menu Options — WhatsApp Style */
.chat-menu {
    position: absolute;
    right: 12px;
    top: 52px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.2);
    padding: 6px 0;
    display: none;
    min-width: 200px;
    z-index: 1000;
}

.chat-menu.show {
    display: block;
}

.menu-item {
    padding: 10px 22px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 14.5px;
    color: #111B21;
}

.menu-item:hover {
    background: #F5F6F6;
}

/* Reply Preview */
.reply-preview-container {
    background: #D9FDD3;
    padding: 8px 12px;
    margin-bottom: 8px;
    border-left: 4px solid #06CF9C;
    border-radius: 8px;
    display: none;
    font-size: 14px;
}

.reply-preview-container.show {
    display: block;
}

/* Voice Recording Interface */
.voice-recording-container {
    background: #0EA5E9;
    padding: 15px;
    border-radius: 12px;
    display: none;
    align-items: center;
    gap: 15px;
    margin-bottom: 10px;
    animation: slideUp 0.3s ease;
}

.voice-recording-container.show {
    display: flex;
}

@keyframes slideUp {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.recording-indicator {
    display: flex;
    align-items: center;
    gap: 10px;
    color: white;
    flex: 1;
}

.recording-pulse {
    width: 12px;
    height: 12px;
    background: #ff4444;
    border-radius: 50%;
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.3); opacity: 0.7; }
}

.recording-timer {
    font-size: 16px;
    font-weight: bold;
    font-family: 'Courier New', monospace;
}

.waveform {
    display: flex;
    align-items: center;
    gap: 2px;
    flex: 1;
}

.waveform-bar {
    width: 3px;
    background: rgba(255,255,255,0.6);
    border-radius: 2px;
    animation: wave 0.8s infinite ease-in-out;
}

.waveform-bar:nth-child(2) { animation-delay: 0.1s; }
.waveform-bar:nth-child(3) { animation-delay: 0.2s; }
.waveform-bar:nth-child(4) { animation-delay: 0.3s; }
.waveform-bar:nth-child(5) { animation-delay: 0.4s; }

@keyframes wave {
    0%, 100% { height: 10px; }
    50% { height: 25px; }
}

.voice-controls {
    display: flex;
    gap: 10px;
}

.voice-control-btn {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 18px;
    transition: all 0.2s;
}

.btn-cancel-recording {
    background: rgba(255,255,255,0.2);
    color: white;
}

.btn-send-recording {
    background: white;
    color: #0EA5E9;
}

/* Voice Note Bubble */
.voice-note-bubble {
    background: #dcf8c6;
    padding: 12px 15px;
    border-radius: 18px;
    max-width: 280px;
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.voice-note-bubble:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.voice-note-bubble.sent {
    background: #D9FDD3;
    color: #111B21;
}

.voice-play-btn {
    background: #0EA5E9;
    color: white;
}

.voice-play-btn:hover {
    background: #0284C7;
    transform: scale(1.1);
}

.voice-note-bubble.received {
    background: white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.voice-play-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(0,0,0,0.1);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    flex-shrink: 0;
}

.voice-play-btn:hover {
    background: rgba(0,0,0,0.2);
    transform: scale(1.1);
}

.voice-waveform {
    display: flex;
    align-items: center;
    gap: 2px;
    flex: 1;
    height: 30px;
}

.voice-wave-bar {
    width: 3px;
    background: currentColor;
    opacity: 0.4;
    border-radius: 2px;
    transition: all 0.1s;
}

.voice-wave-bar.active {
    opacity: 1;
    background: #0EA5E9;
}

.voice-duration {
    font-size: 12px;
    opacity: 0.8;
    white-space: nowrap;
}

/* File Upload Preview */
.file-upload-preview {
    display: none;
    padding: 8px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 8px;
    max-height: 120px;
    overflow-x: auto;
}

.file-upload-preview.show {
    display: flex;
    gap: 8px;
    flex-wrap: nowrap;
}

.file-preview-item {
    position: relative;
    flex-shrink: 0;
}

.file-preview-item img,
.file-preview-item video {
    max-width: 80px;
    max-height: 80px;
    border-radius: 8px;
    object-fit: cover;
}

.file-remove-btn {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 50%;
    width: 22px;
    height: 22px;
    cursor: pointer;
    font-size: 12px;
    line-height: 1;
}

/* Input Form */
#messageForm {
    display: flex;
    gap: 8px;
    align-items: flex-end;
}

.input-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    flex-shrink: 0;
    font-size: 18px;
}

.input-btn:active {
    transform: scale(0.95);
}

.btn-camera {
    background: #0EA5E9;
    color: white;
}

.btn-attach {
    background: #f0f2f5;
    color: #666;
}

.btn-emoji {
    background: #f0f2f5;
    font-size: 20px;
}

.btn-voice {
    background: #0EA5E9;
    color: white;
}

.btn-voice.recording {
    background: #EF4444;
    animation: pulse-button 1.5s infinite;
}

@keyframes pulse-button {
    0%, 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
    50% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
}

.btn-send {
    background: #0EA5E9;
    color: white;
}

#messageInput {
    flex: 1;
    border: 1px solid #e0e0e0;
    border-radius: 20px;
    padding: 10px 15px;
    font-size: 15px;
    resize: none;
    max-height: 100px;
    min-height: 40px;
}

#messageInput:focus {
    outline: none;
    border-color: #0EA5E9;
}

.hide-on-recording {
    transition: opacity 0.3s;
}

.hide-on-recording.hidden {
    opacity: 0;
    pointer-events: none;
}

/* Camera Modal */
.camera-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.95);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 2000;
    flex-direction: column;
    gap: 20px;
}

.camera-modal.show {
    display: flex;
}

#cameraPreview {
    width: 100%;
    max-width: 640px;
    border-radius: 12px;
}

.camera-controls {
    display: flex;
    gap: 15px;
}

.camera-btn {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: none;
    font-size: 24px;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-capture {
    background: #0EA5E9;
    color: white;
}

.btn-switch-camera {
    background: #0EA5E9;
    color: white;
}

.btn-close-camera {
    background: #f44336;
    color: white;
}

.camera-mode-toggle {
    position: absolute;
    top: 20px;
    right: 20px;
    background: rgba(0, 0, 0, 0.6);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    border: none;
    font-size: 14px;
    cursor: pointer;
    z-index: 10;
}

.video-recording-indicator {
    position: absolute;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(255, 0, 0, 0.9);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    display: none;
    align-items: center;
    gap: 10px;
    font-weight: bold;
    z-index: 10;
}

.video-recording-indicator.show {
    display: flex;
}

.video-rec-dot {
    width: 10px;
    height: 10px;
    background: white;
    border-radius: 50%;
    animation: blink 1s infinite;
}

@keyframes blink {
    0%, 50%, 100% { opacity: 1; }
    25%, 75% { opacity: 0.3; }
}

.btn-capture.video-mode {
    background: #f44336;
    animation: pulse-record 2s infinite;
}

@keyframes pulse-record {
    0%, 100% { box-shadow: 0 0 0 0 rgba(244, 67, 54, 0.7); }
    50% { box-shadow: 0 0 0 15px rgba(244, 67, 54, 0); }
}

/* Emoji Picker */
.emoji-picker {
    position: absolute;
    bottom: 60px;
    right: 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    padding: 15px;
    display: none;
    max-height: 300px;
    overflow-y: auto;
    z-index: 1000;
}

.emoji-picker.show {
    display: block;
}

.emoji-item {
    font-size: 24px;
    cursor: pointer;
    padding: 5px;
    display: inline-block;
}

.emoji-item:hover {
    transform: scale(1.3);
}

/* Upload Progress */
.upload-progress {
    margin-top: 8px;
    display: none;
}

.upload-progress.show {
    display: block;
}

.progress {
    height: 6px;
    background: #e0e0e0;
    border-radius: 3px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: #0EA5E9;
    transition: width 0.3s;
}

#uploadStatus {
    font-size: 12px;
    color: #666;
    margin-top: 4px;
    display: block;
}

/* Members Modal */
.members-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 2000;
}

.members-modal.show {
    display: flex;
}

.members-modal-content {
    background: white;
    border-radius: 15px;
    padding: 30px;
    max-width: 500px;
    width: 90%;
    max-height: 80vh;
    overflow-y: auto;
}

.member-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    border-bottom: 1px solid #f0f0f0;
}

.member-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
}

.member-info {
    flex: 1;
}

.member-name {
    font-weight: 600;
    color: #333;
}

.member-role {
    font-size: 12px;
    color: #666;
}

.btn-remove-member {
    background: #dc3545;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 12px;
    cursor: pointer;
}

/* Typing Indicator */
.typing-indicator {
    display: none;
    padding: 10px 15px;
}

.typing-indicator.show {
    display: flex;
    align-items: center;
    gap: 5px;
}

.typing-dot {
    width: 8px;
    height: 8px;
    background: #0EA5E9;
    border-radius: 50%;
    animation: typing 1.4s infinite;
}

.typing-dot:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-dot:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% {
        transform: translateY(0);
    }
    30% {
        transform: translateY(-10px);
    }
}

/* Message Reactions */
.message-reactions {
    position: absolute;
    bottom: -15px;
    right: 10px;
    background: white;
    border-radius: 15px;
    padding: 2px 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    font-size: 14px;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .chat-wrapper {
        top: 0;
    }

    .chat-messages {
        padding: 12px 12px;
    }

    .message-bubble {
        max-width: 85%;
    }

    .chat-header {
        padding: 8px 12px;
    }

    .chat-input-area {
        padding: 8px 10px;
    }

    .input-btn {
        width: 38px;
        height: 38px;
        font-size: 16px;
    }

    #messageInput {
        font-size: 16px;
        padding: 9px 12px;
    }

    .voice-note-bubble {
        max-width: 240px;
    }
}

/* Active Call Button */
.active-call-btn {
    position: fixed;
    top: 80px;
    right: 20px;
    background: #0EA5E9;
    color: white;
    padding: 12px 20px;
    border-radius: 30px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    z-index: 1000;
    box-shadow: 0 4px 20px rgba(14, 165, 233, 0.4);
    animation: callBtnBounce 2s infinite;
    transition: all 0.3s;
}

.active-call-btn:hover {
    transform: scale(1.05);
    text-decoration: none;
    color: white;
    box-shadow: 0 6px 25px rgba(14, 165, 233, 0.6);
}

.active-call-btn i {
    font-size: 18px;
    animation: phoneRing 1.5s infinite;
}

@keyframes callBtnBounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-5px);
    }
}

@keyframes phoneRing {
    0%, 100% {
        transform: rotate(0deg);
    }
    10%, 30% {
        transform: rotate(-15deg);
    }
    20%, 40% {
        transform: rotate(15deg);
    }
}

.call-pulse {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 30px;
    border: 2px solid #0EA5E9;
    animation: pulse 2s infinite;
    top: 0;
    left: 0;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    100% {
        transform: scale(1.3);
        opacity: 0;
    }
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .active-call-btn {
        top: auto;
        bottom: 80px;
        right: 15px;
        padding: 10px 16px;
        font-size: 14px;
    }
    
    .active-call-btn span {
        display: none;
    }
    
    .active-call-btn i {
        font-size: 20px;
    }
}

/* Pinned Message Banner */
.pinned-message-banner {
    background: #fff3cd;
    color: #111B21;
    padding: 10px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-left: 4px solid #ffc107;
    cursor: pointer;
    transition: all 0.2s;
}

.pinned-message-banner:hover {
    background: #fff0b3;
}

.pinned-content {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
}

.pinned-content i {
    font-size: 16px;
    transform: rotate(45deg);
}

.pinned-text {
    font-size: 14px;
}

.unpin-btn {
    background: rgba(0,0,0,0.1);
    border: none;
    color: #666;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.unpin-btn:hover {
    background: rgba(0,0,0,0.15);
    transform: scale(1.1);
}

.typing-indicator {
    padding: 6px 16px;
    background: #E8EFF5;
    font-size: 13px;
    color: #0EA5E9;
    font-style: italic;
    display: none;
}

.typing-indicator.show {
    display: block;
}


/* ✅ NEW: Link Preview Styles */
    .link-preview-container {
        background: #f8f9fa;
        border-left: 3px solid #0EA5E9;
        border-radius: 8px;
        padding: 8px;
        margin-top: 8px;
        margin-bottom: 8px;
        max-width: 350px;
        cursor: pointer;
        transition: all 0.2s;
        display: none;
    }
    
    .link-preview-container.show {
        display: block;
    }
    
    .link-preview-container:hover {
        background: #e9ecef;
        transform: translateX(2px);
    }
    
    .link-preview-loading {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px;
        font-size: 13px;
        color: #666;
    }
    
    .link-preview-content {
        text-decoration: none;
        color: inherit;
        display: block;
    }
    
    .link-preview-image {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 6px;
        margin-bottom: 8px;
    }
    
    .link-preview-favicon {
        width: 16px;
        height: 16px;
        margin-right: 6px;
        vertical-align: middle;
    }
    
    .link-preview-site {
        font-size: 11px;
        color: #666;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
    }
    
    .link-preview-title {
        font-weight: 600;
        font-size: 14px;
        color: #333;
        margin-bottom: 4px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    
    .link-preview-description {
        font-size: 12px;
        color: #666;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    
    .link-preview-url {
        font-size: 11px;
        color: #999;
        margin-top: 4px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .link-preview-remove {
        position: absolute;
        top: 4px;
        right: 4px;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        cursor: pointer;
        font-size: 12px;
        display: none;
    }
    
    .link-preview-container:hover .link-preview-remove {
        display: block;
    }
    
    /* Link preview in messages */
    .message-link-preview {
        background: rgba(255, 255, 255, 0.1);
        border-left: 3px solid rgba(255, 255, 255, 0.3);
        border-radius: 8px;
        padding: 10px;
        margin-top: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .message-bubble.received .message-link-preview {
        background: #f8f9fa;
        border-left-color: #0EA5E9;
    }
    
    .message-link-preview:hover {
        opacity: 0.9;
        transform: scale(0.98);
    }
    
    .message-link-preview-image {
        width: 100%;
        max-height: 180px;
        object-fit: cover;
        border-radius: 6px;
        margin-bottom: 8px;
    }
    
    .message-link-preview-title {
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 4px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    
    .message-link-preview-description {
        font-size: 12px;
        opacity: 0.8;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    
    .message-link-preview-site {
        font-size: 11px;
        opacity: 0.7;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    </style>
</head>
<body>
    @extends('layouts.app')
    
    @section('content')

    <!-- Add audio elements for beep sounds -->
<audio id="sendBeep" preload="auto">
    <source src="{{ asset('sounds/send_beep.wav') }}" type="audio/wav">
    <source src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBTGH0fPTgjMGHm7A7+OZSA" type="audio/wav">
</audio>

<audio id="receiveBeep" preload="auto">
    <source src="{{ asset('sounds/receive_beep.wav') }}" type="audio/wav">
    <source src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBTGH0fPTgjMGHm7A7+OZSA" type="audio/wav">
</audio>
    <div class="chat-wrapper">
        {{-- Chat Header --}}
        <div class="chat-header">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center flex-grow-1">
                    <a href="{{ route('groups.index') }}" class="text-white me-3 mr-2">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                    
                    <div class="position-relative me-3">
                        @if($group->group_image)
                            <img src="{{ $group->group_image }}"
                                 class="rounded-circle mr-2"
                                 style="width:40px;height:40px;object-fit:cover;">
                        @else
                            <div class="rounded-circle mr-2"
                                 style="width:40px;height:40px;background:#0EA5E9;display:flex;align-items:center;justify-content:center;color:white;font-weight:bold;font-size:16px;">
                                {{ strtoupper(substr($group->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    

                    <!-- Active call indicator - join via overlay -->
                    @if(isset($activeCall) && $activeCall)
                    <a href="#" class="active-call-btn" id="activeCallBtn" onclick="event.preventDefault(); GroupCallApp.joinCall({{ $activeCall->id }});">
                        <i class="fa fa-phone"></i>
                        <span>Join Call</span>
                        <div class="call-pulse"></div>
                    </a>
                    @endif
                    
                    <div class="flex-grow-1" style="cursor:pointer;" onclick="showGroupInfo()">
                        <h6 class="mb-0">{{ $group->name }}</h6>
                        <small>{{ $group->member_count }} members</small>
                    </div>
                </div>
                
                <div class="d-flex gap-3">
                    <a href="#" class="text-white mr-2" onclick="initiateGroupCall('audio', event)">
                        <i class="fa fa-phone fa-lg"></i>
                    </a>
                    <a href="#" class="text-white mr-3" onclick="initiateGroupCall('video', event)">
                        <i class="fa fa-video fa-lg"></i>
                    </a>
                    <a href="#" class="text-white" onclick="toggleChatMenu(event)">
                        <i class="fa fa-ellipsis-v fa-lg"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Add this after the chat header, before messages container --}}
                @if(isset($pinnedMessage) && $pinnedMessage)
                <div class="pinned-message-banner" id="pinnedBanner">
                    <div class="pinned-content">
                        <i class="fa fa-thumbtack"></i>
                        <div class="pinned-text">
                            <strong>{{ $pinnedMessage->sender->name }}:</strong>
                            {{ Str::limit($pinnedMessage->message, 50) }}
                        </div>
                    </div>
                    @if($isAdmin)
                    <button onclick="unpinMessage()" class="unpin-btn">
                        <i class="fa fa-times"></i>
                    </button>
                    @endif
                </div>
                @endif
        
        {{-- Chat Menu --}}
        {{-- Chat Menu --}}
<div class="chat-menu" id="chatMenu">
    <div class="menu-item" onclick="showMembersModal()">
        <i class="fa fa-users text-primary"></i>
        <span>View Members</span>
    </div>
    @if($isAdmin)
    <div class="menu-item" onclick="window.location.href='{{ route('groups.index') }}'">
        <i class="fa fa-user-plus text-success"></i>
        <span>Add Members</span>
    </div>
    @endif
    <!-- ✅ ADD REPORT GROUP -->
    <div class="menu-item" onclick="showReportModal()">
        <i class="fa fa-flag text-warning"></i>
        <span>Report Group</span>
    </div>
    <div class="menu-item" onclick="leaveGroup()">
        <i class="fa fa-sign-out-alt text-warning"></i>
        <span>Leave Group</span>
    </div>
    @if($isCreator)
    <div class="menu-item" onclick="deleteGroup()">
        <i class="fa fa-trash text-danger"></i>
        <span>Delete Group</span>
    </div>
    @endif
</div>

        
        
        {{-- Messages Container --}}
<div class="chat-messages" id="messagesContainer">
    @forelse($messages as $message)
        {{-- ✅ CHECK IF IT'S A CALL MESSAGE --}}
        @if($message->message_type === 'call')
            <div class="call-message-wrapper" style="text-align: center; margin: 8px 0;">
                <div class="call-message" style="display: inline-flex; align-items:center; gap:8px; background: white; padding: 8px 16px; border-radius: 7.5px; font-size: 13px; color: #111B21; box-shadow: 0 1px 0.5px rgba(11,20,26,0.13);">
                    <div style="width:32px;height:32px;border-radius:50%;background:#0EA5E9;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fa fa-{{ $message->call_type === 'video' ? 'video' : 'phone' }}" style="color:white;font-size:14px;"></i>
                    </div>
                    <div style="text-align:left;">
                        <div><strong>{{ $message->sender->name }}</strong> started a {{ $message->call_type }} call</div>
                        <div style="font-size: 11px; color: #667781; margin-top: 2px;">
                            {{ $message->created_at->format('g:i A') }}
                            @if($message->call_duration)
                                &middot; {{ gmdate('H:i:s', $message->call_duration) }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- REGULAR MESSAGE --}}
            <div class="d-flex {{ $message->sender_id == $user->id ? 'justify-content-end' : 'justify-content-start' }}">
                <div class="message-bubble {{ $message->sender_id == $user->id ? 'sent' : 'received' }}"
                     data-message-id="{{ $message->id }}"
                     data-message="{{ $message->message }}"
                     data-sender="{{ $message->sender_id }}"
                     onclick="handleMessageClick(event, this)"
                     oncontextmenu="showMessageActions(event, this); return false;">
                        
                        {{-- Show sender name for received messages --}}
                    @if($message->sender_id != $user->id)
                        <div class="message-sender-name">{{ $message->sender->name }}</div>
                    @endif
                        
                        {{-- Reply Reference --}}
                        @if($message->reply_to_id && $message->replyTo)
                            @php
                                $repliedToMessage = $message->replyTo;
                                $repliedToFiles = $repliedToMessage->file_path ? json_decode($repliedToMessage->file_path, true) : null;
                                $fileCount = $repliedToFiles ? count($repliedToFiles) : 0;
                                $displayText = \Str::limit($repliedToMessage->message, 30);
                            @endphp
                            
                            <div class="reply-reference" onclick="highlightMessage('{{ $repliedToMessage->id }}')">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-reply me-1"></i>
                                    <small style="opacity: 0.8;" class="ml-2">
                                        {{ $repliedToMessage->sender->name }}: {{ $displayText ?: ($fileCount ? 'Attachment' : 'Message') }}
                                    </small>
                                </div>
                            </div>
                        @endif
                        
                        {{-- Voice Note --}}
                        @if($message->voice_note)
                            @php
                                $duration = $message->voice_duration ?? 0;
                                $minutes = floor($duration / 60);
                                $seconds = $duration % 60;
                                $durationText = sprintf('%d:%02d', $minutes, $seconds);
                            @endphp
                            <div class="voice-note-bubble {{ $message->sender_id == $user->id ? 'sent' : 'received' }}" 
                                 onclick="playVoiceNote(this, '{{ $message->voice_note }}')"
                                 data-is-deleted="false">
                                <button class="voice-play-btn">
                                    <i class="fa fa-play"></i>
                                </button>
                                <div class="voice-waveform">
                                    <div class="voice-wave-bar" style="height: 15px;"></div>
                                    <div class="voice-wave-bar" style="height: 22px;"></div>
                                    <div class="voice-wave-bar" style="height: 18px;"></div>
                                    <div class="voice-wave-bar" style="height: 25px;"></div>
                                    <div class="voice-wave-bar" style="height: 12px;"></div>
                                    <div class="voice-wave-bar" style="height: 20px;"></div>
                                    <div class="voice-wave-bar" style="height: 16px;"></div>
                                    <div class="voice-wave-bar" style="height: 23px;"></div>
                                </div>
                                <span class="voice-duration">{{ $durationText }}</span>
                            </div>
                        @endif
                        
                        {{-- Message Content --}}
                        @if($message->message && !$message->voice_note)
                            <div class="message-content">
                                {{ $message->message }}
                                @if($message->is_edited)
                                    <small style="opacity:0.6;"> (edited)</small>
                                @endif
                            </div>
                        @endif

                        {{-- ✅ ADD THIS AFTER MESSAGE CONTENT AND BEFORE FILE ATTACHMENTS --}}
                @if($message->link_preview)
                    @php
                        $preview = json_decode($message->link_preview, true);
                    @endphp
                    @if($preview)
                        <div class="message-link-preview" onclick="window.open('{{ $preview['url'] }}', '_blank')">
                            @if(!empty($preview['image']))
                                <img src="{{ $preview['image'] }}" class="message-link-preview-image" alt="Link preview">
                            @endif
                            @if(!empty($preview['title']))
                                <div class="message-link-preview-title">{{ $preview['title'] }}</div>
                            @endif
                            @if(!empty($preview['description']))
                                <div class="message-link-preview-description">{{ $preview['description'] }}</div>
                            @endif
                            <div class="message-link-preview-site">
                                @if(!empty($preview['favicon']))
                                    <img src="{{ $preview['favicon'] }}" class="link-preview-favicon" onerror="this.style.display='none'">
                                @endif
                                <span>{{ $preview['site_name'] ?? parse_url($preview['url'], PHP_URL_HOST) }}</span>
                            </div>
                        </div>
                    @endif
                @endif
                
            
                        



                        {{-- File Attachments --}}
                        @if($message->file_path)
                            @php
                                $files = json_decode($message->file_path, true);
                                $fileCount = count($files);
                                  

                        if (!is_array($files) || $fileCount === 0) {
                                $files = [];
                                $fileCount = 0;
                            }
                            
                            $caption = trim($message->message) ?: 'Sent attachment';
                            $senderName = $message->sender_id == $user->id ? 'You' : $message->sender->username;
                            $fullCaption = "{$caption} (From: {$senderName})";
                            $firstFile = $fileCount > 0 ? $files[0] : null;
                        @endphp
                        
                        @if($fileCount > 0)
                            <div class="mt-2 attachment-grid d-flex align-items-end">
                                @if($firstFile)
                                    @php
                                        $isImage = Str::contains($firstFile, ['.jpg', '.jpeg', '.png', '.gif', '.webp']);
                                        $isVideo = Str::contains($firstFile, ['.mp4', '.webm', '.ogg']);
                                        $isPdf = Str::contains($firstFile, '.pdf');
                                    @endphp
                                    
                                    @if($isImage)
                                        <a data-fancybox="group-chat-{{ $message->id }}" 
                                            href="{{ $firstFile }}" 
                                            data-caption="{{ $fullCaption }}"
                                            class="d-inline-block me-1 mb-1 position-relative">
                                            <img src="{{ $firstFile }}" 
                                                class="img-fluid rounded attachment-thumb" 
                                                alt="Attached Image" 
                                                style="max-width:200px; max-height: 150px; object-fit: cover;">
                                        </a>
                                    
                                            @elseif($isVideo)
                                                <a data-fancybox="group-chat-{{ $message->id }}" 
                                                    href="{{ $firstFile }}" 
                                                    data-type="html5video"
                                                    data-caption="{{ $fullCaption }}"
                                                    class="d-inline-block me-1 mb-1">
                                                    <div class="video-thumb-wrapper" style="position:relative; max-width:200px; height:150px; overflow:hidden;">
                                                        <video class="rounded" style="width: 100%; height: 100%; object-fit: cover;" preload="metadata">
                                                            <source src="{{ $firstFile }}#t=0.1" type="video/mp4">
                                                        </video>
                                                        <span class="play-icon" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-size: 30px; background: rgba(0,0,0,0.5); border-radius: 50%; width: 40px; height: 40px; text-align: center; line-height: 40px;">&#9658;</span>
                                                    </div>
                                                </a>

                                    @elseif($isPdf)
                                        <a href="{{ $firstFile }}" target="_blank" class="btn btn-sm btn-light mt-1 me-1">
                                            <i class="fa fa-file-pdf text-danger"></i> View PDF
                                        </a>
                                    @else
                                        <a href="{{ $firstFile }}" target="_blank" class="btn btn-sm btn-light mt-1 me-1">
                                            <i class="fa fa-file"></i> Download File
                                        </a>
                                    @endif
                                @endif
                                
                                @if($fileCount > 1)
                                    @foreach(array_slice($files, 1) as $remainingFile)
                                        @php
                                            $isRemainingVideo = Str::contains($remainingFile, ['.mp4', '.webm', '.ogg']);
                                            $isRemainingImage = Str::contains($remainingFile, ['.jpg', '.jpeg', '.png', '.gif', '.webp']);
                                        @endphp
                                        <a data-fancybox="group-chat-{{ $message->id }}" 
                                        href="{{ $remainingFile }}" 
                                        data-type="{{ $isRemainingVideo ? 'html5video' : ($isRemainingImage ? 'image' : '') }}"
                                        data-caption="{{ $fullCaption }}"
                                        class="d-none">
                                        </a>
                                    @endforeach
                                    
                                    <span class="ms-2" 
                                          onclick="document.querySelector('[data-fancybox=&quot;group-chat-{{ $message->id }}&quot;]').click();"
                                          style="opacity: 0.7; font-size: 0.9rem; font-weight: 500; cursor: pointer;">
                                        +{{ $fileCount - 1 }} more file{{ $fileCount - 1 > 1 ? 's' : '' }}
                                    </span>
                                @endif
                            </div>
                        @endif
                    @endif
                    
                     {{-- ✅ TIME AND READ RECEIPTS --}}
                    <div class="message-time" style="display: flex; align-items: center; gap: 4px;">
                        <span>{{ $message->created_at->format('g:i A') }}</span>
                        
                        {{-- Show read receipts only for sent messages --}}
                        @if($message->sender_id == $user->id)
                            @if($message->status === 'seen')
                                <span style="color: #53BDEB;">✓✓</span>
                            @else
                                <span style="color: #667781;">✓</span>
                            @endif
                        @endif
                    </div>
                    
                    {{-- Reactions --}}
                    @if($message->reactions)
                        @php
                            $reactions = json_decode($message->reactions, true);
                        @endphp
                        @if(!empty($reactions))
                        <div class="message-reactions">
                            @foreach($reactions as $emoji => $userIds)
                                @if(count($userIds) > 0)
                                <span class="reaction-item" onclick="reactToMessage('{{ $emoji }}', {{ $message->id }})">
                                    {{ $emoji }} {{ count($userIds) }}
                                </span>
                                @endif
                            @endforeach
                        </div>
                        @endif
                    @endif
                </div>
            </div>
        @endif
    @empty
        <div class="text-center text-muted mt-5">
            <i class="fa fa-comments fa-3x mb-3" style="opacity:0.3;"></i>
            <p>No messages yet. Start the conversation!</p>
        </div>
    @endforelse
</div>
    
    {{-- ADD THIS BEFORE chat-input-area --}}
<div class="typing-indicator" id="typingIndicator">
    <span id="typingText"></span>
</div>

    {{-- Message Input Area --}}
    <div class="chat-input-area">
        {{-- Reply Preview --}}
        <div class="reply-preview-container" id="replyPreview">
            <div class="d-flex justify-content-between align-items-center">
                <small><strong>Replying to:</strong></small>
                <button style="background:none;border:none;color:#666;cursor:pointer;font-size:18px;" onclick="cancelReply()">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div style="font-size:13px;color:#555;margin-top:4px;" id="replyText"></div>
        </div>
        
        {{-- Voice Recording Interface --}}
        <div class="voice-recording-container" id="voiceRecordingContainer">
            <div class="recording-indicator">
                <div class="recording-pulse"></div>
                <span class="recording-timer" id="recordingTimer">0:00</span>
            </div>
            <div class="waveform">
                <div class="waveform-bar"></div>
                <div class="waveform-bar"></div>
                <div class="waveform-bar"></div>
                <div class="waveform-bar"></div>
                <div class="waveform-bar"></div>
            </div>
            <div class="voice-controls">
                <button class="voice-control-btn btn-cancel-recording" onclick="cancelVoiceRecording()">
                    <i class="fa fa-trash"></i>
                </button>
                <button class="voice-control-btn btn-send-recording" onclick="sendVoiceNote()">
                    <i class="fa fa-paper-plane"></i>
                </button>
            </div>
        </div>
        
        {{-- File Upload Preview --}}
        <div class="file-upload-preview" id="filePreview">
            <div id="filePreviewContainer"></div>
        </div>


        {{-- ✅ NEW: Link Preview in Input Area --}}
            <div class="link-preview-container" id="linkPreviewContainer">
                <div class="link-preview-loading" id="linkPreviewLoading">
                    <i class="fa fa-spinner fa-spin"></i>
                    <span>Loading preview...</span>
                </div>
                <div class="link-preview-content" id="linkPreviewContent" style="display:none;">
                    <button class="link-preview-remove" onclick="removeLinkPreview()">×</button>
                    <img id="linkPreviewImage" class="link-preview-image" style="display:none;">
                    <div class="link-preview-site" id="linkPreviewSite"></div>
                    <div class="link-preview-title" id="linkPreviewTitle"></div>
                    <div class="link-preview-description" id="linkPreviewDescription"></div>
                    <div class="link-preview-url" id="linkPreviewUrl"></div>
                </div>
            </div>
        




        {{-- Upload Progress --}}
        <div class="upload-progress" id="uploadProgress">
            <div class="progress">
                <div class="progress-bar" id="progressBar" style="width: 0%"></div>
            </div>
            <small id="uploadStatus">Uploading...</small>
        </div>
        
        {{-- Message Form --}}
        <form id="messageForm">
            @csrf
            <input type="file" 
                   id="fileInput" 
                   multiple 
                   accept="image/*,video/*,.pdf,.doc,.docx"
                   style="display:none;" 
                   onchange="handleFileSelect(event)">
            
            {{-- Camera Button --}}
            <button type="button" 
                    class="input-btn btn-camera hide-on-recording" 
                    onclick="openCamera()"
                    title="Take Photo/Video">
                <i class="fa fa-camera"></i>
            </button>
            
            {{-- Attach Files Button --}}
            <button type="button" 
                    class="input-btn btn-attach hide-on-recording" 
                    onclick="document.getElementById('fileInput').click()"
                    title="Attach Files">
                <i class="fa fa-paperclip"></i>
            </button>
            
            {{-- Message Input --}}
            <textarea id="messageInput" 
                      class="hide-on-recording" 
                      placeholder="Type a message..." 
                      rows="1"></textarea>
            
            {{-- Emoji Button --}}
            <button type="button" 
                    class="input-btn btn-emoji hide-on-recording" 
                    onclick="toggleEmojiPicker()">
                😊
            </button>
            
            {{-- Voice/Send Button --}}
            <button type="button" 
                    class="input-btn btn-voice" 
                    id="voiceBtn" 
                    onclick="toggleVoiceRecording()">
                <i class="fa fa-microphone" id="voiceIcon"></i>
            </button>
            
            {{-- Send Button --}}
            <button type="submit" 
                    class="input-btn btn-send hide-on-recording" 
                    id="sendBtn" 
                    style="display:none;">
                <i class="fa fa-paper-plane"></i>
            </button>
        </form>
    </div>
</div>


{{-- Message Actions Menu --}}
{{-- Message Actions Menu --}}
<div class="message-actions" id="messageActions">
    <div class="message-action-item" onclick="replyToMessage()">
        <i class="fa fa-reply"></i>
        <span>Reply</span>
    </div>
    <div class="message-action-item" onclick="copyMessage()">
        <i class="fa fa-copy"></i>
        <span>Copy</span>
    </div>
    <div class="message-action-item" id="editAction" onclick="editMessage()" style="display:none;">
        <i class="fa fa-edit"></i>
        <span>Edit</span>
    </div>
    <div class="message-action-item" onclick="showForwardModal()">
        <i class="fa fa-share"></i>
        <span>Forward</span>
    </div>
    <!-- ✅ ADD PIN MESSAGE -->
    <div class="message-action-item" id="pinAction" onclick="pinMessage()" style="display:none;">
        <i class="fa fa-thumbtack"></i>
        <span>Pin Message</span>
    </div>
    <div class="message-action-item" onclick="showReactionPicker()">
        <i class="fa fa-smile"></i>
        <span>React</span>
    </div>
    <div class="message-action-item text-danger" onclick="deleteMessage()" id="deleteAction">
        <i class="fa fa-trash"></i>
        <span>Delete</span>
    </div>
</div>

{{-- Emoji Picker --}}
<div class="emoji-picker" id="emojiPicker">
    @foreach(['❤️', '😀','😂', '👍', '🎉', '😊', '😍', '🔥', '👏', '✅', '💯', '🙏', '😎', '🤔', '😢', '😡', '👎',
        '😭', '🤯', '🤩', '🥳', '🥺', '😇', '🤫', '😶', '😬', '😵', '😪', '🤑', '🤭', '🤐', '🤢',
        '🙌', '🤘', '🤙', '✌️', '👌', '✍️', '💅', '👀', '🧠', '👑', '💫', '💥', '✨', '⚡️', '🌈'] as $emoji)
        <span class="emoji-item" onclick="insertEmoji('{{ $emoji }}')">{{ $emoji }}</span>
    @endforeach
</div>

{{-- Reaction Picker --}}
<div class="emoji-picker" id="reactionPicker">
    @foreach(['❤️', '😀','😂', '👍', '🎉', '😊', '😍', '🔥', '👏', '✅', '💯', '🙏', '😎', '🤔', '😢', '😡', '👎',
        '😭', '🤯', '🤩', '🥳', '🥺', '😇'] as $emoji)
        <span class="emoji-item" onclick="sendReaction('{{ $emoji }}')">{{ $emoji }}</span>
    @endforeach
</div>

{{-- Camera Modal --}}
<div class="camera-modal" id="cameraModal">
    <button class="camera-mode-toggle" id="cameraModeToggle" onclick="toggleCameraMode()">
        📷 Photo Mode
    </button>
    
    <div class="video-recording-indicator" id="videoRecIndicator">
        <div class="video-rec-dot"></div>
        <span id="videoRecTimer">0:00</span>
    </div>
    
    <video id="cameraPreview" autoplay playsinline muted></video>
    <canvas id="photoCanvas" style="display:none;"></canvas>
    
    <div class="camera-controls">
        <button class="camera-btn btn-close-camera" onclick="closeCamera()">
            <i class="fa fa-times"></i>
        </button>
        <button class="camera-btn btn-capture" onclick="handleCapture()" id="captureBtn">
            <i class="fa fa-camera"></i>
        </button>
        <button class="camera-btn btn-switch-camera" onclick="switchCamera()">
            <i class="fa fa-repeat"></i>
        </button>
    </div>
</div>

{{-- Members Modal --}}
<div class="members-modal" id="membersModal">
    <div class="members-modal-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Group Members ({{ $group->member_count }})</h3>
            <button style="background:none;border:none;font-size:28px;cursor:pointer;color:#999;" onclick="closeMembersModal()">
                &times;
            </button>
        </div>
        <div id="membersList">
            @foreach($group->members as $member)
                <div class="member-item">
                    <img src="{{ $member->user->profileimg ?? asset('images/best3.png') }}" 
                         alt="{{ $member->user->name }}" 
                         class="member-avatar">
                    <div class="member-info">
                        <div class="member-name">{{ $member->user->name }}</div>
                        <div class="member-role">{{ ucfirst($member->role) }}</div>
                    </div>
                    @if($isAdmin && $member->user_id != $group->created_by && $member->user_id != $user->id)
                        <button class="btn-remove-member" onclick="removeMember({{ $member->user_id }})">
                            Remove
                        </button>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Report Group Modal --}}
<div class="report-modal" id="reportModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 15px; padding: 30px; max-width: 500px; width: 90%;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0;">Report Group</h3>
            <button onclick="closeReportModal()" style="background: none; border: none; font-size: 28px; cursor: pointer; color: #999;">&times;</button>
        </div>
        
        <p style="color: #666; margin-bottom: 20px;">Please select a reason for reporting this group:</p>
        
        <select id="reportReason" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 15px; font-size: 15px;">
            <option value="">Select a reason...</option>
            <option value="spam">Spam or misleading content</option>
            <option value="harassment">Harassment or bullying</option>
            <option value="hate_speech">Hate speech</option>
            <option value="violence">Violence or dangerous content</option>
            <option value="inappropriate">Inappropriate content</option>
            <option value="illegal">Illegal activities</option>
            <option value="other">Other</option>
        </select>
        
        <textarea id="reportDetails" placeholder="Additional details (optional)" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 20px; min-height: 100px; font-size: 15px; font-family: inherit;"></textarea>
        
        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <button onclick="closeReportModal()" style="padding: 10px 20px; border: 1px solid #ddd; border-radius: 8px; background: white; cursor: pointer;">Cancel</button>
            <button onclick="submitReport()" style="padding: 10px 20px; border: none; border-radius: 8px; background: #dc3545; color: white; cursor: pointer;">Submit Report</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>



<script>

// ========================================
// GLOBAL VARIABLES (Must be at top)
// ========================================

const groupId = {{ $group->id }};
const userId = {{ $user->id }};
window._inGroupChat = groupId;
const isAdmin = {{ $isAdmin ? 'true' : 'false' }};
const isCreator = {{ $isCreator ? 'true' : 'false' }};
let lastMessageId = {{ $messages->last()->id ?? 0 }};
let selectedMessage = null;
let replyingToId = null;
let selectedFiles = [];
let isEditing = false;
let editingMessageId = null;
let currentReactingMessageId = null;

// Voice Recording Variables
let mediaRecorder;
let audioChunks = [];
let recordingStartTime;
let recordingInterval;
let isRecording = false;
let currentAudio = null;

// Camera Variables
let cameraStream = null;
let currentFacingMode = 'user';
let isRecordingVideo = false;
let videoRecorder = null;
let videoChunks = [];
let cameraMode = 'photo';
let videoRecordingStartTime = null;
let videoRecordingInterval = null;

// ✅ NEW: Link Preview Variables
    let currentLinkPreview = null;
    let linkPreviewTimeout = null;


// Define the global CSRF token variable for JavaScript usage
    const csrfToken = '{{ csrf_token() }}';
// Initialize FancyBox
// Fancybox.bind("[data-fancybox]", {});

// ========================================
// BEEP SOUND FUNCTIONS (Must be global)
// ========================================

function playSendBeep() {
    const sendBeep = document.getElementById('sendBeep');
    if (sendBeep) {
        sendBeep.currentTime = 0;
        sendBeep.play().catch(err => console.log('Send beep error:', err));
    } else {
        playBeep(600, 100, 0.1);
    }
}

function playReceiveBeep() {
    const receiveBeep = document.getElementById('receiveBeep');
    if (receiveBeep) {
        receiveBeep.currentTime = 0;
        receiveBeep.play().catch(err => console.log('Receive beep error:', err));
    } else {
        playBeep(800, 150, 0.15);
    }
}

function playBeep(frequency, duration, volume) {
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.value = frequency;
        gainNode.gain.value = volume;
        
        oscillator.start();
        setTimeout(() => {
            gainNode.gain.exponentialRampToValueAtTime(0.00001, audioContext.currentTime + 0.1);
            oscillator.stop(audioContext.currentTime + 0.1);
        }, duration);
    } catch (error) {
        console.error('Beep error:', error);
    }
}

function playCallBeep() {
    // Play a distinct sound for incoming calls
    playBeep(1000, 300, 0.2);
}

// ✅ NEW: Extract URLs from text
    function extractUrls(text) {
        const urlRegex = /\b(?:https?:\/\/|www\.)[^\s<>"']+/gi;
        const matches = text.match(urlRegex) || [];
        return matches.map(url => {
            if (url.toLowerCase().startsWith('www.')) {
                return 'http://' + url;
            }
            return url;
        });
    }
    
    // ✅ NEW: Fetch and display link preview
    async function fetchLinkPreview(url) {
        try {
            const response = await fetch('/groups/link-preview', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ url: url })
            });
            
            const data = await response.json();
            
            if (data.success && data.preview) {
                displayLinkPreview(data.preview);
                currentLinkPreview = data.preview;
            } else {
                hideLinkPreview();
            }
        } catch (error) {
            console.error('Error fetching link preview:', error);
            hideLinkPreview();
        }
    }
    
    // ✅ NEW: Display link preview
    function displayLinkPreview(preview) {
        const container = document.getElementById('linkPreviewContainer');
        const loading = document.getElementById('linkPreviewLoading');
        const content = document.getElementById('linkPreviewContent');
        
        loading.style.display = 'none';
        content.style.display = 'block';
        
        // Image
        const img = document.getElementById('linkPreviewImage');
        if (preview.image) {
            img.src = preview.image;
            img.style.display = 'block';
            img.onerror = () => img.style.display = 'none';
        } else {
            img.style.display = 'none';
        }
        
        // Site name with favicon
        const site = document.getElementById('linkPreviewSite');
        let siteHtml = '';
        if (preview.favicon) {
            siteHtml += `<img src="${preview.favicon}" class="link-preview-favicon" onerror="this.style.display='none'">`;
        }
        siteHtml += preview.site_name || new URL(preview.url).hostname;
        site.innerHTML = siteHtml;
        
        // Title
        document.getElementById('linkPreviewTitle').textContent = preview.title || 'No title';
        
        // Description
        const desc = document.getElementById('linkPreviewDescription');
        if (preview.description) {
            desc.textContent = preview.description;
            desc.style.display = 'block';
        } else {
            desc.style.display = 'none';
        }
        
        // URL
        document.getElementById('linkPreviewUrl').textContent = preview.url;
        
        container.classList.add('show');
    }
    
    // ✅ NEW: Hide link preview
    function hideLinkPreview() {
        document.getElementById('linkPreviewContainer').classList.remove('show');
        document.getElementById('linkPreviewLoading').style.display = 'flex';
        document.getElementById('linkPreviewContent').style.display = 'none';
        currentLinkPreview = null;
    }
    
    // ✅ NEW: Remove link preview
    function removeLinkPreview() {
        hideLinkPreview();
    }


// REPLACE YOUR FANCYBOX INITIALIZATION WITH THIS:
// ========================================
// FIXED FANCYBOX WITH PROPER VIDEO SUPPORT
// ========================================

document.addEventListener('DOMContentLoaded', function() {
    Fancybox.bind('[data-fancybox]', {
        // Toolbar with close button
        Toolbar: {
            display: {
                left: [],
                middle: [],
                right: ['close']
            }
        },
        
        // Image settings
        Images: {
            zoom: true,
            protected: true
        },
        
        // Handle all content types
        on: {
            // Before content is loaded
            init: (fancybox) => {
                console.log('FancyBox initialized');
            },
            
            // When slide is being created
            'Carousel.createSlide': (fancybox, carousel, slide) => {
                console.log('Creating slide:', slide.type, slide.src);
                
                // Handle video files
                if (slide.src && (slide.src.includes('.mp4') || slide.src.includes('.webm') || slide.src.includes('.ogg') || slide.type === 'html5video')) {
                    slide.type = 'html5video';
                }
            },
            
            // After slide content is set
            'Carousel.ready': (fancybox, carousel, slide) => {
                console.log('Slide ready:', slide);
                
                // For video slides, create proper video element
                if (slide && slide.type === 'html5video' && slide.$el) {
                    const container = slide.$el.querySelector('.fancybox__content');
                    
                    if (container && !container.querySelector('video')) {
                        // Clear container
                        container.innerHTML = '';
                        
                        // Create video element
                        const video = document.createElement('video');
                        video.src = slide.src;
                        video.controls = true;
                        video.autoplay = false;
                        video.playsinline = true;
                        video.style.width = '100%';
                        video.style.height = '100%';
                        video.style.maxHeight = '90vh';
                        video.style.objectFit = 'contain';
                        
                        container.appendChild(video);
                        
                        // Auto play after loading
                        video.addEventListener('loadedmetadata', () => {
                            video.play().catch(e => console.log('Autoplay prevented:', e));
                        });
                    }
                }
            },
            
            // Handle errors
            error: (fancybox, slide) => {
                console.error('FancyBox error:', slide);
            }
        }
    });
});

// ========================================
// MESSAGE INPUT HANDLERS (Must be global)
// ========================================

function scrollToBottom() {
    const container = document.getElementById('messagesContainer');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
}

function handleMessageClick(event, element) {
    // Single click - do nothing special
}

function showMessageActions(event, element) {
    event.preventDefault();
    
    selectedMessage = element;
    const messageId = element.getAttribute('data-message-id');
    const senderId = element.getAttribute('data-sender');
    
    const menu = document.getElementById('messageActions');
    const editAction = document.getElementById('editAction');
    const deleteAction = document.getElementById('deleteAction');
    
    if (senderId == userId) {
        editAction.style.display = 'flex';
    } else {
        editAction.style.display = 'none';
    }
    
    if (senderId == userId || isAdmin) {
        deleteAction.style.display = 'flex';
    } else {
        deleteAction.style.display = 'none';
    }
    
    const isMobile = window.innerWidth <= 768;
    if (isMobile) {
        menu.style.left = '50%';
        menu.style.top = '50%';
        menu.style.transform = 'translate(-50%, -50%)';
    } else {
        menu.style.left = event.clientX + 'px';
        menu.style.top = event.clientY + 'px';
        menu.style.transform = 'none';
    }
    
    menu.classList.add('show');
    element.classList.add('highlighted');
}

function toggleChatMenu(event) {
    event.preventDefault();
    const menu = document.getElementById('chatMenu');
    menu.classList.toggle('show');
}

function hideMessageActions() {
    const menu = document.getElementById('messageActions');
    if (menu) {
        menu.classList.remove('show');
    }
    if (selectedMessage) {
        selectedMessage.classList.remove('highlighted');
    }
}

function replyToMessage() {
    if (!selectedMessage) return;
    
    replyingToId = selectedMessage.getAttribute('data-message-id');
    const messageText = selectedMessage.getAttribute('data-message') || '';
    
    document.getElementById('replyText').textContent = messageText.substring(0, 50) + (messageText.length > 50 ? '...' : '');
    document.getElementById('replyPreview').classList.add('show');
    document.getElementById('messageInput').focus();
    
    hideMessageActions();
}

function cancelReply() {
    replyingToId = null;
    const replyPreview = document.getElementById('replyPreview');
    if (replyPreview) {
        replyPreview.classList.remove('show');
    }
}

function copyMessage() {
    if (!selectedMessage) return;
    const messageText = selectedMessage.getAttribute('data-message');
    navigator.clipboard.writeText(messageText);
    alert('Message copied!');
    hideMessageActions();
}

function editMessage() {
    if (!selectedMessage) return;
    isEditing = true;
    editingMessageId = selectedMessage.getAttribute('data-message-id');
    const messageText = selectedMessage.getAttribute('data-message');

    document.getElementById('messageInput').value = messageText;
    document.getElementById('messageInput').focus();
    document.getElementById('sendBtn').innerHTML = '<i class="fa fa-check"></i>';
    document.getElementById('sendBtn').style.display = 'flex';
    document.getElementById('voiceBtn').style.display = 'none';

    hideMessageActions();
}

function showReactionPicker() {
    if (!selectedMessage) return;
    currentReactingMessageId = selectedMessage.getAttribute('data-message-id');
    const reactionPicker = document.getElementById('reactionPicker');
    reactionPicker.classList.add('show');
    hideMessageActions();
}

function sendReaction(emoji) {
    if (!currentReactingMessageId) return;
    
    fetch('/groups/messages/' + currentReactingMessageId + '/react', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ emoji: emoji })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
    
    document.getElementById('reactionPicker').classList.remove('show');
}


// ========================================
// PIN MESSAGE FEATURE
// ========================================

function showMessageActions(event, element) {
    event.preventDefault();
    
    selectedMessage = element;
    const messageId = element.getAttribute('data-message-id');
    const senderId = element.getAttribute('data-sender');
    
    const menu = document.getElementById('messageActions');
    const editAction = document.getElementById('editAction');
    const deleteAction = document.getElementById('deleteAction');
    const pinAction = document.getElementById('pinAction'); // ✅ ADD THIS
    
    if (senderId == userId) {
        editAction.style.display = 'flex';
    } else {
        editAction.style.display = 'none';
    }
    
    if (senderId == userId || isAdmin) {
        deleteAction.style.display = 'flex';
    } else {
        deleteAction.style.display = 'none';
    }
    
    // ✅ Show pin option for admins
    if (isAdmin) {
        pinAction.style.display = 'flex';
    } else {
        pinAction.style.display = 'none';
    }
    
    const isMobile = window.innerWidth <= 768;
    if (isMobile) {
        menu.style.left = '50%';
        menu.style.top = '50%';
        menu.style.transform = 'translate(-50%, -50%)';
    } else {
        menu.style.left = event.clientX + 'px';
        menu.style.top = event.clientY + 'px';
        menu.style.transform = 'none';
    }
    
    menu.classList.add('show');
    element.classList.add('highlighted');
}

async function pinMessage() {
    if (!selectedMessage) return;
    
    const messageId = selectedMessage.getAttribute('data-message-id');
    
    if (confirm('Pin this message to the top?')) {
        try {
            const response = await fetch('/groups/' + groupId + '/messages/' + messageId + '/pin', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                alert('Message pinned successfully!');
                location.reload();
            } else {
                alert(data.message || 'Failed to pin message');
            }
        } catch (error) {
            console.error('Error pinning message:', error);
            alert('Failed to pin message');
        }
    }
    
    hideMessageActions();
}

async function unpinMessage() {
    if (confirm('Unpin this message?')) {
        try {
            const response = await fetch('/groups/' + groupId + '/messages/unpin', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                location.reload();
            }
        } catch (error) {
            console.error('Error unpinning message:', error);
            alert('Failed to unpin message');
        }
    }
}

// Click pinned banner to scroll to message
document.getElementById('pinnedBanner')?.addEventListener('click', function(e) {
    if (!e.target.closest('.unpin-btn')) {
        const pinnedMsgId = '{{ $pinnedMessage->id ?? '' }}';
        if (pinnedMsgId) {
            highlightMessage(pinnedMsgId);
        }
    }
});



function deleteMessage() {
    if (!selectedMessage) return;
    if (confirm('Delete this message?')) {
        const messageId = selectedMessage.getAttribute('data-message-id');
        
        fetch('/groups/messages/' + messageId + '/delete', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const voiceNote = selectedMessage.querySelector('.voice-note-bubble');
                if (voiceNote) {
                    voiceNote.setAttribute('data-is-deleted', 'true');
                    voiceNote.onclick = null;
                    voiceNote.style.opacity = '0.5';
                    voiceNote.innerHTML = '<i class="fa fa-ban"></i> <span style="margin-left:8px;">This voice message was deleted</span>';
                } else {
                    const messageContent = selectedMessage.querySelector('.message-content');
                    if (messageContent) {
                        messageContent.textContent = '🚫 This message was deleted';
                    }
                }
                selectedMessage.classList.add('text-muted');
            }
        });
    }
    hideMessageActions();
}

function highlightMessage(messageId) {
    const messageElement = document.querySelector('[data-message-id="' + messageId + '"]');
    
    if (messageElement) {
        document.querySelectorAll('.highlighted').forEach(el => el.classList.remove('highlighted'));
        messageElement.classList.add('highlighted');
        messageElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        setTimeout(() => {
            messageElement.classList.remove('highlighted');
        }, 3000);
    }
}

// ========================================
// REPORT GROUP FEATURE
// ========================================

function showReportModal() {
    document.getElementById('reportModal').style.display = 'flex';
    document.getElementById('chatMenu').classList.remove('show');
}

function closeReportModal() {
    document.getElementById('reportModal').style.display = 'none';
    document.getElementById('reportReason').value = '';
    document.getElementById('reportDetails').value = '';
}

async function submitReport() {
    const reason = document.getElementById('reportReason').value;
    const details = document.getElementById('reportDetails').value;
    
    if (!reason) {
        alert('Please select a reason for reporting');
        return;
    }
    
    try {
        const response = await fetch('/groups/' + groupId + '/report', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                reason: reason,
                details: details
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Thank you for your report. We will review it shortly.');
            closeReportModal();
        } else {
            alert(data.message || 'Failed to submit report');
        }
    } catch (error) {
        console.error('Error submitting report:', error);
        alert('Failed to submit report');
    }
}



// ========================================
// FILE HANDLING (Must be global)
// ========================================

function handleFileSelect(event) {
    selectedFiles = Array.from(event.target.files);
    const previewContainer = document.getElementById('filePreviewContainer');
    previewContainer.innerHTML = '';
    
    document.getElementById('filePreview').classList.add('show');
    
    selectedFiles.forEach((file, index) => {
        const filePreview = document.createElement('div');
        filePreview.className = 'file-preview-item';
        
        if (file.type.startsWith('image/')) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            filePreview.appendChild(img);
        } else if (file.type.startsWith('video/')) {
            const video = document.createElement('video');
            video.src = URL.createObjectURL(file);
            video.controls = true;
            video.style.maxWidth = '80px';
            video.style.maxHeight = '80px';
            filePreview.appendChild(video);
        } else if (file.type === 'application/pdf') {
            const pdfDiv = document.createElement('div');
            pdfDiv.className = 'p-2 bg-light rounded text-center';
            pdfDiv.innerHTML = '<i class="fa fa-file-pdf fa-2x text-danger"></i><br><small>' + file.name + '</small>';
            filePreview.appendChild(pdfDiv);
        } else {
            const fileDiv = document.createElement('div');
            fileDiv.className = 'p-2 bg-light rounded text-center';
            fileDiv.innerHTML = '<i class="fa fa-file fa-2x"></i><br><small>' + file.name + '</small>';
            filePreview.appendChild(fileDiv);
        }
        
        const removeBtn = document.createElement('button');
        removeBtn.className = 'file-remove-btn';
        removeBtn.innerHTML = '×';
        removeBtn.onclick = (e) => {
            e.preventDefault();
            selectedFiles.splice(index, 1);
            filePreview.remove();
            if (selectedFiles.length === 0) {
                document.getElementById('fileInput').value = '';
                document.getElementById('filePreview').classList.remove('show');
            }
        };
        
        filePreview.appendChild(removeBtn);
        previewContainer.appendChild(filePreview);
    });
}

function cancelFileUpload() {
    selectedFiles = [];
    document.getElementById('fileInput').value = '';
    document.getElementById('filePreviewContainer').innerHTML = '';
    document.getElementById('filePreview').classList.remove('show');
}

// ========================================
// EMOJI PICKER (Must be global)
// ========================================

function toggleEmojiPicker() {
    const picker = document.getElementById('emojiPicker');
    picker.classList.toggle('show');
}

function insertEmoji(emoji) {
    const input = document.getElementById('messageInput');
    input.value += emoji;
    input.focus();
    
    input.dispatchEvent(new Event('input'));
    
    document.getElementById('emojiPicker').classList.remove('show');
}

// ========================================
// VOICE RECORDING (Must be global)
// ========================================

function toggleVoiceRecording() {
    if (!isRecording) {
        startVoiceRecording();
    } else {
        stopVoiceRecording();
    }
}

async function startVoiceRecording() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        
        mediaRecorder = new MediaRecorder(stream);
        audioChunks = [];
        
        mediaRecorder.ondataavailable = (event) => {
            audioChunks.push(event.data);
        };
        
        mediaRecorder.onstop = () => {
            stream.getTracks().forEach(track => track.stop());
        };
        
        mediaRecorder.start();
        isRecording = true;
        
        document.getElementById('voiceRecordingContainer').classList.add('show');
        document.getElementById('voiceBtn').classList.add('recording');
        document.getElementById('voiceIcon').className = 'fa fa-stop';
        document.querySelectorAll('.hide-on-recording').forEach(el => el.classList.add('hidden'));
        
        recordingStartTime = Date.now();
        recordingInterval = setInterval(updateRecordingTimer, 100);
        
    } catch (error) {
        console.error('Error accessing microphone:', error);
        alert('Unable to access microphone. Please grant permission.');
    }
}

function updateRecordingTimer() {
    const elapsed = Math.floor((Date.now() - recordingStartTime) / 1000);
    const minutes = Math.floor(elapsed / 60);
    const seconds = elapsed % 60;
    document.getElementById('recordingTimer').textContent = 
        minutes + ':' + seconds.toString().padStart(2, '0');
}

function stopVoiceRecording() {
    if (mediaRecorder && isRecording) {
        mediaRecorder.stop();
        isRecording = false;
        clearInterval(recordingInterval);
    }
}

function cancelVoiceRecording() {
    stopVoiceRecording();
    audioChunks = [];
    resetVoiceUI();
}

async function sendVoiceNote() {
    if (audioChunks.length === 0) return;
    
    stopVoiceRecording();
    
    const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
    const duration = Math.round((Date.now() - recordingStartTime) / 1000);
    
    document.getElementById('uploadProgress').style.display = 'block';
    document.getElementById('uploadStatus').textContent = 'Uploading voice note...';

    try {
        const formData = new FormData();
        formData.append('file', audioBlob, 'voice-note.webm');
        formData.append('upload_preset', 'francis');
        formData.append('resource_type', 'video');
        
        const response = await fetch('https://api.cloudinary.com/v1_1/djaqqrwoi/video/upload', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        const voiceUrl = data.secure_url;
        
        const messageFormData = new FormData();
        messageFormData.append('voice_note', voiceUrl);
        messageFormData.append('voice_duration', duration);
        messageFormData.append('message', '🎤 Voice message');

        const sendResponse = await fetch('/groups/' + groupId + '/messages', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: messageFormData
        });
        
        const sendData = await sendResponse.json();
        
        if (sendData.success) {
            addMessageToUI(sendData.message);
            playSendBeep(); // ADD THIS LINE
            resetVoiceUI();
            scrollToBottom();
            
        }
        
    } catch (error) {
        console.error('Error uploading voice note:', error);
        alert('Failed to upload voice note');
    } finally {
        document.getElementById('uploadProgress').style.display = 'none';
    }
}

function resetVoiceUI() {
    document.getElementById('voiceRecordingContainer').classList.remove('show');
    document.getElementById('voiceBtn').classList.remove('recording');
    document.getElementById('voiceIcon').className = 'fa fa-microphone';
    document.querySelectorAll('.hide-on-recording').forEach(el => el.classList.remove('hidden'));
}

function playVoiceNote(element, audioUrl) {
    const isDeleted = element.getAttribute('data-is-deleted') === 'true';
    if (isDeleted) {
        alert('This voice message has been deleted');
        return;
    }

    const playBtn = element.querySelector('.voice-play-btn i');
    const waveBars = element.querySelectorAll('.voice-wave-bar');

    if (currentAudio && !currentAudio.paused && currentAudio.src === audioUrl) {
        currentAudio.pause();
        currentAudio.currentTime = 0;
        playBtn.className = 'fa fa-play';
        waveBars.forEach(bar => bar.classList.remove('active'));
        currentAudio = null;
        return;
    }

    if (currentAudio) {
        currentAudio.pause();
        document.querySelectorAll('.voice-play-btn i').forEach(btn => btn.className = 'fa fa-play');
        document.querySelectorAll('.voice-wave-bar').forEach(bar => bar.classList.remove('active'));
    }

    currentAudio = new Audio(audioUrl);
    playBtn.className = 'fa fa-pause';

    let currentBar = 0;
    const waveInterval = setInterval(() => {
        waveBars.forEach((bar, index) => {
            bar.classList.toggle('active', index === currentBar % waveBars.length);
        });
        currentBar++;
    }, 100);

    currentAudio.play();

    currentAudio.onended = () => {
        playBtn.className = 'fa fa-play';
        clearInterval(waveInterval);
        waveBars.forEach(bar => bar.classList.remove('active'));
        currentAudio = null;
    };
}

// ========================================
// CAMERA FUNCTIONS
// ========================================

async function openCamera() {
    try {
        if (cameraStream) {
            cameraStream.getTracks().forEach(track => track.stop());
            cameraStream = null;
        }

        const constraints = {
            video: { 
                facingMode: currentFacingMode,
                width: { ideal: 1280 },
                height: { ideal: 720 }
            }
        };
        
        if (cameraMode === 'video') {
            constraints.audio = true;
        }
        
        cameraStream = await navigator.mediaDevices.getUserMedia(constraints);
        
        const video = document.getElementById('cameraPreview');
        video.srcObject = cameraStream;
        
        await new Promise((resolve) => {
            video.onloadedmetadata = () => {
                video.play();
                resolve();
            };
        });
        
        document.getElementById('cameraModal').classList.add('show');
        updateCameraModeUI();
        
    } catch (error) {
        console.error('Error accessing camera:', error);
        alert('Unable to access camera: ' + error.message);
    }
}

function closeCamera() {
    if (isRecordingVideo) {
        stopVideoRecording(false);
    }

    if (cameraStream) {
        cameraStream.getTracks().forEach(track => track.stop());
        cameraStream = null;
    }

    document.getElementById('cameraModal').classList.remove('show');
    document.getElementById('videoRecIndicator').classList.remove('show');

    cameraMode = 'photo';
    isRecordingVideo = false;
}

async function switchCamera() {
    currentFacingMode = currentFacingMode === 'user' ? 'environment' : 'user';
    closeCamera();
    await openCamera();
}

function toggleCameraMode() {
    if (isRecordingVideo) {
        alert('Stop recording first!');
        return;
    }

     // Toggle between photo and video
    cameraMode = cameraMode === 'photo' ? 'video' : 'photo';
    
    console.log('Camera mode switched to:', cameraMode); // Debug log

    // Close current camera stream
    if (cameraStream) {
        cameraStream.getTracks().forEach(track => track.stop());
        cameraStream = null;
    }


    // Close camera modal
    document.getElementById('cameraModal').classList.remove('show');
    
    // Reopen with new mode after a short delay
    setTimeout(() => {
        openCamera();
    }, 300);
}
    // cameraMode = cameraMode === 'photo' ? 'video' : 'photo';
    // updateCameraModeUI();

    // closeCamera();
    // setTimeout(() => openCamera(), 300);


// Replace your updateCameraModeUI function:
function updateCameraModeUI() {
    const modeToggle = document.getElementById('cameraModeToggle');
    const captureBtn = document.getElementById('captureBtn');
    const captureBtnIcon = captureBtn.querySelector('i');

    if (cameraMode === 'photo') {
        modeToggle.innerHTML = '📷 Photo Mode';
        modeToggle.style.background = 'rgba(52, 152, 219, 0.8)';
        captureBtnIcon.className = 'fa fa-camera';
        captureBtn.classList.remove('video-mode');
    } else {
        modeToggle.innerHTML = '🎥 Video Mode';
        modeToggle.style.background = 'rgba(231, 76, 60, 0.8)';
        captureBtnIcon.className = 'fa fa-circle';
        captureBtn.classList.remove('video-mode');
    }
}

// ========================================
// FIX 2: ADD UPLOAD COMPLETE SOUND
// ========================================

function playUploadCompleteSound() {
    // Play a success sound when upload completes
    playBeep(800, 200, 0.15);
    setTimeout(() => playBeep(1000, 150, 0.15), 150);
}

function handleCapture() {
    if (cameraMode === 'photo') {
        capturePhoto();
    } else {
        if (isRecordingVideo) {
            stopVideoRecording(true);
        } else {
            startVideoRecording();
        }
    }
}

async function capturePhoto() {
    const video = document.getElementById('cameraPreview');
    const canvas = document.getElementById('photoCanvas');
    const context = canvas.getContext('2d');

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0);

    canvas.toBlob(async (blob) => {
        closeCamera();
        
        document.getElementById('uploadProgress').style.display = 'block';
        document.getElementById('uploadStatus').textContent = 'Uploading photo...';
        
        try {
            const formData = new FormData();
            formData.append('file', blob, 'camera-photo.jpg');
            formData.append('upload_preset', 'francis');
            
            const response = await fetch('https://api.cloudinary.com/v1_1/djaqqrwoi/image/upload', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            const photoUrl = data.secure_url;
            
            const messageFormData = new FormData();
            messageFormData.append('files[]', photoUrl);
            messageFormData.append('message', '📷 Photo');
            
            const sendResponse = await fetch('/groups/' + groupId + '/messages', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: messageFormData
            });
            
            const sendData = await sendResponse.json();
            
            if (sendData.success) {
                addMessageToUI(sendData.message);
                scrollToBottom();
                playSendBeep(); // ADD THIS LINE
                playUploadCompleteSound(); // ✅ ADD THIS
                
            }
            
        } catch (error) {
            console.error('Error uploading photo:', error);
            alert('Failed to upload photo');
        } finally {
            document.getElementById('uploadProgress').style.display = 'none';
        }
    }, 'image/jpeg', 0.9);
}

function startVideoRecording() {
    try {
        if (!MediaRecorder.isTypeSupported('video/webm')) {
            alert('Video recording not supported on this browser');
            return;
        }

        videoChunks = [];
        videoRecorder = new MediaRecorder(cameraStream, {
            mimeType: 'video/webm;codecs=vp8,opus',
            videoBitsPerSecond: 2500000
        });
        
        videoRecorder.ondataavailable = (event) => {
            if (event.data && event.data.size > 0) {
                videoChunks.push(event.data);
            }
        };
        
        videoRecorder.start(1000);
        isRecordingVideo = true;
        
        document.getElementById('videoRecIndicator').classList.add('show');
        document.getElementById('captureBtn').classList.add('video-mode');
        document.getElementById('captureBtn').querySelector('i').className = 'fa fa-stop';
        
        videoRecordingStartTime = Date.now();
        videoRecordingInterval = setInterval(updateVideoRecordingTimer, 100);
        
    } catch (error) {
        console.error('Error starting video recording:', error);
        alert('Failed to start video recording');
    }
}

function updateVideoRecordingTimer() {
    const elapsed = Math.floor((Date.now() - videoRecordingStartTime) / 1000);
    const minutes = Math.floor(elapsed / 60);
    const seconds = elapsed % 60;
    document.getElementById('videoRecTimer').textContent =
        minutes + ':' + seconds.toString().padStart(2, '0');
}

function stopVideoRecording(shouldSend) {
    if (!videoRecorder || !isRecordingVideo) return;

    return new Promise((resolve) => {
        videoRecorder.onstop = async () => {
            clearInterval(videoRecordingInterval);
            document.getElementById('videoRecIndicator').classList.remove('show');
            document.getElementById('captureBtn').classList.remove('video-mode');
            document.getElementById('captureBtn').querySelector('i').className = 'fa fa-circle';
            
            if (shouldSend && videoChunks.length > 0) {
                const videoBlob = new Blob(videoChunks, { type: 'video/webm' });
                await uploadVideo(videoBlob);
            }
            
            videoChunks = [];
            isRecordingVideo = false;
            videoRecorder = null;
            resolve();
        };
        
        videoRecorder.stop();
    });
}

async function uploadVideo(videoBlob) {
    closeCamera();

    const duration = Math.floor((Date.now() - videoRecordingStartTime) / 1000);

    document.getElementById('uploadProgress').style.display = 'block';
    document.getElementById('uploadStatus').textContent = 'Uploading video...';
    document.getElementById('progressBar').style.width = '0%';

    try {
        const formData = new FormData();
        formData.append('file', videoBlob, 'camera-video.webm');
        formData.append('upload_preset', 'francis');
        formData.append('resource_type', 'video');
        
        const xhr = new XMLHttpRequest();
        
        xhr.upload.addEventListener('progress', (e) => {
            if (e.lengthComputable) {
                const percentComplete = (e.loaded / e.total) * 100;
                document.getElementById('progressBar').style.width = percentComplete + '%';
                document.getElementById('uploadStatus').textContent = 
                    'Uploading video... ' + Math.round(percentComplete) + '%';
            }
        });
        
        xhr.onload = async () => {
            if (xhr.status === 200) {
                const data = JSON.parse(xhr.responseText);
                const videoUrl = data.secure_url;
                
                document.getElementById('uploadStatus').textContent = 'Sending video...';
                
                const messageFormData = new FormData();
                messageFormData.append('files[]', videoUrl);
                messageFormData.append('message', '🎥 Video (' + Math.floor(duration / 60) + ':' + (duration % 60).toString().padStart(2, '0') + ')');
                
                const sendResponse = await fetch('/groups/' + groupId + '/messages', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: messageFormData
                });
                
                const sendData = await sendResponse.json();
                
                if (sendData.success) {
                    addMessageToUI(sendData.message);
                    scrollToBottom();
                    playSendBeep(); // ADD THIS LINE
                    playUploadCompleteSound(); // ✅ ADD THIS
                    
                }
                
                document.getElementById('uploadProgress').style.display = 'none';
            } else {
                throw new Error('Upload failed');
            }
        };
        
        xhr.onerror = () => {
            throw new Error('Network error');
        };
        
        xhr.open('POST', 'https://api.cloudinary.com/v1_1/djaqqrwoi/video/upload');
        xhr.send(formData);
        
    } catch (error) {
        console.error('Error uploading video:', error);
        alert('Failed to upload video');
        document.getElementById('uploadProgress').style.display = 'none';
    }
}

// ========================================
// FIX 3: FORWARD MESSAGE FEATURE
// ========================================

let forwardingMessageId = null;

function showForwardModal() {
    if (!selectedMessage) return;
    
    forwardingMessageId = selectedMessage.getAttribute('data-message-id');
    
    // Fetch friends list
    fetch('/groups/friends/list')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayForwardModal(data.friends);
            }
        })
        .catch(error => {
            console.error('Error fetching friends:', error);
            alert('Failed to load friends list');
        });
    
    hideMessageActions();
}

function displayForwardModal(friends) {
    let friendsHTML = '';
    
    friends.forEach(friend => {
        friendsHTML += `
            <div class="forward-friend-item" style="padding: 12px; border-bottom: 1px solid #f0f0f0; cursor: pointer; display: flex; align-items: center; gap: 10px;" onclick="selectForwardFriend(${friend.id}, this)">
                <input type="checkbox" class="forward-checkbox" data-friend-id="${friend.id}" style="width: 18px; height: 18px;">
                <img src="${friend.profileimg || '{{ asset("images/best3.png") }}'}" alt="${friend.name}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                <div>
                    <div style="font-weight: 600;">${friend.name}</div>
                    <div style="font-size: 12px; color: #666;">@${friend.username}</div>
                </div>
            </div>
        `;
    });
    
    const modalHTML = `
        <div class="forward-modal" id="forwardModal" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 2000;">
            <div class="forward-modal-content" style="background: white; border-radius: 15px; padding: 20px; max-width: 500px; width: 90%; max-height: 70vh; overflow-y: auto;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="margin: 0;">Forward to...</h3>
                    <button onclick="closeForwardModal()" style="background: none; border: none; font-size: 28px; cursor: pointer; color: #999;">&times;</button>
                </div>
                <div id="forwardFriendsList">
                    ${friendsHTML || '<p style="text-align: center; color: #999; padding: 20px;">No friends available</p>'}
                </div>
                <div style="margin-top: 20px; display: flex; gap: 10px; justify-content: flex-end;">
                    <button onclick="closeForwardModal()" style="padding: 10px 20px; border: 1px solid #ddd; border-radius: 8px; background: white; cursor: pointer;">Cancel</button>
                    <button onclick="confirmForward()" style="padding: 10px 20px; border: none; border-radius: 8px; background: #0EA5E9; color: white; cursor: pointer;">Forward</button>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('forwardModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add new modal
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

function selectForwardFriend(friendId, element) {
    const checkbox = element.querySelector('.forward-checkbox');
    checkbox.checked = !checkbox.checked;
}

function closeForwardModal() {
    const modal = document.getElementById('forwardModal');
    if (modal) {
        modal.remove();
    }
    forwardingMessageId = null;
}

async function confirmForward() {
    const checkboxes = document.querySelectorAll('.forward-checkbox:checked');
    const selectedFriendIds = Array.from(checkboxes).map(cb => cb.getAttribute('data-friend-id'));
    
    if (selectedFriendIds.length === 0) {
        alert('Please select at least one friend');
        return;
    }
    
    try {
        const response = await fetch('/groups/messages/' + forwardingMessageId + '/forward', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                friend_ids: selectedFriendIds
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Message forwarded successfully to ' + selectedFriendIds.length + ' friend(s)');
            closeForwardModal();
        } else {
            alert('Failed to forward message');
        }
    } catch (error) {
        console.error('Error forwarding message:', error);
        alert('Failed to forward message');
    }
}






// ========================================
// MESSAGE INPUT HANDLERS
// ========================================

// ✅ UPDATED: Message input handler with link preview detection
    document.getElementById('messageInput').addEventListener('input', function() {
        const sendBtn = document.getElementById('sendBtn');
        const voiceBtn = document.getElementById('voiceBtn');
        
        if (this.value.trim().length > 0) {
            sendBtn.style.display = 'flex';
            voiceBtn.style.display = 'none';
            
            // ✅ Detect links and show preview
            clearTimeout(linkPreviewTimeout);
            linkPreviewTimeout = setTimeout(() => {
                const urls = extractUrls(this.value);
                if (urls.length > 0) {
                    // Show loading state
                    const container = document.getElementById('linkPreviewContainer');
                    const loading = document.getElementById('linkPreviewLoading');
                    const content = document.getElementById('linkPreviewContent');
                    
                    container.classList.add('show');
                    loading.style.display = 'flex';
                    content.style.display = 'none';
                    
                    // Fetch preview for first URL
                    fetchLinkPreview(urls[0]);
                } else {
                    hideLinkPreview();
                }
            }, 1000); // Wait 1 second after typing stops
        } else {
            sendBtn.style.display = 'none';
            voiceBtn.style.display = 'flex';
            hideLinkPreview();
        }
        
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

scrollToBottom();

// ========================================
// MESSAGE ACTIONS
// ========================================

document.addEventListener('click', function(event) {
    const menu = document.getElementById('messageActions');
    const chatMenu = document.getElementById('chatMenu');
    if (!menu.contains(event.target) && !event.target.closest('.message-bubble')) {
        menu.classList.remove('show');
        if (selectedMessage) {
            selectedMessage.classList.remove('highlighted');
        }
    }

    if (!chatMenu.contains(event.target) && !event.target.closest('.fa-ellipsis-v')) {
        chatMenu.classList.remove('show');
    }
});

// ========================================
// SEND MESSAGE
// ========================================

document.getElementById('messageForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    // Clear typing indicator IMMEDIATELY
    isCurrentlyTyping = false;
    clearTimeout(typingTimeout);
    sendTypingIndicator(false);

    const input = document.getElementById('messageInput');
    const message = input.value.trim();

    if (!message && selectedFiles.length === 0) return;

    if (isEditing) {
        const response = await fetch('/groups/messages/' + editingMessageId + '/edit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: message })
        });
        
        const data = await response.json();

        if (data.success) {
            const messageElement = document.querySelector('[data-message-id="' + editingMessageId + '"]');
            if (messageElement) {
                messageElement.querySelector('.message-content').innerHTML = message + ' <small style="opacity:0.6;"> (edited)</small>';
                messageElement.setAttribute('data-message', message);
            }
            
            input.value = '';
            input.style.height = 'auto';
            isEditing = false;
            editingMessageId = null;
            playSendBeep();
            playUploadCompleteSound(); // ✅ ADD THIS
            document.getElementById('sendBtn').innerHTML = '<i class="fa fa-paper-plane"></i>';
            document.getElementById('sendBtn').style.display = 'none';
            document.getElementById('voiceBtn').style.display = 'flex';
            hideLinkPreview();
        }
        return;
    }

    if (selectedFiles.length > 0) {
        document.getElementById('uploadProgress').style.display = 'block';
        document.getElementById('progressBar').style.width = '0%';
        document.getElementById('uploadStatus').textContent = 'Uploading files...';
        document.getElementById('sendBtn').disabled = true;
        
        const uploadPromises = [];
        let totalProgress = 0;
        const uploadedUrls = [];
        
        selectedFiles.forEach((file, fileIndex) => {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('upload_preset', 'francis');
            
            const endpoint = file.type.startsWith('video/')
                ? 'https://api.cloudinary.com/v1_1/djaqqrwoi/video/upload'
                : 'https://api.cloudinary.com/v1_1/djaqqrwoi/image/upload';
            
            const promise = new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();

                xhr.upload.addEventListener('progress', function(evt) {
                    if (evt.lengthComputable) {
                        const fileProgress = (evt.loaded / evt.total) * (100 / selectedFiles.length);
                        const currentFileProgress = (fileIndex / selectedFiles.length) * 100;
                        totalProgress = currentFileProgress + fileProgress;
                        
                        document.getElementById('progressBar').style.width = totalProgress + '%';
                        document.getElementById('uploadStatus').textContent = 'Uploading file ' + (fileIndex + 1) + ' of ' + selectedFiles.length + '...';
                    }
                }, false);

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                uploadedUrls.push(response.secure_url);
                                resolve(response);
                            } catch (e) {
                                reject(new Error("Failed to parse Cloudinary response."));
                            }
                        } else {
                            reject(new Error('Upload failed for ' + file.name));
                        }
                    }
                };

                xhr.open('POST', endpoint, true);
                xhr.send(formData);
            });
            
            uploadPromises.push(promise);
        });
        
        try {
            await Promise.all(uploadPromises);
            
            document.getElementById('uploadStatus').textContent = 'Sending message...';
            
            const formData = new FormData();
            formData.append('message', message || '');
            formData.append('reply_to_id', replyingToId || '');
            
            uploadedUrls.forEach((url) => {
                formData.append('files[]', url);
            });

            const response = await fetch('/groups/' + groupId + '/messages', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                addMessageToUI(data.message);
                playSendBeep(); // ADD THIS LINE
                
                input.value = '';
                input.style.height = 'auto';
                cancelReply();
                cancelFileUpload();
                 hideLinkPreview(); // ✅ ADD THIS LINE
                scrollToBottom();
                lastMessageId = data.message.id;
                
                document.getElementById('sendBtn').disabled = false;
                document.getElementById('sendBtn').style.display = 'none';
                document.getElementById('voiceBtn').style.display = 'flex';
                document.getElementById('uploadProgress').style.display = 'none';
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Upload failed. Please try again.');
            document.getElementById('sendBtn').disabled = false;
            document.getElementById('uploadProgress').style.display = 'none';
        }
    } else {
        const formData = new FormData();
        formData.append('message', message);
        formData.append('reply_to_id', replyingToId || '');
        
        const response = await fetch('/groups/' + groupId + '/messages', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            addMessageToUI(data.message);
            playSendBeep(); // ADD THIS LINE
            
            input.value = '';
            input.style.height = 'auto';
            cancelReply();
             hideLinkPreview(); // ✅ ADD THIS LINE
            scrollToBottom();
            lastMessageId = data.message.id;
            
            document.getElementById('sendBtn').style.display = 'none';
            document.getElementById('voiceBtn').style.display = 'flex';
        }
    }
});

// ✅ UPDATED: addMessageToUI to include link preview
    function addMessageToUI(message) {
        const container = document.getElementById('messagesContainer');
        const messageDiv = document.createElement('div');
        messageDiv.className = 'd-flex justify-content-end';
        
        let readReceipt = '';
        if (message.status === 'seen') {
            readReceipt = '<span style="color: #4fc3f7;"> ✓✓</span>';
        } else {
            readReceipt = '<span style="color: #999;"> ✓</span>';
        }
        
        let messageContent = '';

    // Voice note
    if (message.voice_note) {
        const duration = message.voice_duration || 0;
        const minutes = Math.floor(duration / 60);
        const seconds = duration % 60;
        const durationText = minutes + ':' + seconds.toString().padStart(2, '0');
        
        messageContent = '<div class="voice-note-bubble sent" onclick="playVoiceNote(this, \'' + message.voice_note + '\')" data-is-deleted="false"><button class="voice-play-btn"><i class="fa fa-play"></i></button><div class="voice-waveform"><div class="voice-wave-bar" style="height: 15px;"></div><div class="voice-wave-bar" style="height: 22px;"></div><div class="voice-wave-bar" style="height: 18px;"></div><div class="voice-wave-bar" style="height: 25px;"></div><div class="voice-wave-bar" style="height: 12px;"></div><div class="voice-wave-bar" style="height: 20px;"></div><div class="voice-wave-bar" style="height: 16px;"></div><div class="voice-wave-bar" style="height: 23px;"></div></div><span class="voice-duration">' + durationText + '</span></div>';
    } else {
        let replyHtml = '';
        if (message.reply_to_id) {
            replyHtml = '<div class="reply-reference"><i class="fa fa-reply"></i> Replying to message</div>';
        }

        // ✅ NEW: Link preview in message
            let linkPreviewHtml = '';
            if (message.link_preview) {
                const preview = typeof message.link_preview === 'string' 
                    ? JSON.parse(message.link_preview) 
                    : message.link_preview;
                
                linkPreviewHtml = '<div class="message-link-preview" onclick="window.open(\'' + preview.url + '\', \'_blank\')">';
                
                if (preview.image) {
                    linkPreviewHtml += '<img src="' + preview.image + '" class="message-link-preview-image">';
                }
                
                if (preview.title) {
                    linkPreviewHtml += '<div class="message-link-preview-title">' + preview.title + '</div>';
                }
                
                if (preview.description) {
                    linkPreviewHtml += '<div class="message-link-preview-description">' + preview.description + '</div>';
                }
                
                linkPreviewHtml += '<div class="message-link-preview-site">';
                if (preview.favicon) {
                    linkPreviewHtml += '<img src="' + preview.favicon + '" class="link-preview-favicon" onerror="this.style.display=\'none\'">';
                }
                linkPreviewHtml += (preview.site_name || new URL(preview.url).hostname) + '</div></div>';
            }
        
        let filesHtml = '';
        if (message.file_path) {
            try {
                const files = JSON.parse(message.file_path);
                filesHtml = '<div class="mt-2">';
                files.forEach(file => {
                    if (file.includes('.jpg') || file.includes('.png') || file.includes('.jpeg') || file.includes('.gif') || file.includes('.webp')) {
                        filesHtml += '<img src="' + file + '" class="img-fluid rounded" style="max-width:200px;cursor:pointer;" onclick="window.open(\'' + file + '\', \'_blank\')">';
                    } else if (file.includes('.mp4') || file.includes('.webm') || file.includes('.ogg')) {
                        filesHtml += '<video controls class="rounded" style="max-width:200px;"><source src="' + file + '"></video>';
                    } else if (file.includes('.pdf')) {
                        filesHtml += '<a href="' + file + '" target="_blank" class="btn btn-sm btn-light mt-2"><i class="fa fa-file-pdf text-danger"></i> View PDF</a>';
                    } else {
                        filesHtml += '<a href="' + file + '" target="_blank" class="btn btn-sm btn-light mt-2"><i class="fa fa-file"></i> Download File</a>';
                    }
                });
                filesHtml += '</div>';
            } catch (e) {
                console.error('Error parsing files:', e);
            }
        }
        
        messageContent = '<div class="message-bubble sent" data-message-id="' + message.id + '" data-message="' + (message.message || '') + '" data-sender="' + userId + '" oncontextmenu="showMessageActions(event, this); return false;">' + replyHtml + (message.message ? '<div class="message-content">' + message.message + '</div>' : '') + filesHtml + '<div class="message-time">Just now</div></div>';
    }

    messageDiv.innerHTML = messageContent;
    container.appendChild(messageDiv);
}



// ========================================
// POLL FOR NEW MESSAGES
// ========================================

// ========================================
// FIXED TYPING INDICATOR
// ========================================

let typingTimeout;
let isCurrentlyTyping = false;

const messageInput = document.getElementById('messageInput');
if (messageInput) {
    messageInput.addEventListener('input', function() {
        clearTimeout(typingTimeout);
        
        // Only send if we have text and not already marked as typing
        if (this.value.trim().length > 0) {
            if (!isCurrentlyTyping) {
                isCurrentlyTyping = true;
                sendTypingIndicator(true);
            }
            
            // Stop typing after 3 seconds of no input
            typingTimeout = setTimeout(() => {
                isCurrentlyTyping = false;
                sendTypingIndicator(false);
            }, 3000);
        } else {
            // Empty input - stop typing immediately
            isCurrentlyTyping = false;
            sendTypingIndicator(false);
        }
    });
    
    // ✅ IMPORTANT: Stop typing when message is sent
    messageInput.addEventListener('blur', function() {
        if (isCurrentlyTyping) {
            isCurrentlyTyping = false;
            sendTypingIndicator(false);
        }
    });
}

function sendTypingIndicator(isTyping) {
    fetch('/groups/' + groupId + '/typing', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            is_typing: isTyping
        })
    }).catch(err => console.log('Typing indicator error:', err));
}

// ========================================
// FIXED MESSAGE POLLING WITH READ RECEIPTS
// ========================================

// ✅ UPDATED POLLING - Properly handle messages and read receipts
// ✅ REPLACE YOUR EXISTING POLLING setInterval FUNCTION WITH THIS:

setInterval(function() {
    fetch('/groups/' + groupId + '/messages/new/' + lastMessageId)
    .then(response => response.json())
    .then(data => {
        if (data.messages && data.messages.length > 0) {
            const container = document.getElementById('messagesContainer');
            
            data.messages.forEach(msg => {
                if (msg.sender_id != userId) {
                    const messageDiv = document.createElement('div');
                    messageDiv.className = 'd-flex justify-content-start';
                    
                    let messageContent = '';
                    
                    if (msg.message_type === 'call') {
                        // Call message (keep existing code)
                        const callIcon = msg.call_type === 'video' ? 'video' : 'phone';
                        const callDuration = msg.call_duration ? ` (${formatCallDuration(msg.call_duration)})` : '';
                        messageContent = `
                            <div class="call-message-wrapper" style="text-align: center; margin: 20px 0; width: 100%;">
                                <div class="call-message" style="display: inline-block; background: #e3f2fd; padding: 12px 20px; border-radius: 20px; font-size: 14px; color: #1976d2;">
                                    <i class="fa fa-${callIcon}" style="margin-right: 8px;"></i>
                                    <strong>${msg.sender.name}</strong> started a ${msg.call_type} call${callDuration}
                                    <div style="font-size: 12px; color: #666; margin-top: 4px;">
                                        ${new Date(msg.created_at).toLocaleTimeString()}
                                    </div>
                                </div>
                            </div>
                        `;
                        messageDiv.innerHTML = messageContent;
                    } else if (msg.voice_note) {
                        // Voice note message (keep existing code)
                        const duration = msg.voice_duration || 0;
                        const minutes = Math.floor(duration / 60);
                        const seconds = duration % 60;
                        const durationText = minutes + ':' + seconds.toString().padStart(2, '0');
                        
                        messageContent = `
                            <div class="message-bubble received" data-message-id="${msg.id}" 
                                 data-message="${msg.message || ''}" data-sender="${msg.sender_id}" 
                                 oncontextmenu="showMessageActions(event, this); return false;">
                                <div class="message-sender-name">${msg.sender.name}</div>
                                <div class="voice-note-bubble received" onclick="playVoiceNote(this, '${msg.voice_note}')" data-is-deleted="false">
                                    <button class="voice-play-btn">
                                        <i class="fa fa-play"></i>
                                    </button>
                                    <div class="voice-waveform">
                                        <div class="voice-wave-bar" style="height: 15px;"></div>
                                        <div class="voice-wave-bar" style="height: 22px;"></div>
                                        <div class="voice-wave-bar" style="height: 18px;"></div>
                                        <div class="voice-wave-bar" style="height: 25px;"></div>
                                        <div class="voice-wave-bar" style="height: 12px;"></div>
                                        <div class="voice-wave-bar" style="height: 20px;"></div>
                                        <div class="voice-wave-bar" style="height: 16px;"></div>
                                        <div class="voice-wave-bar" style="height: 23px;"></div>
                                    </div>
                                    <span class="voice-duration">${durationText}</span>
                                </div>
                                <div class="message-time">${new Date(msg.created_at).toLocaleTimeString()}</div>
                            </div>
                        `;
                        messageDiv.innerHTML = messageContent;
                    } else {
                        // Regular text message
                        const messageText = msg.message ? msg.message : '';
                        
                        // ✅ NEW: Generate link preview HTML
                        let linkPreviewHtml = '';
                        if (msg.link_preview) {
                            const preview = typeof msg.link_preview === 'string' 
                                ? JSON.parse(msg.link_preview) 
                                : msg.link_preview;
                            
                            if (preview && preview.url) {
                                linkPreviewHtml = `<div class="message-link-preview" onclick="window.open('${preview.url}', '_blank')">`;
                                
                                if (preview.image) {
                                    linkPreviewHtml += `<img src="${preview.image}" class="message-link-preview-image" alt="Link preview">`;
                                }
                                
                                if (preview.title) {
                                    linkPreviewHtml += `<div class="message-link-preview-title">${preview.title}</div>`;
                                }
                                
                                if (preview.description) {
                                    linkPreviewHtml += `<div class="message-link-preview-description">${preview.description}</div>`;
                                }
                                
                                linkPreviewHtml += `<div class="message-link-preview-site">`;
                                if (preview.favicon) {
                                    linkPreviewHtml += `<img src="${preview.favicon}" class="link-preview-favicon" onerror="this.style.display='none'">`;
                                }
                                linkPreviewHtml += `${preview.site_name || new URL(preview.url).hostname}</div></div>`;
                            }
                        }
                        
                        const filesHtml = msg.file_path ? generateFilesHTML(msg.file_path) : '';
                        
                        messageContent = `
                            <div class="message-bubble received" data-message-id="${msg.id}" 
                                 data-message="${messageText}" data-sender="${msg.sender_id}" 
                                 oncontextmenu="showMessageActions(event, this); return false;">
                                <div class="message-sender-name">${msg.sender.name}</div>
                                <div class="message-content">${messageText}</div>
                                ${linkPreviewHtml}
                                ${filesHtml}
                                <div class="message-time">${new Date(msg.created_at).toLocaleTimeString()}</div>
                            </div>
                        `;
                        messageDiv.innerHTML = messageContent;
                    }
                    
                    container.appendChild(messageDiv);
                    lastMessageId = msg.id;
                    playReceiveBeep();
                }
            });
            
            scrollToBottom();
        }
        
        // Read receipts update (keep existing code)
        if (data.messages && data.messages.length > 0) {
            data.messages.forEach(msg => {
                if (msg.sender_id == userId && msg.status === 'seen') {
                    const messageElement = document.querySelector(`[data-message-id="${msg.id}"]`);
                    if (messageElement) {
                        const timeDiv = messageElement.querySelector('.message-time');
                        if (timeDiv) {
                            const oldReceipt = timeDiv.querySelector('span[style*="color"]');
                            if (oldReceipt) {
                                oldReceipt.remove();
                            }
                            
                            const receipt = document.createElement('span');
                            receipt.style.color = '#4fc3f7';
                            receipt.textContent = ' ✓✓';
                            timeDiv.appendChild(receipt);
                        }
                    }
                }
            });
        }
        
        // Typing indicator (keep existing code)
        const typingContainer = document.getElementById('typingIndicator');
        const typingText = document.getElementById('typingText');
        
        if (data.typing_users && data.typing_users.length > 0) {
            const names = data.typing_users.slice(0, 3).join(', ');
            const moreCount = data.typing_users.length - 3;
            const text = data.typing_users.length === 1 
                ? `${names} is typing...`
                : `${names}${moreCount > 0 ? ` and ${moreCount} other${moreCount > 1 ? 's' : ''}` : ''} are typing...`;
            
            if (typingText) typingText.textContent = text;
            if (typingContainer) typingContainer.classList.add('show');
        } else {
            if (typingContainer) typingContainer.classList.remove('show');
        }
    })
    .catch(error => console.error('Polling error:', error));
}, 2000);// Poll every 2 seconds

// Helper function to generate files HTML
function generateFilesHTML(filePath) {
    try {
        const files = JSON.parse(filePath);
        if (!files || files.length === 0) return '';
        
        let html = '<div class="mt-2">';
        files.forEach(file => {
            if (file.includes('.jpg') || file.includes('.png') || file.includes('.jpeg') || file.includes('.gif') || file.includes('.webp')) {
                html += `<img src="${file}" class="img-fluid rounded" style="max-width:200px;cursor:pointer;" onclick="window.open('${file}', '_blank')">`;
            } else if (file.includes('.mp4') || file.includes('.webm') || file.includes('.ogg')) {
                html += `<video controls class="rounded" style="max-width:200px;"><source src="${file}"></video>`;
            } else if (file.includes('.pdf')) {
                html += `<a href="${file}" target="_blank" class="btn btn-sm btn-light mt-2"><i class="fa fa-file-pdf text-danger"></i> View PDF</a>`;
            } else {
                html += `<a href="${file}" target="_blank" class="btn btn-sm btn-light mt-2"><i class="fa fa-file"></i> Download File</a>`;
            }
        });
        html += '</div>';
        return html;
    } catch (e) {
        console.error('Error parsing files:', e);
        return '';
    }
}

// Helper function to format call duration
function formatCallDuration(seconds) {
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    const secs = seconds % 60;
    
    if (hours > 0) {
        return `${hours}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }
    return `${minutes}:${secs.toString().padStart(2, '0')}`;
}

// ========================================
// GROUP FUNCTIONS
// ========================================

function showGroupInfo() {
    window.location.href = '{{ route("groups.show", $group->id) }}';
}

function showMembersModal() {
    document.getElementById('membersModal').classList.add('show');
    document.getElementById('chatMenu').classList.remove('show');
}

function closeMembersModal() {
    document.getElementById('membersModal').classList.remove('show');
}

async function removeMember(memberId) {
    if (!confirm('Remove this member from the group?')) return;

    try {
        const response = await fetch('/groups/' + groupId + '/members/' + memberId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.error || 'Failed to remove member');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to remove member');
    }
}

async function leaveGroup() {
    if (!confirm('Are you sure you want to leave this group?')) return;

    try {
        const response = await fetch('/groups/' + groupId + '/members/' + userId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            window.location.href = '{{ route("groups.index") }}';
        } else {
            alert(data.error || 'Failed to leave group');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to leave group');
    }
}

async function deleteGroup() {
    if (!confirm('Are you sure you want to delete this group? This action cannot be undone.')) return;

    try {
        const response = await fetch('/groups/' + groupId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            window.location.href = '{{ route("groups.index") }}';
        } else {
            alert(data.error || 'Failed to delete group');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to delete group');
    }
}

// ========================================
// GROUP CALLS — Now uses in-page overlay
// ========================================

function initiateGroupCall(type, event) {
    event.preventDefault();
    GroupCallApp.start(type);
}

// ========================================
// GROUP CALL NOTIFICATIONS
// ========================================

document.addEventListener('DOMContentLoaded', () => {
    if (typeof window.Echo !== 'undefined') {
        console.log("✅ Listening for group calls in group " + groupId);

        window.Echo.private('group.' + groupId)
            .listen('.GroupCallInitiated', (e) => {
                console.log('📞 Group call initiated:', e);

                // Don't show notification if you're the one who initiated
                if (e.initiatorId != userId) {
                    playCallBeep();
                    GroupCallApp.handleIncoming(e.callId, e.groupName, e.callType, e.initiatorName, e.agoraChannel);
                }
            });
    }
});

</script>

{{-- ============================================
     GROUP CALL OVERLAY — WhatsApp-style in-page
     ============================================ --}}

{{-- Full-screen call overlay — WhatsApp Style --}}
<div id="groupCallOverlay" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; z-index:5000; background:linear-gradient(180deg, #0B3D35 0%, #0B141A 40%); flex-direction:column;">
    {{-- Header --}}
    <div style="background:transparent; padding:16px 20px; display:flex; align-items:center; justify-content:space-between;">
        <div>
            <h3 id="gcOverlayGroupName" style="color:#E9EDEF; margin:0; font-size:17px; font-weight:500;">{{ $group->name }}</h3>
            <p id="gcOverlayStatus" style="color:#8696A0; margin:4px 0 0; font-size:13px;">Calling...</p>
            <p id="gcOverlayTimer" style="color:#E9EDEF; margin:4px 0 0; font-size:18px; font-weight:500; display:none;">00:00</p>
        </div>
        <button onclick="GroupCallApp.minimize()" style="background:rgba(255,255,255,0.08); border:none; color:#AEBAC1; width:38px; height:38px; border-radius:50%; cursor:pointer; font-size:15px;" title="Minimize"><i class="fa fa-compress"></i></button>
    </div>

    {{-- Participant video grid --}}
    <div id="gcParticipantsGrid" style="flex:1; display:grid; gap:4px; padding:4px; overflow-y:auto; grid-template-columns:1fr; align-content:center;">
        {{-- Local user --}}
        <div id="gcLocalParticipant" style="position:relative; background:#1B2B32; border-radius:16px; overflow:hidden; min-height:200px; display:flex; align-items:center; justify-content:center;">
            <div id="gcLocalVideo" style="width:100%;height:100%;"></div>
            <div id="gcLocalPlaceholder" style="display:flex; flex-direction:column; align-items:center; justify-content:center; color:#E9EDEF; position:absolute;">
                <div style="width:80px; height:80px; border-radius:50%; background:#0EA5E9; display:flex; align-items:center; justify-content:center; font-size:32px; font-weight:500; margin-bottom:10px; color:white;">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                </div>
                <p style="margin:0; font-size:14px;">You</p>
            </div>
            <div style="position:absolute; bottom:10px; left:10px; background:rgba(11,20,26,0.7); color:#E9EDEF; padding:4px 10px; border-radius:6px; font-size:13px;">You</div>
        </div>
        {{-- Remote participants are added dynamically --}}
    </div>

    {{-- Incoming call buttons (for receivers) --}}
    <div id="gcIncomingButtons" style="display:none; justify-content:center; gap:48px; padding:24px 20px 40px;">
        <div style="text-align:center;">
            <button onclick="GroupCallApp.declineCall()" style="width:60px; height:60px; border-radius:50%; background:#EA4335; border:none; color:white; font-size:22px; cursor:pointer;"><i class="fa fa-phone-slash"></i></button>
            <div style="color:#8696A0; font-size:12px; margin-top:8px;">Decline</div>
        </div>
        <div style="text-align:center;">
            <button onclick="GroupCallApp.joinCall(GroupCallApp.callId)" style="width:60px; height:60px; border-radius:50%; background:#4CAF50; border:none; color:white; font-size:22px; cursor:pointer;"><i class="fa fa-phone"></i></button>
            <div style="color:#8696A0; font-size:12px; margin-top:8px;">Join</div>
        </div>
    </div>

    {{-- Active call controls --}}
    <div id="gcActiveButtons" style="display:flex; justify-content:center; align-items:center; gap:20px; padding:20px 0 34px; background:rgba(11,20,26,0.95);">
        <button id="gcMuteBtn" onclick="GroupCallApp.toggleMute()" style="width:54px; height:54px; border-radius:50%; background:rgba(255,255,255,0.1); border:none; color:#E9EDEF; font-size:20px; cursor:pointer;" title="Mute"><i class="fa fa-microphone"></i></button>
        <button id="gcVideoBtn" onclick="GroupCallApp.toggleVideo()" style="width:54px; height:54px; border-radius:50%; background:rgba(255,255,255,0.1); border:none; color:#E9EDEF; font-size:20px; cursor:pointer; display:none;" title="Camera"><i class="fa fa-video"></i></button>
        <button onclick="GroupCallApp.leaveCall()" style="width:60px; height:60px; border-radius:50%; background:#EA4335; border:none; color:white; font-size:22px; cursor:pointer;" title="Leave Call"><i class="fa fa-phone-slash"></i></button>
        <button onclick="GroupCallApp.minimize()" style="width:54px; height:54px; border-radius:50%; background:rgba(255,255,255,0.1); border:none; color:#E9EDEF; font-size:20px; cursor:pointer;" title="Minimize"><i class="fa fa-compress"></i></button>
    </div>
</div>

{{-- Minimized Group Call Bar — WhatsApp Style --}}
<div id="gcMinimizedBar" style="display:none; position:fixed; top:0; left:0; right:0; z-index:4999; background:#0EA5E9; color:white; padding:8px 16px; align-items:center; justify-content:space-between;">
    <div style="display:flex; align-items:center; gap:10px; cursor:pointer;" onclick="GroupCallApp.maximize()">
        <i class="fa fa-phone" style="font-size:14px;"></i>
        <span id="gcBarTimer" style="font-size:14px; font-weight:500;">00:00</span>
        <span style="font-size:14px;">{{ $group->name }}</span>
    </div>
    <button onclick="GroupCallApp.leaveCall()" style="background:#EA4335; color:white; border:none; border-radius:16px; padding:4px 14px; cursor:pointer; font-weight:500; font-size:13px;">Leave</button>
</div>

<style>
#gcParticipantsGrid[data-count="2"] { grid-template-columns: repeat(2, 1fr); }
#gcParticipantsGrid[data-count="3"],
#gcParticipantsGrid[data-count="4"] { grid-template-columns: repeat(2, 1fr); }
#gcParticipantsGrid[data-count="5"],
#gcParticipantsGrid[data-count="6"] { grid-template-columns: repeat(3, 1fr); }
@media (max-width: 768px) {
    #gcParticipantsGrid { grid-template-columns: 1fr !important; }
}
</style>

{{-- Agora SDK --}}
<script src="https://download.agora.io/sdk/release/AgoraRTC_N-4.22.0.js"></script>

{{-- GroupCallApp JS --}}
<script>
const GroupCallApp = {
    callId: null,
    callType: null,
    agoraClient: null,
    agoraChannel: null,
    localAudioTrack: null,
    localVideoTrack: null,
    isMuted: false,
    isVideoOff: false,
    isCallActive: false,
    callTimer: null,
    callSeconds: 0,
    audioContext: null,
    ringbackInterval: null,
    remoteUsers: {},
    _joiningAgora: false,

    // ---- Start outgoing group call ----
    async start(type) {
        this.cleanup();
        this.callType = type;
        this.showOverlay();
        document.getElementById('gcOverlayStatus').textContent = 'Starting call...';
        document.getElementById('gcActiveButtons').style.display = 'flex';
        document.getElementById('gcIncomingButtons').style.display = 'none';

        if (type === 'video') {
            document.getElementById('gcVideoBtn').style.display = '';
        } else {
            document.getElementById('gcVideoBtn').style.display = 'none';
        }

        try {
            const response = await fetch('/group-calls/initiate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    group_id: groupId,
                    call_type: type
                })
            });
            const data = await response.json();

            if (!data.success) {
                alert('Failed to start call.');
                this.hideOverlay();
                return;
            }

            this.callId = data.call_id;
            this.agoraChannel = data.agora_channel || ('group_call_' + data.call_id);
            console.log('✅ Group call initiated, callId:', data.call_id, 'agoraChannel:', this.agoraChannel);

            this.playRingback();
            // Join Agora immediately (initiator)
            await this.joinAgoraChannel();
        } catch (error) {
            console.error('Group call start error:', error);
            alert('Failed to start call.');
            this.hideOverlay();
        }
    },

    // ---- Handle incoming call notification ----
    handleIncoming(callId, groupName, callType, initiatorName, agoraChannel) {
        this.cleanup();
        this.callId = callId;
        this.callType = callType;
        this.agoraChannel = agoraChannel || ('group_call_' + callId);

        document.getElementById('gcOverlayGroupName').textContent = groupName;
        document.getElementById('gcOverlayStatus').textContent = initiatorName + ' started a ' + callType + ' call';
        document.getElementById('gcOverlayTimer').style.display = 'none';
        document.getElementById('gcActiveButtons').style.display = 'none';
        document.getElementById('gcIncomingButtons').style.display = 'flex';

        if (callType === 'video') {
            document.getElementById('gcVideoBtn').style.display = '';
        }

        this.showOverlay();
    },

    // ---- Join an existing call (click "Join") ----
    async joinCall(callId) {
        this.callId = callId;
        this.agoraChannel = this.agoraChannel || ('group_call_' + callId);

        document.getElementById('gcIncomingButtons').style.display = 'none';
        document.getElementById('gcActiveButtons').style.display = 'flex';
        document.getElementById('gcOverlayStatus').textContent = 'Connecting...';
        this.showOverlay();

        try {
            // Tell server we joined
            await fetch(`/group-calls/${callId}/join`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });

            // Join Agora channel
            await this.joinAgoraChannel();
        } catch (error) {
            console.error('Join call error:', error);
            alert('Failed to join call.');
            this.hideOverlay();
        }
    },

    // ---- Decline incoming call ----
    async declineCall() {
        if (this.callId) {
            try {
                await fetch(`/group-calls/${this.callId}/decline`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                });
            } catch (e) {}
        }
        this.cleanup();
        this.hideOverlay();
    },

    // ---- Join Agora Channel ----
    async joinAgoraChannel() {
        if (this._joiningAgora) {
            console.log('⚠️ Already joining Agora channel, skipping duplicate call');
            return;
        }
        this._joiningAgora = true;
        try {
            console.log('🔗 Joining Agora channel:', this.agoraChannel);

            const tokenResponse = await fetch('/agora/token', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ channel: this.agoraChannel })
            });
            const tokenData = await tokenResponse.json();

            if (!tokenData.success) {
                console.error('Failed to get Agora token');
                alert('Failed to connect call.');
                this.cleanup();
                this.hideOverlay();
                return;
            }

            this.agoraClient = AgoraRTC.createClient({ mode: 'rtc', codec: 'vp8' });

            // Remote user published
            this.agoraClient.on('user-published', async (user, mediaType) => {
                await this.agoraClient.subscribe(user, mediaType);
                console.log('✅ Subscribed to remote user:', user.uid, mediaType);
                this.handleRemoteUser(user, mediaType);
                if (!this.isCallActive) this.onCallConnected();
            });

            this.agoraClient.on('user-unpublished', (user, mediaType) => {
                if (mediaType === 'video') {
                    const vc = document.getElementById(`gc-remote-video-${user.uid}`);
                    if (vc) vc.innerHTML = '';
                    const ph = document.getElementById(`gc-remote-placeholder-${user.uid}`);
                    if (ph) ph.style.display = 'flex';
                }
            });

            this.agoraClient.on('user-left', (user) => {
                console.log('📴 Remote user left:', user.uid);
                this.handleRemoteLeft(user);
            });

            // Auto-renew token before it expires
            this.agoraClient.on('token-privilege-will-expire', async () => {
                console.log('🔄 Token expiring soon, renewing...');
                try {
                    const renewResponse = await fetch('/agora/token', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ channel: this.agoraChannel })
                    });
                    const renewData = await renewResponse.json();
                    if (renewData.success && renewData.token) {
                        await this.agoraClient.renewToken(renewData.token);
                        console.log('✅ Token renewed successfully');
                    }
                } catch (err) {
                    console.error('❌ Token renewal failed:', err);
                }
            });

            this.agoraClient.on('token-privilege-did-expire', async () => {
                console.log('⚠️ Token expired, attempting to renew...');
                try {
                    const renewResponse = await fetch('/agora/token', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ channel: this.agoraChannel })
                    });
                    const renewData = await renewResponse.json();
                    if (renewData.success && renewData.token) {
                        await this.agoraClient.renewToken(renewData.token);
                        console.log('✅ Token renewed after expiry');
                    }
                } catch (err) {
                    console.error('❌ Token renewal after expiry failed:', err);
                }
            });

            // Join
            await this.agoraClient.join(tokenData.app_id, this.agoraChannel, tokenData.token, tokenData.uid);
            console.log('✅ Joined Agora channel');

            // Publish local tracks
            if (this.callType === 'video') {
                [this.localAudioTrack, this.localVideoTrack] = await AgoraRTC.createMicrophoneAndCameraTracks(
                    {}, { encoderConfig: '480p_1' }
                );
                const localContainer = document.getElementById('gcLocalVideo');
                this.localVideoTrack.play(localContainer);
                document.getElementById('gcLocalPlaceholder').style.display = 'none';
                await this.agoraClient.publish([this.localAudioTrack, this.localVideoTrack]);
            } else {
                this.localAudioTrack = await AgoraRTC.createMicrophoneAudioTrack();
                await this.agoraClient.publish([this.localAudioTrack]);
            }

            console.log('✅ Published local tracks');
            document.getElementById('gcOverlayStatus').textContent = 'Connected';
            this.stopRingback();

            // Mark connected after short delay if not already
            setTimeout(() => {
                if (!this.isCallActive && this.agoraClient) {
                    this.onCallConnected();
                }
            }, 2000);

        } catch (error) {
            console.error('❌ Agora join error:', error);
            this._joiningAgora = false;
            alert('Failed to connect call. Check microphone/camera permissions.');
            this.cleanup();
            this.hideOverlay();
        }
    },

    // ---- Handle remote user joining ----
    handleRemoteUser(user, mediaType) {
        const uid = user.uid;
        let participantDiv = document.getElementById(`gc-remote-${uid}`);

        if (!participantDiv) {
            participantDiv = document.createElement('div');
            participantDiv.id = `gc-remote-${uid}`;
            participantDiv.style.cssText = 'position:relative; background:#1B2B32; border-radius:16px; overflow:hidden; min-height:200px; display:flex; align-items:center; justify-content:center;';
            participantDiv.innerHTML = `
                <div id="gc-remote-video-${uid}" style="width:100%;height:100%;"></div>
                <div id="gc-remote-placeholder-${uid}" style="display:flex; flex-direction:column; align-items:center; justify-content:center; color:#E9EDEF; position:absolute;">
                    <div style="width:80px; height:80px; border-radius:50%; background:#0EA5E9; display:flex; align-items:center; justify-content:center; font-size:32px; font-weight:500; margin-bottom:10px; color:white;">U</div>
                    <p style="margin:0; font-size:14px;">User ${uid}</p>
                </div>
                <div style="position:absolute; bottom:10px; left:10px; background:rgba(11,20,26,0.7); color:#E9EDEF; padding:4px 10px; border-radius:6px; font-size:13px;">User ${uid}</div>
                <div style="position:absolute; top:10px; right:10px; padding:3px 8px; border-radius:6px; font-size:11px; font-weight:500; background:rgba(14,165,233,0.85); color:white;">Joined</div>
            `;
            document.getElementById('gcParticipantsGrid').appendChild(participantDiv);
            this.remoteUsers[uid] = user;
            this.updateGridCount();
        }

        if (mediaType === 'video') {
            const vc = document.getElementById(`gc-remote-video-${uid}`);
            user.videoTrack.play(vc);
            document.getElementById(`gc-remote-placeholder-${uid}`).style.display = 'none';
        }
        if (mediaType === 'audio') {
            user.audioTrack.play();
        }
    },

    // ---- Handle remote user leaving ----
    handleRemoteLeft(user) {
        const uid = user.uid;
        const div = document.getElementById(`gc-remote-${uid}`);
        if (div) div.remove();
        delete this.remoteUsers[uid];
        this.updateGridCount();
    },

    updateGridCount() {
        const grid = document.getElementById('gcParticipantsGrid');
        const count = grid.children.length;
        grid.setAttribute('data-count', count);
    },

    // ---- Call connected ----
    onCallConnected() {
        if (this.isCallActive) return;
        this.isCallActive = true;
        this.stopRingback();
        document.getElementById('gcOverlayStatus').style.display = 'none';
        this.startTimer();
    },

    // ---- Leave call ----
    async leaveCall() {
        if (this.callId) {
            try {
                await fetch(`/group-calls/${this.callId}/leave`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                });
            } catch (e) { console.error('Leave call error:', e); }
        }
        this.cleanup();
        this.hideOverlay();
    },

    // ---- Toggle mute ----
    toggleMute() {
        if (!this.localAudioTrack) return;
        this.isMuted = !this.isMuted;
        this.localAudioTrack.setEnabled(!this.isMuted);
        const btn = document.getElementById('gcMuteBtn');
        btn.querySelector('i').className = this.isMuted ? 'fa fa-microphone-slash' : 'fa fa-microphone';
        btn.style.background = this.isMuted ? '#EA4335' : 'rgba(255,255,255,0.1)';
    },

    // ---- Toggle video ----
    toggleVideo() {
        if (!this.localVideoTrack) return;
        this.isVideoOff = !this.isVideoOff;
        this.localVideoTrack.setEnabled(!this.isVideoOff);
        const btn = document.getElementById('gcVideoBtn');
        btn.querySelector('i').className = this.isVideoOff ? 'fa fa-video-slash' : 'fa fa-video';
        btn.style.background = this.isVideoOff ? '#EA4335' : 'rgba(255,255,255,0.1)';
    },

    // ---- Minimize / Maximize ----
    minimize() {
        document.getElementById('groupCallOverlay').style.display = 'none';
        document.getElementById('gcMinimizedBar').style.display = 'flex';
    },

    maximize() {
        document.getElementById('gcMinimizedBar').style.display = 'none';
        document.getElementById('groupCallOverlay').style.display = 'flex';
    },

    // ---- Timer ----
    startTimer() {
        this.callSeconds = 0;
        const timerEl = document.getElementById('gcOverlayTimer');
        const barTimer = document.getElementById('gcBarTimer');
        timerEl.style.display = 'block';

        this.callTimer = setInterval(() => {
            this.callSeconds++;
            const m = String(Math.floor(this.callSeconds / 60)).padStart(2, '0');
            const s = String(this.callSeconds % 60).padStart(2, '0');
            timerEl.textContent = `${m}:${s}`;
            barTimer.textContent = `${m}:${s}`;
        }, 1000);
    },

    // ---- Ringback ----
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
        } catch (e) {}
    },

    stopRingback() {
        if (this.ringbackInterval) clearInterval(this.ringbackInterval);
        if (this.audioContext) {
            try { this.audioContext.close(); } catch (e) {}
            this.audioContext = null;
        }
    },

    // ---- Show/Hide overlay ----
    showOverlay() {
        document.getElementById('groupCallOverlay').style.display = 'flex';
    },

    hideOverlay() {
        document.getElementById('groupCallOverlay').style.display = 'none';
        document.getElementById('gcMinimizedBar').style.display = 'none';
        document.getElementById('gcOverlayTimer').style.display = 'none';
        document.getElementById('gcOverlayStatus').style.display = '';
        document.getElementById('gcOverlayStatus').textContent = '';
        document.getElementById('gcOverlayGroupName').textContent = '{{ $group->name }}';

        // Clear remote participant divs
        const grid = document.getElementById('gcParticipantsGrid');
        const remotes = grid.querySelectorAll('[id^="gc-remote-"]');
        remotes.forEach(el => el.remove());

        // Reset local placeholder
        document.getElementById('gcLocalPlaceholder').style.display = 'flex';
        document.getElementById('gcLocalVideo').innerHTML = '';

        this.callId = null;
        this.callType = null;
    },

    // ---- Cleanup ----
    cleanup() {
        this.stopRingback();
        if (this.callTimer) clearInterval(this.callTimer);

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

        this.isCallActive = false;
        this.callSeconds = 0;
        this.isMuted = false;
        this.isVideoOff = false;
        this.remoteUsers = {};
        this.agoraChannel = null;
        this._joiningAgora = false;
    }
};

// Auto-join group call from URL parameter (when navigating from global notification)
(function() {
    const urlParams = new URLSearchParams(window.location.search);
    const joinCallId = urlParams.get('join_call');
    const joinCallType = urlParams.get('call_type');
    if (joinCallId) {
        GroupCallApp.callType = joinCallType || 'audio';
        GroupCallApp.joinCall(joinCallId);
        window.history.replaceState({}, '', window.location.pathname);
    }
})();
</script>

@endsection

</body>
</html>


   