@extends('layouts.app')

@section('title', 'My Prescriptions')

@section('content')
<div>
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-4xl font-bold text-gray-800">My Prescriptions</h1>
            <p class="mt-2 text-lg text-gray-600">Review all prescriptions you have issued.</p>
        </div>
        <a href="{{ route('doctor.prescriptions.create') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 glow-on-hover transform hover:scale-105 transition-transform duration-200">
            <i class="fas fa-file-prescription mr-3"></i>
            New Prescription
        </a>
    </div>

    <!-- Prescriptions Table -->
    <div class="glass-card overflow-hidden p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200/30">
                <thead>
                    <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-600 sm:pl-0">Patient</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-600">Medication</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-600">Dosage</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-600">Date Issued</th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/20">
                    @forelse($prescriptions as $prescription)
                    <tr>
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-800 sm:pl-0">{{ $prescription->patient->cName ?? 'N/A' }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600">{{ $prescription->cMedication }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600">{{ $prescription->cDosage }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600">{{ \Carbon\Carbon::parse($prescription->dPrescriptionDate)->format('F j, Y') }}</td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                            <a href="#" class="text-blue-600 hover:text-blue-900">View<span class="sr-only">, prescription for {{ $prescription->patient->cName }}</span></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-gray-500">
                            <i class="fas fa-file-medical fa-2x mb-2"></i>
                            <p>No prescriptions found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="mt-6">
            {{ $prescriptions->links() }}
        </div>
    </div>
</div>
@endsection
