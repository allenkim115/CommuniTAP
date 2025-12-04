<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Task Feedback') }}
            </h2>
        </div>
    </x-slot>

    <div class="min-h-screen bg-white dark:bg-gray-950 transition-colors duration-200">
        <div class="max-w-3xl mx-auto px-3 sm:px-4 lg:px-8 py-4 sm:py-6 lg:py-8 space-y-4 sm:space-y-6">
            <!-- Task Info -->
            <div class="bg-white/95 dark:bg-gray-900/70 backdrop-blur rounded-xl sm:rounded-2xl shadow-xl border border-brand-peach/60 dark:border-gray-800 p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $feedback->task->title }}</h3>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">{{ $feedback->task->description }}</p>
            </div>

            <!-- Feedback Form -->
            <div class="bg-white/95 dark:bg-gray-900/80 rounded-xl sm:rounded-2xl shadow-2xl border border-brand-peach/60 dark:border-gray-800 p-4 sm:p-6 lg:p-8 space-y-4 sm:space-y-6">
                <form action="{{ route('feedback.update', $feedback) }}" method="POST" novalidate>
                    @csrf
                    @method('PATCH')
                    
                    <!-- Rating -->
                    <div class="mb-6 sm:mb-8">
                        <label for="rating" class="block text-sm sm:text-base font-semibold text-gray-900 dark:text-white mb-3 sm:mb-4">
                            How would you rate this task? <span class="text-red-500">*</span>
                        </label>
                        @php
                            $ratingLabels = [
                                1 => 'Poor',
                                2 => 'Fair',
                                3 => 'Good',
                                4 => 'Very Good',
                                5 => 'Excellent'
                            ];
                            $currentRating = old('rating', $feedback->rating);
                        @endphp
                        <div class="bg-brand-peach/20 dark:bg-gray-900/50 rounded-xl sm:rounded-2xl p-3 sm:p-4 lg:p-6 border border-brand-peach/70 dark:border-gray-800">
                            <input type="hidden" name="rating" id="rating-value" value="{{ $currentRating }}" required>
                            <div class="flex flex-wrap justify-center gap-1.5 sm:gap-2 lg:gap-3 mb-3 sm:mb-4" id="rating-container" role="slider" aria-valuemin="0.5" aria-valuemax="5" aria-valuenow="{{ $currentRating ?? 0 }}" aria-valuetext="{{ $currentRating ? ($ratingLabels[(int) round($currentRating)] ?? '') . ' (' . number_format($currentRating, 1) . ' / 5)' : 'No rating selected' }}" tabindex="0">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" class="rating-star-btn group relative" data-rating="{{ $i }}" aria-label="{{ $ratingLabels[$i] }}">
                                        <span class="rating-star" data-rating="{{ $i }}"></span>
                                        <span class="rating-label">{{ $ratingLabels[$i] }}</span>
                                    </button>
                                @endfor
                            </div>
                            <div class="text-center pt-3 sm:pt-4 border-t border-brand-peach/60 dark:border-gray-800">
                                <p id="rating-text" class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 min-h-[1.25rem] sm:min-h-[1.5rem] px-2">
                                    {{ $currentRating ? ($ratingLabels[(int) round($currentRating)] ?? '') . ' (' . number_format($currentRating, 1) . ' / 5)' : 'Select a rating' }}
                                </p>
                            </div>
                        </div>
                        @error('rating')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Comment -->
                    <div class="space-y-3 sm:space-y-4">
                        <label for="comment" class="block text-sm sm:text-base font-semibold text-gray-900 dark:text-white">
                            Feedback Comment <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <textarea 
                                id="comment" 
                                name="comment" 
                                rows="6" 
                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border-2 border-brand-peach/70 dark:border-gray-700 rounded-xl sm:rounded-2xl shadow-sm focus:outline-none focus:ring-2 focus:ring-brand-teal focus:border-brand-teal dark:bg-gray-800 dark:text-white transition-all duration-200 resize-none"
                                placeholder="Please share your experience with this task..."
                                required>{{ old('comment', $feedback->comment) }}</textarea>
                            <div class="absolute bottom-2 sm:bottom-3 right-2 sm:right-3 text-xs text-gray-400 dark:text-gray-500">
                                <span id="edit-char-count">0</span> characters
                            </div>
                        </div>
                        @error('comment')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 pt-4 sm:pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('feedback.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 sm:py-2.5 text-sm font-semibold text-brand-teal bg-white dark:bg-gray-800 border-2 border-brand-teal/40 hover:bg-brand-teal/10 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                            Cancel
                        </a>
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 sm:py-2.5 text-sm font-semibold text-white bg-brand-orange hover:bg-brand-orange-dark rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-brand-teal focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-900">
                            Update Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @once
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
                width: 2.5rem;
                height: 2.5rem;
                background: #E5E7EB;
                transition: transform 0.2s ease, background 0.2s ease;
                mask: url("data:image/svg+xml,%3Csvg%20viewBox%3D%270%200%2020%2020%27%20xmlns%3D%27http%3A//www.w3.org/2000/svg%27%3E%3Cpath%20fill%3D%27white%27%20d%3D%27M9.049%202.927c.3-.921%201.603-.921%201.902%200l1.07%203.292a1%201%200%2000.95.69h3.462c.969%200%201.371%201.24.588%201.81l-2.8%202.034a1%201%200%2000-.364%201.118l1.07%203.292c.3.921-.755%201.688-1.54%201.118l-2.8-2.034a1%201%200%2000-1.175%200l-2.8%202.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1%201%200%2000-.364-1.118L2.98%208.72c-.783-.57-.38-1.81.588-1.81h3.461a1%201%200%2000.951-.69l1.07-3.292z%27/%3E%3C/svg%3E") no-repeat center / contain;
                -webkit-mask: url("data:image/svg+xml,%3Csvg%20viewBox%3D%270%200%2020%2020%27%20xmlns%3D%27http%3A//www.w3.org/2000/svg%27%3E%3Cpath%20fill%3D%27white%27%20d%3D%27M9.049%202.927c.3-.921%201.603-.921%201.902%200l1.07%203.292a1%201%200%2000.95.69h3.462c.969%200%201.371%201.24.588%201.81l-2.8%202.034a1%201%200%2000-.364%201.118l1.07%203.292c.3.921-.755%201.688-1.54%201.118l-2.8-2.034a1%201%200%2000-1.175%200l-2.8%202.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1%201%200%2000-.364-1.118L2.98%208.72c-.783-.57-.38-1.81.588-1.81h3.461a1%201%200%2000.951-.69l1.07-3.292z%27/%3E%3C/svg%3E") no-repeat center / contain;
            }
            @media (min-width: 640px) {
                .rating-star {
                    width: 3rem;
                    height: 3rem;
                }
            }
            @media (min-width: 1024px) {
                .rating-star {
                    width: 3.5rem;
                    height: 3.5rem;
                }
            }
            .rating-star-btn:hover .rating-star,
            .rating-star-btn:focus-visible .rating-star {
                transform: scale(1.1);
            }
            .rating-label {
                position: absolute;
                left: 50%;
                bottom: -1.5rem;
                transform: translateX(-50%);
                font-size: 0.625rem;
                font-weight: 500;
                color: #4B5563;
                background: white;
                border: 1px solid #E5E7EB;
                padding: 0.2rem 0.4rem;
                border-radius: 0.375rem;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.2s ease, transform 0.2s ease;
                z-index: 10;
                white-space: nowrap;
            }
            @media (min-width: 640px) {
                .rating-label {
                    bottom: -1.75rem;
                    font-size: 0.75rem;
                    padding: 0.25rem 0.5rem;
                    border-radius: 0.5rem;
                }
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
    @endonce

    <!-- Rating JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ratingInput = document.getElementById('rating-value');
            const stars = document.querySelectorAll('.rating-star');
            const starButtons = document.querySelectorAll('.rating-star-btn');
            const ratingText = document.getElementById('rating-text');
            const ratingContainer = document.getElementById('rating-container');
            const commentTextarea = document.getElementById('comment');
            const charCount = document.getElementById('edit-char-count');
            const ratingLabels = @json($ratingLabels);

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
