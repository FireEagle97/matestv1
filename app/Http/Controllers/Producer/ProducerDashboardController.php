<?php

namespace App\Http\Controllers\Producer;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProducerDashboardController extends Controller
{
    /**
     * Show the producer dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $producer = Auth::user();
        
        // Get total content count
        $totalContent = Content::where('producer_id', $producer->id)->count();
        
        // Get total views across all content
        $totalViews = Content::where('producer_id', $producer->id)
            ->sum('views_count');
        
        // Get total downloads across all content
        $totalDownloads = Content::where('producer_id', $producer->id)
            ->sum('downloads_count');
        
        // Get recent content with pagination
        $contents = Content::where('producer_id', $producer->id)
            ->latest()
            ->paginate(10);
        
        // Get recent activities
        $recentActivities = Activity::where('user_id', $producer->id)
            ->latest()
            ->take(5)
            ->get();

        return view('producer.dashboard', compact(
            'totalContent',
            'totalViews',
            'totalDownloads',
            'contents',
            'recentActivities'
        ));
    }
} 