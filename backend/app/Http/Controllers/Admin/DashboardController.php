<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Movie; // Assurez-vous d'avoir ce modèle
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $usersCount = User::count();
        $moviesCount = Movie::count();
        return response()->json([
            'usersCount' => $usersCount,
            'moviesCount' => $moviesCount,
        ]);
    }

    public function users()
    {
        $users = User::paginate(10);
        return response()->json($users);
    }

    public function movies()
    {
        $movies = Movie::paginate(10);
        return response()->json($movies);
    }
}