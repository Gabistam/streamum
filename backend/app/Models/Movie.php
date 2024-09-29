<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'tmdb_id', 'title', 'original_title', 'overview', 'poster_path',
        'backdrop_path', 'release_date', 'vote_average', 'vote_count',
        'popularity', 'adult', 'video', 'original_language', 'genres'
    ];

    protected $casts = [
        'release_date' => 'date',
        'vote_average' => 'float',
        'popularity' => 'float',
        'adult' => 'boolean',
        'video' => 'boolean',
        'genres' => 'array',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function trendingMovies()
    {
        return $this->hasMany(TrendingMovie::class);
    }
}