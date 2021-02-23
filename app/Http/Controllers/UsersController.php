<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\Models\Post;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UsersController extends Controller
{

    public function index(UsersDataTable $dataTable){
        return $dataTable->render('users.index');
    }

    public function posts(Request $request, $id){
        $user_id = $id;
        $model = Post::where('author_id', $user_id)->with('author');
        return DataTables::of($model)->toJson();
    }



}
