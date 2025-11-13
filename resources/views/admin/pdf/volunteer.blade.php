<!DOCTYPE html>
<html>
<head>
    <title>Volunteer Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #111; margin: 20px; }
        h1 { font-size: 20px; margin-bottom: 5px; color: #1e40af; }
        h2 { font-size: 14px; margin-top: 15px; margin-bottom: 8px; border-bottom: 2px solid #3b82f6; padding-bottom: 3px; color: #1e40af; }
        .header { margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #ccc; }
        .header-info { font-size: 10px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; page-break-inside: avoid; }
        th, td { border: 1px solid #999; padding: 6px 8px; text-align: left; font-size: 10px; }
        th { background: #3b82f6; color: white; font-weight: bold; }
        tr:nth-child(even) { background-color: #f3f4f6; }
        .stat-box { display: inline-block; margin: 5px 10px 5px 0; padding: 8px 12px; background: #eff6ff; border: 1px solid #3b82f6; border-radius: 4px; }
        .stat-label { font-size: 9px; color: #666; }
        .stat-value { font-size: 14px; font-weight: bold; color: #1e40af; }
        .section-title { margin-top: 20px; font-size: 12px; font-weight: bold; color: #333; background: #e5e7eb; padding: 5px 8px; }
        .user-section { margin-bottom: 20px; page-break-inside: avoid; }
        .user-header { background: #dbeafe; padding: 8px; font-weight: bold; border-left: 4px solid #3b82f6; }
        .no-data { text-align: center; padding: 20px; color: #999; font-style: italic; }
        @media print {
            .page-break { page-break-after: always; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Volunteer Report</h1>
        <div class="header-info">
            <strong>Generated on:</strong> {{ now()->format('F d, Y H:i') }}<br>
            @if($startDate || $endDate)
                <strong>Date Range:</strong> 
                {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('M d, Y') : 'All' }} - 
                {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('M d, Y') : 'All' }}<br>
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
            <div class="stat-label">Total Volunteers</div>
            <div class="stat-value">{{ $totalUsers }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Active Volunteers</div>
            <div class="stat-value">{{ $activeUsers }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Total Points</div>
            <div class="stat-value">{{ number_format($totalPoints) }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Reported Volunteers</div>
            <div class="stat-value">{{ count($userStats) }}</div>
        </div>
    </div>

    <!-- Volunteer Details -->
    <h2>Volunteer Details</h2>
    @if(count($userStats) > 0)
        @foreach($userStats as $index => $stat)
            <div class="user-section {{ $index > 0 && $index % 3 == 0 ? 'page-break' : '' }}">
                <div class="user-header">
                    {{ $stat['user']->fullName }} (ID: {{ $stat['user']->userId }})
                </div>
                <table>
                    <tr>
                        <th style="width: 30%;">Field</th>
                        <th style="width: 70%;">Value</th>
                    </tr>
                    <tr>
                        <td><strong>Email</strong></td>
                        <td>{{ $stat['user']->email }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>{{ ucfirst($stat['user']->status) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Points</strong></td>
                        <td>{{ number_format($stat['user']->points) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Date Registered</strong></td>
                        <td>{{ $stat['user']->date_registered ? \Carbon\Carbon::parse($stat['user']->date_registered)->format('M d, Y') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tasks Assigned</strong></td>
                        <td>{{ $stat['totalTasksAssigned'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tasks Completed</strong></td>
                        <td>{{ $stat['totalTasksCompleted'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Points Earned</strong></td>
                        <td>{{ number_format($stat['totalPointsEarned']) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nominations Made</strong></td>
                        <td>{{ $stat['nominationsMade'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nominations Received</strong></td>
                        <td>{{ $stat['nominationsReceived'] }}</td>
                    </tr>
                </table>
            </div>
        @endforeach
    @else
        <div class="no-data">
            No volunteer data found for the selected filters.
        </div>
    @endif

    <!-- Summary Table -->
    @if(count($userStats) > 0)
        <h2>Summary Table</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Points</th>
                    <th>Tasks Assigned</th>
                    <th>Tasks Completed</th>
                    <th>Points Earned</th>
                </tr>
            </thead>
            <tbody>
                @foreach($userStats as $index => $stat)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $stat['user']->fullName }}</td>
                    <td>{{ $stat['user']->email }}</td>
                    <td>{{ ucfirst($stat['user']->status) }}</td>
                    <td>{{ number_format($stat['user']->points) }}</td>
                    <td>{{ $stat['totalTasksAssigned'] }}</td>
                    <td>{{ $stat['totalTasksCompleted'] }}</td>
                    <td>{{ number_format($stat['totalPointsEarned']) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>

