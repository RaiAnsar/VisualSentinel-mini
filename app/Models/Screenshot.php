<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Screenshot extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'website_id',
        'path',
        'thumbnail_path',
        'width',
        'height',
        'file_size',
        'has_changes',
        'change_percentage',
        'change_details',
        'is_baseline',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'width' => 'integer',
        'height' => 'integer',
        'file_size' => 'integer',
        'has_changes' => 'boolean',
        'change_percentage' => 'float',
        'change_details' => 'json',
        'is_baseline' => 'boolean',
        'metadata' => 'json',
    ];

    /**
     * Get the website that the screenshot belongs to.
     */
    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    /**
     * Get the full URL to the screenshot.
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }

    /**
     * Get the full URL to the thumbnail.
     */
    public function getThumbnailUrlAttribute(): string
    {
        return asset('storage/' . $this->thumbnail_path);
    }

    /**
     * Get the previous screenshot for comparison.
     */
    public function previousScreenshot()
    {
        return Screenshot::where('website_id', $this->website_id)
            ->where('created_at', '<', $this->created_at)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Scope a query to only include screenshots with changes.
     */
    public function scopeWithChanges($query)
    {
        return $query->where('has_changes', true);
    }

    /**
     * Get the formatted file size (KB, MB).
     */
    public function getFormattedFileSizeAttribute(): string
    {
        $size = $this->file_size;
        
        if ($size < 1024) {
            return $size . ' bytes';
        } elseif ($size < 1048576) {
            return round($size / 1024, 2) . ' KB';
        } else {
            return round($size / 1048576, 2) . ' MB';
        }
    }
} 