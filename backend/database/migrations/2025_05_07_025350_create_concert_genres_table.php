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
        Schema::create('concert_genres', function (Blueprint $table) {
            $table->foreignId('concert_id');
            $table->foreign('concert_id')->references('id')->on('concerts');
            $table->foreignId('genre_id');
            $table->foreign('genre_id')->references('id')->on('genres');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concert_genres');
    }
};
