@extends('layouts.app')

@section('title', 'Financial Management')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Financial Management</h1>
            <p class="mb-0">Monitor revenue, billing, and financial analytics</p>
        </div>
        <div>
            <button class="btn btn-primary" data-toggle="modal" data-target="#createInvoiceModal">
                <i class="fas fa-file-invoice-dollar fa-sm"></i> Create Invoice
            </button>
            <button class="btn btn-success" data-toggle="modal" data-target="#exportModal">
                <i class="fas fa-file-export fa-sm"></i> Export Data
            </button>
        </div>
    </div>

    <!-- Financial Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($financialStats['total_revenue'], 2) }}
                            </div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span class="text-success mr-2">
                                    <i class="fas fa-arrow-up"></i> All Time
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Monthly Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($financialStats['monthly_revenue'], 2) }}
                            </div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span>Current Month</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
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
                                Pending Payments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($financialStats['pending_revenue'], 2) }}
                            </div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span>Awaiting Collection</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Overdue Payments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($financialStats['overdue_revenue'], 2) }}
                            </div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span>Past Due Date</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="row mb-4">
        <div class="col-xl-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue Overview (Last 6 Months)</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                           data-toggle="dropdown">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                            <a class="dropdown-item" href="#" onclick="exportChart()">Export Chart</a>
                            <a class="dropdown-item" href="#" onclick="printChart()">Print Chart</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Status Distribution</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="paymentStatusChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Paid
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> Pending
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-warning"></i> Partial
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-danger"></i> Overdue
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Billings</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.financials') }}" method="GET" class="form-inline">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text"
                               name="search"
                               class="form-control w-100"
                               placeholder="Search invoice or patient..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-control w-100">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                            <option value="Partial" {{ request('status') == 'Partial' ? 'selected' : '' }}>Partial</option>
                            <option value="Overdue" {{ request('status') == 'Overdue' ? 'selected' : '' }}>Overdue</option>
                            <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date"
                               name="date_from"
                               class="form-control w-100"
                               value="{{ request('date_from') }}"
                               placeholder="From Date">
                    </div>
                    <div class="col-md-2">
                        <input type="date"
                               name="date_to"
                               class="form-control w-100"
                               value="{{ request('date_to') }}"
                               placeholder="To Date">
                    </div>
                    <div class="col-md-2">
                        <select name="payment_mode" class="form-control w-100">
                            <option value="all" {{ request('payment_mode') == 'all' ? 'selected' : '' }}>All Payment Modes</option>
                            <option value="Cash" {{ request('payment_mode') == 'Cash' ? 'selected' : '' }}>Cash</option>
                            <option value="Card" {{ request('payment_mode') == 'Card' ? 'selected' : '' }}>Card</option>
                            <option value="Bank Transfer" {{ request('payment_mode') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="Insurance" {{ request('payment_mode') == 'Insurance' ? 'selected' : '' }}>Insurance</option>
                            <option value="Online" {{ request('payment_mode') == 'Online' ? 'selected' : '' }}>Online</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Billings Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Billing Records</h6>
            <span class="badge badge-primary">Total: {{ $billings->total() }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="billingsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Patient</th>
                            <th>Amount Details</th>
                            <th>Dates</th>
                            <th>Status</th>
                            <th>Payment Mode</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($billings as $billing)
                        <tr>
                            <td>
                                <div class="font-weight-bold">{{ $billing->InvoiceNumber }}</div>
                                <div class="small text-muted">ID: {{ $billing->InvoiceID }}</div>
                                @if($billing->appointment)
                                <div class="small">
                                    <i class="fas fa-calendar-alt"></i>
                                    Appt: {{ $billing->appointment->AppointmentID }}
                                </div>
                                @endif
                            </td>
                            <td>
                                @if($billing->patient)
                                <div class="font-weight-bold">{{ $billing->patient->Name }}</div>
                                <div class="small text-muted">
                                    <i class="fas fa-id-card"></i> {{ $billing->patient->PatientID }}
                                </div>
                                <div class="small">
                                    <i class="fas fa-phone"></i> {{ $billing->patient->PhoneNumber }}
                                </div>
                                @else
                                <span class="text-muted">Patient not found</span>
                                @endif
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between">
                                            <span class="small">Subtotal:</span>
                                            <span class="small">${{ number_format($billing->TotalAmount, 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="small">Discount:</span>
                                            <span class="small text-success">-${{ number_format($billing->Discount, 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="small">Tax:</span>
                                            <span class="small">${{ number_format($billing->TaxAmount, 2) }}</span>
                                        </div>
                                        <hr class="my-1">
                                        <div class="d-flex justify-content-between font-weight-bold">
                                            <span>Total:</span>
                                            <span>${{ number_format($billing->FinalAmount, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    <div>
                                        <i class="fas fa-calendar-plus"></i>
                                        Issued: {{ $billing->IssueDate->format('M d, Y') }}
                                    </div>
                                    <div>
                                        <i class="fas fa-calendar-check"></i>
                                        Due: {{ $billing->DueDate->format('M d, Y') }}
                                    </div>
                                    @if($billing->isOverdue())
                                    <div class="text-danger">
                                        <i class="fas fa-exclamation-circle"></i>
                                        Overdue: {{ $billing->getDaysOverdue() }} days
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @php
                                    $statusClass = [
                                        'Pending' => 'warning',
                                        'Paid' => 'success',
                                        'Partial' => 'info',
                                        'Overdue' => 'danger',
                                        'Cancelled' => 'secondary'
                                    ][$billing->PaymentStatus] ?? 'secondary';
                                @endphp
                                <span class="badge badge-{{ $statusClass }} p-2">
                                    {{ $billing->PaymentStatus }}
                                </span>
                                @if($billing->TransactionID)
                                <div class="small text-muted mt-1">
                                    TXN: {{ $billing->TransactionID }}
                                </div>
                                @endif
                            </td>
                            <td>
                                @if($billing->PaymentMode)
                                <span class="badge badge-light p-2">
                                    <i class="fas fa-{{ $billing->getPaymentModeIcon() }}"></i>
                                    {{ $billing->PaymentMode }}
                                </span>
                                @else
                                <span class="text-muted">Not specified</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button"
                                            class="btn btn-sm btn-info"
                                            onclick="viewInvoice({{ $billing->InvoiceID }})">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    @if($billing->PaymentStatus !== 'Paid' && $billing->PaymentStatus !== 'Cancelled')
                                    <button type="button"
                                            class="btn btn-sm btn-success"
                                            data-toggle="modal"
                                            data-target="#recordPaymentModal{{ $billing->InvoiceID }}">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </button>
                                    @endif

                                    <button type="button"
                                            class="btn btn-sm btn-primary"
                                            onclick="printInvoice({{ $billing->InvoiceID }})">
                                        <i class="fas fa-print"></i>
                                    </button>

                                    <button type="button"
                                            class="btn btn-sm btn-warning"
                                            data-toggle="modal"
                                            data-target="#editInvoiceModal{{ $billing->InvoiceID }}">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    @if($billing->PaymentStatus === 'Cancelled')
                                    <span class="btn btn-sm btn-secondary disabled">
                                        <i class="fas fa-ban"></i>
                                    </span>
                                    @else
                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            onclick="confirmCancelInvoice({{ $billing->InvoiceID }})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Record Payment Modal -->
                        <div class="modal fade" id="recordPaymentModal{{ $billing->InvoiceID }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <form action="{{ route('admin.billings.record-payment', $billing->InvoiceID) }}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Record Payment</h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Invoice Number</label>
                                                <input type="text" class="form-control" value="{{ $billing->InvoiceNumber }}" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label>Patient</label>
                                                <input type="text" class="form-control" value="{{ $billing->patient->Name ?? 'N/A' }}" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label>Total Amount Due</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">$</span>
                                                    </div>
                                                    <input type="text"
                                                           class="form-control"
                                                           value="{{ number_format($billing->FinalAmount, 2) }}"
                                                           readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Payment Amount *</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">$</span>
                                                    </div>
                                                    <input type="number"
                                                           class="form-control"
                                                           name="amount"
                                                           step="0.01"
                                                           max="{{ $billing->FinalAmount }}"
                                                           value="{{ $billing->FinalAmount }}"
                                                           required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Payment Mode *</label>
                                                <select class="form-control" name="payment_mode" required>
                                                    <option value="">Select Payment Mode</option>
                                                    <option value="Cash">Cash</option>
                                                    <option value="Card">Card</option>
                                                    <option value="Bank Transfer">Bank Transfer</option>
                                                    <option value="Insurance">Insurance</option>
                                                    <option value="Online">Online Payment</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Transaction ID (Optional)</label>
                                                <input type="text" class="form-control" name="transaction_id">
                                            </div>
                                            <div class="form-group">
                                                <label>Payment Date</label>
                                                <input type="date"
                                                       class="form-control"
                                                       name="payment_date"
                                                       value="{{ date('Y-m-d') }}">
                                            </div>
                                            <div class="form-group">
                                                <label>Notes (Optional)</label>
                                                <textarea class="form-control" name="notes" rows="2"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Record Payment</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Edit Invoice Modal -->
                        <div class="modal fade" id="editInvoiceModal{{ $billing->InvoiceID }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg" role="document">
                                <form action="{{ route('admin.billings.update', $billing->InvoiceID) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Invoice: {{ $billing->InvoiceNumber }}</h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Invoice Number</label>
                                                        <input type="text"
                                                               class="form-control"
                                                               value="{{ $billing->InvoiceNumber }}"
                                                               readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Issue Date *</label>
                                                        <input type="date"
                                                               class="form-control"
                                                               name="issue_date"
                                                               value="{{ $billing->IssueDate->format('Y-m-d') }}"
                                                               required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Due Date *</label>
                                                        <input type="date"
                                                               class="form-control"
                                                               name="due_date"
                                                               value="{{ $billing->DueDate->format('Y-m-d') }}"
                                                               required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Payment Status</label>
                                                        <select class="form-control" name="payment_status">
                                                            <option value="Pending" {{ $billing->PaymentStatus == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                            <option value="Paid" {{ $billing->PaymentStatus == 'Paid' ? 'selected' : '' }}>Paid</option>
                                                            <option value="Partial" {{ $billing->PaymentStatus == 'Partial' ? 'selected' : '' }}>Partial</option>
                                                            <option value="Overdue" {{ $billing->PaymentStatus == 'Overdue' ? 'selected' : '' }}>Overdue</option>
                                                            <option value="Cancelled" {{ $billing->PaymentStatus == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Consultation Fee</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">$</span>
                                                            </div>
                                                            <input type="number"
                                                                   class="form-control"
                                                                   name="consultation_fee"
                                                                   step="0.01"
                                                                   value="{{ $billing->ConsultationFee }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Test Fees</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">$</span>
                                                            </div>
                                                            <input type="number"
                                                                   class="form-control"
                                                                   name="test_fees"
                                                                   step="0.01"
                                                                   value="{{ $billing->TestFees }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Medicine Fees</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">$</span>
                                                            </div>
                                                            <input type="number"
                                                                   class="form-control"
                                                                   name="medicine_fees"
                                                                   step="0.01"
                                                                   value="{{ $billing->MedicineFees }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Discount</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">$</span>
                                                            </div>
                                                            <input type="number"
                                                                   class="form-control"
                                                                   name="discount"
                                                                   step="0.01"
                                                                   value="{{ $billing->Discount }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Tax Amount</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">$</span>
                                                            </div>
                                                            <input type="number"
                                                                   class="form-control"
                                                                   name="tax_amount"
                                                                   step="0.01"
                                                                   value="{{ $billing->TaxAmount }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Notes</label>
                                                        <textarea class="form-control"
                                                                  name="notes"
                                                                  rows="2">{{ $billing->Notes }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Update Invoice</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="fas fa-file-invoice-dollar fa-3x text-muted mb-3"></i>
                                    <h4>No billing records found</h4>
                                    <p class="text-muted">No records match your search criteria.</p>
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
                    Showing {{ $billings->firstItem() }} to {{ $billings->lastItem() }} of {{ $billings->total() }} records
                </div>
                <div>
                    {{ $billings->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Invoice Modal -->
<div class="modal fade" id="createInvoiceModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('admin.billings.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Invoice</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Patient *</label>
                                <select class="form-control" name="patient_id" required>
                                    <option value="">Select Patient</option>
                                    @foreach($patients as $patient)
                                    <option value="{{ $patient->PatientID }}">
                                        {{ $patient->Name }} (ID: {{ $patient->PatientID }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Appointment (Optional)</label>
                                <select class="form-control" name="appointment_id">
                                    <option value="">Select Appointment</option>
                                    @foreach($appointments as $appointment)
                                    <option value="{{ $appointment->AppointmentID }}">
                                        {{ $appointment->AppointmentID }} - {{ $appointment->Date->format('M d, Y') }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Issue Date *</label>
                                <input type="date"
                                       class="form-control"
                                       name="issue_date"
                                       value="{{ date('Y-m-d') }}"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Due Date *</label>
                                <input type="date"
                                       class="form-control"
                                       name="due_date"
                                       value="{{ date('Y-m-d', strtotime('+30 days')) }}"
                                       required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Consultation Fee</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number"
                                           class="form-control"
                                           name="consultation_fee"
                                           step="0.01"
                                           value="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Test Fees</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number"
                                           class="form-control"
                                           name="test_fees"
                                           step="0.01"
                                           value="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Medicine Fees</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number"
                                           class="form-control"
                                           name="medicine_fees"
                                           step="0.01"
                                           value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Discount</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number"
                                           class="form-control"
                                           name="discount"
                                           step="0.01"
                                           value="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tax Amount</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number"
                                           class="form-control"
                                           name="tax_amount"
                                           step="0.01"
                                           value="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Payment Status</label>
                                <select class="form-control" name="payment_status">
                                    <option value="Pending">Pending</option>
                                    <option value="Paid">Paid</option>
                                    <option value="Partial">Partial</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Notes (Optional)</label>
                        <textarea class="form-control" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Invoice</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="{{ route('admin.financials.export') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Export Financial Data</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Export Format *</label>
                        <select class="form-control" name="format" required>
                            <option value="pdf">PDF Document</option>
                            <option value="csv">CSV File</option>
                            <option value="excel">Excel File</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Date Range *</label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="date"
                                       class="form-control"
                                       name="start_date"
                                       value="{{ date('Y-m-01') }}"
                                       required>
                            </div>
                            <div class="col-md-6">
                                <input type="date"
                                       class="form-control"
                                       name="end_date"
                                       value="{{ date('Y-m-d') }}"
                                       required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Include Columns</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="columns[]" value="invoice_number" checked>
                            <label class="form-check-label">Invoice Number</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="columns[]" value="patient_name" checked>
                            <label class="form-check-label">Patient Name</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="columns[]" value="amounts" checked>
                            <label class="form-check-label">Amount Details</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="columns[]" value="dates" checked>
                            <label class="form-check-label">Dates</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="columns[]" value="status" checked>
                            <label class="form-check-label">Payment Status</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Export Data</button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.chart-area {
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

.badge-success { background-color: #1cc88a; }
.badge-primary { background-color: #4e73df; }
.badge-warning { background-color: #f6c23e; }
.badge-danger { background-color: #e74a3b; }
.badge-secondary { background-color: #858796; }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: @json($revenueChart->keys()),
        datasets: [{
            label: 'Revenue',
            data: @json($revenueChart->values()),
            borderColor: '#4e73df',
            backgroundColor: 'rgba(78, 115, 223, 0.05)',
            pointBackgroundColor: '#4e73df',
            pointBorderColor: '#4e73df',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: '#4e73df',
            pointRadius: 3,
            pointHoverRadius: 5,
            borderWidth: 2,
            fill: true
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
                ticks: {
                    callback: function(value) {
                        return '$' + value.toLocaleString();
                    }
                }
            }
        },
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Revenue: $' + context.parsed.y.toLocaleString();
                    }
                }
            }
        }
    }
});

// Payment Status Chart
const paymentCtx = document.getElementById('paymentStatusChart').getContext('2d');
const paymentChart = new Chart(paymentCtx, {
    type: 'doughnut',
    data: {
        labels: ['Paid', 'Pending', 'Partial', 'Overdue', 'Cancelled'],
        datasets: [{
            data: @json($paymentStats),
            backgroundColor: ['#1cc88a', '#4e73df', '#36b9cc', '#e74a3b', '#858796'],
            hoverBackgroundColor: ['#17a673', '#2e59d9', '#2c9faf', '#d52a1e', '#6c757d'],
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

function exportChart() {
    const revenueCanvas = document.getElementById('revenueChart');
    const link = document.createElement('a');
    link.download = 'revenue-chart.png';
    link.href = revenueCanvas.toDataURL('image/png');
    link.click();
}

function printChart() {
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Revenue Chart</title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    img { max-width: 100%; height: auto; }
                </style>
            </head>
            <body>
                <h2>Revenue Overview</h2>
                <img src="${document.getElementById('revenueChart').toDataURL('image/png')}">
                <p>Generated on ${new Date().toLocaleString()}</p>
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

function viewInvoice(invoiceId) {
    window.open(`/admin/billings/${invoiceId}/view`, '_blank');
}

function printInvoice(invoiceId) {
    window.open(`/admin/billings/${invoiceId}/print`, '_blank');
}

function confirmCancelInvoice(invoiceId) {
    if (confirm('Are you sure you want to cancel this invoice? This action cannot be undone.')) {
        fetch(`/admin/billings/${invoiceId}/cancel`, {
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
                alert(data.message || 'Failed to cancel invoice');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while cancelling the invoice');
        });
    }
}

// Auto-calculate total amount
document.addEventListener('DOMContentLoaded', function() {
    const feeInputs = document.querySelectorAll('[name="consultation_fee"], [name="test_fees"], [name="medicine_fees"]');
    feeInputs.forEach(input => {
        input.addEventListener('input', calculateTotal);
    });
});

function calculateTotal() {
    const consultationFee = parseFloat(document.querySelector('[name="consultation_fee"]').value) || 0;
    const testFees = parseFloat(document.querySelector('[name="test_fees"]').value) || 0;
    const medicineFees = parseFloat(document.querySelector('[name="medicine_fees"]').value) || 0;
    const discount = parseFloat(document.querySelector('[name="discount"]').value) || 0;
    const taxAmount = parseFloat(document.querySelector('[name="tax_amount"]').value) || 0;

    const subtotal = consultationFee + testFees + medicineFees;
    const total = subtotal - discount + taxAmount;

    // You can display this total somewhere or use it for validation
    console.log('Calculated total:', total);
}

</script>
@endsection
