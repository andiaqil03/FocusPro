<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\PomodoroSession;
use App\Models\Task;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get total Pomodoro sessions and cycles
        $totalSessions = PomodoroSession::where('user_id', $user->id)->count();
        $totalCycles = PomodoroSession::where('user_id', $user->id)->sum('cycles');

        // Get total work time (25 min per cycle) and break time (5 min per cycle)
        $totalWorkTime = $totalCycles * 25;  // in minutes
        $totalBreakTime = $totalCycles * 5;  // in minutes

        // Get average session duration
        $averageSessionDuration = PomodoroSession::where('user_id', $user->id)->avg('session_duration') ?? 0;

        // Get filter date range (default: last 7 days)
        $dateRange = $request->query('date_range', '7_days');
        $days = $dateRange == '30_days' ? 30 : 7;

        // Get daily sessions within the selected range
        $dailySessions = PomodoroSession::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as sessions')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // ✅ Task Completion Data
        $totalTasks = Task::where('user_id', $user->id)->count();
        $completedTasks = Task::where('user_id', $user->id)->where('status', 'completed')->count();
        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0;

        return view('analytics.index', compact(
            'totalSessions', 'totalCycles', 'totalWorkTime', 'totalBreakTime', 
            'averageSessionDuration', 'dailySessions', 'dateRange',
            'totalTasks', 'completedTasks', 'completionRate'
        ));
    }

    public function downloadPdf(Request $request)
    {
        $user = Auth::user();

        $totalSessions = PomodoroSession::where('user_id', $user->id)->count();
        $totalCycles = PomodoroSession::where('user_id', $user->id)->sum('cycles');
        $totalWorkTime = $totalCycles * 25;
        $totalBreakTime = $totalCycles * 5;
        $averageSessionDuration = PomodoroSession::where('user_id', $user->id)->avg('session_duration') ?? 0;

        $totalTasks = Task::where('user_id', $user->id)->count();
        $completedTasks = Task::where('user_id', $user->id)->where('status', 'completed')->count();
        $completionRate = $totalTasks ? round(($completedTasks / $totalTasks) * 100, 2) : 0;

        $dateRange = $request->query('date_range', '7_days');
        $days = $dateRange == '30_days' ? 30 : 7;

        $dailySessions = PomodoroSession::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as sessions')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();

        $pdf = Pdf::loadView('analytics.analytics-report', compact(
            'totalSessions', 'totalCycles', 'totalWorkTime', 'totalBreakTime',
            'averageSessionDuration', 'dailySessions', 'totalTasks', 'completedTasks',
            'completionRate', 'dateRange'
        ));

        return $pdf->download('focuspro_analytics_report.pdf');
    }

}
