<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('System Settings') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Configure system-wide settings for data retention, backups, and notifications
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Data Retention Settings -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Data Retention') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Configure how long monitoring data is kept before automatic cleanup.') }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('settings.system.data-retention') }}" class="mt-6 space-y-6">
                            @csrf
                            
                            <!-- Data Retention Enabled -->
                            <div class="block">
                                <label class="flex items-center">
                                    <input
                                        type="checkbox"
                                        name="data_retention_enabled"
                                        class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                        {{ SystemSetting::getValue('data_retention_enabled', true) ? 'checked' : '' }}
                                    >
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Enable automatic data cleanup') }}</span>
                                </label>
                            </div>

                            <!-- Monitoring Logs Retention -->
                            <div>
                                <x-input-label for="data_retention_logs_days" :value="__('Monitoring Logs Retention (days)')" />
                                <x-text-input
                                    id="data_retention_logs_days"
                                    name="data_retention_logs_days"
                                    type="number"
                                    class="mt-1 block w-full"
                                    :value="SystemSetting::getValue('data_retention_logs_days', 90)"
                                    min="7"
                                    max="365"
                                    required
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('data_retention_logs_days')" />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Number of days to keep monitoring logs. Older logs will be automatically deleted.</p>
                            </div>

                            <!-- Screenshots Retention -->
                            <div>
                                <x-input-label for="data_retention_screenshots_days" :value="__('Screenshots Retention (days)')" />
                                <x-text-input
                                    id="data_retention_screenshots_days"
                                    name="data_retention_screenshots_days"
                                    type="number"
                                    class="mt-1 block w-full"
                                    :value="SystemSetting::getValue('data_retention_screenshots_days', 30)"
                                    min="7"
                                    max="365"
                                    required
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('data_retention_screenshots_days')" />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Number of days to keep screenshots. Older screenshots will be automatically deleted.</p>
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>

                                <a href="{{ route('settings.system.cleanup') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                                    {{ __('Run Cleanup Now') }}
                                </a>

                                @if (session('success') && str_contains(session('success'), 'Data'))
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)" class="text-sm text-green-600 dark:text-green-400">{{ session('success') }}</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            <!-- Backup Settings -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Database Backup') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Configure automatic backups of your monitoring data.') }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('settings.system.backup') }}" class="mt-6 space-y-6">
                            @csrf
                            
                            <!-- Backup Enabled -->
                            <div class="block">
                                <label class="flex items-center">
                                    <input
                                        type="checkbox"
                                        name="backup_enabled"
                                        class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                        {{ SystemSetting::getValue('backup_enabled', true) ? 'checked' : '' }}
                                    >
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Enable automatic backups') }}</span>
                                </label>
                            </div>

                            <!-- Backup Frequency -->
                            <div>
                                <x-input-label for="backup_frequency" :value="__('Backup Frequency')" />
                                <select id="backup_frequency" name="backup_frequency" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="daily" {{ SystemSetting::getValue('backup_frequency') === 'daily' ? 'selected' : '' }}>Daily</option>
                                    <option value="weekly" {{ SystemSetting::getValue('backup_frequency') === 'weekly' ? 'selected' : '' }}>Weekly</option>
                                    <option value="monthly" {{ SystemSetting::getValue('backup_frequency') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('backup_frequency')" />
                            </div>

                            <!-- Backup Retention -->
                            <div>
                                <x-input-label for="backup_retention" :value="__('Number of Backups to Keep')" />
                                <x-text-input
                                    id="backup_retention"
                                    name="backup_retention"
                                    type="number"
                                    class="mt-1 block w-full"
                                    :value="SystemSetting::getValue('backup_retention', 7)"
                                    min="1"
                                    max="30"
                                    required
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('backup_retention')" />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Number of backup copies to keep before removing the oldest ones.</p>
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>

                                <a href="{{ route('settings.system.create-backup') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                                    {{ __('Create Backup Now') }}
                                </a>

                                @if (session('success') && str_contains(session('success'), 'backup'))
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)" class="text-sm text-green-600 dark:text-green-400">{{ session('success') }}</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            <!-- Twilio SMS Settings -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('SMS Notifications (Twilio)') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Configure SMS notifications for website status alerts.') }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('settings.system.twilio') }}" class="mt-6 space-y-6">
                            @csrf
                            
                            <!-- SMS Notifications Enabled -->
                            <div class="block">
                                <label class="flex items-center">
                                    <input
                                        type="checkbox"
                                        name="sms_notifications_enabled"
                                        class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                        {{ SystemSetting::getValue('sms_notifications_enabled', false) ? 'checked' : '' }}
                                    >
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Enable SMS notifications') }}</span>
                                </label>
                            </div>

                            <!-- Twilio Account SID -->
                            <div>
                                <x-input-label for="twilio_sid" :value="__('Twilio Account SID')" />
                                <x-text-input
                                    id="twilio_sid"
                                    name="twilio_sid"
                                    type="text"
                                    class="mt-1 block w-full"
                                    :value="SystemSetting::getValue('twilio_sid')"
                                    autocomplete="off"
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('twilio_sid')" />
                            </div>

                            <!-- Twilio Auth Token -->
                            <div>
                                <x-input-label for="twilio_token" :value="__('Twilio Auth Token')" />
                                <x-text-input
                                    id="twilio_token"
                                    name="twilio_token"
                                    type="password"
                                    class="mt-1 block w-full"
                                    :value="SystemSetting::getValue('twilio_token')"
                                    autocomplete="off"
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('twilio_token')" />
                            </div>

                            <!-- Twilio Phone Number -->
                            <div>
                                <x-input-label for="twilio_phone_number" :value="__('Twilio Phone Number')" />
                                <x-text-input
                                    id="twilio_phone_number"
                                    name="twilio_phone_number"
                                    type="text"
                                    class="mt-1 block w-full"
                                    :value="SystemSetting::getValue('twilio_phone_number')"
                                    placeholder="+15551234567"
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('twilio_phone_number')" />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Enter in international format, e.g., +15551234567</p>
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>

                                @if (session('success') && str_contains(session('success'), 'SMS'))
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)" class="text-sm text-green-600 dark:text-green-400">{{ session('success') }}</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            <!-- License Settings -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('License Management') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Manage your Visual Sentinel license key.') }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('settings.system.license') }}" class="mt-6 space-y-6">
                            @csrf
                            
                            <!-- License Key -->
                            <div>
                                <x-input-label for="license_key" :value="__('License Key')" />
                                <x-text-input
                                    id="license_key"
                                    name="license_key"
                                    type="text"
                                    class="mt-1 block w-full"
                                    :value="SystemSetting::getValue('license_key')"
                                    placeholder="VS-XXXX-XXXX-XXXX-XXXX"
                                    autocomplete="off"
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('license_key')" />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Enter your Visual Sentinel license key. Valid license keys start with "VS-" and should be at least 16 characters.</p>
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>

                                @if (session('success') && str_contains(session('success'), 'License'))
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)" class="text-sm text-green-600 dark:text-green-400">{{ session('success') }}</p>
                                @endif
                                
                                @if (session('error') && str_contains(session('error'), 'license'))
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)" class="text-sm text-red-600 dark:text-red-400">{{ session('error') }}</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 