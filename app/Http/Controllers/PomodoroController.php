<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PomodoroSession;

class PomodoroController extends Controller
{
    public function index()
    {
        // Get the user's completed cycles & sessions
        $user = Auth::user();
        $cyclesCompleted = PomodoroSession::where('user_id', $user->id)->sum('cycles');
        $sessionsCompleted = PomodoroSession::where('user_id', $user->id)->count();

        return view('pomodoro.index', compact('cyclesCompleted', 'sessionsCompleted'));
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
