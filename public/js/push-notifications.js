class PushNotificationManager {
    constructor() {
        this.pushButton = document.querySelector('.enable-push-button');
        this.isSubscribed = false;
        this.swRegistration = null;
        this.applicationServerPublicKey = null;
        this.pushStatus = document.getElementById('push-status');
        this.notificationSupportedDiv = document.getElementById('notification-supported');
        this.notificationNotSupportedDiv = document.getElementById('notification-not-supported');
        
        this.init();
    }
    
    init() {
        console.log('Initializing push notifications manager');
        // Fetch the public VAPID key
        this.getVapidPublicKey()
            .then(key => {
                console.log('Received VAPID key:', key);
                if (!key) {
                    throw new Error('No public key in response');
                }
                this.applicationServerPublicKey = key;
                this.initServiceWorker();
            })
            .catch(error => {
                console.error('Error fetching VAPID key:', error);
                this.updatePushButtonStatus(false);
                this.showError('Failed to fetch VAPID key. Push notifications may not work properly.');
            });
    }
    
    initServiceWorker() {
        if ('serviceWorker' in navigator && 'PushManager' in window) {
            console.log('Service Worker and Push are supported');
            
            // Check if service worker is already active
            if (navigator.serviceWorker.controller) {
                console.log('Service Worker is already active');
                navigator.serviceWorker.ready.then(registration => {
                    console.log('Using existing service worker registration', registration);
                    this.swRegistration = registration;
                    this.setupPushButtons();
                    this.checkSubscription();
                    
                    if (this.notificationSupportedDiv) {
                        this.notificationSupportedDiv.classList.remove('hidden');
                    }
                });
                return;
            }
            
            // Register new service worker
            navigator.serviceWorker.register('/service-worker.js', {scope: '/'})
                .then(swReg => {
                    console.log('Service Worker is registered', swReg);
                    this.swRegistration = swReg;
                    this.setupPushButtons();
                    this.checkSubscription();
                    
                    if (this.notificationSupportedDiv) {
                        this.notificationSupportedDiv.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Service Worker Error', error);
                    this.updatePushButtonStatus(false);
                    this.showError('Failed to register service worker. Push notifications will not work. Error: ' + error.message);
                });
        } else {
            console.warn('Push messaging is not supported');
            this.updatePushButtonStatus(false);
            
            if (this.pushButton) {
                this.pushButton.style.display = 'none';
            }
            
            if (this.notificationNotSupportedDiv) {
                this.notificationNotSupportedDiv.classList.remove('hidden');
            }
            
            if (this.pushStatus) {
                this.pushStatus.innerHTML = `
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>Push notifications are not supported in this browser.</span>
                    </div>
                `;
            }
        }
    }
    
    resetServiceWorker() {
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.getRegistrations().then(registrations => {
                for (let registration of registrations) {
                    registration.unregister();
                    console.log('Service Worker unregistered');
                }
                // Reload the page to re-register service worker
                window.location.reload();
            });
        }
    }
    
    checkSubscription() {
        this.swRegistration.pushManager.getSubscription()
            .then(subscription => {
                console.log('Current subscription:', subscription);
                this.isSubscribed = !(subscription === null);
                this.updatePushButtonStatus(this.isSubscribed);
                
                // Enable/disable test notification button
                const testButton = document.getElementById('test-notification-button');
                if (testButton) {
                    testButton.disabled = !this.isSubscribed;
                }
                
                // Call global UI update function if it exists
                if (typeof window.updatePushStatusUI === 'function') {
                    window.updatePushStatusUI(this.isSubscribed);
                }
            })
            .catch(error => {
                console.error('Error checking subscription:', error);
                this.updatePushButtonStatus(false);
            });
    }
    
    setupPushButtons() {
        const pushButtons = document.querySelectorAll('.enable-push-button');
        
        if (pushButtons.length === 0) return;
        
        pushButtons.forEach(button => {
            button.disabled = false;
            button.addEventListener('click', () => this.togglePushSubscription());
        });
    }
    
    updatePushButtonStatus(isSubscribed) {
        const pushButtons = document.querySelectorAll('.enable-push-button');
        
        if (pushButtons.length === 0) return;
        
        pushButtons.forEach(button => {
            button.disabled = false;
            
            if (isSubscribed) {
                button.textContent = 'Disable Push Notifications';
                button.classList.add('push-enabled');
                button.classList.add('bg-red-600');
                button.classList.add('hover:bg-red-500');
                button.classList.remove('bg-indigo-600');
                button.classList.remove('hover:bg-indigo-500');
            } else {
                button.textContent = 'Enable Push Notifications';
                button.classList.remove('push-enabled');
                button.classList.remove('bg-red-600');
                button.classList.remove('hover:bg-red-500');
                button.classList.add('bg-indigo-600');
                button.classList.add('hover:bg-indigo-500');
            }
        });
    }
    
    togglePushSubscription() {
        if (this.isSubscribed) {
            this.unsubscribeUser();
        } else {
            // Check current permission status
            if (Notification.permission === 'denied') {
                // Permission was previously denied
                this.showError('Notification permission was previously denied. Please enable notifications in your browser settings and try again.');
                console.error('Notification permission denied by browser settings');
                
                // Show instructions for enabling notifications based on browser
                let browserName = '';
                if (navigator.userAgent.indexOf('Chrome') !== -1) {
                    browserName = 'Chrome';
                } else if (navigator.userAgent.indexOf('Firefox') !== -1) {
                    browserName = 'Firefox';
                } else if (navigator.userAgent.indexOf('Edge') !== -1) {
                    browserName = 'Edge';
                } else if (navigator.userAgent.indexOf('Safari') !== -1) {
                    browserName = 'Safari';
                }
                
                if (browserName) {
                    this.showError(`To enable notifications in ${browserName}, click the lock/info icon in the address bar and change notification settings.`);
                }
                
                return;
            }
            
            // Request notification permission
            console.log('Requesting notification permission...');
            Notification.requestPermission().then(permission => {
                console.log('Notification permission response:', permission);
                if (permission === 'granted') {
                    this.subscribeUser();
                } else {
                    this.showError('Notification permission denied. Please enable notifications in your browser settings.');
                }
            }).catch(error => {
                console.error('Error requesting notification permission:', error);
                this.showError('Failed to request notification permission: ' + error.message);
            });
        }
    }
    
    subscribeUser() {
        if (!this.applicationServerPublicKey) {
            this.showError('No VAPID public key available. Cannot subscribe to push notifications.');
            console.error('applicationServerPublicKey is null or undefined');
            return;
        }
        
        if (!this.swRegistration) {
            this.showError('Service worker not registered. Cannot subscribe to push notifications.');
            console.error('swRegistration is null or undefined');
            return;
        }
        
        const applicationServerKey = this.urlB64ToUint8Array(this.applicationServerPublicKey);
        if (!applicationServerKey) {
            return; // urlB64ToUint8Array already showed an error
        }
        
        console.log('Attempting to subscribe user with application server key:', this.applicationServerPublicKey);
        
        this.swRegistration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: applicationServerKey
        })
        .then(subscription => {
            console.log('User is subscribed:', subscription);
            this.updateSubscriptionOnServer(subscription);
            this.isSubscribed = true;
            this.updatePushButtonStatus(this.isSubscribed);
            
            // Enable test notification button
            const testButton = document.getElementById('test-notification-button');
            if (testButton) {
                testButton.disabled = false;
            }
            
            // Call global UI update function if it exists
            if (typeof window.updatePushStatusUI === 'function') {
                window.updatePushStatusUI(this.isSubscribed);
            }
            
            this.showSuccess('Successfully subscribed to push notifications!');
        })
        .catch(error => {
            console.error('Failed to subscribe the user:', error);
            
            // Provide more specific error messages
            if (error.name === 'NotAllowedError') {
                this.showError('Permission denied. Please enable notifications in your browser settings.');
            } else if (error.name === 'InvalidStateError') {
                this.showError('You are already subscribed to push notifications in another tab or window.');
            } else if (error.message && error.message.includes('permission')) {
                this.showError('Permission issue: ' + error.message);
            } else {
                this.showError('Failed to subscribe to push notifications: ' + error.message);
            }
            
            this.updatePushButtonStatus(false);
        });
    }
    
    unsubscribeUser() {
        navigator.serviceWorker.ready
            .then(registration => {
                return registration.pushManager.getSubscription();
            })
            .then(subscription => {
                if (subscription) {
                    // Delete subscription from server
                    fetch('/settings/push-notifications/subscription', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            endpoint: subscription.endpoint
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Subscription deleted from server:', data);
                    })
                    .catch(error => {
                        console.error('Error deleting subscription from server:', error);
                    });
                    
                    // Unsubscribe
                    return subscription.unsubscribe();
                }
            })
            .catch(error => {
                console.error('Error unsubscribing', error);
                this.showError('Failed to unsubscribe from push notifications. Please try again.');
            })
            .then(() => {
                console.log('User is unsubscribed');
                this.isSubscribed = false;
                this.updatePushButtonStatus(this.isSubscribed);
                
                // Disable test notification button
                const testButton = document.getElementById('test-notification-button');
                if (testButton) {
                    testButton.disabled = true;
                }
                
                // Call global UI update function if it exists
                if (typeof window.updatePushStatusUI === 'function') {
                    window.updatePushStatusUI(this.isSubscribed);
                }
                
                this.showSuccess('Successfully unsubscribed from push notifications.');
            });
    }
    
    updateSubscriptionOnServer(subscription) {
        if (subscription) {
            // Convert the subscription to JSON to send to server
            const subscriptionJson = subscription.toJSON();
            
            fetch('/settings/push-notifications/subscription', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    endpoint: subscriptionJson.endpoint,
                    keys: subscriptionJson.keys,
                    content_encoding: (PushManager.supportedContentEncodings || ['aesgcm'])[0]
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Subscription updated on server:', data);
            })
            .catch(error => {
                console.error('Failed to update subscription on server:', error);
            });
        }
    }
    
    urlB64ToUint8Array(base64String) {
        try {
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
        } catch (error) {
            console.error('Error converting base64 to Uint8Array:', error);
            this.showError('Invalid public key format. Cannot enable push notifications.');
            return null;
        }
    }
    
    getVapidPublicKey() {
        return fetch('/settings/push-notifications/key')
            .then(response => response.json())
            .then(data => {
                return data.publicKey;
            });
    }
    
    showSuccess(message) {
        this.showFlashMessage(message, 'success');
    }
    
    showError(message) {
        this.showFlashMessage(message, 'error');
    }
    
    showFlashMessage(message, type) {
        // Create a flash message element
        const container = document.querySelector('.max-w-7xl.mx-auto.sm\\:px-6.lg\\:px-8');
        if (!container) return;
        
        const flashContainer = document.createElement('div');
        
        if (type === 'success') {
            flashContainer.className = 'bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 dark:bg-green-900/30 dark:border-green-600 dark:text-green-400';
        } else {
            flashContainer.className = 'bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 dark:bg-red-900/30 dark:border-red-600 dark:text-red-400';
        }
        
        flashContainer.innerHTML = `<p>${message}</p>`;
        
        // Find any existing flash messages and remove them
        const existingFlash = container.querySelector('.bg-green-100, .bg-red-100');
        if (existingFlash) {
            existingFlash.remove();
        }
        
        // Add the flash message to the page
        container.prepend(flashContainer);
        
        // Remove the flash message after 5 seconds
        setTimeout(() => {
            if (flashContainer.parentNode) {
                flashContainer.remove();
            }
        }, 5000);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Only initialize push manager if we have elements for it
    if (document.querySelector('.enable-push-button')) {
        new PushNotificationManager();
    }
}); 