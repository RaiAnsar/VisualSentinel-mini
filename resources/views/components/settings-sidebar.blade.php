<div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg mb-6">
    <div class="p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Settings</h3>
        
        <div class="space-y-3">
            <div>
                <a 
                    href="{{ route('settings.smtp') }}" 
                    class="flex items-center {{ request()->routeIs('settings.smtp') ? 'text-indigo-600 dark:text-indigo-400 font-medium' : 'text-gray-700 dark:text-gray-300' }} hover:text-indigo-600 dark:hover:text-indigo-400"
                >
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                    SMTP Settings
                </a>
            </div>
            
            <div>
                <a 
                    href="{{ route('settings.notification_emails') }}" 
                    class="flex items-center {{ request()->routeIs('settings.notification_emails') ? 'text-indigo-600 dark:text-indigo-400 font-medium' : 'text-gray-700 dark:text-gray-300' }} hover:text-indigo-600 dark:hover:text-indigo-400"
                >
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 9v.906a2.25 2.25 0 01-1.183 1.981l-6.478 3.488M2.25 9v.906a2.25 2.25 0 001.183 1.981l6.478 3.488m8.839 2.51l-4.66-2.51m0 0l-1.023-.55a2.25 2.25 0 00-2.134 0l-1.022.55m0 0l-4.661 2.51m16.5 1.615a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V8.844a2.25 2.25 0 011.183-1.98l7.5-4.04a2.25 2.25 0 012.134 0l7.5 4.04a2.25 2.25 0 011.183 1.98V19.5z" />
                    </svg>
                    Notification Emails
                </a>
            </div>
            
            <div>
                <a 
                    href="{{ route('settings.push_notifications') }}" 
                    class="flex items-center {{ request()->routeIs('settings.push_notifications') ? 'text-indigo-600 dark:text-indigo-400 font-medium' : 'text-gray-700 dark:text-gray-300' }} hover:text-indigo-600 dark:hover:text-indigo-400"
                >
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    Push Notifications
                </a>
            </div>
            
            <div>
                <a 
                    href="{{ route('settings.system') }}" 
                    class="flex items-center {{ request()->routeIs('settings.system') ? 'text-indigo-600 dark:text-indigo-400 font-medium' : 'text-gray-700 dark:text-gray-300' }} hover:text-indigo-600 dark:hover:text-indigo-400"
                >
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                    </svg>
                    System Settings
                </a>
            </div>
        </div>
    </div>
</div> 