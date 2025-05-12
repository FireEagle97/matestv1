<?php

namespace App\Http\Controllers\Producer;

use App\Http\Controllers\Controller;
use Modules\Entertainment\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'entertainment'])
            ->whereHas('entertainment', function($query) {
                $query->where('producer_id', Auth::id());
            })
            ->latest()
            ->paginate(10);
            
        return view('producer.reviews.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        // Check if review belongs to this producer's content
        if (!$review->entertainment || $review->entertainment->producer_id !== Auth::id()) {
            abort(403);
        }

        $review->load(['user', 'entertainment']);
        return view('producer.reviews.show', compact('review'));
    }
} 