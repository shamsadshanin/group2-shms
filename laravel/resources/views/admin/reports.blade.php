@extends('layouts.app')

@section('title', 'Reports & Analytics')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Reports & Analytics</h1>
            <p class="mb-0">Generate comprehensive reports and analyze system data</p>
        </div>
        <div>
            <button class="btn btn-primary" data-toggle="modal" data-target="#generateReportModal">
                <i class="fas fa-file-alt fa-sm"></i> Generate Report
            </button>
            <button class="btn btn-success" onclick="refreshAnalytics()">
                <i class="fas fa-sync-alt fa-sm"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Report Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Reports Generated</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $reportStats['total_reports'] }}</div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span>Last 30 days: {{ $reportStats['recent_reports'] }}</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                System Audit Logs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $reportStats['audit_logs'] }}</div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span>Today: {{ $reportStats['today_logs'] }}</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Data Export Volume</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $reportStats['export_volume'] }} MB</div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span>Average: {{ $reportStats['avg_export'] }} MB per report</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-database fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Report Success Rate</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $reportStats['success_rate'] }}%</div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span>Failed: {{ $reportStats['failed_reports'] }} reports</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Reports Section -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Reports</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="card h-100 border-left-primary">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-2x text-primary mb-3"></i>
                                    <h5 class="card-title">User Summary</h5>
                                    <p class="card-text small">Overview of user registrations and activity</p>
                                    <button class="btn btn-sm btn-primary" onclick="generateQuickReport('user')">
                                        Generate
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card h-100 border-left-success">
                                <div class="card-body text-center">
                                    <i class="fas fa-money-bill-wave fa-2x text-success mb-3"></i>
                                    <h5 class="card-title">Financial Summary</h5>
                                    <p class="card-text small">Revenue, payments, and financial metrics</p>
                                    <button class="btn btn-sm btn-success" onclick="generateQuickReport('financial')">
                                        Generate
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card h-100 border-left-info">
                                <div class="card-body text-center">
                                    <i class="fas fa-calendar-check fa-2x text-info mb-3"></i>
                                    <h5 class="card-title">Appointment Summary</h5>
                                    <p class="card-text small">Appointment statistics and trends</p>
                                    <button class="btn btn-sm btn-info" onclick="generateQuickReport('appointment')">
                                        Generate
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card h-100 border-left-warning">
                                <div class="card-body text-center">
                                    <i class="fas fa-clipboard-list fa-2x text-warning mb-3"></i>
                                    <h5 class="card-title">Audit Trail</h5>
                                    <p class="card-text small">System activity and user actions log</p>
                                    <button class="btn btn-sm btn-warning" onclick="generateQuickReport('audit')">
                                        Generate
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Dashboard -->
    <div class="row">
        <!-- User Registration Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">User Registration Trend (Last 12 Months)</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                            <a class="dropdown-item" href="#" onclick="exportUserChart()">Export Chart</a>
                            <a class="dropdown-item" href="#" onclick="toggleDataLabels()">Toggle Labels</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="userRegistrationChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> Total Registrations
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Active Users
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-info"></i> New This Month
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Type Distribution -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Report Type Distribution</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4">
                        <canvas id="reportTypeChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> Financial
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> User
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-info"></i> Appointment
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-warning"></i> Audit
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reports Table -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Reports</h6>
                    <button class="btn btn-sm btn-primary" onclick="refreshReports()">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="reportsTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Report ID</th>
                                    <th>Report Type</th>
                                    <th>Generated By</th>
                                    <th>Date Range</th>
                                    <th>Format</th>
                                    <th>Size</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentReports as $report)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold">{{ $report->report_id }}</div>
                                        <div class="small text-muted">
                                            {{ $report->created_at->format('M d, Y h:i A') }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $report->getTypeBadgeClass() }}">
                                            {{ ucfirst($report->report_type) }}
                                        </span>
                                        <div class="small mt-1">{{ $report->title }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="mr-2">
                                                <div class="avatar-circle-sm">
                                                    <span class="initials-sm">
                                                        {{ substr($report->generator->name, 0, 2) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div>
                                                <div>{{ $report->generator->name }}</div>
                                                <div class="small text-muted">{{ $report->generator->role }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div>
                                                <i class="fas fa-calendar-alt"></i>
                                                From: {{ $report->start_date->format('M d, Y') }}
                                            </div>
                                            <div>
                                                <i class="fas fa-calendar-check"></i>
                                                To: {{ $report->end_date->format('M d, Y') }}
                                            </div>
                                            <div class="text-muted">
                                                Duration: {{ $report->getDuration() }} days
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-light p-2">
                                            <i class="fas fa-file-{{ $report->getFormatIcon() }}"></i>
                                            {{ strtoupper($report->format) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">{{ $report->size }}</div>
                                        <div class="small text-muted">{{ $report->record_count }} records</div>
                                    </td>
                                    <td>
                                        @if($report->status === 'completed')
                                        <span class="badge badge-success p-2">
                                            <i class="fas fa-check-circle"></i> Completed
                                        </span>
                                        @elseif($report->status === 'processing')
                                        <span class="badge badge-warning p-2">
                                            <i class="fas fa-spinner fa-spin"></i> Processing
                                        </span>
                                        @elseif($report->status === 'failed')
                                        <span class="badge badge-danger p-2">
                                            <i class="fas fa-times-circle"></i> Failed
                                        </span>
                                        @endif
                                        @if($report->generated_at)
                                        <div class="small text-muted mt-1">
                                            {{ $report->generated_at->diffForHumans() }}
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @if($report->status === 'completed')
                                            <a href="{{ $report->download_url }}"
                                               class="btn btn-sm btn-primary"
                                               target="_blank">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-sm btn-info"
                                                    onclick="viewReportPreview('{{ $report->id }}')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn btn-sm btn-success"
                                                    onclick="sendReportEmail('{{ $report->id }}')">
                                                <i class="fas fa-envelope"></i>
                                            </button>
                                            @endif

                                            @if($report->status === 'failed')
                                            <button type="button"
                                                    class="btn btn-sm btn-warning"
                                                    onclick="retryReport('{{ $report->id }}')">
                                                <i class="fas fa-redo"></i>
                                            </button>
                                            @endif

                                            <button type="button"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="deleteReport('{{ $report->id }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="empty-state">
                                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                            <h4>No reports generated yet</h4>
                                            <p class="text-muted">Generate your first report using the button above.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Showing {{ $recentReports->firstItem() }} to {{ $recentReports->lastItem() }} of {{ $recentReports->total() }} reports
                        </div>
                        <div>
                            {{ $recentReports->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Analytics -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Report Generation Speed</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="generationSpeedChart"></canvas>
                    </div>
                    <div class="mt-3 text-center small">
                        <span class="text-primary font-weight-bold">Average: {{ $analytics['avg_generation_time'] }} seconds</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Report Usage by Department</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar-horizontal">
                        <canvas id="departmentUsageChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Generate Report Modal -->
<div class="modal fade" id="generateReportModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('admin.reports.generate') }}" method="POST" id="reportForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Generate Custom Report</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Report Type *</label>
                                <select class="form-control" name="report_type" id="reportType" required>
                                    <option value="">Select Report Type</option>
                                    <option value="financial">Financial Report</option>
                                    <option value="user">User Report</option>
                                    <option value="appointment">Appointment Report</option>
                                    <option value="audit">Audit Report</option>
                                    <option value="custom">Custom Report</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Report Title *</label>
                                <input type="text"
                                       class="form-control"
                                       name="title"
                                       placeholder="Enter report title"
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Start Date *</label>
                                <input type="date"
                                       class="form-control"
                                       name="start_date"
                                       value="{{ date('Y-m-01') }}"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>End Date *</label>
                                <input type="date"
                                       class="form-control"
                                       name="end_date"
                                       value="{{ date('Y-m-d') }}"
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Output Format *</label>
                                <select class="form-control" name="format" required>
                                    <option value="pdf">PDF Document</option>
                                    <option value="excel">Excel Spreadsheet</option>
                                    <option value="csv">CSV File</option>
                                    <option value="html">HTML Report</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email Notification</label>
                                <input type="email"
                                       class="form-control"
                                       name="email"
                                       placeholder="Enter email for notification (optional)">
                            </div>
                        </div>
                    </div>

                    <!-- Custom Fields based on Report Type -->
                    <div id="financialFields" class="report-fields" style="display: none;">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Financial Report Options</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="include_revenue" id="includeRevenue" checked>
                                                <label class="custom-control-label" for="includeRevenue">Include Revenue Details</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="include_payments" id="includePayments" checked>
                                                <label class="custom-control-label" for="includePayments">Include Payment Details</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="include_tax" id="includeTax" checked>
                                                <label class="custom-control-label" for="includeTax">Include Tax Details</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="include_charts" id="includeCharts">
                                                <label class="custom-control-label" for="includeCharts">Include Charts</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="userFields" class="report-fields" style="display: none;">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">User Report Options</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>User Role Filter</label>
                                            <select class="form-control" name="user_role">
                                                <option value="all">All Roles</option>
                                                <option value="admin">Administrators</option>
                                                <option value="doctor">Doctors</option>
                                                <option value="patient">Patients</option>
                                                <option value="lab_technician">Lab Technicians</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Status Filter</label>
                                            <select class="form-control" name="user_status">
                                                <option value="all">All Status</option>
                                                <option value="active">Active Only</option>
                                                <option value="inactive">Inactive Only</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Additional Notes (Optional)</label>
                        <textarea class="form-control" name="notes" rows="3" placeholder="Enter any additional notes for the report..."></textarea>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="schedule_report" id="scheduleReport">
                            <label class="custom-control-label" for="scheduleReport">
                                Schedule this report for regular generation
                            </label>
                        </div>
                    </div>

                    <div id="scheduleFields" style="display: none;">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Schedule Options</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Frequency</label>
                                            <select class="form-control" name="schedule_frequency">
                                                <option value="daily">Daily</option>
                                                <option value="weekly">Weekly</option>
                                                <option value="monthly">Monthly</option>
                                                <option value="quarterly">Quarterly</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Delivery Time</label>
                                            <input type="time" class="form-control" name="schedule_time" value="09:00">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="generateBtn">
                        <i class="fas fa-spinner fa-spin" style="display: none;"></i>
                        Generate Report
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.chart-area, .chart-bar, .chart-bar-horizontal {
    position: relative;
    height: 300px;
    width: 100%;
}

.chart-pie {
    position: relative;
    height: 250px;
    width: 100%;
}

.empty-state {
    text-align: center;
    padding: 40px 0;
}

.avatar-circle-sm {
    width: 30px;
    height: 30px;
    background-color: #4e73df;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.initials-sm {
    color: white;
    font-weight: bold;
    font-size: 12px;
}

.badge-primary { background-color: #4e73df; }
.badge-success { background-color: #1cc88a; }
.badge-info { background-color: #36b9cc; }
.badge-warning { background-color: #f6c23e; }
.badge-danger { background-color: #e74a3b; }

.card.border-left-primary { border-left-color: #4e73df !important; }
.card.border-left-success { border-left-color: #1cc88a !important; }
.card.border-left-info { border-left-color: #36b9cc !important; }
.card.border-left-warning { border-left-color: #f6c23e !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// User Registration Chart
const userCtx = document.getElementById('userRegistrationChart').getContext('2d');
const userChart = new Chart(userCtx, {
    type: 'line',
    data: {
        labels: @json($userRegistrations->keys()),
        datasets: [
            {
                label: 'Total Registrations',
                data: @json($userRegistrations->values()),
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderWidth: 2,
                fill: true
            },
            {
                label: 'Active Users',
                data: @json($activeUsers->values()),
                borderColor: '#1cc88a',
                backgroundColor: 'rgba(28, 200, 138, 0.05)',
                borderWidth: 2,
                fill: true,
                borderDash: [5, 5]
            }
        ]
    },
    options: {
        maintainAspectRatio: false,
        scales: {
            x: {
                grid: {
                    display: false
                }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString();
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                mode: 'index',
                intersect: false,
                callbacks: {
                    label: function(context) {
                        return `${context.dataset.label}: ${context.parsed.y.toLocaleString()}`;
                    }
                }
            }
        }
    }
});

// Report Type Chart
const reportTypeCtx = document.getElementById('reportTypeChart').getContext('2d');
const reportTypeChart = new Chart(reportTypeCtx, {
    type: 'doughnut',
    data: {
        labels: ['Financial', 'User', 'Appointment', 'Audit', 'Custom'],
        datasets: [{
            data: @json($reportDistribution),
            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#858796'],
            hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#d6891f', '#6c757d'],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
        }],
    },
    options: {
        maintainAspectRatio: false,
        cutout: '70%',
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.raw || 0;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = Math.round((value / total) * 100);
                        return `${label}: ${value} (${percentage}%)`;
                    }
                }
            }
        }
    }
});

// Generation Speed Chart
const speedCtx = document.getElementById('generationSpeedChart').getContext('2d');
const speedChart = new Chart(speedCtx, {
    type: 'bar',
    data: {
        labels: @json($generationSpeed->keys()),
        datasets: [{
            label: 'Generation Time (seconds)',
            data: @json($generationSpeed->values()),
            backgroundColor: '#4e73df',
            borderColor: '#2e59d9',
            borderWidth: 1
        }]
    },
    options: {
        maintainAspectRatio: false,
        scales: {
            x: {
                grid: {
                    display: false
                }
            },
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Seconds'
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Department Usage Chart
const deptCtx = document.getElementById('departmentUsageChart').getContext('2d');
const deptChart = new Chart(deptCtx, {
    type: 'bar',
    data: {
        labels: @json($departmentUsage->keys()),
        datasets: [{
            label: 'Report Usage',
            data: @json($departmentUsage->values()),
            backgroundColor: '#1cc88a',
            borderColor: '#17a673',
            borderWidth: 1
        }]
    },
    options: {
        indexAxis: 'y',
        maintainAspectRatio: false,
        scales: {
            x: {
                beginAtZero: true,
                grid: {
                    display: false
                }
            },
            y: {
                grid: {
                    display: false
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Toggle report type specific fields
document.getElementById('reportType').addEventListener('change', function() {
    const reportType = this.value;
    const allFields = document.querySelectorAll('.report-fields');

    // Hide all fields first
    allFields.forEach(field => {
        field.style.display = 'none';
    });

    // Show selected field
    if (reportType) {
        const selectedField = document.getElementById(reportType + 'Fields');
        if (selectedField) {
            selectedField.style.display = 'block';
        }
    }
});

// Toggle schedule fields
document.getElementById('scheduleReport').addEventListener('change', function() {
    const scheduleFields = document.getElementById('scheduleFields');
    scheduleFields.style.display = this.checked ? 'block' : 'none';
});

// Generate quick report
function generateQuickReport(type) {
    const modal = new bootstrap.Modal(document.getElementById('generateReportModal'));
    const form = document.getElementById('reportForm');
    const reportType = document.getElementById('reportType');

    reportType.value = type;
    reportType.dispatchEvent(new Event('change'));

    // Set title based on type
    const titles = {
        'user': 'User Activity Report',
        'financial': 'Financial Summary Report',
        'appointment': 'Appointment Analysis Report',
        'audit': 'System Audit Trail Report'
    };

    form.querySelector('[name="title"]').value = titles[type] || 'Custom Report';
    modal.show();
}

// Form submission
document.getElementById('reportForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const button = document.getElementById('generateBtn');
    const spinner = button.querySelector('.fa-spinner');

    // Show loading state
    button.disabled = true;
    spinner.style.display = 'inline-block';
    button.innerHTML = 'Generating...';

    // Submit form
    this.submit();
});

// Report actions
function viewReportPreview(reportId) {
    window.open(`/admin/reports/${reportId}/preview`, '_blank');
}

function sendReportEmail(reportId) {
    const email = prompt('Enter email address to send report:');
    if (email) {
        fetch(`/admin/reports/${reportId}/send-email`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Report sent successfully!');
            } else {
                alert(data.message || 'Failed to send report');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while sending the report');
        });
    }
}

function retryReport(reportId) {
    if (confirm('Retry generating this report?')) {
        fetch(`/admin/reports/${reportId}/retry`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to retry report generation');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while retrying report generation');
        });
    }
}

function deleteReport(reportId) {
    if (confirm('Are you sure you want to delete this report? This action cannot be undone.')) {
        fetch(`/admin/reports/${reportId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to delete report');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the report');
        });
    }
}

function refreshAnalytics() {
    location.reload();
}

function refreshReports() {
    // You can implement AJAX refresh here
    location.reload();
}

function exportUserChart() {
    const canvas = document.getElementById('userRegistrationChart');
    const link = document.createElement('a');
    link.download = 'user-registration-chart.png';
    link.href = canvas.toDataURL('image/png');
    link.click();
}

function toggleDataLabels() {
    // Implement data label toggling
    alert('Data labels toggled');
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection
