<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PomodoroSession;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get total Pomodoro sessions and cycles
        $totalSessions = PomodoroSession::where('user_id', $user->id)->count();
        $totalCycles = PomodoroSession::where('user_id', $user->id)->sum('cycles');

        // Get daily sessions for the last 7 days
        $dailySessions = PomodoroSession::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as sessions')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('analytics.index', compact('totalSessions', 'totalCycles', 'dailySessions'));
    }
}
