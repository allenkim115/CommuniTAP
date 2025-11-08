<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                {{ __('Progress') }}
            </h2>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-950 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Toast Notifications -->
            <x-session-toast />
            
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Points Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Total Points</span>
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-900/30">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                    </div>
                    <div class="text-4xl font-bold text-gray-900 dark:text-white mb-1">{{ $userPoints ?? 0 }}</div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Earned from completed tasks</p>
                </div>

                <!-- Completed Tasks Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Completed Tasks</span>
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                    </div>
                    <div class="text-4xl font-bold text-gray-900 dark:text-white mb-1">{{ $completedTasksCount ?? 0 }}</div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Tasks successfully completed</p>
                </div>

                <!-- Claimed Rewards Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Claimed Rewards</span>
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900/30">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                            </svg>
                        </span>
                    </div>
                    <div class="text-4xl font-bold text-gray-900 dark:text-white mb-1">{{ $claimedRewardsCount ?? 0 }}</div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Rewards redeemed</p>
                </div>
            </div>

            <!-- Tasks History Section -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Tasks History
                        </h3>
                        @if($completedTasks && $completedTasks->count() > 0)
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $completedTasks->count() }} task{{ $completedTasks->count() !== 1 ? 's' : '' }}
                            </span>
                        @endif
                    </div>
                    
                    <!-- Filters -->
                    <form method="GET" action="{{ route('progress') }}" class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Start Date
                                </label>
                                <input type="date" 
                                       name="start_date" 
                                       id="start_date" 
                                       value="{{ $startDate ?? '' }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white text-sm">
                            </div>
                            
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    End Date
                                </label>
                                <input type="date" 
                                       name="end_date" 
                                       id="end_date" 
                                       value="{{ $endDate ?? '' }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white text-sm">
                            </div>
                            
                            <div>
                                <label for="task_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Task Type
                                </label>
                                <select name="task_type" 
                                        id="task_type" 
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white text-sm">
                                    <option value="all" {{ ($taskType ?? 'all') === 'all' ? 'selected' : '' }}>All Types</option>
                                    <option value="daily" {{ ($taskType ?? '') === 'daily' ? 'selected' : '' }}>Daily Tasks</option>
                                    <option value="one_time" {{ ($taskType ?? '') === 'one_time' ? 'selected' : '' }}>One-Time Tasks</option>
                                    <option value="user_uploaded" {{ ($taskType ?? '') === 'user_uploaded' ? 'selected' : '' }}>User-Uploaded Tasks</option>
                                </select>
                            </div>
                            
                            <div class="flex items-end gap-2">
                                <button type="submit" 
                                        class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-sm transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                    </svg>
                                    Filter
                                </button>
                                
                                @if($startDate || $endDate || ($taskType && $taskType !== 'all'))
                                <a href="{{ route('progress') }}" 
                                   class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>

                    <!-- Tasks Table -->
                    @if($completedTasks && $completedTasks->count() > 0)
                        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Task Title</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Points</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Location</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Completed Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($completedTasks as $task)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $task->title }}</div>
                                            @if($task->description)
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-1">{{ $task->description }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $typeColors = [
                                                    'daily' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300',
                                                    'one_time' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                                    'user_uploaded' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300'
                                                ];
                                                $typeColor = $typeColors[strtolower($task->task_type)] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
                                            @endphp
                                            <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full {{ $typeColor }}">
                                                {{ ucfirst(str_replace('_', ' ', $task->task_type)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300 rounded-full text-xs font-semibold">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                +{{ $task->points_awarded ?? 0 }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $task->location ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
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
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('tasks.show', $task) }}" 
                                               class="inline-flex items-center gap-1 text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 transition-colors">
                                                View
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <div class="mx-auto w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">No Completed Tasks</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                @if($startDate || $endDate || ($taskType && $taskType !== 'all'))
                                    No tasks match your current filters.
                                @else
                                    You haven't completed any tasks yet. Start completing tasks to see your progress here!
                                @endif
                            </p>
                            @if($startDate || $endDate || ($taskType && $taskType !== 'all'))
                                <a href="{{ route('progress') }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                    Clear Filters
                                </a>
                            @else
                                <a href="{{ route('tasks.index') }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                    Browse Tasks
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Pagination -->
            @if(isset($completedTasks) && method_exists($completedTasks, 'links'))
                <div class="mt-6">
                    {{ $completedTasks->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
