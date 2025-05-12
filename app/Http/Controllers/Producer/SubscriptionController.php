<?php

namespace App\Http\Controllers\Producer;

use App\Http\Controllers\Controller;
use Modules\Subscriptions\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['user', 'plan'])
            ->where('producer_id', Auth::id())
            ->latest()
            ->paginate(10);
            
        return view('producer.subscriptions.index', compact('subscriptions'));
    }

    public function show(Subscription $subscription)
    {
        // Check if subscription belongs to this producer
        if ($subscription->producer_id !== Auth::id()) {
            abort(403);
        }

        $subscription->load(['user', 'plan']);
        return view('producer.subscriptions.show', compact('subscription'));
    }
} 