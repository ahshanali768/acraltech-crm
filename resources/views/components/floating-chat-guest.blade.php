<!-- Floating Chat Modal (Enhanced UX, modern design matching landing page theme) -->
<div id="chat-modal-guest" class="fixed bottom-28 right-8 z-50 w-72 max-w-full bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 hidden flex-col overflow-hidden animate-fade-in scale-95 opacity-0 pointer-events-none transition-all duration-300" style="height:auto; min-height:unset; max-height:80vh;">
    <!-- Enhanced Header with gradient background matching landing page -->
    <div class="flex items-center justify-between px-4 py-3 border-b border-white/20 bg-gradient-to-r from-blue-600 via-purple-600 to-blue-800 relative">
        <div class="flex gap-2 items-center">
            <!-- Online status indicator -->
            <div class="relative">
                <div class="w-2.5 h-2.5 bg-green-400 rounded-full animate-pulse"></div>
                <div class="absolute inset-0 w-2.5 h-2.5 bg-green-300 rounded-full animate-ping opacity-30"></div>
            </div>
            <div class="flex flex-col">
                <span class="font-bold text-white text-sm">Live Chat</span>
                <span class="text-xs text-blue-200">We're online</span>
            </div>
        </div>
        <button id="chat-modal-close-guest" type="button" class="p-2 rounded-full hover:bg-white/10 text-white/70 hover:text-white transition-all duration-200 group" aria-label="Close chat">
            <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    <!-- Pre-chat form with modern styling -->
    <div id="prechat-form-guest" class="p-4 bg-white/5 backdrop-blur-sm">
        <!-- Welcome message -->
        <div class="text-center mb-4">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-2">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-white mb-1">Start a conversation</h3>
            <p class="text-xs text-gray-300">Connect with our lead generation experts</p>
        </div>
        
        <form id="prechat-form-submit" class="space-y-3">
            <div class="space-y-2.5">
                <div class="group">
                    <label for="prechat-name-guest" class="block text-xs font-medium text-gray-300 mb-1">Full Name *</label>
                    <input id="prechat-name-guest" name="name" type="text" required 
                           class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-sm text-white placeholder-gray-400 transition-all duration-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20 focus:outline-none hover:border-white/30"
                           placeholder="Enter your name">
                </div>
                <div class="group">
                    <label for="prechat-phone-guest" class="block text-xs font-medium text-gray-300 mb-1">Phone *</label>
                    <input id="prechat-phone-guest" name="phone" type="tel" required 
                           class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-sm text-white placeholder-gray-400 transition-all duration-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20 focus:outline-none hover:border-white/30"
                           placeholder="Enter your phone number">
                </div>
                <div class="group">
                    <label for="prechat-email-guest" class="block text-xs font-medium text-gray-300 mb-1">Email *</label>
                    <input id="prechat-email-guest" name="email" type="email" required 
                           class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-sm text-white placeholder-gray-400 transition-all duration-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20 focus:outline-none hover:border-white/30"
                           placeholder="your@email.com">
                </div>
            </div>
            
            <div class="flex items-start space-x-2 pt-1">
                <input id="prechat-consent-guest" name="consent" type="checkbox" required 
                       class="mt-0.5 h-3.5 w-3.5 text-blue-600 border-white/20 bg-white/10 rounded focus:ring-blue-500 focus:ring-1">
                <label for="prechat-consent-guest" class="text-xs text-gray-300 leading-relaxed">
                    I agree to be contacted per 
                    <a href="/privacy" class="text-blue-400 hover:text-blue-300 underline">Privacy Policy</a>
                </label>
            </div>
            
            <button type="submit" 
                    class="w-full bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white rounded-xl px-4 py-2.5 text-sm font-semibold shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span>Start Chat</span>
            </button>
        </form>
        
        <!-- Trust indicators with landing page styling -->
        <div class="mt-3 pt-3 border-t border-white/20">
            <div class="flex items-center justify-center space-x-3 text-xs text-gray-300">
                <div class="flex items-center space-x-1">
                    <svg class="w-3 h-3 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-7-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Secure</span>
                </div>
                <div class="flex items-center space-x-1">
                    <svg class="w-3 h-3 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>GDPR</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Chat UI with modern glass-morphism design -->
    <div id="chat-ui-guest" style="display:none;" class="flex flex-col h-full">
        <!-- Chat messages area with modern styling -->
        <div id="chat-content-guest" class="flex-1 flex flex-col min-h-0 max-h-48 overflow-y-auto p-3 bg-gradient-to-b from-white/5 to-white/10 space-y-2">
            <!-- Welcome message with modern styling -->
            <div class="flex items-start space-x-2">
                <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg rounded-tl-sm px-3 py-2 shadow-sm border border-white/20 max-w-xs">
                    <p class="text-xs text-white">Hi! 👋 How can I help you with lead generation today?</p>
                    <span class="text-xs text-gray-300 mt-1 block">Just now</span>
                </div>
            </div>
        </div>
        
        <!-- Message input area with modern styling -->
        <div class="border-t border-white/20 bg-white/5 backdrop-blur-sm p-3">
            <form id="chat-form-guest" class="flex items-end space-x-2">
                <div class="flex-1 relative">
                    <input id="chat-input-guest" 
                           type="text" 
                           class="w-full rounded-xl bg-white/10 border border-white/20 px-3 py-2 pr-10 text-xs text-white placeholder-gray-400 resize-none transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400/20 focus:border-blue-400 hover:bg-white/20" 
                           placeholder="Type your message..." 
                           autocomplete="off"
                           maxlength="500" />
                    <!-- Character counter -->
                    <span id="char-counter" class="absolute bottom-0.5 right-2 text-xs text-gray-400">0/500</span>
                </div>
                <button type="submit" 
                        class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 disabled:from-gray-400 disabled:to-gray-500 text-white rounded-xl p-2 shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105 active:scale-95 disabled:transform-none disabled:cursor-not-allowed flex items-center justify-center"
                        disabled>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </form>
            
            <!-- Status with modern styling -->
            <div class="flex items-center justify-between mt-2 text-xs text-gray-300">
                <div class="flex items-center space-x-1">
                    <div class="w-1.5 h-1.5 bg-green-400 rounded-full"></div>
                    <span>Connected</span>
                </div>
                <span>End-to-end encrypted</span>
            </div>
        </div>
    </div>
</div>
<!-- Floating Chat Button with landing page gradient -->
<div id="floating-chat-btn-guest" class="fixed bottom-8 right-8 z-50">
    <button id="floating-chat-btn-inner-guest" type="button" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-full w-16 h-16 flex items-center justify-center shadow-xl transition-all focus:outline-none focus:ring-4 focus:ring-blue-300/30 relative scale-100 hover:scale-110 active:scale-95 duration-200" aria-label="Open chat">
        <span class="relative flex items-center justify-center">
            <!-- Modern chat icon -->
            <svg class="h-8 w-8" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M24 4C13.5 4 5 11.9 5 21.5c0 4.8 2.1 9.1 5.5 12.3v8.7l8.5-4.7c1.6.3 3.2.5 5 .5 10.5 0 19-7.9 19-17.5S34.5 4 24 4zm2.5 23.5l-6.5-7-12.5 7 13.8-14.8 6.7 7L40.5 12 26.5 27.5z" fill="white"/>
            </svg>
            <span id="chat-unread-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full px-1.5 py-0.5 shadow border-2 border-white hidden">1</span>
        </span>
    </button>
</div>
<style>
/* Enhanced floating chat button with landing page theme */
#floating-chat-btn-inner-guest {
    box-shadow: 0 4px 24px 0 rgba(37, 99, 235, 0.3), 0 2px 8px 0 rgba(0,0,0,0.1);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
}

#floating-chat-btn-inner-guest:hover {
    box-shadow: 0 8px 32px 0 rgba(37, 99, 235, 0.4), 0 4px 16px 0 rgba(0,0,0,0.15);
    background: linear-gradient(135deg, #1d4ed8 0%, #6d28d9 100%);
}

#floating-chat-btn-inner-guest:active {
    transform: scale(0.95);
}

#floating-chat-btn-inner-guest:focus {
    outline: none;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.2), 0 8px 32px 0 rgba(37, 99, 235, 0.4);
}

/* Enhanced modal animations with glass-morphism */
#chat-modal-guest {
    opacity: 0;
    pointer-events: none;
    transform: scale(0.9) translateY(20px);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

#chat-modal-guest.show {
    opacity: 1 !important;
    pointer-events: auto !important;
    transform: scale(1) translateY(0) !important;
}

/* Modern input styling */
.group input:focus + label,
.group input:not(:placeholder-shown) + label {
    transform: translateY(-0.5rem) scale(0.875);
    color: #60a5fa;
}

/* Custom scrollbar with theme colors */
#chat-content-guest {
    scrollbar-width: thin;
    scrollbar-color: rgba(96, 165, 250, 0.5) transparent;
}

#chat-content-guest::-webkit-scrollbar {
    width: 4px;
}

#chat-content-guest::-webkit-scrollbar-track {
    background: transparent;
}

#chat-content-guest::-webkit-scrollbar-thumb {
    background: rgba(96, 165, 250, 0.5);
    border-radius: 2px;
}

#chat-content-guest::-webkit-scrollbar-thumb:hover {
    background: rgba(96, 165, 250, 0.7);
}

/* Message bubble animations */
.message-bubble {
    animation: slideInMessage 0.3s ease-out;
}

@keyframes slideInMessage {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Enhanced fade-in animation */
@keyframes fade-in { 
    from { 
        opacity: 0; 
        transform: translateY(30px) scale(0.95); 
    } 
    to { 
        opacity: 1; 
        transform: translateY(0) scale(1); 
    } 
}

.animate-fade-in { 
    animation: fade-in 0.4s cubic-bezier(0.4, 0, 0.2, 1); 
}

/* Pulse animation for online indicator */
@keyframes pulse-blue {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.7;
        transform: scale(1.1);
    }
}

.animate-pulse-blue {
    animation: pulse-blue 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Loading states with theme colors */
.loading {
    position: relative;
    overflow: hidden;
}

.loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(96, 165, 250, 0.2), transparent);
    animation: loading-shimmer 1.5s infinite;
}

@keyframes loading-shimmer {
    100% {
        left: 100%;
    }
}

/* Mobile responsiveness */
@media (max-width: 640px) {
    #chat-modal-guest {
        right: 4px;
        left: 4px;
        width: auto;
        max-width: none;
    }
    
    #floating-chat-btn-guest {
        right: 16px;
        bottom: 16px;
    }
}

/* Additional theme-specific styles */
input[type="checkbox"] {
    accent-color: #3b82f6;
}

input[type="checkbox"]:checked {
    background-color: #3b82f6;
    border-color: #3b82f6;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced chat functionality with better UX
    var btn = document.getElementById('floating-chat-btn-inner-guest');
    var modal = document.getElementById('chat-modal-guest');
    var closeBtn = document.getElementById('chat-modal-close-guest');
    var prechatForm = document.getElementById('prechat-form-submit');
    var prechatContainer = document.getElementById('prechat-form-guest');
    var chatUI = document.getElementById('chat-ui-guest');
    var chatInput = document.getElementById('chat-input-guest');
    var chatForm = document.getElementById('chat-form-guest');
    var chatContent = document.getElementById('chat-content-guest');
    var charCounter = document.getElementById('char-counter');
    var unreadBadge = document.getElementById('chat-unread-badge');
    var notificationSound = document.getElementById('chat-notification-sound');
    var submitBtn = chatForm ? chatForm.querySelector('button[type="submit"]') : null;
    
    var unreadCount = 0;
    var isTyping = false;
    var typingTimeout;
    
    // Enhanced modal open/close with animations
    if (btn && modal) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            modal.classList.add('show');
            modal.classList.remove('hidden');
            
            // Auto-focus first input
            setTimeout(function() { 
                var firstInput = modal.querySelector('input[type="text"]');
                if (firstInput) firstInput.focus();
            }, 100);
            
            // Reset to pre-chat form
            if (prechatContainer) prechatContainer.style.display = '';
            if (chatUI) chatUI.style.display = 'none';
            
            // Clear unread badge
            unreadCount = 0;
            if (unreadBadge) unreadBadge.classList.add('hidden');
        });
    }
    
    // Enhanced close functionality
    if (closeBtn && modal) {
        closeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            modal.classList.remove('show');
            setTimeout(function() { 
                modal.classList.add('hidden'); 
            }, 400);
        });
    }
    
    // Close on outside click
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.remove('show');
                setTimeout(function() { 
                    modal.classList.add('hidden'); 
                }, 400);
            }
        });
    }
    
    // Enhanced form submission with loading states
    if (prechatForm) {
        prechatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            var submitButton = this.querySelector('button[type="submit"]');
            var originalText = submitButton.innerHTML;
            
            // Get form data
            var name = document.getElementById('prechat-name-guest').value.trim();
            var phone = document.getElementById('prechat-phone-guest').value.trim();
            var email = document.getElementById('prechat-email-guest').value.trim();
            var consent = document.getElementById('prechat-consent-guest').checked;
            
            // Validation
            if (!name || !phone || !email || !consent) {
                showNotification('Please fill in all required fields', 'error');
                return;
            }
            
            // Show loading state
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span>Connecting...</span>
            `;
            
            // Submit form data
            console.log('Submitting form data:', { name, phone, email, consent });
            
            // Get CSRF token
            var csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error('CSRF token not found');
                showNotification('Security token not found. Please refresh the page and try again.', 'error');
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
                return;
            }
            
            fetch('/chat/guest-contact', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                },
                body: JSON.stringify({ name, phone, email, consent })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Success - switch to chat UI
                    prechatContainer.style.display = 'none';
                    chatUI.style.display = 'flex';
                    
                    // Focus on chat input
                    setTimeout(() => {
                        if (chatInput) chatInput.focus();
                    }, 300);
                    
                    showNotification('Chat started successfully!', 'success');
                } else {
                    throw new Error(data.message || 'Unknown error occurred');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                let errorMessage = 'Failed to start chat. Please try again.';
                
                if (error.message.includes('403') || error.message.includes('419')) {
                    errorMessage = 'Security token expired. Please refresh the page and try again.';
                } else if (error.message.includes('422')) {
                    errorMessage = 'Please check your input and try again.';
                } else if (error.message.includes('500')) {
                    errorMessage = 'Server error. Please try again later.';
                }
                
                showNotification(errorMessage, 'error');
                
                // Restore button
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            });
        });
    }
    
    // Enhanced chat input functionality
    if (chatInput && submitBtn) {
        // Character counter
        chatInput.addEventListener('input', function() {
            var length = this.value.length;
            charCounter.textContent = length + '/500';
            
            // Enable/disable submit button
            submitBtn.disabled = length === 0;
            
            // Color coding for character limit
            if (length > 450) {
                charCounter.style.color = '#ef4444';
            } else if (length > 400) {
                charCounter.style.color = '#f59e0b';
            } else {
                charCounter.style.color = '#9ca3af';
            }
        });
        
        // Auto-resize textarea effect (if needed)
        chatInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (chatForm) {
                    chatForm.dispatchEvent(new Event('submit'));
                }
            }
        });
    }
    
    // Enhanced chat form submission
    if (chatForm) {
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            var message = chatInput.value.trim();
            if (!message) return;
            
            // Add user message to chat
            addMessageToChat(message, 'user');
            
            // Clear input
            chatInput.value = '';
            charCounter.textContent = '0/500';
            submitBtn.disabled = true;
            
            // Simulate agent response (replace with real implementation)
            setTimeout(() => {
                showTypingIndicator();
                setTimeout(() => {
                    hideTypingIndicator();
                    addMessageToChat("Thank you for your message! An agent will respond shortly.", 'agent');
                }, 2000);
            }, 500);
        });
    }
    
    // Utility functions
    function addMessageToChat(message, sender) {
        if (!chatContent) return;
        
        var messageDiv = document.createElement('div');
        messageDiv.className = 'flex items-start space-x-2 message-bubble';
        
        var timestamp = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        
        if (sender === 'user') {
            messageDiv.innerHTML = `
                <div class="flex-1"></div>
                <div class="bg-green-500 text-white rounded-2xl rounded-tr-md px-4 py-3 shadow-sm max-w-xs">
                    <p class="text-sm">${escapeHtml(message)}</p>
                    <span class="text-xs opacity-75 mt-1 block">${timestamp}</span>
                </div>
                <div class="w-8 h-8 bg-gray-400 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            `;
        } else {
            messageDiv.innerHTML = `
                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="bg-white rounded-2xl rounded-tl-md px-4 py-3 shadow-sm border border-gray-100 max-w-xs">
                    <p class="text-sm text-gray-800">${escapeHtml(message)}</p>
                    <span class="text-xs text-gray-500 mt-1 block">${timestamp}</span>
                </div>
            `;
        }
        
        chatContent.appendChild(messageDiv);
        chatContent.scrollTop = chatContent.scrollHeight;
    }
    
    function showTypingIndicator() {
        var indicator = document.getElementById('typing-indicator');
        if (indicator) {
            indicator.classList.remove('hidden');
        }
    }
    
    function hideTypingIndicator() {
        var indicator = document.getElementById('typing-indicator');
        if (indicator) {
            indicator.classList.add('hidden');
        }
    }
    
    function showNotification(message, type = 'info') {
        // Create notification element
        var notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg text-white text-sm transition-all duration-300 transform translate-x-full opacity-0 ${
            type === 'success' ? 'bg-green-500' : 
            type === 'error' ? 'bg-red-500' : 
            'bg-blue-500'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
            notification.style.opacity = '1';
        }, 100);
        
        // Auto remove
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            notification.style.opacity = '0';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
    
    function escapeHtml(text) {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
    
    // Enhanced notification system
    window.simulateNewChatMessage = function() {
        unreadCount++;
        if (unreadBadge) {
            unreadBadge.textContent = unreadCount;
            unreadBadge.classList.remove('hidden');
        }
        if (notificationSound) {
            notificationSound.currentTime = 0;
            notificationSound.play().catch(() => {
                // Ignore autoplay errors
            });
        }
        
        // Add visual feedback
        if (btn) {
            btn.style.animation = 'none';
            setTimeout(() => {
                btn.style.animation = 'bounce 0.5s ease-in-out';
            }, 10);
        }
    };
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Escape to close modal
        if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
            modal.classList.remove('show');
            setTimeout(function() { 
                modal.classList.add('hidden'); 
            }, 400);
        }
    });
});
</script>
{{-- Chat notification sound can be added here if needed --}}
