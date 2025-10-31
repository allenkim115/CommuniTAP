<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('My Redemptions') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('rewards.index') }}">
                    <x-secondary-button>
                        ← Back to Rewards
                    </x-secondary-button>
                </a>
            </div>
            @if(session('status'))
                <div class="mb-6 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            @forelse($redemptions as $r)
                <div class="mb-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="mb-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ $r->reward->sponsor_name }}</p>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ $r->reward->reward_name }}
                            </h3>
                        </div>

                        @if($r->admin_notes)
                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                <p class="text-lg font-mono font-bold text-gray-900 dark:text-gray-100 tracking-wider">
                                    {{ str_replace('Coupon: ', '', $r->admin_notes) }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <p class="text-gray-500 dark:text-gray-400 text-lg">No redemptions yet.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>


