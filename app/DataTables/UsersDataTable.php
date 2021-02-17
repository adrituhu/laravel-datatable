<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
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
            ->addColumn('action', 'users.action')
            ->addColumn('test', 'hi {{$name}}')
            ->editColumn('name', 'Nama adalah {{$name}}');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('users-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(2, 'desc')
                    ->parameters([
                        'initComplete' => 'function(){
                            this.api().columns().every(function (index) {
                                let column = this;
                                let input = document.createElement("input");

                                let isSearchable = this.settings()[0].aoColumns[index].bSearchable; 
                                if(!isSearchable) return;

                                $(input)
                                .appendTo($(column.footer()).empty())
                                .on(\'change\', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
                        }'
                    ])
                    ->buttons(
                        Button::make('create'),
                        Button::make('export'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('action')
                  ->width(160)
                  ->addClass('text-center'),
            Column::make('id'),
            Column::make('email')->title('Email'),
            Column::make('name')->title('Nama Lengkap'),
            Column::make('nonexistent')->data('email')->searchable(false),
            Column::make('created_at')->searchable(false),
            Column::make('updated_at')->searchable(false),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Users_' . date('YmdHis');
    }
}
