<?php

namespace App\Http\Controllers;

use App\Models\Intern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class InternController extends Controller
{
    /**
     * Store a new intern via registration form (Pre-Enrollment Phase).
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:interns,email',
            'password' => 'required|min:6',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'course' => 'required|string|max:100',
            'section' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'supervisor_name' => 'required|string|max:100',
            'supervisor_position' => 'nullable|string|max:100',
            'supervisor_email' => 'required|email',
            'company_name' => 'nullable|string|max:150',
            'company_address' => 'nullable|string|max:255',
        ]);

        // Create intern record; satisfy legacy NOT NULL columns with placeholders
        Intern::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'course' => $request->course,
            'section' => $request->section,
            'phone' => $request->phone,
            'supervisor_name' => $request->supervisor_name,
            'supervisor_position' => $request->supervisor_position,
            'supervisor_email' => $request->supervisor_email,
            'company_name' => $request->company_name ?? '',
            'company_address' => $request->company_address ?? '',
            // Legacy NOT NULL columns from initial schema
            'company_phone' => '',
            'application_letter' => '',
            'parents_waiver' => '',
            'acceptance_letter' => '',
            // Phase tracking
            'status' => 'pending',
            'current_phase' => 'pre_deployment', // Start directly at Pre-Deployment after registration
            'pre_enrollment_status' => 'pending',
        ]);

        return redirect()->back()->with('success', "Please wait for the Admin's Approval.");
    }

    /**
     * Update an intern's editable fields (used by admin).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'course' => 'required|string|max:100',
            'section' => 'required|string|max:50',
        ]);

        $intern = Intern::findOrFail($id);
        $intern->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'course' => $request->course,
            'section' => $request->section,
        ]);

        return redirect()->back()->with('success', 'Intern updated successfully.');
    }

    /**
     * Delete an intern and optionally their files.
     */
    public function destroy($id)
    {
        $intern = Intern::findOrFail($id);

        // Delete documents from storage if exist
        Storage::disk('public')->delete([
            $intern->application_letter,
            $intern->parents_waiver,
            $intern->acceptance_letter,
        ]);

        $intern->delete();

        return redirect()->back()->with('success', 'Intern deleted successfully.');
    }

    /**
     * Accept an intern (allow login access).
     */
    public function accept($id)
    {
        $intern = Intern::findOrFail($id);
        $intern->status = 'accepted';
        $intern->save();

        return redirect()->back()->with('success', 'Intern accepted and now able to log in.');
    }

    /**
     * Reject an intern (block login & hide from admin pending list).
     */
    public function reject($id)
    {
        $intern = Intern::findOrFail($id);
        $intern->status = 'rejected';
        $intern->save();

        return redirect()->back()->with('success', 'Intern rejected and removed from list.');
    }

    /**
     * Accept Pre-Enrollment Phase
     */
    public function acceptPreEnrollment($id)
    {
        $intern = Intern::findOrFail($id);
        $intern->pre_enrollment_status = 'accepted';
        $intern->pre_enrollment_accepted_at = now();
        $intern->current_phase = 'pre_deployment';
        $intern->save();

        return redirect()->back()->with('success', 'Pre-Enrollment Phase accepted. Intern can now proceed to Pre-Deployment Phase.');
    }

    /**
     * Accept Pre-Deployment Phase
     */
    public function acceptPreDeployment($id)
    {
        $intern = Intern::findOrFail($id);
        $intern->pre_deployment_status = 'accepted';
        $intern->pre_deployment_accepted_at = now();
        $intern->current_phase = 'mid_deployment';
        $intern->save();

        return redirect()->back()->with('success', 'Pre-Deployment Phase accepted. Intern can now proceed to Mid-Deployment Phase.');
    }

    /**
     * Accept Mid-Deployment Phase
     */
    public function acceptMidDeployment($id)
    {
        $intern = Intern::findOrFail($id);
        $intern->mid_deployment_status = 'accepted';
        $intern->mid_deployment_accepted_at = now();
        $intern->current_phase = 'deployment';
        $intern->save();

        return redirect()->back()->with('success', 'Mid-Deployment Phase accepted. Intern can now proceed to Deployment Phase.');
    }

    /**
     * Accept Deployment Phase
     */
    public function acceptDeployment($id)
    {
        $intern = Intern::findOrFail($id);
        $intern->deployment_status = 'accepted';
        $intern->deployment_accepted_at = now();
        $intern->current_phase = 'completed';
        $intern->save();

        return redirect()->back()->with('success', 'Deployment Phase accepted. Intern has completed all phases.');
    }

    /**
     * Submit Pre-Deployment documents
     */
    public function submitPreDeployment(Request $request)
    {
        $request->validate([
            'resume' => 'required|file|mimes:pdf,doc,docx',
            'application_letter' => 'required|file|mimes:pdf,doc,docx',
            'medical_certificate' => 'required|file|mimes:pdf,doc,docx',
            'insurance' => 'required|file|mimes:pdf,doc,docx',
            'parents_waiver' => 'required|file|mimes:pdf,doc,docx',
        ]);

        $intern = Auth::guard('intern')->user();
        
        // Upload Pre-Deployment Phase documents
        $resume = $request->file('resume')->store('documents', 'public');
        $applicationLetter = $request->file('application_letter')->store('documents', 'public');
        $medicalCertificate = $request->file('medical_certificate')->store('documents', 'public');
        $insurance = $request->file('insurance')->store('documents', 'public');
        $parentsWaiver = $request->file('parents_waiver')->store('documents', 'public');

        // Auto-generate Acceptance Letter HTML and attach
        $start = now('Asia/Manila');
        $end = (clone $start)->addHours(486);
        $html = view('Acceptance-Letter', [
            'intern' => $intern,
            'startDate' => $start,
            'endDate' => $end,
        ])->render();
        $acceptancePath = 'documents/acceptance_letter_intern_' . $intern->id . '.html';
        Storage::disk('public')->put($acceptancePath, $html);

        $intern->update([
            'resume' => $resume,
            'application_letter' => $applicationLetter,
            'medical_certificate' => $medicalCertificate,
            'insurance' => $insurance,
            'acceptance_letter' => $acceptancePath,
            'parents_waiver' => $parentsWaiver,
            'pre_deployment_status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Pre-Deployment documents submitted successfully. Please wait for admin acceptance.');
    }

    /**
     * Submit Mid-Deployment documents
     */
    public function submitMidDeployment(Request $request)
    {
        $request->validate([]);

        $intern = Auth::guard('intern')->user();
        
        // Auto-generate MOA and Contract HTML and attach
        $now = now('Asia/Manila');
        $moaHtml = view('memorandum', [
            'intern' => $intern,
            'today' => $now,
        ])->render();
        $moaPath = 'documents/memorandum_intern_' . $intern->id . '.html';
        Storage::disk('public')->put($moaPath, $moaHtml);

        $start = $now;
        $end = (clone $start)->addHours(486);
        $contractHtml = view('internship-contract', [
            'intern' => $intern,
            'startDate' => $start,
            'endDate' => $end,
            'today' => $now,
        ])->render();
        $contractPath = 'documents/internship_contract_intern_' . $intern->id . '.html';
        Storage::disk('public')->put($contractPath, $contractHtml);

        $intern->update([
            'memorandum_of_agreement' => $moaPath,
            'internship_contract' => $contractPath,
            'mid_deployment_status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Mid-Deployment documents submitted successfully. Please wait for admin acceptance.');
    }

    /**
     * Submit Deployment documents
     */
    public function submitDeployment(Request $request)
    {
        $request->validate([
            'recommendation_letter' => 'required|file|mimes:pdf,doc,docx',
            // endorsement_letter is auto-generated as HTML, no upload needed
        ]);

        $intern = Auth::guard('intern')->user();
        
        // Upload Deployment Phase documents
        $recommendationLetter = $request->file('recommendation_letter')->store('documents', 'public');
        // Endorsement letter is generated as HTML; store a marker/path if needed later
        $endorsementLetter = null;

        $intern->update([
            'recommendation_letter' => $recommendationLetter,
            'endorsement_letter' => $endorsementLetter,
            'deployment_status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Deployment documents submitted successfully. Please wait for admin acceptance.');
    }

    /**
     * View uploaded documents with proper content-type headers.
     */
    public function viewDocument($filename)
    {
        $filePath = storage_path('app/public/documents/' . $filename);
        
        if (!file_exists($filePath)) {
            abort(404, 'Document not found.');
        }

        $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        // Set appropriate content-type headers based on file extension
        switch ($fileExtension) {
            case 'pdf':
                $contentType = 'application/pdf';
                break;
            case 'docx':
                $contentType = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
                break;
            case 'doc':
                $contentType = 'application/msword';
                break;
            case 'jpg':
            case 'jpeg':
                $contentType = 'image/jpeg';
                break;
            case 'png':
                $contentType = 'image/png';
                break;
            case 'gif':
                $contentType = 'image/gif';
                break;
            default:
                $contentType = 'application/octet-stream';
        }

        return response()->file($filePath, [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }

    /**
     * Admin view: Render the auto-generated Endorsement letter as HTML for a specific intern.
     */
    public function viewEndorsement($id)
    {
        $intern = Intern::findOrFail($id);

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
}

