<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h1, h2, h3 { color: #111; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid #333; }
        th, td { padding: 5px; text-align: left; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h1>Admin Dashboard Report</h1>
    <h2>Overview</h2>
    <ul>
        <li>Total Users: {{ $totalUsers }}</li>
        <li>Active Users: {{ $activeUsers }}</li>
        <li>Total Tasks: {{ $totalTasks }}</li>
        <li>Total Points: {{ $totalPoints }}</li>
    </ul>

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
                <td>{{ $user->status }}</td>
                <td>{{ $user->points }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

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
                <td>{{ $task->task_type }}</td>
                <td>{{ $task->status }}</td>
                <td>{{ $task->assignedUsers->pluck('name')->join(', ') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
