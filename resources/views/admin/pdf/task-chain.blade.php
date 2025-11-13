<!DOCTYPE html>
<html>
<head>
    <title>Task Chain Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #111; margin: 20px; }
        h1 { font-size: 20px; margin-bottom: 5px; color: #7c3aed; }
        h2 { font-size: 14px; margin-top: 15px; margin-bottom: 8px; border-bottom: 2px solid #a855f7; padding-bottom: 3px; color: #7c3aed; }
        h3 { font-size: 12px; margin-top: 12px; margin-bottom: 6px; color: #6b21a8; }
        .header { margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #ccc; }
        .header-info { font-size: 10px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; page-break-inside: avoid; }
        th, td { border: 1px solid #999; padding: 6px 8px; text-align: left; font-size: 10px; }
        th { background: #a855f7; color: white; font-weight: bold; }
        tr:nth-child(even) { background-color: #f3f4f6; }
        .stat-box { display: inline-block; margin: 5px 10px 5px 0; padding: 8px 12px; background: #faf5ff; border: 1px solid #a855f7; border-radius: 4px; }
        .stat-label { font-size: 9px; color: #666; }
        .stat-value { font-size: 14px; font-weight: bold; color: #7c3aed; }
        .chain-section { margin-bottom: 20px; page-break-inside: avoid; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: #faf5ff; }
        .chain-link { display: flex; align-items: center; justify-content: space-between; margin: 8px 0; padding: 8px; background: white; border-radius: 4px; border-left: 4px solid #a855f7; }
        .chain-user { flex: 1; text-align: center; }
        .chain-arrow { margin: 0 10px; color: #7c3aed; font-weight: bold; }
        .no-data { text-align: center; padding: 20px; color: #999; font-style: italic; }
        .task-title { font-weight: bold; color: #6b21a8; margin-bottom: 5px; }
        .nomination-date { font-size: 9px; color: #666; }
        @media print {
            .page-break { page-break-after: always; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Task Chain Report</h1>
        <div class="header-info">
            <strong>Generated on:</strong> {{ now()->format('F d, Y H:i') }}<br>
            @if($startDate || $endDate)
                <strong>Date Range:</strong> 
                {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('M d, Y') : 'All' }} - 
                {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('M d, Y') : 'All' }}<br>
            @endif
        </div>
    </div>

    <!-- Overall Statistics -->
    <h2>Overall Statistics</h2>
    <div>
        <div class="stat-box">
            <div class="stat-label">Total Nominations</div>
            <div class="stat-value">{{ $totalNominations }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Unique Tasks</div>
            <div class="stat-value">{{ $uniqueTasks }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Unique Nominators</div>
            <div class="stat-value">{{ $uniqueNominators }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Unique Nominees</div>
            <div class="stat-value">{{ $uniqueNominees }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Total Participants</div>
            <div class="stat-value">{{ $uniqueParticipants }}</div>
        </div>
    </div>

    <!-- Task Chain Details -->
    <h2>Task Chain Details</h2>
    @if($taskChain->count() > 0)
        @foreach($chainsByTask as $taskId => $nominations)
            @php
                $task = $nominations->first()->task;
            @endphp
            <div class="chain-section">
                <div class="task-title">
                    Task #{{ $taskId }}: {{ $task->title }}
                </div>
                <div style="font-size: 9px; color: #666; margin-bottom: 10px;">
                    {{ Str::limit($task->description, 100) }} | Points: {{ $task->points_awarded }} | Type: {{ ucfirst($task->task_type) }}
                </div>
                
                <h3>Chain Links ({{ $nominations->count() }} nominations)</h3>
                
                @foreach($nominations as $nomination)
                    <div class="chain-link">
                        <div class="chain-user">
                            <strong>{{ $nomination->nominator->fullName }}</strong><br>
                            <span style="font-size: 9px; color: #666;">Nominator</span>
                        </div>
                        <div class="chain-arrow">→</div>
                        <div class="chain-user">
                            <strong>{{ $nomination->nominee->fullName }}</strong><br>
                            <span style="font-size: 9px; color: #666;">Nominee</span>
                        </div>
                        <div style="margin-left: 15px; font-size: 9px; color: #666; text-align: right;">
                            {{ $nomination->nomination_date->format('M d, Y H:i') }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    @else
        <div class="no-data">
            No task chain data found for the selected filters.
        </div>
    @endif

    <!-- Summary Table -->
    @if($taskChain->count() > 0)
        <h2>Summary Table</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nomination ID</th>
                    <th>Task</th>
                    <th>Nominator</th>
                    <th>Nominee</th>
                    <th>Nomination Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($taskChain as $index => $nomination)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $nomination->nominationId }}</td>
                    <td>{{ Str::limit($nomination->task->title, 30) }}</td>
                    <td>{{ $nomination->nominator->fullName }}</td>
                    <td>{{ $nomination->nominee->fullName }}</td>
                    <td>{{ $nomination->nomination_date->format('M d, Y H:i') }}</td>
                    <td>{{ ucfirst($nomination->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Chain Statistics by Task -->
    @if($chainsByTask->count() > 0)
        <h2>Chain Statistics by Task</h2>
        <table>
            <thead>
                <tr>
                    <th>Task ID</th>
                    <th>Task Title</th>
                    <th>Nominations Count</th>
                    <th>Unique Nominators</th>
                    <th>Unique Nominees</th>
                </tr>
            </thead>
            <tbody>
                @foreach($chainsByTask as $taskId => $nominations)
                    @php
                        $task = $nominations->first()->task;
                        $uniqueNominators = $nominations->pluck('FK2_nominatorId')->unique()->count();
                        $uniqueNominees = $nominations->pluck('FK3_nomineeId')->unique()->count();
                    @endphp
                    <tr>
                        <td>{{ $taskId }}</td>
                        <td>{{ Str::limit($task->title, 40) }}</td>
                        <td>{{ $nominations->count() }}</td>
                        <td>{{ $uniqueNominators }}</td>
                        <td>{{ $uniqueNominees }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>

