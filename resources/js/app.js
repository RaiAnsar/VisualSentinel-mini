import './bootstrap';
import './darkMode';
import './passwordToggle';
import { initPushNotifications } from './pushNotifications';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Initialize push notifications when DOM content is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Only initialize if user is authenticated
    if (document.body.classList.contains('auth')) {
        initPushNotifications()
            .then(success => {
                if (success) {
                    console.log('Push notifications initialized');
                }
            });
    }
});
