<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\news;
use App\Models\news as newsObject;
use App\Models\author;
use App\Models\person;
use DateTime;

class NewsController extends Controller
{

    public function store($records){
        foreach($records as $record){
            $author = new author;
            $author->original = isset($record['byline']['original']) ? $record['byline']['original'] : "";
            $author->organization = isset($record['byline']['organization']) ? $record['byline']['organization'] : null;
            $author->save();
            $author_id = $author->id;
            
            if (isset($record['byline']['person']) and is_array($record['byline']['person'])){
                foreach($record['byline']['person'] as $b_person){
                    $person = new person;
                    $person->author_id = $author_id;
                    $person->first_name = $b_person['firstname'];
                    $person->middle_name = $b_person['middlename'];
                    $person->last_name = isset($b_person['lastname']) ? $b_person['lastname'] : null;
                    $person->qualifier = $b_person['qualifier'];
                    $person->title = $b_person['title'];
                    $person->role = $b_person['role'];
                    $person->organization = $b_person['organization'];
                    $person->rank = $b_person['rank'];
                    $person->save();
                }
            }

            $date = new DateTime($record['pub_date']);
            $pub_date = $date->format('Y-m-d H:i:s');
            $news = new newsObject;
            $news->abstract = $record['abstract'];
            $news->web_url = $record['web_url'];
            $news->snippet = $record['snippet'];
            $news->lead_paragraph = isset($record['lead_paragraph']) ? $record['lead_paragraph'] : null;
            $news->print_section = isset($record['print_section']) ? $record['print_section'] : null;
            $news->print_page = isset($record['print_page']) ? $record['print_page'] : null;
            $news->source = $record['source'];
            $news->pub_date = $pub_date;
            $news->document_type = $record['document_type'];
            $news->news_desk = $record['news_desk'];
            $news->section_name = $record['section_name'];
            $news->subsection_name = isset($record['subsection_name']) ? $record['subsection_name'] : null;
            $news->author_id = $author_id;
            $news->type_of_material = $record['type_of_material'];
            $news->identifier = $record['_id'];
            $news->word_count = $record['word_count'];
            $news->uri = $record['uri'];
            $news->save();


        }
    }

    public function loadNewsFromJson(){
        $file = Storage::exists('public/news_1.json');

        if ($file) {
            $records = Storage::json('public/news_1.json');
            $this->store($records);
            return true;
        } else {
            $records = false;
        }
    }

    public function mapResponse($news){
        $count = $news->count();
        
        if ($count > 0){
            foreach($news as $news_item){
                $current_item = [];
                $news_data = $news_item->toArray();
                # $author_data = $news_item->author->toArray();
                $person_data = $news_item->author->person->toArray();
                $current_item['title'] = $news_data['abstract'];
                $current_item['short'] = $news_data['lead_paragraph'];
                $current_item['source'] = $news_data['source'];
                $current_item['category'] = $news_data['section_name'];
                $current_item['subCategory'] = $news_data['subsection_name'];
                if(isset($person_data[0])){
                    $current_item['author'] = 
                        $person_data[0]['first_name'] . " " . 
                        $person_data[0]['middle_name'] . " " . 
                        $person_data[0]['last_name'];
                }else{
                    $current_item['author'] = "Undefined";
                }
                $current_item['link'] = $news_data['web_url'];
                $response[] = $current_item;
            }
        }

        $final_response['totalResults'] = $count;
        $final_response['news'] = $response;


        return $final_response;
    }

    public function getNews($date){
        $file_exists = $this->loadNewsFromJson();

        $search_date = new DateTime($date);
        $query_date = $search_date->format('Y-m-d');

        $news = newsObject::where('pub_date', 'LIKE', "%{$query_date}%")->with('author.person')->get();

        $response = $this->mapResponse($news);

        return response()->json($response);
    }
}
