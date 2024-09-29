<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrendingMovie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrendingMovieController extends Controller
{
    public function index()
    {
        $trendingMovies = TrendingMovie::with('movie')->get();
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
}