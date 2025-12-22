@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">My Medical History</h1>
        <a href="{{ route('patient.dashboard') }}" class="text-blue-600 hover:text-blue-800">Back to Dashboard</a>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="w-1/4 text-left py-3 px-4 uppercase font-semibold text-sm">Treatment Date</th>
                        <th class="w-1/4 text-left py-3 px-4 uppercase font-semibold text-sm">Diagnosis / Disease</th>
                        <th class="w-1/2 text-left py-3 px-4 uppercase font-semibold text-sm">Symptoms</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 divide-y divide-gray-200">
                    @forelse($medicalHistory as $record)
                    <tr class="hover:bg-gray-50 transition-colors">
                        {{-- Date Column (Matches SQL: Treatment_Start_Date) --}}
                        <td class="w-1/4 text-left py-4 px-4 align-top">
                            {{ \Carbon\Carbon::parse($record->Treatment_Start_Date)->format('d M, Y') }}
                        </td>

                        {{-- Diagnosis Column (Matches SQL: Disease_Name) --}}
                        <td class="w-1/4 text-left py-4 px-4 align-top font-semibold">
                            {{ $record->Disease_Name }}
                        </td>

                        {{-- Symptoms Column (Matches SQL: Symptoms) --}}
                        <td class="w-1/2 text-left py-4 px-4 align-top text-gray-600">
                            {{ $record->Symptoms }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-8 text-gray-500">
                            <i class="fas fa-file-medical-alt fa-2x mb-2 text-gray-300"></i>
                            <p>No medical history records found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
