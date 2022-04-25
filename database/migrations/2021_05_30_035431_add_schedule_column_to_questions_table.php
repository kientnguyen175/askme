<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddScheduleColumnToQuestionsTable extends Migration
{
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->timestamp('schedule_time')->nullable();
            $table->tinyInteger('status')->default(1); // 1 - mac dinh, 0 - pending
        });
    }

    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['schedule_time', 'status']);
        });
    }
}
