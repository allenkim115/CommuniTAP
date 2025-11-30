<x-guest-layout>
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

    <!-- Heading -->
    <h2 class="text-3xl sm:text-4xl font-bold text-center text-gray-800 mb-10">
        {{ __('Reset Password') }}
    </h2>

    <!-- Form -->
    <form method="POST" action="{{ route('password.store') }}" id="resetPasswordForm" class="animate-fade-in"
          style="animation-delay: 0.1s;">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Form Container -->
        <div class="w-full max-w-md sm:max-w-lg lg:max-w-xl mx-auto space-y-6">
            <!-- Email Address -->
            <div class="input-group">
                <x-text-input 
                    id="email" 
                    type="email" 
                    name="email" 
                    :value="old('email', $request->email)"
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
                    autocomplete="new-password" 
                    placeholder=" " 
                    class="w-full h-14" 
                />
                <label for="password">{{ __('Password') }}</label>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-xs" />
            </div>

            <!-- Confirm Password -->
            <div class="input-group">
                <x-text-input 
                    id="password_confirmation" 
                    type="password" 
                    name="password_confirmation"
                    required 
                    autocomplete="new-password" 
                    placeholder=" " 
                    class="w-full h-14" 
                />
                <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500 text-xs" />
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-center mt-10">
            <button type="submit" class="btn-primary flex items-center gap-1.5" style="padding: 0.875rem 1.5rem; min-width: auto;">
                {{ __('Reset Password') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </button>
        </div>
    </form>

    <!-- Back to Login Link -->
    <p class="mt-6 text-center text-sm text-gray-600">
        <a href="{{ route('login') }}" 
           class="font-semibold text-primary hover:text-orange-600 underline-offset-4 hover:underline transition">
            {{ __('Back to Login') }}
        </a>
    </p>
</x-guest-layout>
