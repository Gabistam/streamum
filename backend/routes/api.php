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

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::get('/user', [ApiAuthController::class, 'user']);

    // CRUD resources
    Route::apiResource('movies', MovieController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('trending-movies', TrendingMovieController::class);
    Route::apiResource('searches', SearchController::class);
});