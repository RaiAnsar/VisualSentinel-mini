<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\TagController;
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
    
    // Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        // SMTP Settings
        Route::get('/smtp', [SettingsController::class, 'showSmtpSettings'])->name('smtp');
        Route::post('/smtp', [SettingsController::class, 'updateSmtpSettings'])->name('smtp.update');
        Route::post('/smtp/test', [SettingsController::class, 'testSmtpSettings'])->name('smtp.test');
        
        // Notification Emails
        Route::get('/notification-emails', [SettingsController::class, 'notificationEmails'])->name('notification_emails');
        Route::post('/notification-emails', [SettingsController::class, 'storeNotificationEmail'])->name('notification_emails.store');
        Route::patch('/notification-emails/{notificationEmail}', [SettingsController::class, 'updateNotificationEmail'])->name('notification_emails.update');
        Route::delete('/notification-emails/{notificationEmail}', [SettingsController::class, 'deleteNotificationEmail'])->name('notification_emails.destroy');
        
        // Push Notifications
        Route::get('/push-notifications', [SettingsController::class, 'pushNotificationSettings'])->name('push_notifications');
        Route::post('/push-subscription', [SettingsController::class, 'storePushSubscription'])->name('push_subscription.store');
    });
});

require __DIR__.'/auth.php';
