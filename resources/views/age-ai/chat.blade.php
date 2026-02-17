<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AGE AI - Your Intelligent Assistant</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <link rel="stylesheet" href="{{ asset('css/post.css') }}">

    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">

    <link rel="stylesheet" href="{{ asset('css/finebtn.css') }}">

    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">

    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">
    
    <style>
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
            background: linear-gradient(to bottom right, #eff6ff, #faf5ff);
        }

        .chat-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .chat-header {
            background: linear-gradient(to right, #3b82f6, #a855f7, #ec4899);
            color: white;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .chat-header-content {
            max-width: 800px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .ai-icon {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ai-icon i {
            color: #a855f7;
            font-size: 24px;
        }

        .header-title h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }

        .header-title p {
            margin: 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .btn-clear {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-clear:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }

        .messages-wrapper {
            max-width: 800px;
            margin: 0 auto;
        }

        .message {
            display: flex;
            margin-bottom: 20px;
        }

        .message.user {
            justify-content: flex-end;
        }

        .message.assistant {
            justify-content: flex-start;
        }

        .message-bubble {
            max-width: 80%;
            padding: 15px;
            border-radius: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .message.user .message-bubble {
            background: linear-gradient(to right, #3b82f6, #a855f7);
            color: white;
        }

        .message.assistant .message-bubble {
            background: white;
            color: #1f2937;
        }

        .message-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .message-header i {
            color: #a855f7;
        }

        .message-header span {
            font-weight: 600;
            font-size: 14px;
            color: #a855f7;
        }

        .message-content {
            white-space: pre-wrap;
            line-height: 1.6;
        }

        .message-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .message.assistant .message-footer {
            border-top-color: rgba(0, 0, 0, 0.1);
        }

        .message-time {
            font-size: 12px;
            opacity: 0.7;
        }

        .message-actions {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            background: none;
            border: none;
            padding: 5px;
            cursor: pointer;
            border-radius: 4px;
            transition: background 0.2s;
        }

        .btn-action:hover {
            background: rgba(0, 0, 0, 0.1);
        }

        .btn-action i {
            font-size: 14px;
            color: #6b7280;
        }

        .loading-message {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 20px;
        }

        .loading-bubble {
            background: white;
            padding: 15px;
            border-radius: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .loading-content {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .loading-content i {
            color: #a855f7;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .suggested-questions {
            padding: 0 20px 20px;
        }

        .suggested-wrapper {
            max-width: 800px;
            margin: 0 auto;
        }

        .suggested-title {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 10px;
        }

        .questions-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .question-btn {
            background: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            text-align: left;
            cursor: pointer;
            transition: background 0.2s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            font-size: 14px;
        }

        .question-btn:hover {
            background: #eff6ff;
        }

        .input-area {
            background: white;
            border-top: 1px solid #e5e7eb;
            padding: 15px;
            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.05);
        }

        .input-wrapper {
            max-width: 800px;
            margin: 0 auto;
            display: flex;
            gap: 10px;
        }

        #messageInput {
            flex: 1;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            padding: 12px;
            font-size: 15px;
            resize: none;
            max-height: 120px;
            font-family: inherit;
        }

        #messageInput:focus {
            outline: none;
            border-color: #a855f7;
            box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.1);
        }

        .btn-send {
            background: linear-gradient(to right, #3b82f6, #a855f7);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-send:hover:not(:disabled) {
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
            transform: translateY(-2px);
        }

        .btn-send:disabled {
            background: #d1d5db;
            cursor: not-allowed;
        }

        .disclaimer {
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .chat-header {
                padding: 15px;
            }

            .header-title h1 {
                font-size: 20px;
            }

            .questions-grid {
                grid-template-columns: 1fr;
            }

            .message-bubble {
                max-width: 85%;
            }
        }

        /* AGE AI Icon Styling */
        .age-ai-icon {
            font-weight: 800;
            font-size: 50px;
            line-height: 1;
            background: linear-gradient(90deg, #00BFFF, #FFD700, #FF4500, #8A2BE2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 8px 12px;
            border-radius: 8px;
            position: relative;
        }

        .age-ai-icon:hover {
            transform: scale(1.05);
            background: linear-gradient(90deg, #8A2BE2, #FF4500, #FFD700, #00BFFF);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body>
    <!-- Your msg  navbar content  -->
 @include('layouts.navbar')


    @extends('layouts.app')
    @section('seo_title', 'AGE AI - Your Intelligent Assistant | SupperAge')
    @section('seo_description', 'Chat with AGE AI, your intelligent assistant on SupperAge. Get help, answers, and smart suggestions powered by AI.')
    @section('content')

    <div class="chat-container">
        <!-- Header -->
        <div class="chat-header">
            <div class="chat-header-content">
                <div class="header-left">
                    <div class="ai-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="header-title">
                        <h1 class="age-ai-icon">AGE AI</h1>
                        <p>Your Intelligent Assistant</p>
                    </div>
                </div>
                <button class="btn-clear" onclick="clearChat()">
                    <i class="fas fa-trash"></i> Clear
                </button>
            </div>
        </div>

        <!-- Messages Container -->
        <div class="messages-container" id="messagesContainer">
            <div class="messages-wrapper" id="messagesWrapper">
                <!-- Initial message -->
                <div class="message assistant">
                    <div class="message-bubble">
                        <div class="message-header">
                            <i class="fas fa-robot"></i>
                            <span class="age-ai-icon">AGE AI</span>
                        </div>
                        <div class="message-content">Hello! I'm AGE AI, your intelligent assistant powered by Supperage. How can I help you today? 🤖</div>
                        <div class="message-footer">
                            <span class="message-time" id="initialTime"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Suggested Questions -->
        <div class="suggested-questions" id="suggestedQuestions">
            <div class="suggested-wrapper">
                <p class="suggested-title">Suggested questions:</p>
                <div class="questions-grid">
                    <button class="question-btn" onclick="setSuggested(this)">How do I create a group?</button>
                    <button class="question-btn" onclick="setSuggested(this)">How can I earn money?</button>
                    <button class="question-btn" onclick="setSuggested(this)">How do I get verified?</button>
                    <button class="question-btn" onclick="setSuggested(this)">Tell me about Supperage features</button>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="input-area">
            <div class="input-wrapper">
                <textarea id="messageInput" placeholder="Ask AGE AI anything..." rows="1"></textarea>
                <button class="btn-send" id="sendBtn" onclick="sendMessage()">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
            <p class="disclaimer">AGE AI can make mistakes. Consider checking important information.</p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('myjs/bar.js') }}"></script> <!-- this should come last -->
    <script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
<script src="{{ asset('myjs/searchuser.js') }}"></script>


    <script>
        const csrfToken = '{{ csrf_token() }}';
        let messageCount = 1;

        // Set initial time
        $(document).ready(function() {
            $('#initialTime').text(getCurrentTime());
            
            // Auto-resize textarea
            $('#messageInput').on('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
                updateSendButton();
            });

            // Enter to send
            $('#messageInput').on('keypress', function(e) {
                if (e.which === 13 && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });
        });

        function getCurrentTime() {
            return new Date().toLocaleTimeString('en-US', { 
                hour: 'numeric', 
                minute: '2-digit',
                hour12: true 
            });
        }

        function updateSendButton() {
            const input = $('#messageInput').val().trim();
            $('#sendBtn').prop('disabled', input.length === 0);
        }

        function setSuggested(btn) {
            $('#messageInput').val($(btn).text());
            updateSendButton();
            $('#messageInput').focus();
        }

        function scrollToBottom() {
            const container = $('#messagesContainer');
            container.animate({ scrollTop: container[0].scrollHeight }, 300);
        }

        function sendMessage() {
            const input = $('#messageInput').val().trim();
            if (!input) return;

            // Hide suggested questions after first message
            if (messageCount === 1) {
                $('#suggestedQuestions').hide();
            }
            messageCount++;

            // Add user message
            addUserMessage(input);
            
            // Clear input
            $('#messageInput').val('').css('height', 'auto');
            updateSendButton();

            // Show loading
            showLoading();

            // Send to server
            $.ajax({
                url: '{{ route("age-ai.send") }}',
                method: 'POST',
                data: {
                    message: input,
                    _token: csrfToken
                },
                success: function(response) {
                    hideLoading();
                    if (response.success) {
                        addAssistantMessage(response.response);
                    }
                },
                error: function() {
                    hideLoading();
                    addAssistantMessage("I apologize, but I'm having trouble connecting right now. Please try again in a moment.");
                }
            });
        }

        function addUserMessage(text) {
            const html = `
                <div class="message user">
                    <div class="message-bubble">
                        <div class="message-content">${escapeHtml(text)}</div>
                        <div class="message-footer">
                            <span class="message-time">${getCurrentTime()}</span>
                        </div>
                    </div>
                </div>
            `;
            $('#messagesWrapper').append(html);
            scrollToBottom();
        }

        function addAssistantMessage(text) {
            const html = `
                <div class="message assistant">
                    <div class="message-bubble">
                        <div class="message-header">
                            <i class="fas fa-robot"></i>
                            <span class="age-ai-icon">AGE AI</span>
                        </div>
                        <div class="message-content">${escapeHtml(text)}</div>
                        <div class="message-footer">
                            <span class="message-time">${getCurrentTime()}</span>
                            <div class="message-actions">
                                <button class="btn-action" onclick="copyMessage(this)" title="Copy">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <button class="btn-action" onclick="speakMessage(this)" title="Read Aloud">
                                    <i class="fas fa-volume-up"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            $('#messagesWrapper').append(html);
            scrollToBottom();
        }

        function showLoading() {
            const html = `
                <div class="loading-message" id="loadingMessage">
                    <div class="loading-bubble">
                        <div class="loading-content">
                            <i class="fas fa-robot"></i>
                            <span class="age-ai-icon">AGE AI is thinking...</span>
                        </div>
                    </div>
                </div>
            `;
            $('#messagesWrapper').append(html);
            scrollToBottom();
        }

        function hideLoading() {
            $('#loadingMessage').remove();
        }

        function clearChat() {
            if (confirm('Clear all chat history?')) {
                $.post('{{ route("age-ai.clear") }}', { _token: csrfToken }, function() {
                    $('#messagesWrapper').html(`
                        <div class="message assistant">
                            <div class="message-bubble">
                                <div class="message-header">
                                    <i class="fas fa-robot"></i>
                                    <span class="age-ai-icon">AGE AI</span>
                                </div>
                                <div class="message-content">Chat cleared! How can I help you today? 🤖</div>
                                <div class="message-footer">
                                    <span class="message-time">${getCurrentTime()}</span>
                                </div>
                            </div>
                        </div>
                    `);
                    messageCount = 1;
                    $('#suggestedQuestions').show();
                });
            }
        }

        function copyMessage(btn) {
            const text = $(btn).closest('.message-bubble').find('.message-content').text();
            navigator.clipboard.writeText(text).then(() => {
                const icon = $(btn).find('i');
                icon.removeClass('fa-copy').addClass('fa-check');
                setTimeout(() => {
                    icon.removeClass('fa-check').addClass('fa-copy');
                }, 2000);
            });
        }

        function speakMessage(btn) {
            const text = $(btn).closest('.message-bubble').find('.message-content').text();
            if ('speechSynthesis' in window) {
                const utterance = new SpeechSynthesisUtterance(text);
                window.speechSynthesis.speak(utterance);
            } else {
                alert('Text-to-speech is not supported in your browser.');
            }
        }

        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, m => map[m]).replace(/\n/g, '<br>');
        }
    </script>

    @endsection
</body>
</html>