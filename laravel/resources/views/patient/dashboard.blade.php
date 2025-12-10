@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Patient Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <a href="{{ route('patient.book-appointment') }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
            <h2 class="text-lg font-semibold mb-2">Book Appointment</h2>
            <p class="text-gray-600">Schedule a new appointment with a doctor.</p>
        </a>
        <a href="{{ route('patient.medical-history') }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
            <h2 class="text-lg font-semibold mb-2">Medical History</h2>
            <p class="text-gray-600">View your past medical records and diagnoses.</p>
        </a>
        <a href="{{ route('patient.prescriptions') }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
            <h2 class="text-lg font-semibold mb-2">Prescriptions</h2>
            <p class="text-gray-600">Access your current and past prescriptions.</p>
        </a>
        <a href="{{ route('patient.billing') }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
            <h2 class="text-lg font-semibold mb-2">Billing</h2>
            <p class="text-gray-600">View your invoices and payment history.</p>
        </a>
        <a href="{{ route('patient.symptom-checker') }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
            <h2 class="text-lg font-semibold mb-2">Symptom Checker</h2>
            <p class="text-gray-600">Check your symptoms for a preliminary diagnosis.</p>
        </a>
    </div>
</div>
@endsection
