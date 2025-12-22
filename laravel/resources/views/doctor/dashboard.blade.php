@extends('layouts.app')

@section('title', 'Doctor Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <div class="md:col-span-2">
        <div class="glass-card p-6 mb-8 bg-white rounded-xl shadow-md">
            <h1 class="text-3xl font-bold text-gray-800">
                Welcome back, Dr. {{ $doctor->First_Name ?? '' }} {{ $doctor->Last_Name ?? '' }}!
            </h1>
            <p class="mt-2 text-lg text-gray-600">Here's a summary of your activities today.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <div class="glass-card p-5 glow-on-hover bg-white rounded-xl shadow-sm">
                <div class="flex items-center">
                    <div class="bg-blue-500/20 text-blue-600 p-4 rounded-full">
                        <i class="fas fa-calendar-check fa-2x"></i>
                    </div>
                    <div class="ml-4">
                        {{-- Filter by 'Status' = 'Scheduled' --}}
                        <h3 class="text-2xl font-bold text-gray-800">{{ $doctor->appointments->where('Status', 'Scheduled')->count() }}</h3>
                        <p class="text-gray-600">Scheduled</p>
                    </div>
                </div>
            </div>
            <div class="glass-card p-5 glow-on-hover bg-white rounded-xl shadow-sm">
                <div class="flex items-center">
                    <div class="bg-green-500/20 text-green-600 p-4 rounded-full">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <div class="ml-4">
                        {{-- Filter by 'Status' = 'Completed' --}}
                        <h3 class="text-2xl font-bold text-gray-800">{{ $doctor->appointments->where('Status', 'Completed')->count() }}</h3>
                        <p class="text-gray-600">Completed</p>
                    </div>
                </div>
            </div>
            <div class="glass-card p-5 glow-on-hover bg-white rounded-xl shadow-sm">
                <div class="flex items-center">
                    <div class="bg-red-500/20 text-red-600 p-4 rounded-full">
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                    <div class="ml-4">
                        {{-- Filter by 'Status' = 'Cancelled' --}}
                        <h3 class="text-2xl font-bold text-gray-800">{{ $doctor->appointments->where('Status', 'Cancelled')->count() }}</h3>
                        <p class="text-gray-600">Cancelled</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-card p-6 bg-white rounded-xl shadow-md">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">My Patients</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-600 sm:pl-0">Patient Name</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-600">Total Appointments</th>
                            <th class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                <span class="sr-only">View History</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                         @forelse($patients as $patient)
                            <tr class="hover:bg-gray-50">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-800 sm:pl-0">
                                    <a href="{{ route('doctor.patients.history', $patient->PatientID) }}" class="hover:text-blue-600">
                                        {{ $patient->First_Name }} {{ $patient->Last_Name }}
                                    </a>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-center text-gray-600">{{ $patient->appointments_count }}</td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                    <a href="{{ route('doctor.patients.history', $patient->PatientID) }}" class="text-blue-600 hover:text-blue-900">View History</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-10 text-gray-500">
                                    <i class="fas fa-user-injured fa-2x mb-2"></i>
                                    <p>No patients found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $patients->links() }}
                </div>
            </div>
        </div>
    </div>

    <div class="md:col-span-1">
        <div class="glass-card p-6 bg-white rounded-xl shadow-md">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Upcoming Appointments</h2>
            <ul class="space-y-4">
                {{-- Sort by Date and Time --}}
                @forelse($doctor->appointments->where('Status', 'Scheduled')->sortBy([['Date', 'asc'], ['Time', 'asc']])->take(5) as $appointment)
                <li class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-semibold text-gray-800">
                                {{ $appointment->patient->First_Name ?? 'Unknown' }} {{ $appointment->patient->Last_Name ?? '' }}
                            </p>
                            <p class="text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($appointment->Time)->format('h:i A') }}
                            </p>
                        </div>
                        <p class="text-sm text-gray-500 font-medium">
                            {{ \Carbon\Carbon::parse($appointment->Date)->format('M d') }}
                        </p>
                    </div>
                </li>
                @empty
                <li class="text-center py-6 text-gray-500">
                    <i class="fas fa-calendar-day fa-2x mb-2"></i>
                    <p>No upcoming appointments.</p>
                </li>
                @endforelse
            </ul>
            <div class="mt-6 text-center">
                <a href="{{ route('doctor.appointments') }}" class="text-blue-600 hover:underline font-medium">View All Appointments</a>
            </div>
        </div>
    </div>
</div>
@endsection
