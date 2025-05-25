<?php
session_start();

// Check if user is authenticated and has either business or influencer role
if (!isset($_SESSION['authenticated']) || 
    !in_array($_SESSION['user_role'], ['business', 'influencer']) || 
    $_SESSION['status'] != 1) {
    header('Location: ../auth/login.php');
    exit;
}

// Determine user type for navigation
$isBusiness = $_SESSION['user_role'] === 'business';
$dashboardLink = $isBusiness ? 'business_dashboard.php' : 'influencer_dashboard.php';
$campaignsLink = $isBusiness ? 'campaigns.php' : 'my_campaigns.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - InfluenceON</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .chat-container {
            display: grid;
            grid-template-columns: 320px 1fr;
            height: calc(100vh - 64px);
            background: var(--bg-white);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin: 2rem 0;
        }

        /* Chat List Styles */
        .chat-list {
            border-right: 1px solid rgba(99, 102, 241, 0.1);
            background: var(--bg-light);
            display: flex;
            flex-direction: column;
        }

        .chat-list-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(99, 102, 241, 0.1);
            background: var(--bg-white);
        }

        .chat-list-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }

        .chat-search {
            position: relative;
        }

        .chat-search input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 0.5rem;
            font-size: 0.875rem;
            background: var(--bg-white);
            transition: all 0.2s ease;
        }

        .chat-search input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        .chat-search svg {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            width: 1rem;
            height: 1rem;
            color: var(--text-secondary);
        }

        .chat-list-content {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
        }

        .chat-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
            background: var(--bg-white);
            margin-bottom: 0.5rem;
        }

        .chat-item:hover {
            background: rgba(99, 102, 241, 0.05);
            transform: translateY(-1px);
        }

        .chat-item.active {
            background: rgba(99, 102, 241, 0.1);
            border-left: 3px solid var(--primary);
        }

        .chat-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.25rem;
            position: relative;
        }

        .chat-avatar.online::after {
            content: '';
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 12px;
            height: 12px;
            background: #10B981;
            border: 2px solid var(--bg-white);
            border-radius: 50%;
        }

        .chat-info {
            flex: 1;
            min-width: 0;
        }

        .chat-name {
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .chat-preview {
            font-size: 0.875rem;
            color: var(--text-secondary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .chat-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.25rem;
        }

        .chat-time {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        .chat-badge {
            background: var(--primary);
            color: white;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 1rem;
            min-width: 1.5rem;
            text-align: center;
        }

        /* Chat Conversation Styles */
        .chat-conversation {
            display: flex;
            flex-direction: column;
            background: var(--bg-white);
        }

        .conversation-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(99, 102, 241, 0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
            background: var(--bg-white);
        }

        .conversation-title {
            font-weight: 500;
            color: var(--text-primary);
        }

        .conversation-status {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .conversation-actions {
            margin-left: auto;
            display: flex;
            gap: 0.5rem;
        }

        .action-button {
            padding: 0.5rem;
            border-radius: 0.375rem;
            color: var(--text-secondary);
            transition: all 0.2s ease;
            background: none;
            border: none;
            cursor: pointer;
        }

        .action-button:hover {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
        }

        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            background: var(--bg-light);
        }

        .message {
            display: flex;
            gap: 1rem;
            max-width: 80%;
        }

        .message.sent {
            margin-left: auto;
            flex-direction: row-reverse;
        }

        .message-content {
            background: var(--bg-white);
            padding: 1rem;
            border-radius: 1rem;
            border-top-left-radius: 0;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .message.sent .message-content {
            background: var(--primary);
            color: white;
            border-radius: 1rem;
            border-top-right-radius: 0;
        }

        .message-time {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-top: 0.25rem;
        }

        .message.sent .message-time {
            color: rgba(255, 255, 255, 0.8);
            text-align: right;
        }

        .message-input-container {
            padding: 1.5rem;
            border-top: 1px solid rgba(99, 102, 241, 0.1);
            background: var(--bg-white);
        }

        .message-input-wrapper {
            display: flex;
            gap: 1rem;
            align-items: flex-end;
        }

        .message-input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 0.5rem;
            font-size: 0.875rem;
            resize: none;
            min-height: 40px;
            max-height: 120px;
            transition: all 0.2s ease;
            background: var(--bg-light);
        }

        .message-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
            background: var(--bg-white);
        }

        .send-button {
            padding: 0.75rem;
            border-radius: 0.5rem;
            background: var(--primary);
            color: white;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .send-button:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .send-button svg {
            width: 1.25rem;
            height: 1.25rem;
        }

        .attachment-button {
            padding: 0.75rem;
            border-radius: 0.5rem;
            color: var(--text-secondary);
            background: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .attachment-button:hover {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
        }

        .attachment-button svg {
            width: 1.25rem;
            height: 1.25rem;
        }

        /* Empty State */
        .empty-state {
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            padding: 2rem;
            text-align: center;
            color: var(--text-secondary);
        }

        .empty-state.active {
            display: flex;
        }

        .empty-state svg {
            width: 64px;
            height: 64px;
            margin-bottom: 1rem;
            color: var(--text-secondary);
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 1.25rem;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            font-size: 0.875rem;
            max-width: 300px;
        }
    </style>
</head>
<body>
    <main class="container">
        <div class="chat-container">
            <!-- Chat List -->
            <div class="chat-list">
                <div class="chat-list-header">
                    <h2 class="chat-list-title">Messages</h2>
                    <div class="chat-search">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="search" placeholder="Search messages...">
                    </div>
                </div>
                <div class="chat-list-content">
                    <div class="chat-item active">
                        <div class="chat-avatar online">SJ</div>
                        <div class="chat-info">
                            <div class="chat-name">Sarah Johnson</div>
                            <div class="chat-preview">Looking forward to working on the summer collection campaign!</div>
                        </div>
                        <div class="chat-meta">
                            <div class="chat-time">2m ago</div>
                            <div class="chat-badge">2</div>
                        </div>
                    </div>
                    <div class="chat-item">
                        <div class="chat-avatar">MJ</div>
                        <div class="chat-info">
                            <div class="chat-name">Mike Johnson</div>
                            <div class="chat-preview">I've sent the contract for review</div>
                        </div>
                        <div class="chat-meta">
                            <div class="chat-time">1h ago</div>
                        </div>
                    </div>
                    <div class="chat-item">
                        <div class="chat-avatar online">AL</div>
                        <div class="chat-info">
                            <div class="chat-name">Alex Lee</div>
                            <div class="chat-preview">The content calendar is ready for your approval</div>
                        </div>
                        <div class="chat-meta">
                            <div class="chat-time">3h ago</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Conversation -->
            <div class="chat-conversation">
                <div class="conversation-header">
                    <div class="chat-avatar online">SJ</div>
                    <div>
                        <div class="conversation-title">Sarah Johnson</div>
                        <div class="conversation-status">Online</div>
                    </div>
                    <div class="conversation-actions">
                        <button class="action-button" title="More options">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="1"></circle>
                                <circle cx="19" cy="12" r="1"></circle>
                                <circle cx="5" cy="12" r="1"></circle>
                            </svg>
                        </button>
                        <button class="action-button" title="View profile">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="messages-container">
                    <div class="message">
                        <div class="chat-avatar">SJ</div>
                        <div>
                            <div class="message-content">
                                Hi! I'm excited to collaborate on the summer collection campaign. I've reviewed the brief and have some ideas to discuss.
                            </div>
                            <div class="message-time">10:30 AM</div>
                        </div>
                    </div>

                    <div class="message sent">
                        <div class="chat-avatar">You</div>
                        <div>
                            <div class="message-content">
                                Hello Sarah! That's great to hear. I'd love to hear your ideas. What are you thinking?
                            </div>
                            <div class="message-time">10:32 AM</div>
                        </div>
                    </div>

                    <div class="message">
                        <div class="chat-avatar">SJ</div>
                        <div>
                            <div class="message-content">
                                I was thinking we could create a series of lifestyle content showcasing the collection in different settings. Maybe start with a beach shoot and then transition to urban settings?
                            </div>
                            <div class="message-time">10:33 AM</div>
                        </div>
                    </div>
                </div>

                <div class="message-input-container">
                    <div class="message-input-wrapper">
                        <button class="attachment-button" title="Attach file">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"></path>
                            </svg>
                        </button>
                        <textarea class="message-input" placeholder="Type your message..." rows="1"></textarea>
                        <button class="send-button" title="Send message">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="22" y1="2" x2="11" y2="13"></line>
                                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Empty State (hidden by default) -->
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                <h3>No conversation selected</h3>
                <p>Choose a conversation from the list or start a new one to begin messaging.</p>
            </div>
        </div>
    </main>

    <script>
        // Auto-resize textarea
        const messageInput = document.querySelector('.message-input');
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        // Handle chat item selection
        document.querySelectorAll('.chat-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.chat-item').forEach(chat => {
                    chat.classList.remove('active');
                });
                this.classList.add('active');
                
                // Hide empty state and show conversation
                document.querySelector('.empty-state').classList.remove('active');
                document.querySelector('.chat-conversation').style.display = 'flex';
            });
        });

        // Handle message sending
        const sendButton = document.querySelector('.send-button');
        sendButton.addEventListener('click', function() {
            const message = messageInput.value.trim();
            if (message) {
                // Add message to chat
                const messagesContainer = document.querySelector('.messages-container');
                const newMessage = document.createElement('div');
                newMessage.className = 'message sent';
                newMessage.innerHTML = `
                    <div class="chat-avatar">You</div>
                    <div>
                        <div class="message-content">${message}</div>
                        <div class="message-time">${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</div>
                    </div>
                `;
                messagesContainer.appendChild(newMessage);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;

                // Clear input
                messageInput.value = '';
                messageInput.style.height = 'auto';
            }
        });

        // Handle Enter key to send message
        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendButton.click();
            }
        });

        // Handle search
        const searchInput = document.querySelector('.chat-search input');
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            document.querySelectorAll('.chat-item').forEach(item => {
                const name = item.querySelector('.chat-name').textContent.toLowerCase();
                const preview = item.querySelector('.chat-preview').textContent.toLowerCase();
                if (name.includes(searchTerm) || preview.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html> 