<x-guest-layout>
    <!-- First User Admin Notice -->
    @if(App\Models\User::count() === 0)
        <div class="mb-6 p-5 bg-white/80 backdrop-blur-sm border border-orange-200 rounded-2xl shadow-sm animate-fade-in">
            <div class="flex items-center gap-3">
                <svg class="h-6 w-6 text-orange-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm font-medium text-orange-800">
                    <strong>First User:</strong> You’ll become the <span class="text-primary font-bold">Administrator</span>.
                </p>
            </div>
        </div>
    @endif

    <!-- Registration Form -->
    <form method="POST" action="{{ route('register') }}" id="registerForm" class="animate-fade-in"
          style="animation-delay: 0.1s;">
        @csrf

        <!-- Logo -->
        <div class="flex justify-center mb-8">
            <div class="logo-container">
                <img 
                    src="{{ asset('images/communitaplogo1.svg') }}" 
                    alt="CommuniTap Logo" 
                    class="w-32 h-32 sm:w-40 sm:h-40 drop-shadow-lg"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                >
                <div style="display:none;" class="bg-gradient-to-br from-orange-200 to-teal-200 rounded-full w-40 h-40 flex items-center justify-center">
                    <span class="text-3xl font-bold text-white">CT</span>
                </div>
            </div>
        </div>

        <!-- Heading -->
        <h2 class="text-3xl sm:text-4xl font-bold text-center text-gray-800 mb-10">
            Create Your Account
        </h2>

        <!-- Form Container -->
        <div class="w-full max-w-md sm:max-w-lg lg:max-w-xl mx-auto space-y-6">

            <!-- First & Last Name -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <!-- First Name -->
                <div class="input-group">
                    <x-text-input 
                        id="firstName" 
                        type="text" 
                        name="firstName" 
                        :value="old('firstName')"
                        required 
                        autofocus 
                        autocomplete="given-name" 
                        placeholder=" " 
                        class="w-full h-14" 
                    />
                    <label for="firstName">{{ __('First name') }}</label>
                    <x-input-error :messages="$errors->get('firstName')" class="mt-2 text-red-500 text-xs" />
                </div>

                <!-- Last Name -->
                <div class="input-group">
                    <x-text-input 
                        id="lastName" 
                        type="text" 
                        name="lastName" 
                        :value="old('lastName')"
                        required 
                        autocomplete="family-name" 
                        placeholder=" " 
                        class="w-full h-14" 
                    />
                    <label for="lastName">{{ __('Last name') }}</label>
                    <x-input-error :messages="$errors->get('lastName')" class="mt-2 text-red-500 text-xs" />
                </div>
            </div>

            <!-- Sitio Dropdown (FIXED) -->
            <div class="input-group">
                <select 
                    id="sitio" 
                    name="sitio" 
                    class="w-full h-14 px-4 pt-5 pb-2 bg-transparent border-b-2 border-gray-300 text-gray-800 text-base font-medium focus:border-primary focus:outline-none transition-all duration-300 appearance-none cursor-pointer"
                    required
                >
                    <option value="" disabled selected hidden>Select a sitio</option>
                    <option value="Pig Vendor">Pig Vendor</option>
                    <option value="Ermita Proper">Ermita Proper</option>
                    <option value="Kastilaan">Kastilaan</option>
                    <option value="Sitio Bato">Sitio Bato</option>
                    <option value="YHC">YHC</option>
                    <option value="Eyeseekers">Eyeseekers</option>
                    <option value="Panagdait">Panagdait</option>
                    <option value="Kawit">Kawit</option>
                </select>
                <label for="sitio" class="pointer-events-none">
                    {{ __('Sitio') }}
                </label>
                <x-input-error :messages="$errors->get('sitio')" class="mt-2 text-red-500 text-xs" />
            </div>

            <!-- Email -->
            <div class="input-group">
                <x-text-input 
                    id="email" 
                    type="email" 
                    name="email" 
                    :value="old('email')"
                    required 
                    autocomplete="username" 
                    placeholder=" " 
                    class="w-full h-14" 
                />
                <label for="email">{{ __('Email') }}</label>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-xs" />
            </div>

            <!-- Password & Confirm -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
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
                    <label for="password_confirmation">{{ __('Confirm') }}</label>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500 text-xs" />
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex justify-center mt-10">
            <button type="submit" class="btn-primary flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 13l4 4L19 7"></path>
                </svg>
                {{ __('Sign Up') }}
            </button>
        </div>

        <!-- Login Link -->
        <p class="mt-6 text-center text-sm text-gray-600">
            {{ __('Already have an account?') }}
            <a href="{{ route('login') }}" 
               class="font-semibold text-primary hover:text-orange-600 underline-offset-4 hover:underline transition">
                {{ __('Log in') }}
            </a>
        </p>
    </form>
</x-guest-layout>