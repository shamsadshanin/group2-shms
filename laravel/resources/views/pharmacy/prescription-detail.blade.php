@extends('layouts.app')
@section('title', 'Prescription Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">

            {{-- Header --}}
            <div class="px-6 py-4 bg-gray-100 border-b flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">Prescription #{{ $prescription->PrescriptionID }}</h1>
                <div class="text-sm text-gray-500">
                    <span class="font-bold">Issue Date:</span> {{ \Carbon\Carbon::parse($prescription->IssueDate)->format('F d, Y') }}
                </div>
            </div>

            <div class="p-6">
                {{-- Patient & Doctor Information Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    {{-- Patient Card --}}
                    <div class="bg-blue-50 p-5 rounded-xl border border-blue-100">
                        <h2 class="text-lg font-bold text-blue-800 mb-3 border-b border-blue-200 pb-2">Patient Information</h2>
                        <div class="text-gray-700 space-y-2 text-sm">
                            <p><span class="font-semibold text-gray-900">Name:</span> {{ $patient->First_Name ?? '' }} {{ $patient->Last_Name ?? '' }}</p>
                            <p><span class="font-semibold text-gray-900">Patient ID:</span> {{ $patient->PatientID }}</p>
                            <p><span class="font-semibold text-gray-900">Email:</span> {{ $patient->Email ?? 'N/A' }}</p>
                            <p><span class="font-semibold text-gray-900">Age/Gender:</span> {{ $patient->Age ?? 'N/A' }} / {{ $patient->Gender ?? 'N/A' }}</p>
                        </div>
                    </div>

                    {{-- Doctor Card --}}
                    <div class="bg-green-50 p-5 rounded-xl border border-green-100">
                        <h2 class="text-lg font-bold text-green-800 mb-3 border-b border-green-200 pb-2">Doctor Information</h2>
                        <div class="text-gray-700 space-y-2 text-sm">
                            <p><span class="font-semibold text-gray-900">Name:</span> Dr. {{ $doctor->First_Name ?? '' }} {{ $doctor->Last_Name ?? '' }}</p>
                            <p><span class="font-semibold text-gray-900">Doctor ID:</span> {{ $doctor->DoctorID }}</p>
                            <p><span class="font-semibold text-gray-900">Specialization:</span> {{ $doctor->Specialization ?? 'General' }}</p>
                            <p><span class="font-semibold text-gray-900">Email:</span> {{ $doctor->Email ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Medication Table --}}
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-pills mr-2 text-blue-600"></i> Medications Prescribed
                    </h3>
                    <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Medicine Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Dosage</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Frequency</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Duration</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($prescription->medicines as $med)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $med->Medicine_Name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $med->Dosage }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $med->Frequency }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $med->Duration ?? '-' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-6 text-center text-sm text-gray-500 italic">
                                        No medicines found associated with this prescription.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Footer Actions --}}
                <div class="flex items-center justify-between border-t border-gray-100 pt-6 mt-6">
                    <a href="{{ route('pharmacy.prescriptions') }}" class="text-gray-600 hover:text-blue-600 font-medium flex items-center transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Prescriptions
                    </a>

                    <div class="flex space-x-3">
                        <button onclick="window.print()" class="px-5 py-2.5 bg-gray-800 text-white font-semibold rounded-lg shadow hover:bg-gray-900 focus:outline-none transition-transform transform hover:scale-105 flex items-center">
                            <i class="fas fa-print mr-2"></i> Print Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Print Styles --}}
<style>
    @media print {
        body * { visibility: hidden; }
        .container, .container * { visibility: visible; }
        .container { position: absolute; left: 0; top: 0; width: 100%; margin: 0; padding: 0; }
        button, a { display: none !important; }
    }
</style>
@endsection
