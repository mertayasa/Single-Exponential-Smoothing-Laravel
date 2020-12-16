<?php

namespace App\Http\Controllers;

use App\DataTables\ForecastDataTable;
use App\DataTables\ForecastDetailDataTable;
use App\models\Forecast;
use App\Repositories\ForecastRepository;
use Illuminate\Http\Request;

class ForecastController extends Controller{

    protected $forecastRepository;

    public function __construct(ForecastRepository $forecastRepo){
        $this->forecastRepository = $forecastRepo;
    }

    public function index(ForecastDataTable $forecastDataTable){
        // dd($temp);
        return $forecastDataTable->render('forecast.index');
    }

    public function create(){
        //
    }

    public function store(Request $request){
        //
    }

    public function show($product_id, ForecastDetailDataTable $forecastDetailDataTable){
        $forecast = $this->forecastRepository->findByProductId($product_id)->toArray();
        // dd($forecast);
        if($forecast){
            $product_name = $forecast[0]['product']['product_name'];
            return $forecastDetailDataTable->with('product_id', $product_id)->render('forecast.detail', compact('product_name'));
        }else{
            abort(404);
        }
    }

    public function edit(Forecast $forecast){
        //
    }

    public function update(Request $request, Forecast $forecast){
        //
    }

    public function destroy(Forecast $forecast){
        //
    }
}
