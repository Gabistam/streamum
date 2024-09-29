<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrendingMovie extends Model
{
    use HasFactory;

    protected $fillable = ['movie_id', 'trend_date', 'trend_type'];

    protected $casts = [
        'trend_date' => 'date',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}