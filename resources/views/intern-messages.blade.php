<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Intern Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
        }

        /* Header Navigation */
        .header-nav {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
            margin-bottom: 20px;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 25px;
        }

        .nav-brand {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
        }

        .nav-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .nav-btn {
            background: #4299e1;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .nav-btn:hover {
            background: #3182ce;
            transform: translateY(-1px);
        }

        .logout-btn {
            background: #f56565;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .logout-btn:hover {
            background: #e53e3e;
            transform: translateY(-1px);
        }

        /* Main Container */
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .chat-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            height: 600px;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .chat-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .chat-header p {
            margin: 5px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }

        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background: #f8fafc;
        }

        .message {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .message.sent {
            flex-direction: row-reverse;
        }

        .message-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
            flex-shrink: 0;
        }

        .admin-avatar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .intern-avatar {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        }

        .message-content {
            max-width: 70%;
            background: white;
            padding: 12px 16px;
            border-radius: 18px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .message.sent .message-content {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .message-text {
            margin: 0;
            line-height: 1.4;
            word-wrap: break-word;
        }

        .message-time {
            font-size: 11px;
            opacity: 0.7;
            margin-top: 5px;
            text-align: right;
        }

        .message.received .message-time {
            text-align: left;
        }

        .message-form {
            background: white;
            padding: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .form-group {
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }

        .message-input {
            flex: 1;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 25px;
            font-size: 14px;
            resize: none;
            min-height: 20px;
            max-height: 100px;
            font-family: inherit;
            transition: border-color 0.3s ease;
        }

        .message-input:focus {
            outline: none;
            border-color: #667eea;
        }

        .send-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 18px;
        }

        .send-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .send-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .no-messages {
            text-align: center;
            color: #718096;
            font-style: italic;
            margin-top: 50px;
        }

        .typing-indicator {
            display: none;
            padding: 10px 0;
            color: #718096;
            font-style: italic;
            font-size: 14px;
        }

        /* Scrollbar Styling */
        .messages-container::-webkit-scrollbar {
            width: 6px;
        }

        .messages-container::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .messages-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .messages-container::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 0 10px;
            }

            .chat-container {
                height: 500px;
            }

            .message-content {
                max-width: 85%;
            }

            .nav-container {
                padding: 0 15px;
            }

            .nav-brand {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Header Navigation -->
    <div class="header-nav">
        <div class="nav-container">
            <div class="nav-brand">💼 Intern Portal</div>
            <div class="nav-actions">
                <a href="{{ route('intern.dashboard') }}" class="nav-btn">
                    🏠 Dashboard
                </a>
                <form action="{{ route('intern.logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn">
                        🚪 Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="chat-container">
            <div class="chat-header">
                <h2>💬 Admin Chat</h2>
                <p>Direct conversation with your administrator</p>
            </div>

            <div class="messages-container" id="messagesContainer">
                @if($messages->count() > 0)
                    @foreach($messages as $message)
                        <div class="message {{ $message->sender_type === 'intern' ? 'sent' : 'received' }}">
                            <div class="message-avatar {{ $message->sender_type === 'admin' ? 'admin-avatar' : 'intern-avatar' }}">
                                {{ $message->sender_type === 'admin' ? 'A' : 'I' }}
                            </div>
                            <div class="message-content">
                                <p class="message-text">{{ $message->content }}</p>
                                <div class="message-time">
                                    {{ $message->created_at->format('M j, Y g:i A') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="no-messages">
                        <p>No messages yet. Start a conversation with your admin!</p>
                    </div>
                @endif
            </div>

            <div class="typing-indicator" id="typingIndicator">
                Admin is typing...
            </div>

            <div class="message-form">
                <form id="messageForm" action="{{ route('intern.messages.send') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <textarea 
                            name="message" 
                            id="messageInput"
                            class="message-input" 
                            placeholder="Type your message here..."
                            required
                            rows="1"
                        ></textarea>
                        <button type="submit" class="send-btn" id="sendBtn">
                            ➤
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messagesContainer = document.getElementById('messagesContainer');
            const messageForm = document.getElementById('messageForm');
            const messageInput = document.getElementById('messageInput');
            const sendBtn = document.getElementById('sendBtn');

            // Auto-scroll to bottom
            function scrollToBottom() {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            // Initial scroll to bottom
            scrollToBottom();

            // Auto-resize textarea
            messageInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 100) + 'px';
            });

            // Handle Enter key (send message)
            messageInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    messageForm.dispatchEvent(new Event('submit'));
                }
            });

            // Handle form submission
            messageForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const message = messageInput.value.trim();
                if (!message) return;

                // Disable send button
                sendBtn.disabled = true;
                sendBtn.innerHTML = '⏳';

                // Create FormData
                const formData = new FormData(this);

                // Send AJAX request
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Add message to chat
                        addMessageToChat(data.message, true);
                        
                        // Clear input
                        messageInput.value = '';
                        messageInput.style.height = 'auto';
                        
                        // Scroll to bottom
                        scrollToBottom();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to send message. Please try again.');
                })
                .finally(() => {
                    // Re-enable send button
                    sendBtn.disabled = false;
                    sendBtn.innerHTML = '➤';
                });
            });

            // Add message to chat UI
            function addMessageToChat(message, isSent = false) {
                const messageDiv = document.createElement('div');
                messageDiv.className = `message ${isSent ? 'sent' : 'received'}`;
                
                const now = new Date();
                const timeString = now.toLocaleDateString('en-US', { 
                    month: 'short', 
                    day: 'numeric', 
                    year: 'numeric' 
                }) + ' ' + now.toLocaleTimeString('en-US', { 
                    hour: 'numeric', 
                    minute: '2-digit',
                    hour12: true 
                });

                messageDiv.innerHTML = `
                    <div class="message-avatar ${isSent ? 'intern-avatar' : 'admin-avatar'}">
                        ${isSent ? 'I' : 'A'}
                    </div>
                    <div class="message-content">
                        <p class="message-text">${message.content}</p>
                        <div class="message-time">${timeString}</div>
                    </div>
                `;

                // Remove "no messages" placeholder if it exists
                const noMessages = messagesContainer.querySelector('.no-messages');
                if (noMessages) {
                    noMessages.remove();
                }

                messagesContainer.appendChild(messageDiv);
            }

            // Real-time message polling
            let lastMessageId = {{ $messages->max('id') ?? 0 }};
            
            function pollForNewMessages() {
                fetch("{{ route('api.intern.messages.new') }}?last_message_id=" + lastMessageId, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.messages && data.messages.length > 0) {
                        data.messages.forEach(function(message) {
                            if (message.id > lastMessageId) {
                                // Only add messages from admin (not our own)
                                if (message.sender_type === 'admin') {
                                    addMessageToChat(message, false);
                                }
                                lastMessageId = Math.max(lastMessageId, message.id);
                            }
                        });
                        scrollToBottom();
                    }
                })
                .catch(error => {
                    console.log('Error polling for new messages:', error);
                });
            }

            // Poll every 2 seconds
            setInterval(pollForNewMessages, 2000);
        });
    </script>

    @if(session('success'))
        <script>
            // Show success message if needed
            console.log('{{ session('success') }}');
        </script>
    @endif
</body>
</html>