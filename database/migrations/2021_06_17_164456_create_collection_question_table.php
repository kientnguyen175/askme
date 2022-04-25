<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionQuestionTable extends Migration
{
    public function up()
    {
        Schema::create('collection_question', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collection_id');
            $table->unsignedBigInteger('question_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('collection_question');
    }
}
