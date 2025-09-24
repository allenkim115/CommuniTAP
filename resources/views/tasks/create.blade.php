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
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8">
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
                    
                    <div class="space-y-6">
                        <!-- Task Type -->
                        <div>
                            <label for="task_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Task Type <span class="text-red-500">*</span>
                            </label>
                            <select name="task_type" id="task_type" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required>
                                <option value="">Select Task Type</option>
                                <option value="user_uploaded" {{ old('task_type') === 'user_uploaded' ? 'selected' : '' }}>User-Uploaded Task</option>
                            </select>
                            @error('task_type')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Task Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                                   placeholder="Enter task title" required maxlength="100">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Task Description <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" id="description" rows="6" 
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                                      placeholder="Provide detailed description of the task, including objectives, requirements, and expected outcomes" required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Points Awarded -->
                        <div>
                            <label for="points_awarded" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Points to Award <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="points_awarded" id="points_awarded" value="{{ old('points_awarded') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                                   placeholder="Enter points (minimum 1)" min="1" required>
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
                            @error('due_date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Time and Location Section -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Time & Location Details</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Start Time -->
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

                                <!-- End Time -->
                                <div>
                                    <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        End Time <span class="text-red-500">*</span>
                                    </label>
                                    <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required>
                                    @error('end_time')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Location -->
                            <div class="mt-6">
                                <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Location <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="location" id="location" value="{{ old('location') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                                       placeholder="Enter task location (e.g., Community Center, Park, etc.)" maxlength="255" required>
                                @error('location')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Participant Limit -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Participant Limit</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Leave blank for unlimited participants.</p>
                            <input type="number" name="max_participants" id="max_participants" value="{{ old('max_participants') }}"
                                   class="w-full md:w-1/2 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="e.g., 10" min="1">
                            @error('max_participants')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
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
</x-app-layout>
