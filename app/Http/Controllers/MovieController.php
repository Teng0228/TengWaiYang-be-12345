<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Rating;
use App\Models\OverallRating;

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
        'Genre1' => 'sometimes|string',
        'Genre2' => 'sometimes|string',
        'Genre3' => 'sometimes|string',
        'Director' => 'required|string',
        'Performer1' => 'sometimes|string',
        'Performer2' => 'sometimes|string',
        'Performer3' => 'sometimes|string',
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

    // Assign genres and performers
    $movie->genre1 = $validatedData['Genre1'] ?? null;
    $movie->genre2 = $validatedData['Genre2'] ?? null;
    $movie->genre3 = $validatedData['Genre3'] ?? null;
    $movie->director = $validatedData['Director'];
    $movie->performer1 = $validatedData['Performer1'] ?? null;
    $movie->performer2 = $validatedData['Performer2'] ?? null;
    $movie->performer3 = $validatedData['Performer3'] ?? null;
    $movie->language = $validatedData['Language'];

    // Save the movie to the database
    $movie->save();


    // Return a response indicating success
    return response()->json(['message' => 'Movie inserted successfully'], 201);
}

    public function insertRating(Request $request)
    {
        // Validate incoming request
        $validatedData = $request->validate([
            'movie_title' => 'required|string',
            'username' => 'required|string',
            'rating' => 'required|integer|min:1|max:10',
            'description' => 'nullable|string',
        ]);

        // Check if the movie exists
        $movieExists = Movie::where('title', $validatedData['movie_title'])->exists();

        if (!$movieExists) {
            return response()->json(['error' => 'Movie does not exist'], 404);
        }

        // Create a new rating instance
        $rating = new Rating();
        $rating->movie_title = $validatedData['movie_title'];
        $rating->username = $validatedData['username'];
        $rating->rating = $validatedData['rating'];
        $rating->description = $validatedData['description'];

        // Save the rating to the database
        $rating->save();

        // Update the overall rating
        $this->updateOverallRating($validatedData['movie_title']);

        // Return a response indicating success
        return response()->json(['message' => 'Rating inserted successfully'], 201);
    }

    public function updateOverallRating($movie_title)
    {
        // Get all ratings for the specified movie title
        $ratings = Rating::where('movie_title', $movie_title)->get();

        $total_ratings = $ratings->count();
        $sum_ratings = $ratings->sum('rating');

        // Calculate overall rating
        $overall_rating = $total_ratings > 0 ? $sum_ratings / $total_ratings : 0;

        // Update or create overall rating record
        OverallRating::updateOrCreate(
            ['movie_title' => $movie_title],
            ['overall_rating' => $overall_rating, 'total_ratings' => $total_ratings]
        );

        return response()->json(['message' => 'Overall rating updated successfully']);
    }
}