<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Task Details') }}
            </h2>
            <a href="{{ route('tasks.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Tasks
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Toast Notifications -->
            <x-session-toast />
            <!-- Task Header with Tabs -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                <!-- Task Title and Admin Label -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-start">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $task->title }}</h1>
                        @if(Auth::user()->isAdmin())
                            <span class="text-blue-600 dark:text-blue-400 text-sm font-medium">Admin</span>
                        @endif
                    </div>
                </div>

                <!-- Tabs -->
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex space-x-8 px-6" aria-label="Tabs">
                        <button id="details-tab" class="py-4 px-1 border-b-2 border-orange-500 font-medium text-sm text-orange-600 dark:text-orange-400">
                            Details
                        </button>
                        <button id="participants-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                            Participants
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="px-6 py-6">
                    <!-- Details Tab Content -->
                    <div id="details-content" class="tab-content">
                        <!-- Task Description -->
                        <div class="mb-6">
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $task->description }}</p>
                        </div>

                        <!-- Task Details Grid -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    <strong>Date:</strong> 
                                    @if($task->due_date)
                                        {{ is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date)->format('M j, Y') : $task->due_date->format('M j, Y') }}
                                    @else
                                        {{ is_string($task->creation_date) ? \Carbon\Carbon::parse($task->creation_date)->format('M j, Y') : $task->creation_date->format('M j, Y') }}
                                    @endif
                                </span>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    <strong>Time:</strong> 
                                    @if($task->start_time && $task->end_time)
                                        {{ \Carbon\Carbon::parse($task->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($task->end_time)->format('g:i A') }}
                                    @elseif($task->start_time)
                                        {{ \Carbon\Carbon::parse($task->start_time)->format('g:i A') }} onwards
                                    @else
                                        Flexible
                                    @endif
                                </span>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    <strong>Location:</strong> {{ $task->location ?: 'Community' }}
                                </span>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    <strong>Points:</strong> {{ $task->points_awarded }}
                                </span>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 6a2 2 0 114 0 2 2 0 01-4 0zm8 0a2 2 0 114 0 2 2 0 01-4 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    <strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $task->task_type)) }}
                                </span>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    <strong>Created:</strong> {{ is_string($task->creation_date) ? \Carbon\Carbon::parse($task->creation_date)->format('M j, Y') : $task->creation_date->format('M j, Y') }}
                                </span>
                            </div>
                        </div>

                        <!-- Join Task Button - Only show if user hasn't joined the task -->
                        @if(!$task->isAssignedTo(Auth::id()) && $task->status === 'published')
                        <div class="mb-6 text-center">
                            <form action="{{ route('tasks.join', $task) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition-colors">
                                    Join This Task
                                </button>
                            </form>
                        </div>
                        @endif

                        <!-- Task Status Section - Only show if user has joined the task -->
                        @if($task->isAssignedTo(Auth::id()))
                            @php
                                $userAssignment = $task->assignments->where('userId', Auth::id())->first();
                            @endphp
                            
                            @if($userAssignment->status === 'submitted')
                                <!-- Pending Approval Status -->
                                <div class="mb-6">
                                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-yellow-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-200 mb-2">Pending Approval</h3>
                                            <p class="text-sm text-yellow-700 dark:text-yellow-300 mb-4">
                                                Your task completion has been submitted and is waiting for admin approval.
                                            </p>
                                            @if($userAssignment->submitted_at)
                                                <p class="text-xs text-yellow-600 dark:text-yellow-400">
                                                    Submitted on {{ is_string($userAssignment->submitted_at) ? \Carbon\Carbon::parse($userAssignment->submitted_at)->format('M j, Y \a\t g:i A') : $userAssignment->submitted_at->format('M j, Y \a\t g:i A') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Task Feedback Button -->
                                    <div class="mt-4 text-center">
                                        <a href="{{ route('feedback.create', $task) }}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                            Task Feedback
                                        </a>
                                    </div>
                                </div>
                            @elseif($userAssignment->status === 'assigned' && $userAssignment->rejection_count > 0)
                                <!-- Rejected Status - Show rejection reason and allow resubmission -->
                                <div class="mb-6">
                                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            <h3 class="text-lg font-semibold text-red-800 dark:text-red-200 mb-2">Submission Rejected</h3>
                                            <p class="text-sm text-red-700 dark:text-red-300 mb-4">
                                                Your task submission was rejected. Please review the feedback and resubmit.
                                            </p>
                                            
                                            <!-- Rejection Details -->
                                            <div class="w-full max-w-md bg-red-100 dark:bg-red-800/30 rounded-lg p-4 mb-4">
                                                <p class="text-sm font-medium text-red-800 dark:text-red-200 mb-2">Rejection Details:</p>
                                                <p class="text-sm text-red-700 dark:text-red-300 mb-2">
                                                    <strong>Attempt:</strong> {{ $userAssignment->rejection_count }}/3
                                                </p>
                                                @if($userAssignment->rejection_reason)
                                                    <p class="text-sm text-red-700 dark:text-red-300">
                                                        <strong>Reason:</strong> {{ $userAssignment->rejection_reason }}
                                                    </p>
                                                @endif
                                            </div>
                                            
                                            @if($userAssignment->rejection_count < 3)
                                                <p class="text-sm text-red-600 dark:text-red-400 mb-4">
                                                    You have {{ 3 - $userAssignment->rejection_count }} remaining attempts to resubmit.
                                                </p>
                                            @else
                                                <p class="text-sm text-red-600 dark:text-red-400 mb-4">
                                                    You have reached the maximum number of attempts (3). Please contact admin for assistance.
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Upload Photos Section for rejected tasks (if attempts remaining) -->
                                @if($userAssignment->rejection_count < 3)
                                <div class="mb-6">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Resubmit Photos</h3>
                                    <div id="upload-area" class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-8 text-center hover:border-gray-400 dark:hover:border-gray-500 transition-colors cursor-pointer">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            <p id="upload-text" class="text-sm text-gray-600 dark:text-gray-400 mb-2">Upload new proof of task completion</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-500">Click to select photos or drag and drop</p>
                                        </div>
                                    </div>
                                    <div id="selected-files" class="mt-4 hidden">
                                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Selected Files:</h4>
                                        <div id="file-list" class="space-y-2"></div>
                                    </div>
                                </div>
                                @endif
                            @elseif($userAssignment->status === 'assigned')
                                <!-- Upload Photos Section for assigned tasks -->
                                <div class="mb-6">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Upload Photos</h3>
                                    <div id="upload-area" class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-8 text-center hover:border-gray-400 dark:hover:border-gray-500 transition-colors cursor-pointer">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            <p id="upload-text" class="text-sm text-gray-600 dark:text-gray-400 mb-2">Upload proof of task completion</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-500">Click to select photos or drag and drop</p>
                                        </div>
                                    </div>
                                    <div id="selected-files" class="mt-4 hidden">
                                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Selected Files:</h4>
                                        <div id="file-list" class="space-y-2"></div>
                                    </div>
                                </div>
                            @elseif($userAssignment->status === 'completed')
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
                                            @if($userAssignment->completed_at)
                                                <p class="text-xs text-green-600 dark:text-green-400">
                                                    Completed on {{ is_string($userAssignment->completed_at) ? \Carbon\Carbon::parse($userAssignment->completed_at)->format('M j, Y \a\t g:i A') : $userAssignment->completed_at->format('M j, Y \a\t g:i A') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="mt-4 text-center space-y-3">
                                        <!-- Task Feedback Button -->
                                        <a href="{{ route('feedback.create', $task) }}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                            Task Feedback
                                        </a>
                                        
                                        <!-- Tap & Pass Button - Only for daily tasks completed TODAY -->
                                        @if($task->task_type === 'daily' && $userAssignment->completed_at && \Carbon\Carbon::parse($userAssignment->completed_at)->isToday())
                                        <div>
                                            <a href="{{ route('tap-nominations.create', $task) }}" class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-medium rounded-lg transition-colors">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                </svg>
                                                🎯 Tap & Pass
                                            </a>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Nominate someone for a daily task (completed today)</p>
                                        </div>
                                        @elseif($task->task_type === 'daily' && $userAssignment->completed_at && !\Carbon\Carbon::parse($userAssignment->completed_at)->isToday())
                                        <div class="text-center">
                                            <div class="inline-flex items-center px-4 py-2 bg-gray-400 text-white font-medium rounded-lg cursor-not-allowed opacity-60">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                </svg>
                                                🎯 Tap & Pass
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Only available for tasks completed today</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endif

                        <!-- Complete Task Form -->
                        @if($task->isAssignedTo(Auth::id()) && $task->assignments->where('userId', Auth::id())->first()->status === 'assigned')
                        <div class="flex justify-center">
                            <form action="{{ route('tasks.submit', $task) }}" method="POST" enctype="multipart/form-data" class="w-full max-w-md">
                                @csrf
                                
                                <!-- Completion Notes -->
                                <div class="mb-4">
                                    <label for="completion_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Completion Notes (Optional)
                                    </label>
                                    <textarea 
                                        id="completion_notes" 
                                        name="completion_notes" 
                                        rows="3" 
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white"
                                        placeholder="Describe what you did to complete this task..."></textarea>
                                </div>

                                <!-- File input for photos -->
                                <input type="file" id="photos" name="photos[]" multiple accept="image/*" class="hidden">
                                
                                <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                                    Complete Task
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>

                    <!-- Participants Tab Content -->
                    <div id="participants-content" class="tab-content hidden">
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
                                Participants ({{ $task->assignments->count() }})
                            </h3>
                            @if($task->assignments->count() > 0)
                                <div class="space-y-3">
                                    @foreach($task->assignments as $assignment)
                                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                            <div class="flex items-center space-x-3">
                                                <div class="h-10 w-10 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                        {{ substr($assignment->user->name, 0, 2) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $assignment->user->name }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $assignment->user->email }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="px-2 py-1 text-xs rounded-full
                                                    @if($assignment->status === 'assigned') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                    @elseif($assignment->status === 'submitted') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                    @elseif($assignment->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @endif">
                                                    {{ ucfirst($assignment->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400">No participants yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const detailsTab = document.getElementById('details-tab');
            const participantsTab = document.getElementById('participants-tab');
            const detailsContent = document.getElementById('details-content');
            const participantsContent = document.getElementById('participants-content');

            detailsTab.addEventListener('click', function() {
                // Update tab styles
                detailsTab.classList.add('border-orange-500', 'text-orange-600', 'dark:text-orange-400');
                detailsTab.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                
                participantsTab.classList.remove('border-orange-500', 'text-orange-600', 'dark:text-orange-400');
                participantsTab.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                
                // Show/hide content
                detailsContent.classList.remove('hidden');
                participantsContent.classList.add('hidden');
            });

            participantsTab.addEventListener('click', function() {
                // Update tab styles
                participantsTab.classList.add('border-orange-500', 'text-orange-600', 'dark:text-orange-400');
                participantsTab.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                
                detailsTab.classList.remove('border-orange-500', 'text-orange-600', 'dark:text-orange-400');
                detailsTab.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                
                // Show/hide content
                participantsContent.classList.remove('hidden');
                detailsContent.classList.add('hidden');
            });

            // Photo upload functionality
            const uploadArea = document.getElementById('upload-area');
            const photosInput = document.getElementById('photos');
            const uploadText = document.getElementById('upload-text');
            const selectedFiles = document.getElementById('selected-files');
            const fileList = document.getElementById('file-list');

            if (uploadArea && photosInput) {
                uploadArea.addEventListener('click', function() {
                    photosInput.click();
                });

                // Drag and drop functionality
                uploadArea.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    uploadArea.classList.add('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/20');
                });

                uploadArea.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    uploadArea.classList.remove('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/20');
                });

                uploadArea.addEventListener('drop', function(e) {
                    e.preventDefault();
                    uploadArea.classList.remove('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/20');
                    
                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        photosInput.files = files;
                        handleFileSelection(files);
                    }
                });

                photosInput.addEventListener('change', function(e) {
                    const files = e.target.files;
                    if (files.length > 0) {
                        handleFileSelection(files);
                    }
                });

                function handleFileSelection(files) {
                    console.log('Selected files:', files.length);
                    
                    // Update upload text
                    uploadText.textContent = `${files.length} photo(s) selected`;
                    uploadText.classList.add('text-green-600', 'dark:text-green-400');
                    
                    // Show selected files
                    selectedFiles.classList.remove('hidden');
                    fileList.innerHTML = '';
                    
                    Array.from(files).forEach((file, index) => {
                        const fileItem = document.createElement('div');
                        fileItem.className = 'flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded';
                        fileItem.innerHTML = `
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm text-gray-700 dark:text-gray-300">${file.name}</span>
                                <span class="text-xs text-gray-500">(${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
                            </div>
                        `;
                        fileList.appendChild(fileItem);
                    });
                }
            }
        });
    </script>
</x-app-layout>
