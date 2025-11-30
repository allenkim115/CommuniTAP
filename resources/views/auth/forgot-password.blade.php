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
    <h2 class="text-3xl sm:text-4xl font-bold text-center text-gray-800 mb-6">
        {{ __('Forgot Password?') }}
    </h2>

    <!-- Message -->
    <div class="mb-6 text-sm text-gray-600 text-center max-w-md mx-auto">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Form -->
    <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm" class="animate-fade-in"
          style="animation-delay: 0.1s;">
        @csrf

        <!-- Form Container -->
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
        </div>

        <!-- Submit Button -->
        <div class="flex justify-center mt-10">
            <button type="submit" class="btn-primary flex items-center gap-1.5" style="padding: 0.875rem 1.5rem; min-width: auto;">
                {{ __('Email Password Reset Link') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
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
