@extends('layouts.app')

@section('title', 'Patient History - Dr. ' . auth()->user()->doctor->Name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Patient Medical History</h1>
            <p class="text-gray-600">Dr. {{ auth()->user()->doctor->Name }} - {{ auth()->user()->doctor->Specialization }}</p>
        </div>
        <a href="{{ route('doctor.dashboard') }}"
           class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </a>
    </div>

    <!-- Patient Information -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Patient Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="text-sm font-medium text-gray-500">Name</label>
                <p class="text-lg font-semibold">{{ $patient->Name }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Age</label>
                <p class="text-lg font-semibold">{{ $patient->Age }} years</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Gender</label>
                <p class="text-lg font-semibold">{{ $patient->Gender }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Contact</label>
                <p class="text-lg font-semibold">{{ $patient->ContactNumber }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Email</label>
                <p class="text-lg font-semibold">{{ $patient->Email }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Address</label>
                <p class="text-lg font-semibold">{{ $patient->Address }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Appointments History -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Appointment History</h2>
            <div class="space-y-4">
                @forelse($appointments as $appt)
                <div class="border-l-4 border-green-500 bg-green-50 p-4 rounded-r-lg">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold text-gray-800">
                                {{ \Carbon\Carbon::parse($appt->Date)->format('M j, Y') }} at {{ $appt->Time }}
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $appt->Purpose }}</p>
                            <span class="inline-block mt-2 px-2 py-1 text-xs font-semibold rounded-full
                                @if($appt->Status == 'Completed') bg-green-100 text-green-800
                                @elseif($appt->Status == 'Confirmed') bg-blue-100 text-blue-800
                                @elseif($appt->Status == 'Pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $appt->Status }}
                            </span>
                        </div>
                        @if($appt->hasPrescription())
                        <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">
                            <i class="fas fa-prescription mr-1"></i> Prescribed
                        </span>
                        @endif
                    </div>
                    @if($appt->Notes)
                    <div class="mt-2 text-sm text-gray-700">
                        <strong>Notes:</strong> {{ $appt->Notes }}
                    </div>
                    @endif
                </div>
                @empty
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-calendar-times text-4xl text-gray-300 mb-2"></i>
                    <p>No appointment history found.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Prescriptions History -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Prescription History</h2>
            <div class="space-y-4">
                @forelse($prescriptions as $prescription)
                <div class="border-l-4 border-blue-500 bg-blue-50 p-4 rounded-r-lg">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-semibold text-gray-800">{{ $prescription->MedicineName }}</h3>
                        <span class="text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($prescription->IssueDate)->format('M j, Y') }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div><strong>Dosage:</strong> {{ $prescription->Dosage }}</div>
                        <div><strong>Frequency:</strong> {{ $prescription->Frequency }}</div>
                        <div><strong>Duration:</strong> {{ $prescription->Duration }}</div>
                        <div class="col-span-2">
                            <strong>Instructions:</strong> {{ $prescription->Instructions ?? 'None' }}
                        </div>
                    </div>
                    @if($prescription->Notes)
                    <div class="mt-2 text-sm text-gray-700">
                        <strong>Clinical Notes:</strong> {{ $prescription->Notes }}
                    </div>
                    @endif
                </div>
                @empty
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-prescription-bottle-alt text-4xl text-gray-300 mb-2"></i>
                    <p>No prescription history found.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
