@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    $hour = now()->hour;
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
    $firstName = explode(' ', trim(Auth::user()->name))[0];
    $completionRate = $totalTasks ? round($completedTasks / $totalTasks * 100) : 0;
@endphp

<div class="page">
    <div class="page-header" data-reveal>
        <div>
            <h1 class="page-title">{{ $greeting }}, {{ $firstName }}</h1>
            <p class="page-subtitle">{{ now()->format('l, j F') }} · Here's where things stand.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('tasks.create') }}" class="btn-secondary"><x-icon name="plus" class="h-4 w-4" /> New task</a>
            <a href="{{ route('pomodoro') }}" class="btn-primary"><x-icon name="play" class="h-4 w-4" /> Start focusing</a>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4" data-stagger>
        <x-stat-card label="Total tasks" :value="$totalTasks" icon="list-checks" />
        <x-stat-card label="Completed" :value="$completedTasks" icon="circle-check">
            <x-slot name="hint">{{ $completionRate }}% of all tasks</x-slot>
        </x-stat-card>
        <x-stat-card label="Focus sessions" :value="$totalSessions" icon="timer" />
        <x-stat-card label="Cycles" :value="$totalCycles" icon="refresh" />
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        {{-- Up next --}}
        <section class="card lg:col-span-2" data-reveal="0.1">
            <div class="flex items-center justify-between border-b border-ink-100 px-5 py-4 sm:px-6">
                <h2 class="section-title">Up next</h2>
                <a href="{{ route('tasks.index') }}" class="text-sm font-medium text-ink-500 transition hover:text-ink-900">View all</a>
            </div>

            @forelse ($upcomingTasks as $task)
                <a href="{{ route('tasks.edit', $task) }}" class="flex items-center gap-4 border-b border-ink-100 px-5 py-3.5 transition last:border-b-0 hover:bg-ink-50/60 sm:px-6">
                    <span class="h-2 w-2 shrink-0 rounded-full {{ $task->status === 'in_progress' ? 'bg-brand-500' : 'bg-ink-300' }}" title="{{ str_replace('_', ' ', $task->status) }}"></span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-ink-900">{{ $task->title }}</p>
                        @if ($task->category)
                            <p class="truncate text-xs text-ink-500">{{ $task->category }}</p>
                        @endif
                    </div>
                    <div class="hidden shrink-0 items-center gap-2 sm:flex">
                        <x-due-chip :date="$task->due_date" />
                        <x-priority-chip :priority="$task->priority" />
                    </div>
                </a>
            @empty
                <div class="flex flex-col items-center px-6 py-12 text-center">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-ink-50 text-ink-400"><x-icon name="inbox" /></span>
                    <p class="mt-3 text-sm font-medium text-ink-900">You're all caught up</p>
                    <p class="mt-1 text-sm text-ink-500">Add a task to plan your next focus session.</p>
                    <a href="{{ route('tasks.create') }}" class="btn-secondary mt-4"><x-icon name="plus" class="h-4 w-4" /> New task</a>
                </div>
            @endforelse
        </section>

        {{-- Progress --}}
        <section class="card card-body flex flex-col" data-reveal="0.15">
            <h2 class="section-title">Task progress</h2>
            <p class="mt-1 text-sm text-ink-500">{{ $completedTasks }} of {{ $totalTasks }} tasks done</p>

            <div class="mt-6 flex flex-1 items-center justify-center">
                @php $c = 2 * M_PI * 52; @endphp
                <div class="relative h-36 w-36">
                    <svg viewBox="0 0 120 120" class="h-full w-full -rotate-90" aria-hidden="true">
                        <circle cx="60" cy="60" r="52" fill="none" class="stroke-ink-100" stroke-width="10" />
                        <circle cx="60" cy="60" r="52" fill="none" class="stroke-brand-600" stroke-width="10" stroke-linecap="round"
                                stroke-dasharray="{{ $c }}" stroke-dashoffset="{{ $c * (1 - $completionRate / 100) }}"
                                data-progress-ring data-offset="{{ $c * (1 - $completionRate / 100) }}" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-2xl font-semibold tabular-nums text-ink-900"><span data-count="{{ $completionRate }}">{{ $completionRate }}</span>%</span>
                    </div>
                </div>
            </div>

            <a href="{{ route('analytics') }}" class="btn-ghost mt-6 w-full">See analytics <x-icon name="arrow-right" class="h-4 w-4" /></a>
        </section>
    </div>
</div>
@endsection
