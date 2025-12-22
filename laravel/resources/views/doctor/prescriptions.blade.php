@extends('layouts.app')

@section('title', 'My Prescriptions')

@section('content')
<div>
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

    <div class="glass-card overflow-hidden p-6 bg-white rounded-xl shadow-md">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-600 sm:pl-0">Patient</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-600">Primary Medication</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-600">Dosage</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-600">Date Issued</th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($prescriptions as $prescription)
                    <tr class="hover:bg-gray-50 transition-colors">
                        {{-- Patient Name --}}
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-800 sm:pl-0">
                            {{ $prescription->patient->First_Name ?? 'Unknown' }} {{ $prescription->patient->Last_Name ?? '' }}
                        </td>

                        {{-- Medication (Get the first one from the related table as preview) --}}
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600">
                            @if($prescription->medicines->isNotEmpty())
                                <span class="font-semibold">{{ $prescription->medicines->first()->Medicine_Name }}</span>
                                @if($prescription->medicines->count() > 1)
                                    <span class="text-xs text-gray-400 ml-1">(+{{ $prescription->medicines->count() - 1 }} more)</span>
                                @endif
                            @else
                                <span class="text-gray-400 italic">No medicines listed</span>
                            @endif
                        </td>

                        {{-- Dosage (Get the first one) --}}
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600">
                            {{ $prescription->medicines->first()->Dosage ?? '-' }}
                        </td>

                        {{-- Issue Date --}}
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($prescription->IssueDate)->format('F j, Y') }}
                        </td>

                        {{-- Actions --}}
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                            <a href="{{ route('doctor.prescriptions.show', $prescription->PrescriptionID) }}" class="text-blue-600 hover:text-blue-900 font-bold">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-file-medical fa-2x mb-2 text-gray-300"></i>
                                <p>No prescriptions found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $prescriptions->links() }}
        </div>
    </div>
</div>
@endsection
