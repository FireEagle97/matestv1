<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\Trait\AuthTrait;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Auth;
use Illuminate\Http\Request;

class RegisteredUserController extends Controller
{
    use AuthTrait;

    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        return view('frontend::auth.registration');
    }

    /**
     * Handle an incoming registration request.
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        // Determine user type based on route
        $userType = $request->is('producer/register') ? 'producer' : 'user';
        
        // Add user_type to request for AuthTrait
        $request->merge(['user_type' => $userType]);
        
        $user = $this->registerTrait($request);

        $user = Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
