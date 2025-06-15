@extends('layouts.app')

@section('content')
<style>
    .task-row:hover {
        background: rgba(59,130,246,0.07);
        transition: background 0.2s;
    }
    .badge-animate {
        animation: pulse-badge 1.5s infinite;
    }
    @keyframes pulse-badge {
        0%, 100% { box-shadow: 0 0 0 0 rgba(239,68,68,0.5);}
        50% { box-shadow: 0 0 0 6px rgba(239,68,68,0);}
    }
    .progress-bar {
        background: linear-gradient(90deg, #34d399 0%, #60a5fa 100%);
        height: 10px;
        border-radius: 6px;
        transition: width 0.5s;
    }
</style>

<div class="container mx-auto p-6">
    <div class="bg-white/90 rounded-2xl shadow-2xl p-8 mb-8">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-4">
            <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-500 to-pink-500 mb-2">My Tasks</h2>
            <a href="{{ route('tasks.create') }}" class="bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 text-white px-6 py-2 rounded-xl shadow-lg font-semibold transition-all duration-200 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                New Task
            </a>
        </div>

        <!-- Progress Bar -->
        @php
            $total = $activeTasks->count() + $completedTasks->count();
            $completed = $completedTasks->count();
            $percent = $total ? round(($completed / $total) * 100) : 0;
        @endphp
        <div class="mb-6">
            <div class="flex justify-between items-center mb-1">
                <span class="text-sm text-gray-700 font-semibold">Progress</span>
                <span class="text-xs text-gray-500">{{ $percent }}% completed</span>
            </div>
            <div class="w-full bg-gray-200 rounded h-2">
                <div class="progress-bar" style="width: {{ $percent }}%"></div>
            </div>
        </div>

        <!-- Filter and Search Form -->
        <form method="GET" action="{{ route('tasks.index') }}" class="mb-8 flex flex-col md:flex-row gap-4 items-center bg-gray-50 rounded-xl p-4 shadow">
            <div class="flex items-center gap-2">
                <label for="category" class="text-gray-900">Category:</label>
                <select name="category" id="category" onchange="this.form.submit()" class="bg-white border border-gray-300 text-gray-900 px-3 py-2 rounded">
                    <option value="">All</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <label for="search" class="text-gray-900">Search:</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                    class="bg-white border border-gray-300 text-gray-900 px-3 py-2 rounded" placeholder="Title or Description">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow">Apply</button>
            </div>
            <div class="flex items-center gap-2">
                <label for="sort_by" class="text-gray-900">Sort By:</label>
                <select name="sort_by" id="sort_by" onchange="this.form.submit()" class="bg-white border border-gray-300 text-gray-900 px-3 py-2 rounded">
                    <option value="">None</option>
                    <option value="due_date" {{ request('sort_by') == 'due_date' ? 'selected' : '' }}>Due Date</option>
                    <option value="priority" {{ request('sort_by') == 'priority' ? 'selected' : '' }}>Priority</option>
                </select>
            </div>
        </form>

        <!-- Export Buttons -->
        <div class="mb-6 flex gap-4">
            <a href="{{ route('tasks.exportPdf', ['status' => 'all']) }}" class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white px-4 py-2 rounded shadow flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                Export All Tasks
            </a>
            <a href="{{ route('tasks.exportPdf', ['status' => 'completed']) }}" class="bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white px-4 py-2 rounded shadow flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                Export Completed Tasks
            </a>
        </div>

        <!-- Incomplete Tasks Table -->
        <h3 class="text-2xl text-gray-900 font-bold mt-10 mb-4">Incomplete Tasks</h3>
        <div class="overflow-x-auto rounded-xl shadow">
        <table class="w-full text-left border border-gray-200 text-gray-800 bg-white rounded-xl">
            <thead class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                <tr>
                    <th class="py-2 px-4 border-b">Title</th>
                    <th class="py-2 px-4 border-b">Description</th>
                    <th class="py-2 px-4 border-b">Category</th>
                    <th class="py-2 px-4 border-b">Due Date</th>
                    <th class="py-2 px-4 border-b">Priority</th>
                    <th class="py-2 px-4 border-b">Status</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activeTasks as $task)
                <tr class="border-b border-gray-100 task-row">
                    <td class="py-2 px-4 font-semibold">{{ $task->title }}</td>
                    <td class="py-2 px-4">{{ $task->description }}</td>
                    <td class="py-2 px-4">{{ $task->category }}</td>
                    @php
                        $due = \Carbon\Carbon::parse($task->due_date);
                        $now = \Carbon\Carbon::now();
                        $diff = $now->diffInDays($due, false);
                        $dueClass = $diff < 0 ? 'bg-red-600 badge-animate' :
                                    ($diff === 0 ? 'bg-yellow-500' :
                                    ($diff <= 3 ? 'bg-blue-500' : 'bg-gray-600'));
                    @endphp
                    <td class="py-2 px-4">
                        <span class="px-2 py-1 rounded text-white {{ $dueClass }}">
                            {{ $task->due_date }}
                            @if($diff < 0)
                                <span class="ml-2 text-xs font-bold text-white">Overdue</span>
                            @elseif($diff === 0)
                                <span class="ml-2 text-xs font-bold text-white">Today</span>
                            @elseif($diff <= 3)
                                <span class="ml-2 text-xs font-bold text-white">Soon</span>
                            @endif
                        </span>
                    </td>
                    <td class="py-2 px-4">
                        <span class="px-2 py-1 text-xs font-semibold text-white rounded
                        {{ $task->priority == 'P1' ? 'bg-red-500' : ($task->priority == 'P2' ? 'bg-yellow-500' : ($task->priority == 'P3' ? 'bg-blue-500' : 'bg-gray-500')) }}">
                            {{ $task->priority }}
                        </span>
                    </td>
                    <td class="py-2 px-4 capitalize">
                        <span class="px-3 py-1 text-white text-sm rounded-md 
                            {{ $task->status == 'completed' ? 'bg-green-500' : 'bg-yellow-500' }}">
                            {{ ucfirst($task->status) }}
                        </span>
                    </td>
                    <td class="py-2 px-4 flex gap-2 items-center">
                        <a href="{{ route('tasks.edit', $task->id) }}" class="text-yellow-500 hover:text-yellow-700" title="Edit">
                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536M9 13l6-6 3 3-6 6H9v-3z"/></svg>
                        </a>
                        @if($task->status != 'completed')
                            <form action="{{ route('tasks.complete', $task->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-blue-500 hover:text-blue-700 ml-2" title="Mark as Completed">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 ml-2" onclick="return confirm('Are you sure?')" title="Delete">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        @if($completedTasks->count())
            <h3 class="text-2xl text-green-700 font-bold mt-10 mb-4">Completed Tasks</h3>
            <div class="overflow-x-auto rounded-xl shadow">
            <table class="w-full text-left border border-gray-200 text-gray-800 bg-white rounded-xl">
                <thead class="bg-gradient-to-r from-green-600 to-blue-600 text-white">
                    <tr>
                        <th class="py-2 px-4 border-b">Title</th>
                        <th class="py-2 px-4 border-b">Description</th>
                        <th class="py-2 px-4 border-b">Category</th>
                        <th class="py-2 px-4 border-b">Due Date</th>
                        <th class="py-2 px-4 border-b">Priority</th>
                        <th class="py-2 px-4 border-b">Status</th>
                        <th class="py-2 px-4 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($completedTasks as $task)
                    <tr class="border-b border-gray-100 task-row">
                        <td class="py-2 px-4 font-semibold">{{ $task->title }}</td>
                        <td class="py-2 px-4">{{ $task->description }}</td>
                        <td class="py-2 px-4">{{ $task->category }}</td>
                        <td class="py-2 px-4">{{ $task->due_date }}</td>
                        <td class="py-2 px-4">
                            <span class="px-2 py-1 text-xs font-semibold text-white rounded
                            {{ $task->priority == 'P1' ? 'bg-red-500' : ($task->priority == 'P2' ? 'bg-yellow-500' : ($task->priority == 'P3' ? 'bg-blue-500' : 'bg-gray-500')) }}">
                                {{ $task->priority }}
                            </span>
                        </td>
                        <td class="py-2 px-4 capitalize">
                            <span class="px-3 py-1 text-white text-sm rounded-md bg-green-500">
                                {{ ucfirst($task->status) }}
                            </span>
                        </td>
                        <td class="py-2 px-4 flex gap-2 items-center">
                            <a href="{{ route('tasks.edit', $task->id) }}" class="text-yellow-500 hover:text-yellow-700" title="Edit">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536M9 13l6-6 3 3-6 6H9v-3z"/></svg>
                            </a>
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 ml-2" onclick="return confirm('Are you sure?')" title="Delete">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        @endif
    </div>
</div>
@endsection
