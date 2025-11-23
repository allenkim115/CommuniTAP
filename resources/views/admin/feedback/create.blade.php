<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create Admin Feedback') }}
            </h2>
            <a href="{{ route('admin.feedback.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Feedback
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Task Info -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $task->title }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $task->description }}</p>
                <div class="mt-3 flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                    <span><strong>Points:</strong> {{ $task->points_awarded }}</span>
                    <span><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $task->task_type)) }}</span>
                    <span><strong>Status:</strong> {{ ucfirst($task->status) }}</span>
                </div>
            </div>

            <!-- Feedback Form -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <form action="{{ route('admin.feedback.store', $task) }}" method="POST">
                    @csrf
                    
                    <!-- Rating -->
                    <div class="mb-6">
                        <label for="rating" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Rating <span class="text-red-500">*</span>
                        </label>
                        <div class="flex space-x-2">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="rating" value="{{ $i }}" class="sr-only" required>
                                    <svg class="w-8 h-8 text-gray-300 transition-colors" fill="currentColor" viewBox="0 0 20 20"
                                         onmouseover="this.style.color='#FED2B3';"
                                         onmouseout="this.style.color='#D1D5DB';">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </label>
                            @endfor
                        </div>
                        @error('rating')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Comment -->
                    <div class="mb-6">
                        <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Admin Feedback Comment <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            id="comment" 
                            name="comment" 
                            rows="6" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white"
                            placeholder="Provide admin feedback about this task..."
                            required>{{ old('comment') }}</textarea>
                        @error('comment')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.feedback.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-md transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 rounded-md transition-colors">
                            Submit Admin Feedback
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
            
            ratingInputs.forEach((input, index) => {
                input.addEventListener('change', function() {
                    // Update star colors based on selection
                    ratingInputs.forEach((star, starIndex) => {
                        const svg = star.nextElementSibling;
                        if (starIndex < index + 1) {
                            svg.style.color = '#FED2B3';
                        } else {
                            svg.style.color = '#D1D5DB';
                        }
                    });
                });
            });
        });
    </script>
</x-admin-layout>
