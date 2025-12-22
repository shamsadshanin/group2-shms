@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Edit Patient Profile: {{ $patient->PatientID }}</h1>

    <form action="{{ route('admin.patients.update', $patient->PatientID) }}" method="POST" class="bg-white p-8 rounded-lg shadow-lg border border-gray-100">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            {{-- Patient ID (Readonly) --}}
            <div>
                <label class="block text-gray-700 font-bold mb-2">Patient ID:</label>
                <input type="text" value="{{ $patient->PatientID }}" class="bg-gray-100 border rounded w-full py-2 px-3 text-gray-500 cursor-not-allowed" readonly>
            </div>

            {{-- Email --}}
            <div>
                <label for="Email" class="block text-gray-700 font-bold mb-2">Email Address:</label>
                <input type="email" name="Email" id="Email" value="{{ $patient->Email }}" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>

            {{-- Name Fields --}}
            <div>
                <label for="First_Name" class="block text-gray-700 font-bold mb-2">First Name:</label>
                <input type="text" name="First_Name" id="First_Name" value="{{ $patient->First_Name }}" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
            <div>
                <label for="Last_Name" class="block text-gray-700 font-bold mb-2">Last Name:</label>
                <input type="text" name="Last_Name" id="Last_Name" value="{{ $patient->Last_Name }}" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>

            {{-- Contact Number (Fetched from Patient_Number) --}}
            <div>
                <label for="Contact_Number" class="block text-gray-700 font-bold mb-2">Phone Number:</label>
                <input type="text" name="Contact_Number" id="Contact_Number" value="{{ $contact->Contact_Number ?? '' }}" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>

            {{-- Age & Gender --}}
            <div>
                <label for="Age" class="block text-gray-700 font-bold mb-2">Age:</label>
                <input type="number" name="Age" id="Age" value="{{ $patient->Age }}" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>

            <div>
                <label for="Gender" class="block text-gray-700 font-bold mb-2">Gender:</label>
                <select name="Gender" id="Gender" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="Male" {{ $patient->Gender == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ $patient->Gender == 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ $patient->Gender == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
        </div>

        <hr class="my-6 border-gray-200">
        <h4 class="text-gray-700 font-bold mb-4">Address Details</h4>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label for="Street" class="block text-sm font-bold text-gray-600 mb-1">Street:</label>
                <input type="text" name="Street" value="{{ $patient->Street }}" class="border rounded w-full py-2 px-3 focus:border-blue-500 outline-none" required>
            </div>
            <div>
                <label for="City" class="block text-sm font-bold text-gray-600 mb-1">City:</label>
                <input type="text" name="City" value="{{ $patient->City }}" class="border rounded w-full py-2 px-3 focus:border-blue-500 outline-none" required>
            </div>
            <div>
                <label for="Zip" class="block text-sm font-bold text-gray-600 mb-1">Zip:</label>
                <input type="text" name="Zip" value="{{ $patient->Zip }}" class="border rounded w-full py-2 px-3 focus:border-blue-500 outline-none" required>
            </div>
        </div>

        @if($insurance)
        <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <h4 class="text-blue-800 font-bold mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path></svg>
                Insurance Details (Insured Patient)
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-1">Provider Name:</label>
                    <input type="text" name="Provider_Name" value="{{ $insurance->Provider_Name }}" class="border rounded w-full py-2 px-3 focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-1">Policy Number:</label>
                    <input type="text" name="Policy_Number" value="{{ $insurance->Policy_Number }}" class="border rounded w-full py-2 px-3 focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-1">Coverage Limit ($):</label>
                    <input type="number" step="0.01" name="Coverage_Limit" value="{{ $insurance->Coverage_Limit }}" class="border rounded w-full py-2 px-3 focus:border-blue-500 outline-none">
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
