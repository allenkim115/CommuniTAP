<!DOCTYPE html>
<html>
<head>
    <title>Task Chain Report - CommuniTAP</title>
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
            color: #495057;
            margin-top: 8px;
            margin-bottom: 4px;
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
                <h1>TASK CHAIN REPORT</h1>
                <div class="subtitle">Tap & Pass Nomination Chain Analysis</div>
                <div class="header-info">
            <strong>Report Generated:</strong> {{ now()->format('F d, Y \a\t g:i A') }}<br>
            @if(isset($periodLabel))
            <strong>Reporting Period:</strong> {{ $periodLabel }}<br>
            @endif
            @if($startDate || $endDate)
            <strong>Date Range:</strong> 
            {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('F d, Y') : 'All' }} through 
            {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('F d, Y') : 'All' }}
            @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Overall Statistics -->
    <h2>Summary Statistics</h2>
    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-label">Total Nominations</div>
            <div class="stat-value">{{ number_format($totalNominations) }}</div>
            @php
                $avgNominationsPerTask = $uniqueTasks > 0 ? round($totalNominations / $uniqueTasks, 1) : 0;
            @endphp
            <div class="metric-comparison">Avg: {{ $avgNominationsPerTask }} per task</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Unique Tasks</div>
            <div class="stat-value">{{ number_format($uniqueTasks) }}</div>
            @php
                $taskParticipationRate = $uniqueTasks > 0 ? round(($uniqueParticipants / $uniqueTasks) * 10, 1) : 0;
            @endphp
            <div class="metric-comparison">{{ $taskParticipationRate }} participants per task</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Unique Nominators</div>
            <div class="stat-value">{{ number_format($uniqueNominators) }}</div>
            @php
                $nominatorActivity = $uniqueNominators > 0 ? round($totalNominations / $uniqueNominators, 1) : 0;
            @endphp
            <div class="metric-comparison">Avg: {{ $nominatorActivity }} nominations each</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Unique Nominees</div>
            <div class="stat-value">{{ number_format($uniqueNominees) }}</div>
            @php
                $nomineeFrequency = $uniqueNominees > 0 ? round($totalNominations / $uniqueNominees, 1) : 0;
            @endphp
            <div class="metric-comparison">Avg: {{ $nomineeFrequency }} nominations received</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Total Participants</div>
            <div class="stat-value">{{ number_format($uniqueParticipants) }}</div>
            @php
                $engagementRate = $uniqueParticipants > 0 ? round((($uniqueNominators + $uniqueNominees) / ($uniqueParticipants * 2)) * 100, 1) : 0;
            @endphp
            <div class="metric-comparison">Engagement: {{ $engagementRate }}%</div>
        </div>
    </div>

    <!-- Key Insights -->
    @php
        $chainEfficiency = $uniqueTasks > 0 ? round($totalNominations / $uniqueTasks, 1) : 0;
        $nominationRatio = $uniqueNominators > 0 ? round($uniqueNominees / $uniqueNominators, 2) : 0;
        $participationSpread = $uniqueParticipants > 0 ? round((($uniqueNominators + $uniqueNominees) / $uniqueParticipants) * 100, 1) : 0;
    @endphp
    <h2>Key Insights & Analysis</h2>
    <div class="insight-box">
        <strong>Chain Efficiency:</strong>
        @if($chainEfficiency >= 5)
            High chain activity with {{ $chainEfficiency }} nominations per task on average. This indicates strong engagement and effective task propagation.
        @elseif($chainEfficiency >= 2)
            Moderate chain activity with {{ $chainEfficiency }} nominations per task. Consider strategies to increase nomination frequency.
        @else
            Low chain activity with {{ $chainEfficiency }} nominations per task. Review task appeal and nomination incentives.
        @endif
    </div>
    <div class="insight-box">
        <strong>Nomination Balance:</strong>
        @if($nominationRatio >= 0.8 && $nominationRatio <= 1.2)
            Balanced nomination flow ({{ $nominationRatio }}:1 nominee-to-nominator ratio). Healthy participation distribution.
        @elseif($nominationRatio > 1.2)
            More nominees than nominators ({{ $nominationRatio }}:1). Some volunteers are receiving multiple nominations, indicating high recognition.
        @else
            More nominators than nominees ({{ $nominationRatio }}:1). Consider encouraging more diverse nomination patterns.
        @endif
    </div>
    <div class="insight-box">
        <strong>Participation Spread:</strong>
        @if($participationSpread >= 80)
            Excellent participation spread ({{ $participationSpread }}%). Most participants are actively involved in nominations.
        @elseif($participationSpread >= 60)
            Good participation spread ({{ $participationSpread }}%). Majority of participants are engaged.
        @else
            Limited participation spread ({{ $participationSpread }}%). Focus on increasing engagement among inactive participants.
        @endif
    </div>

    <!-- Summary Table -->
    @if($taskChain->count() > 0)
    <h2>Detailed Nomination Chain</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 4%;">#</th>
                <th style="width: 9%;">Nomination ID</th>
                <th style="width: 22%;">Task Title</th>
                <th style="width: 18%;">Nominator</th>
                <th style="width: 18%;">Nominee</th>
                <th style="width: 14%;">Nomination Date</th>
                <th style="width: 8%;" class="text-center">Status</th>
                <th style="width: 7%;" class="text-center">Tier</th>
            </tr>
        </thead>
        <tbody>
            @php
                $avgChainLength = $uniqueTasks > 0 ? $totalNominations / $uniqueTasks : 0;
            @endphp
            @foreach($taskChain as $index => $nomination)
                @php
                    $taskNominations = $taskChain->where('FK1_taskId', $nomination->FK1_taskId)->count();
                    $chainTier = $taskNominations >= ($avgChainLength * 1.5) ? 'high' : ($taskNominations >= $avgChainLength ? 'medium' : 'low');
                @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $nomination->nominationId }}</td>
                <td><strong>{{ Str::limit($nomination->task->title, 30) }}</strong></td>
                <td>{{ $nomination->nominator->fullName }}</td>
                <td>{{ $nomination->nominee->fullName }}</td>
                <td>{{ $nomination->nomination_date->format('M d, Y g:i A') }}</td>
                <td class="text-center">{{ ucfirst($nomination->status) }}</td>
                <td class="text-center">
                    <span class="performance-tier tier-{{ $chainTier }}">
                        {{ ucfirst($chainTier) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Chain Statistics by Task -->
    @if($chainsByTask->count() > 0)
    <h2>Chain Performance by Task</h2>
    @php
        $avgNominationsPerTask = $chainsByTask->count() > 0 ? $chainsByTask->map->count()->avg() : 0;
        $sortedChains = $chainsByTask->sortByDesc(function($nominations) {
            return $nominations->count();
        });
    @endphp
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">Rank</th>
                <th style="width: 8%;">Task ID</th>
                <th style="width: 30%;">Task Title</th>
                <th style="width: 12%;" class="text-center">Nominations</th>
                <th style="width: 12%;" class="text-center">Nominators</th>
                <th style="width: 12%;" class="text-center">Nominees</th>
                <th style="width: 10%;" class="text-center">Efficiency</th>
                <th style="width: 11%;" class="text-center">Performance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sortedChains as $rank => $nominations)
                @php
                    $task = $nominations->first()->task;
                    $uniqueNominators = $nominations->pluck('FK2_nominatorId')->unique()->count();
                    $uniqueNominees = $nominations->pluck('FK3_nomineeId')->unique()->count();
                    $nominationCount = $nominations->count();
                    $chainEfficiency = $uniqueNominators > 0 ? round($nominationCount / $uniqueNominators, 2) : 0;
                    $performanceTier = $nominationCount >= ($avgNominationsPerTask * 1.5) ? 'high' : ($nominationCount >= $avgNominationsPerTask ? 'medium' : 'low');
                    $vsAverage = $avgNominationsPerTask > 0 ? round((($nominationCount - $avgNominationsPerTask) / $avgNominationsPerTask) * 100, 1) : 0;
                @endphp
                <tr>
                    <td class="text-center"><strong>#{{ $rank + 1 }}</strong></td>
                    <td>{{ $task->taskId }}</td>
                    <td><strong>{{ Str::limit($task->title, 35) }}</strong></td>
                    <td class="text-center"><strong>{{ number_format($nominationCount) }}</strong></td>
                    <td class="text-center">{{ number_format($uniqueNominators) }}</td>
                    <td class="text-center">{{ number_format($uniqueNominees) }}</td>
                    <td class="text-center">{{ $chainEfficiency }}x</td>
                    <td class="text-center">
                        <span class="performance-tier tier-{{ $performanceTier }}">
                            {{ ucfirst($performanceTier) }}
                        </span>
                        @if($vsAverage != 0)
                            <div class="metric-comparison {{ $vsAverage > 0 ? 'positive' : 'negative' }}">
                                {{ $vsAverage > 0 ? '+' : '' }}{{ $vsAverage }}% vs avg
                            </div>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Top Performers Summary -->
    <h3>Top Performing Task Chains</h3>
    <div class="stats-grid" style="margin-top: 5px;">
        @php
            $top3Tasks = $sortedChains->take(3);
        @endphp
        @foreach($top3Tasks as $index => $nominations)
            @php
                $task = $nominations->first()->task;
                $nominationCount = $nominations->count();
            @endphp
            <div class="stat-item">
                <div class="stat-label">#{{ $index + 1 }} Task Chain</div>
                <div class="stat-value" style="font-size: 12pt;">{{ number_format($nominationCount) }}</div>
                <div class="stat-detail">{{ Str::limit($task->title, 25) }}</div>
            </div>
        @endforeach
        <div class="stat-item">
            <div class="stat-label">Average Chain Length</div>
            <div class="stat-value" style="font-size: 12pt;">{{ number_format(round($avgNominationsPerTask, 1)) }}</div>
            <div class="stat-detail">Across all tasks</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Chain Distribution</div>
            <div class="stat-value" style="font-size: 12pt;">
                @php
                    $highPerforming = $sortedChains->filter(function($n) use ($avgNominationsPerTask) {
                        return $n->count() >= ($avgNominationsPerTask * 1.5);
                    })->count();
                    $highPercent = $sortedChains->count() > 0 ? round(($highPerforming / $sortedChains->count()) * 100, 1) : 0;
                @endphp
                {{ $highPercent }}%
            </div>
            <div class="stat-detail">High performing chains</div>
        </div>
    </div>
    @endif

    <div class="footer">
        <p>This report was generated automatically by the CommuniTAP Administrative System.</p>
        <p>For questions or concerns, please contact the system administrator.</p>
    </div>
</body>
</html>
