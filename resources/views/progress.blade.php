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
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider w-2/5">Task Title</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/12">Type</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/12">Points</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/6">Location</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/6">Completed Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($completedTasks as $task)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700/70 transition-colors cursor-pointer task-row" 
                                        data-task-id="{{ $task->taskId }}"
                                        onclick="openTaskModal({{ $task->taskId }})">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $task->title }}</div>
                                            @if($task->description)
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-1">{{ $task->description }}</div>
                                            @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
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
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300 rounded-full text-xs font-semibold">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                +{{ $task->points_awarded ?? 0 }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $task->location ?? 'N/A' }}
                                            </div>
                                    </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
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

    <!-- Task Details Modal -->
    <div id="task-modal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-black/50" onclick="closeTaskModal()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-2xl bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden">
                <div class="px-6 py-0" style="display:none"></div>
                <div id="task-modal-content" class="p-6 space-y-4">
                    <!-- Dynamic content -->
                </div>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end gap-2">
                    <a id="task-modal-view-link" href="#" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 rounded-md text-sm">Open full page</a>
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-md text-sm" onclick="closeTaskModal()">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for task details modal -->
    <script>
        // Store task data for JavaScript access
        const tasks = @json($completedTasks->keyBy('taskId'));
        const userAssignments = @json(collect());

        function openTaskModal(taskId) {
            const task = tasks[taskId];
            if (!task) return;

            const modal = document.getElementById('task-modal');
            const contentEl = document.getElementById('task-modal-content');
            const viewLink = document.getElementById('task-modal-view-link');

            // Compute uploader name
            const uploaderName = (() => {
                const u = task.assigned_user;
                if (!u) return 'Admin';
                const computed = u.name || [u.firstName, u.middleName, u.lastName].filter(Boolean).join(' ').trim();
                return computed && computed.length > 0 ? computed : 'Admin';
            })();

            // Compute date text
            const dateText = task.due_date ?
                (typeof task.due_date === 'string' ? new Date(task.due_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : new Date(task.due_date.date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })) :
                (task.published_date ? (typeof task.published_date === 'string' ? new Date(task.published_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : new Date(task.published_date.date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })) : 'Not specified');

            // Compute time text
            const timeText = (task.start_time && task.end_time)
                ? (new Date('2000-01-01T' + task.start_time).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }) + ' - ' + new Date('2000-01-01T' + task.end_time).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }))
                : (task.start_time ? (new Date('2000-01-01T' + task.start_time).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }) + ' onwards') : 'Flexible');

            // Compute completed date
            const completedDate = (() => {
                if (task.pivot && task.pivot.completed_at) {
                    const date = typeof task.pivot.completed_at === 'string' ? new Date(task.pivot.completed_at) : new Date(task.pivot.completed_at.date);
                    return date.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
                }
                return 'N/A';
            })();

            const isCreator = task.FK1_userId && Number(task.FK1_userId) === Number({{ auth()->user()->userId }});

            viewLink.href = `/tasks/${taskId}`;

            contentEl.innerHTML = `
                <div class="border-b border-gray-200 dark:border-gray-700 -mt-2 -mx-6 px-6">
                    <div class="flex items-center justify-between">
                        <nav class="flex space-x-8" aria-label="Tabs">
                            <button id="modal-details-tab" class="py-4 px-1 border-b-2 border-orange-500 font-medium text-sm text-orange-600 dark:text-orange-400">Details</button>
                            <button id="modal-participants-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">Participants</button>
                        </nav>
                        <button type="button" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white py-4" onclick="closeTaskModal()" aria-label="Close modal">✕</button>
                    </div>
                </div>
                <div class="pt-6">
                <div id="modal-details-content">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">${task.title}</h2>
                    <div class="mb-4">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            Completed
                        </span>
                    </div>
                    <div class="space-y-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Uploaded by:</strong> ${uploaderName}</p>
                    <p class="text-gray-700 dark:text-gray-300">${task.description || ''}</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-sm text-gray-600 dark:text-gray-400"><strong>Date:</strong> ${dateText}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-sm text-gray-600 dark:text-gray-400"><strong>Time:</strong> ${timeText}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-gray-800" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                            <span class="text-sm text-gray-600 dark:text-gray-400"><strong>Location:</strong> ${task.location || 'Community'}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-gray-800" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 6a2 2 0 114 0 2 2 0 01-4 0zm8 0a2 2 0 114 0 2 2 0 01-4 0z" clip-rule="evenodd"></path></svg>
                            <span class="text-sm text-gray-600 dark:text-gray-400"><strong>Type:</strong> ${task.task_type ? task.task_type.replace('_',' ').replace(/\b\w/g, l => l.toUpperCase()) : ''}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-gray-800" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <span class="text-sm text-gray-600 dark:text-gray-400"><strong>Points:</strong> ${((task.points_awarded !== undefined && task.points_awarded !== null) ? task.points_awarded : '')}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-gray-800" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span class="text-sm text-gray-600 dark:text-gray-400"><strong>Completed:</strong> ${completedDate}</span>
                        </div>
                    </div>
                    </div>
                </div>
                <div id="modal-participants-content" class="hidden">
                    <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3">Participants ${task.assignments && task.assignments.length ? `(${task.assignments.length})` : ''}</h4>
                    ${task.assignments && task.assignments.length > 0 ?
                        task.assignments.map(assignment => `
                            <div class="flex items-center justify-between p-3 mb-2 last:mb-0 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300">${assignment.user && assignment.user.name ? assignment.user.name.substring(0,2).toUpperCase() : 'U'}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">${assignment.user && assignment.user.name ? assignment.user.name : 'Unknown User'}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">${assignment.user && assignment.user.email ? assignment.user.email : ''}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-1 text-xs rounded-full ${assignment.status === 'assigned' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : assignment.status === 'submitted' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : assignment.status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'}">${assignment.status ? assignment.status.charAt(0).toUpperCase() + assignment.status.slice(1) : ''}</span>
                                </div>
                            </div>
                        `).join('') :
                        '<p class="text-sm text-gray-500 dark:text-gray-400">No participants yet.</p>'
                    }
                </div>
                </div>
            `;

            // Wire modal tabs
            const mdTab = document.getElementById('modal-details-tab');
            const mpTab = document.getElementById('modal-participants-tab');
            const mdContent = document.getElementById('modal-details-content');
            const mpContent = document.getElementById('modal-participants-content');
            function setModalTab(tab){
                if(tab==='details'){
                    mdTab.classList.add('border-orange-500','text-orange-600','dark:text-orange-400');
                    mdTab.classList.remove('border-transparent','text-gray-500','dark:text-gray-400');
                    mpTab.classList.remove('border-orange-500','text-orange-600','dark:text-orange-400');
                    mpTab.classList.add('border-transparent','text-gray-500','dark:text-gray-400');
                    mdContent.classList.remove('hidden');
                    mpContent.classList.add('hidden');
                } else {
                    mpTab.classList.add('border-orange-500','text-orange-600','dark:text-orange-400');
                    mpTab.classList.remove('border-transparent','text-gray-500','dark:text-gray-400');
                    mdTab.classList.remove('border-orange-500','text-orange-600','dark:text-orange-400');
                    mdTab.classList.add('border-transparent','text-gray-500','dark:text-gray-400');
                    mpContent.classList.remove('hidden');
                    mdContent.classList.add('hidden');
                }
            }
            if(mdTab && mpTab){
                mdTab.addEventListener('click', ()=>setModalTab('details'));
                mpTab.addEventListener('click', ()=>setModalTab('participants'));
                setModalTab('details');
            }

            modal.classList.remove('hidden');
        }

        function closeTaskModal() {
            const modal = document.getElementById('task-modal');
            if (modal) modal.classList.add('hidden');
        }

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeTaskModal();
        });
    </script>
</x-app-layout>
