<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>User Accounts List - CommuniTAP</title>
    <style>
        @page {
            margin: 0.5cm 1cm;
            size: A4 landscape;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Times New Roman', serif;
            font-size: 11pt;
            color: #1a1a1a;
            line-height: 1.6;
        }
        .header {
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 5px;
            margin-bottom: 8px;
            display: table;
            width: 100%;
        }
        .header-content { display: table-row; }
        .logo-cell {
            display: table-cell;
            vertical-align: middle;
            width: 150px;
            padding-right: 10px;
            text-align: center;
        }
        .logo-cell img {
            max-width: 140px;
            height: auto;
        }
        .title-cell { display: table-cell; vertical-align: middle; padding-left: 5px; }
        .title-cell h1 {
            font-size: 16pt;
            font-weight: bold;
            color: #2c3e50;
            letter-spacing: 0.5px;
        }
        .title-cell .subtitle {
            font-size: 8pt;
            color: #555;
            margin-bottom: 3px;
        }
        .header-info {
            font-size: 7pt;
            color: #666;
            line-height: 1.3;
        }
        .header-info strong { color: #2c3e50; }
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
        .stats-grid {
            display: table;
            width: 100%;
            border-collapse: separate;
            border-spacing: 5px;
            margin-bottom: 8px;
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
            font-size: 9pt;
        }
        thead { background: #2c3e50; color: #fff; }
        th {
            padding: 6px 8px;
            text-align: left;
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border: 1px solid #1a252f;
        }
        td { padding: 6px 8px; border: 1px solid #dee2e6; vertical-align: top; }
        tbody tr:nth-child(even) { background: #f8f9fa; }
        .insight-box {
            background: #f8f9fa;
            border-left: 4px solid #2c3e50;
            padding: 8px 12px;
            margin: 8px 0;
            font-size: 8pt;
        }
        .no-data {
            text-align: center;
            padding: 30px;
            color: #999;
            font-style: italic;
            border: 1px dashed #ccc;
            margin: 20px 0;
        }
        .footer {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #e0e0e0;
            font-size: 7pt;
            color: #666;
            text-align: center;
        }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th { background: #111827; color: #fff; text-transform: uppercase; font-size: 10px; letter-spacing: 0.5px; padding: 6px; text-align: left; }
        td { border: 1px solid #e5e7eb; padding: 6px; vertical-align: top; font-size: 10px; }
        tr:nth-child(even) td { background: #f9fafb; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: 600; text-transform: uppercase; }
        .badge-active { background: #d1fae5; color: #065f46; }
        .badge-inactive { background: #fee2e2; color: #991b1b; }
        .badge-role { background: #e0f2fe; color: #0369a1; }
        .insight-box { border-left: 4px solid #f97316; background: #fff7ed; padding: 10px 12px; margin-bottom: 10px; }
        .no-data { border: 1px dashed #d1d5db; padding: 20px; text-align: center; color: #6b7280; font-style: italic; }
        .mini-table { width: 100%; border-collapse: collapse; }
        .mini-table td { border: none; padding: 2px 0; font-size: 10px; }
    </style>
</head>
<body>
@php
    $logoPath = public_path('images/communitaplogo1.svg');
    $logoSrc = '';
    if (file_exists($logoPath)) {
        $svgContent = file_get_contents($logoPath);
        if (preg_match('/xlink:href="data:image\/png;base64,([^"]+)"/', $svgContent, $matches)) {
            $logoSrc = 'data:image/png;base64,' . trim($matches[1]);
        } else {
            $logoSrc = $logoPath;
        }
    }
@endphp
    <div class="header">
        <div class="header-content">
            <div class="logo-cell">
                @if($logoSrc)
                    <img src="{{ $logoSrc }}" alt="CommuniTAP Logo">
                @endif
            </div>
            <div class="title-cell">
                <h1>COMMUNITAP ADMINISTRATIVE DASHBOARD REPORT</h1>
                <div class="subtitle">User Accounts List &amp; Volunteer Directory</div>
                <div class="header-info">
                    <strong>Report Generated:</strong> {{ now()->format('F d, Y \\a\\t g:i A') }}<br>
                    <strong>Reporting Period:</strong> {{ $periodLabel ?? 'Custom' }}<br>
                    <strong>Date Range:</strong> {{ \Carbon\Carbon::parse($startDate)->format('F d, Y') }} through {{ \Carbon\Carbon::parse($endDate)->format('F d, Y') }}<br>
                    <strong>Filters:</strong> Status={{ ucfirst($status) }}, Role={{ ucfirst($role ?? 'all') }}
                </div>
            </div>
        </div>
    </div>

    @php
        $totalAssigned = $userStats->sum('assignments');
        $totalCompleted = $userStats->sum('completions');
        $completionRatio = $totalAssigned > 0 ? ($totalCompleted / $totalAssigned) * 100 : 0;
        $engagedUsers = $userStats->where('assignments', '>', 0)->count();
        $engagementPercent = $totalUsers > 0 ? ($engagedUsers / $totalUsers) * 100 : 0;
        $activeVolunteersCount = $userStats->where('completions', '>', 0)->count();
    @endphp

    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-label">Total Users</div>
            <div class="stat-value">{{ number_format($totalUsers) }}</div>
            <div class="stat-detail">Active: {{ number_format($activeUsers) }} ({{ $totalUsers > 0 ? number_format(($activeUsers / $totalUsers) * 100, 1) : 0 }}%)</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Task Completion Rate</div>
            <div class="stat-value">{{ number_format($completionRatio, 1) }}%</div>
            <div class="stat-detail">{{ number_format($totalCompleted) }} / {{ number_format($totalAssigned) }} assignments</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">User Engagement</div>
            <div class="stat-value">{{ number_format($engagementPercent, 1) }}%</div>
            <div class="stat-detail">{{ number_format($engagedUsers) }} of {{ number_format($totalUsers) }} users</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Total Points Awarded</div>
            <div class="stat-value">{{ number_format($totalPointsPeriod) }}</div>
            <div class="stat-detail">Avg: {{ $totalUsers > 0 ? number_format($totalPointsPeriod / max($totalUsers, 1), 1) : 0 }} per user</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Active Volunteers</div>
            <div class="stat-value">{{ number_format($activeVolunteersCount) }}</div>
            <div class="stat-detail">Completed tasks this period</div>
        </div>
    </div>

    <h2>Engagement Highlights</h2>
    <div class="insight-box">
        @if($completionRatio >= 70)
            Strong volunteer engagement this period with above-average completion activity.
        @elseif($completionRatio >= 40)
            Moderate engagement. Consider spotlighting top performers to lift completions.
        @else
            Engagement dipped below target. Review onboarding or provide fresh incentives.
        @endif
    </div>

    <h2>User Directory</h2>
    @if($userStats->isEmpty())
        <div class="no-data">No user activity recorded for the selected period.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Contact</th>
                    <th>Role / Status</th>
                    <th>Registered</th>
                    <th>Assignments</th>
                    <th>Completions</th>
                    <th>Points Earned</th>
                    <th>Nominations</th>
                    <th>Recent Activity</th>
                </tr>
            </thead>
            <tbody>
                @foreach($userStats as $stat)
                    <tr>
                        <td>
                            <strong>{{ $stat['user']->fullName ?? ($stat['user']->firstName.' '.$stat['user']->lastName) }}</strong><br>
                            ID: #{{ $stat['user']->userId }}
                        </td>
                        <td>{{ $stat['user']->email }}</td>
                        <td>
                            <span class="badge badge-role">{{ ucfirst($stat['user']->role) }}</span><br>
                            @if($stat['user']->status === 'active')
                                <span class="badge badge-active">Active</span>
                            @else
                                <span class="badge badge-inactive">{{ ucfirst($stat['user']->status) }}</span>
                            @endif
                        </td>
                        <td>{{ optional($stat['user']->created_at)->format('M d, Y') ?? '—' }}</td>
                        <td>{{ number_format($stat['assignments']) }}</td>
                        <td>{{ number_format($stat['completions']) }}</td>
                        <td>{{ number_format($stat['pointsEarned']) }}</td>
                        <td>
                            Made: {{ number_format($stat['nominationsMade']) }}<br>
                            Received: {{ number_format($stat['nominationsReceived']) }}
                        </td>
                        <td>
                            Assigned: {{ optional($stat['lastAssignedAt'])->format('M d, Y g:i A') ?? '—' }}<br>
                            Completed: {{ optional($stat['lastCompletedAt'])->format('M d, Y g:i A') ?? '—' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="insight-box">
        <strong>Retention Watchlist</strong><br>
        Accounts without assignments during this period: {{ number_format($userStats->where('assignments', 0)->count()) }}.
        Consider promoting new tasks or pairing mentors for these volunteers.
    </div>
</body>
</html>

