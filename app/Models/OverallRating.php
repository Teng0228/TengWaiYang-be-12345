<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OverallRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_title',
        'overall_rating',
        'total_ratings'
    ];

    public $timestamps = true;
}
