<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Task Details') }}
            </h2>
            <div class="flex space-x-2">
                @if($task->task_type !== 'user_uploaded' && $task->status !== 'completed')
                    <a href="{{ route('admin.tasks.edit', $task) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Edit Task
                    </a>
                @endif
                <a href="{{ route('admin.tasks.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Tasks
                </a>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                <!-- Task Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $task->title }}</h1>
                            <div class="mt-2 flex items-center space-x-4">
                                <span class="px-3 py-1 text-sm font-medium rounded-full
                                    @if($task->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @elseif($task->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($task->status === 'published') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                    @elseif($task->status === 'assigned') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                    @elseif($task->status === 'submitted') bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200
                                    @elseif($task->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($task->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                    @endif">
                                    {{ ucfirst($task->status) }}
                                </span>
                                <span class="px-3 py-1 text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 rounded-full">
                                    {{ ucfirst(str_replace('_', ' ', $task->task_type)) }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $task->points_awarded }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Points</div>
                        </div>
                    </div>
                </div>

                <!-- Task Content -->
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Main Content -->
                        <div class="lg:col-span-2">
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Description</h3>
                                <div class="prose dark:prose-invert max-w-none">
                                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $task->description }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="lg:col-span-1">
                            <div class="space-y-6">
                                <!-- Task Information -->
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Task Information</h3>
                                    <dl class="space-y-3">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created</dt>
                                            <dd class="text-sm text-gray-900 dark:text-white">
                                                @if($task->creation_date)
                                                    {{ is_string($task->creation_date) ? \Carbon\Carbon::parse($task->creation_date)->format('F j, Y \a\t g:i A') : $task->creation_date->format('F j, Y \a\t g:i A') }}
                                                @else
                                                    Not available
                                                @endif
                                            </dd>
                                        </div>
                                        
                                        @if($task->approval_date)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Approved</dt>
                                            <dd class="text-sm text-gray-900 dark:text-white">
                                                {{ is_string($task->approval_date) ? \Carbon\Carbon::parse($task->approval_date)->format('F j, Y \a\t g:i A') : $task->approval_date->format('F j, Y \a\t g:i A') }}
                                            </dd>
                                        </div>
                                        @endif
                                        
                                        @if($task->published_date)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Published</dt>
                                            <dd class="text-sm text-gray-900 dark:text-white">
                                                {{ is_string($task->published_date) ? \Carbon\Carbon::parse($task->published_date)->format('F j, Y \a\t g:i A') : $task->published_date->format('F j, Y \a\t g:i A') }}
                                            </dd>
                                        </div>
                                        @endif
                                        
                                        @if($task->due_date)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Due Date</dt>
                                            <dd class="text-sm text-gray-900 dark:text-white">
                                                {{ is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date)->format('F j, Y \a\t g:i A') : $task->due_date->format('F j, Y \a\t g:i A') }}
                                            </dd>
                                        </div>
                                        @endif
                                    </dl>
                                </div>

                                <!-- Participants -->
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
                                        Participants ({{ $task->assignments->count() }})
                                    </h3>
                                    @if($task->assignments->count() > 0)
                                        <div class="space-y-3 max-h-64 overflow-y-auto">
                                            @foreach($task->assignments as $assignment)
                                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="h-8 w-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
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
                                                        @if(!empty($assignment->progress))
                                                        <span class="px-2 py-1 text-xs rounded-full
                                                            @switch($assignment->progress)
                                                                @case('accepted') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 @break
                                                                @case('on_the_way') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 @break
                                                                @case('working') bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200 @break
                                                                @case('done') bg-teal-100 text-teal-800 dark:bg-teal-900 dark:text-teal-200 @break
                                                                @case('submitted_proof') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200 @break
                                                                @default bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                                            @endswitch">
                                                            {{ ucfirst(str_replace('_',' ', $assignment->progress)) }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500 dark:text-gray-400">No users have joined this task yet.</p>
                                    @endif
                                </div>

                                <!-- Admin Actions -->
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Admin Actions</h3>
                                    <div class="space-y-2">
                                        @if($task->status === 'pending')
                                            <form action="{{ route('admin.tasks.approve', $task) }}" method="POST" class="w-full">
                                                @csrf
                                                <button type="submit" 
                                                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                    Approve Task
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.tasks.reject', $task) }}" method="POST" class="w-full">
                                                @csrf
                                                <button type="submit" 
                                                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                                        onclick="return confirm('Are you sure you want to reject this task?')">
                                                    Reject Task
                                                </button>
                                            </form>
                                        @elseif($task->status === 'approved')
                                            <form action="{{ route('admin.tasks.publish', $task) }}" method="POST" class="w-full">
                                                @csrf
                                                <button type="submit" 
                                                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    Publish Task
                                                </button>
                                            </form>
                                        @elseif($task->status === 'submitted')
                                            <form action="{{ route('admin.tasks.complete', $task) }}" method="POST" class="w-full">
                                                @csrf
                                                <button type="submit" 
                                                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                    Complete Task
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if($task->status !== 'completed')
                                            <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST" class="w-full">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                                        onclick="return confirm('Are you sure you want to deactivate this task?')">
                                                    Deactivate Task
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
