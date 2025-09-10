<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Task Management') }}
            </h2>
            <a href="{{ route('tasks.create') }}" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                + Add Task
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Toast Notifications -->
            <x-session-toast />

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Available Tasks -->
                <div class="lg:col-span-1">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4">Available Tasks</h3>
                        
                        <!-- Filter Bar -->
                        <div class="flex items-center space-x-4 mb-4">
                            <select id="task-filter" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white" onchange="filterTasks()">
                                <option value="all">All type</option>
                                <option value="daily">Daily Task</option>
                                <option value="one_time">One-Time Task</option>
                                <option value="user_uploaded">User-Uploaded Task</option>
                            </select>
                        </div>
                    </div>

                    @if($availableTasks->count() > 0)
                        <div class="space-y-4 max-h-96 overflow-y-auto">
                            @foreach($availableTasks as $task)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 transition-all duration-200 hover:shadow-md cursor-pointer task-card" 
                                 data-task-id="{{ $task->taskId }}" 
                                 data-task-type="{{ $task->task_type }}"
                                 onclick="showTaskDetails({{ $task->taskId }})">
                                 
                                <!-- Task Header -->
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-bold text-gray-900 dark:text-white text-lg">{{ $task->title }}</h4>
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
                                        {{ ucfirst(str_replace('_', ' ', $task->task_type)) }}
                                    </span>
                                </div>
                                
                                <!-- Task Date -->
                                <div class="flex items-center space-x-2 mb-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        @if($task->due_date)
                                            {{ is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date)->format('M j, Y') : $task->due_date->format('M j, Y') }}
                                        @else
                                            {{ is_string($task->published_date) ? \Carbon\Carbon::parse($task->published_date)->format('M j, Y') : $task->published_date->format('M j, Y') }}
                                        @endif
                                    </span>
                                </div>
                                
                                <!-- Action Link -->
                                <div class="text-right">
                                    <span class="text-orange-500 hover:text-orange-700 text-sm font-medium">
                                        See more details →
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">No available tasks at the moment</p>
                        </div>
                    @endif
                </div>

                <!-- Right Column - Task Details -->
                <div class="lg:col-span-2">
                    <div id="task-details-container" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <!-- Default state when no task is selected -->
                        <div id="no-task-selected" class="p-8 text-center">
                            <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Select a Task</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Choose a task from the left to view its details</p>
                        </div>

                        <!-- Task details will be loaded here via JavaScript -->
                        <div id="task-details" class="hidden">
                            <!-- Content will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for task details -->
    <script>
        // Store task data for JavaScript access
        const tasks = @json($availableTasks->keyBy('taskId'));
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
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">${task.title}</h1>
                            <span class="px-3 py-1 text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
                                Admin
                            </span>
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

                        ${hasJoined ? `
                        <!-- Upload Photos Section - Only show if user has joined -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Upload Photos</h3>
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-8 text-center">
                                <svg class="mx-auto h-12 w-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Click to upload or drag and drop</p>
                            </div>
                        </div>

                        <!-- Complete Task Button - Only show if user has joined -->
                        <div class="text-center">
                            <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-3 px-8 rounded-lg text-lg">
                                Complete Task
                            </button>
                        </div>
                        ` : `
                        <!-- Join Task Button - Show if user hasn't joined -->
                        <div class="text-center">
                            <form action="/tasks/${taskId}/join" method="POST" class="inline">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-3 px-8 rounded-lg text-lg">
                                    Join Task
                                </button>
                            </form>
                        </div>
                        `}
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

        function filterTasks() {
            const filterValue = document.getElementById('task-filter').value;
            const taskCards = document.querySelectorAll('.task-card');
            
            taskCards.forEach(card => {
                const taskType = card.getAttribute('data-task-type');
                
                if (filterValue === 'all' || taskType === filterValue) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
            
            // If the currently selected task is hidden, clear the details
            const selectedCard = document.querySelector('.task-card.ring-2');
            if (selectedCard && selectedCard.style.display === 'none') {
                // Clear selection
                selectedCard.classList.remove('ring-2', 'ring-orange-500', 'bg-orange-50', 'dark:bg-orange-900/20');
                
                // Show no-task-selected message
                document.getElementById('no-task-selected').classList.remove('hidden');
                document.getElementById('task-details').classList.add('hidden');
            }
        }

        // Show first task by default if available
        document.addEventListener('DOMContentLoaded', function() {
            const firstTask = document.querySelector('.task-card');
            if (firstTask) {
                const taskId = firstTask.getAttribute('data-task-id');
                showTaskDetails(parseInt(taskId));
            }
        });
    </script>
</x-app-layout>