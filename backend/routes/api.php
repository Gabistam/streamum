<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TmdbController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\Admin\DashboardController;

// Routes publiques
Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/forgot-password', [ApiAuthController::class, 'forgotPassword']);
Route::post('/reset-password', [ApiAuthController::class, 'resetPassword']);

// Routes protégées par authentification
Route::middleware('auth:sanctum')->group(function () {
    // Profil utilisateur
    Route::put('/user/profile', [ApiAuthController::class, 'updateProfile']);
    Route::post('/user/change-password', [ApiAuthController::class, 'changePassword']);

    // Films
    Route::get('/movies/popular', [TmdbController::class, 'getPopularMovies']);
    Route::get('/movies/search', [TmdbController::class, 'searchMovies']);
    Route::get('/movies/{id}', [TmdbController::class, 'getMovieDetails']);
    Route::get('/movies/trending', [TmdbController::class, 'getTrendingMovies']);
    Route::get('/movies/upcoming', [TmdbController::class, 'getUpcomingMovies']);
    Route::get('/movies/top-rated', [TmdbController::class, 'getTopRatedMovies']);
    Route::get('/movies/{id}/similar', [TmdbController::class, 'getSimilarMovies']);
    Route::get('/movies/{id}/recommendations', [TmdbController::class, 'getRecommendedMovies']);

    // Favoris et historique
    Route::post('/movies/{id}/favorite', [MovieController::class, 'addToFavorites']);
    Route::delete('/movies/{id}/favorite', [MovieController::class, 'removeFromFavorites']);
    Route::get('/user/favorites', [MovieController::class, 'getFavorites']);
    Route::post('/movies/{id}/watch-history', [MovieController::class, 'addToWatchHistory']);
    Route::get('/user/watch-history', [MovieController::class, 'getWatchHistory']);

    // Genres et filtres
    Route::get('/genres', [TmdbController::class, 'getGenres']);
    Route::get('/movies/filter', [TmdbController::class, 'filterMovies']);

    // Avis et notes
    Route::post('/movies/{id}/review', [MovieController::class, 'addReview']);
    Route::put('/movies/{id}/review', [MovieController::class, 'updateReview']);
    Route::delete('/movies/{id}/review', [MovieController::class, 'deleteReview']);
    Route::get('/movies/{id}/reviews', [MovieController::class, 'getMovieReviews']);
    Route::post('/movies/{id}/rate', [MovieController::class, 'rateMovie']);

    // Routes admin
    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::get('/users', [DashboardController::class, 'users']);
        Route::get('/movies', [DashboardController::class, 'movies']);
    });
});

// Route de test pour vérifier l'authentification
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');