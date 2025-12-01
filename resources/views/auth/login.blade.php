<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-2" :status="session('status')" />

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" id="loginForm" class="animate-fade-in"
          style="animation-delay: 0.1s;" novalidate>
        @csrf
        <!-- Logo (Centered, Floating) -->
        <div class="flex justify-center mb-8">
            <a href="{{ url('/') }}" class="logo-container cursor-pointer">
                <img 
                    src="{{ asset('images/communitaplogo1.svg') }}" 
                    alt="CommuniTap Logo" 
                    class="w-32 h-32 sm:w-40 sm:h-40 drop-shadow-lg"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                >
                <!-- Fallback -->
                <div style="display:none;" class="rounded-full w-40 h-40 flex items-center justify-center" style="background: linear-gradient(to bottom right, rgba(243, 162, 97, 0.8), rgba(43, 157, 141, 0.8));">
                    <span class="text-3xl font-bold text-white">CT</span>
                </div>
            </a>
        </div>
        <!-- Responsive Form Container -->
        <div class="w-full max-w-md sm:max-w-lg lg:max-w-xl mx-auto space-y-6">

            <!-- Email -->
            <div class="input-group">
                <x-text-input 
                    id="email" 
                    type="email" 
                    name="email" 
                    :value="old('email')"
                    required 
                    autofocus 
                    autocomplete="username" 
                    placeholder=" " 
                    class="w-full h-14" 
                />
                <label for="email">{{ __('Email') }}</label>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-xs" />
            </div>

            <!-- Password -->
            <div class="input-group">
                <x-text-input 
                    id="password" 
                    type="password" 
                    name="password"
                    required 
                    autocomplete="current-password" 
                    placeholder=" " 
                    class="w-full h-14" 
                />
                <label for="password">{{ __('Password') }}</label>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-xs" />
            </div>

            <!-- Forgot Password -->
            @if (Route::has('password.request'))
                <div class="text-right">
                    <a href="{{ route('password.request') }}" 
                       class="text-sm font-medium text-primary hover:text-orange-600 underline-offset-4 hover:underline transition">
                        {{ __('Forgot Password?') }}
                    </a>
                </div>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="flex justify-center mt-10">
            <button type="submit" class="btn-primary flex items-center gap-1.5" style="padding: 0.875rem 1.5rem; min-width: auto;">
                {{ __('Login') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </button>
        </div>

        <!-- Register Link -->
        <p class="mt-6 text-center text-sm text-gray-600">
            {{ __("Don't have an account?") }}
            <a href="{{ route('register') }}" 
               class="font-semibold text-primary hover:text-orange-600 underline-offset-4 hover:underline transition">
                {{ __('Sign up') }}
            </a>
        </p>
    </form>
</x-guest-layout>   