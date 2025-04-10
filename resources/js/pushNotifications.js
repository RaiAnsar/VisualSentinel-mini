/**
 * Push Notifications handler
 */

export function initPushNotifications() {
    // Check if the browser supports service workers and Push API
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
        console.warn('Push notifications are not supported by this browser');
        return false;
    }

    // Register service worker and subscribe to push notifications
    return registerServiceWorker()
        .then(subscribeToPushNotifications)
        .then(() => true)
        .catch(error => {
            console.error('Error initializing push notifications:', error);
            return false;
        });
}

/**
 * Register the service worker
 */
function registerServiceWorker() {
    return navigator.serviceWorker.register('/service-worker.js')
        .then(registration => {
            console.log('Service Worker registered successfully:', registration);
            return registration;
        });
}

/**
 * Subscribe to push notifications
 */
function subscribeToPushNotifications(registration) {
    return registration.pushManager.getSubscription()
        .then(subscription => {
            if (subscription) {
                console.log('User is already subscribed to push notifications');
                return subscription;
            }

            // Get the server's public key
            return fetch('/api/push/key')
                .then(response => response.json())
                .then(data => {
                    const vapidPublicKey = data.vapidPublicKey;
                    const convertedVapidKey = urlBase64ToUint8Array(vapidPublicKey);

                    // Subscribe the user
                    return registration.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: convertedVapidKey
                    });
                })
                .then(subscription => {
                    // Send the subscription details to the server
                    return fetch('/api/push/subscribe', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            subscription: subscription
                        })
                    })
                    .then(() => subscription);
                });
        });
}

/**
 * Convert a base64 string to a Uint8Array for the applicationServerKey
 */
function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
} 