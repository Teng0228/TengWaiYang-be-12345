<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Rating;
use App\Models\Theater;
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

    // Compose the success message
    $message = "Successfully added movie {$movie->title} with Movie_ID {$movie->id}";

    // Return a response indicating success
    return response()->json(['message' => $message, 'success' => true], 201);
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

        // Compose the success message
        $message = "Successfully added review for {$rating->movie_title} by user: {$rating->username}";

        // Return a response indicating success
        return response()->json(['message' => $message, 'success' => true], 201);
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

    public function getMoviesByGenre(Request $request)
    {
        $genre = $request->query('genre');
    
        // Query movies by genre and join with overall_ratings table
        $movies = Movie::select(
                'movies.id',
                'movies.title',
                'movies.length',
                'movies.description',
                'overall_ratings.overall_rating'
            )
            ->leftJoin('overall_ratings', 'movies.title', '=', 'overall_ratings.movie_title')
            ->where('movies.genre1', $genre)
            ->orWhere('movies.genre2', $genre)
            ->orWhere('movies.genre3', $genre)
            ->get();
    
        // Format the data
        $formattedMovies = $movies->map(function ($movie) use ($genre) {
            return [
                'Movie_ID' => $movie->id,
                'Title' => $movie->title,
                'Genre' => $genre,
                'Duration' => $movie->length,
                'Overall_rating' => $movie->overall_rating ?? null,
                'Description' => $movie->description
            ];
        });
    
        return response()->json($formattedMovies);
    } 
    
    public function getMoviesByTimeSlot(Request $request)
{
    $theaterName = $request->query('theater_name');
    $startTime = $request->query('time_start');
    $endTime = $request->query('time_end');

    // Query theaters based on the provided time slot and theater name
    $theaters = Theater::select(
            'theaters.movie_title',
            'theaters.theater_name',
            'theaters.theater_room_no',
            'theaters.start_time',
            'theaters.end_time',
            'movies.id',
            'movies.title',
            'movies.length',
            'movies.description',
            'overall_ratings.overall_rating',
            'movies.genre1'
        )
        ->leftJoin('movies', 'theaters.movie_title', '=', 'movies.title')
        ->leftJoin('overall_ratings', 'movies.title', '=', 'overall_ratings.movie_title')
        ->where('theaters.theater_name', $theaterName)
        ->whereBetween('theaters.start_time', [$startTime, $endTime])
        ->get();

    // Format the data
    $formattedMovies = $theaters->map(function ($theater) {
        return [
            'Movie_ID' => $theater->id,
            'Title' => $theater->title,
            'Duration' => $theater->length,
            'Genre' => $theater->genre1 ?: 'Unknown',
            'Overall_rating' => $theater->overall_rating ?? null,
            'Theater_name' => $theater->theater_name,
            'Start_time' => $theater->start_time,
            'End_time' => $theater->end_time,
            'Description' => $theater->description,
            'Theater_room_no' => $theater->theater_room_no
        ];
    });

    return response()->json($formattedMovies);
}

public function getSpecificMovieTheater(Request $request)
{
    $theaterName = $request->query('theater_name');
    $date = $request->query('d_date');

    // Query theaters based on the provided theater name and date
    $theaters = Theater::select(
            'theaters.movie_title',
            'theaters.theater_name',
            'theaters.theater_room_no',
            'theaters.start_time',
            'theaters.end_time',
            'movies.id',
            'movies.title',
            'movies.length',
            'movies.description',
            'overall_ratings.overall_rating',
            'movies.genre1'
        )
        ->leftJoin('movies', 'theaters.movie_title', '=', 'movies.title')
        ->leftJoin('overall_ratings', 'movies.title', '=', 'overall_ratings.movie_title')
        ->where('theaters.theater_name', $theaterName)
        ->whereDate('theaters.start_time', $date)
        ->get();

    // Format the data
    $formattedMovies = $theaters->map(function ($theater) {
        return [
            'Movie_ID' => $theater->id,
            'Title' => $theater->title,
            'Duration' => $theater->length,
            'Genre' => $theater->genre1 ?: 'Unknown',
            'Overall_rating' => $theater->overall_rating ?? null,
            'Theater_name' => $theater->theater_name,
            'Start_time' => $theater->start_time,
            'End_time' => $theater->end_time,
            'Description' => $theater->description,
            'Theater_room_no' => $theater->theater_room_no
        ];
    });

    return response()->json($formattedMovies);
}

public function searchPerformer(Request $request)
{
    $performerName = $request->query('performer_name');

    // Query movies featuring the provided performer
    $movies = Movie::select(
            'movies.id',
            'movies.title',
            'movies.length',
            'movies.description',
            'overall_ratings.overall_rating',
            'movies.genre1',
            'movies.performer1',
            'movies.performer2',
            'movies.performer3'
        )
        ->leftJoin('overall_ratings', 'movies.title', '=', 'overall_ratings.movie_title')
        ->where(function($query) use ($performerName) {
            $query->where('performer1', 'like', '%'.$performerName.'%')
                  ->orWhere('performer2', 'like', '%'.$performerName.'%')
                  ->orWhere('performer3', 'like', '%'.$performerName.'%');
        })
        ->get();

    // Format the data
    $formattedMovies = $movies->map(function ($movie) {
        return [
            'Movie_ID' => $movie->id,
            'Overall_rating' => $movie->overall_rating ?? null,
            'Title' => $movie->title,
            'Description' => $movie->description,
            'Duration' => $movie->length,
            'Genre' => $movie->genre1 ?: 'Unknown',
            'Performer1' => $movie->performer1,
            'Performer2' => $movie->performer2,
            'Performer3' => $movie->performer3,
        ];
    });

    return response()->json($formattedMovies);
}

public function getNewMovies(Request $request)
{
    $releaseDate = $request->query('r_date');

    // Query movies released before the provided date
    $movies = Movie::select(
            'movies.id',
            'movies.title',
            'movies.length',
            'movies.description',
            'overall_ratings.overall_rating',
            'movies.genre1'
        )
        ->leftJoin('overall_ratings', 'movies.title', '=', 'overall_ratings.movie_title')
        ->whereDate('movies.release', '<', $releaseDate)
        ->get();

    // Format the data
    $formattedMovies = $movies->map(function ($movie) {
        return [
            'Movie_ID' => $movie->id,
            'Title' => $movie->title,
            'Genre' => $movie->genre1 ?: 'Unknown',
            'Duration' => $movie->length,
            'Overall_rating' => $movie->overall_rating ?? null,
            'Description' => $movie->description
        ];
    });

    return response()->json($formattedMovies);
}

    
}