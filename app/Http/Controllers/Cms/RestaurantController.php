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

    public function getRestaurants($lastId, Request $request)
    {
        $filter = $this->parseUrlQuery($request->fullUrl());
        $restaurants = Restaurant::where('id', '>', $lastId)
                                 ->when($filter->count() > 0, function ($query) use ($filter) {
                                     return $query->where('name', 'like', '%'. $filter['search'] .'%');
                                 })
                                 ->limit(10)->get();
        $hasNext = Restaurant::when($filter->count() > 0, function ($query) use ($filter) {
                                 return $query->where('name', 'like', '%'. $filter['search'] .'%');
                             })
                             ->orderBy('id', 'desc')->first()->id ?? 0;
        return [
            'html' => view('includes.get-restaurants', compact('restaurants'))->render(),
            'lastId' => $restaurants->last()->id ?? 0,
            'hasNext' => $hasNext > ($restaurants->last()->id ?? 0),
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
            'name' => ['required', 'max:255'],
            'address' => ['required', 'min:5'],
        ]);

        $data = [
            'qraved_resto_mapping_id' => $request->qraved_mapping_id ?? 0,
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
        $footer = 'By ' . substr(auth()->user()->name, 0, 10) . ' at ' . now()->toDateTimeString() . ' | paper size : A6';
        $resto = Restaurant::find($restaurant_id);
            return SnappyPdf::loadView('exports.qr_code', compact('resto'))
                            ->setPaper('a6')
                            ->setOption('margin-top', 5)
                            ->setOption('margin-bottom', 5)
                            ->setOption('margin-left', 5)
                            ->setOption('margin-right', 5)
                            ->setOption('footer-html', $footer)
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

    private function parseUrlQuery($url)
    {
        $query = explode('&', parse_url($url, PHP_URL_QUERY));
        $result = collect();
        foreach ($query as $item) {
            if (!empty($item)) {
                $data = explode('=', $item);
                $result->put($data[0], $data[1]);
            }
        }
        return $result;
    }
}
