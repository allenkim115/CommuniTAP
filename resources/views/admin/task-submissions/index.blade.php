<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Task Submissions') }}
            </h2>
                <p class="text-sm text-gray-600 mt-1">Review and manage task submissions</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.task-submissions.history') }}" 
                   class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg transition-colors"
                   style="background-color: #2B9D8D;"
                   onmouseover="this.style.backgroundColor='#248A7C'"
                   onmouseout="this.style.backgroundColor='#2B9D8D'">
                    <i class="fas fa-history mr-2"></i>
                    View History
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Toast Notifications -->
            <x-session-toast />
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Pending Review</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $submissions->total() }}</p>
                            </div>
                        <div class="h-12 w-12 rounded-lg flex items-center justify-center" style="background-color: rgba(254, 210, 179, 0.2);">
                            <i class="fas fa-clock text-xl" style="color: #FED2B3;"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Completed Today</p>
                            <p class="text-2xl font-bold text-gray-900">0</p>
                            </div>
                        <div class="h-12 w-12 rounded-lg flex items-center justify-center" style="background-color: rgba(43, 157, 141, 0.2);">
                            <i class="fas fa-check-circle text-xl" style="color: #2B9D8D;"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Total Completed</p>
                            <p class="text-2xl font-bold text-gray-900">0</p>
                            </div>
                        <div class="h-12 w-12 rounded-lg flex items-center justify-center" style="background-color: rgba(43, 157, 141, 0.2);">
                            <i class="fas fa-chart-line text-xl" style="color: #2B9D8D;"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Rejected Today</p>
                            <p class="text-2xl font-bold text-gray-900">0</p>
                            </div>
                        <div class="h-12 w-12 rounded-lg flex items-center justify-center" style="background-color: rgba(43, 157, 141, 0.2);">
                            <i class="fas fa-times-circle text-xl" style="color: #2B9D8D;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 mb-6">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1 flex items-center gap-2">
                        <i class="fas fa-filter" style="color: #2B9D8D;"></i>
                        Filter Submissions
                    </h3>
                    <p class="text-sm text-gray-600">Refine your submission list by task type or search</p>
                </div>
                <form action="{{ route('admin.task-submissions.index') }}" method="GET" id="filterForm" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Search Input -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-search" style="color: #2B9D8D;"></i> Search
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       name="search" 
                                       id="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Search by task title, user name, or email..."
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                @if(request('search'))
                                    <button type="button" 
                                            onclick="document.getElementById('search').value=''; document.getElementById('filterForm').submit();"
                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Task Type Filter -->
                        <div>
                            <label for="task_type" class="block text-sm font-medium text-gray-700 mb-2">Task Type</label>
                            <select name="task_type" id="task_type" onchange="document.getElementById('filterForm').submit();" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="all" {{ request('task_type') === 'all' || !request('task_type') ? 'selected' : '' }}>All Types</option>
                                <option value="daily" {{ request('task_type') === 'daily' ? 'selected' : '' }}>Daily Task</option>
                                <option value="one_time" {{ request('task_type') === 'one_time' ? 'selected' : '' }}>One-Time Task</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex justify-end mt-4">
                        <a href="{{ route('admin.task-submissions.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
                            <i class="fas fa-times"></i>
                            Clear Filters
                        </a>
                    </div>
                </form>
                
                <!-- Active Filters Display -->
                @if(request('search') || (request('task_type') && request('task_type') !== 'all'))
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-sm font-medium text-gray-700">Active Filters:</span>
                        @if(request('search'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                <i class="fas fa-search mr-1"></i>
                                Search: "{{ request('search') }}"
                            </span>
                        @endif
                        @if(request('task_type') && request('task_type') !== 'all')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium" style="background-color: rgba(43, 157, 141, 0.2); color: #2B9D8D;">
                                Type: {{ ucfirst(str_replace('_', ' ', request('task_type'))) }}
                            </span>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Submissions Table -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Pending Submissions</h3>
                </div>
                    
                    @if($submissions->count() > 0)
                        <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                    <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Task</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Submitted</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Photos</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Points</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider"></th>
                                    </tr>
                                </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($submissions as $submission)
                                    <tr onclick="window.location='{{ route('admin.task-submissions.show', $submission) }}'" class="transition-colors cursor-pointer"
                                        onmouseover="this.style.backgroundColor='rgba(43, 157, 141, 0.1)';"
                                        onmouseout="this.style.backgroundColor='';">
                                        <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                <x-user-avatar
                                                    :user="$submission->user"
                                                    size="h-10 w-10"
                                                    text-size="text-sm"
                                                    class="flex-shrink-0 text-white font-semibold"
                                                    style="background-color: #2B9D8D;"
                                                />
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900">{{ $submission->user->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ Str::limit($submission->user->email, 25) }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ Str::limit($submission->task->title, 40) }}</div>
                                            <div class="text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $submission->task->task_type)) }}</div>
                                            </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ is_string($submission->submitted_at) ? \Carbon\Carbon::parse($submission->submitted_at)->format('M j, Y g:i A') : $submission->submitted_at->format('M j, Y g:i A') }}
                                            </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col gap-1">
                                                    @php
                                                        $photoBg = 'rgba(229, 231, 235, 0.5)';
                                                        $photoColor = '#6B7280';
                                                        if($submission->photos && count($submission->photos) > 0) {
                                                            $photoBg = 'rgba(43, 157, 141, 0.2)';
                                                            $photoColor = '#2B9D8D';
                                                        }
                                                    @endphp
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: {{ $photoBg }}; color: {{ $photoColor }};"
                                                        @if($submission->photos && count($submission->photos) > 0) 
                                                        @else 
                                                        @endif>
                                                    <i class="fas fa-images mr-1"></i>
                                                    {{ $submission->photos ? count($submission->photos) : 0 }}
                                                    </span>
                                                    @if($submission->rejection_count > 0)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs text-red-600 bg-red-50">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    Rejected {{ $submission->rejection_count }}/3
                                                </span>
                                                    @endif
                                                </div>
                                            </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-sm font-semibold" style="background-color: rgba(43, 157, 141, 0.2); color: #2B9D8D;">
                                                <i class="fas fa-star mr-1 text-xs"></i>
                                                {{ $submission->task->points_awarded }}
                                            </span>
                                            </td>
                                        <td class="px-6 py-4 text-right">
                                            <i class="fas fa-chevron-right text-gray-400"></i>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200">
                            {{ $submissions->appends(request()->query())->links() }}
                        </div>
                    @else
                    <div class="text-center py-16">
                        <div class="mx-auto h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">No pending submissions</h3>
                        <p class="text-sm text-gray-500">All task submissions have been reviewed.</p>
                        </div>
                    @endif
            </div>
        </div>
    </div>
</x-admin-layout>
