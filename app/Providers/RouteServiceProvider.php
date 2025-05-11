<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const USER_LOGIN_REDIRECT = '/';
    public const ADMIN_LOGIN_REDIRECT = '/admin/dashboard';
    public const PRODUCER_LOGIN_REDIRECT = '/producer/dashboard';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web','checkInstallation')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Get the redirect path based on user type
     *
     * @return string
     */
    public static function getRedirectPath()
    {
        if (Auth::check()) {
            if (Auth::user()->hasRole('producer')) {
                return self::PRODUCER_LOGIN_REDIRECT;
            }
            if (Auth::user()->hasAnyRole(['admin', 'demo_admin'])) {
                return self::ADMIN_LOGIN_REDIRECT;
            }
            return self::USER_LOGIN_REDIRECT;
        }

        // If user is not authenticated, check the previous URL
        if (request()->is('producer/*')) {
            return route('producer.login');
        }
        if (request()->is('admin/*')) {
            return route('admin.login');
        }
        return route('login');
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('global', function ($request) {
            return Limit::perMinute(100)->by($request->user()?->id ?: $request->ip());
        });
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(100)->by($request->user()?->id ?: $request->ip());
        });
    }

    public function map()
    {
        $this->mapWebRoutes();
        $this->mapAccountsRoutes();
    }

    protected function mapAccountsRoutes()
    {
        Route::middleware('web')
            ->namespace('Modules\Subscriptions\Http\Controllers')
            ->group(base_path('app/Modules/Subscriptions/routes/web.php'));
    }
}
