
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

    <title>massage</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>

    <!-- Stylesheets -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <!-- Font Awesome 4.7 (matches your fa fa-user-circle class) -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    
    <!-- Your Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/finebtn.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script>
    // Initialize FancyBox (often not required if attributes are used correctly)
    Fancybox.bind("[data-fancybox]", {
    //     // options
    });
</script>



</head>
<body>



@extends('layouts.app')

@section('content')
<style>
/* ========================================
   WHATSAPP-STYLE CHAT DESIGN
   ======================================== */

/* Chat Container Styles */
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
}

/* Message Bubbles */
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

.reply-reference {
    background: rgba(0,0,0,0.05);
    font-size: 12px;
    margin-bottom: 5px;
    padding: 5px 10px;
    border-left: 3px solid #06CF9C;
    border-radius: 4px;
    font-style: italic;
    opacity: 1;
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

/* SVG Tick Marks */
.tick-sent svg, .tick-delivered svg, .tick-read svg {
    vertical-align: middle;
}

/* Message Actions Menu */
.message-actions {
    position: fixed;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    padding: 8px 0;
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
    padding: 12px 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: background 0.2s;
}

.message-action-item:hover {
    background: #f0f2f5;
}

/* Forward Modal */
.forward-modal {
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

.forward-modal.show {
    display: flex;
}

.forward-modal-content {
    background: white;
    border-radius: 12px;
    padding: 20px;
    max-width: 500px;
    width: 90%;
    max-height: 80vh;
    overflow-y: auto;
}

.friend-item {
    padding: 10px;
    border-bottom: 1px solid #e0e0e0;
    cursor: pointer;
}

.friend-item:hover {
    background: #f0f2f5;
}

/* Menu Options */
.chat-menu {
    position: absolute;
    right: 20px;
    top: 60px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    padding: 8px 0;
    display: none;
    min-width: 180px;
    z-index: 1000;
}

.chat-menu.show {
    display: block;
}

.menu-item {
    padding: 12px 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
}

.menu-item:hover {
    background: #f0f2f5;
}

/* Date Separator */
.date-separator {
    display: flex;
    justify-content: center;
    margin: 12px 0;
}

.date-separator-chip {
    background: #E1F2FA;
    color: #54656F;
    border-radius: 7px;
    font-size: 12px;
    padding: 5px 12px;
    box-shadow: 0 1px 0.5px rgba(11,20,26,0.13);
    text-transform: uppercase;
    font-weight: 500;
}

/* Search Bar */
.chat-search-bar {
    display: none;
    background: white;
    padding: 8px 12px;
    align-items: center;
    gap: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    z-index: 9;
}

.chat-search-bar.show {
    display: flex;
}

.chat-search-bar input {
    flex: 1;
    border: none;
    background: #F0F2F5;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 14px;
    outline: none;
}

.chat-search-bar .search-nav-btn {
    background: none;
    border: none;
    color: #54656F;
    cursor: pointer;
    font-size: 16px;
    padding: 4px 8px;
}

.search-result-count {
    font-size: 12px;
    color: #667781;
    white-space: nowrap;
}

/* Scroll to Bottom Button */
.scroll-to-bottom-btn {
    position: absolute;
    bottom: 80px;
    right: 20px;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: white;
    border: none;
    box-shadow: 0 1px 3px rgba(11,20,26,0.26);
    cursor: pointer;
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 5;
    color: #54656F;
    font-size: 18px;
}

.scroll-to-bottom-btn .unread-badge {
    position: absolute;
    top: -6px;
    right: -2px;
    background: #25D366;
    color: white;
    border-radius: 12px;
    font-size: 11px;
    min-width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 5px;
    font-weight: 600;
}

/* Forwarded Label */
.forwarded-label {
    color: #667781;
    font-size: 11px;
    font-style: italic;
    margin-bottom: 2px;
}

.forwarded-label svg {
    vertical-align: middle;
    margin-right: 2px;
}

/* File Upload Preview */
.file-upload-preview {
    display: none;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 10px;
}

.file-upload-preview.show {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
}

.file-preview-item {
    position: relative;
    display: inline-block;
    margin: 5px;
}

.file-preview-item img,
.file-preview-item video {
    max-width: 100px;
    max-height: 100px;
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
    width: 24px;
    height: 24px;
    cursor: pointer;
    font-weight: bold;
    line-height: 1;
}

.file-remove-btn:hover {
    background: #c82333;
}


.upload-progress {
    margin-top: 10px;
}

.progress {
    height: 20px;
}

.message-action-item i {
    width: 20px;
}

/* Reply Preview */
.reply-preview {
    background: #D9FDD3;
    padding: 10px 15px;
    margin-bottom: 10px;
    border-left: 4px solid #06CF9C;
    border-radius: 8px;
    display: none;
}

.reply-preview.show {
    display: block;
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
}

/* Pinned Message */
.pinned-message {
    background: #fff3cd;
    padding: 10px 15px;
    border-left: 4px solid #ffc107;
    margin-bottom: 10px;
    display: none;
}

.pinned-message.show {
    display: block;
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

/* Add this to your main CSS file */
.pinned-highlight {
    background-color: #ffeb3b !important; /* Bright yellow */
    transition: background-color 0.5s ease;
    border-radius: 8px;
    padding: 10px;
    box-shadow: 0 0 10px rgba(255, 235, 59, 0.7);
}

/* Ensure the pin bar is visually clickable */
.pinned-message {
    cursor: pointer;
}



/* <!-- Add this CSS for call messages styling --> */
.call-message-bubble {
    background: #0EA5E9 !important;
    color: white !important;
    padding: 15px 20px;
    border-radius: 18px;
    text-align: center;
    font-weight: 500;
    box-shadow: 0 2px 8px rgba(0, 128, 105, 0.3);
    max-width: 80%;
    margin: 10px auto;
}

.call-message-bubble i {
    font-size: 18px;
    margin-right: 5px;
}

.call-message-info {
    font-size: 13px;
    opacity: 0.9;
    margin-top: 5px;
}

.call-status-ended {
    background: linear-gradient(135deg, #4caf50 0%, #45a049 100%) !important;
}

.call-status-declined {
    background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%) !important;
}

.call-status-no_answer {
    background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%) !important;
}

/* ========================================
   MOBILE-OPTIMIZED CHAT INPUT STYLES
   ======================================== */

/* Chat Input Container */
.chat-input-area {
    background: #F0F2F5;
    padding: 8px 16px;
    box-shadow: none;
    position: sticky;
    bottom: 0;
    z-index: 100;
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

/* Voice Control Buttons */
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

/* Voice Note Bubble in Chat */
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

.voice-note-bubble.received {
    background: white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.voice-play-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #00A884;
    color: white;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    flex-shrink: 0;
}

.voice-play-btn:hover {
    background: #0284C7;
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
    background: #00A884;
}

.voice-duration {
    font-size: 12px;
    opacity: 0.8;
    white-space: nowrap;
}

/* Voice Note Controls Wrapper */
.voice-note-controls {
    display: flex;
    align-items: center;
    gap: 12px;
    width: 100%;
}

/* Voice Transcribe Button */
.voice-transcribe-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    margin-top: 6px;
    border-radius: 12px;
    background: rgba(0,0,0,0.08);
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 11px;
    color: #555;
}

.voice-transcribe-btn i {
    font-size: 12px;
}

.voice-transcribe-btn:hover {
    background: rgba(0,0,0,0.15);
}

.voice-note-bubble.sent .voice-transcribe-btn {
    background: rgba(0,0,0,0.08);
    color: #333;
}

.voice-note-bubble.received .voice-transcribe-btn {
    background: rgba(0,0,0,0.06);
    color: #555;
}

.voice-transcribe-btn.transcribing {
    animation: pulse-transcribe 1.5s infinite;
}

@keyframes pulse-transcribe {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.05); opacity: 0.7; }
}

.voice-transcript {
    margin-top: 4px;
    padding: 8px 12px;
    background: rgba(0,0,0,0.04);
    border-radius: 8px;
    font-size: 13px;
    line-height: 1.5;
    display: none;
    border-left: 3px solid #0EA5E9;
    max-width: 280px;
    color: #333;
}

.voice-transcript.show {
    display: block;
}

.transcript-error {
    color: #f44336;
    font-style: italic;
}

.transcript-loading {
    color: #666;
    font-style: italic;
}

/* Modern Popup Modal */
.custom-popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 99999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.25s ease;
    backdrop-filter: blur(4px);
}

.custom-popup-overlay.show {
    opacity: 1;
    visibility: visible;
}

.custom-popup {
    background: #fff;
    border-radius: 16px;
    padding: 28px 24px 20px;
    max-width: 340px;
    width: 90%;
    text-align: center;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    transform: scale(0.85) translateY(20px);
    transition: transform 0.25s ease;
}

.custom-popup-overlay.show .custom-popup {
    transform: scale(1) translateY(0);
}

.custom-popup-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 14px;
    font-size: 20px;
}

.custom-popup-icon.success {
    background: #E8F5E9;
    color: #43A047;
}

.custom-popup-icon.error {
    background: #FFEBEE;
    color: #E53935;
}

.custom-popup-icon.warning {
    background: #FFF3E0;
    color: #FB8C00;
}

.custom-popup-icon.info {
    background: #E3F2FD;
    color: #1E88E5;
}

.custom-popup-message {
    font-size: 14px;
    color: #333;
    line-height: 1.5;
    margin-bottom: 20px;
    word-wrap: break-word;
}

.custom-popup-btn {
    padding: 10px 32px;
    border: none;
    border-radius: 24px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    background: #0EA5E9;
    color: white;
}

.custom-popup-btn:hover {
    filter: brightness(1.1);
    transform: translateY(-1px);
}

/* File Upload Preview - Mobile */
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

/* Input Form - Mobile Optimized */
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
    background: #4CAF50;
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
    background: #ff4444;
    animation: pulse-button 1.5s infinite;
}

@keyframes pulse-button {
    0%, 100% { box-shadow: 0 0 0 0 rgba(255, 68, 68, 0.7); }
    50% { box-shadow: 0 0 0 10px rgba(255, 68, 68, 0); }
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

/* Hide when voice recording */
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
    background: #4CAF50;
    color: white;
}

.btn-switch-camera {
    background: #2196F3;
    color: white;
}

.btn-close-camera {
    background: #f44336;
    color: white;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .chat-input-area {
        padding: 8px 10px;
    }

    .input-btn {
        width: 38px;
        height: 38px;
        font-size: 16px;
    }

    #messageInput {
        font-size: 16px; /* Prevents iOS zoom */
        padding: 9px 12px;
    }

    .voice-note-bubble {
        max-width: 240px;
    }
}

/* Video Recording Indicator */
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

.camera-mode-toggle:hover {
    background: rgba(0, 0, 0, 0.8);
}

/* Update capture button for video mode */
.btn-capture.video-mode {
    background: #f44336;
    animation: pulse-record 2s infinite;
}

@keyframes pulse-record {
    0%, 100% { box-shadow: 0 0 0 0 rgba(244, 67, 54, 0.7); }
    50% { box-shadow: 0 0 0 15px rgba(244, 67, 54, 0); }
}



/* ========================================
   MOBILE-OPTIMIZED LINK PREVIEW STYLES
   ======================================== */

/* Link Preview Loading State */
.link-preview-loading {
    padding: 8px 12px;
    text-align: center;
    background: #f8f9fa;
    border-radius: 8px;
    color: #0EA5E9;
    font-size: 12px;
    margin: 8px 0;
}

/* Main Preview Card */
.link-preview-card {
    position: relative;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    overflow: hidden;
    background: white;
    margin-top: 8px;
    margin-bottom: 8px;
    max-width: 320px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

/* Close Button */
.link-preview-close {
    position: absolute;
    top: 6px;
    right: 6px;
    background: rgba(0, 0, 0, 0.6);
    color: white;
    border: none;
    border-radius: 50%;
    width: 22px;
    height: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    font-size: 12px;
    padding: 0;
    transition: background 0.2s;
}

.link-preview-close:hover {
    background: rgba(220, 53, 69, 0.9);
}

/* Preview Image */
.link-preview-image {
    width: 100%;
    height: 120px;
    overflow: hidden;
    background: #f5f5f5;
    position: relative;
}

.link-preview-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Content Area */
.link-preview-content {
    padding: 10px;
}

/* Site Name */
.link-preview-site {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 6px;
    color: #666;
    font-size: 11px;
    overflow: hidden;
}

.link-preview-favicon {
    width: 12px;
    height: 12px;
    border-radius: 2px;
    flex-shrink: 0;
}

/* Title */
.link-preview-title {
    font-size: 13px;
    font-weight: bold;
    color: #333;
    margin: 0 0 5px 0;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Description */
.link-preview-description {
    font-size: 11px;
    color: #666;
    line-height: 1.4;
    margin: 0 0 6px 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* URL Link */
.link-preview-url {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 10px;
    color: #0EA5E9;
    text-decoration: none;
    word-break: break-all;
    max-width: 100%;
}

.link-preview-url:hover {
    text-decoration: underline;
}

.link-preview-url i {
    font-size: 9px;
    flex-shrink: 0;
}

/* In Message Container */
.message-link-preview {
    margin-top: 6px;
}

/* Before Sending (in input area) */
#messageLinkPreviewContainer {
    margin-top: 10px;
}

#messageLinkPreviewContainer .link-preview-card {
    max-width: 100%;
}

/* Mobile Optimization */
@media (max-width: 768px) {
    .link-preview-card {
        max-width: 280px;
    }
    
    .link-preview-image {
        height: 100px;
    }
    
    .link-preview-content {
        padding: 8px;
    }
    
    .link-preview-title {
        font-size: 12px;
    }
    
    .link-preview-description {
        font-size: 10px;
        -webkit-line-clamp: 1; /* Show only 1 line on mobile */
    }
    
    .link-preview-url {
        font-size: 9px;
    }
    
    #messageLinkPreviewContainer .link-preview-card {
        max-width: 100%;
    }
}

/* Very Small Screens */
@media (max-width: 480px) {
    .link-preview-card {
        max-width: 240px;
    }
    
    .link-preview-image {
        height: 80px;
    }
    
    .link-preview-content {
        padding: 6px;
    }
    
    .link-preview-title {
        font-size: 11px;
    }
}

</style>


<!-- Add audio elements for beep sounds -->
<audio id="sendBeep" preload="auto">
    <source src="{{ asset('sounds/send_beep.wav') }}" type="audio/mpeg">
</audio>

<audio id="receiveBeep" preload="auto">
    <source src="{{ asset('sounds/receive_beep.wav') }}" type="audio/mpeg">
</audio>

<div class="chat-wrapper">
    {{-- Chat Header --}}
    <div class="chat-header">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center flex-grow-1">
                <a href="{{ route('messages.index') }}" class="text-white me-3 mr-2">
                    <i class="fa fa-arrow-left"></i>
                </a>
                
                @php
                    $loginSession = $friend->lastLoginSession;
                    $isOnline = $loginSession 
                        && is_null($loginSession->logout_at) 
                        && $loginSession->updated_at 
                        && \Carbon\Carbon::parse($loginSession->updated_at)->isAfter(now()->subMinutes(30));
                @endphp
                
                <div class="position-relative me-3">
                    <img src="{{ $friend->profileimg ?? asset('images/best3.png') }}" 
                         class="rounded-circle mr-2" 
                         style="width:45px;height:45px;object-fit:cover;border:2px solid white;">
                    <!-- <span class="position-absolute bottom-0 end-0 p-1 
                                 {{ $isOnline ? 'bg-success' : 'bg-secondary' }} 
                                 border border-light rounded-circle"
                          style="width:12px;height:12px;">
                    </span> -->
                </div>
                
                <div class="flex-grow-1">
                    <h6 class="mb-0">{{ $friend->name }}
                    @if($friend->badge_status)
                        <img src="{{ asset($friend->badge_status) }}" 
                                style="width:16px;height:16px;vertical-align:middle;">
                    @endif
                    </h6>
                    <small id="typingIndicator" style="display:none;">
                        <i>typing...</i>
                    </small>
                    <small id="onlineStatus">
                        {{ $isOnline ? '🟢 Online' : '⚫ Offline' }}
                    </small>
                </div>
            </div>
            
            <div class="d-flex align-items-center" style="gap: 20px;">
                <a href="#" class="text-white" onclick="initiateCall('video'); return false;" title="Video Call">
                    <i class="fa fa-video fa-lg"></i>
                </a>
                <a href="#" class="text-white" onclick="initiateCall('audio'); return false;" title="Audio Call">
                    <i class="fa fa-phone fa-lg"></i>
                </a>
                <a href="#" class="text-white" onclick="toggleSearchBar(); return false;" title="Search">
                    <i class="fa fa-search fa-lg"></i>
                </a>
                <a href="#" class="text-white" onclick="toggleChatMenu(event)">
                    <i class="fa fa-ellipsis-v fa-lg"></i>
                </a>
            </div>
        </div>
    </div>
    
   
    {{-- Pinned Message --}}
@php
    $initialPinnedMessage = $pinnedMessage ?? null; // Assume $pinnedMessage is passed from controller
@endphp

<div class="pinned-message {{ $initialPinnedMessage ? 'show' : '' }}" 
     id="pinnedMessage"
     data-pinned-id="{{ $initialPinnedMessage->id ?? '' }}"
     onclick="highlightMessage('{{ $initialPinnedMessage->id ?? '' }}')">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <strong>📌 Pinned:</strong>
            <span id="pinnedMessageText">
                @if($initialPinnedMessage)
                    {{-- Re-use the display logic from the reply reference or just show text/attachment hint --}}
                    @php
                        $initialPinnedFiles = $initialPinnedMessage->file_path ? json_decode($initialPinnedMessage->file_path, true) : null;
                        $initialFileCount = $initialPinnedFiles ? count($initialPinnedFiles) : 0;
                    @endphp
                    // ...
@if($initialPinnedFiles)
    <div class="reply-attachment-preview">
        @foreach(array_slice($initialPinnedFiles, 0, 1) as $file)
            
            @php
                // 1. Handle if file is a string (URL) or array (just in case)
                $fileUrl = is_array($file) ? ($file['path'] ?? '') : $file;

                // 2. Get extension from the URL
                $path = parse_url($fileUrl, PHP_URL_PATH);
                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            @endphp

            {{-- 3. Check Extension to decide what to show --}}
            @if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']))
                {{-- It is an Image --}}
                <img src="{{ $fileUrl }}" 
                     alt="Attachment" 
                     style="width: 30px; height: 30px; object-fit: cover; border-radius: 4px; margin-right: 5px;">
            
            @elseif(in_array($ext, ['mp4', 'mov', 'avi', 'wmv', 'flv', 'webm', 'ogg']))
                {{-- It is a Video --}}
                <div style="width: 30px; height: 30px; background: #eee; border-radius: 4px; display: inline-flex; align-items: center; justify-content: center; margin-right: 5px;">
                    <i class="fa fa-video" style="color: #6c757d; font-size: 14px;"></i>
                </div>

            @else
                {{-- It is a File (PDF, etc) --}}
                <div style="width: 30px; height: 30px; background: #eee; border-radius: 4px; display: inline-flex; align-items: center; justify-content: center; margin-right: 5px;">
                    <i class="fa fa-file" style="color: #6c757d; font-size: 14px;"></i>
                </div>
            @endif

        @endforeach

        @if($initialFileCount > 1)
            <span style="font-size:10px; opacity: 0.7;"> +{{ $initialFileCount - 1 }}</span>
        @endif
                        </div>
                    @endif
                    
                    {{ \Str::limit($initialPinnedMessage->message, 80) ?: ($initialFileCount ? 'Attachment' : 'Message') }}
                @endif
            </span>
        </div>
        <button class="btn btn-sm btn-link" onclick="unpinMessage(event)">
            <i class="fa fa-times"></i>
        </button>
    </div>
</div>
    

        
    {{-- Chat Menu --}}
<div class="chat-menu" id="chatMenu">
    <div class="menu-item" onclick="reportUser()">
        <i class="fa fa-flag text-danger"></i>
        <span>Report</span>
    </div>
    <div class="menu-item" onclick="blockUser()">
        <i class="fa fa-ban text-danger"></i>
        <span>Block</span>
    </div>
    <div class="menu-item" onclick="archiveChat()">
        <i class="fa fa-archive text-warning"></i>
        <span>Archive</span>
    </div>
    <div class="menu-item" onclick="initiateCall('audio')">
        <i class="fa fa-phone text-success"></i>
        <span>Call</span>
    </div>
    <div class="menu-item" onclick="initiateCall('video')">
        <i class="fa fa-video text-primary"></i>
        <span>Video Call</span>
    </div>

    <div class="menu-item">
        <i class="fa fa-users text-primary"></i>
        <a href="{{ route('groups.index') }}" style="text-decoration:none; color: #333;">
    <span>Create Groups</span>
    </a>
    </div>

    <div class="menu-item">
        <i class="fa fa-users text-primary"></i>
        <a href="{{ route('groups.index') }}" style="text-decoration:none; color: #333;">
    <span>View Groups</span>
    </a>
    </div>

    <div class="menu-item">
        <i class="fas fa-check-circle" style="color: #1DA1F2;"></i>
        <a href="{{ url('/premium') }}" style="text-decoration:none; color: #333;">
    <span>Blue Badge Verify</span>
    </a>
    </div>
    
</div>




    {{-- Search Bar --}}
    <div class="chat-search-bar" id="chatSearchBar">
        <button class="search-nav-btn" onclick="closeSearchBar()"><i class="fa fa-arrow-left"></i></button>
        <input type="text" id="chatSearchInput" placeholder="Search..." oninput="searchMessages(this.value)">
        <span class="search-result-count" id="searchResultCount"></span>
        <button class="search-nav-btn" onclick="searchPrev()"><i class="fa fa-chevron-up"></i></button>
        <button class="search-nav-btn" onclick="searchNext()"><i class="fa fa-chevron-down"></i></button>
    </div>

    {{-- Messages Container --}}
    <div class="chat-messages" id="messagesContainer" style="position:relative;">
        @php $lastDateShown = null; @endphp
        @forelse($messages as $message)
            @php
                $messageDate = $message->created_at->format('Y-m-d');
                $showDateSep = ($lastDateShown !== $messageDate);
                $lastDateShown = $messageDate;
            @endphp
            @if($showDateSep)
                <div class="date-separator">
                    <span class="date-separator-chip">
                        @if($message->created_at->isToday())
                            TODAY
                        @elseif($message->created_at->isYesterday())
                            YESTERDAY
                        @else
                            {{ $message->created_at->format('M d, Y') }}
                        @endif
                    </span>
                </div>
            @endif
            <div class="d-flex {{ $message->sender_id == $user->id ? 'justify-content-end' : 'justify-content-start' }}">
                <div class="message-bubble {{ $message->sender_id == $user->id ? 'sent' : 'received' }}"
                     data-message-id="{{ $message->id }}"
                     data-message="{{ $message->message }}"
                     data-sender="{{ $message->sender_id }}"
                     onclick="handleMessageClick(event, this)"
                     oncontextmenu="showMessageActions(event, this); return false;">

                     {{-- Forwarded Label --}}
                    @if($message->is_forwarded)
                    <div class="forwarded-label">
                        <svg width="12" height="12" viewBox="0 0 16 16" fill="#667781"><path d="M8.3 2.3L14 8l-5.7 5.7-.6-.7L12.2 8.5H2V7.5h10.2L7.7 3l.6-.7z"/></svg>
                        <em>Forwarded</em>
                    </div>
                    @endif
                    
                    {{-- Reply Reference --}}
@if($message->reply_to_id && $message->replyTo)
    @php
        // Get the replied-to message object
        $repliedToMessage = $message->replyTo;
        $repliedToFiles = $repliedToMessage->file_path ? json_decode($repliedToMessage->file_path, true) : null;
        $fileCount = $repliedToFiles ? count($repliedToFiles) : 0;
        $displayText = \Str::limit($repliedToMessage->message, 30);
    @endphp

    {{-- 🎯 ADD onclick HANDLER HERE --}}
    <div class="reply-reference" 
         onclick="highlightMessage('{{ $repliedToMessage->id }}')" 
         style="cursor: pointer;">
        <div class="d-flex align-items-center">
            <i class="fa fa-reply me-1"></i>
            
            {{-- Display File Thumbnails if available --}}
            @if($repliedToFiles)
                <div class="d-flex align-items-center me-2">
                    @foreach(array_slice($repliedToFiles, 0, 1) as $file) {{-- Show only the first file thumbnail --}}
                        @php
                            $isImage = Str::contains($file, ['.jpg', '.jpeg', '.png', '.gif', '.webp']);
                            $isVideo = Str::contains($file, ['.mp4', '.webm', '.ogg']);
                        @endphp
                        
                        @if($isImage)
                            <img src="{{ $file }}" class="ml-2" style="width: 20px; height: 20px; object-fit: cover; border-radius: 2px;">
                        @elseif($isVideo)
                            <div class="ml-2" style="width: 20px; height: 20px; background-color: #3498db; color: white; border-radius: 2px; display: inline-flex; align-items: center; justify-content: center; font-size: 10px;">
                                <i class="fa fa-video "></i>
                            </div>
                        @else
                            <div style="width: 20px; height: 20px; background-color: #f39c12; color: white; border-radius: 2px; display: inline-flex; align-items: center; justify-content: center; font-size: 10px;">
                                <i class="fa fa-file"></i>
                            </div>
                        @endif
                    @endforeach

                    {{-- Show file count if more than 1 file --}}
                    @if($fileCount > 1)
                        <small class="ms-1 ml-2" style="opacity: 0.7;">+{{ $fileCount - 1 }}</small>
                    @endif
                </div>
            @endif

            {{-- Display Message Text (or placeholder if file-only) --}}
            <small style="opacity: 0.8;" class="ml-2">
                {{ $displayText ?: ($fileCount ? 'Attachment' : 'Message') }}
            </small>
        </div>
    </div>
@endif


                    
                    {{-- Message Content --}}           
<div class="message-content">
    {{ $message->message }}
    @if($message->is_edited)
        <small style="opacity:0.6;"> (edited)</small>
    @endif
</div>

{{-- ✅ NEW: Link Preview Display --}}
@if($message->link_preview)
    @php
        $linkData = json_decode($message->link_preview, true);
    @endphp
    <div class="message-link-preview">
        <div class="link-preview-card">
            @if(!empty($linkData['image']))
                <div class="link-preview-image">
                    <img src="{{ $linkData['image'] }}" alt="{{ $linkData['title'] }}">
                </div>
            @endif
            <div class="link-preview-content">
                <div class="link-preview-site">
                    <img src="{{ $linkData['favicon'] }}" class="link-preview-favicon">
                    <span>{{ $linkData['site_name'] }}</span>
                </div>
                <h4 class="link-preview-title">{{ $linkData['title'] }}</h4>
                @if(!empty($linkData['description']))
                    <p class="link-preview-description">{{ $linkData['description'] }}</p>
                @endif
                <a href="{{ $linkData['url'] }}" class="link-preview-url" target="_blank" rel="noopener">
                    <i class="fas fa-external-link-alt"></i> {{ Str::limit($linkData['url'], 50) }}
                </a>
            </div>
        </div>
    </div>
@endif

{{-- Then continue with your file attachments section --}}
                    
                    {{-- File Attachments --}}
{{-- File Attachments --}}
@if($message->file_path)
    @php
        // Decode the files and get the total count
        $files = json_decode($message->file_path, true);
        $fileCount = count($files);
        
        // Safety check for valid data
        if (!is_array($files) || $fileCount === 0) {
            $files = [];
            $fileCount = 0;
        }

        // Create a basic caption based on message content and sender
        $caption = trim($message->message) ?: 'Sent attachment';
        $senderName = $message->sender_id == $user->id ? 'You' : $message->sender->username;
        $fullCaption = "{$caption} (From: {$senderName})";
        
        // Determine the number of files to hide
        $hiddenFileCount = $fileCount > 0 ? $fileCount - 1 : 0;
        
        // Get the first file for display
        $firstFile = $fileCount > 0 ? $files[0] : null;

    @endphp
    
    @if($fileCount > 0)
        <div class="mt-2 attachment-grid d-flex align-items-end">
            
            {{-- 🎯 Display ONLY THE FIRST FILE --}}
            @if($firstFile)
                @php
                    // Note: Your original file check uses Str::contains on the raw string $file. 
                    // This assumes $files is an array of strings (paths).
                    $isImage = Str::contains($firstFile, ['.jpg', '.jpeg', '.png', '.gif', '.webp']);
                    $isVideo = Str::contains($firstFile, ['.mp4', '.webm', '.ogg']);
                    $isPdf = Str::contains($firstFile, '.pdf');
                @endphp

                @if($isImage)
                    {{-- FancyBox Link for IMAGES --}}
                    <a data-fancybox="chat-{{ $message->id }}" 
                        href="{{ $firstFile }}" 
                        data-caption="{{ $fullCaption }}"
                        class="d-inline-block me-1 mb-1 position-relative">
                        <img src="{{ $firstFile }}" 
                            class="img-fluid rounded attachment-thumb" 
                            alt="Attached Image" 
                            style="max-width:200px; max-height: 150px; object-fit: cover;">
                    </a>
                @elseif($isVideo)
                    {{-- FancyBox Link for VIDEOS --}}
                    <a data-fancybox="chat-{{ $message->id }}" 
                        href="{{ $firstFile }}" 
                        data-type="video" 
                        data-caption="{{ $fullCaption }}"
                        class="d-inline-block me-1 mb-1">
                        <div class="video-thumb-wrapper" style="position:relative; max-width:200px; height:150px; overflow:hidden;">
                            <video class="rounded" style="width: 100%; height: 100%; object-fit: cover;" preload="metadata">
                                <source src="{{ $firstFile }}#t=0.1">
                            </video>
                            <span class="play-icon" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-size: 30px; background: rgba(0,0,0,0.5); border-radius: 50%; width: 40px; height: 40px; text-align: center; line-height: 40px;">&#9658;</span>
                        </div>
                    </a>
                @elseif($isPdf)
                    {{-- Keep PDF link as standard --}}
                    <a href="{{ $firstFile }}" target="_blank" class="btn btn-sm btn-light mt-1 me-1">
                        <i class="fa fa-file-pdf text-danger"></i> View PDF
                    </a>
                @else
                    {{-- Keep other file links as standard --}}
                    <a href="{{ $firstFile }}" target="_blank" class="btn btn-sm btn-light mt-1 me-1">
                        <i class="fa fa-file"></i> Download File
                    </a>
                @endif
            @endif 
            {{-- End firstFile display --}}

            {{-- 2. 🎯 NEW: Hidden Links for Remaining Files --}}
        {{-- These links MUST use the same data-fancybox group ID to be included in the gallery --}}
        @if($fileCount > 1)
            @foreach(array_slice($files, 1) as $remainingFile)
                @php
                    $isRemainingVideo = Str::contains($remainingFile, ['.mp4', '.webm', '.ogg']);
                @endphp
                {{-- This link is hidden but is picked up by FancyBox --}}
                <a data-fancybox="chat-{{ $message->id }}" 
                   href="{{ $remainingFile }}" 
                   data-caption="{{ $fullCaption }}"
                   class="d-none" {{-- 🛑 The key change: Hide the element --}}
                   {{ $isRemainingVideo ? 'data-type="video"' : '' }}>
                </a>
            @endforeach

            {{-- 3. Display the clickable count badge --}}
            {{-- We make the badge clickable to trigger FancyBox --}}
            <span class="ms-2" 
                  onclick="document.querySelector('[data-fancybox=&quot;chat-{{ $message->id }}&quot;]').click();"
                  style="opacity: 0.7; font-size: 0.9rem; font-weight: 500; cursor: pointer;">
                +{{ $fileCount - 1 }} more file{{ $fileCount - 1 > 1 ? 's' : '' }}
            </span>
            @endif
            {{-- Note: For a proper multi-file viewer, you would modify the FancyBox data-fancybox 
            group for the first item to also contain the remaining $hiddenFileCount files. --}}
        </div>

        {{-- 2. Compact Attachment Preview (Hidden/Small for Reply/Pin Copying) --}}
        <div class="reply-attachment-preview d-none">
            @foreach($files as $file)
                @php
                    $isImage = Str::contains($file, ['.jpg', '.jpeg', '.png', '.gif', '.webp']);
                    $isVideo = Str::contains($file, ['.mp4', '.webm', '.ogg']);
                @endphp

                @if($isImage)
                    <img src="{{ $file }}" class="reply-thumb" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; margin-right: 5px;" alt="Image thumbnail">
                @elseif($isVideo)
                    <div class="reply-thumb video-icon" style="width: 40px; height: 40px; background-color: #3498db; color: white; border-radius: 4px; display: inline-flex; align-items: center; justify-content: center; margin-right: 5px;">
                        <i class="fa fa-video"></i>
                    </div>
                @else
                    <div class="reply-thumb file-icon" style="width: 40px; height: 40px; background-color: #f39c12; color: white; border-radius: 4px; display: inline-flex; align-items: center; justify-content: center; margin-right: 5px;">
                        <i class="fa fa-file"></i>
                    </div>
                @endif
            @endforeach
            {{-- Optional: Add a text indicator for files if the message has both text and files --}}
            @if($fileCount > 1)
                <span style="font-size:10px; opacity: 0.7;"> +{{ $fileCount - 1 }}</span>
            @endif
        </div>
    @endif
@endif
    
{{-- Voice Note Display --}}
{{-- Voice Note Display --}}
@if($message->voice_note)
    @php
        $duration = $message->voice_duration ?? 0;
        $minutes = floor($duration / 60);
        $seconds = $duration % 60;
        $durationText = sprintf('%d:%02d', $minutes, $seconds);
        $isDeleted = $message->is_deleted_by_sender || $message->message === '🚫 This message was deleted';
    @endphp
    
    @if($isDeleted)
        {{-- Show deleted message instead of voice note --}}
        <div class="message-content text-muted">
            <i class="fa fa-ban"></i> This voice message was deleted
        </div>
    @else
        <div class="voice-note-bubble {{ $message->sender_id == $user->id ? 'sent' : 'received' }}"
             onclick="playVoiceNote(this, '{{ $message->voice_note }}')"
             data-message-id="{{ $message->id }}"
             data-is-deleted="false">
            <div class="voice-note-controls">
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
            <button class="voice-transcribe-btn" onclick="event.stopPropagation(); transcribeVoiceNote(this, '{{ $message->voice_note }}', {{ $message->id }})" title="Transcribe voice note">
                <i class="fa-solid fa-language"></i> Transcribe
            </button>
        </div>
        <div class="voice-transcript" id="transcript-{{ $message->id }}"></div>
    @endif
@endif
                    
                    <div class="message-time">
                        {{ $message->created_at->format('g:i A') }}
                        @if($message->sender_id == $user->id)
                            <span class="message-status">
                                @if($message->status == 'read')
                                    <span class="tick-read"><svg viewBox="0 0 16 11" width="16" height="11"><path d="M11.071.653a.457.457 0 0 0-.304-.102.493.493 0 0 0-.381.178l-6.19 7.636-2.011-2.175a.463.463 0 0 0-.336-.153.457.457 0 0 0-.344.146.441.441 0 0 0-.101.317c0 .12.045.229.134.327l2.39 2.588a.459.459 0 0 0 .312.153.484.484 0 0 0 .384-.187L11.1 1.27a.469.469 0 0 0 .109-.293.441.441 0 0 0-.138-.324z" fill="#53BDEB"/><path d="M14.871.653a.457.457 0 0 0-.304-.102.493.493 0 0 0-.381.178l-6.19 7.636-1.2-1.3-.66.756 1.48 1.603a.459.459 0 0 0 .312.153.484.484 0 0 0 .384-.187L14.9 1.27a.469.469 0 0 0 .109-.293.441.441 0 0 0-.138-.324z" fill="#53BDEB"/></svg></span>
                                @elseif($message->status == 'delivered')
                                    <span class="tick-delivered"><svg viewBox="0 0 16 11" width="16" height="11"><path d="M11.071.653a.457.457 0 0 0-.304-.102.493.493 0 0 0-.381.178l-6.19 7.636-2.011-2.175a.463.463 0 0 0-.336-.153.457.457 0 0 0-.344.146.441.441 0 0 0-.101.317c0 .12.045.229.134.327l2.39 2.588a.459.459 0 0 0 .312.153.484.484 0 0 0 .384-.187L11.1 1.27a.469.469 0 0 0 .109-.293.441.441 0 0 0-.138-.324z" fill="#667781"/><path d="M14.871.653a.457.457 0 0 0-.304-.102.493.493 0 0 0-.381.178l-6.19 7.636-1.2-1.3-.66.756 1.48 1.603a.459.459 0 0 0 .312.153.484.484 0 0 0 .384-.187L14.9 1.27a.469.469 0 0 0 .109-.293.441.441 0 0 0-.138-.324z" fill="#667781"/></svg></span>
                                @else
                                    <span class="tick-sent"><svg viewBox="0 0 12 11" width="12" height="11"><path d="M11.071.653a.457.457 0 0 0-.304-.102.493.493 0 0 0-.381.178l-6.19 7.636-2.011-2.175a.463.463 0 0 0-.336-.153.457.457 0 0 0-.344.146.441.441 0 0 0-.101.317c0 .12.045.229.134.327l2.39 2.588a.459.459 0 0 0 .312.153.484.484 0 0 0 .384-.187L11.1 1.27a.469.469 0 0 0 .109-.293.441.441 0 0 0-.138-.324z" fill="#667781"/></svg></span>
                                @endif
                            </span>
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
        @empty
            <div class="text-center text-muted mt-5">
                <i class="fa fa-comments fa-3x mb-3" style="opacity:0.3;"></i>
                <p>No messages yet. Start the conversation!</p>
            </div>
        @endforelse
        
        {{-- Typing Indicator --}}
        <div class="typing-indicator" id="typingIndicatorBubble">
            <div class="message-bubble received">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
        </div>

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

    {{-- Upload Progress --}}
    <div class="upload-progress" id="uploadProgress" style="display:none;">
        <div class="progress">
            <div class="progress-bar" id="progressBar" role="progressbar" style="width: 0%"></div>
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

        {{-- Send Button (shown when typing) --}}
        <button type="submit" 
                class="input-btn btn-send hide-on-recording" 
                id="sendBtn" 
                style="display:none;">
            <i class="fa fa-paper-plane"></i>
        </button>
    </form>
</div>

{{-- Scroll to Bottom Button --}}
<button class="scroll-to-bottom-btn" id="scrollToBottomBtn" onclick="scrollToBottom()">
    <i class="fa fa-chevron-down"></i>
    <span class="unread-badge" id="unreadBadge" style="display:none;">0</span>
</button>

{{-- Modern Popup Modal --}}
<div class="custom-popup-overlay" id="customPopup">
    <div class="custom-popup">
        <div class="custom-popup-icon" id="popupIcon"><i class="fa-solid fa-circle-info"></i></div>
        <div class="custom-popup-message" id="popupMessage"></div>
        <button class="custom-popup-btn" onclick="closePopup()">OK</button>
    </div>
</div>

{{-- Call Overlay --}}
<div id="callOverlay" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; z-index:5000; background:linear-gradient(180deg, #0B3D35 0%, #0A1E1A 100%); flex-direction:column; align-items:center; justify-content:space-between; padding:40px 20px;">
    {{-- Caller info --}}
    <div style="text-align:center; margin-top:30px;">
        <div id="callOverlayAvatar" style="width:100px; height:100px; border-radius:50%; border:3px solid #00A884; margin:0 auto 15px; overflow:hidden; animation:callPulseRing 2s infinite;">
            <img src="{{ $friend->profileimg ?? asset('images/best3.png') }}" style="width:100%; height:100%; object-fit:cover;">
        </div>
        <h2 id="callOverlayName" style="color:white; margin:0 0 8px; font-size:24px;">{{ $friend->name }}</h2>
        <p id="callOverlayStatus" style="color:#8696A0; margin:0; font-size:16px;">Calling...</p>
        <p id="callOverlayTimer" style="color:white; margin:8px 0 0; font-size:24px; font-weight:bold; display:none;">00:00</p>
    </div>

    {{-- Video container --}}
    <div id="callVideoContainer" style="display:none; flex:1; width:100%; position:relative; margin:20px 0; border-radius:12px; overflow:hidden; background:#000;">
        <div id="callRemoteVideo" style="width:100%; height:100%;"></div>
        <div id="callLocalVideo" style="position:absolute; bottom:16px; right:16px; width:120px; height:160px; border-radius:10px; border:2px solid white; overflow:hidden; z-index:10; cursor:move;"></div>
    </div>

    {{-- Incoming call buttons --}}
    <div id="callIncomingButtons" style="display:none; gap:40px; margin-bottom:40px;">
        <button onclick="ChatCallApp.decline()" style="width:65px; height:65px; border-radius:50%; background:#f44336; border:none; color:white; font-size:24px; cursor:pointer;"><i class="fa fa-phone-slash"></i></button>
        <button onclick="ChatCallApp.accept()" style="width:65px; height:65px; border-radius:50%; background:#4CAF50; border:none; color:white; font-size:24px; cursor:pointer;"><i class="fa fa-phone"></i></button>
    </div>

    {{-- Active call buttons --}}
    <div id="callActiveButtons" style="display:flex; gap:20px; margin-bottom:40px;">
        <button id="callMuteBtn" onclick="ChatCallApp.toggleMute()" style="width:55px; height:55px; border-radius:50%; background:rgba(255,255,255,0.2); border:none; color:white; font-size:20px; cursor:pointer;" title="Mute"><i class="fa fa-microphone"></i></button>
        <button id="callVideoToggleBtn" onclick="ChatCallApp.toggleVideo()" style="width:55px; height:55px; border-radius:50%; background:rgba(255,255,255,0.2); border:none; color:white; font-size:20px; cursor:pointer; display:none;" title="Video"><i class="fa fa-video"></i></button>
        <button onclick="ChatCallApp.endCall()" style="width:65px; height:65px; border-radius:50%; background:#f44336; border:none; color:white; font-size:24px; cursor:pointer;" title="End Call"><i class="fa fa-phone-slash"></i></button>
        <button id="callSpeakerBtn" onclick="ChatCallApp.toggleSpeaker()" style="width:55px; height:55px; border-radius:50%; background:rgba(255,255,255,0.2); border:none; color:white; font-size:20px; cursor:pointer;" title="Speaker"><i class="fa fa-volume-up"></i></button>
        <button onclick="ChatCallApp.minimize()" style="width:55px; height:55px; border-radius:50%; background:rgba(255,255,255,0.2); border:none; color:white; font-size:20px; cursor:pointer;" title="Minimize"><i class="fa fa-compress"></i></button>
    </div>
</div>

{{-- Minimized Call Bar --}}
<div id="callMinimizedBar" style="display:none; position:fixed; top:0; left:0; right:0; z-index:4999; background:#0EA5E9; color:white; padding:10px 16px; align-items:center; justify-content:space-between;">
    <div style="display:flex; align-items:center; gap:10px; cursor:pointer;" onclick="ChatCallApp.maximize()">
        <i class="fa fa-phone" style="animation:callPulseRing 2s infinite;"></i>
        <span id="callBarTimer">00:00</span>
        <span id="callBarName">{{ $friend->name }}</span>
    </div>
    <button onclick="ChatCallApp.endCall()" style="background:#f44336; color:white; border:none; border-radius:20px; padding:5px 16px; cursor:pointer; font-weight:bold;">End</button>
</div>

<style>
@keyframes callPulseRing {
    0%, 100% { box-shadow: 0 0 0 0 rgba(0, 168, 132, 0.4); }
    50% { box-shadow: 0 0 0 15px rgba(0, 168, 132, 0); }
}
</style>

{{-- Camera Modal --}}
<div class="camera-modal" id="cameraModal">
    {{-- Mode Toggle Button --}}
    <button class="camera-mode-toggle" id="cameraModeToggle" onclick="toggleCameraMode()">
        📷 Photo Mode
    </button>
    
    {{-- Recording Indicator --}}
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


</div>






{{-- Message Actions Menu --}}
<div class="message-actions" id="messageActions">
    <div class="message-action-item" onclick="replyToMessage()">
        <i class="fa fa-reply"></i>
        <span>Reply</span>
    </div>
    <div class="message-action-item" onclick="forwardMessage()">
        <i class="fa fa-share"></i>
        <span>Forward</span>
    </div>
    <div class="message-action-item" onclick="copyMessage()">
        <i class="fa fa-copy"></i>
        <span>Copy</span>
    </div>
    <div class="message-action-item" onclick="pinMessage()">
        <i class="fa fa-thumbtack"></i>
        <span>Pin</span>
    </div>
    <div class="message-action-item" id="editAction" onclick="editMessage()" style="display:none;">
        <i class="fa fa-edit"></i>
        <span>Edit</span>
    </div>
     <div class="message-action-item" onclick="showReactionPicker()">
    <!-- <div class="message-action-item" onclick="reactToMessage()"> -->
        <i class="fa fa-smile"></i>
        <span>React</span>
    </div>
    <div class="message-action-item text-danger" onclick="deleteMessage()">
        <i class="fa fa-trash"></i>
        <span>Delete</span>
    </div>
</div>

{{-- Emoji Picker --}}
<div class="emoji-picker" id="emojiPicker">
    @foreach(['❤️', '😀','😂', '❤️', '👍', '🎉', '😊', '😍', '🔥', '👏', '✅', '💯', '🙏', '😎', '🤔', '😢', '😡', '👎',
        '😭', '🤯', '🤩', '🥳', '🥺', '😇', '🤫', '😶', '😬', '😵', '😪', '🤑', '🤭', '🤐', '🤢',
        '🙌', '🤘', '🤙', '✌️', '👌', '✍️', '💅', '👀', '🧠', '👑', '💫', '💥', '✨', '⚡️', '🌈',
        '🐶', '🐱', '🐭', '🐻', '🐼', '🦊', '🐨', '🦁', '🙈', '🙊', '🙉',
        '🍎', '🍕', '🍔', '🍟', '🍺', '☕️', '🎂', '🎁', '🎈', '🏈', '⚽️', '🏀', '🏎️', '✈️', '🏠',
        '⏰', '💡', '🎤', '💻', '📱', '💰', '🔑', '🔗', '🔒', '🔎', '🗑️', '🔔', '📌', '📈', '📉', '😍',
        '😎','🎉', '😊',] as $emoji)
        <span class="emoji-item" onclick="insertEmoji('{{ $emoji }}')">{{ $emoji }}</span>
    @endforeach
</div>

{{-- Reaction Picker --}}
<div class="emoji-picker" id="reactionPicker">
    @foreach([
        // --- Popular Emojis (Your originals + many more) ---
        '❤️', '😀','😂', '❤️', '👍', '🎉', '😊', '😍', '🔥', '👏', '✅', '💯', '🙏', '😎', '🤔', '😢', '😡', '👎',
        '😭', '🤯', '🤩', '🥳', '🥺', '😇', '🤫', '😶', '😬', '😵', '😪', '🤑', '🤭', '🤐', '🤢',
        '🙌', '🤘', '🤙', '✌️', '👌', '✍️', '💅', '👀', '🧠', '👑', '💫', '💥', '✨', '⚡️', '🌈',
        '🐶', '🐱', '🐭', '🐻', '🐼', '🦊', '🐨', '🦁', '🙈', '🙊', '🙉',
        '🍎', '🍕', '🍔', '🍟', '🍺', '☕️', '🎂', '🎁', '🎈', '🏈', '⚽️', '🏀', '🏎️', '✈️', '🏠',
        '⏰', '💡', '🎤', '💻', '📱', '💰', '🔑', '🔗', '🔒', '🔎', '🗑️', '🔔', '📌', '📈', '📉', '😍',
        '😎','🎉', '😊',] as $emoji)

        <span class="emoji-item" onclick="sendReaction('{{ $emoji }}')">{{ $emoji }}</span>
    @endforeach
</div>

{{-- Forward Modal --}}
<div class="forward-modal" id="forwardModal">
    <div class="forward-modal-content">
        <h5>Forward Message To:</h5>
        <div id="friendsList"></div>
        <div class="mt-3">
            <button class="btn btn-secondary" onclick="closeForwardModal()">Cancel</button>
            <button class="btn btn-primary" onclick="sendForward()">Send</button>
        </div>
    </div>
</div>


<!-- Place this AFTER closing the message container div and BEFORE the  -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// -----------------------------------------------------------------
// 🎯 SECTION 1: CORE VARIABLES AND ALL YOUR CHAT FUNCTIONS
// -----------------------------------------------------------------

const friendId = {{ $friend->id }};
const userId = {{ $user->id }};
window._inPrivateChat = friendId;
let lastMessageId = {{ $messages->last()->id ?? 0 }};
let typingTimeout;
let selectedMessage = null;
let replyingToId = null;
let selectedFiles = [];
let touchTimer;
let selectedForwardFriends = [];
let isEditing = false;
let editingMessageId = null;
let currentReactingMessageId = null;

// ========================================
// VOICE NOTE & CAMERA FUNCTIONALITY
// ========================================

// Voice Recording Variables
let mediaRecorder;
let audioChunks = [];
let recordingStartTime;
let recordingInterval;
let isRecording = false;
let currentAudio = null;

// Modern Popup Functions
function showPopup(message, type = 'info') {
    const overlay = document.getElementById('customPopup');
    const msgEl = document.getElementById('popupMessage');
    const iconEl = document.getElementById('popupIcon');

    msgEl.textContent = message;

    const icons = {
        success: '<i class="fa-solid fa-circle-check"></i>',
        error: '<i class="fa-solid fa-circle-xmark"></i>',
        warning: '<i class="fa-solid fa-triangle-exclamation"></i>',
        info: '<i class="fa-solid fa-circle-info"></i>'
    };

    iconEl.innerHTML = icons[type] || icons.info;
    iconEl.className = 'custom-popup-icon ' + type;

    overlay.classList.add('show');
}

function closePopup() {
    document.getElementById('customPopup').classList.remove('show');
}

// Camera Variables
let cameraStream = null;
let currentFacingMode = 'user'; // 'user' or 'environment'
let isRecordingVideo = false;
let videoRecorder = null;
let videoChunks = [];
let cameraMode = 'photo'; // 'photo' or 'video'
let videoRecordingStartTime = null;
let videoRecordingInterval = null;

// ========================================
// LINK PREVIEW FUNCTIONALITY
// ========================================

let messageLinkPreviewData = null;
let messageUrlDetectionTimeout = null;


// Auto-resize textarea
document.getElementById('messageInput').addEventListener('input', function() {

    const sendBtn = document.getElementById('sendBtn');
    const voiceBtn = document.getElementById('voiceBtn');
    
    if (this.value.trim().length > 0) {
        sendBtn.style.display = 'flex';
        voiceBtn.style.display = 'none';
    } else {
        sendBtn.style.display = 'none';
        voiceBtn.style.display = 'flex';
    }

    // Auto-resize
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
    
    // Update typing indicator
    clearTimeout(typingTimeout);
    
    fetch(`/messages/typing/${friendId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });
    
    typingTimeout = setTimeout(() => {
        // Stop typing
    }, 3000);
});

// Scroll to bottom
function scrollToBottom() {
    const container = document.getElementById('messagesContainer');
    container.scrollTop = container.scrollHeight;
}

scrollToBottom();

// ========================================
// VOICE RECORDING FUNCTIONS
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
        
        // Update UI
        document.getElementById('voiceRecordingContainer').classList.add('show');
        document.getElementById('voiceBtn').classList.add('recording');
        document.getElementById('voiceIcon').className = 'fa fa-stop';
        document.querySelectorAll('.hide-on-recording').forEach(el => el.classList.add('hidden'));
        
        // Start timer
        recordingStartTime = Date.now();
        recordingInterval = setInterval(updateRecordingTimer, 100);
        
    } catch (error) {
        console.error('Error accessing microphone:', error);
        showPopup('Unable to access microphone. Please grant permission.', 'error');
    }
}

function updateRecordingTimer() {
    const elapsed = Math.floor((Date.now() - recordingStartTime) / 1000);
    const minutes = Math.floor(elapsed / 60);
    const seconds = elapsed % 60;
    document.getElementById('recordingTimer').textContent = 
        `${minutes}:${seconds.toString().padStart(2, '0')}`;
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
        
        // Send to backend
        const messageFormData = new FormData();
        messageFormData.append('voice_note', voiceUrl);
        messageFormData.append('voice_duration', duration);
        messageFormData.append('message', '🎤 Voice message');
        
        const sendResponse = await fetch(`/messages/send/${friendId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: messageFormData
        });
        
        const sendData = await sendResponse.json();
        
        if (sendData.success) {
            addVoiceNoteToUI(sendData.message);
            resetVoiceUI();
            scrollToBottom();
            playSendBeep();
        }
        
    } catch (error) {
        console.error('Error uploading voice note:', error);
        showPopup('Failed to upload voice note', 'error');
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

function addVoiceNoteToUI(message) {
    const container = document.getElementById('messagesContainer');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'd-flex justify-content-end';
    
    const duration = message.voice_duration || 0;
    const minutes = Math.floor(duration / 60);
    const seconds = duration % 60;
    const durationText = `${minutes}:${seconds.toString().padStart(2, '0')}`;
    
    messageDiv.innerHTML = `
        <div class="voice-note-bubble sent"
             onclick="playVoiceNote(this, '${message.voice_note}')"
             data-message-id="${message.id}"
             data-is-deleted="false">
            <div class="voice-note-controls">
                <button class="voice-play-btn">
                    <i class="fa fa-play"></i>
                </button>
                <div class="voice-waveform">
                    ${generateWaveformBars()}
                </div>
                <span class="voice-duration">${durationText}</span>
            </div>
            <button class="voice-transcribe-btn" onclick="event.stopPropagation(); transcribeVoiceNote(this, '${message.voice_note}', ${message.id})" title="Transcribe voice note">
                <i class="fa-solid fa-language"></i> Transcribe
            </button>
        </div>
        <div class="voice-transcript" id="transcript-${message.id}"></div>
    `;
    
    container.appendChild(messageDiv);
    lastMessageId = message.id;
    
    // Reset buttons
    const input = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');
    const voiceBtn = document.getElementById('voiceBtn');
    
    input.value = '';
    input.style.height = 'auto';
    sendBtn.style.display = 'none';
    voiceBtn.style.display = 'flex';
}

function generateWaveformBars() {
    const heights = [15, 22, 18, 25, 12, 20, 16, 23];
    return heights.map(h => `<div class="voice-wave-bar" style="height: ${h}px;"></div>`).join('');
}

function playVoiceNote(element, audioUrl) {
    // Check if message is deleted
    const isDeleted = element.getAttribute('data-is-deleted') === 'true';
    if (isDeleted) {
        showPopup('This voice message has been deleted', 'warning');
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
    
    currentAudio.play().catch(error => {
        console.error('Error playing audio:', error);
        showPopup('Failed to play voice message', 'error');
        playBtn.className = 'fa fa-play';
        clearInterval(waveInterval);
        waveBars.forEach(bar => bar.classList.remove('active'));
    });
    
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
        // Close existing stream first
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
        
        // Only request audio for video mode
        if (cameraMode === 'video') {
            constraints.audio = true;
        }
        
        cameraStream = await navigator.mediaDevices.getUserMedia(constraints);
        
        const video = document.getElementById('cameraPreview');
        video.srcObject = cameraStream;
        
        // Wait for video to be ready
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
        if (error.name === 'NotAllowedError') {
            showPopup('Camera/microphone permission denied. Please allow access in your browser settings.', 'error');
        } else if (error.name === 'NotFoundError') {
            showPopup('No camera found on this device.', 'error');
        } else {
            showPopup('Unable to access camera: ' + error.message, 'error');
        }
    }
}


function closeCamera() {
    // Stop any ongoing video recording
    if (isRecordingVideo) {
        stopVideoRecording(false); // false = don't send
    }
    
    if (cameraStream) {
        cameraStream.getTracks().forEach(track => track.stop());
        cameraStream = null;
    }
    
    document.getElementById('cameraModal').classList.remove('show');
    document.getElementById('videoRecIndicator').classList.remove('show');
    
    // Reset to photo mode
    cameraMode = 'photo';
    isRecordingVideo = false;
}

async function switchCamera() {
    currentFacingMode = currentFacingMode === 'user' ? 'environment' : 'user';
    closeCamera();
    await openCamera();
}

async function toggleCameraMode() {
    if (isRecordingVideo) {
        showPopup('Stop recording first!', 'warning');
        return;
    }
    
    // Toggle mode
    cameraMode = cameraMode === 'photo' ? 'video' : 'photo';
    
    // Stop current stream
    if (cameraStream) {
        cameraStream.getTracks().forEach(track => track.stop());
        cameraStream = null;
    }
    
    // Update UI
    updateCameraModeUI();
    
    // Small delay before restarting
    await new Promise(resolve => setTimeout(resolve, 300));
    
    // Restart camera with new permissions
    try {
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
        
    } catch (error) {
        console.error('Error switching camera mode:', error);
        // Fallback to photo mode if video fails
        if (cameraMode === 'video') {
            showPopup('Could not access microphone for video mode. Switching back to photo mode.', 'warning');
            cameraMode = 'photo';
            updateCameraModeUI();
            
            // Try again without audio
            try {
                cameraStream = await navigator.mediaDevices.getUserMedia({
                    video: { 
                        facingMode: currentFacingMode,
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    }
                });
                const video = document.getElementById('cameraPreview');
                video.srcObject = cameraStream;
                await video.play();
            } catch (e) {
                showPopup('Unable to access camera.', 'error');
                closeCamera();
            }
        }
    }
}

function updateCameraModeUI() {
    const modeToggle = document.getElementById('cameraModeToggle');
    const captureBtn = document.getElementById('captureBtn');
    const captureBtnIcon = captureBtn.querySelector('i');
    
    if (cameraMode === 'photo') {
        modeToggle.innerHTML = '📷 Photo Mode';
        captureBtnIcon.className = 'fa fa-camera';
        captureBtn.classList.remove('video-mode');
    } else {
        modeToggle.innerHTML = '🎥 Video Mode';
        captureBtnIcon.className = 'fa fa-circle';
        captureBtn.classList.remove('video-mode');
    }
}

function handleCapture() {
    if (cameraMode === 'photo') {
        capturePhoto();
    } else {
        if (isRecordingVideo) {
            stopVideoRecording(true); // true = send video
        } else {
            startVideoRecording();
        }
    }
}

// ========================================
// PHOTO CAPTURE
// ========================================

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
            
            // Send to backend
            const messageFormData = new FormData();
            messageFormData.append('files[]', photoUrl);
            messageFormData.append('message', '📷 Photo');
            
            const sendResponse = await fetch(`/messages/send/${friendId}`, {
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
                playSendBeep();
                
                // Reset buttons
                document.getElementById('sendBtn').style.display = 'none';
                document.getElementById('voiceBtn').style.display = 'flex';
            }
            
        } catch (error) {
            console.error('Error uploading photo:', error);
            showPopup('Failed to upload photo', 'error');
        } finally {
            document.getElementById('uploadProgress').style.display = 'none';
        }
    }, 'image/jpeg', 0.9);
}

// ========================================
// VIDEO RECORDING
// ========================================

function startVideoRecording() {
    try {
        // Check if browser supports MediaRecorder
        if (!MediaRecorder.isTypeSupported('video/webm')) {
            showPopup('Video recording not supported on this browser', 'warning');
            return;
        }
        
        videoChunks = [];
        videoRecorder = new MediaRecorder(cameraStream, {
            mimeType: 'video/webm;codecs=vp8,opus',
            videoBitsPerSecond: 2500000 // 2.5 Mbps
        });
        
        videoRecorder.ondataavailable = (event) => {
            if (event.data && event.data.size > 0) {
                videoChunks.push(event.data);
            }
        };
        
        videoRecorder.start(1000); // Collect data every second
        isRecordingVideo = true;
        
        // Update UI
        document.getElementById('videoRecIndicator').classList.add('show');
        document.getElementById('captureBtn').classList.add('video-mode');
        document.getElementById('captureBtn').querySelector('i').className = 'fa fa-stop';
        
        // Start timer
        videoRecordingStartTime = Date.now();
        videoRecordingInterval = setInterval(updateVideoRecordingTimer, 100);
        
        console.log('Video recording started');
        
    } catch (error) {
        console.error('Error starting video recording:', error);
        showPopup('Failed to start video recording', 'error');
    }
}

function updateVideoRecordingTimer() {
    const elapsed = Math.floor((Date.now() - videoRecordingStartTime) / 1000);
    const minutes = Math.floor(elapsed / 60);
    const seconds = elapsed % 60;
    document.getElementById('videoRecTimer').textContent = 
        `${minutes}:${seconds.toString().padStart(2, '0')}`;
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
        
        // Upload with progress tracking
        const xhr = new XMLHttpRequest();
        
        xhr.upload.addEventListener('progress', (e) => {
            if (e.lengthComputable) {
                const percentComplete = (e.loaded / e.total) * 100;
                document.getElementById('progressBar').style.width = percentComplete + '%';
                document.getElementById('uploadStatus').textContent = 
                    `Uploading video... ${Math.round(percentComplete)}%`;
            }
        });
        
        xhr.onload = async () => {
            if (xhr.status === 200) {
                const data = JSON.parse(xhr.responseText);
                const videoUrl = data.secure_url;
                
                document.getElementById('uploadStatus').textContent = 'Sending video...';
                
                // Send to backend
                const messageFormData = new FormData();
                messageFormData.append('files[]', videoUrl);
                messageFormData.append('message', `🎥 Video (${Math.floor(duration / 60)}:${(duration % 60).toString().padStart(2, '0')})`);
                
                const sendResponse = await fetch(`/messages/send/${friendId}`, {
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
                    playSendBeep();
                    
                    // Reset buttons
                    document.getElementById('sendBtn').style.display = 'none';
                    document.getElementById('voiceBtn').style.display = 'flex';
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
        showPopup('Failed to upload video', 'error');
        document.getElementById('uploadProgress').style.display = 'none';
    }
}
// end of cameral

// Handle message click (for selection)
let clickTimeout;
function handleMessageClick(event, element) {
    if (event.type === 'click') {
        // Single click - do nothing or show timestamp
    }
}

// Touch handlers for long press
function handleTouchStart(event, element) {
    touchTimer = setTimeout(function() {
        showMessageActions(event, element);
    }, 500); // 500ms for long press
}

function handleTouchEnd(event) {
    clearTimeout(touchTimer);
}

// Show message actions menu
function showMessageActions(event, element) {
    event.preventDefault();
    
    selectedMessage = element;
    const messageId = element.getAttribute('data-message-id');
    const senderId = element.getAttribute('data-sender');
    
    const menu = document.getElementById('messageActions');
    const editAction = document.getElementById('editAction');
    
    // Show edit option only for sender
    if (senderId == userId) {
        editAction.style.display = 'flex';
    } else {
        editAction.style.display = 'none';
    }
    
    // Position menu - adjust for mobile
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

// Hide menu when clicking outside
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

// Toggle chat menu
function toggleChatMenu(event) {
    event.preventDefault();
    const menu = document.getElementById('chatMenu');
    menu.classList.toggle('show');
}

// Report user
function reportUser() {
    const reason = prompt('Why are you reporting this user?');
    if (reason) {
        fetch('/users/report', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                reported_user_id: friendId,
                reason: reason
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showPopup('User reported successfully. Our team will review this.', 'success');
            }
        });
    }
    document.getElementById('chatMenu').classList.remove('show');
}

// Block user
function blockUser() {
    if (confirm('Are you sure you want to block this user? You will no longer be able to message each other.')) {
        fetch('/users/block', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                blocked_user_id: friendId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showPopup('User blocked successfully.', 'success');
                window.location.href = '{{ route("messages.index") }}';
            }
        });
    }
    document.getElementById('chatMenu').classList.remove('show');
}

// Archive chat
function archiveChat() {
    if (confirm('Archive this conversation?')) {
        fetch('/messages/archive', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                friend_id: friendId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showPopup('Chat archived.', 'success');
                window.location.href = '{{ route("messages.index") }}';
            }
        });
    }
    document.getElementById('chatMenu').classList.remove('show');
}

// Initiate call — now uses overlay instead of navigating away
function initiateCall(type) {
    document.getElementById('chatMenu').classList.remove('show');
    ChatCallApp.start(type);
}

// Reply to message
function replyToMessage() {
    if (!selectedMessage) return;
    
    replyingToId = selectedMessage.getAttribute('data-message-id');
    const messageText = selectedMessage.getAttribute('data-message') || '';
    
    const filePreview = selectedMessage.querySelector('.reply-attachment-preview');
    const replyTextElement = document.getElementById('replyText');
    
    replyTextElement.innerHTML = ''; 
    
    if (filePreview) {
        replyTextElement.appendChild(filePreview.cloneNode(true));
        replyTextElement.querySelector('.reply-attachment-preview').classList.remove('d-none');
    }
    
    const displayMessage = messageText.trim() ? `: ${messageText.substring(0, 50)}${messageText.length > 50 ? '...' : ''}` : '';
    replyTextElement.innerHTML += displayMessage;
    
    document.getElementById('replyPreview').style.display = 'block';
    document.getElementById('messageInput').focus();
    
    hideMessageActions();
}

function cancelReply() {
    replyingToId = null;
    document.getElementById('replyPreview').style.display = 'none';
}

// Forward message
function forwardMessage() {
    if (!selectedMessage) return;
    
    fetch('/messages/friends')
        .then(response => response.json())
        .then(data => {
            const friendsList = document.getElementById('friendsList');
            friendsList.innerHTML = '';
            
            data.friends.forEach(friend => {
                const friendItem = document.createElement('div');
                friendItem.className = 'friend-item';
                friendItem.innerHTML = `
                    <input type="checkbox" id="friend_${friend.id}" value="${friend.id}" onchange="toggleFriend(${friend.id})">
                    <img src="${friend.profileimg || '{{ asset('images/best3.png') }}'}" 
                         style="width:30px;height:30px;border-radius:50%;margin:0 10px;">
                    <label for="friend_${friend.id}">${friend.name}</label>
                `;
                friendsList.appendChild(friendItem);
            });
            
            document.getElementById('forwardModal').classList.add('show');
        });
    
    hideMessageActions();
}

function toggleFriend(friendId) {
    const index = selectedForwardFriends.indexOf(friendId);
    if (index > -1) {
        selectedForwardFriends.splice(index, 1);
    } else {
        selectedForwardFriends.push(friendId);
    }
}

function closeForwardModal() {
    document.getElementById('forwardModal').classList.remove('show');
    selectedForwardFriends = [];
}

function sendForward() {
    if (selectedForwardFriends.length === 0) {
        showPopup('Please select at least one friend', 'warning');
        return;
    }

    const messageId = selectedMessage.getAttribute('data-message-id');

    fetch(`/messages/forward/${messageId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            friend_ids: selectedForwardFriends
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showPopup('Message forwarded successfully!', 'success');
            closeForwardModal();
        }
    });
}

// Copy message
function copyMessage() {
    if (!selectedMessage) return;
    const messageText = selectedMessage.getAttribute('data-message');
    navigator.clipboard.writeText(messageText);
    showPopup('Message copied!', 'success');
    hideMessageActions();
}

// Pin message
function pinMessage() {
    if (!selectedMessage) return;

    const messageId = selectedMessage.getAttribute('data-message-id');
    const messageText = selectedMessage.getAttribute('data-message') || '';
    const filePreviewHtml = selectedMessage.querySelector('.reply-attachment-preview') ? 
        selectedMessage.querySelector('.reply-attachment-preview').outerHTML.replace('d-none', '') : '';
    
    hideMessageActions(); 

    fetch(`/messages/pin/${friendId}/${messageId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const pinnedMessageDiv = document.getElementById('pinnedMessage');
            const pinnedMessageTextSpan = document.getElementById('pinnedMessageText');
            
            let displayContent = messageText.substring(0, 80) + (messageText.length > 80 ? '...' : '');
            if (!displayContent && filePreviewHtml) {
                displayContent = 'Attachment';
            }
            
            pinnedMessageTextSpan.innerHTML = filePreviewHtml + ' ' + displayContent;
            pinnedMessageDiv.setAttribute('data-pinned-id', messageId);
            pinnedMessageDiv.setAttribute('onclick', `highlightMessage('${messageId}')`);
            pinnedMessageDiv.classList.add('show');
            
            showPopup('Message pinned successfully!', 'success');
        } else {
            showPopup('Failed to pin message: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error pinning message:', error);
        showPopup('An error occurred while pinning the message.', 'error');
    });
}

// Unpin message
function unpinMessage(event) {
    if (event) event.stopPropagation(); 
    
    const pinnedMessageDiv = document.getElementById('pinnedMessage');
    const pinnedId = pinnedMessageDiv.getAttribute('data-pinned-id');
    
    fetch(`/messages/unpin/${friendId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            pinnedMessageDiv.classList.remove('show');
            pinnedMessageDiv.removeAttribute('data-pinned-id');
            pinnedMessageDiv.onclick = null; 
            document.getElementById('pinnedMessageText').innerHTML = '';

            const oldPinned = document.querySelector(`[data-message-id="${pinnedId}"]`);
            if (oldPinned) {
                oldPinned.classList.remove('pinned-highlight'); 
            }
        } else {
            console.error('Unpin failed:', data.message);
        }
    })
    .catch(error => {
        console.error('Error during unpin operation:', error);
    });
}

// Highlight message
function highlightMessage(messageId) {
    const container = document.getElementById('messagesContainer');
    const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
    
    if (messageElement) {
        document.querySelectorAll('.pinned-highlight').forEach(el => el.classList.remove('pinned-highlight'));
        messageElement.classList.add('pinned-highlight');
        messageElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        setTimeout(() => {
            messageElement.classList.remove('pinned-highlight');
        }, 3000);
    }
}

// Edit message
function editMessage() {
    if (!selectedMessage) return;
    isEditing = true;
    editingMessageId = selectedMessage.getAttribute('data-message-id');
    const messageText = selectedMessage.getAttribute('data-message');

    document.getElementById('messageInput').value = messageText;
    document.getElementById('messageInput').focus();
    document.getElementById('sendBtn').innerHTML = '<i class="fa fa-check"></i>';

    hideMessageActions();
}

// Show reaction picker
function showReactionPicker() {
    if (!selectedMessage) return;
    currentReactingMessageId = selectedMessage.getAttribute('data-message-id');
    const reactionPicker = document.getElementById('reactionPicker');
    reactionPicker.classList.add('show');
    hideMessageActions();
}

// Send reaction
function sendReaction(emoji) {
    if (!currentReactingMessageId) return;
    
    fetch(`/messages/react/${currentReactingMessageId}`, {
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

// Delete message
function deleteMessage() {
    if (!selectedMessage) return;
    if (confirm('Delete this message?')) {
        const messageId = selectedMessage.getAttribute('data-message-id');
        
        fetch(`/messages/delete/${messageId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Check if it's a voice note
                const voiceNote = selectedMessage.querySelector('.voice-note-bubble');
                if (voiceNote) {
                    // Mark voice note as deleted
                    voiceNote.setAttribute('data-is-deleted', 'true');
                    voiceNote.onclick = null;
                    voiceNote.style.opacity = '0.5';
                    voiceNote.innerHTML = '<i class="fa fa-ban"></i> <span style="margin-left:8px;">This voice message was deleted</span>';
                } else {
                    // Regular message
                    const messageContent = selectedMessage.querySelector('.message-content');
                    if (messageContent) {
                        messageContent.textContent = '🚫 This message was deleted';
                    }
                }
                selectedMessage.classList.add('text-muted');
            }
        })
        .catch(error => {
            console.error('Error deleting message:', error);
            showPopup('Failed to delete message', 'error');
        });
    }
    hideMessageActions();
}

function hideMessageActions() {
    document.getElementById('messageActions').classList.remove('show');
    if (selectedMessage) {
        selectedMessage.classList.remove('highlighted');
    }
}

// File handling
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
            video.style.maxWidth = '100px';
            video.style.maxHeight = '100px';
            filePreview.appendChild(video);
        } else if (file.type === 'application/pdf') {
            const pdfDiv = document.createElement('div');
            pdfDiv.className = 'p-2 bg-light rounded text-center';
            pdfDiv.innerHTML = `<i class="fa fa-file-pdf fa-2x text-danger"></i><br><small>${file.name}</small>`;
            filePreview.appendChild(pdfDiv);
        } else {
            const fileDiv = document.createElement('div');
            fileDiv.className = 'p-2 bg-light rounded text-center';
            fileDiv.innerHTML = `<i class="fa fa-file fa-2x"></i><br><small>${file.name}</small>`;
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

// Emoji picker
function toggleEmojiPicker() {
    const picker = document.getElementById('emojiPicker');
    picker.classList.toggle('show');
}

function insertEmoji(emoji) {
    const input = document.getElementById('messageInput');
    input.value += emoji;
    input.focus();
    document.getElementById('emojiPicker').classList.remove('show');
}

// Beep functions
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

function playBeep(frequency = 800, duration = 100, volume = 0.1) {
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



// -----------------------------
// -------------------------


// Detect URLs in message input
document.getElementById('messageInput').addEventListener('input', function() {
    const text = this.value;
    
    // Existing button toggle code...
    const sendBtn = document.getElementById('sendBtn');
    const voiceBtn = document.getElementById('voiceBtn');
    
    if (text.trim().length > 0) {
        sendBtn.style.display = 'flex';
        voiceBtn.style.display = 'none';
    } else {
        sendBtn.style.display = 'none';
        voiceBtn.style.display = 'flex';
    }
    
    // Auto-resize
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
    
    // ✅ NEW: Detect URLs for link preview
    clearTimeout(messageUrlDetectionTimeout);
    messageUrlDetectionTimeout = setTimeout(() => {
        detectAndPreviewMessageLinks(text);
    }, 1000);
    
    // Typing indicator
    clearTimeout(typingTimeout);
    fetch(`/messages/typing/${friendId}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });
    typingTimeout = setTimeout(() => {}, 3000);
});

// ✅ NEW: Detect and preview links
function detectAndPreviewMessageLinks(text) {
    const urlRegex = /(https?:\/\/[^\s]+)/gi;
    const urls = text.match(urlRegex);
    
    if (urls && urls.length > 0) {
        const firstUrl = urls[0];
        fetchMessageLinkPreview(firstUrl);
    } else {
        clearMessageLinkPreview();
    }
}

// ✅ NEW: Fetch link preview
function fetchMessageLinkPreview(url) {
    // Check if container exists, if not create it
    if ($('#messageLinkPreviewContainer').length === 0) {
        $('#messageInput').after('<div id="messageLinkPreviewContainer" style="margin-top:10px;"></div>');
    }
    
    $('#messageLinkPreviewContainer').html(`
        <div class="link-preview-loading">
            <i class="fa fa-spinner fa-spin"></i> Loading preview...
        </div>
    `).show();

    $.ajax({
        url: '/messages/fetch-link-preview',
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            url: url
        },
        success: function(data) {
            messageLinkPreviewData = data;
            displayMessageLinkPreview(data);
        },
        error: function(xhr) {
            console.error('Failed to fetch link preview:', xhr);
            clearMessageLinkPreview();
        }
    });
}

// ✅ NEW: Display link preview
function displayMessageLinkPreview(data) {
    const previewHtml = `
        <div class="link-preview-card">
            <button type="button" class="link-preview-close" onclick="clearMessageLinkPreview()">
                <i class="fa fa-times"></i>
            </button>
            ${data.image ? `
                <div class="link-preview-image">
                    <img src="${data.image}" alt="${data.title}" onerror="this.parentElement.style.display='none'">
                </div>
            ` : ''}
            <div class="link-preview-content">
                <div class="link-preview-site">
                    <img src="${data.favicon}" class="link-preview-favicon" onerror="this.style.display='none'">
                    <span>${data.site_name}</span>
                </div>
                <h4 class="link-preview-title">${data.title}</h4>
                ${data.description ? `<p class="link-preview-description">${data.description}</p>` : ''}
                <a href="${data.url}" class="link-preview-url" target="_blank" rel="noopener">
                    <i class="fas fa-external-link-alt"></i> ${data.url}
                </a>
            </div>
        </div>
    `;
    
    $('#messageLinkPreviewContainer').html(previewHtml).show();
}

// ✅ NEW: Clear link preview
function clearMessageLinkPreview() {
    messageLinkPreviewData = null;
    $('#messageLinkPreviewContainer').empty().hide();
}






// ✅ UPDATE: Send message form to include link preview
document.getElementById('messageForm').addEventListener('submit', function(e) {
    e.preventDefault();

    
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    
    if (!message && selectedFiles.length === 0) return;

    if (isEditing) {
        fetch(`/messages/edit/${editingMessageId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const messageElement = document.querySelector(`[data-message-id="${editingMessageId}"]`);
                if (messageElement) {
                    messageElement.querySelector('.message-content').innerHTML = `${message} <small style="opacity:0.6;"> (edited)</small>`;
                    messageElement.setAttribute('data-message', message);
                }
                
                input.value = '';
                input.style.height = 'auto';
                isEditing = false;
                editingMessageId = null;
                

                // ✅ RESET BUTTONS AFTER EDITING
                document.getElementById('sendBtn').innerHTML = '<i class="fa fa-paper-plane"></i>';
                document.getElementById('sendBtn').style.display = 'none';
                document.getElementById('voiceBtn').style.display = 'flex';
            }
        });
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
                        document.getElementById('uploadStatus').textContent = `Uploading file ${fileIndex + 1} of ${selectedFiles.length}...`;
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
                            reject(new Error(`Cloudinary upload failed for ${file.name}. Status: ${xhr.status} - ${xhr.responseText}`));
                        }
                    }
                };

                xhr.open('POST', endpoint, true);
                xhr.send(formData);
            });
            
            uploadPromises.push(promise);
        });
        
        Promise.all(uploadPromises)
            .then(() => {
                document.getElementById('uploadStatus').textContent = 'Sending message...';
                
                const formData = new FormData();
                formData.append('message', message || '');
                formData.append('reply_to_id', replyingToId || '');
                
                // ✅ NEW: Include link preview
                if (messageLinkPreviewData) {
                    formData.append('link_preview', JSON.stringify(messageLinkPreviewData));
                }
                
                uploadedUrls.forEach((url) => {
                    formData.append('files[]', url);
                });

                return fetch(`/messages/send/${friendId}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    addMessageToUI(data.message);
                    
                    input.value = '';
                    input.style.height = 'auto';
                    cancelReply();
                    cancelFileUpload();
                    clearMessageLinkPreview(); // ✅ NEW
                    scrollToBottom();
                    lastMessageId = data.message.id;

                    document.getElementById('sendBtn').style.display = 'none';
                    document.getElementById('voiceBtn').style.display = 'flex';
                    document.getElementById('sendBtn').disabled = false;
                    document.getElementById('uploadProgress').style.display = 'none';
                }
            });
    } else {
        // ✅ UPDATE: Text-only message with link preview
        const formData = new FormData();
        formData.append('message', message);
        formData.append('reply_to_id', replyingToId || '');
        
        // ✅ NEW: Include link preview
        if (messageLinkPreviewData) {
            formData.append('link_preview', JSON.stringify(messageLinkPreviewData));
        }
        
        fetch(`/messages/send/${friendId}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                addMessageToUI(data.message);
                
                input.value = '';
                input.style.height = 'auto';
                cancelReply();
                clearMessageLinkPreview(); // ✅ NEW
                scrollToBottom();
                lastMessageId = data.message.id;

                document.getElementById('sendBtn').style.display = 'none';
                document.getElementById('voiceBtn').style.display = 'flex';
            }
        });
    }
});

// ========================================
// UPDATED addMessageToUI Function with Link Preview
// ========================================

function addMessageToUI(message) {
    const container = document.getElementById('messagesContainer');

    // Skip if message already exists in DOM
    if (message.id && document.querySelector(`[data-message-id="${message.id}"]`)) {
        return;
    }

    // Add date separator if needed
    maybeAddDateSeparator(container, message.created_at || new Date().toISOString());

    const messageDiv = document.createElement('div');

    const isCallMessage = message.message && (
        message.message.includes('📞') || 
        message.message.includes('📹') ||
        message.message.includes('Call')
    );
    
    if (isCallMessage) {
        let statusClass = '';
        if (message.message.includes('ended')) statusClass = 'call-status-ended';
        else if (message.message.includes('declined')) statusClass = 'call-status-declined';
        else if (message.message.includes('no_answer')) statusClass = 'call-status-no_answer';
        
        messageDiv.className = 'd-flex justify-content-center';
        messageDiv.innerHTML = `
            <div class="call-message-bubble ${statusClass}"
                 data-message-id="${message.id}"
                 data-message="${message.message || ''}"
                 data-sender="${userId}">
                <div class="call-message-content">${message.message}</div>
                <div class="call-message-info">${new Date(message.created_at).toLocaleTimeString()}</div>
            </div>
        `;
    } else {
        messageDiv.className = 'd-flex justify-content-end';
        
        let replyHtml = '';
        if (message.reply_to_id) {
            replyHtml = '<div class="reply-reference"><i class="fa fa-reply"></i> Replying to message</div>';
        }
        
        let filesHtml = '';
        if (message.file_path) {
            try {
                const files = JSON.parse(message.file_path);
                filesHtml = '<div class="mt-2">';
                files.forEach(file => {
                    if (file.includes('.jpg') || file.includes('.png') || file.includes('.jpeg') || file.includes('.gif') || file.includes('.webp')) {
                        filesHtml += `<img src="${file}" class="img-fluid rounded" style="max-width:200px;cursor:pointer;" onclick="window.open('${file}', '_blank')">`;
                    } else if (file.includes('.mp4') || file.includes('.webm') || file.includes('.ogg')) {
                        filesHtml += `<video controls class="rounded" style="max-width:200px;"><source src="${file}"></video>`;
                    } else if (file.includes('.pdf')) {
                        filesHtml += `<a href="${file}" target="_blank" class="btn btn-sm btn-light mt-2"><i class="fa fa-file-pdf text-danger"></i> View PDF</a>`;
                    } else {
                        filesHtml += `<a href="${file}" target="_blank" class="btn btn-sm btn-light mt-2"><i class="fa fa-file"></i> Download File</a>`;
                    }
                });
                filesHtml += '</div>';
            } catch (e) {
                console.error('Error parsing files:', e);
            }
        }
        
        // ✅ FIX: Link preview HTML generation
        let linkPreviewHtml = '';
        if (message.link_preview) {
            try {
                const linkData = typeof message.link_preview === 'string' 
                    ? JSON.parse(message.link_preview) 
                    : message.link_preview;
                
                linkPreviewHtml = `
                    <div class="message-link-preview">
                        <div class="link-preview-card">
                            ${linkData.image ? `
                                <div class="link-preview-image">
                                    <img src="${linkData.image}" alt="${linkData.title}" onerror="this.parentElement.style.display='none'">
                                </div>
                            ` : ''}
                            <div class="link-preview-content">
                                <div class="link-preview-site">
                                    <img src="${linkData.favicon}" class="link-preview-favicon" onerror="this.style.display='none'">
                                    <span>${linkData.site_name}</span>
                                </div>
                                <h4 class="link-preview-title">${linkData.title}</h4>
                                ${linkData.description ? `<p class="link-preview-description">${linkData.description}</p>` : ''}
                                <a href="${linkData.url}" class="link-preview-url" target="_blank" rel="noopener">
                                    <i class="fas fa-external-link-alt"></i> ${linkData.url.substring(0, 50)}${linkData.url.length > 50 ? '...' : ''}
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            } catch (e) {
                console.error('Error parsing link preview:', e);
            }
        }
        
        messageDiv.innerHTML = `
            <div class="message-bubble sent"
                 data-message-id="${message.id}"
                 data-message="${message.message || ''}"
                 data-sender="${userId}"
                 oncontextmenu="showMessageActions(event, this); return false;"
                 ontouchstart="handleTouchStart(event, this)"
                 ontouchend="handleTouchEnd(event)">
                ${replyHtml}
                ${message.message ? `<div class="message-content">${message.message}</div>` : ''}
                ${linkPreviewHtml}
                ${filesHtml}
                <div class="message-time">Just now <span class="tick-sent"><svg viewBox="0 0 12 11" width="12" height="11"><path d="M11.071.653a.457.457 0 0 0-.304-.102.493.493 0 0 0-.381.178l-6.19 7.636-2.011-2.175a.463.463 0 0 0-.336-.153.457.457 0 0 0-.344.146.441.441 0 0 0-.101.317c0 .12.045.229.134.327l2.39 2.588a.459.459 0 0 0 .312.153.484.484 0 0 0 .384-.187L11.1 1.27a.469.469 0 0 0 .109-.293.441.441 0 0 0-.138-.324z" fill="#667781"/></svg></span></div>
            </div>
        `;
    }
    
    container.appendChild(messageDiv);
    playSendBeep();

    // Reset buttons
    const input = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');
    const voiceBtn = document.getElementById('voiceBtn');
    
    input.value = '';
    input.style.height = 'auto';
    sendBtn.style.display = 'none';
    voiceBtn.style.display = 'flex';
}

// Poll for new messages
setInterval(function() {
    fetch(`/messages/new/${friendId}/${lastMessageId}`)
    .then(response => response.json())
    .then(data => {
        if (data.messages && data.messages.length > 0) {
            const container = document.getElementById('messagesContainer');
            
            data.messages.forEach(msg => {
                // Skip if message already exists in DOM
                if (document.querySelector(`[data-message-id="${msg.id}"]`)) {
                    lastMessageId = Math.max(lastMessageId, msg.id);
                    return;
                }

                // Add date separator if needed
                maybeAddDateSeparator(container, msg.created_at);

                const messageDiv = document.createElement('div');

                const isCallMessage = msg.message && (
                    msg.message.includes('📞') ||
                    msg.message.includes('📹') ||
                    msg.message.includes('Call')
                );

                if (isCallMessage) {
                    let statusClass = '';
                    if (msg.message.includes('ended')) statusClass = 'call-status-ended';
                    else if (msg.message.includes('declined')) statusClass = 'call-status-declined';
                    else if (msg.message.includes('no_answer')) statusClass = 'call-status-no_answer';

                    messageDiv.className = 'd-flex justify-content-center';
                    messageDiv.innerHTML = `
                        <div class="call-message-bubble ${statusClass}"
                             data-message-id="${msg.id}"
                             data-message="${msg.message || ''}"
                             data-sender="${msg.sender_id}">
                            <div class="call-message-content">${msg.message}</div>
                            <div class="call-message-info">${new Date(msg.created_at).toLocaleTimeString()}</div>
                        </div>
                    `;
                } else {
                    messageDiv.className = 'd-flex justify-content-start';
                    messageDiv.innerHTML = `
                        <div class="message-bubble received"
                             data-message-id="${msg.id}"
                             data-message="${msg.message}"
                             data-sender="${msg.sender_id}"
                             oncontextmenu="showMessageActions(event, this); return false;"
                             ontouchstart="handleTouchStart(event, this)"
                             ontouchend="handleTouchEnd(event)">
                            <div class="message-content">${msg.message}</div>
                            <div class="message-time">${new Date(msg.created_at).toLocaleTimeString()}</div>
                        </div>
                    `;
                }

                container.appendChild(messageDiv);
                lastMessageId = msg.id;
                playReceiveBeep();
                incrementUnreadBadge();
            });
            
            scrollToBottom();
        }
        
        const typingIndicator = document.getElementById('typingIndicator');
        const typingBubble = document.getElementById('typingIndicatorBubble');
        const onlineStatus = document.getElementById('onlineStatus');
        
        if (data.is_typing) {
            typingIndicator.style.display = 'block';
            typingBubble.classList.add('show');
            onlineStatus.style.display = 'none';
        } else {
            typingIndicator.style.display = 'none';
            typingBubble.classList.remove('show');
            onlineStatus.style.display = 'block';
        }
    })
    .catch(error => console.error('Error:', error));
}, 2000);

// Sound enable banner
document.addEventListener('DOMContentLoaded', function() {
    const soundEnabled = localStorage.getItem('messageSoundEnabled');
    
    if (!soundEnabled) {
        showSoundEnableBanner();
    }
});

function showSoundEnableBanner() {
    const banner = document.createElement('div');
    banner.id = 'soundEnableBanner';
    banner.style.cssText = `
        position: fixed;
        top: 60px;
        left: 0;
        right: 0;
        background: #2196f3;
        color: white;
        padding: 12px 20px;
        text-align: center;
        z-index: 9999;
        box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px;
    `;
    banner.innerHTML = `
        <span>🔔 <strong>Enable message sounds</strong></span>
        <button onclick="enableMessageSound()" style="padding: 8px 20px; background: white; color: #2196f3; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;">
            🔊 Enable Sound
        </button>
        <button onclick="dismissSoundBanner()" style="padding: 8px 15px; background: transparent; color: white; border: 1px solid white; border-radius: 5px; cursor: pointer;">
            Dismiss
        </button>
    `;
    document.body.appendChild(banner);
}

function enableMessageSound() {
    playBeep(800, 150, 0.15);
    localStorage.setItem('messageSoundEnabled', 'true');
    
    const banner = document.getElementById('soundEnableBanner');
    if (banner) banner.remove();
    
    showPopup('Message sounds enabled!', 'success');
}

function dismissSoundBanner() {
    const banner = document.getElementById('soundEnableBanner');
    if (banner) banner.remove();
    localStorage.setItem('messageSoundDismissed', 'true');
}

// ========================================
// WHATSAPP SEARCH BAR FUNCTIONALITY
// ========================================

let searchResults = [];
let currentSearchIndex = -1;

function toggleSearchBar() {
    const bar = document.getElementById('chatSearchBar');
    bar.classList.toggle('show');
    if (bar.classList.contains('show')) {
        document.getElementById('chatSearchInput').focus();
    } else {
        closeSearchBar();
    }
}

function closeSearchBar() {
    document.getElementById('chatSearchBar').classList.remove('show');
    document.getElementById('chatSearchInput').value = '';
    document.getElementById('searchResultCount').textContent = '';
    clearSearchHighlights();
    searchResults = [];
    currentSearchIndex = -1;
}

function searchMessages(query) {
    clearSearchHighlights();
    searchResults = [];
    currentSearchIndex = -1;

    if (!query || query.trim().length < 2) {
        document.getElementById('searchResultCount').textContent = '';
        return;
    }

    const bubbles = document.querySelectorAll('.message-bubble');
    const lowerQuery = query.toLowerCase();

    bubbles.forEach(bubble => {
        const content = bubble.querySelector('.message-content');
        if (content && content.textContent.toLowerCase().includes(lowerQuery)) {
            searchResults.push(bubble);
            bubble.style.boxShadow = '0 0 0 2px #FFD700';
        }
    });

    if (searchResults.length > 0) {
        currentSearchIndex = searchResults.length - 1;
        scrollToSearchResult();
    }

    document.getElementById('searchResultCount').textContent = searchResults.length > 0
        ? `${currentSearchIndex + 1} of ${searchResults.length}`
        : 'No results';
}

function searchNext() {
    if (searchResults.length === 0) return;
    currentSearchIndex = (currentSearchIndex + 1) % searchResults.length;
    scrollToSearchResult();
}

function searchPrev() {
    if (searchResults.length === 0) return;
    currentSearchIndex = (currentSearchIndex - 1 + searchResults.length) % searchResults.length;
    scrollToSearchResult();
}

function scrollToSearchResult() {
    searchResults.forEach(r => r.style.boxShadow = '0 0 0 2px #FFD700');
    if (searchResults[currentSearchIndex]) {
        searchResults[currentSearchIndex].style.boxShadow = '0 0 0 3px #FF8C00';
        searchResults[currentSearchIndex].scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    document.getElementById('searchResultCount').textContent = `${currentSearchIndex + 1} of ${searchResults.length}`;
}

function clearSearchHighlights() {
    document.querySelectorAll('.message-bubble').forEach(b => {
        b.style.boxShadow = '';
    });
}

// ========================================
// SCROLL-TO-BOTTOM + UNREAD BADGE
// ========================================

let unreadCount = 0;

(function() {
    const container = document.getElementById('messagesContainer');
    const btn = document.getElementById('scrollToBottomBtn');
    const badge = document.getElementById('unreadBadge');

    container.addEventListener('scroll', function() {
        const distFromBottom = container.scrollHeight - container.scrollTop - container.clientHeight;
        if (distFromBottom > 200) {
            btn.style.display = 'flex';
        } else {
            btn.style.display = 'none';
            unreadCount = 0;
            badge.style.display = 'none';
        }
    });
})();

function incrementUnreadBadge() {
    const container = document.getElementById('messagesContainer');
    const distFromBottom = container.scrollHeight - container.scrollTop - container.clientHeight;
    if (distFromBottom > 200) {
        unreadCount++;
        const badge = document.getElementById('unreadBadge');
        badge.textContent = unreadCount;
        badge.style.display = 'flex';
    }
}

// ========================================
// DYNAMIC DATE SEPARATORS
// ========================================

let lastDynamicDate = null;

function maybeAddDateSeparator(container, messageDate) {
    if (!messageDate) return;
    const dateStr = new Date(messageDate).toDateString();
    if (lastDynamicDate === dateStr) return;
    lastDynamicDate = dateStr;

    const today = new Date().toDateString();
    const yesterday = new Date(Date.now() - 86400000).toDateString();

    let label;
    if (dateStr === today) label = 'TODAY';
    else if (dateStr === yesterday) label = 'YESTERDAY';
    else label = new Date(messageDate).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

    const sep = document.createElement('div');
    sep.className = 'date-separator';
    sep.innerHTML = `<span class="date-separator-chip">${label}</span>`;
    container.appendChild(sep);
}

</script>











<!-- Agora Web SDK 4.x for Audio/Video calls -->
<script src="https://download.agora.io/sdk/release/AgoraRTC_N-4.22.0.js"></script>

<!-- ========================================
     CHAT CALL APP — WhatsApp-style overlay (Agora)
     ======================================== -->
<script>
const ChatCallApp = {
    callId: null,
    callType: null,
    agoraClient: null,
    localAudioTrack: null,
    localVideoTrack: null,
    isMuted: false,
    isVideoOff: false,
    callTimer: null,
    callSeconds: 0,
    isCallActive: false,
    callTimeout: null,
    echoChannel: null,
    audioContext: null,
    ringbackInterval: null,
    agoraChannel: null,

    // ---- Start outgoing call ----
    async start(type) {
        this.cleanup();
        this.callType = type;
        this.showOverlay();
        document.getElementById('callOverlayStatus').textContent = 'Calling...';
        document.getElementById('callActiveButtons').style.display = 'flex';
        document.getElementById('callIncomingButtons').style.display = 'none';

        if (type === 'video') {
            document.getElementById('callVideoToggleBtn').style.display = '';
        } else {
            document.getElementById('callVideoToggleBtn').style.display = 'none';
        }

        try {
            console.log('📞 Initiating', type, 'call to friend:', friendId);
            const response = await fetch('/calls/initiate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    receiver_id: friendId,
                    call_type: type
                })
            });
            const data = await response.json();

            if (!data.success) {
                showPopup('Failed to start call.', 'error');
                this.hideOverlay();
                return;
            }

            this.callId = data.call_id;
            this.agoraChannel = data.agora_channel;
            console.log('✅ Call initiated, callId:', data.call_id, 'agoraChannel:', data.agora_channel);
            this.subscribeToChannel();
            this.playRingback();
            console.log('⏳ Waiting for receiver to accept...');
            this.callTimeout = setTimeout(() => {
                if (!this.isCallActive) {
                    this.endCallTimeout();
                }
            }, 60000);
        } catch (error) {
            console.error('Call start error:', error);
            showPopup('Failed to start call.', 'error');
            this.hideOverlay();
        }
    },

    // ---- Handle incoming call (replaces old dialog) ----
    handleIncoming(callId, callerName, callType) {
        console.log('📞 handleIncoming: callId:', callId, 'from:', callerName, 'type:', callType);
        this.cleanup();
        this.callId = callId;
        this.callType = callType;
        this.agoraChannel = 'call_' + callId;

        document.getElementById('callOverlayName').textContent = callerName;
        document.getElementById('callOverlayStatus').textContent = 'Incoming ' + callType + ' call...';
        document.getElementById('callOverlayTimer').style.display = 'none';
        document.getElementById('callVideoContainer').style.display = 'none';
        document.getElementById('callActiveButtons').style.display = 'none';
        document.getElementById('callIncomingButtons').style.display = 'flex';
        if (callType === 'video') {
            document.getElementById('callVideoToggleBtn').style.display = '';
        }
        console.log('📞 Showing incoming call overlay with accept/decline buttons');
        this.showOverlay();
        this.subscribeToChannel();
    },

    // ---- Accept incoming call ----
    async accept() {
        document.getElementById('callIncomingButtons').style.display = 'none';
        document.getElementById('callActiveButtons').style.display = 'flex';
        document.getElementById('callOverlayStatus').textContent = 'Connecting...';

        try {
            console.log('📞 Accepting call:', this.callId);
            const acceptResponse = await fetch(`/calls/${this.callId}/accept`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
            const acceptData = await acceptResponse.json();
            console.log('📞 Accept response:', acceptData);
            if (!acceptData.success) {
                console.error('❌ Accept failed:', acceptData.message);
                showPopup('Failed to accept call: ' + (acceptData.message || 'Unknown error'), 'error');
                this.cleanup();
                this.hideOverlay();
                return;
            }
            // Join Agora channel after accepting
            await this.joinAgoraChannel();
        } catch (error) {
            console.error('Accept error:', error);
            showPopup('Failed to accept call.', 'error');
            this.cleanup();
            this.hideOverlay();
        }
    },

    // ---- Decline incoming call ----
    async decline() {
        this.cleanup();
        try {
            await fetch(`/calls/${this.callId}/decline`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
        } catch (e) {}
        this.hideOverlay();
    },

    // ---- End active call ----
    async endCall() {
        if (this._ending) return;
        this._ending = true;
        if (this.callId) {
            try {
                await fetch(`/calls/${this.callId}/end`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });
            } catch (e) { console.error('End call error:', e); }
        }
        this.cleanup();
        this.hideOverlay();
        this._ending = false;
    },

    async endCallTimeout() {
        if (this._ending) return;
        this._ending = true;
        if (this.callId) {
            try {
                await fetch(`/calls/${this.callId}/timeout`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });
            } catch (e) { console.error('Timeout call error:', e); }
        }
        this.cleanup();
        showPopup('No answer', 'info');
        this.hideOverlay();
        this._ending = false;
    },

    // ---- Subscribe to Echo channel ----
    subscribeToChannel() {
        if (!window.Echo || !this.callId) return;

        this.echoChannel = window.Echo.private(`calls.${this.callId}`);

        this.echoChannel
            .listen('.CallAccepted', async (e) => {
                console.log('✅ CallAccepted received!', e);
                if (this.callTimeout) clearTimeout(this.callTimeout);
                this.stopRingback();
                if (e.agoraChannel) {
                    this.agoraChannel = e.agoraChannel;
                }
                // Caller joins Agora channel after receiver accepted
                if (!this.agoraClient) {
                    console.log('📞 Receiver accepted — joining Agora channel');
                    await this.joinAgoraChannel();
                }
            })
            .listen('.CallDeclined', (e) => {
                if (this.callTimeout) clearTimeout(this.callTimeout);
                this.cleanup();
                this.hideOverlay();
                showPopup('Call was declined', 'info');
            })
            .listen('.CallEnded', (e) => {
                this.cleanup();
                this.hideOverlay();
            });
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

            // Get token from server
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
                console.error('❌ Failed to get Agora token');
                showPopup('Failed to connect call.', 'error');
                this.cleanup();
                this.hideOverlay();
                return;
            }

            // Create Agora client
            this.agoraClient = AgoraRTC.createClient({ mode: 'rtc', codec: 'vp8' });

            // Handle remote user published
            this.agoraClient.on('user-published', async (user, mediaType) => {
                await this.agoraClient.subscribe(user, mediaType);
                console.log('✅ Subscribed to remote user:', user.uid, 'mediaType:', mediaType);

                if (mediaType === 'video') {
                    const remoteContainer = document.getElementById('callRemoteVideo');
                    // Clear any existing content
                    remoteContainer.innerHTML = '';
                    user.videoTrack.play(remoteContainer);
                    document.getElementById('callVideoContainer').style.display = 'block';
                }
                if (mediaType === 'audio') {
                    user.audioTrack.play();
                }

                if (!this.isCallActive) this.onCallConnected();
            });

            this.agoraClient.on('user-unpublished', (user, mediaType) => {
                console.log('📴 Remote user unpublished:', user.uid, mediaType);
                if (mediaType === 'video') {
                    const remoteContainer = document.getElementById('callRemoteVideo');
                    remoteContainer.innerHTML = '';
                }
            });

            this.agoraClient.on('user-left', (user) => {
                console.log('📴 Remote user left:', user.uid);
                if (this.isCallActive) {
                    this.endCall();
                }
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
                console.log('⚠️ Token expired, attempting to rejoin...');
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

            // Join channel
            console.log('🔑 Agora join params:', {
                app_id: tokenData.app_id,
                channel: this.agoraChannel,
                token: tokenData.token ? tokenData.token.substring(0, 20) + '...' : 'NULL',
                uid: tokenData.uid,
                full_response: tokenData
            });
            await this.agoraClient.join(tokenData.app_id, this.agoraChannel, tokenData.token, tokenData.uid);
            console.log('✅ Joined Agora channel successfully');

            // Create and publish local tracks
            if (this.callType === 'video') {
                [this.localAudioTrack, this.localVideoTrack] = await AgoraRTC.createMicrophoneAndCameraTracks(
                    {}, { encoderConfig: '480p_1' }
                );
                // Play local video in the local video container
                const localContainer = document.getElementById('callLocalVideo');
                localContainer.innerHTML = '';
                this.localVideoTrack.play(localContainer);
                document.getElementById('callVideoContainer').style.display = 'block';
                await this.agoraClient.publish([this.localAudioTrack, this.localVideoTrack]);
            } else {
                this.localAudioTrack = await AgoraRTC.createMicrophoneAudioTrack();
                await this.agoraClient.publish([this.localAudioTrack]);
            }

            console.log('✅ Published local tracks');
            document.getElementById('callOverlayStatus').textContent = 'Connected';

            // If we haven't connected yet via remote-published, mark as connected after a short delay
            setTimeout(() => {
                if (!this.isCallActive && this.agoraClient) {
                    this.onCallConnected();
                }
            }, 2000);

        } catch (error) {
            console.error('❌ Agora join error:', error);
            showPopup('Failed to connect call.', 'error');
            this.cleanup();
            this.hideOverlay();
        }
    },

    // ---- Call connected ----
    onCallConnected() {
        if (this.isCallActive) return;
        console.log('🎉 Call connected! Starting timer...');
        this.isCallActive = true;
        this.stopRingback();
        if (typeof stopRingtone === 'function') stopRingtone();
        if (typeof currentNotification !== 'undefined' && currentNotification) { currentNotification.close(); currentNotification = null; }
        document.getElementById('callOverlayStatus').textContent = 'Connected';
        this.startTimer();
    },

    // ---- Toggle mute ----
    toggleMute() {
        if (!this.localAudioTrack) return;
        this.isMuted = !this.isMuted;
        this.localAudioTrack.setEnabled(!this.isMuted);
        const btn = document.getElementById('callMuteBtn');
        btn.querySelector('i').className = this.isMuted ? 'fa fa-microphone-slash' : 'fa fa-microphone';
        btn.style.background = this.isMuted ? 'rgba(244,67,54,0.7)' : 'rgba(255,255,255,0.2)';
    },

    // ---- Toggle video ----
    toggleVideo() {
        if (!this.localVideoTrack) return;
        this.isVideoOff = !this.isVideoOff;
        this.localVideoTrack.setEnabled(!this.isVideoOff);
        const btn = document.getElementById('callVideoToggleBtn');
        btn.querySelector('i').className = this.isVideoOff ? 'fa fa-video-slash' : 'fa fa-video';
        btn.style.background = this.isVideoOff ? 'rgba(244,67,54,0.7)' : 'rgba(255,255,255,0.2)';
    },

    toggleSpeaker() {
        const btn = document.getElementById('callSpeakerBtn');
        btn.classList.toggle('active');
    },

    // ---- Minimize / Maximize ----
    minimize() {
        document.getElementById('callOverlay').style.display = 'none';
        const bar = document.getElementById('callMinimizedBar');
        bar.style.display = 'flex';
    },

    maximize() {
        document.getElementById('callMinimizedBar').style.display = 'none';
        document.getElementById('callOverlay').style.display = 'flex';
    },

    // ---- Timer ----
    startTimer() {
        this.callSeconds = 0;
        const timerEl = document.getElementById('callOverlayTimer');
        const barTimer = document.getElementById('callBarTimer');
        timerEl.style.display = 'block';
        document.getElementById('callOverlayStatus').style.display = 'none';

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
        document.getElementById('callOverlay').style.display = 'flex';
    },

    hideOverlay() {
        document.getElementById('callOverlay').style.display = 'none';
        document.getElementById('callMinimizedBar').style.display = 'none';
        document.getElementById('callVideoContainer').style.display = 'none';
        document.getElementById('callOverlayTimer').style.display = 'none';
        document.getElementById('callOverlayStatus').style.display = '';
        document.getElementById('callOverlayStatus').textContent = '';
        this.callId = null;
        this.callType = null;
    },

    // ---- Cleanup ----
    cleanup() {
        this.stopRingback();
        if (typeof stopRingtone === 'function') stopRingtone();
        if (typeof currentNotification !== 'undefined' && currentNotification) { try { currentNotification.close(); } catch(e) {} }
        if (this.callTimer) clearInterval(this.callTimer);
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

        if (this.echoChannel && this.callId) {
            try { window.Echo.leave(`calls.${this.callId}`); } catch (e) {}
            this.echoChannel = null;
        }
        this.isCallActive = false;
        this.callSeconds = 0;
        this.isMuted = false;
        this.isVideoOff = false;
        this.agoraChannel = null;
        this._joiningAgora = false;
    }
};

// ---- Draggable local video (PiP) ----
(function() {
    const vid = document.getElementById('callLocalVideo');
    if (!vid) return;
    let isDragging = false, offsetX = 0, offsetY = 0;

    function startDrag(x, y) {
        isDragging = true;
        const rect = vid.getBoundingClientRect();
        offsetX = x - rect.left;
        offsetY = y - rect.top;
        vid.style.transition = 'none';
    }
    function doDrag(x, y) {
        if (!isDragging) return;
        const parent = vid.parentElement;
        if (!parent) return;
        const pRect = parent.getBoundingClientRect();
        let newLeft = x - pRect.left - offsetX;
        let newTop = y - pRect.top - offsetY;
        newLeft = Math.max(0, Math.min(newLeft, pRect.width - vid.offsetWidth));
        newTop = Math.max(0, Math.min(newTop, pRect.height - vid.offsetHeight));
        vid.style.left = newLeft + 'px';
        vid.style.top = newTop + 'px';
        vid.style.right = 'auto';
        vid.style.bottom = 'auto';
    }
    function endDrag() { isDragging = false; vid.style.transition = ''; }

    vid.addEventListener('mousedown', (e) => { e.preventDefault(); startDrag(e.clientX, e.clientY); });
    document.addEventListener('mousemove', (e) => doDrag(e.clientX, e.clientY));
    document.addEventListener('mouseup', endDrag);
    vid.addEventListener('touchstart', (e) => { startDrag(e.touches[0].clientX, e.touches[0].clientY); }, { passive: true });
    document.addEventListener('touchmove', (e) => { if (isDragging) doDrag(e.touches[0].clientX, e.touches[0].clientY); }, { passive: true });
    document.addEventListener('touchend', endDrag);
})();
</script>

<!-- incoming calls  -->



 <!-- Sound Enable Banner -->
<div id="enableSoundBanner" style="display:none; position:fixed; top:60px; left:0; right:0; background:#ff9800; color:white; padding:15px; text-align:center; z-index:9999; box-shadow:0 2px 10px rgba(0,0,0,0.3);">
    🔔 <strong>Enable call ringtone to hear incoming calls</strong>
    <button onclick="enableCallSound()" style="margin-left:15px; padding:8px 20px; background:white; color:#ff9800; border:none; border-radius:5px; font-weight:bold; cursor:pointer;">
        🔊 Enable Sound
    </button>
    <button onclick="dismissSoundBanner()" style="margin-left:10px; padding:8px 15px; background:transparent; color:white; border:1px solid white; border-radius:5px; cursor:pointer;">
        Dismiss
    </button>
</div>

<!-- Old call dialog removed — now using ChatCallApp overlay -->


@if (isset($user) && $user)
<script>
    const currentUserId = {{ $user->id }}; 
    console.log('User ID for Echo:', currentUserId);

    let ringtone = null;
    let soundEnabled = false;
    let notificationPermission = false;
    let currentCallId = null;
    let audioContext = null;
    let oscillator = null;
    let gainNode = null;

    // Request notification permission on page load
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission().then(permission => {
            notificationPermission = permission === 'granted';
            console.log('Notification permission:', permission);
        });
    } else if ('Notification' in window && Notification.permission === 'granted') {
        notificationPermission = true;
    }

    // Function to enable sound (requires user interaction)
    function enableCallSound() {
        console.log('Attempting to enable sound...');
        
        // Test with oscillator
        playTestBeep();
        
        soundEnabled = true;
        document.getElementById('enableSoundBanner').style.display = 'none';
        localStorage.setItem('callSoundEnabled', 'true');
        
        showToast('✅ Call ringtone enabled!', 'success');
        console.log('✅ Sound enabled successfully');
    }

    function playTestBeep() {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = ctx.createOscillator();
        const g = ctx.createGain();
        
        osc.connect(g);
        g.connect(ctx.destination);
        
        osc.frequency.value = 800;
        g.gain.value = 0.3;
        
        osc.start();
        setTimeout(() => {
            g.gain.exponentialRampToValueAtTime(0.00001, ctx.currentTime + 0.5);
            osc.stop(ctx.currentTime + 0.5);
        }, 200);
    }

    function dismissSoundBanner() {
        document.getElementById('enableSoundBanner').style.display = 'none';
        localStorage.setItem('soundBannerDismissed', 'true');
    }

    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.style.cssText = `
            position: fixed;
            top: 80px;
            right: 20px;
            background: ${type === 'success' ? '#4caf50' : '#2196f3'};
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            z-index: 10000;
            animation: slideIn 0.3s ease-out;
        `;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    function playRingtone() {
        if (!soundEnabled) {
            console.warn('⚠️ Sound not enabled by user');
            return false;
        }

        try {
            // Create continuous beeping ringtone using Web Audio API
            audioContext = new (window.AudioContext || window.webkitAudioContext)();
            
            // Create a more pleasant ringtone pattern
            let currentTime = audioContext.currentTime;
            
            function playBeepPattern() {
                if (!audioContext) return;
                
                // Create oscillator for this beep
                oscillator = audioContext.createOscillator();
                gainNode = audioContext.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                
                // Ringtone frequency (higher = more urgent)
                oscillator.frequency.value = 800;
                gainNode.gain.value = 0.3;
                
                oscillator.start(currentTime);
                oscillator.stop(currentTime + 0.3); // Beep for 0.3 seconds
                
                currentTime += 0.8; // Wait 0.5 seconds before next beep
                
                // Schedule next beep
                if (audioContext) {
                    setTimeout(playBeepPattern, 800);
                }
            }
            
            playBeepPattern();
            
            console.log('✅ Ringtone playing (Web Audio API)');
            return true;
            
        } catch (error) {
            console.error('Error playing ringtone:', error);
            return false;
        }
    }

    function stopRingtone() {
        try {
            if (oscillator) {
                oscillator.stop();
                oscillator.disconnect();
                oscillator = null;
            }
            if (gainNode) {
                gainNode.disconnect();
                gainNode = null;
            }
            if (audioContext) {
                audioContext.close();
                audioContext = null;
            }
            console.log('🔇 Ringtone stopped');
        } catch (error) {
            console.error('Error stopping ringtone:', error);
        }
    }

    function showNotification(callerName, callType) {
        if (notificationPermission && 'Notification' in window) {
            const notification = new Notification(`Incoming ${callType} Call`, {
                body: `${callerName} is calling you`,
                icon: '{{ asset("images/favicon-32x32.png") }}',
                tag: 'incoming-call',
                requireInteraction: true,
                vibrate: [200, 100, 200]
            });
            
            notification.onclick = () => {
                window.focus();
                notification.close();
            };
            
            return notification;
        }
        return null;
    }

    let currentNotification = null;

    function handleIncomingCall(callId, callerName, callType, callerId) {
        console.log('📞 Incoming call received:', { callId, callerName, callType, callerId });

        currentCallId = callId;

        // Play ringtone
        const soundPlayed = playRingtone();

        // Show browser notification
        currentNotification = showNotification(callerName, callType);

        // If sound didn't play, show a visual alert
        if (!soundPlayed) {
            showToast('📞 Incoming call from ' + callerName, 'info');
        }

        // Use WhatsApp-style overlay instead of old dialog
        ChatCallApp.handleIncoming(callId, callerName, callType);
    }

    function acceptCall() {
        // Stop ringtone
        stopRingtone();
        if (currentNotification) currentNotification.close();
        ChatCallApp.accept();
    }

    function declineCall() {
        // Stop ringtone
        stopRingtone();
        if (currentNotification) currentNotification.close();
        ChatCallApp.decline();
        currentCallId = null;
    }

    // Initialize Echo listener
    document.addEventListener('DOMContentLoaded', () => {
        // Check if sound was previously enabled
        if (localStorage.getItem('callSoundEnabled') === 'true') {
            soundEnabled = true;
            console.log('✅ Sound previously enabled');
        } else if (localStorage.getItem('soundBannerDismissed') !== 'true') {
            // Show banner if not dismissed
            document.getElementById('enableSoundBanner').style.display = 'block';
        }

        if (typeof window.Echo !== 'undefined') {
            console.log("✅ Call Listener: Starting Private Channel setup for user " + currentUserId);
            
            window.Echo.private(`users.${currentUserId}`) 
                .listen('IncomingCallEvent', (e) => {
                    console.log('📡 IncomingCallEvent received:', e);
                    handleIncomingCall(e.callId, e.callerName, e.callType, e.callerId);
                })
                .error((error) => {
                    console.error("❌ Echo Subscription Error:", error);
                });
        } else {
            console.error("❌ Laravel Echo is not initialized.");
        }
    });

    // Cleanup on page unload
    window.addEventListener('beforeunload', () => {
        stopRingtone();
    });

    // Add CSS animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(400px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(400px); opacity: 0; }
        }
    `;
    document.head.appendChild(style);


    // ✅ Update read receipts - only check last few unread sent messages to avoid flooding network
function updateReadReceipts() {
    const sentMessages = document.querySelectorAll('.message-bubble.sent');
    if (!sentMessages.length) return;

    // Only check the last 5 sent messages that haven't been marked as read yet
    const unreadSent = [];
    for (let i = sentMessages.length - 1; i >= 0 && unreadSent.length < 5; i--) {
        const msg = sentMessages[i];
        const statusEl = msg.querySelector('.message-status');
        // Skip if already marked as read (has tick-read class)
        if (statusEl && statusEl.querySelector('.tick-read')) continue;
        const messageId = msg.getAttribute('data-message-id');
        if (messageId) unreadSent.push({ el: msg, id: messageId });
    }

    if (!unreadSent.length) return;

    // Process one at a time with sequential fetches to avoid flooding
    (async function() {
        for (const item of unreadSent) {
            try {
                const response = await fetch(`/groups/messages/${item.id}/read-count`);
                const data = await response.json();
                if (data.success && data.read_count > 0) {
                    const statusEl = item.el.querySelector('.message-status');
                    if (statusEl) {
                        statusEl.innerHTML = '<span class="tick-read"><svg viewBox="0 0 16 11" width="16" height="11"><path d="M11.071.653a.457.457 0 0 0-.304-.102.493.493 0 0 0-.381.178l-6.19 7.636-2.011-2.175a.463.463 0 0 0-.336-.153.457.457 0 0 0-.344.146.441.441 0 0 0-.101.317c0 .12.045.229.134.327l2.39 2.588a.459.459 0 0 0 .312.153.484.484 0 0 0 .384-.187L11.1 1.27a.469.469 0 0 0 .109-.293.441.441 0 0 0-.138-.324z" fill="#53BDEB"/><path d="M14.871.653a.457.457 0 0 0-.304-.102.493.493 0 0 0-.381.178l-6.19 7.636-1.2-1.3-.66.756 1.48 1.603a.459.459 0 0 0 .312.153.484.484 0 0 0 .384-.187L14.9 1.27a.469.469 0 0 0 .109-.293.441.441 0 0 0-.138-.324z" fill="#53BDEB"/></svg></span>';
                    }
                }
            } catch (error) {
                // Silently ignore - don't spam console
            }
        }
    })();
}

// Call this every 10 seconds (not 5) to reduce load
setInterval(updateReadReceipts, 10000);

// ====== Voice Transcription ======
const transcriptionCache = {};

function transcribeVoiceNote(button, voiceUrl, messageId) {
    const bubble = button.closest('.voice-note-bubble');
    let transcriptDiv = document.getElementById('transcript-' + messageId);

    if (!transcriptDiv) {
        transcriptDiv = document.createElement('div');
        transcriptDiv.className = 'voice-transcript';
        transcriptDiv.id = 'transcript-' + messageId;
        bubble.insertAdjacentElement('afterend', transcriptDiv);
    }

    // Toggle if already shown
    if (transcriptDiv.classList.contains('show') && transcriptDiv.textContent.trim() !== '') {
        transcriptDiv.classList.remove('show');
        return;
    }

    // Check cache
    if (transcriptionCache[messageId]) {
        transcriptDiv.textContent = transcriptionCache[messageId];
        transcriptDiv.classList.add('show');
        return;
    }

    // Show loading state
    button.classList.add('transcribing');
    transcriptDiv.innerHTML = '<span class="transcript-loading"><i class="fa fa-spinner fa-spin"></i> Transcribing...</span>';
    transcriptDiv.classList.add('show');

    fetch('/messages/transcribe-voice', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            voice_url: voiceUrl,
            message_id: messageId
        })
    })
    .then(response => response.json())
    .then(data => {
        button.classList.remove('transcribing');
        if (data.success && data.transcription) {
            transcriptionCache[messageId] = data.transcription;
            transcriptDiv.textContent = data.transcription;
        } else {
            const errMsg = data.error || 'Could not transcribe this voice note';
            transcriptDiv.innerHTML = '<span class="transcript-error">' + errMsg + '</span>';
        }
    })
    .catch(error => {
        console.error('Transcription error:', error);
        button.classList.remove('transcribing');
        transcriptDiv.innerHTML = '<span class="transcript-error">Transcription failed. Try again.</span>';
    });
}

function addTranscribeButtonsToExistingVoiceNotes() {
    document.querySelectorAll('.voice-note-bubble').forEach(bubble => {
        if (bubble.querySelector('.voice-transcribe-btn')) return;

        const voiceUrl = bubble.getAttribute('onclick');
        if (!voiceUrl) return;

        const urlMatch = voiceUrl.match(/playVoiceNote\(this,\s*'([^']+)'\)/);
        if (!urlMatch) return;

        const messageId = bubble.getAttribute('data-message-id');
        if (!messageId) return;

        const transcribeBtn = document.createElement('button');
        transcribeBtn.className = 'voice-transcribe-btn';
        transcribeBtn.title = 'Transcribe voice note';
        transcribeBtn.innerHTML = '<i class="fa-solid fa-language"></i> Transcribe';
        transcribeBtn.onclick = function(e) {
            e.stopPropagation();
            transcribeVoiceNote(this, urlMatch[1], messageId);
        };
        bubble.appendChild(transcribeBtn);

        const transcriptDiv = document.createElement('div');
        transcriptDiv.className = 'voice-transcript';
        transcriptDiv.id = 'transcript-' + messageId;
        bubble.insertAdjacentElement('afterend', transcriptDiv);
    });
}

// Run on load and periodically for dynamically added voice notes
document.addEventListener('DOMContentLoaded', function() {
    addTranscribeButtonsToExistingVoiceNotes();
    setInterval(addTranscribeButtonsToExistingVoiceNotes, 3000);
});
</script>
@endif
@endsection

</body>
</html>

