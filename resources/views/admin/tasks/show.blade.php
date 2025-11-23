<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Task Details') }}
            </h2>
            <div class="flex space-x-2">
                @if($task->task_type !== 'user_uploaded' && $task->status !== 'completed' && $task->status !== 'published')
                    <a href="{{ route('admin.tasks.edit', $task) }}" class="inline-flex items-center gap-2 text-white font-medium py-2 px-4 rounded-lg transition-colors brand-primary-btn">
                        <i class="fas fa-edit"></i>
                        @if($task->status === 'inactive')
                            Edit Task (Required)
                        @else
                            Edit Task
                        @endif
                    </a>
                @endif
                <a href="{{ route('admin.tasks.index') }}" class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left"></i>
                    Back to Tasks
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Main Card Container -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <!-- Task Header -->
                <div class="px-6 py-6" style="background: linear-gradient(135deg, #F53003 0%, #F4A261 50%, #2A9D8F 100%);">
                    <div class="flex flex-col lg:flex-row justify-between items-start gap-4">
                        <div class="flex-1">
                            <h1 class="text-2xl lg:text-3xl font-bold text-white mb-3">
                                {{ $task->title }}
                            </h1>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-sm font-medium rounded-full bg-white/20 text-white">
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
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-sm font-medium rounded-full bg-white/20 text-white">
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
                        <div class="bg-white/20 rounded-lg px-6 py-4">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-white mb-1">
                                    {{ $task->points_awarded }}
                                </div>
                                <div class="text-xs text-white/90 font-medium">Points</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Task Content Section -->
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Main Content Column -->
                        <div class="lg:col-span-2 space-y-4">
                            <!-- Description -->
                            <div class="bg-white rounded-lg p-5 border border-gray-200 shadow-sm">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                                <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $task->description }}</p>
                            </div>

                            <!-- Task Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if($task->creation_date)
                                <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-calendar-alt" style="color: #F4A261;"></i>
                                        <h4 class="text-xs font-semibold text-gray-600 uppercase">Created</h4>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ is_string($task->creation_date) ? \Carbon\Carbon::parse($task->creation_date)->format('M j, Y \a\t g:i A') : $task->creation_date->format('M j, Y \a\t g:i A') }}
                                    </p>
                                </div>
                                @endif

                                @if($task->approval_date)
                                <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-check-square text-green-600"></i>
                                        <h4 class="text-xs font-semibold text-gray-600 uppercase">Approved</h4>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ is_string($task->approval_date) ? \Carbon\Carbon::parse($task->approval_date)->format('M j, Y \a\t g:i A') : $task->approval_date->format('M j, Y \a\t g:i A') }}
                                    </p>
                                </div>
                                @endif

                                @if($task->published_date)
                                <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-bullhorn" style="color: #2A9D8F;"></i>
                                        <h4 class="text-xs font-semibold text-gray-600 uppercase">Published</h4>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ is_string($task->published_date) ? \Carbon\Carbon::parse($task->published_date)->format('M j, Y \a\t g:i A') : $task->published_date->format('M j, Y \a\t g:i A') }}
                                    </p>
                                </div>
                                @endif

                                @if($task->due_date)
                                <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-play-circle" style="color: #F53003;"></i>
                                        <h4 class="text-xs font-semibold text-gray-600 uppercase">Start Date</h4>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">
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
                                        <i class="fas fa-hourglass-end text-gray-600"></i>
                                        <h4 class="text-xs font-semibold text-gray-600 uppercase">Due Date</h4>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">
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
                                        <i class="fas fa-map-marker-alt" style="color: #2A9D8F;"></i>
                                        <h4 class="text-xs font-semibold text-gray-600 uppercase">Location</h4>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $task->location }}
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Sidebar Column -->
                        <div class="lg:col-span-1 space-y-6">
                            <!-- Participants Section -->
                            <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                        <i class="fas fa-users" style="color: #F53003;"></i>
                                        Participants
                                    </h3>
                                    <span class="px-3 py-1 text-white text-sm font-bold rounded-full" style="background: linear-gradient(135deg, #F53003 0%, #F4A261 100%);">
                                        {{ $task->assignments->count() }}
                                    </span>
                                </div>

                                @if($task->assignments->count() > 0)
                                    <!-- Scrollable Participants List -->
                                    <div class="space-y-3 max-h-80 overflow-y-auto pr-2">
                                        @foreach($task->assignments as $assignment)
                                            <div class="flex items-center justify-between p-4 bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                                                <div class="flex items-center space-x-3 flex-1 min-w-0">
                                                    <div class="h-10 w-10 rounded-full flex items-center justify-center flex-shrink-0 shadow-md" style="background: linear-gradient(135deg, #F53003 0%, #F4A261 100%);">
                                                        <span class="text-sm font-bold text-white">
                                                            {{ strtoupper(substr($assignment->user->name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $assignment->user->name }}</p>
                                                        <p class="text-xs text-gray-500 truncate">{{ $assignment->user->email }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-2 flex-shrink-0 ml-3">
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full
                                                        @if($assignment->status === 'assigned') bg-orange-100 text-orange-800
                                                        @elseif($assignment->status === 'submitted') bg-yellow-100 text-yellow-800
                                                        @elseif($assignment->status === 'completed') bg-teal-100 text-teal-800
                                                        @endif">
                                                        <i class="fas 
                                                            @if($assignment->status === 'assigned') fa-user-check
                                                            @elseif($assignment->status === 'submitted') fa-paper-plane
                                                            @elseif($assignment->status === 'completed') fa-check-circle
                                                            @endif"></i>
                                                        {{ ucfirst($assignment->status) }}
                                                    </span>
                                                    @if(!empty($assignment->progress))
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full
                                                        @switch($assignment->progress)
                                                            @case('accepted') bg-gray-100 text-gray-800 @break
                                                            @case('on_the_way') bg-purple-100 text-purple-800 @break
                                                            @case('working') bg-indigo-100 text-indigo-800 @break
                                                            @case('done') bg-teal-100 text-teal-800 @break
                                                            @case('submitted_proof') bg-orange-100 text-orange-800 @break
                                                            @default bg-gray-100 text-gray-800
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
                                    <div class="text-center py-8">
                                        <i class="fas fa-users-slash text-gray-300 text-3xl mb-3"></i>
                                        <p class="text-sm text-gray-500 font-medium">No users have joined this task yet.</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Admin Actions Section -->
                            <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                    <i class="fas fa-cogs" style="color: #F53003;"></i>
                                    Admin Actions
                                </h3>
                                <div class="space-y-3">
                                    @if($task->status === 'pending')
                                        <form action="{{ route('admin.tasks.approve', $task) }}" method="POST" class="w-full">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 border border-transparent text-sm font-bold rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-md transition-colors">
                                                <i class="fas fa-check"></i> Approve Task
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.tasks.reject', $task) }}" method="POST" class="w-full" id="reject-task-form">
                                            @csrf
                                            <button type="button" 
                                                    onclick="showConfirmModal('Are you sure you want to reject this task?', 'Reject Task', 'Reject', 'Cancel', 'red').then(confirmed => { if(confirmed) document.getElementById('reject-task-form').submit(); });"
                                                    class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 border border-transparent text-sm font-bold rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-md transition-colors">
                                                <i class="fas fa-times"></i> Reject Task
                                            </button>
                                        </form>
                                    @elseif($task->status === 'approved' && $task->task_type !== 'user_uploaded')
                                        <form action="{{ route('admin.tasks.publish', $task) }}" method="POST" class="w-full">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 border border-transparent text-sm font-bold rounded-lg text-white shadow-md transition-colors brand-primary-btn">
                                                <i class="fas fa-bullhorn"></i> Publish Task
                                            </button>
                                        </form>
                                    @elseif($task->status === 'submitted')
                                        <form action="{{ route('admin.tasks.complete', $task) }}" method="POST" class="w-full">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 border border-transparent text-sm font-bold rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-md transition-colors">
                                                <i class="fas fa-check-double"></i> Complete Task
                                            </button>
                                        </form>
                                    @endif
                                    
                                    {{-- Only show deactivate/reactivate for non-user-uploaded tasks (admin-created tasks) --}}
                                    @if($task->task_type !== 'user_uploaded')
                                        @if($task->status !== 'completed' && $task->status !== 'inactive')
                                            <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST" class="w-full" id="deactivate-task-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        onclick="showConfirmModal('Are you sure you want to deactivate this task?', 'Deactivate Task', 'Deactivate', 'Cancel', 'red').then(confirmed => { if(confirmed) document.getElementById('deactivate-task-form').submit(); });"
                                                        class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 border border-transparent text-sm font-bold rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-md transition-colors">
                                                    <i class="fas fa-pause"></i> Deactivate Task
                                                </button>
                                            </form>
                                        @elseif($task->status === 'inactive')
                                            <a href="{{ route('admin.tasks.edit', $task) }}" 
                                               class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 border border-transparent text-sm font-bold rounded-lg text-white shadow-md transition-colors brand-primary-btn">
                                                <i class="fas fa-edit"></i> Edit Task (Required Before Reactivation)
                                            </a>
                                            
                                            @php
                                                if (!$task->deactivated_at) {
                                                    $canReactivate = false;
                                                } else {
                                                    $canReactivate = $task->updated_at && $task->updated_at > $task->deactivated_at;
                                                }
                                            @endphp
                                            
                                            @if($canReactivate)
                                                <form action="{{ route('admin.tasks.reactivate', $task) }}" method="POST" class="w-full mt-3">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 border border-transparent text-sm font-bold rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-md transition-colors">
                                                        <i class="fas fa-play"></i> Reactivate Task
                                                    </button>
                                                </form>
                                            @else
                                                <div class="w-full mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                                    <p class="text-sm text-yellow-800 font-medium">
                                                        ⚠️ This task must be edited before it can be reactivated.
                                                    </p>
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Scrollbar Styling -->
    <style>
        /* Brand Primary Button with Gradient */
        .brand-primary-btn {
            background: linear-gradient(135deg, #F53003 0%, #F4A261 100%);
            box-shadow: 0 4px 15px rgba(245, 48, 3, 0.3);
        }
        .brand-primary-btn:hover {
            background: linear-gradient(135deg, #d42802 0%, #e68a4a 100%);
            box-shadow: 0 6px 20px rgba(245, 48, 3, 0.4);
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
            background: linear-gradient(135deg, #F53003 0%, #F4A261 100%);
            border-radius: 10px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #d42802 0%, #e68a4a 100%);
        }
        
        /* Firefox Scrollbar */
        .overflow-y-auto {
            scrollbar-width: thin;
            scrollbar-color: #F53003 #f1f1f1;
        }
        
        /* Smooth transitions */
        .brand-primary-btn {
            transition: all 0.3s ease;
        }
    </style>
</x-admin-layout>
