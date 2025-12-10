@extends('layouts.app')

@section('title', 'Create New Prescription')

@section('content')
<div>
    <!-- Page Header -->
    <div class="mb-8">
        <a href="{{ route('doctor.prescriptions') }}" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Prescriptions
        </a>
        <h1 class="text-4xl font-bold text-gray-800 mt-2">Create Prescription</h1>
        <p class="mt-2 text-lg text-gray-600">Issue a new prescription for a patient.</p>
    </div>

    <!-- Create Form -->
    <div class="max-w-4xl mx-auto">
        <div class="glass-card p-8">
            <form action="{{ route('doctor.prescriptions.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <!-- Patient Selection -->
                    <div>
                        <label for="cPatientID" class="block text-sm font-medium text-gray-700 mb-2">Patient</label>
                        <select id="cPatientID" name="cPatientID" class="form-input w-full px-4 py-3 bg-white/50 border-gray-300/50 rounded-xl shadow-sm focus:ring-green-500 focus:border-green-500 transition-colors backdrop-blur-sm">
                            <option value="" disabled selected>Select a patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->cPatientID }}" {{ old('cPatientID') == $patient->cPatientID ? 'selected' : '' }}>
                                    {{ $patient->cName }} (ID: {{ $patient->cPatientID }})
                                </option>
                            @endforeach
                        </select>
                        @error('cPatientID')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prescription Date -->
                    <div>
                        <label for="dPrescriptionDate" class="block text-sm font-medium text-gray-700 mb-2">Prescription Date</label>
                        <input type="date" id="dPrescriptionDate" name="dPrescriptionDate" value="{{ old('dPrescriptionDate', date('Y-m-d')) }}" class="form-input w-full px-4 py-3 bg-white/50 border-gray-300/50 rounded-xl shadow-sm focus:ring-green-500 focus:border-green-500 transition-colors backdrop-blur-sm">
                        @error('dPrescriptionDate')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Medication -->
                    <div class="md:col-span-2">
                        <label for="cMedication" class="block text-sm font-medium text-gray-700 mb-2">Medication</label>
                        <input type="text" id="cMedication" name="cMedication" value="{{ old('cMedication') }}" placeholder="e.g., Paracetamol 500mg" class="form-input w-full px-4 py-3 bg-white/50 border-gray-300/50 rounded-xl shadow-sm focus:ring-green-500 focus:border-green-500 transition-colors backdrop-blur-sm">
                        @error('cMedication')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dosage -->
                    <div>
                        <label for="cDosage" class="block text-sm font-medium text-gray-700 mb-2">Dosage</label>
                        <input type="text" id="cDosage" name="cDosage" value="{{ old('cDosage') }}" placeholder="e.g., 1 tablet twice a day" class="form-input w-full px-4 py-3 bg-white/50 border-gray-300/50 rounded-xl shadow-sm focus:ring-green-500 focus:border-green-500 transition-colors backdrop-blur-sm">
                        @error('cDosage')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Instructions -->
                    <div class="md:col-span-2">
                        <label for="cInstructions" class="block text-sm font-medium text-gray-700 mb-2">Instructions</label>
                        <textarea id="cInstructions" name="cInstructions" rows="4" placeholder="e.g., After meals for 3 days" class="form-textarea w-full px-4 py-3 bg-white/50 border-gray-300/50 rounded-xl shadow-sm focus:ring-green-500 focus:border-green-500 transition-colors backdrop-blur-sm">{{ old('cInstructions') }}</textarea>
                        @error('cInstructions')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- Form Actions -->
                <div class="mt-10 pt-6 border-t border-gray-200/50 flex justify-end items-center gap-4">
                    <a href="{{ route('doctor.prescriptions') }}" class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 glow-on-hover transform hover:scale-105 transition-transform duration-200">
                        <i class="fas fa-save mr-3"></i>
                        Save Prescription
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
