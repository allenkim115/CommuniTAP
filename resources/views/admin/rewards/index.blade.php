<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Rewards') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-6 text-gray-900">
                    @if(session('status'))
                        <div class="mb-4 text-green-600">{{ session('status') }}</div>
                    @endif

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Manage Rewards</h3>
                        <a href="{{ route('admin.rewards.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-plus"></i>
                            Create Reward
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @forelse($rewards as $reward)
                            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 hover:shadow-md transition-shadow @if($reward->QTY === 0) border-red-300 @endif">
                                @if($reward->image_path)
                                    <img src="{{ route('rewards.image', $reward) }}" alt="{{ $reward->reward_name }}" class="w-full h-40 object-cover rounded-lg mb-3" />
                                @endif
                                <div class="text-lg font-semibold text-gray-900">{{ $reward->reward_name }}</div>
                                <div class="text-sm text-gray-600">Sponsor: {{ $reward->sponsor_name }}</div>
                                <p class="mt-2 text-sm text-gray-700">{{ $reward->description }}</p>
                                <div class="mt-3 text-sm text-gray-600">Points: <span class="font-medium text-gray-900">{{ $reward->points_cost }}</span></div>
                                <div class="text-sm flex items-center gap-2">
                                    <span class="text-gray-600">Qty left:</span>
                                    <span class="font-medium text-gray-900">{{ $reward->QTY }}</span>
                                    @if($reward->QTY === 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Out of stock</span>
                                    @endif
                                </div>
                                <div class="mt-4 flex gap-2">
                                    <a href="{{ route('admin.rewards.edit', $reward) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.rewards.destroy', $reward) }}" method="POST" id="delete-reward-form-{{ $reward->id }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="showConfirmModal('Delete this reward?', 'Delete Reward', 'Delete', 'Cancel', 'red').then(confirmed => { if(confirmed) document.getElementById('delete-reward-form-{{ $reward->id }}').submit(); });" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                            <i class="fas fa-trash"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <div class="mx-auto h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-gift text-gray-400 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">No rewards yet</h3>
                                <p class="text-sm text-gray-500 mb-6">Click Create Reward to add one.</p>
                            </div>
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


