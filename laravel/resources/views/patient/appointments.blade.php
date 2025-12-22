@extends('layouts.app')

@section('title', 'Patient Appointments')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Appointments</h1>
        <p class="mt-2 text-gray-600">Manage your medical appointments</p>
    </div>

    <div class="mb-6 bg-white p-4 rounded-lg shadow">
        <div class="flex justify-between items-center">
            <div class="flex space-x-4">
                <a href="{{ route('patient.book-appointment') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 flex items-center">
                    <i class="fas fa-plus mr-2"></i>Book New Appointment
                </a>
            </div>
            {{-- Search/Filter Form (Optional implementation) --}}
            <form action="{{ route('patient.appointments') }}" method="GET" class="flex space-x-2">
                <select name="status" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="Scheduled">Scheduled</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">Filter</button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        {{-- Total --}}
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <i class="fas fa-calendar-check text-white text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Appointments</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $totalAppointments }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        {{-- Completed --}}
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Completed</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $completedAppointments }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        {{-- Upcoming --}}
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Upcoming</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $upcomingAppointments }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cancelled --}}
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                        <i class="fas fa-times-circle text-white text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Cancelled</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $cancelledAppointments }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">All Appointments</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($appointments as $appointment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    {{-- Generated Avatar using Doctor's First Name --}}
                                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($appointment->doctor->First_Name . ' ' . $appointment->doctor->Last_Name) }}&background=3B82F6&color=white" alt="{{ $appointment->doctor->First_Name }}">
                                </div>
                                <div class="ml-4">
                                    {{-- Use First_Name and Last_Name from SQL --}}
                                    <div class="text-sm font-medium text-gray-900">Dr. {{ $appointment->doctor->First_Name }} {{ $appointment->doctor->Last_Name }}</div>
                                    {{-- Use Specialization from SQL --}}
                                    <div class="text-sm text-gray-500">{{ $appointment->doctor->Specialization }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{-- Use Date and Time from SQL --}}
                            <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($appointment->Date)->format('M d, Y') }}</div>
                            <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($appointment->Time)->format('h:i A') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{-- Use Purpose from SQL --}}
                            {{ Str::limit($appointment->Purpose, 30) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{-- Use Status from SQL --}}
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $appointment->Status === 'Scheduled' ? 'bg-blue-100 text-blue-800' :
                                   ($appointment->Status === 'Completed' ? 'bg-green-100 text-green-800' :
                                   ($appointment->Status === 'Cancelled' ? 'bg-red-100 text-red-800' :
                                   'bg-yellow-100 text-yellow-800')) }}">
                                {{ $appointment->Status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            {{-- Use AppointmentID from SQL --}}
                            <button class="text-blue-600 hover:text-blue-900 mr-3" onclick="viewAppointment('{{ $appointment->AppointmentID }}')">
                                <i class="fas fa-eye"></i>
                            </button>
                            @if($appointment->Status === 'Scheduled')
                            <button class="text-red-600 hover:text-red-900" onclick="cancelAppointment('{{ $appointment->AppointmentID }}')">
                                <i class="fas fa-times"></i>
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

<div id="appointmentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Appointment Details</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="appointmentDetails" class="space-y-4">
                </div>
        </div>
    </div>
</div>

<script>
// We need the route base for AJAX calls
const apiBase = "{{ url('/patient/api/appointments') }}";

function viewAppointment(appointmentId) {
    // Corrected Route to match Controller
    fetch(`${apiBase}/${appointmentId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('appointmentDetails').innerHTML = `
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Doctor</label>
                        <p class="text-sm text-gray-900 font-bold">${data.doctor_name}</p>
                        <p class="text-xs text-gray-500">${data.doctor_spec}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date & Time</label>
                        <p class="text-sm text-gray-900">${data.date} at ${data.time}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Purpose</label>
                        <p class="text-sm text-gray-900">${data.purpose}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <p class="text-sm text-gray-900">
                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">${data.status}</span>
                        </p>
                    </div>
                </div>
            `;
            document.getElementById('appointmentModal').classList.remove('hidden');
        })
        .catch(error => console.error('Error:', error));
}

function cancelAppointment(appointmentId) {
    if (confirm('Are you sure you want to cancel this appointment?')) {
        // Corrected Route to match Controller
        fetch(`${apiBase}/${appointmentId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Important for Laravel Security
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Appointment cancelled successfully');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

function closeModal() {
    document.getElementById('appointmentModal').classList.add('hidden');
}
</script>
@endsection
