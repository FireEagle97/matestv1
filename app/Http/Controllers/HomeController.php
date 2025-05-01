<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Video\Models\Video;
use Modules\Video\Transformers\VideoResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the application home page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        try {
            // Get featured videos with eager loading
            $featuredVideos = Video::with(['VideoStreamContentMappings', 'plan'])
                ->where('status', 1)
                ->where('access', 'free')
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();

            return view('frontend::index', [
                'featuredVideos' => VideoResource::collection($featuredVideos),
                'user_id' => Auth::id()
            ]);
        } catch (\Exception $e) {
            Log::error('Error in HomeController: ' . $e->getMessage());
            // Return a default view with empty featured videos in case of error
            return view('frontend::index', [
                'featuredVideos' => collect(),
                'user_id' => Auth::id()
            ]);
        }
    }
} 