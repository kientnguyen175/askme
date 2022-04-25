<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionTagTable extends Migration
{
    public function up()
    {
        Schema::create('question_tag', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('tag_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('question_tag');
    }
}
