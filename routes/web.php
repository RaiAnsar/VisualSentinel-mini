<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\SystemSettingsController;
use App\Http\Controllers\PushNotificationController;
use App\Http\Controllers\NotificationSettingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Website routes
    Route::resource('websites', WebsiteController::class);
    
    // Tags routes
    Route::resource('tags', TagController::class);
    
    // Website notification settings
    Route::patch('/websites/{website}/notifications', [WebsiteController::class, 'updateNotifications'])
        ->name('websites.notifications.update');
    
    // Website advanced settings
    Route::patch('/websites/{website}/advanced', [WebsiteController::class, 'updateAdvancedSettings'])
        ->name('websites.advanced.update');
    
    // Reset baseline screenshot
    Route::post('/websites/{website}/reset-baseline', [WebsiteController::class, 'resetBaseline'])
        ->name('websites.reset-baseline');
    
    // Check website now
    Route::post('/websites/{website}/check-now', [WebsiteController::class, 'checkNow'])
        ->name('websites.check-now');
    
    // Website export data
    Route::get('/websites/{website}/export', [WebsiteController::class, 'export'])
        ->name('websites.export');
    
    // Website bulk action
    Route::post('/websites/bulk-action', [WebsiteController::class, 'bulkAction'])
        ->name('websites.bulk-action');
    
    // Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        // SMTP settings
        Route::get('/smtp', [SettingsController::class, 'showSmtpSettings'])->name('smtp');
        Route::post('/smtp', [SettingsController::class, 'updateSmtpSettings'])->name('smtp.update');
        Route::post('/smtp/test', [SettingsController::class, 'testSmtpSettings'])->name('smtp.test');
        
        // Notification email settings
        Route::get('/notification-emails', [SettingsController::class, 'notificationEmails'])->name('notification_emails');
        Route::post('/notification-emails', [SettingsController::class, 'storeNotificationEmail'])->name('notification_emails.store');
        Route::patch('/notification-emails/{notificationEmail}', [SettingsController::class, 'updateNotificationEmail'])->name('notification_emails.update');
        Route::delete('/notification-emails/{notificationEmail}', [SettingsController::class, 'deleteNotificationEmail'])->name('notification_emails.destroy');
        
        // Push notification settings
        Route::get('/push-notifications', [PushNotificationController::class, 'index'])->name('push_notifications');
        Route::post('/push-notifications/test', [PushNotificationController::class, 'sendTestNotification'])->name('push_notifications.test');
        Route::post('/push-notifications/subscription', [PushNotificationController::class, 'storeSubscription'])->name('push_notifications.subscribe');
        Route::delete('/push-notifications/subscription', [PushNotificationController::class, 'deleteSubscription'])->name('push_notifications.unsubscribe');
        Route::get('/push-notifications/key', [PushNotificationController::class, 'getKey'])->name('push_notifications.key');
        
        // Notification Settings
        Route::get('/notification-settings', [NotificationSettingController::class, 'current'])->name('notification_settings');
        Route::post('/notification-settings', [NotificationSettingController::class, 'update'])->name('notification_settings.update');
    });

    // System Settings Routes
    Route::get('/settings/system', [SystemSettingsController::class, 'index'])->name('settings.system');
    Route::post('/settings/system/data-retention', [SystemSettingsController::class, 'updateDataRetention'])->name('settings.system.data-retention');
    Route::post('/settings/system/backup', [SystemSettingsController::class, 'updateBackup'])->name('settings.system.backup');
    Route::post('/settings/system/twilio', [SystemSettingsController::class, 'updateTwilio'])->name('settings.system.twilio');
    Route::post('/settings/system/license', [SystemSettingsController::class, 'updateLicense'])->name('settings.system.license');
    Route::get('/settings/system/cleanup', [SystemSettingsController::class, 'cleanupData'])->name('settings.system.cleanup');
    Route::get('/settings/system/create-backup', [SystemSettingsController::class, 'createBackup'])->name('settings.system.create-backup');
});

require __DIR__.'/auth.php';
