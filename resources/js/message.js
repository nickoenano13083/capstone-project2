import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: true
});

const userId = document.querySelector('meta[name="user-id"]').content;

Echo.private(`user.${userId}.messages`)
    .listen('NewMessage', (e) => {
        // Add message to chat
        appendMessage(e);
        // Update unread count
        updateUnreadCount();
    });

function appendMessage(message) {
    const chatContainer = document.querySelector('.chat-messages');
    const messageHtml = `
        <div class="message">
            <strong>${message.sender}:</strong>
            <p>${message.content}</p>
            <small>${message.created_at}</small>
        </div>
    `;
    chatContainer.insertAdjacentHTML('beforeend', messageHtml);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

function updateUnreadCount() {
    const unreadBadge = document.querySelector('.unread-count');
    if (unreadBadge) {
        const currentCount = parseInt(unreadBadge.textContent);
        unreadBadge.textContent = currentCount + 1;
    }
}