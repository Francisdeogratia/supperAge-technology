

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true; // ← CRITICAL: Send cookies with requests

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    authEndpoint: '/broadcasting/auth',
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    },
    // ← CRITICAL: Enable credentials to send cookies
    authorizer: (channel, options) => {
        return {
            authorize: (socketId, callback) => {
                axios.post('/broadcasting/auth', {
                    socket_id: socketId,
                    channel_name: channel.name
                }, {
                    withCredentials: true, // ← Send session cookies
                    headers: options.auth.headers
                })
                .then(response => {
                    callback(null, response.data);
                })
                .catch(error => {
                    console.error('Authorization error:', error.response?.data || error);
                    callback(error);
                });
            }
        };
    }
});

console.log('✅ Laravel Echo initialized with session support');

// Add this to your resources/js/bootstrap.js (after Echo initialization)

// Send heartbeat every 2 minutes to maintain online status
function sendOnlineStatusHeartbeat() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    
    if (!csrfToken) {
        console.warn('⚠️ No CSRF token found, skipping heartbeat');
        return;
    }
    
    fetch('/api/users/update-online-status', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        credentials: 'same-origin' // Important for session cookies
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('💓 Online status heartbeat sent:', data);
    })
    .catch(error => {
        console.error('❌ Heartbeat error:', error.message);
    });
}

// Only run if user is logged in (CSRF token exists)
if (document.querySelector('meta[name="csrf-token"]')) {
    // Send initial heartbeat after 2 seconds (let page load first)
    setTimeout(() => {
        sendOnlineStatusHeartbeat();
    }, 2000);
    
    // Send heartbeat every 2 minutes
    setInterval(sendOnlineStatusHeartbeat, 120000);
    
    // Send heartbeat on user activity (debounced)
    let activityTimeout;
    let lastHeartbeat = Date.now();
    const minHeartbeatInterval = 30000; // Minimum 30 seconds between heartbeats
    
    ['mousedown', 'keydown', 'scroll', 'touchstart'].forEach(event => {
        document.addEventListener(event, () => {
            clearTimeout(activityTimeout);
            activityTimeout = setTimeout(() => {
                // Only send if last heartbeat was more than 30 seconds ago
                if (Date.now() - lastHeartbeat > minHeartbeatInterval) {
                    sendOnlineStatusHeartbeat();
                    lastHeartbeat = Date.now();
                }
            }, 1000);
        }, { passive: true });
    });
    
    // Send heartbeat before page unload (if browser supports it)
    window.addEventListener('beforeunload', () => {
        if (navigator.sendBeacon) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (csrfToken) {
                const formData = new FormData();
                formData.append('_token', csrfToken);
                navigator.sendBeacon('/api/users/update-online-status', formData);
            }
        }
    });
    
    console.log('✅ Online status heartbeat initialized');
}

// /**
//  * We'll load the axios HTTP library which allows us to easily issue requests
//  * to our Laravel back-end. This library automatically handles sending the
//  * CSRF token as a header based on the value of the "XSRF" token cookie.
//  */

// import axios from 'axios';
// window.axios = axios;

// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// /**
//  * Echo exposes an expressive API for subscribing to channels and listening
//  * for events that are broadcast by Laravel. Echo and event broadcasting
//  * allows your team to easily build robust real-time web applications.
//  */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });
