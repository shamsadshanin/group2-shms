@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Hospital Analytics</h1>
        <button onclick="window.print()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm font-semibold">
            Print Report
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-blue-600 p-6 rounded-xl shadow-lg text-white">
            <h2 class="text-blue-100 text-sm font-semibold uppercase">Total Patients</h2>
            <p class="text-4xl font-bold mt-2">{{ $analyticsData['patients'] }}</p>
            <div class="mt-4 text-sm text-blue-200">Registered in system</div>
        </div>

        <div class="bg-green-600 p-6 rounded-xl shadow-lg text-white">
            <h2 class="text-green-100 text-sm font-semibold uppercase">Total Appointments</h2>
            <p class="text-4xl font-bold mt-2">{{ $analyticsData['appointments'] }}</p>
            <div class="mt-4 text-sm text-green-200">Total visits booked</div>
        </div>

        <div class="bg-purple-600 p-6 rounded-xl shadow-lg text-white">
            <h2 class="text-purple-100 text-sm font-semibold uppercase">Total Revenue</h2>
            <p class="text-4xl font-bold mt-2">${{ $analyticsData['revenue'] }}</p>
            <div class="mt-4 text-sm text-purple-200">From paid billing records</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Patient Segmentation</h3>
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg mb-4">
                <span class="text-gray-600">Insured Patients</span>
                <span class="font-bold text-blue-600 text-xl">{{ $analyticsData['insured'] }}</span>
            </div>
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <span class="text-gray-600">Non-Insured Patients</span>
                <span class="font-bold text-gray-800 text-xl">{{ $analyticsData['non_insured'] }}</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 flex flex-col justify-center text-center">
            <h3 class="text-lg font-bold text-gray-700 mb-2">Efficiency Tip</h3>
            <p class="text-gray-500 italic">"Ensure all billing records are marked as 'Paid' to reflect accurate revenue in this analytics dashboard."</p>
        </div>
    </div>
</div>
@endsection
