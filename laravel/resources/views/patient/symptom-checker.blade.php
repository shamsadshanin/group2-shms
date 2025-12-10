@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Symptom Checker</h1>

    <form action="{{ route('patient.check-symptoms') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        <div class="mb-4">
            <label for="symptoms" class="block text-gray-700 font-bold mb-2">Describe your symptoms:</label>
            <textarea name="symptoms" id="symptoms" rows="6" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="e.g., fever, cough, headache"></textarea>
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Check Symptoms</button>
    </form>

    @if(isset($diagnosis))
    <div class="bg-white p-6 rounded-lg shadow-md mt-8">
        <h2 class="text-lg font-semibold mb-4">Preliminary Diagnosis</h2>
        <p>{{ $diagnosis }}</p>
    </div>
    @endif
</div>
@endsection
