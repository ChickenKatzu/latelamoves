<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('messages.app_name') }} - @yield('title')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100">
    <div id="app">
        @if(Session::has('authenticated'))
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('movies.index') }}" class="text-xl font-bold text-gray-800">
                            {{ __('messages.app_name') }}
                        </a>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('movies.index') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2">
                            {{ __('messages.movie_list') }}
                        </a>
                        <a href="{{ route('favorites') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2">
                            {{ __('messages.favorites') }}
                        </a>
                        
                        <!-- Language Switcher -->
                        <div class="flex space-x-2">
                            <a href="{{ route('lang.switch', 'en') }}" 
                               class="px-2 py-1 {{ app()->getLocale() == 'en' ? 'bg-blue-500 text-white' : 'bg-gray-200' }} rounded">
                                EN
                            </a>
                            <a href="{{ route('lang.switch', 'id') }}" 
                               class="px-2 py-1 {{ app()->getLocale() == 'id' ? 'bg-blue-500 text-white' : 'bg-gray-200' }} rounded">
                                ID
                            </a>
                        </div>
                        
                        <span class="text-gray-600">Welcome, {{ Session::get('username') }}</span>
                        <a href="{{ route('logout') }}" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                            {{ __('messages.logout') }}
                        </a>
                    </div>
                </div>
            </div>
        </nav>
        @endif
        
        <main class="py-6">
            @yield('content')
        </main>
    </div>
    
    @stack('scripts')
</body>
</html>