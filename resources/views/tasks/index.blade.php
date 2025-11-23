<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-bold text-3xl bg-gradient-to-r from-orange-600 via-orange-500 to-teal-500 bg-clip-text text-transparent">
                {{ __('Task Management') }}
            </h2>
            <div class="flex items-center gap-3 flex-wrap">
                <a href="{{ route('tasks.my-uploads') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-teal-600 hover:text-teal-700 bg-teal-50 hover:bg-teal-100 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a2 2 0 012-2h4l2 2h6a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                    </svg>
                    Uploaded Tasks
                </a>
                <a href="{{ route('tasks.create') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-orange-600 to-teal-500 hover:from-orange-700 hover:to-teal-600 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Task
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
                           class="flex-1 text-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ $filter === 'available' ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-md' : 'text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            Available ({{ $taskStats['available'] }})
                        </a>
                        <a href="{{ route('tasks.index', ['filter' => 'assigned']) }}" 
                           class="flex-1 text-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ $filter === 'assigned' ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-md' : 'text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            Assigned ({{ $taskStats['assigned'] }})
                        </a>
                        <a href="{{ route('tasks.index', ['filter' => 'submitted']) }}" 
                           class="flex-1 text-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ $filter === 'submitted' ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-md' : 'text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            Submitted ({{ $taskStats['submitted'] }})
                        </a>
                        <a href="{{ route('tasks.index', ['filter' => 'completed']) }}" 
                           class="flex-1 text-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ $filter === 'completed' ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-md' : 'text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            Completed ({{ $taskStats['completed'] }})
                        </a>
                        <a href="{{ route('tasks.index', ['filter' => 'all']) }}" 
                           class="flex-1 text-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ $filter === 'all' ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-md' : 'text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
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
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-orange-500 to-teal-500 flex items-center justify-center shadow-lg">
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
                            <!-- Task Type Filter -->
                            <div class="px-4 py-2 bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
                                <select id="task-type-filter" 
                                        class="text-sm font-semibold text-gray-700 dark:text-gray-300 bg-transparent border-none focus:outline-none focus:ring-0 cursor-pointer"
                                        onchange="if(typeof filterByTaskType === 'function') filterByTaskType();">
                                    <option value="all">All Types</option>
                                    <option value="daily">Daily Tasks</option>
                                    <option value="one_time">One-Time Tasks</option>
                                    <option value="user_uploaded">User-Uploaded Tasks</option>
                                </select>
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
                                 onclick="openTaskModal({{ $task->taskId }})">
                                 
                                <!-- Decorative gradient background -->
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-orange-400/10 to-teal-400/10 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                                <!-- Shine effect on hover -->
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                                
                                <div class="relative">
                                    <!-- Task Header -->
                                    <div class="flex justify-between items-start mb-3 gap-3">
                                        <h4 class="font-bold text-gray-900 dark:text-white text-lg leading-tight flex-1 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                                            {{ $task->title }}
                                        </h4>
                                        <div class="flex flex-col items-end gap-2 flex-shrink-0">
                                            <span class="px-3 py-1.5 text-xs font-bold bg-gradient-to-r from-teal-500 to-teal-600 text-white rounded-lg shadow-md">
                                                {{ ucwords(str_replace('_', ' ', $task->task_type)) }}
                                            </span>
                                            @if(isset($task->pivot) && $task->pivot->status)
                                                <span class="px-3 py-1.5 text-xs font-bold rounded-lg
                                                    @if($task->pivot->status === 'assigned') bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 dark:from-blue-900/30 dark:to-blue-800/30 dark:text-blue-300
                                                    @elseif($task->pivot->status === 'submitted') bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 dark:from-yellow-900/30 dark:to-amber-900/30 dark:text-yellow-300
                                                    @elseif($task->pivot->status === 'completed') bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 dark:from-green-900/30 dark:to-emerald-900/30 dark:text-green-300
                                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
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
                            <div class="mx-auto w-24 h-24 mb-6 rounded-full bg-gradient-to-br from-orange-100 to-teal-100 dark:from-orange-900/30 dark:to-teal-900/30 flex items-center justify-center">
                                <svg class="w-12 h-12 text-orange-500 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-3">No Tasks Available</h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-2 text-lg">
                                No tasks match the selected filter criteria.
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Try selecting a different task type or check back later.</p>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="bg-white dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-3xl shadow-xl p-12 text-center">
                            <div class="mx-auto w-24 h-24 mb-6 rounded-full bg-gradient-to-br from-orange-100 to-teal-100 dark:from-orange-900/30 dark:to-teal-900/30 flex items-center justify-center">
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
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Check back later or create a new task.</p>
                            <a href="{{ route('tasks.create') }}" 
                               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-600 to-teal-500 hover:from-orange-700 hover:to-teal-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Create New Task
                            </a>
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
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end gap-3">
                    <a id="task-modal-view-link" href="#" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-600 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-200 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">Open full page</a>
                    <button type="button" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-orange-600 to-teal-500 hover:from-orange-700 hover:to-teal-600 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5" onclick="closeTaskModal()">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for task details -->
    <script>
        // Store task data for JavaScript access
        const tasks = @json($filteredTasks->keyBy('taskId'));
        const userAssignments = @json($userTasks->keyBy('taskId'));
        let currentActiveTab = 'details';
        
        function showTaskDetails(taskId) {
            const task = tasks[taskId];
            if (!task) return;

            // Hide no-task-selected message
            document.getElementById('no-task-selected').classList.add('hidden');
            
            // Show task details
            const taskDetails = document.getElementById('task-details');
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
                <!-- Tabs -->
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8 px-6 pt-4">
                        <button onclick="switchTab('details')" id="details-tab" class="border-orange-500 text-orange-600 dark:text-orange-400 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                            Details
                        </button>
                        <button onclick="switchTab('participants')" id="participants-tab" class="border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                            Participants
                        </button>
                    </nav>
                </div>

                <!-- Task Content -->
                <div class="p-6">
                    <!-- Details Tab Content -->
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

                    <!-- Participants Tab Content -->
                    <div id="participants-content" class="hidden">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Participants</h3>
                        <div class="space-y-3">
                            ${task.assignments && task.assignments.length > 0 ? 
                                task.assignments.map(assignment => `
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <div class="h-8 w-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                                    ${assignment.user.name ? assignment.user.name.substring(0, 2).toUpperCase() : 'U'}
                                                </span>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">${assignment.user.name || 'Unknown User'}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">${assignment.user.email || ''}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="px-2 py-1 text-xs rounded-full
                                                ${assignment.status === 'assigned' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' :
                                                  assignment.status === 'submitted' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                                  assignment.status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                                  'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'}">
                                                ${assignment.status.charAt(0).toUpperCase() + assignment.status.slice(1)}
                                            </span>
                                        </div>
                                    </div>
                                `).join('') :
                                '<p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">No participants yet</p>'
                            }
                        </div>
                    </div>
                </div>
            `;
        }

        function switchTab(tabName) {
            currentActiveTab = tabName;
            
            // Update tab buttons
            document.querySelectorAll('[id$="-tab"]').forEach(tab => {
                tab.classList.remove('border-orange-500', 'text-orange-600', 'dark:text-orange-400');
                tab.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
            });
            
            document.getElementById(tabName + '-tab').classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
            document.getElementById(tabName + '-tab').classList.add('border-orange-500', 'text-orange-600', 'dark:text-orange-400');
            
            // Show/hide content
            document.getElementById('details-content').classList.toggle('hidden', tabName !== 'details');
            document.getElementById('participants-content').classList.toggle('hidden', tabName !== 'participants');
        }

        // Task type filter function - defined immediately
        function filterByTaskType() {
            try {
                const filterSelect = document.getElementById('task-type-filter');
                if (!filterSelect) {
                    return;
                }
                
                const filterValue = filterSelect.value || 'all';
                const taskCards = document.querySelectorAll('.task-card');
                const noTasksMessage = document.getElementById('no-tasks-filtered');
                
                if (taskCards.length === 0) {
                    return;
                }
                
                let visibleCount = 0;
                
                taskCards.forEach(card => {
                    const taskType = card.getAttribute('data-task-type');
                    
                    // Normalize task type for comparison
                    const normalizedTaskType = (taskType || '').toLowerCase().trim();
                    const normalizedFilter = filterValue.toLowerCase().trim();
                    
                    // Show or hide based on filter
                    if (normalizedFilter === 'all' || normalizedTaskType === normalizedFilter) {
                        // Show the card
                        card.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        // Hide the card
                        card.classList.add('hidden');
                    }
                });
                
                // Show/hide the "no tasks available" message
                if (noTasksMessage) {
                    if (visibleCount === 0 && filterValue !== 'all') {
                        noTasksMessage.classList.remove('hidden');
                    } else {
                        noTasksMessage.classList.add('hidden');
                    }
                }
            } catch (error) {
                console.error('Error in filterByTaskType:', error);
            }
        }

        // Make function globally available
        window.filterByTaskType = filterByTaskType;

        // Show first task by default if available
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize task type filter
            const taskTypeFilter = document.getElementById('task-type-filter');
            if (taskTypeFilter) {
                // Add event listener for immediate filtering when dropdown changes
                taskTypeFilter.addEventListener('change', function(e) {
                    e.stopPropagation();
                    filterByTaskType();
                });
                
                // Also add input event for better responsiveness
                taskTypeFilter.addEventListener('input', function(e) {
                    e.stopPropagation();
                    filterByTaskType();
                });
                
                // Check if there's a task_type parameter in the URL
                const urlParams = new URLSearchParams(window.location.search);
                const taskType = urlParams.get('task_type');
                if (taskType) {
                    taskTypeFilter.value = taskType;
                    filterByTaskType();
                } else {
                    // Apply initial filter (show all by default)
                    filterByTaskType();
                }
            }

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
                if (e.key === 'Escape') closeTaskModal();
            });
        });

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

            // Determine button state
            const userAssignment = userAssignments[taskId];
            const hasJoined = !!userAssignment;
            const isCreator = task.FK1_userId && Number(task.FK1_userId) === Number({{ auth()->user()->userId }});
            const isFull = task.max_participants !== null && task.max_participants !== undefined && task.assignments && task.assignments.length >= task.max_participants;

            viewLink.href = `/tasks/${taskId}`;

            contentEl.innerHTML = `
                <div class="border-b border-gray-200 dark:border-gray-700 -mt-2 -mx-6 px-6">
                    <div class="flex items-center justify-between">
                        <nav class="flex space-x-8" aria-label="Tabs">
                            <button id=\"modal-details-tab\" class=\"py-4 px-1 border-b-2 border-orange-500 font-semibold text-sm text-orange-600 dark:text-orange-400\">Details</button>
                            <button id=\"modal-participants-tab\" class=\"py-4 px-1 border-b-2 border-transparent font-semibold text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300\">Participants</button>
                        </nav>
                        <button type=\"button\" class=\"text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white py-4 transition-colors\" onclick=\"closeTaskModal()\" aria-label=\"Close modal\">
                            <svg class=\"w-5 h-5\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">
                                <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M6 18L18 6M6 6l12 12\"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class=\"pt-6\">
                <div id=\"modal-details-content\">
                    <div class=\"space-y-6\">
                    <div>
                        <h2 class=\"text-2xl font-bold text-gray-900 dark:text-white mb-2\">${task.title}</h2>
                        <p class=\"text-sm text-gray-600 dark:text-gray-400\"><strong>Uploaded by:</strong> ${uploaderName}</p>
                    </div>
                    <p class=\"text-gray-700 dark:text-gray-300 leading-relaxed\">${task.description || ''}</p>
                    <div class=\"grid grid-cols-1 sm:grid-cols-2 gap-4\">
                        <div class=\"flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl\">
                            <svg class=\"w-5 h-5 text-orange-500\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z\"></path></svg>
                            <span class=\"text-sm text-gray-700 dark:text-gray-300\"><strong>Date:</strong> ${dateText}</span>
                        </div>
                        <div class=\"flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl\">
                            <svg class=\"w-5 h-5 text-teal-500\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z\"></path></svg>
                            <span class=\"text-sm text-gray-700 dark:text-gray-300\"><strong>Time:</strong> ${timeText}</span>
                        </div>
                        <div class=\"flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl\">
                            <svg class=\"w-5 h-5 text-orange-500\" fill=\"currentColor\" viewBox=\"0 0 20 20\"><path fill-rule=\"evenodd\" d=\"M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z\" clip-rule=\"evenodd\"></path></svg>
                            <span class=\"text-sm text-gray-700 dark:text-gray-300\"><strong>Location:</strong> ${task.location || 'Community'}</span>
                        </div>
                        <div class=\"flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl\">
                            <svg class=\"w-5 h-5 text-teal-500\" fill=\"currentColor\" viewBox=\"0 0 20 20\"><path fill-rule=\"evenodd\" d=\"M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 6a2 2 0 114 0 2 2 0 01-4 0zm8 0a2 2 0 114 0 2 2 0 01-4 0z\" clip-rule=\"evenodd\"></path></svg>
                            <span class=\"text-sm text-gray-700 dark:text-gray-300\"><strong>Type:</strong> ${task.task_type ? task.task_type.replace('_',' ').replace(/\\b\\w/g, l => l.toUpperCase()) : ''}</span>
                        </div>
                        <div class=\"flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl\">
                            <svg class=\"w-5 h-5 text-orange-500\" fill=\"currentColor\" viewBox=\"0 0 20 20\"><path d=\"M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z\"></path></svg>
                            <span class=\"text-sm text-gray-700 dark:text-gray-300\"><strong>Points:</strong> ${((task.points_awarded !== undefined && task.points_awarded !== null) ? task.points_awarded : '')}</span>
                        </div>
                        <div class=\"flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl\">
                            <svg class=\"w-5 h-5 text-teal-500\" fill=\"currentColor\" viewBox=\"0 0 20 20\"><path fill-rule=\"evenodd\" d=\"M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z\" clip-rule=\"evenodd\"></path></svg>
                            <span class=\"text-sm text-gray-700 dark:text-gray-300\"><strong>Created:</strong> ${task.published_date ? (typeof task.published_date === 'string' ? new Date(task.published_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : new Date(task.published_date.date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })) : (typeof task.creation_date === 'string' ? new Date(task.creation_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : new Date(task.creation_date.date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }))}</span>
                        </div>
                    </div>
                    <div class=\"pt-4\">
                        ${(() => {
                            if (isCreator) {
                                return '<div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl p-4 text-sm text-center text-gray-700 dark:text-gray-300">You created this task. You cannot join your own task.</div>';
                            }
                            if (hasJoined) {
                                return '<a href="/tasks/' + taskId + '" class="inline-flex items-center justify-center gap-2 w-full bg-gradient-to-r from-orange-600 to-teal-500 hover:from-orange-700 hover:to-teal-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Open Task</a>';
                            }
                            return '';
                        })()}
                    </div>
                </div>
                <div id=\"modal-participants-content\" class=\"hidden\">
                    <h4 class=\"text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2\">
                        <svg class=\"w-5 h-5 text-orange-500\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">
                            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z\"/>
                        </svg>
                        Participants ${task.assignments && task.assignments.length ? `(${task.assignments.length})` : ''}
                    </h4>
                    ${task.assignments && task.assignments.length > 0 ?
                        task.assignments.map(assignment => `
                            <div class=\\"flex items-center justify-between p-4 mb-3 last:mb-0 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl border border-gray-200 dark:border-gray-600 hover:shadow-md transition-all duration-200\\">
                                <div class=\\"flex items-center space-x-3\\">
                                    <div class=\\"h-10 w-10 rounded-full bg-gradient-to-br from-orange-500 to-teal-500 flex items-center justify-center shadow-md\\">
                                        <span class=\\"text-sm font-bold text-white\\">${assignment.user && assignment.user.name ? assignment.user.name.substring(0,2).toUpperCase() : 'U'}</span>
                                    </div>
                                    <div>
                                        <p class=\\"text-sm font-bold text-gray-900 dark:text-white\\">${assignment.user && assignment.user.name ? assignment.user.name : 'Unknown User'}</p>
                                        <p class=\\"text-xs text-gray-500 dark:text-gray-400\\">${assignment.user && assignment.user.email ? assignment.user.email : ''}</p>
                                    </div>
                                </div>
                                <div class=\\"flex items-center space-x-2\\">
                                    <span class=\\"px-3 py-1.5 text-xs font-bold rounded-lg ${assignment.status === 'assigned' ? 'bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 dark:from-blue-900/30 dark:to-blue-800/30 dark:text-blue-300' : assignment.status === 'submitted' ? 'bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 dark:from-yellow-900/30 dark:to-amber-900/30 dark:text-yellow-300' : assignment.status === 'completed' ? 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 dark:from-green-900/30 dark:to-emerald-900/30 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'}\\">${assignment.status ? assignment.status.charAt(0).toUpperCase() + assignment.status.slice(1) : ''}</span>
                                </div>
                            </div>
                        `).join('') :
                        '<div class="text-center py-8"><p class="text-sm text-gray-500 dark:text-gray-400">No participants yet.</p></div>'
                    }
                </div>
                </div>
            `;
            // Inject CSRF token into the modal form if present
            (function(){
                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const tokenInput = contentEl.querySelector('input[name="_token"]');
                if (tokenInput && csrf) tokenInput.value = csrf;
            })();

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

    </script>
</x-app-layout>