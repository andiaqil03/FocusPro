<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\PomodoroSession;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalTasks = Task::where('user_id', $user->id)->count();
        $completedTasks = Task::where('user_id', $user->id)->where('status', 'completed')->count();
        $totalSessions = PomodoroSession::where('user_id', $user->id)->count();
        $totalCycles = PomodoroSession::where('user_id', $user->id)->sum('cycles');

        // Open tasks, soonest due first; tasks without a due date go last.
        $openTasks = fn () => Task::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'in_progress'])
            ->orderByRaw('due_date IS NULL, due_date')
            ->orderBy('priority');

        $openCount = $openTasks()->count();

        // The hero task: whatever is already in progress, otherwise the next one due.
        $nextTask = $openTasks()->where('status', 'in_progress')->first() ?? $openTasks()->first();

        $upcomingTasks = $openTasks()
            ->when($nextTask, fn ($query) => $query->whereKeyNot($nextTask->getKey()))
            ->limit(4)
            ->get();

        return view('dashboard', compact('totalTasks', 'completedTasks', 'totalSessions', 'totalCycles', 'nextTask', 'upcomingTasks', 'openCount'));
    }
}
