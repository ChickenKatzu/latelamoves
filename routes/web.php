<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

// route debugs
Route::get('/debug-assets', function() {
    $data = [
        'environment' => app()->environment(),
        'app_url' => config('app.url'),
        'asset_url' => asset(''),
        'public_path' => public_path(),
        'base_path' => base_path(),
    ];

    // Cek folder build
    $buildPath = public_path('build');
    $data['build_exists'] = file_exists($buildPath);

    if (file_exists($buildPath)) {
        $data['build_contents'] = array_diff(scandir($buildPath), ['.', '..']);

        // Cek manifest
        $manifestPath = $buildPath . '/manifest.json';
        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);
            $data['manifest'] = $manifest;

            // Test asset URLs
            if (isset($manifest['resources/css/app.css'])) {
                $cssFile = $manifest['resources/css/app.css']['file'];
                $data['css_file'] = $cssFile;
                $data['css_exists'] = file_exists(public_path($cssFile));
                $data['css_url'] = asset($cssFile);
            }

            if (isset($manifest['resources/js/app.js'])) {
                $jsFile = $manifest['resources/js/app.js']['file'];
                $data['js_file'] = $jsFile;
                $data['js_exists'] = file_exists(public_path($jsFile));
                $data['js_url'] = asset($jsFile);
            }
        }

        // Cek folder assets
        $assetsPath = $buildPath . '/assets';
        if (file_exists($assetsPath)) {
            $data['assets_contents'] = array_diff(scandir($assetsPath), ['.', '..']);
        }
    }

    // Test langsung akses file
    $testFiles = [
        'manifest' => '/build/manifest.json',
        'css' => '/build/assets/app-D7in9Jx8.css',
        'js' => '/build/assets/app-Dz35q6s9.js',
    ];

    foreach ($testFiles as $key => $file) {
        $fullPath = public_path($file);
        $data['file_check'][$key] = [
            'path' => $file,
            'exists' => file_exists($fullPath),
            'size' => file_exists($fullPath) ? filesize($fullPath) : 0,
            'url' => asset($file),
        ];
    }

    return response()->json($data);
});

Route::get('/test-css', function() {
    $cssPath = public_path('build/assets/app-D7in9Jx8.css');
    if (file_exists($cssPath)) {
        return response(file_get_contents($cssPath))->header('Content-Type', 'text/css');
    }
    return 'CSS not found';
});

Route::get('/test-js', function() {
    $jsPath = public_path('build/assets/app-Dz35q6s9.js');
    if (file_exists($jsPath)) {
        return response(file_get_contents($jsPath))->header('Content-Type', 'application/javascript');
    }
    return 'JS not found';
});


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
