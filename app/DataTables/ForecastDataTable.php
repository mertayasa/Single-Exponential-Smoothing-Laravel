<?php

namespace App\DataTables;

use App\Models\Forecast;
use Yajra\DataTables\Services\DataTable;

class ForecastDataTable extends DataTable{

    public function dataTable($query){
        return datatables()
            ->eloquent($query)
            ->addColumn('action', 'product.datatables_action');
    }

    public function query(Forecast $model){
        return $model->newQuery()->with('product')->with('month');
    }

    public function html(){
        return $this->builder()
                    ->setTableId('forecastdatatable-table')
                    ->columns($this->getColumns())
                    ->addAction(['title' => 'Action', 'width' => '150px', 'printable' => false, 'responsivePriority' => '100', 'id' => 'actionColumn'])
                    ->minifiedAjax()
                    ->orderBy(1);
    }

    protected function getColumns(){
        $columns = [
            [
                'data' => 'id',
                'visible' => false
            ],
            [
                'data' => 'product.product_name',
                'title' => 'Nama Product'
            ],
            [
                'data' => 'month.month',
                'title' => 'Bulan'
            ],
            [
                'data' => 'alpha',
                'title' => 'Alpha'
            ],
            [
                'data' => 'forecast',
                'title' => 'Peramalan'
            ],
            [
                'data' => 'year',
                'title' => 'Tahun'
            ],
        ];

        return $columns;
    }

    protected function filename(){
        return 'Forecast_' . date('YmdHis');
    }
}
