@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-semibold text-gray-100 mb-4">My Tasks</h2>

    <a href="{{ route('tasks.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">New Task</a>

    <div class="mt-4">
        <table class="w-full text-left border border-gray-700 text-gray-200">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="py-2 px-4 border-b">Title</th>
                    <th class="py-2 px-4 border-b">Description</th>
                    <th class="py-2 px-4 border-b">Due Date</th>
                    <th class="py-2 px-4 border-b">Priority</th>
                    <th class="py-2 px-4 border-b">Status</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                <tr class="border-b border-gray-700">
                    <td class="py-2 px-4">{{ $task->title }}</td>
                    <td class="py-2 px-4">{{ $task->description }}</td>
                    <td class="py-2 px-4">{{ $task->due_date }}</td>
                    <td class="py-2 px-4">
                        <span class="px-2 py-1 text-xs font-semibold text-white rounded
                        {{ $task->priority == 'P1' ? 'bg-red-500' : ($task->priority == 'P2' ? 'bg-yellow-500' : ($task->priority == 'P3' ? 'bg-blue-500' : 'bg-gray-500')) }}">
                            {{ $task->priority }}
                        </span>
                    </td>
                    <td class="py-2 px-4 capitalize">{{ $task->status }}</td>
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
    </div>
</div>
@endsection
