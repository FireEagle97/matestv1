<x-auth-layout>
    <x-slot name="title">
        @if(request()->is('producer/register'))
            @lang('Producer Registration')
        @else
            @lang('Register')
        @endif
    </x-slot>

    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 " />
            </a>
        </x-slot>

        <div class="text-center mb-4">
            <h2 class="font-bold text-2xl text-red-600">
                @if(request()->is('producer/register'))
                    {{ __('Producer Registration') }}
                @else
                    {{ __('Create your Account') }}
                @endif
            </h2>
            <p class="text-gray-400">
                @if(request()->is('producer/register'))
                    {{ __('Register as a producer to upload and manage your movies, view analytics, and request payouts.') }}
                @else
                    {{ __('Sign up to start streaming and enjoying content!') }}
                @endif
            </p>
        </div>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <!-- Social login -->
        <x-auth-social-login />

        <form method="POST" action="@if(request()->is('producer/register')){{ route('producer.register') }}@else{{ route('register') }}@endif">
            @csrf

            <!-- First Name -->
            <div class="mt-4">
                <x-label for="first_name" :value="__('First Name')" />
                <x-input id="first_name" type="text" name="first_name" :value="old('first_name')" required autofocus />
                <x-input-error :messages="$errors->get('first_name')" />
            </div>

            <!-- Last Name -->
            <div class="mt-4">
                <x-label for="last_name" :value="__('Last Name')" />
                <x-input id="last_name" type="text" name="last_name" :value="old('last_name')" required autofocus />
                <x-input-error :messages="$errors->get('last_name')" />
            </div>
            <!-- Mobile -->
            <div class="mt-4">
                <x-label for="mobile" :value="__('Contact No')" />
                <x-input id="mobile" type="number" name="mobile" required />
                <x-input-error :messages="$errors->get('mobile')" />
            </div>
            <!-- Email Address -->
            <div class="mt-4">
                <x-label for="email" :value="__('Email')" />
                <x-input id="email" type="email" name="email" :value="old('email')" required />
                <x-input-error :messages="$errors->get('email')" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />
                <x-input id="password" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-input id="password_confirmation" type="password" name="password_confirmation" required />
                <x-input-error :messages="$errors->get('password_confirmation')" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="ml-4 w-100">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
        <div class="d-none">
            <h6 class="text-center border-top py-3 mt-3">Demo Accounts</h6>
            <div class="parent">
                <select name="select"  id="SelectUser" class="form-control selectpiker" onchange="getSelectedOption()">
                    <option value="">Select Role</option>
                    <option value="12345678,admin@streamit.com">Admin</option>
                    <option value="12345678,demo@streamit.com" selected>Demo Admin</option>
                    <option value="secret,john@streamit.com">User</option>
                </select>
            </div>
        </div>
        @if(env('IS_DEMO'))
        <div>
            <h6 class="text-center border-top py-3 mt-3">Demo Accounts</h6>
            <div class="d-flex justify-content-between">
                <div>
                    <p class="mb-0" id="admin_email">admin@streamit.com</p>
                    <p id="admin_password">12345678</p>
                </div>
                <div>
                    <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-title="Click To Copy" onclick="setLoginCredentials('admin')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184" />
                        </svg>
                    </a>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <div>
                    <p class="mb-0" id="employee_email">john@streamit.com</p>
                    <p id="employee_password">secret</p>
                </div>
                <div>
                    <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-title="Click To Copy" onclick="setLoginCredentials('employee')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        @endif

        <x-slot name="extra">
            <span>
                @if(request()->is('producer/register'))
                    {{ __('Already registered as a producer?') }} <a href="{{ route('producer.login') }}">Login</a>.
                @else
                    {{ __('Already registered?') }} <a href="{{ route('login') }}">Login</a>.<br>
                    <a href="{{ route('producer.register') }}">Register as Producer</a>
                @endif
            </span>
        </x-slot>
    </x-auth-card>
</x-auth-layout>
