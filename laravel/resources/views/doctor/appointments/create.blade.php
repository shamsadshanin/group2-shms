@extends('layouts.app')

@section('title', 'Create New Appointment')

@section('content')
<div>
    <div class="mb-8">
        <a href="{{ route('doctor.appointments') }}" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Appointments
        </a>
        <h1 class="text-4xl font-bold text-gray-800 mt-2">Create Appointment</h1>
        <p class="mt-2 text-lg text-gray-600">Schedule a new appointment for a patient.</p>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="glass-card p-8 bg-white rounded-xl shadow-lg">
            <form action="{{ route('doctor.appointments.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <div class="md:col-span-2">
                        <label for="PatientID" class="block text-sm font-medium text-gray-700 mb-2">Select Patient</label>
                        <select id="PatientID" name="PatientID" class="form-input w-full px-4 py-3 bg-white border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                            <option value="" disabled selected>-- Choose a Patient --</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->PatientID }}" {{ old('PatientID') == $patient->PatientID ? 'selected' : '' }}>
                                    {{ $patient->First_Name }} {{ $patient->Last_Name }} (ID: {{ $patient->PatientID }})
                                </option>
                            @endforeach
                        </select>
                        @error('PatientID')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="Date" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                        <input type="date" id="Date" name="Date" value="{{ old('Date') }}" class="form-input w-full px-4 py-3 bg-white border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                        @error('Date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="Time" class="block text-sm font-medium text-gray-700 mb-2">Time</label>
                        <input type="time" id="Time" name="Time" value="{{ old('Time') }}" class="form-input w-full px-4 py-3 bg-white border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                        @error('Time')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-10 pt-6 border-t border-gray-200 flex justify-end items-center gap-4">
                    <a href="{{ route('doctor.appointments') }}" class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 font-semibold transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-bold rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-md transition-transform transform hover:scale-105">
                        <i class="fas fa-save mr-2"></i>
                        Confirm Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
