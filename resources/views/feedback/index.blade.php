<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Feedbacks') }}
        </h2>
    </x-slot>

    @php
        $totalFeedback = method_exists($feedback, 'total') ? $feedback->total() : $feedback->count();
    @endphp

    @php
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
    <div class="min-h-screen bg-white dark:bg-gray-950 transition-colors duration-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
            <div class="bg-white/90 dark:bg-gray-900/80 backdrop-blur rounded-2xl shadow-xl border border-brand-peach/50 dark:border-gray-800">
                <div class="px-6 py-6 border-b border-gray-100/70 dark:border-gray-800 space-y-4 md:space-y-0 md:flex md:items-center md:justify-between">
                    <div>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">Feedback history</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Search, filter and revisit your past feedback.</p>
                        <div class="inline-flex items-center gap-2 px-3 py-1 mt-3 text-xs font-semibold rounded-full bg-brand-peach/70 text-brand-orange-dark dark:text-brand-peach-dark">
                            <span class="inline-flex h-2 w-2 rounded-full bg-brand-orange"></span>
                            {{ $totalFeedback }} total records
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:flex-wrap md:flex-nowrap gap-3 w-full sm:items-center sm:justify-end md:ml-auto md:w-auto">
                        <div class="relative w-full sm:flex-none sm:min-w-[220px] sm:max-w-[360px]">
                            <input id="feedback-search" type="text" placeholder="Search tasks or comments..."
                                class="w-full bg-white dark:bg-gray-900/40 border border-gray-200/80 dark:border-gray-700 rounded-lg pl-9 pr-3 py-2 text-sm text-gray-900 dark:text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-teal/60 focus:border-brand-teal/50 transition">
                            <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-brand-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/>
                            </svg>
                        </div>
                        <input type="hidden" id="feedback-rating-filter" value="">
                        <div class="flex flex-col gap-1 w-full sm:w-auto sm:max-w-[220px]">
                            <div class="flex items-center justify-end">
                                <button type="button" id="rating-filter-reset" class="text-[10px] font-semibold text-brand-teal hover:text-brand-teal-dark transition uppercase tracking-[0.2em]">
                                    Reset
                                </button>
                            </div>
                            <div class="rating-filter-stars flex items-center justify-between gap-1.5 pt-0.5">
                                @for ($i = 1; $i <= 5; $i++)
                                    <div class="rating-filter-star-wrapper relative flex items-center justify-center">
                                        <span class="rating-filter-star" data-star="{{ $i }}"></span>
                                        @foreach ([$i - 0.5, $i] as $portion)
                                            @php
                                                $formatted = rtrim(number_format($portion, 1), '.0');
                                                $tooltip = $ratingFilterLabels[$portion] ?? "{$formatted}★ focus";
                                                $positionClass = $loop->first ? 'rating-filter-hit--left' : 'rating-filter-hit--right';
                                            @endphp
                                            <button type="button"
                                                class="rating-filter-hit {{ $positionClass }} focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-brand-teal/60 focus-visible:ring-offset-white dark:focus-visible:ring-offset-gray-900"
                                                data-rating="{{ $portion }}"
                                                aria-label="Minimum {{ $formatted }} star rating"
                                                aria-pressed="false">
                                                <span class="rating-filter-tooltip">{{ $tooltip }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                @endfor
                            </div>
                            <p id="rating-filter-label" class="text-[9px] text-gray-500 dark:text-gray-400">Showing all ratings</p>
                        </div>
                    </div>
                </div>

                @if($feedback->count() > 0)
                    <div class="divide-y divide-gray-100 dark:divide-gray-800" id="feedback-list">
                        @foreach($feedback as $f)
                            @php
                                $ratingValue = (float) ($f->rating ?? 0);
                                $filledStars = (int) floor($ratingValue);
                            @endphp
                            <article class="group relative px-6 py-5 transition hover:bg-brand-peach/20 dark:hover:bg-gray-800/70 feedback-card"
                                data-rating="{{ $ratingValue }}"
                                data-search="{{ Str::lower($f->task->title.' '.$f->comment) }}">
                                <button type="button" class="absolute inset-0" onclick="window.location.href='{{ route('feedback.edit', $f) }}'">
                                    <span class="sr-only">Open feedback</span>
                                </button>
                                <div class="flex gap-4">
                                    <div class="flex flex-col items-center">
                                        <div class="w-2 h-2 rounded-full bg-brand-teal"></div>
                                        <div class="flex-1 w-px bg-gradient-to-b from-brand-teal/60 to-transparent mt-2"></div>
                                    </div>
                                    <div class="flex-1 space-y-3">
                                        <div class="flex flex-wrap items-center gap-4">
                                            <div class="flex items-center gap-2 text-base font-semibold text-gray-900 dark:text-white">
                                            <div class="flex items-center gap-1">
                                                <x-rating-stars :value="$ratingValue" size="md" class="{{ $ratingValue === 0 ? 'opacity-40' : '' }}" />
                                            </div>
                                                <span class="inline-flex items-center text-xs font-semibold uppercase tracking-wide px-2.5 py-0.5 rounded-full bg-brand-peach/70 text-brand-orange-dark">
                                                    {{ $ratingValue > 0 ? number_format($ratingValue, 1).' / 5' : 'Awaiting rating' }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2">
                                                <span class="h-1.5 w-1.5 rounded-full bg-brand-orange"></span>
                                                {{ is_string($f->feedback_date) ? \Carbon\Carbon::parse($f->feedback_date)->format('M j, Y') : $f->feedback_date->format('M j, Y') }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $f->task->title }}</p>
                                            @if($f->task->description)
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-2">{{ Str::limit($f->task->description, 100) }}</p>
                                            @endif
                                        </div>
                                        <div class="flex flex-col gap-2">
                                            <p class="text-sm text-gray-700 dark:text-gray-200 bg-brand-peach/30 dark:bg-gray-900/60 rounded-xl px-4 py-3 border border-brand-peach/60 dark:border-gray-800">
                                                {{ $f->comment }}
                                            </p>
                                            <div class="flex flex-wrap gap-3 text-xs text-gray-500 dark:text-gray-400">
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                                    <svg class="w-3.5 h-3.5 text-brand-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Submitted
                                                </span>
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                                    <svg class="w-3.5 h-3.5 text-brand-orange-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V4a3 3 0 10-6 0v1.083A6 6 0 002 11v3.159c0 .538-.214 1.055-.595 1.436L0 17h5"/>
                                                    </svg>
                                                    Task: {{ Str::limit($f->task->title, 24) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="self-start">
                                        <svg class="w-5 h-5 text-brand-teal opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 flex flex-col gap-3">
                        <div id="feedback-empty-state" class="hidden rounded-xl border border-dashed border-brand-orange/60 bg-brand-peach/30 text-brand-orange-dark text-sm px-4 py-3">
                            We couldn’t find feedback that matches those filters. Try clearing them or using different keywords.
                        </div>
                        {{ $feedback->links() }}
                    </div>
                @else
                    <div class="px-6 py-16 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-900/60 mb-4">
                            <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <p class="text-base font-medium text-gray-900 dark:text-white">No feedback yet</p>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Once you start sharing feedback, it will appear here in a beautiful timeline.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <style>
        .rating-filter-star-wrapper {
            position: relative;
            width: 1.65rem;
            height: 1.65rem;
        }
        .rating-filter-star {
            --rating-star-base: #E5E7EB;
            display: block;
            width: 100%;
            height: 100%;
            background: var(--rating-star-base);
            mask: url("data:image/svg+xml,%3Csvg%20viewBox%3D%270%200%2020%2020%27%20xmlns%3D%27http%3A//www.w3.org/2000/svg%27%3E%3Cpath%20fill%3D%27white%27%20d%3D%27M9.049%202.927c.3-.921%201.603-.921%201.902%200l1.07%203.292a1%201%200%2000.95.69h3.462c.969%200%201.371%201.24.588%201.81l-2.8%202.034a1%201%200%2000-.364%201.118l1.07%203.292c.3.921-.755%201.688-1.54%201.118l-2.8-2.034a1%201%200%2000-1.175%200l-2.8%202.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1%201%200%2000-.364-1.118L2.98%208.72c-.783-.57-.38-1.81.588-1.81h3.461a1%201%200%2000.951-.69l1.07-3.292z%27/%3E%3C/svg%3E") no-repeat center / contain;
            -webkit-mask: url("data:image/svg+xml,%3Csvg%20viewBox%3D%270%200%2020%2020%27%20xmlns%3D%27http%3A//www.w3.org/2000/svg%27%3E%3Cpath%20fill%3D%27white%27%20d%3D%27M9.049%202.927c.3-.921%201.603-.921%201.902%200l1.07%203.292a1%201%200%2000.95.69h3.462c.969%200%201.371%201.24.588%201.81l-2.8%202.034a1%201%200%2000-.364%201.118l1.07%203.292c.3.921-.755%201.688-1.54%201.118l-2.8-2.034a1%201%200%2000-1.175%200l-2.8%202.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1%201%200%2000-.364-1.118L2.98%208.72c-.783-.57-.38-1.81.588-1.81h3.461a1%201%200%2000.951-.69l1.07-3.292z%27/%3E%3C/svg%3E") no-repeat center / contain;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            box-shadow: none;
        }
        .rating-filter-star-wrapper:hover .rating-filter-star,
        .rating-filter-star-wrapper:focus-within .rating-filter-star {
            transform: scale(1.06);
        }
        .dark .rating-filter-star {
            --rating-star-base: #374151;
        }
        .rating-filter-hit {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 50%;
            background: transparent;
            border: none;
            padding: 0;
            cursor: pointer;
        }
        .rating-filter-hit--left {
            left: 0;
        }
        .rating-filter-hit--right {
            right: 0;
        }
        .rating-filter-tooltip {
            position: absolute;
            bottom: -2rem;
            left: 50%;
            transform: translateX(-50%);
            background: #fff;
            border: 1px solid #E5E7EB;
            padding: 0.2rem 0.5rem;
            border-radius: 0.5rem;
            font-size: 0.7rem;
            font-weight: 500;
            color: #4B5563;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease, transform 0.2s ease;
            white-space: nowrap;
            z-index: 10;
        }
        .dark .rating-filter-tooltip {
            background: #1F2937;
            border-color: #374151;
            color: #D1D5DB;
        }
        .rating-filter-hit:hover .rating-filter-tooltip,
        .rating-filter-hit:focus-visible .rating-filter-tooltip {
            opacity: 1;
            transform: translate(-50%, 0.2rem);
        }
    </style>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const searchInput = document.getElementById('feedback-search');
                const ratingFilter = document.getElementById('feedback-rating-filter');
                const ratingInputs = Array.from(document.querySelectorAll('.rating-filter-hit'));
                const ratingStars = Array.from(document.querySelectorAll('.rating-filter-star'));
                const ratingLabel = document.getElementById('rating-filter-label');
                const ratingReset = document.getElementById('rating-filter-reset');
                const cards = Array.from(document.querySelectorAll('.feedback-card'));
                const emptyState = document.getElementById('feedback-empty-state');
                const list = document.getElementById('feedback-list');
                let currentRating = 0;

                const toggleEmptyState = () => {
                    const hasVisible = cards.some(card => card.style.display !== 'none');
                    list?.classList.toggle('opacity-25', !hasVisible);
                    emptyState?.classList.toggle('hidden', hasVisible);
                };

                const ratingMessages = @json($ratingFilterMessages);

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

                    ratingInputs.forEach(button => {
                        button.blur();
                    });

                    renderStars(currentRating);

                    if (ratingLabel) {
                        ratingLabel.textContent = ratingMessages[numericValue] ?? ratingMessages[''];
                    }

                    if (ratingReset) {
                        ratingReset.classList.toggle('opacity-50', numericValue === '');
                        ratingReset.classList.toggle('pointer-events-none', numericValue === '');
                    }
                };

                const filterCards = () => {
                    const query = searchInput.value.trim().toLowerCase();
                    const exactRating = Number(ratingFilter.value || 0);

                    cards.forEach(card => {
                        const matchesSearch = card.dataset.search.includes(query);
                        const ratingValue = Number(card.dataset.rating);
                        const matchesRating = exactRating === 0
                            ? true
                            : (ratingValue > exactRating - 0.5 && ratingValue <= exactRating + 0.001);
                        card.style.display = matchesSearch && matchesRating ? '' : 'none';
                    });

                    toggleEmptyState();
                };

                searchInput?.addEventListener('input', filterCards);
                ratingInputs.forEach(input => {
                    const selectRating = () => {
                        setActiveRating(input.dataset.rating || '');
                        filterCards();
                    };

                    input.addEventListener('click', selectRating);
                    input.addEventListener('mouseenter', () => {
                        renderStars(Number(input.dataset.rating));
                    });
                    input.addEventListener('mouseleave', () => {
                        renderStars(currentRating);
                    });
                    input.addEventListener('focus', () => {
                        renderStars(Number(input.dataset.rating));
                    });
                    input.addEventListener('blur', () => {
                        renderStars(currentRating);
                    });
                    input.addEventListener('keydown', (event) => {
                        if (event.key === 'Enter' || event.key === ' ') {
                            event.preventDefault();
                            selectRating();
                        }
                    });
                });

                ratingReset?.addEventListener('click', () => {
                    setActiveRating('');
                    filterCards();
                });

                setActiveRating('');
                renderStars(currentRating);
            });
        </script>
    @endpush
</x-app-layout>


