import Alpine from "alpinejs";
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Alpine = Alpine;
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
    authorizer: (channel, options) => {
        return {
            authorize: (socketId, callback) => {
                const sessionId = localStorage.getItem('chat_session_id') || '';
                fetch('/broadcasting/auth', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'X-Session-Id': sessionId,
                    },
                    body: JSON.stringify({
                        socket_id: socketId,
                        channel_name: channel.name,
                        session_id: sessionId,
                    }),
                })
                .then(response => response.json())
                .then(data => callback(false, data))
                .catch(error => callback(true, error));
            }
        };
    },
});

Alpine.start();

export default Alpine;
