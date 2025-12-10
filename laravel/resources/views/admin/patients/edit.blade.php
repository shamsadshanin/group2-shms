@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Edit Patient</h1>

    <form action="{{ route('admin.patients.update', $patient->cPatientID) }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="cName" class="block text-gray-700 font-bold mb-2">Name:</label>
            <input type="text" name="cName" id="cName" value="{{ $patient->cName }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="cEmail" class="block text-gray-700 font-bold mb-2">Email:</label>
            <input type="email" name="cEmail" id="cEmail" value="{{ $patient->cEmail }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="cContactNumber" class="block text-gray-700 font-bold mb-2">Contact Number:</label>
            <input type="text" name="cContactNumber" id="cContactNumber" value="{{ $patient->cContactNumber }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Patient</button>
    </form>
</div>
@endsection
