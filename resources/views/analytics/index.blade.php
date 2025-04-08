@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">Productivity Analytics</h2>

    <!-- Date Filter -->
    <div class="text-center mb-6">
        <label for="dateRange" class="text-lg font-semibold text-gray-900">Filter by:</label>
        <select id="dateRange" class="p-2 rounded border border-gray-400" onchange="updateDateFilter()">
            <option value="7_days" {{ $dateRange == '7_days' ? 'selected' : '' }}>Last 7 Days</option>
            <option value="30_days" {{ $dateRange == '30_days' ? 'selected' : '' }}>Last 30 Days</option>
        </select>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold text-gray-900">Total Focus Data</h3>
        <p class="text-lg text-gray-700 mt-2">Total Sessions: <strong>{{ $totalSessions }}</strong></p>
        <p class="text-lg text-gray-700">Total Cycles Completed: <strong>{{ $totalCycles }}</strong></p>
        <p class="text-lg text-gray-700">Average Focus Time per Session: <strong>{{ number_format($averageSessionDuration, 2) }} mins</strong></p>

        <!-- Chart for Daily Pomodoro Sessions -->
        <canvas id="dailySessionsChart" class="mt-6"></canvas>

        <!-- Pie Chart for Work vs. Break Time -->
        <h3 class="text-xl font-semibold text-gray-900 mt-8">Work vs. Break Time</h3>
        <canvas id="workBreakChart" class="mt-4"></canvas>

        <!-- Pie Chart for Task Completion -->
        <h3 class="text-xl font-semibold text-gray-900 mt-8">Task Completion Rate</h3>
        <canvas id="taskCompletionChart" class="mt-4"></canvas>

        <!-- Analytics Dashboard will display Total Tasks, Completed Tasks and Task Completion Rate -->
        <h3 class="text-xl font-semibold text-gray-900 mt-6">Task Completion</h3>
        <p class="text-lg text-gray-700">Total Tasks: <strong>{{ $totalTasks }}</strong></p>
        <p class="text-lg text-gray-700">Completed Tasks: <strong>{{ $completedTasks }}</strong></p>
        <p class="text-lg text-gray-700">Completion Rate: <strong>{{ $completionRate }}%</strong></p>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const dailySessions = @json($dailySessions);

    // Daily Sessions Bar Chart
    const ctx = document.getElementById('dailySessionsChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dailySessions.map(d => d.date),
            datasets: [{
                label: 'Sessions per Day',
                data: dailySessions.map(d => d.sessions),
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Work vs. Break Pie Chart
    const workTime = {{ $totalWorkTime }};
    const breakTime = {{ $totalBreakTime }};

    // Task Completion Pie Chart
    const completedTasks = {{ $completedTasks }};
    const incompleteTasks = {{ $totalTasks - $completedTasks }};

    const ctxTask = document.getElementById('taskCompletionChart').getContext('2d');
    new Chart(ctxTask, {
        type: 'pie',
        data: {
            labels: ['Completed Tasks', 'Incomplete Tasks'],
            datasets: [{
                data: [completedTasks, incompleteTasks],
                backgroundColor: ['rgba(75, 192, 192, 0.6)', 'rgba(255, 159, 64, 0.6)'],
                borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 159, 64, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });


    const ctxPie = document.getElementById('workBreakChart').getContext('2d');
    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['Work Time', 'Break Time'],
            datasets: [{
                data: [workTime, breakTime],
                backgroundColor: ['rgba(75, 192, 192, 0.6)', 'rgba(255, 99, 132, 0.6)'],
                borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });

    // Update Date Filter Function
    function updateDateFilter() {
        const selectedRange = document.getElementById('dateRange').value;
        window.location.href = `?date_range=${selectedRange}`;
    }
</script>
@endsection
