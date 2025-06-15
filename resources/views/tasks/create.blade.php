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
        border-image: linear-gradient(90deg, #3b82f6 0%, #a78bfa 100%);
        border-image-slice: 1;
    }
</style>
<div class="container mx-auto p-6">
    <div class="max-w-xl mx-auto bg-white/90 rounded-2xl shadow-2xl p-8 gradient-border fade-in">
        <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-500 to-pink-500 mb-6 text-center flex items-center justify-center gap-2">
            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
            Create Task
        </h2>
        <form action="{{ route('tasks.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="title" class="block text-gray-700 font-semibold mb-1">Title</label>
                <input type="text" id="title" name="title" class="w-full px-4 py-2 rounded-md bg-gray-100 border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:bg-white transition" required>
            </div>

            <div>
                <label for="description" class="block text-gray-700 font-semibold mb-1">Description</label>
                <textarea id="description" name="description" class="w-full px-4 py-2 rounded-md bg-gray-100 border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:bg-white transition"></textarea>
            </div>

            <div>
                <label for="category" class="block text-gray-700 font-semibold mb-1">Category</label>
                <input type="text" id="category" name="category" value="{{ old('category', 'General') }}"
                    class="w-full px-4 py-2 rounded-md bg-gray-100 border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:bg-white transition">
            </div>

            <div>
                <label for="due_date" class="block text-gray-700 font-semibold mb-1">Due Date</label>
                <input type="date" id="due_date" name="due_date" class="w-full px-4 py-2 rounded-md bg-gray-100 border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:bg-white transition">
            </div>

            <div>
                <label for="priority" class="block text-gray-700 font-semibold mb-1">Priority</label>
                <select id="priority" name="priority" class="w-full px-4 py-2 rounded-md bg-gray-100 border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:bg-white transition">
                    <option value="P1">P1 (Highest)</option>
                    <option value="P2">P2</option>
                    <option value="P3">P3</option>
                    <option value="P4" selected>P4 (Lowest)</option>
                </select>
            </div>

            <div>
                <label for="status" class="block text-gray-700 font-semibold mb-1">Status</label>
                <select id="status" name="status" class="w-full px-4 py-2 rounded-md bg-gray-100 border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:bg-white transition">
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>

            <div class="flex justify-between items-center mt-6">
                <button type="submit" class="bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 text-white px-6 py-2 rounded-xl font-semibold shadow-lg transition-all duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                    Save Task
                </button>
                <a href="{{ route('tasks.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-xl font-semibold shadow transition-all duration-200">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
