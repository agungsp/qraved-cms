<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QrCode;
use App\Helpers\StdResponseHelper;

class RestaurantController extends Controller
{
    public function get($unique_code)
    {
        try {
            $restaurant = QrCode::where('code', 'like', '%' . $unique_code . '%')->first()->restaurant;
            return StdResponseHelper::make(true, '', $restaurant);
        } catch (\Exception $e) {
            return StdResponseHelper::make(false, $e->getMessage());
        }
    }
}
