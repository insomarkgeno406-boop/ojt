<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TimeLog;
use App\Models\Intern;
use Carbon\Carbon;

class TimeLogController extends Controller
{
    /**
     * Handle intern time in (only once per day).
     */
    public function timeIn()
    {
        $intern = Auth::guard('intern')->user();
        $today = now('Asia/Manila')->toDateString();

        $existing = TimeLog::where('intern_id', $intern->id)
            ->where('date', $today)
            ->first();

        if ($existing) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already timed in today.'
                ]);
            }
            return back()->with('error', 'You already timed in today.');
        }

        TimeLog::create([
            'intern_id' => $intern->id,
            'date' => $today,
            'time_in' => now('Asia/Manila')->toTimeString(),
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Time In recorded successfully!'
            ]);
        }

        return back()->with('success', '✅ Time In recorded!');
    }

    /**
     * Handle intern time out (manual or automatic at 5:00 PM).
     */
    public function timeOut()
    {
        $intern = Auth::guard('intern')->user();
        $today = now('Asia/Manila')->toDateString();

        $log = TimeLog::where('intern_id', $intern->id)
            ->where('date', $today)
            ->first();

        if (!$log) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must time in first before timing out.'
                ]);
            }
            return back()->with('error', '⚠️ You must time in first before timing out.');
        }

        if ($log->time_out) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already timed out today.'
                ]);
            }
            return back()->with('error', '⏳ You already timed out today.');
        }

        $now = now('Asia/Manila');

        // If time out is after 5:00 PM, record exactly 5:00 PM
        $timeOut = $now->greaterThan(Carbon::createFromTime(17, 0, 0, 'Asia/Manila'))
            ? '17:00:00'
            : $now->toTimeString();

        $log->update([
            'time_out' => $timeOut,
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Time Out recorded successfully!'
            ]);
        }

        return back()->with('success', '🕔 Time Out recorded!');
    }

    /**
     * Admin view of DTR as printable Blade table (not download).
     */
    public function showDTR($id)
    {
        $intern = Intern::findOrFail($id);
        $logs = TimeLog::where('intern_id', $id)
            ->orderBy('date', 'asc')
            ->get();

        return view('dtr', compact('intern', 'logs'));
    }

    /**
     * Get real-time DTR data for the current month
     */
    public function getRealTimeDTR()
    {
        $intern = Auth::guard('intern')->user();
        $currentMonth = now('Asia/Manila')->format('Y-m');
        
        $logs = TimeLog::where('intern_id', $intern->id)
            ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
            ->orderBy('date', 'desc')
            ->get();

        $totalHours = 0;
        $totalDays = 0;
        $currentDayLog = null;

        foreach ($logs as $log) {
            if ($log->time_in && $log->time_out) {
                $timeIn = Carbon::parse($log->date . ' ' . $log->time_in, 'Asia/Manila');
                $timeOut = Carbon::parse($log->date . ' ' . $log->time_out, 'Asia/Manila');
                $totalHours += $timeIn->diffInHours($timeOut);
                $totalDays++;
            }
            
            // Get current day's log
            if ($log->date === now('Asia/Manila')->toDateString()) {
                $currentDayLog = $log;
            }
        }

        return response()->json([
            'total_hours' => $totalHours,
            'total_days' => $totalDays,
            'current_day_log' => $currentDayLog,
            'current_time' => now('Asia/Manila')->format('H:i:s'),
            'is_working_hours' => now('Asia/Manila')->between(
                Carbon::createFromTime(8, 0, 0, 'Asia/Manila'),
                Carbon::createFromTime(17, 0, 0, 'Asia/Manila')
            )
        ]);
    }

    /**
     * Get DTR summary for dashboard
     */
    public function getDTRSummary()
    {
        $intern = Auth::guard('intern')->user();
        $today = now('Asia/Manila')->toDateString();
        $currentMonth = now('Asia/Manila')->format('Y-m');
        
        $todayLog = TimeLog::where('intern_id', $intern->id)
            ->where('date', $today)
            ->first();

        $monthlyLogs = TimeLog::where('intern_id', $intern->id)
            ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
            ->get();

        $monthlyHours = 0;
        $monthlyDays = 0;

        foreach ($monthlyLogs as $log) {
            if ($log->time_in && $log->time_out) {
                $timeIn = Carbon::parse($log->date . ' ' . $log->time_in, 'Asia/Manila');
                $timeOut = Carbon::parse($log->date . ' ' . $log->time_out, 'Asia/Manila');
                $monthlyHours += $timeIn->diffInHours($timeOut);
                $monthlyDays++;
            }
        }

        return response()->json([
            'today_status' => $todayLog ? ($todayLog->time_out ? 'completed' : 'working') : 'not_started',
            'today_time_in' => $todayLog?->time_in,
            'today_time_out' => $todayLog?->time_out,
            'monthly_hours' => $monthlyHours,
            'monthly_days' => $monthlyDays,
            'target_hours' => 486,
            'progress_percent' => min(100, round(($monthlyHours / 486) * 100))
        ]);
    }
}
