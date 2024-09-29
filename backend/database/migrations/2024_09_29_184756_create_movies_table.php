<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->integer('tmdb_id')->unique();
            $table->string('title');
            $table->string('original_title')->nullable();
            $table->text('overview')->nullable();
            $table->string('poster_path')->nullable();
            $table->string('backdrop_path')->nullable();
            $table->date('release_date')->nullable();
            $table->decimal('vote_average', 3, 1)->nullable();
            $table->integer('vote_count')->nullable();
            $table->decimal('popularity', 10, 3)->nullable();
            $table->boolean('adult')->default(false);
            $table->boolean('video')->default(false);
            $table->string('original_language', 10)->nullable();
            $table->json('genres')->nullable();
            $table->timestamps();

            $table->index('title');
            $table->index('release_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('movies');
    }
};