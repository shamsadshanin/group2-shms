@extends('layouts.app')

@section('title', 'Prescription Details - ' . $prescription->PrescriptionID)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('doctor.prescriptions') }}" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to List
        </a>
        <button onclick="window.print()" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-gray-800 hover:bg-gray-900 focus:outline-none shadow-sm transition-transform hover:scale-105">
            <i class="fas fa-print mr-2"></i>
            Print Prescription
        </button>
    </div>

    <div class="bg-white p-10 rounded-xl shadow-xl border border-gray-100 print:shadow-none print:border-none" id="prescription-paper">

        <div class="border-b-2 border-gray-800 pb-6 mb-8 flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-serif font-bold text-gray-900">Smart Healthcare</h1>
                <p class="text-sm text-gray-500 mt-1">Excellence in Digital Medicine</p>
                <div class="mt-4">
                    <h2 class="text-lg font-bold text-gray-800">Dr. {{ $prescription->doctor->First_Name }} {{ $prescription->doctor->Last_Name }}</h2>
                    <p class="text-sm text-gray-600 uppercase tracking-wide">{{ $prescription->doctor->Specialization ?? 'General Physician' }}</p>
                    <p class="text-sm text-gray-600">{{ $prescription->doctor->Email }}</p>
                </div>
            </div>
            <div class="text-right">
                <div class="bg-gray-100 p-3 rounded-lg inline-block text-center">
                    <p class="text-xs text-gray-500 uppercase">Prescription ID</p>
                    <p class="text-xl font-mono font-bold text-gray-800">{{ $prescription->PrescriptionID }}</p>
                </div>
                <p class="mt-4 text-sm text-gray-600">
                    <span class="font-bold">Date:</span> {{ \Carbon\Carbon::parse($prescription->IssueDate)->format('d M, Y') }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-8 bg-gray-50 p-4 rounded-lg border border-gray-200">
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold">Patient Name</p>
                <p class="text-lg font-semibold text-gray-900">{{ $prescription->patient->First_Name }} {{ $prescription->patient->Last_Name }}</p>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-bold">Age</p>
                    <p class="text-base text-gray-900">{{ $prescription->patient->Age }} Yrs</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-bold">Gender</p>
                    <p class="text-base text-gray-900">{{ $prescription->patient->Gender }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-bold">PID</p>
                    <p class="text-base text-gray-900">{{ $prescription->patient->PatientID }}</p>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <h3 class="text-4xl font-serif italic font-bold text-gray-800">Rx</h3>
        </div>

        <div class="mb-12">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b-2 border-gray-300">
                        <th class="py-3 text-sm font-bold text-gray-600 uppercase">Medicine Name</th>
                        <th class="py-3 text-sm font-bold text-gray-600 uppercase">Dosage</th>
                        <th class="py-3 text-sm font-bold text-gray-600 uppercase">Frequency</th>
                        <th class="py-3 text-sm font-bold text-gray-600 uppercase">Duration</th>
                    </tr>
                </thead>
                <tbody class="align-top">
                    @forelse($prescription->medicines as $index => $medicine)
                    <tr class="border-b border-gray-100">
                        <td class="py-4 pr-4">
                            <span class="text-gray-400 font-mono mr-2">{{ $index + 1 }}.</span>
                            <span class="font-bold text-gray-800 text-lg">{{ $medicine->Medicine_Name }}</span>
                        </td>
                        <td class="py-4 text-gray-700 font-medium">{{ $medicine->Dosage }}</td>
                        <td class="py-4 text-gray-700">{{ $medicine->Frequency }}</td>
                        <td class="py-4 text-gray-700">{{ $medicine->Duration ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-6 text-center text-gray-500 italic">No medicines added to this prescription.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-20 flex justify-between items-end">
            <div class="text-xs text-gray-400">
                <p>Generated by Smart Healthcare System</p>
                <p>{{ now()->format('d M, Y h:i A') }}</p>
            </div>
            <div class="text-center w-64">
                <div class="h-16 border-b border-gray-400 mb-2"></div> <p class="text-sm font-bold text-gray-800">Dr. {{ $prescription->doctor->Last_Name }}</p>
                <p class="text-xs text-gray-500">Signature</p>
            </div>
        </div>
    </div>
</div>

{{-- Simple Print Styles --}}
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #prescription-paper, #prescription-paper * {
            visibility: visible;
        }
        #prescription-paper {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none;
            box-shadow: none;
        }
    }
</style>
@endsection
