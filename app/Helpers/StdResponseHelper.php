<?php

namespace App\Helpers;

class StdResponseHelper {

    public static function make($success, $message = '', $data = [])
    {
        return compact('success', 'message', 'data');
    }
}
