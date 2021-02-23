<?php

namespace App\DataTables;

use App\Models\Post;
use App\Models\User;
use Yajra\DataTables\DataTables;
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
            ->addColumn('more', '<i class="fa fa-plus"> </i>')
            ->addColumn('info_detail', function(User $user){
                return view('users.info-detail', compact('user'));
            })
            ->addColumn('post_url', function(User $user){
                return url("/users/$user->id/posts");
            })
            ->addColumn('post_detail', function(User $user){
                return view('users.posts-detail', ['user' => $user]);
            })
            ->addColumn('action', 'users.action')
            ->rawColumns(['more', 'action']);
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
                        "initComplete" => 'function(){
                            function format(d){ return d.info_detail }

                            let table = this.api();

                            $("#users-table").on("click", "td.details-control", function(){
                               let tr = $(this).closest("tr");
                               let row = table.row(tr); 
                               let tableId = "posts-" + row.data().id;

                               if ( row.child.isShown() ) {
                                   row.child.hide();
                                   tr.removeClass("shown");
                               }

                               else {
                                   row.child(row.data().post_detail).show();
                                   initTable(tableId, row.data().post_url)
                                   tr.addClass("shown");
                               }
                            })

                            function initTable(tableId, posts_detail_url) {
                                $("#" + tableId).DataTable({
                                    processing: true,
                                    serverSide: true,
                                    ajax: posts_detail_url,
                                    columns: [
                                        { data: "id", name: "id" },
                                        { data: "title", name: "title" },
                                    ]
                                })
                            }
                        }'
                    ])
                    ->buttons(
                          Button::make(["extend" => "create", "text" => "Buat baru"]),
                          Button::make(["extend" => "export", "text" => "Download"]),
                          Button::make(["extend" => "print", "text" => "Cetak"]),
                          Button::make(["extend" => "reload", "text" => "Muat ulang"]),
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
            Column::computed('more')->addClass('details-control'),
            Column::computed('action')
                ->width(160)
                ->addClass('text-center'),
            Column::make('id'),
            Column::make('email')->title('Email')->printable(false),
            Column::make('name')->title('Nama Lengkap')->exportable(false),
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
