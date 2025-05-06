<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user && $user->status == 1 && $user->hasAnyRole(['admin', 'demo_admin'])) {
                $request->session()->regenerate();
                Artisan::call('cache:clear');
                Artisan::call('config:clear');
                Artisan::call('view:clear');
                Artisan::call('config:cache');
                Artisan::call('route:clear');
                return redirect()->intended('/admin/dashboard');
            }
            Auth::logout();
            return back()->withErrors(['email' => 'You do not have admin privileges.'])->onlyInput('email');
        }
        return back()->withErrors(['email' => 'The provided credentials do not match our records.'])->onlyInput('email');
    }
} 