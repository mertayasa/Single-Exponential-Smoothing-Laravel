<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class OveralForecastExport implements FromView{
    
    
    protected $export_data;

    function __construct($data){
        $this->export_data = $data;
    }

    public function view(): View{
        return view('export.overal',  ['export_data' => $this->export_data]);
    }

}
