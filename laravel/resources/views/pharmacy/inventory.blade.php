@extends('layouts.app')

@section('title', 'Medicine Inventory')

@section('styles')
<style>
    .stock-low { background-color: #fff3cd; }
    .stock-out { background-color: #f8d7da; }
    .stock-expiring { background-color: #cce5ff; }
    .stock-good { background-color: #d1e7dd; }

    .medicine-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 5px;
    }

    .status-badge {
        font-size: 0.75rem;
        padding: 2px 8px;
        border-radius: 12px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Medicine Inventory</h2>
                    <p class="text-muted mb-0">Manage all medicines in stock</p>
                </div>
                <div>
                    <a href="{{ route('pharmacy.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMedicineModal">
                        <i class="fas fa-plus"></i> Add New Medicine
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Medicines</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMedicines ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-pills fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Low Stock</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lowStockCount ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Out of Stock</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $outOfStockCount ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Expiring Soon</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $expiringSoonCount ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('pharmacy.inventory') }}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search by name, SKU or generic name..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->CategoryID }}" {{ request('category') == $category->CategoryID ? 'selected' : '' }}>
                                {{ $category->CategoryName }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Stock Status</label>
                        <select name="stock_status" class="form-select">
                            <option value="">All</option>
                            <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low Stock</option>
                            <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                            <option value="expiring" {{ request('stock_status') == 'expiring' ? 'selected' : '' }}>Expiring Soon</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Filter
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Supplier</label>
                        <select name="supplier" class="form-select">
                            <option value="">All Suppliers</option>
                            @foreach($suppliers ?? [] as $supplier)
                            <option value="{{ $supplier->SupplierID }}" {{ request('supplier') == $supplier->SupplierID ? 'selected' : '' }}>
                                {{ $supplier->CompanyName }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Prescription Required</label>
                        <select name="requires_prescription" class="form-select">
                            <option value="">All</option>
                            <option value="1" {{ request('requires_prescription') == '1' ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ request('requires_prescription') == '0' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Sort By</label>
                        <select name="sort" class="form-select">
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                            <option value="stock_asc" {{ request('sort') == 'stock_asc' ? 'selected' : '' }}>Stock (Low to High)</option>
                            <option value="stock_desc" {{ request('sort') == 'stock_desc' ? 'selected' : '' }}>Stock (High to Low)</option>
                            <option value="expiry" {{ request('sort') == 'expiry' ? 'selected' : '' }}>Expiry Date</option>
                            <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Unit Price</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <a href="{{ route('pharmacy.inventory') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Clear
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Medicines Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Stock</th>
                            <th>Unit Price</th>
                            <th>Expiry Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medicines as $medicine)
                        @php
                            $stockClass = '';
                            if($medicine->StockQuantity == 0) {
                                $stockClass = 'stock-out';
                            } elseif($medicine->StockQuantity <= $medicine->ReorderLevel) {
                                $stockClass = 'stock-low';
                            } elseif($medicine->isExpiringSoon()) {
                                $stockClass = 'stock-expiring';
                            } elseif($medicine->StockQuantity > $medicine->ReorderLevel) {
                                $stockClass = 'stock-good';
                            }
                        @endphp
                        <tr class="{{ $stockClass }}">
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <div class="bg-light rounded p-2 text-center" style="width: 50px; height: 50px;">
                                            <i class="fas fa-pills fa-lg text-primary"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <strong>{{ $medicine->Name }}</strong>
                                        @if($medicine->GenericName)
                                        <div class="text-muted small">{{ $medicine->GenericName }}</div>
                                        @endif
                                        @if($medicine->BrandName)
                                        <div class="text-muted small">{{ $medicine->BrandName }}</div>
                                        @endif
                                        @if($medicine->RequiresPrescription)
                                        <span class="badge bg-danger status-badge">Rx</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <code>{{ $medicine->SKU }}</code>
                                <div class="small text-muted">{{ $medicine->DosageForm }}</div>
                                @if($medicine->Strength)
                                <div class="small text-muted">{{ $medicine->Strength }}</div>
                                @endif
                            </td>
                            <td>{{ $medicine->category->CategoryName ?? 'N/A' }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <strong>{{ $medicine->StockQuantity }}</strong>
                                    </div>
                                    <div class="small text-muted">
                                        @if($medicine->isLowStock())
                                        <i class="fas fa-exclamation-triangle text-warning"></i> Low stock
                                        @elseif($medicine->StockQuantity == 0)
                                        <i class="fas fa-times-circle text-danger"></i> Out of stock
                                        @else
                                        <i class="fas fa-check-circle text-success"></i> In stock
                                        @endif
                                    </div>
                                </div>
                                <div class="progress" style="height: 5px;">
                                    @php
                                        $percentage = min(100, ($medicine->StockQuantity / ($medicine->ReorderLevel * 3)) * 100);
                                        $color = 'bg-success';
                                        if($medicine->StockQuantity <= $medicine->ReorderLevel) {
                                            $color = 'bg-warning';
                                        }
                                        if($medicine->StockQuantity == 0) {
                                            $color = 'bg-danger';
                                        }
                                    @endphp
                                    <div class="progress-bar {{ $color }}" style="width: {{ $percentage }}%"></div>
                                </div>
                                <div class="small text-muted">Reorder at: {{ $medicine->ReorderLevel }}</div>
                            </td>
                            <td>
                                <div class="fw-bold">৳{{ number_format($medicine->UnitPrice, 2) }}</div>
                                <div class="small text-muted">Cost: ৳{{ number_format($medicine->CostPrice, 2) }}</div>
                            </td>
                            <td>
                                <div>{{ $medicine->ExpiryDate->format('M d, Y') }}</div>
                                <div class="small {{ $medicine->isExpiringSoon() ? 'text-warning' : 'text-muted' }}">
                                    {{ $medicine->ExpiryDate->diffForHumans() }}
                                </div>
                            </td>
                            <td>
                                @if($medicine->isExpired())
                                <span class="badge bg-danger">Expired</span>
                                @elseif($medicine->StockQuantity == 0)
                                <span class="badge bg-danger">Out of Stock</span>
                                @elseif($medicine->isLowStock())
                                <span class="badge bg-warning">Low Stock</span>
                                @elseif($medicine->isExpiringSoon())
                                <span class="badge bg-info">Expiring Soon</span>
                                @else
                                <span class="badge bg-success">Available</span>
                                @endif

                                @if($medicine->IsActive)
                                <span class="badge bg-success ms-1">Active</span>
                                @else
                                <span class="badge bg-secondary ms-1">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal" data-bs-target="#editMedicineModal{{ $medicine->MedicineID }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-info"
                                            data-bs-toggle="modal" data-bs-target="#stockModal{{ $medicine->MedicineID }}">
                                        <i class="fas fa-boxes"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-success"
                                            onclick="showMedicineDetails({{ $medicine->MedicineID }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Edit Medicine Modal -->
                        <div class="modal fade" id="editMedicineModal{{ $medicine->MedicineID }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form action="{{ route('pharmacy.medicine.update', $medicine->MedicineID) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Medicine: {{ $medicine->Name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Medicine Name *</label>
                                                    <input type="text" name="Name" class="form-control"
                                                           value="{{ $medicine->Name }}" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Generic Name</label>
                                                    <input type="text" name="GenericName" class="form-control"
                                                           value="{{ $medicine->GenericName }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Brand Name</label>
                                                    <input type="text" name="BrandName" class="form-control"
                                                           value="{{ $medicine->BrandName }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">SKU *</label>
                                                    <input type="text" name="SKU" class="form-control"
                                                           value="{{ $medicine->SKU }}" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Category *</label>
                                                    <select name="CategoryID" class="form-select" required>
                                                        @foreach($categories as $category)
                                                        <option value="{{ $category->CategoryID }}"
                                                            {{ $medicine->CategoryID == $category->CategoryID ? 'selected' : '' }}>
                                                            {{ $category->CategoryName }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Supplier</label>
                                                    <select name="SupplierID" class="form-select">
                                                        <option value="">Select Supplier</option>
                                                        @foreach($suppliers ?? [] as $supplier)
                                                        <option value="{{ $supplier->SupplierID }}"
                                                            {{ $medicine->SupplierID == $supplier->SupplierID ? 'selected' : '' }}>
                                                            {{ $supplier->CompanyName }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Dosage Form *</label>
                                                    <input type="text" name="DosageForm" class="form-control"
                                                           value="{{ $medicine->DosageForm }}" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Strength</label>
                                                    <input type="text" name="Strength" class="form-control"
                                                           value="{{ $medicine->Strength }}">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Stock Quantity *</label>
                                                    <input type="number" name="StockQuantity" class="form-control"
                                                           value="{{ $medicine->StockQuantity }}" required min="0">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Reorder Level *</label>
                                                    <input type="number" name="ReorderLevel" class="form-control"
                                                           value="{{ $medicine->ReorderLevel }}" required min="0">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Unit Price *</label>
                                                    <input type="number" step="0.01" name="UnitPrice" class="form-control"
                                                           value="{{ $medicine->UnitPrice }}" required min="0">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Cost Price *</label>
                                                    <input type="number" step="0.01" name="CostPrice" class="form-control"
                                                           value="{{ $medicine->CostPrice }}" required min="0">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Expiry Date *</label>
                                                    <input type="date" name="ExpiryDate" class="form-control"
                                                           value="{{ $medicine->ExpiryDate->format('Y-m-d') }}" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Manufacturer</label>
                                                    <input type="text" name="Manufacturer" class="form-control"
                                                           value="{{ $medicine->Manufacturer }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Storage Conditions</label>
                                                    <input type="text" name="StorageConditions" class="form-control"
                                                           value="{{ $medicine->StorageConditions }}">
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">Description</label>
                                                    <textarea name="Description" class="form-control" rows="2">{{ $medicine->Description }}</textarea>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="form-check">
                                                        <input type="checkbox" name="RequiresPrescription" class="form-check-input"
                                                               value="1" {{ $medicine->RequiresPrescription ? 'checked' : '' }}>
                                                        <label class="form-check-label">Requires Prescription</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="form-check">
                                                        <input type="checkbox" name="IsActive" class="form-check-input"
                                                               value="1" {{ $medicine->IsActive ? 'checked' : '' }}>
                                                        <label class="form-check-label">Active</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Update Medicine</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Stock Adjustment Modal -->
                        <div class="modal fade" id="stockModal{{ $medicine->MedicineID }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('pharmacy.medicine.stock.update', $medicine->MedicineID) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Adjust Stock: {{ $medicine->Name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Current Stock</label>
                                                <input type="text" class="form-control" value="{{ $medicine->StockQuantity }}" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Adjustment Type</label>
                                                <select name="adjustment_type" class="form-select" required>
                                                    <option value="add">Add Stock</option>
                                                    <option value="subtract">Remove Stock</option>
                                                    <option value="set">Set to Specific Value</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Quantity *</label>
                                                <input type="number" name="quantity" class="form-control" required min="1">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Reason *</label>
                                                <select name="reason" class="form-select" required>
                                                    <option value="">Select Reason</option>
                                                    <option value="purchase">New Purchase</option>
                                                    <option value="return">Return to Supplier</option>
                                                    <option value="damage">Damaged/Expired</option>
                                                    <option value="adjustment">Stock Adjustment</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Notes</label>
                                                <textarea name="notes" class="form-control" rows="2"></textarea>
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
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No medicines found</h5>
                                <p class="text-muted">Add your first medicine to start managing inventory</p>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMedicineModal">
                                    <i class="fas fa-plus"></i> Add Medicine
                                </button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($medicines->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $medicines->firstItem() }} to {{ $medicines->lastItem() }} of {{ $medicines->total() }} medicines
                </div>
                <div>
                    {{ $medicines->withQueryString()->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Medicine Modal -->
<div class="modal fade" id="addMedicineModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('pharmacy.medicine.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Medicine</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Medicine Name *</label>
                            <input type="text" name="Name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Generic Name</label>
                            <input type="text" name="GenericName" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Brand Name</label>
                            <input type="text" name="BrandName" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">SKU *</label>
                            <input type="text" name="SKU" class="form-control" required>
                            <div class="form-text">Stock Keeping Unit (Unique identifier)</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category *</label>
                            <select name="CategoryID" class="form-select" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->CategoryID }}">{{ $category->CategoryName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Supplier</label>
                            <select name="SupplierID" class="form-select">
                                <option value="">Select Supplier</option>
                                @foreach($suppliers ?? [] as $supplier)
                                <option value="{{ $supplier->SupplierID }}">{{ $supplier->CompanyName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Dosage Form *</label>
                            <select name="DosageForm" class="form-select" required>
                                <option value="">Select Form</option>
                                <option value="Tablet">Tablet</option>
                                <option value="Capsule">Capsule</option>
                                <option value="Syrup">Syrup</option>
                                <option value="Injection">Injection</option>
                                <option value="Cream">Cream</option>
                                <option value="Ointment">Ointment</option>
                                <option value="Drops">Drops</option>
                                <option value="Inhaler">Inhaler</option>
                                <option value="Powder">Powder</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Strength</label>
                            <input type="text" name="Strength" class="form-control" placeholder="e.g., 500mg, 10mg/ml">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Initial Stock *</label>
                            <input type="number" name="StockQuantity" class="form-control" value="0" required min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Reorder Level *</label>
                            <input type="number" name="ReorderLevel" class="form-control" value="10" required min="0">
                            <div class="form-text">Alert when stock reaches this level</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Unit Price *</label>
                            <input type="number" step="0.01" name="UnitPrice" class="form-control" required min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cost Price *</label>
                            <input type="number" step="0.01" name="CostPrice" class="form-control" required min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Expiry Date *</label>
                            <input type="date" name="ExpiryDate" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Manufacturer</label>
                            <input type="text" name="Manufacturer" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Storage Conditions</label>
                            <input type="text" name="StorageConditions" class="form-control" placeholder="e.g., Room Temperature, Refrigerate">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="Description" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="RequiresPrescription" class="form-check-input" value="1">
                                <label class="form-check-label">Requires Prescription</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="IsActive" class="form-check-input" value="1" checked>
                                <label class="form-check-label">Active</label
