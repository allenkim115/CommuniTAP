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
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $task->title }}</h1>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                @php $uploader = $task->assignedUser; @endphp
                                <strong>Uploaded by:</strong> {{ $uploader?->name ?? 'Admin' }}
                            </p>
                        </div>
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

                        <!-- Join Task Button - Only show if user hasn't joined the task and is not the creator -->
                        @php $isCreator = $task->FK1_userId === Auth::id(); @endphp
                        @if(!$task->isAssignedTo(Auth::id()) && !$isCreator && $task->status === 'published')
                        <div class="mb-6 text-center">
                            @php
                                $isFull = !is_null($task->max_participants) && $task->assignments->count() >= $task->max_participants;
                            @endphp
                            @if($isFull)
                                <button type="button" class="bg-gray-400 text-white font-bold py-3 px-8 rounded-lg cursor-not-allowed" title="This task has reached its participant limit" aria-disabled="true">
                                    Join This Task
                                </button>
                            @else
                                <form action="{{ route('tasks.join', $task) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition-colors">
                                        Join This Task
                                    </button>
                                </form>
                            @endif
                        </div>
                        @endif
                        @if($isCreator)
                        <div class="mb-6 text-center">
                            <button type="button" class="bg-gray-400 text-white font-bold py-3 px-8 rounded-lg cursor-not-allowed" title="You created this task" aria-disabled="true">
                                Join This Task
                            </button>
                        </div>
                        @endif

                        <!-- Task Status Section - Only show if user has joined the task -->
                        @if($task->isAssignedTo(Auth::id()))
                            @php
                                $userAssignment = $task->assignments->where('userId', Auth::id())->first();
                            @endphp
                            
                            <!-- Progress tracker -->
                            @php
                                $steps = ['accepted' => 'Accepted', 'on_the_way' => 'On the way', 'working' => 'Working', 'done' => 'Task done', 'submitted_proof' => 'Submitted proof'];
                                $current = $userAssignment->progress ?? 'accepted';
                                $stepKeys = array_keys($steps);
                                $currentIndex = array_search($current, $stepKeys);
                                if ($currentIndex === false) { $currentIndex = 0; }
                            @endphp
                            <div class="mb-6">
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-4">
                                    @php $percent = ($currentIndex) * (100 / (count($steps) - 1)); @endphp
                                    <div class="bg-orange-500 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                                </div>
                                <div class="flex justify-between">
                                    @foreach($steps as $key => $label)
                                        @php $index = array_search($key, $stepKeys); $active = $index <= $currentIndex; @endphp
                                        <div class="text-xs {{ $active ? 'text-orange-600 dark:text-orange-400' : 'text-gray-500 dark:text-gray-400' }}">
                                            {{ $label }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Progress actions (except submitted_proof which is set by submit) -->
                            @if($userAssignment->status === 'assigned')
                                <div class="mb-6 flex flex-wrap gap-2">
                                    @foreach(['accepted','on_the_way','working','done'] as $p)
                                        <form action="{{ route('tasks.progress', $task) }}" method="POST" onsubmit="return confirm('Move progress to {{ ucfirst(str_replace('_',' ', $p)) }}?');">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="progress" value="{{ $p }}">
                                            @php
                                                $order = ['accepted','on_the_way','working','done'];
                                                $curr = $userAssignment->progress ?? 'accepted';
                                                $currIdx = array_search($curr, $order);
                                                $btnIdx = array_search($p, $order);
                                                $disabled = $btnIdx !== false && $currIdx !== false && $btnIdx < $currIdx;
                                            @endphp
                                            <button type="submit" {{ $disabled ? 'disabled' : '' }} class="px-3 py-1 rounded border text-sm {{ ($userAssignment->progress ?? 'accepted') === $p ? 'bg-orange-500 text-white border-orange-600' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600' }} {{ $disabled ? 'opacity-50 cursor-not-allowed' : '' }}">
                                                {{ ucfirst(str_replace('_',' ', $p)) }}
                                            </button>
                                        </form>
                                    @endforeach
                                </div>
                            @endif
                            
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
                                    <!-- User Submission Preview -->
                                    <div class="mt-6 text-left">
                                        <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3">Your Submission</h4>
                                        @if(($userAssignment->completion_notes ?? null))
                                            <div class="mb-4 text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                                                {{ $userAssignment->completion_notes }}
                                            </div>
                                        @endif
                                        @php
                                            $photos = $userAssignment->photos ?? [];
                                        @endphp
                                        @if(is_array($photos) && count($photos) > 0)
                                            <div class="mb-4">
                                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                                    @foreach($photos as $index => $photo)
                                                        @php 
                                                            // Build a robust relative URL to avoid APP_URL issues
                                                            $photoUrl = \Illuminate\Support\Str::startsWith($photo, ['http://','https://','/'])
                                                                ? $photo
                                                                : '/storage/' . ltrim($photo, '/');
                                                        @endphp
                                                        <div class="relative group">
                                                            <div class="relative overflow-hidden rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200" onclick="openImageModal('{{ $photoUrl }}')">
                                                                <img src="{{ $photoUrl }}" 
                                                                    alt="Task completion proof {{ $index + 1 }}" 
                                                                    class="w-full h-28 object-cover cursor-pointer hover:scale-105 transition-transform duration-200"
                                                                    data-photo-url="{{ $photoUrl }}"
                                                                    onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjgwIiB2aWV3Qm94PSIwIDAgMTAwIDgwIiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iODAiIHJ4PSIxMiIgZmlsbD0iI0YzRjRGNiIvPjxwYXRoIGQ9Ik0zMCAzMEg3MFY1MEgzMFYzMFoiIHN0cm9rZT0iIzkzOTM5MyIgc3Ryb2tlLXdpZHRoPSIyIi8+PHBhdGggZD0iTTQyIDQyTDQ4IDQ4TDYwIDM2IiBzdHJva2U9IiM5MzkzOTMiIHN0cm9rZS13aWR0aD0iMiIvPjwvc3ZnPg==';">
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="mt-3">
                                                    <button type="button" onclick="openImageModal(document.querySelector('img[data-photo-url]')?.getAttribute('data-photo-url'))" class="inline-flex items-center px-3 py-2 bg-gray-900 text-white text-sm rounded hover:bg-black">
                                                        Review Proof
                                                    </button>
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-500 dark:text-gray-400">No photos attached.</p>
                                        @endif
                                    </div>

                                    <!-- Task Feedback Button -->
                                    <div class="mt-4 text-center space-x-2">
                                        <a href="{{ route('feedback.create', $task) }}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                            Task Feedback
                                        </a>
                                        <a href="{{ route('feedback.show', $task) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 font-medium rounded-lg transition-colors">
                                            View Feedback
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
                                
                                <!-- Removed duplicate upload area to consolidate into the form below -->
                            @elseif($userAssignment->status === 'assigned')
                                <!-- Removed duplicate upload area to consolidate into the form below -->
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
                                        <div>
                                            <a href="{{ route('feedback.show', $task) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 font-medium rounded-lg transition-colors">
                                                View Feedback
                                            </a>
                                        </div>
                                        
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

                        <!-- Complete Task Form (only when progress is 'done') -->
                        @php $assignmentForForm = $task->assignments->where('userId', Auth::id())->first(); @endphp
                        @if($task->isAssignedTo(Auth::id()) 
                            && $assignmentForForm 
                            && $assignmentForForm->status === 'assigned' 
                            && ($assignmentForForm->progress ?? 'accepted') === 'done')
                        <div class="flex justify-center">
                            <form id="task-submit-form" action="{{ route('tasks.submit', $task) }}" method="POST" enctype="multipart/form-data" class="w-full max-w-md">
                                @csrf
                                
                                <!-- Completion Notes (visible only when progress is done) -->
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
                                    @error('completion_notes')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- File input for photos (max 3) -->
                                <input type="file" id="photos" name="photos[]" multiple accept="image/*" class="hidden" aria-describedby="photos-help">
                                
                                <!-- In-form Upload Area (only when progress is done) -->
                                <div id="form-upload-area" class="mt-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-gray-400 dark:hover:border-gray-500 transition-colors cursor-pointer">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-10 h-10 text-red-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        <p id="form-upload-text" class="text-sm text-gray-600 dark:text-gray-400 mb-1">Click to select photos or drag and drop (2–3 photos)</p>
                                        <p id="photos-help" class="text-xs text-gray-500 dark:text-gray-400">Max 3 images. Accepted: jpeg, png, jpg, gif.</p>
                                    </div>
                                </div>
                                @error('photos')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                @error('photos.*')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <div id="form-selected-files" class="mt-3 hidden">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Selected Files:</h4>
                                    <div id="form-file-list" class="space-y-2 grid grid-cols-3 gap-2"></div>
                                </div>
                                
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
                                                @if($assignment->user->userId !== Auth::id())
                                                    <a href="{{ route('incident-reports.create', ['reported_user' => $assignment->user->userId, 'task' => $task->taskId]) }}" 
                                                       class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-xs font-medium"
                                                       title="Report this user">
                                                        🚨 Report
                                                    </a>
                                                @endif
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

            // Photo upload functionality (limit 3 files, require min 2 client-side)
            // Support multiple upload areas elsewhere, but prioritize in-form scoped elements to prevent conflicts
            const photosInput = document.getElementById('photos');

            // Scoped elements for the submission form
            const formUploadArea = document.getElementById('form-upload-area');
            const formUploadText = document.getElementById('form-upload-text');
            const formSelectedFiles = document.getElementById('form-selected-files');
            const formFileList = document.getElementById('form-file-list');
            const MAX_FILES = 3;
            const MIN_FILES = 2;
            let selectedFilesState = [];

            function wireUploadArea(areaEl, textEl, listContainerEl, listEl) {
                if (!areaEl || !photosInput) return;

                areaEl.addEventListener('click', function() {
                    photosInput.click();
                });

                // Drag and drop functionality
                areaEl.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    areaEl.classList.add('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/20');
                });

                areaEl.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    areaEl.classList.remove('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/20');
                });

                areaEl.addEventListener('drop', function(e) {
                    e.preventDefault();
                    areaEl.classList.remove('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/20');
                    
                    let newFiles = e.dataTransfer.files;
                    if (newFiles.length > 0) {
                        selectedFilesState = limitFiles(mergeFiles(selectedFilesState, newFiles));
                        setInputFiles(photosInput, selectedFilesState);
                        handleFileSelection(selectedFilesState, textEl, listContainerEl, listEl);
                    }
                });

                photosInput.addEventListener('change', function(e) {
                    let newFiles = e.target.files;
                    if (newFiles.length > 0) {
                        selectedFilesState = limitFiles(mergeFiles(selectedFilesState, newFiles));
                        setInputFiles(photosInput, selectedFilesState);
                        handleFileSelection(selectedFilesState, textEl, listContainerEl, listEl);
                    }
                });

                function handleFileSelection(files, textTarget, containerTarget, listTarget) {
                    // Always render from selectedFilesState to keep indices stable
                    renderSelectedFiles(textTarget, containerTarget, listTarget);
                }

                function renderSelectedFiles(textTarget, containerTarget, listTarget) {
                    const count = selectedFilesState.length;
                    let message = `${count} photo(s) selected`;
                    if (count > MAX_FILES) {
                        message = `You can upload up to ${MAX_FILES} photos.`;
                    } else if (count > 0 && count < MIN_FILES) {
                        message = `Please select at least ${MIN_FILES} photos.`;
                    }
                    if (textTarget) {
                        textTarget.textContent = message;
                        textTarget.classList.add('text-green-600', 'dark:text-green-400');
                    }

                    if (containerTarget) containerTarget.classList.toggle('hidden', count === 0);
                    if (listTarget) listTarget.innerHTML = '';

                    selectedFilesState.slice(0, MAX_FILES).forEach((file, index) => {
                        const fileItem = document.createElement('div');
                        fileItem.className = 'relative group overflow-hidden rounded border border-gray-200 dark:border-gray-700';
                        const objectUrl = URL.createObjectURL(file);
                        fileItem.innerHTML = `
                            <img src="${objectUrl}" alt="preview" class="w-full h-24 object-cover" />
                            <button type="button" aria-label="Remove image" data-index="${index}" class="absolute top-1 right-1 bg-black/60 hover:bg-black/80 text-white text-xs leading-none rounded px-2 py-1">×</button>
                            <div class="absolute bottom-0 left-0 right-0 bg-black/50 text-white text-[10px] px-1 truncate">${file.name}</div>
                        `;
                        if (listTarget) listTarget.appendChild(fileItem);
                    });

                    // Wire remove buttons
                    if (listTarget) {
                        listTarget.querySelectorAll('button[data-index]').forEach(btn => {
                            btn.addEventListener('click', () => {
                                const idx = parseInt(btn.getAttribute('data-index'));
                                if (!isNaN(idx)) {
                                    selectedFilesState.splice(idx, 1);
                                    setInputFiles(photosInput, selectedFilesState);
                                    renderSelectedFiles(textTarget, containerTarget, listTarget);
                                }
                            });
                        });
                    }
                }

                function limitFiles(fileList) {
                    // Accept Array<File> or FileList; return Array<File> limited to MAX_FILES
                    const files = Array.isArray(fileList) ? fileList : Array.from(fileList);
                    if (files.length > MAX_FILES) {
                        alert(`You can upload up to ${MAX_FILES} photos.`);
                    }
                    return files.slice(0, MAX_FILES);
                }

                function setInputFiles(input, filesArray) {
                    // Create a new DataTransfer to set limited files on input
                    const dataTransfer = new DataTransfer();
                    filesArray.forEach(file => dataTransfer.items.add(file));
                    input.files = dataTransfer.files;
                }

                function mergeFiles(existingList, newList) {
                    const existing = Array.isArray(existingList) ? existingList : Array.from(existingList || []);
                    const incoming = Array.from(newList || []);
                    // Merge while avoiding exact duplicates (name+size)
                    const signature = (f) => `${f.name}|${f.size}`;
                    const seen = new Set(existing.map(signature));
                    const merged = [...existing];
                    for (const f of incoming) {
                        if (seen.has(signature(f))) continue;
                        merged.push(f);
                        seen.add(signature(f));
                        if (merged.length >= MAX_FILES) break;
                    }
                    return merged;
                }
            }

            // Only wire the scoped form area to keep a single uploader experience
            wireUploadArea(formUploadArea, formUploadText, formSelectedFiles, formFileList);

            // Prevent submit if files are not between 2 and 3
            const submitForm = document.getElementById('task-submit-form');
            if (submitForm && photosInput) {
                submitForm.addEventListener('submit', function(e) {
                    // Ensure the input reflects our state before submit
                    if (selectedFilesState.length > 0) {
                        setInputFiles(photosInput, selectedFilesState);
                    }
                    const count = selectedFilesState.length || (photosInput.files ? photosInput.files.length : 0);
                    if (count < MIN_FILES || count > MAX_FILES) {
                        e.preventDefault();
                        alert(`Please upload between ${MIN_FILES} and ${MAX_FILES} photos.`);
                    }
                });
            }
        });
    </script>
    
    <!-- Image Review Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-4" style="display: none;">
        <div class="relative max-w-7xl max-h-full w-full h-full flex items-center justify-center">
            <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10 bg-black bg-opacity-50 rounded-full p-2 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <button id="prevButton" onclick="previousImage()" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 z-10 bg-black bg-opacity-50 rounded-full p-3 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button id="nextButton" onclick="nextImage()" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 z-10 bg-black bg-opacity-50 rounded-full p-3 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
            <div class="flex items-center justify-center w-full h-full">
                <img id="modalImage" src="" alt="Task completion proof" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
            </div>
            <div id="imageCounter" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white bg-black bg-opacity-50 px-4 py-2 rounded-full text-sm">
                <span id="currentImageIndex">1</span> / <span id="totalImages">1</span>
            </div>
            <button id="downloadButton" onclick="downloadImage()" class="absolute bottom-4 right-4 text-white hover:text-gray-300 z-10 bg-black bg-opacity-50 rounded-full p-2 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </button>
        </div>
    </div>
    <script>
        let currentImageIndex = 0;
        let imageSources = [];
        document.addEventListener('DOMContentLoaded', function() {
            const imageElements = document.querySelectorAll('img[data-photo-url]');
            imageSources = Array.from(imageElements).map(img => img.getAttribute('data-photo-url'));
        });
        function openImageModal(imageSrc) {
            if (imageSources.length === 0) return;
            currentImageIndex = imageSources.indexOf(imageSrc);
            if (currentImageIndex === -1) currentImageIndex = 0;
            updateModalImage();
            const modal = document.getElementById('imageModal');
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        function nextImage() { if (imageSources.length > 1) { currentImageIndex = (currentImageIndex + 1) % imageSources.length; updateModalImage(); } }
        function previousImage() { if (imageSources.length > 1) { currentImageIndex = (currentImageIndex - 1 + imageSources.length) % imageSources.length; updateModalImage(); } }
        function updateModalImage() {
            const modalImage = document.getElementById('modalImage');
            const currentIndexSpan = document.getElementById('currentImageIndex');
            const totalImagesSpan = document.getElementById('totalImages');
            const prevButton = document.getElementById('prevButton');
            const nextButton = document.getElementById('nextButton');
            if (imageSources.length > 0) {
                modalImage.src = imageSources[currentImageIndex];
                currentIndexSpan.textContent = currentImageIndex + 1;
                totalImagesSpan.textContent = imageSources.length;
                if (imageSources.length === 1) { prevButton.style.display = 'none'; nextButton.style.display = 'none'; }
                else { prevButton.style.display = 'block'; nextButton.style.display = 'block'; }
            }
        }
        function downloadImage() { 
            if (imageSources.length > 0) { 
                const link = document.createElement('a'); 
                link.href = imageSources[currentImageIndex]; 
                link.download = `task-proof-${currentImageIndex + 1}.jpg`; 
                document.body.appendChild(link); 
                link.click(); 
                document.body.removeChild(link); 
            } 
        }
        document.addEventListener('click', function(e) { const modal = document.getElementById('imageModal'); if (e.target === modal) { closeImageModal(); } });
        document.addEventListener('keydown', function(e) { const modal = document.getElementById('imageModal'); if (modal && !modal.classList.contains('hidden')) { if (e.key === 'Escape') closeImageModal(); if (e.key === 'ArrowLeft') previousImage(); if (e.key === 'ArrowRight') nextImage(); } });
    </script>
</x-app-layout>
