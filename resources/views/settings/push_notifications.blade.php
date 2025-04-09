<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Push Notifications') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Configure browser push notifications settings
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Session Status -->
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 dark:bg-green-900/30 dark:border-green-600 dark:text-green-400" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 dark:bg-red-900/30 dark:border-red-600 dark:text-red-400" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Push Notification Settings -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Push Notification Settings</h3>
                    
                    <div class="mb-8">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Enable push notifications to receive alerts directly in your browser when your monitored websites have issues.
                            Push notifications work even when you're not actively using Visual Sentinel.
                        </p>

                        <div class="flex items-center mb-4">
                            <div class="mr-6">
                                <div id="subscription-status" class="text-sm font-medium">
                                    @if($isPushEnabled)
                                        <span class="text-green-600 dark:text-green-400">Push notifications are enabled</span>
                                    @else
                                        <span class="text-red-600 dark:text-red-400">Push notifications are not enabled</span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    @if($isPushEnabled)
                                        You will receive browser notifications for monitored websites
                                    @else
                                        You will not receive browser notifications
                                    @endif
                                </div>
                            </div>
                            
                            <div>
                                @if($isPushEnabled)
                                    <button id="btn-disable-push" type="button" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-300 disabled:opacity-25 transition">
                                        Disable Push Notifications
                                    </button>
                                @else
                                    <button id="btn-enable-push" type="button" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                                        Enable Push Notifications
                                    </button>
                                @endif
                            </div>
                        </div>
                        
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 dark:border-yellow-600 p-4 mt-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400 dark:text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700 dark:text-yellow-400">
                                        <strong>Note:</strong> Push notifications work only in supported browsers and require HTTPS.
                                        You will be prompted to allow notifications by your browser when enabling this feature.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-4">Notification Preferences</h4>
                    
                    <form id="push-preferences-form" method="POST" action="{{ route('settings.push_notifications.update_preferences') }}" class="mt-4">
                        @csrf
                        
                        <div class="space-y-4">
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="notify_down" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ $preferences->notify_down ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Notify when a website goes down</span>
                                </label>
                            </div>
                            
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="notify_up" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ $preferences->notify_up ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Notify when a website recovers</span>
                                </label>
                            </div>
                            
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="notify_performance" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ $preferences->notify_performance ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Notify on performance issues</span>
                                </label>
                            </div>
                            
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="notify_visual_change" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ $preferences->notify_visual_change ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Notify on significant visual changes</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <x-primary-button>
                                {{ __('Save Preferences') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Current Devices -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Registered Devices</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        These are the devices currently registered to receive push notifications.
                    </p>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Device</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Browser</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Last Used</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($pushSubscriptions as $subscription)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $subscription->device_name ?? 'Unknown Device' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $subscription->browser ?? 'Unknown Browser' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $subscription->updated_at->diffForHumans() }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <form method="POST" action="{{ route('settings.push_notifications.delete_subscription', $subscription) }}" class="inline-block" onsubmit="return confirm('Are you sure you want to remove this device?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                                    Remove
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                            No devices registered for push notifications.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Push Notification Service Worker Registration
        document.addEventListener('DOMContentLoaded', function() {
            // Check if service workers are supported
            if ('serviceWorker' in navigator && 'PushManager' in window) {
                // Register event listeners for the buttons
                const enableButton = document.getElementById('btn-enable-push');
                const disableButton = document.getElementById('btn-disable-push');
                
                if (enableButton) {
                    enableButton.addEventListener('click', enablePushNotifications);
                }
                
                if (disableButton) {
                    disableButton.addEventListener('click', disablePushNotifications);
                }
            } else {
                // Service workers not supported
                const subscriptionStatus = document.getElementById('subscription-status');
                if (subscriptionStatus) {
                    subscriptionStatus.innerHTML = '<span class="text-red-600 dark:text-red-400">Push notifications are not supported in your browser</span>';
                }
                
                // Disable the buttons
                const buttons = document.querySelectorAll('#btn-enable-push, #btn-disable-push');
                buttons.forEach(button => {
                    if (button) {
                        button.disabled = true;
                        button.classList.add('opacity-50', 'cursor-not-allowed');
                    }
                });
            }
        });
        
        async function enablePushNotifications() {
            try {
                // Register the service worker
                const registration = await navigator.serviceWorker.register('/service-worker.js');
                
                // Request permission
                const permission = await Notification.requestPermission();
                if (permission !== 'granted') {
                    throw new Error('Permission not granted for push notifications');
                }
                
                // Get the push subscription
                const subscription = await registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: urlBase64ToUint8Array('{{ $vapidPublicKey }}')
                });
                
                // Send the subscription to the server
                await fetch('{{ route('settings.push_notifications.subscribe') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        subscription: subscription,
                        device_info: {
                            browser: getBrowserInfo(),
                            device: getDeviceInfo()
                        }
                    })
                });
                
                // Update the UI
                window.location.reload();
            } catch (error) {
                console.error('Error enabling push notifications:', error);
                alert('There was an error enabling push notifications: ' + error.message);
            }
        }
        
        async function disablePushNotifications() {
            try {
                // Get the service worker registration
                const registration = await navigator.serviceWorker.getRegistration();
                if (!registration) {
                    throw new Error('No service worker registration found');
                }
                
                // Get the push subscription
                const subscription = await registration.pushManager.getSubscription();
                if (!subscription) {
                    throw new Error('No push subscription found');
                }
                
                // Send the unsubscribe request to the server
                await fetch('{{ route('settings.push_notifications.unsubscribe') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        endpoint: subscription.endpoint
                    })
                });
                
                // Unsubscribe from push
                await subscription.unsubscribe();
                
                // Update the UI
                window.location.reload();
            } catch (error) {
                console.error('Error disabling push notifications:', error);
                alert('There was an error disabling push notifications: ' + error.message);
            }
        }
        
        // Helper function to convert base64 to Uint8Array for the applicationServerKey
        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding)
                .replace(/-/g, '+')
                .replace(/_/g, '/');
            
            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);
            
            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }
        
        // Helper function to get browser info
        function getBrowserInfo() {
            const userAgent = navigator.userAgent;
            let browser = 'Unknown';
            
            if (userAgent.indexOf('Firefox') > -1) {
                browser = 'Firefox';
            } else if (userAgent.indexOf('Chrome') > -1) {
                browser = 'Chrome';
            } else if (userAgent.indexOf('Safari') > -1) {
                browser = 'Safari';
            } else if (userAgent.indexOf('Edge') > -1) {
                browser = 'Edge';
            } else if (userAgent.indexOf('MSIE') > -1 || userAgent.indexOf('Trident/') > -1) {
                browser = 'Internet Explorer';
            }
            
            return browser;
        }
        
        // Helper function to get device info
        function getDeviceInfo() {
            const userAgent = navigator.userAgent;
            let device = 'Desktop';
            
            if (/Android/i.test(userAgent)) {
                device = 'Android';
            } else if (/iPhone|iPad|iPod/i.test(userAgent)) {
                device = 'iOS';
            } else if (/Windows Phone/i.test(userAgent)) {
                device = 'Windows Phone';
            } else if (/Windows/i.test(userAgent)) {
                device = 'Windows';
            } else if (/Macintosh/i.test(userAgent)) {
                device = 'Mac';
            } else if (/Linux/i.test(userAgent)) {
                device = 'Linux';
            }
            
            return device;
        }
    </script>
</x-app-layout> 