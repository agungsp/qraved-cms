<?php

namespace App\Helpers;

use App\Models\Setting;


class SettingHelper {

    public static function getAll()
    {
        $settings = Setting::all(['key', 'value']);
        $result = collect();
        foreach ($settings as $setting) {
            $result->put($setting->key, $setting->value);
        }
        return $result;
    }

    public static function get($key)
    {
        return static::getAll()[$key];
    }
}
