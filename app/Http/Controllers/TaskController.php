<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TaskController extends Controller
{
    // Show All Tasks
    public function index(Request $request)
    {
        $user = Auth::user();
        $category = $request->query('category');
        $search = $request->query('search');

        $sortBy = $request->query('sort_by');

        $activeTasks = Task::where('user_id', $user->id)
            ->when($category, fn($query) => $query->where('category', $category))
            ->when($search, fn($query) => $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%");
            }))
            ->when($sortBy == 'due_date', fn($query) => $query->orderBy('due_date'))
            ->when($sortBy == 'priority', fn($query) => $query->orderByRaw("FIELD(priority, 'P1', 'P2', 'P3', 'P4')"))
            ->whereIn('status', ['pending', 'in_progress'])
            ->get();

        $completedTasks = Task::where('user_id', $user->id)
            ->when($category, fn($query) => $query->where('category', $category))
            ->when($search, fn($query) => $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%");
            }))
            ->when($sortBy == 'due_date', fn($query) => $query->orderBy('due_date'))
            ->when($sortBy == 'priority', fn($query) => $query->orderByRaw("FIELD(priority, 'P1', 'P2', 'P3', 'P4')"))
            ->where('status', 'completed')
            ->get();

        $categories = Task::where('user_id', $user->id)->select('category')->distinct()->pluck('category');

        return view('tasks.index', compact('activeTasks', 'completedTasks', 'categories', 'category', 'search', 'sortBy'));
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
            'category' => 'nullable|string|max:50',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:P1,P2,P3,P4',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        Task::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
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
            'category' => 'nullable|string|max:50',
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

    public function markAsCompleted(Task $task)
    {
        $task->update(['status' => 'completed']);

        return redirect()->route('tasks.index')->with('success', 'Task marked as completed.');
    }

    //Export tasks to PDF
    public function exportPdf(Request $request)
    {
        $user = Auth::user();

        $status = $request->query('status', 'all'); // 'all' or 'completed'
        
        $tasks = Task::where('user_id', $user->id)
            ->when($status == 'completed', fn($query) => $query->where('status', 'completed'))
            ->orderBy('due_date')
            ->get();

        $pdf = Pdf::loadView('tasks.pdf', compact('tasks', 'status'));
        
        return $pdf->download("task-report-{$status}.pdf");
    }

}
