@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-blue-600 text-white text-lg font-semibold py-4 px-6">
            Book Appointment
        </div>
        <div class="p-6">
            <form action="{{ route('patient.store-appointment') }}" method="POST">
                @csrf

                {{-- Doctor Selection --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Select Doctor</label>
                    <select name="DoctorID" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">-- Choose a Doctor --</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->DoctorID }}">
                                Dr. {{ $doctor->First_Name }} {{ $doctor->Last_Name }} ({{ $doctor->Specialization }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Date --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Date</label>
                    <input type="date" name="Date" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                {{-- Time --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Time</label>
                    <input type="time" name="Time" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                {{-- Purpose --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Purpose / Symptoms</label>
                    <textarea name="Purpose" rows="3" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Describe your problem..."></textarea>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                    Confirm Booking
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
