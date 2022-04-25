<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowsTable extends Migration
{
    public function up()
    {
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('followable_id');
            $table->string('followable_type');
            $table->unsignedBigInteger('model_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('follows');
    }
}
