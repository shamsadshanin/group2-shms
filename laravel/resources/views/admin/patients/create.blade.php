@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Add Patient</h1>

    <form action="{{ route('admin.patients.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Patient ID (Auto-Generated):</label>
                <input type="text" name="cPatientID" value="{{ $nextId }}"
                       class="bg-gray-100 border rounded w-full py-2 px-3 text-gray-600 font-bold cursor-not-allowed"
                       readonly required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Full Name:</label>
                <input type="text" name="cName" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Email:</label>
                <input type="email" name="cEmail" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Phone:</label>
                <input type="text" name="cPhone" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Age:</label>
                <input type="number" name="nAge" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Gender:</label>
                <select name="cGender" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="patient_type" class="block text-gray-700 font-bold mb-2">Patient Type:</label>
                <select name="patient_type" id="patient_type" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500">
                    <option value="Non-Insured">Non-Insured (Default)</option>
                    <option value="Insured">Insured Patient</option>
                </select>
            </div>
        </div>

        <div id="insurance_fields" class="mt-4 p-4 bg-blue-50 rounded hidden border border-blue-200">
            <h4 class="font-bold text-blue-800 mb-3 uppercase text-sm">Insurance Details</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-600">Insurance Company:</label>
                    <input type="text" name="cInsuranceCompany" class="border rounded w-full py-2 px-3">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-600">Policy Number:</label>
                    <input type="text" name="cPolicyNumber" class="border rounded w-full py-2 px-3">
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
        fields.classList.toggle('hidden', this.value !== 'Insured');
    });
</script>
@endsection
