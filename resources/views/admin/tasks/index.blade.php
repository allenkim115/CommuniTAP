<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                {{ __('Task Management') }}
            </h2>
            
            <a href="{{ route('admin.tasks.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-white font-semibold py-3 px-6 rounded-lg transition-colors text-base min-h-[44px]"
               style="background-color: #F3A261;"
               onmouseover="this.style.backgroundColor='#E8944F'"
               onmouseout="this.style.backgroundColor='#F3A261'">
                <i class="fas fa-plus text-lg"></i>
                <span>Create Task</span>
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Toast Notifications -->
            <x-session-toast />

            <!-- Task Statistics -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2.5 sm:gap-3 lg:gap-4 mb-4 sm:mb-6">
                <a href="{{ route('admin.tasks.index') }}" class="bg-white rounded-lg border-2 border-gray-200 p-3 sm:p-4 lg:p-5 hover:shadow-md transition-all min-h-[90px] sm:min-h-[100px] lg:min-h-[120px] flex flex-col justify-between {{ !request('status') || request('status') === 'all' ? '' : '' }}"
                   @if(!request('status') || request('status') === 'all') style="border-color: #2B9D8D; background-color: rgba(43, 157, 141, 0.1);" @endif
                   @if(!request('status') || request('status') === 'all') onmouseover="this.style.borderColor='#248A7C';" onmouseout="this.style.borderColor='#2B9D8D';" @endif>
                    <div class="flex items-center gap-1.5 sm:gap-2 mb-1.5 sm:mb-2">
                        <i class="fas fa-tasks text-sm sm:text-base lg:text-lg" style="color: #2B9D8D;"></i>
                        <p class="text-[10px] sm:text-xs lg:text-sm font-semibold text-gray-600 leading-tight">Total Tasks</p>
                    </div>
                    <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">{{ $taskStats['total'] }}</p>
                </a>

                <a href="{{ route('admin.tasks.filter', ['status' => 'pending']) }}" class="bg-white rounded-lg border-2 border-gray-200 p-3 sm:p-4 lg:p-5 hover:shadow-md transition-all min-h-[90px] sm:min-h-[100px] lg:min-h-[120px] flex flex-col justify-between {{ request('status') === 'pending' ? '' : '' }}"
                   @if(request('status') === 'pending') style="border-color: #FED2B3; background-color: rgba(254, 210, 179, 0.1);" @endif
                   @if(request('status') === 'pending') onmouseover="this.style.borderColor='#E8C19F';" onmouseout="this.style.borderColor='#FED2B3';" @endif>
                    <div class="flex items-center gap-1.5 sm:gap-2 mb-1.5 sm:mb-2">
                        <i class="fas fa-clock text-sm sm:text-base" style="color: #FED2B3;"></i>
                        <p class="text-[10px] sm:text-xs font-medium text-gray-600 leading-tight">Pending</p>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $taskStats['pending'] }}</p>
                </a>

                <a href="{{ route('admin.tasks.filter', ['status' => 'completed']) }}" class="bg-white rounded-lg border-2 border-gray-200 p-3 sm:p-4 lg:p-5 hover:shadow-md transition-all min-h-[90px] sm:min-h-[100px] lg:min-h-[120px] flex flex-col justify-between {{ request('status') === 'completed' ? '' : '' }}"
                   @if(request('status') === 'completed') style="border-color: #2B9D8D; background-color: rgba(43, 157, 141, 0.1);" @endif
                   @if(request('status') === 'completed') onmouseover="this.style.borderColor='#248A7C';" onmouseout="this.style.borderColor='#2B9D8D';" @endif>
                    <div class="flex items-center gap-1.5 sm:gap-2 mb-1.5 sm:mb-2">
                        <i class="fas fa-check-circle text-sm sm:text-base" style="color: #2B9D8D;"></i>
                        <p class="text-[10px] sm:text-xs font-medium text-gray-600 leading-tight">Completed</p>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $taskStats['completed'] }}</p>
                </a>

                <a href="{{ route('admin.tasks.filter', ['status' => 'published']) }}" class="bg-white rounded-lg border-2 border-gray-200 p-3 sm:p-4 lg:p-5 hover:shadow-md transition-all min-h-[90px] sm:min-h-[100px] lg:min-h-[120px] flex flex-col justify-between {{ request('status') === 'published' ? '' : '' }}"
                   @if(request('status') === 'published') style="border-color: #2B9D8D; background-color: rgba(43, 157, 141, 0.1);" @endif
                   @if(request('status') === 'published') onmouseover="this.style.borderColor='#248A7C';" onmouseout="this.style.borderColor='#2B9D8D';" @endif>
                    <div class="flex items-center gap-1.5 sm:gap-2 mb-1.5 sm:mb-2">
                        <i class="fas fa-rocket text-sm sm:text-base" style="color: #2B9D8D;"></i>
                        <p class="text-[10px] sm:text-xs font-medium text-gray-600 leading-tight">Published</p>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $taskStats['published'] }}</p>
                </a>

                <a href="{{ route('admin.tasks.filter', ['status' => 'uncompleted']) }}" class="bg-white rounded-lg border-2 border-gray-200 p-3 sm:p-4 lg:p-5 hover:border-orange-500 hover:shadow-md transition-all min-h-[90px] sm:min-h-[100px] lg:min-h-[120px] flex flex-col justify-between {{ request('status') === 'uncompleted' ? 'border-orange-500 bg-orange-50' : '' }}">
                    <div class="flex items-center gap-1.5 sm:gap-2 mb-1.5 sm:mb-2">
                        <i class="fas fa-exclamation-triangle text-sm sm:text-base lg:text-lg text-orange-600"></i>
                        <p class="text-[10px] sm:text-xs lg:text-sm font-semibold text-gray-600 leading-tight">Uncompleted</p>
                    </div>
                    <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">{{ $taskStats['uncompleted'] ?? 0 }}</p>
                </a>

                <a href="{{ route('admin.tasks.filter', ['status' => 'inactive']) }}" class="bg-white rounded-lg border-2 border-gray-200 p-3 sm:p-4 lg:p-5 hover:border-gray-400 hover:shadow-md transition-all min-h-[90px] sm:min-h-[100px] lg:min-h-[120px] flex flex-col justify-between {{ request('status') === 'inactive' ? 'border-gray-400 bg-gray-50' : '' }}">
                    <div class="flex items-center gap-1.5 sm:gap-2 mb-1.5 sm:mb-2">
                        <i class="fas fa-pause-circle text-sm sm:text-base lg:text-lg text-gray-600"></i>
                        <p class="text-[10px] sm:text-xs lg:text-sm font-semibold text-gray-600 leading-tight">Inactive</p>
                    </div>
                    <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">{{ $taskStats['inactive'] ?? 0 }}</p>
                </a>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-3 sm:p-6 mb-4">
                <div x-data="{ filtersOpen: false }" class="w-full">
                    <button type="button" @click="filtersOpen = !filtersOpen" class="w-full sm:hidden flex items-center justify-between py-2.5 px-3 bg-gray-50 rounded-lg mb-0">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-filter text-base" style="color: #2B9D8D;"></i>
                            <span class="text-sm font-semibold text-gray-900">Filters</span>
                            @if(request('search') || request('task_type') && request('task_type') !== 'all' || request('date_from') || request('date_to'))
                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-brand-teal text-white text-xs font-bold">{{ 
                                    (request('search') ? 1 : 0) + 
                                    (request('task_type') && request('task_type') !== 'all' ? 1 : 0) + 
                                    (request('date_from') ? 1 : 0) + 
                                    (request('date_to') ? 1 : 0) 
                                }}</span>
                            @endif
                        </div>
                        <i class="fas fa-chevron-down transition-transform text-sm" :class="{'rotate-180': filtersOpen}"></i>
                    </button>
                    <div class="hidden sm:block mb-3">
                        <h3 class="text-base font-semibold text-gray-900 mb-1 flex items-center gap-2">
                            <i class="fas fa-filter" style="color: #2B9D8D;"></i>
                            Filter Tasks
                        </h3>
                        <p class="text-xs text-gray-500">Refine your task list by type and progress</p>
                    </div>
                <form action="{{ route('admin.tasks.filter') }}" method="GET" id="filterForm" class="space-y-3 sm:space-y-3 mt-3 sm:mt-0" x-show="filtersOpen || window.innerWidth >= 640" x-cloak novalidate>
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    
                    <!-- Search Input -->
                    <div>
                        <label for="search" class="hidden sm:block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-search" style="color: #2B9D8D;"></i> Search Tasks
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   id="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Search tasks..."
                                   class="w-full pl-10 pr-10 sm:pl-12 sm:pr-12 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm min-h-[40px] sm:min-h-[44px]">
                            <i class="fas fa-search absolute left-3 sm:left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                            @if(request('search'))
                                <button type="button" 
                                        id="clearSearch" 
                                        class="absolute right-3 sm:right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 p-1 min-w-[28px] min-h-[28px] flex items-center justify-center">
                                    <i class="fas fa-times text-sm"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-3">
                        <div>
                            <label for="task_type" class="hidden sm:block text-sm font-semibold text-gray-700 mb-1.5">Task Type</label>
                            <select name="task_type" id="task_type" class="w-full px-3 py-2 sm:px-4 sm:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm min-h-[40px] sm:min-h-[44px] bg-white">
                                <option value="all" {{ request('task_type') === 'all' || !request('task_type') ? 'selected' : '' }}>All Types</option>
                                <option value="daily" {{ request('task_type') === 'daily' ? 'selected' : '' }}>Daily Task</option>
                                <option value="one_time" {{ request('task_type') === 'one_time' ? 'selected' : '' }}>One-Time Task</option>
                                <option value="user_uploaded" {{ request('task_type') === 'user_uploaded' ? 'selected' : '' }}>User-Uploaded Task</option>
                            </select>
                        </div>
                        <div>
                            <label for="date_from" class="hidden sm:block text-sm font-semibold text-gray-700 mb-1.5">Date From</label>
                            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 sm:px-4 sm:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm min-h-[40px] sm:min-h-[44px]">
                        </div>
                        <div>
                            <label for="date_to" class="hidden sm:block text-sm font-semibold text-gray-700 mb-1.5">Date To</label>
                            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 sm:px-4 sm:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm min-h-[40px] sm:min-h-[44px]">
                        </div>
                    </div>
                    @if(request('search') || request('task_type') && request('task_type') !== 'all' || request('date_from') || request('date_to'))
                    <div class="flex justify-end pt-2">
                        <a href="{{ route('admin.tasks.index') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium text-sm transition-colors min-h-[36px] sm:min-h-[40px]">
                            <i class="fas fa-times text-xs"></i>
                            <span class="hidden sm:inline">Clear</span>
                        </a>
                    </div>
                    @endif
                </form>
                </div>
                
                <!-- Active Filters Display -->
                <div class="mt-6 pt-6 border-t border-gray-200" id="active-filters">
                    @if(request('status') && request('status') !== 'all' || request('task_type') && request('task_type') !== 'all' || request('date_from') || request('date_to') || request('search'))
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-sm font-medium text-gray-700">Active Filters:</span>
                        @if(request('search'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium" style="background-color: rgba(254, 210, 179, 0.2); color: #FED2B3;">
                                <i class="fas fa-search mr-1"></i>
                                Search: "{{ request('search') }}"
                            </span>
                        @endif
                        @if(request('status') && request('status') !== 'all')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium" style="background-color: rgba(43, 157, 141, 0.2); color: #2B9D8D;">
                                Status: {{ ucfirst(request('status')) }}
                            </span>
                        @endif
                        @if(request('task_type') && request('task_type') !== 'all')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium" style="background-color: rgba(43, 157, 141, 0.2); color: #2B9D8D;">
                                Type: {{ ucfirst(str_replace('_', ' ', request('task_type'))) }}
                            </span>
                        @endif
                        @if(request('date_from') || request('date_to'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium" style="background-color: rgba(243, 162, 97, 0.2); color: #F3A261;">
                                @if(request('date_from') && request('date_to'))
                                    {{ \Carbon\Carbon::parse(request('date_from'))->format('M d, Y') }} - {{ \Carbon\Carbon::parse(request('date_to'))->format('M d, Y') }}
                                @elseif(request('date_from'))
                                    From {{ \Carbon\Carbon::parse(request('date_from'))->format('M d, Y') }}
                                @elseif(request('date_to'))
                                    Until {{ \Carbon\Carbon::parse(request('date_to'))->format('M d, Y') }}
                                @endif
                            </span>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
                    
            <!-- Tasks Cards -->
            <div id="tasks-table-container">
                @if($tasks->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 mb-4 sm:mb-6">
                        @foreach($tasks as $task)
                            @php
                                $searchBlob = strtolower(
                                    ($task->title ?? '') . ' ' .
                                    ($task->description ?? '') . ' ' .
                                    ($task->task_type ?? '') . ' ' .
                                    ($task->status ?? '')
                                );
                            @endphp
                        <a href="{{ route('admin.tasks.show', $task) }}"
                           data-task-card
                           data-url="{{ route('admin.tasks.show', $task) }}"
                           data-search="{{ $searchBlob }}"
                           class="group bg-white rounded-lg border-2 border-gray-200 p-5 sm:p-6 hover:border-blue-500 hover:shadow-md transition-all block min-h-[200px] sm:min-h-[180px]
                            @if($task->status === 'pending') border-l-4 border-l-yellow-500
                            @elseif($task->status === 'approved') border-l-4 border-l-green-500
                            @elseif($task->status === 'published') border-l-4 border-l-blue-500
                            @elseif($task->status === 'assigned') border-l-4 border-l-purple-500
                            @elseif($task->status === 'submitted') border-l-4 border-l-indigo-500
                            @elseif($task->status === 'completed') border-l-4 border-l-green-600
                            @elseif($task->status === 'rejected') border-l-4 border-l-red-500
                            @elseif($task->status === 'uncompleted') border-l-4 border-l-orange-500
                            @elseif($task->status === 'inactive') border-l-4 border-l-gray-400
                            @endif">
                            <!-- Card Header -->
                            <div class="mb-4">
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 line-clamp-2 transition-colors mb-2 group-hover:text-blue-600 leading-tight" style="--hover-color: #2B9D8D;">
                                    {{ $task->title }}
                                </h3>
                                <p class="text-sm sm:text-base text-gray-600 line-clamp-2 leading-relaxed">
                                    {{ Str::limit($task->description, 80) }}
                                </p>
                            </div>

                            <!-- Badges Row -->
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 sm:py-1 text-xs sm:text-sm font-semibold rounded-lg" style="background-color: rgba(43, 157, 141, 0.2); color: #2B9D8D;">
                                    @if($task->task_type === 'daily')
                                        <i class="fas fa-calendar-day"></i>
                                    @elseif($task->task_type === 'one_time')
                                        <i class="fas fa-bullseye"></i>
                                    @else
                                        <i class="fas fa-user"></i>
                                    @endif
                                    {{ ucfirst(str_replace('_', ' ', $task->task_type)) }}
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 sm:py-1 text-xs sm:text-sm font-semibold rounded-lg
                                    @if($task->status === 'pending') 
                                    @php $statusBg = 'rgba(254, 210, 179, 0.2)'; $statusColor = '#FED2B3'; @endphp
                                    @elseif($task->status === 'approved') 
                                    @php $statusBg = 'rgba(43, 157, 141, 0.2)'; $statusColor = '#2B9D8D'; @endphp
                                    @elseif($task->status === 'published') 
                                    @php $statusBg = 'rgba(43, 157, 141, 0.2)'; $statusColor = '#2B9D8D'; @endphp
                                    @elseif($task->status === 'assigned') 
                                    @php $statusBg = 'rgba(43, 157, 141, 0.2)'; $statusColor = '#2B9D8D'; @endphp
                                    @elseif($task->status === 'submitted') 
                                    @php $statusBg = 'rgba(254, 210, 179, 0.2)'; $statusColor = '#FED2B3'; @endphp
                                    @elseif($task->status === 'completed') 
                                    @php $statusBg = 'rgba(43, 157, 141, 0.2)'; $statusColor = '#2B9D8D'; @endphp
                                    @elseif($task->status === 'rejected') bg-red-100 text-red-800
                                    @elseif($task->status === 'uncompleted') bg-orange-100 text-orange-800
                                    @elseif($task->status === 'inactive') bg-gray-100 text-gray-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    @if($task->status === 'pending')
                                        <i class="fas fa-clock"></i>
                                    @elseif($task->status === 'approved')
                                        <i class="fas fa-check"></i>
                                    @elseif($task->status === 'published')
                                        <i class="fas fa-rocket"></i>
                                    @elseif($task->status === 'assigned')
                                        <i class="fas fa-user-check"></i>
                                    @elseif($task->status === 'submitted')
                                        <i class="fas fa-paper-plane"></i>
                                    @elseif($task->status === 'completed')
                                        <i class="fas fa-check-circle"></i>
                                    @elseif($task->status === 'rejected')
                                        <i class="fas fa-times-circle"></i>
                                    @elseif($task->status === 'uncompleted')
                                        <i class="fas fa-exclamation-triangle"></i>
                                    @elseif($task->status === 'inactive')
                                        <i class="fas fa-pause"></i>
                                    @else
                                        <i class="fas fa-list"></i>
                                    @endif
                                    {{ ucfirst($task->status) }}
                                </span>
                            </div>

                            <!-- Task Info -->
                            <div class="space-y-2 mb-0">
                                <div class="flex items-center gap-2.5 text-base sm:text-sm text-gray-600">
                                    <i class="fas fa-star text-yellow-500 text-lg"></i>
                                    <span class="font-bold text-gray-900 text-lg sm:text-base">{{ $task->points_awarded }}</span>
                                    <span class="font-semibold">points</span>
                                </div>
                                <div class="flex items-center gap-2.5 text-base sm:text-sm text-gray-600">
                                    <i class="fas fa-calendar text-gray-400 text-base"></i>
                                    <span class="font-medium">{{ is_string($task->creation_date) ? \Carbon\Carbon::parse($task->creation_date)->format('M j, Y') : $task->creation_date->format('M j, Y') }}</span>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-12 text-center">
                        <div class="mx-auto h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No tasks found</h3>
                        <p class="text-gray-600 mb-6">Try adjusting your filters to see more tasks.</p>
                        <a href="{{ route('admin.tasks.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-redo"></i>
                            Clear All Filters
                        </a>
                    </div>
                @endif

                <div id="tasks-search-empty" class="hidden bg-white rounded-lg border border-dashed border-gray-200 shadow-sm p-12 text-center">
                    <div class="mx-auto h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-search text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No tasks match your search</h3>
                    <p class="text-gray-600">Try a different keyword.</p>
                </div>
                
                <!-- Pagination -->
                <div class="mt-4 sm:mt-6 flex justify-center" id="tasks-pagination">
                    {{ $tasks->links() }}
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
    <script>
        // Update tasks via AJAX when filters change (no page refresh)
        document.addEventListener('DOMContentLoaded', function() {
            const taskTypeSelect = document.getElementById('task_type');
            const dateFromInput = document.getElementById('date_from');
            const dateToInput = document.getElementById('date_to');
            const searchInput = document.getElementById('search');
            const clearSearchBtn = document.getElementById('clearSearch');
            const filterForm = document.getElementById('filterForm');
            let searchTimeout;

            function updateTasks() {
                // Show loading state
                const tableContainer = document.getElementById('tasks-table-container');
                if (tableContainer) {
                    tableContainer.innerHTML = '<div class="p-12 text-center"><div class="inline-block animate-spin rounded-full h-8 w-8 border-2 border-blue-600 border-t-transparent"></div><p class="mt-4 text-gray-600 text-sm">Loading tasks...</p></div>';
                }

                // Get form data
                const formData = new FormData(filterForm);
                const params = new URLSearchParams(formData);

                // Make AJAX request
                fetch(`{{ route('admin.tasks.filter') }}?${params.toString()}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html',
                    }
                })
                .then(response => response.text())
                .then(html => {
                    // Parse the HTML response
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    // Extract the table container content
                    const newTableContainer = doc.getElementById('tasks-table-container');
                    const newActiveFilters = doc.getElementById('active-filters');
                    
                    // Update the table
                    if (newTableContainer && tableContainer) {
                        tableContainer.innerHTML = newTableContainer.innerHTML;
                    }
                    
                    // Update active filters
                    const currentActiveFilters = document.getElementById('active-filters');
                    if (newActiveFilters && currentActiveFilters) {
                        currentActiveFilters.innerHTML = newActiveFilters.innerHTML;
                    }
                    
                    // Update URL without page reload
                    const newUrl = `{{ route('admin.tasks.filter') }}?${params.toString()}`;
                    window.history.pushState({path: newUrl}, '', newUrl);
                })
                .catch(error => {
                    console.error('Error updating tasks:', error);
                    if (tableContainer) {
                        tableContainer.innerHTML = '<div class="p-12 text-center"><p class="text-red-600 text-sm font-medium">Error loading tasks. Please refresh the page.</p></div>';
                    }
                });
            }

            // Function to toggle clear button visibility
            function toggleClearButton() {
                const clearBtn = document.getElementById('clearSearch');
                if (searchInput && searchInput.value) {
                    if (!clearBtn) {
                        // Create clear button if it doesn't exist
                        const searchContainer = searchInput.parentElement;
                        const clearButton = document.createElement('button');
                        clearButton.type = 'button';
                        clearButton.id = 'clearSearch';
                        clearButton.className = 'absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600';
                        clearButton.innerHTML = '<i class="fas fa-times"></i>';
                        clearButton.addEventListener('click', function() {
                            searchInput.value = '';
                            toggleClearButton();
                            updateTasks();
                        });
                        searchContainer.appendChild(clearButton);
                    }
                } else {
                    if (clearBtn) {
                        clearBtn.remove();
                    }
                }
            }

            // Search input with debouncing
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    toggleClearButton();
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(function() {
                        updateTasks();
                    }, 500); // Wait 500ms after user stops typing
                });
            }

            // Clear search button
            if (clearSearchBtn) {
                clearSearchBtn.addEventListener('click', function() {
                    searchInput.value = '';
                    toggleClearButton();
                    updateTasks();
                });
            }

            // Initialize clear button visibility
            toggleClearButton();

            if (taskTypeSelect) {
                taskTypeSelect.addEventListener('change', function() {
                    updateTasks();
                });
            }

            if (dateFromInput) {
                dateFromInput.addEventListener('change', function() {
                    // Validate date range
                    if (dateToInput && dateToInput.value && dateFromInput.value > dateToInput.value) {
                        alert('Date From cannot be later than Date To');
                        dateFromInput.value = '';
                        return;
                    }
                    updateTasks();
                });
            }

            if (dateToInput) {
                dateToInput.addEventListener('change', function() {
                    // Validate date range
                    if (dateFromInput && dateFromInput.value && dateToInput.value < dateFromInput.value) {
                        alert('Date To cannot be earlier than Date From');
                        dateToInput.value = '';
                        return;
                    }
                    updateTasks();
                });
            }
        });
    </script>

    @push('scripts')
    <script>
        (() => {
            const searchInput = document.getElementById('search');
            const cards = Array.from(document.querySelectorAll('[data-task-card]'));
            const emptyState = document.getElementById('tasks-search-empty');

            if (!searchInput || cards.length === 0) {
                console.info('Task search: missing input or cards', { hasInput: !!searchInput, cards: cards.length });
                return;
            }

            const applyFilter = () => {
                const query = (searchInput.value || '').trim().toLowerCase();
                let visibleCount = 0;

                cards.forEach(card => {
                    const haystack = (card.dataset.search || '').toLowerCase();
                    const matches = !query || haystack.includes(query);
                    card.classList.toggle('hidden', !matches);
                    if (matches) visibleCount++;
                });

                if (emptyState) {
                    emptyState.classList.toggle('hidden', visibleCount !== 0);
                }
            };

            const navigateToFirstVisible = () => {
                const firstVisible = cards.find(card => !card.classList.contains('hidden') && card.dataset.url);
                if (firstVisible && firstVisible.dataset.url) {
                    window.location = firstVisible.dataset.url;
                }
            };

            searchInput.addEventListener('input', applyFilter);
            searchInput.addEventListener('keyup', applyFilter);
            searchInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    navigateToFirstVisible();
                }
            });

            applyFilter();
        })();
    </script>
    @endpush

</x-admin-layout>
