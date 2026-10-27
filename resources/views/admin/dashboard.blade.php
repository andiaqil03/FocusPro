@extends('layouts.admin-layout')

@section('title', 'Overview')

@section('content')
<div class="page-header" data-reveal>
    <div>
        <h1 class="page-title">Overview</h1>
        <p class="page-subtitle">Activity across all FocusPro users.</p>
    </div>
    <a href="{{ route('admin.users') }}" class="btn-secondary"><x-icon name="users" class="h-4 w-4" /> Manage users</a>
</div>

<div class="grid grid-cols-2 gap-4 lg:grid-cols-4" data-stagger>
    <x-stat-card label="Users" :value="$totalUsers" icon="users" />
    <x-stat-card label="Tasks" :value="$totalTasks" icon="list-checks" />
    <x-stat-card label="Completed tasks" :value="$completedTasks" icon="circle-check">
        <x-slot name="hint">{{ $totalTasks ? round($completedTasks / $totalTasks * 100) : 0 }}% completion rate</x-slot>
    </x-stat-card>
    <x-stat-card label="Focus sessions" :value="$totalSessions" icon="timer" />
</div>
@endsection
