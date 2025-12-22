@extends('layouts.app')

@section('title', 'Patient History - ' . $patient->First_Name)

@section('content')
<div x-data="{ activeTab: 'medical-records' }">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-4xl font-bold text-gray-800">Patient History</h1>
            <p class="mt-2 text-lg text-gray-600">Complete medical overview for {{ $patient->First_Name }} {{ $patient->Last_Name }}</p>
        </div>
        <a href="{{ route('doctor.dashboard') }}" class="text-gray-500 hover:text-blue-600">
            <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
        </a>
    </div>

    <div class="glass-card p-6 mb-8 glow-on-hover bg-white rounded-xl shadow-md">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <img class="h-20 w-20 rounded-full object-cover ring-4 ring-white/50"
                     src="https://ui-avatars.com/api/?name={{ urlencode($patient->First_Name . ' ' . $patient->Last_Name) }}&background=0D9488&color=fff&size=128"
                     alt="{{ $patient->First_Name }}">
                <div class="ml-6">
                    <h2 class="text-2xl font-bold text-gray-800">{{ $patient->First_Name }} {{ $patient->Last_Name }}</h2>
                    <p class="text-sm text-gray-600 font-medium">Patient ID: {{ $patient->PatientID }}</p>
                    <div class="flex items-center mt-3 space-x-6 text-gray-600">
                        <span class="text-sm"><i class="fas fa-birthday-cake mr-2 text-pink-500"></i>Age: {{ $patient->Age ?? 'N/A' }}</span>
                        <span class="text-sm"><i class="fas fa-venus-mars mr-2 text-blue-500"></i>{{ $patient->Gender ?? 'N/A' }}</span>
                        <span class="text-sm">
                            <i class="fas fa-phone-alt mr-2 text-green-500"></i>
                            {{ $patient->contactNumbers->first()->Contact_Number ?? 'N/A' }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex space-x-3">
                {{-- Edit Profile Button --}}
                <a href="{{ route('doctor.patients.edit', $patient->PatientID) }}" class="inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105 transition-transform duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit Profile
                </a>

                {{-- New Medical Record Button --}}
                <a href="{{ route('doctor.medical-records.create', $patient->PatientID) }}" class="inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-xl text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transform hover:scale-105 transition-transform duration-200">
                    <i class="fas fa-file-medical mr-2"></i>New Record
                </a>
            </div>
        </div>
    </div>

    <div class="mb-8">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button @click="activeTab = 'medical-records'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'medical-records', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'medical-records' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Medical Records
                </button>
                <button @click="activeTab = 'prescriptions'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'prescriptions', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'prescriptions' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Prescriptions
                </button>
                <button @click="activeTab = 'appointments'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'appointments', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'appointments' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Appointments
                </button>
            </nav>
        </div>
    </div>

    <div class="glass-card overflow-hidden p-6 bg-white rounded-xl shadow-md">

        {{-- Medical Records Tab --}}
        <div x-show="activeTab === 'medical-records'" class="space-y-6">
            @forelse($patient->medicalRecords as $record)
            <div class="border border-gray-200 rounded-xl p-5 hover:shadow-lg transition-shadow duration-300">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Record #{{ $record->RecordID }}</h3>
                        {{-- FIX: Using Treatment_Start_Date based on SQL --}}
                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($record->Treatment_Start_Date)->format('F j, Y') }}</p>
                    </div>

                    {{-- FIX: Using Follow_Up based on SQL --}}
                    @if($record->Follow_Up)
                     <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        Follow-up: {{ \Carbon\Carbon::parse($record->Follow_Up)->format('M d, Y') }}
                    </span>
                    @endif
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                    <div class="prose prose-sm max-w-none text-gray-700">
                        <h4 class="font-bold text-gray-800">Disease / Diagnosis</h4>
                        {{-- FIX: Using Disease_Name based on SQL --}}
                        <p>{{ $record->Disease_Name }}</p>
                    </div>
                    <div class="prose prose-sm max-w-none text-gray-700">
                        <h4 class="font-bold text-gray-800">Symptoms</h4>
                        {{-- FIX: Using Symptoms based on SQL --}}
                        <p>{{ $record->Symptoms }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12 text-gray-500">
                <i class="fas fa-file-medical-alt fa-3x mb-3"></i>
                <p class="text-lg">No medical records found.</p>
            </div>
            @endforelse
        </div>

        {{-- Prescriptions Tab --}}
        <div x-show="activeTab === 'prescriptions'" class="space-y-4">
            @forelse($patient->prescriptions as $prescription)
             <div class="border border-gray-200 rounded-xl p-5 hover:shadow-lg transition-shadow duration-300">
                 <div class="flex justify-between items-start mb-2">
                    <div>
                        <h3 class="font-semibold text-gray-800">Prescription #{{ $prescription->PrescriptionID }}</h3>
                        <p class="text-sm text-gray-600">Issued on {{ \Carbon\Carbon::parse($prescription->IssueDate)->format('F j, Y') }}</p>
                    </div>
                    <p class="text-sm text-gray-500">Dr. {{ $prescription->doctor->Last_Name ?? 'Unknown' }}</p>
                </div>
                <div class="mt-3 bg-gray-50 p-3 rounded-lg">
                    <h4 class="text-xs font-bold text-gray-500 uppercase mb-2">Medications</h4>
                    <ul class="list-disc list-inside text-sm text-gray-700">
                        @foreach($prescription->medicines as $med)
                            <li>
                                <span class="font-semibold">{{ $med->Medicine_Name }}</span> - {{ $med->Dosage }} ({{ $med->Frequency }})
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="mt-4 text-right">
                     <a href="{{ route('doctor.prescriptions.show', $prescription->PrescriptionID) }}" class="text-blue-600 hover:text-blue-900 font-bold text-sm">View Details</a>
                </div>
             </div>
            @empty
            <div class="text-center py-12 text-gray-500">
                <i class="fas fa-pills fa-3x mb-3"></i>
                <p class="text-lg">No prescriptions found.</p>
            </div>
            @endforelse
        </div>

        {{-- Appointments Tab --}}
        <div x-show="activeTab === 'appointments'" class="space-y-4">
            @forelse($patient->appointments as $appointment)
            <div class="border border-gray-200 rounded-xl p-5 hover:shadow-lg transition-shadow duration-300">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-semibold text-gray-800">Appointment with Dr. {{ $appointment->doctor->First_Name ?? '' }} {{ $appointment->doctor->Last_Name ?? '' }}</h3>
                        <p class="text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($appointment->Date)->format('F j, Y') }} at {{ \Carbon\Carbon::parse($appointment->Time)->format('h:i A') }}
                        </p>
                    </div>
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                        {{ $appointment->Status === 'Scheduled' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $appointment->Status === 'Completed' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $appointment->Status === 'Cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ $appointment->Status }}
                    </span>
                </div>
            </div>
            @empty
            <div class="text-center py-12 text-gray-500">
                <i class="fas fa-calendar-check fa-3x mb-3"></i>
                <p class="text-lg">No appointments found.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
