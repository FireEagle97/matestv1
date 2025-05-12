<?php

namespace App\Http\Controllers\Producer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Modules\Subscriptions\Models\Subscription;
use Modules\Entertainment\Models\Review;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $producer = Auth::user();
        
        $data = [
            // Content Overview
            'totalContent' => DB::table('entertainments')
                ->where('created_by', $producer->id)
                ->count(),
            
            'totalViews' => DB::table('entertainment_views')
                ->join('entertainments', 'entertainments.id', '=', 'entertainment_views.entertainment_id')
                ->where('entertainments.created_by', $producer->id)
                ->count(),
            
            'totalLikes' => DB::table('likes')
                ->join('entertainments', 'entertainments.id', '=', 'likes.entertainment_id')
                ->where('entertainments.created_by', $producer->id)
                ->where('is_like', 1)
                ->count(),
            
            'totalReviews' => DB::table('reviews')
                ->join('entertainments', 'entertainments.id', '=', 'reviews.entertainment_id')
                ->where('entertainments.created_by', $producer->id)
                ->count(),
            
            'averageRating' => DB::table('reviews')
                ->join('entertainments', 'entertainments.id', '=', 'reviews.entertainment_id')
                ->where('entertainments.created_by', $producer->id)
                ->avg('rating'),
            
            // Revenue Metrics - Based on plan subscriptions
            'totalRevenue' => DB::table('subscriptions')
                ->join('entertainments', 'entertainments.plan_id', '=', 'subscriptions.plan_id')
                ->where('entertainments.created_by', $producer->id)
                ->where('subscriptions.status', 'active')
                ->sum('subscriptions.amount'),
            
            // Subscriber Metrics - Based on plan subscriptions
            'totalSubscribers' => DB::table('subscriptions')
                ->join('entertainments', 'entertainments.plan_id', '=', 'subscriptions.plan_id')
                ->where('entertainments.created_by', $producer->id)
                ->where('subscriptions.status', 'active')
                ->distinct('subscriptions.user_id')
                ->count('subscriptions.user_id'),
        ];

        return view('producer.dashboard.index', $data);
    }

    public function getGenreChartData()
    {
        $producer = Auth::user();
        
        // Get top 5 genres with their counts and views for this producer's content
        $genreData = DB::table('entertainments')
            ->select(
                'genre',
                DB::raw('count(*) as total_content'),
                DB::raw('(SELECT COUNT(*) FROM entertainment_views WHERE entertainment_id IN 
                    (SELECT id FROM entertainments WHERE created_by = ? AND genre = entertainments.genre)) as total_views')
            )
            ->where('created_by', $producer->id)
            ->whereNotNull('genre')
            ->groupBy('genre')
            ->orderBy('total_views', 'desc')
            ->limit(5)
            ->setBindings([$producer->id])
            ->get();

        $chartData = $genreData->pluck('total_views')->toArray();
        $category = $genreData->pluck('genre')->toArray();

        return response()->json([
            'status' => true,
            'data' => [
                'chartData' => $chartData,
                'category' => $category
            ]
        ]);
    }

    public function getRevenueChartData($type)
    {
        $producer = Auth::user();
        
        $query = DB::table('subscriptions')
            ->join('entertainments', 'entertainments.plan_id', '=', 'subscriptions.plan_id')
            ->select(
                DB::raw('SUM(subscriptions.amount) as total_amount'),
                DB::raw('DATE(subscriptions.created_at) as date'),
                DB::raw('entertainments.type as content_type')
            )
            ->where('entertainments.created_by', $producer->id)
            ->where('subscriptions.status', 'active')
            ->groupBy('date', 'content_type');

        switch ($type) {
            case 'Week':
                $query->whereBetween('subscriptions.created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
                break;

            case 'Month':
                $query->whereBetween('subscriptions.created_at', [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth()
                ]);
                break;

            case 'Year':
            default:
                $query->whereBetween('subscriptions.created_at', [
                    Carbon::now()->startOfYear(),
                    Carbon::now()->endOfYear()
                ])
                ->groupBy(DB::raw('MONTH(subscriptions.created_at)'), 'content_type');
                break;
        }

        $revenueData = $query->get();

        // Process data for chart
        $chartData = [];
        $categories = [];
        
        foreach ($revenueData as $item) {
            $date = $type === 'Year' 
                ? Carbon::create()->month($item->date)->format('M')
                : Carbon::parse($item->date)->format('d M');
                
            if (!isset($chartData[$date])) {
                $chartData[$date] = [];
                $categories[] = $date;
            }
            
            $chartData[$date][$item->content_type] = $item->total_amount;
        }

        return response()->json([
            'status' => true,
            'data' => [
                'chartData' => $chartData,
                'category' => array_unique($categories)
            ]
        ]);
    }

    public function getSubscriberChartData($type)
    {
        $producer = Auth::user();
        
        $query = DB::table('subscriptions')
            ->join('entertainments', 'entertainments.plan_id', '=', 'subscriptions.plan_id')
            ->select(
                DB::raw('COUNT(DISTINCT subscriptions.user_id) as total'),
                DB::raw('DATE(subscriptions.created_at) as date'),
                DB::raw('entertainments.type as content_type')
            )
            ->where('entertainments.created_by', $producer->id)
            ->where('subscriptions.status', 'active')
            ->groupBy('date', 'content_type');

        switch ($type) {
            case 'Week':
                $query->whereBetween('subscriptions.created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
                break;

            case 'Month':
                $query->whereBetween('subscriptions.created_at', [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth()
                ]);
                break;

            case 'Year':
            default:
                $query->whereBetween('subscriptions.created_at', [
                    Carbon::now()->startOfYear(),
                    Carbon::now()->endOfYear()
                ])
                ->groupBy(DB::raw('MONTH(subscriptions.created_at)'), 'content_type');
                break;
        }

        $subscriberData = $query->get();

        // Process data for chart
        $chartData = [];
        $categories = [];
        
        foreach ($subscriberData as $item) {
            $date = $type === 'Year' 
                ? Carbon::create()->month($item->date)->format('M')
                : Carbon::parse($item->date)->format('d M');
                
            if (!isset($chartData[$date])) {
                $chartData[$date] = [];
                $categories[] = $date;
            }
            
            $chartData[$date][$item->content_type] = $item->total;
        }

        return response()->json([
            'status' => true,
            'data' => [
                'chartData' => $chartData,
                'category' => array_unique($categories)
            ]
        ]);
    }
} 