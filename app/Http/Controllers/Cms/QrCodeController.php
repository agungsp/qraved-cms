<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QrCode;
use SimpleSoftwareIO\QrCode\Facades\QrCode as GenerateQR;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Helpers\SettingHelper;

class QrCodeController extends Controller
{
    public function index()
    {
        return view('cms.qr-codes');
    }

    public function getQrcodes($lastId)
    {
        $qrcodes = QrCode::where('id', '>', $lastId)->limit(50)->get();
        return [
            'html' => view('includes.get-qrcodes', compact('qrcodes'))->render(),
            'hasNext' => !($qrcodes->count() < 50),
        ];
    }

    public function getQrcode($code)
    {
        return GenerateQR::backgroundColor(255, 255, 255, 0)->generate(base64_decode($code));
    }

    public function store(Request $request)
    {
        $success = true;
        $message = '';
        $request->validate([
            'code' => Rule::requiredIf(!$request->makeAFew),
            'total' => Rule::requiredIf($request->makeAFew),
        ]);
        $total = $request->total ?? 1;
        $qr_length = SettingHelper::getAll()['qr_length'];
        $qr_prefix = SettingHelper::getAll()['qr_prefix'];

        $data = [];
        for ($i = 0; $i < $total; $i++) {
            $data[$i] = [
                'code' => $request->makeAFew ?
                          SettingHelper::qrCodeBuilder($qr_prefix . Str::lower(Str::random($qr_length - strlen($qr_prefix))))
                          : $request->code,
                'updated_by' => auth()->id(),
            ];
        }

        try {
            if (empty($request->id)) {
                foreach ($data as $key => $value) {
                    $value['created_by'] = auth()->id();
                    $qr_code = QrCode::create($value);
                }
                $message = 'New QR Code has been created';
            }
            else {
                $qr_code = QrCode::find($request->id)
                                 ->update($data);
                $message = 'The QR Code has been updated';
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

            $restaurant = QrCode::find($request->id)->delete();
            $message = 'The QR Code has been deleted!';

        } catch (\Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        return [
            'success' => $success,
            'message' => $message,
        ];
    }

    public function qrBuilder($randomCode)
    {
        return SettingHelper::qrCodeBuilder($randomCode);
    }
}
