@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg">
            <div class="bg-gray-200 text-gray-700 text-lg font-semibold py-4 px-6 rounded-t-lg">
                Create New Lab Test
            </div>

            <div class="p-6">
                <form action="{{ route('lab.tests.store') }}" method="POST">
                    @csrf

                    <div class="mb-6">
                        <label for="cPatientID" class="block text-gray-700 font-semibold mb-2">Patient</label>
                        <select id="cPatientID" name="cPatientID" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('cPatientID') border-red-500 @enderror" required>
                            <option value="" disabled selected>Select a patient</option>
                            @foreach ($patients as $patient)
                                <option value="{{ $patient->cPatientID }}">{{ $patient->cName }} ({{ $patient->cPatientID }})</option>
                            @endforeach
                        </select>
                        @error('cPatientID')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="cTestName" class="block text-gray-700 font-semibold mb-2">Test Name</label>
                        <input type="text" id="cTestName" name="cTestName" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('cTestName') border-red-500 @enderror" value="{{ old('cTestName') }}" required>
                        @error('cTestName')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg transition duration-300">Create Test</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
