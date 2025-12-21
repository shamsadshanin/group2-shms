@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Edit Patient Profile</h1>

    <form action="{{ route('admin.patients.update', $patient->cPatientID) }}" method="POST" class="bg-white p-8 rounded-lg shadow-lg border border-gray-100">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-700 font-bold mb-2">Patient ID:</label>
                <input type="text" value="{{ $patient->cPatientID }}" class="bg-gray-100 border rounded w-full py-2 px-3 text-gray-500 cursor-not-allowed" readonly>
            </div>

            <div>
                <label for="cName" class="block text-gray-700 font-bold mb-2">Full Name:</label>
                <input type="text" name="cName" id="cName" value="{{ $patient->cName }}" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>

            <div>
                <label for="cEmail" class="block text-gray-700 font-bold mb-2">Email Address:</label>
                <input type="email" name="cEmail" id="cEmail" value="{{ $patient->cEmail }}" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>

            <div>
                <label for="cPhone" class="block text-gray-700 font-bold mb-2">Phone Number:</label>
                <input type="text" name="cPhone" id="cPhone" value="{{ $patient->cPhone }}" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>

            <div>
                <label for="nAge" class="block text-gray-700 font-bold mb-2">Age:</label>
                <input type="number" name="nAge" id="nAge" value="{{ $patient->nAge }}" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label for="cGender" class="block text-gray-700 font-bold mb-2">Gender:</label>
                <select name="cGender" id="cGender" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="Male" {{ $patient->cGender == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ $patient->cGender == 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ $patient->cGender == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
        </div>

        <hr class="my-6 border-gray-200">

        @if($patient->insurance)
        <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <h4 class="text-blue-800 font-bold mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path></svg>
                Insurance Details
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-1">Insurance Company:</label>
                    <input type="text" name="cInsuranceCompany" value="{{ $patient->insurance->cInsuranceCompany }}" class="border rounded w-full py-2 px-3 focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-1">Policy Number:</label>
                    <input type="text" name="cPolicyNumber" value="{{ $patient->insurance->cPolicyNumber }}" class="border rounded w-full py-2 px-3 focus:border-blue-500 outline-none">
                </div>
            </div>
        </div>
        @else
        <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200 text-gray-600 text-sm">
            <p>This is a <strong>Non-Insured</strong> patient profile.</p>
        </div>
        @endif

        <div class="flex items-center space-x-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-3 px-8 rounded shadow-lg transition duration-200">
                Update Profile
            </button>
            <a href="{{ route('admin.patients.index') }}" class="text-gray-600 hover:text-gray-800 font-semibold underline">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
