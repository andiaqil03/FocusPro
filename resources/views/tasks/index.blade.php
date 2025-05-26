@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">My Tasks</h2>

    <a href="{{ route('tasks.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">New Task</a>
    
    <div class="mt-4">
    <div class="flex flex-wrap justify-between items-center mb-4">
    <!-- Filter and Search Form -->
    <form method="GET" action="{{ route('tasks.index') }}" class="mb-6 flex flex-col sm:flex-row gap-4 items-center">
        <div class="flex items-center gap-2">
            <label for="category" class="text-gray-900 dark:text-white">Category:</label>
            <select name="category" id="category" onchange="this.form.submit()" class="bg-gray-700 text-white px-3 py-2 rounded">
                <option value="">All</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-2">
            <label for="search" class="text-gray-900 dark:text-white">Search:</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}"
                class="bg-gray-700 text-white px-3 py-2 rounded" placeholder="Title or Description">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Apply</button>
        </div>

        <div class="flex items-center gap-2">
            <label for="sort_by" class="text-gray-900 dark:text-white">Sort By:</label>
            <select name="sort_by" id="sort_by" onchange="this.form.submit()" class="bg-gray-700 text-white px-3 py-2 rounded">
                <option value="">None</option>
                <option value="due_date" {{ request('sort_by') == 'due_date' ? 'selected' : '' }}>Due Date</option>
                <option value="priority" {{ request('sort_by') == 'priority' ? 'selected' : '' }}>Priority</option>
            </select>
        </div>
    </form>

    <!--Export Buttons-->
    <div class="mb-4">
        <a href="{{ route('tasks.exportPdf', ['status' => 'all']) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded mr-2">
            Export All Tasks
        </a>
        <a href="{{ route('tasks.exportPdf', ['status' => 'completed']) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            Export Completed Tasks
        </a>
    </div>
    </div>

        <h3 class="text-xl text-gray-900 dark:text-white font-semibold mt-10 mb-4">Incomplete Tasks</h3>
        <table class="w-full text-left border border-gray-700 text-gray-200">
            <thead class="bg-gray-800 text-white">
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
                <tr class="border-b border-gray-700">
                    <td class="py-2 px-4">{{ $task->title }}</td>
                    <td class="py-2 px-4">{{ $task->description }}</td>
                    <td class="py-2 px-4">{{ $task->category }}</td>

                    @php
                        $due = \Carbon\Carbon::parse($task->due_date);
                        $now = \Carbon\Carbon::now();
                        $diff = $now->diffInDays($due, false);
                        $dueClass = $diff < 0 ? 'bg-red-600' :
                                    ($diff === 0 ? 'bg-yellow-500' :
                                    ($diff <= 3 ? 'bg-blue-500' : 'bg-gray-600'));
                    @endphp
                    <td class="py-2 px-4">
                        <span class="px-2 py-1 rounded text-white {{ $dueClass }}">
                            {{ $task->due_date }}
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
                    <td class="py-2 px-4">
                        <a href="{{ route('tasks.edit', $task->id) }}" class="text-yellow-400">Edit</a> |

                        <!-- Mark as Completed Button -->
                        @if($task->status != 'completed')
                            <form action="{{ route('tasks.complete', $task->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-blue-400 hover:text-blue-500 ml-2">Mark as Completed</button>
                            </form>
                        @endif |

                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($completedTasks->count())
            <h3 class="text-xl text-gray-100 font-semibold mt-10 mb-4">Completed Tasks</h3>
            <table class="w-full text-left border border-gray-700 text-gray-300">
                <thead class="bg-gray-800 text-white">
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
                    <tr class="border-b border-gray-700">
                        <td class="py-2 px-4">{{ $task->title }}</td>
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
                        <td class="py-2 px-4">
                            <a href="{{ route('tasks.edit', $task->id) }}" class="text-yellow-400">Edit</a> |
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    </div>
</div>
@endsection
