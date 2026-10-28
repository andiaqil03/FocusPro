<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PomodoroSession;
use App\Models\Task;

class PomodoroController extends Controller
{
    public function index(Request $request)
    {
        // Get the user's completed cycles & sessions
        $user = Auth::user();
        $cyclesCompleted = PomodoroSession::where('user_id', $user->id)->sum('cycles');
        $sessionsCompleted = PomodoroSession::where('user_id', $user->id)->count();

        // ?task=ID (from the dashboard) labels the session with the task being worked on.
        $task = $request->filled('task')
            ? Task::where('user_id', $user->id)->whereIn('status', ['pending', 'in_progress'])->find($request->integer('task'))
            : null;
        $autoStart = $task && $request->boolean('start');

        return view('pomodoro.index', compact('cyclesCompleted', 'sessionsCompleted', 'task', 'autoStart'));
    }

    public function storeSession(Request $request)
    {
        $request->validate([
            'cycles' => 'required|integer|min:1',
            'session_duration' => 'required|integer|min:1',
        ]);

        $session = PomodoroSession::create([
            'user_id' => Auth::id(),
            'cycles' => $request->cycles,
            'session_duration' => $request->session_duration,
        ]);

        if ($session) {
            return response()->json(['message' => 'Session recorded successfully']);
        } else {
            return response()->json(['message' => 'Failed to save session'], 500);
        }
    }
}
