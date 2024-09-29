<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrendingMovie;
use App\Models\Movie;
use App\Services\TmdbService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class TrendingMovieController extends Controller
{
    protected $tmdbService;

    public function __construct(TmdbService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    public function index(Request $request)
    {
        $timeWindow = $request->input('time_window', 'day');
        $trendingMovies = TrendingMovie::with('movie')
            ->where('trend_type', $timeWindow)
            ->where('trend_date', Carbon::today())
            ->get();

        if ($trendingMovies->isEmpty()) {
            $this->fetchAndStoreTrendingMovies($timeWindow);
            $trendingMovies = TrendingMovie::with('movie')
                ->where('trend_type', $timeWindow)
                ->where('trend_date', Carbon::today())
                ->get();
        }

        return response()->json($trendingMovies);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|exists:movies,id',
            'trend_date' => 'required|date',
            'trend_type' => 'required|in:day,week'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $trendingMovie = TrendingMovie::create($request->all());
        return response()->json($trendingMovie, 201);
    }

    public function show($id)
    {
        $trendingMovie = TrendingMovie::with('movie')->findOrFail($id);
        return response()->json($trendingMovie);
    }

    public function update(Request $request, $id)
    {
        $trendingMovie = TrendingMovie::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'movie_id' => 'exists:movies,id',
            'trend_date' => 'date',
            'trend_type' => 'in:day,week'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $trendingMovie->update($request->all());
        return response()->json($trendingMovie);
    }

    public function destroy($id)
    {
        $trendingMovie = TrendingMovie::findOrFail($id);
        $trendingMovie->delete();
        return response()->json(null, 204);
    }

    protected function fetchAndStoreTrendingMovies($timeWindow)
    {
        $trendingMovies = $this->tmdbService->getTrending($timeWindow);

        foreach ($trendingMovies['results'] as $movieData) {
            $movie = Movie::firstOrCreate(
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
                    'genres' => json_encode($movieData['genre_ids']),
                ]
            );

            TrendingMovie::create([
                'movie_id' => $movie->id,
                'trend_date' => Carbon::today(),
                'trend_type' => $timeWindow
            ]);
        }
    }
}