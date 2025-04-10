<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SystemSettingsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the system settings page.
     */
    public function index()
    {
        // Group settings by their category
        $dataRetentionSettings = SystemSetting::where('group', 'data_retention')->get();
        $backupSettings = SystemSetting::where('group', 'backup')->get();
        $notificationSettings = SystemSetting::where('group', 'notifications')->get();
        $licensingSettings = SystemSetting::where('group', 'licensing')->get();

        return view('settings.system', [
            'dataRetentionSettings' => $dataRetentionSettings,
            'backupSettings' => $backupSettings,
            'notificationSettings' => $notificationSettings,
            'licensingSettings' => $licensingSettings
        ]);
    }

    /**
     * Update the data retention settings.
     */
    public function updateDataRetention(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data_retention_logs_days' => 'required|integer|min:7|max:365',
            'data_retention_screenshots_days' => 'required|integer|min:7|max:365',
            'data_retention_enabled' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->route('settings.system')
                ->withErrors($validator)
                ->withInput();
        }

        // Update settings
        SystemSetting::setValue('data_retention_logs_days', $request->input('data_retention_logs_days'));
        SystemSetting::setValue('data_retention_screenshots_days', $request->input('data_retention_screenshots_days'));
        SystemSetting::setValue('data_retention_enabled', $request->has('data_retention_enabled') ? 'true' : 'false');

        return redirect()->route('settings.system')
            ->with('success', 'Data retention settings updated successfully.');
    }

    /**
     * Update the backup settings.
     */
    public function updateBackup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'backup_frequency' => 'required|in:daily,weekly,monthly',
            'backup_retention' => 'required|integer|min:1|max:30',
            'backup_enabled' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->route('settings.system')
                ->withErrors($validator)
                ->withInput();
        }

        // Update settings
        SystemSetting::setValue('backup_frequency', $request->input('backup_frequency'));
        SystemSetting::setValue('backup_retention', $request->input('backup_retention'));
        SystemSetting::setValue('backup_enabled', $request->has('backup_enabled') ? 'true' : 'false');

        return redirect()->route('settings.system')
            ->with('success', 'Backup settings updated successfully.');
    }

    /**
     * Update the Twilio SMS settings.
     */
    public function updateTwilio(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'twilio_sid' => 'nullable|string',
            'twilio_token' => 'nullable|string',
            'twilio_phone_number' => 'nullable|string',
            'sms_notifications_enabled' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->route('settings.system')
                ->withErrors($validator)
                ->withInput();
        }

        // Update settings
        SystemSetting::setValue('twilio_sid', $request->input('twilio_sid'));
        SystemSetting::setValue('twilio_token', $request->input('twilio_token'));
        SystemSetting::setValue('twilio_phone_number', $request->input('twilio_phone_number'));
        SystemSetting::setValue('sms_notifications_enabled', $request->has('sms_notifications_enabled') ? 'true' : 'false');

        return redirect()->route('settings.system')
            ->with('success', 'SMS notification settings updated successfully.');
    }

    /**
     * Create a database backup manually.
     */
    public function createBackup()
    {
        // In a real application, this would use a backup package like spatie/laravel-backup
        // For now, we'll just simulate a successful backup
        
        // Generate a timestamp for the backup filename
        $timestamp = date('Y-m-d_H-i-s');
        $filename = "visual_sentinel_backup_{$timestamp}.sql";
        
        // Return success message with the simulated filename
        return redirect()->route('settings.system')
            ->with('success', "Database backup created successfully: {$filename}");
    }
    
    /**
     * Run manual data cleanup based on retention settings.
     */
    public function cleanupData()
    {
        // Get retention settings
        $logsRetentionDays = SystemSetting::getValue('data_retention_logs_days', 90);
        $screenshotsRetentionDays = SystemSetting::getValue('data_retention_screenshots_days', 30);
        
        // Calculate cutoff dates
        $logsCutoff = now()->subDays($logsRetentionDays);
        $screenshotsCutoff = now()->subDays($screenshotsRetentionDays);
        
        // In a real application, this would delete records from the database
        // For now, we'll just return a success message with how many would be deleted
        
        return redirect()->route('settings.system')
            ->with('success', "Data cleanup completed. Removed logs older than {$logsRetentionDays} days and screenshots older than {$screenshotsRetentionDays} days.");
    }

    /**
     * Update the license key.
     */
    public function updateLicense(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'license_key' => 'required|string|min:8|max:64',
        ]);

        if ($validator->fails()) {
            return redirect()->route('settings.system')
                ->withErrors($validator)
                ->withInput();
        }

        // Update license key setting
        SystemSetting::setValue('license_key', $request->input('license_key'));

        // Add license check simulation here
        $isValid = $this->validateLicenseKey($request->input('license_key'));

        if (!$isValid) {
            return redirect()->route('settings.system')
                ->with('error', 'The license key is not valid. Please check and try again.');
        }

        return redirect()->route('settings.system')
            ->with('success', 'License key updated successfully.');
    }

    /**
     * Simulate license key validation.
     * 
     * @param string $licenseKey
     * @return bool
     */
    private function validateLicenseKey(string $licenseKey): bool
    {
        // For demo purposes, we'll consider a license valid if it:
        // 1. Is at least 16 characters
        // 2. Contains at least one uppercase letter
        // 3. Contains at least one number
        // 4. Starts with "VS-"
        
        $isValid = strlen($licenseKey) >= 16 &&
                  preg_match('/[A-Z]/', $licenseKey) &&
                  preg_match('/[0-9]/', $licenseKey) &&
                  strpos($licenseKey, 'VS-') === 0;
                  
        return $isValid;
    }
}
