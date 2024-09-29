<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('searches', function (Blueprint $table) {
            $table->id();
            $table->string('query');
            $table->integer('count')->default(1);
            $table->timestamps();

            $table->index('query');
        });
    }

    public function down()
    {
        Schema::dropIfExists('searches');
    }
};