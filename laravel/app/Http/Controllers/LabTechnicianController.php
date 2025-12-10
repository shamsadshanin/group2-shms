<?php

namespace App\Http\Controllers;

use App\Models\LabTechnician;
use App\Models\LabTest;
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

    public function dashboard()
    {
        $labTechnician = Auth::user()->labTechnician;

        // Pending investigations
        $pendingTests = LabTest::where('cLabTechnicianID', $labTechnician->cLabTechnicianID)
            ->where('cStatus', 'Pending')
            ->orderBy('dTestDate', 'asc')
            ->get();

        // In-progress tests
        $inProgressTests = LabTest::where('cLabTechnicianID', $labTechnician->cLabTechnicianID)
            ->where('cStatus', 'In Progress')
            ->orderBy('dTestDate', 'asc')
            ->get();

        // Completed tests
        $completedTests = LabTest::where('cLabTechnicianID', $labTechnician->cLabTechnicianID)
            ->where('cStatus', 'Completed')
            ->orderBy('dTestDate', 'desc')
            ->take(10)
            ->get();

        return view('lab.dashboard', compact(
            'labTechnician',
            'pendingTests',
            'inProgressTests',
            'completedTests'
        ));
    }

    public function tests()
    {
        $labTechnician = Auth::user()->labTechnician;
        $tests = LabTest::where('cLabTechnicianID', $labTechnician->cLabTechnicianID)
            ->orderBy('dTestDate', 'desc')
            ->get();

        return view('lab.tests', compact('labTechnician', 'tests'));
    }

    public function show($cLabTestID)
    {
        $labTechnician = Auth::user()->labTechnician;
        $test = LabTest::where('cLabTestID', $cLabTestID)
            ->where('cLabTechnicianID', $labTechnician->cLabTechnicianID)
            ->firstOrFail();

        return view('lab.tests.show', compact('test'));
    }

    public function create()
    {
        $patients = Patient::all();
        return view('lab.tests.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cPatientID' => ['required', 'exists:tblpatient,cPatientID'],
            'cTestName' => ['required', 'string', 'max:255'],
        ]);

        $labTechnician = Auth::user()->labTechnician;

        LabTest::create([
            'cLabTestID' => $this->generateLabTestId(),
            'cPatientID' => $request->cPatientID,
            'cLabTechnicianID' => $labTechnician->cLabTechnicianID,
            'cTestName' => $request->cTestName,
            'dTestDate' => now(),
            'cStatus' => 'Pending',
        ]);

        return redirect()->route('lab.tests')->with('success', 'Lab test created successfully.');
    }

    private function generateLabTestId()
    {
        do {
            $id = 'LT-' . strtoupper(Str::random(8));
        } while (LabTest::where('cLabTestID', $id)->exists());

        return $id;
    }
}
