<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-3xl font-semibold text-gray-900 leading-tight">{{ __('Reward Redemptions') }}</h2>
                <p class="text-sm text-gray-500">Track fulfillment without leaving the CommuniTAP visual language.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.rewards.index') }}"
                   class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-teal">
                    <i class="fas fa-arrow-left"></i>
                    Back to Rewards
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
            <div class="card-surface p-3 sm:p-6 space-y-4 sm:space-y-6">
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-brand-orange-dark uppercase tracking-wide">Walk-in verification</p>
                            <h3 class="text-xl font-semibold text-gray-900">Match the volunteer and coupon code before marking claimed.</h3>
                            <p class="text-sm text-gray-500">Only the essentials: who redeemed, what they redeemed, and their coupon code.</p>
                        </div>
                        <div class="inline-flex items-center gap-2 rounded-full border border-gray-200 px-4 py-2 text-sm font-medium text-gray-600">
                            <span class="h-2 w-2 rounded-full bg-brand-teal"></span>
                            {{ number_format($tabCounts[$activeTab] ?? $redemptions->total()) }} records
                        </div>
                    </div>

                    <div class="flex w-full flex-wrap gap-3 rounded-full bg-gray-50 p-1 text-sm font-semibold">
                        @php
                            $tabs = [
                                'pending' => 'Needs verification',
                                'claimed' => 'Claimed',
                            ];
                        @endphp
                        @foreach($tabs as $tabKey => $label)
                            <a href="{{ route('admin.redemptions.index', ['status' => $tabKey] + ($search ? ['search' => $search] : [])) }}"
                               class="flex flex-1 items-center justify-between rounded-full px-4 py-2 transition-colors {{ $activeTab === $tabKey ? 'bg-white text-brand-teal shadow-sm border border-brand-teal/40' : 'text-gray-500 hover:text-brand-teal' }}">
                                <span>{{ $label }}</span>
                                <span class="text-xs font-bold {{ $activeTab === $tabKey ? 'text-brand-teal' : 'text-gray-400' }}">
                                    {{ number_format($tabCounts[$tabKey] ?? 0) }}
                                </span>
                            </a>
                        @endforeach
                    </div>

                    <div class="flex flex-col gap-2 sm:gap-3 rounded-xl sm:rounded-2xl border border-gray-100 bg-gray-50/60 p-3 sm:p-4 sm:flex-row sm:items-center">
                        <div class="relative w-full sm:w-96">
                            <input
                                type="text"
                                id="redemption-search"
                                placeholder="Search redemptions..."
                                class="w-full pl-10 pr-10 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-teal focus:border-brand-teal text-sm min-h-[40px]"
                            >
                            <svg class="h-4 w-4 text-gray-400 flex-shrink-0 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M16.65 16.65A7 7 0 1012 19a7 7 0 004.65-2.35z" />
                            </svg>
                            <button
                                type="button"
                                id="clearRedemptionSearch"
                                class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                aria-label="Clear search"
                            >
                                <i class="fas fa-times text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 text-sm">
                        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <tr>
                                <th class="px-4 py-3">Volunteer</th>
                                <th class="px-4 py-3">Reward</th>
                                <th class="px-4 py-3">Redeemed on</th>
                                <th class="px-4 py-3">Coupon code</th>
                                <th class="px-4 py-3">Verification</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($redemptions as $red)
                                @php
                                    $couponCode = $red->coupon_code;
                                    if (!$couponCode && $red->admin_notes && str_contains($red->admin_notes, 'Coupon:')) {
                                        $couponCode = trim(\Illuminate\Support\Str::after($red->admin_notes, 'Coupon:'));
                                    }
                                @endphp
                                <tr
                                    class="hover:bg-gray-50"
                                    data-redemption-row
                                    data-search="{{ strtolower(
                                        ($red->user?->name ?? '') . ' ' .
                                        ($red->user?->email ?? '') . ' ' .
                                        ($red->reward?->reward_name ?? '') . ' ' .
                                        ($couponCode ?? '') . ' ' .
                                        ($red->status ?? '')
                                    ) }}"
                                >
                                    <td class="px-4 py-4">
                                        <p class="text-sm font-semibold text-gray-900">{{ $red->user?->name ?? '—' }}</p>
                                        <p class="text-xs text-gray-500">{{ $red->user?->email ?? '—' }}</p>
                                    </td>
                                    <td class="px-4 py-4">
                                        <p class="text-sm font-semibold text-gray-900">{{ $red->reward?->reward_name ?? 'Deleted reward' }}</p>
                                        <p class="text-xs text-gray-500">Points: {{ $red->reward?->points_cost ?? '—' }}</p>
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($red->redemption_date)
                                            <p class="text-sm font-semibold text-gray-900">
                                                {{ $red->redemption_date->timezone(config('app.timezone'))->format('M d, Y') }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $red->redemption_date->timezone(config('app.timezone'))->format('g:i A') }}
                                            </p>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($couponCode)
                                            <span class="font-mono text-sm font-semibold tracking-widest text-gray-900">{{ $couponCode }}</span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($red->status !== 'claimed')
                                            <form method="POST" action="{{ route('admin.redemptions.claim', $red) }}" class="inline-flex" id="claim-redemption-{{ $red->redemptionId }}" novalidate>
                                                @csrf
                                                @method('PATCH')
                                                <button type="button"
                                                    onclick="showConfirmModal('Mark this reward as claimed?', 'Verify claim', 'Mark as claimed', 'Cancel', 'green').then(confirmed => { if (confirmed) document.getElementById('claim-redemption-{{ $red->redemptionId }}').submit(); });"
                                                    class="inline-flex items-center justify-center rounded-full bg-brand-teal px-3 py-2 text-xs font-semibold text-white hover:bg-brand-teal/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-teal">
                                                    Mark as claimed
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs font-semibold uppercase text-brand-teal">Claimed</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-sm text-gray-500">
                                        @if($activeTab === 'claimed')
                                            No claimed rewards yet.
                                        @else
                                            No redemptions awaiting verification.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div id="redemptions-search-empty" class="hidden bg-white rounded-lg border border-dashed border-gray-200 shadow-sm p-8 text-center text-sm text-gray-600">
                    <div class="mx-auto h-12 w-12 bg-gray-100 rounded-full flex items-center justify-center mb-3 text-gray-400">
                        <i class="fas fa-search text-lg"></i>
                    </div>
                    <p class="font-semibold text-gray-900">No redemptions match your search</p>
                    <p>Try a different keyword.</p>
                </div>

                <div>
                    {{ $redemptions->links() }}
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('redemption-search');
        const clearBtn = document.getElementById('clearRedemptionSearch');
        const rows = Array.from(document.querySelectorAll('[data-redemption-row]'));
        const emptyState = document.getElementById('redemptions-search-empty');

        if (!searchInput || rows.length === 0) return;

        const applyFilter = () => {
            const query = (searchInput.value || '').trim().toLowerCase();
            let visibleCount = 0;

            rows.forEach(row => {
                const haystack = (row.dataset.search || '').toLowerCase();
                const matches = !query || haystack.includes(query);
                row.classList.toggle('hidden', !matches);
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
