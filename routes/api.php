<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\MovieController;
use App\Models;



Route::get ('/testing', function () {
    return 'this is a test';
});


Route::post('/movies', [MovieController::class, 'insertMovie']);
Route::post('/ratings', [MovieController::class, 'insertRating']);








