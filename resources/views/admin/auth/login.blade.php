<x-auth-layout>
  <x-slot name="title">
    @lang('Admin Login')
  </x-slot>

  <x-auth-card>
    <x-slot name="logo">
      <a>
        <x-application-logo />
      </a>
    </x-slot>

    <div class="text-center mb-4">
      <h2 class="font-bold text-2xl text-red-600">
        {{ __('Admin Login') }}
      </h2>
      <p class="text-gray-400">
        {{ __('Sign in to manage the platform.') }}
      </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />
    <x-auth-social-login />
    <x-auth-validation-errors class="mb-4" :errors="$errors" />

    <form method="POST" action="{{ route('admin.login.submit') }}">
      @csrf
      <div>
        <x-label for="email" :value="__('frontend.email')" />
        <x-input id="email" type="email" name="email" placeholder="{{ __('frontend.enter_email') }}" :value="old('email')" required autofocus />
        <x-input-error :messages="$errors->get('email')" />
      </div>
      <div class="mt-4">
        <x-label for="password" :value="__('frontend.password')" />
        <x-input id="password" type="password" name="password" placeholder="{{ __('messages.enter_password') }}" required autocomplete="current-password" />
        <x-input-error :messages="$errors->get('password')" />
      </div>
      <div class="mt-4">
        <label for="remember_me" class="d-inline-flex">
          <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
          <span class="ms-2">{{ __('frontend.remember_me') }}</span>
        </label>
      </div>
      <div class="d-flex align-items-center justify-content-between mt-4">
        <button type="submit" id="submit-btn" onclick="disableButton()" class="btn btn-primary">
            {{ __('frontend.login') }}
        </button>
      </div>
    </form>
  </x-auth-card>
  <script>
    function disableButton(){
      document.getElementById('submit-btn').classList.add('disabled');
      document.getElementById('submit-btn').innerText = 'Login...';
    }
  </script>
</x-auth-layout> 