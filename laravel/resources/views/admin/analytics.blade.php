@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Analytics</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold mb-2">Patients</h2>
            <p class="text-3xl font-bold">{{ $analyticsData['patients'] ?? 'N/A' }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold mb-2">Appointments</h2>
            <p class="text-3xl font-bold">{{ $analyticsData['appointments'] ?? 'N/A' }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold mb-2">Revenue</h2>
            <p class="text-3xl font-bold">${{ $analyticsData['revenue'] ?? 'N/A' }}</p>
        </div>
    </div>
</div>
@endsection
