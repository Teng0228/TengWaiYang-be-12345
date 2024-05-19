<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\MovieController;
use App\Http\Controllers\TheaterController;
use App\Models;


Route::get('/', function () {
    return view('welcome');
});



Route::get('/genre', [MovieController::class, 'getMoviesByGenre']);
Route::get('/timeslot', [MovieController::class, 'getMoviesByTimeSlot']);
Route::get('/specific_movie_theater', [MovieController::class, 'getSpecificMovieTheater']);
Route::get('/search_performer', [MovieController::class, 'searchPerformer']);
Route::get('/new_movies', [MovieController::class, 'getNewMovies']);






