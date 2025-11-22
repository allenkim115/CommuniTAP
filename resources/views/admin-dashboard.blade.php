<x-admin-layout>
    <div class="py-6 lg:py-8 bg-gradient-to-br from-gray-50 via-white to-orange-50/30 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 lg:space-y-8">
            
            <!-- Page Header -->
            <div class="bg-gradient-to-r from-red-600 via-orange-600 to-orange-500 rounded-2xl shadow-lg border border-orange-200 overflow-hidden">
                <div class="bg-white/10 backdrop-blur-sm p-6 lg:p-8">
                    <div class="flex items-start gap-4">
                        <div class="bg-white/20 rounded-xl p-3 backdrop-blur-sm">
                            <i class="fas fa-chart-line text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl lg:text-4xl font-bold text-white mb-2">Admin Dashboard</h1>
                            <p class="text-white/90 text-sm lg:text-base">Comprehensive overview of your platform metrics and insights</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-orange-100 rounded-lg p-2">
                        <i class="fas fa-filter text-orange-600"></i>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900">Filter Options</h2>
                </div>
                <form method="GET" action="{{ route('admin.dashboard') }}" id="filter-form" class="space-y-5">
                    <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-end">
                        <div class="flex-1 w-full lg:w-auto">
                            <label for="period-select" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <i class="fas fa-calendar-alt text-orange-500 text-xs"></i>
                                Time Period
                            </label>
                            <select id="period-select" name="period" 
                                    class="w-full lg:w-auto min-w-[200px] rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm px-4 py-2.5 font-medium bg-white hover:border-orange-400 transition-colors">
                                @foreach($periodOptions as $key => $label)
                                    <option value="{{ $key }}" @selected($selectedPeriod === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="custom-range-fields" class="flex-1 w-full lg:w-auto flex gap-4 hidden">
                            <div class="flex-1">
                                <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-calendar text-orange-500 text-xs"></i>
                                    Start Date
                                </label>
                                <input type="date" id="start_date" name="start_date" value="{{ $filterStartDate }}" 
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm px-4 py-2.5 font-medium bg-white hover:border-orange-400 transition-colors">
                            </div>
                            <div class="flex-1">
                                <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-calendar text-orange-500 text-xs"></i>
                                    End Date
                                </label>
                                <input type="date" id="end_date" name="end_date" value="{{ $filterEndDate }}" 
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm px-4 py-2.5 font-medium bg-white hover:border-orange-400 transition-colors">
                            </div>
                        </div>
                        <div class="flex gap-3 w-full lg:w-auto">
                            <a href="{{ route('admin.dashboard') }}" 
                               class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-semibold transition-all duration-200 flex items-center gap-2 w-full lg:w-auto justify-center">
                                <i class="fas fa-redo text-sm"></i>
                                Reset
                            </a>
                        </div>
                        <input type="hidden" name="segment" value="{{ $selectedSegment }}">
                    </div>
                    <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-lg p-4 border border-orange-100">
                        <p class="text-sm text-gray-700" id="range-summary">
                            <span class="font-bold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-info-circle text-orange-600"></i>
                                Showing:
                            </span> 
                            <span class="font-semibold text-gray-800">{{ $rangeSummary['label'] }}</span>
                            <span class="text-gray-600">({{ $rangeSummary['current_start']->format('M d, Y') }} – {{ $rangeSummary['current_end']->format('M d, Y') }})</span>
                            <span class="text-gray-500 block mt-1">Compared with: {{ $rangeSummary['previous_start']->format('M d, Y') }} – {{ $rangeSummary['previous_end']->format('M d, Y') }}</span>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Alerts Section -->
            @if(!empty($alerts))
                <div class="grid grid-cols-1 md:grid-cols-{{ min(count($alerts), 3) }} gap-4">
                    @foreach($alerts as $alert)
                        @php
                            $alertStyles = [
                                'critical' => 'bg-gradient-to-br from-red-50 to-red-100 border-red-300 text-red-900 shadow-red-100',
                                'warning' => 'bg-gradient-to-br from-amber-50 to-amber-100 border-amber-300 text-amber-900 shadow-amber-100',
                                'info' => 'bg-gradient-to-br from-orange-50 to-orange-100 border-orange-300 text-orange-900 shadow-orange-100',
                                'success' => 'bg-gradient-to-br from-emerald-50 to-emerald-100 border-emerald-300 text-emerald-900 shadow-emerald-100',
                            ];
                            $iconStyles = [
                                'critical' => 'fa-triangle-exclamation text-red-600 bg-red-200',
                                'warning' => 'fa-circle-exclamation text-amber-600 bg-amber-200',
                                'info' => 'fa-info-circle text-orange-600 bg-orange-200',
                                'success' => 'fa-circle-check text-emerald-600 bg-emerald-200',
                            ];
                            $style = $alertStyles[$alert['level']] ?? $alertStyles['info'];
                            $icon = $iconStyles[$alert['level']] ?? $iconStyles['info'];
                        @endphp
                        <div class="bg-white rounded-xl border-2 {{ $style }} p-5 flex items-start gap-4 shadow-md hover:shadow-lg transition-all duration-200">
                            <div class="bg-white rounded-lg p-2.5 flex-shrink-0">
                                <i class="fas {{ $icon }} text-xl"></i>
                            </div>
                            <p class="text-sm font-semibold flex-1 leading-relaxed">{{ $alert['message'] }}</p>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Key Metrics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Users Card -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white/20 rounded-lg p-3">
                            <i class="fas fa-users text-2xl"></i>
                        </div>
                        @php
                            $usersDeltaValue = $totalUsersDelta['value'] ?? 0;
                            $usersDeltaPercent = $totalUsersDelta['percent'];
                            $usersDeltaPositive = $usersDeltaValue >= 0;
                        @endphp
                        <div class="text-right">
                            <span class="text-sm opacity-90">vs Previous</span>
                            <div class="flex items-center gap-1 {{ $usersDeltaPositive ? 'text-green-200' : 'text-red-200' }}">
                                <i class="fas {{ $usersDeltaPositive ? 'fa-arrow-up' : 'fa-arrow-down' }} text-xs"></i>
                                <span class="text-sm font-semibold">
                                    {{ $usersDeltaPositive ? '+' : '' }}{{ number_format($usersDeltaValue) }}
                                    @if(!is_null($usersDeltaPercent))
                                        ({{ $usersDeltaPositive ? '+' : '' }}{{ $usersDeltaPercent }}%)
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="text-4xl font-bold mb-1">{{ number_format($totalUsers) }}</div>
                    <p class="text-blue-100 text-sm font-medium mb-4">Total Users</p>
                    <a href="{{ route('admin.users.index') }}" 
                       class="text-white/90 hover:text-white text-sm font-medium inline-flex items-center gap-1">
                        View Details <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>

                <!-- Total Tasks Card -->
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white/20 rounded-lg p-3">
                            <i class="fas fa-tasks text-2xl"></i>
                        </div>
                        @php
                            $tasksDeltaValue = $totalTasksDelta['value'] ?? 0;
                            $tasksDeltaPercent = $totalTasksDelta['percent'];
                            $tasksDeltaPositive = $tasksDeltaValue >= 0;
                        @endphp
                        <div class="text-right">
                            <span class="text-sm opacity-90">vs Previous</span>
                            <div class="flex items-center gap-1 {{ $tasksDeltaPositive ? 'text-green-200' : 'text-red-200' }}">
                                <i class="fas {{ $tasksDeltaPositive ? 'fa-arrow-up' : 'fa-arrow-down' }} text-xs"></i>
                                <span class="text-sm font-semibold">
                                    {{ $tasksDeltaPositive ? '+' : '' }}{{ number_format($tasksDeltaValue) }}
                                    @if(!is_null($tasksDeltaPercent))
                                        ({{ $tasksDeltaPositive ? '+' : '' }}{{ $tasksDeltaPercent }}%)
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="text-4xl font-bold mb-1">{{ number_format($totalTasks) }}</div>
                    <p class="text-orange-100 text-sm font-medium mb-4">Tasks Created</p>
                    <a href="{{ route('admin.tasks.index') }}" 
                       class="text-white/90 hover:text-white text-sm font-medium inline-flex items-center gap-1">
                        View Details <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>

                <!-- Total Incidents Card -->
                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white/20 rounded-lg p-3">
                            <i class="fas fa-exclamation-triangle text-2xl"></i>
                        </div>
                        @php
                            $incidentsDeltaValue = $totalIncidentsDelta['value'] ?? 0;
                            $incidentsDeltaPercent = $totalIncidentsDelta['percent'];
                            $incidentsDeltaPositive = $incidentsDeltaValue >= 0;
                        @endphp
                        <div class="text-right">
                            <span class="text-sm opacity-90">vs Previous</span>
                            <div class="flex items-center gap-1 {{ $incidentsDeltaPositive ? 'text-green-200' : 'text-red-200' }}">
                                <i class="fas {{ $incidentsDeltaPositive ? 'fa-arrow-up' : 'fa-arrow-down' }} text-xs"></i>
                                <span class="text-sm font-semibold">
                                    {{ $incidentsDeltaPositive ? '+' : '' }}{{ number_format($incidentsDeltaValue) }}
                                    @if(!is_null($incidentsDeltaPercent))
                                        ({{ $incidentsDeltaPositive ? '+' : '' }}{{ $incidentsDeltaPercent }}%)
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="text-4xl font-bold mb-1">{{ number_format($totalIncidents) }}</div>
                    <p class="text-red-100 text-sm font-medium mb-4">Incident Reports</p>
                    <a href="{{ route('admin.incident-reports.index') }}" 
                       class="text-white/90 hover:text-white text-sm font-medium inline-flex items-center gap-1">
                        View Details <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>

            <!-- Performance Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-green-300 transition-all duration-200 group">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-semibold text-gray-700">Task Completion Rate</span>
                        <div class="bg-green-100 rounded-xl p-2.5 group-hover:bg-green-200 transition-colors">
                            <i class="fas fa-check-circle text-green-600 text-lg"></i>
                        </div>
                    </div>
                    <div class="text-4xl font-bold text-gray-900 mb-2 group-hover:text-green-600 transition-colors">{{ $taskCompletionRate ?? 0 }}%</div>
                    <p class="text-xs text-gray-500 flex items-center gap-1">
                        <i class="fas fa-info-circle text-gray-400"></i>
                        Based on all assignments
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-blue-300 transition-all duration-200 group">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-semibold text-gray-700">Active Volunteers</span>
                        <div class="bg-blue-100 rounded-xl p-2.5 group-hover:bg-blue-200 transition-colors">
                            <i class="fas fa-user-check text-blue-600 text-lg"></i>
                        </div>
                    </div>
                    <div class="text-4xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">{{ $activeVolunteers ?? 0 }}</div>
                    <p class="text-xs text-gray-500 flex items-center gap-1">
                        <i class="fas fa-info-circle text-gray-400"></i>
                        Completed at least 1 task
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-orange-300 transition-all duration-200 group">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-semibold text-gray-700">Engagement Rate</span>
                        <div class="bg-orange-100 rounded-xl p-2.5 group-hover:bg-orange-200 transition-colors">
                            <i class="fas fa-chart-line text-orange-600 text-lg"></i>
                        </div>
                    </div>
                    <div class="text-4xl font-bold text-gray-900 mb-2 group-hover:text-orange-600 transition-colors">{{ $engagementRate ?? 0 }}%</div>
                    <p class="text-xs text-gray-500 flex items-center gap-1">
                        <i class="fas fa-info-circle text-gray-400"></i>
                        Users with assigned tasks
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-orange-300 transition-all duration-200 group">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-semibold text-gray-700">Points Awarded</span>
                        <div class="bg-orange-100 rounded-xl p-2.5 group-hover:bg-orange-200 transition-colors">
                            <i class="fas fa-star text-orange-600 text-lg"></i>
                        </div>
                    </div>
                    <div class="text-4xl font-bold text-gray-900 mb-2 group-hover:text-orange-600 transition-colors">{{ number_format($totalPointsAwarded ?? 0) }}</div>
                    <p class="text-xs text-gray-500 flex items-center gap-1">
                        <i class="fas fa-info-circle text-gray-400"></i>
                        From completed tasks
                    </p>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- User Growth Chart -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="bg-green-100 rounded-xl p-2.5">
                                <i class="fas fa-users text-green-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">User Growth Trend</h3>
                                <p class="text-sm text-gray-500 mt-1">New user registrations over time</p>
                            </div>
                        </div>
                        <span class="px-3 py-1.5 bg-green-100 text-green-700 rounded-full text-xs font-semibold border border-green-200">Last 6 months</span>
                    </div>
                    <div id="userGrowthChart" style="height: 300px;"></div>
                </div>

                <!-- Task Completion Trend Chart -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="bg-blue-100 rounded-xl p-2.5">
                                <i class="fas fa-tasks text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Task Completion Trend</h3>
                                <p class="text-sm text-gray-500 mt-1">Completed tasks over time</p>
                            </div>
                        </div>
                        <span class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold border border-blue-200">Last 6 months</span>
                    </div>
                    <div id="taskCompletionChart" style="height: 300px;"></div>
                </div>
            </div>

            <!-- Task Status & Engagement Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Task Status Distribution -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="bg-orange-100 rounded-xl p-2.5">
                                <i class="fas fa-chart-pie text-orange-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Task Status Distribution</h3>
                                <p class="text-sm text-gray-500 mt-1">Current task status breakdown</p>
                            </div>
                        </div>
                    </div>
                    <div id="taskDistributionChart" style="height: 300px;"></div>
                    <div class="flex flex-wrap gap-4 mt-6 justify-center bg-gray-50 rounded-lg p-4">
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
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="bg-orange-100 rounded-xl p-2.5">
                                <i class="fas fa-chart-bar text-orange-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Engagement by Task Type</h3>
                                <p class="text-sm text-gray-500 mt-1">Assignments vs completed tasks</p>
                            </div>
                        </div>
                    </div>
                    <div id="taskTypeEngagementChart" style="height: 300px;"></div>
                </div>
            </div>

            <!-- Segmented Insights Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8" id="segmented-insights-section">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 lg:gap-6 mb-6 lg:mb-8">
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-900 flex items-center gap-3 mb-2">
                            <div class="bg-orange-100 rounded-xl p-2.5">
                                <i class="fas fa-layer-group text-orange-600 text-lg"></i>
                            </div>
                            Segmented Insights
                        </h3>
                        <p class="text-sm text-gray-600 mt-1 ml-12" id="segment-description">
                            {{ $segmentationData['description'] ?? 'Explore key metrics segmented by task location or reward sponsor.' }}
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full lg:w-auto">
                        <label for="segment-select" class="text-sm font-medium text-gray-700 whitespace-nowrap">
                            Segment By:
                        </label>
                        <select name="segment" id="segment-select"
                                class="w-full sm:w-auto min-w-[200px] rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm bg-white px-4 py-2.5 font-medium text-gray-700 hover:border-orange-400 transition-colors">
                            <option value="task_location" @selected($selectedSegment === 'task_location')>Task Location</option>
                            <option value="reward_sponsor" @selected($selectedSegment === 'reward_sponsor')>Reward Sponsor</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                    <!-- Chart Section -->
                    <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-xl p-6 border border-orange-100">
                        <div class="mb-4">
                            <h4 class="text-base font-semibold text-gray-800 mb-1">Distribution Overview</h4>
                            <p class="text-xs text-gray-600">Task creation volume by segment</p>
                        </div>
                        <div id="segmentDistributionChart" style="height: 320px;"></div>
                    </div>
                    
                    <!-- Table Section -->
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
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
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8">
                <div class="mb-6 lg:mb-8">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-2">
                        <div class="flex items-center gap-3">
                            <div class="bg-blue-100 rounded-xl p-2.5">
                                <i class="fas fa-chart-line text-blue-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Business Analytics</h3>
                                <p class="text-sm text-gray-600 mt-1">Key performance indicators from generated reports</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.dashboard.pdf', ['period' => $selectedPeriod, 'start_date' => $filterStartDate, 'end_date' => $filterEndDate]) }}"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-red-600 to-orange-600 hover:from-red-700 hover:to-orange-700 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105"
                           title="Download summary report with key metrics (charts and detailed insights not included)">
                            <i class="fas fa-file-pdf text-lg"></i>
                            <span>Download Summary Report</span>
                        </a>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Volunteer Report -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border-2 border-blue-200 p-6 hover:shadow-lg hover:border-blue-300 transition-all duration-200 group">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="bg-blue-600 rounded-xl p-3 group-hover:scale-110 transition-transform">
                                <i class="fas fa-users text-white text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">Volunteer Report</h4>
                                <p class="text-xs text-gray-600 font-medium">Analytics</p>
                            </div>
                        </div>
                        <div class="space-y-3 mb-5">
                            <div class="flex justify-between items-center bg-white/60 rounded-lg px-4 py-2.5">
                                <span class="text-sm font-medium text-gray-700">Active Volunteers</span>
                                <span class="text-lg font-bold text-gray-900">{{ $activeVolunteers ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center bg-white/60 rounded-lg px-4 py-2.5">
                                <span class="text-sm font-medium text-gray-700">Engagement Rate</span>
                                <span class="text-lg font-bold text-blue-600">{{ $engagementRate ?? 0 }}%</span>
                            </div>
                            <div class="flex justify-between items-center bg-white/60 rounded-lg px-4 py-2.5">
                                <span class="text-sm font-medium text-gray-700">Users with Tasks</span>
                                <span class="text-lg font-bold text-gray-900">{{ $usersWithTasks ?? 0 }}</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.reports.volunteer', ['period' => $selectedPeriod, 'start_date' => $filterStartDate, 'end_date' => $filterEndDate]) }}" 
                           class="mt-4 inline-flex items-center gap-2 text-sm text-blue-700 hover:text-blue-800 font-semibold bg-white/80 px-4 py-2 rounded-lg hover:bg-white transition-all">
                            View Full Report <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>

                    <!-- Task Report -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl border-2 border-green-200 p-6 hover:shadow-lg hover:border-green-300 transition-all duration-200 group">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="bg-green-600 rounded-xl p-3 group-hover:scale-110 transition-transform">
                                <i class="fas fa-tasks text-white text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">Task Report</h4>
                                <p class="text-xs text-gray-600 font-medium">Analytics</p>
                            </div>
                        </div>
                        <div class="space-y-3 mb-5">
                            <div class="flex justify-between items-center bg-white/60 rounded-lg px-4 py-2.5">
                                <span class="text-sm font-medium text-gray-700">Completion Rate</span>
                                <span class="text-lg font-bold text-green-600">{{ $taskCompletionRate ?? 0 }}%</span>
                            </div>
                            <div class="flex justify-between items-center bg-white/60 rounded-lg px-4 py-2.5">
                                <span class="text-sm font-medium text-gray-700">Completed Tasks</span>
                                <span class="text-lg font-bold text-gray-900">{{ $completedAssignments ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center bg-white/60 rounded-lg px-4 py-2.5">
                                <span class="text-sm font-medium text-gray-700">Points Awarded</span>
                                <span class="text-lg font-bold text-gray-900">{{ number_format($totalPointsAwarded ?? 0) }}</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.reports.task', ['period' => $selectedPeriod, 'start_date' => $filterStartDate, 'end_date' => $filterEndDate]) }}" 
                           class="mt-4 inline-flex items-center gap-2 text-sm text-green-700 hover:text-green-800 font-semibold bg-white/80 px-4 py-2 rounded-lg hover:bg-white transition-all">
                            View Full Report <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>

                    <!-- Task Chain Report -->
                    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl border-2 border-red-200 p-6 hover:shadow-lg hover:border-red-300 transition-all duration-200 group">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="bg-red-600 rounded-xl p-3 group-hover:scale-110 transition-transform">
                                <i class="fas fa-link text-white text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">Task Chain Report</h4>
                                <p class="text-xs text-gray-600 font-medium">Analytics</p>
                            </div>
                        </div>
                        <div class="space-y-3 mb-5">
                            <div class="flex justify-between items-center bg-white/60 rounded-lg px-4 py-2.5">
                                <span class="text-sm font-medium text-gray-700">Task Chain Links</span>
                                <span class="text-lg font-bold text-gray-900">{{ $taskChainNominations ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center bg-white/60 rounded-lg px-4 py-2.5">
                                <span class="text-sm font-medium text-gray-700">Chain Engagement</span>
                                <span class="text-lg font-bold text-red-600">{{ $chainEngagementRate ?? 0 }}%</span>
                            </div>
                            <div class="flex justify-between items-center bg-white/60 rounded-lg px-4 py-2.5">
                                <span class="text-sm font-medium text-gray-700">Total Nominations</span>
                                <span class="text-lg font-bold text-gray-900">{{ $totalNominations ?? 0 }}</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.reports.task-chain', ['period' => $selectedPeriod, 'start_date' => $filterStartDate, 'end_date' => $filterEndDate]) }}" 
                           class="mt-4 inline-flex items-center gap-2 text-sm text-red-700 hover:text-red-800 font-semibold bg-white/80 px-4 py-2 rounded-lg hover:bg-white transition-all">
                            View Full Report <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Retention & Incident Insights -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8">
                <div class="mb-6 lg:mb-8">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="bg-emerald-100 rounded-xl p-2.5">
                            <i class="fas fa-chart-line text-emerald-600 text-lg"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Retention & Incident Insights</h3>
                    </div>
                    <p class="text-sm text-gray-600 ml-12">Track volunteer retention cohorts and incident resolution metrics</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    @forelse($retentionMetrics as $metric)
                        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl border border-emerald-200 p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-4">
                                <div class="bg-emerald-600 rounded-lg p-2.5">
                                    <i class="fas fa-users text-white"></i>
                                </div>
                                <span class="text-xs font-semibold uppercase tracking-wide text-emerald-700 bg-emerald-200/50 px-2 py-1 rounded">{{ $metric['label'] }}</span>
                            </div>
                            <p class="text-4xl font-bold text-gray-900 mb-1">{{ $metric['retention_rate'] }}%</p>
                            <div class="mt-4 space-y-2 text-sm">
                                <div class="flex items-center justify-between bg-white/60 rounded-lg px-3 py-2">
                                    <span class="text-gray-600">New Users</span>
                                    <span class="font-semibold text-gray-900">{{ number_format($metric['cohort_size']) }}</span>
                                </div>
                                <div class="flex items-center justify-between bg-emerald-200/40 rounded-lg px-3 py-2">
                                    <span class="text-emerald-700">Active</span>
                                    <span class="font-semibold text-emerald-700">{{ number_format($metric['retained']) }}</span>
                                </div>
                                <p class="text-xs text-gray-500 text-center mt-2">Within {{ $metric['lookback_days'] }} days</p>
                            </div>
                        </div>
                    @empty
                        <div class="md:col-span-3 bg-gray-50 rounded-xl border border-gray-200 p-8 text-center">
                            <i class="fas fa-info-circle text-gray-400 text-2xl mb-2"></i>
                            <p class="text-sm text-gray-500">No retention data available. Encourage new sign-ups to populate cohort metrics.</p>
                        </div>
                    @endforelse
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl border border-red-200 p-6 shadow-sm">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="bg-red-600 rounded-lg p-2.5">
                                <i class="fas fa-clock text-white text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-red-700">Incident Resolution</p>
                                <p class="text-xs text-gray-600 mt-0.5">Response time metrics</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="bg-white/60 rounded-lg p-3">
                                <p class="text-xs text-gray-600 mb-1">Average Resolution Time</p>
                                <p class="text-2xl font-bold text-gray-900">
                                    @if($incidentInsights['average_resolution_hours'] !== null)
                                        @php
                                            $avgHours = $incidentInsights['average_resolution_hours'];
                                            if ($avgHours >= 24) {
                                                $days = floor($avgHours / 24);
                                                $hours = floor($avgHours % 24);
                                                echo $days . 'd ' . $hours . 'h';
                                            } elseif ($avgHours >= 1) {
                                                $wholeHours = floor($avgHours);
                                                $minutes = round(($avgHours - $wholeHours) * 60);
                                                echo $wholeHours . 'h' . ($minutes > 0 ? ' ' . $minutes . 'm' : '');
                                            } else {
                                                echo round($avgHours * 60) . 'm';
                                            }
                                        @endphp
                                    @else
                                        —
                                    @endif
                                </p>
                            </div>
                            <div class="bg-white/60 rounded-lg p-3">
                                <p class="text-xs text-gray-600 mb-1">Median Resolution Time</p>
                                <p class="text-xl font-semibold text-gray-900">
                                    @if($incidentInsights['median_resolution_hours'] !== null)
                                        @php
                                            $medHours = $incidentInsights['median_resolution_hours'];
                                            if ($medHours >= 24) {
                                                $days = floor($medHours / 24);
                                                $hours = floor($medHours % 24);
                                                echo $days . 'd ' . $hours . 'h';
                                            } elseif ($medHours >= 1) {
                                                $wholeHours = floor($medHours);
                                                $minutes = round(($medHours - $wholeHours) * 60);
                                                echo $wholeHours . 'h' . ($minutes > 0 ? ' ' . $minutes . 'm' : '');
                                            } else {
                                                echo round($medHours * 60) . 'm';
                                            }
                                        @endphp
                                    @else
                                        —
                                    @endif
                                </p>
                            </div>
                            <div class="pt-3 border-t border-red-200 space-y-2">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-700">Open Incidents</span>
                                    <span class="font-semibold text-gray-900 bg-white/60 px-2 py-1 rounded">{{ $incidentInsights['open_incidents'] }}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-red-700 font-medium">Overdue (&gt;7 days)</span>
                                    <span class="font-semibold text-red-700 bg-red-200/60 px-2 py-1 rounded">{{ $incidentInsights['overdue_incidents'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide flex items-center gap-2">
                                    <i class="fas fa-list-check text-gray-500"></i>
                                    Recent Resolutions
                                </h4>
                                <p class="text-xs text-gray-500 mt-1">Latest incident resolutions</p>
                            </div>
                            <span class="text-xs font-medium text-gray-600 bg-gray-100 px-3 py-1.5 rounded-full">
                                {{ $incidentInsights['resolved_count'] }} resolved
                            </span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="border-b-2 border-gray-200 bg-gray-50">
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Incident Type</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Action Taken</th>
                                        <th class="text-right py-3 px-4 font-semibold text-gray-700">Resolution</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($incidentInsights['recent_resolutions'] ?? [] as $recent)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="py-3 px-4">
                                                <span class="font-medium text-gray-900">
                                                    {{ \Illuminate\Support\Str::headline($recent['incident_type'] ?? 'Unknown') }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4">
                                                @if($recent['action_taken'])
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                                        {{ \Illuminate\Support\Str::headline($recent['action_taken']) }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">—</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4 text-right">
                                                <div class="flex flex-col items-end">
                                                    <span class="font-medium text-gray-900">{{ $recent['resolved_at'] ?? '—' }}</span>
                                                    @if(!empty($recent['resolution_time_formatted']))
                                                        <span class="text-xs text-gray-500 mt-1 inline-flex items-center gap-1 bg-gray-100 px-2 py-0.5 rounded">
                                                            <i class="fas fa-clock text-gray-400 text-xs"></i>
                                                            {{ $recent['resolution_time_formatted'] }}
                                                        </span>
                                                    @elseif(!empty($recent['resolution_hours']))
                                                        <span class="text-xs text-gray-500 mt-1">
                                                            {{ number_format($recent['resolution_hours'], 1) }}h
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="py-8 text-center">
                                                <div class="flex flex-col items-center">
                                                    <i class="fas fa-inbox text-gray-300 text-3xl mb-2"></i>
                                                    <p class="text-sm text-gray-500">No incidents were resolved during this period.</p>
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

            <!-- Top Performers Leaderboard -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8">
                <div class="flex justify-between items-center mb-6 lg:mb-8">
                    <div class="flex items-center gap-3">
                        <div class="bg-yellow-100 rounded-xl p-2.5">
                            <i class="fas fa-trophy text-yellow-600 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Top Performers</h3>
                            <p class="text-sm text-gray-600 mt-1">Leaderboard by points earned</p>
                        </div>
                    </div>
                </div>
                <div class="overflow-hidden rounded-xl border-2 border-gray-200 shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Rank</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Points</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($topPerformers ?? [] as $index => $user)
                                <tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-white transition-all duration-200 group">
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        @if($index === 0)
                                            <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-r from-yellow-400 to-yellow-500 text-white font-bold shadow-lg group-hover:scale-110 transition-transform">
                                                <i class="fas fa-crown text-sm mr-1"></i>
                                                {{ $index + 1 }}
                                            </span>
                                        @elseif($index === 1)
                                            <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-r from-gray-300 to-gray-400 text-white font-bold shadow-md group-hover:scale-110 transition-transform">
                                                {{ $index + 1 }}
                                            </span>
                                        @elseif($index === 2)
                                            <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-r from-orange-300 to-orange-400 text-white font-bold shadow-md group-hover:scale-110 transition-transform">
                                                {{ $index + 1 }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-700 font-bold group-hover:bg-gray-300 transition-colors">
                                                {{ $index + 1 }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center text-white font-semibold text-sm">
                                                {{ strtoupper(substr($user->fullName, 0, 1)) }}
                                            </div>
                                            <span class="text-gray-900 font-semibold group-hover:text-orange-600 transition-colors">{{ $user->fullName }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold bg-gradient-to-r from-orange-100 to-orange-200 text-orange-700 border border-orange-300 group-hover:from-orange-200 group-hover:to-orange-300 transition-all">
                                            <i class="fas fa-star text-orange-500"></i>
                                            {{ number_format($user->points) }} pts
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center">
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
            const customRangeFields = document.getElementById('custom-range-fields');
            const filterForm = document.getElementById('filter-form');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            if (periodSelect && customRangeFields) {
                const toggleCustomFields = () => {
                    if (periodSelect.value === 'custom') {
                        customRangeFields.classList.remove('hidden');
                    } else {
                        customRangeFields.classList.add('hidden');
                    }
                };

                periodSelect.addEventListener('change', function() {
                    toggleCustomFields();
                    // Update dashboard via AJAX when period changes
                    updateDashboard();
                });
                toggleCustomFields();
            }
            
            // Update dashboard when custom date inputs change
            if (startDateInput) {
                startDateInput.addEventListener('change', function() {
                    if (periodSelect && periodSelect.value === 'custom') {
                        updateDashboard();
                    }
                });
            }
            
            if (endDateInput) {
                endDateInput.addEventListener('change', function() {
                    if (periodSelect && periodSelect.value === 'custom') {
                        updateDashboard();
                    }
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
                const startDateInput = document.getElementById('start_date');
                const endDateInput = document.getElementById('end_date');
                const segmentSelect = document.getElementById('segment-select');
                
                const params = new URLSearchParams({
                    period: periodSelect ? periodSelect.value : 'last_30_days',
                    start_date: startDateInput ? startDateInput.value : '',
                    end_date: endDateInput ? endDateInput.value : '',
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
                .then(response => response.json())
                .then(data => {
                    // Update range summary
                    const rangeSummary = document.getElementById('range-summary');
                    if (rangeSummary && data.rangeSummary) {
                        rangeSummary.innerHTML = `
                            <span class="font-medium">Showing:</span> ${data.rangeSummary.label} 
                            (${data.rangeSummary.current_start} – ${data.rangeSummary.current_end})
                            <span class="text-gray-500">| Compared with: ${data.rangeSummary.previous_start} – ${data.rangeSummary.previous_end}</span>
                        `;
                    }
                    
                    // Update metrics cards
                    updateMetricsCards(data);
                    
                    // Update charts
                    updateCharts(data);
                    
                    // Update segmented insights
                    updateSegmentedInsights(data.selectedSegment, data.segmentationData);
                    
                    // Update alerts
                    updateAlerts(data.alerts);
                    
                    // Update top performers
                    updateTopPerformers(data.topPerformers);
                    
                    // Remove loading indicator
                    const loading = document.getElementById('dashboard-loading');
                    if (loading) loading.remove();
                })
                .catch(error => {
                    console.error('Error updating dashboard:', error);
                    const loading = document.getElementById('dashboard-loading');
                    if (loading) {
                        loading.className = 'fixed top-4 right-4 bg-red-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                        loading.innerHTML = '<i class="fas fa-exclamation-circle"></i> Error updating dashboard';
                        setTimeout(() => loading.remove(), 3000);
                    }
                });
            }
            
            function updateMetricsCards(data) {
                // Update Total Users Card
                const usersCard = document.querySelector('.bg-gradient-to-br.from-blue-500');
                if (usersCard) {
                    const valueEl = usersCard.querySelector('.text-4xl');
                    if (valueEl) valueEl.textContent = new Intl.NumberFormat().format(data.totalUsers || 0);
                    
                    const deltaValue = data.totalUsersDelta?.value || 0;
                    const deltaPercent = data.totalUsersDelta?.percent;
                    const deltaPositive = deltaValue >= 0;
                    const deltaEl = usersCard.querySelector('.text-right .flex.items-center');
                    if (deltaEl) {
                        deltaEl.className = `flex items-center gap-1 ${deltaPositive ? 'text-green-200' : 'text-red-200'}`;
                        deltaEl.innerHTML = `
                            <i class="fas ${deltaPositive ? 'fa-arrow-up' : 'fa-arrow-down'} text-xs"></i>
                            <span class="text-sm font-semibold">
                                ${deltaPositive ? '+' : ''}${new Intl.NumberFormat().format(deltaValue)}
                                ${deltaPercent !== null ? `(${deltaPositive ? '+' : ''}${deltaPercent}%)` : ''}
                            </span>
                        `;
                    }
                }
                
                // Update Total Tasks Card
                const tasksCard = document.querySelector('.bg-gradient-to-br.from-orange-500');
                if (tasksCard) {
                    const valueEl = tasksCard.querySelector('.text-4xl');
                    if (valueEl) valueEl.textContent = new Intl.NumberFormat().format(data.totalTasks || 0);
                    
                    const deltaValue = data.totalTasksDelta?.value || 0;
                    const deltaPercent = data.totalTasksDelta?.percent;
                    const deltaPositive = deltaValue >= 0;
                    const deltaEl = tasksCard.querySelector('.text-right .flex.items-center');
                    if (deltaEl) {
                        deltaEl.className = `flex items-center gap-1 ${deltaPositive ? 'text-green-200' : 'text-red-200'}`;
                        deltaEl.innerHTML = `
                            <i class="fas ${deltaPositive ? 'fa-arrow-up' : 'fa-arrow-down'} text-xs"></i>
                            <span class="text-sm font-semibold">
                                ${deltaPositive ? '+' : ''}${new Intl.NumberFormat().format(deltaValue)}
                                ${deltaPercent !== null ? `(${deltaPositive ? '+' : ''}${deltaPercent}%)` : ''}
                            </span>
                        `;
                    }
                }
                
                // Update Total Incidents Card
                const incidentsCard = document.querySelector('.bg-gradient-to-br.from-red-500');
                if (incidentsCard) {
                    const valueEl = incidentsCard.querySelector('.text-4xl');
                    if (valueEl) valueEl.textContent = new Intl.NumberFormat().format(data.totalIncidents || 0);
                    
                    const deltaValue = data.totalIncidentsDelta?.value || 0;
                    const deltaPercent = data.totalIncidentsDelta?.percent;
                    const deltaPositive = deltaValue >= 0;
                    const deltaEl = incidentsCard.querySelector('.text-right .flex.items-center');
                    if (deltaEl) {
                        deltaEl.className = `flex items-center gap-1 ${deltaPositive ? 'text-green-200' : 'text-red-200'}`;
                        deltaEl.innerHTML = `
                            <i class="fas ${deltaPositive ? 'fa-arrow-up' : 'fa-arrow-down'} text-xs"></i>
                            <span class="text-sm font-semibold">
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
            
            function updateAlerts(alerts) {
                const alertsSection = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-3');
                if (!alertsSection || !alerts || !Array.isArray(alerts)) return;
                
                const alertStyles = {
                    'critical': 'bg-red-50 border-red-200 text-red-800',
                    'warning': 'bg-amber-50 border-amber-200 text-amber-800',
                    'info': 'bg-blue-50 border-blue-200 text-blue-800',
                    'success': 'bg-emerald-50 border-emerald-200 text-emerald-800',
                };
                
                const iconStyles = {
                    'critical': 'fa-triangle-exclamation text-red-600',
                    'warning': 'fa-circle-exclamation text-amber-600',
                    'info': 'fa-info-circle text-blue-600',
                    'success': 'fa-circle-check text-emerald-600',
                };
                
                alertsSection.innerHTML = alerts.map(alert => {
                    const style = alertStyles[alert.level] || alertStyles['info'];
                    const icon = iconStyles[alert.level] || iconStyles['info'];
                    return `
                        <div class="bg-white rounded-xl border-2 ${style} p-4 flex items-start gap-3">
                            <i class="fas ${icon} text-lg mt-0.5"></i>
                            <p class="text-sm font-medium flex-1">${alert.message}</p>
                        </div>
                    `;
                }).join('');
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
                    const startDateInput = document.getElementById('start_date');
                    const endDateInput = document.getElementById('end_date');
                    
                    const params = new URLSearchParams({
                        segment: this.value,
                        period: periodSelect ? periodSelect.value : 'last_30_days',
                        start_date: startDateInput ? startDateInput.value : '',
                        end_date: endDateInput ? endDateInput.value : ''
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
                const startDateInput = document.getElementById('start_date');
                const endDateInput = document.getElementById('end_date');
                
                const params = new URLSearchParams({
                    segment: segment,
                    period: periodSelect ? periodSelect.value : 'last_30_days',
                    start_date: startDateInput ? startDateInput.value : '',
                    end_date: endDateInput ? endDateInput.value : ''
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
                                height: 320,
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
                        height: 300,
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
                        height: 300,
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
                        height: 300
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
                        height: 300,
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
                        height: 320,
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
