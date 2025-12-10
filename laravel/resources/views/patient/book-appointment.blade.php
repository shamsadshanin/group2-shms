@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Book Appointment</h1>

    <form action="{{ route('patient.store-appointment') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        <div class="mb-4">
            <label for="cDoctorID" class="block text-gray-700 font-bold mb-2">Doctor:</label>
            <select name="cDoctorID" id="cDoctorID" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                @foreach($doctors as $doctor)
                <option value="{{ $doctor->cDoctorID }}">{{ $doctor->cName }} - {{ $doctor->cSpecialization }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="dAppointmentDateTime" class="block text-gray-700 font-bold mb-2">Appointment Date & Time:</label>
            <input type="datetime-local" name="dAppointmentDateTime" id="dAppointmentDateTime" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="cSymptoms" class="block text-gray-700 font-bold mb-2">Symptoms:</label>
            <textarea name="cSymptoms" id="cSymptoms" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Book Appointment</button>
    </form>
</div>
@endsection
