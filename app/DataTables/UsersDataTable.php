<?php

namespace App\DataTables;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;


class UsersDataTable extends DataTable
{

    protected $actions = ['print', 'export', 'csv', 'excel', 'hapusUsers'];

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query, Request $request)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function(User $user){
                return view('users.actions', compact('user'));
            })
            ->addColumn('posts', function(User $user){
                return $user->posts->map(function($post){
                    return \Str::limit($post->title, 30, '...');
                })->implode('<br>');
            })
            ->editColumn('created_at', function(User $user){
                return $user->created_at->format('d/m/Y');
            })
            ->editColumn('updated_at', function(User $user){
                return $user->updated_at->format('d/m/Y');
            })
            ->filter(function($query) use($request) {

                if($request->has('operator') && $request->has('jumlah_post')){
                    $operator = $request->get('operator');
                    $jumlah = $request->get('jumlah_post');
                    $query->withCount('posts')->having('posts_count', $operator, $jumlah);
                }

                if($request->has('email')){
                    $email = $request->get('email');
                    $query->where('email', 'LIKE', "%$email%");
                }

                return $query;

            }, true)
            ->rawColumns(['action', 'posts']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
       return $model->with('posts')->select('users.*')->newQuery();
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
                    ->dom('Brftip')
                    ->orderBy(2, 'desc')
                    ->addCheckbox(["class" => "selection", "title" => ""], true)
                    ->parameters([
                        "initComplete" => $this->initComplete(),
                        "drawCallback" => $this->drawCallback()
                    ])
                    ->buttons(
                          Button::make(["extend" => "create", "text" => "Buat baru"]),
                          Button::make(["extend" => "export", "text" => "Download"]),
                          Button::make(["extend" => "print", "text" => "Cetak"]),
                          Button::make(["extend" => "reload", "text" => "Muat ulang"]),
                          Button::make(["text" => "Hapus"])->action($this->hapusActionCallback())
                    );
    }

    public function hapusActionCallback(){
        return 'function(e, dt, node, config){
          var _buildUrl = function(dt, action) {
                var url = dt.ajax.url() || "";
                var params = dt.ajax.params();
                params.action = action;

                if (url.indexOf("?") > -1) {
                    return url + "&" + $.param(params);
                }
                
                return url + "?" + $.param(params);
            };


            let url = _buildUrl(dt, "hapusUsers");
            window.location = url + "&selected=" + window.selected;

        }';
    }



    public function initComplete(){
        return 'function(){
            let data = this.api().data();
            window.selected = [];

            $("#users-table tbody").on("click", "input.selection", function(){
                let tr = $(this).closest("tr")[0];
                let row = data[tr.sectionRowIndex];
                let checked = $(this).is(":checked");

                if(checked) return selected.push(row.id);
                selected.filter(id => id !== row.id)
            })
          }
        ';
    }

    public function drawCallback(){
        return 'function(){

            let data = this.api().data();
            let selected = window.selected || [];

            $("input.selection").each(function(){
                let tr = $(this).closest("tr")[0];
                let row = data[tr.sectionRowIndex];
            
                if(selected.indexOf(row.id) > -1){
                  $(this).attr("checked", true);
                }
            })
            
        }';
    }

    public function hapusUsers(){
        $selectedIds = $this->request()->get('selected');
        $selectedIds = explode(',', $selectedIds);

        User::whereIn('id', $selectedIds)->delete();
        return redirect()->back();
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
            Column::make('id', 'users.id'),
            Column::make('email', 'users.email')->title('Email')->printable(false),
            Column::make('name', 'users.name')->title('Nama Lengkap')->exportable(false),
            Column::make('posts','posts.title'),
            Column::make('created_at', 'users.created_at')->searchable(false),
            Column::make('updated_at', 'users.updated_at')->searchable(false),
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
