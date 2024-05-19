<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'release', 'length', 'description', 'mpaa_rating',
        'genre1', 'genre2', 'genre3', 'director', 'performer1',
        'performer2', 'performer3', 'language'
    ];

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function movieRating()
    {
        return $this->hasOne(MovieRating::class, 'movie_title', 'title');
    }
}