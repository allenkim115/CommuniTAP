<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                {{ __('Create Task') }}
            </h2>
            <a href="{{ route('admin.tasks.index') }}"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-white text-sm font-semibold py-3 sm:py-2 px-4 rounded-lg transition-colors min-h-[44px]"
                style="background-color: #2B9D8D;"
                onmouseover="this.style.backgroundColor='#248A7C'"
                onmouseout="this.style.backgroundColor='#2B9D8D'">
                <i class="fas fa-arrow-left text-base"></i>
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 sm:p-6 lg:p-8 space-y-4 sm:space-y-6">
                
                <!-- Guidelines Section -->
                <div class="rounded-lg border-l-4 p-4" style="background-color: rgba(43, 157, 141, 0.1); border-color: #2B9D8D;">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Admin Task Creation Guidelines</h3>
                    <ul class="list-disc list-inside text-sm text-gray-700 space-y-1">
                        <li><strong>Daily Tasks:</strong> Recurring tasks that appear daily</li>
                        <li><strong>One-Time Tasks:</strong> Tasks that appear weekly or monthly</li>
                    </ul>
                </div>

                <!-- Form Section -->
                <form action="{{ route('admin.tasks.store') }}" method="POST" class="space-y-8" novalidate>
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Left column -->
                        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm space-y-6">
                            <h4 class="text-lg font-semibold text-gray-900">Basic Information</h4>

                            <!-- Task Type -->
                            <div>
                                <label for="task_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Task Type <span class="text-red-500">*</span>
                                </label>
                                <select name="task_type" id="task_type" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white" 
                                    required>
                                    <option value="">Select Task Type</option>
                                    <option value="daily" {{ old('task_type') === 'daily' ? 'selected' : '' }}>Daily Task</option>
                                    <option value="one_time" {{ old('task_type') === 'one_time' ? 'selected' : '' }}>One-Time Task (Weekly/Monthly)</option>
                                </select>
                                @error('task_type')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Task Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                                    placeholder="Enter task title" required maxlength="100">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Min 10, max 100 characters.</p>
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Task Description <span class="text-red-500">*</span>
                                </label>
                                <textarea name="description" id="description" rows="7" minlength="10" maxlength="1000"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                                    placeholder="Provide details: objectives, requirements, and outcomes"
                                    required>{{ old('description') }}</textarea>
                                <div class="mt-1 flex justify-between text-xs text-gray-500 dark:text-gray-400">
                                    <p>Be specific about objectives and expected results.</p>
                                    <p id="description-counter">0/1000</p>
                                </div>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Right column -->
                        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm space-y-6">
                            <h4 class="text-lg font-semibold text-gray-900">Schedule & Rewards</h4>

                            <!-- Points -->
                            <div>
                                <label for="points_awarded" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Points to Award <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="points_awarded" id="points_awarded" value="{{ old('points_awarded') }}"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                                    placeholder="e.g., 50" min="1" required>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Recommended: 5–100 points depending on effort.</p>
                                @error('points_awarded')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Due Date -->
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Due Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                                    min="{{ date('Y-m-d') }}" required>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Due date must be today or later.</p>
                                @error('due_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Start & End Time -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Start Time <span class="text-red-500">*</span>
                                    </label>
                                    <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white @error('start_time') border-red-500 @enderror"
                                        required>
                                    @error('start_time')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        End Time <span class="text-red-500">*</span>
                                    </label>
                                    <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white @error('end_time') border-red-500 @enderror"
                                        required>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Ensure end time is after start time.</p>
                                    @error('end_time')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Location -->
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Location <span class="text-red-500">*</span>
                                </label>
                                <select name="location" id="location"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                                    required>
                                    <option value="" disabled selected hidden>Select a sitio</option>
                                    @foreach(['Pig Vendor','Ermita Proper','Kastilaan','Sitio Bato','YHC','Eyeseekers','Panagdait','Kawit'] as $loc)
                                        <option value="{{ $loc }}" {{ old('location') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                                    @endforeach
                                </select>
                                @error('location')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Participant Limit -->
                            <div>
                                <label for="max_participants" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Participant Limit
                                </label>
                                <input type="number" name="max_participants" id="max_participants"
                                    value="{{ old('max_participants') }}"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                                    placeholder="Leave blank for unlimited" min="1">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave blank for unlimited participants.</p>
                                @error('max_participants')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-end gap-3 sm:gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.tasks.index') }}"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3 sm:py-2.5 rounded-lg text-sm font-semibold border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition min-h-[44px]">
                            Cancel
                        </a>
                        <button type="submit" name="action" value="create"
                            class="w-full sm:w-auto px-6 py-3 sm:py-2.5 text-sm font-semibold rounded-lg text-white shadow-md transition focus:ring-2 focus:ring-orange-400 focus:ring-offset-2 min-h-[44px] whitespace-nowrap"
                            style="background-color: #F3A261;"
                            onmouseover="this.style.backgroundColor='#E8944F'"
                            onmouseout="this.style.backgroundColor='#F3A261'">
                            Create Task
                        </button>
                        <button type="submit" name="action" value="create_and_publish"
                            class="w-full sm:w-auto px-6 py-3 sm:py-2.5 text-sm font-semibold rounded-lg text-white shadow-md transition focus:ring-2 focus:ring-teal-400 focus:ring-offset-2 min-h-[44px] whitespace-nowrap"
                            style="background-color: #2B9D8D;"
                            onmouseover="this.style.backgroundColor='#248A7C'"
                            onmouseout="this.style.backgroundColor='#2B9D8D'">
                            <span class="hidden sm:inline">Create and Publish Task</span>
                            <span class="sm:hidden">Create & Publish</span>
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
</x-admin-layout>
