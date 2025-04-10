<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Edit Website: {{ $website->name }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Update website monitoring settings and configuration
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('websites.show', $website) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Details
                </a>
                <a href="{{ $website->url }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    Visit Website
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                
                    <!-- Tabs -->
                    <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <a href="#general" class="border-indigo-500 text-indigo-600 dark:text-indigo-400 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                General Settings
                            </a>
                            <a href="#monitoring" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Monitoring Options
                            </a>
                            <a href="#notifications" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Notifications
                            </a>
                            <a href="#advanced" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Advanced Settings
                            </a>
                        </nav>
                    </div>
                    
                    <form method="POST" action="{{ route('websites.update', $website) }}">
                        @csrf
                        @method('PUT')
                        
                        <div id="general" class="space-y-6">
                            <div class="bg-gray-50 dark:bg-gray-900/30 p-6 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">General Information</h3>
                                
                                <!-- Name -->
                                <div class="mb-4">
                                    <x-input-label for="name" :value="__('Website Name')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $website->name)" required autofocus />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">A descriptive name to identify this website.</p>
                                </div>

                                <!-- URL -->
                                <div class="mb-4">
                                    <x-input-label for="url" :value="__('Website URL')" />
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-sm">
                                            https://
                                        </span>
                                        <x-text-input id="url" class="block w-full rounded-none rounded-r-md" type="text" name="url" :value="old('url', preg_replace('/^https?:\/\//', '', $website->url))" required placeholder="www.example.com" />
                                    </div>
                                    <x-input-error :messages="$errors->get('url')" class="mt-2" />
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">The full URL of the website you want to monitor.</p>
                                </div>

                                <!-- Check Interval -->
                                <div class="mb-4">
                                    <x-input-label for="check_interval" :value="__('Check Interval (minutes)')" />
                                    <div class="mt-1 flex rounded-md shadow-sm w-full sm:w-1/4">
                                        <x-text-input id="check_interval" class="block w-full" type="number" name="check_interval" :value="old('check_interval', $website->check_interval)" min="1" required />
                                    </div>
                                    <x-input-error :messages="$errors->get('check_interval')" class="mt-2" />
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">How often the website should be checked (in minutes).</p>
                                </div>
                                
                                <!-- Tags -->
                                <div class="mb-4">
                                    <x-input-label for="tags" :value="__('Tags')" />
                                    <div class="mt-1">
                                        <select id="tags" name="tags[]" class="form-multiselect block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" multiple>
                                            @foreach($tags as $tag)
                                                <option value="{{ $tag->id }}" @selected(in_array($tag->id, old('tags', $website->tags->pluck('id')->toArray())))>{{ $tag->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <x-input-error :messages="$errors->get('tags')" class="mt-2" />
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Group websites by tags for better organization.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div id="monitoring" class="space-y-6 mt-6">
                            <div class="bg-gray-50 dark:bg-gray-900/30 p-6 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Monitoring Options</h3>
                                
                                <!-- Monitoring Status -->
                                <div class="mb-6">
                                    <h4 class="text-base font-medium text-gray-700 dark:text-gray-300 mb-2">Monitoring Status</h4>
                                    <div class="mt-2 flex items-center space-x-4">
                                        <div class="flex items-center">
                                            <input id="active" name="is_active" type="radio" value="1" @checked(old('is_active', $website->is_active) == 1) class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:border-gray-700 dark:bg-gray-900">
                                            <label for="active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                Active
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="paused" name="is_active" type="radio" value="0" @checked(old('is_active', $website->is_active) == 0) class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:border-gray-700 dark:bg-gray-900">
                                            <label for="paused" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                Paused
                                            </label>
                                        </div>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Set to 'Paused' to temporarily stop monitoring this website.</p>
                                </div>

                                <!-- Monitoring Features -->
                                <div>
                                    <h4 class="text-base font-medium text-gray-700 dark:text-gray-300 mb-2">Monitoring Features</h4>
                                    <div class="bg-white dark:bg-gray-800 rounded-md -space-y-px">
                                        <!-- Check SSL Certificate -->
                                        <div class="relative border border-gray-200 dark:border-gray-700 rounded-tl-md rounded-tr-md p-4 flex">
                                            <div class="flex items-center h-5">
                                                <input id="check_ssl" name="monitoring_options[check_ssl]" type="checkbox" value="1" @checked(old('monitoring_options.check_ssl', $website->monitoring_options['check_ssl'] ?? false)) class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:border-gray-700 dark:bg-gray-900">
                                            </div>
                                            <div class="ml-3 flex-grow">
                                                <label for="check_ssl" class="font-medium text-gray-700 dark:text-gray-300">SSL Certificate Monitoring</label>
                                                <p class="text-gray-500 dark:text-gray-400 text-sm">Monitor SSL certificate validity and expiration date.</p>
                                            </div>
                                            <div class="ml-3 flex items-center text-indigo-600 dark:text-indigo-400">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                </svg>
                                            </div>
                                        </div>
                                        
                                        <!-- Check Content Changes -->
                                        <div class="relative border border-gray-200 dark:border-gray-700 p-4 flex">
                                            <div class="flex items-center h-5">
                                                <input id="check_content" name="monitoring_options[check_content]" type="checkbox" value="1" @checked(old('monitoring_options.check_content', $website->monitoring_options['check_content'] ?? false)) class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:border-gray-700 dark:bg-gray-900">
                                            </div>
                                            <div class="ml-3 flex-grow">
                                                <label for="check_content" class="font-medium text-gray-700 dark:text-gray-300">Content Change Detection</label>
                                                <p class="text-gray-500 dark:text-gray-400 text-sm">Detect when website content changes from the baseline.</p>
                                            </div>
                                            <div class="ml-3 flex items-center text-yellow-600 dark:text-yellow-400">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        </div>
                                        
                                        <!-- Take Screenshots -->
                                        <div class="relative border border-gray-200 dark:border-gray-700 rounded-bl-md rounded-br-md p-4 flex">
                                            <div class="flex items-center h-5">
                                                <input id="take_screenshots" name="monitoring_options[take_screenshots]" type="checkbox" value="1" @checked(old('monitoring_options.take_screenshots', $website->monitoring_options['take_screenshots'] ?? false)) class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:border-gray-700 dark:bg-gray-900">
                                            </div>
                                            <div class="ml-3 flex-grow">
                                                <label for="take_screenshots" class="font-medium text-gray-700 dark:text-gray-300">Screenshot Comparison</label>
                                                <p class="text-gray-500 dark:text-gray-400 text-sm">Take screenshots of the website to visually compare changes over time.</p>
                                            </div>
                                            <div class="ml-3 flex items-center text-purple-600 dark:text-purple-400">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notifications Section -->
                        <div id="notifications" class="space-y-6 mt-6">
                            <div class="bg-gray-50 dark:bg-gray-900/30 p-6 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Notification Settings</h3>

                                <!-- Enable/Disable Notifications -->
                                <div class="mb-6">
                                    <label for="notifications_enabled" class="flex items-center">
                                        <input id="notifications_enabled" name="notification_settings[enabled]" type="checkbox" value="1" @checked(old('notification_settings.enabled', is_array($website->notification_settings) && isset($website->notification_settings['enabled']) ? $website->notification_settings['enabled'] : true)) class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:border-gray-700 dark:bg-gray-900">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Enable notifications for this website</span>
                                    </label>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Toggle alerts for downtime, SSL issues, or significant changes.</p>
                                    <x-input-error :messages="$errors->get('notification_settings.enabled')" class="mt-2" />
                                </div>

                                <!-- Notification Emails (Optional - could use global settings) -->
                                {{-- <div class="mb-4">
                                    <x-input-label for="notification_emails" :value="__('Specific Notification Emails')" />
                                    <div class="mt-1">
                                        <select id="notification_emails" name="notification_settings[emails][]" class="form-multiselect block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" multiple>
                                            @foreach($allNotificationEmails as $email)
                                                <option value="{{ $email->id }}" @selected(in_array($email->id, old('notification_settings.emails', is_array($website->notification_settings) && isset($website->notification_settings['emails']) ? $website->notification_settings['emails'] : [])))>{{ $email->email }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <x-input-error :messages="$errors->get('notification_settings.emails')" class="mt-2" />
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Select specific emails to notify for this site, or leave blank to use global list.</p>
                                </div> --}}

                                <!-- Downtime Threshold -->
                                <div class="mb-4">
                                    <x-input-label for="notify_downtime_threshold" :value="__('Notify After Consecutive Failures')" />
                                    <div class="mt-1 flex rounded-md shadow-sm w-full sm:w-1/4">
                                        <x-text-input id="notify_downtime_threshold" class="block w-full" type="number" name="notification_settings[downtime_threshold]" :value="old('notification_settings.downtime_threshold', is_array($website->notification_settings) && isset($website->notification_settings['downtime_threshold']) ? $website->notification_settings['downtime_threshold'] : 2)" min="1" />
                                    </div>
                                    <x-input-error :messages="$errors->get('notification_settings.downtime_threshold')" class="mt-2" />
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Send alert only after this many consecutive failed checks.</p>
                                </div>

                            </div>
                        </div>

                        <!-- Advanced Settings Section -->
                        <div id="advanced" class="space-y-6 mt-6">
                            <div class="bg-gray-50 dark:bg-gray-900/30 p-6 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Advanced Monitoring Settings</h3>

                                <!-- Timeout -->
                                <div class="mb-4">
                                    <x-input-label for="advanced_timeout" :value="__('Check Timeout (seconds)')" />
                                    <div class="mt-1 flex rounded-md shadow-sm w-full sm:w-1/4">
                                        <x-text-input id="advanced_timeout" class="block w-full" type="number" name="advanced_settings[timeout]" :value="old('advanced_settings.timeout', is_array($website->advanced_settings) && isset($website->advanced_settings['timeout']) ? $website->advanced_settings['timeout'] : 30)" min="1" max="120" />
                                    </div>
                                    <x-input-error :messages="$errors->get('advanced_settings.timeout')" class="mt-2" />
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Maximum time (in seconds) to wait for a response.</p>
                                </div>
                                
                                <!-- Screenshot Viewport (Example) -->
                                <div class="mb-4">
                                    <x-input-label for="advanced_viewport" :value="__('Screenshot Viewport (Width x Height)')" />
                                    <div class="mt-1 flex rounded-md shadow-sm w-full sm:w-1/2">
                                        <x-text-input id="advanced_viewport_width" class="block w-1/2 rounded-none rounded-l-md" type="number" name="advanced_settings[viewport_width]" :value="old('advanced_settings.viewport_width', is_array($website->advanced_settings) && isset($website->advanced_settings['viewport_width']) ? $website->advanced_settings['viewport_width'] : 1920)" placeholder="Width" />
                                        <span class="inline-flex items-center px-2 border border-l-0 border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-sm">
                                            x
                                        </span>
                                        <x-text-input id="advanced_viewport_height" class="block w-1/2 rounded-none rounded-r-md" type="number" name="advanced_settings[viewport_height]" :value="old('advanced_settings.viewport_height', is_array($website->advanced_settings) && isset($website->advanced_settings['viewport_height']) ? $website->advanced_settings['viewport_height'] : 1080)" placeholder="Height" />
                                    </div>
                                    <x-input-error :messages="$errors->get('advanced_settings.viewport_width')" class="mt-2" />
                                    <x-input-error :messages="$errors->get('advanced_settings.viewport_height')" class="mt-2" />
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Dimensions for capturing screenshots (e.g., 1920x1080, 1366x768).</p>
                                </div>

                                <!-- Add more advanced settings as needed -->
                                
                            </div>
                        </div>
                        
                        <!-- Tips and Best Practices -->
                        <div id="tips" class="hidden bg-blue-50 dark:bg-blue-900/30 rounded-lg p-6 mt-6">
                            <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-300 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Tips for Effective Monitoring
                            </h3>
                            
                            <div class="space-y-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-200 dark:bg-blue-800 text-blue-600 dark:text-blue-300">1</div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-md font-medium text-blue-800 dark:text-blue-300">Choose the Right Check Interval</h4>
                                        <p class="mt-1 text-sm text-blue-700 dark:text-blue-400">
                                            For critical websites, use shorter intervals (5-15 minutes). For less critical sites, longer intervals (30-60 minutes) help conserve resources.
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-200 dark:bg-blue-800 text-blue-600 dark:text-blue-300">2</div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-md font-medium text-blue-800 dark:text-blue-300">Configure Notifications Strategically</h4>
                                        <p class="mt-1 text-sm text-blue-700 dark:text-blue-400">
                                            Enable push notifications for urgent issues and email notifications for less critical alerts. Adjust performance thresholds based on your site's normal response times.
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-200 dark:bg-blue-800 text-blue-600 dark:text-blue-300">3</div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-md font-medium text-blue-800 dark:text-blue-300">Using Advanced Settings</h4>
                                        <p class="mt-1 text-sm text-blue-700 dark:text-blue-400">
                                            IP override is useful when your site is behind Cloudflare or another CDN to check the actual origin server directly.
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-200 dark:bg-blue-800 text-blue-600 dark:text-blue-300">4</div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-md font-medium text-blue-800 dark:text-blue-300">Content Change Detection</h4>
                                        <p class="mt-1 text-sm text-blue-700 dark:text-blue-400">
                                            This feature works best on stable pages. Dynamic content can trigger many false positives. Consider using it selectively for critical pages.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-6 flex justify-end">
                                <button type="button" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm flex items-center" onclick="document.getElementById('tips').classList.add('hidden')">
                                    <span>Hide Tips</span>
                                    <svg class="ml-1 w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between mt-8">
                            <button type="button" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 flex items-center text-sm" onclick="document.getElementById('tips').classList.toggle('hidden')">
                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Show/Hide Tips</span>
                            </button>
                            
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('websites.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Cancel
                                </a>
                                
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get tab elements
            const tabLinks = document.querySelectorAll('a[href^="#"]');
            const tabContents = {
                general: document.getElementById('general'),
                monitoring: document.getElementById('monitoring'),
                notifications: document.getElementById('notifications'),
                advanced: document.getElementById('advanced')
            };
            
            // Function to activate a tab
            function activateTab(tabId) {
                // Hide all tab contents
                Object.values(tabContents).forEach(content => {
                    content.style.display = 'none';
                });
                
                // Show selected tab content
                if (tabContents[tabId]) {
                    tabContents[tabId].style.display = 'block';
                }
                
                // Update tab styles
                tabLinks.forEach(link => {
                    const linkTabId = link.getAttribute('href').substring(1);
                    if (linkTabId === tabId) {
                        link.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300', 'dark:hover:border-gray-600');
                        link.classList.add('border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
                    } else {
                        link.classList.remove('border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
                        link.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300', 'dark:hover:border-gray-600');
                    }
                });
            }
            
            // Add click event listeners to tabs
            tabLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const tabId = this.getAttribute('href').substring(1);
                    activateTab(tabId);
                    // Update URL hash without scrolling
                    history.pushState(null, null, '#' + tabId);
                });
            });
            
            // Check for hash in URL
            let initialTab = 'general';
            if (window.location.hash) {
                const hash = window.location.hash.substring(1);
                if (tabContents[hash]) {
                    initialTab = hash;
                }
            }
            
            // Activate initial tab
            activateTab(initialTab);
        });
    </script>
</x-app-layout> 