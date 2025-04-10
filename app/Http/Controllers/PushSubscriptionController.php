<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PushSubscriptionController extends Controller
{
    /**
     * Store a new push subscription.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'endpoint' => 'required|string',
            'keys.auth' => 'required|string',
            'keys.p256dh' => 'required|string'
        ]);

        $subscription = PushSubscription::updateOrCreate(
            ['endpoint' => $request->endpoint],
            [
                'user_id' => Auth::id(),
                'public_key' => $request->keys['p256dh'],
                'auth_token' => $request->keys['auth'],
                'content_encoding' => $request->content_encoding ?? '',
                'options' => $request->except(['endpoint', 'keys'])
            ]
        );

        return response()->json(['success' => true]);
    }

    /**
     * Get the VAPID public key.
     *
     * @return \Illuminate\Http\Response
     */
    public function getKey()
    {
        return response()->json([
            'publicKey' => config('webpush.vapid.public_key')
        ]);
    }

    /**
     * Delete a push subscription.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $this->validate($request, [
            'endpoint' => 'required|string',
        ]);

        PushSubscription::where('endpoint', $request->endpoint)->delete();

        return response()->json(['success' => true]);
    }
}
