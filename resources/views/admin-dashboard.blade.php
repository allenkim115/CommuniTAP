<x-admin-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            <!-- 🔹 Dashboard Section -->
            <section class="backdrop-blur-xl bg-white/80 dark:bg-gray-800/80 p-8 rounded-3xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300">

                <!-- Header: Title + PDF Button -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
                    <div>
                        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                            Admin Dashboard
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Real-time insights and system overview</p>
                    </div>
                    <a href="{{ route('admin.dashboard.pdf') }}"
                       class="group bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl font-medium flex items-center gap-2.5 shadow-lg hover:shadow-red-600/25 transition-all duration-200 transform hover:-translate-y-0.5">
                        <i class="fas fa-file-pdf text-sm"></i>
                        <span>Download Report</span>
                    </a>
                </div>

                <!-- Filters -->
                <div class="mb-8 w-full">
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-col lg:flex-row gap-4 items-start lg:items-end">
                        <div class="flex flex-col">
                            <label for="period-select" class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Period</label>
                            <select id="period-select" name="period" class="rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 text-sm px-4 py-2 focus:ring-2 focus:ring-orange-400">
                                @foreach($periodOptions as $key => $label)
                                    <option value="{{ $key }}" @selected($selectedPeriod === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="custom-range-fields" class="flex flex-col sm:flex-row gap-4 hidden">
                            <div class="flex flex-col">
                                <label for="start_date" class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Start date</label>
                                <input type="date" id="start_date" name="start_date" value="{{ $filterStartDate }}" class="rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 text-sm px-4 py-2 focus:ring-2 focus:ring-orange-400">
                            </div>
                            <div class="flex flex-col">
                                <label for="end_date" class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">End date</label>
                                <input type="date" id="end_date" name="end_date" value="{{ $filterEndDate }}" class="rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 text-sm px-4 py-2 focus:ring-2 focus:ring-orange-400">
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-md hover:shadow-lg transition">
                                Apply
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl text-sm font-semibold text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                Reset
                            </a>
                        </div>
                        <input type="hidden" name="segment" value="{{ $selectedSegment }}">
                    </form>
                    <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                        Showing data for <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $rangeSummary['label'] }}</span>
                        ({{ $rangeSummary['current_start']->format('M d, Y') }} – {{ $rangeSummary['current_end']->format('M d, Y') }}).
                        Compared with {{ $rangeSummary['previous_start']->format('M d, Y') }} – {{ $rangeSummary['previous_end']->format('M d, Y') }}.
                    </p>
                </div>

                @php
                    $alertStyles = [
                        'critical' => [
                            'wrapper' => 'border border-red-200 bg-red-50 text-red-800 dark:border-red-500/40 dark:bg-red-900/20 dark:text-red-200',
                            'badge' => 'bg-red-100 text-red-600 dark:bg-red-500/30 dark:text-red-100',
                            'icon' => 'text-red-500 dark:text-red-300 fa-triangle-exclamation',
                            'label' => 'Critical',
                        ],
                        'warning' => [
                            'wrapper' => 'border border-amber-200 bg-amber-50 text-amber-800 dark:border-amber-500/40 dark:bg-amber-900/20 dark:text-amber-200',
                            'badge' => 'bg-amber-100 text-amber-600 dark:bg-amber-500/30 dark:text-amber-100',
                            'icon' => 'text-amber-500 dark:text-amber-300 fa-circle-exclamation',
                            'label' => 'Warning',
                        ],
                        'info' => [
                            'wrapper' => 'border border-blue-200 bg-blue-50 text-blue-800 dark:border-blue-500/40 dark:bg-blue-900/20 dark:text-blue-200',
                            'badge' => 'bg-blue-100 text-blue-600 dark:bg-blue-500/30 dark:text-blue-100',
                            'icon' => 'text-blue-500 dark:text-blue-300 fa-info-circle',
                            'label' => 'Heads-up',
                        ],
                        'success' => [
                            'wrapper' => 'border border-emerald-200 bg-emerald-50 text-emerald-800 dark:border-emerald-500/40 dark:bg-emerald-900/20 dark:text-emerald-200',
                            'badge' => 'bg-emerald-100 text-emerald-600 dark:bg-emerald-500/30 dark:text-emerald-100',
                            'icon' => 'text-emerald-500 dark:text-emerald-300 fa-circle-check',
                            'label' => 'All clear',
                        ],
                    ];
                @endphp

                @if(!empty($alerts))
                    <div class="grid grid-cols-1 md:grid-cols-{{ min(count($alerts), 3) }} gap-4 mb-8">
                        @foreach($alerts as $alert)
                            @php
                                $style = $alertStyles[$alert['level']] ?? $alertStyles['info'];
                            @endphp
                            <div class="p-4 rounded-2xl shadow-sm backdrop-blur-sm {{ $style['wrapper'] }}">
                                <div class="flex items-start gap-3">
                                    <div class="mt-1">
                                        <i class="fas {{ $style['icon'] }}"></i>
                                    </div>
                                    <div class="flex-1">
                                        <span class="inline-flex items-center text-xs font-semibold px-2 py-1 rounded-full uppercase tracking-wide {{ $style['badge'] }}">
                                            {{ $style['label'] }}
                                        </span>
                                        <p class="mt-2 text-sm leading-relaxed">
                                            {{ $alert['message'] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Overview Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
                    <!-- Total Users -->
                    <div class="group relative bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="absolute inset-0 bg-white/10 rounded-2xl blur-xl group-hover:blur-2xl transition"></div>
                        <div class="relative z-10">
                            <div class="text-5xl font-black text-white">{{ number_format($totalUsers) }}</div>
                            <p class="text-blue-100 font-semibold mt-1 text-sm">Total Users · {{ $rangeSummary['label'] }}</p>
                            @php
                                $usersDeltaValue = $totalUsersDelta['value'] ?? 0;
                                $usersDeltaPercent = $totalUsersDelta['percent'];
                                $usersDeltaPositive = $usersDeltaValue >= 0;
                            @endphp
                            <div class="mt-3 flex items-center gap-2 text-xs font-medium {{ $usersDeltaPositive ? 'text-green-100' : 'text-red-100' }}">
                                <i class="fas {{ $usersDeltaPositive ? 'fa-arrow-up text-green-200' : 'fa-arrow-down text-red-200' }}"></i>
                                <span>
                                    {{ $usersDeltaPositive ? '+' : '' }}{{ number_format($usersDeltaValue) }}
                                    @if(!is_null($usersDeltaPercent))
                                        ({{ $usersDeltaPositive ? '+' : '' }}{{ $usersDeltaPercent }}%)
                                    @else
                                        (new period)
                                    @endif
                                    vs previous
                                </span>
                            </div>
                            <a href="{{ route('admin.users.index') }}"
                               class="text-white/90 hover:text-white font-medium text-sm mt-3 inline-flex items-center gap-1 transition">
                                Manage Users <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Total Tasks -->
                    <div class="group relative bg-gradient-to-br from-orange-500 to-orange-600 p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="absolute inset-0 bg-white/10 rounded-2xl blur-xl group-hover:blur-2xl transition"></div>
                        <div class="relative z-10">
                            <div class="text-5xl font-black text-white">{{ number_format($totalTasks) }}</div>
                            <p class="text-orange-100 font-semibold mt-1 text-sm">Tasks Created · {{ $rangeSummary['label'] }}</p>
                            @php
                                $tasksDeltaValue = $totalTasksDelta['value'] ?? 0;
                                $tasksDeltaPercent = $totalTasksDelta['percent'];
                                $tasksDeltaPositive = $tasksDeltaValue >= 0;
                            @endphp
                            <div class="mt-3 flex items-center gap-2 text-xs font-medium {{ $tasksDeltaPositive ? 'text-green-100' : 'text-red-100' }}">
                                <i class="fas {{ $tasksDeltaPositive ? 'fa-arrow-up text-green-200' : 'fa-arrow-down text-red-200' }}"></i>
                                <span>
                                    {{ $tasksDeltaPositive ? '+' : '' }}{{ number_format($tasksDeltaValue) }}
                                    @if(!is_null($tasksDeltaPercent))
                                        ({{ $tasksDeltaPositive ? '+' : '' }}{{ $tasksDeltaPercent }}%)
                                    @else
                                        (new period)
                                    @endif
                                    vs previous
                                </span>
                            </div>
                            <a href="{{ route('admin.tasks.index') }}"
                               class="text-white/90 hover:text-white font-medium text-sm mt-3 inline-flex items-center gap-1 transition">
                                View Tasks <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Total Incidents -->
                    <div class="group relative bg-gradient-to-br from-purple-500 to-purple-600 p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="absolute inset-0 bg-white/10 rounded-2xl blur-xl group-hover:blur-2xl transition"></div>
                        <div class="relative z-10">
                            <div class="text-5xl font-black text-white">{{ number_format($totalIncidents) }}</div>
                            <p class="text-purple-100 font-semibold mt-1 text-sm">Incident Reports · {{ $rangeSummary['label'] }}</p>
                            @php
                                $incidentsDeltaValue = $totalIncidentsDelta['value'] ?? 0;
                                $incidentsDeltaPercent = $totalIncidentsDelta['percent'];
                                $incidentsDeltaPositive = $incidentsDeltaValue >= 0;
                            @endphp
                            <div class="mt-3 flex items-center gap-2 text-xs font-medium {{ $incidentsDeltaPositive ? 'text-green-100' : 'text-red-100' }}">
                                <i class="fas {{ $incidentsDeltaPositive ? 'fa-arrow-up text-green-200' : 'fa-arrow-down text-red-200' }}"></i>
                                <span>
                                    {{ $incidentsDeltaPositive ? '+' : '' }}{{ number_format($incidentsDeltaValue) }}
                                    @if(!is_null($incidentsDeltaPercent))
                                        ({{ $incidentsDeltaPositive ? '+' : '' }}{{ $incidentsDeltaPercent }}%)
                                    @else
                                        (new period)
                                    @endif
                                    vs previous
                                </span>
                            </div>
                            <a href="{{ route('admin.incident-reports.index') }}"
                               class="text-white/90 hover:text-white font-medium text-sm mt-3 inline-flex items-center gap-1 transition">
                                View Incidents <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Business Analytics from Reports -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <!-- Task Completion Rate -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 p-4 rounded-xl border border-green-200 dark:border-green-700">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-semibold text-gray-700 dark:text-gray-300">Task Completion Rate</p>
                            <span class="text-xs text-green-600 dark:text-green-400">📊</span>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $taskCompletionRate ?? 0 }}%</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Based on all assignments</p>
                    </div>

                    <!-- Active Volunteers -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 p-4 rounded-xl border border-blue-200 dark:border-blue-700">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-semibold text-gray-700 dark:text-gray-300">Active Volunteers</p>
                            <span class="text-xs text-blue-600 dark:text-blue-400">👥</span>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $activeVolunteers ?? 0 }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Completed at least 1 task</p>
                    </div>

                    <!-- Engagement Rate -->
                    <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 p-4 rounded-xl border border-orange-200 dark:border-orange-700">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-semibold text-gray-700 dark:text-gray-300">Engagement Rate</p>
                            <span class="text-xs text-orange-600 dark:text-orange-400">📈</span>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $engagementRate ?? 0 }}%</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Users with assigned tasks</p>
                    </div>

                    <!-- Points Awarded -->
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 p-4 rounded-xl border border-purple-200 dark:border-purple-700">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-semibold text-gray-700 dark:text-gray-300">Points Awarded</p>
                            <span class="text-xs text-purple-600 dark:text-purple-400">⭐</span>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalPointsAwarded ?? 0) }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">From completed tasks</p>
                    </div>
                </div>

                <!-- Segmented Insights -->
                <section class="backdrop-blur-xl bg-white/80 dark:bg-gray-800/80 p-6 rounded-3xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 mb-10">
                    <div class="flex flex-col lg:flex-row justify-between gap-6 mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <i class="fas fa-layer-group text-indigo-500"></i>
                                Segmented Insights
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ $segmentationData['description'] ?? 'Explore key metrics segmented by task location or reward sponsor.' }}
                            </p>
                        </div>
                        <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-col sm:flex-row gap-4 items-start sm:items-end">
                            <div class="flex flex-col">
                                <label for="segment-select" class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Segment by</label>
                                <select id="segment-select" name="segment" class="rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 text-sm px-4 py-2 focus:ring-2 focus:ring-indigo-400">
                                    <option value="task_location" @selected($selectedSegment === 'task_location')>Task Location</option>
                                    <option value="reward_sponsor" @selected($selectedSegment === 'reward_sponsor')>Reward Sponsor</option>
                                </select>
                            </div>
                            <input type="hidden" name="period" value="{{ $selectedPeriod }}">
                            <input type="hidden" name="start_date" value="{{ $filterStartDate }}">
                            <input type="hidden" name="end_date" value="{{ $filterEndDate }}">
                            <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-md hover:shadow-lg transition">
                                Update
                            </button>
                        </form>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-white/70 dark:bg-gray-900/40 backdrop-blur-sm p-6 rounded-2xl border border-gray-200/40 dark:border-gray-700/40 shadow-sm relative">
                            @if(!empty($segmentationData['labels']))
                                <canvas id="segmentDistributionChart" class="mt-2"></canvas>
                            @else
                                <div class="flex items-center justify-center h-48 text-sm text-gray-500 dark:text-gray-400">
                                    No segmentation data available for this period.
                                </div>
                            @endif
                        </div>
                        <div class="bg-white/70 dark:bg-gray-900/40 backdrop-blur-sm p-6 rounded-2xl border border-gray-200/40 dark:border-gray-700/40 shadow-sm">
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wide mb-4">
                                Segment Breakdown
                            </h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead class="text-gray-500 dark:text-gray-400 uppercase">
                                        <tr>
                                            <th class="py-2 pr-4 text-left font-medium">Segment</th>
                                            <th class="py-2 pr-4 text-right font-medium">Count</th>
                                            <th class="py-2 text-right font-medium">Share</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-gray-700 dark:text-gray-200">
                                        @forelse($segmentationData['table'] ?? [] as $row)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
                                                <td class="py-2 pr-4 font-medium">{{ $row['label'] }}</td>
                                                <td class="py-2 pr-4 text-right">{{ number_format($row['count']) }}</td>
                                                <td class="py-2 text-right">{{ $row['share'] ?? 0 }}%</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="py-6 text-center text-gray-500 dark:text-gray-400">
                                                    No data available. Try adjusting the date filters or segment.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Charts -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- User Growth Line Chart -->
                    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm p-6 rounded-2xl shadow-lg border border-gray-200/30 dark:border-gray-700/30">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">User Growth Trend</h3>
                            <span class="text-xs text-green-600 dark:text-green-400 font-medium bg-green-100 dark:bg-green-900/30 px-2 py-1 rounded-full">Last 6 months</span>
                        </div>
                        <canvas id="userGrowthChart" class="mt-2"></canvas>
                    </div>

                    <!-- Task Completion Trend -->
                    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm p-6 rounded-2xl shadow-lg border border-gray-200/30 dark:border-gray-700/30">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Task Completion Trend</h3>
                            <span class="text-xs text-blue-600 dark:text-blue-400 font-medium bg-blue-100 dark:bg-blue-900/30 px-2 py-1 rounded-full">Last 6 months</span>
                        </div>
                        <canvas id="taskCompletionChart" class="mt-2"></canvas>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Task Status Chart -->
                    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm p-6 rounded-2xl shadow-lg border border-gray-200/30 dark:border-gray-700/30">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Task Status Distribution</h3>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Current Status</span>
                        </div>
                        <canvas id="taskDistributionChart" class="mt-2"></canvas>
                        <div class="flex flex-wrap gap-4 mt-4 text-xs font-medium text-gray-600 dark:text-gray-300">
                            <span class="inline-flex items-center gap-2">
                                <span class="w-4 h-1 rounded-full bg-[#22c55e]"></span> Completed
                            </span>
                            <span class="inline-flex items-center gap-2">
                                <span class="w-4 h-1 rounded-full bg-[#f97316]"></span> Pending
                            </span>
                            <span class="inline-flex items-center gap-2">
                                <span class="w-4 h-1 rounded-full bg-[#3b82f6]"></span> Published
                            </span>
                            <span class="inline-flex items-center gap-2">
                                <span class="w-4 h-1 rounded-full bg-[#9ca3af]"></span> Inactive
                            </span>
                        </div>
                    </div>

                    <!-- Volunteer Engagement by Task Type -->
                    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm p-6 rounded-2xl shadow-lg border border-gray-200/30 dark:border-gray-700/30">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Volunteer Engagement by Task Type</h3>
                            <span class="text-xs text-indigo-600 dark:text-indigo-400 font-medium bg-indigo-100 dark:bg-indigo-900/40 px-2 py-1 rounded-full">Assignments vs Completed</span>
                        </div>
                        <canvas id="taskTypeEngagementChart" class="mt-2"></canvas>
                    </div>
                </div>

            </section>

            <!-- 🔹 Business Analytics from Reports Section -->
            <section class="backdrop-blur-xl bg-white/80 dark:bg-gray-800/80 p-6 rounded-3xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 mb-10">
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fas fa-chart-line text-blue-500"></i>
                        Business Analytics from Reports
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Key performance indicators derived from generated reports</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- From Volunteer Report -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 p-6 rounded-xl border border-blue-200 dark:border-blue-700">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white">Volunteer Report</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Analytics</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Active Volunteers</span>
                                <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $activeVolunteers ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Engagement Rate</span>
                                <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $engagementRate ?? 0 }}%</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Users with Tasks</span>
                                <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $usersWithTasks ?? 0 }}</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.reports.volunteer') }}" 
                           class="mt-4 inline-flex items-center text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                            View Full Report <i class="fas fa-arrow-right ml-1 text-xs"></i>
                        </a>
                    </div>

                    <!-- From Task Report -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 p-6 rounded-xl border border-green-200 dark:border-green-700">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-tasks text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white">Task Report</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Analytics</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Completion Rate</span>
                                <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ $taskCompletionRate ?? 0 }}%</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Completed Tasks</span>
                                <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $completedAssignments ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Points Awarded</span>
                                <span class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($totalPointsAwarded ?? 0) }}</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.reports.task') }}" 
                           class="mt-4 inline-flex items-center text-sm text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 font-medium">
                            View Full Report <i class="fas fa-arrow-right ml-1 text-xs"></i>
                        </a>
                    </div>

                    <!-- From Task Chain Report -->
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 p-6 rounded-xl border border-purple-200 dark:border-purple-700">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-link text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white">Task Chain Report</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Analytics</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Task Chain Links</span>
                                <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $taskChainNominations ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Chain Engagement</span>
                                <span class="text-lg font-bold text-purple-600 dark:text-purple-400">{{ $chainEngagementRate ?? 0 }}%</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Total Nominations</span>
                                <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $totalNominations ?? 0 }}</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.reports.task-chain') }}" 
                           class="mt-4 inline-flex items-center text-sm text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300 font-medium">
                            View Full Report <i class="fas fa-arrow-right ml-1 text-xs"></i>
                        </a>
                    </div>
                </div>
            </section>
            <section class="backdrop-blur-xl bg-white/80 dark:bg-gray-800/80 p-6 rounded-3xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 mb-10">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-chart-line text-emerald-500"></i>
                            Retention & Incident Insights
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Track volunteer retention cohorts alongside operational health of incident resolution.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    @forelse($retentionMetrics as $metric)
                        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 p-5 rounded-2xl border border-emerald-200 dark:border-emerald-700 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-300">{{ $metric['label'] }}</p>
                            <p class="mt-2 text-3xl font-black text-gray-900 dark:text-white">{{ $metric['retention_rate'] }}%</p>
                            <div class="mt-3 space-y-1 text-sm text-gray-600 dark:text-gray-300">
                                <p><span class="font-semibold text-gray-800 dark:text-gray-100">{{ number_format($metric['cohort_size']) }}</span> new users</p>
                                <p><span class="font-semibold text-emerald-600 dark:text-emerald-300">{{ number_format($metric['retained']) }}</span> active within {{ $metric['lookback_days'] }} days</p>
                            </div>
                        </div>
                    @empty
                        <div class="md:col-span-3 text-sm text-gray-500 dark:text-gray-400">
                            No retention data available. Encourage new sign-ups to populate cohort metrics.
                        </div>
                    @endforelse
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-1 bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 p-5 rounded-2xl border border-red-200 dark:border-red-700 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wide text-red-600 dark:text-red-300">Incident Resolution</p>
                        <div class="mt-3 space-y-4">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Average Resolution Time</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ $incidentInsights['average_resolution_hours'] !== null ? $incidentInsights['average_resolution_hours'].'h' : '—' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Median Resolution Time</p>
                                <p class="text-xl font-semibold text-gray-900 dark:text-white">
                                    {{ $incidentInsights['median_resolution_hours'] !== null ? $incidentInsights['median_resolution_hours'].'h' : '—' }}
                                </p>
                            </div>
                            <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-300">
                                <span>Open Incidents</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $incidentInsights['open_incidents'] }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-red-600 dark:text-red-300">
                                <span>Overdue (&gt;7 days)</span>
                                <span class="font-semibold">{{ $incidentInsights['overdue_incidents'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-2 bg-white/70 dark:bg-gray-900/40 backdrop-blur-sm p-5 rounded-2xl border border-gray-200/40 dark:border-gray-700/40 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wide">
                                Recent Resolutions
                            </h4>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $incidentInsights['resolved_count'] }} resolved this period
                            </span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="text-gray-500 dark:text-gray-400 uppercase">
                                    <tr>
                                        <th class="py-2 pr-4 text-left font-medium">Incident Type</th>
                                        <th class="py-2 pr-4 text-left font-medium">Action Taken</th>
                                        <th class="py-2 text-right font-medium">Resolution</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-700 dark:text-gray-200">
                                    @forelse($incidentInsights['recent_resolutions'] ?? [] as $recent)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
                                            <td class="py-2 pr-4 font-medium">
                                                {{ \Illuminate\Support\Str::headline($recent['incident_type'] ?? 'Unknown') }}
                                            </td>
                                            <td class="py-2 pr-4">
                                                {{ $recent['action_taken'] ? \Illuminate\Support\Str::headline($recent['action_taken']) : '—' }}
                                            </td>
                                            <td class="py-2 text-right">
                                                <span class="block">{{ $recent['resolved_at'] ?? '—' }}</span>
                                                @if(!empty($recent['resolution_hours']))
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $recent['resolution_hours'] }}h</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="py-6 text-center text-gray-500 dark:text-gray-400">
                                                No incidents were resolved during this period.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </section>
            <!-- 🔹 Leaderboard Section -->
            <section class="backdrop-blur-xl bg-white/80 dark:bg-gray-800/80 p-6 rounded-3xl shadow-xl border border-gray-200/50 dark:border-gray-700/50">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="text-yellow-500">🏆</span> Top Performers
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Leaderboard by points</p>
                    </div>
                    <button class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-5 py-2 rounded-xl text-sm font-medium shadow-md hover:shadow-lg transition-all duration-200 transform hover:scale-105">
                        View All
                    </button>
                </div>

                <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50/80 dark:bg-gray-700/50 backdrop-blur-sm">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Rank
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Name
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Points
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($topPerformers ?? [] as $index => $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($index === 0)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-r from-orange-400 to-orange-500 text-white font-bold text-sm shadow-md">
                                                {{ $index + 1 }}
                                            </span>
                                        @elseif($index === 1)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-r from-orange-300 to-orange-400 text-white font-bold text-sm shadow-md">
                                                {{ $index + 1 }}
                                            </span>
                                        @elseif($index === 2)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-r from-yellow-400 to-amber-500 text-white font-bold text-sm shadow-md">
                                                {{ $index + 1 }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-300 text-gray-700 font-bold text-sm shadow-md">
                                                {{ $index + 1 }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100 font-medium">
                                        {{ $user->fullName }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300">
                                            {{ number_format($user->points) }} pts
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No performers data available
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

        </div>
    </div>

    <!-- 🔹 Enhanced Chart.js with Modern Styling -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const periodSelect = document.getElementById('period-select');
            const customRangeFields = document.getElementById('custom-range-fields');

            if (periodSelect && customRangeFields) {
                const toggleCustomFields = () => {
                    if (periodSelect.value === 'custom') {
                        customRangeFields.classList.remove('hidden');
                    } else {
                        customRangeFields.classList.add('hidden');
                    }
                };

                periodSelect.addEventListener('change', toggleCustomFields);
                toggleCustomFields();
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        Chart.defaults.font.family = "'Inter', system-ui, sans-serif";
        Chart.defaults.color = '#6b7280';

        const segmentationData = @json($segmentationData ?? []);
        const taskTypeEngagement = @json($taskTypeEngagement ?? []);

        const filterState = {
            period: @json($selectedPeriod ?? null),
            start_date: @json($filterStartDate ?? null),
            end_date: @json($filterEndDate ?? null),
            segment: @json($selectedSegment ?? null),
        };

        const buildUrl = (baseRoute, extraParams = {}) => {
            const url = new URL(baseRoute, window.location.origin);

            Object.entries(filterState).forEach(([key, value]) => {
                if (value) {
                    url.searchParams.set(key, value);
                }
            });

            Object.entries(extraParams).forEach(([key, value]) => {
                if (value) {
                    url.searchParams.set(key, value);
                }
            });

            return url.toString();
        };

        const userGrowthLabels = @json($labels ?? []);
        const userGrowthValues = @json($userGrowth ?? []);
        const taskCompletionLabels = @json($taskCompletionLabels ?? []);
        const taskCompletionValues = @json($taskCompletionTrend ?? []);

        const userGrowthChartCtx = document.getElementById('userGrowthChart');
        const taskCompletionChartCtx = document.getElementById('taskCompletionChart');
        const taskDistributionChartCtx = document.getElementById('taskDistributionChart');
        const segmentChartCtx = document.getElementById('segmentDistributionChart');
        const taskTypeChartCtx = document.getElementById('taskTypeEngagementChart');

        if (userGrowthChartCtx) {
            const userGrowthChart = new Chart(userGrowthChartCtx.getContext('2d'), {
            type: 'line',
            data: {
                    labels: userGrowthLabels,
                datasets: [{
                    label: 'Users Joined',
                        data: userGrowthValues,
                    borderColor: '#f97316',
                    backgroundColor: 'rgba(249, 115, 22, 0.15)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#f97316',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, position: 'top' }
                    },
                    onClick: (event, elements) => {
                        let labelParam = null;
                        if (elements && elements.length) {
                            const index = elements[0].index;
                            labelParam = userGrowthChart.data.labels[index];
                        }
                        const params = labelParam ? { label: labelParam } : {};
                        const targetUrl = buildUrl("{{ route('admin.dashboard.chart-details', ['chart' => 'user-growth']) }}", params);
                        window.location.href = targetUrl;
                    }
                }
            });
        }

        if (taskCompletionChartCtx) {
            const taskCompletionChart = new Chart(taskCompletionChartCtx.getContext('2d'), {
            type: 'line',
            data: {
                    labels: taskCompletionLabels,
                datasets: [{
                    label: 'Tasks Completed',
                        data: taskCompletionValues,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.15)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, position: 'top' }
                    },
                    onClick: (event, elements) => {
                        let labelParam = null;
                        if (elements && elements.length) {
                            const index = elements[0].index;
                            labelParam = taskCompletionChart.data.labels[index];
                        }
                        const params = labelParam ? { label: labelParam } : {};
                        const targetUrl = buildUrl("{{ route('admin.dashboard.chart-details', ['chart' => 'task-completion']) }}", params);
                        window.location.href = targetUrl;
                    }
                }
            });
        }

        if (taskDistributionChartCtx) {
            const taskStatusChart = new Chart(taskDistributionChartCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Pending', 'Published', 'Inactive'],
                datasets: [{
                    data: [{{ $tasksCompleted ?? 0 }}, {{ $tasksPending ?? 0 }}, {{ $tasksPublished ?? 0 }}, {{ $tasksInactive ?? 0 }}],
                    backgroundColor: ['#22c55e', '#f97316', '#3b82f6', '#9ca3af'],
                    borderColor: '#fff',
                    borderWidth: 3,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { position: 'bottom' }
                },
                onClick: (event, elements) => {
                    let labelParam = null;
                    if (elements && elements.length) {
                        const segmentIndex = elements[0].index;
                        labelParam = taskStatusChart.data.labels[segmentIndex];
                    }
                    const params = labelParam ? { label: labelParam } : {};
                    const targetUrl = buildUrl("{{ route('admin.dashboard.chart-details', ['chart' => 'task-status']) }}", params);
                    window.location.href = targetUrl;
                }
            }
        });
        }

        if (segmentChartCtx && Array.isArray(segmentationData.labels) && segmentationData.labels.length > 0) {
            const segmentChart = new Chart(segmentChartCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: segmentationData.labels,
                    datasets: [{
                        label: segmentationData.title || 'Segment Distribution',
                        data: segmentationData.values || [],
                        backgroundColor: '#6366f1',
                        borderRadius: 8,
                        maxBarThickness: 40,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            ticks: { color: '#6b7280' },
                            grid: { display: false }
                        },
                        y: {
                            ticks: { beginAtZero: true },
                            grid: { color: 'rgba(148, 163, 184, 0.2)' }
                        }
                    },
                    onClick: (event, elements) => {
                        let labelParam = null;
                        if (elements && elements.length) {
                            const index = elements[0].index;
                            labelParam = segmentChart.data.labels[index];
                        }
                        const params = labelParam ? { label: labelParam } : {};
                        const targetUrl = buildUrl("{{ route('admin.dashboard.chart-details', ['chart' => 'segmentation']) }}", params);
                        window.location.href = targetUrl;
                    }
                }
            });
        }

        if (taskTypeChartCtx && taskTypeEngagement && Array.isArray(taskTypeEngagement.labels) && taskTypeEngagement.labels.length > 0) {
            new Chart(taskTypeChartCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: taskTypeEngagement.labels,
                    datasets: [
                        {
                            label: 'Assignments',
                            data: taskTypeEngagement.assignments || [],
                            backgroundColor: 'rgba(59, 130, 246, 0.25)',
                            borderColor: '#3b82f6',
                            borderWidth: 2,
                            borderRadius: 6,
                        },
                        {
                            label: 'Completed',
                            data: taskTypeEngagement.completed || [],
                            backgroundColor: 'rgba(34, 197, 94, 0.25)',
                            borderColor: '#22c55e',
                            borderWidth: 2,
                            borderRadius: 6,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            ticks: { color: '#6b7280' },
                            grid: { display: false },
                        },
                        y: {
                            beginAtZero: true,
                            ticks: { color: '#6b7280' },
                            grid: { color: 'rgba(148, 163, 184, 0.2)' },
                        },
                    },
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                afterBody: (items) => {
                                    const index = items[0].dataIndex;
                                    const rate = taskTypeEngagement.completion_rates
                                        ? taskTypeEngagement.completion_rates[index]
                                        : null;
                                    return rate !== null
                                        ? [`Completion Rate: ${rate}%`]
                                        : [];
                                },
                            },
                        },
                    },
                },
            });
        }
    </script>

    <style>
        canvas {
            height: 220px !important;
        }
        @media (max-width: 640px) {
            canvas {
                height: 180px !important;
            }
        }
    </style>
</x-admin-layout>