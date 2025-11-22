<!DOCTYPE html>
<html>
<head>
    <title>Task Performance Report - CommuniTAP</title>
    <style>
        @page {
            margin: 0.5cm 1cm;
            size: A4 landscape;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Times New Roman', serif;
            font-size: 11pt;
            color: #1a1a1a;
            line-height: 1.6;
            padding: 0;
            margin: 0;
        }
        .header {
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 5px;
            margin-bottom: 8px;
            margin-top: 0;
            display: table;
            width: 100%;
        }
        .header-content {
            display: table-row;
        }
        .logo-cell {
            display: table-cell;
            vertical-align: middle;
            width: 150px;
            padding-right: 10px;
            padding-left: 0;
            padding-top: 0;
            padding-bottom: 0;
            text-align: center;
        }
        .logo-cell img {
            max-width: 140px;
            max-height: 140px;
            width: 140px;
            height: auto;
            display: inline-block;
            margin: 0;
            vertical-align: middle;
        }
        .title-cell {
            display: table-cell;
            vertical-align: middle;
            padding-left: 5px;
        }
        .header h1 {
            font-size: 16pt;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 2px;
            margin-top: 0;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }
        .header .subtitle {
            font-size: 8pt;
            color: #555;
            margin-bottom: 3px;
            line-height: 1.2;
        }
        .header-info {
            font-size: 7pt;
            color: #666;
            line-height: 1.3;
            margin-top: 2px;
        }
        .header-info strong {
            color: #2c3e50;
            font-weight: bold;
        }
        h2 {
            font-size: 12pt;
            font-weight: bold;
            color: #2c3e50;
            margin-top: 8px;
            margin-bottom: 3px;
            padding-bottom: 2px;
            border-bottom: 1px solid #e0e0e0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        h2:first-of-type {
            margin-top: 5px;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 8px;
            margin-top: 0;
            border-collapse: separate;
            border-spacing: 5px;
        }
        .stat-item {
            display: table-cell;
            padding: 8px 10px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-left: 3px solid #2c3e50;
            vertical-align: top;
            width: 20%;
        }
        .stat-label {
            font-size: 8pt;
            color: #666;
            margin-bottom: 3px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .stat-value {
            font-size: 14pt;
            font-weight: bold;
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            margin-top: 0;
            page-break-inside: auto;
            font-size: 9pt;
        }
        thead {
            background: #2c3e50;
            color: white;
            display: table-header-group;
        }
        thead tr {
            page-break-after: avoid;
            page-break-inside: avoid;
        }
        tbody {
            display: table-row-group;
        }
        tbody tr {
            page-break-inside: avoid;
        }
        th {
            padding: 6px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border: 1px solid #1a252f;
        }
        td {
            padding: 6px 8px;
            border: 1px solid #dee2e6;
            vertical-align: top;
        }
        tbody tr {
            border-bottom: 1px solid #e9ecef;
        }
        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .no-data {
            text-align: center;
            padding: 30px;
            color: #999;
            font-style: italic;
            border: 1px dashed #ccc;
            margin: 20px 0;
        }
        .completion-rate {
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 3px;
        }
        .completion-rate.high {
            color: #155724;
            background: #d4edda;
        }
        .completion-rate.medium {
            color: #856404;
            background: #fff3cd;
        }
        .completion-rate.low {
            color: #721c24;
            background: #f8d7da;
        }
        .insight-box {
            background: #f8f9fa;
            border-left: 4px solid #2c3e50;
            padding: 8px 12px;
            margin: 8px 0;
            font-size: 8pt;
            line-height: 1.4;
        }
        .insight-box strong {
            color: #2c3e50;
            display: block;
            margin-bottom: 3px;
        }
        .performance-tier {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .tier-high {
            background: #d4edda;
            color: #155724;
        }
        .tier-medium {
            background: #fff3cd;
            color: #856404;
        }
        .tier-low {
            background: #f8d7da;
            color: #721c24;
        }
        .metric-comparison {
            font-size: 7pt;
            color: #666;
            margin-top: 2px;
        }
        .metric-comparison.positive {
            color: #155724;
        }
        .metric-comparison.negative {
            color: #721c24;
        }
        .stat-detail {
            font-size: 7pt;
            color: #666;
            margin-top: 2px;
            line-height: 1.2;
        }
        .footer {
            margin-top: 15px;
            padding-top: 10px;
            padding-bottom: 5px;
            border-top: 1px solid #e0e0e0;
            font-size: 7pt;
            color: #666;
            text-align: center;
        }
        .footer p {
            margin-bottom: 3px;
        }
        @media print {
            .page-break {
                page-break-after: always;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div class="logo-cell">
                @php
                    $logoPath = public_path('images/communitaplogo1.svg');
                    $logoSrc = '';
                    
                    if (file_exists($logoPath)) {
                        // Extract embedded PNG from SVG (DomPDF has limited SVG support)
                        $svgContent = file_get_contents($logoPath);
                        // Match xlink:href="data:image/png;base64,..." pattern
                        if (preg_match('/xlink:href="data:image\/png;base64,([^"]+)"/', $svgContent, $matches)) {
                            // Use the embedded PNG directly as base64 data URI
                            $logoSrc = 'data:image/png;base64,' . trim($matches[1]);
                        } else {
                            // Fallback to SVG file path if no embedded image found
                            $logoSrc = $logoPath;
                        }
                    }
                @endphp
                @if($logoSrc)
                    <img src="{{ $logoSrc }}" alt="CommuniTAP Logo" />
                @endif
            </div>
            <div class="title-cell">
                <h1>TASK PERFORMANCE REPORT</h1>
                <div class="subtitle">Comprehensive Task Analysis and Completion Metrics</div>
                <div class="header-info">
            <strong>Report Generated:</strong> {{ now()->format('F d, Y \a\t g:i A') }}<br>
            @if(isset($periodLabel))
            <strong>Reporting Period:</strong> {{ $periodLabel }}<br>
            @endif
            @if($startDate || $endDate)
            <strong>Date Range:</strong> 
            {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('F d, Y') : 'All' }} through 
            {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('F d, Y') : 'All' }}<br>
            @endif
            @if($taskType !== 'all')
            <strong>Task Type Filter:</strong> {{ ucfirst(str_replace('_', ' ', $taskType)) }}<br>
            @endif
            @if($status !== 'all')
            <strong>Status Filter:</strong> {{ ucfirst($status) }}
            @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Overall Statistics -->
    <h2>Summary Statistics</h2>
    @php
        $completionRate = $totalAssignments > 0 ? ($totalCompleted / $totalAssignments) * 100 : 0;
        $avgPointsPerTask = $totalTasks > 0 ? round($totalPointsAwarded / $totalTasks, 1) : 0;
        $avgPointsPerAssignment = $totalAssignments > 0 ? round($totalPointsAwarded / $totalAssignments, 1) : 0;
        $avgAssignmentsPerTask = $totalTasks > 0 ? round($totalAssignments / $totalTasks, 1) : 0;
        $efficiencyScore = $totalAssignments > 0 ? round(($completionRate / 100) * ($totalCompleted / max($totalTasks, 1)), 2) : 0;
    @endphp
    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-label">Total Tasks</div>
            <div class="stat-value">{{ number_format($totalTasks) }}</div>
            <div class="stat-detail">Avg: {{ $avgAssignmentsPerTask }} assignments each</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Total Assignments</div>
            <div class="stat-value">{{ number_format($totalAssignments) }}</div>
            <div class="stat-detail">Avg: {{ $avgPointsPerAssignment }} points per assignment</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Completed Tasks</div>
            <div class="stat-value">{{ number_format($totalCompleted) }}</div>
            <div class="stat-detail">{{ $totalTasks > 0 ? number_format(($totalCompleted / $totalTasks) * 100, 1) : 0 }}% of total tasks</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Total Points Awarded</div>
            <div class="stat-value">{{ number_format($totalPointsAwarded) }}</div>
            <div class="stat-detail">Avg: {{ $avgPointsPerTask }} points per task</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Completion Rate</div>
            <div class="stat-value">{{ number_format($completionRate, 1) }}%</div>
            <div class="stat-detail">
                @if($completionRate >= 70)
                    <span class="metric-comparison positive">Excellent Performance</span>
                @elseif($completionRate >= 50)
                    <span class="metric-comparison">Good Performance</span>
                @else
                    <span class="metric-comparison negative">Needs Improvement</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Key Insights -->
    <h2>Performance Insights & Analysis</h2>
    <div class="insight-box">
        <strong>Overall Performance Assessment:</strong>
        @if($completionRate >= 70)
            Strong completion rate of {{ number_format($completionRate, 1) }}% indicates high volunteer engagement and task effectiveness. The system is performing well above average.
        @elseif($completionRate >= 50)
            Moderate completion rate of {{ number_format($completionRate, 1) }}%. Consider reviewing task difficulty, clarity, or incentives to improve engagement.
        @else
            Low completion rate of {{ number_format($completionRate, 1) }}% requires attention. Review task design, volunteer support, and engagement strategies.
        @endif
    </div>
    <div class="insight-box">
        <strong>Task Efficiency Analysis:</strong>
        @if($avgAssignmentsPerTask >= 5)
            High task distribution with {{ $avgAssignmentsPerTask }} assignments per task on average. Tasks are being widely distributed among volunteers.
        @elseif($avgAssignmentsPerTask >= 2)
            Moderate task distribution ({{ $avgAssignmentsPerTask }} assignments per task). Consider increasing task visibility or assignment frequency.
        @else
            Low task distribution ({{ $avgAssignmentsPerTask }} assignments per task). Tasks may need better promotion or more strategic assignment.
        @endif
    </div>
    <div class="insight-box">
        <strong>Points Distribution:</strong>
        @if($avgPointsPerTask >= 100)
            Generous points allocation ({{ $avgPointsPerTask }} points per task average). This may indicate high-value tasks or strong reward structure.
        @elseif($avgPointsPerTask >= 50)
            Balanced points allocation ({{ $avgPointsPerTask }} points per task). Points distribution appears appropriate for task complexity.
        @else
            Lower points allocation ({{ $avgPointsPerTask }} points per task). Consider reviewing point values to ensure they match task effort and value.
        @endif
    </div>

    <!-- Summary Table -->
    @if(count($taskStats) > 0)
    <h2>Task Performance Analysis</h2>
    @php
        $avgCompletionRate = collect($taskStats)->avg('completionRate');
        $avgAssignedCount = collect($taskStats)->avg('assignedCount');
        $sortedTasks = collect($taskStats)->sortByDesc(function($stat) {
            return $stat['completionRate'] * $stat['completedCount'];
        });
    @endphp
    <table>
        <thead>
            <tr>
                <th style="width: 3%;">#</th>
                <th style="width: 7%;">Task ID</th>
                <th style="width: 22%;">Title</th>
                <th style="width: 10%;">Type</th>
                <th style="width: 8%;" class="text-center">Status</th>
                <th style="width: 7%;" class="text-right">Points</th>
                <th style="width: 7%;" class="text-center">Assigned</th>
                <th style="width: 7%;" class="text-center">Completed</th>
                <th style="width: 8%;" class="text-center">Rate</th>
                <th style="width: 7%;" class="text-right">Total Points</th>
                <th style="width: 8%;" class="text-center">Tier</th>
                <th style="width: 6%;" class="text-center">Rank</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sortedTasks as $index => $stat)
                @php
                    $performanceScore = ($stat['completionRate'] / 100) * $stat['completedCount'];
                    $vsAvgRate = $avgCompletionRate > 0 ? round((($stat['completionRate'] - $avgCompletionRate) / $avgCompletionRate) * 100, 1) : 0;
                    $tier = $stat['completionRate'] >= 70 ? 'high' : ($stat['completionRate'] >= 50 ? 'medium' : 'low');
                    $efficiency = $stat['assignedCount'] > 0 ? round($stat['completedCount'] / $stat['assignedCount'], 2) : 0;
                @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $stat['task']->taskId }}</td>
                <td><strong>{{ Str::limit($stat['task']->title, 28) }}</strong></td>
                <td>{{ ucfirst(str_replace('_', ' ', $stat['task']->task_type)) }}</td>
                <td class="text-center">{{ ucfirst($stat['task']->status) }}</td>
                <td class="text-right">{{ number_format($stat['task']->points_awarded) }}</td>
                <td class="text-center">{{ number_format($stat['assignedCount']) }}</td>
                <td class="text-center">{{ number_format($stat['completedCount']) }}</td>
                <td class="text-center">
                    <span class="completion-rate {{ $tier }}">
                        {{ number_format($stat['completionRate'], 1) }}%
                    </span>
                    @if($vsAvgRate != 0)
                        <div class="metric-comparison {{ $vsAvgRate > 0 ? 'positive' : 'negative' }}">
                            {{ $vsAvgRate > 0 ? '+' : '' }}{{ $vsAvgRate }}%
                        </div>
                    @endif
                </td>
                <td class="text-right"><strong>{{ number_format($stat['totalPointsAwarded']) }}</strong></td>
                <td class="text-center">
                    <span class="performance-tier tier-{{ $tier }}">
                        {{ ucfirst($tier) }}
                    </span>
                </td>
                <td class="text-center">
                    @if($index < 3)
                        <strong>#{{ $index + 1 }}</strong>
                    @else
                        {{ $index + 1 }}
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Performance Distribution -->
    @php
        $highPerforming = collect($taskStats)->filter(function($stat) { return $stat['completionRate'] >= 70; })->count();
        $mediumPerforming = collect($taskStats)->filter(function($stat) { return $stat['completionRate'] >= 50 && $stat['completionRate'] < 70; })->count();
        $lowPerforming = collect($taskStats)->filter(function($stat) { return $stat['completionRate'] < 50; })->count();
        $totalTasksCount = count($taskStats);
    @endphp
    <h3>Performance Distribution</h3>
    <div class="stats-grid" style="margin-top: 5px;">
        <div class="stat-item">
            <div class="stat-label">High Performers (≥70%)</div>
            <div class="stat-value">{{ number_format($highPerforming) }}</div>
            <div class="stat-detail">{{ $totalTasksCount > 0 ? number_format(($highPerforming / $totalTasksCount) * 100, 1) : 0 }}% of tasks</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Medium Performers (50-69%)</div>
            <div class="stat-value">{{ number_format($mediumPerforming) }}</div>
            <div class="stat-detail">{{ $totalTasksCount > 0 ? number_format(($mediumPerforming / $totalTasksCount) * 100, 1) : 0 }}% of tasks</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Low Performers (<50%)</div>
            <div class="stat-value">{{ number_format($lowPerforming) }}</div>
            <div class="stat-detail">{{ $totalTasksCount > 0 ? number_format(($lowPerforming / $totalTasksCount) * 100, 1) : 0 }}% of tasks</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Average Completion Rate</div>
            <div class="stat-value">{{ number_format($avgCompletionRate, 1) }}%</div>
            <div class="stat-detail">Across all tasks</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Top Task Efficiency</div>
            <div class="stat-value">
                @php
                    $topTask = $sortedTasks->first();
                    $topEfficiency = $topTask['assignedCount'] > 0 ? round(($topTask['completedCount'] / $topTask['assignedCount']) * 100, 1) : 0;
                @endphp
                {{ $topEfficiency }}%
            </div>
            <div class="stat-detail">{{ Str::limit($topTask['task']->title, 20) }}</div>
        </div>
    </div>
    @endif

    <div class="footer">
        <p>This report was generated automatically by the CommuniTAP Administrative System.</p>
        <p>For questions or concerns, please contact the system administrator.</p>
    </div>
</body>
</html>
