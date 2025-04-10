<?php

namespace App\Http\Controllers;

use App\Models\NotificationEmail;
use App\Models\Setting;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    /**
     * Display the SMTP settings page.
     */
    public function showSmtpSettings()
    {
        $smtpSettings = Setting::getSmtpSettings();
        $masterEmails = NotificationEmail::master()->get();
        
        return view('settings.smtp', [
            'smtpSettings' => $smtpSettings,
            'masterEmails' => $masterEmails
        ]);
    }
    
    /**
     * Update SMTP settings.
     */
    public function updateSmtpSettings(Request $request)
    {
        $validated = $request->validate([
            'smtp_host' => 'required|string|max:255',
            'smtp_port' => 'required|numeric',
            'smtp_username' => 'required|string|max:255',
            'smtp_password' => 'required|string|max:255',
            'smtp_encryption' => 'required|in:tls,ssl',
            'smtp_from_address' => 'required|email',
            'smtp_from_name' => 'required|string|max:255',
        ]);
        
        // Save settings
        foreach ($validated as $key => $value) {
            Setting::set($key, $value, 'smtp', ucfirst(str_replace('_', ' ', $key)));
        }
        
        return redirect()->route('settings.smtp')->with('success', 'SMTP settings updated successfully!');
    }
    
    /**
     * Test SMTP settings.
     */
    public function testSmtpSettings(Request $request)
    {
        $email = $request->input('test_email');
        
        if (!$email) {
            return redirect()->back()->with('error', 'Test email address is required.');
        }
        
        try {
            // Get SMTP settings
            $smtpSettings = Setting::getSmtpSettings();
            
            // Log SMTP settings for debugging (without password)
            Log::info('SMTP Test Settings', [
                'host' => $smtpSettings['smtp_host'] ?? env('MAIL_HOST'),
                'port' => $smtpSettings['smtp_port'] ?? env('MAIL_PORT'),
                'username' => $smtpSettings['smtp_username'] ?? env('MAIL_USERNAME'),
                'encryption' => $smtpSettings['smtp_encryption'] ?? env('MAIL_ENCRYPTION'),
                'from_address' => $smtpSettings['smtp_from_address'] ?? env('MAIL_FROM_ADDRESS'),
                'from_name' => $smtpSettings['smtp_from_name'] ?? env('MAIL_FROM_NAME'),
            ]);
            
            // Configure mail with these settings
            config([
                'mail.mailers.smtp.host' => $smtpSettings['smtp_host'] ?? env('MAIL_HOST'),
                'mail.mailers.smtp.port' => $smtpSettings['smtp_port'] ?? env('MAIL_PORT'),
                'mail.mailers.smtp.username' => $smtpSettings['smtp_username'] ?? env('MAIL_USERNAME'),
                'mail.mailers.smtp.password' => $smtpSettings['smtp_password'] ?? env('MAIL_PASSWORD'),
                'mail.mailers.smtp.encryption' => $smtpSettings['smtp_encryption'] ?? env('MAIL_ENCRYPTION'),
                'mail.from.address' => $smtpSettings['smtp_from_address'] ?? env('MAIL_FROM_ADDRESS'),
                'mail.from.name' => $smtpSettings['smtp_from_name'] ?? env('MAIL_FROM_NAME'),
                // Additional settings to help with TLS/SSL issues
                'mail.mailers.smtp.allow_self_signed' => true,
                'mail.mailers.smtp.verify_peer' => false,
                'mail.mailers.smtp.verify_peer_name' => false,
            ]);
            
            // Force the mailer to be SMTP
            config(['mail.default' => 'smtp']);
            
            // Send test email
            Mail::raw('This is a test email from Visual Sentinel.', function($message) use ($email) {
                $message->to($email)
                    ->subject('Visual Sentinel - Test Email');
            });
            
            return redirect()->back()->with('success', 'Test email sent successfully! Please check your inbox and spam folder.');
        } catch (\Exception $e) {
            Log::error('SMTP Test Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }
    
    /**
     * Show all notification emails.
     */
    public function notificationEmails()
    {
        $masterEmails = NotificationEmail::master()->get();
        $websiteEmails = NotificationEmail::websiteSpecific()->with('website')->get();
        $websites = Website::where('user_id', Auth::id())->get();
        
        return view('settings.notification_emails', [
            'masterEmails' => $masterEmails,
            'websiteEmails' => $websiteEmails,
            'websites' => $websites
        ]);
    }
    
    /**
     * Store a new notification email.
     */
    public function storeNotificationEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
            'type' => 'required|in:master,website_specific',
            'website_id' => 'required_if:type,website_specific|nullable|exists:websites,id',
            'notification_types' => 'nullable|array',
            'notes' => 'nullable|string'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Check if website belongs to the authenticated user
        if ($request->input('type') === 'website_specific' && $request->input('website_id')) {
            $website = Website::find($request->input('website_id'));
            if (!$website || $website->user_id !== Auth::id()) {
                return redirect()->back()
                    ->with('error', 'You do not have permission to add emails to this website.')
                    ->withInput();
            }
        }
        
        // Create the notification email
        $notificationEmail = NotificationEmail::create([
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'type' => $request->input('type'),
            'website_id' => $request->input('type') === 'website_specific' ? $request->input('website_id') : null,
            'is_active' => true,
            'notification_types' => $request->input('notification_types'),
            'notes' => $request->input('notes')
        ]);
        
        return redirect()->route('settings.notification_emails')
            ->with('success', 'Notification email added successfully!');
    }
    
    /**
     * Update a notification email.
     */
    public function updateNotificationEmail(Request $request, NotificationEmail $notificationEmail)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
            'type' => 'required|in:master,website_specific',
            'website_id' => 'required_if:type,website_specific|nullable|exists:websites,id',
            'is_active' => 'nullable|boolean',
            'notification_types' => 'nullable|array',
            'notes' => 'nullable|string'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Check if website belongs to the authenticated user
        if ($request->input('type') === 'website_specific' && $request->input('website_id')) {
            $website = Website::find($request->input('website_id'));
            if (!$website || $website->user_id !== Auth::id()) {
                return redirect()->back()
                    ->with('error', 'You do not have permission to associate emails with this website.')
                    ->withInput();
            }
        }
        
        // Update the notification email
        $notificationEmail->update([
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'type' => $request->input('type'),
            'website_id' => $request->input('type') === 'website_specific' ? $request->input('website_id') : null,
            'is_active' => $request->has('is_active'),
            'notification_types' => $request->input('notification_types'),
            'notes' => $request->input('notes')
        ]);
        
        return redirect()->route('settings.notification_emails')
            ->with('success', 'Notification email updated successfully!');
    }
    
    /**
     * Delete a notification email.
     */
    public function deleteNotificationEmail(NotificationEmail $notificationEmail)
    {
        // Check if website-specific email belongs to the authenticated user
        if ($notificationEmail->type === 'website_specific' && $notificationEmail->website_id) {
            $website = Website::find($notificationEmail->website_id);
            if (!$website || $website->user_id !== Auth::id()) {
                return redirect()->back()
                    ->with('error', 'You do not have permission to delete this notification email.');
            }
        }
        
        $notificationEmail->delete();
        
        return redirect()->route('settings.notification_emails')
            ->with('success', 'Notification email deleted successfully!');
    }
    
    /**
     * Show notification settings page.
     */
    public function pushNotificationSettings()
    {
        return view('settings.push_notifications');
    }
    
    /**
     * Store push notification subscription.
     */
    public function storePushSubscription(Request $request)
    {
        $validated = $request->validate([
            'endpoint' => 'required|string|max:500',
            'keys' => 'required|array',
            'keys.auth' => 'required|string',
            'keys.p256dh' => 'required|string',
        ]);
        
        // Store the subscription details for the current user
        $user = Auth::user();
        $pushSubscription = [
            'endpoint' => $validated['endpoint'],
            'keys' => $validated['keys'],
            'user_id' => $user->id,
            'created_at' => now()->toIso8601String()
        ];
        
        // Store in a 'push_subscriptions' setting as a JSON array
        $subscriptions = Setting::get('push_subscriptions', []);
        $subscriptions[] = $pushSubscription;
        Setting::set('push_subscriptions', $subscriptions, 'notifications', 'Push notification subscriptions');
        
        return response()->json(['success' => true]);
    }
}
