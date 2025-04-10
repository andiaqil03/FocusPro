<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Task Report</title>
    <style>
        body { font-family: DejaVu Sans; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; font-size: 12px; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <h2>Task Report ({{ ucfirst($status) }})</h2>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Due Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tasks as $task)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->category }}</td>
                    <td>{{ $task->priority }}</td>
                    <td>{{ ucfirst($task->status) }}</td>
                    <td>{{ $task->due_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
