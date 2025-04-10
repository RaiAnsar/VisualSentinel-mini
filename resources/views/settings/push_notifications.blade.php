<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Push Notifications') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Configure browser push notifications for monitoring alerts
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Settings Navigation Sidebar -->
                <div class="md:col-span-1">
                    <x-settings-sidebar />
                </div>
                
                <!-- Main Content -->
                <div class="md:col-span-3 space-y-6">
                    <!-- Push Notifications Card -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Browser Push Notifications</h3>
                            
                            <p class="mb-4 text-gray-600 dark:text-gray-400">
                                Receive instant alerts directly in your browser when your websites go down, change significantly, or have other issues.
                            </p>
                            
                            <div class="mb-6">
                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                                    <button class="enable-push-button inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" disabled>
                                        Enable Push Notifications
                                    </button>
                                    
                                    <div class="text-sm text-gray-600 dark:text-gray-400" id="push-status">
                                        <div class="flex items-center">
                                            <svg class="h-5 w-5 text-yellow-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            <span>Checking push notification status...</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4 text-right">
                                    <button type="button" id="reset-push-button" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                        Reset Push Notifications
                                    </button>
                                </div>
                            </div>
                            
                            <div id="notification-supported" class="hidden">
                                <div class="mt-6">
                                    <h4 class="text-base font-medium text-gray-900 dark:text-gray-100 mb-2">Test Notifications</h4>
                                    <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                                        Send a test notification to verify that push notifications are working correctly on this device.
                                    </p>
                                    
                                    <form action="{{ route('settings.push_notifications.test') }}" method="POST" class="mt-4">
                                        @csrf
                                        <x-primary-button type="submit" id="test-notification-button" disabled>
                                            {{ __('Send Test Notification') }}
                                        </x-primary-button>
                                    </form>
                                </div>
                            </div>
                            
                            <div id="notification-not-supported" class="hidden mt-6">
                                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 dark:bg-yellow-900/30 dark:border-yellow-600 dark:text-yellow-400">
                                    <p class="font-medium">Push notifications are not supported in this browser.</p>
                                    <p class="mt-2">Try using a modern browser like Chrome, Firefox, Edge, or Safari.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notification Settings -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Notification Settings</h3>
                            
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Control which notifications you receive through browser push alerts:
                                </p>
                            </div>
                            
                            <div class="space-y-4 mt-6">
                                <div class="flex items-center">
                                    <input id="notify-downtime" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:ring-offset-gray-800" checked>
                                    <label for="notify-downtime" class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Website downtime alerts
                                    </label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input id="notify-visual-changes" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:ring-offset-gray-800" checked>
                                    <label for="notify-visual-changes" class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Visual change detection
                                    </label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input id="notify-ssl" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:ring-offset-gray-800" checked>
                                    <label for="notify-ssl" class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        SSL certificate issues
                                    </label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input id="notify-performance" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:ring-offset-gray-800" checked>
                                    <label for="notify-performance" class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Performance alerts
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <x-primary-button type="button" id="save-notification-settings">
                                    {{ __('Save Settings') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- About Push Notifications -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">About Push Notifications</h3>
                            
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mt-6">
                                <h4 class="text-base font-medium text-gray-900 dark:text-gray-100 mb-2">Important Notes</h4>
                                <ul class="list-disc pl-5 text-sm text-gray-600 dark:text-gray-400 space-y-2">
                                    <li>Push notifications work when your browser is open (even in the background or on a different tab).</li>
                                    <li>You'll need to enable notifications for each browser and device you use.</li>
                                    <li>Push notifications are sent directly from our servers to your browser.</li>
                                    <li>You can disable push notifications at any time using the toggle above.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
        <script src="{{ url('/js/push-notifications.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize elements
                const pushStatus = document.getElementById('push-status');
                const notificationSupported = document.getElementById('notification-supported');
                const notificationNotSupported = document.getElementById('notification-not-supported');
                const saveSettingsBtn = document.getElementById('save-notification-settings');
                const resetPushBtn = document.getElementById('reset-push-button');
                
                // Reset push notifications
                if (resetPushBtn) {
                    resetPushBtn.addEventListener('click', function() {
                        if (confirm('This will reset push notification settings and refresh the page. Continue?')) {
                            // Unregister all service workers
                            if ('serviceWorker' in navigator) {
                                navigator.serviceWorker.getRegistrations().then(registrations => {
                                    for (let registration of registrations) {
                                        registration.unregister();
                                        console.log('Service Worker unregistered');
                                    }
                                    // Show success message
                                    const flashContainer = document.createElement('div');
                                    flashContainer.className = 'bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 dark:bg-green-900/30 dark:border-green-600 dark:text-green-400';
                                    flashContainer.innerHTML = '<p>Push notifications reset. Refreshing page...</p>';
                                    
                                    // Find the container to prepend the flash message
                                    const container = document.querySelector('.max-w-7xl.mx-auto.sm\\:px-6.lg\\:px-8');
                                    container.prepend(flashContainer);
                                    
                                    // Reload the page after a short delay
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1500);
                                });
                            }
                        }
                    });
                }
                
                // Check if notifications are supported
                if ('serviceWorker' in navigator && 'PushManager' in window) {
                    notificationSupported.classList.remove('hidden');
                    
                    // Load current notification settings
                    loadNotificationSettings();
                    
                    // Save notification settings
                    saveSettingsBtn.addEventListener('click', function() {
                        const downtimeChecked = document.getElementById('notify-downtime').checked;
                        const visualChangesChecked = document.getElementById('notify-visual-changes').checked;
                        const sslChecked = document.getElementById('notify-ssl').checked;
                        const performanceChecked = document.getElementById('notify-performance').checked;
                        
                        // Save to server
                        fetch('{{ route("settings.notification_settings.update") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                notify_downtime: downtimeChecked,
                                notify_visual_changes: visualChangesChecked,
                                notify_ssl: sslChecked,
                                notify_performance: performanceChecked
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Create a flash message
                                const flashContainer = document.createElement('div');
                                flashContainer.className = 'bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 dark:bg-green-900/30 dark:border-green-600 dark:text-green-400';
                                flashContainer.innerHTML = '<p>Notification settings saved successfully.</p>';
                                
                                // Find the container to prepend the flash message
                                const container = document.querySelector('.max-w-7xl.mx-auto.sm\\:px-6.lg\\:px-8');
                                container.prepend(flashContainer);
                                
                                // Remove the flash message after 5 seconds
                                setTimeout(function() {
                                    flashContainer.remove();
                                }, 5000);
                            }
                        })
                        .catch(error => {
                            console.error('Error saving notification settings:', error);
                            
                            // Show error message
                            const flashContainer = document.createElement('div');
                            flashContainer.className = 'bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 dark:bg-red-900/30 dark:border-red-600 dark:text-red-400';
                            flashContainer.innerHTML = '<p>Failed to save notification settings. Please try again.</p>';
                            
                            // Find the container to prepend the flash message
                            const container = document.querySelector('.max-w-7xl.mx-auto.sm\\:px-6.lg\\:px-8');
                            container.prepend(flashContainer);
                            
                            // Remove the flash message after 5 seconds
                            setTimeout(function() {
                                flashContainer.remove();
                            }, 5000);
                        });
                    });
                } else {
                    notificationNotSupported.classList.remove('hidden');
                    document.querySelector('.enable-push-button').style.display = 'none';
                    pushStatus.innerHTML = `
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-red-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>Push notifications are not supported in this browser.</span>
                        </div>
                    `;
                }
                
                // Function to update the status message based on subscription state
                window.updatePushStatusUI = function(isSubscribed) {
                    if (pushStatus) {
                        let iconClass = isSubscribed ? 'text-green-500' : 'text-yellow-500';
                        let iconPath = isSubscribed 
                            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />'
                            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />';
                        
                        let statusText = isSubscribed 
                            ? 'Push notifications are enabled for this browser.'
                            : 'Push notifications are not enabled for this browser.';
                            
                        pushStatus.innerHTML = `
                            <div class="flex items-center">
                                <svg class="h-5 w-5 ${iconClass} mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    ${iconPath}
                                </svg>
                                <span>${statusText}</span>
                            </div>
                        `;
                    }
                    
                    // Update test button state
                    const testButton = document.getElementById('test-notification-button');
                    if (testButton) {
                        testButton.disabled = !isSubscribed;
                    }
                };
                
                // Function to load notification settings
                function loadNotificationSettings() {
                    fetch('{{ route("settings.notification_settings") }}')
                        .then(response => response.json())
                        .then(data => {
                            // Update checkbox states
                            document.getElementById('notify-downtime').checked = data.notify_downtime;
                            document.getElementById('notify-visual-changes').checked = data.notify_visual_changes;
                            document.getElementById('notify-ssl').checked = data.notify_ssl;
                            document.getElementById('notify-performance').checked = data.notify_performance;
                        })
                        .catch(error => {
                            console.error('Error loading notification settings:', error);
                        });
                }
            });
        </script>
    @endpush
</x-app-layout> 