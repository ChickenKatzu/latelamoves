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
            $table->string('user_id')->default('aldmic'); // Simple user tracking
            $table->string('movie_id');
            $table->string('imdb_id');
            $table->string('title');
            $table->string('year')->nullable();
            $table->string('poster')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'imdb_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('movies');
    }
};