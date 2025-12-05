<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl sm:text-3xl font-semibold text-gray-900 leading-tight">{{ __('Rewards Catalog') }}</h2>
                <p class="text-xs sm:text-sm text-gray-500">Curate incentives that keep TAP volunteers energized.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 w-full sm:w-auto">
                <a href="{{ route('admin.redemptions.index') }}" class="w-full sm:w-auto btn-muted flex items-center justify-center gap-2 text-base sm:text-sm px-6 py-3 sm:px-5 sm:py-2.5 min-h-[44px] font-semibold">
                    <i class="fas fa-receipt text-lg"></i>
                    Reward Redemptions
                </a>
                <a href="{{ route('admin.rewards.create') }}" class="w-full sm:w-auto btn-brand flex items-center justify-center gap-2 text-base sm:text-sm px-6 py-3 sm:px-5 sm:py-2.5 min-h-[44px] font-semibold">
                    <i class="fas fa-plus text-lg"></i>
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

    <div class="py-4 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-8">
            @if(session('status'))
                <div class="rounded-2xl border border-brand-teal/30 bg-brand-teal/10 px-4 py-3 text-sm font-medium text-brand-teal">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid gap-3 sm:gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                <div class="stat-card flex flex-col gap-2 p-4 sm:p-5">
                    <p class="text-xs sm:text-sm font-medium text-gray-500">Rewards in catalog</p>
                    <p class="mt-1.5 sm:mt-2 text-2xl sm:text-3xl font-semibold text-gray-900">{{ $totalRewards }}</p>
                </div>
                <div class="stat-card flex flex-col gap-2 p-4 sm:p-5">
                    <p class="text-xs sm:text-sm font-medium text-gray-500">Active rewards</p>
                    <div class="flex items-baseline gap-2 mt-1.5 sm:mt-2">
                        <span class="text-2xl sm:text-3xl font-semibold text-gray-900">{{ $activeRewards }}</span>
                        <span class="text-[10px] sm:text-xs uppercase tracking-wide text-green-500 font-semibold">live</span>
                    </div>
                </div>
                <div class="stat-card flex flex-col gap-2 p-4 sm:p-5">
                    <p class="text-xs sm:text-sm font-medium text-gray-500">Out of stock</p>
                    <div class="flex items-center gap-2 mt-1.5 sm:mt-2">
                        <span class="text-2xl sm:text-3xl font-semibold {{ $outOfStock ? 'text-red-600' : 'text-gray-900' }}">{{ $outOfStock }}</span>
                        <span class="text-[10px] sm:text-xs uppercase tracking-wide {{ $outOfStock ? 'text-red-500' : 'text-gray-400' }}">needs restock</span>
                    </div>
                </div>
            </div>

            <div class="card-surface p-4 sm:p-6">
                <div class="flex flex-col gap-3 border-b border-gray-100 pb-4 sm:pb-6 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-900">Manage Rewards</h3>
                    </div>
                    <div class="w-full sm:w-auto sm:flex sm:items-center sm:gap-3">
                        <div class="relative w-full sm:w-72">
                            <input
                                type="text"
                                id="reward-search"
                                placeholder="Search rewards..."
                                class="w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-teal focus:border-brand-teal text-sm min-h-[40px]"
                            >
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <button
                                type="button"
                                id="clearRewardSearch"
                                class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                aria-label="Clear search"
                            >
                                <i class="fas fa-times text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2 text-xs text-gray-500 mb-4 sm:mb-6">
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

                <div class="mt-4 sm:mt-6 grid gap-4 sm:gap-6 sm:grid-cols-2 xl:grid-cols-3">
                    @forelse($rewards as $reward)
                        @php
                            $isOutOfStock = $reward->QTY === 0;
                        @endphp
                        <div
                            data-reward-card
                            data-search="{{ strtolower(($reward->reward_name ?? '') . ' ' . ($reward->sponsor_name ?? '') . ' ' . ($reward->description ?? '') . ' ' . ($reward->status ?? '')) }}"
                            @class([
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
                                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                                    <a href="{{ route('admin.rewards.edit', $reward) }}" class="w-full sm:w-auto btn-muted text-xs font-semibold px-4 py-2 text-center">
                                        <i class="fas fa-pen mr-2"></i>Edit
                                    </a>
                                    <form action="{{ route('admin.rewards.destroy', $reward) }}" method="POST" id="delete-reward-form-{{ $reward->id }}" class="w-full sm:w-auto" novalidate>
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            onclick="showConfirmModal('Delete this reward?', 'Delete Reward', 'Delete', 'Cancel', 'red').then(confirmed => { if(confirmed) document.getElementById('delete-reward-form-{{ $reward->id }}').submit(); });"
                                            class="w-full btn-brand text-xs font-semibold px-4 py-2 bg-brand-teal hover:bg-brand-teal-dark">
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

                <div id="rewards-search-empty" class="hidden bg-white rounded-lg border border-dashed border-gray-200 shadow-sm p-12 text-center">
                    <div class="mx-auto h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-search text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No rewards match your search</h3>
                    <p class="text-gray-600">Try a different keyword.</p>
                </div>

                @if(method_exists($rewards, 'links'))
                    <div class="mt-8">
                        {{ $rewards->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('reward-search');
        const clearBtn = document.getElementById('clearRewardSearch');
        const cards = Array.from(document.querySelectorAll('[data-reward-card]'));
        const emptyState = document.getElementById('rewards-search-empty');

        if (!searchInput || cards.length === 0) return;

        const applyFilter = () => {
            const query = (searchInput.value || '').trim().toLowerCase();
            let visibleCount = 0;

            cards.forEach(card => {
                const haystack = (card.dataset.search || '').toLowerCase();
                const matches = !query || haystack.includes(query);
                card.classList.toggle('hidden', !matches);
                if (matches) visibleCount++;
            });

            if (emptyState) {
                emptyState.classList.toggle('hidden', visibleCount !== 0);
            }

            if (clearBtn) {
                clearBtn.classList.toggle('hidden', !query);
            }
        };

        searchInput.addEventListener('input', applyFilter);
        searchInput.addEventListener('keyup', applyFilter);
        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });

        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                searchInput.value = '';
                applyFilter();
                searchInput.focus();
            });
        }

        applyFilter();
    });
</script>
</x-admin-layout>
