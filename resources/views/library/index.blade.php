@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">My Library</h1>

            @if($purchases->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No movies purchased yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by browsing our collection of movies.</p>
                    <div class="mt-6">
                        <a href="{{ route('movies.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Browse Movies
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($purchases as $purchase)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                            <!-- Movie Thumbnail -->
                            <div class="relative">
                                <img src="{{ $purchase->movie->thumbnail_url }}" alt="{{ $purchase->movie->title }}" class="w-full h-48 object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300">
                                    <a href="{{ route('library.show', $purchase->movie) }}" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                        Watch Now
                                    </a>
                                </div>
                            </div>

                            <!-- Movie Info -->
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $purchase->movie->title }}</h3>
                                <p class="text-sm text-gray-600 mb-2">Purchased: {{ $purchase->created_at->format('M d, Y') }}</p>
                                <p class="text-sm text-gray-600">Price: ${{ number_format($purchase->price_paid, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $purchases->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- HLS Player Modal -->
<div id="playerModal" class="fixed inset-0 bg-black bg-opacity-75 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg w-full max-w-4xl">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle"></h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" onclick="closePlayer()">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <video id="videoPlayer" class="w-full" controls></video>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
<script>
    function openPlayer(movieId, title, hlsUrl) {
        const modal = document.getElementById('playerModal');
        const titleElement = document.getElementById('modalTitle');
        const video = document.getElementById('videoPlayer');
        
        titleElement.textContent = title;
        modal.classList.remove('hidden');
        
        if (Hls.isSupported()) {
            const hls = new Hls();
            hls.loadSource(hlsUrl);
            hls.attachMedia(video);
            hls.on(Hls.Events.MANIFEST_PARSED, function() {
                video.play();
            });
        } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
            video.src = hlsUrl;
            video.addEventListener('loadedmetadata', function() {
                video.play();
            });
        }
    }

    function closePlayer() {
        const modal = document.getElementById('playerModal');
        const video = document.getElementById('videoPlayer');
        
        video.pause();
        video.src = '';
        modal.classList.add('hidden');
    }
</script>
@endpush
@endsection 