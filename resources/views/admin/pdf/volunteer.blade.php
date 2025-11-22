<!DOCTYPE html>
<html>
<head>
    <title>Volunteer Activity Report - CommuniTAP</title>
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
            width: 25%;
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
        .tier-champion {
            background: #cfe2ff;
            color: #084298;
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
                <h1>VOLUNTEER ACTIVITY REPORT</h1>
                <div class="subtitle">Comprehensive Volunteer Performance and Engagement Analysis</div>
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
        $totalVolunteersReported = count($userStats);
        $totalTasksAssigned = collect($userStats)->sum('totalTasksAssigned');
        $totalTasksCompleted = collect($userStats)->sum('totalTasksCompleted');
        $overallCompletionRate = $totalTasksAssigned > 0 ? round(($totalTasksCompleted / $totalTasksAssigned) * 100, 1) : 0;
        $avgPointsPerVolunteer = $totalVolunteersReported > 0 ? round($totalPoints / $totalVolunteersReported, 1) : 0;
        $avgTasksPerVolunteer = $totalVolunteersReported > 0 ? round($totalTasksAssigned / $totalVolunteersReported, 1) : 0;
        $activationRate = $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 1) : 0;
        $engagementRate = $totalUsers > 0 ? round(($totalVolunteersReported / $totalUsers) * 100, 1) : 0;
    @endphp
    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-label">Total Volunteers</div>
            <div class="stat-value">{{ number_format($totalUsers) }}</div>
            <div class="stat-detail">{{ $activationRate }}% active</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Active Volunteers</div>
            <div class="stat-value">{{ number_format($activeUsers) }}</div>
            <div class="stat-detail">{{ $engagementRate }}% engaged</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Total Points Earned</div>
            <div class="stat-value">{{ number_format($totalPoints) }}</div>
            <div class="stat-detail">Avg: {{ $avgPointsPerVolunteer }} per volunteer</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Volunteers Reported</div>
            <div class="stat-value">{{ number_format($totalVolunteersReported) }}</div>
            <div class="stat-detail">Avg: {{ $avgTasksPerVolunteer }} tasks each</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Overall Completion Rate</div>
            <div class="stat-value">{{ number_format($overallCompletionRate, 1) }}%</div>
            <div class="stat-detail">
                @if($overallCompletionRate >= 70)
                    <span class="metric-comparison positive">Excellent</span>
                @elseif($overallCompletionRate >= 50)
                    <span class="metric-comparison">Good</span>
                @else
                    <span class="metric-comparison negative">Needs Improvement</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Key Insights -->
    <h2>Volunteer Engagement Insights</h2>
    <div class="insight-box">
        <strong>Engagement Analysis:</strong>
        @if($engagementRate >= 70)
            Strong volunteer engagement with {{ $engagementRate }}% of total volunteers actively participating. The platform is effectively mobilizing its volunteer base.
        @elseif($engagementRate >= 50)
            Moderate engagement at {{ $engagementRate }}%. Consider outreach strategies to activate the {{ number_format($totalUsers - $totalVolunteersReported) }} inactive volunteers.
        @else
            Low engagement at {{ $engagementRate }}%. Focus on re-engagement campaigns and improving task appeal to activate {{ number_format($totalUsers - $totalVolunteersReported) }} inactive volunteers.
        @endif
    </div>
    <div class="insight-box">
        <strong>Performance Metrics:</strong>
        @if($overallCompletionRate >= 70)
            Excellent completion rate of {{ $overallCompletionRate }}% indicates high volunteer commitment and task clarity. Volunteers are successfully completing assigned tasks.
        @elseif($overallCompletionRate >= 50)
            Moderate completion rate of {{ $overallCompletionRate }}%. Review task difficulty, support resources, and volunteer feedback to improve completion rates.
        @else
            Completion rate of {{ $overallCompletionRate }}% needs attention. Investigate barriers to completion and consider task redesign or additional support.
        @endif
    </div>
    <div class="insight-box">
        <strong>Productivity Analysis:</strong>
        @if($avgPointsPerVolunteer >= 500)
            High productivity with {{ $avgPointsPerVolunteer }} points per volunteer average. Volunteers are actively earning rewards and contributing significantly.
        @elseif($avgPointsPerVolunteer >= 200)
            Moderate productivity ({{ $avgPointsPerVolunteer }} points per volunteer). Consider incentive programs to boost participation.
        @else
            Lower productivity at {{ $avgPointsPerVolunteer }} points per volunteer. Review reward structure and task availability to increase engagement.
        @endif
    </div>

    <!-- Summary Table -->
    @if(count($userStats) > 0)
    <h2>Volunteer Performance Analysis</h2>
    @php
        $avgCompletionRate = collect($userStats)->filter(function($stat) {
            return $stat['totalTasksAssigned'] > 0;
        })->map(function($stat) {
            return ($stat['totalTasksCompleted'] / $stat['totalTasksAssigned']) * 100;
        })->avg();
        $avgPointsEarned = collect($userStats)->avg('totalPointsEarned');
        $sortedVolunteers = collect($userStats)->sortByDesc(function($stat) {
            return $stat['totalPointsEarned'] * ($stat['totalTasksAssigned'] > 0 ? ($stat['totalTasksCompleted'] / $stat['totalTasksAssigned']) : 0);
        });
    @endphp
    <table>
        <thead>
            <tr>
                <th style="width: 3%;">#</th>
                <th style="width: 20%;">Volunteer Name</th>
                <th style="width: 22%;">Email Address</th>
                <th style="width: 8%;" class="text-center">Status</th>
                <th style="width: 9%;" class="text-right">Total Points</th>
                <th style="width: 7%;" class="text-center">Assigned</th>
                <th style="width: 7%;" class="text-center">Completed</th>
                <th style="width: 8%;" class="text-center">Rate</th>
                <th style="width: 10%;" class="text-right">Points Earned</th>
                <th style="width: 6%;" class="text-center">Tier</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sortedVolunteers as $index => $stat)
                @php
                    $completionRate = $stat['totalTasksAssigned'] > 0 ? round(($stat['totalTasksCompleted'] / $stat['totalTasksAssigned']) * 100, 1) : 0;
                    $vsAvgPoints = $avgPointsEarned > 0 ? round((($stat['totalPointsEarned'] - $avgPointsEarned) / $avgPointsEarned) * 100, 1) : 0;
                    $vsAvgRate = $avgCompletionRate > 0 ? round((($completionRate - $avgCompletionRate) / $avgCompletionRate) * 100, 1) : 0;
                    
                    // Determine tier based on multiple factors
                    if ($stat['totalPointsEarned'] >= ($avgPointsEarned * 2) && $completionRate >= 80) {
                        $tier = 'champion';
                    } elseif ($stat['totalPointsEarned'] >= $avgPointsEarned && $completionRate >= 70) {
                        $tier = 'high';
                    } elseif ($completionRate >= 50 || $stat['totalPointsEarned'] >= $avgPointsEarned) {
                        $tier = 'medium';
                    } else {
                        $tier = 'low';
                    }
                @endphp
            <tr>
                <td class="text-center">
                    @if($index < 3)
                        <strong>#{{ $index + 1 }}</strong>
                    @else
                        {{ $index + 1 }}
                    @endif
                </td>
                <td><strong>{{ $stat['user']->fullName }}</strong></td>
                <td>{{ $stat['user']->email }}</td>
                <td class="text-center">{{ ucfirst($stat['user']->status) }}</td>
                <td class="text-right">{{ number_format($stat['user']->points) }}</td>
                <td class="text-center">{{ number_format($stat['totalTasksAssigned']) }}</td>
                <td class="text-center">{{ number_format($stat['totalTasksCompleted']) }}</td>
                <td class="text-center">
                    @if($stat['totalTasksAssigned'] > 0)
                        <span class="completion-rate {{ $completionRate >= 70 ? 'high' : ($completionRate >= 50 ? 'medium' : 'low') }}">
                            {{ $completionRate }}%
                        </span>
                        @if($vsAvgRate != 0)
                            <div class="metric-comparison {{ $vsAvgRate > 0 ? 'positive' : 'negative' }}">
                                {{ $vsAvgRate > 0 ? '+' : '' }}{{ $vsAvgRate }}%
                            </div>
                        @endif
                    @else
                        <span style="color: #999;">N/A</span>
                    @endif
                </td>
                <td class="text-right">
                    <strong>{{ number_format($stat['totalPointsEarned']) }}</strong>
                    @if($vsAvgPoints != 0)
                        <div class="metric-comparison {{ $vsAvgPoints > 0 ? 'positive' : 'negative' }}">
                            {{ $vsAvgPoints > 0 ? '+' : '' }}{{ $vsAvgPoints }}%
                        </div>
                    @endif
                </td>
                <td class="text-center">
                    <span class="performance-tier tier-{{ $tier }}">
                        {{ ucfirst($tier) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Performance Distribution -->
    @php
        $champions = collect($userStats)->filter(function($stat) use ($avgPointsEarned) {
            $rate = $stat['totalTasksAssigned'] > 0 ? ($stat['totalTasksCompleted'] / $stat['totalTasksAssigned']) * 100 : 0;
            return $stat['totalPointsEarned'] >= ($avgPointsEarned * 2) && $rate >= 80;
        })->count();
        $highPerformers = collect($userStats)->filter(function($stat) use ($avgPointsEarned) {
            $rate = $stat['totalTasksAssigned'] > 0 ? ($stat['totalTasksCompleted'] / $stat['totalTasksAssigned']) * 100 : 0;
            return $stat['totalPointsEarned'] >= $avgPointsEarned && $rate >= 70 && $stat['totalPointsEarned'] < ($avgPointsEarned * 2);
        })->count();
        $mediumPerformers = collect($userStats)->filter(function($stat) use ($avgPointsEarned) {
            $rate = $stat['totalTasksAssigned'] > 0 ? ($stat['totalTasksCompleted'] / $stat['totalTasksAssigned']) * 100 : 0;
            return ($rate >= 50 || $stat['totalPointsEarned'] >= $avgPointsEarned) && !($stat['totalPointsEarned'] >= $avgPointsEarned && $rate >= 70);
        })->count();
        $lowPerformers = collect($userStats)->filter(function($stat) use ($avgPointsEarned) {
            $rate = $stat['totalTasksAssigned'] > 0 ? ($stat['totalTasksCompleted'] / $stat['totalTasksAssigned']) * 100 : 0;
            return $rate < 50 && $stat['totalPointsEarned'] < $avgPointsEarned;
        })->count();
    @endphp
    <h3>Performance Distribution & Top Performers</h3>
    <div class="stats-grid" style="margin-top: 5px;">
        <div class="stat-item">
            <div class="stat-label">Champions</div>
            <div class="stat-value">{{ number_format($champions) }}</div>
            <div class="stat-detail">{{ $totalVolunteersReported > 0 ? number_format(($champions / $totalVolunteersReported) * 100, 1) : 0 }}% of volunteers</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">High Performers</div>
            <div class="stat-value">{{ number_format($highPerformers) }}</div>
            <div class="stat-detail">{{ $totalVolunteersReported > 0 ? number_format(($highPerformers / $totalVolunteersReported) * 100, 1) : 0 }}% of volunteers</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Medium Performers</div>
            <div class="stat-value">{{ number_format($mediumPerformers) }}</div>
            <div class="stat-detail">{{ $totalVolunteersReported > 0 ? number_format(($mediumPerformers / $totalVolunteersReported) * 100, 1) : 0 }}% of volunteers</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Low Performers</div>
            <div class="stat-value">{{ number_format($lowPerformers) }}</div>
            <div class="stat-detail">{{ $totalVolunteersReported > 0 ? number_format(($lowPerformers / $totalVolunteersReported) * 100, 1) : 0 }}% of volunteers</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Top Performer</div>
            <div class="stat-value">
                @php
                    $topVolunteer = $sortedVolunteers->first();
                    $topRate = $topVolunteer['totalTasksAssigned'] > 0 ? round(($topVolunteer['totalTasksCompleted'] / $topVolunteer['totalTasksAssigned']) * 100, 1) : 0;
                @endphp
                {{ number_format($topVolunteer['totalPointsEarned']) }}
            </div>
            <div class="stat-detail">{{ Str::limit($topVolunteer['user']->fullName, 20) }} ({{ $topRate }}%)</div>
        </div>
    </div>
    @endif

    <div class="footer">
        <p>This report was generated automatically by the CommuniTAP Administrative System.</p>
        <p>For questions or concerns, please contact the system administrator.</p>
    </div>
</body>
</html>
