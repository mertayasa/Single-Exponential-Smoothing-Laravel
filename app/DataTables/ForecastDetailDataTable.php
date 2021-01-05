<?php

namespace App\DataTables;

use App\Models\Forecast;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ForecastDetailDataTable extends DataTable{

    public function dataTable($query){
        return datatables()
            ->collection($query)
            ->editColumn('month', function($forecast){
                return $forecast['month']['month'].' '.$forecast['year'];
            });
    }

    public function query(Forecast $model){
        $forecast = Forecast::where('product_id', $this->product_id)->get()->toArray();
        return collect($forecast);
        return $model->newQuery();
    }

    public function html(){
        return $this->builder()
                    ->setTableId('forecastdetaildatatable-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->searching(false)
                    ->orderBy(0, 'desc');
    }

    protected function getColumns(){
        $columns = [
            [
                'data' => 'id',
                'visible' => false
            ],
            [
                'data' => 'product.product_name',
                'title' => 'Nama Product',
                'orderable' => false,
                'searchable' => false,
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
        return 'ForecastDetail_' . date('YmdHis');
    }
}
