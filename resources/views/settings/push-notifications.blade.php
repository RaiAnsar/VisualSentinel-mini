<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Push Notifications Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Browser Push Notifications') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Enable browser push notifications to receive alerts when your websites go down or experience visual changes, even when you\'re not on the app.') }}
                            </p>
                        </header>

                        <div class="mt-6 flex items-center space-x-4">
                            <button type="button" class="enable-push-button px-4 py-2 bg-primary-500 dark:bg-primary-600 text-white rounded-md shadow-sm hover:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 disabled:opacity-25" disabled>
                                {{ __('Loading...') }}
                            </button>
                            
                            <div id="push-notification-status" class="hidden">
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('Push notifications are ') }}
                                    <span id="push-status-indicator" class="font-medium"></span>
                                </span>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Test Push Notification') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Send a test notification to verify your push notification settings are working correctly.') }}
                            </p>
                        </header>

                        <div class="mt-6">
                            <form action="{{ route('settings.push_notifications.test') }}" method="post">
                                @csrf
                                <x-primary-button type="submit" id="test-notification-button">
                                    {{ __('Send Test Notification') }}
                                </x-primary-button>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
            
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('About Push Notifications') }}
                            </h2>
                        </header>

                        <div class="mt-6 space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="mt-1 flex-shrink-0">
                                    <svg class="h-5 w-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('Push notifications are sent directly to your browser, even when you\'re not on the Visual Sentinel website.') }}
                                </p>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="mt-1 flex-shrink-0">
                                    <svg class="h-5 w-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('You\'ll need to allow notifications for this website when prompted by your browser.') }}
                                </p>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="mt-1 flex-shrink-0">
                                    <svg class="h-5 w-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('If you\'re using multiple browsers or devices, you\'ll need to enable push notifications on each one.') }}
                                </p>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
        <script src="{{ asset('js/push-notifications.js') }}"></script>
    @endpush
</x-app-layout> 