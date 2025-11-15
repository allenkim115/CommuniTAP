<!DOCTYPE html>
<html>
<head>
    <title>Task Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #111; margin: 20px; }
        h1 { font-size: 20px; margin-bottom: 5px; color: #059669; }
        h2 { font-size: 14px; margin-top: 15px; margin-bottom: 8px; border-bottom: 2px solid #10b981; padding-bottom: 3px; color: #059669; }
        .header { margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #ccc; }
        .header-info { font-size: 10px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; page-break-inside: avoid; }
        th, td { border: 1px solid #999; padding: 6px 8px; text-align: left; font-size: 10px; }
        th { background: #10b981; color: white; font-weight: bold; }
        tr:nth-child(even) { background-color: #f3f4f6; }
        .stat-box { display: inline-block; margin: 5px 10px 5px 0; padding: 8px 12px; background: #ecfdf5; border: 1px solid #10b981; border-radius: 4px; }
        .stat-label { font-size: 9px; color: #666; }
        .stat-value { font-size: 14px; font-weight: bold; color: #059669; }
        .section-title { margin-top: 20px; font-size: 12px; font-weight: bold; color: #333; background: #e5e7eb; padding: 5px 8px; }
        .task-section { margin-bottom: 20px; page-break-inside: avoid; }
        .task-header { background: #d1fae5; padding: 8px; font-weight: bold; border-left: 4px solid #10b981; }
        .no-data { text-align: center; padding: 20px; color: #999; font-style: italic; }
        .completion-rate { font-weight: bold; }
        .completion-rate.high { color: #059669; }
        .completion-rate.medium { color: #d97706; }
        .completion-rate.low { color: #dc2626; }
        @media print {
            .page-break { page-break-after: always; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Task Report</h1>
        <div class="header-info">
            <strong>Generated on:</strong> {{ now()->format('F d, Y H:i') }}<br>
            @if($startDate || $endDate)
                <strong>Date Range:</strong> 
                {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('M d, Y') : 'All' }} - 
                {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('M d, Y') : 'All' }}<br>
            @endif
            @if($taskType !== 'all')
                <strong>Task Type:</strong> {{ ucfirst(str_replace('_', ' ', $taskType)) }}<br>
            @endif
            @if($status !== 'all')
                <strong>Status Filter:</strong> {{ ucfirst($status) }}<br>
            @endif
        </div>
    </div>

    <!-- Overall Statistics -->
    <h2>Overall Statistics</h2>
    <div>
        <div class="stat-box">
            <div class="stat-label">Total Tasks</div>
            <div class="stat-value">{{ $totalTasks }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Total Assignments</div>
            <div class="stat-value">{{ $totalAssignments }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Completed Tasks</div>
            <div class="stat-value">{{ $totalCompleted }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Total Points Awarded</div>
            <div class="stat-value">{{ number_format($totalPointsAwarded) }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Completion Rate</div>
            <div class="stat-value">
                {{ $totalAssignments > 0 ? number_format(($totalCompleted / $totalAssignments) * 100, 1) : 0 }}%
            </div>
        </div>
    </div>

    <!-- Task Details -->
    <h2>Task Details</h2>
    @if(count($taskStats) > 0)
        @foreach($taskStats as $index => $stat)
            <div class="task-section {{ $index > 0 && $index % 2 == 0 ? 'page-break' : '' }}">
                <div class="task-header">
                    Task #{{ $stat['task']->taskId }}: {{ $stat['task']->title }}
                </div>
                <table>
                    <tr>
                        <th style="width: 30%;">Field</th>
                        <th style="width: 70%;">Value</th>
                    </tr>
                    <tr>
                        <td><strong>Title</strong></td>
                        <td>{{ $stat['task']->title }}</td>
                    </tr>
                    <tr>
                        <td><strong>Description</strong></td>
                        <td>{{ Str::limit($stat['task']->description, 200) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Task Type</strong></td>
                        <td>{{ ucfirst(str_replace('_', ' ', $stat['task']->task_type)) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>{{ ucfirst($stat['task']->status) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Points Awarded</strong></td>
                        <td>{{ $stat['task']->points_awarded }}</td>
                    </tr>
                    <tr>
                        <td><strong>Creation Date</strong></td>
                        <td>{{ $stat['task']->creation_date ? $stat['task']->creation_date->format('M d, Y H:i') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Due Date</strong></td>
                        <td>{{ $stat['task']->due_date ? $stat['task']->due_date->format('M d, Y') : 'N/A' }}</td>
                    </tr>
                    @if($stat['task']->location)
                    <tr>
                        <td><strong>Location</strong></td>
                        <td>{{ $stat['task']->location }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td><strong>Assigned Count</strong></td>
                        <td>{{ $stat['assignedCount'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Completed Count</strong></td>
                        <td>{{ $stat['completedCount'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Completion Rate</strong></td>
                        <td>
                            <span class="completion-rate 
                                @if($stat['completionRate'] >= 70) high 
                                @elseif($stat['completionRate'] >= 40) medium 
                                @else low 
                                @endif">
                                {{ number_format($stat['completionRate'], 1) }}%
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Total Points Awarded</strong></td>
                        <td>{{ number_format($stat['totalPointsAwarded']) }}</td>
                    </tr>
                </table>
            </div>
        @endforeach
    @else
        <div class="no-data">
            No task data found for the selected filters.
        </div>
    @endif

    <!-- Summary Table -->
    @if(count($taskStats) > 0)
        <h2>Summary Table</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Task ID</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Points</th>
                    <th>Assigned</th>
                    <th>Completed</th>
                    <th>Completion Rate</th>
                    <th>Total Points Awarded</th>
                </tr>
            </thead>
            <tbody>
                @foreach($taskStats as $index => $stat)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $stat['task']->taskId }}</td>
                    <td>{{ Str::limit($stat['task']->title, 30) }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $stat['task']->task_type)) }}</td>
                    <td>{{ ucfirst($stat['task']->status) }}</td>
                    <td>{{ $stat['task']->points_awarded }}</td>
                    <td>{{ $stat['assignedCount'] }}</td>
                    <td>{{ $stat['completedCount'] }}</td>
                    <td>{{ number_format($stat['completionRate'], 1) }}%</td>
                    <td>{{ number_format($stat['totalPointsAwarded']) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>

