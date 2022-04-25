<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtColumnToTagsTable extends Migration
{
    public function up()
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
    }
}
