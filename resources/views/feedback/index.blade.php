<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Feedback') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700/60">
                <div class="px-6 py-6 border-b border-gray-100 dark:border-gray-700/60 space-y-4 md:space-y-0 md:flex md:items-center md:justify-between">
                    <div>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">Feedback history</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Search, filter and revisit your past feedback.</p>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full md:w-auto">
                        <div class="relative flex-1 w-full">
                            <input id="feedback-search" type="text" placeholder="Search tasks or comments..."
                                class="w-full bg-gray-50 dark:bg-gray-900/40 border border-gray-200 dark:border-gray-700 rounded-lg pl-10 pr-4 py-2.5 text-sm text-gray-900 dark:text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/60">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/>
                            </svg>
                        </div>
                        <select id="feedback-rating-filter" class="bg-gray-50 dark:bg-gray-900/40 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/60">
                            <option value="">All ratings</option>
                            <option value="5">Only 5 ★</option>
                            <option value="4">4 ★ & up</option>
                            <option value="3">3 ★ & up</option>
                            <option value="2">2 ★ & up</option>
                            <option value="1">1 ★ & up</option>
                        </select>
                    </div>
                </div>

                @if($feedback->count() > 0)
                    <div class="divide-y divide-gray-100 dark:divide-gray-700/60" id="feedback-list">
                        @foreach($feedback as $f)
                            @php
                                $ratingValue = (float) ($f->rating ?? 0);
                                $filledStars = (int) floor($ratingValue);
                            @endphp
                            <article class="group relative px-6 py-5 transition hover:bg-gray-50/80 dark:hover:bg-gray-800/70 feedback-card"
                                data-rating="{{ $ratingValue }}"
                                data-search="{{ Str::lower($f->task->title.' '.$f->comment) }}">
                                <button type="button" class="absolute inset-0" onclick="window.location.href='{{ route('feedback.edit', $f) }}'">
                                    <span class="sr-only">Open feedback</span>
                                </button>
                                <div class="flex gap-4">
                                    <div class="flex flex-col items-center">
                                        <div class="w-2 h-2 rounded-full bg-indigo-500"></div>
                                        <div class="flex-1 w-px bg-gradient-to-b from-indigo-500/40 to-transparent mt-2"></div>
                                    </div>
                                    <div class="flex-1 space-y-3">
                                        <div class="flex flex-wrap items-center gap-4">
                                            <div class="flex items-center gap-2 text-base font-semibold text-gray-900 dark:text-white">
                                                <div class="flex items-center gap-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @php
                                                            $isFilled = $ratingValue >= $i;
                                                            $fillColor = $isFilled ? '#FBBF24' : '#E5E7EB';
                                                        @endphp
                                                        <svg class="w-5 h-5 {{ $ratingValue === 0 ? 'opacity-40' : '' }}" viewBox="0 0 20 20"
                                                            fill="{{ $fillColor }}" stroke="{{ $fillColor }}">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                    @endfor
                                                </div>
                                                <span class="text-sm text-gray-600 dark:text-gray-300">
                                                    {{ $ratingValue > 0 ? number_format($ratingValue, 2).' out of 5' : 'No rating yet' }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ is_string($f->feedback_date) ? \Carbon\Carbon::parse($f->feedback_date)->format('M j, Y') : $f->feedback_date->format('M j, Y') }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $f->task->title }}</p>
                                            @if($f->task->description)
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-2">{{ Str::limit($f->task->description, 100) }}</p>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-900/60 rounded-lg px-4 py-3 border border-gray-200 dark:border-gray-700/80">
                                            {{ $f->comment }}
                                        </p>
                                    </div>
                                    <div class="self-start">
                                        <svg class="w-5 h-5 text-gray-400 opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700/60">
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
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const searchInput = document.getElementById('feedback-search');
                const ratingFilter = document.getElementById('feedback-rating-filter');
                const cards = Array.from(document.querySelectorAll('.feedback-card'));

                const toggleEmptyState = () => {
                    const hasVisible = cards.some(card => card.style.display !== 'none');
                    document.getElementById('feedback-list')?.classList.toggle('opacity-25', !hasVisible);
                };

                const filterCards = () => {
                    const query = searchInput.value.trim().toLowerCase();
                    const minRating = Number(ratingFilter.value || 0);

                    cards.forEach(card => {
                        const matchesSearch = card.dataset.search.includes(query);
                        const matchesRating = Number(card.dataset.rating) >= minRating;
                        card.style.display = matchesSearch && matchesRating ? '' : 'none';
                    });

                    toggleEmptyState();
                };

                searchInput?.addEventListener('input', filterCards);
                ratingFilter?.addEventListener('change', filterCards);
            });
        </script>
    @endpush
</x-app-layout>


