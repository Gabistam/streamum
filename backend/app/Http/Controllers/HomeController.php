<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Affiche la page d'accueil.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $title = 'Bienvenue sur Mon Application Laravel';
        $description = 'Ceci est la page d\'accueil de mon application Laravel.';

        return view('welcome', compact('title', 'description'));
    }
}