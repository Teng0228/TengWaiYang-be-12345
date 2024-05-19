<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Theater;
use App\Models\Movie;
use App\Models\OverallRating;


class TheaterController extends Controller
{
    public function insertTheater(Request $request)
    {
        // Validate incoming request
        $validatedData = $request->validate([
            'movie_title' => 'required|string',
            'theater_name' => 'required|string',
            'theater_room_no' => 'required|integer|min:1|max:20',
            'start_time' => 'required|date_format:Y-m-d H:i:s',
            'end_time' => 'required|date_format:Y-m-d H:i:s',
        ]);

        // Check if the movie exists
        $movieExists = Movie::where('title', $validatedData['movie_title'])->exists();

        if (!$movieExists) {
            return response()->json(['error' => 'Movie does not exist'], 404);
        }

        // Create a new rating instance
        $theater = new Theater();
        $theater->movie_title = $validatedData['movie_title'];
        $theater->theater_name = $validatedData['theater_name'];
        $theater->theater_room_no = $validatedData['theater_room_no'];
        $theater->start_time = $validatedData['start_time'];
        $theater->end_time = $validatedData['end_time'];

        // Save the rating to the database
        $theater->save();

        // Return a response indicating success
        return response()->json(['message' => 'Theater inserted successfully'], 201);
    }
}
