<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Supervisor;
use App\Models\Intern;
use App\Models\Message;
use App\Models\Attendance;
use Carbon\Carbon;

class SupervisorController extends Controller
{
    // Show supervisor login form
    public function showLoginForm()
    {
        return view('supervisor-login');
    }

    // Handle supervisor login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $supervisor = Supervisor::where('email', $credentials['email'])->first();
        if ($supervisor && $supervisor->is_accepted) {
            if (Auth::guard('supervisor')->attempt($credentials)) {
                return redirect()->route('supervisor.dashboard');
            }
            return back()->with('error', 'Invalid credentials.');
        }
        return back()->with('error', 'Your account is pending approval by the admin.');
    }

    // Show supervisor registration form
    public function showRegisterForm()
    {
        return view('supervisor-register');
    }

    // Handle supervisor registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:supervisors,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        \App\Models\Supervisor::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Hash::make($request->password),
            'is_accepted' => false,
        ]);

        return redirect()->route('supervisor.login')->with('error', 'Registration successful! Please wait for admin approval before logging in.');
    }

    // List all supervisors for admin
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Supervisor::query();
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        $supervisors = $query->orderBy('name')->get();
        return view('supervisor', compact('supervisors', 'search'));
    }

    // Update supervisor (admin)
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:supervisors,email,' . $id,
        ]);
        $supervisor = Supervisor::findOrFail($id);
        $supervisor->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        return redirect()->route('supervisors')->with('success', 'Supervisor updated successfully.');
    }

    // Delete supervisor (admin)
    public function delete($id)
    {
        $supervisor = Supervisor::findOrFail($id);
        $supervisor->delete();
        return redirect()->route('supervisors')->with('success', 'Supervisor deleted successfully.');
    }

    // Accept supervisor (admin)
    public function accept($id)
    {
        $supervisor = Supervisor::findOrFail($id);
        $supervisor->is_accepted = true;
        $supervisor->save();
        return redirect()->route('supervisors')->with('success', 'Supervisor accepted.');
    }

    // Reject supervisor (admin)
    public function reject($id)
    {
        $supervisor = Supervisor::findOrFail($id);
        $supervisor->delete();
        return redirect()->route('supervisors')->with('success', 'Supervisor rejected.');
    }

    // Supervisor dashboard
    public function dashboard()
    {
        $supervisor = auth()->guard('supervisor')->user();
        $interns = Intern::where('supervisor_id', $supervisor->id)
            ->where('status', 'accepted')
            ->get();
            
        // Get attendance statistics
        $totalInterns = $interns->count();
        $presentCount = $interns->where('attendance_status', 'present')->count();
        $absentCount = $interns->where('attendance_status', 'absent')->count();
        $notNoticedCount = $interns->where('attendance_status', 'not_released')->count();
        
        // Get active attendance session
        $activeAttendance = Attendance::where('supervisor_id', $supervisor->id)
            ->where('is_active', true)
            ->where('created_at', '>', now()->subMinutes(5))
            ->first();
            
        return view('supervisor-dashboard', compact(
            'supervisor', 
            'interns', 
            'totalInterns', 
            'presentCount', 
            'absentCount', 
            'notNoticedCount',
            'activeAttendance'
        ));
    }

    // Supervisor releases attendance for connected interns only
    public function releaseAttendance()
    {
        $supervisor = auth()->guard('supervisor')->user();
        
        // Get only interns connected to this supervisor
        $connectedInterns = Intern::where('supervisor_id', $supervisor->id)
            ->where('status', 'accepted')
            ->get();
            
        if ($connectedInterns->isEmpty()) {
            return redirect()->route('supervisor.dashboard')
                ->with('error', 'No interns are connected to you.');
        }
        
        $now = now();
        
        // Deactivate any existing active attendances for this supervisor
        Attendance::where('supervisor_id', $supervisor->id)
            ->where('is_active', true)
            ->update(['is_active' => false]);
        
        // Create new attendance session
        Attendance::createForSupervisor($supervisor->id);
        
        foreach ($connectedInterns as $intern) {
            $intern->update([
                'attendance_status' => 'released',
                'attendance_released_at' => $now
            ]);
            
            // Send notification to intern
            Message::create([
                'sender_id' => $supervisor->id,
                'receiver_id' => $intern->id,
                'sender_type' => 'supervisor',
                'receiver_type' => 'intern',
                'content' => 'Time In is now available! Click "Time In" to mark your attendance. (Expires in 5 minutes)',
                'is_read' => false,
            ]);
        }
        
        return redirect()->route('supervisor.dashboard')
            ->with('success', 'Time In released for ' . $connectedInterns->count() . ' connected interns. (Expires in 5 minutes)');
    }

    // Supervisor logout
    public function logout()
    {
        \Auth::guard('supervisor')->logout();
        return redirect()->route('supervisor.login');
    }
} 