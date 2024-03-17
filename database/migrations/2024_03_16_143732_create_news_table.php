<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->text('abstract');
            $table->string('web_url');
            $table->text('snippet');
            $table->text('lead_paragraph')->nullable();
            $table->string('print_section')->nullable();
            $table->string('print_page')->nullable();
            $table->string('source');
            $table->dateTime('pub_date');
            $table->string('document_type');
            $table->string('news_desk');
            $table->string('section_name');
            $table->string('subsection_name')->nullable();
            $table->unsignedBigInteger('author_id');
            $table->string('type_of_material');
            $table->string('identifier');
            $table->integer('word_count');
            $table->string('uri');
            $table->foreign('author_id')->references('id')->on('authors');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
