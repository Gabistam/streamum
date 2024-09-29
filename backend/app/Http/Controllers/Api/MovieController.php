<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::all();
        return response()->json($movies);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tmdb_id' => 'required|integer|unique:movies',
            'title' => 'required|string|max:255',
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

        $movie = Movie::create($request->all());
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
}