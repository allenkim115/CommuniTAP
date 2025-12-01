<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-3xl font-semibold text-gray-900 leading-tight">{{ __('Rewards Catalog') }}</h2>
                <p class="text-sm text-gray-500">Curate incentives that keep TAP volunteers energized.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.redemptions.index') }}" class="btn-muted flex items-center gap-2 text-sm px-5 py-2.5">
                    <i class="fas fa-receipt"></i>
                    Reward Redemptions
                </a>
                <a href="{{ route('admin.rewards.create') }}" class="btn-brand flex items-center gap-2 text-sm px-5 py-2.5">
                    <i class="fas fa-plus"></i>
                    Create Reward
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $totalRewards = method_exists($rewards, 'total') ? $rewards->total() : $rewards->count();
        $activeRewards = $rewards->filter(fn($reward) => $reward->status === 'active')->count();
        $outOfStock = $rewards->filter(fn($reward) => $reward->QTY === 0)->count();
    @endphp

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            @if(session('status'))
                <div class="rounded-2xl border border-brand-teal/30 bg-brand-teal/10 px-4 py-3 text-sm font-medium text-brand-teal">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                <div class="stat-card flex flex-col gap-2">
                    <p class="text-sm font-medium text-gray-500">Rewards in catalog</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $totalRewards }}</p>
                </div>
                <div class="stat-card flex flex-col gap-2">
                    <p class="text-sm font-medium text-gray-500">Active rewards</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-semibold text-gray-900">{{ $activeRewards }}</span>
                        <span class="text-xs uppercase tracking-wide text-green-500 font-semibold">live</span>
                    </div>
                </div>
                <div class="stat-card flex flex-col gap-2">
                    <p class="text-sm font-medium text-gray-500">Out of stock</p>
                    <div class="flex items-center gap-2">
                        <span class="text-3xl font-semibold {{ $outOfStock ? 'text-red-600' : 'text-gray-900' }}">{{ $outOfStock }}</span>
                        <span class="text-xs uppercase tracking-wide {{ $outOfStock ? 'text-red-500' : 'text-gray-400' }}">needs restock</span>
                    </div>
                </div>
            </div>

            <div class="card-surface p-6">
                <div class="flex flex-col gap-3 border-b border-gray-100 pb-6 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-900">Manage Rewards</h3>
                    </div>
                    <div class="flex flex-wrap gap-2 text-xs text-gray-500">
                        <span class="inline-flex items-center gap-2 rounded-full bg-gray-100 px-3 py-1 font-semibold">
                            <i class="fas fa-box-open text-brand-teal"></i>
                            {{ $totalRewards }} total
                        </span>
                        <span class="inline-flex items-center gap-2 rounded-full bg-gray-100 px-3 py-1 font-semibold">
                            <i class="fas fa-circle text-xs text-green-500"></i>
                            {{ $activeRewards }} active
                        </span>
                        <span class="inline-flex items-center gap-2 rounded-full bg-gray-100 px-3 py-1 font-semibold">
                            <i class="fas fa-triangle-exclamation text-amber-500"></i>
                            {{ $outOfStock }} out of stock
                        </span>
                    </div>
                </div>

                <div class="mt-6 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @forelse($rewards as $reward)
                        @php
                            $isOutOfStock = $reward->QTY === 0;
                        @endphp
                        <div @class([
                            'card-surface overflow-hidden flex flex-col border transition-shadow',
                            'border-gray-100 hover:shadow-md' => ! $isOutOfStock,
                            'border-red-500 ring-2 ring-red-200 bg-red-50/60 shadow-lg shadow-red-100/60' => $isOutOfStock,
                        ])>
                            @if($reward->image_path)
                                <div class="relative">
                                    <img src="{{ route('rewards.image', $reward) }}" alt="{{ $reward->reward_name }}" class="h-44 w-full object-cover {{ $isOutOfStock ? 'grayscale contrast-75 opacity-70' : '' }}">
                                    <span class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></span>
                                    @if($isOutOfStock)
                                        <span class="absolute top-3 left-3 inline-flex items-center gap-2 rounded-full border border-white/70 bg-red-600/90 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white shadow-md">
                                            <i class="fas fa-ban text-[10px]"></i>
                                            Out of Stock
                                        </span>
                                    @endif
                                </div>
                            @endif
                            <div class="flex flex-1 flex-col gap-4 p-5">
                                <div>
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="space-y-1">
                                            <p class="text-lg font-semibold text-gray-900">{{ $reward->reward_name }}</p>
                                            <p class="text-sm text-gray-500">Sponsor: {{ $reward->sponsor_name }}</p>
                                        </div>
                                        <div class="flex flex-col items-end gap-1">
                                            <span class="badge-soft {{
                                                $isOutOfStock
                                                    ? 'badge-soft-orange'
                                                    : ($reward->status === 'active' ? 'badge-soft-teal' : 'badge-soft-slate')
                                            }}">
                                                {{ $isOutOfStock ? 'Inactive' : ucfirst($reward->status) }}
                                            </span>
                                            @if($isOutOfStock)
                                                <span class="text-[11px] font-semibold uppercase text-red-500 tracking-wide">Restock needed</span>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="mt-3 text-sm text-gray-600">{{ $reward->description }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div class="rounded-2xl border border-gray-200 px-3 py-2">
                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Points cost</p>
                                        <p class="text-lg font-semibold text-gray-900">{{ $reward->points_cost }}</p>
                                    </div>
                                    <div @class([
                                        'rounded-2xl border border-gray-200 px-3 py-2',
                                        'bg-red-50/70 border-red-200' => $isOutOfStock,
                                    ])>
                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Quantity</p>
                                        <div class="flex items-center gap-2">
                                            <p class="text-lg font-semibold {{ $isOutOfStock ? 'text-red-600' : 'text-gray-900' }}">{{ $reward->QTY }}</p>
                                            @if($reward->QTY === 0)
                                                <span class="badge-soft badge-soft-orange text-[10px] uppercase">Out</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-3">
                                    <a href="{{ route('admin.rewards.edit', $reward) }}" class="btn-muted text-xs font-semibold px-4 py-2">
                                        <i class="fas fa-pen mr-2"></i>Edit
                                    </a>
                                    <form action="{{ route('admin.rewards.destroy', $reward) }}" method="POST" id="delete-reward-form-{{ $reward->id }}" novalidate>
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            onclick="showConfirmModal('Delete this reward?', 'Delete Reward', 'Delete', 'Cancel', 'red').then(confirmed => { if(confirmed) document.getElementById('delete-reward-form-{{ $reward->id }}').submit(); });"
                                            class="btn-brand text-xs font-semibold px-4 py-2 bg-brand-teal hover:bg-brand-teal-dark">
                                            <i class="fas fa-trash mr-2"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                <i class="fas fa-gift text-2xl"></i>
                            </div>
                            <p class="mt-4 text-lg font-semibold text-gray-900">No rewards yet</p>
                            <p class="text-sm text-gray-500">Click “Create Reward” to add your first incentive.</p>
                        </div>
                    @endforelse
                </div>

                @if(method_exists($rewards, 'links'))
                    <div class="mt-8">
                        {{ $rewards->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
