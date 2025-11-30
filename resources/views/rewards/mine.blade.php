<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-2xl text-gray-900 dark:text-gray-100">
                    {{ __('Rewards') }}
                </h2>
                <p class="text-sm text-gray-500">Navigate between available and claimed rewards.</p>
            </div>
            <div class="flex items-center gap-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full px-1 py-1 shadow-sm">
                <a href="{{ route('rewards.index') }}" class="px-4 py-1.5 text-xs font-semibold text-gray-500 dark:text-gray-400 rounded-full hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    Available Rewards
                </a>
                <span class="px-4 py-1.5 text-xs font-semibold text-white rounded-full shadow" style="background-color: #2B9D8D;">
                    Claimed Rewards
                </span>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-session-toast />
            @php
                $redemptionItems = method_exists($redemptions, 'getCollection') ? $redemptions->getCollection() : collect($redemptions);
                $activeView = request('view', 'awaiting');
                $adminClaimed = $redemptionItems->filter(fn($item) => strtolower($item->status) === 'claimed');
                $awaiting = $redemptionItems->reject(fn($item) => strtolower($item->status) === 'claimed');
                $tabbedRedemptions = $activeView === 'admin-claimed' ? $adminClaimed : $awaiting;
                $tabCounts = [
                    'awaiting' => $awaiting->count(),
                    'admin-claimed' => $adminClaimed->count(),
                ];
            @endphp

            <div class="mb-6 flex flex-wrap gap-3 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-1 text-xs font-semibold">
                @php
                    $viewTabs = [
                        'awaiting' => 'Awaiting',
                        'admin-claimed' => 'Claimed',
                    ];
                @endphp
                @foreach($viewTabs as $viewKey => $label)
                    <a href="{{ route('rewards.mine', ['view' => $viewKey]) }}"
                       class="flex-1 rounded-full px-4 py-2 text-center transition-all {{ $activeView === $viewKey
                           ? 'bg-[#F3A261] text-white shadow'
                           : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white' }}">
                        <span>{{ $label }}</span>
                        <span class="ml-2 inline-block rounded-full bg-white/20 px-2 py-0.5 text-[10px] font-bold">
                            {{ number_format($tabCounts[$viewKey] ?? 0) }}
                        </span>
                    </a>
                @endforeach
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @forelse($tabbedRedemptions as $r)
                        @php
                            $couponCode = $r->coupon_code;
                            if (!$couponCode && $r->admin_notes && str_contains($r->admin_notes, 'Coupon:')) {
                                $couponCode = trim(\Illuminate\Support\Str::after($r->admin_notes, 'Coupon:'));
                            }

                    $statusColors = [
                        'pending' => 'text-amber-600 bg-amber-50 border-amber-200 dark:text-amber-300 dark:bg-amber-900/30 dark:border-amber-700',
                        'approved' => 'text-emerald-600 bg-emerald-50 border-emerald-200 dark:text-emerald-300 dark:bg-emerald-900/30 dark:border-emerald-700',
                        'completed' => 'text-emerald-600 bg-emerald-50 border-emerald-200 dark:text-emerald-300 dark:bg-emerald-900/30 dark:border-emerald-700',
                        'redeemed' => 'text-emerald-600 bg-emerald-50 border-emerald-200 dark:text-emerald-300 dark:bg-emerald-900/30 dark:border-emerald-700',
                        'rejected' => 'text-red-600 bg-red-50 border-red-200 dark:text-red-300 dark:bg-red-900/30 dark:border-red-700',
                    ];
                    $normalizedStatus = strtolower($r->status);
                    $statusClass = $statusColors[$normalizedStatus] ?? 'text-gray-600 bg-gray-50 border-gray-200 dark:text-gray-300 dark:bg-gray-800/50 dark:border-gray-700';
                    $statusLabel = $normalizedStatus === 'claimed' ? __('Claimed') : __('Awaiting');
                        @endphp

                @php
                    $cardId = 'coupon-card-'.$loop->iteration.'-'.$r->id;
                @endphp
                <div id="{{ $cardId }}" class="relative h-full rounded-xl bg-white dark:bg-gray-900 shadow border border-gray-100 dark:border-gray-800 overflow-hidden coupon-card">
                    <div class="absolute inset-x-0 top-0 h-0.5" style="background-color: #F3A261;"></div>

                    <div class="p-4 space-y-3">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-xs uppercase tracking-[0.4em] text-gray-400 dark:text-gray-500">Sponsor</p>
                                <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $r->reward->sponsor_name }}</p>
                            </div>
                            <img src="{{ asset('images/communitaplogo1.svg') }}" alt="CommuniTAP logo" class="w-14 h-14 object-contain ml-auto">
                        </div>

                        <div class="flex items-center justify-start download-hide">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-[10px] font-semibold border rounded-full capitalize {{ $statusClass }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                {{ $statusLabel }}
                            </span>
                        </div>

                        <div class="space-y-1">
                            <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 dark:text-gray-500">Reward</p>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white leading-tight">{{ $r->reward->reward_name }}</h3>
                        </div>

                        <div class="grid grid-cols-1 gap-2.5 text-[11px] text-gray-600 dark:text-gray-300">
                            <div class="flex flex-col gap-1">
                                <span class="uppercase text-[10px] tracking-[0.25em] text-gray-400 dark:text-gray-500">Points Used</span>
                                <span class="text-sm font-semibold text-amber-600 dark:text-amber-300">{{ number_format($r->points_spent ?? $r->reward->points_cost) }} pts</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="uppercase text-[10px] tracking-[0.25em] text-gray-400 dark:text-gray-500">Claimed On</span>
                                <span class="text-sm font-semibold">{{ $r->created_at?->format('M d, Y') }}</span>
                            </div>
                        </div>

                        @if($r->admin_notes)
                            @php
                                $displayNotes = $r->admin_notes;
                                // If notes only contain coupon code, show a helpful message instead
                                if (str_contains($r->admin_notes, 'Coupon:')) {
                                    // Remove the entire "Coupon: [code]" line using regex
                                    $notesWithoutCoupon = trim(preg_replace('/Coupon:\s*[^\n\r]*/i', '', $r->admin_notes));
                                    // Clean up any extra whitespace or newlines
                                    $notesWithoutCoupon = trim($notesWithoutCoupon);
                                    // If notes only contain the coupon code (or just whitespace after removing it), show default message
                                    if (empty($notesWithoutCoupon)) {
                                        $displayNotes = 'Present this coupon to a TAPmin at the barangay hall to claim your reward.';
                                    } else {
                                        // If there are other notes, show them without the coupon part
                                        $displayNotes = $notesWithoutCoupon;
                                    }
                                }
                            @endphp
                            <div class="text-[11px] text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl p-2.5">
                                <p class="uppercase text-[10px] tracking-[0.25em] text-gray-400 dark:text-gray-500 mb-1">Notes</p>
                                <p>{{ $displayNotes }}</p>
                            </div>
                        @endif

                        <div class="relative bg-white dark:bg-gray-800 border border-dashed border-gray-200 dark:border-gray-700 rounded-lg p-3">
                            <div class="absolute -left-2 top-1/2 -translate-y-1/2 w-4 h-4 bg-gray-100 dark:bg-gray-900 rounded-full border border-gray-200 dark:border-gray-800"></div>
                            <div class="absolute -right-2 top-1/2 -translate-y-1/2 w-4 h-4 bg-gray-100 dark:bg-gray-900 rounded-full border border-gray-200 dark:border-gray-800"></div>

                            <div class="flex flex-col gap-1.5 text-center">
                                <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 dark:text-gray-500">Coupon Code</p>
                                @if($couponCode)
                                    <p class="font-mono text-lg font-black tracking-[0.35em] text-gray-900 dark:text-white">{{ $couponCode }}</p>
                                @else
                                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">Awaiting code assignment</p>
                                @endif
                            </div>
                            <button type="button"
                                    class="mt-4 inline-flex items-center justify-center gap-1 rounded-full border border-gray-200 dark:border-gray-700 px-3 py-1.5 text-[11px] font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 download-coupon-btn download-hide"
                                    data-target="{{ $cardId }}"
                                    data-reference="{{ $couponCode ?? $r->id }}">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"/>
                                </svg>
                                Download Coupon
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
                    <div class="p-12 text-center">
                        <div class="mx-auto w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            @if($activeView === 'admin-claimed')
                                No admin-marked claims yet
                            @else
                                No claimed rewards yet
                            @endif
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
                            @if($activeView === 'admin-claimed')
                                Once an admin verifies a coupon, it will appear in this tab.
                            @else
                                Redeem your points on the rewards page to see your digital coupons here.
                            @endif
                        </p>
                        <a href="{{ route('rewards.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white rounded-full shadow-lg transition-all duration-200"
                           style="background-color: #F3A261;"
                           onmouseover="this.style.backgroundColor='#E8944F'"
                           onmouseout="this.style.backgroundColor='#F3A261'">
                            Browse Rewards
                        </a>
                    </div>
                </div>
            @endforelse
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    .download-mode .download-hide {
        display: none !important;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js" crossorigin="anonymous"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.addEventListener('click', async (event) => {
            const button = event.target.closest('.download-coupon-btn');
            if (!button) return;

            event.preventDefault();
            const targetId = button.dataset.target;
            const reference = button.dataset.reference || 'coupon';
            const target = document.querySelector(`#${CSS.escape(targetId)}`);

            if (!target || !window.html2canvas) {
                console.warn('Coupon card not available for download.');
                return;
            }

            try {
                const previousScrollX = window.scrollX;
                const previousScrollY = window.scrollY;
                window.scrollTo(0, 0);
                target.classList.add('download-mode');

                const canvas = await window.html2canvas(target, {
                    backgroundColor: '#f8fafc',
                    scale: Math.min(4, (window.devicePixelRatio || 1) * 2),
                    useCORS: true,
                    logging: false,
                });

                target.classList.remove('download-mode');
                window.scrollTo(previousScrollX, previousScrollY);

                canvas.toBlob((blob) => {
                    if (!blob) return;
                    const url = URL.createObjectURL(blob);
                    const anchor = document.createElement('a');
                    anchor.href = url;
                    anchor.download = `coupon-${reference}.png`;
                    document.body.appendChild(anchor);
                    anchor.click();
                    anchor.remove();
                    URL.revokeObjectURL(url);
                }, 'image/png');
            } catch (error) {
                console.error('Failed to capture coupon card', error);
            } finally {
                target.classList.remove('download-mode');
            }
        });
    });
</script>