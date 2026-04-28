<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('concerts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->dateTime('concert_start');
            $table->dateTime('concert_end');
            $table->foreignId('venue_id');
            $table->foreign('venue_id')->references('id')->on('venues');
            $table->string('link_poster');
            $table->string('link_venue');
            $table->timestamps();
        });
        DB::statement('ALTER TABLE concerts MODIFY link_poster TEXT');
        DB::statement('ALTER TABLE concerts MODIFY link_venue TEXT');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concerts');
    }
};
