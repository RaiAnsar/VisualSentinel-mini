<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('SMTP Settings') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Configure your email server for sending notifications
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
                    <!-- SMTP Settings Form -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <form method="POST" action="{{ route('settings.smtp.update') }}">
                                @csrf
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- SMTP Host -->
                                    <div>
                                        <x-input-label for="smtp_host" :value="__('SMTP Host')" />
                                        <x-text-input id="smtp_host" class="block mt-1 w-full" type="text" name="smtp_host" :value="old('smtp_host', $smtpSettings['smtp_host'] ?? '')" required />
                                        <x-input-error :messages="$errors->get('smtp_host')" class="mt-2" />
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">e.g. smtp.gmail.com, smtp.mailgun.org</p>
                                    </div>
                                    
                                    <!-- SMTP Port -->
                                    <div>
                                        <x-input-label for="smtp_port" :value="__('SMTP Port')" />
                                        <x-text-input id="smtp_port" class="block mt-1 w-full" type="number" name="smtp_port" :value="old('smtp_port', $smtpSettings['smtp_port'] ?? '587')" required />
                                        <x-input-error :messages="$errors->get('smtp_port')" class="mt-2" />
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Common ports: 25, 465, 587, 2525</p>
                                    </div>
                                    
                                    <!-- SMTP Username -->
                                    <div>
                                        <x-input-label for="smtp_username" :value="__('SMTP Username')" />
                                        <x-text-input id="smtp_username" class="block mt-1 w-full" type="text" name="smtp_username" :value="old('smtp_username', $smtpSettings['smtp_username'] ?? '')" required />
                                        <x-input-error :messages="$errors->get('smtp_username')" class="mt-2" />
                                    </div>
                                    
                                    <!-- SMTP Password -->
                                    <div>
                                        <x-input-label for="smtp_password" :value="__('SMTP Password')" />
                                        <x-text-input id="smtp_password" class="block mt-1 w-full" type="password" name="smtp_password" :value="old('smtp_password', $smtpSettings['smtp_password'] ?? '')" required />
                                        <x-input-error :messages="$errors->get('smtp_password')" class="mt-2" />
                                    </div>
                                    
                                    <!-- SMTP Encryption -->
                                    <div>
                                        <x-input-label for="smtp_encryption" :value="__('SMTP Encryption')" />
                                        <select id="smtp_encryption" name="smtp_encryption" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300">
                                            <option value="tls" {{ (old('smtp_encryption', $smtpSettings['smtp_encryption'] ?? 'tls') == 'tls') ? 'selected' : '' }}>TLS</option>
                                            <option value="ssl" {{ (old('smtp_encryption', $smtpSettings['smtp_encryption'] ?? '') == 'ssl') ? 'selected' : '' }}>SSL</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('smtp_encryption')" class="mt-2" />
                                    </div>
                                    
                                    <!-- From Address -->
                                    <div>
                                        <x-input-label for="smtp_from_address" :value="__('From Email Address')" />
                                        <x-text-input id="smtp_from_address" class="block mt-1 w-full" type="email" name="smtp_from_address" :value="old('smtp_from_address', $smtpSettings['smtp_from_address'] ?? '')" required />
                                        <x-input-error :messages="$errors->get('smtp_from_address')" class="mt-2" />
                                    </div>
                                    
                                    <!-- From Name -->
                                    <div>
                                        <x-input-label for="smtp_from_name" :value="__('From Name')" />
                                        <x-text-input id="smtp_from_name" class="block mt-1 w-full" type="text" name="smtp_from_name" :value="old('smtp_from_name', $smtpSettings['smtp_from_name'] ?? 'Visual Sentinel')" required />
                                        <x-input-error :messages="$errors->get('smtp_from_name')" class="mt-2" />
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-end mt-6">
                                    <x-primary-button>
                                        {{ __('Save SMTP Settings') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Test SMTP Settings -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Test SMTP Settings</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Send a test email to verify your SMTP configuration is working correctly.
                            </p>
                            
                            <form method="POST" action="{{ route('settings.smtp.test') }}" class="mt-4">
                                @csrf
                                
                                <div class="flex flex-col md:flex-row gap-4">
                                    <div class="flex-grow">
                                        <x-input-label for="test_email" :value="__('Test Email Address')" />
                                        <x-text-input id="test_email" class="block mt-1 w-full" type="email" name="test_email" required />
                                    </div>
                                    
                                    <div class="self-end">
                                        <x-primary-button>
                                            {{ __('Send Test Email') }}
                                        </x-primary-button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 