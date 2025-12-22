@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Add Patient</h1>

    {{-- Error Handling --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.patients.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Patient ID (Auto) --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Patient ID (Auto):</label>
                <input type="text" name="PatientID" value="{{ $nextId }}"
                       class="bg-gray-100 border rounded w-full py-2 px-3 text-gray-600 font-bold cursor-not-allowed"
                       readonly required>
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Email:</label>
                <input type="email" name="Email" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500" required>
            </div>

            {{-- First Name --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">First Name:</label>
                <input type="text" name="First_Name" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500" required>
            </div>

            {{-- Last Name --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Last Name:</label>
                <input type="text" name="Last_Name" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500" required>
            </div>

            {{-- Contact Number (Saved to Patient_Number table) --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Phone Number:</label>
                <input type="text" name="Contact_Number" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500" required>
            </div>

            {{-- Age --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Age:</label>
                <input type="number" name="Age" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500" required>
            </div>

            {{-- Gender --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Gender:</label>
                <select name="Gender" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            {{-- Patient Type --}}
            <div class="mb-4">
                <label for="patient_type" class="block text-gray-700 font-bold mb-2">Patient Type:</label>
                <select name="patient_type" id="patient_type" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500">
                    <option value="Non-Insured">Non-Insured (Default)</option>
                    <option value="Insured">Insured Patient</option>
                </select>
            </div>
        </div>

        <hr class="my-6 border-gray-300">
        <h3 class="text-lg font-bold text-gray-700 mb-4">Address Details</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div class="md:col-span-1">
                <label class="block text-sm font-bold text-gray-600">Street:</label>
                <input type="text" name="Street" class="border rounded w-full py-2 px-3" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-600">City:</label>
                <input type="text" name="City" class="border rounded w-full py-2 px-3" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-600">Zip:</label>
                <input type="text" name="Zip" class="border rounded w-full py-2 px-3" required>
            </div>
        </div>

        {{-- Dynamic Insurance Fields --}}
        <div id="insurance_fields" class="mt-6 p-4 bg-blue-50 rounded hidden border border-blue-200">
            <h4 class="font-bold text-blue-800 mb-3 uppercase text-sm">Insurance Details</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-600">Provider Name:</label>
                    <input type="text" name="Provider_Name" class="border rounded w-full py-2 px-3">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-600">Policy Number:</label>
                    <input type="text" name="Policy_Number" class="border rounded w-full py-2 px-3">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-600">Coverage Limit ($):</label>
                    <input type="number" step="0.01" name="Coverage_Limit" class="border rounded w-full py-2 px-3">
                </div>
            </div>
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-3 px-8 rounded mt-6 shadow-lg transition duration-200">
            Create Patient Account
        </button>
    </form>
</div>

<script>
    document.getElementById('patient_type').addEventListener('change', function() {
        const fields = document.getElementById('insurance_fields');
        if (this.value === 'Insured') {
            fields.classList.remove('hidden');
            // Add required attribute to insurance fields if visible
            fields.querySelectorAll('input').forEach(input => input.setAttribute('required', 'true'));
        } else {
            fields.classList.add('hidden');
            fields.querySelectorAll('input').forEach(input => input.removeAttribute('required'));
        }
    });
</script>
@endsection
