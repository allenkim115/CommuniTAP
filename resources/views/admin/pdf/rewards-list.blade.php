<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rewards List - CommuniTAP</title>
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
        .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: 600; text-transform: uppercase; }
        .badge-active { background: #d1fae5; color: #065f46; }
        .badge-inactive { background: #fee2e2; color: #991b1b; }
        .progress { height: 6px; width: 100%; background: #e5e7eb; border-radius: 999px; overflow: hidden; margin-top: 4px; }
        .progress span { display: block; height: 100%; background: linear-gradient(90deg, #fb923c, #f97316); }
        .insight-box {
            background: #fff7ed;
            border-left: 4px solid #f97316;
            padding: 8px 12px;
            margin: 8px 0;
            font-size: 8pt;
            color: #92400e;
        }
        .no-data {
            border: 1px dashed #d1d5db;
            padding: 20px;
            text-align: center;
            color: #6b7280;
            font-style: italic;
            margin: 15px 0;
        }
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
                <div class="subtitle">Rewards Catalog &amp; Redemption Summary</div>
                <div class="header-info">
                    <strong>Report Generated:</strong> {{ now()->format('F d, Y \\a\\t g:i A') }}<br>
                    <strong>Reporting Period:</strong> {{ $periodLabel ?? 'Custom' }}<br>
                    <strong>Date Range:</strong> {{ \Carbon\Carbon::parse($startDate)->format('F d, Y') }} through {{ \Carbon\Carbon::parse($endDate)->format('F d, Y') }}<br>
                    <strong>Filters:</strong> Status={{ ucfirst($status) }}
                </div>
            </div>
        </div>
    </div>

    @php
        $redemptionRate = $inventoryTotal > 0 ? ($redemptionsThisPeriod / max($inventoryTotal, 1)) * 100 : 0;
        $claimedRewards = $rewardStats->sum('claimedRedemptions');
    @endphp

    <h2>Rewards Highlights</h2>
    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-label">Total Rewards</div>
            <div class="stat-value">{{ number_format($totalRewards) }}</div>
            <div class="stat-detail">
                Active: {{ number_format($activeRewards) }}
                ({{ $totalRewards > 0 ? number_format(($activeRewards / $totalRewards) * 100, 1) : 0 }}%)
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Redemption Rate</div>
            <div class="stat-value">{{ number_format($redemptionRate, 1) }}%</div>
            <div class="stat-detail">{{ number_format($redemptionsThisPeriod) }} redemptions</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">User Engagement</div>
            <div class="stat-value">{{ number_format($claimedRewards) }}</div>
            <div class="stat-detail">Rewards claimed all-time</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Points Redeemed</div>
            <div class="stat-value">{{ number_format($pointsRedeemed) }}</div>
            <div class="stat-detail">
                Avg: {{ $redemptionsThisPeriod > 0 ? number_format($pointsRedeemed / max($redemptionsThisPeriod, 1), 1) : 0 }} per redemption
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Pending Approvals</div>
            <div class="stat-value">{{ number_format($pendingGlobal) }}</div>
            <div class="stat-detail">Awaiting admin action</div>
        </div>
    </div>

    <h2>Catalog Overview</h2>

    @if($rewardStats->isEmpty())
        <div class="no-data">No rewards found for the requested filters.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Reward</th>
                    <th>Sponsor</th>
                    <th>Status</th>
                    <th>Points Cost</th>
                    <th>Inventory</th>
                    <th>Redemptions (Period)</th>
                    <th>Pending</th>
                    <th>Approved</th>
                    <th>Claimed</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rewardStats as $row)
                    <tr>
                        <td>
                            <strong>{{ $row['reward']->reward_name }}</strong><br>
                            Added: {{ optional($row['reward']->created_date)->format('M d, Y') ?? '—' }}
                        </td>
                        <td>{{ $row['reward']->sponsor_name ?? 'Community TAP' }}</td>
                        <td>
                            @if($row['reward']->status === 'active')
                                <span class="badge badge-active">Active</span>
                            @else
                                <span class="badge badge-inactive">{{ ucfirst($row['reward']->status ?? 'inactive') }}</span>
                            @endif
                        </td>
                        <td>{{ number_format($row['reward']->points_cost ?? 0) }} pts</td>
                        <td>
                            {{ number_format($row['inventoryRemaining']) }}
                            <div class="progress" style="margin-top:4px;">
                                @php
                                    $baseline = max($row['inventoryRemaining'] + $row['redemptionsInPeriod'], 1);
                                    $percent = min(100, ($row['inventoryRemaining'] / $baseline) * 100);
                                @endphp
                                <span style="width: {{ number_format($percent, 2) }}%;"></span>
                            </div>
                        </td>
                        <td>{{ number_format($row['redemptionsInPeriod']) }}</td>
                        <td>{{ number_format($row['pendingRedemptions']) }}</td>
                        <td>{{ number_format($row['approvedRedemptions']) }}</td>
                        <td>{{ number_format($row['claimedRedemptions']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="insight-box">
        Maintain at least a 30% inventory buffer for high-performing rewards to avoid redemption stalls.
    </div>
</body>
</html>

