@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg">
            <div class="bg-gray-200 text-gray-700 text-lg font-semibold py-4 px-6 rounded-t-lg">
                Create New Investigation
            </div>

            <div class="p-6">
                <form action="{{ route('lab.tests.store') }}" method="POST">
                    @csrf

                    {{-- Patient Selection --}}
                    <div class="mb-6">
                        <label for="PatientID" class="block text-gray-700 font-semibold mb-2">Patient</label>
                        <select id="PatientID" name="PatientID" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('PatientID') border-red-500 @enderror" required>
                            <option value="" disabled selected>Select a patient</option>
                            @foreach ($patients as $patient)
                                <option value="{{ $patient->PatientID }}">
                                    {{ $patient->First_Name }} {{ $patient->Last_Name }} ({{ $patient->PatientID }})
                                </option>
                            @endforeach
                        </select>
                        @error('PatientID')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Test Name --}}
                    <div class="mb-6">
                        <label for="Test" class="block text-gray-700 font-semibold mb-2">Test Name (e.g. CBC, Lipid Profile)</label>
                        <input type="text" id="Test" name="Test" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('Test') border-red-500 @enderror" value="{{ old('Test') }}" required>
                        @error('Test')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Test Type --}}
                    <div class="mb-6">
                        <label for="TestType" class="block text-gray-700 font-semibold mb-2">Test Type (e.g. Blood, Urine, X-Ray)</label>
                        <input type="text" id="TestType" name="TestType" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('TestType') border-red-500 @enderror" value="{{ old('TestType') }}" required>
                        @error('TestType')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg transition duration-300">Create Investigation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
