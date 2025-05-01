@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Filters -->
    <div class="mb-8">
        <div class="flex flex-wrap items-center justify-between">
            <div class="flex space-x-4">
                <select id="genre-filter" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">All Genres</option>
                    <option value="action">Action</option>
                    <option value="comedy">Comedy</option>
                    <option value="drama">Drama</option>
                    <option value="horror">Horror</option>
                    <option value="romance">Romance</option>
                    <option value="sci-fi">Sci-Fi</option>
                    <option value="thriller">Thriller</option>
                </select>
                <select id="price-filter" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">All Prices</option>
                    <option value="0-10">$0 - $10</option>
                    <option value="10-20">$10 - $20</option>
                    <option value="20-50">$20 - $50</option>
                    <option value="50+">$50+</option>
                </select>
            </div>
            <div class="relative">
                <input type="text" id="search" placeholder="Search movies..." 
                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
        </div>
    </div>

    <!-- Movies Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($movies as $movie)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <!-- Movie Thumbnail -->
                <div class="relative">
                    <img src="{{ $movie->thumbnail_url }}" alt="{{ $movie->title }}" class="w-full h-48 object-cover">
                    @if($movie->featured)
                        <span class="absolute top-2 right-2 bg-yellow-400 text-yellow-800 text-xs font-bold px-2 py-1 rounded">
                            Featured
                        </span>
                    @endif
                </div>

                <!-- Movie Info -->
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $movie->title }}</h3>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $movie->description }}</p>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold text-indigo-600">${{ number_format($movie->price, 2) }}</span>
                        
                        @auth
                            @if(auth()->user()->hasPurchased($movie))
                                <a href="{{ route('movies.show', $movie) }}" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                    Watch Now
                                </a>
                            @else
                                <form action="{{ route('purchases.store') }}" method="POST">
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

    <!-- Pagination -->
    <div class="mt-8">
        {{ $movies->links() }}
    </div>
</div>
@endsection 