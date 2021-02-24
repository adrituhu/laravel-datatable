<?php

namespace App\Http\Controllers;

use App\DataTables\PostsDataTable;
use Illuminate\Http\Request;

class PostsController extends Controller
{

    public function index(PostsDataTable $dataTable){
        return $dataTable->render('posts.index');
    } 
}
