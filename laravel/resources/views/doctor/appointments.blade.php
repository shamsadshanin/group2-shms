@extends('layouts.app')

@section('title', 'My Appointments')

@section('content')
<div>
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-4xl font-bold text-gray-800">My Appointments</h1>
            <p class="mt-2 text-lg text-gray-600">View and manage all your scheduled appointments.</p>
        </div>
        <a href="{{ route('doctor.appointments.create') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 glow-on-hover transform hover:scale-105 transition-transform duration-200">
            <i class="fas fa-calendar-plus mr-3"></i>
            New Appointment
        </a>
    </div>

    <div class="glass-card overflow-hidden p-6 bg-white rounded-xl shadow-md">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-600 sm:pl-0">Patient</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-600">Date</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-600">Time</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-600">Status</th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($appointments as $appointment)
                    <tr class="hover:bg-gray-50 transition-colors">
                        {{-- Patient Name --}}
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-800 sm:pl-0">
                            {{-- Assuming you have a route for patient history --}}
                            <a href="{{ route('doctor.patients.history', $appointment->PatientID) }}" class="hover:text-blue-600 transition-colors">
                                {{ $appointment->patient->First_Name ?? 'Unknown' }} {{ $appointment->patient->Last_Name ?? '' }}
                            </a>
                        </td>

                        {{-- Date --}}
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($appointment->Date)->format('M d, Y') }}
                        </td>

                        {{-- Time --}}
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($appointment->Time)->format('h:i A') }}
                        </td>

                        {{-- Status --}}
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $appointment->Status === 'Scheduled' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $appointment->Status === 'Completed' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $appointment->Status === 'Cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $appointment->Status }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                            <a href="{{ route('doctor.patients.history', $appointment->PatientID) }}" class="text-blue-600 hover:text-blue-900 font-bold">
                                View History
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-calendar-times fa-2x mb-2 text-gray-300"></i>
                                <p>No appointments found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $appointments->links() }}
        </div>
    </div>
</div>
@endsection
