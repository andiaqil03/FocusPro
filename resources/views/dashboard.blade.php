@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold text-white mb-6">Dashboard Overview</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 text-center">
        <div class="bg-blue-500 text-white p-6 rounded-lg shadow-md">
            <p class="text-lg font-semibold">Total Tasks</p>
            <h3 class="text-3xl font-bold mt-2">{{ $totalTasks }}</h3>
        </div>

        <div class="bg-green-500 text-white p-6 rounded-lg shadow-md">
            <p class="text-lg font-semibold">Completed Tasks</p>
            <h3 class="text-3xl font-bold mt-2">{{ $completedTasks }}</h3>
        </div>

        <div class="bg-purple-500 text-white p-6 rounded-lg shadow-md">
            <p class="text-lg font-semibold">Pomodoro Sessions</p>
            <h3 class="text-3xl font-bold mt-2">{{ $totalSessions }}</h3>
        </div>

        <div class="bg-yellow-500 text-white p-6 rounded-lg shadow-md">
            <p class="text-lg font-semibold">Total Cycles</p>
            <h3 class="text-3xl font-bold mt-2">{{ $totalCycles }}</h3>
        </div>
    </div>
</div>
@endsection
