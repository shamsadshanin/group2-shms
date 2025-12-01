@extends('layouts.app')

@section('title', 'Pharmacy Reports')

@section('styles')
<style>
    .report-card { transition: transform 0.2s; }
    .report-card:hover { transform: translateY(-5px); }
    .chart-container { position: relative; height: 300px; width: 100%; }
    .sales-chart { height: 400px; }
    .export-btn { min-width: 120px; }
    .date-range { background: #f8f9fa; border-radius: 5px; padding: 10px; }
    .stat-number { font-size: 2rem; font-weight: bold; }
    .stat-label { font-size: 0.9rem; color: #6c757d; }
    .table-report { font-size: 0.9rem; }
    .table-report th { background-color: #f8f9fa; }
    .trend-up { color: #28a745; }
    .trend-down { color: #dc3545; }
    .trend-neutral { color: #6c757d; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar"></i> Pharmacy Reports & Analytics
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Date Range Filter -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="date-range">
                                <form method="GET" action="{{ route('pharmacy.reports') }}" id="reportFilterForm">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-3">
                                            <label class="form-label">Report Type</label>
                                            <select class="form-select" name="report_type" id="reportType">
                                                <option value="sales" {{ request('report_type') == 'sales' ? 'selected' : '' }}>Sales Report</option>
                                                <option value="inventory" {{ request('report_type') == 'inventory' ? 'selected' : '' }}>Inventory Report</option>
                                                <option value="expiry" {{ request('report_type') == 'expiry' ? 'selected' : '' }}>Expiry Report</option>
                                                <option value="prescription" {{ request('report_type') == 'prescription' ? 'selected' : '' }}>Prescription Report</option>
                                                <option value="performance" {{ request('report_type') == 'performance' ? 'selected' : '' }}>Performance Report</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Date From</label>
                                            <input type="date" class="form-control" name="date_from"
                                                   value="{{ request('date_from', date('Y-m-01')) }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Date To</label>
                                            <input type="date" class="form-control" name="date_to"
                                                   value="{{ request('date_to', date('Y-m-d')) }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Group By</label>
                                            <select class="form-select" name="group_by">
                                                <option value="daily" {{ request('group_by') == 'daily' ? 'selected' : '' }}>Daily</option>
                                                <option value="weekly" {{ request('group_by') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                                <option value="monthly" {{ request('group_by') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                                <option value="yearly" {{ request('group_by') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-filter"></i> Generate Report
                                            </button>
                                            <button type="button" class="btn btn-success" onclick="exportReport()">
                                                <i class="fas fa-file-export"></i> Export
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="row mb-4" id="quickStats">
                        <div class="col-md-3">
                            <div class="card report-card border-primary">
                                <div class="card-body text-center">
                                    <div class="stat-number text-primary">৳{{ number_format($quickStats['total_sales'], 2) }}</div>
                                    <div class="stat-label">Total Sales</div>
                                    <div class="mt-2">
                                        <small class="{{ $quickStats['sales_trend'] > 0 ? 'trend-up' : ($quickStats['sales_trend'] < 0 ? 'trend-down' : 'trend-neutral') }}">
                                            <i class="fas fa-arrow-{{ $quickStats['sales_trend'] > 0 ? 'up' : ($quickStats['sales_trend'] < 0 ? 'down' : 'right') }}"></i>
                                            {{ abs($quickStats['sales_trend']) }}%
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card report-card border-success">
                                <div class="card-body text-center">
                                    <div class="stat-number text-success">{{ $quickStats['total_prescriptions'] }}</div>
                                    <div class="stat-label">Prescriptions Dispensed</div>
                                    <div class="mt-2">
                                        <small class="{{ $quickStats['prescription_trend'] > 0 ? 'trend-up' : ($quickStats['prescription_trend'] < 0 ? 'trend-down' : 'trend-neutral') }}">
                                            <i class="fas fa-arrow-{{ $quickStats['prescription_trend'] > 0 ? 'up' : ($quickStats['prescription_trend'] < 0 ? 'down' : 'right') }}"></i>
                                            {{ abs($quickStats['prescription_trend']) }}%
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card report-card border-warning">
                                <div class="card-body text-center">
                                    <div class="stat-number text-warning">{{ $quickStats['low_stock_items'] }}</div>
                                    <div class="stat-label">Low Stock Items</div>
                                    <div class="mt-2">
                                        @if($quickStats['low_stock_change'] > 0)
                                            <small class="trend-up">
                                                <i class="fas fa-arrow-up"></i> {{ $quickStats['low_stock_change'] }} more
                                            </small>
                                        @elseif($quickStats['low_stock_change'] < 0)
                                            <small class="trend-down">
                                                <i class="fas fa-arrow-down"></i> {{ abs($quickStats['low_stock_change']) }} less
                                            </small>
                                        @else
                                            <small class="trend-neutral">No change</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card report-card border-info">
                                <div class="card-body text-center">
                                    <div class="stat-number text-info">{{ $quickStats['expiring_soon'] }}</div>
                                    <div class="stat-label">Expiring Soon (30 days)</div>
                                    <div class="mt-2">
                                        <small class="trend-down">
                                            <i class="fas fa-clock"></i> Monitor
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Report Section -->
                    @if(request('report_type', 'sales') == 'sales')
                    <div class="row mb-4" id="salesReport">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Sales Trend</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container sales-chart">
                                        <canvas id="salesChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Top Selling Medicines</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-report">
                                            <thead>
                                                <tr>
                                                    <th>Medicine</th>
                                                    <th>Units Sold</th>
                                                    <th>Revenue</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($topSellingMedicines as $medicine)
                                                <tr>
                                                    <td>
                                                        <small>{{ $medicine->Name }}</small><br>
                                                        <small class="text-muted">{{ $medicine->SKU }}</small>
                                                    </td>
                                                    <td class="text-end">{{ $medicine->total_units }}</td>
                                                    <td class="text-end">৳{{ number_format($medicine->total_revenue, 2) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Sales Table -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Detailed Sales Report</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-report" id="salesTable">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Prescription #</th>
                                                    <th>Patient</th>
                                                    <th>Medicine</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Price</th>
                                                    <th>Total Amount</th>
                                                    <th>Dispensed By</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($detailedSales as $sale)
                                                <tr>
                                                    <td>{{ $sale->DispensedAt->format('M d, Y H:i') }}</td>
                                                    <td>#{{ $sale->PrescriptionID }}</td>
                                                    <td>{{ $sale->prescription->patient->Name ?? 'N/A' }}</td>
                                                    <td>{{ $sale->medicine->Name }}</td>
                                                    <td class="text-center">{{ $sale->QuantityDispensed }}</td>
                                                    <td class="text-end">৳{{ number_format($sale->UnitPrice, 2) }}</td>
                                                    <td class="text-end">৳{{ number_format($sale->TotalAmount, 2) }}</td>
                                                    <td>{{ $sale->dispenser->name ?? 'N/A' }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="table-info">
                                                    <td colspan="4" class="text-end"><strong>Total</strong></td>
                                                    <td class="text-center"><strong>{{ $detailedSales->sum('QuantityDispensed') }}</strong></td>
                                                    <td colspan="2" class="text-end">
                                                        <strong>৳{{ number_format($detailedSales->sum('TotalAmount'), 2) }}</strong>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Inventory Report Section -->
                    @if(request('report_type') == 'inventory')
                    <div class="row mb-4" id="inventoryReport">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Inventory Value by Category</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="inventoryChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Status Table -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Stock Status Report</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-report">
                                            <thead>
                                                <tr>
                                                    <th>Category</th>
                                                    <th>Total Items</th>
                                                    <th>In Stock</th>
                                                    <th>Low Stock</th>
                                                    <th>Out of Stock</th>
                                                    <th>Total Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($categoryStock as $category)
                                                <tr>
                                                    <td>{{ $category->CategoryName }}</td>
                                                    <td class="text-center">{{ $category->total_items }}</td>
                                                    <td class="text-center">
                                                        <span class="badge bg-success">{{ $category->in_stock }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-warning">{{ $category->low_stock }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-danger">{{ $category->out_of_stock }}</span>
                                                    </td>
                                                    <td class="text-end">৳{{ number_format($category->total_value, 2) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="table-info">
                                                    <td><strong>Total</strong></td>
                                                    <td class="text-center"><strong>{{ $categoryStock->sum('total_items') }}</strong></td>
                                                    <td class="text-center"><strong>{{ $categoryStock->sum('in_stock') }}</strong></td>
                                                    <td class="text-center"><strong>{{ $categoryStock->sum('low_stock') }}</strong></td>
                                                    <td class="text-center"><strong>{{ $categoryStock->sum('out_of_stock') }}</strong></td>
                                                    <td class="text-end"><strong>৳{{ number_format($categoryStock->sum('total_value'), 2) }}</strong></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Expiry Report Section -->
                    @if(request('report_type') == 'expiry')
                    <div class="row mb-4" id="expiryReport">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Medicine Expiry Timeline</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="expiryChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Expiring Medicines Table -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Expiring Medicines Report</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-report">
                                            <thead>
                                                <tr>
                                                    <th>Medicine</th>
                                                    <th>Category</th>
                                                    <th>Current Stock</th>
                                                    <th>Expiry Date</th>
                                                    <th>Days Left</th>
                                                    <th>Stock Value</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($expiringMedicines as $medicine)
                                                <tr class="{{ $medicine->days_left <= 30 ? 'table-warning' : '' }}
                                                            {{ $medicine->days_left <= 7 ? 'table-danger' : '' }}">
                                                    <td>
                                                        <strong>{{ $medicine->Name }}</strong><br>
                                                        <small class="text-muted">{{ $medicine->SKU }}</small>
                                                    </td>
                                                    <td>{{ $medicine->category->CategoryName ?? 'N/A' }}</td>
                                                    <td class="text-center">{{ $medicine->StockQuantity }}</td>
                                                    <td>{{ $medicine->ExpiryDate->format('M d, Y') }}</td>
                                                    <td class="{{ $medicine->days_left <= 30 ? 'text-warning' : '' }}
                                                                {{ $medicine->days_left <= 7 ? 'text-danger' : '' }}">
                                                        <strong>{{ $medicine->days_left }} days</strong>
                                                    </td>
                                                    <td class="text-end">৳{{ number_format($medicine->StockQuantity * $medicine->CostPrice, 2) }}</td>
                                                    <td>
                                                        @if($medicine->days_left <= 30)
                                                        <button class="btn btn-sm btn-warning">
                                                            <i class="fas fa-exclamation-triangle"></i> Alert
                                                        </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Prescription Report Section -->
                    @if(request('report_type') == 'prescription')
                    <div class="row mb-4" id="prescriptionReport">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Prescription Trends</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="prescriptionChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Doctor-wise Prescription -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Doctor-wise Prescription Analysis</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-report">
                                            <thead>
                                                <tr>
                                                    <th>Doctor</th>
                                                    <th>Specialization</th>
                                                    <th>Total Prescriptions</th>
                                                    <th>Total Revenue</th>
                                                    <th>Avg. Prescription Value</th>
                                                    <th>Top Medicine</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($doctorPrescriptions as $doctor)
                                                <tr>
                                                    <td>Dr. {{ $doctor->Name }}</td>
                                                    <td>{{ $doctor->Specialization }}</td>
                                                    <td class="text-center">{{ $doctor->total_prescriptions }}</td>
                                                    <td class="text-end">৳{{ number_format($doctor->total_revenue, 2) }}</td>
                                                    <td class="text-end">৳{{ number_format($doctor->avg_value, 2) }}</td>
                                                    <td>{{ $doctor->top_medicine ?? 'N/A' }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Performance Report Section -->
                    @if(request('report_type') == 'performance')
                    <div class="row mb-4" id="performanceReport">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Pharmacy Performance Metrics</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6>Dispensing Efficiency</h6>
                                                    <div class="progress" style="height: 25px;">
                                                        <div class="progress-bar bg-success" role="progressbar"
                                                             style="width: {{ $performanceMetrics['efficiency'] }}%">
                                                            {{ number_format($performanceMetrics['efficiency'], 1) }}%
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">
                                                        Average time to dispense: {{ $performanceMetrics['avg_dispense_time'] }} minutes
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6>Stock Turnover Ratio</h6>
                                                    <div class="text-center">
                                                        <h2 class="{{ $performanceMetrics['turnover_ratio'] >= 4 ? 'text-success' : 'text-warning' }}">
                                                            {{ number_format($performanceMetrics['turnover_ratio'], 2) }}x
                                                        </h2>
                                                        <small class="text-muted">Times inventory sold and replaced</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pharmacist Performance -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Pharmacist Performance</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-report">
                                            <thead>
                                                <tr>
                                                    <th>Pharmacist</th>
                                                    <th>Total Dispensed</th>
                                                    <th>Total Revenue</th>
                                                    <th>Avg. Daily Dispensing</th>
                                                    <th>Accuracy Rate</th>
                                                    <th>Customer Rating</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($pharmacistPerformance as $pharmacist)
                                                <tr>
                                                    <td>{{ $pharmacist->name }}</td>
                                                    <td class="text-center">{{ $pharmacist->total_dispensed }}</td>
                                                    <td class="text-end">৳{{ number_format($pharmacist->total_revenue, 2) }}</td>
                                                    <td class="text-center">{{ $pharmacist->avg_daily }}</td>
                                                    <td>
                                                        <div class="progress" style="height: 10px;">
                                                            <div class="progress-bar bg-{{ $pharmacist->accuracy >= 90 ? 'success' : ($pharmacist->accuracy >= 80 ? 'warning' : 'danger') }}"
                                                                 style="width: {{ $pharmacist->accuracy }}%">
                                                            </div>
                                                        </div>
                                                        <small>{{ number_format($pharmacist->accuracy, 1) }}%</small>
                                                    </td>
                                                    <td>
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="fas fa-star {{ $i <= $pharmacist->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                        @endfor
                                                        <small>({{ number_format($pharmacist->rating, 1) }})</small>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Export Buttons -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-outline-primary export-btn me-2" onclick="exportPDF()">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </button>
                                <button type="button" class="btn btn-outline-success export-btn me-2" onclick="exportExcel()">
                                    <i class="fas fa-file-excel"></i> Excel
                                </button>
                                <button type="button" class="btn btn-outline-secondary export-btn" onclick="printReport()">
                                    <i class="fas fa-print"></i> Print
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Initialize charts based on report type
        let reportType = '{{ request("report_type", "sales") }}';

        if (reportType === 'sales' && $('#salesChart').length) {
            initSalesChart();
        }

        if (reportType === 'inventory' && $('#inventoryChart').length) {
            initInventoryChart();
        }

        if (reportType === 'expiry' && $('#expiryChart').length) {
            initExpiryChart();
        }

        if (reportType === 'prescription' && $('#prescriptionChart').length) {
            initPrescriptionChart();
        }

        // Auto-refresh report every 30 minutes
        setTimeout(function() {
            $('#reportFilterForm').submit();
        }, 1800000);

        // Initialize DataTable for sales table
        if ($('#salesTable').length) {
            $('#salesTable').DataTable({
                pageLength: 25,
                order: [[0, 'desc']],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        }
    });

    function initSalesChart() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesData = @json($chartData['sales'] ?? []);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: salesData.labels || [],
                datasets: [{
                    label: 'Sales (BDT)',
                    data: salesData.data || [],
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '৳' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '৳' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    function initInventoryChart() {
        const ctx = document.getElementById('inventoryChart').getContext('2d');
        const inventoryData = @json($chartData['inventory'] ?? []);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: inventoryData.labels || [],
                datasets: [{
                    label: 'Inventory Value (BDT)',
                    data: inventoryData.data || [],
                    backgroundColor: [
                        '#28a745', '#007bff', '#6c757d', '#17a2b8',
                        '#ffc107', '#dc3545', '#e83e8c', '#6f42c1'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '৳' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    function initExpiryChart() {
        const ctx = document.getElementById('expiryChart').getContext('2d');
        const expiryData = @json($chartData['expiry'] ?? []);

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: expiryData.labels || [],
                datasets: [{
                    data: expiryData.data || [],
                    backgroundColor: [
                        '#28a745', // > 90 days
                        '#ffc107', // 31-90 days
                        '#fd7e14', // 8-30 days
                        '#dc3545'  // < 7 days
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });
    }

    function initPrescriptionChart() {
        const ctx = document.getElementById('prescriptionChart').getContext('2d');
        const prescriptionData = @json($chartData['prescriptions'] ?? []);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: prescriptionData.labels || [],
                datasets: [{
                    label: 'Prescriptions',
                    data: prescriptionData.data || [],
                    borderColor: '#6f42c1',
                    backgroundColor: 'rgba(111, 66, 193, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true
                    }
                }
            }
        });
    }

    function exportReport() {
        const reportType = $('#reportType').val();
        const dateFrom = $('input[name="date_from"]').val();
        const dateTo = $('input[name="date_to"]').val();

        window.open(`/pharmacy/reports/export?report_type=${reportType}&date_from=${dateFrom}&date_to=${dateTo}`, '_blank');
    }

    function exportPDF() {
        // Implement PDF export logic
        alert('PDF export feature will be implemented');
    }

    function exportExcel() {
        // Implement Excel export logic
        alert('Excel export feature will be implemented');
    }

    function printReport() {
        window.print();
    }

    // Update report when type changes
    $('#reportType').change(function() {
        $('#reportFilterForm').submit();
    });
</script>
@endsection
