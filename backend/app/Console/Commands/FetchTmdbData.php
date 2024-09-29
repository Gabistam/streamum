<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Movie;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\TrendingMovie;
use App\Services\TmdbService;
use Illuminate\Console\Command;

class FetchTmdbData extends Command
{
    protected $signature = 'tmdb:fetch {type=trending} {--time_window=day}';
    protected $description = 'Fetch trending movies and details from TMDB API';

    protected $tmdbService;

    public function __construct(TmdbService $tmdbService)
    {
        parent::__construct();
        $this->tmdbService = $tmdbService;
    }

    public function handle()
    {
        $type = $this->argument('type');
        $timeWindow = $this->option('time_window');

        $this->info("Fetching $type movies for $timeWindow from TMDB API...");

        $trendingMovies = $this->tmdbService->getTrending($timeWindow);

        if ($trendingMovies) {
            $this->processTrendingMovies($trendingMovies['results'], $timeWindow);
            $this->info('Trending movies fetched and saved successfully.');
        } else {
            $this->error('Failed to fetch data from TMDB API.');
        }
    }

    private function processTrendingMovies($movies, $timeWindow)
    {
        foreach ($movies as $movieData) {
            $movieDetails = $this->tmdbService->getMovieDetails($movieData['id']);
            if ($movieDetails) {
                $movie = $this->saveMovie($movieDetails);
                $this->saveTrendingMovie($movie, $timeWindow);
            }
        }
    }

    private function saveMovie($movieData)
    {
        $movie = Movie::updateOrCreate(
            ['tmdb_id' => $movieData['id']],
            [
                'title' => $movieData['title'],
                'original_title' => $movieData['original_title'],
                'overview' => $movieData['overview'],
                'poster_path' => $movieData['poster_path'],
                'backdrop_path' => $movieData['backdrop_path'],
                'release_date' => $movieData['release_date'],
                'vote_average' => $movieData['vote_average'],
                'vote_count' => $movieData['vote_count'],
                'popularity' => $movieData['popularity'],
                'adult' => $movieData['adult'],
                'video' => $movieData['video'],
                'original_language' => $movieData['original_language'],
                'genres' => json_encode(array_column($movieData['genres'], 'id')),
            ]
        );

        $this->processGenres($movie, $movieData['genres']);
        return $movie;
    }

    private function saveTrendingMovie($movie, $timeWindow)
    {
        TrendingMovie::updateOrCreate(
            [
                'movie_id' => $movie->id,
                'trend_date' => Carbon::today(),
                'trend_type' => $timeWindow
            ],
            []
        );
    }

    private function processGenres($movie, $genres)
    {
        $categoryIds = [];
        foreach ($genres as $genre) {
            $category = Category::firstOrCreate(
                ['tmdb_id' => $genre['id']],
                ['name' => $genre['name'], 'slug' => Str::slug($genre['name'])]
            );
            $categoryIds[] = $category->id;
        }
        $movie->categories()->sync($categoryIds);
    }
}