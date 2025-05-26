
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Analytics Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333;
            font-size: 14px;
        }
        h1, h3 {
            text-align: center;
            margin-bottom: 20px;
        }
        .summary, .section {
            margin-bottom: 30px;
        }
        .section h4 {
            margin-bottom: 5px;
            color: #555;
        }
        .stats {
            border-collapse: collapse;
            width: 100%;
        }
        .stats th, .stats td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        .stats th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <h1>FocusPro Productivity Report</h1>

    <div class="summary">
        <h3>Summary</h3>
        <table class="stats">
            <tr><th>Total Pomodoro Sessions</th><td>{{ $totalSessions }}</td></tr>
            <tr><th>Total Cycles Completed</th><td>{{ $totalCycles }}</td></tr>
            <tr><th>Total Work Time</th><td>{{ $totalWorkTime }} mins</td></tr>
            <tr><th>Total Break Time</th><td>{{ $totalBreakTime }} mins</td></tr>
            <tr><th>Average Session Duration</th><td>{{ number_format($averageSessionDuration, 2) }} mins</td></tr>
        </table>
    </div>

    <div class="section">
        <h3>Task Completion</h3>
        <table class="stats">
            <tr><th>Total Tasks</th><td>{{ $totalTasks }}</td></tr>
            <tr><th>Completed Tasks</th><td>{{ $completedTasks }}</td></tr>
            <tr><th>Completion Rate</th><td>{{ $completionRate }}%</td></tr>
        </table>
    </div>

    <div class="section">
        <h3>Session Trends (Last {{ $dateRange == '30_days' ? '30' : '7' }} Days)</h3>
        <table class="stats">
            <thead>
                <tr><th>Date</th><th>Session Count</th></tr>
            </thead>
            <tbody>
                @foreach ($dailySessions as $session)
                <tr>
                    <td>{{ $session['date'] }}</td>
                    <td>{{ $session['sessions'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
