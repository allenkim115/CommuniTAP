<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Rewards') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('status'))
                        <div class="mb-4 text-green-600">{{ session('status') }}</div>
                    @endif

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Manage Rewards</h3>
                        <a href="{{ route('admin.rewards.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">Create Reward</a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @forelse($rewards as $reward)
                            <div class="border dark:border-gray-700 rounded p-4 @if($reward->QTY === 0) border-red-700 @endif">
                                @if($reward->image_path)
                                    <img src="{{ route('rewards.image', $reward) }}" alt="{{ $reward->reward_name }}" class="w-full h-40 object-cover rounded mb-3" />
                                @endif
                                <div class="text-lg font-semibold">{{ $reward->reward_name }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-300">Sponsor: {{ $reward->sponsor_name }}</div>
                                <p class="mt-2 text-sm">{{ $reward->description }}</p>
                                <div class="mt-3 text-sm">Points: <span class="font-medium">{{ $reward->points_cost }}</span></div>
                                <div class="text-sm flex items-center gap-2">
                                    <span>Qty left:</span>
                                    <span class="font-medium">{{ $reward->QTY }}</span>
                                    @if($reward->QTY === 0)
                                        <span class="px-2 py-0.5 text-xs rounded bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300">Out of stock</span>
                                    @endif
                                </div>
                                <div class="mt-4 flex gap-2">
                                    <a href="{{ route('admin.rewards.edit', $reward) }}" class="px-3 py-2 bg-gray-700 text-white rounded">Edit</a>
                                    <form action="{{ route('admin.rewards.destroy', $reward) }}" method="POST" onsubmit="return confirm('Delete this reward?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-2 bg-red-600 text-white rounded">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div>No rewards yet. Click Create Reward to add one.</div>
                        @endforelse
                    </div>

                    @if(method_exists($rewards, 'links'))
                        <div class="mt-6">
                            {{ $rewards->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>


