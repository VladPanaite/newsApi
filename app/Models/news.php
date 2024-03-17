<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use author;

class news extends Model
{
    use HasFactory;

    protected $table = 'news';
    protected $fillable = [
        'abstract', 
        'web_url',
        'snippet',
        'lead_paragraph',
        'print_section',
        'print_page',
        'source',
        'pub_date',
        'document_type',
        'news_desk',
        'section_name',
        'subsection_name',
        'author_id',
        'type_of_material',
        'identifier',
        'word_count',
        'uri'
    ];

    public function author(){
        return $this->belongsTo('App\Models\author');
    }
}
