<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard PDF</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #111; margin: 20px; }
        h1 { font-size: 20px; margin-bottom: 10px; }
        h2 { font-size: 16px; margin-top: 20px; margin-bottom: 10px; border-bottom: 1px solid #ccc; padding-bottom: 3px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #999; padding: 6px 8px; text-align: left; }
        th { background: #f0f0f0; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        ul { list-style: none; padding-left: 0; }
        li { margin-bottom: 4px; }
        .section-title { margin-top: 30px; font-size: 14px; font-weight: bold; color: #333; }
    </style>
</head>
<body>
    <h1>Admin Dashboard Report</h1>
    <p><strong>Generated on:</strong> {{ now()->format('F d, Y H:i') }}</p>

    <!-- Overview -->
    <h2>Overview</h2>
    <ul>
        <li><strong>Total Users:</strong> {{ $totalUsers }}</li>
        <li><strong>Active Users:</strong> {{ $activeUsers }}</li>
        <li><strong>Total Tasks:</strong> {{ $totalTasks }}</li>
        <li><strong>Total Points:</strong> {{ $totalPoints }}</li>
        <li><strong>Total Rewards:</strong> {{ $totalRewards }}</li>
    </ul>

    <!-- Users -->
    <h2>User List</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Points</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ ucfirst($user->status) }}</td>
                <td>{{ $user->points }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tasks -->
    <h2>Tasks List</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Type</th>
                <th>Status</th>
                <th>Assigned To</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $index => $task)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $task->title }}</td>
                <td>{{ ucfirst($task->task_type) }}</td>
                <td>{{ ucfirst($task->status) }}</td>
                <td>{{ $task->assignedUsers->pluck('name')->join(', ') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Rewards -->
    <h2>Rewards List</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Sponsor</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Points Needed</th>
                <th>Status</th>
                <th>Last Updated</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rewards as $index => $reward)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $reward->sponsor_name }}</td>
                <td>{{ $reward->reward_name }}</td>
                <td>{{ $reward->QTY }}</td>
                <td>{{ $reward->points_cost }}</td>
                <td>{{ ucfirst($reward->status) }}</td>
                <td>{{ $reward->last_update_date ? $reward->last_update_date->format('M d, Y') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
