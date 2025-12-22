@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Edit Billing Record: {{ $billing->InvoicedID }}</h1>

    <form action="{{ route('admin.billing.update', $billing->InvoicedID) }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        @method('PUT')

        {{-- Patient Selection --}}
        <div class="mb-4">
            <label for="PatientID" class="block text-gray-700 font-bold mb-2">Patient:</label>
            <select name="PatientID" id="PatientID" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                @foreach($patients as $patient)
                <option value="{{ $patient->PatientID }}"
                    {{ $patient->PatientID == $billing->PatientID ? 'selected' : '' }}>
                    {{ $patient->First_Name }} {{ $patient->Last_Name }} ({{ $patient->PatientID }})
                </option>
                @endforeach
            </select>
        </div>

        {{-- Total Amount --}}
        <div class="mb-4">
            <label for="Total_Amount" class="block text-gray-700 font-bold mb-2">Amount:</label>
            <input type="number" step="0.01" name="Total_Amount" id="Total_Amount" value="{{ $billing->Total_Amount }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        {{-- Issue Date --}}
        <div class="mb-4">
            <label for="IssueDate" class="block text-gray-700 font-bold mb-2">Billing Date:</label>
            <input type="date" name="IssueDate" id="IssueDate" value="{{ $billing->IssueDate }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        {{-- Payment Status --}}
        <div class="mb-4">
            <label for="Payment_Status" class="block text-gray-700 font-bold mb-2">Status:</label>
            <select name="Payment_Status" id="Payment_Status" class="shadow border rounded w-full py-2 px-3">
                <option value="Paid" {{ $billing->Payment_Status == 'Paid' ? 'selected' : '' }}>Paid</option>
                <option value="Unpaid" {{ $billing->Payment_Status == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                <option value="Pending" {{ $billing->Payment_Status == 'Pending' ? 'selected' : '' }}>Pending</option>
            </select>
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Billing Record</button>
    </form>
</div>
@endsection
