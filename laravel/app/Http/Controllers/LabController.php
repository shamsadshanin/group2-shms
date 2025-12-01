<?php

namespace App\Http\Controllers;

use App\Models\LabTechnician;
use App\Models\Investigation;
use App\Models\TestType;
use App\Http\Requests\InvestigationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class LabController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('lab.technician'); // Custom middleware to ensure user is a lab technician
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $technician = LabTechnician::where('user_id', $user->id)->firstOrFail();

        // Base query for investigations
        $query = Investigation::with(['patient', 'testType', 'doctor'])
                    ->orderBy('Priority', 'desc')
                    ->orderBy('created_at', 'asc');

        // Apply filters
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('patient', function($q2) use ($search) {
                    $q2->where('Name', 'like', "%{$search}%")
                       ->orWhere('Email', 'like', "%{$search}%");
                })->orWhereHas('testType', function($q2) use ($search) {
                    $q2->where('TestName', 'like', "%{$search}%");
                });
            });
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('Status', $request->status);
        } else {
            // Default to pending and assigned investigations
            $query->whereIn('Status', ['Pending', 'Assigned', 'Processing']);
        }

        if ($request->has('priority') && $request->priority !== 'all') {
            $query->where('Priority', $request->priority);
        }

        if ($request->has('test_type') && $request->test_type !== 'all') {
            $query->where('TestTypeID', $request->test_type);
        }

        $investigations = $query->paginate(15);

        // Statistics for dashboard
        $stats = [
            'pending' => Investigation::whereIn('Status', ['Pending', 'Assigned'])->count(),
            'processing' => Investigation::where('Status', 'Processing')->count(),
            'completed_today' => Investigation::where('Status', 'Completed')
                                ->whereDate('CompletedDate', today())->count(),
            'high_priority' => Investigation::whereIn('Priority', ['High', 'Critical'])
                                ->whereIn('Status', ['Pending', 'Assigned', 'Processing'])->count(),
        ];

        // Get test types for filter
        $testTypes = TestType::active()->get();

        return view('lab.dashboard', compact('technician', 'investigations', 'stats', 'testTypes'));
    }

    public function updateInvestigation(InvestigationRequest $request, $id)
    {
        try {
            $technician = Auth::user()->labTechnician;

            $investigation = Investigation::findOrFail($id);

            // Verify the investigation can be updated
            if (!$investigation->canBeUpdated()) {
                return redirect()->back()
                               ->with('error', 'This investigation cannot be updated as it is already completed or cancelled.');
            }

            // Handle file upload
            $digitalReportPath = $investigation->DigitalReport;
            if ($request->hasFile('DigitalReport')) {
                // Delete old file if exists
                if ($investigation->DigitalReport) {
                    Storage::disk('public')->delete($investigation->DigitalReport);
                }

                $digitalReportPath = $request->file('DigitalReport')->store('lab-reports', 'public');
            }

            // Update investigation
            $updateData = [
                'ResultSummary' => $request->ResultSummary,
                'DetailedResults' => $request->DetailedResults,
                'TestParameters' => $request->TestParameters,
                'DigitalReport' => $digitalReportPath,
                'Status' => $request->Status,
                'StaffID' => $technician->StaffID,
            ];

            // Set timestamps based on status
            if ($request->Status == 'Processing' && !$investigation->ProcessingDate) {
                $updateData['ProcessingDate'] = now();
            } elseif ($request->Status == 'Completed' && !$investigation->CompletedDate) {
                $updateData['CompletedDate'] = now();
            }

            $investigation->update($updateData);

            // Here you could trigger notifications (email, SMS, etc.)
            // event(new InvestigationUpdated($investigation));

            return redirect()->route('lab.dashboard')
                           ->with('success', 'Investigation updated successfully!');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()
                           ->with('error', 'Investigation not found.')
                           ->withInput();
        } catch (\Exception $e) {
            \Log::error('Investigation update failed: ' . $e->getMessage());

            return redirect()->back()
                           ->with('error', 'Failed to update investigation. Please try again.')
                           ->withInput();
        }
    }

    public function assignToMe($id)
    {
        try {
            $technician = Auth::user()->labTechnician;

            $investigation = Investigation::where('InvestigationID', $id)
                            ->whereIn('Status', ['Pending', 'Assigned'])
                            ->firstOrFail();

            // Check if technician is available
            if (!$technician->isAvailable()) {
                return redirect()->back()
                               ->with('error', 'You have too many pending investigations. Please complete some before taking new ones.');
            }

            $investigation->update([
                'StaffID' => $technician->StaffID,
                'Status' => 'Assigned',
            ]);

            return redirect()->back()
                           ->with('success', 'Investigation assigned to you successfully.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()
                           ->with('error', 'Investigation not found or cannot be assigned.');
        } catch (\Exception $e) {
            \Log::error('Investigation assignment failed: ' . $e->getMessage());

            return redirect()->back()
                           ->with('error', 'Failed to assign investigation. Please try again.');
        }
    }

    public function investigationHistory(Request $request)
    {
        $technician = Auth::user()->labTechnician;

        $query = Investigation::where('StaffID', $technician->StaffID)
                    ->where('Status', 'Completed')
                    ->with(['patient', 'testType'])
                    ->orderBy('CompletedDate', 'desc');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('patient', function($q2) use ($search) {
                    $q2->where('Name', 'like', "%{$search}%");
                })->orWhereHas('testType', function($q2) use ($search) {
                    $q2->where('TestName', 'like', "%{$search}%");
                });
            });
        }

        if ($request->has('date_from')) {
            $query->whereDate('CompletedDate', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('CompletedDate', '<=', $request->date_to);
        }

        $investigations = $query->paginate(20);

        return view('lab.history', compact('technician', 'investigations'));
    }

    public function downloadReport($id)
    {
        try {
            $technician = Auth::user()->labTechnician;

            $investigation = Investigation::where('InvestigationID', $id)
                            ->where('StaffID', $technician->StaffID)
                            ->firstOrFail();

            if (!$investigation->DigitalReport) {
                return redirect()->back()
                               ->with('error', 'No digital report available for this investigation.');
            }

            return Storage::disk('public')->download($investigation->DigitalReport);

        } catch (\Exception $e) {
            \Log::error('Report download failed: ' . $e->getMessage());

            return redirect()->back()
                           ->with('error', 'Failed to download report.');
        }
    }
    public function investigations(Request $request)
    {
        $user = Auth::user();
        $technician = LabTechnician::where('user_id', $user->id)->firstOrFail();

        $query = Investigation::with(['patient', 'testType', 'doctor', 'technician'])
                    ->orderBy('Priority', 'desc')
                    ->orderBy('created_at', 'asc');

        // Apply filters similar to dashboard
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('patient', function($q2) use ($search) {
                    $q2->where('Name', 'like', "%{$search}%")
                       ->orWhere('Email', 'like', "%{$search}%");
                })->orWhereHas('testType', function($q2) use ($search) {
                    $q2->where('TestName', 'like', "%{$search}%");
                });
            });
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('Status', $request->status);
        }

        if ($request->has('priority') && $request->priority !== 'all') {
            $query->where('Priority', $request->priority);
        }

        if ($request->has('test_type') && $request->test_type !== 'all') {
            $query->where('TestTypeID', $request->test_type);
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $investigations = $query->paginate(15);

        $stats = [
            'pending' => Investigation::whereIn('Status', ['Pending', 'Assigned'])->count(),
            'processing' => Investigation::where('Status', 'Processing')->count(),
            'assigned_to_me' => Investigation::where('StaffID', $technician->StaffID)
                                ->whereIn('Status', ['Assigned', 'Processing'])->count(),
            'high_priority' => Investigation::whereIn('Priority', ['High', 'Critical'])
                                ->whereIn('Status', ['Pending', 'Assigned', 'Processing'])->count(),
        ];

        $testTypes = TestType::active()->get();

        return view('lab.investigations', compact('technician', 'investigations', 'stats', 'testTypes'));
    }

}
