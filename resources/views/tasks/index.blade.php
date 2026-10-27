@extends('layouts.app')

@section('title', 'Tasks')

@section('content')
@php
    $total = $activeTasks->count() + $completedTasks->count();
    $percent = $total ? round($completedTasks->count() / $total * 100) : 0;
    $filtering = filled($category) || filled($search) || filled($sortBy);
@endphp

<div class="page">
    <div class="page-header" data-reveal>
        <div>
            <h1 class="page-title">Tasks</h1>
            <p class="page-subtitle">{{ $activeTasks->count() }} open · {{ $completedTasks->count() }} completed</p>
        </div>
        <div class="flex gap-2">
            <x-dropdown align="right" width="56">
                <x-slot name="trigger">
                    <button type="button" class="btn-secondary"><x-icon name="download" class="h-4 w-4" /> Export <x-icon name="chevron-down" class="h-4 w-4 text-ink-400" /></button>
                </x-slot>
                <x-slot name="content">
                    <div class="p-1">
                        <x-dropdown-link :href="route('tasks.exportPdf', ['status' => 'all'])">
                            <x-icon name="list-checks" class="h-4 w-4 text-ink-400" /> All tasks (PDF)
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('tasks.exportPdf', ['status' => 'completed'])">
                            <x-icon name="circle-check" class="h-4 w-4 text-ink-400" /> Completed tasks (PDF)
                        </x-dropdown-link>
                    </div>
                </x-slot>
            </x-dropdown>
            <a href="{{ route('tasks.create') }}" class="btn-primary"><x-icon name="plus" class="h-4 w-4" /> New task</a>
        </div>
    </div>

    {{-- Progress --}}
    <div class="mb-6" data-reveal="0.05">
        <div class="mb-2 flex items-center justify-between text-sm">
            <span class="font-medium text-ink-700">Overall progress</span>
            <span class="tabular-nums text-ink-500">{{ $percent }}%</span>
        </div>
        <div class="h-1.5 w-full overflow-hidden rounded-full bg-ink-200/70" role="progressbar" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100" aria-label="Tasks completed">
            <div class="h-full rounded-full bg-brand-600 transition-[width] duration-700 ease-out" style="width: {{ $percent }}%"></div>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('tasks.index') }}" class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center" data-reveal="0.08">
        <div class="relative flex-1">
            <label for="search" class="sr-only">Search tasks</label>
            <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-400" />
            <input type="search" name="search" id="search" value="{{ $search }}" placeholder="Search by title or description" class="input pl-10">
        </div>
        <div class="flex gap-3">
            <label for="category" class="sr-only">Category</label>
            <select name="category" id="category" onchange="this.form.submit()" class="input w-full cursor-pointer sm:w-44">
                <option value="">All categories</option>
                @foreach ($categories->filter() as $cat)
                    <option value="{{ $cat }}" @selected($category == $cat)>{{ $cat }}</option>
                @endforeach
            </select>
            <label for="sort_by" class="sr-only">Sort by</label>
            <select name="sort_by" id="sort_by" onchange="this.form.submit()" class="input w-full cursor-pointer sm:w-40">
                <option value="">Date added</option>
                <option value="due_date" @selected($sortBy == 'due_date')>Due date</option>
                <option value="priority" @selected($sortBy == 'priority')>Priority</option>
            </select>
        </div>
        @if ($filtering)
            <a href="{{ route('tasks.index') }}" class="btn-ghost shrink-0"><x-icon name="x" class="h-4 w-4" /> Clear</a>
        @endif
    </form>

    {{-- Open tasks --}}
    <section class="card overflow-hidden" data-reveal="0.1">
        @if ($activeTasks->isEmpty())
            <div class="flex flex-col items-center px-6 py-16 text-center">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-ink-50 text-ink-400"><x-icon name="inbox" /></span>
                @if ($filtering)
                    <p class="mt-3 text-sm font-medium text-ink-900">No tasks match these filters</p>
                    <a href="{{ route('tasks.index') }}" class="btn-secondary mt-4">Clear filters</a>
                @else
                    <p class="mt-3 text-sm font-medium text-ink-900">No open tasks</p>
                    <p class="mt-1 text-sm text-ink-500">Create one to plan your next focus session.</p>
                    <a href="{{ route('tasks.create') }}" class="btn-primary mt-4"><x-icon name="plus" class="h-4 w-4" /> New task</a>
                @endif
            </div>
        @else
            <ul data-stagger>
                @foreach ($activeTasks as $task)
                    <li class="group flex items-start gap-3 border-b border-ink-100 px-4 py-4 transition last:border-b-0 hover:bg-ink-50/50 sm:items-center sm:gap-4 sm:px-6">
                        <form action="{{ route('tasks.complete', $task) }}" method="POST" class="shrink-0">
                            @csrf @method('PATCH')
                            <button type="submit" class="group/check flex h-6 w-6 cursor-pointer items-center justify-center rounded-full border-2 border-ink-300 text-transparent transition hover:border-brand-600 hover:bg-brand-50 hover:text-brand-700" aria-label="Mark “{{ $task->title }}” as completed" title="Mark as completed">
                                <x-icon name="check" class="h-3.5 w-3.5" />
                            </button>
                        </form>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-medium text-ink-900">{{ $task->title }}</p>
                                    @if ($task->description)
                                        <p class="mt-0.5 line-clamp-1 text-sm text-ink-500">{{ $task->description }}</p>
                                    @endif
                                </div>
                                <div class="flex shrink-0 flex-wrap items-center gap-1.5">
                                    @if ($task->status === 'in_progress')
                                        <span class="chip-brand">In progress</span>
                                    @endif
                                    @if ($task->category)
                                        <span class="chip-neutral"><x-icon name="tag" class="h-3 w-3" /> {{ $task->category }}</span>
                                    @endif
                                    <x-due-chip :date="$task->due_date" />
                                    <x-priority-chip :priority="$task->priority" />
                                </div>
                            </div>
                        </div>

                        <div class="flex shrink-0 items-center sm:opacity-60 sm:transition sm:group-hover:opacity-100 sm:group-focus-within:opacity-100">
                            <a href="{{ route('tasks.edit', $task) }}" class="icon-btn" aria-label="Edit “{{ $task->title }}”" title="Edit"><x-icon name="pencil" class="h-4 w-4" /></a>
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Delete this task? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="icon-btn hover:bg-red-50 hover:text-red-600" aria-label="Delete “{{ $task->title }}”" title="Delete"><x-icon name="trash" class="h-4 w-4" /></button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>

    {{-- Completed tasks --}}
    @if ($completedTasks->isNotEmpty())
        <section x-data="{ open: false }" class="mt-6" data-reveal="0.12">
            <button type="button" @click="open = !open" :aria-expanded="open" class="mb-3 flex cursor-pointer items-center gap-2 rounded-lg px-1 text-sm font-medium text-ink-600 transition hover:text-ink-900">
                <x-icon name="chevron-down" class="h-4 w-4 transition-transform duration-200" ::class="open ? '' : '-rotate-90'" />
                Completed <span class="chip-neutral">{{ $completedTasks->count() }}</span>
            </button>

            <div x-show="open" x-cloak x-collapse.duration.250ms class="card overflow-hidden">
                <ul>
                    @foreach ($completedTasks as $task)
                        <li class="group flex items-center gap-3 border-b border-ink-100 px-4 py-3.5 last:border-b-0 sm:gap-4 sm:px-6">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-brand-600 text-white" aria-hidden="true">
                                <x-icon name="check" class="h-3.5 w-3.5" />
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm text-ink-500 line-through decoration-ink-300">{{ $task->title }}</p>
                            </div>
                            <div class="hidden shrink-0 items-center gap-1.5 sm:flex">
                                @if ($task->category)
                                    <span class="chip-neutral">{{ $task->category }}</span>
                                @endif
                                <x-priority-chip :priority="$task->priority" />
                            </div>
                            <div class="flex shrink-0 items-center">
                                <a href="{{ route('tasks.edit', $task) }}" class="icon-btn" aria-label="Edit “{{ $task->title }}”" title="Edit"><x-icon name="pencil" class="h-4 w-4" /></a>
                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Delete this task? This cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="icon-btn hover:bg-red-50 hover:text-red-600" aria-label="Delete “{{ $task->title }}”" title="Delete"><x-icon name="trash" class="h-4 w-4" /></button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif
</div>
@endsection
