@extends('layouts.app')

@section('title', __('messages.movie_list'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Search Bar -->
    <div class="mb-8">
        <form id="search-form" class="flex gap-2">
            <input type="text"
                   id="search-input"
                   value="{{ $query }}"
                   placeholder="{{ __('messages.search_movies') }}"
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            <button type="submit"
                    class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200">
                {{ __('messages.search') }}
            </button>
        </form>
    </div>

    <!-- Movies Grid -->
    <div id="movies-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($movies as $movie)
        <div class="movie-card bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="relative">
                <a href="{{ route('movies.show', $movie['imdbID']) }}">
                    <img src="{{ $movie['Poster'] != 'N/A' ? $movie['Poster'] : 'https://via.placeholder.com/300x450?text=No+Poster' }}"
                         alt="{{ $movie['Title'] }}"
                         loading="lazy"
                         class="w-full h-64 object-cover">
                </a>
                <button onclick="toggleFavorite('{{ $movie['imdbID'] }}', '{{ $movie['Title'] }}', '{{ $movie['Year'] }}', '{{ $movie['Poster'] != 'N/A' ? $movie['Poster'] : '' }}')"
                        class="absolute top-2 right-2 bg-white rounded-full p-2 shadow-lg hover:bg-gray-100 favorite-btn"
                        data-imdb-id="{{ $movie['imdbID'] }}">
                    <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <a href="{{ route('movies.show', $movie['imdbID']) }}">
                    <h3 class="font-bold text-lg mb-2 hover:text-blue-500">{{ $movie['Title'] }}</h3>
                </a>
                <p class="text-gray-600">{{ $movie['Year'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Loading Indicator -->
    <div id="loading" class="text-center py-8 hidden">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
        <p class="mt-2 text-gray-600">{{ __('messages.loading') }}</p>
    </div>

    <!-- Empty State -->
    <div id="empty-state" class="text-center py-12 {{ count($movies) > 0 ? 'hidden' : '' }}">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linecap="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('messages.no_movies_found') }}</h3>
        <p class="mt-1 text-sm text-gray-500">{{ __('messages.search_movies') }}</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentPage = 1;
    let currentQuery = '{{ $query }}';
    let isLoading = false;
    let hasMore = true;
    let favorites = @json($favorites ?? []);

    // Infinite Scroll
    window.addEventListener('scroll', function() {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 1000) {
            if (!isLoading && hasMore && currentQuery) {
                loadMoreMovies();
            }
        }
    });

    // Search Form Submit
    document.getElementById('search-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const searchInput = document.getElementById('search-input');
        currentQuery = searchInput.value;
        currentPage = 1;
        hasMore = true;

        // Clear grid
        document.getElementById('movies-grid').innerHTML = '';

        // Load new search results
        searchMovies();
    });

    function searchMovies() {
        isLoading = true;
        document.getElementById('loading').classList.remove('hidden');

        fetch(`{{ route('movies.search') }}?search=${encodeURIComponent(currentQuery)}&page=${currentPage}`)
            .then(response => response.json())
            .then(data => {
                const grid = document.getElementById('movies-grid');

                if (data.movies && data.movies.length > 0) {
                    data.movies.forEach(movie => {
                        grid.appendChild(createMovieCard(movie));
                    });

                    hasMore = grid.children.length < parseInt(data.totalResults);
                    document.getElementById('empty-state').classList.add('hidden');
                } else if (currentPage === 1) {
                    document.getElementById('empty-state').classList.remove('hidden');
                }

                isLoading = false;
                document.getElementById('loading').classList.add('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                isLoading = false;
                document.getElementById('loading').classList.add('hidden');
            });
    }

    function loadMoreMovies() {
        currentPage++;
        searchMovies();
    }

    function createMovieCard(movie) {
        const div = document.createElement('div');
        div.className = 'movie-card bg-white rounded-lg shadow-lg overflow-hidden';

        const poster = movie.Poster !== 'N/A' ? movie.Poster : 'https://via.placeholder.com/300x450?text=No+Poster';

        div.innerHTML = `
            <div class="relative">
                <a href="/movies/${movie.imdbID}">
                    <img src="${poster}"
                         alt="${movie.Title}"
                         loading="lazy"
                         class="w-full h-64 object-cover">
                </a>
                <button onclick="toggleFavorite('${movie.imdbID}', '${movie.Title.replace(/'/g, "\\'")}', '${movie.Year}', '${poster}')"
                        class="absolute top-2 right-2 bg-white rounded-full p-2 shadow-lg hover:bg-gray-100 favorite-btn"
                        data-imdb-id="${movie.imdbID}">
                    <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <a href="/movies/${movie.imdbID}">
                    <h3 class="font-bold text-lg mb-2 hover:text-blue-500">${movie.Title}</h3>
                </a>
                <p class="text-gray-600">${movie.Year}</p>
            </div>
        `;

        return div;
    }

    function toggleFavorite(imdbId, title, year, poster) {
        fetch('{{ route('movies.favorite.toggle') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                imdb_id: imdbId,
                movie_id: imdbId,
                title: title,
                year: year,
                poster: poster
            })
        })
        .then(response => response.json())
        .then(data => {
            // Show notification
            alert(data.status === 'added' ? '{{ __("messages.added_to_favorites") }}' : '{{ __("messages.removed_from_favorites") }}');
        });
    }
</script>
@endpush
