@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3" style="color: #5D2B4C;">
                    <i class="fas fa-comments me-2"></i>Chat Support
                </h2>
                <p class="lead text-muted">Get help from our customer service team</p>
            </div>

            <!-- Chat Container -->
            <div class="card border-0 shadow-lg" style="border-radius: 20px; height: 600px;">
                <div class="card-header bg-primary text-white border-0" style="border-radius: 20px 20px 0 0;">
                    <div class="d-flex align-items-center">
                        <div class="avatar bg-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                            <i class="fas fa-headset text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Bona's Flower Shop Support</h6>
                            <small class="opacity-75">
                                <span class="badge bg-info ms-1" id="connection-status">Real-time Active</span>
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0 d-flex flex-column" style="height: 500px;">
                    <!-- Messages Container -->
                    <div class="flex-grow-1 p-3" id="messages-container" style="overflow-y: auto;1234 max-height: 400px;">
                        @if($chat && $chat->messages->count() > 0)
                            @foreach($chat->messages as $message)
                                <div class="message {{ $message->user_id === auth()->id() ? 'message-outgoing' : 'message-incoming' }} mb-3">
                                    <div class="message-content">
                                        <div class="message-bubble {{ $message->user_id === auth()->id() ? 'bg-primary text-white' : 'bg-light' }}">
                                            <p class="mb-1">{{ $message->message }}</p>
                                            <small class="opacity-75">{{ $message->created_at->format('g:i A') }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-comments" style="font-size: 3rem; color: #CFB8BE;"></i>
                                <h5 class="mt-3" style="color: #5D2B4C;">Start a conversation</h5>
                                <p class="text-muted">Send us a message and we'll get back to you as soon as possible.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Message Input -->
                    <div class="border-top p-3">
                        <form id="chat-form" class="d-flex">
                            <input type="text" class="form-control me-2" id="message-input" placeholder="Type your message..." required>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Audio for notifications -->
            <audio id="notification-sound" preload="auto">
                <source src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIG2m98OScTgwOUarm7blmGgU7k9n1unEiBC13yO/eizEIHWq+8+OWT" type="audio/wav">
            </audio>

            <!-- Chat Guidelines -->
            <div class="card border-0 shadow-lg mt-4" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="fw-bold mb-0" style="color: #5D2B4C;">Chat Guidelines</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <i class="fas fa-clock" style="font-size: 2rem; color: #5D2B4C;"></i>
                            <h6 class="mt-2" style="color: #5D2B4C;">Response Time</h6>
                            <p class="text-muted small">We typically respond within 15-30 minutes during business hours.</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="fas fa-info-circle" style="font-size: 2rem; color: #5D2B4C;"></i>
                            <h6 class="mt-2" style="color: #5D2B4C;">Business Hours</h6>
                            <p class="text-muted small">Monday - Friday: 9:00 AM - 6:00 PM<br>Saturday: 9:00 AM - 4:00 PM</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="fas fa-phone" style="font-size: 2rem; color: #5D2B4C;"></i>
                            <h6 class="mt-2" style="color: #5D2B4C;">Urgent Matters</h6>
                            <p class="text-muted small">For urgent orders or issues, please call us directly at + 567 8900.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.message {
    display: flex;
    margin-bottom: 1rem;
}

.message-incoming {
    justify-content: flex-start;
}

.message-outgoing {
    justify-content: flex-end;
}

.message-content {
    max-width: 70%;
}

.message-bubble {
    padding: 12px 16px;
    border-radius: 18px;
    position: relative;
}

.message-incoming .message-bubble {
    border-bottom-left-radius: 4px;
}

.message-outgoing .message-bubble {
    border-bottom-right-radius: 4px;
}

#messages-container::-webkit-scrollbar {
    width: 6px;
}

#messages-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

#messages-container::-webkit-scrollbar-thumb {
    background: #CFB8BE;
    border-radius: 3px;
}

#messages-container::-webkit-scrollbar-thumb:hover {
    background: #5D2B4C;
}

/* Typing indicator styles */
.typing-dots {
    display: flex;
    align-items: center;
    gap: 4px;
    margin-bottom: 8px;
}

.typing-dots span {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #6c757d;
    animation: typing 1.4s infinite ease-in-out;
}

.typing-dots span:nth-child(1) {
    animation-delay: -0.32s;
}

.typing-dots span:nth-child(2) {
    animation-delay: -0.16s;
}

@keyframes typing {
    0%, 80%, 100% {
        transform: scale(0.8);
        opacity: 0.5;
    }
    40% {
        transform: scale(1);
        opacity: 1;
    }
}

/* Notification styles */
.alert {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border: none;
    border-radius: 10px;
}

.alert-info {
    background: linear-gradient(135deg, #17a2b8, #138496);
    color: white;
}

.alert-danger {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
}

/* Message animations */
.message {
    animation: messageSlideIn 0.3s ease-out;
}

@keyframes messageSlideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Online/offline status animation */
.badge.bg-success {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message-input');
    const messagesContainer = document.getElementById('messages-container');
    let lastMessageId = {{ $chat && $chat->messages->count() > 0 ? $chat->messages->last()->id : 0 }};
    let isTyping = false;
    let typingTimer;

    // Auto-scroll to bottom
    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Scroll to bottom on page load
    scrollToBottom();

    // Show typing indicator
    function showTypingIndicator() {
        let typingDiv = document.getElementById('typing-indicator');
        if (!typingDiv) {
            typingDiv = document.createElement('div');
            typingDiv.id = 'typing-indicator';
            typingDiv.className = 'message message-incoming mb-3';
            typingDiv.innerHTML = `
                <div class="message-content">
                    <div class="message-bubble bg-light">
                        <div class="typing-dots">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <small class="opacity-75">Support is typing...</small>
                    </div>
                </div>
            `;
            messagesContainer.appendChild(typingDiv);
        }
        typingDiv.style.display = 'block';
        scrollToBottom();
    }

    // Hide typing indicator
    function hideTypingIndicator() {
        const typingDiv = document.getElementById('typing-indicator');
        if (typingDiv) {
            typingDiv.style.display = 'none';
        }
    }

    // Handle form submission
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const message = messageInput.value.trim();
        if (!message) return;

        // Add message to UI immediately
        addMessageToUI(message, true);

        // Clear input
        messageInput.value = '';

        // Scroll to bottom
        scrollToBottom();

        // Send message to server using AJAX
        $.ajax({
            url: '{{ route("chat.sendMessage") }}',
            method: 'POST',
            data: {
                message: message,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    console.log('Message sent successfully');

                    // Show success indicator
                    const lastMessage = messagesContainer.lastElementChild;
                    if (lastMessage) {
                        const checkmark = document.createElement('span');
                        checkmark.className = 'text-success ms-2';
                        checkmark.innerHTML = '<i class="fas fa-check-circle"></i>';
                        lastMessage.querySelector('.message-bubble').appendChild(checkmark);

                        // Remove checkmark after 3 seconds
                        setTimeout(() => {
                            if (checkmark.parentNode) {
                                checkmark.remove();
                            }
                        }, 3000);
                    }

                    // Update last message ID
                    if (response.data && response.data.id) {
                        lastMessageId = Math.max(lastMessageId, response.data.id);
                    }
                } else {
                    console.error('Error sending message:', response.message);
                    showError('Failed to send message. Please try again.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                showError('Failed to send message. Please try again.');
            }
        });
    });

    // Function to add message to UI
    function addMessageToUI(message, isOutgoing = true, messageId = null, timestamp = null) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${isOutgoing ? 'message-outgoing' : 'message-incoming'} mb-3`;

        if (messageId) {
            messageDiv.setAttribute('data-message-id', messageId);
        }

        const timeString = timestamp || new Date().toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });

        messageDiv.innerHTML = `
            <div class="message-content">
                <div class="message-bubble ${isOutgoing ? 'bg-primary text-white' : 'bg-light'}">
                    <p class="mb-1">${message}</p>
                    <small class="opacity-75">${timeString}</small>
                </div>
            </div>
        `;

        messagesContainer.appendChild(messageDiv);
        scrollToBottom();
    }

    // Check for new messages every 2 seconds
    function checkForNewMessages() {
        $.ajax({
            url: '{{ route("chat.getNewMessages") }}',
            method: 'GET',
            data: {
                last_message_id: lastMessageId
            },
            success: function(response) {
                if (response.success && response.new_messages.length > 0) {
                    response.new_messages.forEach(function(msg) {
                        // Only add if it's not from the current user (to avoid duplicates)
                        if (msg.user_id !== {{ auth()->id() }}) {
                            addMessageToUI(msg.message, false, msg.id, msg.created_at);
                            lastMessageId = Math.max(lastMessageId, msg.id);

                            // Show notification
                            showNotification('New message from support team');
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error checking for new messages:', error);
            }
        });
    }

        // Show notification
    function showNotification(message) {
        // Play notification sound
        const audio = document.getElementById('notification-sound');
        if (audio) {
            audio.currentTime = 0;
            audio.play().catch(e => console.log('Audio play failed:', e));
        }

        // Check if browser supports notifications
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification('Bona\'s Flower Shop', {
                body: message,
                icon: '/favicon.ico'
            });
        }

        // Also show in-page notification
        showInPageNotification(message);
    }

    // Show in-page notification
    function showInPageNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'alert alert-info alert-dismissible fade show position-fixed';
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            <i class="fas fa-bell me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(notification);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // Show error message
    function showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'alert alert-danger alert-dismissible fade show position-fixed';
        errorDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        errorDiv.innerHTML = `
            <i class="fas fa-exclamation-triangle me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(errorDiv);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (errorDiv.parentNode) {
                errorDiv.remove();
            }
        }, 5000);
    }

    // Request notification permission
    if ('Notification' in window) {
        Notification.requestPermission();
    }

    // Start checking for new messages
    setInterval(checkForNewMessages, 2000);

    // Initial check
    checkForNewMessages();

    // Update connection status
    function updateConnectionStatus(isActive) {
        const statusBadge = document.getElementById('connection-status');
        if (statusBadge) {
            if (isActive) {
                statusBadge.className = 'badge bg-success ms-1';
                statusBadge.textContent = 'Real-time Active';
            } else {
                statusBadge.className = 'badge bg-warning ms-1';
                statusBadge.textContent = 'Checking...';
            }
        }
    }

    // Update last seen time
    function updateLastSeen() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });

        // Update status badge with last seen time
        const statusBadge = document.getElementById('status-badge');
        if (statusBadge) {
            statusBadge.textContent = `Online (Last seen: ${timeString})`;
        }
    }

    // Update connection status every 5 seconds
    setInterval(() => {
        updateConnectionStatus(true);
        updateLastSeen();
    }, 5000);

    // Initial status update
    updateConnectionStatus(true);

    // Handle input focus to show typing indicator
    messageInput.addEventListener('focus', function() {
        // Simulate typing indicator when user is active
        showTypingIndicator();
        setTimeout(hideTypingIndicator, 2000);
    });

    // Handle input blur to hide typing indicator
    messageInput.addEventListener('blur', function() {
        hideTypingIndicator();
    });

    // Show typing indicator when user is typing
    messageInput.addEventListener('input', function() {
        if (this.value.trim().length > 0) {
            showTypingIndicator();
            clearTimeout(typingTimer);
            typingTimer = setTimeout(hideTypingIndicator, 1000);
        } else {
            hideTypingIndicator();
        }
    });

    // Add enter key support for sending messages
    messageInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            chatForm.dispatchEvent(new Event('submit'));
        }
    });
});
</script>
@endsection
