@extends('layouts.app')

@section('title', 'Investigation History')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1">Investigation History</h5>
                            <p class="text-muted mb-0">Completed test reports and historical data</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                                <i class="fas fa-filter me-1"></i> Filters
                                @if(request()->hasAny(['search', 'date_from', 'date_to', 'test_type']))
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

    <!-- Stats Overview -->
    <div class="row mb-4">
        <div class="col-md-3 mb-4">
            <div class="card bg-gradient-success text-white shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-uppercase mb-0">Total Completed</h6>
                            <h3 class="mb-0">{{ $stats['total_completed'] ?? 0 }}</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card bg-gradient-info text-white shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-uppercase mb-0">Completed This Month</h6>
                            <h3 class="mb-0">{{ $stats['completed_this_month'] ?? 0 }}</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-calendar-check fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card bg-gradient-warning text-white shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-uppercase mb-0">Avg. Turnaround</h6>
                            <h3 class="mb-0">{{ $stats['avg_turnaround'] ?? 0 }}h</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card bg-gradient-primary text-white shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-uppercase mb-0">Reports Generated</h6>
                            <h3 class="mb-0">{{ $stats['reports_generated'] ?? 0 }}</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-file-pdf fa-2x opacity-50"></i>
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

    <!-- History Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Investigation ID</th>
                            <th>Patient Information</th>
                            <th>Test Details</th>
                            <th>Results</th>
                            <th>Completion Date</th>
                            <th>Turnaround Time</th>
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
                                            | Age: {{ $investigation->patient->Age ?? 'N/A' }}
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-user-md me-1"></i>
                                            {{ $investigation->doctor->Name ?? 'No doctor' }}
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
                                        <i class="fas fa-money-bill-wave me-1"></i>
                                        ${{ number_format($investigation->testType->Price, 2) }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                @if($investigation->ResultSummary)
                                <div class="mb-1">
                                    <strong class="d-block">Summary:</strong>
                                    <small>{{ Str::limit($investigation->ResultSummary, 60) }}</small>
                                </div>
                                @endif

                                @if($investigation->TestParameters)
                                <div>
                                    <small class="text-muted">
                                        <i class="fas fa-list me-1"></i>
                                        {{ count($investigation->TestParameters) }} parameters
                                    </small>
                                </div>
                                @endif

                                @php
                                    $resultStatus = 'Normal';
                                    if($investigation->ResultSummary) {
                                        $summary = strtolower($investigation->ResultSummary);
                                        if(str_contains($summary, ['abnormal', 'elevated', 'high', 'low', 'positive'])) {
                                            $resultStatus = 'Abnormal';
                                        }
                                    }
                                @endphp
                                <span class="badge bg-{{ $resultStatus == 'Normal' ? 'success' : 'danger' }} mt-1">
                                    {{ $resultStatus }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $investigation->CompletedDate->format('M j, Y') }}</div>
                                <small class="text-muted">{{ $investigation->CompletedDate->format('g:i A') }}</small>
                                <div class="mt-1">
                                    <small>
                                        <i class="fas fa-hourglass-start me-1"></i>
                                        Processed: {{ $investigation->ProcessingDate ? $investigation->ProcessingDate->format('M j') : 'N/A' }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                @php
                                    $turnaround = $investigation->getTurnaroundTime();
                                    $hours = $turnaround ?? 0;

                                    if($hours < 24) {
                                        $display = $hours . 'h';
                                        $color = $hours <= 12 ? 'success' : ($hours <= 24 ? 'warning' : 'danger');
                                    } else {
                                        $days = floor($hours / 24);
                                        $display = $days . 'd ' . ($hours % 24) . 'h';
                                        $color = $days <= 1 ? 'warning' : 'danger';
                                    }
                                @endphp
                                <span class="badge bg-{{ $color }}">
                                    <i class="fas fa-clock me-1"></i>{{ $display }}
                                </span>
                                @if($investigation->CollectionDate)
                                <div class="mt-1">
                                    <small class="text-muted">
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
                                                <i class="fas fa-eye me-2"></i> View Full Report
                                            </a>
                                        </li>

                                        @if($investigation->DigitalReport)
                                        <li>
                                            <a class="dropdown-item"
                                               href="{{ route('lab.report.download', $investigation->InvestigationID) }}">
                                                <i class="fas fa-download me-2"></i> Download PDF
                                            </a>
                                        </li>
                                        @endif

                                        <li>
                                            <a class="dropdown-item" href="#">
                                                <i class="fas fa-print me-2"></i> Print Report
                                            </a>
                                        </li>

                                        <li><hr class="dropdown-divider"></li>

                                        <li>
                                            <a class="dropdown-item text-danger" href="#"
                                               onclick="return confirm('Are you sure you want to delete this record? This action cannot be undone.')">
                                                <i class="fas fa-trash me-2"></i> Delete Record
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>

                        <!-- View Full Report Modal -->
                        <div class="modal fade" id="viewModal{{ $investigation->InvestigationID }}" tabindex="-1">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header bg-light">
                                        <h5 class="modal-title">
                                            <i class="fas fa-file-medical-alt me-2"></i>
                                            Complete Investigation Report - {{ $investigation->getFormattedId() }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Report Header -->
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="avatar-lg me-3">
                                                        <div class="avatar-title bg-primary bg-opacity-10 rounded-circle">
                                                            <i class="fas fa-hospital text-primary fa-2x"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h4 class="mb-0">SmartHealth Laboratory</h4>
                                                        <p class="text-muted mb-0">Diagnostic & Research Center</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <h5 class="text-muted">INVESTIGATION REPORT</h5>
                                                <h3 class="text-primary">{{ $investigation->getFormattedId() }}</h3>
                                                <p class="mb-0">
                                                    <strong>Report Date:</strong> {{ $investigation->CompletedDate->format('F j, Y') }}
                                                </p>
                                                <p class="mb-0">
                                                    <strong>Report Time:</strong> {{ $investigation->CompletedDate->format('g:i A') }}
                                                </p>
                                            </div>
                                        </div>

                                        <hr class="my-4">

                                        <!-- Patient Information -->
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2 mb-3">
                                                    <i class="fas fa-user me-2"></i>Patient Information
                                                </h6>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <p class="mb-2"><strong>Name:</strong></p>
                                                        <p class="mb-2"><strong>Patient ID:</strong></p>
                                                        <p class="mb-2"><strong>Age/Gender:</strong></p>
                                                        <p class="mb-2"><strong>Contact:</strong></p>
                                                    </div>
                                                    <div class="col-6">
                                                        <p class="mb-2">{{ $investigation->patient->Name ?? 'N/A' }}</p>
                                                        <p class="mb-2">{{ $investigation->patient->PatientID ?? 'N/A' }}</p>
                                                        <p class="mb-2">
                                                            {{ $investigation->patient->Age ?? 'N/A' }} /
                                                            {{ $investigation->patient->Gender ?? 'N/A' }}
                                                        </p>
                                                        <p class="mb-2">{{ $investigation->patient->ContactNumber ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2 mb-3">
                                                    <i class="fas fa-user-md me-2"></i>Referring Physician
                                                </h6>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <p class="mb-2"><strong>Doctor:</strong></p>
                                                        <p class="mb-2"><strong>Department:</strong></p>
                                                        <p class="mb-2"><strong>Request Date:</strong></p>
                                                        <p class="mb-2"><strong>Priority:</strong></p>
                                                    </div>
                                                    <div class="col-6">
                                                        <p class="mb-2">{{ $investigation->doctor->Name ?? 'Not specified' }}</p>
                                                        <p class="mb-2">{{ $investigation->doctor->Department ?? 'N/A' }}</p>
                                                        <p class="mb-2">{{ $investigation->created_at->format('M j, Y') }}</p>
                                                        <p class="mb-2">
                                                            <span class="badge bg-{{
                                                                ['Low' => 'success', 'Normal' => 'info', 'High' => 'warning', 'Critical' => 'danger'][$investigation->Priority] ?? 'secondary'
                                                            }}">
                                                                {{ $investigation->Priority }}
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Test Information -->
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <h6 class="border-bottom pb-2 mb-3">
                                                    <i class="fas fa-flask me-2"></i>Test Information
                                                </h6>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <p class="mb-2"><strong>Test Name:</strong></p>
                                                        <p class="mb-2">{{ $investigation->testType->TestName ?? 'N/A' }}</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <p class="mb-2"><strong>Category:</strong></p>
                                                        <p class="mb-2">{{ $investigation->testType->Category ?? 'N/A' }}</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <p class="mb-2"><strong>Test Code:</strong></p>
                                                        <p class="mb-2">{{ $investigation->testType->TestTypeID ?? 'N/A' }}</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <p class="mb-2"><strong>Price:</strong></p>
                                                        <p class="mb-2">${{ number_format($investigation->testType->Price ?? 0, 2) }}</p>
                                                    </div>
                                                </div>

                                                @if($investigation->testType->Description)
                                                <div class="mt-3">
                                                    <p class="mb-1"><strong>Test Description:</strong></p>
                                                    <p class="text-muted">{{ $investigation->testType->Description }}</p>
                                                </div>
                                                @endif

                                                @if($investigation->TestNotes)
                                                <div class="mt-3">
                                                    <p class="mb-1"><strong>Clinical Notes:</strong></p>
                                                    <div class="card bg-light">
                                                        <div class="card-body">
                                                            {{ $investigation->TestNotes }}
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Timeline -->
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <h6 class="border-bottom pb-2 mb-3">
                                                    <i class="fas fa-history me-2"></i>Investigation Timeline
                                                </h6>
                                                <div class="timeline">
                                                    <div class="timeline-item">
                                                        <div class="timeline-marker bg-primary"></div>
                                                        <div class="timeline-content">
                                                            <h6 class="mb-0">Request Received</h6>
                                                            <p class="text-muted mb-0">{{ $investigation->created_at->format('M j, Y g:i A') }}</p>
                                                        </div>
                                                    </div>
                                                    @if($investigation->CollectionDate)
                                                    <div class="timeline-item">
                                                        <div class="timeline-marker bg-info"></div>
                                                        <div class="timeline-content">
                                                            <h6 class="mb-0">Sample Collected</h6>
                                                            <p class="text-muted mb-0">{{ $investigation->CollectionDate->format('M j, Y g:i A') }}</p>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @if($investigation->ProcessingDate)
                                                    <div class="timeline-item">
                                                        <div class="timeline-marker bg-warning"></div>
                                                        <div class="timeline-content">
                                                            <h6 class="mb-0">Processing Started</h6>
                                                            <p class="text-muted mb-0">{{ $investigation->ProcessingDate->format('M j, Y g:i A') }}</p>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    <div class="timeline-item">
                                                        <div class="timeline-marker bg-success"></div>
                                                        <div class="timeline-content">
                                                            <h6 class="mb-0">Report Completed</h6>
                                                            <p class="text-muted mb-0">{{ $investigation->CompletedDate->format('M j, Y g:i A') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Results -->
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <h6 class="border-bottom pb-2 mb-3">
                                                    <i class="fas fa-chart-line me-2"></i>Test Results
                                                </h6>

                                                <div class="mb-4">
                                                    <h6 class="text-muted">Result Summary</h6>
                                                    <div class="card bg-light">
                                                        <div class="card-body">
                                                            <p class="mb-0">{{ $investigation->ResultSummary ?? 'No summary available' }}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if($investigation->DetailedResults)
                                                <div class="mb-4">
                                                    <h6 class="text-muted">Detailed Analysis</h6>
                                                    <div class="card bg-light">
                                                        <div class="card-body">
                                                            <p class="mb-0">{{ $investigation->DetailedResults }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                @if($investigation->TestParameters && count($investigation->TestParameters) > 0)
                                                <div class="mb-4">
                                                    <h6 class="text-muted mb-3">Test Parameters</h6>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th>Parameter</th>
                                                                    <th>Result</th>
                                                                    <th>Unit</th>
                                                                    <th>Normal Range</th>
                                                                    <th>Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($investigation->TestParameters as $param)
                                                                <tr>
                                                                    <td>{{ $param['name'] ?? 'N/A' }}</td>
                                                                    <td>{{ $param['value'] ?? 'N/A' }}</td>
                                                                    <td>{{ $param['unit'] ?? 'N/A' }}</td>
                                                                    <td>{{ $param['range'] ?? 'N/A' }}</td>
                                                                    <td>
                                                                        @php
                                                                            $status = $param['status'] ?? 'normal';
                                                                            $badgeClass = $status == 'normal' ? 'success' :
                                                                                         ($status == 'borderline' ? 'warning' : 'danger');
                                                                        @endphp
                                                                        <span class="badge bg-{{ $badgeClass }}">
                                                                            {{ ucfirst($status) }}
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Lab Information -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2 mb-3">
                                                    <i class="fas fa-microscope me-2"></i>Laboratory Information
                                                </h6>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <p class="mb-2"><strong>Lab Technician:</strong></p>
                                                        <p class="mb-2"><strong>Department:</strong></p>
                                                        <p class="mb-2"><strong>Qualification:</strong></p>
                                                        <p class="mb-2"><strong>License No:</strong></p>
                                                    </div>
                                                    <div class="col-6">
                                                        <p class="mb-2">{{ $investigation->technician->Name ?? 'N/A' }}</p>
                                                        <p class="mb-2">{{ $investigation->technician->Department ?? 'N/A' }}</p>
                                                        <p class="mb-2">{{ $investigation->technician->Qualification ?? 'N/A' }}</p>
                                                        <p class="mb-2">{{ $investigation->technician->LicenseNumber ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="border-bottom pb-2 mb-3">
                                                    <i class="fas fa-stamp me-2"></i>Report Verification
                                                </h6>
                                                <div class="text-center">
                                                    <div class="border rounded p-3 mb-3">
                                                        <p class="mb-1"><strong>Report Status:</strong></p>
                                                        <span class="badge bg-success">Verified & Completed</span>
                                                    </div>
                                                    <div class="border rounded p-3">
                                                        <p class="mb-1"><strong>Digital Signature:</strong></p>
                                                        <p class="text-muted mb-0">Electronically signed by lab system</p>
                                                        <small class="text-muted">Timestamp: {{ now()->format('Y-m-d H:i:s') }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Footer Notes -->
                                        <hr class="my-4">
                                        <div class="text-center text-muted">
                                            <p class="mb-1">
                                                <strong>Note:</strong> This is an electronically generated report. No physical signature is required.
                                            </p>
                                            <p class="mb-0">
                                                For any queries regarding this report, please contact the laboratory at
                                                <strong>lab@smarthospital.com</strong> or call <strong>(123) 456-7890</strong>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" onclick="window.print()">
                                            <i class="fas fa-print me-1"></i> Print Report
                                        </button>
                                        @if($investigation->DigitalReport)
                                        <a href="{{ route('lab.report.download', $investigation->InvestigationID) }}"
                                           class="btn btn-success">
                                            <i class="fas fa-download me-1"></i> Download PDF
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="py-5">
                                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No investigation history found</h5>
                                    <p class="text-muted mb-0">You haven't completed any investigations yet</p>
                                    <a href="{{ route('lab.dashboard') }}" class="btn btn-primary mt-3">
                                        <i class="fas fa-tachometer-alt me-1"></i> Go to Dashboard
                                    </a>
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
            <form method="GET" action="{{ route('lab.history') }}">
                <div class="modal-header">
                    <h5 class="modal-title">Filter History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Search</label>
                        <input type="text" class="form-control" name="search"
                               value="{{ request('search') }}"
                               placeholder="Search by patient name or test name...">
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

                    <div class="mb-3">
                        <label class="form-label">Test Type</label>
                        <select class="form-select" name="test_type">
                            <option value="all" {{ request('test_type') == 'all' ? 'selected' : '' }}>All Test Types</option>
                            @foreach($testTypes ?? [] as $testType)
                            <option value="{{ $testType->TestTypeID }}"
                                    {{ request('test_type') == $testType->TestTypeID ? 'selected' : '' }}>
                                {{ $testType->TestName }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Result Status</label>
                        <select class="form-select" name="result_status">
                            <option value="all" {{ request('result_status') == 'all' ? 'selected' : '' }}>All Results</option>
                            <option value="normal" {{ request('result_status') == 'normal' ? 'selected' : '' }}>Normal Results</option>
                            <option value="abnormal" {{ request('result_status') == 'abnormal' ? 'selected' : '' }}>Abnormal Results</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('lab.history') }}" class="btn btn-outline-secondary">
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
}

.avatar-lg {
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 5px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 3px rgba(0,0,0,0.1);
}

.timeline-content {
    padding-left: 15px;
}

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
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
    // Auto-dismiss alerts
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);

    // Print modal content
    $('.print-report').on('click', function() {
        var modalContent = $(this).closest('.modal-content').clone();
        modalContent.find('.modal-footer').remove();
        modalContent.find('.btn-close').remove();

        var printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Print Report</title>');
        printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">');
        printWindow.document.write('<style>@media print { body { margin: 20px; } }</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(modalContent.html());
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();

        setTimeout(function() {
            printWindow.print();
            printWindow.close();
        }, 500);
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection
