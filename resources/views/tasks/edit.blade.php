@extends('layouts.app')

@section('title', 'Edit task')

@section('content')
<div class="page max-w-2xl">
    <a href="{{ route('tasks.index') }}" class="mb-6 inline-flex items-center gap-1.5 text-sm font-medium text-ink-500 transition hover:text-ink-900" data-reveal>
        <x-icon name="arrow-right" class="h-4 w-4 rotate-180" /> Back to tasks
    </a>

    <div class="card" data-reveal="0.05">
        <div class="border-b border-ink-100 px-5 py-5 sm:px-8">
            <h1 class="text-xl font-semibold tracking-tight text-ink-900">Edit task</h1>
            <p class="mt-1 text-sm text-ink-500">Created {{ $task->created_at?->diffForHumans() }}</p>
        </div>

        <form action="{{ route('tasks.update', $task) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="px-5 py-6 sm:px-8">
                @include('tasks._form', ['task' => $task])
            </div>
            <div class="flex items-center justify-end gap-2 border-t border-ink-100 bg-ink-50/50 px-5 py-4 sm:px-8">
                <a href="{{ route('tasks.index') }}" class="btn-ghost">Cancel</a>
                <button type="submit" class="btn-primary"><x-icon name="check" class="h-4 w-4" /> Save changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
