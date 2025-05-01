@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="relative bg-gray-900">
    <div class="absolute inset-0">
        <img class="w-full h-full object-cover opacity-50" src="{{ asset('images/hero-bg.jpg') }}" alt="Hero background">
        <div class="absolute inset-0 bg-gray-900 mix-blend-multiply"></div>
    </div>
    <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">Welcome to matesTv</h1>
        <p class="mt-6 text-xl text-gray-300 max-w-3xl">
            Discover and stream the best independent movies from talented producers around the world.
        </p>
        <div class="mt-10">
            <a href="{{ route('movies.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                Browse Movies
            </a>
        </div>
    </div>
</div>

<!-- Featured Movies Section -->
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="text-center">
        <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
            Featured Movies
        </h2>
        <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
            Handpicked by our team of curators
        </p>
    </div>

    <div class="mt-12 grid gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($featuredMovies as $movie)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <!-- Movie Thumbnail -->
                <div class="relative">
                    <img src="{{ $movie->thumbnail_url }}" alt="{{ $movie->title }}" class="w-full h-64 object-cover">
                    <div class="absolute top-2 right-2 bg-yellow-400 text-yellow-800 text-xs font-bold px-2 py-1 rounded">
                        Featured
                    </div>
                </div>

                <!-- Movie Info -->
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $movie->title }}</h3>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $movie->description }}</p>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold text-indigo-600">${{ number_format($movie->price, 2) }}</span>
                        
                        @auth
                            @if(auth()->user()->hasPurchased($movie))
                                <a href="{{ route('library.show', $movie) }}" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                    Watch Now
                                </a>
                            @else
                                <form action="{{ route('purchases.store') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                                    <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                        Buy Now
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Buy Now
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-12 text-center">
        <a href="{{ route('movies.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
            View All Movies
        </a>
    </div>
</div>

<!-- How It Works Section -->
<div class="bg-gray-50">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                How It Works
            </h2>
            <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-500">
                Simple steps to start watching amazing content
            </p>
        </div>

        <div class="mt-12">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <div class="text-center">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white mx-auto">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-medium text-gray-900">Create an Account</h3>
                    <p class="mt-2 text-base text-gray-500">
                        Sign up for free and get access to our entire library of movies.
                    </p>
                </div>

                <div class="text-center">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white mx-auto">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-medium text-gray-900">Purchase Movies</h3>
                    <p class="mt-2 text-base text-gray-500">
                        Buy the movies you want to watch at affordable prices.
                    </p>
                </div>

                <div class="text-center">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white mx-auto">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-medium text-gray-900">Watch Anywhere</h3>
                    <p class="mt-2 text-base text-gray-500">
                        Stream your purchased movies on any device, anytime.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 