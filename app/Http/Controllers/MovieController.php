<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\FavoriteMovie;
use Illuminate\Support\Facades\Session;

class MovieController extends Controller
{
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiKey = env('OMDB_API_KEY');
        $this->apiUrl = env('OMDB_API_URL');
    }

    public function index(Request $request)
    {
        $query = $request->get('search', 'movie'); // Default search
        
        $response = Http::get($this->apiUrl, [
            'apikey' => $this->apiKey,
            's' => $query,
            'page' => 1
        ]);

        $movies = $response->successful() ? ($response->json()['Search'] ?? []) : [];
        
        return view('movies.index', [
            'movies' => $movies,
            'query' => $query
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('search');
        $page = $request->get('page', 1);
        
        $response = Http::get($this->apiUrl, [
            'apikey' => $this->apiKey,
            's' => $query,
            'page' => $page
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return response()->json([
                'movies' => $data['Search'] ?? [],
                'totalResults' => $data['totalResults'] ?? 0,
                'page' => $page
            ]);
        }

        return response()->json(['movies' => [], 'totalResults' => 0], 500);
    }

    public function show($id)
    {
        $response = Http::get($this->apiUrl, [
            'apikey' => $this->apiKey,
            'i' => $id,
            'plot' => 'full'
        ]);

        if (!$response->successful()) {
            abort(404);
        }

        $movie = $response->json();
        $isFavorite = FavoriteMovie::where('user_id', Session::get('username'))
            ->where('imdb_id', $id)
            ->exists();

        return view('movies.show', [
            'movie' => $movie,
            'isFavorite' => $isFavorite
        ]);
    }

    public function toggleFavorite(Request $request)
    {
        $userId = Session::get('username');
        $imdbId = $request->imdb_id;
        
        $favorite = FavoriteMovie::where('user_id', $userId)
            ->where('imdb_id', $imdbId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['status' => 'removed']);
        } else {
            FavoriteMovie::create([
                'user_id' => $userId,
                'movie_id' => $request->movie_id,
                'imdb_id' => $imdbId,
                'title' => $request->title,
                'year' => $request->year,
                'poster' => $request->poster
            ]);
            return response()->json(['status' => 'added']);
        }
    }

    public function favorites()
    {
        $favorites = FavoriteMovie::where('user_id', Session::get('username'))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('movies.favorites', compact('favorites'));
    }

    public function removeFavorite($id)
    {
        $favorite = FavoriteMovie::where('user_id', Session::get('username'))
            ->where('id', $id)
            ->firstOrFail();
        
        $favorite->delete();
        
        return redirect()->route('favorites')->with('success', __('messages.removed_from_favorites'));
    }
}