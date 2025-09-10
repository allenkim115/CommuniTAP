<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Progress') }}
            </h2>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Toast Notifications -->
            <x-session-toast />
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Points Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8">
                    <div class="text-center">
                        <div class="text-5xl font-extrabold text-orange-400 mb-2">{{ $userPoints ?? 0 }}</div>
                        <div class="text-lg font-semibold text-gray-700 dark:text-gray-300">Points</div>
                    </div>
                </div>

                <!-- Completed Tasks Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8">
                    <div class="text-center">
                        <div class="text-5xl font-extrabold text-orange-400 mb-2">{{ $completedTasksCount ?? 0 }}</div>
                        <div class="text-lg font-semibold text-gray-700 dark:text-gray-300">Completed Tasks</div>
                    </div>
                </div>

                <!-- Claimed Rewards Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8">
                    <div class="text-center">
                        <div class="text-5xl font-extrabold text-orange-400 mb-2">{{ $claimedRewardsCount ?? 0 }}</div>
                        <div class="text-lg font-semibold text-gray-700 dark:text-gray-300">Claimed Rewards</div>
                    </div>
                </div>
            </div>

            <!-- Tasks History Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6">Tasks History</h3>
                    
                    <!-- Filters -->
                    <form method="GET" action="{{ route('progress') }}" class="flex flex-wrap gap-4 mb-6">
                        <div class="flex items-center gap-2">
                            <label for="start_date" class="text-sm font-medium text-gray-700 dark:text-gray-300">Start Date</label>
                            <input type="date" 
                                   name="start_date" 
                                   id="start_date" 
                                   value="{{ $startDate ?? '' }}"
                                   class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <label for="end_date" class="text-sm font-medium text-gray-700 dark:text-gray-300">End Date</label>
                            <input type="date" 
                                   name="end_date" 
                                   id="end_date" 
                                   value="{{ $endDate ?? '' }}"
                                   class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <label for="task_type" class="text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                            <select name="task_type" 
                                    id="task_type" 
                                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="all" {{ ($taskType ?? 'all') === 'all' ? 'selected' : '' }}>All Types</option>
                                <option value="daily" {{ ($taskType ?? '') === 'daily' ? 'selected' : '' }}>Daily Tasks</option>
                                <option value="one_time" {{ ($taskType ?? '') === 'one_time' ? 'selected' : '' }}>One-Time Tasks</option>
                                <option value="user_uploaded" {{ ($taskType ?? '') === 'user_uploaded' ? 'selected' : '' }}>User-Uploaded Tasks</option>
                            </select>
                        </div>
                        
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Filter
                        </button>
                        
                        @if($startDate || $endDate || ($taskType && $taskType !== 'all'))
                        <a href="{{ route('progress') }}" 
                           class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Clear Filters
                        </a>
                        @endif
                    </form>

                    <!-- Tasks Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Task Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Location</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Event Date and Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created At</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($completedTasks as $task)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $task->title }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                            {{ ucfirst(str_replace('_', ' ', $task->task_type)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $task->location ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        @if($task->due_date)
                                            {{ is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date)->format('M. j, Y') : $task->due_date->format('M. j, Y') }}
                                            @if($task->start_time && $task->end_time)
                                                at {{ \Carbon\Carbon::parse($task->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($task->end_time)->format('g:i A') }}
                                            @elseif($task->start_time)
                                                at {{ \Carbon\Carbon::parse($task->start_time)->format('g:i A') }}
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ is_string($task->creation_date) ? \Carbon\Carbon::parse($task->creation_date)->format('M. j, Y') : $task->creation_date->format('M. j, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('tasks.show', $task) }}" 
                                           class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No completed tasks found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Theme Toggle JavaScript -->
    <script>
        // Force light mode and clear any dark mode preference
        localStorage.setItem('theme', 'light');
        document.documentElement.classList.remove('dark');
        
        // Show the moon icon (indicating we're in light mode and can switch to dark)
        document.getElementById('theme-toggle-light-icon').classList.remove('hidden');
        document.getElementById('theme-toggle-dark-icon').classList.add('hidden');

        // Theme toggle functionality
        document.getElementById('theme-toggle').addEventListener('click', function() {
            const isDark = document.documentElement.classList.contains('dark');
            
            if (isDark) {
                // Switch to light mode
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                document.getElementById('theme-toggle-dark-icon').classList.add('hidden');
                document.getElementById('theme-toggle-light-icon').classList.remove('hidden');
            } else {
                // Switch to dark mode
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                document.getElementById('theme-toggle-light-icon').classList.add('hidden');
                document.getElementById('theme-toggle-dark-icon').classList.remove('hidden');
            }
        });
    </script>
</x-app-layout>
