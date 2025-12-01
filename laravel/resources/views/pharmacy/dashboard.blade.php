@extends('layouts.app')

@section('title', 'Pharmacy Dashboard')

@section('content')
<div class="flex h-screen bg-gray-50">
    <!-- Sidebar -->
    <div class="w-64 bg-indigo-900 text-white shadow-lg">
        <div class="p-6 border-b border-indigo-800">
            <h1 class="text-2xl font-bold text-indigo-300 flex items-center">
                <i class="fas fa-heartbeat mr-2"></i> SmartHealth
            </h1>
            <p class="text-sm text-indigo-200 mt-1">Pharmacy Portal</p>
        </div>
        <nav class="mt-6">
            <a href="{{ route('pharmacy.dashboard') }}"
               class="flex items-center py-3 px-6 bg-indigo-800 text-indigo-200 border-r-4 border-indigo-400">
                <i class="fas fa-prescription-bottle-alt mr-3"></i> Dispensing
            </a>
            <a href="{{ route('pharmacy.inventory') }}" class="flex items-center py-3 px-6 text-indigo-200 hover:bg-indigo-800 hover:text-white transition">
                <i class="fas fa-pills mr-3"></i> Inventory
            </a>
            <a href="{{ route('pharmacy.history') }}" class="flex items-center py-3 px-6 text-indigo-200 hover:bg-indigo-800 hover:text-white transition">
                <i class="fas fa-history mr-3"></i> Dispensing History
            </a>
            <a href="#" class="flex items-center py-3 px-6 text-indigo-200 hover:bg-indigo-800 hover:text-white transition">
                <i class="fas fa-chart-bar mr-3"></i> Reports
            </a>
            <a href="#" class="flex items-center py-3 px-6 text-indigo-200 hover:bg-indigo-800 hover:text-white transition">
                <i class="fas fa-cog mr-3"></i> Settings
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto">
        <div class="p-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Pharmacy Dashboard</h2>
                    <p class="text-gray-600">Manage prescriptions and medicine inventory</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="font-medium">{{ auth()->user()->name }}</p>
                        <p class="text-sm text-gray-500">Pharmacist</p>
                    </div>
                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-pills text-indigo-600"></i>
                    </div>
                </div>
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <p class="text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                        <p class="text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Inventory Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Total Medicines</h3>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $inventoryStats['total_medicines'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-pills text-blue-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Low Stock</h3>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $inventoryStats['low_stock'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Out of Stock</h3>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $inventoryStats['out_of_stock'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-times-circle text-red-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-orange-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Expiring Soon</h3>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $inventoryStats['expiring_soon'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-orange-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alerts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Low Stock Alerts -->
                @if($lowStockMedicines->count() > 0)
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-xl shadow-sm">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-xl mr-3"></i>
                        <h3 class="text-lg font-bold text-yellow-800">Low Stock Alerts</h3>
                    </div>
                    <div class="space-y-3">
                        @foreach($lowStockMedicines as $medicine)
                        <div class="flex justify-between items-center p-3 bg-yellow-100 rounded-lg">
                            <div>
                                <p class="font-medium text-yellow-900">{{ $medicine->Name }}</p>
                                <p class="text-sm text-yellow-700">{{ $medicine->category->CategoryName }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-yellow-900">{{ $medicine->StockQuantity }} left</p>
                                <p class="text-xs text-yellow-700">Reorder: {{ $medicine->ReorderLevel }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Expiring Soon Alerts -->
                @if($expiringMedicines->count() > 0)
                <div class="bg-orange-50 border-l-4 border-orange-500 p-6 rounded-xl shadow-sm">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-clock text-orange-500 text-xl mr-3"></i>
                        <h3 class="text-lg font-bold text-orange-800">Expiring Soon</h3>
                    </div>
                    <div class="space-y-3">
                        @foreach($expiringMedicines as $medicine)
                        <div class="flex justify-between items-center p-3 bg-orange-100 rounded-lg">
                            <div>
                                <p class="font-medium text-orange-900">{{ $medicine->Name }}</p>
                                <p class="text-sm text-orange-700">{{ $medicine->category->CategoryName }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-orange-900">
                                    {{ $medicine->ExpiryDate->diffForHumans() }}
                                </p>
                                <p class="text-xs text-orange-700">
                                    {{ $medicine->ExpiryDate->format('M j, Y') }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Pending Prescriptions -->
                <div class="lg:col-span-2">
                    <div class="bg-white p-6 rounded-xl shadow-sm">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-bold text-gray-800">Pending Prescriptions</h3>
                            <form method="GET" class="flex gap-2">
                                <input type="text" name="search" placeholder="Search patients or medicines..."
                                       value="{{ request('search') }}"
                                       class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition font-medium text-sm">
                                    <i class="fas fa-search mr-2"></i> Search
                                </button>
                            </form>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medicine</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($pendingPrescriptions as $prescription)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $prescription->appointment->patient->Name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                Age: {{ $prescription->appointment->patient->Age }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-indigo-600">
                                                {{ $prescription->MedicineName }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $prescription->Dosage }} • {{ $prescription->Frequency }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                {{ $prescription->Duration }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            Dr. {{ $prescription->appointment->doctor->Name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($prescription->canBeDispensed())
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Ready
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Check Stock
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button onclick="openDispensingModal('{{ $prescription->PrescriptionID }}')"
                                                    class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-lg transition flex items-center text-sm"
                                                    {{ !$prescription->canBeDispensed() ? 'disabled' : '' }}>
                                                <i class="fas fa-prescription-bottle-alt mr-1"></i> Dispense
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            <i class="fas fa-prescription-bottle-alt text-4xl text-gray-300 mb-2"></i>
                                            <p>No pending prescriptions found.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($pendingPrescriptions->hasPages())
                        <div class="mt-6">
                            {{ $pendingPrescriptions->links() }}
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Activity -->
                <div>
                    <div class="bg-white p-6 rounded-xl shadow-sm mb-6">
                        <h3 class="text-lg font-bold mb-4 text-gray-800">Recent Dispensings</h3>
                        <div class="space-y-4">
                            @forelse($recentDispensings as $dispensing)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-pills text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">
                                            {{ $dispensing->medicine->Name }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $dispensing->prescription->appointment->patient->Name }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-gray-900">
                                        {{ $dispensing->QuantityDispensed }} units
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $dispensing->DispensedAt->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-4 text-gray-500">
                                <i class="fas fa-history text-3xl text-gray-300 mb-2"></i>
                                <p class="text-sm">No recent dispensings</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white p-6 rounded-xl shadow-sm">
                        <h3 class="text-lg font-bold mb-4 text-gray-800">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="{{ route('pharmacy.inventory') }}"
                               class="flex items-center p-3 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition">
                                <i class="fas fa-pills mr-3"></i>
                                <span>Manage Inventory</span>
                            </a>
                            <a href="{{ route('pharmacy.history') }}"
                               class="flex items-center p-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition">
                                <i class="fas fa-history mr-3"></i>
                                <span>View History</span>
                            </a>
                            <button onclick="openAddMedicineModal()"
                                    class="w-full flex items-center p-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition">
                                <i class="fas fa-plus-circle mr-3"></i>
                                <span>Add New Medicine</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dispensing Modal -->
<div id="dispensingModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Dispense Medicine</h3>
                <button onclick="closeDispensingModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <div id="prescriptionDetails" class="mb-4 p-4 bg-gray-50 rounded-lg">
                <!-- Details will be loaded via AJAX -->
            </div>

            <form id="dispensingForm" method="POST" action="{{ route('pharmacy.dispense') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="PrescriptionID" id="modalPrescriptionId">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity to Dispense *</label>
                    <input type="number" name="QuantityDispensed" id="modalQuantity"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           min="1" required>
                    <p class="text-xs text-gray-500 mt-1" id="quantityHelp"></p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="Notes" rows="3" placeholder="Additional dispensing notes..."
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button type="button" onclick="closeDispensingModal()"
                            class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition font-medium">
                        Cancel
                    </button>
                    <button type="submit"
                            class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition font-medium flex items-center">
                        <i class="fas fa-check mr-2"></i> Dispense
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openDispensingModal(prescriptionId) {
        // Fetch prescription details via AJAX
        fetch(`/pharmacy/prescription/${prescriptionId}/details`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const prescription = data.prescription;
                    const patient = data.patient;
                    const medicine = data.medicine;

                    // Update modal content
                    document.getElementById('modalPrescriptionId').value = prescriptionId;
                    document.getElementById('modalQuantity').value = data.remaining_quantity;
                    document.getElementById('modalQuantity').max = data.remaining_quantity;

                    document.getElementById('quantityHelp').textContent =
                        `Maximum: ${data.remaining_quantity} units (${prescription.QuantityPrescribed} prescribed, ${prescription.QuantityDispensed} already dispensed)`;

                    document.getElementById('prescriptionDetails').innerHTML = `
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="font-medium">Patient:</span>
                                <span>${patient.Name}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Medicine:</span>
                                <span>${prescription.MedicineName}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Dosage:</span>
                                <span>${prescription.Dosage} • ${prescription.Frequency}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Duration:</span>
                                <span>${prescription.Duration}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Available Stock:</span>
                                <span class="${medicine.StockQuantity < data.remaining_quantity ? 'text-red-600 font-bold' : 'text-green-600'}">
                                    ${medicine.StockQuantity} units
                                </span>
                            </div>
                        </div>
                    `;

                    document.getElementById('dispensingModal').classList.remove('hidden');
                } else {
                    alert('Failed to load prescription details.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to load prescription details.');
            });
    }

    function closeDispensingModal() {
        document.getElementById('dispensingModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('dispensingModal');
        if (event.target === modal) {
            closeDispensingModal();
        }
    }

    // Handle form submission
    document.getElementById('dispensingForm').addEventListener('submit', function(e) {
        const quantity = parseInt(document.getElementById('modalQuantity').value);
        const maxQuantity = parseInt(document.getElementById('modalQuantity').max);

        if (quantity > maxQuantity) {
            e.preventDefault();
            alert(`Cannot dispense more than ${maxQuantity} units.`);
        }
    });
</script>
@endsection
