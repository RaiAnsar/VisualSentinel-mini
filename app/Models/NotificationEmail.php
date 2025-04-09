<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationEmail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'name',
        'type',
        'website_id',
        'is_active',
        'notification_types',
        'notes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'notification_types' => 'json',
    ];

    /**
     * Get the website that this email is associated with.
     */
    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    /**
     * Scope a query to only include master emails.
     */
    public function scopeMaster($query)
    {
        return $query->where('type', 'master');
    }

    /**
     * Scope a query to only include website-specific emails.
     */
    public function scopeWebsiteSpecific($query)
    {
        return $query->where('type', 'website_specific');
    }

    /**
     * Scope a query to only include active emails.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if this is a master email.
     */
    public function isMaster(): bool
    {
        return $this->type === 'master';
    }

    /**
     * Get all master emails.
     */
    public static function getMasterEmails()
    {
        return self::master()->active()->get();
    }

    /**
     * Get emails for a specific website.
     */
    public static function getEmailsForWebsite($websiteId)
    {
        $masterEmails = self::master()->active()->get();
        $websiteEmails = self::websiteSpecific()->active()->where('website_id', $websiteId)->get();
        
        return $masterEmails->merge($websiteEmails);
    }
}
