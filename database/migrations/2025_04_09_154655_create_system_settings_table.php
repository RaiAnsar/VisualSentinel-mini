<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->index();
            $table->text('value')->nullable();
            $table->string('group')->index();
            $table->string('type')->default('string');
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->json('options')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });

        // Insert default settings
        $this->insertDefaultSettings();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }

    /**
     * Insert default settings
     */
    private function insertDefaultSettings(): void
    {
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
        ];

        DB::table('system_settings')->insert($settings);
    }
};
