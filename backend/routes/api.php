<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TrendingMovieController;
use App\Http\Controllers\Api\SearchController;

// Public routes
Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login']);

// Public movie routes with rate limiting
Route::middleware('tmdb.limit')->group(function () {
    Route::get('/movies', [MovieController::class, 'index']);
    Route::get('/movies/{id}', [MovieController::class, 'show']);
    Route::get('/trending-movies', [TrendingMovieController::class, 'index']);
    Route::post('/search', [SearchController::class, 'store']);
});

// Protected routes
Route::middleware(['auth:sanctum', 'tmdb.limit'])->group(function () {
    // Auth
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::get('/user', [ApiAuthController::class, 'user']);

    // CRUD resources
    Route::apiResource('movies', MovieController::class)->except(['index', 'show']);
    Route::post('/movies/{id}/refresh', [MovieController::class, 'refresh']);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('trending-movies', TrendingMovieController::class)->except(['index']);
    Route::apiResource('searches', SearchController::class)->except(['store']);
});