<x-admin-layout>
    <div class="py-4 sm:py-6 lg:py-8 bg-gradient-to-br from-gray-50 via-white to-orange-50/30 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6 lg:space-y-8">
            
            <!-- Page Header with Filters -->
            <div class="bg-gradient-to-br from-white via-blue-50/30 to-orange-50/40 rounded-lg sm:rounded-xl shadow-md border border-gray-200/60 overflow-hidden relative">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-500/5 via-transparent to-orange-500/5 pointer-events-none"></div>
                <div class="relative p-4 sm:p-6 lg:p-8">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 sm:gap-6">
                        <div class="flex items-start gap-3 sm:gap-4">
                            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg sm:rounded-xl p-2.5 sm:p-3.5 flex-shrink-0 shadow-lg shadow-blue-500/20">
                                <i class="fas fa-chart-line text-white text-lg sm:text-xl"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-gray-100 mb-1 sm:mb-1.5">Dashboard</h1>
                                <p class="text-xs sm:text-sm text-gray-600">Platform metrics and performance insights</p>
                            </div>
                        </div>
                        
                        <!-- Filters Section - Right Side -->
                        <form method="GET" action="{{ route('admin.dashboard') }}" id="filter-form" class="flex flex-col w-full sm:flex-row items-stretch sm:items-end gap-3 mt-2 lg:mt-0" novalidate>
                            <div class="w-full sm:w-auto sm:min-w-[160px] lg:min-w-[180px]">
                                <label for="period-select" class="block text-xs font-medium text-gray-700 mb-1.5">
                                    Time Period
                                </label>
                                <select id="period-select" name="period" 
                                        class="w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm px-3 py-2.5 sm:py-2 font-medium bg-white text-gray-900 hover:border-blue-400 transition-all min-h-[44px]">
                                    @foreach($periodOptions as $key => $label)
                                        <option value="{{ $key }}" @selected($selectedPeriod === $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-full sm:w-auto">
                                <label class="block text-xs font-medium text-gray-700 mb-1.5 opacity-0 pointer-events-none sm:block sm:opacity-100">Reset</label>
                                <a href="{{ route('admin.dashboard') }}" 
                                   class="w-full sm:w-auto px-4 py-2.5 bg-white hover:bg-gray-50 text-gray-700 rounded-lg font-medium text-sm transition-all duration-200 flex items-center gap-2 justify-center border border-gray-300 hover:border-gray-400 shadow-sm hover:shadow min-h-[44px]">
                                    <i class="fas fa-redo text-xs"></i>
                                    Reset
                                </a>
                            </div>
                            <input type="hidden" name="segment" value="{{ $selectedSegment }}">
                        </form>
                    </div>
                </div>
            </div>

            <!-- Key Metrics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-6">
                <!-- Total Users Card -->
                <a href="{{ route('admin.users.index') }}"
                   class="bg-gradient-to-br from-white to-blue-50/30 rounded-lg sm:rounded-xl shadow-md border border-blue-100/50 p-4 sm:p-5 lg:p-6 hover:shadow-xl hover:border-blue-200 hover:-translate-y-1 transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-400 cursor-pointer block group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 sm:w-32 h-24 sm:h-32 bg-blue-200/10 rounded-full -mr-12 sm:-mr-16 -mt-12 sm:-mt-16 blur-2xl"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg sm:rounded-xl p-2.5 sm:p-3 lg:p-3.5 shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-users text-white text-lg sm:text-xl"></i>
                            </div>
                            @php
                                $usersDeltaValue = $totalUsersDelta['value'] ?? 0;
                                $usersDeltaPercent = $totalUsersDelta['percent'];
                                $usersDeltaPositive = $usersDeltaValue >= 0;
                            @endphp
                            <div class="text-right">
                                <span class="text-xs text-gray-500 font-medium">vs Previous</span>
                                <div class="flex items-center gap-1 mt-0.5 {{ $usersDeltaPositive ? 'text-green-600' : 'text-red-600' }}">
                                    <i class="fas {{ $usersDeltaPositive ? 'fa-arrow-up' : 'fa-arrow-down' }} text-xs"></i>
                                    <span class="text-xs font-semibold">
                                        {{ $usersDeltaPositive ? '+' : '' }}{{ number_format($usersDeltaValue) }}
                                        @if(!is_null($usersDeltaPercent))
                                            ({{ $usersDeltaPositive ? '+' : '' }}{{ $usersDeltaPercent }}%)
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-1">{{ number_format($totalUsers) }}</div>
                        <p class="text-xs sm:text-sm text-gray-600 font-medium mb-3 sm:mb-4">Total Users</p>
                        <div class="text-xs sm:text-sm text-blue-600 font-semibold inline-flex items-center gap-2 group-hover:gap-3 transition-all">
                            <span>View Details</span>
                            <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </div>
                </a>

                <!-- Total Tasks Card -->
                <a href="{{ route('admin.tasks.index') }}"
                   class="bg-gradient-to-br from-white to-orange-50/30 rounded-lg sm:rounded-xl shadow-md border border-orange-100/50 p-4 sm:p-5 lg:p-6 hover:shadow-xl hover:border-orange-200 hover:-translate-y-1 transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-orange-400 cursor-pointer block group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 sm:w-32 h-24 sm:h-32 bg-orange-200/10 rounded-full -mr-12 sm:-mr-16 -mt-12 sm:-mt-16 blur-2xl"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg sm:rounded-xl p-2.5 sm:p-3 lg:p-3.5 shadow-lg shadow-orange-500/30 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-tasks text-white text-lg sm:text-xl"></i>
                            </div>
                            @php
                                $tasksDeltaValue = $totalTasksDelta['value'] ?? 0;
                                $tasksDeltaPercent = $totalTasksDelta['percent'];
                                $tasksDeltaPositive = $tasksDeltaValue >= 0;
                            @endphp
                            <div class="text-right">
                                <span class="text-xs text-gray-500 font-medium">vs Previous</span>
                                <div class="flex items-center gap-1 mt-0.5 {{ $tasksDeltaPositive ? 'text-green-600' : 'text-red-600' }}">
                                    <i class="fas {{ $tasksDeltaPositive ? 'fa-arrow-up' : 'fa-arrow-down' }} text-xs"></i>
                                    <span class="text-xs font-semibold">
                                        {{ $tasksDeltaPositive ? '+' : '' }}{{ number_format($tasksDeltaValue) }}
                                        @if(!is_null($tasksDeltaPercent))
                                            ({{ $tasksDeltaPositive ? '+' : '' }}{{ $tasksDeltaPercent }}%)
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-1">{{ number_format($totalTasks) }}</div>
                        <p class="text-xs sm:text-sm text-gray-600 font-medium mb-3 sm:mb-4">Tasks Created</p>
                        <div class="text-xs sm:text-sm text-orange-600 font-semibold inline-flex items-center gap-2 group-hover:gap-3 transition-all">
                            <span>View Details</span>
                            <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </div>
                </a>

                <!-- Total Incidents Card -->
                <a href="{{ route('admin.incident-reports.index') }}"
                   class="bg-gradient-to-br from-white to-red-50/30 rounded-lg sm:rounded-xl shadow-md border border-red-100/50 p-4 sm:p-5 lg:p-6 hover:shadow-xl hover:border-red-200 hover:-translate-y-1 transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-red-400 cursor-pointer block group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 sm:w-32 h-24 sm:h-32 bg-red-200/10 rounded-full -mr-12 sm:-mr-16 -mt-12 sm:-mt-16 blur-2xl"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg sm:rounded-xl p-2.5 sm:p-3 lg:p-3.5 shadow-lg shadow-red-500/30 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-exclamation-triangle text-white text-lg sm:text-xl"></i>
                            </div>
                            @php
                                $incidentsDeltaValue = $totalIncidentsDelta['value'] ?? 0;
                                $incidentsDeltaPercent = $totalIncidentsDelta['percent'];
                                $incidentsDeltaPositive = $incidentsDeltaValue >= 0;
                            @endphp
                            <div class="text-right">
                                <span class="text-xs text-gray-500 font-medium">vs Previous</span>
                                <div class="flex items-center gap-1 mt-0.5 {{ $incidentsDeltaPositive ? 'text-green-600' : 'text-red-600' }}">
                                    <i class="fas {{ $incidentsDeltaPositive ? 'fa-arrow-up' : 'fa-arrow-down' }} text-xs"></i>
                                    <span class="text-xs font-semibold">
                                        {{ $incidentsDeltaPositive ? '+' : '' }}{{ number_format($incidentsDeltaValue) }}
                                        @if(!is_null($incidentsDeltaPercent))
                                            ({{ $incidentsDeltaPositive ? '+' : '' }}{{ $incidentsDeltaPercent }}%)
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-1">{{ number_format($totalIncidents) }}</div>
                        <p class="text-xs sm:text-sm text-gray-600 font-medium mb-3 sm:mb-4">Incident Reports</p>
                        <div class="text-xs sm:text-sm text-red-600 font-semibold inline-flex items-center gap-2 group-hover:gap-3 transition-all">
                            <span>View Details</span>
                            <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </div>
                </a>
            </div>

            <!-- KPI Row -->
            @php
                $engagedUsers = $usersWithTasks ?? 0;
                $engagedPercent = ($totalUsers ?? 0) > 0 ? round(($engagedUsers / $totalUsers) * 100, 1) : 0;
            @endphp
            <div class="bg-white rounded-lg sm:rounded-xl shadow-md border border-gray-200 mt-4 sm:mt-6 lg:mt-8 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 divide-y sm:divide-y-0 divide-x divide-gray-200">
                    <div class="p-3 sm:p-4 lg:p-5 hover:bg-blue-50/30 transition-colors duration-200 group">
                        <div class="flex items-center gap-1.5 sm:gap-2 mb-1.5 sm:mb-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-blue-500 group-hover:scale-125 transition-transform flex-shrink-0"></div>
                            <div class="text-[9px] sm:text-[10px] lg:text-[11px] font-semibold uppercase tracking-wider text-gray-600 leading-tight">Total Users</div>
                        </div>
                        <div class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mt-1 sm:mt-1.5 lg:mt-2">{{ number_format($totalUsers ?? 0) }}</div>
                        <div class="text-[9px] sm:text-[10px] lg:text-xs text-gray-500 mt-1 leading-tight">Total registered accounts</div>
                    </div>
                    <div class="p-3 sm:p-4 lg:p-5 hover:bg-green-50/30 transition-colors duration-200 group">
                        <div class="flex items-center gap-1.5 sm:gap-2 mb-1.5 sm:mb-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-green-500 group-hover:scale-125 transition-transform flex-shrink-0"></div>
                            <div class="text-[9px] sm:text-[10px] lg:text-[11px] font-semibold uppercase tracking-wider text-gray-600 leading-tight">Task Completion Rate</div>
                        </div>
                        <div class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mt-1 sm:mt-1.5 lg:mt-2">{{ number_format($taskCompletionRate ?? 0, 1) }}%</div>
                        <div class="text-[9px] sm:text-[10px] lg:text-xs text-gray-500 mt-1 leading-tight">Based on all assignments</div>
                    </div>
                    <div class="p-3 sm:p-4 lg:p-5 hover:bg-purple-50/30 transition-colors duration-200 group col-span-2 sm:col-span-1">
                        <div class="flex items-center gap-1.5 sm:gap-2 mb-1.5 sm:mb-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-purple-500 group-hover:scale-125 transition-transform flex-shrink-0"></div>
                            <div class="text-[9px] sm:text-[10px] lg:text-[11px] font-semibold uppercase tracking-wider text-gray-600 leading-tight">User Engagement</div>
                        </div>
                        <div class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mt-1 sm:mt-1.5 lg:mt-2">{{ number_format($engagementRate ?? 0, 1) }}%</div>
                        <div class="text-[9px] sm:text-[10px] lg:text-xs text-gray-500 mt-1 leading-tight">Users with assigned tasks</div>
                    </div>
                    <div class="p-3 sm:p-4 lg:p-5 hover:bg-yellow-50/30 transition-colors duration-200 group col-span-2 sm:col-span-1">
                        <div class="flex items-center gap-1.5 sm:gap-2 mb-1.5 sm:mb-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-yellow-500 group-hover:scale-125 transition-transform flex-shrink-0"></div>
                            <div class="text-[9px] sm:text-[10px] lg:text-[11px] font-semibold uppercase tracking-wider text-gray-600 leading-tight">Total Points Awarded</div>
                        </div>
                        <div class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mt-1 sm:mt-1.5 lg:mt-2">{{ number_format($totalPointsAwarded ?? 0) }}</div>
                        @php
                            $avgPointsPerUser = ($totalUsers ?? 0) > 0 ? number_format(($totalPointsAwarded ?? 0) / max($totalUsers, 1), 1) : 0;
                        @endphp
                        <div class="text-[9px] sm:text-[10px] lg:text-xs text-gray-500 mt-1 leading-tight">From completed tasks</div>
                    </div>
                    <div class="p-3 sm:p-4 lg:p-5 hover:bg-orange-50/30 transition-colors duration-200 group col-span-2 sm:col-span-1 lg:col-span-1">
                        <div class="flex items-center gap-1.5 sm:gap-2 mb-1.5 sm:mb-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-orange-500 group-hover:scale-125 transition-transform flex-shrink-0"></div>
                            <div class="text-[9px] sm:text-[10px] lg:text-[11px] font-semibold uppercase tracking-wider text-gray-600 leading-tight">Active Volunteers</div>
                        </div>
                        <div class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mt-1 sm:mt-1.5 lg:mt-2">{{ number_format($activeVolunteers ?? 0) }}</div>
                        <div class="text-[9px] sm:text-[10px] lg:text-xs text-gray-500 mt-1 leading-tight">Completed at least 1 task</div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4 lg:gap-6">
                <!-- User Growth Chart -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 p-3 sm:p-4 lg:p-6 xl:p-8 hover:shadow-md transition-shadow">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 mb-4 sm:mb-6">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="bg-green-100 rounded-lg sm:rounded-xl p-2 sm:p-2.5 flex-shrink-0">
                                <i class="fas fa-users text-green-600 text-sm sm:text-base"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-base sm:text-lg font-bold text-gray-900">User Growth Trend</h3>
                                <p class="text-xs sm:text-sm text-gray-500 mt-0.5 sm:mt-1">New user registrations over time</p>
                            </div>
                        </div>
                        <span class="px-2.5 sm:px-3 py-1 sm:py-1.5 bg-green-100 text-green-700 rounded-full text-[10px] sm:text-xs font-semibold border border-green-200 whitespace-nowrap self-start sm:self-auto">Last 6 months</span>
                    </div>
                    <div id="userGrowthChart" style="height: 250px; min-height: 250px;" class="sm:h-[300px]"></div>
                </div>

                <!-- Task Completion Trend Chart -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 p-3 sm:p-4 lg:p-6 xl:p-8 hover:shadow-md transition-shadow">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 mb-4 sm:mb-6">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="bg-blue-100 rounded-lg sm:rounded-xl p-2 sm:p-2.5 flex-shrink-0">
                                <i class="fas fa-tasks text-blue-600 text-sm sm:text-base"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-base sm:text-lg font-bold text-gray-900">Task Completion Trend</h3>
                                <p class="text-xs sm:text-sm text-gray-500 mt-0.5 sm:mt-1">Completed tasks over time</p>
                            </div>
                        </div>
                        <span class="px-2.5 sm:px-3 py-1 sm:py-1.5 bg-blue-100 text-blue-700 rounded-full text-[10px] sm:text-xs font-semibold border border-blue-200 whitespace-nowrap self-start sm:self-auto">Last 6 months</span>
                    </div>
                    <div id="taskCompletionChart" style="height: 250px; min-height: 250px;" class="sm:h-[300px]"></div>
                </div>
            </div>

            <!-- Task Status & Engagement Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4 lg:gap-6">
                <!-- Task Status Distribution -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 p-3 sm:p-4 lg:p-6 xl:p-8 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4 sm:mb-6">
                        <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                            <div class="bg-orange-100 rounded-lg sm:rounded-xl p-2 sm:p-2.5 flex-shrink-0">
                                <i class="fas fa-chart-pie text-orange-600 text-sm sm:text-base"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-base sm:text-lg font-bold text-gray-900">Task Status Distribution</h3>
                                <p class="text-xs sm:text-sm text-gray-500 mt-0.5 sm:mt-1">Current task status breakdown</p>
                            </div>
                        </div>
                    </div>
                    <div id="taskDistributionChart" style="height: 250px; min-height: 250px;" class="sm:h-[300px]"></div>
                    <div class="flex flex-wrap gap-2 sm:gap-3 lg:gap-4 mt-4 sm:mt-6 justify-center bg-gray-50 rounded-lg p-3 sm:p-4">
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-white rounded-lg shadow-sm">
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            <span class="text-sm font-medium text-gray-700">Completed <span class="text-gray-500">({{ $tasksCompleted ?? 0 }})</span></span>
                        </div>
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-white rounded-lg shadow-sm">
                            <div class="w-3 h-3 rounded-full bg-orange-500"></div>
                            <span class="text-sm font-medium text-gray-700">Pending <span class="text-gray-500">({{ $tasksPending ?? 0 }})</span></span>
                        </div>
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-white rounded-lg shadow-sm">
                            <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                            <span class="text-sm font-medium text-gray-700">Published <span class="text-gray-500">({{ $tasksPublished ?? 0 }})</span></span>
                        </div>
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-white rounded-lg shadow-sm">
                            <div class="w-3 h-3 rounded-full bg-gray-400"></div>
                            <span class="text-sm font-medium text-gray-700">Inactive <span class="text-gray-500">({{ $tasksInactive ?? 0 }})</span></span>
                        </div>
                    </div>
                </div>

                <!-- Volunteer Engagement by Task Type -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 p-3 sm:p-4 lg:p-6 xl:p-8 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4 sm:mb-6">
                        <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                            <div class="bg-orange-100 rounded-lg sm:rounded-xl p-2 sm:p-2.5 flex-shrink-0">
                                <i class="fas fa-chart-bar text-orange-600 text-sm sm:text-base"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-base sm:text-lg font-bold text-gray-900">Engagement by Task Type</h3>
                                <p class="text-xs sm:text-sm text-gray-500 mt-0.5 sm:mt-1">Assignments vs completed tasks</p>
                            </div>
                        </div>
                    </div>
                    <div id="taskTypeEngagementChart" style="height: 250px; min-height: 250px;" class="sm:h-[300px]"></div>
                </div>
            </div>

            <!-- Segmented Insights Section -->
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 p-3 sm:p-4 lg:p-6 xl:p-8" id="segmented-insights-section">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-3 sm:gap-4 lg:gap-6 mb-4 sm:mb-6 lg:mb-8">
                    <div class="flex-1 w-full">
                        <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 flex items-center gap-2 sm:gap-3 mb-2">
                            <div class="bg-orange-100 rounded-lg sm:rounded-xl p-2 sm:p-2.5 flex-shrink-0">
                                <i class="fas fa-layer-group text-orange-600 text-sm sm:text-base lg:text-lg"></i>
                            </div>
                            <span class="min-w-0">Segmented Insights</span>
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-600 mt-1 ml-0 sm:ml-10 lg:ml-12" id="segment-description">
                            {{ $segmentationData['description'] ?? 'Explore key metrics segmented by task location or reward sponsor.' }}
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 w-full lg:w-auto">
                        <label for="segment-select" class="text-xs sm:text-sm font-medium text-gray-700 whitespace-nowrap">
                            Segment By:
                        </label>
                        <select name="segment" id="segment-select"
                                class="w-full sm:w-auto sm:min-w-[180px] lg:min-w-[200px] rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm bg-white px-3 sm:px-4 py-2 sm:py-2.5 font-medium text-gray-700 hover:border-orange-400 transition-colors min-h-[44px]">
                            <option value="task_location" @selected($selectedSegment === 'task_location')>Task Location</option>
                            <option value="reward_sponsor" @selected($selectedSegment === 'reward_sponsor')>Reward Sponsor</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4 lg:gap-6 xl:gap-8">
                    <!-- Chart Section -->
                    <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-xl p-4 sm:p-6 border border-orange-100">
                        <div class="mb-4">
                            <h4 class="text-base font-semibold text-gray-800 mb-1">Distribution Overview</h4>
                            <p class="text-xs text-gray-600">Task creation volume by segment</p>
                        </div>
                        <div id="segmentDistributionChart" style="height: 250px; min-height: 250px;" class="sm:h-[320px]"></div>
                    </div>
                    
                    <!-- Table Section -->
                    <div class="bg-gray-50 rounded-xl p-4 sm:p-6 border border-gray-200 overflow-x-auto">
                        <div class="mb-5">
                            <h4 class="text-base font-semibold text-gray-800 mb-1 flex items-center gap-2">
                                <i class="fas fa-table text-orange-600 text-sm"></i>
                                Segment Breakdown
                            </h4>
                            <p class="text-xs text-gray-600">Detailed breakdown with percentages</p>
                        </div>
                        <div class="overflow-x-auto -mx-2 px-2">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="border-b-2 border-gray-300">
                                        <th class="text-left py-3 px-3 font-bold text-gray-700 text-xs uppercase tracking-wider">Segment</th>
                                        <th class="text-right py-3 px-3 font-bold text-gray-700 text-xs uppercase tracking-wider">Count</th>
                                        <th class="text-right py-3 px-3 font-bold text-gray-700 text-xs uppercase tracking-wider w-32">Share</th>
                                    </tr>
                                </thead>
                                <tbody id="segment-table-body" class="divide-y divide-gray-200">
                                    @forelse($segmentationData['table'] ?? [] as $row)
                                        <tr class="hover:bg-white transition-colors duration-150 group">
                                            <td class="py-3.5 px-3">
                                                <div class="font-semibold text-gray-900 group-hover:text-orange-600 transition-colors">
                                                    {{ $row['label'] }}
                                                </div>
                                            </td>
                                            <td class="py-3.5 px-3 text-right">
                                                <span class="font-bold text-gray-800">{{ number_format($row['count']) }}</span>
                                            </td>
                                            <td class="py-3.5 px-3">
                                                <div class="flex items-center justify-end gap-3">
                                                    <div class="flex-1 max-w-[100px]">
                                                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                                            <div class="bg-gradient-to-r from-orange-500 to-red-500 h-2 rounded-full transition-all duration-500" 
                                                                 style="width: {{ $row['share'] ?? 0 }}%"></div>
                                                        </div>
                                                    </div>
                                                    <span class="font-bold text-gray-700 text-sm min-w-[3.5rem] text-right">
                                                        {{ number_format($row['share'] ?? 0, 1) }}%
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="py-12 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <i class="fas fa-inbox text-gray-300 text-4xl mb-3"></i>
                                                    <p class="text-sm text-gray-500 font-medium">No segmentation data available</p>
                                                    <p class="text-xs text-gray-400 mt-1">for this period</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Analytics Section -->
            <div class="bg-white rounded-lg sm:rounded-xl shadow-md border border-gray-200 p-3 sm:p-4 lg:p-6 xl:p-8">
                <div class="mb-4 sm:mb-6 lg:mb-8">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 sm:gap-4">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg sm:rounded-xl p-2.5 sm:p-3 shadow-lg shadow-blue-500/20 flex-shrink-0">
                                <i class="fas fa-chart-line text-white text-base sm:text-lg"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-gray-100">Reports</h3>
                                <p class="text-xs sm:text-sm text-gray-600 mt-0.5 sm:mt-1">Key performance indicators from generated reports</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3 sm:gap-4 lg:gap-6">
                    <!-- User Accounts -->
                    <div class="bg-gradient-to-br from-white via-blue-50/40 to-blue-50/60 rounded-lg sm:rounded-xl shadow-md border border-blue-100/60 p-4 sm:p-5 lg:p-6 hover:shadow-xl hover:border-blue-200 hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-20 sm:w-24 h-20 sm:h-24 bg-blue-200/20 rounded-full -mr-10 sm:-mr-12 -mt-10 sm:-mt-12 blur-2xl"></div>
                        <div class="relative">
                            <div class="flex items-center gap-2 sm:gap-3 mb-4 sm:mb-5">
                                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg sm:rounded-xl p-2.5 sm:p-3 lg:p-3.5 shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform duration-300 flex-shrink-0">
                                    <i class="fas fa-users text-white text-base sm:text-lg"></i>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-gray-900 text-base sm:text-lg">User Accounts List</h4>
                                    <p class="text-[10px] sm:text-xs text-gray-600 font-medium">Volunteer directory</p>
                                </div>
                            </div>
                            <div class="space-y-2 sm:space-y-2.5 mb-4 sm:mb-5">
                                <div class="flex justify-between items-center bg-white/80 backdrop-blur-sm rounded-lg px-4 py-3 border border-blue-100/50 group-hover:bg-white transition-colors">
                                    <span class="text-sm font-medium text-gray-700">Active Accounts</span>
                                    <span class="text-lg font-bold text-gray-900">{{ $activeVolunteers ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between items-center bg-white/80 backdrop-blur-sm rounded-lg px-4 py-3 border border-blue-100/50 group-hover:bg-white transition-colors">
                                    <span class="text-sm font-medium text-gray-700">Engagement Rate</span>
                                    <span class="text-lg font-bold text-blue-600">{{ $engagementRate ?? 0 }}%</span>
                                </div>
                                <div class="flex justify-between items-center bg-white/80 backdrop-blur-sm rounded-lg px-4 py-3 border border-blue-100/50 group-hover:bg-white transition-colors">
                                    <span class="text-sm font-medium text-gray-700">Users with Tasks</span>
                                    <span class="text-lg font-bold text-gray-900">{{ $usersWithTasks ?? 0 }}</span>
                                </div>
                            </div>
                            <a href="{{ route('admin.reports.users', ['period' => $selectedPeriod, 'start_date' => $filterStartDate, 'end_date' => $filterEndDate]) }}" 
                               class="mt-3 sm:mt-4 inline-flex items-center gap-2 text-xs sm:text-sm text-blue-600 hover:text-blue-700 font-semibold bg-white px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg hover:shadow-md border border-blue-200 hover:border-blue-300 transition-all group/btn w-full sm:w-auto justify-center">
                                <span class="whitespace-nowrap">Download Accounts Report</span> <i class="fas fa-arrow-right text-xs group-hover/btn:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Task List -->
                    <div class="bg-gradient-to-br from-white via-green-50/40 to-green-50/60 rounded-lg sm:rounded-xl shadow-md border border-green-100/60 p-4 sm:p-5 lg:p-6 hover:shadow-xl hover:border-green-200 hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-20 sm:w-24 h-20 sm:h-24 bg-green-200/20 rounded-full -mr-10 sm:-mr-12 -mt-10 sm:-mt-12 blur-2xl"></div>
                        <div class="relative">
                            <div class="flex items-center gap-2 sm:gap-3 mb-4 sm:mb-5">
                                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg sm:rounded-xl p-2.5 sm:p-3 lg:p-3.5 shadow-lg shadow-green-500/30 group-hover:scale-110 transition-transform duration-300 flex-shrink-0">
                                    <i class="fas fa-tasks text-white text-base sm:text-lg"></i>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-gray-900 text-base sm:text-lg">Task List</h4>
                                    <p class="text-[10px] sm:text-xs text-gray-600 font-medium">Performance snapshot</p>
                                </div>
                            </div>
                            <div class="space-y-2 sm:space-y-2.5 mb-4 sm:mb-5">
                                <div class="flex justify-between items-center bg-white/80 backdrop-blur-sm rounded-lg px-3 sm:px-4 py-2.5 sm:py-3 border border-green-100/50 group-hover:bg-white transition-colors">
                                    <span class="text-xs sm:text-sm font-medium text-gray-700">Completion Rate</span>
                                    <span class="text-base sm:text-lg font-bold text-green-600">{{ $taskCompletionRate ?? 0 }}%</span>
                                </div>
                                <div class="flex justify-between items-center bg-white/80 backdrop-blur-sm rounded-lg px-3 sm:px-4 py-2.5 sm:py-3 border border-green-100/50 group-hover:bg-white transition-colors">
                                    <span class="text-xs sm:text-sm font-medium text-gray-700">Completed Assignments</span>
                                    <span class="text-base sm:text-lg font-bold text-gray-900">{{ $completedAssignments ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between items-center bg-white/80 backdrop-blur-sm rounded-lg px-3 sm:px-4 py-2.5 sm:py-3 border border-green-100/50 group-hover:bg-white transition-colors">
                                    <span class="text-xs sm:text-sm font-medium text-gray-700">Points Awarded</span>
                                    <span class="text-base sm:text-lg font-bold text-gray-900">{{ number_format($totalPointsAwarded ?? 0) }}</span>
                                </div>
                            </div>
                            <a href="{{ route('admin.reports.tasks', ['period' => $selectedPeriod, 'start_date' => $filterStartDate, 'end_date' => $filterEndDate]) }}" 
                               class="mt-3 sm:mt-4 inline-flex items-center gap-2 text-xs sm:text-sm text-green-600 hover:text-green-700 font-semibold bg-white px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg hover:shadow-md border border-green-200 hover:border-green-300 transition-all group/btn w-full sm:w-auto justify-center">
                                <span class="whitespace-nowrap">Download Task Report</span> <i class="fas fa-arrow-right text-xs group-hover/btn:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Rewards List -->
                    <div class="bg-gradient-to-br from-white via-purple-50/40 to-purple-50/60 rounded-lg sm:rounded-xl shadow-md border border-purple-100/60 p-4 sm:p-5 lg:p-6 hover:shadow-xl hover:border-purple-200 hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-20 sm:w-24 h-20 sm:h-24 bg-purple-200/20 rounded-full -mr-10 sm:-mr-12 -mt-10 sm:-mt-12 blur-2xl"></div>
                        <div class="relative">
                            <div class="flex items-center gap-2 sm:gap-3 mb-4 sm:mb-5">
                                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg sm:rounded-xl p-2.5 sm:p-3 lg:p-3.5 shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform duration-300 flex-shrink-0">
                                    <i class="fas fa-gift text-white text-base sm:text-lg"></i>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-gray-900 text-base sm:text-lg">Rewards List</h4>
                                    <p class="text-[10px] sm:text-xs text-gray-600 font-medium">Catalog health</p>
                                </div>
                            </div>
                            <div class="space-y-2 sm:space-y-2.5 mb-4 sm:mb-5">
                                <div class="flex justify-between items-center bg-white/80 backdrop-blur-sm rounded-lg px-3 sm:px-4 py-2.5 sm:py-3 border border-purple-100/50 group-hover:bg-white transition-colors">
                                    <span class="text-xs sm:text-sm font-medium text-gray-700">Active Rewards</span>
                                    <span class="text-base sm:text-lg font-bold text-gray-900">{{ $activeRewardsCount ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between items-center bg-white/80 backdrop-blur-sm rounded-lg px-3 sm:px-4 py-2.5 sm:py-3 border border-purple-100/50 group-hover:bg-white transition-colors">
                                    <span class="text-xs sm:text-sm font-medium text-gray-700">Redemptions (Period)</span>
                                    <span class="text-base sm:text-lg font-bold text-purple-600">{{ $rewardRedemptionsThisPeriod ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between items-center bg-white/80 backdrop-blur-sm rounded-lg px-3 sm:px-4 py-2.5 sm:py-3 border border-purple-100/50 group-hover:bg-white transition-colors">
                                    <span class="text-xs sm:text-sm font-medium text-gray-700">Pending Approvals</span>
                                    <span class="text-base sm:text-lg font-bold text-gray-900">{{ $pendingRewardRedemptions ?? 0 }}</span>
                                </div>
                            </div>
                            <a href="{{ route('admin.reports.rewards', ['period' => $selectedPeriod, 'start_date' => $filterStartDate, 'end_date' => $filterEndDate]) }}" 
                               class="mt-3 sm:mt-4 inline-flex items-center gap-2 text-xs sm:text-sm text-purple-600 hover:text-purple-700 font-semibold bg-white px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg hover:shadow-md border border-purple-200 hover:border-purple-300 transition-all group/btn w-full sm:w-auto justify-center">
                                <span class="whitespace-nowrap">Download Rewards Report</span> <i class="fas fa-arrow-right text-xs group-hover/btn:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Tap & Pass -->
                    <div class="bg-gradient-to-br from-white via-red-50/40 to-red-50/60 rounded-lg sm:rounded-xl shadow-md border border-red-100/60 p-4 sm:p-5 lg:p-6 hover:shadow-xl hover:border-red-200 hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-20 sm:w-24 h-20 sm:h-24 bg-red-200/20 rounded-full -mr-10 sm:-mr-12 -mt-10 sm:-mt-12 blur-2xl"></div>
                        <div class="relative">
                            <div class="flex items-center gap-2 sm:gap-3 mb-4 sm:mb-5">
                                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg sm:rounded-xl p-2.5 sm:p-3 lg:p-3.5 shadow-lg shadow-red-500/30 group-hover:scale-110 transition-transform duration-300 flex-shrink-0">
                                    <i class="fas fa-link text-white text-base sm:text-lg"></i>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-gray-900 text-base sm:text-lg">Tap &amp; Pass Nominations</h4>
                                    <p class="text-[10px] sm:text-xs text-gray-600 font-medium">Chain momentum</p>
                                </div>
                            </div>
                            <div class="space-y-2 sm:space-y-2.5 mb-4 sm:mb-5">
                                <div class="flex justify-between items-center bg-white/80 backdrop-blur-sm rounded-lg px-3 sm:px-4 py-2.5 sm:py-3 border border-red-100/50 group-hover:bg-white transition-colors">
                                    <span class="text-xs sm:text-sm font-medium text-gray-700">Accepted Links</span>
                                    <span class="text-base sm:text-lg font-bold text-gray-900">{{ $taskChainNominations ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between items-center bg-white/80 backdrop-blur-sm rounded-lg px-3 sm:px-4 py-2.5 sm:py-3 border border-red-100/50 group-hover:bg-white transition-colors">
                                    <span class="text-xs sm:text-sm font-medium text-gray-700">Engagement</span>
                                    <span class="text-base sm:text-lg font-bold text-red-600">{{ $chainEngagementRate ?? 0 }}%</span>
                                </div>
                                <div class="flex justify-between items-center bg-white/80 backdrop-blur-sm rounded-lg px-3 sm:px-4 py-2.5 sm:py-3 border border-red-100/50 group-hover:bg-white transition-colors">
                                    <span class="text-xs sm:text-sm font-medium text-gray-700">Total Nominations</span>
                                    <span class="text-base sm:text-lg font-bold text-gray-900">{{ $totalNominations ?? 0 }}</span>
                                </div>
                            </div>
                            <a href="{{ route('admin.reports.tap-pass', ['period' => $selectedPeriod, 'start_date' => $filterStartDate, 'end_date' => $filterEndDate]) }}" 
                               class="mt-3 sm:mt-4 inline-flex items-center gap-2 text-xs sm:text-sm text-red-600 hover:text-red-700 font-semibold bg-white px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg hover:shadow-md border border-red-200 hover:border-red-300 transition-all group/btn w-full sm:w-auto justify-center">
                                <span class="whitespace-nowrap">Download Tap &amp; Pass Report</span> <i class="fas fa-arrow-right text-xs group-hover/btn:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Incident Reports -->
                    <div class="bg-gradient-to-br from-white via-slate-50/40 to-slate-50/60 rounded-lg sm:rounded-xl shadow-md border border-slate-100/60 p-4 sm:p-5 lg:p-6 hover:shadow-xl hover:border-slate-200 hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden xl:col-span-1">
                        <div class="absolute top-0 right-0 w-20 sm:w-24 h-20 sm:h-24 bg-slate-200/20 rounded-full -mr-10 sm:-mr-12 -mt-10 sm:-mt-12 blur-2xl"></div>
                        <div class="relative">
                            <div class="flex items-center gap-2 sm:gap-3 mb-4 sm:mb-5">
                                <div class="bg-gradient-to-br from-slate-600 to-slate-700 rounded-lg sm:rounded-xl p-2.5 sm:p-3 lg:p-3.5 shadow-lg shadow-slate-500/30 group-hover:scale-110 transition-transform duration-300 flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-white text-base sm:text-lg"></i>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-gray-900 text-base sm:text-lg">Incident Reports</h4>
                                    <p class="text-[10px] sm:text-xs text-gray-600 font-medium">Safety oversight</p>
                                </div>
                            </div>
                            <div class="space-y-2 sm:space-y-2.5 mb-4 sm:mb-5">
                                <div class="flex justify-between items-center bg-white/80 backdrop-blur-sm rounded-lg px-3 sm:px-4 py-2.5 sm:py-3 border border-slate-100/50 group-hover:bg-white transition-colors">
                                    <span class="text-xs sm:text-sm font-medium text-gray-700">Open Cases</span>
                                    <span class="text-base sm:text-lg font-bold text-gray-900">{{ $incidentInsights['open_incidents'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between items-center bg-white/80 backdrop-blur-sm rounded-lg px-3 sm:px-4 py-2.5 sm:py-3 border border-slate-100/50 group-hover:bg-white transition-colors">
                                    <span class="text-xs sm:text-sm font-medium text-gray-700">Resolved</span>
                                    <span class="text-base sm:text-lg font-bold text-slate-700">{{ $incidentInsights['resolved_count'] ?? 0 }}</span>
                                </div>
                                @php
                                    $avgResolutionHours = $incidentInsights['average_resolution_hours'] ?? null;
                                @endphp
                                <div class="flex justify-between items-center bg-white/80 backdrop-blur-sm rounded-lg px-3 sm:px-4 py-2.5 sm:py-3 border border-slate-100/50 group-hover:bg-white transition-colors">
                                    <span class="text-xs sm:text-sm font-medium text-gray-700">Avg Resolution</span>
                                    <span class="text-base sm:text-lg font-bold text-gray-900">
                                        {{ $avgResolutionHours !== null ? $avgResolutionHours . 'h' : 'N/A' }}
                                    </span>
                                </div>
                            </div>
                            <a href="{{ route('admin.reports.incidents', ['period' => $selectedPeriod, 'start_date' => $filterStartDate, 'end_date' => $filterEndDate]) }}" 
                               class="mt-3 sm:mt-4 inline-flex items-center gap-2 text-xs sm:text-sm text-slate-700 hover:text-slate-800 font-semibold bg-white px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg hover:shadow-md border border-slate-200 hover:border-slate-300 transition-all group/btn w-full sm:w-auto justify-center">
                                <span class="whitespace-nowrap">Download Incident Report</span> <i class="fas fa-arrow-right text-xs group-hover/btn:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Performers Leaderboard -->
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 p-3 sm:p-4 lg:p-6 xl:p-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 mb-4 sm:mb-6 lg:mb-8">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="bg-yellow-100 rounded-lg sm:rounded-xl p-2 sm:p-2.5 flex-shrink-0">
                            <i class="fas fa-trophy text-yellow-600 text-base sm:text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900">Top Performers</h3>
                            <p class="text-xs sm:text-sm text-gray-600 mt-0.5 sm:mt-1">Leaderboard by points earned</p>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto -mx-3 sm:-mx-0 rounded-lg sm:rounded-xl border-2 border-gray-200 shadow-sm">
                    <div class="inline-block min-w-full align-middle">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <tr>
                                    <th class="px-3 sm:px-4 lg:px-6 py-2.5 sm:py-3 lg:py-4 text-left text-[10px] sm:text-xs font-bold text-gray-700 uppercase tracking-wider">Rank</th>
                                    <th class="px-3 sm:px-4 lg:px-6 py-2.5 sm:py-3 lg:py-4 text-left text-[10px] sm:text-xs font-bold text-gray-700 uppercase tracking-wider">Name</th>
                                    <th class="px-3 sm:px-4 lg:px-6 py-2.5 sm:py-3 lg:py-4 text-left text-[10px] sm:text-xs font-bold text-gray-700 uppercase tracking-wider">Points</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($topPerformers ?? [] as $index => $user)
                                    <tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-white transition-all duration-200 group">
                                        <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 lg:py-5 whitespace-nowrap">
                                            @if($index === 0)
                                                <span class="inline-flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-r from-yellow-400 to-yellow-500 text-white font-bold shadow-lg group-hover:scale-110 transition-transform text-xs sm:text-sm">
                                                    <i class="fas fa-crown text-xs sm:text-sm mr-0.5 sm:mr-1"></i>
                                                    {{ $index + 1 }}
                                                </span>
                                            @elseif($index === 1)
                                                <span class="inline-flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-r from-gray-300 to-gray-400 text-white font-bold shadow-md group-hover:scale-110 transition-transform text-xs sm:text-sm">
                                                    {{ $index + 1 }}
                                                </span>
                                            @elseif($index === 2)
                                                <span class="inline-flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-r from-orange-300 to-orange-400 text-white font-bold shadow-md group-hover:scale-110 transition-transform text-xs sm:text-sm">
                                                    {{ $index + 1 }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gray-200 text-gray-700 font-bold group-hover:bg-gray-300 transition-colors text-xs sm:text-sm">
                                                    {{ $index + 1 }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 lg:py-5">
                                            <div class="flex items-center gap-1.5 sm:gap-2 lg:gap-3 min-w-0">
                                                <x-user-avatar
                                                    :user="$user"
                                                    size="h-7 w-7 sm:h-8 sm:w-8 lg:h-10 lg:w-10"
                                                    text-size="text-[10px] sm:text-xs lg:text-sm"
                                                    class="bg-gradient-to-br from-orange-400 to-red-500 text-white font-semibold flex-shrink-0"
                                                />
                                                <span class="text-xs sm:text-sm lg:text-base text-gray-900 font-semibold group-hover:text-orange-600 transition-colors truncate">{{ $user->fullName }}</span>
                                            </div>
                                        </td>
                                        <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 lg:py-5 whitespace-nowrap">
                                            <span class="inline-flex items-center gap-1 sm:gap-2 px-2 sm:px-3 lg:px-4 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-bold bg-gradient-to-r from-orange-100 to-orange-200 text-orange-700 border border-orange-300 group-hover:from-orange-200 group-hover:to-orange-300 transition-all">
                                                <i class="fas fa-star text-orange-500 text-xs"></i>
                                                <span class="whitespace-nowrap">{{ number_format($user->points) }} pts</span>
                                            </span>
                                        </td>
                                    </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-trophy text-gray-300 text-4xl mb-3"></i>
                                            <p class="text-sm text-gray-500 font-medium">No performers data available</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- ApexCharts Scripts -->
    <script>
        // Wait for ApexCharts to be available
        function initCharts() {
            // Check if ApexCharts is loaded (check both window.ApexCharts and ApexCharts)
            const ApexChartsLib = window.ApexCharts || (typeof ApexCharts !== 'undefined' ? ApexCharts : null);
            
            if (!ApexChartsLib) {
                console.warn('ApexCharts not loaded yet, retrying...');
                setTimeout(initCharts, 100);
                return;
            }

            // Use ApexChartsLib for all chart initializations
            const Chart = ApexChartsLib;

            const periodSelect = document.getElementById('period-select');

            if (periodSelect) {
                periodSelect.addEventListener('change', function() {
                    // Update dashboard via AJAX when period changes
                    updateDashboard();
                });
            }
            
            // Main function to update entire dashboard via AJAX
            function updateDashboard() {
                // Show loading indicator
                const loadingIndicator = document.createElement('div');
                loadingIndicator.id = 'dashboard-loading';
                loadingIndicator.className = 'fixed top-4 right-4 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg z-50 flex items-center gap-2';
                loadingIndicator.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating dashboard...';
                document.body.appendChild(loadingIndicator);
                
                // Get current filter values
                const periodSelect = document.getElementById('period-select');
                const segmentSelect = document.getElementById('segment-select');
                
                const params = new URLSearchParams({
                    period: periodSelect ? periodSelect.value : 'last_30_days',
                    start_date: '',
                    end_date: '',
                    segment: segmentSelect ? segmentSelect.value : 'task_location'
                });
                
                // Make AJAX request
                fetch(`{{ route('admin.dashboard.data') }}?${params.toString()}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    // Check if response is ok and is JSON
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Response is not JSON');
                    }
                    return response.json();
                })
                .then(data => {
                    // Update metrics cards
                    updateMetricsCards(data);
                    
                    // Update charts
                    updateCharts(data);
                    
                    // Update segmented insights
                    updateSegmentedInsights(data.selectedSegment, data.segmentationData);
                    
                    // Update top performers
                    updateTopPerformers(data.topPerformers);
                    
                    // Remove loading indicator
                    const loading = document.getElementById('dashboard-loading');
                    if (loading) loading.remove();
                })
                .catch(error => {
                    console.error('AJAX update failed, falling back to form submission:', error);
                    // Remove loading indicator silently - don't show error message
                    const loading = document.getElementById('dashboard-loading');
                    if (loading) {
                        loading.remove();
                    }
                    // Fall back to normal form submission if AJAX fails
                    // This will reload the page with the selected period
                    const filterForm = document.getElementById('filter-form');
                    if (filterForm) {
                        filterForm.submit();
                    }
                });
            }
            
            function updateMetricsCards(data) {
                // Update Total Users Card - find by icon or text content
                const usersCard = document.querySelector('a[href*="admin.users"]');
                if (usersCard) {
                    const valueEl = usersCard.querySelector('.text-3xl.font-bold');
                    if (valueEl) valueEl.textContent = new Intl.NumberFormat().format(data.totalUsers || 0);
                    
                    const deltaValue = data.totalUsersDelta?.value || 0;
                    const deltaPercent = data.totalUsersDelta?.percent;
                    const deltaPositive = deltaValue >= 0;
                    const deltaEl = usersCard.querySelector('.text-right .flex.items-center');
                    if (deltaEl) {
                        deltaEl.className = `flex items-center gap-1 mt-0.5 ${deltaPositive ? 'text-green-600' : 'text-red-600'}`;
                        deltaEl.innerHTML = `
                            <i class="fas ${deltaPositive ? 'fa-arrow-up' : 'fa-arrow-down'} text-xs"></i>
                            <span class="text-xs font-semibold">
                                ${deltaPositive ? '+' : ''}${new Intl.NumberFormat().format(deltaValue)}
                                ${deltaPercent !== null ? `(${deltaPositive ? '+' : ''}${deltaPercent}%)` : ''}
                            </span>
                        `;
                    }
                }
                
                // Update Total Tasks Card
                const tasksCard = document.querySelector('a[href*="admin.tasks"]');
                if (tasksCard) {
                    const valueEl = tasksCard.querySelector('.text-3xl.font-bold');
                    if (valueEl) valueEl.textContent = new Intl.NumberFormat().format(data.totalTasks || 0);
                    
                    const deltaValue = data.totalTasksDelta?.value || 0;
                    const deltaPercent = data.totalTasksDelta?.percent;
                    const deltaPositive = deltaValue >= 0;
                    const deltaEl = tasksCard.querySelector('.text-right .flex.items-center');
                    if (deltaEl) {
                        deltaEl.className = `flex items-center gap-1 mt-0.5 ${deltaPositive ? 'text-green-600' : 'text-red-600'}`;
                        deltaEl.innerHTML = `
                            <i class="fas ${deltaPositive ? 'fa-arrow-up' : 'fa-arrow-down'} text-xs"></i>
                            <span class="text-xs font-semibold">
                                ${deltaPositive ? '+' : ''}${new Intl.NumberFormat().format(deltaValue)}
                                ${deltaPercent !== null ? `(${deltaPositive ? '+' : ''}${deltaPercent}%)` : ''}
                            </span>
                        `;
                    }
                }
                
                // Update Total Incidents Card
                const incidentsCard = document.querySelector('a[href*="admin.incident-reports"]');
                if (incidentsCard) {
                    const valueEl = incidentsCard.querySelector('.text-3xl.font-bold');
                    if (valueEl) valueEl.textContent = new Intl.NumberFormat().format(data.totalIncidents || 0);
                    
                    const deltaValue = data.totalIncidentsDelta?.value || 0;
                    const deltaPercent = data.totalIncidentsDelta?.percent;
                    const deltaPositive = deltaValue >= 0;
                    const deltaEl = incidentsCard.querySelector('.text-right .flex.items-center');
                    if (deltaEl) {
                        deltaEl.className = `flex items-center gap-1 mt-0.5 ${deltaPositive ? 'text-green-600' : 'text-red-600'}`;
                        deltaEl.innerHTML = `
                            <i class="fas ${deltaPositive ? 'fa-arrow-up' : 'fa-arrow-down'} text-xs"></i>
                            <span class="text-xs font-semibold">
                                ${deltaPositive ? '+' : ''}${new Intl.NumberFormat().format(deltaValue)}
                                ${deltaPercent !== null ? `(${deltaPositive ? '+' : ''}${deltaPercent}%)` : ''}
                            </span>
                        `;
                    }
                }
                
                // Update Performance Metrics
                const completionRateEl = document.querySelector('.bg-white.rounded-xl:has(.fa-check-circle) .text-3xl');
                if (completionRateEl) completionRateEl.textContent = `${data.taskCompletionRate || 0}%`;
                
                const activeVolunteersEl = document.querySelector('.bg-white.rounded-xl:has(.fa-user-check) .text-3xl');
                if (activeVolunteersEl) activeVolunteersEl.textContent = data.activeVolunteers || 0;
                
                const engagementRateEl = document.querySelector('.bg-white.rounded-xl:has(.fa-chart-line) .text-3xl');
                if (engagementRateEl) engagementRateEl.textContent = `${data.engagementRate || 0}%`;
                
                const pointsEl = document.querySelector('.bg-white.rounded-xl:has(.fa-star) .text-3xl');
                if (pointsEl) pointsEl.textContent = new Intl.NumberFormat().format(data.totalPointsAwarded || 0);
            }
            
            function updateCharts(data) {
                const ApexChartsLib = window.ApexCharts || (typeof ApexCharts !== 'undefined' ? ApexCharts : null);
                if (!ApexChartsLib) return;
                
                const Chart = ApexChartsLib;
                
                // Update User Growth Chart
                if (data.labels && data.userGrowth && window.userGrowthChartInstance) {
                    window.userGrowthChartInstance.updateSeries([{
                        name: 'New Users',
                        data: data.userGrowth
                    }]);
                    window.userGrowthChartInstance.updateOptions({
                        xaxis: { categories: data.labels }
                    });
                }
                
                // Update Task Completion Chart
                if (data.taskCompletionLabels && data.taskCompletionTrend && window.taskCompletionChartInstance) {
                    window.taskCompletionChartInstance.updateSeries([{
                        name: 'Completed Tasks',
                        data: data.taskCompletionTrend
                    }]);
                    window.taskCompletionChartInstance.updateOptions({
                        xaxis: { categories: data.taskCompletionLabels }
                    });
                }
                
                // Update Task Distribution Chart
                if (window.taskDistributionChartInstance) {
                    window.taskDistributionChartInstance.updateSeries([
                        data.tasksCompleted || 0,
                        data.tasksPending || 0,
                        data.tasksPublished || 0,
                        data.tasksInactive || 0
                    ]);
                }
                
                // Update Task Type Engagement Chart
                if (data.taskTypeEngagement && window.taskTypeEngagementChartInstance) {
                    const labels = Object.keys(data.taskTypeEngagement);
                    const assigned = labels.map(l => data.taskTypeEngagement[l]?.assigned || 0);
                    const completed = labels.map(l => data.taskTypeEngagement[l]?.completed || 0);
                    
                    window.taskTypeEngagementChartInstance.updateSeries([
                        { name: 'Assigned', data: assigned },
                        { name: 'Completed', data: completed }
                    ]);
                    window.taskTypeEngagementChartInstance.updateOptions({
                        xaxis: { categories: labels }
                    });
                }
            }
            
            function updateTopPerformers(performers) {
                const performersTable = document.querySelector('table:has(th:contains("Rank"))');
                if (!performersTable || !performers || !Array.isArray(performers)) return;
                
                const tbody = performersTable.querySelector('tbody');
                if (tbody) {
                    if (performers.length > 0) {
                        tbody.innerHTML = performers.map((performer, index) => `
                            <tr class="hover:bg-gray-50 transition">
                                <td class="text-center py-3 px-4"><strong>#${index + 1}</strong></td>
                                <td class="py-3 px-4"><strong>${performer.name || (performer.firstName + ' ' + performer.lastName)}</strong></td>
                                <td class="py-3 px-4">${performer.email || ''}</td>
                                <td class="text-center py-3 px-4">${new Intl.NumberFormat().format(performer.tasks_completed || 0)}</td>
                                <td class="text-right py-3 px-4"><strong>${new Intl.NumberFormat().format(performer.points_earned || performer.points || 0)}</strong></td>
                            </tr>
                        `).join('');
                    } else {
                        tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">No performers data available</td></tr>';
                    }
                }
            }
            
            // Update segmented insights via AJAX (no page refresh)
            const segmentSelect = document.getElementById('segment-select');
            
            if (segmentSelect) {
                segmentSelect.addEventListener('change', function() {
                    // Update only segmented insights when segment changes
                    const periodSelect = document.getElementById('period-select');
                    
                    const params = new URLSearchParams({
                        segment: this.value,
                        period: periodSelect ? periodSelect.value : 'last_30_days',
                        start_date: '',
                        end_date: ''
                    });
                    
                    fetch(`{{ route('admin.dashboard.segmented-insights') }}?${params.toString()}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        updateSegmentedInsights(data.selectedSegment, data.segmentationData);
                    })
                    .catch(error => {
                        console.error('Error updating segmented insights:', error);
                    });
                });
            }
            
            function updateSegmentedInsights(segment, segmentationData) {
                // If data is provided directly, use it; otherwise fetch via AJAX
                if (segmentationData) {
                    updateSegmentedInsightsContent(segment, segmentationData);
                    return;
                }
                
                // Show loading state
                const chartContainer = document.getElementById('segmentDistributionChart');
                const tableBody = document.getElementById('segment-table-body');
                const description = document.getElementById('segment-description');
                
                if (chartContainer) {
                    chartContainer.innerHTML = '<div class="flex items-center justify-center h-full"><div class="text-gray-500">Loading...</div></div>';
                }
                if (tableBody) {
                    tableBody.innerHTML = '<tr><td colspan="3" class="py-12 text-center"><div class="flex flex-col items-center justify-center"><i class="fas fa-spinner fa-spin text-gray-400 text-2xl mb-2"></i><p class="text-sm text-gray-500">Loading...</p></div></td></tr>';
                }
                
                // Get current filter values
                const periodSelect = document.getElementById('period-select');
                
                const params = new URLSearchParams({
                    segment: segment,
                    period: periodSelect ? periodSelect.value : 'last_30_days',
                    start_date: '',
                    end_date: ''
                });
                
                // Make AJAX request
                fetch(`{{ route('admin.dashboard.segmented-insights') }}?${params.toString()}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    updateSegmentedInsightsContent(data.selectedSegment, data.segmentationData);
                })
                .catch(error => {
                    console.error('Error updating segmented insights:', error);
                    const tableBody = document.getElementById('segment-table-body');
                    const chartContainer = document.getElementById('segmentDistributionChart');
                    if (tableBody) {
                        tableBody.innerHTML = '<tr><td colspan="3" class="py-12 text-center text-red-500"><div class="flex flex-col items-center justify-center"><i class="fas fa-exclamation-circle text-red-400 text-2xl mb-2"></i><p class="text-sm font-medium">Error loading data</p><p class="text-xs text-gray-400 mt-1">Please try again</p></div></td></tr>';
                    }
                    if (chartContainer) {
                        chartContainer.innerHTML = '<div class="flex items-center justify-center h-full"><div class="text-red-500">Error loading chart</div></div>';
                    }
                });
            }
            
            function updateSegmentedInsightsContent(segment, data) {
                const chartContainer = document.getElementById('segmentDistributionChart');
                const tableBody = document.getElementById('segment-table-body');
                const description = document.getElementById('segment-description');
                
                // Update description
                if (description && data.description) {
                    description.textContent = data.description;
                }
                
                // Update table
                if (tableBody) {
                    if (data.table && data.table.length > 0) {
                        tableBody.innerHTML = data.table.map(row => `
                            <tr class="hover:bg-white transition-colors duration-150 group">
                                <td class="py-3.5 px-3">
                                    <div class="font-semibold text-gray-900 group-hover:text-orange-600 transition-colors">
                                        ${row.label}
                                    </div>
                                </td>
                                <td class="py-3.5 px-3 text-right">
                                    <span class="font-bold text-gray-800">${new Intl.NumberFormat().format(row.count)}</span>
                                </td>
                                <td class="py-3.5 px-3">
                                    <div class="flex items-center justify-end gap-3">
                                        <div class="flex-1 max-w-[100px]">
                                            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                                <div class="bg-gradient-to-r from-orange-500 to-red-500 h-2 rounded-full transition-all duration-500" 
                                                     style="width: ${row.share || 0}%"></div>
                                            </div>
                                        </div>
                                        <span class="font-bold text-gray-700 text-sm min-w-[3.5rem] text-right">
                                            ${parseFloat(row.share || 0).toFixed(1)}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        `).join('');
                    } else {
                        tableBody.innerHTML = `
                            <tr>
                                <td colspan="3" class="py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-inbox text-gray-300 text-4xl mb-3"></i>
                                        <p class="text-sm text-gray-500 font-medium">No segmentation data available</p>
                                        <p class="text-xs text-gray-400 mt-1">for this period</p>
                                    </div>
                                </td>
                            </tr>
                        `;
                    }
                }
                
                // Update chart
                if (chartContainer && data.labels && data.labels.length > 0) {
                    const ApexChartsLib = window.ApexCharts || (typeof ApexCharts !== 'undefined' ? ApexCharts : null);
                    if (ApexChartsLib) {
                        const Chart = ApexChartsLib;
                        const segmentOptions = {
                            series: [{
                                name: data.title || 'Distribution',
                                data: data.values || []
                            }],
                            chart: {
                                type: 'bar',
                                height: window.innerWidth < 640 ? 250 : 320,
                                toolbar: { show: false },
                                background: 'transparent'
                            },
                            plotOptions: {
                                bar: {
                                    borderRadius: 8,
                                    columnWidth: '65%',
                                    distributed: false,
                                    dataLabels: {
                                        position: 'top'
                                    }
                                }
                            },
                            dataLabels: {
                                enabled: true,
                                offsetY: -20,
                                style: {
                                    fontSize: '11px',
                                    fontWeight: 600,
                                    colors: ['#4b5563']
                                },
                                formatter: function (val) {
                                    return val;
                                }
                            },
                            xaxis: {
                                categories: data.labels,
                                labels: { 
                                    style: { 
                                        colors: '#6b7280', 
                                        fontSize: '11px',
                                        fontWeight: 500
                                    },
                                    rotate: data.labels.length > 8 ? -45 : 0,
                                    rotateAlways: false,
                                    maxHeight: 80,
                                    trim: true
                                },
                                axisBorder: {
                                    show: false
                                },
                                axisTicks: {
                                    show: false
                                }
                            },
                            yaxis: {
                                labels: { 
                                    style: { 
                                        colors: '#6b7280', 
                                        fontSize: '11px',
                                        fontWeight: 500
                                    }
                                },
                                axisBorder: {
                                    show: false
                                },
                                axisTicks: {
                                    show: false
                                }
                            },
                            fill: {
                                type: 'gradient',
                                gradient: {
                                    shade: 'light',
                                    type: 'vertical',
                                    shadeIntensity: 0.5,
                                    gradientToColors: ['#f97316'],
                                    inverseColors: false,
                                    opacityFrom: 0.8,
                                    opacityTo: 0.6,
                                    stops: [0, 100]
                                }
                            },
                            colors: ['#f97316'],
                            grid: {
                                borderColor: '#e5e7eb',
                                strokeDashArray: 3,
                                xaxis: {
                                    lines: {
                                        show: false
                                    }
                                },
                                yaxis: {
                                    lines: {
                                        show: true
                                    }
                                },
                                padding: {
                                    top: 0,
                                    right: 0,
                                    bottom: 0,
                                    left: 0
                                }
                            },
                            tooltip: {
                                theme: 'light',
                                style: {
                                    fontSize: '12px'
                                },
                                y: {
                                    formatter: function (val) {
                                        return val + ' tasks';
                                    }
                                }
                            }
                        };
                        
                        // Destroy existing chart if it exists
                        if (window.segmentChartInstance) {
                            window.segmentChartInstance.destroy();
                        }
                        
                        // Create new chart
                        window.segmentChartInstance = new Chart(document.querySelector("#segmentDistributionChart"), segmentOptions);
                        window.segmentChartInstance.render();
                    }
                } else if (chartContainer) {
                    chartContainer.innerHTML = '<div class="flex items-center justify-center h-full"><div class="text-gray-500">No data available</div></div>';
                }
            }
            

            // Chart data from Laravel
            const userGrowthLabels = @json($labels ?? []);
            const userGrowthValues = @json($userGrowth ?? []);
            const taskCompletionLabels = @json($taskCompletionLabels ?? []);
            const taskCompletionValues = @json($taskCompletionTrend ?? []);
            const segmentationData = @json($segmentationData ?? []);
            const taskTypeEngagement = @json($taskTypeEngagement ?? []);

            // User Growth Chart
            if (document.getElementById('userGrowthChart')) {
                // Use empty arrays if no data
                const labels = userGrowthLabels && userGrowthLabels.length > 0 ? userGrowthLabels : ['No Data'];
                const values = userGrowthValues && userGrowthValues.length > 0 ? userGrowthValues : [0];
                
                const userGrowthOptions = {
                    series: [{
                        name: 'Users Joined',
                        data: values
                    }],
                    chart: {
                        type: 'area',
                        height: window.innerWidth < 640 ? 250 : 300,
                        toolbar: { show: false },
                        zoom: { enabled: false }
                    },
                    dataLabels: { enabled: false },
                    stroke: {
                        curve: 'smooth',
                        width: 3,
                        colors: ['#f97316']
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.4,
                            opacityTo: 0.1,
                            stops: [0, 100],
                            colorStops: [{
                                offset: 0,
                                color: '#f97316',
                                opacity: 0.4
                            }, {
                                offset: 100,
                                color: '#f97316',
                                opacity: 0.1
                            }]
                        }
                    },
                    xaxis: {
                        categories: labels,
                        labels: {
                            style: { 
                                colors: '#6b7280', 
                                fontSize: '11px',
                                fontWeight: 500,
                                fontFamily: 'inherit'
                            },
                            rotate: labels.length > 10 ? -45 : 0,
                            rotateAlways: false,
                            hideOverlappingLabels: true,
                            trim: true,
                            maxHeight: 100,
                            formatter: function(value, opts) {
                                // Safety check for value
                                if (!value || typeof value !== 'string') {
                                    return value || '';
                                }
                                
                                // Safety check for opts
                                if (!opts || typeof opts.dataPointIndex === 'undefined') {
                                    // If opts is not available, just format the value
                                    const parts = value.split(' ');
                                    if (parts.length === 2) {
                                        return parts[1] + ' ' + parts[0].substring(0, 3);
                                    }
                                    return value;
                                }
                                
                                // Show every nth label if there are too many
                                const totalLabels = labels.length;
                                const dataPointIndex = opts.dataPointIndex;
                                
                                if (totalLabels > 20) {
                                    // Show every 3rd label
                                    if (dataPointIndex % 3 !== 0 && dataPointIndex !== totalLabels - 1) {
                                        return '';
                                    }
                                } else if (totalLabels > 15) {
                                    // Show every 2nd label
                                    if (dataPointIndex % 2 !== 0 && dataPointIndex !== totalLabels - 1) {
                                        return '';
                                    }
                                }
                                
                                // Format dates more cleanly: "Oct 24" -> "24 Oct" or just "24" if too many
                                const parts = value.split(' ');
                                if (parts.length === 2) {
                                    // If many labels, show just day number
                                    if (totalLabels > 15) {
                                        return parts[1];
                                    }
                                    // Otherwise show "24 Oct" format
                                    return parts[1] + ' ' + parts[0].substring(0, 3);
                                }
                                return value;
                            }
                        },
                        tickAmount: labels.length > 20 ? Math.ceil(labels.length / 3) : undefined
                    },
                    yaxis: {
                        labels: { style: { colors: '#6b7280', fontSize: '12px' } }
                    },
                    grid: {
                        borderColor: '#e5e7eb',
                        strokeDashArray: 4
                    },
                    tooltip: {
                        theme: 'light',
                        style: { fontSize: '12px' }
                    },
                    colors: ['#f97316']
                };
                try {
                    window.userGrowthChartInstance = new Chart(document.querySelector("#userGrowthChart"), userGrowthOptions);
                    window.userGrowthChartInstance.render();
                } catch (error) {
                    console.error('Error rendering user growth chart:', error);
                }
            }

            // Task Completion Chart
            if (document.getElementById('taskCompletionChart')) {
                // Use empty arrays if no data
                const taskLabels = taskCompletionLabels && taskCompletionLabels.length > 0 ? taskCompletionLabels : ['No Data'];
                const taskValues = taskCompletionValues && taskCompletionValues.length > 0 ? taskCompletionValues : [0];
                
                const taskCompletionOptions = {
                    series: [{
                        name: 'Tasks Completed',
                        data: taskValues
                    }],
                    chart: {
                        type: 'area',
                        height: window.innerWidth < 640 ? 250 : 300,
                        toolbar: { show: false },
                        zoom: { enabled: false }
                    },
                    dataLabels: { enabled: false },
                    stroke: {
                        curve: 'smooth',
                        width: 3,
                        colors: ['#3b82f6']
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.4,
                            opacityTo: 0.1,
                            stops: [0, 100],
                            colorStops: [{
                                offset: 0,
                                color: '#3b82f6',
                                opacity: 0.4
                            }, {
                                offset: 100,
                                color: '#3b82f6',
                                opacity: 0.1
                            }]
                        }
                    },
                    xaxis: {
                        categories: taskLabels,
                        labels: {
                            style: { 
                                colors: '#6b7280', 
                                fontSize: '11px',
                                fontWeight: 500,
                                fontFamily: 'inherit'
                            },
                            rotate: taskLabels.length > 10 ? -45 : 0,
                            rotateAlways: false,
                            hideOverlappingLabels: true,
                            trim: true,
                            maxHeight: 100,
                            formatter: function(value, opts) {
                                // Safety check for value
                                if (!value || typeof value !== 'string') {
                                    return value || '';
                                }
                                
                                // Safety check for opts
                                if (!opts || typeof opts.dataPointIndex === 'undefined') {
                                    // If opts is not available, just format the value
                                    const parts = value.split(' ');
                                    if (parts.length === 2) {
                                        return parts[1] + ' ' + parts[0].substring(0, 3);
                                    }
                                    return value;
                                }
                                
                                // Show every nth label if there are too many
                                const totalLabels = taskLabels.length;
                                const dataPointIndex = opts.dataPointIndex;
                                
                                if (totalLabels > 20) {
                                    // Show every 3rd label
                                    if (dataPointIndex % 3 !== 0 && dataPointIndex !== totalLabels - 1) {
                                        return '';
                                    }
                                } else if (totalLabels > 15) {
                                    // Show every 2nd label
                                    if (dataPointIndex % 2 !== 0 && dataPointIndex !== totalLabels - 1) {
                                        return '';
                                    }
                                }
                                
                                // Format dates more cleanly: "Oct 24" -> "24 Oct" or just "24" if too many
                                const parts = value.split(' ');
                                if (parts.length === 2) {
                                    // If many labels, show just day number
                                    if (totalLabels > 15) {
                                        return parts[1];
                                    }
                                    // Otherwise show "24 Oct" format
                                    return parts[1] + ' ' + parts[0].substring(0, 3);
                                }
                                return value;
                            }
                        },
                        tickAmount: taskLabels.length > 20 ? Math.ceil(taskLabels.length / 3) : undefined
                    },
                    yaxis: {
                        labels: { style: { colors: '#6b7280', fontSize: '12px' } }
                    },
                    grid: {
                        borderColor: '#e5e7eb',
                        strokeDashArray: 4
                    },
                    tooltip: {
                        theme: 'light',
                        style: { fontSize: '12px' }
                    },
                    colors: ['#3b82f6']
                };
                try {
                    window.taskCompletionChartInstance = new Chart(document.querySelector("#taskCompletionChart"), taskCompletionOptions);
                    window.taskCompletionChartInstance.render();
                } catch (error) {
                    console.error('Error rendering task completion chart:', error);
                }
            }

            // Task Distribution Chart (Donut)
            if (document.getElementById('taskDistributionChart')) {
                const taskDistributionOptions = {
                    series: [{{ $tasksCompleted ?? 0 }}, {{ $tasksPending ?? 0 }}, {{ $tasksPublished ?? 0 }}, {{ $tasksInactive ?? 0 }}],
                    chart: {
                        type: 'donut',
                        height: window.innerWidth < 640 ? 250 : 300
                    },
                    labels: ['Completed', 'Pending', 'Published', 'Inactive'],
                    colors: ['#22c55e', '#f97316', '#3b82f6', '#9ca3af'],
                    legend: {
                        show: false
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function (val) {
                            return val.toFixed(1) + "%";
                        },
                        style: {
                            fontSize: '12px',
                            fontWeight: 600
                        }
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '65%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Total Tasks',
                                        fontSize: '14px',
                                        fontWeight: 600,
                                        color: '#374151',
                                        formatter: function () {
                                            return {{ $tasksCompleted + $tasksPending + $tasksPublished + $tasksInactive }};
                                        }
                                    }
                                }
                            }
                        }
                    },
                    tooltip: {
                        theme: 'light',
                        y: {
                            formatter: function (val) {
                                return val;
                            }
                        }
                    }
                };
                try {
                    window.taskDistributionChartInstance = new Chart(document.querySelector("#taskDistributionChart"), taskDistributionOptions);
                    window.taskDistributionChartInstance.render();
                } catch (error) {
                    console.error('Error rendering task distribution chart:', error);
                }
            }

            // Task Type Engagement Chart
            if (document.getElementById('taskTypeEngagementChart') && taskTypeEngagement && Array.isArray(taskTypeEngagement.labels) && taskTypeEngagement.labels.length > 0) {
                const taskTypeOptions = {
                    series: [
                        {
                            name: 'Assignments',
                            data: taskTypeEngagement.assignments || []
                        },
                        {
                            name: 'Completed',
                            data: taskTypeEngagement.completed || []
                        }
                    ],
                    chart: {
                        type: 'bar',
                        height: window.innerWidth < 640 ? 250 : 300,
                        toolbar: { show: false }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            borderRadius: 6
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: taskTypeEngagement.labels,
                        labels: { style: { colors: '#6b7280', fontSize: '12px' } }
                    },
                    yaxis: {
                        labels: { style: { colors: '#6b7280', fontSize: '12px' } }
                    },
                    fill: {
                        opacity: 1
                    },
                    colors: ['#3b82f6', '#22c55e'],
                    grid: {
                        borderColor: '#e5e7eb',
                        strokeDashArray: 4
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'right',
                        fontSize: '12px'
                    },
                    tooltip: {
                        theme: 'light',
                        y: {
                            formatter: function (val) {
                                return val;
                            }
                        }
                    }
                };
                try {
                    window.taskTypeEngagementChartInstance = new Chart(document.querySelector("#taskTypeEngagementChart"), taskTypeOptions);
                    window.taskTypeEngagementChartInstance.render();
                } catch (error) {
                    console.error('Error rendering task type engagement chart:', error);
                }
            }

            // Segment Distribution Chart
            if (document.getElementById('segmentDistributionChart') && segmentationData && Array.isArray(segmentationData.labels) && segmentationData.labels.length > 0) {
                const segmentOptions = {
                    series: [{
                        name: segmentationData.title || 'Distribution',
                        data: segmentationData.values || []
                    }],
                    chart: {
                        type: 'bar',
                        height: window.innerWidth < 640 ? 250 : 320,
                        toolbar: { show: false },
                        background: 'transparent'
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 8,
                            columnWidth: '65%',
                            distributed: false,
                            dataLabels: {
                                position: 'top'
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        offsetY: -20,
                        style: {
                            fontSize: '11px',
                            fontWeight: 600,
                            colors: ['#4b5563']
                        },
                        formatter: function (val) {
                            return val;
                        }
                    },
                    xaxis: {
                        categories: segmentationData.labels,
                        labels: { 
                            style: { 
                                colors: '#6b7280', 
                                fontSize: '11px',
                                fontWeight: 500
                            },
                            rotate: segmentationData.labels.length > 8 ? -45 : 0,
                            rotateAlways: false,
                            maxHeight: 80,
                            trim: true
                        },
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        }
                    },
                    yaxis: {
                        labels: { 
                            style: { 
                                colors: '#6b7280', 
                                fontSize: '11px',
                                fontWeight: 500
                            }
                        },
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        }
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'light',
                            type: 'vertical',
                            shadeIntensity: 0.5,
                            gradientToColors: ['#8b5cf6'],
                            inverseColors: false,
                            opacityFrom: 0.8,
                            opacityTo: 0.6,
                            stops: [0, 100]
                        }
                    },
                    colors: ['#6366f1'],
                    grid: {
                        borderColor: '#e5e7eb',
                        strokeDashArray: 3,
                        xaxis: {
                            lines: {
                                show: false
                            }
                        },
                        yaxis: {
                            lines: {
                                show: true
                            }
                        },
                        padding: {
                            top: 0,
                            right: 0,
                            bottom: 0,
                            left: 0
                        }
                    },
                    tooltip: {
                        theme: 'light',
                        style: {
                            fontSize: '12px'
                        },
                        y: {
                            formatter: function (val) {
                                return val + ' tasks';
                            }
                        }
                    }
                };
                try {
                    // Store chart instance for later updates
                    window.segmentChartInstance = new Chart(document.querySelector("#segmentDistributionChart"), segmentOptions);
                    window.segmentChartInstance.render();
                } catch (error) {
                    console.error('Error rendering segment distribution chart:', error);
                }
            }
        }

        // Wait for both DOM and ApexCharts to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                // Wait a bit for Vite bundle to load
                setTimeout(initCharts, 100);
            });
        } else {
            // DOM is already ready, just wait for ApexCharts
            setTimeout(initCharts, 100);
        }
    </script>
</x-admin-layout>
