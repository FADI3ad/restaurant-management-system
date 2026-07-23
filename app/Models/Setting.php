<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting by key with a default value fallback.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember('setting_' . $key, 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting key-value pair.
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('setting_' . $key);
        Cache::forget('all_system_settings');
    }

    /**
     * Get all settings as a associative array.
     */
    public static function getAll(): array
    {
        return Cache::remember('all_system_settings', 3600, function () {
            return static::pluck('value', 'key')->toArray();
        });
    }
}
