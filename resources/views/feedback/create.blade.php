<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="p-2 dark:bg-orange-900/30 rounded-lg" style="background-color: rgba(243, 162, 97, 0.1);">
                    <svg class="w-6 h-6 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #F3A261;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Submit Task Feedback') }}
                </h2>
            </div>
            <a href="{{ route('tasks.show', $task) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Task
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-orange-50/30 to-gray-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-900 transition-colors duration-200">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Task Info Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6 transform transition-all duration-300 hover:shadow-xl">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 p-3 dark:bg-blue-900/30 rounded-lg" style="background-color: rgba(43, 157, 141, 0.1);">
                        <svg class="w-6 h-6 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #2B9D8D;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $task->title }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">{{ $task->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Feedback Form Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-8 transform transition-all duration-300">
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Share Your Experience</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Your feedback helps us improve and deliver better results.</p>
                </div>

                <form action="{{ route('feedback.store', $task) }}" method="POST">
                    @csrf
                    
                    <!-- Rating Section -->
                    <div class="mb-8">
                        <label for="rating" class="block text-base font-semibold text-gray-900 dark:text-white mb-4">
                            How would you rate this task? 
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-center gap-3 mb-4" id="rating-container">
                                @php
                                    $ratingLabels = [
                                        1 => 'Poor',
                                        2 => 'Fair',
                                        3 => 'Good',
                                        4 => 'Very Good',
                                        5 => 'Excellent'
                                    ];
                                @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer group relative inline-block" data-rating="{{ $i }}">
                                        <input type="radio" name="rating" value="{{ $i }}" class="sr-only rating-input" 
                                            {{ old('rating') == $i ? 'checked' : '' }}>
                                        <div class="star-wrapper transform transition-all duration-300 group-hover:scale-125 group-active:scale-95">
                                            <svg class="w-16 h-16 star-icon transition-all duration-300 drop-shadow-sm rating-star" 
                                                 viewBox="0 0 20 20"
                                                 xmlns="http://www.w3.org/2000/svg"
                                                 data-rating="{{ $i }}"
                                                 fill="#E5E7EB"
                                                 stroke="#E5E7EB">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        </div>
                                        <span class="absolute -bottom-10 left-1/2 transform -translate-x-1/2 text-xs font-medium text-gray-600 dark:text-gray-400 opacity-0 group-hover:opacity-100 transition-all duration-200 whitespace-nowrap rating-label px-2 py-1 bg-white dark:bg-gray-800 rounded shadow-lg border border-gray-200 dark:border-gray-700 z-10">
                                            {{ $ratingLabels[$i] }}
                                        </span>
                                    </label>
                                @endfor
                            </div>
                            <div class="text-center pt-4 border-t border-gray-200 dark:border-gray-700">
                                <p id="rating-text" class="text-lg font-semibold text-gray-600 dark:text-gray-400 min-h-[2rem] transition-all duration-300">
                                    Select a rating
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
                    <div class="mb-8">
                        <label for="comment" class="block text-base font-semibold text-gray-900 dark:text-white mb-3">
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
                                class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white transition-all duration-200 resize-none"
                                placeholder="Please share your experience with this task. What went well? What could be improved? Your detailed feedback is valuable to us..."
                                required>{{ old('comment') }}</textarea>
                            <div class="absolute bottom-3 right-3 text-xs text-gray-400 dark:text-gray-500">
                                <span id="char-count">0</span> characters
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
                    <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('tasks.show', $task) }}" class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold text-white rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 active:scale-95"
                                style="background-color: #F3A261;"
                                onmouseover="this.style.backgroundColor='#E8944F'"
                                onmouseout="this.style.backgroundColor='#F3A261'">
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
        .star-icon {
            display: block;
            width: 4rem;
            height: 4rem;
        }
        .star-icon path {
            transition: fill 0.2s ease;
        }
        /* Make white stars visible with a subtle border */
        .star-svg.text-white {
            filter: drop-shadow(0 0 1px rgba(0, 0, 0, 0.1));
        }
        .star-svg.text-yellow-400 {
            filter: drop-shadow(0 2px 4px rgba(234, 179, 8, 0.3));
        }
    </style>

    <!-- Rating JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ratingInputs = document.querySelectorAll('input[name="rating"]');
            const stars = document.querySelectorAll('.rating-star');
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

            const setStarColors = (value, hoverValue = null) => {
                const activeValue = hoverValue ?? value;
                stars.forEach(star => {
                    const starValue = Number(star.dataset.rating);
                    const color = activeValue && starValue <= activeValue ? '#FBBF24' : '#E5E7EB';
                    star.setAttribute('fill', color);
                    star.setAttribute('stroke', color);
                });

                if (ratingText) {
                    ratingText.textContent = activeValue ? ratingLabels[activeValue] : 'Select a rating';
                    ratingText.className = activeValue
                        ? 'text-lg font-semibold text-yellow-500 min-h-[2rem] transition-all duration-300'
                        : 'text-lg font-semibold text-gray-600 dark:text-gray-400 min-h-[2rem] transition-all duration-300';
                }
            };

            let selectedRating = Number(document.querySelector('input[name="rating"]:checked')?.value || 0);
            setStarColors(selectedRating || null);

            ratingInputs.forEach(input => {
                input.addEventListener('change', () => {
                    selectedRating = Number(input.value);
                    setStarColors(selectedRating);
                });
            });

            stars.forEach(star => {
                const label = star.closest('label');
                label?.addEventListener('mouseenter', () => {
                    setStarColors(selectedRating, Number(star.dataset.rating));
                });
            });

            ratingContainer?.addEventListener('mouseleave', () => setStarColors(selectedRating || null));

            const updateCharCount = () => {
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
