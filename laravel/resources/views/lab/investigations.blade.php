@extends('layouts.app')

@section('title', 'Lab Investigations')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1">Lab Investigations</h5>
                            <p class="text-muted mb-0">Manage and process patient test requests</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                                <i class="fas fa-filter me-1"></i> Filters
                                @if(request()->has('search') || request()->has('status') || request()->has('priority') || request()->has('test_type'))
                                    <span class="badge bg-danger ms-1">Active</span>
                                @endif
                            </button>
                            <a href="{{ route('lab.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-primary border-4 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">Pending Investigations</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['pending'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-warning border-4 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">In Processing</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['processing'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-spinner fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-info border-4 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">Assigned to Me</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['assigned_to_me'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-danger border-4 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-danger text-uppercase mb-1">High Priority</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['high_priority'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Investigations Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Investigation ID</th>
                            <th>Patient Information</th>
                            <th>Test Details</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Requested Date</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($investigations as $investigation)
                        <tr class="align-middle">
                            <td class="ps-4">
                                <div class="fw-bold">{{ $investigation->getFormattedId() }}</div>
                                <small class="text-muted">#{{ $investigation->InvestigationID }}</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        <div class="avatar-title bg-light rounded-circle">
                                            <i class="fas fa-user text-primary"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $investigation->patient->Name ?? 'N/A' }}</h6>
                                        <small class="text-muted">
                                            ID: {{ $investigation->patient->PatientID ?? 'N/A' }}
                                            @if($investigation->patient->Age ?? false)
                                            | Age: {{ $investigation->patient->Age }}
                                            @endif
                                            @if($investigation->patient->Gender ?? false)
                                            | {{ $investigation->patient->Gender }}
                                            @endif
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-user-md me-1"></i>
                                            {{ $investigation->doctor->Name ?? 'No doctor assigned' }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <span class="badge bg-info bg-opacity-10 text-info mb-1">
                                        {{ $investigation->testType->Category ?? 'N/A' }}
                                    </span>
                                    <h6 class="mb-1">{{ $investigation->testType->TestName ?? 'N/A' }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        Processing: {{ $investigation->testType->getProcessingTimeFormatted() ?? 'N/A' }}
                                    </small>
                                    @if($investigation->TestNotes)
                                    <div class="mt-1">
                                        <small class="text-muted">
                                            <i class="fas fa-sticky-note me-1"></i>
                                            {{ Str::limit($investigation->TestNotes, 50) }}
                                        </small>
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @php
                                    $priorityColors = [
                                        'Low' => 'success',
                                        'Normal' => 'info',
                                        'High' => 'warning',
                                        'Critical' => 'danger'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $priorityColors[$investigation->Priority] ?? 'secondary' }}">
                                    <i class="fas fa-flag me-1"></i>{{ $investigation->Priority }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'Pending' => 'warning',
                                        'Assigned' => 'primary',
                                        'Processing' => 'info',
                                        'Completed' => 'success',
                                        'Cancelled' => 'danger'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$investigation->Status] ?? 'secondary' }}">
                                    {{ $investigation->Status }}
                                </span>
                                @if($investigation->StaffID && $investigation->technician)
                                <div class="mt-1">
                                    <small class="text-muted">
                                        <i class="fas fa-user-cog me-1"></i>
                                        {{ $investigation->technician->Name }}
                                    </small>
                                </div>
                                @endif
                            </td>
                            <td>
                                <div>{{ $investigation->created_at->format('M j, Y') }}</div>
                                <small class="text-muted">{{ $investigation->created_at->format('g:i A') }}</small>
                                @if($investigation->CollectionDate)
                                <div class="mt-1">
                                    <small>
                                        <i class="fas fa-calendar-check me-1 text-success"></i>
                                        Collected: {{ $investigation->CollectionDate->format('M j') }}
                                    </small>
                                </div>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="#"
                                               data-bs-toggle="modal"
                                               data-bs-target="#viewModal{{ $investigation->InvestigationID }}">
                                                <i class="fas fa-eye me-2"></i> View Details
                                            </a>
                                        </li>

                                        @can('assign', $investigation)
                                        @if($investigation->Status == 'Pending' || ($investigation->Status == 'Assigned' && $investigation->StaffID != auth()->user()->labTechnician->StaffID))
                                        <li>
                                            <form action="{{ route('lab.investigation.assign', $investigation->InvestigationID) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-user-check me-2"></i> Assign to Me
                                                </button>
                                            </form>
                                        </li>
                                        @endif
                                        @endcan

                                        @can('update', $investigation)
                                        @if(in_array($investigation->Status, ['Assigned', 'Processing']))
                                        <li>
                                            <a class="dropdown-item" href="#"
                                               data-bs-toggle="modal"
                                               data-bs-target="#updateModal{{ $investigation->InvestigationID }}">
                                                <i class="fas fa-edit me-2"></i> Update Results
                                            </a>
                                        </li>
                                        @endif
                                        @endcan

                                        @if($investigation->Status == 'Completed' && $investigation->DigitalReport)
                                        <li>
                                            <a class="dropdown-item"
                                               href="{{ route('lab.report.download', $investigation->InvestigationID) }}">
                                                <i class="fas fa-download me-2"></i> Download Report
                                            </a>
                                        </li>
                                        @endif

                                        <li><hr class="dropdown-divider"></li>

                                        <li>
                                            <a class="dropdown-item" href="#">
                                                <i class="fas fa-print me-2"></i> Print Label
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>

                        <!-- View Details Modal -->
                        <div class="modal fade" id="viewModal{{ $investigation->InvestigationID }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            Investigation Details - {{ $investigation->getFormattedId() }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <h6 class="text-muted">Patient Information</h6>
                                                <p class="mb-1">
                                                    <strong>Name:</strong> {{ $investigation->patient->Name ?? 'N/A' }}
                                                </p>
                                                <p class="mb-1">
                                                    <strong>Age/Gender:</strong>
                                                    {{ $investigation->patient->Age ?? 'N/A' }} /
                                                    {{ $investigation->patient->Gender ?? 'N/A' }}
                                                </p>
                                                <p class="mb-1">
                                                    <strong>Contact:</strong> {{ $investigation->patient->ContactNumber ?? 'N/A' }}
                                                </p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <h6 class="text-muted">Test Information</h6>
                                                <p class="mb-1">
                                                    <strong>Test:</strong> {{ $investigation->testType->TestName ?? 'N/A' }}
                                                </p>
                                                <p class="mb-1">
                                                    <strong>Category:</strong> {{ $investigation->testType->Category ?? 'N/A' }}
                                                </p>
                                                <p class="mb-1">
                                                    <strong>Processing Time:</strong>
                                                    {{ $investigation->testType->getProcessingTimeFormatted() ?? 'N/A' }}
                                                </p>
                                            </div>
                                        </div>

                                        @if($investigation->TestNotes)
                                        <div class="mb-3">
                                            <h6 class="text-muted">Test Notes</h6>
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    {{ $investigation->TestNotes }}
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <h6 class="text-muted">Status & Timeline</h6>
                                                <p class="mb-1">
                                                    <strong>Status:</strong>
                                                    <span class="badge bg-{{ $statusColors[$investigation->Status] ?? 'secondary' }}">
                                                        {{ $investigation->Status }}
                                                    </span>
                                                </p>
                                                <p class="mb-1">
                                                    <strong>Priority:</strong>
                                                    <span class="badge bg-{{ $priorityColors[$investigation->Priority] ?? 'secondary' }}">
                                                        {{ $investigation->Priority }}
                                                    </span>
                                                </p>
                                                <p class="mb-1">
                                                    <strong>Requested:</strong> {{ $investigation->created_at->format('M j, Y g:i A') }}
                                                </p>
                                                @if($investigation->CollectionDate)
                                                <p class="mb-1">
                                                    <strong>Collected:</strong> {{ $investigation->CollectionDate->format('M j, Y g:i A') }}
                                                </p>
                                                @endif
                                                @if($investigation->CompletedDate)
                                                <p class="mb-1">
                                                    <strong>Completed:</strong> {{ $investigation->CompletedDate->format('M j, Y g:i A') }}
                                                </p>
                                                @endif
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <h6 class="text-muted">Assigned Personnel</h6>
                                                <p class="mb-1">
                                                    <strong>Doctor:</strong> {{ $investigation->doctor->Name ?? 'Not assigned' }}
                                                </p>
                                                <p class="mb-1">
                                                    <strong>Lab Technician:</strong>
                                                    {{ $investigation->technician->Name ?? 'Not assigned' }}
                                                </p>
                                                @if($investigation->technician)
                                                <p class="mb-1">
                                                    <strong>Department:</strong> {{ $investigation->technician->Department }}
                                                </p>
                                                @endif
                                            </div>
                                        </div>

                                        @if($investigation->ResultSummary)
                                        <div class="mb-3">
                                            <h6 class="text-muted">Result Summary</h6>
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    {{ $investigation->ResultSummary }}
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        @if($investigation->Status == 'Completed' && $investigation->DigitalReport)
                                        <a href="{{ route('lab.report.download', $investigation->InvestigationID) }}"
                                           class="btn btn-primary">
                                            <i class="fas fa-download me-1"></i> Download Report
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Update Results Modal -->
                        <div class="modal fade" id="updateModal{{ $investigation->InvestigationID }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form action="{{ route('lab.investigation.update', $investigation->InvestigationID) }}"
                                          method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Results - {{ $investigation->getFormattedId() }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Result Summary *</label>
                                                <textarea class="form-control" name="ResultSummary" rows="3"
                                                          placeholder="Enter test results summary..." required>{{ old('ResultSummary', $investigation->ResultSummary) }}</textarea>
                                                <small class="text-muted">Brief summary of the test findings</small>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Detailed Results</label>
                                                <textarea class="form-control" name="DetailedResults" rows="4"
                                                          placeholder="Enter detailed test results and observations...">{{ old('DetailedResults', $investigation->DetailedResults) }}</textarea>
                                                <small class="text-muted">Comprehensive analysis and observations</small>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Test Parameters (JSON)</label>
                                                <textarea class="form-control" name="TestParameters" rows="3"
                                                          placeholder='{"parameter": "value", ...}'>{{ old('TestParameters', $investigation->TestParameters ? json_encode($investigation->TestParameters, JSON_PRETTY_PRINT) : '') }}</textarea>
                                                <small class="text-muted">Enter test parameters in JSON format</small>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Digital Report *</label>
                                                <input type="file" class="form-control" name="DigitalReport" accept=".pdf,.jpg,.jpeg,.png">
                                                <small class="text-muted">Upload report file (PDF, JPG, PNG, max 5MB)</small>
                                                @if($investigation->DigitalReport)
                                                <div class="mt-2">
                                                    <small class="text-success">
                                                        <i class="fas fa-file me-1"></i>
                                                        Current file: {{ basename($investigation->DigitalReport) }}
                                                    </small>
                                                </div>
                                                @endif
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Status *</label>
                                                <select class="form-select" name="Status" required>
                                                    <option value="Processing" {{ old('Status', $investigation->Status) == 'Processing' ? 'selected' : '' }}>
                                                        Processing
                                                    </option>
                                                    <option value="Completed" {{ old('Status', $investigation->Status) == 'Completed' ? 'selected' : '' }}>
                                                        Completed
                                                    </option>
                                                </select>
                                                <small class="text-muted">Mark as completed when all results are ready</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-1"></i> Update Investigation
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="py-5">
                                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No investigations found</h5>
                                    <p class="text-muted mb-0">No test requests match your current filters</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($investigations->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        Showing {{ $investigations->firstItem() }} to {{ $investigations->lastItem() }} of {{ $investigations->total() }} results
                    </div>
                    <div>
                        {{ $investigations->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="GET" action="{{ route('lab.investigations') }}">
                <div class="modal-header">
                    <h5 class="modal-title">Filter Investigations</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Search</label>
                        <input type="text" class="form-control" name="search"
                               value="{{ request('search') }}"
                               placeholder="Search by patient name, test name, or ID...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Assigned" {{ request('status') == 'Assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="Processing" {{ request('status') == 'Processing' ? 'selected' : '' }}>Processing</option>
                            <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                            <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Priority</label>
                        <select class="form-select" name="priority">
                            <option value="all" {{ request('priority') == 'all' ? 'selected' : '' }}>All Priority</option>
                            <option value="Low" {{ request('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                            <option value="Normal" {{ request('priority') == 'Normal' ? 'selected' : '' }}>Normal</option>
                            <option value="High" {{ request('priority') == 'High' ? 'selected' : '' }}>High</option>
                            <option value="Critical" {{ request('priority') == 'Critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Test Type</label>
                        <select class="form-select" name="test_type">
                            <option value="all" {{ request('test_type') == 'all' ? 'selected' : '' }}>All Test Types</option>
                            @foreach($testTypes ?? [] as $testType)
                            <option value="{{ $testType->TestTypeID }}"
                                    {{ request('test_type') == $testType->TestTypeID ? 'selected' : '' }}>
                                {{ $testType->TestName }} ({{ $testType->Category }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date From</label>
                            <input type="date" class="form-control" name="date_from"
                                   value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date To</label>
                            <input type="date" class="form-control" name="date_to"
                                   value="{{ request('date_to') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('lab.investigations') }}" class="btn btn-outline-secondary">
                        Reset All
                    </a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
}

.avatar-sm {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.badge {
    font-weight: 500;
}

.dropdown-menu {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.modal-content {
    border: none;
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Update character counters for textareas
    $('textarea').on('input', function() {
        var length = $(this).val().length;
        var maxLength = $(this).attr('maxlength');
        if (maxLength) {
            var counter = $(this).next('.char-counter');
            if (!counter.length) {
                counter = $('<small class="char-counter text-muted float-end"></small>');
                $(this).after(counter);
            }
            counter.text(length + '/' + maxLength + ' characters');
        }
    });

    // Trigger input event on page load
    $('textarea').trigger('input');
});
</script>
@endsection
