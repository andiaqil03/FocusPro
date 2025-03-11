<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // Show All Tasks
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())
            ->orderByRaw("FIELD(priority, 'P1', 'P2', 'P3', 'P4')")
            ->orderBy('due_date', 'asc')
            ->get();
            
        return view('tasks.index', compact('tasks'));
    }

    // Show Form to Create Task
    public function create()
    {
        return view('tasks.create');
    }

    // Store New Task
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:P1, P2, P3, P4',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        Task::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'status' => $request->status,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    // Show Edit Form
    public function edit(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }
        return view('tasks.edit', compact('task'));
    }

    // Update Task
    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:P1,P2,P3,P4',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->update($request->all());

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    // Delete Task
    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }
}
