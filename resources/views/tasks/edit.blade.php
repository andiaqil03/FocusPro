@extends('layouts.app')

@section('content')
<style>
    .fade-in {
        opacity: 0;
        transform: translateY(30px);
        animation: fadeInUp 0.8s forwards;
    }
    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: none;
        }
    }
    .gradient-border {
        border: 2px solid;
        border-image: linear-gradient(90deg, #34d399 0%, #60a5fa 100%);
        border-image-slice: 1;
    }
</style>
<div class="container mx-auto p-6">
    <div class="max-w-xl mx-auto bg-white/90 rounded-2xl shadow-2xl p-8 gradient-border fade-in">
        <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-green-500 via-blue-500 to-indigo-500 mb-6 text-center flex items-center justify-center gap-2">
            <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536M9 13l6-6 3 3-6 6H9v-3z"/></svg>
            Edit Task
        </h2>
        <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-gray-700 font-semibold mb-1">Title</label>
                <input type="text" id="title" name="title" value="{{ $task->title }}" 
                    class="w-full px-4 py-2 rounded-md bg-gray-100 border border-gray-300 focus:ring-2 focus:ring-green-400 focus:bg-white transition" required>
            </div>

            <div>
                <label for="description" class="block text-gray-700 font-semibold mb-1">Description</label>
                <textarea id="description" name="description"
                    class="w-full px-4 py-2 rounded-md bg-gray-100 border border-gray-300 focus:ring-2 focus:ring-green-400 focus:bg-white transition">{{ $task->description }}</textarea>
            </div>

            <div>
                <label for="category" class="block text-gray-700 font-semibold mb-1">Category</label>
                <input type="text" id="category" name="category" value="{{ $task->category }}"
                    class="w-full px-4 py-2 rounded-md bg-gray-100 border border-gray-300 focus:ring-2 focus:ring-green-400 focus:bg-white transition">
            </div>

            <div>
                <label for="due_date" class="block text-gray-700 font-semibold mb-1">Due Date</label>
                <input type="date" id="due_date" name="due_date" value="{{ $task->due_date }}" 
                    class="w-full px-4 py-2 rounded-md bg-gray-100 border border-gray-300 focus:ring-2 focus:ring-green-400 focus:bg-white transition">
            </div>

            <div>
                <label for="priority" class="block text-gray-700 font-semibold mb-1">Priority</label>
                <select id="priority" name="priority" class="w-full px-4 py-2 rounded-md bg-gray-100 border border-gray-300 focus:ring-2 focus:ring-green-400 focus:bg-white transition">
                    <option value="P1" {{ $task->priority == 'P1' ? 'selected' : '' }}>P1 (Highest)</option>
                    <option value="P2" {{ $task->priority == 'P2' ? 'selected' : '' }}>P2</option>
                    <option value="P3" {{ $task->priority == 'P3' ? 'selected' : '' }}>P3</option>
                    <option value="P4" {{ $task->priority == 'P4' ? 'selected' : '' }}>P4 (Lowest)</option>
                </select>
            </div>

            <div>
                <label for="status" class="block text-gray-700 font-semibold mb-1">Status</label>
                <select id="status" name="status" class="w-full px-4 py-2 rounded-md bg-gray-100 border border-gray-300 focus:ring-2 focus:ring-green-400 focus:bg-white transition">
                    <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div class="flex justify-between items-center mt-6">
                <button type="submit" class="bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white px-6 py-2 rounded-xl font-semibold shadow-lg transition-all duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                    Update Task
                </button>
                <a href="{{ route('tasks.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-xl font-semibold shadow transition-all duration-200">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
