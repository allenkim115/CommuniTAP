<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                {{ __('Task Details') }}
            </h2>
            <div class="w-full sm:w-auto">
                <a href="{{ route('admin.tasks.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-white font-semibold py-3 px-6 sm:py-2 sm:px-4 rounded-lg transition-colors text-base sm:text-sm min-h-[44px]"
                   style="background-color: #2B9D8D;"
                   onmouseover="this.style.backgroundColor='#248A7C'"
                   onmouseout="this.style.backgroundColor='#2B9D8D'">
                    <i class="fas fa-arrow-left text-lg"></i>
                    Back to Tasks
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Main Card Container -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden mb-4 sm:mb-6">
                <!-- Task Header -->
                <div class="px-4 sm:px-6 py-6 sm:py-8 rounded-t-lg" style="background-color: #FED2B3;">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
                        <div class="flex-1 min-w-0">
                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-800 mb-4 break-words leading-tight">
                                {{ $task->title }}
                            </h1>
                            <div class="flex flex-wrap items-center gap-2.5">
                                <span class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs sm:text-sm font-semibold rounded-lg bg-white/70 backdrop-blur-sm text-gray-800 shadow-sm">
                                    <i class="fas 
                                        @if($task->status === 'pending') fa-clock
                                        @elseif($task->status === 'approved') fa-check-circle
                                        @elseif($task->status === 'published') fa-rocket
                                        @elseif($task->status === 'assigned') fa-user-check
                                        @elseif($task->status === 'submitted') fa-paper-plane
                                        @elseif($task->status === 'completed') fa-check-double
                                        @elseif($task->status === 'rejected') fa-times-circle
                                        @elseif($task->status === 'uncompleted') fa-exclamation-triangle
                                        @elseif($task->status === 'inactive') fa-pause-circle
                                        @else fa-list
                                        @endif text-xs
                                    "></i>
                                    {{ ucfirst($task->status) }}
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs sm:text-sm font-semibold rounded-lg bg-white/70 backdrop-blur-sm text-gray-800 shadow-sm">
                                    <i class="fas 
                                        @if($task->task_type === 'daily') fa-calendar-day
                                        @elseif($task->task_type === 'one_time') fa-bullseye
                                        @else fa-user-plus
                                        @endif text-xs
                                    "></i>
                                    {{ ucfirst(str_replace('_', ' ', $task->task_type)) }}
                                </span>
                            </div>
                        </div>
                        <div class="bg-white/60 backdrop-blur-md rounded-2xl px-6 sm:px-8 py-5 sm:py-6 shadow-lg border border-white/50">
                            <div class="text-center">
                                <div class="text-3xl sm:text-4xl font-bold text-gray-800 mb-1">
                                    {{ $task->points_awarded }}
                                </div>
                                <div class="text-sm text-gray-700 font-semibold">Points</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Task Content Section -->
                <div class="px-4 sm:px-6 py-4 sm:py-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
                        <!-- Main Content Column -->
                        <div class="lg:col-span-2 space-y-4 sm:space-y-5">
                            <!-- Description -->
                            <div class="bg-white rounded-lg p-4 sm:p-5 border border-gray-200 shadow-sm">
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3">Description</h3>
                                <p class="text-sm sm:text-base text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $task->description }}</p>
                            </div>

                            <!-- Task Details -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                @if($task->creation_date)
                                <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-calendar-alt text-sm" style="color: #F3A261;"></i>
                                        <h4 class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Created</h4>
                                    </div>
                                    <p class="text-sm sm:text-base font-medium text-gray-900 break-words">
                                        {{ is_string($task->creation_date) ? \Carbon\Carbon::parse($task->creation_date)->format('M j, Y \a\t g:i A') : $task->creation_date->format('M j, Y \a\t g:i A') }}
                                    </p>
                                </div>
                                @endif

                                @if($task->approval_date)
                                <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-check-square text-sm" style="color: #2B9D8D;"></i>
                                        <h4 class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Approved</h4>
                                    </div>
                                    <p class="text-sm sm:text-base font-medium text-gray-900 break-words">
                                        {{ is_string($task->approval_date) ? \Carbon\Carbon::parse($task->approval_date)->format('M j, Y \a\t g:i A') : $task->approval_date->format('M j, Y \a\t g:i A') }}
                                    </p>
                                </div>
                                @endif

                                @if($task->published_date)
                                <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-bullhorn text-sm" style="color: #2B9D8D;"></i>
                                        <h4 class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Published</h4>
                                    </div>
                                    <p class="text-sm sm:text-base font-medium text-gray-900 break-words">
                                        {{ is_string($task->published_date) ? \Carbon\Carbon::parse($task->published_date)->format('M j, Y \a\t g:i A') : $task->published_date->format('M j, Y \a\t g:i A') }}
                                    </p>
                                </div>
                                @endif

                                @if($task->due_date)
                                <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-play-circle text-sm" style="color: #F3A261;"></i>
                                        <h4 class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Start Date</h4>
                                    </div>
                                    <p class="text-sm sm:text-base font-medium text-gray-900 break-words">
                                        @php
                                            $startDate = is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date) : $task->due_date;
                                            if ($task->start_time) {
                                                $startDateTime = \Carbon\Carbon::parse($startDate->toDateString() . ' ' . $task->start_time);
                                                echo $startDateTime->format('M j, Y \a\t g:i A');
                                            } else {
                                                echo $startDate->format('M j, Y \a\t g:i A');
                                            }
                                        @endphp
                                    </p>
                                </div>
                                @endif

                                @if($task->due_date)
                                <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-hourglass-end text-sm text-gray-600"></i>
                                        <h4 class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Due Date</h4>
                                    </div>
                                    <p class="text-sm sm:text-base font-medium text-gray-900 break-words">
                                        @php
                                            $dueDate = is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date) : $task->due_date;
                                            if ($task->end_time) {
                                                $deadline = \Carbon\Carbon::parse($dueDate->toDateString() . ' ' . $task->end_time);
                                                echo $deadline->format('M j, Y \a\t g:i A');
                                            } else {
                                                echo $dueDate->format('M j, Y \a\t g:i A');
                                            }
                                        @endphp
                                    </p>
                                </div>
                                @endif

                                @if($task->location)
                                <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-map-marker-alt text-sm" style="color: #2B9D8D;"></i>
                                        <h4 class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Location</h4>
                                    </div>
                                    <p class="text-sm sm:text-base font-medium text-gray-900 break-words">
                                        {{ $task->location }}
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Sidebar Column -->
                        <div class="lg:col-span-1 space-y-4 sm:space-y-6">
                            <!-- Participants Section -->
                            <div class="bg-white rounded-lg p-4 sm:p-6 border border-gray-200 shadow-sm">
                                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4 mb-4">
                                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                                        <i class="fas fa-users text-base" style="color: #F3A261;"></i>
                                        Participants
                                    </h3>
                                    <span class="px-3 py-1.5 sm:py-1 text-xs sm:text-sm font-bold rounded-full whitespace-nowrap" style="background: linear-gradient(135deg, #F3A261 0%, #F3A261 100%); color: white;">
                                        @if(!is_null($task->max_participants))
                                            {{ $task->assignments->count() }} / {{ $task->max_participants }}
                                        @else
                                            {{ $task->assignments->count() }}
                                        @endif
                                    </span>
                                </div>

                                @if($task->assignments->count() > 0)
                                    @php
                                        $displayLimit = 3;
                                        $displayedParticipants = $task->assignments->take($displayLimit);
                                        $hasMore = $task->assignments->count() > $displayLimit;
                                    @endphp
                                    <!-- Limited Participants List -->
                                    <div class="space-y-3 mb-4">
                                        @foreach($displayedParticipants as $assignment)
                                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-3 sm:p-4 bg-gray-50 sm:bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow gap-3">
                                                <div class="flex items-center space-x-3 flex-1 min-w-0 w-full sm:w-auto">
                                                    <x-user-avatar
                                                        :user="$assignment->user"
                                                        size="h-10 w-10 sm:h-10 sm:w-10"
                                                        text-size="text-sm"
                                                        class="shadow-md flex-shrink-0"
                                                        style="background: linear-gradient(135deg, #F3A261 0%, #F3A261 100%);"
                                                    />
                                                    <div class="min-w-0 flex-1">
                                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $assignment->user->name }}</p>
                                                        <p class="text-xs text-gray-500 truncate">{{ $assignment->user->email }}</p>
                                                    </div>
                                                </div>
                                                @php
                                                    $assignBg = 'rgba(243, 162, 97, 0.2)';
                                                    $assignColor = '#F3A261';
                                                    if($assignment->status === 'submitted') {
                                                        $assignBg = 'rgba(254, 210, 179, 0.2)';
                                                        $assignColor = '#FED2B3';
                                                    } elseif($assignment->status === 'completed') {
                                                        $assignBg = 'rgba(43, 157, 141, 0.2)';
                                                        $assignColor = '#2B9D8D';
                                                    }
                                                @endphp
                                                <div class="flex items-center flex-wrap gap-2 flex-shrink-0 w-full sm:w-auto">
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full whitespace-nowrap" style="background-color: {{ $assignBg }}; color: {{ $assignColor }};"
                                                        @if($assignment->status === 'assigned') 
                                                        @elseif($assignment->status === 'submitted') 
                                                        @elseif($assignment->status === 'completed') 
                                                        @endif>
                                                        <i class="fas 
                                                            @if($assignment->status === 'assigned') fa-user-check
                                                            @elseif($assignment->status === 'submitted') fa-paper-plane
                                                            @elseif($assignment->status === 'completed') fa-check-circle
                                                            @endif text-xs"></i>
                                                        {{ ucfirst($assignment->status) }}
                                                    </span>
                                                    @if(!empty($assignment->progress))
                                                    @php
                                                        $progressBg = 'rgba(229, 231, 235, 0.5)';
                                                        $progressColor = '#6B7280';
                                                        if(in_array($assignment->progress, ['on_the_way', 'working', 'done', 'submitted_proof'])) {
                                                            $progressBg = 'rgba(43, 157, 141, 0.2)';
                                                            $progressColor = '#2B9D8D';
                                                        } elseif($assignment->progress === 'accepted') {
                                                            $progressBg = 'rgba(243, 162, 97, 0.2)';
                                                            $progressColor = '#F3A261';
                                                        }
                                                    @endphp
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full whitespace-nowrap" style="background-color: {{ $progressBg }}; color: {{ $progressColor }};"
                                                        @switch($assignment->progress)
                                                            @case('accepted') @break
                                                            @case('on_the_way') @break
                                                            @case('working') @break
                                                            @case('done') @break
                                                            @case('submitted_proof') @break
                                                            @default 
                                                        @endswitch>
                                                        <i class="fas 
                                                            @if($assignment->progress === 'accepted') fa-handshake
                                                            @elseif($assignment->progress === 'on_the_way') fa-route
                                                            @elseif($assignment->progress === 'working') fa-tools
                                                            @elseif($assignment->progress === 'done') fa-clipboard-check
                                                            @elseif($assignment->progress === 'submitted_proof') fa-file-upload
                                                            @endif text-xs"></i>
                                                        {{ ucfirst(str_replace('_',' ', $assignment->progress)) }}
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                @else
                                    <div class="text-center py-6 sm:py-8">
                                        <i class="fas fa-users-slash text-gray-300 text-2xl sm:text-3xl mb-3"></i>
                                        <p class="text-xs sm:text-sm text-gray-500 font-medium">No users have joined this task yet.</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Admin Actions Section -->
                            <div class="bg-white rounded-lg p-4 sm:p-6 border border-gray-200 shadow-sm">
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                    <i class="fas fa-cogs text-base" style="color: #F3A261;"></i>
                                    Admin Actions
                                </h3>
                                <div class="space-y-3">
                                    {{-- Edit Task button for non-user_uploaded tasks (except completed, published, and inactive which are handled separately) --}}
                                    @if($task->task_type !== 'user_uploaded' && $task->status !== 'completed' && $task->status !== 'published' && $task->status !== 'inactive')
                                        <a href="{{ route('admin.tasks.edit', $task) }}" 
                                           class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 border border-transparent text-sm font-bold rounded-lg text-white shadow-md transition-colors brand-primary-btn min-h-[44px]">
                                            <i class="fas fa-edit text-base"></i> Edit Task
                                        </a>
                                    @endif
                                    
                                    @if($task->status === 'pending')
                                        {{-- For admin-created tasks (daily, one_time), show Edit and Publish only --}}
                                        @if($task->task_type !== 'user_uploaded')
                                            <form action="{{ route('admin.tasks.publish', $task) }}" method="POST" class="w-full" novalidate>
                                                @csrf
                                                <button type="submit" 
                                                        class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 border border-transparent text-sm font-bold rounded-lg text-white shadow-md transition-colors brand-primary-btn min-h-[44px]">
                                                    <i class="fas fa-bullhorn text-base"></i> Publish Task
                                                </button>
                                            </form>
                                        @else
                                            {{-- For user-uploaded tasks, show Approve and Reject --}}
                                            <form action="{{ route('admin.tasks.approve', $task) }}" method="POST" class="w-full" novalidate>
                                                @csrf
                                                <button type="submit" 
                                                        class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 border border-transparent text-sm font-bold rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-md transition-colors min-h-[44px]">
                                                    <i class="fas fa-check text-base"></i> Approve Task
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.tasks.reject', $task) }}" method="POST" class="w-full" id="reject-task-form" novalidate>
                                                @csrf
                                                <button type="button" 
                                                        onclick="showConfirmModal('Are you sure you want to reject this task?', 'Reject Task', 'Reject', 'Cancel', 'red').then(confirmed => { if(confirmed) document.getElementById('reject-task-form').submit(); });"
                                                        class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 border border-transparent text-sm font-bold rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-offset-2 shadow-md transition-colors min-h-[44px]"
                                                        style="background-color: #2B9D8D;"
                                                        onmouseover="this.style.backgroundColor='#248A7C'"
                                                        onmouseout="this.style.backgroundColor='#2B9D8D'">
                                                    <i class="fas fa-times text-base"></i> Reject Task
                                                </button>
                                            </form>
                                        @endif
                                    @elseif($task->status === 'approved' && $task->task_type !== 'user_uploaded')
                                        <form action="{{ route('admin.tasks.publish', $task) }}" method="POST" class="w-full" novalidate>
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 border border-transparent text-sm font-bold rounded-lg text-white shadow-md transition-colors brand-primary-btn min-h-[44px]">
                                                <i class="fas fa-bullhorn text-base"></i> Publish Task
                                            </button>
                                        </form>
                                    @elseif($task->status === 'submitted')
                                        <form action="{{ route('admin.tasks.complete', $task) }}" method="POST" class="w-full" novalidate>
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 border border-transparent text-sm font-bold rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-md transition-colors min-h-[44px]">
                                                <i class="fas fa-check-double text-base"></i> Complete Task
                                            </button>
                                        </form>
                                    @endif
                                    
                                    {{-- Only show deactivate/reactivate for non-user-uploaded tasks (admin-created tasks) --}}
                                    {{-- Deactivate should only be available for published/live tasks that are visible to users --}}
                                    @if($task->task_type !== 'user_uploaded')
                                        @if(in_array($task->status, ['published', 'approved']) && $task->status !== 'inactive')
                                            <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST" class="w-full" id="deactivate-task-form" novalidate>
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        onclick="showConfirmModal('Are you sure you want to deactivate this task?', 'Deactivate Task', 'Deactivate', 'Cancel', 'red').then(confirmed => { if(confirmed) document.getElementById('deactivate-task-form').submit(); });"
                                                        class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 border border-transparent text-sm font-bold rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-offset-2 shadow-md transition-colors min-h-[44px]"
                                                        style="background-color: #2B9D8D;"
                                                        onmouseover="this.style.backgroundColor='#248A7C'"
                                                        onmouseout="this.style.backgroundColor='#2B9D8D'">
                                                    <i class="fas fa-pause text-base"></i> Deactivate Task
                                                </button>
                                            </form>
                                        @elseif($task->status === 'inactive')
                                            <a href="{{ route('admin.tasks.edit', $task) }}" 
                                               class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 border border-transparent text-sm font-bold rounded-lg text-white shadow-md transition-colors brand-primary-btn min-h-[44px]">
                                                <i class="fas fa-edit text-base"></i> 
                                                <span class="hidden sm:inline">Edit Task (Required Before Reactivation)</span>
                                                <span class="sm:hidden">Edit Task</span>
                                            </a>
                                            
                                            @php
                                                if (!$task->deactivated_at) {
                                                    $canReactivate = false;
                                                } else {
                                                    $canReactivate = $task->updated_at && $task->updated_at > $task->deactivated_at;
                                                }
                                            @endphp
                                            
                                            @if($canReactivate)
                                                <form action="{{ route('admin.tasks.reactivate', $task) }}" method="POST" class="w-full mt-3" novalidate>
                                                    @csrf
                                                    <button type="submit" 
                                                            class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 border border-transparent text-sm font-bold rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-md transition-colors min-h-[44px]">
                                                        <i class="fas fa-play text-base"></i> Reactivate Task
                                                    </button>
                                                </form>
                                            @else
                                                <div class="w-full mt-3 p-3 rounded-lg border" style="background-color: rgba(254, 210, 179, 0.2); border-color: #F3A261;">
                                                    <p class="text-xs sm:text-sm font-medium" style="color: #C9732A;">
                                                        ⚠️ This task must be edited before it can be reactivated.
                                                    </p>
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                    
                                    {{-- Show message if no actions are available --}}
                                    @php
                                        $hasAction = false;
                                        // Check if any action buttons were rendered above
                                        if ($task->status === 'pending') {
                                            if ($task->task_type !== 'user_uploaded') {
                                                $hasAction = true; // Edit and Publish buttons for admin-created tasks
                                            } else {
                                                $hasAction = true; // Approve/Reject buttons for user-uploaded tasks
                                            }
                                        } elseif ($task->status === 'approved' && $task->task_type !== 'user_uploaded') {
                                            $hasAction = true; // Publish button
                                        } elseif ($task->status === 'submitted') {
                                            $hasAction = true; // Complete button
                                        } elseif ($task->task_type !== 'user_uploaded') {
                                            // Check for Edit button (shown for non-inactive, non-completed, non-published tasks)
                                            if ($task->status !== 'completed' && $task->status !== 'published' && $task->status !== 'inactive') {
                                                $hasAction = true; // Edit button
                                            }
                                            // For admin-created tasks, check deactivate/reactivate section
                                            if (in_array($task->status, ['published', 'approved']) && $task->status !== 'inactive') {
                                                $hasAction = true; // Deactivate button
                                            } elseif ($task->status === 'inactive') {
                                                $hasAction = true; // Edit button (always shown for inactive)
                                            }
                                        }
                                    @endphp
                                    
                                    @if(!$hasAction)
                                        <div class="w-full p-3 sm:p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
                                            <i class="fas fa-info-circle text-gray-400 text-lg sm:text-xl mb-2"></i>
                                            <p class="text-xs sm:text-sm text-gray-600 font-medium">
                                                No actions available for this task in its current state.
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                Status: <span class="font-semibold">{{ ucfirst($task->status) }}</span>
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Participants Modal -->
    <div id="participants-modal" class="fixed inset-0 z-50 hidden" aria-hidden="true" style="backdrop-filter: blur(4px);">
        <div class="absolute inset-0 bg-black/50" onclick="closeParticipantsModal()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-2 sm:p-4">
            <div class="w-full max-w-3xl bg-white rounded-xl sm:rounded-2xl shadow-2xl overflow-hidden border-2 border-orange-300 transform transition-all max-h-[95vh] sm:max-h-[90vh] flex flex-col">
                <!-- Modal Header -->
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 flex items-center justify-between gap-3" style="background: linear-gradient(135deg, #F3A261 0%, #F3A261 100%);">
                        <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1">
                        <i class="fas fa-users text-white text-lg sm:text-xl flex-shrink-0"></i>
                        <h3 class="text-base sm:text-xl font-bold text-white truncate">All Participants</h3>
                        <span class="px-2 sm:px-3 py-1 bg-white/20 text-white text-xs sm:text-sm font-bold rounded-full whitespace-nowrap flex-shrink-0">
                            @if(!is_null($task->max_participants))
                                {{ $task->assignments->count() }} / {{ $task->max_participants }}
                            @else
                                {{ $task->assignments->count() }}
                            @endif
                        </span>
                    </div>
                    <button type="button" class="text-white hover:text-gray-200 transition-colors flex-shrink-0 p-1 min-w-[32px] min-h-[32px] flex items-center justify-center" onclick="closeParticipantsModal()" aria-label="Close modal">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Modal Content -->
                <div class="flex-1 overflow-y-auto px-4 sm:px-6 py-3 sm:py-4">
                    @if($task->assignments->count() > 0)
                        <div class="space-y-3">
                            @foreach($task->assignments as $assignment)
                                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-3 sm:p-4 bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow gap-3">
                                    <div class="flex items-center space-x-3 flex-1 min-w-0 w-full sm:w-auto">
                                        <x-user-avatar
                                            :user="$assignment->user"
                                            size="h-10 w-10 sm:h-12 sm:w-12"
                                            text-size="text-sm sm:text-base"
                                            class="flex-shrink-0 shadow-md"
                                            style="background: linear-gradient(135deg, #F3A261 0%, #2B9D8D 100%);"
                                        />
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $assignment->user->name }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ $assignment->user->email }}</p>
                                        </div>
                                    </div>
                                    @php
                                        $assignBg2 = 'rgba(243, 162, 97, 0.2)';
                                        $assignColor2 = '#F3A261';
                                        if($assignment->status === 'submitted') {
                                            $assignBg2 = 'rgba(254, 210, 179, 0.2)';
                                            $assignColor2 = '#FED2B3';
                                        } elseif($assignment->status === 'completed') {
                                            $assignBg2 = 'rgba(43, 157, 141, 0.2)';
                                            $assignColor2 = '#2B9D8D';
                                        }
                                    @endphp
                                    <div class="flex items-center space-x-2 flex-shrink-0 w-full sm:w-auto flex-wrap gap-2">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full" style="background-color: {{ $assignBg2 }}; color: {{ $assignColor2 }};"
                                            @if($assignment->status === 'assigned') 
                                            @elseif($assignment->status === 'submitted') 
                                            @elseif($assignment->status === 'completed') 
                                            @endif>
                                            <i class="fas 
                                                @if($assignment->status === 'assigned') fa-user-check
                                                @elseif($assignment->status === 'submitted') fa-paper-plane
                                                @elseif($assignment->status === 'completed') fa-check-circle
                                                @endif"></i>
                                            {{ ucfirst($assignment->status) }}
                                        </span>
                                        @if(!empty($assignment->progress))
                                        @php
                                            $progressBg2 = 'rgba(229, 231, 235, 0.5)';
                                            $progressColor2 = '#6B7280';
                                            if(in_array($assignment->progress ?? '', ['on_the_way', 'working', 'done'])) {
                                                $progressBg2 = 'rgba(43, 157, 141, 0.2)';
                                                $progressColor2 = '#2B9D8D';
                                            } elseif(($assignment->progress ?? '') === 'accepted' || ($assignment->progress ?? '') === 'submitted_proof') {
                                                $progressBg2 = 'rgba(243, 162, 97, 0.2)';
                                                $progressColor2 = '#F3A261';
                                            }
                                        @endphp
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full" style="background-color: {{ $progressBg2 }}; color: {{ $progressColor2 }};"
                                            @switch($assignment->progress ?? '')
                                                @case('accepted') @break
                                                @case('on_the_way') @break
                                                @case('working') @break
                                                @case('done') @break
                                                @case('submitted_proof') @break
                                                @default 
                                            @endswitch">
                                            <i class="fas 
                                                @if($assignment->progress === 'accepted') fa-handshake
                                                @elseif($assignment->progress === 'on_the_way') fa-route
                                                @elseif($assignment->progress === 'working') fa-tools
                                                @elseif($assignment->progress === 'done') fa-clipboard-check
                                                @elseif($assignment->progress === 'submitted_proof') fa-file-upload
                                                @endif"></i>
                                            {{ ucfirst(str_replace('_',' ', $assignment->progress)) }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-users-slash text-gray-300 text-4xl mb-4"></i>
                            <p class="text-sm text-gray-500 font-medium">No users have joined this task yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Participants Modal -->
    <script>
        function openParticipantsModal() {
            const modal = document.getElementById('participants-modal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeParticipantsModal() {
            const modal = document.getElementById('participants-modal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeParticipantsModal();
            }
        });
    </script>

    <!-- Custom Scrollbar Styling -->
    <style>
        /* Brand Primary Button */
        .brand-primary-btn {
            background-color: #F3A261;
            box-shadow: 0 4px 15px rgba(243, 162, 97, 0.3);
        }
        .brand-primary-btn:hover {
            background-color: #E8944F;
            box-shadow: 0 6px 20px rgba(243, 162, 97, 0.4);
            transform: translateY(-1px);
        }
        
        /* Custom Scrollbar for Participants Section */
        .overflow-y-auto::-webkit-scrollbar {
            width: 8px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-thumb {
            background-color: #F3A261;
            border-radius: 10px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background-color: #E8944F;
        }
        
        /* Firefox Scrollbar */
        .overflow-y-auto {
            scrollbar-width: thin;
            scrollbar-color: #F3A261 #f1f1f1;
        }
        
        /* Smooth transitions */
        .brand-primary-btn {
            transition: all 0.3s ease;
        }
    </style>
</x-admin-layout>
