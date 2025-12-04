<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Submission History') }}
            </h2>
                <p class="text-sm text-gray-600 mt-1">View completed and rejected submissions</p>
            </div>
            
            <a href="{{ route('admin.task-submissions.index') }}" 
               class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 sm:py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-semibold rounded-lg transition-colors min-h-[44px]"
               style="color: white !important;">
                <i class="fas fa-arrow-left mr-2 text-base" style="color: white !important;"></i>
                Back to Pending
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Toast Notifications -->
            <x-session-toast />
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mb-4 sm:mb-6">
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 sm:p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Completed</p>
                            <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $stats['total_completed'] }}</p>
                            </div>
                        <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(43, 157, 141, 0.2);">
                            <i class="fas fa-check-circle text-lg sm:text-xl" style="color: #2B9D8D;"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 sm:p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Rejected</p>
                            <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $stats['total_rejected'] }}</p>
                            </div>
                        <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(43, 157, 141, 0.2);">
                            <i class="fas fa-times-circle text-lg sm:text-xl" style="color: #2B9D8D;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-6">
                <div class="border-b border-gray-200">
                    <nav class="flex" aria-label="Tabs">
                        <a href="{{ route('admin.task-submissions.history', ['type' => 'completed']) }}" 
                           class="@if($type === 'completed') @else border-transparent text-gray-600 hover:text-gray-800 hover:border-gray-300 @endif flex-1 text-center py-3 sm:py-4 px-2 sm:px-4 border-b-2 font-medium text-xs sm:text-sm transition-colors min-h-[44px] sm:min-h-auto flex items-center justify-center"
                           @if($type === 'completed') style="border-color: #2B9D8D; color: #2B9D8D; background-color: rgba(43, 157, 141, 0.1);" @endif>
                            <div class="flex items-center justify-center">
                                <i class="fas fa-check-circle mr-1 sm:mr-2 text-xs sm:text-sm"></i>
                                <span class="hidden sm:inline">Completed</span>
                                <span class="sm:hidden">Done</span>
                                <span class="ml-1 sm:ml-2">({{ $stats['total_completed'] }})</span>
                            </div>
                        </a>
                        <a href="{{ route('admin.task-submissions.history', ['type' => 'rejected']) }}" 
                           class="@if($type === 'rejected') @else border-transparent text-gray-600 hover:text-gray-800 hover:border-gray-300 @endif flex-1 text-center py-3 sm:py-4 px-2 sm:px-4 border-b-2 font-medium text-xs sm:text-sm transition-colors min-h-[44px] sm:min-h-auto flex items-center justify-center"
                           @if($type === 'rejected') style="border-color: #2B9D8D; color: #2B9D8D; background-color: rgba(43, 157, 141, 0.1);" @endif>
                            <div class="flex items-center justify-center">
                                <i class="fas fa-times-circle mr-1 sm:mr-2 text-xs sm:text-sm"></i>
                                <span class="hidden sm:inline">Rejected</span>
                                <span class="sm:hidden">Rej</span>
                                <span class="ml-1 sm:ml-2">({{ $stats['total_rejected'] }})</span>
                            </div>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-3 sm:p-6 mb-6" x-data="{ filtersOpen: window.innerWidth >= 640 || {{ (request('search') || (request('task_type') && request('task_type') !== 'all')) ? 'true' : 'false' }} }">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div class="flex-1">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-0.5 sm:mb-1 flex items-center gap-2">
                            <i class="fas fa-filter text-sm sm:text-base" style="color: #2B9D8D;"></i>
                            Filter Submissions
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-600 hidden sm:block">Refine your submission list by task type or search</p>
                    </div>
                    @php
                        $activeFilterCount = 0;
                        if(request('search')) $activeFilterCount++;
                        if(request('task_type') && request('task_type') !== 'all') $activeFilterCount++;
                    @endphp
                    @if($activeFilterCount > 0)
                        <span class="ml-2 px-2 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-700">{{ $activeFilterCount }}</span>
                    @endif
                    <button type="button" 
                            @click="filtersOpen = !filtersOpen" 
                            class="sm:hidden ml-3 p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors"
                            aria-label="Toggle filters">
                        <i class="fas" :class="filtersOpen ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                </div>
                
                <form action="{{ route('admin.task-submissions.history') }}" method="GET" id="filterForm" x-show="filtersOpen" x-cloak class="space-y-3 sm:space-y-4" novalidate>
                    <input type="hidden" name="type" value="{{ $type }}">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-4">
                        <!-- Search Input -->
                        <div>
                            <label for="search" class="hidden sm:block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-search" style="color: #2B9D8D;"></i> Search
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       name="search" 
                                       id="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Search by task title, user name, or email..."
                                       class="w-full pl-10 pr-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base min-h-[40px] sm:min-h-[44px]">
                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                                @if(request('search'))
                                    <button type="button" 
                                            onclick="document.getElementById('search').value=''; document.getElementById('filterForm').submit();"
                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 p-1">
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Task Type Filter -->
                        <div>
                            <label for="task_type" class="hidden sm:block text-sm font-medium text-gray-700 mb-2">Task Type</label>
                            <select name="task_type" id="task_type" onchange="document.getElementById('filterForm').submit();" class="w-full px-3 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base min-h-[40px] sm:min-h-[44px]">
                                <option value="all" {{ request('task_type') === 'all' || !request('task_type') ? 'selected' : '' }}>All Types</option>
                                <option value="daily" {{ request('task_type') === 'daily' ? 'selected' : '' }}>Daily Task</option>
                                <option value="one_time" {{ request('task_type') === 'one_time' ? 'selected' : '' }}>One-Time Task</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex justify-end mt-3 sm:mt-4">
                        <a href="{{ route('admin.task-submissions.history', ['type' => $type]) }}" class="inline-flex items-center gap-2 px-4 py-2 sm:px-5 sm:py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors text-sm sm:text-base min-h-[36px] sm:min-h-[44px]">
                            <i class="fas fa-times text-xs sm:text-sm"></i>
                            Clear Filters
                        </a>
                    </div>
                </form>
                
                <!-- Active Filters Display -->
                @if(request('search') || (request('task_type') && request('task_type') !== 'all'))
                <div class="mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-gray-200" x-show="filtersOpen" x-cloak>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs sm:text-sm font-medium text-gray-700">Active Filters:</span>
                        @if(request('search'))
                            <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                <i class="fas fa-search mr-1 text-xs"></i>
                                <span class="hidden sm:inline">Search: </span>"{{ Str::limit(request('search'), 15) }}"
                            </span>
                        @endif
                        @if(request('task_type') && request('task_type') !== 'all')
                            <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-medium" style="background-color: rgba(43, 157, 141, 0.2); color: #2B9D8D;">
                                <span class="hidden sm:inline">Type: </span>{{ ucfirst(str_replace('_', ' ', request('task_type'))) }}
                            </span>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Submissions Table -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">
                        @if($type === 'completed')
                            Completed Submissions
                        @else
                            Rejected Submissions
                        @endif
                    </h3>
                </div>
                    
                    @if($submissions->count() > 0)
                        <!-- Desktop Table View -->
                        <div class="hidden sm:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                    <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Task</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            @if($type === 'completed')
                                                Completed
                                            @else
                                                Rejected
                                            @endif
                                        </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Photos</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Points</th>
                                        @if($type === 'rejected')
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Rejections</th>
                                        @endif
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider"></th>
                                    </tr>
                                </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($submissions as $submission)
                                    <tr onclick="window.location='{{ route('admin.task-submissions.show', $submission) }}'" class="hover:bg-blue-50 transition-colors cursor-pointer">
                                        <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                <x-user-avatar
                                                    :user="$submission->user"
                                                    size="h-10 w-10"
                                                    text-size="text-sm"
                                                    class="flex-shrink-0 text-white font-semibold"
                                                    style="background-image: linear-gradient(to bottom right, {{ $type === 'completed' ? '#22c55e' : '#ef4444' }}, {{ $type === 'completed' ? '#16a34a' : '#dc2626' }}); color: #fff;"
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
                                                @if($type === 'completed')
                                                    @if($submission->completed_at)
                                                        {{ is_string($submission->completed_at) ? \Carbon\Carbon::parse($submission->completed_at)->format('M j, Y g:i A') : $submission->completed_at->format('M j, Y g:i A') }}
                                                    @else
                                                    <span class="text-gray-400">N/A</span>
                                                    @endif
                                                @else
                                                    @if($submission->updated_at)
                                                        {{ is_string($submission->updated_at) ? \Carbon\Carbon::parse($submission->updated_at)->format('M j, Y g:i A') : $submission->updated_at->format('M j, Y g:i A') }}
                                                    @else
                                                    <span class="text-gray-400">N/A</span>
                                                    @endif
                                                @endif
                                            </td>
                                        <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($submission->photos && count($submission->photos) > 0) bg-green-100 text-green-800
                                                @else bg-gray-100 text-gray-600
                                                    @endif">
                                                <i class="fas fa-images mr-1"></i>
                                                {{ $submission->photos ? count($submission->photos) : 0 }}
                                                </span>
                                            </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-sm font-semibold bg-blue-50 text-blue-700">
                                                <i class="fas fa-star mr-1 text-xs"></i>
                                                {{ $submission->task->points_awarded }}
                                            </span>
                                            </td>
                                            @if($type === 'rejected')
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                        {{ $submission->rejection_count ?? 0 }}/3
                                                    </span>
                                                </td>
                                            @endif
                                        <td class="px-6 py-4 text-right">
                                            <i class="fas fa-chevron-right text-gray-400"></i>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Mobile Card View -->
                        <div class="sm:hidden space-y-3 p-4">
                            @foreach($submissions as $submission)
                                <div onclick="window.location='{{ route('admin.task-submissions.show', $submission) }}'" class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-center flex-1 min-w-0">
                                            <x-user-avatar
                                                :user="$submission->user"
                                                size="h-10 w-10"
                                                text-size="text-sm"
                                                class="flex-shrink-0 text-white font-semibold"
                                                style="background-image: linear-gradient(to bottom right, {{ $type === 'completed' ? '#22c55e' : '#ef4444' }}, {{ $type === 'completed' ? '#16a34a' : '#dc2626' }}); color: #fff;"
                                            />
                                            <div class="ml-3 min-w-0 flex-1">
                                                <div class="text-sm font-semibold text-gray-900 truncate">{{ $submission->user->name }}</div>
                                                <div class="text-xs text-gray-500 truncate">{{ $submission->user->email }}</div>
                                            </div>
                                        </div>
                                        <i class="fas fa-chevron-right text-gray-400 flex-shrink-0 ml-2"></i>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <div>
                                            <div class="text-xs font-medium text-gray-500 mb-0.5">Task</div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $submission->task->title }}</div>
                                            <div class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $submission->task->task_type)) }}</div>
                                        </div>
                                        
                                        <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                            <div class="flex flex-wrap gap-2">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                    @if($submission->photos && count($submission->photos) > 0) bg-green-100 text-green-800
                                                    @else bg-gray-100 text-gray-600
                                                    @endif">
                                                    <i class="fas fa-images mr-1 text-xs"></i>
                                                    {{ $submission->photos ? count($submission->photos) : 0 }}
                                                </span>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700">
                                                    <i class="fas fa-star mr-1 text-xs"></i>
                                                    {{ $submission->task->points_awarded }}
                                                </span>
                                                @if($type === 'rejected')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-exclamation-triangle mr-1 text-xs"></i>
                                                    {{ $submission->rejection_count ?? 0 }}/3
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="text-xs text-gray-500 pt-1">
                                            @if($type === 'completed')
                                                @if($submission->completed_at)
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    {{ is_string($submission->completed_at) ? \Carbon\Carbon::parse($submission->completed_at)->format('M j, Y g:i A') : $submission->completed_at->format('M j, Y g:i A') }}
                                                @else
                                                    <span class="text-gray-400">N/A</span>
                                                @endif
                                            @else
                                                @if($submission->updated_at)
                                                    <i class="fas fa-times-circle mr-1"></i>
                                                    {{ is_string($submission->updated_at) ? \Carbon\Carbon::parse($submission->updated_at)->format('M j, Y g:i A') : $submission->updated_at->format('M j, Y g:i A') }}
                                                @else
                                                    <span class="text-gray-400">N/A</span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200">
                            {{ $submissions->appends(request()->query())->links() }}
                        </div>
                    @else
                    <div class="text-center py-16">
                        <div class="mx-auto h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas @if($type === 'completed') fa-check-circle @else fa-times-circle @endif text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">
                                @if($type === 'completed')
                                    No completed submissions
                                @else
                                    No rejected submissions
                                @endif
                            </h3>
                        <p class="text-sm text-gray-500">
                                @if($type === 'completed')
                                    There are no completed submissions yet.
                                @else
                                    There are no rejected submissions yet.
                                @endif
                            </p>
                        </div>
                    @endif
            </div>
        </div>
    </div>
</x-admin-layout>

