@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-3xl font-bold text-gray-900 mb-6">Admin Dashboard</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-xl font-semibold">👤 Total Users</h3>
            <p class="text-3xl mt-2">{{ $totalUsers }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-xl font-semibold">📝 Total Tasks</h3>
            <p class="text-3xl mt-2">{{ $totalTasks }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-xl font-semibold">✅ Completed Tasks</h3>
            <p class="text-3xl mt-2">{{ $completedTasks }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-xl font-semibold">⏱️ Total Focus Sessions</h3>
            <p class="text-3xl mt-2">{{ $totalSessions }}</p>
        </div>
    </div>
</div>
@endsection
