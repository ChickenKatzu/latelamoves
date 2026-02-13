<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\DB;


// Language Switch
Route::get('lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// Guest Routes
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
    Route::get('/movies/search', [MovieController::class, 'search'])->name('movies.search');
    Route::get('/movies/{id}', [MovieController::class, 'show'])->name('movies.show');
    Route::post('/movies/favorite/toggle', [MovieController::class, 'toggleFavorite'])->name('movies.favorite.toggle');
    Route::get('/favorites', [MovieController::class, 'favorites'])->name('favorites');
    Route::delete('/favorites/{id}', [MovieController::class, 'removeFavorite'])->name('favorites.remove');
});

// healttest
Route::get('/health', function() {
    try {
        // Cek koneksi database
        DB::connection()->getPdo();
        return response()->json([
            'status' => 'healthy',
            'database' => 'connected',
            'timestamp' => now()
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'unhealthy',
            'database' => 'disconnected',
            'error' => $e->getMessage()
        ], 500);
    }
});
