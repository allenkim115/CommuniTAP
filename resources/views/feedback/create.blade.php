<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-xl bg-brand-orange/15 text-brand-orange-dark dark:bg-orange-900/30 dark:text-brand-peach">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
            </div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Submit Task Feedback') }}
                </h2>
            </div>
        <a href="{{ route('tasks.show', $task) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-brand-teal/40 text-brand-teal font-medium rounded-xl hover:bg-brand-teal/10 dark:hover:bg-gray-700 transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Task
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-white dark:bg-gray-950 transition-colors duration-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
            <!-- Task Info Card -->
            @php
                $rawDueDate = $task->due_date ?? null;
                $dueDateLabel = 'No due date set';

                if ($rawDueDate instanceof \Carbon\CarbonInterface) {
                    $dueDateLabel = $rawDueDate->format('M j, Y');
                } elseif (!empty($rawDueDate)) {
                    $dueDateLabel = \Carbon\Carbon::parse($rawDueDate)->format('M j, Y');
                }
            @endphp
            <div class="bg-white/95 dark:bg-gray-900/70 backdrop-blur rounded-2xl shadow-xl border border-brand-peach/60 dark:border-gray-800 p-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 p-3 rounded-2xl bg-brand-teal/15 text-brand-teal-dark">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $task->title }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">{{ $task->description }}</p>
                        <div class="mt-4 flex flex-wrap gap-3 text-xs font-semibold text-gray-500 dark:text-gray-400">
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-brand-peach/70 text-brand-orange-dark">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-9 8h8"/>
                                </svg>
                                {{ $dueDateLabel }}
                            </span>
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-brand-teal/10 text-brand-teal-dark">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Ready for feedback
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feedback Form Card -->
            <div class="bg-white/95 dark:bg-gray-900/80 rounded-2xl shadow-2xl border border-brand-peach/60 dark:border-gray-800 p-8 space-y-6">
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Share Your Experience</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Your feedback helps us improve and deliver better results.</p>
                </div>

                <form action="{{ route('feedback.store', $task) }}" method="POST" novalidate>
                    @csrf
                    
                    <!-- Rating Section -->
                    <div class="mb-8">
                        <label for="rating" class="block text-base font-semibold text-gray-900 dark:text-white mb-4">
                            How would you rate this task? 
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="bg-brand-peach/20 dark:bg-gray-900/50 rounded-2xl p-6 border border-brand-peach/70 dark:border-gray-800">
                            @php
                                $ratingLabels = [
                                    1 => 'Poor',
                                    2 => 'Fair',
                                    3 => 'Good',
                                    4 => 'Very Good',
                                    5 => 'Excellent'
                                ];
                                $initialRating = old('rating');
                            @endphp
                            <input type="hidden" name="rating" id="rating-value" value="{{ $initialRating }}" required>
                            <div class="flex items-center justify-center gap-3 mb-4" id="rating-container" role="slider" aria-valuemin="0.5" aria-valuemax="5" aria-valuenow="{{ $initialRating ?? 0 }}" aria-valuetext="{{ $initialRating ? ($ratingLabels[(int) round($initialRating)] ?? '') . ' (' . number_format($initialRating, 1) . ' / 5)' : 'No rating selected' }}" tabindex="0">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" class="rating-star-btn group relative" data-rating="{{ $i }}" aria-label="{{ $ratingLabels[$i] }}">
                                        <span class="rating-star" data-rating="{{ $i }}"></span>
                                        <span class="rating-label">{{ $ratingLabels[$i] }}</span>
                                    </button>
                                @endfor
                            </div>
                            <div class="text-center pt-4 border-t border-brand-peach/60 dark:border-gray-800">
                                <p id="rating-text" class="text-lg font-semibold text-gray-600 dark:text-gray-400 min-h-[2rem] transition-all duration-300">
                                    {{ $initialRating ? ($ratingLabels[(int) round($initialRating)] ?? '') . ' (' . number_format($initialRating, 1) . ' / 5)' : 'Select a rating' }}
                                </p>
                            </div>
                        </div>
                        @error('rating')
                            <div class="mt-3 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <!-- Comment Section -->
                    <div class="mb-8 space-y-4">
                        <label for="comment" class="block text-base font-semibold text-gray-900 dark:text-white">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                Feedback Comment
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <div class="relative">
                            <textarea 
                                id="comment" 
                                name="comment" 
                                rows="6" 
                                class="w-full px-4 py-3 border-2 border-brand-peach/70 dark:border-gray-700 rounded-2xl shadow-sm focus:outline-none focus:ring-2 focus:ring-brand-teal focus:border-brand-teal dark:bg-gray-800 dark:text-white transition-all duration-200 resize-none"
                                placeholder="Please share your experience with this task. What went well? What could be improved? Your detailed feedback is valuable to us..."
                                required>{{ old('comment') }}</textarea>
                            <div class="absolute bottom-3 right-3 text-xs text-gray-400 dark:text-gray-500">
                                <span id="char-count">0</span> characters
                            </div>
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div class="rounded-2xl border border-brand-peach/70 bg-brand-peach/20 px-4 py-3 text-xs text-brand-orange-dark dark:text-brand-peach-dark">
                                Be specific about the task outcome, highlight blockers, and mention teammates that helped.
                            </div>
                            <div class="rounded-2xl border border-brand-teal/70 bg-brand-teal/10 px-4 py-3 text-xs text-brand-teal-dark">
                                Add measurable data (time saved, people impacted, etc.) when possible.
                            </div>
                        </div>
                        @error('comment')
                            <div class="mt-3 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex flex-wrap justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('tasks.show', $task) }}" class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold text-brand-teal bg-white dark:bg-gray-800 border-2 border-brand-teal/40 hover:bg-brand-teal/10 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold text-white rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 active:scale-95 bg-brand-orange hover:bg-brand-orange-dark focus:outline-none focus:ring-2 focus:ring-brand-teal focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Submit Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Rating Styles -->
    <style>
        .rating-star-btn {
            background: transparent;
            border: none;
            padding: 0;
            cursor: pointer;
            position: relative;
            transition: transform 0.2s ease;
        }
        .rating-star-btn:focus-visible {
            outline: 2px solid rgba(43, 157, 141, 0.6);
            outline-offset: 6px;
        }
        .rating-star {
            display: block;
            width: 4rem;
            height: 4rem;
            background: #E5E7EB;
            transition: transform 0.2s ease, background 0.2s ease;
            mask: url("data:image/svg+xml,%3Csvg%20viewBox%3D%270%200%2020%2020%27%20xmlns%3D%27http%3A//www.w3.org/2000/svg%27%3E%3Cpath%20fill%3D%27white%27%20d%3D%27M9.049%202.927c.3-.921%201.603-.921%201.902%200l1.07%203.292a1%201%200%2000.95.69h3.462c.969%200%201.371%201.24.588%201.81l-2.8%202.034a1%201%200%2000-.364%201.118l1.07%203.292c.3.921-.755%201.688-1.54%201.118l-2.8-2.034a1%201%200%2000-1.175%200l-2.8%202.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1%201%200%2000-.364-1.118L2.98%208.72c-.783-.57-.38-1.81.588-1.81h3.461a1%201%200%2000.951-.69l1.07-3.292z%27/%3E%3C/svg%3E") no-repeat center / contain;
            -webkit-mask: url("data:image/svg+xml,%3Csvg%20viewBox%3D%270%200%2020%2020%27%20xmlns%3D%27http%3A//www.w3.org/2000/svg%27%3E%3Cpath%20fill%3D%27white%27%20d%3D%27M9.049%202.927c.3-.921%201.603-.921%201.902%200l1.07%203.292a1%201%200%2000.95.69h3.462c.969%200%201.371%201.24.588%201.81l-2.8%202.034a1%201%200%2000-.364%201.118l1.07%203.292c.3.921-.755%201.688-1.54%201.118l-2.8-2.034a1%201%200%2000-1.175%200l-2.8%202.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1%201%200%2000-.364-1.118L2.98%208.72c-.783-.57-.38-1.81.588-1.81h3.461a1%201%200%2000.951-.69l1.07-3.292z%27/%3E%3C/svg%3E") no-repeat center / contain;
        }
        .rating-star-btn:hover .rating-star,
        .rating-star-btn:focus-visible .rating-star {
            transform: scale(1.1);
        }
        .rating-label {
            position: absolute;
            left: 50%;
            bottom: -2rem;
            transform: translateX(-50%);
            font-size: 0.75rem;
            font-weight: 500;
            color: #4B5563;
            background: white;
            border: 1px solid #E5E7EB;
            padding: 0.25rem 0.5rem;
            border-radius: 0.5rem;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease, transform 0.2s ease;
            z-index: 10;
            white-space: nowrap;
        }
        .dark .rating-label {
            background: #1F2937;
            border-color: #374151;
            color: #D1D5DB;
        }
        .rating-star-btn:hover .rating-label,
        .rating-star-btn:focus-visible .rating-label {
            opacity: 1;
            transform: translate(-50%, 0.2rem);
        }
    </style>

    <!-- Rating JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ratingInput = document.getElementById('rating-value');
            const stars = document.querySelectorAll('.rating-star');
            const starButtons = document.querySelectorAll('.rating-star-btn');
            const ratingText = document.getElementById('rating-text');
            const ratingContainer = document.getElementById('rating-container');
            const commentTextarea = document.getElementById('comment');
            const charCount = document.getElementById('char-count');

            const ratingLabels = {
                1: 'Poor',
                2: 'Fair',
                3: 'Good',
                4: 'Very Good',
                5: 'Excellent'
            };

            const formatValue = (value) => {
                const rounded = Math.round(value * 2) / 2;
                if (Number.isNaN(rounded)) {
                    return '';
                }
                return Number.isInteger(rounded) ? String(rounded) : rounded.toFixed(1);
            };

            const getLabelText = (value) => {
                if (!value) {
                    return 'Select a rating';
                }
                const rounded = Math.round(value);
                const label = ratingLabels[rounded] ?? '';
                const displayValue = Number(value).toFixed(1).replace(/\.0$/, '');
                return label ? `${label} (${displayValue} / 5)` : `${displayValue} / 5`;
            };

            const updateRatingText = (value) => {
                if (!ratingText) return;
                ratingText.textContent = getLabelText(value);
                ratingText.classList.toggle('text-brand-orange', Boolean(value));
                ratingText.classList.toggle('dark:text-brand-peach', Boolean(value));
            };

            const applyFill = (star, amount) => {
                const clamped = Math.max(0, Math.min(1, amount));
                const percent = (clamped * 100).toFixed(0);
                star.style.background = `linear-gradient(90deg, #F3A261 0%, #F3A261 ${percent}%, #E5E7EB ${percent}%, #E5E7EB 100%)`;
            };

            const renderStars = (value) => {
                stars.forEach(star => {
                    const starValue = Number(star.dataset.rating);
                    const fillAmount = value ? value - (starValue - 1) : 0;
                    applyFill(star, fillAmount);
                });
                if (ratingContainer) {
                    ratingContainer.setAttribute('aria-valuenow', value || 0);
                    ratingContainer.setAttribute('aria-valuetext', getLabelText(value));
                }
            };

            const getClientX = (event) => {
                if (event.touches?.[0]) return event.touches[0].clientX;
                if (event.changedTouches?.[0]) return event.changedTouches[0].clientX;
                return event.clientX;
            };

            const getValueFromPointer = (event, button) => {
                const star = button.querySelector('.rating-star');
                if (!star) return 0;
                const rect = star.getBoundingClientRect();
                const clientX = getClientX(event);
                const relative = Math.min(Math.max((clientX - rect.left) / rect.width, 0), 1);
                const starIndex = Number(button.dataset.rating);
                const increment = relative <= 0.5 ? 0.5 : 1;
                return Math.min(5, Math.max(0.5, starIndex - 1 + increment));
            };

            const commitValue = (value) => {
                const normalized = formatValue(value);
                if (ratingInput && normalized) {
                    ratingInput.value = normalized;
                }
                selectedRating = Number(normalized);
                renderStars(selectedRating);
                updateRatingText(selectedRating);
            };

            let selectedRating = Number(ratingInput?.value || 0);
            renderStars(selectedRating);
            updateRatingText(selectedRating);

            starButtons.forEach(button => {
                const handleMove = (event) => {
                    const value = getValueFromPointer(event, button);
                    renderStars(value);
                    updateRatingText(value);
                };

                button.addEventListener('mousemove', handleMove);
                button.addEventListener('touchmove', (event) => {
                    event.preventDefault();
                    handleMove(event);
                }, { passive: false });

                button.addEventListener('mouseleave', () => {
                    renderStars(selectedRating);
                    updateRatingText(selectedRating);
                });

                button.addEventListener('click', (event) => {
                    const value = getValueFromPointer(event, button);
                    commitValue(value);
                });

                button.addEventListener('touchend', (event) => {
                    const value = getValueFromPointer(event, button);
                    commitValue(value);
                });
            });

            ratingContainer?.addEventListener('keydown', (event) => {
                if (!['ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(event.key)) {
                    return;
                }
                event.preventDefault();
                let value = selectedRating || 0;
                if (event.key === 'ArrowRight') {
                    value = Math.min(5, (value || 0) + 0.5);
                } else if (event.key === 'ArrowLeft') {
                    value = Math.max(0.5, (value || 0) - 0.5);
                } else if (event.key === 'Home') {
                    value = 0.5;
                } else if (event.key === 'End') {
                    value = 5;
                }
                commitValue(value);
            });

            const updateCharCount = () => {
                if (!commentTextarea || !charCount) {
                    return;
                }

                const length = commentTextarea.value.length;
                charCount.textContent = length;
                if (length > 500) {
                    charCount.style.color = '#F3A261';
                    charCount.classList.add('font-semibold');
                    charCount.classList.remove('text-gray-400', 'dark:text-gray-500');
                } else {
                    charCount.style.color = '';
                    charCount.classList.remove('font-semibold');
                    charCount.classList.add('text-gray-400', 'dark:text-gray-500');
                }
            };

            if (commentTextarea && charCount) {
                updateCharCount();
                commentTextarea.addEventListener('input', updateCharCount);
            }
        });
    </script>
</x-app-layout>
