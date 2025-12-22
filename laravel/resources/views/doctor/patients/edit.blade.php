@extends('layouts.app')

@section('title', 'Edit Patient')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="glass-card p-8 bg-white rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Patient Profile</h2>

        <form action="{{ route('doctor.patients.update', $patient->PatientID) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                    <input type="text" name="First_Name" value="{{ $patient->First_Name }}" class="form-input w-full rounded-lg border-gray-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                    <input type="text" name="Last_Name" value="{{ $patient->Last_Name }}" class="form-input w-full rounded-lg border-gray-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Age</label>
                    <input type="number" name="Age" value="{{ $patient->Age }}" class="form-input w-full rounded-lg border-gray-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                    <select name="Gender" class="form-input w-full rounded-lg border-gray-300">
                        <option value="Male" {{ $patient->Gender == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $patient->Gender == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                    <input type="text" name="Contact_Number" value="{{ $contact->Contact_Number ?? '' }}" class="form-input w-full rounded-lg border-gray-300">
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('doctor.patients.history', $patient->PatientID) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update Profile</button>
            </div>
        </form>
    </div>
</div>
@endsection
