<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="font-semibold text-3xl text-gray-900 leading-tight">Feedback Management</h2>
            </div>
        </div>
    </x-slot>

    @php
        $feedbackCount = method_exists($userFeedback, 'total') ? $userFeedback->total() : $userFeedback->count();
        $averageRating = optional($userFeedback)->count() ? number_format($userFeedback->avg('rating'), 1) : '0.0';
        $latestFeedback = optional($userFeedback->first())->feedback_date;
        $ratingFilterLabels = [
            0.5 => 'Barely there',
            1 => 'Poor',
            1.5 => 'Between poor & fair',
            2 => 'Fair',
            2.5 => 'Near good',
            3 => 'Good',
            3.5 => 'Solid',
            4 => 'Very good',
            4.5 => 'Excellent',
            5 => 'Outstanding',
        ];

        $ratingFilterMessages = ['' => 'Showing all ratings'];
        for ($step = 1; $step <= 10; $step++) {
            $value = $step * 0.5;
            $formatted = rtrim(number_format($value, 1), '.0');
            $ratingFilterMessages[(string) $value] = "Only {$formatted}★ feedback";
        }
    @endphp

    <div class="py-4 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-8">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="stat-card flex flex-col gap-3">
                    <p class="text-sm font-medium text-gray-500">Total Feedback</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-semibold text-gray-900">{{ $feedbackCount }}</span>
                        <span class="text-sm text-gray-400">entries</span>
                    </div>
                </div>
                <div class="stat-card flex flex-col gap-3">
                    <p class="text-sm font-medium text-gray-500">Average Rating</p>
                    <div class="flex items-center gap-3">
                        <x-rating-stars :value="$userFeedback->avg('rating')" size="md" />
                        <span class="text-3xl font-semibold text-gray-900">{{ $averageRating }}</span>
                    </div>
                </div>
                <div class="stat-card flex flex-col gap-3">
                    <p class="text-sm font-medium text-gray-500">Latest Feedback</p>
                    <span class="text-lg font-semibold text-gray-900">
                        {{ $latestFeedback ? $latestFeedback->diffForHumans() : '—' }}
                    </span>
                    <p class="text-sm text-gray-500">
                        {{ $latestFeedback ? $latestFeedback->format('M d, Y • g:i A') : 'Waiting for activity' }}
                    </p>
                </div>
            </div>

            @if (session('status'))
                <div class="rounded-2xl border border-brand-teal/30 bg-brand-teal/10 px-4 py-3 flex gap-3 items-start">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-brand-teal/20 text-brand-teal">
                        <i class="fas fa-check"></i>
                    </span>
                    <p class="text-sm font-medium text-brand-teal">{{ session('status') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 flex gap-3 items-start">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-red-100 text-red-500">
                        <i class="fas fa-exclamation"></i>
                    </span>
                    <p class="text-sm font-medium text-red-600">{{ session('error') }}</p>
                </div>
            @endif

            <div class="card-surface overflow-hidden">
                <div x-data="{ filtersOpen: false }" class="flex flex-col gap-3 sm:gap-6 px-3 sm:px-6 py-3 sm:py-5 border-b border-gray-100">
                    <button type="button" @click="filtersOpen = !filtersOpen" class="w-full sm:hidden flex items-center justify-between py-2.5 px-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-filter text-base" style="color: #2B9D8D;"></i>
                            <span class="text-sm font-semibold text-gray-900">Filters</span>
                        </div>
                        <i class="fas fa-chevron-down transition-transform text-sm" :class="{'rotate-180': filtersOpen}"></i>
                    </button>
                    <div class="hidden sm:flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <h3 class="text-xl sm:text-2xl font-semibold text-gray-900">All Feedback</h3>
                        </div>
                        <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-500">
                            <span class="inline-flex items-center gap-2 rounded-full bg-gray-100 px-2 sm:px-3 py-1 font-medium">
                                <i class="fas fa-database text-brand-teal text-xs"></i>
                                {{ $feedbackCount }} records
                            </span>
                        </div>
                    </div>
                    <div id="admin-feedback-controls" class="flex flex-col gap-3 sm:gap-4 lg:flex-row lg:items-end lg:justify-between" x-show="filtersOpen || window.innerWidth >= 640" x-cloak>
                        <div class="w-full lg:max-w-md">
                            <label for="admin-feedback-search" class="sr-only">Search feedback</label>
                            <div class="relative">
                                <input
                                    id="admin-feedback-search"
                                    type="text"
                                    placeholder="Search..."
                                    class="w-full rounded-lg sm:rounded-xl border border-gray-200 bg-white pl-9 sm:pl-10 pr-4 py-2 sm:py-2.5 text-sm text-gray-900 placeholder-gray-500 focus:border-brand-teal focus:ring-2 focus:ring-brand-teal/40 min-h-[40px] sm:min-h-[44px]"
                                >
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-brand-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="w-full lg:w-auto">
                            <input type="hidden" id="admin-feedback-rating-filter" value="">
                            <div class="flex flex-col gap-2">
                                <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                                    <p class="hidden sm:block text-xs font-semibold uppercase tracking-wide text-gray-500">Filter by rating</p>
                                    <button type="button" id="admin-rating-filter-reset" class="text-[10px] sm:text-[11px] font-semibold text-brand-teal hover:text-brand-teal-dark uppercase tracking-[0.2em]">
                                        Reset
                                    </button>
                                </div>
                                <div class="rating-filter-stars flex items-center justify-between gap-1.5 pt-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <div class="rating-filter-star-wrapper relative flex items-center justify-center">
                                            <span class="rating-filter-star" data-star="{{ $i }}"></span>
                                            @foreach ([$i - 0.5, $i] as $portion)
                                                @php
                                                    $formatted = rtrim(number_format($portion, 1), '.0');
                                                    $tooltip = $ratingFilterLabels[$portion] ?? "{$formatted}★ focus";
                                                    $positionClass = $loop->first ? 'rating-filter-hit--left' : 'rating-filter-hit--right';
                                                @endphp
                                                <button
                                                    type="button"
                                                    class="rating-filter-hit {{ $positionClass }} focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-brand-teal/60 focus-visible:ring-offset-white"
                                                    data-rating="{{ $portion }}"
                                                    aria-label="Minimum {{ $formatted }} star rating"
                                                    aria-pressed="false"
                                                >
                                                    <span class="rating-filter-tooltip">{{ $tooltip }}</span>
                                                </button>
                                            @endforeach
                                        </div>
                                    @endfor
                                </div>
                                <p id="admin-rating-filter-label" class="text-[11px] text-gray-500">Showing all ratings</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($userFeedback->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100 text-sm">
                            <thead class="bg-gray-50 text-left">
                                <tr>
                                    <th class="px-6 py-3 font-medium uppercase tracking-wide text-xs text-gray-500">User</th>
                                    <th class="px-6 py-3 font-medium uppercase tracking-wide text-xs text-gray-500">Task</th>
                                    <th class="px-6 py-3 font-medium uppercase tracking-wide text-xs text-gray-500">Rating</th>
                                    <th class="px-6 py-3 font-medium uppercase tracking-wide text-xs text-gray-500">Comment</th>
                                    <th class="px-6 py-3 font-medium uppercase tracking-wide text-xs text-gray-500">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach($userFeedback as $feedback)
                                    @php
                                        $detailUrl = route('admin.feedback.show', $feedback->task);
                                        $rowSearch = Str::lower(
                                            $feedback->user->name.' '.
                                            $feedback->user->email.' '.
                                            $feedback->task->title.' '.
                                            $feedback->comment
                                        );
                                    @endphp
                                    <tr
                                        class="admin-feedback-row hover:bg-gray-50 cursor-pointer transition-colors focus-within:bg-gray-100"
                                        role="button"
                                        tabindex="0"
                                        aria-label="View feedback details"
                                        onclick="window.location.href='{{ $detailUrl }}'"
                                        onkeydown="if(event.key==='Enter'||event.key===' '){ window.location.href='{{ $detailUrl }}'; }"
                                        data-search="{{ $rowSearch }}"
                                        data-rating="{{ (float) $feedback->rating }}"
                                    >
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <x-user-avatar
                                                    :user="$feedback->user"
                                                    size="h-11 w-11"
                                                    text-size="text-sm"
                                                    class="bg-brand-teal/10 text-brand-teal font-semibold uppercase"
                                                />
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900">{{ $feedback->user->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $feedback->user->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="space-y-1">
                                                <p class="text-sm font-medium text-gray-900">{{ $feedback->task->title }}</p>
                                                <p class="text-xs text-gray-500">{{ Str::limit($feedback->task->description, 60) }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="inline-flex items-center gap-2 rounded-full bg-brand-peach/40 px-3 py-1 text-brand-orange-dark font-semibold">
                                                <x-rating-stars :value="$feedback->rating" size="sm" />
                                                <span>{{ number_format($feedback->rating, 1) }}/5</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ Str::limit($feedback->comment, 90) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            {{ $feedback->feedback_date->format('M j, Y • g:i A') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-100 space-y-4">
                        <div id="admin-feedback-empty-state" class="hidden rounded-xl border border-dashed border-brand-orange/60 bg-brand-peach/30 text-brand-orange-dark text-sm px-4 py-3">
                            We couldn’t find feedback that matches those filters. Try clearing them or using different keywords.
                        </div>
                        {{ $userFeedback->links() }}
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                            <i class="fas fa-comments text-2xl"></i>
                        </div>
                        <p class="mt-4 text-lg font-semibold text-gray-900">No feedback yet</p>
                        <p class="text-sm text-gray-500">Feedback will show up here as soon as participants respond.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        #admin-feedback-controls .rating-filter-star-wrapper {
            position: relative;
            width: 1.5rem;
            height: 1.5rem;
        }
        #admin-feedback-controls .rating-filter-star {
            --rating-star-base: #E5E7EB;
            display: block;
            width: 100%;
            height: 100%;
            background: var(--rating-star-base);
            mask: url("data:image/svg+xml,%3Csvg%20viewBox%3D%270%200%2020%2020%27%20xmlns%3D%27http%3A//www.w3.org/2000/svg%27%3E%3Cpath%20fill%3D%27white%27%20d%3D%27M9.049%202.927c.3-.921%201.603-.921%201.902%200l1.07%203.292a1%201%200%2000.95.69h3.462c.969%200%201.371%201.24.588%201.81l-2.8%202.034a1%201%200%2000-.364%201.118l1.07%203.292c.3.921-.755%201.688-1.54%201.118l-2.8-2.034a1%201%200%2000-1.175%200l-2.8%202.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1%201%200%2000-.364-1.118L2.98%208.72c-.783-.57-.38-1.81.588-1.81h3.461a1%201%200%2000.951-.69l1.07-3.292z%27/%3E%3C/svg%3E") no-repeat center / contain;
            -webkit-mask: url("data:image/svg+xml,%3Csvg%20viewBox%3D%270%200%2020%2020%27%20xmlns%3D%27http%3A//www.w3.org/2000/svg%27%3E%3Cpath%20fill%3D%27white%27%20d%3D%27M9.049%202.927c.3-.921%201.603-.921%201.902%200l1.07%203.292a1%201%200%2000.95.69h3.462c.969%200%201.371%201.24.588%201.81l-2.8%202.034a1%201%200%2000-.364%201.118l1.07%203.292c.3.921-.755%201.688-1.54%201.118l-2.8-2.034a1%201%200%2000-1.175%200l-2.8%202.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1%201%200%2000-.364-1.118L2.98%208.72c-.783-.57-.38-1.81.588-1.81h3.461a1%201%200%2000.951-.69l1.07-3.292z%27/%3E%3C/svg%3E") no-repeat center / contain;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }
        #admin-feedback-controls .rating-filter-star-wrapper:hover .rating-filter-star,
        #admin-feedback-controls .rating-filter-star-wrapper:focus-within .rating-filter-star {
            transform: scale(1.05);
        }
        #admin-feedback-controls .rating-filter-hit {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 50%;
            background: transparent;
            border: none;
            padding: 0;
            cursor: pointer;
        }
        #admin-feedback-controls .rating-filter-hit--left { left: 0; }
        #admin-feedback-controls .rating-filter-hit--right { right: 0; }
        #admin-feedback-controls .rating-filter-tooltip {
            position: absolute;
            bottom: -2.2rem;
            left: 50%;
            transform: translateX(-50%);
            background: #fff;
            border: 1px solid #E5E7EB;
            padding: 0.15rem 0.45rem;
            border-radius: 0.375rem;
            font-size: 0.65rem;
            font-weight: 600;
            color: #4B5563;
            opacity: 0;
            pointer-events: none;
            white-space: nowrap;
            transition: opacity 0.2s ease, transform 0.2s ease;
            z-index: 10;
        }
        #admin-feedback-controls .rating-filter-hit:hover .rating-filter-tooltip,
        #admin-feedback-controls .rating-filter-hit:focus-visible .rating-filter-tooltip {
            opacity: 1;
            transform: translate(-50%, 0.15rem);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('admin-feedback-search');
            const ratingFilter = document.getElementById('admin-feedback-rating-filter');
            const ratingInputs = Array.from(document.querySelectorAll('#admin-feedback-controls .rating-filter-hit'));
            const ratingStars = Array.from(document.querySelectorAll('#admin-feedback-controls .rating-filter-star'));
            const ratingLabel = document.getElementById('admin-rating-filter-label');
            const ratingReset = document.getElementById('admin-rating-filter-reset');
            const rows = Array.from(document.querySelectorAll('.admin-feedback-row'));
            const emptyState = document.getElementById('admin-feedback-empty-state');
            const ratingMessages = @json($ratingFilterMessages);
            let currentRating = 0;

            if (!rows.length) {
                return;
            }

            const toggleEmptyState = () => {
                const hasVisible = rows.some(row => row.style.display !== 'none');
                emptyState?.classList.toggle('hidden', hasVisible);
            };

            const applyFill = (star, amount) => {
                if (!star) return;
                const clamped = Math.max(0, Math.min(1, amount));
                if (clamped === 0) {
                    star.style.background = '';
                    star.style.boxShadow = '';
                    return;
                }
                const percent = (clamped * 100).toFixed(0);
                star.style.background = `linear-gradient(90deg, #F3A261 0%, #F3A261 ${percent}%, #E5E7EB ${percent}%, #E5E7EB 100%)`;
                star.style.boxShadow = '0 10px 20px rgba(243, 162, 97, 0.35)';
            };

            const renderStars = (value) => {
                ratingStars.forEach(star => {
                    const starValue = Number(star.dataset.star);
                    const fillAmount = value ? value - (starValue - 1) : 0;
                    applyFill(star, fillAmount);
                });

                ratingInputs.forEach(input => {
                    const inputValue = Number(input.dataset.rating);
                    const isActive = value && inputValue <= value;
                    input.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                });
            };

            const setActiveRating = (value) => {
                const numericValue = value ? Number(value) : '';
                ratingFilter.value = numericValue;
                currentRating = numericValue || 0;

                renderStars(currentRating);

                if (ratingLabel) {
                    ratingLabel.textContent = ratingMessages[numericValue] ?? ratingMessages[''];
                }

                if (ratingReset) {
                    const disabled = numericValue === '';
                    ratingReset.classList.toggle('opacity-50', disabled);
                    ratingReset.classList.toggle('pointer-events-none', disabled);
                }
            };

            const filterRows = () => {
                const query = (searchInput?.value || '').trim().toLowerCase();
                const exactRating = Number(ratingFilter.value || 0);

                rows.forEach(row => {
                    const matchesSearch = row.dataset.search.includes(query);
                    const ratingValue = Number(row.dataset.rating || 0);
                    const matchesRating = exactRating === 0
                        ? true
                        : (ratingValue > exactRating - 0.5 && ratingValue <= exactRating + 0.001);
                    row.style.display = matchesSearch && matchesRating ? '' : 'none';
                });

                toggleEmptyState();
            };

            searchInput?.addEventListener('input', filterRows);

            ratingInputs.forEach(input => {
                const selectRating = () => {
                    setActiveRating(input.dataset.rating || '');
                    filterRows();
                };

                input.addEventListener('click', selectRating);
                input.addEventListener('mouseenter', () => renderStars(Number(input.dataset.rating)));
                input.addEventListener('mouseleave', () => renderStars(currentRating));
                input.addEventListener('focus', () => renderStars(Number(input.dataset.rating)));
                input.addEventListener('blur', () => renderStars(currentRating));
                input.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        selectRating();
                    }
                });
            });

            ratingReset?.addEventListener('click', () => {
                setActiveRating('');
                filterRows();
            });

            setActiveRating('');
            renderStars(currentRating);
        });
    </script>
</x-admin-layout>
