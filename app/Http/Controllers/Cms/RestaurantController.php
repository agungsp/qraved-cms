<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Helpers\SettingHelper;
use App\Models\QrCode;
use SimpleSoftwareIO\QrCode\Facades\QrCode as GenerateQR;
use Barryvdh\Snappy\Facades\SnappyPdf;
use App\Helpers\StdResponseHelper;

class RestaurantController extends Controller
{
    public function index()
    {
        return view('cms.restaurants');
    }

    public function getRestaurants($lastId)
    {
        $restaurants = Restaurant::where('id', '>', $lastId)->limit(50)->get();
        return [
            'html' => view('includes.get-restaurants', compact('restaurants'))->render(),
            'hasNext' => !($restaurants->count() < 50),
        ];
    }

    public function getRestaurant($id)
    {
        return Restaurant::find($id);
    }

    public function store(Request $request)
    {
        $success = true;
        $message = '';

        $request->validate([
            'name' => ['required'],
            'address' => ['required', 'min:5'],
        ]);

        $data = [
            'qraved_resto_mapping_id' => $request->qraved_mapping_id,
            'name' => $request->name,
            'alias' => $request->alias,
            'address' => $request->address,
            'contact' => $request->contact,
            'updated_by' => auth()->id(),
        ];

        try {
            if (!empty($request->id)) {
                $restaurant = Restaurant::find($request->id)
                                        ->update($data);
                $message = 'The restaurant has been updated';
            }
            else {
                $data['created_by'] = auth()->id();
                $restaurant = Restaurant::create($data);
                $message = 'New restaurant has been created';
            }

        } catch (\Exception $e) {

            $success = false;
            $message = $e->getMessage();

        }

        return [
            'success' => $success,
            'message' => $message,
        ];
    }

    public function delete(Request $request)
    {
        $success = true;
        $message = '';
        try {

            $restaurant = Restaurant::find($request->id)->delete();
            $message = 'The restaurant has been deleted!';

        } catch (\Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        return [
            'success' => $success,
            'message' => $message,
        ];
    }

    public function qrConnect(Request $request)
    {
        $success = true;
        $message = '';

        $request->validate([
            'linkCode' => ['required']
        ], [
            'linkCode.required' => 'QR Code field is required!'
        ]);

        $data = [
            'qr_id' => $request->linkCode,
            'updated_by' => auth()->id(),
        ];

        try {
            Restaurant::find($request->linkRestoId)
                      ->update($data);
            $message = 'The QR Code has been connected.';

        } catch (\Exception $e) {

            $success = false;
            $message = $e->getMessage();

        }

        return [
            'success' => $success,
            'message' => $message,
        ];
    }

    public function qrCodePreview($restaurant_id)
    {
        $resto = Restaurant::find($restaurant_id);
        return view('exports.qr_code', compact('resto'))->render();
    }

    public function availableQr()
    {
        return QrCode::available()->get(['id', 'code as text'])->toArray();
    }

    public function export($restaurant_id)
    {
        $resto = Restaurant::find($restaurant_id);
            return SnappyPdf::loadView('exports.qr_code', compact('resto'))
                  ->setPaper('a5')
                  ->download($resto->name.'.pdf');

    }

    public function getRestoByQr(Request $request)
    {
        $url = env('APP_ENV') !== 'local' ? str_replace('http', 'https', $request->fullUrl()) : $request->fullUrl();
        try {
            $restaurant = QrCode::where('code', $url)->first()->restaurant->id;
            return StdResponseHelper::make(true, '', compact('restaurant'));
        } catch (\Exception $e) {
            return StdResponseHelper::make(false, $e->getMessage());
        }
    }

    public function resto()
    {
        return view('cms.resto');
    }
}
