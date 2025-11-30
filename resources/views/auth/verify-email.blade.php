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
        {{ __('Verify Your Email') }}
    </h2>

    <!-- Message -->
    <div class="mb-6 text-sm text-gray-600 text-center max-w-md mx-auto">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700 text-center max-w-md mx-auto">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <!-- Actions -->
    <div class="w-full max-w-md sm:max-w-lg lg:max-w-xl mx-auto space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <div class="flex justify-center">
                <button type="submit" class="btn-primary flex items-center gap-1.5" style="padding: 0.875rem 1.5rem; min-width: auto;">
                    {{ __('Resend Verification Email') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <div class="flex justify-center">
                <button type="submit" class="text-sm font-medium text-gray-600 hover:text-primary underline-offset-4 hover:underline transition">
                    {{ __('Log Out') }}
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
