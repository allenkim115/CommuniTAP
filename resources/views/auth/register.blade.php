<x-guest-layout>
    <!-- First User Notice -->
    @if(App\Models\User::count() === 0)
        <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-blue-600 dark:text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm text-blue-800 dark:text-blue-200">
                    <strong>First User Notice:</strong> The first user to register will automatically be assigned administrator privileges.
                </p>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" id="registerForm" class="max-w-3xl mx-auto">
        @csrf

        <!-- Logo inside container -->
        <div class="flex justify-center items-center mb-8">
            <img src="{{ asset('images/communitaplogo1.svg') }}" alt="CommuniTAP Logo" class="w-36 h-36" />
        </div>

        <!-- Heading -->
        <h2 class="text-2xl font-semibold text-center mb-6">{{ __('Sign up') }}</h2>

        <!-- Form Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- First Name -->
            <div>
                <x-input-label for="firstName" :value="__('First name')" />
                <x-text-input id="firstName" class="block mt-1 w-full" type="text" name="firstName" :value="old('firstName')" required autofocus autocomplete="given-name" placeholder="Enter first name" />
                <x-input-error :messages="$errors->get('firstName')" class="mt-2" />
            </div>

            <!-- Last Name -->
            <div>
                <x-input-label for="lastName" :value="__('Last name')" />
                <x-text-input id="lastName" class="block mt-1 w-full" type="text" name="lastName" :value="old('lastName')" required autocomplete="family-name" placeholder="Enter last name" />
                <x-input-error :messages="$errors->get('lastName')" class="mt-2" />
            </div>

            <!-- Address -->
            <div>
                <x-input-label for="address" :value="__('Address')" />
                <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" autocomplete="street-address" placeholder="Enter address" />
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Enter email" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" placeholder="Enter password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" placeholder="Enter confirm password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center justify-center mt-8 w-full">
            <x-primary-button class="justify-center">
                {{ __('Sign up') }}
            </x-primary-button>
        </div>

        <div class="mt-4 text-center text-sm text-gray-600">
        {{ __('Already have an account?') }}
            <a class="text-sm  text-orange-500 hover:text-orange-600 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                Login
            </a>
        </div>
    </form>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const formData = new FormData(this);
            console.log('Form data being submitted:');
            for (let [key, value] of formData.entries()) {
                if (key !== 'password' && key !== 'password_confirmation') {
                    console.log(key + ': ' + value);
                } else {
                    console.log(key + ': [HIDDEN]');
                }
            }
        });
    </script>
</x-guest-layout>
