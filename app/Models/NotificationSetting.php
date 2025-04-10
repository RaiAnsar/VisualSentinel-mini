<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'notify_downtime',
        'notify_visual_changes',
        'notify_ssl',
        'notify_performance',
        'settings',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'notify_downtime' => 'boolean',
        'notify_visual_changes' => 'boolean',
        'notify_ssl' => 'boolean',
        'notify_performance' => 'boolean',
        'settings' => 'array',
    ];

    /**
     * Get the user that owns the notification settings.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
