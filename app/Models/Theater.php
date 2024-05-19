<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theater extends Model
{
    use HasFactory;
    
    protected $fillable = ['movie_title', 'theater_name', 'theater_room_no', 'start_time', 'end_time'];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}

