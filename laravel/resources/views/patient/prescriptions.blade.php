@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">My Prescriptions</h1>

    <div class="bg-white rounded-lg shadow-md">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="w-1/4 text-left py-3 px-4 uppercase font-semibold text-sm">Date</th>
                    <th class="w-1/4 text-left py-3 px-4 uppercase font-semibold text-sm">Doctor</th>
                    <th class="w-1/2 text-left py-3 px-4 uppercase font-semibold text-sm">Medication</th>
                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Status</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @forelse($prescriptions as $prescription)
                <tr>
                    <td class="w-1/4 text-left py-3 px-4">{{ $prescription->dDate }}</td>
                    <td class="w-1/4 text-left py-3 px-4">{{ $prescription->doctor->cName }}</td>
                    <td class="w-1/2 text-left py-3 px-4">{{ $prescription->cMedication }}</td>
                    <td class="text-left py-3 px-4">{{ $prescription->cStatus }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4">No prescriptions found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
