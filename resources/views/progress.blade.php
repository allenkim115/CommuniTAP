<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-bold text-3xl bg-gradient-to-r from-orange-600 via-orange-500 to-teal-500 bg-clip-text text-transparent">
                {{ __('Progress') }}
            </h2>
            <div class="flex items-center gap-3 flex-wrap">
                <a href="{{ route('tasks.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-orange-600 hover:text-orange-700 bg-orange-50 hover:bg-orange-100 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Browse Tasks
                </a>
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-600 hover:text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-50 via-orange-50/30 to-teal-50/20 dark:from-gray-900 dark:via-gray-900 dark:to-gray-950 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Toast Notifications -->
            <x-session-toast />

            <!-- Info Banner -->
            <div class="mb-8 bg-gradient-to-r from-orange-50 to-teal-50 dark:from-orange-900/20 dark:to-teal-900/20 border-l-4 border-orange-500 dark:border-orange-400 rounded-xl p-6 shadow-lg flex items-start gap-4 hover:shadow-xl transition-shadow duration-300">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-orange-500 to-teal-500 flex items-center justify-center shadow-md">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-orange-900 dark:text-orange-100 mb-2">Your Progress Dashboard</h3>
                    <p class="text-sm text-orange-800 dark:text-orange-200 leading-relaxed">
                        Track your completed tasks, earned points, and claimed rewards. 
                        Filter your history by date range and task type to analyze your community engagement.
                    </p>
                </div>
            </div>
            
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Points Card -->
                <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-lg border-2 border-orange-200 dark:border-orange-800 p-6 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                    <!-- Decorative gradient background -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-orange-400/10 to-orange-500/10 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Total Points</span>
                            <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 shadow-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                        </div>
                        <div class="text-4xl font-bold text-gray-900 dark:text-white mb-1">{{ $userPoints ?? 0 }}</div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Earned from completed tasks</p>
                    </div>
                </div>

                <!-- Completed Tasks Card -->
                <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-lg border-2 border-green-200 dark:border-green-800 p-6 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                    <!-- Decorative gradient background -->
                    <div class="absolute top-0 right-0 w-32 h-32 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-500" style="background-color: rgba(43, 157, 141, 0.1);"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Completed Tasks</span>
                            <span class="inline-flex items-center justify-center w-12 h-12 rounded-full shadow-md" style="background-color: #2B9D8D;">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                        </div>
                        <div class="text-4xl font-bold text-gray-900 dark:text-white mb-1">{{ $completedTasksCount ?? 0 }}</div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Tasks successfully completed</p>
                    </div>
                </div>

                <!-- Claimed Rewards Card -->
                <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-lg border-2 border-teal-200 dark:border-teal-800 p-6 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                    <!-- Decorative gradient background -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-teal-400/10 to-teal-500/10 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Claimed Rewards</span>
                            <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-teal-500 to-teal-600 shadow-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                                </svg>
                            </span>
                        </div>
                        <div class="text-4xl font-bold text-gray-900 dark:text-white mb-1">{{ $claimedRewardsCount ?? 0 }}</div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Rewards redeemed</p>
                    </div>
                </div>
            </div>

            <!-- Tasks History Section -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border-2 border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-orange-500 to-teal-500 flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Tasks History</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">View your completed tasks</p>
                            </div>
                        </div>
                        @if($completedTasks && $completedTasks->count() > 0)
                            <div class="px-4 py-2 bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Total: <span class="text-orange-600 dark:text-orange-400">{{ $completedTasks->count() }}</span></span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Filters -->
                    <form method="GET" action="{{ route('progress') }}" id="progress-filter-form" class="bg-gradient-to-r from-gray-50 to-orange-50/30 dark:from-gray-700/50 dark:to-orange-900/10 rounded-xl p-5 mb-6 border-2 border-orange-100 dark:border-orange-900/30 shadow-md">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Start Date
                                </label>
                                <input type="date" 
                                       name="start_date" 
                                       id="start_date" 
                                       value="{{ $startDate ?? '' }}"
                                       class="w-full px-4 py-2.5 border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800 dark:text-white text-sm transition-all duration-200 hover:border-orange-400 hover:shadow-md">
                            </div>
                            
                            <div>
                                <label for="end_date" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    End Date
                                </label>
                                <input type="date" 
                                       name="end_date" 
                                       id="end_date" 
                                       value="{{ $endDate ?? '' }}"
                                       class="w-full px-4 py-2.5 border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800 dark:text-white text-sm transition-all duration-200 hover:border-orange-400 hover:shadow-md">
                            </div>
                            
                            <div>
                                <label for="task_type" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Task Type
                                </label>
                                <div class="relative group">
                                    <select name="task_type" 
                                            id="task_type" 
                                            class="block w-full px-4 py-2.5 border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800 dark:text-white text-sm transition-all duration-200 appearance-none bg-white hover:border-orange-400 hover:shadow-md">
                                        <option value="all" {{ ($taskType ?? 'all') === 'all' ? 'selected' : '' }}>All Types</option>
                                        <option value="daily" {{ ($taskType ?? '') === 'daily' ? 'selected' : '' }}>Daily Tasks</option>
                                        <option value="one_time" {{ ($taskType ?? '') === 'one_time' ? 'selected' : '' }}>One-Time Tasks</option>
                                        <option value="user_uploaded" {{ ($taskType ?? '') === 'user_uploaded' ? 'selected' : '' }}>User-Uploaded Tasks</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-end gap-2">
                                @if($startDate || $endDate || ($taskType && $taskType !== 'all'))
                                    <a href="{{ route('progress') }}" 
                                       class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-bold rounded-xl hover:bg-gray-300 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Clear
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                    
                    <script>
                        // Auto-update tasks via AJAX when filters change (no page refresh)
                        document.addEventListener('DOMContentLoaded', function() {
                            const filterForm = document.getElementById('progress-filter-form');
                            const startDateInput = document.getElementById('start_date');
                            const endDateInput = document.getElementById('end_date');
                            const taskTypeSelect = document.getElementById('task_type');

                            function updateTasks() {
                                const tableContainer = document.getElementById('tasks-table-container');
                                const paginationContainer = document.getElementById('tasks-pagination');
                                
                                if (tableContainer) {
                                    tableContainer.innerHTML = '<div class="p-8 text-center"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-orange-600"></div><p class="mt-2 text-gray-600 dark:text-gray-400">Loading tasks...</p></div>';
                                }

                                const formData = new FormData(filterForm);
                                const params = new URLSearchParams(formData);

                                fetch(`{{ route('progress') }}?${params.toString()}`, {
                                    method: 'GET',
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'Accept': 'text/html',
                                    }
                                })
                                .then(response => response.text())
                                .then(html => {
                                    const parser = new DOMParser();
                                    const doc = parser.parseFromString(html, 'text/html');
                                    
                                    const newTableContainer = doc.getElementById('tasks-table-container');
                                    const newPagination = doc.getElementById('tasks-pagination');
                                    
                                    if (newTableContainer && tableContainer) {
                                        tableContainer.innerHTML = newTableContainer.innerHTML;
                                    }
                                    
                                    if (newPagination && paginationContainer) {
                                        paginationContainer.innerHTML = newPagination.innerHTML;
                                    }
                                    
                                    const newUrl = `{{ route('progress') }}?${params.toString()}`;
                                    window.history.pushState({path: newUrl}, '', newUrl);
                                })
                                .catch(error => {
                                    console.error('Error updating tasks:', error);
                                    if (tableContainer) {
                                        tableContainer.innerHTML = '<div class="p-8 text-center" style="color: #2B9D8D;">Error loading tasks. Please refresh the page.</div>';
                                    }
                                });
                            }

                            if (startDateInput) {
                                startDateInput.addEventListener('change', updateTasks);
                            }
                            
                            if (endDateInput) {
                                endDateInput.addEventListener('change', updateTasks);
                            }
                            
                            if (taskTypeSelect) {
                                taskTypeSelect.addEventListener('change', updateTasks);
                            }
                        });
                    </script>

                    <!-- Tasks Table -->
                    <div id="tasks-table-container">
                    @if($completedTasks && $completedTasks->count() > 0)
                        <div class="overflow-x-auto rounded-xl border-2 border-gray-200 dark:border-gray-700 shadow-md">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gradient-to-r from-gray-50 to-orange-50/30 dark:from-gray-700/50 dark:to-orange-900/10">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider w-2/5">Task Title</th>
                                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider w-1/12">Type</th>
                                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider w-1/12">Points</th>
                                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider w-1/6">Location</th>
                                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider w-1/6">Completed Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($completedTasks as $task)
                                    <tr class="hover:bg-gradient-to-r hover:from-orange-50/50 hover:to-teal-50/30 dark:hover:from-orange-900/10 dark:hover:to-teal-900/10 transition-all duration-200 cursor-pointer task-row group" 
                                        data-task-id="{{ $task->taskId }}"
                                        onclick="window.location.href='{{ route('tasks.show', $task->taskId) }}'">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">{{ $task->title }}</div>
                                            @if($task->description)
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-1">{{ $task->description }}</div>
                                            @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                            @php
                                                $typeColors = [
                                                    'daily' => '#FED2B3',
                                                    'one_time' => '#2B9D8D',
                                                    'user_uploaded' => '#2B9D8D'
                                                ];
                                                $bgColor = $typeColors[strtolower($task->task_type)] ?? '#2B9D8D';
                                            @endphp
                                            <span class="px-3 py-1.5 inline-flex text-xs font-bold rounded-lg shadow-md text-white" style="background-color: {{ $bgColor }};">
                                            {{ ucfirst(str_replace('_', ' ', $task->task_type)) }}
                                        </span>
                                    </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg text-xs font-bold shadow-md">
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                                +{{ $task->points_awarded ?? 0 }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $task->location ?? 'N/A' }}
                                            </div>
                                    </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                @if(isset($task->pivot) && $task->pivot->completed_at)
                                                    {{ is_string($task->pivot->completed_at) ? \Carbon\Carbon::parse($task->pivot->completed_at)->format('M j, Y') : $task->pivot->completed_at->format('M j, Y') }}
                                                @elseif($task->due_date)
                                                    {{ is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date)->format('M j, Y') : $task->due_date->format('M j, Y') }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                            @if($task->start_time && $task->end_time)
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ \Carbon\Carbon::parse($task->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($task->end_time)->format('g:i A') }}
                                                </div>
                                            @elseif($task->start_time)
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ \Carbon\Carbon::parse($task->start_time)->format('g:i A') }}
                                                </div>
                                        @endif
                                    </td>
                                </tr>
                                    @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <!-- Empty State -->
                        <div class="bg-white dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-3xl shadow-xl p-12 text-center">
                            <div class="mx-auto w-24 h-24 mb-6 rounded-full bg-gradient-to-br from-orange-100 to-teal-100 dark:from-orange-900/30 dark:to-teal-900/30 flex items-center justify-center">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-12 h-12 text-orange-500 dark:text-orange-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-3">No Completed Tasks</h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-2 text-lg">
                                @if($startDate || $endDate || ($taskType && $taskType !== 'all'))
                                    No tasks match your current filters.
                                @else
                                    You haven't completed any tasks yet.
                                @endif
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                                @if($startDate || $endDate || ($taskType && $taskType !== 'all'))
                                    Try adjusting your filters or clear them to see all tasks.
                                @else
                                    Start completing tasks to see your progress here!
                                @endif
                            </p>
                            @if($startDate || $endDate || ($taskType && $taskType !== 'all'))
                                <a href="{{ route('progress') }}" 
                                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-600 to-teal-500 hover:from-orange-700 hover:to-teal-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Clear Filters
                                </a>
                            @else
                                <a href="{{ route('tasks.index') }}" 
                                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-600 to-teal-500 hover:from-orange-700 hover:to-teal-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Browse Tasks
                                </a>
                            @endif
                        </div>
                    @endif
                    </div>
                </div>
            </div>
            
            <!-- Pagination -->
            <div id="tasks-pagination">
            @if(isset($completedTasks) && method_exists($completedTasks, 'links'))
                <div class="mt-8 flex justify-center">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-2 border border-gray-200 dark:border-gray-700">
                        {{ $completedTasks->links() }}
                    </div>
                </div>
            @endif
            </div>
        </div>
    </div>

    <style>
        @keyframes card-enter {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .grid > div {
            animation: card-enter 0.5s ease-out forwards;
        }
        
        .grid > div:nth-child(1) { animation-delay: 0.1s; }
        .grid > div:nth-child(2) { animation-delay: 0.2s; }
        .grid > div:nth-child(3) { animation-delay: 0.3s; }
        .grid > div:nth-child(4) { animation-delay: 0.4s; }
        .grid > div:nth-child(5) { animation-delay: 0.5s; }
        .grid > div:nth-child(6) { animation-delay: 0.6s; }
        
        .grid > div {
            opacity: 0;
        }

        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }

        .task-row:hover {
            transform: translateX(4px);
        }
    </style>
</x-app-layout>
