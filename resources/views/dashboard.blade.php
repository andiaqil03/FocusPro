@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    $hour = now()->hour;
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
    $firstName = explode(' ', trim(Auth::user()->name))[0];
    $completionRate = $totalTasks ? round($completedTasks / $totalTasks * 100) : 0;
    $statusLabel = fn ($task) => ucfirst(str_replace('_', ' ', $task->status));
@endphp

<div class="page">
    <div class="page-header" data-reveal>
        <div>
            <h1 class="page-title">{{ $greeting }}, {{ $firstName }}</h1>
            <p class="page-subtitle">{{ now()->format('l, j F') }} · Here's where things stand.</p>
        </div>
        <a href="{{ route('tasks.create') }}" class="btn-secondary self-start sm:self-auto" data-shortcut="n" aria-keyshortcuts="N">
            <x-icon name="plus" class="h-4 w-4" /> New task <kbd class="kbd" aria-hidden="true">N</kbd>
        </a>
    </div>

    {{-- Next up: the one thing to start. Not animated in, so it's there the moment the page loads. --}}
    <section class="card mb-6 px-5 py-6 sm:px-7 sm:py-7" aria-label="Next up">
        @if ($nextTask)
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex min-w-0 items-start gap-4">
                    <form action="{{ route('tasks.complete', $nextTask) }}" method="POST" class="mt-1 shrink-0">
                        @csrf @method('PATCH')
                        <button type="submit" class="check-btn" aria-label="Mark “{{ $nextTask->title }}” as done" title="Mark as done">
                            <x-icon name="check" class="h-3.5 w-3.5" />
                        </button>
                    </form>
                    <div class="min-w-0">
                        <h2 class="text-xl font-semibold tracking-tight text-ink-900 sm:text-2xl">
                            <a href="{{ route('tasks.edit', $nextTask) }}" class="rounded-md hover:underline hover:decoration-ink-300 hover:underline-offset-4">{{ $nextTask->title }}</a>
                        </h2>
                        <div class="mt-2.5 flex flex-wrap items-center gap-2">
                            @if ($nextTask->status === 'in_progress')
                                <span class="chip-brand"><span class="h-1.5 w-1.5 rounded-full bg-brand-600" aria-hidden="true"></span> In progress</span>
                            @endif
                            <x-due-chip :date="$nextTask->due_date" />
                            <x-priority-chip :priority="$nextTask->priority" />
                            @if ($nextTask->category)
                                <span class="text-sm text-ink-500">{{ $nextTask->category }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex shrink-0 items-center gap-2 lg:pl-6">
                    <a href="{{ route('pomodoro', ['task' => $nextTask->id, 'start' => 1]) }}" class="btn-primary btn-lg flex-1 lg:flex-none" data-shortcut="f" aria-keyshortcuts="F">
                        <x-icon name="play" class="h-4 w-4" /> Start 25 min <kbd class="kbd" aria-hidden="true">F</kbd>
                    </a>
                    <form action="{{ route('tasks.complete', $nextTask) }}" method="POST" class="hidden sm:block">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn-ghost btn-lg" aria-label="Mark “{{ $nextTask->title }}” as done">
                            <x-icon name="check" class="h-4 w-4" /> Mark done
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-start gap-4">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-ink-50 text-ink-400"><x-icon name="inbox" class="h-5 w-5" /></span>
                    <div>
                        <h2 class="text-lg font-semibold text-ink-900">Nothing queued up</h2>
                        <p class="mt-1 text-sm text-ink-500">Start an open focus session, or <a href="{{ route('tasks.create') }}" class="link">add a task</a> to work on.</p>
                    </div>
                </div>
                <a href="{{ route('pomodoro') }}" class="btn-primary btn-lg shrink-0" data-shortcut="f" aria-keyshortcuts="F">
                    <x-icon name="play" class="h-4 w-4" /> Start focusing <kbd class="kbd" aria-hidden="true">F</kbd>
                </a>
            </div>
        @endif
    </section>

    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4" data-stagger>
        <x-stat-card label="Total tasks" :value="$totalTasks" icon="list-checks" />
        <x-stat-card label="Completed" :value="$completedTasks" icon="circle-check">
            <x-slot name="hint">{{ $completionRate }}% of all tasks</x-slot>
        </x-stat-card>
        <x-stat-card label="Focus sessions" :value="$totalSessions" icon="timer" />
        <x-stat-card label="Cycles" :value="$totalCycles" icon="refresh" />
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        {{-- After that --}}
        <section class="card min-w-0 lg:col-span-2" data-reveal="0.1">
            <div class="flex items-center justify-between border-b border-ink-100 px-5 py-4 sm:px-6">
                <h2 class="section-title">After that</h2>
                <a href="{{ route('tasks.index') }}" class="-my-2 -mr-2 rounded-lg px-2 py-3 text-sm font-medium text-ink-500 transition hover:text-ink-900">View all{{ $openCount ? ' · ' . $openCount . ' open' : '' }}</a>
            </div>

            @if ($upcomingTasks->isNotEmpty())
                <ul>
                    @foreach ($upcomingTasks as $task)
                        <li class="group flex items-center gap-3 border-b border-ink-100 px-5 py-3 transition last:border-b-0 hover:bg-ink-50/60 sm:gap-4 sm:px-6">
                            <form action="{{ route('tasks.complete', $task) }}" method="POST" class="shrink-0">
                                @csrf @method('PATCH')
                                <button type="submit" class="check-btn" aria-label="Mark “{{ $task->title }}” as done" title="Mark as done">
                                    <x-icon name="check" class="h-3.5 w-3.5" />
                                </button>
                            </form>

                            <a href="{{ route('tasks.edit', $task) }}" class="flex min-w-0 flex-1 items-center gap-4 rounded-md py-0.5">
                                <div class="min-w-0 flex-1">
                                    <p class="flex items-center gap-2 text-sm font-medium text-ink-900">
                                        @if ($task->status === 'in_progress')
                                            <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-brand-500" title="In progress" aria-hidden="true"></span>
                                        @endif
                                        <span class="min-w-0 truncate"><span class="sr-only">{{ $statusLabel($task) }}: </span>{{ $task->title }}</span>
                                    </p>
                                    @if ($task->category)
                                        <p class="truncate text-xs text-ink-500">{{ $task->category }}</p>
                                    @endif
                                    {{-- On phones the chips move under the title instead of disappearing --}}
                                    <div class="mt-1.5 flex flex-wrap items-center gap-1.5 sm:hidden">
                                        <x-due-chip :date="$task->due_date" />
                                        <x-priority-chip :priority="$task->priority" />
                                    </div>
                                </div>
                                <div class="hidden shrink-0 items-center gap-2 sm:flex">
                                    <x-due-chip :date="$task->due_date" />
                                    <x-priority-chip :priority="$task->priority" />
                                </div>
                            </a>

                            <a href="{{ route('pomodoro', ['task' => $task->id, 'start' => 1]) }}"
                               class="icon-btn opacity-0 transition group-hover:opacity-100 focus-visible:opacity-100 [@media(hover:none)]:opacity-100 [@media(pointer:coarse)]:h-11 [@media(pointer:coarse)]:w-11"
                               aria-label="Start 25 minutes on “{{ $task->title }}”" title="Start 25 min">
                                <x-icon name="play" class="h-4 w-4" />
                            </a>
                        </li>
                    @endforeach
                </ul>
            @elseif ($nextTask)
                <p class="px-5 py-8 text-center text-sm text-ink-500 sm:px-6">Nothing else queued. Just the one above.</p>
            @else
                <p class="px-5 py-8 text-center text-sm text-ink-500 sm:px-6">No open tasks. <a href="{{ route('tasks.create') }}" class="link">Add one</a> to plan your next session.</p>
            @endif
        </section>

        {{-- Progress --}}
        <section class="card card-body flex flex-col" data-reveal="0.15">
            <h2 class="section-title">Task progress</h2>
            <p class="mt-1 text-sm text-ink-500">{{ $completedTasks }} of {{ $totalTasks }} tasks done</p>

            <div class="mt-6 flex flex-1 items-center justify-center">
                @php $c = 2 * M_PI * 52; @endphp
                <div class="relative h-36 w-36" role="img" aria-label="{{ $completionRate }}% of tasks complete">
                    <svg viewBox="0 0 120 120" class="h-full w-full -rotate-90" aria-hidden="true">
                        <circle cx="60" cy="60" r="52" fill="none" class="stroke-ink-100" stroke-width="10" />
                        <circle cx="60" cy="60" r="52" fill="none" class="stroke-brand-600" stroke-width="10" stroke-linecap="round"
                                stroke-dasharray="{{ $c }}" stroke-dashoffset="{{ $c * (1 - $completionRate / 100) }}"
                                data-progress-ring data-offset="{{ $c * (1 - $completionRate / 100) }}" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center" aria-hidden="true">
                        <span class="text-2xl font-semibold tabular-nums text-ink-900"><span data-count="{{ $completionRate }}">{{ $completionRate }}</span>%</span>
                    </div>
                </div>
            </div>

            <a href="{{ route('analytics') }}" class="btn-ghost mt-6 w-full">See analytics <x-icon name="arrow-right" class="h-4 w-4" /></a>
        </section>
    </div>
</div>
@endsection
