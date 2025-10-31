<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Task Management') }}
            </h2>
            
            <a href="{{ route('admin.tasks.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create Task
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Toast Notifications -->
            <x-session-toast />

            <!-- Task Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Tasks</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $taskStats['total'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $taskStats['pending'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $taskStats['completed'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Published</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $taskStats['published'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                <form action="{{ route('admin.tasks.filter') }}" method="GET" class="flex flex-wrap gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="status" id="status" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="all" {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>All Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="assigned" {{ request('status') === 'assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div>
                        <label for="task_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Task Type</label>
                        <select name="task_type" id="task_type" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="all" {{ request('task_type') === 'all' || !request('task_type') ? 'selected' : '' }}>All Types</option>
                            <option value="daily" {{ request('task_type') === 'daily' ? 'selected' : '' }}>Daily Task</option>
                            <option value="one_time" {{ request('task_type') === 'one_time' ? 'selected' : '' }}>One-Time Task</option>
                            <option value="user_uploaded" {{ request('task_type') === 'user_uploaded' ? 'selected' : '' }}>User-Uploaded Task</option>
                        </select>
                    </div>
                    <div>
                        <label for="assignment_progress" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Assignment Progress</label>
                        <select name="assignment_progress" id="assignment_progress" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="all" {{ request('assignment_progress') === 'all' || !request('assignment_progress') ? 'selected' : '' }}>All Progress</option>
                            <option value="accepted" {{ request('assignment_progress') === 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="on_the_way" {{ request('assignment_progress') === 'on_the_way' ? 'selected' : '' }}>On the way</option>
                            <option value="working" {{ request('assignment_progress') === 'working' ? 'selected' : '' }}>Working</option>
                            <option value="done" {{ request('assignment_progress') === 'done' ? 'selected' : '' }}>Task done</option>
                            <option value="submitted_proof" {{ request('assignment_progress') === 'submitted_proof' ? 'selected' : '' }}>Submitted proof</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Filter
                        </button>
                        <a href="{{ route('admin.tasks.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Clear Filters
                        </a>
                    </div>
                </form>
                
                <!-- Active Filters Display -->
                @if(request('status') && request('status') !== 'all' || request('task_type') && request('task_type') !== 'all' || request('assignment_progress') && request('assignment_progress') !== 'all')
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Active filters:</span>
                    @if(request('status') && request('status') !== 'all')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            Status: {{ ucfirst(request('status')) }}
                        </span>
                    @endif
                    @if(request('task_type') && request('task_type') !== 'all')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            Type: {{ ucfirst(str_replace('_', ' ', request('task_type'))) }}
                        </span>
                    @endif
                    @if(request('assignment_progress') && request('assignment_progress') !== 'all')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                            Progress: {{ ucfirst(str_replace('_', ' ', request('assignment_progress'))) }}
                        </span>
                    @endif
                </div>
                @endif
            </div>
                    
            <!-- Tasks Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Task</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Assigned To</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Points</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($tasks as $task)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">T</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $task->title }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-xs">{{ Str::limit($task->description, 50) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                        {{ ucfirst(str_replace('_', ' ', $task->task_type)) }}
                                    </span>
                                </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
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
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    @if($task->assignments->count() > 0)
                                        <div class="space-y-1">
                                            @foreach($task->assignments->take(3) as $assignment)
                                                <div class="flex items-center">
                                                    <span class="px-2 py-1 text-xs rounded-full
                                                        @if($assignment->status === 'assigned') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                        @elseif($assignment->status === 'submitted') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                        @elseif($assignment->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                        @endif">
                                                        {{ ucfirst($assignment->status) }}
                                                    </span>
                                                    @if(!empty($assignment->progress))
                                                        <span class="ml-2 px-2 py-1 text-xs rounded-full
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
                                                    <span class="ml-2">{{ $assignment->user->name }}</span>
                                                </div>
                                            @endforeach
                                            @if($task->assignments->count() > 3)
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    +{{ $task->assignments->count() - 3 }} more
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">No participants</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $task->points_awarded }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ is_string($task->creation_date) ? \Carbon\Carbon::parse($task->creation_date)->format('M j, Y') : $task->creation_date->format('M j, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.tasks.show', $task) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">View</a>
                                        @if($task->task_type !== 'user_uploaded' && $task->status !== 'completed' && $task->status !== 'published')
                                            <a href="{{ route('admin.tasks.edit', $task) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">Edit</a>
                                        @endif
                                        
                                        @if($task->status === 'pending')
                                            <form action="{{ route('admin.tasks.approve', $task) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">Approve</button>
                                            </form>
                                            <form action="{{ route('admin.tasks.reject', $task) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" 
                                                        onclick="return confirm('Are you sure you want to reject this task?')">Reject</button>
                                            </form>
                                        @elseif($task->status === 'approved')
                                            <form action="{{ route('admin.tasks.publish', $task) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">Publish</button>
                                            </form>
                                        @elseif($task->status === 'submitted')
                                            <form action="{{ route('admin.tasks.complete', $task) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">Complete</button>
                                            </form>
                                        @endif
                                        
                                        @if($task->status !== 'completed' && $task->status !== 'inactive')
                                            <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" 
                                                        onclick="return confirm('Are you sure you want to deactivate this task?')">Deactivate</button>
                                            </form>
                                        @elseif($task->status === 'inactive')
                                            <form action="{{ route('admin.tasks.reactivate', $task) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">Reactivate</button>
                                            </form>
                                        @endif
                                    </div>
                                    </td>
                                </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    No tasks found.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $tasks->links() }}
                </div>
            </div>
        </div>
    </div>

</x-admin-layout>
