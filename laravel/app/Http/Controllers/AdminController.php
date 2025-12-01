<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointment;
use App\Models\Billing;
use App\Models\AuditLog;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\LabTechnician;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin'); // Custom middleware to ensure user is an admin
    }

    public function dashboard()
    {
        // 1. Key Metrics
        $totalPatients = Patient::count();
        $totalDoctors = Doctor::where('IsActive', true)->count();
        $totalLabTechnicians = LabTechnician::where('IsActive', true)->count();
        $totalAppointments = Appointment::count();

        // 2. Revenue Metrics
        $totalRevenue = Billing::paid()->sum('FinalAmount');
        $monthlyRevenue = Billing::paid()
                        ->whereYear('IssueDate', date('Y'))
                        ->whereMonth('IssueDate', date('m'))
                        ->sum('FinalAmount');
        $pendingPayments = Billing::pending()->sum('FinalAmount');
        $overduePayments = Billing::overdue()->sum('FinalAmount');

        // 3. Data for Charts
        // Monthly appointments chart
        $appointmentsChart = Appointment::select(
                DB::raw("COUNT(*) as count"),
                DB::raw("MONTHNAME(Date) as month_name")
            )
            ->whereYear('Date', date('Y'))
            ->groupBy(DB::raw("MONTHNAME(Date)"))
            ->orderBy(DB::raw("MIN(Date)"))
            ->pluck('count', 'month_name');

        // Revenue chart (last 6 months)
        $revenueChart = Billing::paid()
            ->select(
                DB::raw("SUM(FinalAmount) as revenue"),
                DB::raw("DATE_FORMAT(IssueDate, '%Y-%m') as month_year")
            )
            ->where('IssueDate', '>=', now()->subMonths(6))
            ->groupBy('month_year')
            ->orderBy('month_year')
            ->pluck('revenue', 'month_year');

        // User registration chart (last 12 months)
        $userRegistrations = User::select(
                DB::raw("COUNT(*) as count"),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month_year")
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month_year')
            ->orderBy('month_year')
            ->pluck('count', 'month_year');

        // 4. Recent Activity
        $recentUsers = User::with(['patient', 'doctor', 'labTechnician'])
                      ->latest()
                      ->take(8)
                      ->get();

        $recentActivity = AuditLog::with('user')
                          ->latest()
                          ->take(10)
                          ->get();

        // 5. System Alerts
        $systemAlerts = [
            'overdue_bills' => Billing::overdue()->count(),
            'pending_appointments' => Appointment::where('Status', 'Pending')->count(),
            'inactive_users' => User::where('is_active', false)->count(),
        ];

        return view('admin.dashboard', compact(
            'totalPatients', 'totalDoctors', 'totalLabTechnicians', 'totalAppointments',
            'totalRevenue', 'monthlyRevenue', 'pendingPayments', 'overduePayments',
            'appointmentsChart', 'revenueChart', 'userRegistrations',
            'recentUsers', 'recentActivity', 'systemAlerts'
        ));
    }

    public function users(Request $request)
    {
        $query = User::with(['patient', 'doctor', 'labTechnician']);

        // Apply filters
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->latest()->paginate(20);

        $userStats = [
            'total' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'doctors' => User::where('role', 'doctor')->count(),
            'patients' => User::where('role', 'patient')->count(),
            'lab_technicians' => User::where('role', 'lab_technician')->count(),
        ];

        return view('admin.users', compact('users', 'userStats'));
    }

    public function updateUser(UserUpdateRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $oldValues = $user->toArray();
            $user->update($request->validated());
            $newValues = $user->toArray();

            // Log the user update
            AuditLog::logAction(
                auth()->user(),
                'user.updated',
                "Updated user: {$user->name}",
                $oldValues,
                $newValues
            );

            return redirect()->route('admin.users')
                           ->with('success', 'User updated successfully.');

        } catch (\Exception $e) {
            \Log::error('User update failed: ' . $e->getMessage());

            return redirect()->back()
                           ->with('error', 'Failed to update user. Please try again.')
                           ->withInput();
        }
    }

    public function destroyUser($id)
    {
        try {
            $user = User::findOrFail($id);

            // Prevent deleting self
            if (auth()->id() == $user->id) {
                return redirect()->back()
                               ->with('error', 'You cannot delete your own account.');
            }

            $userName = $user->name;
            $user->delete();

            // Log the user deletion
            AuditLog::logAction(
                auth()->user(),
                'user.deleted',
                "Deleted user: {$userName}"
            );

            return redirect()->route('admin.users')
                           ->with('success', 'User deleted successfully.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()
                           ->with('error', 'User not found.');
        } catch (\Exception $e) {
            \Log::error('User deletion failed: ' . $e->getMessage());

            return redirect()->back()
                           ->with('error', 'Failed to delete user. Please try again.');
        }
    }

    public function financials(Request $request)
    {
        $query = Billing::with(['patient', 'appointment']);

        // Apply filters
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('InvoiceNumber', 'like', "%{$search}%")
                  ->orWhereHas('patient', function($q2) use ($search) {
                      $q2->where('Name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('PaymentStatus', $request->status);
        }

        if ($request->has('date_from')) {
            $query->where('IssueDate', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('IssueDate', '<=', $request->date_to);
        }

        $billings = $query->latest()->paginate(25);

        $financialStats = [
            'total_revenue' => Billing::paid()->sum('FinalAmount'),
            'pending_revenue' => Billing::pending()->sum('FinalAmount'),
            'overdue_revenue' => Billing::overdue()->sum('FinalAmount'),
            'monthly_revenue' => Billing::paid()
                                ->whereYear('IssueDate', date('Y'))
                                ->whereMonth('IssueDate', date('m'))
                                ->sum('FinalAmount'),
        ];

        return view('admin.financials', compact('billings', 'financialStats'));
    }

    public function generateReport(Request $request)
    {
        $validated = $request->validate([
            'report_type' => 'required|in:financial,users,appointments,audit',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:pdf,csv,excel',
        ]);

        try {
            $startDate = Carbon::parse($validated['start_date']);
            $endDate = Carbon::parse($validated['end_date']);

            // Generate report based on type
            switch ($validated['report_type']) {
                case 'financial':
                    $data = $this->generateFinancialReport($startDate, $endDate);
                    $fileName = "financial-report-{$startDate->format('Y-m-d')}-to-{$endDate->format('Y-m-d')}";
                    break;

                case 'users':
                    $data = $this->generateUserReport($startDate, $endDate);
                    $fileName = "user-report-{$startDate->format('Y-m-d')}-to-{$endDate->format('Y-m-d')}";
                    break;

                case 'appointments':
                    $data = $this->generateAppointmentReport($startDate, $endDate);
                    $fileName = "appointment-report-{$startDate->format('Y-m-d')}-to-{$endDate->format('Y-m-d')}";
                    break;

                case 'audit':
                    $data = $this->generateAuditReport($startDate, $endDate);
                    $fileName = "audit-report-{$startDate->format('Y-m-d')}-to-{$endDate->format('Y-m-d')}";
                    break;
            }

            // Log report generation
            AuditLog::logAction(
                auth()->user(),
                'report.generated',
                "Generated {$validated['report_type']} report from {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')}"
            );

            // Here you would implement the actual export logic
            // For now, we'll return a success message
            return redirect()->back()
                           ->with('success', "Report generated successfully. Format: {$validated['format']}");

        } catch (\Exception $e) {
            \Log::error('Report generation failed: ' . $e->getMessage());

            return redirect()->back()
                           ->with('error', 'Failed to generate report. Please try again.');
        }
    }

    private function generateFinancialReport($startDate, $endDate)
    {
        return Billing::with(['patient', 'appointment'])
                ->whereBetween('IssueDate', [$startDate, $endDate])
                ->get();
    }

    private function generateUserReport($startDate, $endDate)
    {
        return User::with(['patient', 'doctor', 'labTechnician'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();
    }

    private function generateAppointmentReport($startDate, $endDate)
    {
        return Appointment::with(['patient', 'doctor'])
                ->whereBetween('Date', [$startDate, $endDate])
                ->get();
    }

    private function generateAuditReport($startDate, $endDate)
    {
        return AuditLog::with('user')
                ->whereBetween('performed_at', [$startDate, $endDate])
                ->get();
    }
}
