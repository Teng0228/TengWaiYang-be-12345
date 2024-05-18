<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    public function insertMovie(Request $request)
    {
        // Validate incoming request
        $validatedData = $request->validate([
            'Title' => 'required|string',
            'Release_date' => 'required|date',
            'Length' => 'required|string',
            'Description' => 'required|string',
            'Mpaa_rating' => 'required|string',
            'Genre' => 'required|string',
            'Director' => 'required|string',
            'Performer' => 'required|string',
            'Language' => 'required|string',
        ]);

        // Create a new movie instance
        $movie = new Movie();
        
        // Assign values from the request to the movie instance
        $movie->title = $validatedData['Title'];
        $movie->release = $validatedData['Release_date'];
        $movie->length = $validatedData['Length'];
        $movie->description = $validatedData['Description'];
        $movie->mpaa_rating = $validatedData['Mpaa_rating'];
        $movie->genre = $validatedData['Genre'];
        $movie->director = $validatedData['Director'];
        $movie->performer = $validatedData['Performer'];
        $movie->language = $validatedData['Language'];

        // Save the movie to the database
        $movie->save();

        // Return a response indicating success
        return response()->json(['message' => 'Movie inserted successfully'], 201);
    }
}
