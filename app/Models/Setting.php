<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'group',
        'is_json',
        'description'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_json' => 'boolean',
    ];

    /**
     * Get a setting by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }
        
        $value = $setting->value;
        
        if ($setting->is_json) {
            return json_decode($value, true);
        }
        
        return $value;
    }

    /**
     * Set a setting value
     *
     * @param string $key
     * @param mixed $value
     * @param string $group
     * @param string|null $description
     * @return \App\Models\Setting
     */
    public static function set(string $key, $value, string $group = 'general', ?string $description = null)
    {
        $isJson = is_array($value) || is_object($value);
        
        if ($isJson) {
            $value = json_encode($value);
        }
        
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'is_json' => $isJson,
                'description' => $description ?? $key,
            ]
        );
        
        return $setting;
    }

    /**
     * Get all settings for a group
     *
     * @param string $group
     * @return array
     */
    public static function getGroup(string $group = 'general')
    {
        $settings = self::where('group', $group)->get();
        $result = [];
        
        foreach ($settings as $setting) {
            $value = $setting->value;
            
            if ($setting->is_json) {
                $value = json_decode($value, true);
            }
            
            $result[$setting->key] = $value;
        }
        
        return $result;
    }

    /**
     * Get specific SMTP settings
     *
     * @return array
     */
    public static function getSmtpSettings()
    {
        return self::getGroup('smtp');
    }
}
