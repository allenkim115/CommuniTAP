<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard Report - CommuniTAP</title>
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
        h3 {
            font-size: 10pt;
            font-weight: bold;
            color: #555;
            margin-top: 8px;
            margin-bottom: 5px;
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
        .stat-detail {
            font-size: 7pt;
            color: #666;
            margin-top: 2px;
            line-height: 1.2;
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
        tbody tr:hover {
            background-color: #e9ecef;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
            border: 1px dashed #ccc;
            margin: 25px 0;
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
        .page-break {
            page-break-after: always;
        }
        .page-break-before {
            page-break-before: always;
        }
        @media print {
            .page-break {
                page-break-after: always;
            }
            .page-break-before {
                page-break-before: always;
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
                <h1>COMMUNITAP ADMINISTRATIVE DASHBOARD REPORT</h1>
                <div class="subtitle">Comprehensive Platform Analytics and Metrics</div>
                <div class="header-info">
            <strong>Report Generated:</strong> {{ now()->format('F d, Y \a\t g:i A') }}<br>
            @if(isset($periodLabel) && isset($currentStart) && isset($currentEnd))
            <strong>Reporting Period:</strong> {{ $periodLabel }}<br>
            <strong>Date Range:</strong> {{ $currentStart->format('F d, Y') }} through {{ $currentEnd->format('F d, Y') }}
            @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Executive Summary -->
    <h2>Executive Summary</h2>
    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-label">Total Users</div>
            <div class="stat-value">{{ number_format($totalUsers) }}</div>
            <div class="stat-detail">Active: {{ number_format($activeUsers) }} ({{ $userActivationRate }}%)</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Task Completion Rate</div>
            <div class="stat-value">{{ $taskCompletionRate }}%</div>
            <div class="stat-detail">{{ number_format($completedAssignments) }} / {{ number_format($totalAssignments) }} tasks</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">User Engagement</div>
            <div class="stat-value">{{ $engagementRate }}%</div>
            <div class="stat-detail">{{ number_format($usersWithTasks) }} of {{ number_format($allTimeUsers) }} users</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Total Points Awarded</div>
            <div class="stat-value">{{ number_format($totalPoints) }}</div>
            <div class="stat-detail">Avg: {{ $avgPointsPerUser }} per user</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Active Volunteers</div>
            <div class="stat-value">{{ number_format($activeVolunteers) }}</div>
            <div class="stat-detail">Completed tasks this period</div>
        </div>
    </div>

    <!-- Top Performers -->
    <h2>Top Performers (This Period)</h2>
    @if($topPerformers->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 8%;">Rank</th>
                <th style="width: 30%;">Name</th>
                <th style="width: 35%;">Email</th>
                <th style="width: 12%;" class="text-center">Tasks Completed</th>
                <th style="width: 15%;" class="text-right">Points Earned</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topPerformers as $index => $performer)
            <tr>
                <td class="text-center"><strong>#{{ $index + 1 }}</strong></td>
                <td><strong>{{ $performer->name }}</strong></td>
                <td>{{ $performer->email }}</td>
                <td class="text-center">{{ number_format($performer->tasks_completed) }}</td>
                <td class="text-right"><strong>{{ number_format($performer->points_earned) }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-data">No performance data available for the selected period.</div>
    @endif

    <!-- Task Analytics -->
    <h2>Task Analytics</h2>
    <div class="stats-grid" style="margin-bottom: 10px;">
        <div class="stat-item">
            <div class="stat-label">Total Tasks Created</div>
            <div class="stat-value">{{ number_format($totalTasks) }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Completed</div>
            <div class="stat-value">{{ number_format($tasksCompleted) }}</div>
            <div class="stat-detail">{{ $totalTasks > 0 ? round(($tasksCompleted / $totalTasks) * 100, 1) : 0 }}% of total</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Published</div>
            <div class="stat-value">{{ number_format($tasksPublished) }}</div>
            <div class="stat-detail">{{ $totalTasks > 0 ? round(($tasksPublished / $totalTasks) * 100, 1) : 0 }}% of total</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Pending</div>
            <div class="stat-value">{{ number_format($tasksPending) }}</div>
            <div class="stat-detail">{{ $totalTasks > 0 ? round(($tasksPending / $totalTasks) * 100, 1) : 0 }}% of total</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Inactive</div>
            <div class="stat-value">{{ number_format($tasksInactive) }}</div>
            <div class="stat-detail">{{ $totalTasks > 0 ? round(($tasksInactive / $totalTasks) * 100, 1) : 0 }}% of total</div>
        </div>
    </div>

    @if($taskTypeBreakdown->count() > 0)
    <h3 style="font-size: 10pt; margin-top: 8px; margin-bottom: 5px; color: #555;">Task Breakdown by Type</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 60%;">Task Type</th>
                <th style="width: 20%;" class="text-center">Count</th>
                <th style="width: 20%;" class="text-center">Percentage</th>
            </tr>
        </thead>
        <tbody>
            @foreach($taskTypeBreakdown as $type => $count)
            <tr>
                <td><strong>{{ ucfirst(str_replace('_', ' ', $type)) }}</strong></td>
                <td class="text-center">{{ number_format($count) }}</td>
                <td class="text-center">{{ $totalTasks > 0 ? round(($count / $totalTasks) * 100, 1) : 0 }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Reward Analytics -->
    <div class="page-break-before" style="page-break-before: always;">&nbsp;</div>
    <h2>Reward Analytics</h2>
    <div class="stats-grid" style="margin-bottom: 10px;">
        <div class="stat-item">
            <div class="stat-label">Total Rewards</div>
            <div class="stat-value">{{ number_format($totalRewards) }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Available</div>
            <div class="stat-value">{{ number_format($rewardsAvailable) }}</div>
            <div class="stat-detail">{{ $totalRewards > 0 ? round(($rewardsAvailable / $totalRewards) * 100, 1) : 0 }}% of total</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Redeemed</div>
            <div class="stat-value">{{ number_format($rewardsRedeemed) }}</div>
            <div class="stat-detail">{{ $totalRewards > 0 ? round(($rewardsRedeemed / $totalRewards) * 100, 1) : 0 }}% of total</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Inactive</div>
            <div class="stat-value">{{ number_format($rewardsInactive) }}</div>
            <div class="stat-detail">{{ $totalRewards > 0 ? round(($rewardsInactive / $totalRewards) * 100, 1) : 0 }}% of total</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Redemption Rate</div>
            <div class="stat-value">{{ $totalRewards > 0 ? round(($rewardsRedeemed / $totalRewards) * 100, 1) : 0 }}%</div>
            <div class="stat-detail">Rewards claimed</div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <h2>Performance Metrics</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 50%;">Metric</th>
                <th style="width: 25%;" class="text-center">Value</th>
                <th style="width: 25%;">Insight</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Task Completion Rate</strong></td>
                <td class="text-center"><strong>{{ $taskCompletionRate }}%</strong></td>
                <td>{{ $taskCompletionRate >= 70 ? 'Excellent' : ($taskCompletionRate >= 50 ? 'Good' : ($taskCompletionRate >= 30 ? 'Fair' : 'Needs Improvement')) }}</td>
            </tr>
            <tr>
                <td><strong>User Engagement Rate</strong></td>
                <td class="text-center"><strong>{{ $engagementRate }}%</strong></td>
                <td>{{ $engagementRate >= 60 ? 'High Engagement' : ($engagementRate >= 40 ? 'Moderate Engagement' : 'Low Engagement') }}</td>
            </tr>
            <tr>
                <td><strong>User Activation Rate</strong></td>
                <td class="text-center"><strong>{{ $userActivationRate }}%</strong></td>
                <td>{{ $userActivationRate >= 80 ? 'Strong Activation' : ($userActivationRate >= 60 ? 'Good Activation' : 'Needs Attention') }}</td>
            </tr>
            <tr>
                <td><strong>Average Points per User</strong></td>
                <td class="text-center"><strong>{{ $avgPointsPerUser }}</strong></td>
                <td>Points distribution indicator</td>
            </tr>
            <tr>
                <td><strong>Average Points per Task</strong></td>
                <td class="text-center"><strong>{{ $avgPointsPerTask }}</strong></td>
                <td>Task value indicator</td>
            </tr>
            <tr>
                <td><strong>Active Volunteers</strong></td>
                <td class="text-center"><strong>{{ number_format($activeVolunteers) }}</strong></td>
                <td>{{ $activeVolunteers > 0 ? number_format(($activeVolunteers / $activeUsers) * 100, 1) : 0 }}% of active users</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>This report was generated automatically by the CommuniTAP Administrative System.</p>
        <p>For questions or concerns, please contact the system administrator.</p>
    </div>
</body>
</html>
