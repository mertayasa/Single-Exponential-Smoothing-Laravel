<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ForecastExport implements FromView
{
    protected $export_data;

    function __construct($data){
        $this->export_data = $data;
    }

    public function view(): View{
        return view('export.export_excel',  ['export_data' => $this->export_data]);
    }
}
