<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeFieldsToUsersTable extends Migration
{
  
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('avatar')->nullable();
            $table->string('website_link')->nullable();
            $table->string('bio')->nullable();
            $table->unsignedBigInteger('points')->default(0);
            $table->string('reset_password_token')->nullable();
            $table->unsignedBigInteger('role_id')->default(1);
            $table->string('username')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'website_link', 'bio', 'points', 'reset_password_token', 'role_id', 'username']);
        });
    }
}
