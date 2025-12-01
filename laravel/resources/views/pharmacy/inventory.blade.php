@extends('layouts.app')

@section('title', 'Medicine Inventory')

@section('styles')
<style>
    .stock-low { color: #dc3545; font-weight: bold; }
    .stock-normal { color: #28a745; }
    .stock-zero { color: #6c757d; }
    .expired { color: #dc3545; text-decoration: line-through; }
    .expiring-soon { color: #ffc107; }
    .medicine-card { transition: all 0.3s; }
    .medicine-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    .badge-prescription { background-color: #6f42c1; }
    .stock-badge { font-size: 0.75rem; }
    .modal-xl { max-width: 90%; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-pills"></i> Medicine Inventory
                        <span class="badge bg-info">{{ $medicines->total() }} Medicines</span>
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Filters and Search -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('pharmacy.inventory') }}" class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control"
                                           placeholder="Search by name, generic, SKU..."
                                           value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <select name="category" class="form-select">
                                        <option value="all">All Categories</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->CategoryID }}"
                                                {{ request('category') == $category->CategoryID ? 'selected' : '' }}>
                                            {{ $category->CategoryName }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="stock_status" class="form-select">
                                        <option value="all">All Stock Status</option>
                                        <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low Stock</option>
                                        <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                                        <option value="expiring" {{ request('stock_status') == 'expiring' ? 'selected' : '' }}>Expiring Soon</option>
                                        <option value="expired" {{ request('stock_status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="dosage_form" class="form-select">
                                        <option value="all">All Forms</option>
                                        <option value="tablet" {{ request('dosage_form') == 'tablet' ? 'selected' : '' }}>Tablet</option>
                                        <option value="capsule" {{ request('dosage_form') == 'capsule' ? 'selected' : '' }}>Capsule</option>
                                        <option value="syrup" {{ request('dosage_form') == 'syrup' ? 'selected' : '' }}>Syrup</option>
                                        <option value="injection" {{ request('dosage_form') == 'injection' ? 'selected' : '' }}>Injection</option>
                                        <option value="ointment" {{ request('stock_status') == 'ointment' ? 'selected' : '' }}>Ointment</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Filter
                                    </button>
                                    <a href="{{ route('pharmacy.inventory') }}" class="btn btn-secondary">
                                        <i class="fas fa-redo"></i> Reset
                                    </a>
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMedicineModal">
                                        <i class="fas fa-plus"></i> Add Medicine
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title">Total Medicines</h6>
                                            <h2>{{ $totalMedicines }}</h2>
                                        </div>
                                        <i class="fas fa-pills fa-3x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title">Low Stock</h6>
                                            <h2>{{ $lowStockCount }}</h2>
                                        </div>
                                        <i class="fas fa-exclamation-triangle fa-3x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title">Out of Stock</h6>
                                            <h2>{{ $outOfStockCount }}</h2>
                                        </div>
                                        <i class="fas fa-times-circle fa-3x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title">Total Value</h6>
                                            <h2>৳{{ number_format($totalValue, 2) }}</h2>
                                        </div>
                                        <i class="fas fa-dollar-sign fa-3x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Medicine Name</th>
                                    <th>Category</th>
                                    <th>Stock</th>
                                    <th>Price</th>
                                    <th>Expiry Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($medicines as $medicine)
                                <tr class="medicine-card {{ $medicine->isExpired() ? 'table-danger' : '' }}
                                            {{ $medicine->isExpiringSoon() ? 'table-warning' : '' }}">
                                    <td>
                                        <span class="badge bg-secondary">{{ $medicine->SKU }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $medicine->Name }}</strong><br>
                                        <small class="text-muted">{{ $medicine->GenericName }}</small>
                                        @if($medicine->BrandName)
                                        <br><small class="text-info">{{ $medicine->BrandName }}</small>
                                        @endif
                                        @if($medicine->RequiresPrescription)
                                        <span class="badge badge-prescription">Rx</span>
                                        @endif
                                    </td>
                                    <td>{{ $medicine->category->CategoryName ?? 'N/A' }}</td>
                                    <td>
                                        @if($medicine->StockQuantity == 0)
                                            <span class="badge bg-danger stock-badge">Out of Stock</span>
                                        @elseif($medicine->isLowStock())
                                            <span class="badge bg-warning stock-badge">Low Stock</span>
                                        @else
                                            <span class="badge bg-success stock-badge">In Stock</span>
                                        @endif
                                        <br>
                                        <strong>{{ $medicine->StockQuantity }}</strong> units
                                        <br>
                                        <small>Reorder at: {{ $medicine->ReorderLevel }}</small>
                                    </td>
                                    <td>
                                        <strong>৳{{ number_format($medicine->UnitPrice, 2) }}</strong><br>
                                        <small class="text-muted">Cost: ৳{{ number_format($medicine->CostPrice, 2) }}</small>
                                    </td>
                                    <td>
                                        @if($medicine->isExpired())
                                            <span class="text-danger">
                                                <i class="fas fa-exclamation-circle"></i> Expired
                                            </span>
                                        @elseif($medicine->isExpiringSoon())
                                            <span class="text-warning">
                                                <i class="fas fa-clock"></i> {{ $medicine->ExpiryDate->diffForHumans() }}
                                            </span>
                                        @else
                                            {{ $medicine->ExpiryDate->format('d M, Y') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($medicine->IsActive)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-info"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewMedicineModal{{ $medicine->MedicineID }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-warning"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editMedicineModal{{ $medicine->MedicineID }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#stockAdjustmentModal{{ $medicine->MedicineID }}">
                                                <i class="fas fa-boxes"></i>
                                            </button>
                                            @if($medicine->StockQuantity == 0)
                                            <form action="{{ route('pharmacy.medicine.destroy', $medicine->MedicineID) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this medicine?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- View Medicine Modal -->
                                <div class="modal fade" id="viewMedicineModal{{ $medicine->MedicineID }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white">
                                                <h5 class="modal-title">Medicine Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="text-center mb-3">
                                                            <i class="fas fa-pills fa-4x text-primary"></i>
                                                        </div>
                                                        <div class="card">
                                                            <div class="card-body text-center">
                                                                <h6>Stock Status</h6>
                                                                <h3 class="mb-0">{{ $medicine->StockQuantity }}</h3>
                                                                <small>units available</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <table class="table table-sm">
                                                            <tr>
                                                                <th width="30%">Name:</th>
                                                                <td>{{ $medicine->Name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Generic Name:</th>
                                                                <td>{{ $medicine->GenericName ?? 'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Brand Name:</th>
                                                                <td>{{ $medicine->BrandName ?? 'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Category:</th>
                                                                <td>{{ $medicine->category->CategoryName ?? 'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Dosage Form:</th>
                                                                <td>{{ $medicine->DosageForm }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Strength:</th>
                                                                <td>{{ $medicine->Strength ?? 'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Unit Price:</th>
                                                                <td>৳{{ number_format($medicine->UnitPrice, 2) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Cost Price:</th>
                                                                <td>৳{{ number_format($medicine->CostPrice, 2) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Expiry Date:</th>
                                                                <td class="{{ $medicine->isExpired() ? 'text-danger' : '' }}">
                                                                    {{ $medicine->ExpiryDate->format('d M, Y') }}
                                                                    @if($medicine->isExpired())
                                                                    <span class="badge bg-danger">Expired</span>
                                                                    @elseif($medicine->isExpiringSoon())
                                                                    <span class="badge bg-warning">Expiring Soon</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Manufacturer:</th>
                                                                <td>{{ $medicine->Manufacturer ?? 'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Requires Prescription:</th>
                                                                <td>
                                                                    @if($medicine->RequiresPrescription)
                                                                    <span class="badge bg-purple">Yes</span>
                                                                    @else
                                                                    <span class="badge bg-secondary">No</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                                @if($medicine->Description)
                                                <div class="mt-3">
                                                    <h6>Description:</h6>
                                                    <p class="text-muted">{{ $medicine->Description }}</p>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Medicine Modal -->
                                <div class="modal fade" id="editMedicineModal{{ $medicine->MedicineID }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-warning text-white">
                                                <h5 class="modal-title">Edit Medicine</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('pharmacy.medicine.update', $medicine->MedicineID) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <!-- Edit form fields similar to add medicine form -->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Medicine Name *</label>
                                                                <input type="text" class="form-control" name="Name"
                                                                       value="{{ $medicine->Name }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Generic Name</label>
                                                                <input type="text" class="form-control" name="GenericName"
                                                                       value="{{ $medicine->GenericName }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Add more fields as needed -->
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-warning">Update Medicine</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Stock Adjustment Modal -->
                                <div class="modal fade" id="stockAdjustmentModal{{ $medicine->MedicineID }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">Adjust Stock - {{ $medicine->Name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('pharmacy.medicine.stock.update', $medicine->MedicineID) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Current Stock</label>
                                                        <input type="text" class="form-control"
                                                               value="{{ $medicine->StockQuantity }} units" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Adjustment Type</label>
                                                        <select class="form-select" name="adjustment_type" id="adjustmentType{{ $medicine->MedicineID }}" required>
                                                            <option value="add">Add Stock</option>
                                                            <option value="subtract">Remove Stock</option>
                                                            <option value="set">Set Stock Level</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Quantity *</label>
                                                        <input type="number" class="form-control" name="quantity"
                                                               min="1" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Reason *</label>
                                                        <select class="form-select" name="reason" required>
                                                            <option value="">Select Reason</option>
                                                            <option value="new_supply">New Supply</option>
                                                            <option value="damaged">Damaged Stock</option>
                                                            <option value="expired">Expired</option>
                                                            <option value="correction">Stock Correction</option>
                                                            <option value="other">Other</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Notes</label>
                                                        <textarea class="form-control" name="notes" rows="2"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update Stock</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <div class="py-5">
                                            <i class="fas fa-pills fa-4x text-muted mb-3"></i>
                                            <h4>No medicines found</h4>
                                            <p class="text-muted">No medicines match your search criteria.</p>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMedicineModal">
                                                <i class="fas fa-plus"></i> Add Your First Medicine
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            Showing {{ $medicines->firstItem() }} to {{ $medicines->lastItem() }} of {{ $medicines->total() }} medicines
                        </div>
                        <div>
                            {{ $medicines->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Medicine Modal -->
<div class="modal fade" id="addMedicineModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle"></i> Add New Medicine
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pharmacy.medicine.store') }}" method="POST" id="addMedicineForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Medicine Name *</label>
                                <input type="text" class="form-control" name="Name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Generic Name</label>
                                <input type="text" class="form-control" name="GenericName">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Brand Name</label>
                                <input type="text" class="form-control" name="BrandName">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">SKU *</label>
                                <input type="text" class="form-control" name="SKU" required>
                                <small class="form-text text-muted">Unique stock keeping unit</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Category *</label>
                                <select class="form-select" name="CategoryID" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->CategoryID }}">{{ $category->CategoryName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Supplier</label>
                                <select class="form-select" name="SupplierID">
                                    <option value="">Select Supplier</option>
                                    <!-- Add suppliers from database -->
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Dosage Form *</label>
                                <select class="form-select" name="DosageForm" required>
                                    <option value="">Select Form</option>
                                    <option value="Tablet">Tablet</option>
                                    <option value="Capsule">Capsule</option>
                                    <option value="Syrup">Syrup</option>
                                    <option value="Injection">Injection</option>
                                    <option value="Ointment">Ointment</option>
                                    <option value="Cream">Cream</option>
                                    <option value="Drops">Drops</option>
                                    <option value="Inhaler">Inhaler</option>
                                    <option value="Powder">Powder</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Strength</label>
                                <input type="text" class="form-control" name="Strength"
                                       placeholder="e.g., 500mg, 10mg/ml">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Manufacturer</label>
                                <input type="text" class="form-control" name="Manufacturer">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Stock Quantity *</label>
                                <input type="number" class="form-control" name="StockQuantity"
                                       min="0" value="0" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Reorder Level *</label>
                                <input type="number" class="form-control" name="ReorderLevel"
                                       min="1" value="10" required>
                                <small class="form-text text-muted">Alert when stock reaches this level</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Expiry Date *</label>
                                <input type="date" class="form-control" name="ExpiryDate"
                                       min="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Unit Price (Selling) *</label>
                                <div class="input-group">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" class="form-control" name="UnitPrice"
                                           step="0.01" min="0" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Cost Price *</label>
                                <div class="input-group">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" class="form-control" name="CostPrice"
                                           step="0.01" min="0" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="Description" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Storage Conditions</label>
                                <input type="text" class="form-control" name="StorageConditions"
                                       placeholder="e.g., Room temperature, Refrigerate">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox"
                                           name="RequiresPrescription" value="1" id="requiresPrescription">
                                    <label class="form-check-label" for="requiresPrescription">
                                        Requires Prescription
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Save Medicine
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Auto-generate SKU on name change
        $('input[name="Name"]').on('blur', function() {
            if (!$('input[name="SKU"]').val()) {
                let name = $(this).val();
                let sku = name.replace(/\s+/g, '-').toUpperCase().substring(0, 10) + '-' +
                          Math.floor(Math.random() * 1000);
                $('input[name="SKU"]').val(sku);
            }
        });

        // Calculate profit margin
        $('input[name="UnitPrice"], input[name="CostPrice"]').on('change', function() {
            let cost = parseFloat($('input[name="CostPrice"]').val()) || 0;
            let price = parseFloat($('input[name="UnitPrice"]').val()) || 0;

            if (cost > 0 && price > 0) {
                let margin = ((price - cost) / cost * 100).toFixed(2);
                let profit = price - cost;
                $('#profitInfo').remove();
                $('input[name="UnitPrice"]').parent().append(
                    `<small id="profitInfo" class="form-text ${margin > 0 ? 'text-success' : 'text-danger'}">
                        Profit: ৳${profit.toFixed(2)} (${margin}%)
                    </small>`
                );
            }
        });

        // Form validation
        $('#addMedicineForm').validate({
            rules: {
                Name: { required: true, minlength: 3 },
                SKU: { required: true },
                UnitPrice: { required: true, min: 0 },
                CostPrice: { required: true, min: 0 },
                ExpiryDate: {
                    required: true,
                    date: true,
                    min: "{{ date('Y-m-d') }}"
                }
            },
            messages: {
                ExpiryDate: {
                    min: "Expiry date must be in the future"
                }
            }
        });

        // Quick search
        $('#quickSearch').on('keyup', function() {
            let value = $(this).val().toLowerCase();
            $('table tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });
</script>
@endsection
