<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Intern;
use App\Models\Message;
use App\Models\DocumentRequest;
use App\Models\GradeSubmission;
use App\Models\TimeLog;
use App\Models\Journal;
use App\Models\Document;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $acceptedCount = Intern::where('status', 'accepted')->whereNull('archived_at')->count();
        $pendingCount = Intern::where('status', 'pending')->count();
        $messageCount = Message::count();

        $unreadMessagesCount = Message::where('receiver_id', Auth::id())
            ->where('receiver_type', 'admin')
            ->where('sender_type', 'intern')
            ->where('is_read', false)
            ->count();

        // Progress Tracking
        $totalInterns = $acceptedCount;
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        // Sum durations for today
        $todayHours = TimeLog::whereDate('time_in', $today)->get()->sum(function ($log) {
            return ($log->time_in && $log->time_out)
                ? Carbon::parse($log->time_out)->diffInMinutes(Carbon::parse($log->time_in)) / 60
                : 0;
        });

        // Sum durations for this week
        $weekHours = TimeLog::whereBetween('time_in', [$startOfWeek, now()])->get()->sum(function ($log) {
            return ($log->time_in && $log->time_out)
                ? Carbon::parse($log->time_out)->diffInMinutes(Carbon::parse($log->time_in)) / 60
                : 0;
        });

        // Sum durations for this month
        $monthHours = TimeLog::whereBetween('time_in', [$startOfMonth, now()])->get()->sum(function ($log) {
            return ($log->time_in && $log->time_out)
                ? Carbon::parse($log->time_out)->diffInMinutes(Carbon::parse($log->time_in)) / 60
                : 0;
        });

        // Count working days (Mon–Sat) for current month
        $workingDaysThisMonth = Carbon::now()->diffInDaysFiltered(function ($date) {
            return !$date->isSunday(); // Mon–Sat only
        }, $startOfMonth);

        // Progress Calculations (capped at 100%)
        $todayProgress = $totalInterns > 0 ? min(100, round(($todayHours / ($totalInterns * 8)) * 100)) : 0;
        $weekProgress = $totalInterns > 0 ? min(100, round(($weekHours / ($totalInterns * 6 * 8)) * 100)) : 0;
        $monthProgress = ($totalInterns > 0 && $workingDaysThisMonth > 0)
            ? min(100, round(($monthHours / ($totalInterns * $workingDaysThisMonth * 8)) * 100))
            : 0;

        // ➕ Count "To Review" Submissions
        $interns = Intern::where('status', 'accepted')->whereNull('archived_at')->get();

        $requests = DocumentRequest::all()->groupBy('intern_id')->map->keyBy('type');
        $submissions = GradeSubmission::all()->groupBy('intern_id')->map->keyBy(function ($item) {
            $map = ['1st' => 'midterm', '2nd' => 'final', '3rd' => 'certificate', '4th' => 'evaluation'];
            return $map[$item->semester] ?? null;
        });

        $toReview = 0;

        foreach ($interns as $intern) {
            foreach (['midterm', 'final', 'certificate', 'evaluation'] as $type) {
                $isRequested = isset($requests[$intern->id][$type]);
                $isSubmitted = isset($submissions[$intern->id][$type]) &&
                               !empty($submissions[$intern->id][$type]->file_path);
                if ($isRequested && !$isSubmitted) {
                    $toReview++;
                }
            }
        }

        // ➕ Calculate percentage for circular chart
        $toReviewPercent = $acceptedCount > 0
            ? min(100, round(($toReview / ($acceptedCount * 4)) * 100))
            : 0;

        return view('dashboard', compact(
            'acceptedCount',
            'pendingCount',
            'messageCount',
            'unreadMessagesCount',
            'todayProgress',
            'weekProgress',
            'monthProgress',
            'toReview',
            'toReviewPercent'
        ));
    }

    public function interns(Request $request)
    {
        $filter = $request->get('filter');
        $phase = $request->get('phase', 'all');

        if ($phase && $phase !== 'all') {
            // Phase-focused view: list interns by current_phase regardless of status
            $query = Intern::query()->where('current_phase', $phase);
        } else {
            // Default view: pending accounts awaiting admin approval
            $query = Intern::query()->where('status', 'pending');
        }

        if ($filter && $filter !== 'all') {
            $query->where('section', $filter);
        }

        $interns = $query->select(
                'id',
                'first_name',
                'last_name',
                'course',
                'section',
                'status',
                'current_phase',
                'pre_enrollment_status',
                'pre_deployment_status',
                'mid_deployment_status',
                'deployment_status',
                'resume',
                'application_letter',
                'medical_certificate',
                'insurance',
                'acceptance_letter',
                'parents_waiver',
                'memorandum_of_agreement',
                'internship_contract',
                'recommendation_letter'
            )
            ->orderBy('section')
            ->paginate(10);

        $interns->appends($request->all());

        $sectionCounts = ($phase && $phase !== 'all')
            ? Intern::where('current_phase', $phase)
                ->selectRaw('section, COUNT(*) as count')
                ->groupBy('section')
                ->pluck('count', 'section')
            : Intern::where('status', 'pending')
                ->selectRaw('section, COUNT(*) as count')
                ->groupBy('section')
                ->pluck('count', 'section');

        // Phase counts across all interns (regardless of status)
        $phaseCounts = Intern::selectRaw('current_phase, COUNT(*) as count')
            ->groupBy('current_phase')
            ->pluck('count', 'current_phase');

        return view('interns', compact('interns', 'sectionCounts', 'phaseCounts', 'filter', 'phase'));
    }

    public function documents(Request $request)
    {
        $filter = $request->input('filter');
        $search = $request->input('search');

        $query = Intern::where('status', 'accepted')
            ->whereNull('archived_at')
            ->with(['timeLogs', 'documents', 'journals']);

        // Apply section filter
        if ($filter && $filter !== 'all') {
            $query->where('section', $filter);
        }

        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('section', 'LIKE', "%{$search}%");
            });
        }

        // Add pagination - 10 items per page
        $interns = $query->select(
                'id',
                'first_name',
                'last_name',
                'supervisor_name',
                'company_name',
                'section',
                'application_letter',
                'parents_waiver',
                'acceptance_letter'
            )
            ->orderBy('section')
            ->paginate(10);

        // Preserve query parameters in pagination links
        $interns->appends($request->all());

        // Get section counts for accepted (unarchived) interns
        $sectionCounts = Intern::where('status', 'accepted')
            ->whereNull('archived_at')
            ->selectRaw('section, COUNT(*) as count')
            ->groupBy('section')
            ->pluck('count', 'section')
            ->toArray();

        return view('documents', compact('interns', 'sectionCounts', 'filter', 'search'));
    }

    public function documentsArchive(Request $request)
    {
        $search = $request->input('search');

        $query = Intern::whereNotNull('archived_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('section', 'LIKE', "%{$search}%");
            });
        }

        $archivedInterns = $query->select(
                'id', 'first_name', 'last_name', 'section',
                'application_letter', 'parents_waiver', 'acceptance_letter', 'archived_at'
            )
            ->orderBy('section')
            ->orderBy('last_name')
            ->get();

        return view('documents-archive', compact('archivedInterns', 'search'));
    }

    public function archiveIntern($id)
    {
        $intern = Intern::findOrFail($id);
        $intern->archived_at = now();
        $intern->save();

        return redirect()->back()->with('success', 'Intern archived successfully.');
    }

    public function qr()
    {
        return view('qr');
    }

    public function messages()
    {
        $interns = Intern::where('status', 'accepted')
            ->select('id', 'first_name', 'last_name', 'email')
            ->get();

        return view('messages', compact('interns'));
    }

    public function grades(Request $request)
    {
        $filter = $request->input('filter');
        $search = $request->input('search');

        $query = Intern::where('status', 'accepted');

        // Apply section filter
        if ($filter && $filter !== 'all') {
            $query->where('section', $filter);
        }

        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('section', 'LIKE', "%{$search}%");
            });
        }

        $interns = $query->select('id', 'first_name', 'last_name', 'course', 'section')
            ->orderBy('section')
            ->get();

        $sectionCounts = Intern::where('status', 'accepted')
            ->selectRaw('section, COUNT(*) as count')
            ->groupBy('section')
            ->pluck('count', 'section')
            ->toArray();

        $requests = DocumentRequest::all()->groupBy('intern_id')->map(function ($items) {
            return $items->keyBy('type');
        });

        $submissions = GradeSubmission::all()->groupBy('intern_id')->map(function ($items) {
            return $items->keyBy(function ($item) {
                $map = [
                    '3rd' => 'certificate',
                    '4th' => 'evaluation',
                ];
                return $map[$item->semester] ?? null;
            });
        });

        return view('grades', compact(
            'interns',
            'sectionCounts',
            'filter',
            'search',
            'requests',
            'submissions'
        ));
    }

    public function sendGradeRequest(Request $request)
    {
        $request->validate([
            'intern_id' => 'required|exists:interns,id',
            'type' => 'required|string|in:Midterm,Final,Certificate,Evaluation Form',
        ]);

        $normalized = strtolower(str_replace(' ', '', $request->type));

        $typeMap = [
            'midterm' => 'midterm',
            'final' => 'final',
            'certificate' => 'certificate',
            'evaluationform' => 'evaluation',
        ];

        $type = $typeMap[$normalized] ?? null;

        if (!$type) {
            return back()->with('error', 'Invalid document type.');
        }

        DocumentRequest::updateOrCreate(
            ['intern_id' => $request->intern_id, 'type' => $type],
            ['requested_at' => now()]
        );

        return redirect()->back()->with('success', 'Document request sent successfully.');
    }

    public function deleteAllInterns()
    {
        DB::transaction(function () {
            TimeLog::truncate();
            Journal::truncate();
            Document::truncate();
            GradeSubmission::truncate();
            Message::where('sender_type', 'intern')->orWhere('receiver_type', 'intern')->delete();
            DocumentRequest::truncate();
            Intern::truncate();
        });

        return redirect()->back()->with('success', 'All interns and their related data have been deleted.');
    }

    /**
     * Delete a single intern and their related data/files.
     */
    public function destroyIntern($id)
    {
        DB::transaction(function () use ($id) {
            $intern = Intern::findOrFail($id);
            
            // Delete all related data
            TimeLog::where('intern_id', $id)->delete();
            Journal::where('intern_id', $id)->delete();
            Document::where('intern_id', $id)->delete();
            GradeSubmission::where('intern_id', $id)->delete();
            Message::where('sender_id', $id)->where('sender_type', 'intern')->delete();
            Message::where('receiver_id', $id)->where('receiver_type', 'intern')->delete();
            DocumentRequest::where('intern_id', $id)->delete();
            
            // Delete documents from storage if exist
            if ($intern->application_letter) {
                Storage::disk('public')->delete($intern->application_letter);
            }
            if ($intern->parents_waiver) {
                Storage::disk('public')->delete($intern->parents_waiver);
            }
            if ($intern->acceptance_letter) {
                Storage::disk('public')->delete($intern->acceptance_letter);
            }
            
            // Finally delete the intern
            $intern->delete();
        });

        return redirect()->back()->with('success', 'Intern and all related data deleted successfully.');
    }

    public function index()
    {
        $dtrs = Dtr::with('intern')->get();
        return view('documents', compact('dtrs'));
    }
}