<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    
    protected $fillable = ['movie_title', 'username', 'rating', 'description'];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}

