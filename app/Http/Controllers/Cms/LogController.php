<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QravedUserLog;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class LogController extends Controller
{
    public function index()
    {
        return view('cms.log');
    }

    public function getLogs(Request $request)
    {
        $filter = $this->parseUrlQuery($request->fullUrl());
        $baseLogs = $this->getRecord($filter['refresh_cache'], $filter);
        $logs = $baseLogs->when(!empty($filter['search']),
                                function ($query) use ($filter) {
                                    return $query->filter(function ($value, $key) use ($filter) {
                                        if ($filter['search_field'] == 'user.email') {
                                            return false !== stripos($value->user->email, $filter['search']);
                                        }
                                        elseif ($filter['search_field'] == 'restaurant.name') {
                                            return false !== stripos($value->restaurant->name, $filter['search']);
                                        }
                                        elseif ($filter['search_field'] == 'action') {
                                            return false !== stripos($value->action, $filter['search']);
                                        }
                                    });
                                })
                         ->when($filter['sort'] == 'asc',
                                function ($query) use ($filter) {
                                    return $query->sortBy($filter['sort_field']);
                                })
                         ->when($filter['sort'] == 'desc',
                                function ($query) use ($filter) {
                                    return $query->sortByDesc($filter['sort_field']);
                                });

        return [
            'html' => view('includes.get-logs', compact('logs'))->render(),
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

    private function getRecord($refresh = false, $filter = [])
    {
        $cacheKey = 'recordOfLogs';
        if ($refresh || !Cache::has($cacheKey)) {
            if (empty($filter)) {
                $baseDateStartFilter = now()->subDays(7)->hour(0)->minute(0)->second(0)->toDateTimeString();
                $baseDateEndFilter = now()->addDay()->hour(0)->minute(0)->second(0)->toDateTimeString();
            }
            else {
                $baseDateStartFilter = Carbon::create($filter['date_start'])->hour(0)->minute(0)->second(0)->toDateTimeString();
                $baseDateEndFilter = Carbon::create($filter['date_end'])->addDay()->hour(0)->minute(0)->second(0)->toDateTimeString();
            }
            $baseLogs = QravedUserLog::where('created_at', '>=', $baseDateStartFilter)
                                     ->where('created_at', '<', $baseDateEndFilter)
                                     ->when(!empty($filter['search']), function ($query) use ($filter) {
                                         return $query->where($filter['search_field'], '%' . $filter['search'] . '%');
                                     })
                                     ->orderBy('created_at', 'desc')
                                     ->get();
            Cache::put($cacheKey, $baseLogs, 3600);
        }
        return Cache::get($cacheKey);
    }
}
