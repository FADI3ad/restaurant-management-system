<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    /**
     * Get or set settings helper function.
     */
    function setting($key = null, $default = null)
    {
        if (is_null($key)) {
            return Setting::getAll();
        }

        if (is_array($key)) {
            foreach ($key as $k => $v) {
                Setting::set($k, $v);
            }
            return true;
        }

        try {
            return Setting::get($key, $default);
        } catch (\Throwable $e) {
            return $default;
        }
    }
}
