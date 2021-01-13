<?php

namespace App\Http\Controllers;

use App\DataTables\ForecastDataTable;
use App\DataTables\ForecastDetailDataTable;
use App\Exports\ForecastExport;
use App\Exports\OveralForecastExport;
use App\models\Forecast;
use App\Repositories\ForecastRepository;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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

    public function show($menu_id, ForecastDetailDataTable $forecastDetailDataTable){
        $forecast = $this->forecastRepository->findByMenuId($menu_id)->toArray();
        // dd($forecast);
        if($forecast){
            $menu_name = $forecast[0]['menu']['menu_name'];
            return $forecastDetailDataTable->with('menu_id', $menu_id)->render('forecast.detail', compact('menu_name', 'menu_id'));
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

    public function exportOveral($method){
        $all_data = Forecast::orderBy('id', 'DESC')->get()->groupBy('menu_id')->toArray();
        
        $overal_forecast = [];
        foreach($all_data as $data){
            array_push($overal_forecast, $data[0]);
        }
        $title = 'Rangkuman Peramalan';
        
        if($method == 'excel'){
            return Excel::download(new ForecastExport($overal_forecast), $title.' '.Carbon::now().'.xlsx');
        }else if($method == 'pdf'){
            // $export_data = $overal_forecast;
            // return view('export.export_pdf', compact('export_data'));
            $pdf = PDF::loadview('export.export_pdf',['export_data' => $overal_forecast]);
            return $pdf->download($title.' '.Carbon::now().'.pdf');
        }
    }

    public function exportHistory($method, $menu_id){
        $overal_forecast = Forecast::where('menu_id', $menu_id)->get()->toArray();
        $title = 'Riwayat Peramalan';

        // dd($overal_forecast);
        
        if($method == 'excel'){
            return Excel::download(new ForecastExport($overal_forecast), $title.' '.Carbon::now().'.xlsx');
        }else if($method == 'pdf'){
            // $export_data = $overal_forecast;
            // return view('export.export_pdf', compact('export_data'));
            $pdf = PDF::loadview('export.export_pdf',['export_data' => $overal_forecast]);
            return $pdf->download($title.' '.Carbon::now().'.pdf');
        }
    }
}
