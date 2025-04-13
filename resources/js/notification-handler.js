import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

class NotificationHandler {
    constructor() {
        this.initializeEcho();
        this.bindNotificationListeners();
    }

    initializeEcho() {
        window.Pusher = Pusher;

        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: process.env.MIX_PUSHER_APP_KEY,
            cluster: process.env.MIX_PUSHER_APP_CLUSTER,
            encrypted: true
        });
    }

    bindNotificationListeners() {
        if (window.Echo && window.userId) {
            window.Echo.private(`notifications.${window.userId}`)
                .listen('new-notification', (notification) => {
                    this.handleNewNotification(notification);
                });
        }
    }

    handleNewNotification(notification) {
        // Create notification element
        const notificationElement = this.createNotificationElement(notification);

        // Add to notification container
        const container = document.getElementById('notifications-container');
        if (container) {
            container.insertBefore(notificationElement, container.firstChild);
        }

        // Update unread count
        this.updateUnreadCount();

        // Show toast notification
        this.showToast(notification);
    }

    createNotificationElement(notification) {
        const element = document.createElement('div');
        element.className = 'notification-item unread';
        element.dataset.notificationId = notification.id;

        element.innerHTML = `
            <div class="notification-content">
                <p>${this.formatNotificationMessage(notification)}</p>
                <small>${this.formatTimestamp(notification.created_at)}</small>
            </div>
        `;

        return element;
    }

    formatNotificationMessage(notification) {
        // Format message based on notification type
        switch (notification.type) {
            case 'new_comment':
                return `New comment on your post: ${notification.data.post_title}`;
            case 'new_like':
                return `Someone liked your post: ${notification.data.post_title}`;
            default:
                return 'You have a new notification';
        }
    }

    formatTimestamp(timestamp) {
        return new Date(timestamp).toLocaleString();
    }

    updateUnreadCount() {
        const countElement = document.getElementById('unread-notifications-count');
        if (countElement) {
            const currentCount = parseInt(countElement.textContent) || 0;
            countElement.textContent = currentCount + 1;
        }
    }

    showToast(notification) {
        // Create and show toast notification
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.innerHTML = `
            <div class="toast-content">
                ${this.formatNotificationMessage(notification)}
            </div>
        `;

        document.body.appendChild(toast);

        // Remove toast after 5 seconds
        setTimeout(() => {
            toast.remove();
        }, 5000);
    }
}

// Initialize notification handler when document is ready
document.addEventListener('DOMContentLoaded', () => {
    new NotificationHandler();
});