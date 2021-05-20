<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QravedUserLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Restaurant;
use App\Exports\DashboardTableExport;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    private $_colors = [
        '255, 99, 132',
        '255, 159, 64',
        '255, 205, 86',
        '75, 192, 192',
        '54, 162, 235',
        '153, 102, 255',
        '201, 203, 207'
    ];

    public function index()
    {
        $restaurants = Restaurant::all();
        $actions = QravedUserLog::groupBy('action')->pluck('action');
        return view('cms.dashboard', compact('restaurants', 'actions'));
    }

    public function getChart(Request $request)
    {
        $filter = $this->parseUrlQuery($request->fullUrl());
        $forChart = QravedUserLog::whereDate('created_at', '>=', $filter['date_start'])
                                 ->whereDate('created_at', '<', Carbon::create($filter['date_end'])->addDay()->toDateString())
                                 ->when($filter['filter_resto'] > 0, function ($query) use ($filter) {
                                     return $query->where('restaurant_id', $filter['filter_resto']);
                                 })
                                 ->groupBy('action')
                                 ->get(['action', DB::raw('count(*) as `count`')]);

        $response['labels'] = $forChart->pluck('action')->toArray();
        $response['datasets'][0]['label'] = 'Total of action';
        $response['datasets'][0]['data'] = $forChart->pluck('count')->toArray();
        for ($i = 0; $i < $forChart->count(); $i++) {
            $response['datasets'][0]['backgroundColor'][$i] = 'rgba('. $this->_colors[$i % count($this->_colors)] .', 0.5)';
            $response['datasets'][0]['borderColor'][$i] = 'rgba('. $this->_colors[$i % count($this->_colors)] .', 1)';
        }
        $response['datasets'][0]['fill'] = true;

        return $response;
    }

    public function getTable(Request $request)
    {
        $filter = $this->parseUrlQuery($request->fullUrl());

        $restaurants = Restaurant::all();
        $html = '';
        foreach ($restaurants as $resto) {
            $count = QravedUserLog::whereDate('created_at', '>=', $filter['date_start'])
                                  ->whereDate('created_at', '<', Carbon::create($filter['date_end'])->addDay()->toDateString())
                                  ->where('restaurant_id', $resto->id)
                                  ->where('action', str_replace('%20', ' ', $filter['filter_action']))
                                  ->count();

            $html .= '<tr>';
            $html .= '    <td>' . $resto->name . '</td>';
            $html .= '    <td>' . $count . '</td>';
            $html .= '</tr>';
        }
        return $html;
    }

    public function exportTable(Request $request)
    {
        $filter = $this->parseUrlQuery($request->fullUrl());
        $data = collect();
        $restaurants = Restaurant::all();
        foreach ($restaurants as $resto) {
            $count = QravedUserLog::whereDate('created_at', '>=', $filter['date_start'])
                                  ->whereDate('created_at', '<', Carbon::create($filter['date_end'])->addDay()->toDateString())
                                  ->where('restaurant_id', $resto->id)
                                  ->where('action', str_replace('%20', ' ', $filter['filter_action']))
                                  ->count();

            $data->push([
                'date_start' => $filter['date_start'],
                'date_end' => $filter['date_end'],
                'restaurant' => $resto->name,
                'count' => $count
            ]);
        }

        return Excel::download(new DashboardTableExport($data), 'dashboard_table_' . now()->format('Ymd_His') . '.csv');
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
