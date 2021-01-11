<?php

namespace App\DataTables;

use App\Models\Forecast;
use Yajra\DataTables\Services\DataTable;

class ForecastDataTable extends DataTable{

    public function dataTable($query){
        return datatables()
            ->collection($query)
            ->editColumn('month', function($forecast){
                return $forecast['month']['month'].' '.$forecast['year'];
            })
            ->addColumn('action', function($forecast){
                $menu_id = $forecast['menu_id'];
                return view('forecast.datatables_action', compact('menu_id'));
            });
            // ->addColumn('action', 'forecast.datatables_action');
    }

    public function query(Forecast $model){
        $all_data = Forecast::orderBy('id', 'DESC')->get()->groupBy('menu_id')->toArray();

        $temp = [];
        foreach($all_data as $data){
            array_push($temp, $data[0]);
        }

        return collect($temp);
    }

    public function html(){
        return $this->builder()
                    ->setTableId('forecastdatatable-table')
                    ->columns($this->getColumns())
                    ->addAction(['title' => 'Action', 'width' => '150px', 'printable' => false, 'responsivePriority' => '100', 'id' => 'actionColumn'])
                    ->minifiedAjax()
                    ->searching(false)
                    ->orderBy(1);
    }

    protected function getColumns(){
        $columns = [
            [
                'data' => 'id',
                'visible' => false
            ],
            [
                'data' => 'menu.menu_name',
                'title' => 'Nama Menu',
                'orderable' => false,
            ],
            [
                'data' => 'month',
                'title' => 'Bulan',
                'orderable' => false,
                'searchable' => false,
            ],
            // [
            //     'data' => 'alpha',
            //     'title' => 'Alpha'
            // ],
            [
                'data' => 'forecast',
                'title' => 'Peramalan'
            ],
            // [
            //     'data' => 'year',
            //     'title' => 'Tahun'
            // ],
        ];

        return $columns;
    }

    protected function filename(){
        return 'Forecast_' . date('YmdHis');
    }
}
