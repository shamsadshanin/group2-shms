@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Add User</h1>

    <form action="{{ route('admin.users.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-bold mb-2">Name:</label>
            <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
            <input type="email" name="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-gray-700 font-bold mb-2">Password:</label>
            <input type="password" name="password" id="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="password_confirmation" class="block text-gray-700 font-bold mb-2">Confirm Password:</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="role" class="block text-gray-700 font-bold mb-2">Role:</label>
            <select name="role" id="role" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <option value="">Select a role</option>
                <option value="admin">Admin</option>
                <option value="doctor">Doctor</option>
                <option value="patient">Patient</option>
                <option value="lab">Lab</option>
                <option value="pharmacy">Pharmacy</option>
                <option value="reception">Reception</option>
            </select>
        </div>

        <div id="doctor-fields" style="display: none;">
            <div class="mb-4">
                <label for="cSpecialization" class="block text-gray-700 font-bold mb-2">Specialization:</label>
                <input type="text" name="cSpecialization" id="cSpecialization" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="doctor_cContactNumber" class="block text-gray-700 font-bold mb-2">Contact Number:</label>
                <input type="text" name="doctor_cContactNumber" id="doctor_cContactNumber" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
        </div>

        <div id="patient-fields" style="display: none;">
            <div class="mb-4">
                <label for="nAge" class="block text-gray-700 font-bold mb-2">Age:</label>
                <input type="number" name="nAge" id="nAge" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="cGender" class="block text-gray-700 font-bold mb-2">Gender:</label>
                <select name="cGender" id="cGender" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="cAddress" class="block text-gray-700 font-bold mb-2">Address:</label>
                <input type="text" name="cAddress" id="cAddress" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="patient_cContactNumber" class="block text-gray-700 font-bold mb-2">Contact Number:</label>
                <input type="text" name="patient_cContactNumber" id="patient_cContactNumber" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
        </div>

        <div id="lab-fields" style="display: none;">
            <div class="mb-4">
                <label for="lab_cContactNumber" class="block text-gray-700 font-bold mb-2">Contact Number:</label>
                <input type="text" name="lab_cContactNumber" id="lab_cContactNumber" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add User</button>
    </form>
</div>

<script>
    document.getElementById('role').addEventListener('change', function () {
        var role = this.value;
        var doctorFields = document.getElementById('doctor-fields');
        var patientFields = document.getElementById('patient-fields');
        var labFields = document.getElementById('lab-fields');

        // Disable all fields first
        doctorFields.querySelectorAll('input, select').forEach(function(el) {
            el.disabled = true;
        });
        patientFields.querySelectorAll('input, select').forEach(function(el) {
            el.disabled = true;
        });
        labFields.querySelectorAll('input, select').forEach(function(el) {
            el.disabled = true;
        });

        // Hide all fields
        doctorFields.style.display = 'none';
        patientFields.style.display = 'none';
        labFields.style.display = 'none';

        // Enable and show fields based on role
        if (role === 'doctor') {
            doctorFields.style.display = 'block';
            doctorFields.querySelectorAll('input, select').forEach(function(el) {
                el.disabled = false;
            });
        } else if (role === 'patient') {
            patientFields.style.display = 'block';
            patientFields.querySelectorAll('input, select').forEach(function(el) {
                el.disabled = false;
            });
        } else if (role === 'lab') {
            labFields.style.display = 'block';
            labFields.querySelectorAll('input, select').forEach(function(el) {
                el.disabled = false;
            });
        }
    });

    // Trigger the change event on page load to set initial state
    document.getElementById('role').dispatchEvent(new Event('change'));
</script>
@endsection
