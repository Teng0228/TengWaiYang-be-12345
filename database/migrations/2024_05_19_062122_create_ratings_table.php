<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->string('movie_title');
            $table->string('username'); 
            $table->integer('rating');
            $table->string('description')->nullable(); 
            $table->timestamps();

            // Define foreign key constraint
            $table->foreign('movie_title')->references('title')->on('movies');

            // Add unique constraint
            $table->unique(['movie_title', 'username']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ratings');
    }
}
