@extends('layouts.app')

@section('title', $movie['Title'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-4">
        <a href="{{ route('movies.index') }}" class="text-blue-500 hover:text-blue-700">
            &larr; {{ __('messages.back_to_list') }}
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="md:flex">
            <div class="md:w-1/3">
                <img src="{{ $movie['Poster'] != 'N/A' ? $movie['Poster'] : 'https://via.placeholder.com/300x450?text=No+Poster' }}"
                     alt="{{ $movie['Title'] }}"
                     loading="lazy"
                     class="w-full h-auto">
            </div>

            <div class="md:w-2/3 p-8">
                <div class="flex justify-between items-start">
                    <h1 class="text-3xl font-bold mb-4">{{ $movie['Title'] }} ({{ $movie['Year'] }})</h1>

                    <button onclick="toggleFavorite()"
                            class="px-4 py-2 {{ $isFavorite ? 'bg-red-500 hover:bg-red-600' : 'bg-blue-500 hover:bg-blue-600' }} text-white rounded-lg transition duration-200">
                        {{ $isFavorite ? __('messages.remove_from_favorites') : __('messages.add_to_favorites') }}
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <h3 class="font-semibold text-gray-700">{{ __('messages.imdb_rating') }}:</h3>
                        <p>{{ $movie['imdbRating'] }}/10</p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-700">{{ __('messages.genre') }}:</h3>
                        <p>{{ $movie['Genre'] }}</p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-700">{{ __('messages.director') }}:</h3>
                        <p>{{ $movie['Director'] }}</p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-700">{{ __('messages.actors') }}:</h3>
                        <p>{{ $movie['Actors'] }}</p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-700">{{ __('messages.language') }}:</h3>
                        <p>{{ $movie['Language'] }}</p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-700">{{ __('messages.awards') }}:</h3>
                        <p>{{ $movie['Awards'] }}</p>
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="font-semibold text-gray-700 text-lg">{{ __('messages.plot') }}:</h3>
                    <p class="mt-2 text-gray-600">{{ $movie['Plot'] }}</p>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="font-semibold text-gray-700">{{ __('messages.details') }}:</h3>
                    <div class="grid grid-cols-2 gap-4 mt-2">
                        <div>
                            <span class="text-gray-600">Rated:</span>
                            <span class="ml-2">{{ $movie['Rated'] }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Runtime:</span>
                            <span class="ml-2">{{ $movie['Runtime'] }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Country:</span>
                            <span class="ml-2">{{ $movie['Country'] }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Box Office:</span>
                            <span class="ml-2">{{ $movie['BoxOffice'] ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleFavorite() {
    fetch('{{ route('movies.favorite.toggle') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            imdb_id: '{{ $movie['imdbID'] }}',
            movie_id: '{{ $movie['imdbID'] }}',
            title: '{{ $movie['Title'] }}',
            year: '{{ $movie['Year'] }}',
            poster: '{{ $movie['Poster'] }}'
        })
    })
    .then(response => response.json())
    .then(data => {
        location.reload();
    });
}
</script>
@endpush
