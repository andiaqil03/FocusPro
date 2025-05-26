@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">Create Task</h2>
    
    <form action="{{ route('tasks.store') }}" method="POST" class="bg-gray-800 p-6 rounded-lg shadow-md">
        @csrf

        <div class="mb-4">
            <label for="title" class="block text-gray-200">Title</label>
            <input type="text" id="title" name="title" class="w-full px-4 py-2 rounded-md bg-gray-700 text-white border border-gray-600 focus:ring focus:ring-blue-500" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-200">Description</label>
            <textarea id="description" name="description" class="w-full px-4 py-2 rounded-md bg-gray-700 text-white border border-gray-600 focus:ring focus:ring-blue-500"></textarea>
        </div>

        <div class="mb-4">
            <label for="category" class="block text-gray-200">Category</label>
            <input type="text" id="category" name="category" value="{{ old('category', 'General') }}"
                class="w-full px-4 py-2 rounded-md bg-gray-700 text-white border border-gray-600 focus:ring focus:ring-blue-500">
        </div>


        <div class="mb-4">
            <label for="due_date" class="block text-gray-200">Due Date</label>
            <input type="date" id="due_date" name="due_date" class="w-full px-4 py-2 rounded-md bg-gray-700 text-white border border-gray-600 focus:ring focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label for="priority" class="block text-gray-200">Priority</label>
            <select id="priority" name="priority" class="w-full px-4 py-2 rounded-md bg-gray-700 text-white border border-gray-600">
                <option value="P1">P1 (Highest)</option>
                <option value="P2">P2</option>
                <option value="P3">P3</option>
                <option value="P4" selected>P4 (Lowest)</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="status" class="block text-gray-200">Status</label>
            <select id="status" name="status" class="w-full px-4 py-2 rounded-md bg-gray-700 text-white border border-gray-600">
                <option value="pending">Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
            </select>
        </div>

        <div class="flex justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded">Save Task</button>
            <a href="{{ route('tasks.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded">Cancel</a>
        </div>
</form>
</div>
@endsection
