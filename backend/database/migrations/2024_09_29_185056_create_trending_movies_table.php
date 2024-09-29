<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trending_movies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained()->onDelete('cascade');
            $table->date('trend_date');
            $table->enum('trend_type', ['day', 'week']);
            $table->timestamps();

            $table->unique(['movie_id', 'trend_date', 'trend_type']);
            $table->index('trend_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('trending_movies');
    }
};