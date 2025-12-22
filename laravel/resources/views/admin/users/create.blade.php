@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Add New User</h1>

    {{-- Error Display --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <strong class="font-bold">Whoops!</strong>
            <span class="block sm:inline">There were some problems with your input.</span>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST" class="bg-white p-8 rounded-lg shadow-lg border border-gray-100">
        @csrf

        {{-- Role Selection --}}
        <div class="mb-6">
            <label for="role" class="block text-gray-700 font-bold mb-2">User Role:</label>
            <select name="role" id="role" class="shadow border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="">-- Select Role --</option>
                <option value="admin">System Admin</option>
                <option value="doctor">Doctor</option>
                <option value="patient">Patient</option>
                <option value="lab">Lab Technician</option>
                <option value="pharmacy">Pharmacist</option>
                <option value="reception">Receptionist</option>
            </select>
        </div>

        <hr class="my-6 border-gray-200">
        <h3 class="text-lg font-bold text-gray-700 mb-4">Account Information</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-700 font-bold mb-2">First Name:</label>
                <input type="text" name="first_name" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Last Name:</label>
                <input type="text" name="last_name" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Email:</label>
                <input type="email" name="email" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Password:</label>
                <input type="password" name="password" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Confirm Password:</label>
                <input type="password" name="password_confirmation" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500" required>
            </div>
        </div>

        {{-- Dynamic Fields --}}

        {{-- DOCTOR FIELDS --}}
        <div id="doctor-fields" class="role-section hidden p-6 bg-blue-50 rounded-lg border border-blue-200 mb-6">
            <h4 class="text-blue-800 font-bold mb-4 uppercase text-sm">Doctor Details</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-600 mb-1">Specialization:</label>
                    <input type="text" name="specialization" class="border rounded w-full py-2 px-3">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-600 mb-1">Contact Number:</label>
                    <input type="text" name="doctor_contact" class="border rounded w-full py-2 px-3">
                </div>
                <div class="mb-4 md:col-span-2">
                    <label class="block text-sm font-bold text-gray-600 mb-1">Available Days:</label>
                    <input type="text" name="available_days" placeholder="Mon, Wed, Fri" class="border rounded w-full py-2 px-3">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-600 mb-1">Start Time:</label>
                    <input type="time" name="start_time" class="border rounded w-full py-2 px-3">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-600 mb-1">End Time:</label>
                    <input type="time" name="end_time" class="border rounded w-full py-2 px-3">
                </div>
            </div>
        </div>

        {{-- PATIENT FIELDS --}}
        <div id="patient-fields" class="role-section hidden p-6 bg-green-50 rounded-lg border border-green-200 mb-6">
            <h4 class="text-green-800 font-bold mb-4 uppercase text-sm">Patient Details</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-1">Age:</label>
                    <input type="number" name="age" class="border rounded w-full py-2 px-3">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-1">Gender:</label>
                    <select name="gender" class="border rounded w-full py-2 px-3 bg-white">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-1">Contact Number:</label>
                    <input type="text" name="patient_contact" class="border rounded w-full py-2 px-3">
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-bold text-gray-600 mb-2">Address:</label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input type="text" name="street" placeholder="Street" class="border rounded w-full py-2 px-3">
                    <input type="text" name="city" placeholder="City" class="border rounded w-full py-2 px-3">
                    <input type="text" name="zip" placeholder="Zip Code" class="border rounded w-full py-2 px-3">
                </div>
            </div>
        </div>

        {{-- LAB FIELDS --}}
        <div id="lab-fields" class="role-section hidden p-6 bg-purple-50 rounded-lg border border-purple-200 mb-6">
            <h4 class="text-purple-800 font-bold mb-4 uppercase text-sm">Lab Technician Details</h4>
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-600 mb-1">Department:</label>
                <input type="text" name="department" class="border rounded w-full py-2 px-3">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-600 mb-1">Contact Number:</label>
                <input type="text" name="lab_contact" class="border rounded w-full py-2 px-3">
            </div>
        </div>

        {{-- PHARMACY FIELDS --}}
        <div id="pharmacy-fields" class="role-section hidden p-6 bg-yellow-50 rounded-lg border border-yellow-200 mb-6">
            <h4 class="text-yellow-800 font-bold mb-4 uppercase text-sm">Pharmacist Details</h4>
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-600 mb-1">Contact Number:</label>
                <input type="text" name="pharmacy_contact" class="border rounded w-full py-2 px-3">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-600 mb-1">License Number (Optional):</label>
                <input type="text" name="pharmacy_license" class="border rounded w-full py-2 px-3">
            </div>
        </div>

        {{-- RECEPTION FIELDS --}}
        <div id="reception-fields" class="role-section hidden p-6 bg-pink-50 rounded-lg border border-pink-200 mb-6">
            <h4 class="text-pink-800 font-bold mb-4 uppercase text-sm">Receptionist Details</h4>
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-600 mb-1">Contact Number:</label>
                <input type="text" name="reception_contact" class="border rounded w-full py-2 px-3">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-600 mb-1">Shift (e.g. Morning/Night):</label>
                <input type="text" name="reception_shift" class="border rounded w-full py-2 px-3">
            </div>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-800 text-white font-bold py-3 px-4 rounded shadow-md transition duration-200">
            Create User Account
        </button>
    </form>
</div>

<script>
    document.getElementById('role').addEventListener('change', function () {
        const role = this.value;
        const sections = {
            'doctor': document.getElementById('doctor-fields'),
            'patient': document.getElementById('patient-fields'),
            'lab': document.getElementById('lab-fields'),
            'pharmacy': document.getElementById('pharmacy-fields'),
            'reception': document.getElementById('reception-fields')
        };

        // Hide all sections and disable inputs
        Object.values(sections).forEach(section => {
            if(section) {
                section.classList.add('hidden');
                section.querySelectorAll('input, select').forEach(el => el.disabled = true);
            }
        });

        // Show specific section based on role
        if (sections[role]) {
            sections[role].classList.remove('hidden');
            sections[role].querySelectorAll('input, select').forEach(el => el.disabled = false);
        }
    });

    // Initialize on load
    document.getElementById('role').dispatchEvent(new Event('change'));
</script>
@endsection
