@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Add Billing Record</h1>

    <form action="{{ route('admin.billing.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        <div class="mb-4">
            <label for="cBillingID" class="block text-gray-700 font-bold mb-2">Billing ID:</label>
            <input type="text" name="cBillingID" id="cBillingID" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
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
            <label for="fAmount" class="block text-gray-700 font-bold mb-2">Amount:</label>
            <input type="number" name="fAmount" id="fAmount" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="dBillingDate" class="block text-gray-700 font-bold mb-2">Billing Date:</label>
            <input type="date" name="dBillingDate" id="dBillingDate" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="cStatus" class="block text-gray-700 font-bold mb-2">Status:</label>
            <input type="text" name="cStatus" id="cStatus" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Billing Record</button>
    </form>
</div>
@endsection
