@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Edit Doctor: {{ $doctor->DoctorID }}</h1>

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

    <form action="{{ route('admin.doctors.update', $doctor->DoctorID) }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        @method('PUT')

        {{-- ID & Email (ID is Readonly) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="DoctorID" class="block text-gray-700 font-bold mb-2">Doctor ID:</label>
                <input type="text" name="DoctorID" id="DoctorID" value="{{ $doctor->DoctorID }}"
                       class="bg-gray-100 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       readonly>
            </div>
            <div>
                <label for="Email" class="block text-gray-700 font-bold mb-2">Email:</label>
                <input type="email" name="Email" id="Email" value="{{ $doctor->Email }}"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
        </div>

        {{-- Name Fields --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="First_Name" class="block text-gray-700 font-bold mb-2">First Name:</label>
                <input type="text" name="First_Name" id="First_Name" value="{{ $doctor->First_Name }}"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div>
                <label for="Last_Name" class="block text-gray-700 font-bold mb-2">Last Name:</label>
                <input type="text" name="Last_Name" id="Last_Name" value="{{ $doctor->Last_Name }}"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
        </div>

        {{-- Specialization & Contact --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="Specialization" class="block text-gray-700 font-bold mb-2">Specialization:</label>
                <input type="text" name="Specialization" id="Specialization" value="{{ $doctor->Specialization }}"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div>
                <label for="Contact_Number" class="block text-gray-700 font-bold mb-2">Contact Number:</label>
                {{-- Fetching from the passed $contact object --}}
                <input type="text" name="Contact_Number" id="Contact_Number" value="{{ $contact->Contact_Number ?? '' }}"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
        </div>

        <hr class="my-6 border-gray-300">
        <h3 class="text-lg font-bold text-gray-700 mb-4">Availability Schedule</h3>

        {{-- Availability Fields --}}
        <div class="mb-4">
            <label for="Available_Days" class="block text-gray-700 font-bold mb-2">Available Days:</label>
            <input type="text" name="Available_Days" id="Available_Days" value="{{ $availability->Available_Days ?? '' }}"
                   placeholder="e.g. Monday, Wednesday, Friday" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label for="Start_Time" class="block text-gray-700 font-bold mb-2">Start Time:</label>
                <input type="time" name="Start_Time" id="Start_Time" value="{{ $availability->Start_Time ?? '' }}"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div>
                <label for="End_Time" class="block text-gray-700 font-bold mb-2">End Time:</label>
                <input type="time" name="End_Time" id="End_Time" value="{{ $availability->End_Time ?? '' }}"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Doctor</button>
    </form>
</div>
@endsection
