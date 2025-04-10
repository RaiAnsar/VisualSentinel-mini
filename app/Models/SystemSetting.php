<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
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
        'type',
        'display_name',
        'description',
        'options',
        'is_public',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'options' => 'array',
        'is_public' => 'boolean',
    ];

    /**
     * Get a setting value by key
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }
        
        return self::castValue($setting->value, $setting->type);
    }

    /**
     * Set a setting value by key
     * 
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public static function setValue(string $key, $value): bool
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return false;
        }
        
        $setting->value = $value;
        return $setting->save();
    }

    /**
     * Get all settings by group
     * 
     * @param string $group
     * @param bool $publicOnly
     * @return array
     */
    public static function getGroup(string $group, bool $publicOnly = false): array
    {
        $query = self::where('group', $group);
        
        if ($publicOnly) {
            $query->where('is_public', true);
        }
        
        $settings = $query->get();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting->key] = self::castValue($setting->value, $setting->type);
        }
        
        return $result;
    }

    /**
     * Cast value to the correct type
     * 
     * @param mixed $value
     * @param string $type
     * @return mixed
     */
    private static function castValue($value, string $type)
    {
        switch ($type) {
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'json':
                return json_decode($value, true);
            case 'array':
                return explode(',', $value);
            default:
                return $value;
        }
    }
}
