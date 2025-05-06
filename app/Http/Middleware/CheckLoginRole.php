<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class CheckLoginRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $email = $request->input('email');
        $path = $request->path();

        if ($email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                if ($path === 'admin/login' && !$user->hasAnyRole(['admin', 'demo_admin'])) {
                    return back()->withErrors(['email' => 'You do not have admin privileges. Please use the correct login page.'])->onlyInput('email');
                }
                if ($path === 'producer/login' && !$user->hasRole('producer')) {
                    return back()->withErrors(['email' => 'You do not have producer privileges. Please use the correct login page.'])->onlyInput('email');
                }
                if ($path === 'login' && ($user->hasRole('producer') || $user->hasAnyRole(['admin', 'demo_admin']))) {
                    return back()->withErrors(['email' => 'Please use the correct login page for your account type.'])->onlyInput('email');
                }
            }
        }
        return $next($request);
    }
} 