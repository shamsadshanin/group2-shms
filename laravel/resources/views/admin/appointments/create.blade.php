@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Add Appointment</h1>

    <form action="{{ route('admin.appointments.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        <div class="mb-4">
            <label for="cAppointmentID" class="block text-gray-700 font-bold mb-2">Appointment ID:</label>
            <input type="text" name="cAppointmentID" id="cAppointmentID" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="cPatientID" class="block text-gray-700 font-bold mb-2">Patient:</label>
            <select name="cPatientID" id="cPatientID" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                @foreach($patients as $patient)
                <option value="{{ $patient->cPatientID }}">{{ $patient->cName }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="cDoctorID" class="block text-gray-700 font-bold mb-2">Doctor:</label>
            <select name="cDoctorID" id="cDoctorID" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                @foreach($doctors as $doctor)
                <option value="{{ $doctor->cDoctorID }}">{{ $doctor->cName }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="dAppointmentDateTime" class="block text-gray-700 font-bold mb-2">Date & Time:</label>
            <input type="datetime-local" name="dAppointmentDateTime" id="dAppointmentDateTime" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="cPurpose" class="block text-gray-700 font-bold mb-2">Purpose:</label>
            <textarea name="cPurpose" id="cPurpose" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
        </div>
        <div class="mb-4">
            <label for="cStatus" class="block text-gray-700 font-bold mb-2">Status:</label>
            <input type="text" name="cStatus" id="cStatus" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Appointment</button>
    </form>
</div>
@endsection
