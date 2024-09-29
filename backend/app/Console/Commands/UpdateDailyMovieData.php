<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TmdbService;
use App\Models\Movie;
use App\Models\TrendingMovie;
use Carbon\Carbon;

class UpdateDailyMovieData extends Command
{
    protected $signature = 'movies:update-daily';
    protected $description = 'Update movie data and trending movies on a daily basis';

    protected $tmdbService;

    public function __construct(TmdbService $tmdbService)
    {
        parent::__construct();
        $this->tmdbService = $tmdbService;
    }

    public function handle()
    {
        $this->info('Starting daily movie data update...');

        $this->updateTrendingMovies();
        $this->updateExistingMovies();

        $this->info('Daily movie data update completed.');
    }

    protected function updateTrendingMovies()
    {
        $this->info('Updating trending movies...');

        $trendingMovies = $this->tmdbService->getTrending('day');

        foreach ($trendingMovies['results'] as $movieData) {
            $movie = Movie::updateOrCreate(
                ['tmdb_id' => $movieData['id']],
                [
                    'title' => $movieData['title'],
                    'overview' => $movieData['overview'],
                    'poster_path' => $movieData['poster_path'],
                    'release_date' => $movieData['release_date'],
                    // Add other fields as necessary
                ]
            );

            TrendingMovie::updateOrCreate(
                [
                    'movie_id' => $movie->id,
                    'trend_date' => Carbon::today(),
                    'trend_type' => 'day'
                ]
            );
        }
    }

    protected function updateExistingMovies()
    {
        $this->info('Updating existing movies...');

        // Get movies that haven't been updated in the last 24 hours
        $moviesToUpdate = Movie::where('updated_at', '<', Carbon::now()->subDay())->get();

        foreach ($moviesToUpdate as $movie) {
            $movieData = $this->tmdbService->getMovieDetails($movie->tmdb_id);

            if ($movieData) {
                $movie->update([
                    'title' => $movieData['title'],
                    'overview' => $movieData['overview'],
                    'poster_path' => $movieData['poster_path'],
                    'release_date' => $movieData['release_date'],
                    'vote_average' => $movieData['vote_average'],
                    'vote_count' => $movieData['vote_count'],
                    // Update other fields as necessary
                ]);
            }
        }
    }
}