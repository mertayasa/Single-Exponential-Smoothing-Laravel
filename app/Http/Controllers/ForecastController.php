<?php

namespace App\Http\Controllers;

use App\DataTables\ForecastDataTable;
use App\models\Forecast;
use Illuminate\Http\Request;

class ForecastController extends Controller{

    public function index(ForecastDataTable $forecastDataTable){
        $data = Forecast::orderBy('id', 'DESC')->get()->groupBy('product_id')->toArray();

        dd($data);
        // return $forecastDataTable->render('forecast.index');
    }

    public function create(){
        //
    }

    public function store(Request $request){
        //
    }

    public function show(Forecast $forecast){
        //
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
