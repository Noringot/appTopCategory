<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use PHPUnit\Exception;

class ApplicationController extends Controller
{
    private Fetcher $fetcher;

    public function __construct()
    {
        $this->fetcher = new Fetcher();
    }

    public function getPosition(Request $request) {
        if(!$request->has('date')) {
            return response('date must be specified')->setStatusCode(404);
        }

        $url = 'https://api.apptica.com/package/top_history/1421444/1';
        $date_from = $request->query('date');
        $date_to = date('Y-m-d', strtotime($date_from) + 86400);
        $params = [
            'date_from' => $date_from,
            'date_to' => $date_to,
            'B4NKGg' => 'fVN5Q9KVOlOHDx9mOsKPAQsFBlEhBOwguLkNEDTZvKzJzT3l'
        ];

        $response = $this->fetcher->get($url, $params);

        if ($response['status_code'] !== 200) {
            return $response;
        }

        $positionData = [];

        foreach ($response['data'] as $categoryId => $category) {
            $positionsInTop = [];
            foreach ($category as $subCategory) {
                $positionsInTop = array_merge($positionsInTop, array_values($subCategory));
            }
            $min = min(array_filter($positionsInTop));
            $positionData[$categoryId] = $min;
        }

        $this->store($date_from, $positionData);

        return response([
            'status_code' => 200,
            'message' => 'ok',
            'data' => $positionData
        ])->setStatusCode(200);
    }

    public function index()
    {
        try {
            $apps = Application::all()->map(function ($app) {
                return [
                    'date' => $app->date,
                    'position_info' => json_decode($app->position_info)
                ];
            })->all();
            dd($apps);

        } catch (\Exception $e) {
            return response($e)->setStatusCode(500);
        }
    }


    public function store($date, $positionInfo)
    {
        $app = Application::where('date', $date)->first();
        if (!empty($app)) {
            $this->update($date, $positionInfo);
            return 1;
        }

        $data = [
            'date' => $date,
            'position_info' => json_encode($positionInfo),
        ];

        $app = new Application($data);

        try {
            $app->save();
            return response('success store')->setStatusCode(200);
        } catch (\Exception $e) {
            return response($e)->setStatusCode(500);
        }
    }

    public function update($date, $positionInfo)
    {
        try {
            $app = Application::where('date', $date)->first();
            $app->update([
               'position_info' => json_encode($positionInfo)
            ]);
            return response('success update')->setStatusCode(200);
        } catch (\Exception $e) {
            return response($e)->setStatusCode(500);
        }
    }

    public function destroy(Application $application)
    {

    }
}
