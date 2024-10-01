<?php

namespace App\Http\Controllers;

use App\Models\Movie; // Assurez-vous d'avoir un modèle Movie
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        // Effectuer une recherche dans la base de données
        $movies = Movie::where('title', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->get();

        return response()->json($movies);
    }
}