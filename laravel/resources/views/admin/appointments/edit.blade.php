@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Edit Appointment: {{ $appointment->AppointmentID }}</h1>

    {{-- Error Display --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.appointments.update', $appointment->AppointmentID) }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        @method('PUT')

        {{-- Patient Selection --}}
        <div class="mb-4">
            <label for="PatientID" class="block text-gray-700 font-bold mb-2">Patient:</label>
            <select name="PatientID" id="PatientID" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                @foreach($patients as $patient)
                <option value="{{ $patient->PatientID }}"
                    {{ $patient->PatientID == $appointment->PatientID ? 'selected' : '' }}>
                    {{ $patient->First_Name }} {{ $patient->Last_Name }} ({{ $patient->PatientID }})
                </option>
                @endforeach
            </select>
        </div>

        {{-- Doctor Selection --}}
        <div class="mb-4">
            <label for="DoctorID" class="block text-gray-700 font-bold mb-2">Doctor:</label>
            <select name="DoctorID" id="DoctorID" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                @foreach($doctors as $doctor)
                <option value="{{ $doctor->DoctorID }}"
                    {{ $doctor->DoctorID == $appointment->DoctorID ? 'selected' : '' }}>
                    Dr. {{ $doctor->First_Name }} {{ $doctor->Last_Name }} ({{ $doctor->Specialization }})
                </option>
                @endforeach
            </select>
        </div>

        {{-- Date and Time (Split fields based on new Schema) --}}
        <div class="flex flex-wrap -mx-3 mb-4">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label for="Date" class="block text-gray-700 font-bold mb-2">Date:</label>
                <input type="date" name="Date" id="Date" value="{{ $appointment->Date }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="w-full md:w-1/2 px-3">
                <label for="Time" class="block text-gray-700 font-bold mb-2">Time:</label>
                {{-- Ensure time is formatted to H:i for the input --}}
                <input type="time" name="Time" id="Time" value="{{ \Carbon\Carbon::parse($appointment->Time)->format('H:i') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
        </div>

        {{-- Purpose --}}
        <div class="mb-4">
            <label for="Purpose" class="block text-gray-700 font-bold mb-2">Purpose:</label>
            <textarea name="Purpose" id="Purpose" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>{{ $appointment->Purpose }}</textarea>
        </div>

        {{-- Status --}}
        <div class="mb-4">
            <label for="Status" class="block text-gray-700 font-bold mb-2">Status:</label>
            <select name="Status" id="Status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <option value="Scheduled" {{ $appointment->Status == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                <option value="Checked-in" {{ $appointment->Status == 'Checked-in' ? 'selected' : '' }}>Checked-in</option>
                <option value="Completed" {{ $appointment->Status == 'Completed' ? 'selected' : '' }}>Completed</option>
                <option value="Cancelled" {{ $appointment->Status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Appointment</button>
    </form>
</div>
@endsection
