@extends('layouts.app')

@section('title', 'Patient History - ' . $patient->cName)

@section('content')
<div x-data="{ activeTab: 'medical-records' }">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-4xl font-bold text-gray-800">Patient History</h1>
            <p class="mt-2 text-lg text-gray-600">Complete medical overview for {{ $patient->cName }}</p>
        </div>
    </div>

    <!-- Patient Info Card -->
    <div class="glass-card p-6 mb-8 glow-on-hover">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <img class="h-20 w-20 rounded-full object-cover ring-4 ring-white/50" src="https://ui-avatars.com/api/?name={{ urlencode($patient->cName) }}&background=0D9488&color=fff&size=128" alt="{{ $patient->cName }}">
                <div class="ml-6">
                    <h2 class="text-2xl font-bold text-gray-800">{{ $patient->cName }}</h2>
                    <p class="text-sm text-gray-600 font-medium">Patient ID: {{ $patient->cPatientID }}</p>
                    <div class="flex items-center mt-3 space-x-6 text-gray-600">
                        <span class="text-sm"><i class="fas fa-birthday-cake mr-2 text-pink-500"></i>Age: {{ $patient->nAge ?? 'N/A' }}</span>
                        <span class="text-sm"><i class="fas fa-venus-mars mr-2 text-blue-500"></i>{{ $patient->cGender ?? 'N/A' }}</span>
                        <span class="text-sm"><i class="fas fa-phone-alt mr-2 text-green-500"></i>{{ $patient->cContactNumber }}</span>
                    </div>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="#" class="inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105 transition-transform duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit Profile
                </a>
                <a href="#" class="inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-xl text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transform hover:scale-105 transition-transform duration-200">
                    <i class="fas fa-file-medical mr-2"></i>New Record
                </a>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="mb-8">
        <div class="border-b border-gray-200/50">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button @click="activeTab = 'medical-records'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'medical-records', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'medical-records' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Medical Records
                </button>
                <button @click="activeTab = 'prescriptions'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'prescriptions', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'prescriptions' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Prescriptions
                </button>
                <button @click="activeTab = 'appointments'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'appointments', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'appointments' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Appointments
                </button>
            </nav>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="glass-card overflow-hidden p-6">
        <!-- Medical Records -->
        <div x-show="activeTab === 'medical-records'" class="space-y-6">
            @forelse($patient->medicalRecords as $record)
            <div class="border border-gray-200/30 rounded-xl p-5 hover:shadow-lg transition-shadow duration-300">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Record #{{ $record->cRecordID }}</h3>
                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($record->dRecordDate)->format('F j, Y') }}</p>
                    </div>
                     <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $record->cFollowUpRequired ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                        {{ $record->cFollowUpRequired ? 'Follow-up Needed' : 'Completed' }}
                    </span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                    <div class="prose prose-sm max-w-none text-gray-700">
                        <h4>Diagnosis</h4>
                        <p>{{ $record->cDiagnosisDetails }}</p>
                    </div>
                    <div class="prose prose-sm max-w-none text-gray-700">
                        <h4>Treatment</h4>
                        <p>{{ $record->cTreatmentNotes }}</p>
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

        <!-- Prescriptions -->
        <div x-show="activeTab === 'prescriptions'" class="space-y-4">
            @forelse($patient->prescriptions as $prescription)
             <div class="border border-gray-200/30 rounded-xl p-5 hover:shadow-lg transition-shadow duration-300">
                 <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-semibold text-gray-800">{{ $prescription->cMedication }}</h3>
                        <p class="text-sm text-gray-600">{{ $prescription->cDosage }} - Issued on {{ \Carbon\Carbon::parse($prescription->dPrescriptionDate)->format('F j, Y') }}</p>
                    </div>
                    <p class="text-sm text-gray-500">Prescribed by Dr. {{ $prescription->doctor->user->name ?? 'N/A' }}</p>
                </div>
             </div>
            @empty
            <div class="text-center py-12 text-gray-500">
                <i class="fas fa-pills fa-3x mb-3"></i>
                <p class="text-lg">No prescriptions found.</p>
            </div>
            @endforelse
        </div>

        <!-- Appointments -->
        <div x-show="activeTab === 'appointments'" class="space-y-4">
            @forelse($patient->appointments as $appointment)
            <div class="border border-gray-200/30 rounded-xl p-5 hover:shadow-lg transition-shadow duration-300">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-semibold text-gray-800">{{ $appointment->cPurpose }}</h3>
                        <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($appointment->dAppointmentDateTime)->format('F j, Y, g:i A') }} with Dr. {{ $appointment->doctor->user->name ?? 'N/A' }}</p>
                    </div>
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $appointment->cStatus === 'Scheduled' ? 'bg-blue-100 text-blue-800' : ($appointment->cStatus === 'Completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                        {{ $appointment->cStatus }}
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
