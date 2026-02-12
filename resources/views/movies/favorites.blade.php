@extends('layouts.app')

@section('title', __('messages.favorites'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold mb-6">{{ __('messages.favorites') }}</h1>

    @if($favorites->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($favorites as $favorite)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="relative">
                <a href="{{ route('movies.show', $favorite->imdb_id) }}">
                    <img src="{{ $favorite->poster ?? 'https://via.placeholder.com/300x450?text=No+Poster' }}"
                         alt="{{ $favorite->title }}"
                         loading="lazy"
                         class="w-full h-64 object-cover">
                </a>
                <form action="{{ route('favorites.remove', $favorite->id) }}" method="POST" class="absolute top-2 right-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition duration-200"
                            onclick="return confirm('Remove from favorites?')">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                        </svg>
                    </button>
                </form>
            </div>
            <div class="p-4">
                <a href="{{ route('movies.show', $favorite->imdb_id) }}">
                    <h3 class="font-bold text-lg mb-2 hover:text-blue-500">{{ $favorite->title }}</h3>
                </a>
                <p class="text-gray-600">{{ $favorite->year }}</p>
                <p class="text-xs text-gray-400 mt-2">Added: {{ $favorite->created_at->diffForHumans() }}</p>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linecap="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('messages.no_favorites') }}</h3>
        <p class="mt-1 text-sm text-gray-500">{{ __('messages.add_to_favorites') }}</p>
        <div class="mt-6">
            <a href="{{ route('movies.index') }}"
               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                {{ __('messages.search_movies') }}
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
