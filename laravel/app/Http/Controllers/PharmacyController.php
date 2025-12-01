<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\Supplier;
use App\Models\Dispensing;
use App\Http\Requests\MedicineRequest;
use App\Http\Requests\DispensingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PharmacyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('pharmacy'); // Custom middleware to ensure user has pharmacy role
    }

    public function dashboard(Request $request)
    {
        // Base query for pending prescriptions
        $prescriptionsQuery = Prescription::with(['appointment.patient', 'appointment.doctor', 'medicine'])
                             ->where('Status', 'Pending')
                             ->orderBy('created_at', 'asc');

        // Search prescriptions
        if ($request->has('search')) {
            $search = $request->get('search');
            $prescriptionsQuery->where(function($q) use ($search) {
                $q->whereHas('appointment.patient', function($q2) use ($search) {
                    $q2->where('Name', 'like', "%{$search}%");
                })->orWhere('MedicineName', 'like', "%{$search}%");
            });
        }

        $pendingPrescriptions = $prescriptionsQuery->paginate(15);

        // Inventory statistics
        $inventoryStats = [
            'total_medicines' => Medicine::active()->count(),
            'low_stock' => Medicine::lowStock()->count(),
            'out_of_stock' => Medicine::active()->where('StockQuantity', 0)->count(),
            'expiring_soon' => Medicine::expiringSoon()->count(),
        ];

        // Low stock alerts
        $lowStockMedicines = Medicine::lowStock()->with('category')->get();
        $expiringMedicines = Medicine::expiringSoon()->with('category')->get();

        // Recent dispensings
        $recentDispensings = Dispensing::with(['prescription', 'medicine'])
                              ->latest()
                              ->take(10)
                              ->get();

        return view('pharmacy.dashboard', compact(
            'pendingPrescriptions', 'inventoryStats',
            'lowStockMedicines', 'expiringMedicines', 'recentDispensings'
        ));
    }

    public function dispense(DispensingRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $prescription = Prescription::findOrFail($request->PrescriptionID);
                $medicine = $prescription->medicine;
                $quantityDispensed = $request->QuantityDispensed;

                // Create dispensing record
                $dispensing = Dispensing::create([
                    'PrescriptionID' => $prescription->PrescriptionID,
                    'MedicineID' => $medicine->MedicineID,
                    'QuantityDispensed' => $quantityDispensed,
                    'UnitPrice' => $medicine->UnitPrice,
                    'DispensedBy' => Auth::id(),
                    'DispensedAt' => now(),
                    'Notes' => $request->Notes,
                ]);

                // Update prescription status and quantities
                $prescription->QuantityDispensed += $quantityDispensed;

                if ($prescription->isFullyDispensed()) {
                    $prescription->Status = 'Dispensed';
                } else {
                    $prescription->Status = 'Partially_Dispensed';
                }

                $prescription->DispensedAt = now();
                $prescription->DispensedBy = Auth::id();
                $prescription->PharmacyNotes = $request->Notes;
                $prescription->save();

                // Update medicine stock
                $medicine->updateStock(-$quantityDispensed);
            });

            return redirect()->route('pharmacy.dashboard')
                           ->with('success', 'Medicine dispensed successfully.');

        } catch (\Exception $e) {
            \Log::error('Medicine dispensing failed: ' . $e->getMessage());

            return redirect()->back()
                           ->with('error', 'Failed to dispense medicine. Please try again.')
                           ->withInput();
        }
    }

    public function inventory(Request $request)
    {
        $query = Medicine::with(['category', 'supplier'])->active();

        // Apply filters
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('Name', 'like', "%{$search}%")
                  ->orWhere('GenericName', 'like', "%{$search}%")
                  ->orWhere('SKU', 'like', "%{$search}%");
            });
        }

        if ($request->has('category') && $request->category !== 'all') {
            $query->where('CategoryID', $request->category);
        }

        if ($request->has('stock_status') && $request->stock_status !== 'all') {
            switch ($request->stock_status) {
                case 'low':
                    $query->lowStock();
                    break;
                case 'out':
                    $query->where('StockQuantity', 0);
                    break;
                case 'expiring':
                    $query->expiringSoon();
                    break;
            }
        }

        $medicines = $query->orderBy('Name')->paginate(20);
        $categories = MedicineCategory::active()->get();

        return view('pharmacy.inventory', compact('medicines', 'categories'));
    }

    public function storeMedicine(MedicineRequest $request)
    {
        try {
            $medicine = Medicine::create($request->validated());

            return redirect()->route('pharmacy.inventory')
                           ->with('success', 'Medicine added to inventory successfully.');

        } catch (\Exception $e) {
            \Log::error('Medicine creation failed: ' . $e->getMessage());

            return redirect()->back()
                           ->with('error', 'Failed to add medicine. Please try again.')
                           ->withInput();
        }
    }

    public function updateMedicine(MedicineRequest $request, $id)
    {
        try {
            $medicine = Medicine::findOrFail($id);
            $medicine->update($request->validated());

            return redirect()->route('pharmacy.inventory')
                           ->with('success', 'Medicine updated successfully.');

        } catch (\Exception $e) {
            \Log::error('Medicine update failed: ' . $e->getMessage());

            return redirect()->back()
                           ->with('error', 'Failed to update medicine. Please try again.')
                           ->withInput();
        }
    }

    public function updateStock(Request $request, $id)
    {
        try {
            $request->validate([
                'stock_change' => 'required|integer',
                'reason' => 'required|string|max:255',
            ]);

            $medicine = Medicine::findOrFail($id);
            $medicine->updateStock($request->stock_change);

            // Here you could log the stock adjustment
            // StockAdjustment::create([...]);

            return redirect()->back()
                           ->with('success', 'Stock updated successfully.');

        } catch (\Exception $e) {
            \Log::error('Stock update failed: ' . $e->getMessage());

            return redirect()->back()
                           ->with('error', 'Failed to update stock. Please try again.');
        }
    }

    public function dispensingHistory(Request $request)
    {
        $query = Dispensing::with(['prescription.appointment.patient', 'medicine', 'dispenser']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('prescription.appointment.patient', function($q2) use ($search) {
                    $q2->where('Name', 'like', "%{$search}%");
                })->orWhereHas('medicine', function($q2) use ($search) {
                    $q2->where('Name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->has('date_from')) {
            $query->whereDate('DispensedAt', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('DispensedAt', '<=', $request->date_to);
        }

        $dispensings = $query->latest()->paginate(25);

        return view('pharmacy.history', compact('dispensings'));
    }

    public function getPrescriptionDetails($id)
    {
        try {
            $prescription = Prescription::with([
                'appointment.patient',
                'appointment.doctor',
                'medicine'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'prescription' => $prescription,
                'patient' => $prescription->appointment->patient,
                'doctor' => $prescription->appointment->doctor,
                'medicine' => $prescription->medicine,
                'remaining_quantity' => $prescription->getRemainingQuantity(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Prescription not found.'
            ], 404);
        }
    }

// Add these methods to your PharmacyController

public function reports(Request $request)
{
    $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
    $dateTo = $request->get('date_to', now()->format('Y-m-d'));
    $reportType = $request->get('report_type', 'sales');

    // Quick Statistics
    $quickStats = $this->getQuickStats($dateFrom, $dateTo);

    // Initialize variables for different reports
    $salesData = [];
    $topSellingMedicines = [];
    $detailedSales = [];
    $categoryStock = [];
    $expiringMedicines = [];
    $doctorPrescriptions = [];
    $performanceMetrics = [];
    $pharmacistPerformance = [];
    $chartData = [];

    switch ($reportType) {
        case 'sales':
            $salesData = $this->getSalesData($dateFrom, $dateTo, $request->group_by);
            $topSellingMedicines = $this->getTopSellingMedicines($dateFrom, $dateTo);
            $detailedSales = $this->getDetailedSales($dateFrom, $dateTo);
            $chartData['sales'] = $salesData;
            break;

        case 'inventory':
            $categoryStock = $this->getCategoryStockReport();
            $chartData['inventory'] = $this->getInventoryChartData();
            break;

        case 'expiry':
            $expiringMedicines = $this->getExpiringMedicines();
            $chartData['expiry'] = $this->getExpiryChartData();
            break;

        case 'prescription':
            $doctorPrescriptions = $this->getDoctorPrescriptionAnalysis($dateFrom, $dateTo);
            $chartData['prescriptions'] = $this->getPrescriptionChartData($dateFrom, $dateTo, $request->group_by);
            break;

        case 'performance':
            $performanceMetrics = $this->getPerformanceMetrics($dateFrom, $dateTo);
            $pharmacistPerformance = $this->getPharmacistPerformance($dateFrom, $dateTo);
            break;
    }

    return view('pharmacy.reports', compact(
        'quickStats', 'salesData', 'topSellingMedicines', 'detailedSales',
        'categoryStock', 'expiringMedicines', 'doctorPrescriptions',
        'performanceMetrics', 'pharmacistPerformance', 'chartData',
        'dateFrom', 'dateTo', 'reportType'
    ));
}

    public function reports(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        $reportType = $request->get('report_type', 'sales');

        // Quick Statistics
        $quickStats = $this->getQuickStats($dateFrom, $dateTo);

        // Initialize variables for different reports
        $salesData = [];
        $topSellingMedicines = [];
        $detailedSales = [];
        $categoryStock = [];
        $expiringMedicines = [];
        $doctorPrescriptions = [];
        $performanceMetrics = [];
        $pharmacistPerformance = [];
        $chartData = [];

        switch ($reportType) {
            case 'sales':
                $salesData = $this->getSalesData($dateFrom, $dateTo, $request->group_by);
                $topSellingMedicines = $this->getTopSellingMedicines($dateFrom, $dateTo);
                $detailedSales = $this->getDetailedSales($dateFrom, $dateTo);
                $chartData['sales'] = $salesData;
                break;

            case 'inventory':
                $categoryStock = $this->getCategoryStockReport();
                $chartData['inventory'] = $this->getInventoryChartData();
                break;

            case 'expiry':
                $expiringMedicines = $this->getExpiringMedicines();
                $chartData['expiry'] = $this->getExpiryChartData();
                break;

            case 'prescription':
                $doctorPrescriptions = $this->getDoctorPrescriptionAnalysis($dateFrom, $dateTo);
                $chartData['prescriptions'] = $this->getPrescriptionChartData($dateFrom, $dateTo, $request->group_by);
                break;

            case 'performance':
                $performanceMetrics = $this->getPerformanceMetrics($dateFrom, $dateTo);
                $pharmacistPerformance = $this->getPharmacistPerformance($dateFrom, $dateTo);
                break;
        }

        return view('pharmacy.reports', compact(
            'quickStats', 'salesData', 'topSellingMedicines', 'detailedSales',
            'categoryStock', 'expiringMedicines', 'doctorPrescriptions',
            'performanceMetrics', 'pharmacistPerformance', 'chartData',
            'dateFrom', 'dateTo', 'reportType'
        ));
    }

    private function getQuickStats($dateFrom, $dateTo)
    {
        // Total sales in period
        $totalSales = Dispensing::whereBetween('created_at', [$dateFrom, $dateTo])
                      ->sum('TotalAmount');

        // Total prescriptions dispensed
        $totalPrescriptions = Prescription::whereBetween('DispensedAt', [$dateFrom, $dateTo])
                            ->where('Status', 'Dispensed')
                            ->count();

        // Low stock items
        $lowStockItems = Medicine::lowStock()->count();

        // Expiring soon (within 30 days)
        $expiringSoon = Medicine::expiringSoon()->count();

        // Sales trend compared to previous period
        $previousPeriodSales = Dispensing::whereBetween('created_at',
            [now()->subDays(30)->format('Y-m-d'), now()->format('Y-m-d')])
            ->sum('TotalAmount');

        $salesTrend = $previousPeriodSales > 0 ?
                      (($totalSales - $previousPeriodSales) / $previousPeriodSales) * 100 : 0;

        // Prescription trend
        $previousPrescriptions = Prescription::whereBetween('DispensedAt',
            [now()->subDays(30)->format('Y-m-d'), now()->format('Y-m-d')])
            ->where('Status', 'Dispensed')
            ->count();

        $prescriptionTrend = $previousPrescriptions > 0 ?
                            (($totalPrescriptions - $previousPrescriptions) / $previousPrescriptions) * 100 : 0;

        return [
            'total_sales' => $totalSales,
            'total_prescriptions' => $totalPrescriptions,
            'low_stock_items' => $lowStockItems,
            'expiring_soon' => $expiringSoon,
            'sales_trend' => round($salesTrend, 2),
            'prescription_trend' => round($prescriptionTrend, 2),
            'low_stock_change' => 0 // You can calculate this based on previous period
        ];
    }
}
