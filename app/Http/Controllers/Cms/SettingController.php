<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode as GenerateQR;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use App\Helpers\SettingHelper;

class SettingController extends Controller
{
    public function index()
    {
        $settings = SettingHelper::getAll();
        // dd($settings);
        return view('cms.settings', compact('settings'));
    }

    public function getQrCode($code_base64)
    {
        return GenerateQR::backgroundColor(255, 255, 255, 0)->size(150)->generate(base64_decode($code_base64));
    }

    public function store(Request $request)
    {
        $success = true;
        $message = '';
        $request->validate([
            'url' => ['required'],
            // 'qr_length' => ['required', 'numeric', 'min:10', 'max:255'],
        ]);

        try {
            DB::transaction(function () use ($request) {
                Setting::updateOrCreate(
                    ['key' => 'qr_url'],
                    ['value' => $request->url]
                );
                // Setting::updateOrCreate(
                //     ['key' => 'qr_length'],
                //     ['value' => $request->qr_length]
                // );
                Setting::updateOrCreate(
                    ['key' => 'qr_prefix'],
                    ['value' => $request->qr_prefix]
                );
            });
            $message = 'Settings has been updated';
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
