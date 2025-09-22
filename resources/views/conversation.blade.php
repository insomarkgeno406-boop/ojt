<!DOCTYPE html>
<html>
<head>
    <title>Conversation with {{ $intern->first_name }} {{ $intern->last_name }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            overflow: hidden;
        }

        .chat-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        /* Header */
        .chat-header {
            background: #0084ff;
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .back-button {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .back-button:hover {
            background: rgba(255,255,255,0.3);
            transform: translateX(-2px);
        }

        .user-info {
            flex: 1;
        }

        .user-name {
            font-weight: 600;
            font-size: 18px;
        }

        .user-status {
            font-size: 12px;
            opacity: 0.9;
        }

        /* Messages Area */
        .messages-area {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background: #f0f2f5;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(0,132,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(0,132,255,0.1) 0%, transparent 50%);
        }

        .message {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-end;
            gap: 8px;
            max-width: 70%;
        }

        .message.sent {
            margin-left: auto;
            flex-direction: row-reverse;
        }

        .message.received {
            margin-right: auto;
        }

        .message-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #0084ff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
            flex-shrink: 0;
        }

        .message.received .message-avatar {
            background: #e4e6eb;
            color: #65676b;
        }

        .message-content {
            background: white;
            padding: 12px 16px;
            border-radius: 18px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
            position: relative;
            word-wrap: break-word;
            max-width: 100%;
        }

        .message.sent .message-content {
            background: #0084ff;
            color: white;
        }

        .message.received .message-content {
            background: white;
            color: #050505;
        }

        .message-time {
            font-size: 11px;
            opacity: 0.7;
            margin-top: 4px;
            text-align: right;
        }

        .message.sent .message-time {
            text-align: right;
        }

        .message.received .message-time {
            text-align: left;
        }

        /* Input Area */
        .input-area {
            background: white;
            padding: 15px 20px;
            border-top: 1px solid #e4e6eb;
            display: flex;
            align-items: flex-end;
            gap: 12px;
        }

        .message-input {
            flex: 1;
            border: none;
            outline: none;
            resize: none;
            font-family: inherit;
            font-size: 15px;
            line-height: 1.4;
            max-height: 120px;
            min-height: 20px;
            padding: 8px 12px;
            background: #f0f2f5;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .message-input:focus {
            background: white;
            box-shadow: 0 0 0 2px #0084ff;
        }

        .send-button {
            background: #0084ff;
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .send-button:hover {
            background: #0073e6;
            transform: scale(1.05);
        }

        .send-button:active {
            transform: scale(0.95);
        }

        .send-button svg {
            width: 18px;
            height: 18px;
        }

        /* Scrollbar Styling */
        .messages-area::-webkit-scrollbar {
            width: 6px;
        }

        .messages-area::-webkit-scrollbar-track {
            background: transparent;
        }

        .messages-area::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.2);
            border-radius: 3px;
        }

        .messages-area::-webkit-scrollbar-thumb:hover {
            background: rgba(0,0,0,0.3);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .chat-container {
                height: 100vh;
                max-width: 100%;
            }
            
            .message {
                max-width: 85%;
            }
            
            .chat-header {
                padding: 12px 15px;
            }
            
            .input-area {
                padding: 12px 15px;
            }
        }

        /* Typing indicator */
        .typing-indicator {
            display: none;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            color: #65676b;
            font-size: 14px;
        }

        .typing-dots {
            display: flex;
            gap: 2px;
        }

        .typing-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #65676b;
            animation: typing 1.4s infinite ease-in-out;
        }

        .typing-dot:nth-child(1) { animation-delay: -0.32s; }
        .typing-dot:nth-child(2) { animation-delay: -0.16s; }

        @keyframes typing {
            0%, 80%, 100% { transform: scale(0.8); opacity: 0.5; }
            40% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body>
<div class="chat-container">
    <!-- Header -->
    <div class="chat-header">
        <a href="{{ url()->previous() }}" class="back-button">
            ← Back
        </a>
        <div class="user-info">
            <div class="user-name">{{ $intern->first_name }} {{ $intern->last_name }}</div>
            <div class="user-status">Online</div>
        </div>
    </div>

    <!-- Messages Area -->
    <div class="messages-area" id="messages-area">
        @foreach ($messages as $msg)
            <div class="message {{ $msg->sender_type === 'admin' ? 'sent' : 'received' }}">
                <div class="message-avatar">
                    {{ strtoupper(substr($msg->sender_name, 0, 1)) }}
                </div>
                <div class="message-content">
                    <div class="message-text">{{ $msg->content }}</div>
                    <div class="message-time">
                        {{ \Carbon\Carbon::parse($msg->created_at)->format('g:i A') }}
                    </div>
                </div>
            </div>
        @endforeach
        
        <!-- Typing indicator -->
        <div class="typing-indicator" id="typing-indicator">
            <div class="typing-dots">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
            <span>typing...</span>
        </div>
    </div>

    <!-- Input Area -->
    <div class="input-area">
        <textarea 
            class="message-input" 
            id="message-input"
            placeholder="Type a message..."
            rows="1"
        ></textarea>
        <button class="send-button" id="send-button" type="button">
            <svg fill="currentColor" viewBox="0 0 24 24">
                <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
            </svg>
        </button>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        const messageInput = $('#message-input');
        const sendButton = $('#send-button');
        const messagesArea = $('#messages-area');
        const typingIndicator = $('#typing-indicator');
        
        // Auto-resize textarea
        messageInput.on('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });

        // Send message on Enter (Shift+Enter for new line)
        messageInput.on('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        // Send button click
        sendButton.on('click', sendMessage);

        function sendMessage() {
            const content = messageInput.val().trim();
            if (!content) return;

            // Show typing indicator briefly
            typingIndicator.show();
            
            // Add message to chat immediately (optimistic UI)
            const messageHtml = `
                <div class="message sent">
                    <div class="message-avatar">A</div>
                    <div class="message-content">
                        <div class="message-text">${content}</div>
                        <div class="message-time">${new Date().toLocaleTimeString('en-US', {hour: 'numeric', minute: '2-digit'})}</div>
                    </div>
                </div>
            `;
            messagesArea.append(messageHtml);
            
            // Clear input and reset height
            messageInput.val('').height('auto');
            
            // Scroll to bottom
            messagesArea.scrollTop(messagesArea[0].scrollHeight);
            
            // Hide typing indicator
            setTimeout(() => typingIndicator.hide(), 1000);

            // Send to server
            $.ajax({
                url: "{{ route('messages.send') }}",
                method: "POST",
                data: {
                    receiver_id: "{{ $intern->id }}",
                    content: content,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Message sent');
                },
                error: function() {
                    // Show error message
                    alert('Failed to send message. Please try again.');
                }
            });
        }

        // Scroll to bottom on load
        messagesArea.scrollTop(messagesArea[0].scrollHeight);
        
        // Focus input on load
        messageInput.focus();

        // Real-time message polling
        let lastMessageId = {{ $messages->max('id') ?? 0 }};
        
        function pollForNewMessages() {
            $.ajax({
                url: "{{ route('api.messages.new', $intern->id) }}",
                method: "GET",
                data: { last_message_id: lastMessageId },
                success: function(response) {
                    if (response.messages && response.messages.length > 0) {
                        response.messages.forEach(function(message) {
                            if (message.id > lastMessageId) {
                                // Only add messages from the intern (not our own)
                                if (message.sender_type === 'intern') {
                                    const messageHtml = `
                                        <div class="message received">
                                            <div class="message-avatar">${message.sender_name.charAt(0)}</div>
                                            <div class="message-content">
                                                <div class="message-text">${message.content}</div>
                                                <div class="message-time">${new Date(message.created_at).toLocaleTimeString('en-US', {hour: 'numeric', minute: '2-digit'})}</div>
                                            </div>
                                        </div>
                                    `;
                                    messagesArea.append(messageHtml);
                                    messagesArea.scrollTop(messagesArea[0].scrollHeight);
                                }
                                lastMessageId = Math.max(lastMessageId, message.id);
                            }
                        });
                    }
                },
                error: function() {
                    console.log('Error polling for new messages');
                }
            });
        }

        // Poll every 2 seconds
        setInterval(pollForNewMessages, 2000);
    });
</script>
</body>
</html>
