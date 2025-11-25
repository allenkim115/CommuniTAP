<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Incident Reports - CommuniTAP</title>
    <style>
        @page { margin: 0.5cm 1cm; size: A4 landscape; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', serif; font-size: 11pt; color: #1a1a1a; line-height: 1.6; }
        .header { border-bottom: 2px solid #2c3e50; padding-bottom: 5px; margin-bottom: 8px; display: table; width: 100%; }
        .header-content { display: table-row; }
        .logo-cell { display: table-cell; width: 150px; text-align: center; vertical-align: middle; padding-right: 10px; }
        .logo-cell img { max-width: 140px; height: auto; }
        .title-cell { display: table-cell; vertical-align: middle; padding-left: 5px; }
        .title-cell h1 { font-size: 16pt; font-weight: bold; color: #2c3e50; letter-spacing: 0.5px; }
        .title-cell .subtitle { font-size: 8pt; color: #555; margin-bottom: 3px; }
        .header-info { font-size: 7pt; color: #666; line-height: 1.3; }
        .header-info strong { color: #2c3e50; }
        h2 { font-size: 12pt; font-weight: bold; color: #2c3e50; margin-top: 8px; margin-bottom: 3px; padding-bottom: 2px; border-bottom: 1px solid #e0e0e0; text-transform: uppercase; letter-spacing: 0.5px; }
        .stats-grid { display: table; width: 100%; border-collapse: separate; border-spacing: 5px; margin-bottom: 8px; }
        .stat-item { display: table-cell; width: 20%; padding: 8px 10px; background: #f8f9fa; border: 1px solid #dee2e6; border-left: 3px solid #2c3e50; vertical-align: top; }
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
        .badge.under_review { background: #dbeafe; color: #1d4ed8; }
        .badge.resolved { background: #d1fae5; color: #065f46; }
        .badge.dismissed { background: #f3e8ff; color: #6b21a8; }
        .no-data { border: 1px dashed #cbd5f5; padding: 20px; text-align: center; color: #64748b; font-style: italic; }
        .type-list { columns: 2; column-gap: 20px; }
        .type-list li { list-style: none; margin-bottom: 6px; font-size: 10px; }
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
    $totalReports = $incidents->count();
    $pendingCount = $statusCounts['pending'] ?? 0;
    $resolvedCount = $statusCounts['resolved'] ?? 0;
    $resolutionRate = $totalReports > 0 ? ($resolvedCount / $totalReports) * 100 : 0;
    $uniqueReporters = $incidents->pluck('FK1_reporterId')->unique()->count();
    $topType = $typeCounts->sortDesc()->keys()->first();
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
                <div class="subtitle">Incident Reports &amp; Moderation Summary</div>
                <div class="header-info">
                    <strong>Report Generated:</strong> {{ now()->format('F d, Y \\a\\t g:i A') }}<br>
                    <strong>Reporting Period:</strong> {{ $periodLabel ?? 'Custom' }}<br>
                    <strong>Date Range:</strong> {{ \Carbon\Carbon::parse($startDate)->format('F d, Y') }} through {{ \Carbon\Carbon::parse($endDate)->format('F d, Y') }}<br>
                    <strong>Filters:</strong> Status={{ ucfirst($status) }}, Type={{ $incidentType === 'all' ? 'All' : ucfirst(str_replace('_', ' ', $incidentType)) }}
                </div>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-label">Total Reports</div>
            <div class="stat-value">{{ number_format($totalReports) }}</div>
            <div class="stat-detail">Pending: {{ number_format($pendingCount) }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Resolution Rate</div>
            <div class="stat-value">{{ number_format($resolutionRate, 1) }}%</div>
            <div class="stat-detail">{{ number_format($resolvedCount) }} resolved</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">User Engagement</div>
            <div class="stat-value">{{ number_format($uniqueReporters) }}</div>
            <div class="stat-detail">Unique reporters this period</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Average Resolution</div>
            <div class="stat-value">{{ $averageResolutionHours !== null ? number_format($averageResolutionHours, 1).'h' : 'N/A' }}</div>
            <div class="stat-detail">Median: {{ $medianResolutionHours !== null ? number_format($medianResolutionHours, 1).'h' : 'N/A' }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Overdue Reports</div>
            <div class="stat-value">{{ number_format($overdueCount) }}</div>
            <div class="stat-detail">Open &gt; 7 days</div>
        </div>
    </div>

    <h2>Case Ledger</h2>
    @if($incidents->isEmpty())
        <div class="no-data">No incident reports matched the current filters.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Report Date</th>
                    <th>Reporter</th>
                    <th>Reported User</th>
                    <th>Incident Type</th>
                    <th>Status</th>
                    <th>Task</th>
                    <th>Moderator</th>
                    <th>Action</th>
                    <th>Resolution Time (hrs)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($incidents as $incident)
                    @php
                        $resolutionHours = null;
                        if ($incident->moderation_date && $incident->report_date) {
                            $start = $incident->report_date;
                            $end = $incident->moderation_date;
                            if ($end->lessThan($start)) {
                                [$start, $end] = [$end, $start];
                            }
                            $resolutionHours = $end->diffInHours($start);
                        }
                    @endphp
                    <tr>
                        <td>#{{ $incident->reportId }}</td>
                        <td>{{ optional($incident->report_date)->format('M d, Y g:i A') ?? '—' }}</td>
                        <td>{{ optional($incident->reporter)->fullName ?? 'User #'.$incident->FK1_reporterId }}</td>
                        <td>{{ optional($incident->reportedUser)->fullName ?? 'User #'.$incident->FK2_reportedUserId }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $incident->incident_type ?? 'unknown')) }}</td>
                        <td><span class="badge {{ $incident->status }}">{{ ucfirst(str_replace('_', ' ', $incident->status)) }}</span></td>
                        <td>{{ optional($incident->task)->title ?? ($incident->FK3_taskId ? 'Task #'.$incident->FK3_taskId : '—') }}</td>
                        <td>{{ optional($incident->moderator)->fullName ?? ($incident->FK4_moderatorId ? 'User #'.$incident->FK4_moderatorId : '—') }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $incident->action_taken ?? 'pending')) }}</td>
                        <td>{{ $resolutionHours !== null ? number_format($resolutionHours, 1) : '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h2>Incident Mix</h2>
    @if($typeCounts->isEmpty())
        <div class="no-data">No categorized incidents to display.</div>
    @else
        <ul class="type-list">
            @foreach($typeCounts as $type => $count)
                <li><strong>{{ ucfirst(str_replace('_', ' ', $type ?? 'unknown')) }}:</strong> {{ number_format($count) }} cases</li>
            @endforeach
        </ul>
    @endif

    <p style="font-size:10px; color:#475569;">
        Recommendation: escalate any pending incident older than one week and provide interim communication to reporters to maintain trust.
    </p>
</body>
</html>

