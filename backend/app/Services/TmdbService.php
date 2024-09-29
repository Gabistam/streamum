<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

class TmdbService
{
    protected $baseUrl = 'https://api.themoviedb.org/3';
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.api_key');
    }

    public function getTrending($timeWindow = 'day')
    {
        return $this->get("/trending/movie/$timeWindow");
    }

    public function getMovieDetails($movieId)
    {
        return $this->get("/movie/$movieId");
    }

    public function searchMovies($query)
    {
        return $this->get("/search/movie", ['query' => $query]);
    }

    protected function get($endpoint, $params = [])
    {
        $url = $this->baseUrl . $endpoint;
        $cacheKey = 'tmdb_' . md5($url . serialize($params));

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($url, $params) {
            $executed = RateLimiter::attempt(
                'tmdb-api',
                40, // Maximum attempts
                function() use ($url, $params) {
                    $response = Http::withToken($this->apiKey)->get($url, $params);

                    if ($response->successful()) {
                        return $response->json();
                    }

                    Log::error("TMDB API request failed: " . $response->body());
                    return null;
                },
                60 // Time window in seconds
            );

            if (!$executed) {
                Log::warning("TMDB API rate limit exceeded. Request delayed.");
                return null;
            }

            return $executed;
        });
    }
}