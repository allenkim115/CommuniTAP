<x-guest-layout>
    <style>
        body {
            background: linear-gradient(135deg, #F1F1F1 0%, #F4A261 48%, #2A9D8F 96%) !important;
        }
        .min-h-screen {
            background: linear-gradient(135deg, #F1F1F1 0%, #F4A261 48%, #2A9D8F 96%) !important;
        }
        .bg-white {
            border-radius: 25px !important;
        }
    </style>
    
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="flex justify-center items-center mb-2">
        <img src="{{ asset('images/communitaplogo1.svg') }}" alt="CommuniTAP Logo" class="w-36 h-36" />
    </div>
    <h1 class="text-2xl font-semibold text-center mt-2">Login</h1>

    <form method="POST" action="{{ route('login') }}" class="mt-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')"/>
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" placeholder="Enter email" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            placeholder="Enter password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        @if (Route::has('password.request'))
            <div class="mt-2 text-right">
                <a class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot Password?') }}
                </a>
            </div>
        @endif

        <div class="mt-6 flex justify-center">
            <x-primary-button class="w-auto">
                {{ __('Login') }}
            </x-primary-button>
        </div>
    </form>

    <p class="mt-6 text-center text-sm text-gray-600">
        {{ "Don't have account?" }}
        <a href="{{ route('register') }}" class="font-medium text-orange-500 hover:text-orange-600">Sign up</a>
    </p>
</x-guest-layout>
