<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Rewards') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex justify-end">
                <a href="{{ route('rewards.mine') }}">
                    <x-primary-button>
                        View My Redemptions
                    </x-primary-button>
                </a>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('status'))
                        <div class="mb-4 text-green-600">{{ session('status') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 text-red-600">{{ session('error') }}</div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @forelse($rewards as $reward)
                            <div class="border dark:border-gray-700 rounded p-4">
                                @if($reward->image_path)
                                    <img src="{{ route('rewards.image', $reward) }}" alt="{{ $reward->reward_name }}" class="w-full h-40 object-cover rounded mb-3" />
                                @endif
                                <div class="text-lg font-semibold">{{ $reward->reward_name }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-300">Sponsor: {{ $reward->sponsor_name }}</div>
                                <p class="mt-2 text-sm">{{ $reward->description }}</p>
                                <div class="mt-3 text-sm">Points: <span class="font-medium">{{ $reward->points_cost }}</span></div>
                                <div class="text-sm">Qty left: <span class="font-medium">{{ $reward->QTY }}</span></div>
                                <form method="POST" action="{{ route('rewards.redeem', $reward) }}" class="mt-4">
                                    @csrf
                                    @if($reward->isAvailable())
                                        <x-primary-button>
                                            Redeem
                                        </x-primary-button>
                                    @else
                                        <x-primary-button disabled>
                                            Redeem
                                        </x-primary-button>
                                    @endif
                                </form>
                            </div>
                        @empty
                            <div>No rewards available right now.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


