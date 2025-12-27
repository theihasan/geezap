import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

// Only initialize Echo if Reverb configuration is available
const reverbAppKey = import.meta.env.VITE_REVERB_APP_KEY;
const reverbHost = import.meta.env.VITE_REVERB_HOST;

if (reverbAppKey && reverbHost) {
    try {
        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: reverbAppKey,
            wsHost: reverbHost,
            wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
            wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
            forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
            enabledTransports: ['ws', 'wss'],
        });
        console.log('Echo/Reverb initialized successfully');
    } catch (error) {
        console.warn('Echo/Reverb initialization failed:', error);
        window.Echo = null;
    }
} else {
    console.log('Echo/Reverb not initialized - missing configuration');
    // Set Echo to null to indicate it's not available
    window.Echo = null;
}
