<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    protected $fillable = [
        'title', 
        'content'
    ];

    use HasFactory;

    public function author(){
        return $this->belongsTo(User::class, 'author_id');
    }

}
