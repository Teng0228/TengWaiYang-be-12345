<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTheatersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theaters', function (Blueprint $table) {
            $table->id();
            $table->string('movie_title');
            $table->string('theater_name'); 
            $table->integer('theater_room_no');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->timestamps();

            // Define foreign key constraint
            $table->foreign('movie_title')->references('title')->on('movies');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('theaters');
    }
}
