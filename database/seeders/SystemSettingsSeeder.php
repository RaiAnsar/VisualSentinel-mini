<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if settings already exist
        if (DB::table('system_settings')->count() > 0) {
            $this->command->info('System settings already exist, skipping...');
            return;
        }

        $settings = [
            // Data Retention Settings
            [
                'key' => 'data_retention_logs_days',
                'value' => '90',
                'group' => 'data_retention',
                'type' => 'integer',
                'display_name' => 'Monitoring Logs Retention (days)',
                'description' => 'Number of days to keep monitoring logs before automatic cleanup',
                'options' => json_encode(['min' => 7, 'max' => 365]),
                'is_public' => true
            ],
            [
                'key' => 'data_retention_screenshots_days',
                'value' => '30',
                'group' => 'data_retention',
                'type' => 'integer',
                'display_name' => 'Screenshots Retention (days)',
                'description' => 'Number of days to keep screenshots before automatic cleanup',
                'options' => json_encode(['min' => 7, 'max' => 365]),
                'is_public' => true
            ],
            [
                'key' => 'data_retention_enabled',
                'value' => 'true',
                'group' => 'data_retention',
                'type' => 'boolean',
                'display_name' => 'Enable Auto Cleanup',
                'description' => 'Automatically remove old monitoring data based on retention periods',
                'options' => null,
                'is_public' => true
            ],

            // Notification SMS Settings (Twilio)
            [
                'key' => 'twilio_sid',
                'value' => null,
                'group' => 'notifications',
                'type' => 'string',
                'display_name' => 'Twilio Account SID',
                'description' => 'Your Twilio account SID for SMS notifications',
                'options' => null,
                'is_public' => false
            ],
            [
                'key' => 'twilio_token',
                'value' => null,
                'group' => 'notifications',
                'type' => 'string',
                'display_name' => 'Twilio Auth Token',
                'description' => 'Your Twilio authentication token',
                'options' => null,
                'is_public' => false
            ],
            [
                'key' => 'twilio_phone_number',
                'value' => null,
                'group' => 'notifications',
                'type' => 'string',
                'display_name' => 'Twilio Phone Number',
                'description' => 'Your Twilio phone number for sending SMS notifications',
                'options' => null,
                'is_public' => false
            ],
            [
                'key' => 'sms_notifications_enabled',
                'value' => 'false',
                'group' => 'notifications',
                'type' => 'boolean',
                'display_name' => 'Enable SMS Notifications',
                'description' => 'Enable or disable SMS notifications via Twilio',
                'options' => null,
                'is_public' => true
            ],

            // Backup Settings
            [
                'key' => 'backup_enabled',
                'value' => 'true',
                'group' => 'backup',
                'type' => 'boolean',
                'display_name' => 'Enable Automatic Backups',
                'description' => 'Automatically backup database on a schedule',
                'options' => null,
                'is_public' => true
            ],
            [
                'key' => 'backup_frequency',
                'value' => 'daily',
                'group' => 'backup',
                'type' => 'select',
                'display_name' => 'Backup Frequency',
                'description' => 'How often to create automatic backups',
                'options' => json_encode(['daily', 'weekly', 'monthly']),
                'is_public' => true
            ],
            [
                'key' => 'backup_retention',
                'value' => '7',
                'group' => 'backup',
                'type' => 'integer',
                'display_name' => 'Backup Retention Count',
                'description' => 'Number of backups to keep before removing old ones',
                'options' => json_encode(['min' => 1, 'max' => 30]),
                'is_public' => true
            ],
            
            // Licensing
            [
                'key' => 'license_key',
                'value' => null,
                'group' => 'licensing',
                'type' => 'string',
                'display_name' => 'License Key',
                'description' => 'Your Visual Sentinel license key',
                'options' => null,
                'is_public' => false
            ],
        ];

        DB::table('system_settings')->insert($settings);
        $this->command->info('System settings seeded successfully!');
    }
}
