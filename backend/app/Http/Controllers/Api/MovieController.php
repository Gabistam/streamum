<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Services\TmdbService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MovieController extends Controller
{
    protected $tmdbService;

    public function __construct(TmdbService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    public function index()
    {
        $movies = Movie::all();
        return response()->json($movies);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tmdb_id' => 'required|integer|unique:movies',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $movieData = $this->tmdbService->getMovieDetails($request->tmdb_id);
        if (!$movieData) {
            return response()->json(['error' => 'Movie not found in TMDB'], 404);
        }

        $movie = Movie::create([
            'tmdb_id' => $movieData['id'],
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
        ]);

        return response()->json($movie, 201);
    }

    public function show($id)
    {
        $movie = Movie::findOrFail($id);
        return response()->json($movie);
    }

    public function update(Request $request, $id)
    {
        $movie = Movie::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'string|max:255',
            'original_title' => 'nullable|string|max:255',
            'overview' => 'nullable|string',
            'poster_path' => 'nullable|string',
            'backdrop_path' => 'nullable|string',
            'release_date' => 'nullable|date',
            'vote_average' => 'nullable|numeric|between:0,10',
            'vote_count' => 'nullable|integer',
            'popularity' => 'nullable|numeric',
            'adult' => 'boolean',
            'video' => 'boolean',
            'original_language' => 'nullable|string|max:10',
            'genres' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $movie->update($request->all());
        return response()->json($movie);
    }

    public function destroy($id)
    {
        $movie = Movie::findOrFail($id);
        $movie->delete();
        return response()->json(null, 204);
    }

    public function refresh($id)
    {
        $movie = Movie::findOrFail($id);
        $movieData = $this->tmdbService->getMovieDetails($movie->tmdb_id);

        if (!$movieData) {
            return response()->json(['error' => 'Movie not found in TMDB'], 404);
        }

        $movie->update([
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
        ]);

        return response()->json($movie);
    }
}