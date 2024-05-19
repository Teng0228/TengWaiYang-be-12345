<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('movies', function (Blueprint $table) {
        $table->id();
        $table->string('title')->unique(); 
        $table->date('release');
        $table->string('length');
        $table->text('description');
        $table->string('mpaa_rating');
        $table->string('genre1')->nullable();
        $table->string('genre2')->nullable();
        $table->string('genre3')->nullable();
        $table->string('director');
        $table->string('performer1')->nullable();
        $table->string('performer2')->nullable();
        $table->string('performer3')->nullable();
        $table->string('language');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies');
    }
}
