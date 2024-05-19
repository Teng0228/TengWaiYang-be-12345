<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOverallRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overall_ratings', function (Blueprint $table) {
            $table->id();
            $table->string('movie_title')->unique();
            $table->decimal('overall_rating', 5, 2)->default(0);
            $table->integer('total_ratings')->default(0);
            $table->timestamps();

            // Define foreign key constraint if necessary
            // $table->foreign('movie_title')->references('title')->on('movies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('overall_ratings');
    }
}
