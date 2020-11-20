<?php

namespace App\DataTables;

use App\Models\ActualData;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ActualDataDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('selection', function($actual_data){
                return view('actual_data.datatables_check', compact('actual_data'));
            })
            ->addColumn('action', 'product.datatables_action');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ActualDataDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ActualData $model)
    {
        return $model->newQuery()->with('month');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('actualdatadatatable-table')
                    ->columns($this->getColumns())
                    ->addAction(['title' => 'Action', 'width' => '150px', 'printable' => false, 'responsivePriority' => '100', 'id' => 'actionColumn'])
                    ->minifiedAjax()
                    ->orderBy(5);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
            [
                'data'  => 'selection',
                'name'  => '#',
                'title'  => "<input type='checkbox' name='check_all' id='check_all'>",
                'width' => '3%',
                'orderable' => false,
                'exportable' => false,
                'printable' => false,
                'searchable' => false
            ],
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
                'data' => 'actual',
                'title' => 'Data Aktual'
            ],
            [
                'data' => 'created_at',
                'visible' => false
            ],
        ];

        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'ActualData_' . date('YmdHis');
    }
}
