<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use author;

class person extends Model
{
    use HasFactory;

    protected $table = 'people';
    protected $fillable = [
        'author_id', 
        'first_name',
        'middle_name',
        'last_name',
        'qualifier',
        'title',
        'role',
        'organization',
        'rank'
    ];

    public function author(){
        $this->belongsTo('App\Models\author');
    }
}
