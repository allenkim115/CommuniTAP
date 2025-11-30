<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-bold text-3xl bg-clip-text text-transparent" style="background: linear-gradient(to right, #F3A261, #2B9D8D); -webkit-background-clip: text;">
                {{ __('Tasks') }}
            </h2>
            <div class="flex items-center gap-3 flex-wrap">
                <a href="{{ route('tasks.my-uploads') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-xl transition-all duration-200 shadow-sm hover:shadow-md"
                   style="color: #2B9D8D; background-color: rgba(43, 157, 141, 0.1);"
                   onmouseover="this.style.color='#248A7C'; this.style.backgroundColor='rgba(43, 157, 141, 0.2)';"
                   onmouseout="this.style.color='#2B9D8D'; this.style.backgroundColor='rgba(43, 157, 141, 0.1)';">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a2 2 0 012-2h4l2 2h6a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                    </svg>
                    Uploaded Tasks
                </a>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-50 via-orange-50/30 to-teal-50/20 dark:from-gray-900 dark:via-gray-900 dark:to-gray-950 py-10">
        <style>
            /* Ensure hidden task cards don't take up space in grid */
            .task-card.hidden {
                display: none !important;
            }
            /* Smooth transition for filtering */
            .task-card {
                transition: opacity 0.2s ease-in-out, transform 0.2s ease-in-out;
            }
        </style>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Toast Notifications -->
            <x-session-toast />

            <!-- Task Filter Tabs -->
            <div class="mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-2 border border-gray-200 dark:border-gray-700">
                    <nav class="flex space-x-2" aria-label="Tabs">
                        <a href="{{ route('tasks.index', ['filter' => 'available']) }}" 
                           class="flex-1 text-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ $filter === 'available' ? 'text-white shadow-md' : 'text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                           @if($filter === 'available') style="background-color: #F3A261;" @endif>
                            Available ({{ $taskStats['available'] }})
                        </a>
                        <a href="{{ route('tasks.index', ['filter' => 'assigned']) }}" 
                           class="flex-1 text-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ $filter === 'assigned' ? 'text-white shadow-md' : 'text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                           @if($filter === 'assigned') style="background-color: #F3A261;" @endif>
                            Assigned ({{ $taskStats['assigned'] }})
                        </a>
                        <a href="{{ route('tasks.index', ['filter' => 'submitted']) }}" 
                           class="flex-1 text-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ $filter === 'submitted' ? 'text-white shadow-md' : 'text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                           @if($filter === 'submitted') style="background-color: #F3A261;" @endif>
                            Submitted ({{ $taskStats['submitted'] }})
                        </a>
                        <a href="{{ route('tasks.index', ['filter' => 'completed']) }}" 
                           class="flex-1 text-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ $filter === 'completed' ? 'text-white shadow-md' : 'text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                           @if($filter === 'completed') style="background-color: #F3A261;" @endif>
                            Completed ({{ $taskStats['completed'] }})
                        </a>
                        <a href="{{ route('tasks.index', ['filter' => 'all']) }}" 
                           class="flex-1 text-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ $filter === 'all' ? 'text-white shadow-md' : 'text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                           @if($filter === 'all') style="background-color: #F3A261;" @endif>
                            All ({{ $taskStats['all'] }})
                        </a>
                    </nav>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8">
                <!-- Left Column - Filtered Tasks -->
                <div class="col-span-1">
                    <div class="mb-8">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-lg" style="background-color: #F3A261;">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                        @switch($filter)
                                            @case('available')
                                                Available Tasks
                                                @break
                                            @case('assigned')
                                                Assigned Tasks
                                                @break
                                            @case('submitted')
                                                Submitted Tasks
                                                @break
                                            @case('completed')
                                                Completed Tasks
                                                @break
                                            @case('all')
                                                All Tasks
                                                @break
                                            @default
                                                Available Tasks
                                        @endswitch
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Browse and manage community tasks</p>
                                </div>
                            </div>
                            <!-- Search and Filter Controls -->
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                                <!-- Search Input -->
                                <div class="relative flex-1 sm:flex-initial sm:w-64">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           id="task-search" 
                                           placeholder="Search tasks..." 
                                           class="block w-full pl-10 pr-3 py-2.5 text-sm bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500"
                                           oninput="if(typeof applyFilters === 'function') applyFilters();">
                                    <button type="button" 
                                            id="clear-search" 
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center hidden"
                                            onclick="document.getElementById('task-search').value = ''; if(typeof applyFilters === 'function') applyFilters();">
                                        <svg class="h-5 w-5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <!-- Task Type Filter -->
                                <div class="px-4 py-2 bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
                                    <select id="task-type-filter" 
                                            class="text-sm font-semibold text-gray-700 dark:text-gray-300 bg-transparent border-none focus:outline-none focus:ring-0 cursor-pointer"
                                            onchange="if(typeof applyFilters === 'function') applyFilters();">
                                        <option value="all">All Types</option>
                                        <option value="daily">Daily Tasks</option>
                                        <option value="one_time">One-Time Tasks</option>
                                        <option value="user_uploaded">User-Uploaded Tasks</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($filteredTasks->count() > 0)
                        <!-- Task cards grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="tasks-grid">
                            @foreach($filteredTasks as $task)
                            <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 border-2 border-gray-200 dark:border-gray-700 overflow-hidden cursor-pointer task-card task-type-{{ $task->task_type }}" 
                                 data-task-id="{{ $task->taskId }}" 
                                 data-task-type="{{ $task->task_type }}"
                                 data-task-title="{{ strtolower($task->title) }}"
                                 data-task-description="{{ strtolower($task->description) }}"
                                 data-task-location="{{ strtolower($task->location ?? '') }}"
                                 data-task-uploader="{{ strtolower($task->assignedUser?->name ?? 'admin') }}"
                                 onclick="openTaskModal({{ $task->taskId }})">
                                 
                                <!-- Decorative gradient background -->
                                <div class="absolute top-0 right-0 w-32 h-32 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-500" style="background-color: rgba(243, 162, 97, 0.1);"></div>
                                <!-- Shine effect on hover -->
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                                
                                <div class="relative">
                                    <!-- Task Header -->
                                    <div class="flex justify-between items-start mb-3 gap-3">
                                        <h4 class="font-bold text-gray-900 dark:text-white text-lg leading-tight flex-1 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                                            {{ $task->title }}
                                        </h4>
                                        <div class="flex flex-col items-end gap-2 flex-shrink-0">
                                            <span class="px-3 py-1.5 text-xs font-bold text-white rounded-lg shadow-md" style="background-color: #2B9D8D;">
                                                {{ ucwords(str_replace('_', ' ', $task->task_type)) }}
                                            </span>
                                            @if(isset($task->pivot) && $task->pivot->status)
                                                @php
                                                    $statusBg = 'rgba(229, 231, 235, 0.5)';
                                                    $statusColor = '#6B7280';
                                                    if($task->pivot->status === 'assigned' || $task->pivot->status === 'completed') {
                                                        $statusBg = 'rgba(43, 157, 141, 0.2)';
                                                        $statusColor = '#2B9D8D';
                                                    } elseif($task->pivot->status === 'submitted') {
                                                        $statusBg = 'rgba(254, 210, 179, 0.2)';
                                                        $statusColor = '#FED2B3';
                                                    }
                                                @endphp
                                                <span class="px-3 py-1.5 text-xs font-bold rounded-lg dark:bg-gray-900 dark:text-gray-200" style="background-color: {{ $statusBg }}; color: {{ $statusColor }};">
                                                    {{ ucfirst($task->pivot->status) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Description -->
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2 leading-relaxed">
                                        {{ $task->description }}
                                    </p>
                                    
                                    <!-- Badges: Points, Date -->
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        <span class="inline-flex items-center gap-1 bg-gradient-to-r from-orange-500 to-orange-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-md">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            +{{ $task->points_awarded }} pts
                                        </span>
                                        <span class="inline-flex items-center gap-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-1.5 rounded-lg text-xs font-medium">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            @if($task->due_date)
                                                {{ is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date)->format('M j, Y') : $task->due_date->format('M j, Y') }}
                                            @else
                                                {{ is_string($task->published_date) ? \Carbon\Carbon::parse($task->published_date)->format('M j, Y') : (optional($task->published_date)?->format('M j, Y') ?? 'No date') }}
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <!-- Uploader Info -->
                                    <div class="flex items-center gap-2 mb-4 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl text-sm">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-teal-100 dark:bg-teal-900/30 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-gray-900 dark:text-gray-100 truncate">
                                                @php $uploader = $task->assignedUser; @endphp
                                                {{ $uploader?->name ?? 'Admin' }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Uploader</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Action Link -->
                                    <div class="pt-4 border-t-2 border-gray-100 dark:border-gray-700">
                                        <a href="#" onclick="event.stopPropagation(); openTaskModal({{ $task->taskId }}); return false;" 
                                           class="inline-flex items-center gap-2 text-orange-600 hover:text-orange-700 dark:text-orange-400 dark:hover:text-orange-300 text-sm font-semibold transition-colors">
                                            View Details
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- No tasks available after filtering message -->
                        <div id="no-tasks-filtered" class="hidden bg-white dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-3xl shadow-xl p-12 text-center">
                            <div class="mx-auto w-24 h-24 mb-6 rounded-full dark:from-orange-900/30 dark:to-teal-900/30 flex items-center justify-center" style="background-color: rgba(243, 162, 97, 0.2);">
                                <svg class="w-12 h-12 text-orange-500 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-3">No Tasks Available</h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-2 text-lg">
                                No tasks match the selected filter criteria.
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Try adjusting your search query or selecting a different task type.</p>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="bg-white dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-3xl shadow-xl p-12 text-center">
                            <div class="mx-auto w-24 h-24 mb-6 rounded-full dark:from-orange-900/30 dark:to-teal-900/30 flex items-center justify-center" style="background-color: rgba(243, 162, 97, 0.2);">
                                <svg class="w-12 h-12 text-orange-500 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-3">No Tasks Found</h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-2 text-lg">
                                @switch($filter)
                                    @case('available')
                                        No available tasks at the moment
                                        @break
                                    @case('assigned')
                                        No assigned tasks
                                        @break
                                    @case('submitted')
                                        No submitted tasks
                                        @break
                                    @case('completed')
                                        No completed tasks
                                        @break
                                    @case('all')
                                        No tasks found
                                        @break
                                    @default
                                        No tasks available
                                @endswitch
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Check back later.</p>
                        </div>
                    @endif
                </div>

                
            </div>
        </div>
    </div>

    <!-- Task Details Modal -->
    <div id="task-modal" class="fixed inset-0 z-50 hidden" aria-hidden="true" style="backdrop-filter: blur(4px);">
        <div class="absolute inset-0 bg-black/50" onclick="closeTaskModal()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-2xl bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden border-2 border-orange-300 dark:border-orange-700 transform transition-all">
                <div class="px-6 py-0" style="display:none"></div>
                <div id="task-modal-content" class="p-6 space-y-4">
                    <!-- Dynamic content -->
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for task details -->
    <script>
        // Store task data for JavaScript access
        // Ensure assignments are included in serialization
        @php
            // Ensure all tasks have assignments loaded with users
            $filteredTasks->loadMissing('assignments.user');
            
            // Build tasks array with assignments explicitly included
            $tasksForJson = [];
            foreach ($filteredTasks as $task) {
                // Get assignments - ensure it's always a collection
                $assignments = $task->assignments ?? collect();
                
                $taskData = [
                    'taskId' => $task->taskId,
                    'title' => $task->title,
                    'description' => $task->description,
                    'points_awarded' => $task->points_awarded,
                    'task_type' => $task->task_type,
                    'status' => $task->status,
                    'location' => $task->location,
                    'due_date' => $task->due_date,
                    'start_time' => $task->start_time,
                    'end_time' => $task->end_time,
                    'published_date' => $task->published_date,
                    'creation_date' => $task->creation_date,
                    'FK1_userId' => $task->FK1_userId,
                    'max_participants' => $task->max_participants,
                    'assigned_user' => $task->assignedUser ? [
                        'name' => $task->assignedUser->name,
                        'firstName' => $task->assignedUser->firstName,
                        'middleName' => $task->assignedUser->middleName,
                        'lastName' => $task->assignedUser->lastName,
                        'email' => $task->assignedUser->email,
                    ] : null,
                    'assignments' => $assignments->map(function($assignment) {
                        return [
                            'id' => $assignment->assignmentId ?? $assignment->id ?? null,
                            'userId' => $assignment->userId,
                            'taskId' => $assignment->taskId,
                            'status' => $assignment->status,
                            'progress' => $assignment->progress,
                            'user' => $assignment->user ? [
                                'userId' => $assignment->user->userId,
                                'name' => $assignment->user->name ?? (trim(($assignment->user->firstName ?? '') . ' ' . ($assignment->user->lastName ?? ''))),
                                'firstName' => $assignment->user->firstName ?? null,
                                'middleName' => $assignment->user->middleName ?? null,
                                'lastName' => $assignment->user->lastName ?? null,
                                'email' => $assignment->user->email ?? null,
                            ] : null,
                        ];
                    })->values()->toArray(),
                ];
                $tasksForJson[$task->taskId] = $taskData;
            }
        @endphp
        const tasks = @json($tasksForJson);
        const userAssignments = @json($userTasks->keyBy('taskId'));
        
        function showTaskDetails(taskId) {
            const task = tasks[taskId];
            if (!task) return;

            // Hide no-task-selected message (if it exists)
            const noTaskSelected = document.getElementById('no-task-selected');
            if (noTaskSelected) {
                noTaskSelected.classList.add('hidden');
            }
            
            // Show task details (if it exists - for old layout)
            const taskDetails = document.getElementById('task-details');
            if (!taskDetails) {
                // Old layout not present, skip
                return;
            }
            taskDetails.classList.remove('hidden');
            
            // Update active task card
            document.querySelectorAll('.task-card').forEach(card => {
                card.classList.remove('ring-2', 'ring-orange-500', 'bg-orange-50', 'dark:bg-orange-900/20');
            });
            document.querySelector(`[data-task-id="${taskId}"]`).classList.add('ring-2', 'ring-orange-500', 'bg-orange-50', 'dark:bg-orange-900/20');

            // Check if user has joined this task
            const userAssignment = userAssignments[taskId];
            const hasJoined = userAssignment !== undefined;
            const assignmentStatus = hasJoined ? userAssignment.pivot.status : null;
            const isCreator = task.FK1_userId && Number(task.FK1_userId) === Number({{ auth()->user()->userId }});

            // Populate task details
            taskDetails.innerHTML = `
                <!-- Task Content -->
                <div class="p-6">
                    <!-- Details Content -->
                    <div id="details-content">
                        <!-- Task Header -->
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">${task.title}</h1>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                    <strong>Uploaded by:</strong> ${(() => {
                                        const u = task.assigned_user;
                                        if (!u) return 'Admin';
                                        const computed = u.name || [u.firstName, u.middleName, u.lastName].filter(Boolean).join(' ').trim();
                                        return computed && computed.length > 0 ? computed : 'Admin';
                                    })()}
                                </p>
                            </div>
                        </div>

                        <!-- Task Description -->
                        <div class="mb-6">
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">${task.description}</p>
                        </div>

                    <!-- Task Information Grid -->
                    <div class="grid grid-cols-2 gap-6 mb-8">
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    <strong>Date:</strong> 
                                    ${task.due_date ? 
                                        (typeof task.due_date === 'string' ? new Date(task.due_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : new Date(task.due_date.date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })) :
                                        (task.published_date ? 
                                            (typeof task.published_date === 'string' ? new Date(task.published_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : new Date(task.published_date.date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })) :
                                            'Not specified'
                                        )
                                    }
                                </span>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    <strong>Time:</strong> 
                                    ${task.start_time && task.end_time ? 
                                        new Date('2000-01-01T' + task.start_time).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }) + ' - ' + new Date('2000-01-01T' + task.end_time).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }) :
                                        task.start_time ? 
                                            new Date('2000-01-01T' + task.start_time).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }) + ' onwards' :
                                            'Flexible'
                                    }
                                </span>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    <strong>Location:</strong> ${task.location || 'Community'}
                                </span>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 6a2 2 0 114 0 2 2 0 01-4 0zm8 0a2 2 0 114 0 2 2 0 01-4 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    <strong>Type:</strong> ${task.task_type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}
                                </span>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    <strong>Points:</strong> ${task.points_awarded}
                                </span>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    <strong>Created:</strong> 
                                    ${task.published_date ? 
                                        (typeof task.published_date === 'string' ? new Date(task.published_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : new Date(task.published_date.date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })) :
                                        (typeof task.creation_date === 'string' ? new Date(task.creation_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : new Date(task.creation_date.date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }))
                                    }
                                </span>
                            </div>
                        </div>
                    </div>

                        ${(isCreator) ? `
                            <div class="mb-6">
                                <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6 text-center">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">You created this task. You cannot join your own task.</p>
                                </div>
                            </div>
                        ` : hasJoined ? `
                            ${assignmentStatus === 'assigned' ? `
                                <!-- Complete Task Button - Redirect to task details page -->
                                <div class="text-center">
                                    <a href="/tasks/${taskId}" class="inline-block bg-orange-500 hover:bg-orange-700 text-white font-bold py-3 px-8 rounded-lg text-lg transition-colors">
                                        Complete Task
                                    </a>
                                </div>
                            ` : assignmentStatus === 'submitted' ? `
                                <!-- Pending Approval Status -->
                                <div class="mb-6">
                                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-yellow-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-200 mb-2">Pending Approval</h3>
                                            <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                                ${task.task_type === 'user_uploaded' 
                                                    ? 'Your task completion has been submitted and is waiting for the task creator\'s approval.'
                                                    : 'Your task completion has been submitted and is waiting for admin approval.'}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            ` : assignmentStatus === 'completed' ? `
                                <!-- Completed Status -->
                                <div class="mb-6">
                                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-green-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <h3 class="text-lg font-semibold text-green-800 dark:text-green-200 mb-2">Task Completed</h3>
                                            <p class="text-sm text-green-700 dark:text-green-300 mb-4">
                                                Congratulations! Your task has been approved and completed.
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="text-center space-y-3">
                                        <!-- Task Feedback Button -->
                                        <a href="/feedback/${taskId}/create" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                            Task Feedback
                                        </a>
                                        
                                        <!-- Tap & Pass Button - Only for daily tasks completed TODAY -->
                                        ${task.task_type === 'daily' && userAssignments[taskId] && userAssignments[taskId].pivot && userAssignments[taskId].pivot.completed_today ? `
                                        <div>
                                            <a href="/tap-nominations/create/${taskId}" class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-medium rounded-lg transition-colors">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                </svg>
                                                🎯 Tap & Pass
                                            </a>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Nominate someone for a daily task (completed today)</p>
                                        </div>
                                        ` : task.task_type === 'daily' && userAssignments[taskId] && userAssignments[taskId].pivot && !userAssignments[taskId].pivot.completed_today ? `
                                        <div class="text-center">
                                            <div class="inline-flex items-center px-4 py-2 bg-gray-400 text-white font-medium rounded-lg cursor-not-allowed opacity-60">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                </svg>
                                                🎯 Tap & Pass
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Only available for tasks completed today</p>
                                        </div>
                                        ` : ''}
                                    </div>
                                </div>
                            ` : ''}
                        ` : ''}
                    </div>
                </div>
            `;
        }


        // Combined filter function that handles both search and task type filtering
        function applyFilters() {
            try {
                const searchInput = document.getElementById('task-search');
                const filterSelect = document.getElementById('task-type-filter');
                const clearSearchBtn = document.getElementById('clear-search');
                
                if (!searchInput || !filterSelect) {
                    return;
                }
                
                const searchQuery = (searchInput.value || '').toLowerCase().trim();
                const filterValue = filterSelect.value || 'all';
                const taskCards = document.querySelectorAll('.task-card');
                const noTasksMessage = document.getElementById('no-tasks-filtered');
                
                // Show/hide clear search button
                if (clearSearchBtn) {
                    if (searchQuery.length > 0) {
                        clearSearchBtn.classList.remove('hidden');
                    } else {
                        clearSearchBtn.classList.add('hidden');
                    }
                }
                
                if (taskCards.length === 0) {
                    return;
                }
                
                let visibleCount = 0;
                
                taskCards.forEach(card => {
                    const taskType = card.getAttribute('data-task-type');
                    const taskTitle = card.getAttribute('data-task-title') || '';
                    const taskDescription = card.getAttribute('data-task-description') || '';
                    const taskLocation = card.getAttribute('data-task-location') || '';
                    const taskUploader = card.getAttribute('data-task-uploader') || '';
                    
                    // Normalize task type for comparison
                    const normalizedTaskType = (taskType || '').toLowerCase().trim();
                    const normalizedFilter = filterValue.toLowerCase().trim();
                    
                    // Check task type filter
                    const matchesTaskType = normalizedFilter === 'all' || normalizedTaskType === normalizedFilter;
                    
                    // Check search query
                    const matchesSearch = searchQuery === '' || 
                        taskTitle.includes(searchQuery) ||
                        taskDescription.includes(searchQuery) ||
                        taskLocation.includes(searchQuery) ||
                        taskUploader.includes(searchQuery);
                    
                    // Show or hide based on both filters
                    if (matchesTaskType && matchesSearch) {
                        card.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        card.classList.add('hidden');
                    }
                });
                
                // Show/hide the "no tasks available" message
                if (noTasksMessage) {
                    if (visibleCount === 0 && (filterValue !== 'all' || searchQuery !== '')) {
                        noTasksMessage.classList.remove('hidden');
                    } else {
                        noTasksMessage.classList.add('hidden');
                    }
                }
            } catch (error) {
                console.error('Error in applyFilters:', error);
            }
        }

        // Legacy function for backward compatibility
        function filterByTaskType() {
            applyFilters();
        }

        // Make functions globally available
        window.applyFilters = applyFilters;
        window.filterByTaskType = filterByTaskType;

        // Show first task by default if available
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize search input
            const searchInput = document.getElementById('task-search');
            if (searchInput) {
                // Add event listeners for real-time search
                searchInput.addEventListener('input', function(e) {
                    e.stopPropagation();
                    applyFilters();
                });
                
                // Add event listener for Enter key
                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        applyFilters();
                    }
                });
            }
            
            // Initialize task type filter
            const taskTypeFilter = document.getElementById('task-type-filter');
            if (taskTypeFilter) {
                // Add event listener for immediate filtering when dropdown changes
                taskTypeFilter.addEventListener('change', function(e) {
                    e.stopPropagation();
                    applyFilters();
                });
                
                // Also add input event for better responsiveness
                taskTypeFilter.addEventListener('input', function(e) {
                    e.stopPropagation();
                    applyFilters();
                });
                
                // Check if there's a task_type parameter in the URL
                const urlParams = new URLSearchParams(window.location.search);
                const taskType = urlParams.get('task_type');
                if (taskType) {
                    taskTypeFilter.value = taskType;
                }
            }
            
            // Apply initial filters (show all by default)
            applyFilters();

            const firstTask = document.querySelector('.task-card:not(.hidden)');
            if (firstTask) {
                const taskId = firstTask.getAttribute('data-task-id');
                // Only call showTaskDetails if it exists (for old layout)
                if (typeof showTaskDetails === 'function') {
                    showTaskDetails(parseInt(taskId));
                }
            }

            // Close on ESC key for modal
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const modal = document.getElementById('task-modal');
                    if (modal && !modal.classList.contains('hidden')) {
                        closeTaskModal();
                    } else if (searchInput && searchInput === document.activeElement) {
                        // Clear search if focused
                        searchInput.value = '';
                        applyFilters();
                    }
                }
            });
        });

        function openTaskModal(taskId) {
            const task = tasks[taskId];
            if (!task) {
                console.error('Task not found:', taskId);
                return;
            }

            const modal = document.getElementById('task-modal');
            const contentEl = document.getElementById('task-modal-content');

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

            // Determine button state
            const userAssignment = userAssignments[taskId];
            const hasJoined = !!userAssignment;
            const isCreator = task.FK1_userId && Number(task.FK1_userId) === Number({{ auth()->user()->userId }});
            const isFull = task.max_participants !== null && task.max_participants !== undefined && task.assignments && task.assignments.length >= task.max_participants;

            contentEl.innerHTML = `
                <div class="border-b border-gray-200 dark:border-gray-700 -mt-2 -mx-6 px-6 pb-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">${task.title}</h2>
                        <button type=\"button\" class=\"text-gray-400 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-200 transition-colors\" onclick=\"closeTaskModal()\" aria-label=\"Close modal\">
                            <svg class=\"w-6 h-6\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">
                                <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M6 18L18 6M6 6l12 12\"/>
                            </svg>
                        </button>
                    </div>
                    <p class=\"text-xs text-gray-500 dark:text-gray-400 mt-1\">by ${uploaderName}</p>
                </div>
                <div class=\"pt-6\">
                <div id=\"modal-details-content\">
                    <div class=\"space-y-6\">
                    <p class=\"text-gray-700 dark:text-gray-300 leading-relaxed\">${task.description || ''}</p>
                    <div class=\"grid grid-cols-1 sm:grid-cols-2 gap-3\">
                        <div class=\"flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl\">
                            <svg class=\"w-5 h-5 text-orange-500 flex-shrink-0\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z\"></path></svg>
                            <div class=\"min-w-0\">
                                <p class=\"text-xs text-gray-500 dark:text-gray-400\">Date</p>
                                <p class=\"text-sm font-medium text-gray-700 dark:text-gray-300\">${dateText}</p>
                            </div>
                        </div>
                        <div class=\"flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl\">
                            <svg class=\"w-5 h-5 text-teal-500 flex-shrink-0\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z\"></path></svg>
                            <div class=\"min-w-0\">
                                <p class=\"text-xs text-gray-500 dark:text-gray-400\">Time</p>
                                <p class=\"text-sm font-medium text-gray-700 dark:text-gray-300\">${timeText}</p>
                            </div>
                        </div>
                        <div class=\"flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl\">
                            <svg class=\"w-5 h-5 text-orange-500 flex-shrink-0\" fill=\"currentColor\" viewBox=\"0 0 20 20\"><path fill-rule=\"evenodd\" d=\"M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z\" clip-rule=\"evenodd\"></path></svg>
                            <div class=\"min-w-0\">
                                <p class=\"text-xs text-gray-500 dark:text-gray-400\">Location</p>
                                <p class=\"text-sm font-medium text-gray-700 dark:text-gray-300\">${task.location || 'Community'}</p>
                            </div>
                        </div>
                        <div class=\"flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl\">
                            <svg class=\"w-5 h-5 text-orange-500 flex-shrink-0\" fill=\"currentColor\" viewBox=\"0 0 20 20\"><path d=\"M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z\"></path></svg>
                            <div class=\"min-w-0\">
                                <p class=\"text-xs text-gray-500 dark:text-gray-400\">Points</p>
                                <p class=\"text-sm font-medium text-gray-700 dark:text-gray-300\">${((task.points_awarded !== undefined && task.points_awarded !== null) ? task.points_awarded : 'N/A')}</p>
                            </div>
                        </div>
                    </div>
                    <div class=\"pt-2\">
                        ${(() => {
                            if (isCreator) {
                                return '<div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 text-sm text-center text-gray-600 dark:text-gray-400">You created this task</div>';
                            }
                            return '<a href="/tasks/' + taskId + '" class="inline-flex items-center justify-center gap-2 w-full text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5" style="background-color: #F3A261;" onmouseover="this.style.backgroundColor=\'#E8944F\'" onmouseout="this.style.backgroundColor=\'#F3A261\'"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Open Task</a>';
                        })()}
                    </div>
                </div>
                </div>
            `;
            // Inject CSRF token into the modal form if present
            (function(){
                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const tokenInput = contentEl.querySelector('input[name="_token"]');
                if (tokenInput && csrf) tokenInput.value = csrf;
            })();

            modal.classList.remove('hidden');
        }

        function closeTaskModal() {
            const modal = document.getElementById('task-modal');
            if (modal) modal.classList.add('hidden');
        }

    </script>
</x-app-layout>