<x-guest-layout>
    <!-- First User Admin Notice -->
    @if(App\Models\User::count() === 0)
        <div class="mb-6 p-5 bg-white/80 backdrop-blur-sm rounded-2xl shadow-sm animate-fade-in" style="border-color: #F3A261; border-width: 1px;">
            <div class="flex items-center gap-3">
                <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #F3A261;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm font-medium" style="color: #F3A261;">
                    <strong>First User:</strong> You'll become the <span class="text-primary font-bold">Administrator</span>.
                </p>
            </div>
        </div>
    @endif

    <!-- Registration Form -->
    <form method="POST" action="{{ route('register') }}" id="registerForm" class="animate-fade-in"
          style="animation-delay: 0.1s;" novalidate>
        @csrf

        <!-- Logo -->
        <div class="flex justify-center mb-8">
            <a href="{{ url('/') }}" class="logo-container cursor-pointer">
                <img 
                    src="{{ asset('images/communitaplogo1.svg') }}" 
                    alt="CommuniTap Logo" 
                    class="w-32 h-32 sm:w-40 sm:h-40 drop-shadow-lg"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                >
                <div style="display:none;" class="rounded-full w-40 h-40 flex items-center justify-center" style="background: linear-gradient(to bottom right, rgba(243, 162, 97, 0.8), rgba(43, 157, 141, 0.8));">
                    <span class="text-3xl font-bold text-white">CT</span>
                </div>
            </a>
        </div>

        <!-- Heading -->
        <h2 class="text-3xl sm:text-4xl font-bold text-center text-gray-800 mb-10">
            Create Your Account
        </h2>

        <!-- Form Container -->
        <div class="w-full max-w-md sm:max-w-lg lg:max-w-xl mx-auto space-y-6">

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
                    class="w-full border-none rounded-none shadow-none focus:ring-0 focus:ring-offset-0" 
                />
                <label for="firstName">{{ __('First name') }}</label>
                <x-input-error :messages="$errors->get('firstName')" class="mt-2 text-red-500 text-xs" />
            </div>

            <!-- Middle Name (Optional) -->
            <div class="input-group">
                <x-text-input 
                    id="middleName" 
                    type="text" 
                    name="middleName" 
                    :value="old('middleName')"
                    autocomplete="additional-name" 
                    placeholder=" " 
                    class="w-full border-none rounded-none shadow-none focus:ring-0 focus:ring-offset-0" 
                />
                <label for="middleName">{{ __('Middle Name') }}</label>
                <x-input-error :messages="$errors->get('middleName')" class="mt-2 text-red-500 text-xs" />
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
                    class="w-full border-none rounded-none shadow-none focus:ring-0 focus:ring-offset-0" 
                />
                <label for="lastName">{{ __('Last name') }}</label>
                <x-input-error :messages="$errors->get('lastName')" class="mt-2 text-red-500 text-xs" />
            </div>

            <!-- Sitio Dropdown (FIXED) -->
            <div class="input-group">
                <select 
                    id="sitio" 
                    name="sitio" 
                    class="w-full px-4 pt-5 pb-2 bg-transparent border-b-2 border-gray-300 text-gray-800 text-base font-medium focus:border-primary focus:outline-none transition-all duration-300 appearance-none cursor-pointer"
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
                    class="w-full border-none rounded-none shadow-none focus:ring-0 focus:ring-offset-0" 
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
                        class="w-full border-none rounded-none shadow-none focus:ring-0 focus:ring-offset-0" 
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
                        class="w-full border-none rounded-none shadow-none focus:ring-0 focus:ring-offset-0" 
                    />
                    <label for="password_confirmation">{{ __('Confirm Password') }}</label>
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

    <script>
        // Ensure middleName field animations work exactly like other inputs
        (function() {
            function setupMiddleName() {
                const input = document.getElementById('middleName');
                if (!input) {
                    requestAnimationFrame(setupMiddleName);
                    return;
                }
                
                const group = input.closest('.input-group');
                const label = group?.querySelector('label[for="middleName"]');
                
                if (!group || !label) {
                    requestAnimationFrame(setupMiddleName);
                    return;
                }
                
                // Ensure label has transition
                label.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                
                function updateState() {
                    const hasValue = input.value && input.value.trim() !== '';
                    const isFocused = document.activeElement === input;
                    
                    // Clear any existing classes first
                    group.classList.remove('has-value');
                    input.classList.remove('has-value');
                    
                    // Only add classes if there's a value or it's focused
                    if (hasValue || isFocused) {
                        group.classList.add('has-value');
                        input.classList.add('has-value');
                    }
                }
                
                // Set initial state - ensure it's cleared first if empty
                group.classList.remove('has-value');
                input.classList.remove('has-value');
                updateState();
                
                // Add event listeners (use capture to ensure they run)
                ['focus', 'blur', 'input'].forEach(eventType => {
                    input.addEventListener(eventType, function(e) {
                        updateState();
                    }, { capture: true, passive: true });
                });
                
                // Handle paste
                input.addEventListener('paste', function() {
                    setTimeout(updateState, 10);
                }, { capture: true, passive: true });
            }
            
            // Try multiple times to ensure it runs
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', setupMiddleName);
            } else {
                setupMiddleName();
            }
            setTimeout(setupMiddleName, 100);
            setTimeout(setupMiddleName, 300);
        })();
    </script>
</x-guest-layout>