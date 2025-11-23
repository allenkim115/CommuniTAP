<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Task Feedback') }}
            </h2>
            <a href="{{ route('tasks.show', $feedback->task) }}" class="text-white font-bold py-2 px-4 rounded transition-colors"
               style="background-color: #2B9D8D;"
               onmouseover="this.style.backgroundColor='#248A7C'"
               onmouseout="this.style.backgroundColor='#2B9D8D'">
                Back to Task
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Task Info -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $feedback->task->title }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $feedback->task->description }}</p>
            </div>

            <!-- Feedback Form -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <form action="{{ route('feedback.update', $feedback) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <!-- Rating -->
                    <div class="mb-6">
                        <label for="rating" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            How would you rate this task? <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-1 mb-2" id="rating-container">
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
                            @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer group relative" data-rating="{{ $i }}">
                                    <input type="radio" name="rating" value="{{ $i }}" class="sr-only rating-input" required
                                           {{ $currentRating == $i ? 'checked' : '' }}>
                                    <div class="star-wrapper">
                                        <svg class="w-12 h-12 transition-all duration-200 group-hover:scale-110 rating-star" 
                                             data-rating="{{ $i }}"
                                             viewBox="0 0 20 20"
                                             fill="#E5E7EB"
                                             stroke="#E5E7EB">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    </div>
                                    <span class="absolute -bottom-6 left-1/2 transform -translate-x-1/2 text-xs text-gray-500 dark:text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap rating-label">
                                        {{ $ratingLabels[$i] }}
                                    </span>
                                </label>
                            @endfor
                        </div>
                        <div class="mt-8 text-center">
                            <p id="rating-text" class="text-sm font-medium text-gray-600 dark:text-gray-400 min-h-[1.5rem]">
                                @if($currentRating)
                                    {{ $ratingLabels[$currentRating] }}
                                @else
                                    Select a rating
                                @endif
                            </p>
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
                    <div class="mb-6">
                        <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Feedback Comment <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            id="comment" 
                            name="comment" 
                            rows="6" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white"
                            placeholder="Please share your experience with this task..."
                            required>{{ old('comment', $feedback->comment) }}</textarea>
                        @error('comment')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('tasks.show', $feedback->task) }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-md transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 rounded-md transition-colors">
                            Update Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Rating JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ratingInputs = document.querySelectorAll('input[name="rating"]');
            const stars = document.querySelectorAll('.rating-star');
            const ratingText = document.getElementById('rating-text');
            const ratingLabels = @json($ratingLabels);

            const updateStars = (value) => {
                stars.forEach(star => {
                    const starValue = Number(star.dataset.rating);
                    const color = starValue <= value ? '#FBBF24' : '#E5E7EB';
                    star.setAttribute('fill', color);
                    star.setAttribute('stroke', color);
                });

                if (ratingText) {
                    ratingText.textContent = value ? ratingLabels[value] : 'Select a rating';
                }
            };

            const initialValue = Number({{ $currentRating ?? 0 }});
            updateStars(initialValue);

            ratingInputs.forEach(input => {
                input.addEventListener('change', () => {
                    updateStars(Number(input.value));
                });
            });
        });
    </script>
</x-app-layout>
