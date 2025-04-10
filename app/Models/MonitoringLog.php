<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitoringLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'website_id',
        'status_code',
        'response_time',
        'status',
        'error_message',
        'is_cdn_error',
        'details',
        'content_hash',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'response_time' => 'float',
        'is_cdn_error' => 'boolean',
        'details' => 'json',
    ];

    // Status constants
    const STATUS_UP = 'up';
    const STATUS_DOWN = 'down';
    const STATUS_CHANGED = 'changed';
    const STATUS_WARNING = 'warning';

    /**
     * Get the website that the log belongs to.
     */
    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    /**
     * Scope a query to only include logs with a specific status.
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include logs from the last 24 hours.
     */
    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', now()->subDay());
    }

    /**
     * Check if the status is up.
     */
    public function isUp(): bool
    {
        return $this->status === self::STATUS_UP;
    }

    /**
     * Check if the status is down.
     */
    public function isDown(): bool
    {
        return $this->status === self::STATUS_DOWN;
    }

    /**
     * Check if the status has changed.
     */
    public function hasChanged(): bool
    {
        return $this->status === self::STATUS_CHANGED;
    }
} 