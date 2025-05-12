<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Device;


class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  array  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if (!Auth::check()) {
            if ($request->is('producer/*')) {
                return redirect()->route('producer.login');
            }
            if ($request->is('admin/*')) {
                return redirect()->route('admin.login');
            }
            return redirect()->route('login');
        }

        return parent::handle($request, $next, ...$guards);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            if ($request->is('producer/*')) {
                return route('producer.login');
            }
            if ($request->is('admin/*')) {
                return route('admin.login');
            }
            return route('login');
        }
    }
}
