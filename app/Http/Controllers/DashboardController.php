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

        return view('dashboard', compact('totalTasks', 'completedTasks', 'totalSessions', 'totalCycles'));
    }
}
