<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot</title>
    <style>
        /* Modern Color Scheme and Variables */
        :root {
            --primary-color: #6366f1;
            --primary-light: #818cf8;
            --primary-dark: #4f46e5;
            --secondary-color: #f0f4ff;
            --accent-color: #c7d2fe;
            --text-color: #1e293b;
            --text-light: #64748b;
            --text-white: #ffffff;
            --bg-color: #ffffff;
            --bg-secondary: #f8fafc;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --radius-sm: 0.375rem;
            --radius: 0.5rem;
            --radius-lg: 1rem;
            --radius-full: 9999px;
            --transition: all 0.3s ease;
        }

        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        body {
            color: var(--text-color);
            line-height: 1.5;
            font-size: 16px;
        }

        /* Chat Container */
        .chat-widget {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            font-size: 16px;
        }

        .chat-container {
            position: absolute;
            bottom: 70px;
            right: 0;
            width: 380px;
            height: 550px;
            background-color: var(--bg-color);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transition: var(--transition);
            transform: translateY(20px) scale(0.95);
            opacity: 0;
            pointer-events: none;
            border: 1px solid var(--border-color);
        }

        .chat-container.active {
            transform: translateY(0) scale(1);
            opacity: 1;
            pointer-events: all;
        }

        /* Chat Header */
        .chat-header {
            padding: 1.25rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--text-white);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .chat-header-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .chat-avatar {
            width: 38px;
            height: 38px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chat-avatar svg {
            width: 22px;
            height: 22px;
            color: var(--text-white);
        }

        .chat-title h3 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .chat-status {
            font-size: 0.75rem;
            opacity: 0.8;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .status-indicator {
            width: 8px;
            height: 8px;
            background-color: #10b981;
            border-radius: var(--radius-full);
        }

        .chat-controls button {
            background: none;
            border: none;
            color: var(--text-white);
            font-size: 1.25rem;
            cursor: pointer;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-full);
            transition: var(--transition);
        }

        .chat-controls button:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Chat Messages */
        .chat-messages {
            flex: 1;
            padding: 1.5rem;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            background-color: var(--bg-secondary);
            scrollbar-width: thin;
            scrollbar-color: var(--border-color) transparent;
        }

        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }

        .chat-messages::-webkit-scrollbar-track {
            background: transparent;
        }

        .chat-messages::-webkit-scrollbar-thumb {
            background-color: var(--border-color);
            border-radius: var(--radius-full);
        }

        .welcome-message {
            background-color: var(--accent-color);
            padding: 1rem 1.25rem;
            border-radius: var(--radius-lg) var(--radius-lg) var(--radius-lg) 0;
            align-self: flex-start;
            max-width: 85%;
            box-shadow: var(--shadow-sm);
            position: relative;
            animation: fadeIn 0.3s ease;
        }

        .welcome-message::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: -8px;
            width: 20px;
            height: 20px;
            background-color: var(--accent-color);
            border-radius: 0 0 20px 0;
            clip-path: polygon(100% 0, 100% 100%, 0 100%);
        }

        .message {
            padding: 1rem 1.25rem;
            border-radius: var(--radius-lg);
            max-width: 85%;
            box-shadow: var(--shadow-sm);
            position: relative;
            animation: fadeIn 0.3s ease;
            line-height: 1.5;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .user-message {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--text-white);
            align-self: flex-end;
            border-radius: var(--radius-lg) var(--radius-lg) 0 var(--radius-lg);
        }

        .user-message::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: -8px;
            width: 20px;
            height: 20px;
            background: var(--primary-dark);
            border-radius: 20px 0 0 0;
            clip-path: polygon(0 0, 100% 100%, 0 100%);
        }

        .bot-message {
            background-color: var(--secondary-color);
            align-self: flex-start;
            border-radius: var(--radius-lg) var(--radius-lg) var(--radius-lg) 0;
        }

        .bot-message::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: -8px;
            width: 20px;
            height: 20px;
            background-color: var(--secondary-color);
            border-radius: 0 0 20px 0;
            clip-path: polygon(100% 0, 100% 100%, 0 100%);
        }

        .message-time {
            font-size: 0.7rem;
            opacity: 0.7;
            margin-top: 0.5rem;
            text-align: right;
        }

        /* Chat Input */
        .chat-input-container {
            padding: 1rem 1.25rem;
            background-color: var(--bg-color);
            border-top: 1px solid var(--border-color);
        }

        .chat-input-wrapper {
            display: flex;
            align-items: center;
            background-color: var(--bg-secondary);
            border-radius: var(--radius-full);
            padding: 0.25rem 0.25rem 0.25rem 1.25rem;
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .chat-input-wrapper:focus-within {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1);
        }

        #chat-form {
            display: flex;
            width: 100%;
            align-items: center;
        }

        #chat-input {
            flex: 1;
            border: none;
            background: none;
            padding: 0.75rem 0;
            outline: none;
            font-size: 0.9375rem;
            color: var(--text-color);
        }

        #chat-input::placeholder {
            color: var(--text-light);
        }

        #send-btn {
            background-color: var(--primary-color);
            color: var(--text-white);
            border: none;
            border-radius: var(--radius-full);
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            margin-left: 0.5rem;
        }

        #send-btn:hover {
            background-color: var(--primary-dark);
            transform: scale(1.05);
        }

        #send-btn:active {
            transform: scale(0.95);
        }

        .send-icon {
            width: 18px;
            height: 18px;
        }

        /* Chat Button */
        .chat-button {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: var(--shadow-lg);
            z-index: 1000;
            transition: var(--transition);
            position: relative;
        }

        .chat-button:hover {
            transform: scale(1.05);
        }

        .chat-button:active {
            transform: scale(0.95);
        }

        .chat-button .chat-icon {
            color: var(--text-white);
            width: 28px;
            height: 28px;
            transition: var(--transition);
        }

        .chat-button.active .chat-icon {
            transform: scale(0);
            opacity: 0;
        }

        .chat-button .close-icon {
            position: absolute;
            color: var(--text-white);
            width: 28px;
            height: 28px;
            transform: scale(0);
            opacity: 0;
            transition: var(--transition);
        }

        .chat-button.active .close-icon {
            transform: scale(1);
            opacity: 1;
        }

        /* Loading indicator */
        .loading {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.75rem 1.25rem;
            background-color: var(--secondary-color);
            border-radius: var(--radius-lg) var(--radius-lg) var(--radius-lg) 0;
            align-self: flex-start;
            max-width: 85%;
            box-shadow: var(--shadow-sm);
            position: relative;
        }

        .loading::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: -8px;
            width: 20px;
            height: 20px;
            background-color: var(--secondary-color);
            border-radius: 0 0 20px 0;
            clip-path: polygon(100% 0, 100% 100%, 0 100%);
        }

        .loading-dot {
            width: 8px;
            height: 8px;
            background-color: var(--primary-color);
            border-radius: 50%;
            opacity: 0.6;
            animation: bounce 1.4s infinite ease-in-out both;
        }

        .loading-dot:nth-child(1) {
            animation-delay: -0.32s;
        }

        .loading-dot:nth-child(2) {
            animation-delay: -0.16s;
        }

        @keyframes bounce {
            0%, 80%, 100% {
                transform: scale(0);
            }
            40% {
                transform: scale(1);
            }
        }

        /* Responsive styles */
        @media (max-width: 480px) {
            .chat-container {
                width: calc(100vw - 40px);
                height: 70vh;
                right: 0;
                bottom: 80px;
            }
            
            .chat-button {
                width: 50px;
                height: 50px;
            }
            
            .chat-button .chat-icon,
            .chat-button .close-icon {
                width: 24px;
                height: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="chat-widget">
        <div class="chat-container" id="chatbot-container">
            <div class="chat-header">
                <div class="chat-header-left">
                    <div class="chat-avatar">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 16v-4"></path>
                            <path d="M12 8h.01"></path>
                        </svg>
                    </div>
                    <div class="chat-title">
                        <h3>AI Assistant</h3>
                        <div class="chat-status">
                            <span class="status-indicator"></span>
                            <span>Online</span>
                        </div>
                    </div>
                </div>
                <div class="chat-controls">
                    <button id="minimize-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="chat-messages" id="chat-messages">
                <div class="welcome-message">
                    <p>ðŸ‘‹ Hi there! How can I help you today?</p>
                    <div class="message-time">Just now</div>
                </div>
            </div>
            <div class="chat-input-container">
                <div class="chat-input-wrapper">
                    <form id="chat-form">
                        <input type="text" id="chat-input" placeholder="Type your message..." autocomplete="off">
                        <button type="submit" id="send-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="send-icon">
                                <line x1="22" y1="2" x2="11" y2="13"></line>
                                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="chat-button" id="chat-button">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="chat-icon">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="close-icon">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatButton = document.getElementById('chat-button');
            const chatContainer = document.getElementById('chatbot-container');
            const minimizeBtn = document.getElementById('minimize-btn');
            const chatForm = document.getElementById('chat-form');
            const chatInput = document.getElementById('chat-input');
            const chatMessages = document.getElementById('chat-messages');
            
            // Toggle chat container visibility
            chatButton.addEventListener('click', function() {
                chatContainer.classList.toggle('active');
                chatButton.classList.toggle('active');
                
                // Focus input when opening
                if (chatContainer.classList.contains('active')) {
                    setTimeout(() => {
                        chatInput.focus();
                    }, 300);
                }
            });
            
            minimizeBtn.addEventListener('click', function() {
                chatContainer.classList.remove('active');
                chatButton.classList.remove('active');
            });
            
            // Handle form submission
            chatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const message = chatInput.value.trim();
                if (!message) return;
                
                // Add user message to chat
                addMessage(message, 'user');
                chatInput.value = '';
                
                // Show loading indicator
                const loadingIndicator = document.createElement('div');
                loadingIndicator.className = 'loading';
                loadingIndicator.innerHTML = `
                    <div class="loading-dot"></div>
                    <div class="loading-dot"></div>
                    <div class="loading-dot"></div>
                `;
                chatMessages.appendChild(loadingIndicator);
                
                // Scroll to bottom
                scrollToBottom();
                
                // Send message to server
                fetch('chatbot.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'message=' + encodeURIComponent(message)
                })
                .then(response => response.text())
                .then(data => {
                    // Remove loading indicator
                    chatMessages.removeChild(loadingIndicator);
                    
                    // Add bot response
                    addMessage(data, 'bot');
                })
                .catch(error => {
                    // Remove loading indicator
                    chatMessages.removeChild(loadingIndicator);
                    
                    // Add error message
                    addMessage('Sorry, there was an error processing your request. Please try again later.', 'bot');
                    console.error('Error:', error);
                });
            });
            
            // Function to add a message to the chat
            function addMessage(text, sender) {
                const messageElement = document.createElement('div');
                messageElement.className = `message ${sender}-message`;
                
                const now = new Date();
                const hours = now.getHours().toString().padStart(2, '0');
                const minutes = now.getMinutes().toString().padStart(2, '0');
                const timeString = `${hours}:${minutes}`;
                
                messageElement.innerHTML = `
                    <p>${text}</p>
                    <div class="message-time">${timeString}</div>
                `;
                
                chatMessages.appendChild(messageElement);
                
                // Scroll to bottom
                scrollToBottom();
            }
            
            // Function to scroll chat to bottom
            function scrollToBottom() {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
            
            // Function to embed the chatbot in another website
            window.embedChatbot = function(targetElementId) {
                const targetElement = document.getElementById(targetElementId);
                if (targetElement) {
                    const chatWidget = document.querySelector('.chat-widget');
                    targetElement.appendChild(chatWidget);
                }
            };
        });
    </script>
</body>
</html>
