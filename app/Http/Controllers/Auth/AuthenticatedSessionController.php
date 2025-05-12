<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\Trait\AuthTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

class AuthenticatedSessionController extends Controller
{
    use AuthTrait;

    /**
     * Display the user/producer login view.
     *
     * @return \Illuminate\View\View
     */
    public function showUserLogin()
    {
        return view('frontend::auth.login');
    }

    /**
     * Display the admin login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            
            // Attempt authentication
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                
                // Check if user exists and is active
                if ($user && $user->status == 1) {
                    $request->session()->regenerate();
                    
                    // Clear various caches
                    Artisan::call('cache:clear');
                    Artisan::call('config:clear');
                    Artisan::call('view:clear');
                    Artisan::call('config:cache');
                    Artisan::call('route:clear');

                    // Redirect based on user role
                    if ($user->hasRole('producer')) {
                        return redirect()->intended(route('producer.dashboard'));
                    }
                    if ($user->hasAnyRole(['admin', 'demo_admin'])) {
                        return redirect()->intended('/admin/dashboard');
                    }
                    return redirect()->intended('/');
                }
                
                // If user is inactive, logout and return with error
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is inactive.',
                ])->onlyInput('email');
            }

            // If authentication failed
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Login Error: ' . $e->getMessage());
            
            return back()->withErrors([
                'email' => 'An error occurred during login. Please try again.',
            ])->onlyInput('email');
        }
    }

    /**
     * Destroy an authenticated session.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();
        $isProducer = $user && $user->hasRole('producer');

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Check if the request is coming from admin routes
        if ($request->is('admin/*') || $request->is('app/*')) {
            return redirect()->route('admin.login');
        }

        // Redirect producers to producer login page
        if ($isProducer) {
            return redirect()->route('producer.login');
        }

        return redirect('/login');
    }
}
