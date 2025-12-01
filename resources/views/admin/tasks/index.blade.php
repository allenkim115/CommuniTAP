<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Task Management') }}
            </h2>
            
            <a href="{{ route('admin.tasks.create') }}" class="inline-flex items-center gap-2 text-white font-medium py-2 px-4 rounded-lg transition-colors"
               style="background-color: #F3A261;"
               onmouseover="this.style.backgroundColor='#E8944F'"
               onmouseout="this.style.backgroundColor='#F3A261'">
                <i class="fas fa-plus"></i>
                Create Task
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Toast Notifications -->
            <x-session-toast />

            <!-- Task Statistics -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
                <a href="{{ route('admin.tasks.index') }}" class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-all {{ !request('status') || request('status') === 'all' ? '' : '' }}"
                   @if(!request('status') || request('status') === 'all') style="border-color: #2B9D8D; background-color: rgba(43, 157, 141, 0.1);" @endif
                   @if(!request('status') || request('status') === 'all') onmouseover="this.style.borderColor='#248A7C';" onmouseout="this.style.borderColor='#2B9D8D';" @endif>
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-tasks" style="color: #2B9D8D;"></i>
                        <p class="text-xs font-medium text-gray-600">Total Tasks</p>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $taskStats['total'] }}</p>
                </a>

                <a href="{{ route('admin.tasks.filter', ['status' => 'pending']) }}" class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-all {{ request('status') === 'pending' ? '' : '' }}"
                   @if(request('status') === 'pending') style="border-color: #FED2B3; background-color: rgba(254, 210, 179, 0.1);" @endif
                   @if(request('status') === 'pending') onmouseover="this.style.borderColor='#E8C19F';" onmouseout="this.style.borderColor='#FED2B3';" @endif>
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-clock" style="color: #FED2B3;"></i>
                        <p class="text-xs font-medium text-gray-600">Pending</p>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $taskStats['pending'] }}</p>
                </a>

                <a href="{{ route('admin.tasks.filter', ['status' => 'completed']) }}" class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-all {{ request('status') === 'completed' ? '' : '' }}"
                   @if(request('status') === 'completed') style="border-color: #2B9D8D; background-color: rgba(43, 157, 141, 0.1);" @endif
                   @if(request('status') === 'completed') onmouseover="this.style.borderColor='#248A7C';" onmouseout="this.style.borderColor='#2B9D8D';" @endif>
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-check-circle" style="color: #2B9D8D;"></i>
                        <p class="text-xs font-medium text-gray-600">Completed</p>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $taskStats['completed'] }}</p>
                </a>

                <a href="{{ route('admin.tasks.filter', ['status' => 'published']) }}" class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-all {{ request('status') === 'published' ? '' : '' }}"
                   @if(request('status') === 'published') style="border-color: #2B9D8D; background-color: rgba(43, 157, 141, 0.1);" @endif
                   @if(request('status') === 'published') onmouseover="this.style.borderColor='#248A7C';" onmouseout="this.style.borderColor='#2B9D8D';" @endif>
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-rocket" style="color: #2B9D8D;"></i>
                        <p class="text-xs font-medium text-gray-600">Published</p>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $taskStats['published'] }}</p>
                </a>

                <a href="{{ route('admin.tasks.filter', ['status' => 'uncompleted']) }}" class="bg-white rounded-lg border border-gray-200 p-4 hover:border-orange-500 hover:shadow-md transition-all {{ request('status') === 'uncompleted' ? 'border-orange-500 bg-orange-50' : '' }}">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-exclamation-triangle text-orange-600"></i>
                        <p class="text-xs font-medium text-gray-600">Uncompleted</p>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $taskStats['uncompleted'] ?? 0 }}</p>
                </a>

                <a href="{{ route('admin.tasks.filter', ['status' => 'inactive']) }}" class="bg-white rounded-lg border border-gray-200 p-4 hover:border-gray-400 hover:shadow-md transition-all {{ request('status') === 'inactive' ? 'border-gray-400 bg-gray-50' : '' }}">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-pause-circle text-gray-600"></i>
                        <p class="text-xs font-medium text-gray-600">Inactive</p>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $taskStats['inactive'] ?? 0 }}</p>
                </a>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 mb-4">
                <div class="mb-3">
                    <h3 class="text-base font-semibold text-gray-900 mb-1 flex items-center gap-2">
                        <i class="fas fa-filter" style="color: #2B9D8D;"></i>
                        Filter Tasks
                    </h3>
                    <p class="text-xs text-gray-500">Refine your task list by type and progress</p>
                </div>
                <form action="{{ route('admin.tasks.filter') }}" method="GET" id="filterForm" class="space-y-3" novalidate>
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    
                    <!-- Search Input -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-search" style="color: #2B9D8D;"></i> Search Tasks
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   id="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Search by task title..."
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            @if(request('search'))
                                <button type="button" 
                                        id="clearSearch" 
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div>
                            <label for="task_type" class="block text-sm font-medium text-gray-700 mb-2">Task Type</label>
                            <select name="task_type" id="task_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="all" {{ request('task_type') === 'all' || !request('task_type') ? 'selected' : '' }}>All Types</option>
                                <option value="daily" {{ request('task_type') === 'daily' ? 'selected' : '' }}>Daily Task</option>
                                <option value="one_time" {{ request('task_type') === 'one_time' ? 'selected' : '' }}>One-Time Task</option>
                                <option value="user_uploaded" {{ request('task_type') === 'user_uploaded' ? 'selected' : '' }}>User-Uploaded Task</option>
                            </select>
                        </div>
                        <div>
                            <label for="assignment_progress" class="block text-sm font-medium text-gray-700 mb-2">Assignment Progress</label>
                            <select name="assignment_progress" id="assignment_progress" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="all" {{ request('assignment_progress') === 'all' || !request('assignment_progress') ? 'selected' : '' }}>All Progress</option>
                                <option value="accepted" {{ request('assignment_progress') === 'accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="on_the_way" {{ request('assignment_progress') === 'on_the_way' ? 'selected' : '' }}>On the way</option>
                                <option value="working" {{ request('assignment_progress') === 'working' ? 'selected' : '' }}>Working</option>
                                <option value="done" {{ request('assignment_progress') === 'done' ? 'selected' : '' }}>Task done</option>
                                <option value="submitted_proof" {{ request('assignment_progress') === 'submitted_proof' ? 'selected' : '' }}>Submitted proof</option>
                            </select>
                        </div>
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                    </div>
                    <div class="flex justify-end mt-3">
                        <a href="{{ route('admin.tasks.index') }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium text-sm transition-colors">
                            <i class="fas fa-times"></i>
                            Clear Filters
                        </a>
                    </div>
                </form>
                
                <!-- Active Filters Display -->
                <div class="mt-6 pt-6 border-t border-gray-200" id="active-filters">
                    @if(request('status') && request('status') !== 'all' || request('task_type') && request('task_type') !== 'all' || request('assignment_progress') && request('assignment_progress') !== 'all' || request('date_from') || request('date_to') || request('search'))
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
                        @if(request('assignment_progress') && request('assignment_progress') !== 'all')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                Progress: {{ ucfirst(str_replace('_', ' ', request('assignment_progress'))) }}
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
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                        @foreach($tasks as $task)
                        <a href="{{ route('admin.tasks.show', $task) }}" class="group bg-white rounded-lg border border-gray-200 p-5 hover:border-blue-500 hover:shadow-md transition-all block
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
                            <div class="mb-3">
                                <h3 class="text-base font-semibold text-gray-900 line-clamp-2 transition-colors mb-2 group-hover:text-blue-600" style="--hover-color: #2B9D8D;">
                                    {{ $task->title }}
                                </h3>
                                <p class="text-sm text-gray-600 line-clamp-2">
                                    {{ Str::limit($task->description, 80) }}
                                </p>
                            </div>

                            <!-- Badges Row -->
                            <div class="flex flex-wrap gap-2 mb-3">
                                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded" style="background-color: rgba(43, 157, 141, 0.2); color: #2B9D8D;">
                                    @if($task->task_type === 'daily')
                                        <i class="fas fa-calendar-day"></i>
                                    @elseif($task->task_type === 'one_time')
                                        <i class="fas fa-bullseye"></i>
                                    @else
                                        <i class="fas fa-user"></i>
                                    @endif
                                    {{ ucfirst(str_replace('_', ' ', $task->task_type)) }}
                                </span>
                                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded
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
                            <div class="space-y-1.5 mb-3">
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-star text-yellow-500"></i>
                                    <span class="font-medium text-gray-900">{{ $task->points_awarded }}</span>
                                    <span> points</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-calendar text-gray-400"></i>
                                    <span>{{ is_string($task->creation_date) ? \Carbon\Carbon::parse($task->creation_date)->format('M j, Y') : $task->creation_date->format('M j, Y') }}</span>
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
                
                <!-- Pagination -->
                <div class="mt-6 flex justify-center" id="tasks-pagination">
                    {{ $tasks->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        // Update tasks via AJAX when filters change (no page refresh)
        document.addEventListener('DOMContentLoaded', function() {
            const taskTypeSelect = document.getElementById('task_type');
            const assignmentProgressSelect = document.getElementById('assignment_progress');
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

            if (assignmentProgressSelect) {
                assignmentProgressSelect.addEventListener('change', function() {
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

</x-admin-layout>
