<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;

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
            'qraved_resto_mapping_id' => 0, // $request->qraved_mapping_id,
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
}
