<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-bold text-3xl bg-gradient-to-r from-orange-600 via-orange-500 to-teal-500 bg-clip-text text-transparent">
                {{ __('Edit Task Proposal') }}
            </h2>
            <a href="{{ route('tasks.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-600 hover:text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Tasks
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-50 via-orange-50/30 to-teal-50/20 dark:from-gray-900 dark:via-gray-900 dark:to-gray-950 transition-colors duration-200">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-8">
                @php
                    $isUncompleted = !in_array($task->status, ['completed']) && !in_array($task->status, ['approved', 'published']);
                    $showTwoButtons = $isUncompleted || $task->status === 'inactive';
                @endphp
                @if($isUncompleted)
                <div class="mb-6">
                    <div class="dark:from-blue-900/20 dark:to-cyan-900/20 border-l-4 dark:border-blue-400 rounded-xl p-6 shadow-lg" style="background-color: rgba(43, 157, 141, 0.1); border-color: #2B9D8D;">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #2B9D8D;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-blue-800 dark:text-blue-200">
                                    <strong>Note:</strong> Choose "Update Task" to set status to "Pending" (requires admin approval) or "Update & Publish" to publish directly.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="mb-6">
                    <div class="bg-gradient-to-r from-yellow-50 to-amber-50 dark:from-yellow-900/20 dark:to-amber-900/20 border-l-4 border-yellow-500 dark:border-yellow-400 rounded-xl p-6 shadow-lg">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-yellow-800 dark:text-yellow-200">
                                    <strong>Note:</strong> Editing this task will reset its status to "Pending" and require admin approval again.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <form action="{{ route('tasks.update', $task) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <!-- Task Type (Read-only) -->
                        <div>
                            <label for="task_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Task Type
                            </label>
                            <input type="text" value="{{ ucfirst(str_replace('_', ' ', $task->task_type)) }}" 
                                   class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-400" 
                                   readonly>
                        </div>

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Task Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}" 
                                   class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white transition-all" 
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
                                      class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white transition-all" 
                                      placeholder="Provide detailed description of the task, including objectives, requirements, and expected outcomes" required>{{ old('description', $task->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Points Awarded -->
                        <div>
                            <label for="points_awarded" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Points to Award <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="points_awarded" id="points_awarded" value="{{ old('points_awarded', $task->points_awarded) }}" 
                                   class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white transition-all" 
                                   placeholder="Enter points (minimum 1)" min="1" required>
                            @error('points_awarded')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Due Date (Optional)
                            </label>
                            <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $task->due_date ? (is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : $task->due_date->format('Y-m-d')) : '') }}" 
                                   class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white transition-all" 
                                   min="{{ date('Y-m-d') }}">
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
                                        Start Time (Optional)
                                    </label>
                                    <input type="time" name="start_time" id="start_time" value="{{ old('start_time', $task->start_time ? (\Carbon\Carbon::parse($task->start_time)->format('H:i')) : '') }}" 
                                           class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white transition-all">
                                    @error('start_time')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- End Time -->
                                <div>
                                    <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        End Time (Optional)
                                    </label>
                                    <input type="time" name="end_time" id="end_time" value="{{ old('end_time', $task->end_time ? (\Carbon\Carbon::parse($task->end_time)->format('H:i')) : '') }}" 
                                           class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white transition-all">
                                    @error('end_time')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Location -->
                            <div class="mt-6">
                                <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Location (Optional)
                                </label>
                                <input type="text" name="location" id="location" value="{{ old('location', $task->location) }}" 
                                       class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white transition-all" 
                                       placeholder="Enter task location (e.g., Community Center, Park, etc.)" maxlength="255">
                                @error('location')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Current Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Current Status
                            </label>
                            <div class="px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-gray-100 dark:bg-gray-600">
                                <span class="px-4 py-1.5 text-xs font-bold bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 dark:from-yellow-900/30 dark:to-amber-900/30 dark:text-yellow-300 rounded-lg shadow-sm">
                                    {{ ucfirst($task->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="mt-8 flex justify-end space-x-4">
                        <a href="{{ route('tasks.index') }}" 
                           class="px-6 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm text-sm font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-200">
                            Cancel
                        </a>
                        @if($showTwoButtons)
                            <button type="submit" name="action" value="update" 
                                    class="px-6 py-3 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-xl"
                                    style="background-color: #2B9D8D;"
                                    onmouseover="this.style.backgroundColor='#248A7C'"
                                    onmouseout="this.style.backgroundColor='#2B9D8D'">
                                Update Task
                            </button>
                            <button type="submit" name="action" value="publish" 
                                    class="px-6 py-3 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-xl"
                                    style="background-color: #F3A261;"
                                    onmouseover="this.style.backgroundColor='#E8944F'"
                                    onmouseout="this.style.backgroundColor='#F3A261'">
                                Update & Publish
                            </button>
                        @else
                            <button type="submit" name="action" value="update" 
                                    class="px-6 py-3 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-xl"
                                    style="background-color: #F3A261;"
                                    onmouseover="this.style.backgroundColor='#E8944F'"
                                    onmouseout="this.style.backgroundColor='#F3A261'">
                                Update Task Proposal
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
