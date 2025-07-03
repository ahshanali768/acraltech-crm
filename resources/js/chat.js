import axios from 'axios';
import Echo from 'laravel-echo';

let currentTab = 'public';
let currentUser = null;

function renderMessages(messages) {
    const chatContent = document.getElementById('chat-content');
    if (!chatContent) return;
    chatContent.innerHTML = '';
    messages.forEach(msg => {
        const mine = msg.sender_id === window.Laravel.userId;
        const div = document.createElement('div');
        div.className = 'mb-2 flex ' + (mine ? 'justify-end' : 'justify-start');
        div.innerHTML = `<div class="max-w-[70%] px-3 py-2 rounded-xl ${mine ? 'bg-[#edeaff] text-[#6c63ff]' : 'bg-gray-100 text-gray-800'} shadow-sm">
            <span class="font-semibold">${msg.sender?.name || ''}</span><br>${msg.message}
        </div>`;
        chatContent.appendChild(div);
    });
    chatContent.scrollTop = chatContent.scrollHeight;
}

function fetchPublicMessages() {
    if (!window.Laravel || !window.Laravel.userId) {
        return; // Don't fetch if not authenticated
    }
    axios.get('/chat/public')
        .then(r => renderMessages(r.data))
        .catch(error => {
            if (error.response && error.response.status === 401) {
                console.warn('Chat: User not authenticated for public messages');
            } else {
                console.error('Chat: Error fetching public messages:', error);
            }
        });
}

function fetchPrivateMessages(userId) {
    if (!window.Laravel || !window.Laravel.userId) {
        return; // Don't fetch if not authenticated
    }
    axios.get('/chat/private/' + userId)
        .then(r => renderMessages(r.data))
        .catch(error => {
            if (error.response && error.response.status === 401) {
                console.warn('Chat: User not authenticated for private messages');
            } else {
                console.error('Chat: Error fetching private messages:', error);
            }
        });
}
function fetchUsers() {
    if (!window.Laravel || !window.Laravel.userId) {
        return; // Don't fetch if not authenticated
    }
    axios.get('/chat/users')
        .then(r => {
            const users = r.data;
            const usersDiv = document.getElementById('chat-users');
            if (!usersDiv) return;
            usersDiv.innerHTML = '';
            users.forEach(u => {
                const btn = document.createElement('button');
                btn.className = 'block w-full text-left px-2 py-1 rounded hover:bg-[#edeaff]';
                btn.innerText = u.name;
                btn.onclick = () => {
                    currentUser = u.id;
                    currentTab = 'private';
                    const privateTab = document.getElementById('chat-tab-private');
                    const publicTab = document.getElementById('chat-tab-public');
                    const chatUsers = document.getElementById('chat-users');
                    
                    if (privateTab) privateTab.classList.add('bg-[#edeaff]', 'text-[#6c63ff]');
                    if (publicTab) publicTab.classList.remove('bg-[#edeaff]', 'text-[#6c63ff]');
                    if (chatUsers) chatUsers.classList.add('hidden');
                    fetchPrivateMessages(u.id);
                };
                usersDiv.appendChild(btn);
            });
        })
        .catch(error => {
            if (error.response && error.response.status === 401) {
                console.warn('Chat: User not authenticated for users list');
            } else {
                console.error('Chat: Error fetching users:', error);
            }
        });
}

// Guest/Private chat restrictions
function updateGuestUI() {
    const isGuest = window.Laravel.isGuest === true || window.Laravel.isGuest === 'true';
    const privateTab = document.getElementById('chat-tab-private');
    const chatForm = document.getElementById('chat-form');
    const guestWarning = document.getElementById('chat-guest-warning');
    
    if (privateTab) {
        privateTab.disabled = isGuest;
        privateTab.classList.toggle('opacity-50', isGuest);
    }
    if (chatForm) {
        chatForm.style.display = (isGuest && currentTab === 'private') ? 'none' : '';
    }
    if (guestWarning) {
        guestWarning.classList.toggle('hidden', !(isGuest && currentTab === 'private'));
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Check if user is authenticated before initializing chat
    if (!window.Laravel || !window.Laravel.userId) {
        // User is not authenticated, don't initialize chat
        return;
    }
    
    // Tabs
    const publicTab = document.getElementById('chat-tab-public');
    const privateTab = document.getElementById('chat-tab-private');
    const chatUsers = document.getElementById('chat-users');
    const chatContent = document.getElementById('chat-content');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    
    if (publicTab) {
        publicTab.onclick = function() {
            currentTab = 'public';
            updateGuestUI();
            publicTab.classList.add('bg-[#edeaff]', 'text-[#6c63ff]');
            if (privateTab) privateTab.classList.remove('bg-[#edeaff]', 'text-[#6c63ff]');
            if (chatUsers) chatUsers.classList.add('hidden');
            fetchPublicMessages();
        };
    }
    
    if (privateTab) {
        privateTab.onclick = function() {
            currentTab = 'private';
            updateGuestUI();
            privateTab.classList.add('bg-[#edeaff]', 'text-[#6c63ff]');
            if (publicTab) publicTab.classList.remove('bg-[#edeaff]', 'text-[#6c63ff]');
            if (chatUsers) chatUsers.classList.remove('hidden');
            fetchUsers();
            if (chatContent) chatContent.innerHTML = '<div class="text-gray-400 text-center mt-8">Select a user to chat privately</div>';
        };
    }
    
    updateGuestUI();
    // Only fetch messages if user is authenticated
    if (window.Laravel.userId) {
        fetchPublicMessages();
    }
    
    // Send message
    if (chatForm) {
        chatForm.onsubmit = function(e) {
            const isGuest = window.Laravel.isGuest === true || window.Laravel.isGuest === 'true';
            if (isGuest && currentTab === 'private') {
                e.preventDefault();
                updateGuestUI();
                return false;
            }
            e.preventDefault();
            if (!chatInput) return;
            const msg = chatInput.value.trim();
            if (!msg) return;
            axios.post('/chat/send', {
                message: msg,
                receiver_id: (currentTab === 'private' && currentUser) ? currentUser : null
            }).then(() => {
                chatInput.value = '';
                if (currentTab === 'public') fetchPublicMessages();
                else if (currentUser) fetchPrivateMessages(currentUser);
            });
        };
    }
    // Real-time updates with Echo
    if (window.Echo) {
        // Public chat
        window.Echo.channel('chat.public')
            .listen('MessageSent', (e) => {
                if (currentTab === 'public' && document.getElementById('chat-modal') && !document.getElementById('chat-modal').classList.contains('hidden')) {
                    fetchPublicMessages();
                }
            });
        // Private chat
        window.Echo.private('chat.private.' + Math.min(window.Laravel.userId, currentUser || 0) + '.' + Math.max(window.Laravel.userId, currentUser || 0))
            .listen('MessageSent', (e) => {
                if (currentTab === 'private' && currentUser && document.getElementById('chat-modal') && !document.getElementById('chat-modal').classList.contains('hidden')) {
                    fetchPrivateMessages(currentUser);
                }
            });
    }
});

// Toggle chat modal visibility
window.toggleChatModal = function() {
    const modal = document.getElementById('chat-modal');
    if (!modal) return;
    modal.classList.toggle('hidden');
    // When opening, reset to public tab and update guest UI
    if (!modal.classList.contains('hidden')) {
        currentTab = 'public';
        if (typeof updateGuestUI === 'function') updateGuestUI();
        
        // Only fetch messages if user is authenticated
        if (window.Laravel && window.Laravel.userId && typeof fetchPublicMessages === 'function') {
            fetchPublicMessages();
        }
        
        const publicTab = document.getElementById('chat-tab-public');
        const privateTab = document.getElementById('chat-tab-private');
        const chatUsers = document.getElementById('chat-users');
        
        if (publicTab) publicTab.classList.add('bg-[#edeaff]', 'text-[#6c63ff]');
        if (privateTab) privateTab.classList.remove('bg-[#edeaff]', 'text-[#6c63ff]');
        if (chatUsers) chatUsers.classList.add('hidden');
    }
};
