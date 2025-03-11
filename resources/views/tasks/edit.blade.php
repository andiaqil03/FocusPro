@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-3xl">
    <h2 class="text-2xl font-semibold text-gray-100 mb-4">Edit Task</h2>

    <div class="bg-gray-800 p-6 rounded-lg shadow-md">
        <form action="{{ route('tasks.update', $task->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="title" class="block text-gray-200">Title</label>
                <input type="text" id="title" name="title" value="{{ $task->title }}" 
                    class="w-full px-4 py-2 rounded-md bg-gray-700 text-white border border-gray-600 focus:ring focus:ring-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-200">Description</label>
                <textarea id="description" name="description"
                    class="w-full px-4 py-2 rounded-md bg-gray-700 text-white border border-gray-600 focus:ring focus:ring-blue-500">{{ $task->description }}</textarea>
            </div>

            <div class="mb-4">
                <label for="due_date" class="block text-gray-200">Due Date</label>
                <input type="date" id="due_date" name="due_date" value="{{ $task->due_date }}" 
                    class="w-full px-4 py-2 rounded-md bg-gray-700 text-white border border-gray-600 focus:ring focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label for="priority" class="block text-gray-200">Priority</label>
                <select id="priority" name="priority" class="w-full px-4 py-2 rounded-md bg-gray-700 text-white border border-gray-600">
                    <option value="P1" {{ $task->priority == 'P1' ? 'selected' : '' }}>P1 (Highest)</option>
                    <option value="P2" {{ $task->priority == 'P2' ? 'selected' : '' }}>P2</option>
                    <option value="P3" {{ $task->priority == 'P3' ? 'selected' : '' }}>P3</option>
                    <option value="P4" {{ $task->priority == 'P4' ? 'selected' : '' }}>P4 (Lowest)</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="status" class="block text-gray-200">Status</label>
                <select id="status" name="status" class="w-full px-4 py-2 rounded-md bg-gray-700 text-white border border-gray-600">
                    <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div class="flex justify-between">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded">Update Task</button>
                <a href="{{ route('tasks.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
