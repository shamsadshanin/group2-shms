@extends('layouts.app')

@section('title', 'Prescription Dispensing')

@section('styles')
<style>
    .prescription-card { border-left: 4px solid #007bff; }
    .prescription-card.dispensed { border-left-color: #28a745; }
    .prescription-card.pending { border-left-color: #ffc107; }
    .prescription-card.cancelled { border-left-color: #dc3545; }
    .patient-avatar { width: 50px; height: 50px; object-fit: cover; }
    .status-badge { font-size: 0.75rem; }
    .medicine-icon { color: #6c757d; }
    .prescription-details { max-height: 300px; overflow-y: auto; }
    .quantity-input { max-width: 100px; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-prescription"></i> Prescription Dispensing
                        <span class="badge bg-info">{{ $prescriptions->total() }} Prescriptions</span>
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Search and Filter -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('pharmacy.prescriptions') }}" class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control"
                                           placeholder="Search patient or medicine..."
                                           value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <select name="status" class="form-select">
                                        <option value="all">All Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="dispensed" {{ request('status') == 'dispensed' ? 'selected' : '' }}>Dispensed</option>
                                        <option value="partially" {{ request('status') == 'partially' ? 'selected' : '' }}>Partially Dispensed</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="date" name="date_from" class="form-control"
                                           value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" name="date_to" class="form-control"
                                           value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Filter
                                    </button>
                                    <a href="{{ route('pharmacy.prescriptions') }}" class="btn btn-secondary">
                                        <i class="fas fa-redo"></i> Reset
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title">Pending</h6>
                                            <h2>{{ $stats['pending'] }}</h2>
                                        </div>
                                        <i class="fas fa-clock fa-3x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title">Today's Dispensed</h6>
                                            <h2>{{ $stats['today_dispensed'] }}</h2>
                                        </div>
                                        <i class="fas fa-check-circle fa-3x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title">This Month</h6>
                                            <h2>{{ $stats['month_dispensed'] }}</h2>
                                        </div>
                                        <i class="fas fa-calendar-alt fa-3x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title">Total Revenue</h6>
                                            <h2>৳{{ number_format($stats['total_revenue'], 2) }}</h2>
                                        </div>
                                        <i class="fas fa-dollar-sign fa-3x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Prescriptions List -->
                    <div class="row">
                        @forelse($prescriptions as $prescription)
                        <div class="col-md-6 mb-4">
                            <div class="card prescription-card
                                @if($prescription->Status == 'Dispensed') dispensed
                                @elseif($prescription->Status == 'Pending') pending
                                @elseif($prescription->Status == 'Cancelled') cancelled
                                @endif">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">
                                                <i class="fas fa-user-injured"></i>
                                                {{ $prescription->patient->Name ?? 'N/A' }}
                                            </h6>
                                            <small class="text-muted">
                                                Age: {{ $prescription->patient->Age ?? 'N/A' }} |
                                                Prescription #{{ $prescription->PrescriptionID }}
                                            </small>
                                        </div>
                                        <div>
                                            @if($prescription->Status == 'Pending')
                                                <span class="badge bg-warning status-badge">Pending</span>
                                            @elseif($prescription->Status == 'Dispensed')
                                                <span class="badge bg-success status-badge">Dispensed</span>
                                            @elseif($prescription->Status == 'Partially_Dispensed')
                                                <span class="badge bg-info status-badge">Partially Dispensed</span>
                                            @elseif($prescription->Status == 'Cancelled')
                                                <span class="badge bg-danger status-badge">Cancelled</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- Medicine Information -->
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-pills medicine-icon me-2"></i>
                                                <div>
                                                    <strong>{{ $prescription->MedicineName }}</strong>
                                                    @if($prescription->medicine)
                                                    <span class="badge bg-secondary">
                                                        {{ $prescription->medicine->SKU }}
                                                    </span>
                                                    @endif
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ $prescription->Dosage }} • {{ $prescription->Frequency }} • {{ $prescription->Duration }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Doctor Information -->
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-md text-primary me-2"></i>
                                                <div>
                                                    <small class="text-muted">Prescribed by:</small><br>
                                                    <strong>Dr. {{ $prescription->doctor->Name ?? 'N/A' }}</strong>
                                                    <small class="text-muted ms-2">
                                                        {{ $prescription->IssueDate->format('M d, Y') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Dispensing Information -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-2">
                                                <small class="text-muted">Prescribed:</small><br>
                                                <strong>{{ $prescription->QuantityPrescribed }} units</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-2">
                                                <small class="text-muted">Dispensed:</small><br>
                                                <strong class="{{ $prescription->QuantityDispensed < $prescription->QuantityPrescribed ? 'text-warning' : 'text-success' }}">
                                                    {{ $prescription->QuantityDispensed }} units
                                                </strong>
                                            </div>
                                        </div>
                                    </div>

                                    @if($prescription->medicine)
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-sm {{ $prescription->medicine->StockQuantity >= $prescription->getRemainingQuantity() ? 'alert-success' : 'alert-danger' }}">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <i class="fas fa-box me-1"></i>
                                                        Available Stock:
                                                        <strong>{{ $prescription->medicine->StockQuantity }}</strong> units
                                                    </div>
                                                    <div>
                                                        Remaining:
                                                        <strong>{{ $prescription->getRemainingQuantity() }}</strong> units
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Instructions -->
                                    @if($prescription->Instructions)
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <small class="text-muted">Instructions:</small>
                                            <p class="mb-0">{{ $prescription->Instructions }}</p>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Action Buttons -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="d-flex justify-content-between">
                                                <button type="button" class="btn btn-sm btn-info"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#viewPrescriptionModal{{ $prescription->PrescriptionID }}">
                                                    <i class="fas fa-eye"></i> View
                                                </button>

                                                @if($prescription->canBeDispensed())
                                                <button type="button" class="btn btn-sm btn-success"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#dispenseModal{{ $prescription->PrescriptionID }}">
                                                    <i class="fas fa-hand-holding-medical"></i> Dispense
                                                </button>
                                                @elseif($prescription->Status == 'Partially_Dispensed')
                                                <button type="button" class="btn btn-sm btn-warning"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#dispenseModal{{ $prescription->PrescriptionID }}">
                                                    <i class="fas fa-plus-circle"></i> Add More
                                                </button>
                                                @elseif($prescription->Status == 'Pending')
                                                <button type="button" class="btn btn-sm btn-secondary" disabled>
                                                    <i class="fas fa-times-circle"></i> Out of Stock
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- View Prescription Modal -->
                        <div class="modal fade" id="viewPrescriptionModal{{ $prescription->PrescriptionID }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title">Prescription Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body prescription-details">
                                        <div class="row mb-4">
                                            <div class="col-md-12">
                                                <h6 class="border-bottom pb-2">Patient Information</h6>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <strong>Name:</strong> {{ $prescription->patient->Name ?? 'N/A' }}<br>
                                                        <strong>Age:</strong> {{ $prescription->patient->Age ?? 'N/A' }}<br>
                                                        <strong>Gender:</strong> {{ $prescription->patient->Gender ?? 'N/A' }}
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Contact:</strong> {{ $prescription->patient->Phone ?? 'N/A' }}<br>
                                                        <strong>Blood Group:</strong> {{ $prescription->patient->BloodGroup ?? 'N/A' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-12">
                                                <h6 class="border-bottom pb-2">Prescription Details</h6>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <strong>Medicine:</strong> {{ $prescription->MedicineName }}<br>
                                                        <strong>Dosage:</strong> {{ $prescription->Dosage }}<br>
                                                        <strong>Frequency:</strong> {{ $prescription->Frequency }}
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Duration:</strong> {{ $prescription->Duration }}<br>
                                                        <strong>Prescribed Date:</strong> {{ $prescription->IssueDate->format('M d, Y') }}<br>
                                                        <strong>Quantity:</strong> {{ $prescription->QuantityPrescribed }} units
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-12">
                                                <h6 class="border-bottom pb-2">Doctor Information</h6>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <strong>Doctor:</strong> Dr. {{ $prescription->doctor->Name ?? 'N/A' }}<br>
                                                        <strong>Specialization:</strong> {{ $prescription->doctor->Specialization ?? 'N/A' }}
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Appointment Date:</strong>
                                                        {{ $prescription->appointment->AppointmentDate ?? 'N/A' }}<br>
                                                        <strong>Diagnosis:</strong>
                                                        {{ $prescription->appointment->Diagnosis ?? 'Not specified' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if($prescription->Instructions)
                                        <div class="row mb-4">
                                            <div class="col-md-12">
                                                <h6 class="border-bottom pb-2">Instructions</h6>
                                                <p>{{ $prescription->Instructions }}</p>
                                            </div>
                                        </div>
                                        @endif

                                        @if($prescription->Notes)
                                        <div class="row mb-4">
                                            <div class="col-md-12">
                                                <h6 class="border-bottom pb-2">Doctor's Notes</h6>
                                                <p>{{ $prescription->Notes }}</p>
                                            </div>
                                        </div>
                                        @endif

                                        <!-- Dispensing History -->
                                        @if($prescription->dispensings->count() > 0)
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h6 class="border-bottom pb-2">Dispensing History</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Quantity</th>
                                                                <th>Price</th>
                                                                <th>Total</th>
                                                                <th>Dispensed By</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($prescription->dispensings as $dispensing)
                                                            <tr>
                                                                <td>{{ $dispensing->DispensedAt->format('M d, Y H:i') }}</td>
                                                                <td>{{ $dispensing->QuantityDispensed }}</td>
                                                                <td>৳{{ number_format($dispensing->UnitPrice, 2) }}</td>
                                                                <td>৳{{ number_format($dispensing->TotalAmount, 2) }}</td>
                                                                <td>{{ $dispensing->dispenser->name ?? 'N/A' }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr class="table-info">
                                                                <td colspan="2"><strong>Total</strong></td>
                                                                <td colspan="3">
                                                                    <strong>{{ $prescription->QuantityDispensed }} units dispensed</strong> |
                                                                    <strong>৳{{ number_format($prescription->dispensings->sum('TotalAmount'), 2) }}</strong>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" onclick="printPrescription({{ $prescription->PrescriptionID }})">
                                            <i class="fas fa-print"></i> Print
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dispense Modal -->
                        <div class="modal fade" id="dispenseModal{{ $prescription->PrescriptionID }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title">Dispense Medicine</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('pharmacy.dispense') }}" method="POST"
                                          id="dispenseForm{{ $prescription->PrescriptionID }}">
                                        @csrf
                                        <input type="hidden" name="PrescriptionID" value="{{ $prescription->PrescriptionID }}">

                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Patient</label>
                                                <input type="text" class="form-control"
                                                       value="{{ $prescription->patient->Name }}" readonly>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Medicine</label>
                                                <input type="text" class="form-control"
                                                       value="{{ $prescription->MedicineName }}" readonly>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Prescribed</label>
                                                    <input type="text" class="form-control"
                                                           value="{{ $prescription->QuantityPrescribed }} units" readonly>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Already Dispensed</label>
                                                    <input type="text" class="form-control"
                                                           value="{{ $prescription->QuantityDispensed }} units" readonly>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Remaining to Dispense</label>
                                                <input type="text" class="form-control"
                                                       value="{{ $prescription->getRemainingQuantity() }} units" readonly>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Available Stock</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control"
                                                           value="{{ $prescription->medicine->StockQuantity ?? 0 }} units" readonly>
                                                    <span class="input-group-text {{ $prescription->medicine && $prescription->medicine->isLowStock() ? 'bg-warning' : 'bg-success text-white' }}">
                                                        @if($prescription->medicine)
                                                            {{ $prescription->medicine->getStockStatus() }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Quantity to Dispense *</label>
                                                <input type="number" class="form-control quantity-input"
                                                       name="QuantityDispensed"
                                                       min="1"
                                                       max="{{ min($prescription->getRemainingQuantity(), $prescription->medicine->StockQuantity ?? 0) }}"
                                                       value="{{ min($prescription->getRemainingQuantity(), $prescription->medicine->StockQuantity ?? 0) }}"
                                                       required>
                                                <small class="form-text text-muted">
                                                    Max: {{ min($prescription->getRemainingQuantity(), $prescription->medicine->StockQuantity ?? 0) }} units
                                                </small>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Unit Price</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">৳</span>
                                                    <input type="number" class="form-control"
                                                           value="{{ $prescription->medicine->UnitPrice ?? 0 }}"
                                                           step="0.01" readonly>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Total Amount</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">৳</span>
                                                    <input type="text" class="form-control" id="totalAmount{{ $prescription->PrescriptionID }}"
                                                           value="0.00" readonly>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Notes</label>
                                                <textarea class="form-control" name="Notes" rows="2"
                                                          placeholder="Any special instructions or notes..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check-circle"></i> Confirm Dispensing
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-md-12">
                            <div class="text-center py-5">
                                <i class="fas fa-prescription fa-4x text-muted mb-3"></i>
                                <h4>No prescriptions found</h4>
                                <p class="text-muted">No prescriptions match your search criteria.</p>
                            </div>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($prescriptions->hasPages())
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-center">
                                {{ $prescriptions->links() }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Calculate total amount when quantity changes
        $('.quantity-input').on('input', function() {
            let quantity = $(this).val();
            let prescriptionId = $(this).closest('form').find('input[name="PrescriptionID"]').val();
            let unitPrice = $(this).closest('.modal-body').find('input[readonly]').val();

            let totalAmount = quantity * unitPrice;
            $('#totalAmount' + prescriptionId).val(totalAmount.toFixed(2));
        });

        // Initialize total amount on modal show
        $('[id^="dispenseModal"]').on('shown.bs.modal', function() {
            let quantityInput = $(this).find('.quantity-input');
            let prescriptionId = $(this).find('input[name="PrescriptionID"]').val();
            let quantity = quantityInput.val();
            let unitPrice = $(this).find('input[readonly]').val();

            let totalAmount = quantity * unitPrice;
            $('#totalAmount' + prescriptionId).val(totalAmount.toFixed(2));
        });

        // Form validation
        $('[id^="dispenseForm"]').validate({
            rules: {
                QuantityDispensed: {
                    required: true,
                    min: 1,
                    max: function() {
                        let form = $(this).closest('form');
                        let maxQty = form.find('.quantity-input').attr('max');
                        return parseInt(maxQty);
                    }
                }
            },
            messages: {
                QuantityDispensed: {
                    max: "Quantity cannot exceed available stock or remaining prescribed amount"
                }
            },
            errorPlacement: function(error, element) {
                error.addClass('text-danger small');
                error.insertAfter(element);
            }
        });

        // Print prescription
        window.printPrescription = function(prescriptionId) {
            window.open('/pharmacy/prescription/' + prescriptionId + '/print', '_blank');
        };

        // Auto-refresh for pending prescriptions
        @if(request('status') == 'pending' || !request('status'))
        setTimeout(function() {
            location.reload();
        }, 300000); // Refresh every 5 minutes
        @endif
    });
</script>
@endsection
