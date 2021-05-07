<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Str;


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

    public static function qrCodeBuilder($qrCodeRandomString)
    {
        $setting = static::getAll();
        $url = isset($setting['qr_url']) ? Str::replaceLast('/', '', $setting['qr_url']) . '/' : '';
        return $url . $qrCodeRandomString;
    }
}
