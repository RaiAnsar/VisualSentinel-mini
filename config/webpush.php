<?php

return [
    /*
    |--------------------------------------------------------------------------
    | VAPID Keys
    |--------------------------------------------------------------------------
    |
    | Public and private keys for VAPID protocol. These keys must be generated
    | and provided. You can use `php artisan webpush:generate` command to
    | generate a key pair, or you can use https://web-push-codelab.glitch.me/
    | to generate one.
    |
    */
    'vapid' => [
        'public_key' => env('VAPID_PUBLIC_KEY'),
        'private_key' => env('VAPID_PRIVATE_KEY'),
        'subject' => env('VAPID_SUBJECT', 'mailto:webmaster@visual-sentinel.com'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Google Cloud Messaging
    |--------------------------------------------------------------------------
    |
    | Used for legacy browsers that don't support the VAPID protocol yet.
    | Most modern browsers support VAPID, so this field can be null.
    |
    */
    'gcm' => [
        'key' => env('GCM_KEY'),
        'sender_id' => env('GCM_SENDER_ID'),
    ],

    /*
    |--------------------------------------------------------------------------
    | TTL (Time To Live)
    |--------------------------------------------------------------------------
    |
    | The default time-to-live of a notification. This is used to tell
    | the push service how long a push message should be retained if the
    | user is not online.
    |
    */
    'ttl' => env('WEBPUSH_TTL', 2419200),

    /*
    |--------------------------------------------------------------------------
    | Automatic Push Subscription Update
    |--------------------------------------------------------------------------
    |
    | When a push subscription is invalid, the package will delete or update it.
    | This setting controls whether to automatically delete or update it.
    |
    */
    'automatic_push_subscription_update' => env('WEBPUSH_AUTOMATIC_UPDATE', true),
]; 