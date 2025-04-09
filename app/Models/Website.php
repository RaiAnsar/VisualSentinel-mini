<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Website extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'url',
        'check_interval',
        'is_active',
        'last_status',
        'last_status_code',
        'last_response_time',
        'last_checked_at',
        'last_baseline_at',
        'baseline_screenshot_id',
        'monitoring_options',
        'notification_settings',
        'metadata',
        'ip_override',
        'use_ip_override',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'check_interval' => 'integer',
        'is_active' => 'boolean',
        'last_checked_at' => 'datetime',
        'last_baseline_at' => 'datetime',
        'monitoring_options' => 'json',
        'notification_settings' => 'json',
        'metadata' => 'json',
        'use_ip_override' => 'boolean',
    ];

    /**
     * Get the user that owns the website.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the monitoring logs for the website.
     */
    public function monitoringLogs(): HasMany
    {
        return $this->hasMany(MonitoringLog::class);
    }

    /**
     * Get the screenshots for the website.
     */
    public function screenshots(): HasMany
    {
        return $this->hasMany(Screenshot::class);
    }

    /**
     * Get the tags for the website.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Get the recent logs for the website.
     */
    public function recentLogs()
    {
        return $this->monitoringLogs()->orderBy('created_at', 'desc')->limit(10);
    }

    /**
     * Scope a query to only include active websites.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the latest screenshot for the website.
     */
    public function latestScreenshot()
    {
        return $this->screenshots()->latest()->first();
    }

    /**
     * Get the notification emails for the website.
     */
    public function notificationEmails(): HasMany
    {
        return $this->hasMany(NotificationEmail::class);
    }
    
    /**
     * Get all notification recipients for this website.
     */
    public function getNotificationRecipients()
    {
        return NotificationEmail::getEmailsForWebsite($this->id);
    }
} 