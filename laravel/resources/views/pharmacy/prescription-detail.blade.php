@extends('layouts.app')

@section('title', 'Prescription Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-100 border-b">
                <h1 class="text-2xl font-bold text-gray-800">Prescription #{{ $prescription->cPrescriptionID }}</h1>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Patient Information</h2>
                        <div class="text-gray-600">
                            <p><span class="font-medium">Name:</span> {{ $patient->cName }}</p>
                            <p><span class="font-medium">Email:</span> {{ $patient->cEmail }}</p>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Doctor Information</h2>
                        <div class="text-gray-600">
                            <p><span class="font-medium">Name:</span> {{ $doctor->cName }}</p>
                            <p><span class="font-medium">Specialization:</span> {{ $doctor->cSpecialization }}</p>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">Prescription Details</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-600">
                        <p><span class="font-medium">Issue Date:</span> {{ $prescription->dIssueDate ? $prescription->dIssueDate->format('F d, Y') : 'Not available' }}</p>
                        <p><span class="font-medium">Status:</span> 
                            <span class="px-3 py-1 text-sm font-semibold rounded-full 
                                @switch($prescription->cStatus)
                                    @case('Active') bg-blue-200 text-blue-800 @break
                                    @case('Dispensed') bg-green-200 text-green-800 @break
                                    @case('Collected') bg-gray-200 text-gray-800 @break
                                @endswitch">
                                {{ $prescription->cStatus }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-md font-semibold text-gray-700 mb-2">Medication</h3>
                    <p class="text-gray-600">{{ $prescription->cMedicineDetails }}</p>
                </div>

                <div class="mb-8">
                    <h3 class="text-md font-semibold text-gray-700 mb-2">Instructions</h3>
                    <p class="text-gray-600">{{ $prescription->cInstructions ?: 'No specific instructions provided.' }}</p>
                </div>

                <div class="flex items-center justify-between border-t pt-4">
                    <a href="{{ route('pharmacy.prescriptions') }}" class="text-blue-600 hover:underline">
                        &larr; Back to All Prescriptions
                    </a>

                    <div class="flex items-center space-x-4">
                        @if($prescription->cStatus == 'Active')
                            <form action="{{ route('pharmacy.mark-as-dispensed', $prescription->cPrescriptionID) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-4 py-2 bg-green-500 text-white font-semibold rounded-lg shadow-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-opacity-75">
                                    Mark as Dispensed
                                </button>
                            </form>
                        @endif

                        @if($prescription->cStatus == 'Dispensed')
                            <form action="{{ route('pharmacy.mark-as-collected', $prescription->cPrescriptionID) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-4 py-2 bg-gray-500 text-white font-semibold rounded-lg shadow-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-75">
                                    Mark as Collected
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
