<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtColumnToAnswersTable extends Migration
{
    public function up()
    {
        Schema::table('answers', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('answers', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
    }
}
