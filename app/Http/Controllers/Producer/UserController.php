<?php

namespace App\Http\Controllers\Producer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereHas('subscriptions', function($query) {
            $query->where('producer_id', Auth::id());
        })->paginate(10);
        
        return view('producer.users.index', compact('users'));
    }

    public function show(User $user)
    {
        // Check if user belongs to this producer
        if (!$user->subscriptions()->where('producer_id', Auth::id())->exists()) {
            abort(403);
        }
        
        return view('producer.users.show', compact('user'));
    }
} 