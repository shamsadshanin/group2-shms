@extends('layouts.app')

@section('title', 'Manage Appointments')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Manage Appointments</h1>
        <p class="mt-2 text-gray-600">Oversee all scheduled appointments.</p>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">All Appointments</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($appointments as $appointment)
                    <tr class="hover:bg-gray-50">
                        {{-- Patient Name (First_Name + Last_Name) --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $appointment->patient->First_Name ?? 'Unknown' }} {{ $appointment->patient->Last_Name ?? '' }}
                        </td>

                        {{-- Doctor Name --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Dr. {{ $appointment->doctor->First_Name ?? 'Unknown' }} {{ $appointment->doctor->Last_Name ?? '' }}
                        </td>

                        {{-- Date & Time (Combined) --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="font-medium">{{ \Carbon\Carbon::parse($appointment->Date)->format('M d, Y') }}</div>
                            <div class="text-gray-500 text-xs">{{ \Carbon\Carbon::parse($appointment->Time)->format('h:i A') }}</div>
                        </td>

                        {{-- Status with Dynamic Colors --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $appointment->Status === 'Scheduled' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $appointment->Status === 'Checked-in' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $appointment->Status === 'Completed' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $appointment->Status === 'Cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $appointment->Status }}
                            </span>
                        </td>

                        {{-- Actions (Using AppointmentID) --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            {{-- Pointing to existing edit route or view route --}}
                            <a href="{{ route('admin.appointments.edit', $appointment->AppointmentID) }}" class="text-blue-600 hover:text-blue-900">View/Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No appointments found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
