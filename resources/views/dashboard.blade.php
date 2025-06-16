@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6 text-center">Dashboard Overview</h2>

    <!-- Motivation Banner -->
    <div class="bg-gradient-to-r from-indigo-400 to-purple-500 text-white text-lg font-semibold p-4 rounded-lg shadow-md mb-8 text-center">
        <i class="fas fa-bolt mr-2"></i> "Success is the sum of small efforts, repeated day in and day out."
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 text-center">
        <div class="bg-gradient-to-r from-blue-400 to-blue-600 text-white p-6 rounded-lg shadow-md transition-transform transform hover:scale-105">
            <div class="text-4xl mb-2"><i class="fas fa-tasks"></i></div>
            <p class="text-lg font-semibold">Total Tasks</p>
            <h3 class="text-3xl font-bold mt-2">{{ $totalTasks }}</h3>
        </div>

        <div class="bg-gradient-to-r from-green-400 to-green-600 text-white p-6 rounded-lg shadow-md transition-transform transform hover:scale-105">
            <div class="text-4xl mb-2"><i class="fas fa-check-circle"></i></div>
            <p class="text-lg font-semibold">Completed Tasks</p>
            <h3 class="text-3xl font-bold mt-2">{{ $completedTasks }}</h3>
        </div>

        <div class="bg-gradient-to-r from-purple-400 to-purple-600 text-white p-6 rounded-lg shadow-md transition-transform transform hover:scale-105">
            <div class="text-4xl mb-2"><i class="fas fa-clock"></i></div>
            <p class="text-lg font-semibold">Pomodoro Sessions</p>
            <h3 class="text-3xl font-bold mt-2">{{ $totalSessions }}</h3>
        </div>

        <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 text-white p-6 rounded-lg shadow-md transition-transform transform hover:scale-105">
            <div class="text-4xl mb-2"><i class="fas fa-sync-alt"></i></div>
            <p class="text-lg font-semibold">Total Cycles</p>
            <h3 class="text-3xl font-bold mt-2">{{ $totalCycles }}</h3>
        </div>
    </div>
</div>

<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
@endsection
