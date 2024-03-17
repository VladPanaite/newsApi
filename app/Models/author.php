<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use news;
use person;

class author extends Model
{
    use HasFactory;

    protected $table = 'authors';
    protected $fillable = ['original', 'organization'];

    public function news(){
       return $this->hasMany('App\Models\news'); 
    }

    public function person(){
        return $this->hasMany('App\Models\person');
    }
}
