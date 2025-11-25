<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Task List - CommuniTAP</title>
    <style>
        @page { margin: 0.5cm 1cm; size: A4 landscape; }
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
        .logo-cell { display: table-cell; width: 150px; text-align: center; vertical-align: middle; padding-right: 10px; }
        .logo-cell img { max-width: 140px; height: auto; }
        .title-cell { display: table-cell; vertical-align: middle; padding-left: 5px; }
        .title-cell h1 {
            font-size: 16pt;
            font-weight: bold;
            color: #2c3e50;
            letter-spacing: 0.5px;
        }
        .title-cell .subtitle { font-size: 8pt; color: #555; margin-bottom: 3px; }
        .header-info { font-size: 7pt; color: #666; line-height: 1.3; }
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
        .stat-label { font-size: 8pt; color: #666; margin-bottom: 3px; text-transform: uppercase; letter-spacing: 0.3px; }
        .stat-value { font-size: 14pt; font-weight: bold; color: #2c3e50; }
        .stat-detail { font-size: 7pt; color: #666; margin-top: 2px; line-height: 1.2; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 9pt; }
        thead { background: #2c3e50; color: #fff; }
        th { padding: 6px 8px; text-align: left; font-size: 8pt; text-transform: uppercase; letter-spacing: 0.3px; border: 1px solid #1a252f; }
        td { border: 1px solid #dee2e6; padding: 6px 8px; vertical-align: top; }
        tr:nth-child(even) td { background: #f8f9fa; }
        .badge { padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: 600; display: inline-block; text-transform: uppercase; }
        .badge-status { background: #dbeafe; color: #1d4ed8; }
        .badge-status.completed { background: #d1fae5; color: #047857; }
        .badge-status.pending { background: #fef3c7; color: #92400e; }
        .badge-status.inactive { background: #fee2e2; color: #991b1b; }
        .trend-positive { color: #047857; font-weight: 600; }
        .trend-warning { color: #b45309; font-weight: 600; }
        .no-data { border: 1px dashed #d1d5db; padding: 20px; text-align: center; color: #6b7280; font-style: italic; }
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
                <div class="subtitle">Task List &amp; Assignment Performance</div>
                <div class="header-info">
                    <strong>Report Generated:</strong> {{ now()->format('F d, Y \\a\\t g:i A') }}<br>
                    <strong>Reporting Period:</strong> {{ $periodLabel ?? 'Custom' }}<br>
                    <strong>Date Range:</strong> {{ \Carbon\Carbon::parse($startDate)->format('F d, Y') }} through {{ \Carbon\Carbon::parse($endDate)->format('F d, Y') }}<br>
                    <strong>Filters:</strong> Type={{ $taskType === 'all' ? 'All' : ucfirst(str_replace('_', ' ', $taskType)) }}, Status={{ ucfirst($status) }}
                </div>
            </div>
        </div>
    </div>

    @php
        $averageCompletion = $totalAssignments > 0 ? ($totalCompleted / $totalAssignments) * 100 : 0;
        $avgParticipants = $taskStats->count() ? number_format($taskStats->avg('participants'), 1) : 0;
    @endphp

    @php
        $tasksWithParticipants = $taskStats->where('participants', '>', 0)->count();
        $engagementPercent = $totalTasks > 0 ? ($tasksWithParticipants / $totalTasks) * 100 : 0;
    @endphp

    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-label">Total Tasks</div>
            <div class="stat-value">{{ number_format($totalTasks) }}</div>
            <div class="stat-detail">{{ number_format($totalAssignments) }} assignments logged</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Task Completion Rate</div>
            <div class="stat-value">{{ number_format($averageCompletion, 1) }}%</div>
            <div class="stat-detail">{{ number_format($totalCompleted) }} / {{ number_format($totalAssignments) }} assignments</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">User Engagement</div>
            <div class="stat-value">{{ number_format($engagementPercent, 1) }}%</div>
            <div class="stat-detail">{{ number_format($tasksWithParticipants) }} of {{ number_format($totalTasks) }} tasks</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Total Points Awarded</div>
            <div class="stat-value">{{ number_format($totalPointsAwarded) }}</div>
            <div class="stat-detail">Avg: {{ $totalTasks > 0 ? number_format($totalPointsAwarded / max($totalTasks, 1), 1) : 0 }} per task</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Active Tasks</div>
            <div class="stat-value">{{ number_format($tasksWithParticipants) }}</div>
            <div class="stat-detail">Tasks with participant activity</div>
        </div>
    </div>

    <h2>Task Directory</h2>
    @if($taskStats->isEmpty())
        <div class="no-data">No tasks matched the selected filters.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Due Date</th>
                    <th>Assignments</th>
                    <th>Completions</th>
                    <th>Completion %</th>
                    <th>Points</th>
                    <th>Participants</th>
                </tr>
            </thead>
            <tbody>
                @foreach($taskStats as $stat)
                    <tr>
                        <td>
                            <strong>{{ $stat['task']->title }}</strong><br>
                            ID: #{{ $stat['task']->taskId }}
                        </td>
                        <td>{{ ucfirst(str_replace('_', ' ', $stat['task']->task_type)) }}</td>
                        <td>
                            @php
                                $statusClass = match($stat['task']->status) {
                                    'completed' => 'completed',
                                    'pending', 'submitted' => 'pending',
                                    'inactive' => 'inactive',
                                    default => ''
                                };
                            @endphp
                            <span class="badge badge-status {{ $statusClass }}">{{ ucfirst($stat['task']->status) }}</span>
                        </td>
                        <td>{{ optional($stat['task']->creation_date)->format('M d, Y') ?? '—' }}</td>
                        <td>{{ optional($stat['task']->due_date)->format('M d, Y') ?? '—' }}</td>
                        <td>{{ number_format($stat['assignedCount']) }}</td>
                        <td>{{ number_format($stat['completedCount']) }}</td>
                        <td>
                            {{ number_format($stat['completionRate'], 1) }}%
                            @if($stat['completionRate'] >= 80)
                                <div class="trend-positive">High</div>
                            @elseif($stat['completionRate'] < 40)
                                <div class="trend-warning">Low</div>
                            @endif
                        </td>
                        <td>{{ number_format($stat['totalPointsAwarded']) }}</td>
                        <td>{{ number_format($stat['participants']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h2>Operational Notes</h2>
    <p style="font-size:10px; color:#4b5563;">
        Monitor tasks with fewer than 2 assignments or below 30% completion for potential rework.
        Highlight top tasks in upcoming campaigns to reinforce successful formats.
    </p>
</body>
</html>

