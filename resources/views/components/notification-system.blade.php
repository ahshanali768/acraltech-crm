<!-- Notification System for Chat -->
<div x-data="notificationSystem()" x-init="init()" class="fixed top-4 right-4 z-[70] space-y-2">
    <!-- Notification Items -->
    <template x-for="notification in notifications" :key="notification.id">
        <div 
            x-show="notification.visible"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-x-full scale-95"
            x-transition:enter-end="opacity-100 translate-x-0 scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-x-0 scale-100"
            x-transition:leave-end="opacity-0 translate-x-full scale-95"
            class="bg-white dark:bg-[#23234b] border border-gray-200 dark:border-[#353570] rounded-xl p-4 max-w-sm shadow-xl cursor-pointer transition-colors duration-300"
            @click="markAsRead(notification.id)"
        >
            <!-- Notification Header -->
            <div class="flex items-start gap-3 mb-2">
                <!-- Icon -->
                <div class="flex-shrink-0">
                    <div 
                        class="w-10 h-10 rounded-full flex items-center justify-center"
                        :class="{
                            'bg-indigo-600 dark:bg-[#b49cff]': notification.type === 'message',
                            'bg-green-500': notification.type === 'success',
                            'bg-yellow-500': notification.type === 'warning',
                            'bg-red-500': notification.type === 'error'
                        }"
                    >
                        <!-- Message Icon -->
                        <template x-if="notification.type === 'message'">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 3.582-8 8-8s8 3.582 8 8z"></path>
                            </svg>
                        </template>
                        
                        <!-- User Avatar -->
                        <template x-if="notification.avatar">
                            <span class="text-white font-semibold text-sm" x-text="notification.avatar"></span>
                        </template>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h4 class="text-gray-900 dark:text-white font-medium text-sm truncate" x-text="notification.title"></h4>
                        <button 
                            @click.stop="removeNotification(notification.id)"
                            class="text-gray-600 dark:text-[#bfc9d1] hover:text-gray-900 dark:hover:text-white ml-2 flex-shrink-0"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="text-gray-700 dark:text-[#bfc9d1] text-sm mt-1 line-clamp-2" x-text="notification.message"></p>
                    <p class="text-xs text-gray-500 dark:text-[#bfc9d1] opacity-70 mt-1" x-text="formatTime(notification.timestamp)"></p>
                </div>
            </div>
            
            <!-- Action Buttons (if any) -->
            <template x-if="notification.actions && notification.actions.length > 0">
                <div class="flex gap-2 mt-3">
                    <template x-for="action in notification.actions" :key="action.label">
                        <button 
                            @click.stop="handleAction(action, notification.id)"
                            class="px-3 py-1 rounded-lg text-xs font-medium transition-colors"
                            :class="{
                                'bg-indigo-600 dark:bg-[#b49cff] hover:bg-indigo-700 dark:hover:bg-[#9d7fff] text-white': action.type === 'primary',
                                'bg-gray-200 dark:bg-[#353570] hover:bg-gray-300 dark:hover:bg-[#404086] text-gray-700 dark:text-[#bfc9d1]': action.type === 'secondary'
                            }"
                            x-text="action.label"
                        ></button>
                    </template>
                </div>
            </template>
        </div>
    </template>
</div>

<script>
function notificationSystem() {
    return {
        notifications: [],
        nextId: 1,
        
        init() {
            // Listen for custom notification events
            window.addEventListener('show-notification', (event) => {
                this.showNotification(event.detail);
            });
            
            // Listen for chat message events
            window.addEventListener('new-chat-message', (event) => {
                this.showChatNotification(event.detail);
            });
        },
        
        showNotification(data) {
            const notification = {
                id: this.nextId++,
                type: data.type || 'message',
                title: data.title || 'Notification',
                message: data.message || '',
                avatar: data.avatar || null,
                timestamp: Date.now(),
                visible: false,
                actions: data.actions || [],
                duration: data.duration || 5000
            };
            
            this.notifications.push(notification);
            
            // Show with a slight delay for animation
            this.$nextTick(() => {
                notification.visible = true;
                
                // Auto-remove after duration
                if (notification.duration > 0) {
                    setTimeout(() => {
                        this.removeNotification(notification.id);
                    }, notification.duration);
                }
            });
        },
        
        showChatNotification(data) {
            // Don't show notification if chat widget is open and active
            if (window.chatWidgetOpen && window.chatActiveTab === data.chatType) {
                return;
            }
            
            this.showNotification({
                type: 'message',
                title: `New message from ${data.senderName}`,
                message: data.message.length > 50 ? data.message.substring(0, 50) + '...' : data.message,
                avatar: data.senderName.charAt(0).toUpperCase(),
                actions: [
                    {
                        label: 'View',
                        type: 'primary',
                        action: 'open-chat',
                        data: data
                    },
                    {
                        label: 'Dismiss',
                        type: 'secondary',
                        action: 'dismiss'
                    }
                ]
            });
        },
        
        markAsRead(notificationId) {
            const notification = this.notifications.find(n => n.id === notificationId);
            if (notification && notification.actions) {
                const viewAction = notification.actions.find(a => a.action === 'open-chat');
                if (viewAction) {
                    this.handleAction(viewAction, notificationId);
                }
            }
        },
        
        handleAction(action, notificationId) {
            switch (action.action) {
                case 'open-chat':
                    // Trigger chat widget opening
                    window.dispatchEvent(new CustomEvent('open-chat'));
                    this.removeNotification(notificationId);
                    break;
                case 'dismiss':
                    this.removeNotification(notificationId);
                    break;
                default:
                    if (action.callback && typeof action.callback === 'function') {
                        action.callback(action.data);
                    }
                    this.removeNotification(notificationId);
            }
        },
        
        removeNotification(id) {
            const notification = this.notifications.find(n => n.id === id);
            if (notification) {
                notification.visible = false;
                // Remove from array after animation completes
                setTimeout(() => {
                    this.notifications = this.notifications.filter(n => n.id !== id);
                }, 300);
            }
        },
        
        formatTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diffInSeconds = Math.abs(now - date) / 1000;
            
            if (diffInSeconds < 60) {
                return 'Just now';
            } else if (diffInSeconds < 3600) {
                return Math.floor(diffInSeconds / 60) + 'm ago';
            } else if (diffInSeconds < 86400) {
                return Math.floor(diffInSeconds / 3600) + 'h ago';
            } else {
                return date.toLocaleDateString();
            }
        }
    }
}

// Global notification helper functions
window.showNotification = function(data) {
    window.dispatchEvent(new CustomEvent('show-notification', { detail: data }));
};

window.showChatNotification = function(data) {
    window.dispatchEvent(new CustomEvent('new-chat-message', { detail: data }));
};
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
