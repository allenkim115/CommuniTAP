<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tap &amp; Pass Nominations - CommuniTAP</title>
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
        .title-cell h1 { font-size: 16pt; font-weight: bold; color: #2c3e50; letter-spacing: 0.5px; }
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
        tr:nth-child(even) td { background: #f8fafc; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: 600; text-transform: uppercase; }
        .badge.pending { background: #fef3c7; color: #92400e; }
        .badge.accepted { background: #d1fae5; color: #065f46; }
        .badge.declined { background: #fee2e2; color: #991b1b; }
        .section-title { font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; margin: 18px 0 8px; color: #111827; }
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
    $totalNoms = $nominations->count();
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
                <div class="subtitle">Tap &amp; Pass Nominations Ledger</div>
                <div class="header-info">
                    <strong>Report Generated:</strong> {{ now()->format('F d, Y \\a\\t g:i A') }}<br>
                    <strong>Reporting Period:</strong> {{ $periodLabel ?? 'Custom' }}<br>
                    <strong>Date Range:</strong> {{ \Carbon\Carbon::parse($startDate)->format('F d, Y') }} through {{ \Carbon\Carbon::parse($endDate)->format('F d, Y') }}<br>
                    <strong>Status Filter:</strong> {{ ucfirst($status) }}
                </div>
            </div>
        </div>
    </div>

    @php
        $acceptRate = $totalNoms > 0 ? (($statusCounts['accepted'] ?? 0) / $totalNoms) * 100 : 0;
        $uniqueParticipants = $nominations->pluck('FK2_nominatorId')
            ->merge($nominations->pluck('FK3_nomineeId'))
            ->unique()
            ->count();
    @endphp

    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-label">Total Nominations</div>
            <div class="stat-value">{{ number_format($totalNoms) }}</div>
            <div class="stat-detail">Accepted: {{ number_format($statusCounts['accepted'] ?? 0) }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Task Completion Rate</div>
            <div class="stat-value">{{ number_format($acceptRate, 1) }}%</div>
            <div class="stat-detail">{{ number_format($statusCounts['accepted'] ?? 0) }} / {{ number_format($totalNoms) }} accepted</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">User Engagement</div>
            <div class="stat-value">{{ number_format($uniqueParticipants) }}</div>
            <div class="stat-detail">Unique nominators / nominees</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Total Chain Links</div>
            <div class="stat-value">{{ number_format($taskBreakdown->count()) }}</div>
            <div class="stat-detail">Tasks with nominations</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Active Reviews</div>
            <div class="stat-value">{{ number_format($statusCounts['pending'] ?? 0) }}</div>
            <div class="stat-detail">Awaiting moderator action</div>
        </div>
    </div>

    <div class="section-title">Nomination Ledger</div>
    @if($nominations->isEmpty())
        <div class="no-data">No nominations found for the selected filters.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Task</th>
                    <th>Nominator</th>
                    <th>Nominee</th>
                    <th>Status</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($nominations as $nomination)
                    <tr>
                        <td>{{ optional($nomination->nomination_date)->format('M d, Y g:i A') ?? '—' }}</td>
                        <td>
                            <strong>{{ optional($nomination->task)->title ?? 'Task #'.$nomination->FK1_taskId }}</strong><br>
                            Type: {{ optional($nomination->task)->task_type ?? '—' }}
                        </td>
                        <td>
                            {{ optional($nomination->nominator)->fullName ?? 'User #'.$nomination->FK2_nominatorId }}<br>
                            ID: #{{ $nomination->FK2_nominatorId }}
                        </td>
                        <td>
                            {{ optional($nomination->nominee)->fullName ?? 'User #'.$nomination->FK3_nomineeId }}<br>
                            ID: #{{ $nomination->FK3_nomineeId }}
                        </td>
                        <td>
                            <span class="badge {{ $nomination->status }}">{{ ucfirst($nomination->status) }}</span>
                        </td>
                        <td>
                            @if($nomination->status === 'accepted')
                                Recognized for timely completion of a daily task chain.
                            @elseif($nomination->status === 'declined')
                                Declined &mdash; review rationale with nominator.
                            @else
                                Pending moderator decision.
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="section-title">Top Contributors</div>
    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Highlights</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Top Nominators</strong></td>
                <td>
                    <table class="mini-table">
                        @forelse($topNominators as $entry)
                            @php
                                $userModel = $entry['user'];
                                $fallbackId = $entry['userId'] ?? null;
                                $userLabel = $userModel
                                    ? ($userModel->fullName ?? trim(($userModel->firstName ?? '').' '.($userModel->lastName ?? '')))
                                    : ($fallbackId ? 'User #'.$fallbackId : 'N/A');
                            @endphp
                            <tr>
                                <td>{{ $userLabel }}</td>
                                <td>{{ number_format($entry['count']) }} nominations</td>
                            </tr>
                        @empty
                            <tr><td colspan="2">No data</td></tr>
                        @endforelse
                    </table>
                </td>
            </tr>
            <tr>
                <td><strong>Top Nominees</strong></td>
                <td>
                    <table class="mini-table">
                        @forelse($topNominees as $entry)
                            @php
                                $userModel = $entry['user'];
                                $fallbackId = $entry['userId'] ?? null;
                                $userLabel = $userModel
                                    ? ($userModel->fullName ?? trim(($userModel->firstName ?? '').' '.($userModel->lastName ?? '')))
                                    : ($fallbackId ? 'User #'.$fallbackId : 'N/A');
                            @endphp
                            <tr>
                                <td>{{ $userLabel }}</td>
                                <td>{{ number_format($entry['count']) }} nominations</td>
                            </tr>
                        @empty
                            <tr><td colspan="2">No data</td></tr>
                        @endforelse
                    </table>
                </td>
            </tr>
            <tr>
                <td><strong>Task Hotspots</strong></td>
                <td>
                    <table class="mini-table">
                        @forelse($taskBreakdown as $entry)
                            @php
                                $taskModel = $entry['task'];
                                $fallbackTask = $entry['taskId'] ?? null;
                                $taskLabel = $taskModel
                                    ? ($taskModel->title ?? 'Task #'.$taskModel->taskId)
                                    : ($fallbackTask ? 'Task #'.$fallbackTask : 'N/A');
                            @endphp
                            <tr>
                                <td>{{ $taskLabel }}</td>
                                <td>{{ number_format($entry['count']) }} nominations</td>
                            </tr>
                        @empty
                            <tr><td colspan="2">No data</td></tr>
                        @endforelse
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

    <p style="font-size:10px; color:#4b5563;">
        Recommendation: follow up on pending nominations older than 3 days to keep recognition loops timely.
    </p>
</body>
</html>

