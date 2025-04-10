<?php

namespace App\Http\Controllers;

use App\Models\NotificationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationSettingController extends Controller
{
    /**
     * Update the user's notification settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'notify_downtime' => 'boolean',
            'notify_visual_changes' => 'boolean',
            'notify_ssl' => 'boolean',
            'notify_performance' => 'boolean',
        ]);

        $user = Auth::user();
        
        // Update or create notification settings
        $settings = NotificationSetting::updateOrCreate(
            ['user_id' => $user->id],
            [
                'notify_downtime' => $request->notify_downtime ?? false,
                'notify_visual_changes' => $request->notify_visual_changes ?? false,
                'notify_ssl' => $request->notify_ssl ?? false,
                'notify_performance' => $request->notify_performance ?? false,
            ]
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification settings updated successfully.',
                'settings' => $settings
            ]);
        }

        return redirect()->back()->with('success', 'Notification settings updated successfully.');
    }

    /**
     * Get the current user's notification settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function current()
    {
        $user = Auth::user();
        
        $settings = NotificationSetting::firstOrCreate(
            ['user_id' => $user->id],
            [
                'notify_downtime' => true,
                'notify_visual_changes' => true,
                'notify_ssl' => true,
                'notify_performance' => true,
            ]
        );

        return response()->json($settings);
    }
}
