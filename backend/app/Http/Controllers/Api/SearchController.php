<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Search;
use App\Services\TmdbService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
    protected $tmdbService;

    public function __construct(TmdbService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    public function index()
    {
        $searches = Search::orderBy('count', 'desc')->get();
        return response()->json($searches);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $search = Search::firstOrCreate(
            ['query' => $request->query],
            ['count' => 1]
        );

        if (!$search->wasRecentlyCreated) {
            $search->increment('count');
        }

        $searchResults = $this->tmdbService->searchMovies($request->query);

        return response()->json([
            'search' => $search,
            'results' => $searchResults
        ], 201);
    }

    public function show($id)
    {
        $search = Search::findOrFail($id);
        return response()->json($search);
    }

    public function update(Request $request, $id)
    {
        $search = Search::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'query' => 'required|string|max:255',
            'count' => 'integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $search->update($request->all());
        return response()->json($search);
    }

    public function destroy($id)
    {
        $search = Search::findOrFail($id);
        $search->delete();
        return response()->json(null, 204);
    }
}