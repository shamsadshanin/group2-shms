@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Add Appointment</h1>

    {{-- Error Message Display (Optional but Recommended) --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.appointments.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        @csrf

        {{-- Appointment ID --}}
        <div class="mb-4">
            <label for="AppointmentID" class="block text-gray-700 font-bold mb-2">Appointment ID:</label>
            <input type="text" name="AppointmentID" id="AppointmentID" placeholder="e.g. A-0001" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        {{-- Patient Selection --}}
        <div class="mb-4">
            <label for="PatientID" class="block text-gray-700 font-bold mb-2">Patient:</label>
            <select name="PatientID" id="PatientID" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <option value="">Select Patient</option>
                @foreach($patients as $patient)
                    {{-- SQL Schema অনুযায়ী First_Name এবং Last_Name ব্যবহার করা হয়েছে --}}
                    <option value="{{ $patient->PatientID }}">
                        {{ $patient->First_Name }} {{ $patient->Last_Name }} ({{ $patient->PatientID }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Doctor Selection --}}
        <div class="mb-4">
            <label for="DoctorID" class="block text-gray-700 font-bold mb-2">Doctor:</label>
            <select name="DoctorID" id="DoctorID" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <option value="">Select Doctor</option>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->DoctorID }}">
                        Dr. {{ $doctor->First_Name }} {{ $doctor->Last_Name }} ({{ $doctor->Specialization }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Date and Time Split --}}
        <div class="flex flex-wrap -mx-3 mb-4">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label for="Date" class="block text-gray-700 font-bold mb-2">Date:</label>
                <input type="date" name="Date" id="Date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="w-full md:w-1/2 px-3">
                <label for="Time" class="block text-gray-700 font-bold mb-2">Time:</label>
                <input type="time" name="Time" id="Time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
        </div>

        {{-- Purpose --}}
        <div class="mb-4">
            <label for="Purpose" class="block text-gray-700 font-bold mb-2">Purpose:</label>
            <textarea name="Purpose" id="Purpose" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
        </div>

        {{-- Status --}}
        <div class="mb-4">
            <label for="Status" class="block text-gray-700 font-bold mb-2">Status:</label>
            <select name="Status" id="Status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <option value="Scheduled">Scheduled</option>
                <option value="Checked-in">Checked-in</option>
                <option value="Completed">Completed</option>
                <option value="Cancelled">Cancelled</option>
            </select>
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Appointment</button>
    </form>
</div>
@endsection
