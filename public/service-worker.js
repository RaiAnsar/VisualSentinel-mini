// Service Worker for Visual Sentinel
// Handles push notifications and related events

self.addEventListener('install', event => {
  console.log('Service Worker installing.');
  self.skipWaiting();
});

self.addEventListener('activate', event => {
  console.log('Service Worker activating.');
  return self.clients.claim();
});

// Handle push notifications
self.addEventListener('push', event => {
  console.log('Push notification received:', event);

  if (!event.data) {
    console.log('Push event but no data');
    return;
  }

  try {
    const data = event.data.json();
    console.log('Push data:', data);

    const title = data.title || 'Visual Sentinel Alert';
    const options = {
      body: data.body || 'Something happened with one of your monitored websites.',
      icon: data.icon || '/images/logo.png',
      badge: data.badge || '/images/badge.png',
      data: data.data || {
        url: self.registration.scope
      },
      requireInteraction: true
    };

    event.waitUntil(
      self.registration.showNotification(title, options)
    );
  } catch (error) {
    console.error('Error showing notification:', error);
  }
});

// Handle notification click events
self.addEventListener('notificationclick', event => {
  console.log('Notification clicked:', event);
  
  event.notification.close();

  // Navigate to URL when notification is clicked
  if (event.notification.data && event.notification.data.url) {
    event.waitUntil(
      clients.matchAll({type: 'window'}).then(clientList => {
        // Check if there's already a window open
        for (const client of clientList) {
          if (client.url === event.notification.data.url && 'focus' in client) {
            return client.focus();
          }
        }
        
        // Otherwise open a new window
        if (clients.openWindow) {
          return clients.openWindow(event.notification.data.url);
        }
      })
    );
  }
});

// Handle push subscription change
self.addEventListener('pushsubscriptionchange', event => {
  console.log('Subscription expired:', event);
  
  event.waitUntil(
    self.registration.pushManager.subscribe({ userVisibleOnly: true })
      .then(subscription => {
        console.log('New subscription:', subscription);
        
        // Send the new subscription to the server
        return fetch('/settings/push-notifications/subscription', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            endpoint: subscription.endpoint,
            keys: {
              auth: subscription.getKey('auth'),
              p256dh: subscription.getKey('p256dh')
            }
          })
        });
      })
  );
}); 