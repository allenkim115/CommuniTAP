<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create Task Proposal') }}
            </h2>
            <a href="{{ route('tasks.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Tasks
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8 border border-gray-200 dark:border-gray-700">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-2">Task Proposal Guidelines</h3>
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                            <li>• Only User-Uploaded tasks can be created by regular users</li>
                            <li>• Daily Tasks and One-Time Tasks can only be created by administrators</li>
                            <li>• All task proposals require admin approval before being published</li>
                            <li>• Provide clear and detailed descriptions for better approval chances</li>
                        </ul>
                    </div>
                </div>

                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Left column -->
                        <div class="space-y-6">
                            <h4 class="text-base font-semibold text-gray-900 dark:text-white">Basic Information</h4>
                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Task Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                                       placeholder="Enter task title" required maxlength="100">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max 100 characters.</p>
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description (full width on large screens) -->
                            <div class="lg:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Task Description <span class="text-red-500">*</span>
                                </label>
                                <textarea name="description" id="description" rows="10" maxlength="1000"
                                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                                          placeholder="Provide detailed description of the task, including objectives, requirements, and expected outcomes" required>{{ old('description') }}</textarea>
                                <div class="mt-1 flex items-center justify-between">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Be specific about objectives, requirements, and expected outcomes.</p>
                                    <p id="description-counter" class="text-xs text-gray-500 dark:text-gray-400">0/1000</p>
                                </div>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Right column -->
                        <div class="space-y-6">
                            <h4 class="text-base font-semibold text-gray-900 dark:text-white">Schedule & Rewards</h4>
                            <!-- Points Awarded -->
                            <div>
                                <label for="points_awarded" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Points to Award <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="points_awarded" id="points_awarded" value="{{ old('points_awarded') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                                       placeholder="Enter points (minimum 1)" min="1" required>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Recommended: 5–100 points depending on effort.</p>
                                @error('points_awarded')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Due Date -->
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Due Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Must be at least one day from today.</p>
                                @error('due_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Time -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Start Time <span class="text-red-500">*</span>
                                    </label>
                                    <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required>
                                    @error('start_time')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        End Time <span class="text-red-500">*</span>
                                    </label>
                                    <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Ensure end time is after start time.</p>
                                    @error('end_time')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Location -->
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Location <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="location" id="location" value="{{ old('location') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                                       placeholder="Enter task location (e.g., Community Center, Park, etc.)" maxlength="255" required>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Include venue name and any helpful directions.</p>
                                @error('location')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Participant Limit -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Participant Limit</label>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Leave blank for unlimited participants.</p>
                                <input type="number" name="max_participants" id="max_participants" value="{{ old('max_participants') }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="e.g., 10" min="1">
                                @error('max_participants')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-end space-x-4">
                        <a href="{{ route('tasks.index') }}" 
                           class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Submit Task Proposal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        (function() {
            const textarea = document.getElementById('description');
            const counter = document.getElementById('description-counter');
            if (textarea && counter) {
                const update = () => {
                    const len = textarea.value.length;
                    counter.textContent = `${len}/1000`;
                };
                textarea.addEventListener('input', update);
                update();
            }
        })();
    </script>
</x-app-layout>
