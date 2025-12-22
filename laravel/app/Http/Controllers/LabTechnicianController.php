<?php

namespace App\Http\Controllers;

use App\Models\LabTechnician;
use App\Models\LabTest; // Maps to Investigation Table
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LabTechnicianController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:lab');
    }

    private function getTechnician()
    {
        return LabTechnician::where('Email', Auth::user()->email)->firstOrFail();
    }

    public function dashboard()
    {
        $tech = $this->getTechnician();

        // 1. New Requests (যেগুলোর StaffID এখনো NULL)
        $newRequests = LabTest::whereNull('StaffID')
            ->orderBy('InvestigationID', 'asc')
            ->get();

        // 2. My Pending Work (যেগুলো এই টেকনিশিয়ান একসেপ্ট করেছে কিন্তু রেজাল্ট দেয়নি)
        $myPending = LabTest::where('StaffID', $tech->StaffID)
            ->whereNull('Result_Summary')
            ->get();

        // 3. Completed
        $completed = LabTest::where('StaffID', $tech->StaffID)
            ->whereNotNull('Result_Summary')
            ->take(10)
            ->get();

        return view('lab.dashboard', compact('tech', 'newRequests', 'myPending', 'completed'));
    }

    // টেস্ট একসেপ্ট করার ফাংশন
    public function acceptTest($id)
    {
        $tech = $this->getTechnician();
        $test = LabTest::where('InvestigationID', $id)->whereNull('StaffID')->firstOrFail();

        // স্টাফ আইডি আপডেট করে নিজের নামে নিয়ে নেওয়া
        $test->update(['StaffID' => $tech->StaffID]);

        return redirect()->back()->with('success', 'Investigation accepted!');
    }

    public function tests()
    {
        $tech = $this->getTechnician();
        $tests = LabTest::where('StaffID', $tech->StaffID)
            ->with('patient')
            ->orderBy('InvestigationID', 'desc')
            ->get();

        return view('lab.tests', compact('tech', 'tests'));
    }

    public function show($id)
    {
        $tech = $this->getTechnician();
        $test = LabTest::where('InvestigationID', $id)
            ->where('StaffID', $tech->StaffID)
            ->with('patient')
            ->firstOrFail();

        return view('lab.tests.show', compact('test'));
    }

    public function create()
    {
        $patients = Patient::all();
        return view('lab.tests.create', compact('patients'));
    }

    // --- FIX IS HERE ---
    public function store(Request $request)
    {
        // 1. Validation now matches Blade form names (PatientID, Test, TestType)
        $request->validate([
            'PatientID' => ['required', 'exists:Patient,PatientID'],
            'Test'      => ['required', 'string', 'max:255'],
            'TestType'  => ['required', 'string', 'max:100'],
        ]);

        $tech = $this->getTechnician();

        // 2. Generate ID
        $id = $this->generateLabTestId();

        // 3. Create Record using correct request inputs
        LabTest::create([
            'InvestigationID' => $id,
            'PatientID'       => $request->PatientID,
            'StaffID'         => $tech->StaffID,
            'Test'            => $request->Test,        // Matches form name="Test"
            'TestType'        => $request->TestType,    // Matches form name="TestType"
            'Result_Summary'  => null,
            'DigitalReport'   => null
        ]);

        return redirect()->route('lab.tests')->with('success', 'Investigation created successfully.');
    }

    private function generateLabTestId()
    {
        do {
            $id = 'LT-' . strtoupper(Str::random(8));
        } while (LabTest::where('InvestigationID', $id)->exists());

        return $id;
    }
}
