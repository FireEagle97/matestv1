@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <!-- Movie Header -->
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Movie Poster -->
                <div class="w-full md:w-1/3">
                    <img src="{{ $movie->thumbnail_url }}" alt="{{ $movie->title }}" class="w-full rounded-lg shadow-md">
                </div>

                <!-- Movie Info -->
                <div class="w-full md:w-2/3">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $movie->title }}</h1>
                    
                    <div class="flex items-center space-x-4 mb-4">
                        <span class="text-sm text-gray-600">Genre: {{ ucfirst($movie->genre) }}</span>
                        <span class="text-sm text-gray-600">Released: {{ $movie->created_at->format('Y') }}</span>
                        @if($movie->featured)
                            <span class="bg-yellow-400 text-yellow-800 text-xs font-bold px-2 py-1 rounded">
                                Featured
                            </span>
                        @endif
                    </div>

                    <div class="prose max-w-none mb-6">
                        <p class="text-gray-700">{{ $movie->description }}</p>
                    </div>

                    <!-- Purchase Section -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-2xl font-bold text-indigo-600">${{ number_format($movie->price, 2) }}</span>
                            
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

                        <div class="text-sm text-gray-600">
                            <p class="mb-2">• Instant access after purchase</p>
                            <p class="mb-2">• Watch on any device</p>
                            <p class="mb-2">• Download for offline viewing</p>
                            <p>• 30-day money-back guarantee</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Movie Details -->
            <div class="mt-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Movie Details</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Video Quality -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Video Quality</h3>
                        <p class="text-gray-600">Available in 4K, 1080p, and 720p</p>
                    </div>

                    <!-- Audio Tracks -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Audio Tracks</h3>
                        <ul class="list-disc list-inside text-gray-600">
                            @foreach($movie->audioTracks as $track)
                                <li>{{ $track->language }} ({{ $track->format }})</li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Subtitles -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Subtitles</h3>
                        <ul class="list-disc list-inside text-gray-600">
                            @foreach($movie->subtitles as $subtitle)
                                <li>{{ $subtitle->language }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Producer Info -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Producer</h3>
                        <div class="flex items-center">
                            <img src="{{ $movie->producer->profile_url }}" alt="{{ $movie->producer->name }}" class="w-10 h-10 rounded-full mr-3">
                            <div>
                                <p class="text-gray-900 font-medium">{{ $movie->producer->name }}</p>
                                <p class="text-sm text-gray-600">{{ $movie->producer->bio }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 