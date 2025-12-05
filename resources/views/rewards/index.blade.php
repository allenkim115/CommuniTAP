<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-2xl text-gray-900 dark:text-gray-100">
                    {{ __('Rewards') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Navigate between available and claimed coupons.</p>
            </div>
            <div class="flex items-center gap-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full px-1 py-1 shadow-sm">
                <span class="px-4 py-1.5 text-xs font-semibold text-white rounded-full shadow" style="background-color: #2B9D8D;">
                    Available Rewards
                </span>
                <a href="{{ route('rewards.mine') }}" class="px-4 py-1.5 text-xs font-semibold text-gray-500 dark:text-gray-400 rounded-full hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    Claimed Rewards
                </a>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-950 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Toast Notifications -->
            <x-session-toast />
            @php
                $currentUser = auth()->user();
                $userPoints = $currentUser?->points ?? 0;
            @endphp
            <div class="mb-6 flex justify-start">
                <div class="inline-flex items-center gap-3 px-4 py-2 text-base font-semibold text-amber-800 dark:text-amber-200 bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-700 rounded-full shadow-sm">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-white dark:bg-gray-800 text-amber-500 shadow">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="tracking-wide">Your Points: <strong class="text-lg">{{ number_format($userPoints) }}</strong></span>
                </div>
            </div>
            
            @if($rewards->count() > 0)
                <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Available Rewards</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Redeem your points for exciting rewards</p>
                    </div>
                    <div class="relative w-full sm:w-72">
                        <input
                            type="text"
                            id="reward-search"
                            placeholder="Search rewards..."
                            class="w-full pl-10 pr-10 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-teal focus:border-brand-teal text-sm min-h-[40px] bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                        >
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <button
                            type="button"
                            id="clearRewardSearch"
                            class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300"
                            aria-label="Clear search"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($rewards as $reward)
                        @php
                            $user = auth()->user();
                            $hasEnoughPoints = $user && $user->points >= $reward->points_cost;
                        @endphp
                        <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-all duration-300 transform hover:-translate-y-1"
                             data-reward-card
                             data-search="{{ strtolower(($reward->reward_name ?? '') . ' ' . ($reward->sponsor_name ?? '') . ' ' . ($reward->description ?? '')) }}">
                            <!-- Image Section -->
                            <div class="relative h-28 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 overflow-hidden">
                                @if($reward->image_path)
                                    <img src="{{ route('rewards.image', $reward) }}" 
                                         alt="{{ $reward->reward_name }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Availability Badge -->
                                <div class="absolute top-2 right-2 drop-shadow">
                                    @if($reward->isAvailable())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold text-emerald-600 bg-white/95 border border-emerald-100 shadow-sm backdrop-blur dark:text-emerald-300 dark:bg-emerald-900/40 dark:border-emerald-800">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Available
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold text-red-600 bg-white/95 border border-red-100 shadow-sm backdrop-blur dark:text-red-300 dark:bg-red-900/40 dark:border-red-800">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                            Out of Stock
                                        </span>
                                    @endif
                                </div>

                                <!-- Points Badge -->
                                <div class="absolute top-2 left-2">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold text-white shadow-md" style="background-color: #F3A261;">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $reward->points_cost }} pts
                                    </span>
                                </div>
                            </div>

                            <!-- Content Section -->
                            <div class="p-4">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-1">
                                    {{ $reward->reward_name }}
                                </h3>
                                
                                <div class="flex items-center gap-1.5 mb-2 text-xs text-gray-600 dark:text-gray-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <span class="font-medium">{{ $reward->sponsor_name }}</span>
                                </div>

                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-3 line-clamp-2 min-h-[2.5rem]">
                                    {{ $reward->description }}
                                </p>

                                <!-- Quantity Info -->
                                <div class="flex items-center justify-between mb-3 pb-3 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        <span>Quantity left: <strong class="text-gray-700 dark:text-gray-300 text-xs">{{ $reward->QTY }}</strong></span>
                                    </div>
                                </div>

                                <!-- Redeem Button -->
                                <form method="POST" novalidate
                                      action="{{ route('rewards.redeem', $reward) }}"
                                      id="redeem-reward-form-{{ $reward->id }}">
                                    @csrf
                                    @if($reward->isAvailable())
                                        <button type="button"
                                                class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 text-white font-semibold text-sm rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200"
                                                style="background-color: #F3A261;"
                                                onmouseover="this.style.backgroundColor='#E8944F'"
                                                onmouseout="this.style.backgroundColor='#F3A261'"
                                                onclick="event.preventDefault(); const form=this.closest('form'); if(form){ const message='{{ 'Redeem ' . $reward->reward_name . ' for ' . number_format($reward->points_cost) . ' points?' }}'; Promise.resolve(window.confirm(message)).then(confirmed=>{ if(confirmed){ form.submit(); }}); }">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                                            </svg>
                                            Redeem Now
                                        </button>
                                    @else
                                        <button type="button" 
                                                disabled
                                                class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 font-semibold text-sm rounded-lg cursor-not-allowed opacity-60">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Out of Stock
                                        </button>
                                    @endif
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div id="rewards-search-empty" class="hidden bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-12 text-center">
                        <div class="mx-auto w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">No Rewards Match Your Search</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-1 max-w-md mx-auto">
                            Try a different keyword.
                        </p>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-12 text-center">
                        <div class="mx-auto w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">No Rewards Available</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-1 max-w-md mx-auto">
                            There are no rewards available at the moment.
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-500 mb-6">
                            Check back later for new rewards!
                        </p>
                    </div>
                </div>
            @endif
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
</x-app-layout>

