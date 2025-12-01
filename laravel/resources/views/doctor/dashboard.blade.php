@extends('layouts.app')

@section('title', 'Doctor Dashboard')

@section('content')
<div class="flex h-screen bg-gray-50">
    <!-- Sidebar -->
    <div class="w-64 bg-white shadow-lg">
        <div class="p-6 border-b">
            <h1 class="text-2xl font-bold text-green-600 flex items-center">
                <i class="fas fa-heartbeat mr-2"></i> SmartHealth
            </h1>
            <p class="text-sm text-gray-500 mt-1">Doctor Portal</p>
        </div>
        <nav class="mt-6">
            <a href="{{ route('doctor.dashboard') }}"
               class="flex items-center py-3 px-6 bg-green-50 text-green-700 border-r-4 border-green-700">
                <i class="fas fa-stethoscope mr-3"></i> Appointments
            </a>
            <a href="#" class="flex items-center py-3 px-6 text-gray-600 hover:bg-gray-50 hover:text-green-600 transition">
                <i class="fas fa-user-injured mr-3"></i> My Patients
            </a>
            <a href="#" class="flex items-center py-3 px-6 text-gray-600 hover:bg-gray-50 hover:text-green-600 transition">
                <i class="fas fa-prescription mr-3"></i> Prescriptions
            </a>
            <a href="#" class="flex items-center py-3 px-6 text-gray-600 hover:bg-gray-50 hover:text-green-600 transition">
                <i class="fas fa-calendar-alt mr-3"></i> Schedule
            </a>
            <a href="#" class="flex items-center py-3 px-6 text-gray-600 hover:bg-gray-50 hover:text-green-600 transition">
                <i class="fas fa-chart-line mr-3"></i> Analytics
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto">
        <div class="p-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Welcome, Dr. {{ $doctor->Name }}</h2>
                    <p class="text-gray-600">{{ $doctor->Specialization }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="font-medium">Dr. {{ $doctor->Name }}</p>
                        <p class="text-sm text-gray-500">{{ $doctor->Specialization }}</p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-md text-green-600"></i>
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

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Today's Appointments</h3>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['today'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar-day text-blue-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Upcoming</h3>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['upcoming'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-green-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Pending</h3>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['pending'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-hourglass-half text-yellow-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Completed</h3>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['completed'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-purple-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="bg-white p-6 rounded-xl shadow-sm mb-6">
                <div class="flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
                    <h3 class="text-xl font-bold text-gray-800">Appointment Management</h3>

                    <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                        <form method="GET" class="flex gap-2">
                            <input type="text" name="search" placeholder="Search patients..."
                                   value="{{ request('search') }}"
                                   class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Confirmed" {{ request('status') == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <input type="date" name="date" value="{{ request('date') }}"
                                   class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-medium">
                                <i class="fas fa-search mr-2"></i> Filter
                            </button>
                            <a href="{{ route('doctor.dashboard') }}"
                               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition font-medium flex items-center">
                                <i class="fas fa-redo mr-2"></i> Reset
                            </a>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Appointments Table -->
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($appointments as $appt)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($appt->Date)->format('M j, Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $appt->Time }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $appt->patient->Name }}</div>
                                    <div class="text-sm text-gray-500">Age: {{ $appt->patient->Age }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $appt->patient->ContactNumber }}<br>
                                    {{ $appt->patient->Email }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs">
                                    {{ Str::limit($appt->Purpose, 50) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('doctor.appointment.status', $appt->AppointmentID) }}" method="POST" class="inline">
                                        @csrf
                                        <select name="Status" onchange="this.form.submit()"
                                                class="text-xs font-semibold rounded-full px-3 py-1 border-0 focus:ring-2 focus:ring-green-500
                                                @if($appt->Status == 'Confirmed') bg-green-100 text-green-800
                                                @elseif($appt->Status == 'Completed') bg-blue-100 text-blue-800
                                                @elseif($appt->Status == 'Cancelled') bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                            <option value="Pending" {{ $appt->Status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="Confirmed" {{ $appt->Status == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                                            <option value="Completed" {{ $appt->Status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="Cancelled" {{ $appt->Status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    @if(!$appt->hasPrescription() && in_array($appt->Status, ['Confirmed', 'Completed']))
                                        <button onclick="openPrescriptionModal('{{ $appt->AppointmentID }}', '{{ $appt->patient->Name }}')"
                                                class="text-green-600 hover:text-green-900 bg-green-50 px-3 py-1 rounded-lg transition flex items-center">
                                            <i class="fas fa-prescription mr-1"></i> Prescribe
                                        </button>
                                    @elseif($appt->hasPrescription())
                                        <span class="text-gray-400 flex items-center">
                                            <i class="fas fa-check-circle mr-1"></i> Prescribed
                                        </span>
                                    @endif

                                    <a href="{{ route('doctor.patient.history', $appt->patient->PatientID) }}"
                                       class="text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1 rounded-lg transition flex items-center">
                                        <i class="fas fa-history mr-1"></i> History
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    <i class="fas fa-calendar-times text-4xl text-gray-300 mb-2"></i>
                                    <p>No appointments found matching your criteria.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($appointments->hasPages())
                <div class="mt-6">
                    {{ $appointments->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Prescription Modal -->
<div id="prescriptionModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Issue Prescription for <span id="modalPatientName" class="text-green-600"></span></h3>
                <button onclick="closePrescriptionModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <form action="{{ route('doctor.prescription.store') }}" method="POST" class="mt-4 text-left space-y-4">
                @csrf
                <input type="hidden" name="AppointmentID" id="modalApptId">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Medicine Name *</label>
                        <input type="text" name="MedicineName"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               required>
                        @error('MedicineName')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dosage *</label>
                        <input type="text" name="Dosage" placeholder="e.g., 500mg"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               required>
                        @error('Dosage')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Frequency *</label>
                        <input type="text" name="Frequency" placeholder="e.g., 1-0-1"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               required>
                        @error('Frequency')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Duration *</label>
                        <input type="text" name="Duration" placeholder="e.g., 5 days"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               required>
                        @error('Duration')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Instructions</label>
                    <textarea name="Instructions" rows="2" placeholder="Special instructions for taking the medicine"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Clinical Notes</label>
                    <textarea name="Notes" rows="3" placeholder="Additional clinical notes and observations"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button type="button" onclick="closePrescriptionModal()"
                            class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition font-medium">
                        Cancel
                    </button>
                    <button type="submit"
                            class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition font-medium flex items-center">
                        <i class="fas fa-paper-plane mr-2"></i> Issue Prescription
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openPrescriptionModal(appointmentId, patientName) {
        document.getElementById('modalApptId').value = appointmentId;
        document.getElementById('modalPatientName').textContent = patientName;
        document.getElementById('prescriptionModal').classList.remove('hidden');
    }

    function closePrescriptionModal() {
        document.getElementById('prescriptionModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('prescriptionModal');
        if (event.target === modal) {
            closePrescriptionModal();
        }
    }
</script>
@endsection
