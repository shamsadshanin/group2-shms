@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Medical History</h1>

    <div class="bg-white rounded-lg shadow-md">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="w-1/4 text-left py-3 px-4 uppercase font-semibold text-sm">Date</th>
                    <th class="w-1/4 text-left py-3 px-4 uppercase font-semibold text-sm">Doctor</th>
                    <th class="w-1/2 text-left py-3 px-4 uppercase font-semibold text-sm">Diagnosis</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @forelse($medicalHistory as $record)
                <tr>
                    <td class="w-1/4 text-left py-3 px-4">{{ $record->dDate }}</td>
                    <td class="w-1/4 text-left py-3 px-4">{{ $record->doctor->cName }}</td>
                    <td class="w-1/2 text-left py-3 px-4">{{ $record->cDiagnosis }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center py-4">No medical history found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
