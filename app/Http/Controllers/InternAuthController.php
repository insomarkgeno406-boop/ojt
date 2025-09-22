<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Intern;
use App\Models\TimeLog;
use App\Models\Message;
use App\Models\GradeSubmission;
use App\Models\DocumentRequest;
use Carbon\Carbon;

class InternAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('intern-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $intern = Intern::where('email', $request->email)->first();

        if (!$intern || !\Hash::check($request->password, $intern->password)) {
            return back()->with('error', 'Invalid intern credentials.');
        }

        // Must be accepted by admin before login
        if ($intern->status !== 'accepted') {
            return back()->with('error', "Please wait for the Admin's Approval.");
        }

        Auth::guard('intern')->login($intern);
        
        // If phases complete -> dashboard; else -> phase submission page
        if ($intern->hasCompletedAllPhases()) {
            return redirect()->route('intern.dashboard');
        }

        return redirect()->route('intern.phase-submission');
    }

    public function logout()
    {
        Auth::guard('intern')->logout();
        return redirect()->route('intern.login');
    }

    public function dashboard()
    {
        $intern = Auth::guard('intern')->user();

        // Calculate total OJT hours from TimeLog
        $logs = TimeLog::where('intern_id', $intern->id)->get();
        $totalSeconds = 0;

        foreach ($logs as $log) {
            $in = $log->time_in ? Carbon::parse($log->date . ' ' . $log->time_in, 'Asia/Manila') : null;
            $out = $log->time_out ? Carbon::parse($log->date . ' ' . $log->time_out, 'Asia/Manila') : null;

            if ($in && !$out) {
                $out = Carbon::parse($log->date . ' 17:00:00', 'Asia/Manila');
            }

            if ($in && $out) {
                $totalSeconds += $in->diffInSeconds($out);
            }
        }

        $totalHours = round($totalSeconds / 3600, 2);
        $remainingHours = max(0, 486 - $totalHours);
        $progressPercent = min(100, ($totalHours / 486) * 100);

        // Count unread messages from admin
        $unreadMessages = Message::where('receiver_id', $intern->id)
            ->where('receiver_type', 'intern')
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->count();

        // Get pending document requests
        $pendingRequests = DocumentRequest::where('intern_id', $intern->id)
            ->pluck('type')
            ->toArray();

        // Check if it's Friday and intern hasn't submitted journal this week
        $isFriday = now('Asia/Manila')->isFriday();
        $hasSubmittedJournalThisWeek = $intern->hasSubmittedJournalThisWeek();

        // Check if end of month is approaching
        $daysUntilEndOfMonth = now('Asia/Manila')->endOfMonth()->diffInDays(now('Asia/Manila'));

        return view('intern-dashboard', compact(
            'intern',
            'totalHours',
            'remainingHours',
            'progressPercent',
            'unreadMessages',
            'pendingRequests',
            'isFriday',
            'hasSubmittedJournalThisWeek',
            'daysUntilEndOfMonth'
        ));
    }

    public function endorsement()
    {
        $intern = Auth::guard('intern')->user();

        $data = [
            'supervisor_name' => $intern->supervisor_name,
            'supervisor_position' => $intern->supervisor_position,
            'company_name' => $intern->company_name,
            'company_address' => $intern->company_address,
            'interns' => [ $intern->first_name . ' ' . $intern->last_name ],
            'sentAt' => now('Asia/Manila'),
        ];

        return view('Endorsement', $data);
    }

    public function acceptanceLetter()
    {
        $intern = Auth::guard('intern')->user();

        return view('Acceptance-Letter', [
            'intern' => $intern,
            'today' => now('Asia/Manila'),
        ]);
    }

    public function memorandum()
    {
        $intern = Auth::guard('intern')->user();

        return view('memorandum', [
            'intern' => $intern,
            'today' => now('Asia/Manila'),
        ]);
    }

    public function internshipContract()
    {
        $intern = Auth::guard('intern')->user();

        return view('internship-contract', [
            'intern' => $intern,
            'today' => now('Asia/Manila'),
        ]);
    }

    public function showSendDataForm()
    {
        $intern = Auth::guard('intern')->user();

        $requests = DocumentRequest::where('intern_id', $intern->id)
            ->pluck('type')
            ->toArray();

        return view('send-data', compact('intern', 'requests'));
    }

    public function phaseSubmission()
    {
        $intern = Auth::guard('intern')->user();
        return view('phase-submission', compact('intern'));
    }

    public function uploadDocx(Request $request)
    {
        $request->validate([
            'semester'   => 'required|in:1st,2nd,3rd,4th',
            'grade_doc'  => 'required|file|mimes:doc,docx|max:10240',
        ]);

        $intern = Auth::guard('intern')->user();

        // Store file in storage/app/public/grades
        $file = $request->file('grade_doc');
        $filename = now()->format('YmdHis') . "_intern{$intern->id}." . $file->getClientOriginalExtension();
        $path = $file->storeAs('grades', $filename, 'public');

        // Save or update grade submission
        GradeSubmission::updateOrCreate(
            ['intern_id' => $intern->id, 'semester' => $request->semester],
            ['file_path' => $path, 'submitted_at' => now()]
        );

        // Map semester to request type
        $typeMap = [
            '1st' => 'midterm',
            '2nd' => 'final',
            '3rd' => 'certificate',
            '4th' => 'evaluation',
        ];

        $matchedType = $typeMap[$request->semester] ?? null;

        if ($matchedType) {
            DocumentRequest::where('intern_id', $intern->id)
                ->where('type', $matchedType)
                ->delete();
        }

        return redirect()->route('intern.dashboard')->with('success', 'File successfully uploaded.');
    }

    /**
     * Show the Daily Time Record page for the logged-in intern.
     */
    public function dtr()
    {
        $intern = Auth::guard('intern')->user();

        // Fetch all time logs for this intern
        $logs = TimeLog::where('intern_id', $intern->id)
            ->orderBy('date', 'desc')
            ->get();

        return view('intern-dashboard', compact('intern', 'logs'));
    }
}
