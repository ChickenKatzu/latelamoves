@extends('layouts.app')

@section('title', __('messages.login'))

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-2xl font-bold text-center mb-8">{{ __('messages.app_name') }}</h1>

        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ $errors->first('login') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                    {{ __('messages.username') }}
                </label>
                <input type="text"
                       name="username"
                       id="username"
                       value="{{ old('username') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                       required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    {{ __('messages.password') }}
                </label>
                <input type="password"
                       name="password"
                       id="password"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                       required>
            </div>

            <button type="submit"
                    class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-200">
                {{ __('messages.login_button') }}
            </button>
        </form>

        <div class="mt-4 text-center text-sm text-gray-600">
            Username: aldmic<br>
            Password: 123abc123
        </div>
    </div>
</div>
@endsection
