<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('username');
            $table->string('email')->unique();
            $table->string('password');
            $table->date('birth_date');
            $table->boolean('is_admin')->default(false);
            $table->string('forgot_password_token')->nullable();
            $table->time('forgot_password_expired')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
