<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QravedUserLog;

class LogController extends Controller
{
    public function index()
    {
        return view('cms.log');
    }

    public function getLogs($lastId, Request $request)
    {
        $filter = $this->parseUrlQuery($request->fullUrl());
        // STOP HERE
        // AT IMPLEMENT FILTER INTO ELOQUENT


        $logs = QravedUserLog::where('id', '>', $lastId)
                             ->limit(10)
                             ->get();
        $hasNext = QravedUserLog::orderBy('id', 'desc')->first()->id;
        return [
            'html' => view('includes.get-logs', compact('logs'))->render(),
            'lastId' => $logs->last()->id,
            'hasNext' => $hasNext > $logs->last()->id,
        ];
    }

    private function parseUrlQuery($url)
    {
        $query = explode('&', parse_url($url, PHP_URL_QUERY));
        $result = collect();
        foreach ($query as $item) {
            $data = explode('=', $item);
            $result->put($data[0], $data[1]);
        }
        return $result;
    }
}
