@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">Productivity Analytics</h2>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold text-gray-900">Total Focus Data</h3>
        <p class="text-lg text-gray-700 mt-2">Total Sessions: <strong>{{ $totalSessions }}</strong></p>
        <p class="text-lg text-gray-700">Total Cycles Completed: <strong>{{ $totalCycles }}</strong></p>

        <!-- Chart for daily Pomodoro sessions -->
        <canvas id="dailySessionsChart" class="mt-6"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const dailySessions = @json($dailySessions);

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
</script>
@endsection
