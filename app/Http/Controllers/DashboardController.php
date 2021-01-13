<?php

namespace App\Http\Controllers;

use App\Repositories\ForecastRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller{

    protected $forecastRepository;

    public function __construct(ForecastRepository $forecastRepo){
        $this->forecastRepository = $forecastRepo;
    }

    public function index(){
        $actual_forecast = $this->forecastRepository->groupData();

        $forecast = [];
        $menu = [];
        foreach($actual_forecast as $act_data){
            array_push($forecast, $act_data['forecast']);
            array_push($menu, $act_data['menu']['menu_name']);
        }
        
        $forecasts = array('forecast' => json_encode($forecast), 'menu' => json_encode($menu));

        return view('dashboard.index', compact('forecasts'));
    }
}
