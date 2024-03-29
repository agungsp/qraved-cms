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

    public function getQrcodes($lastId, Request $request)
    {
        $filter = $this->parseUrlQuery($request->fullUrl());
        $qrcodes = QrCode::where('id', '>', $lastId)
                         ->when($filter->count() > 0, function ($query) use ($filter) {
                             return $query->where('code', 'like', '%'. $filter['search'] .'%');
                         })
                         ->limit(10)->get();
        $hasNext = QrCode::when($filter->count() > 0, function ($query) use ($filter) {
                             return $query->where('code', 'like', '%'. $filter['search'] .'%');
                         })
                         ->orderBy('id', 'desc')->first()->id ?? 0;
        return [
            'html' => view('includes.get-qrcodes', compact('qrcodes'))->render(),
            'lastId' => $qrcodes->last()->id ?? 0,
            'hasNext' => $hasNext > ($qrcodes->last()->id ?? 0),
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
        $qr_length = Str::length(SettingHelper::getAll()['qr_prefix']) + 20; // SettingHelper::getAll()['qr_length'];
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
                foreach ($data as $row) {
                    $qr_code = QrCode::find($request->id)
                                     ->update($row);
                    $message = 'The QR Code has been updated';
                }
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
        return SettingHelper::qrCodeBuilder(base64_decode($randomCode));
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
