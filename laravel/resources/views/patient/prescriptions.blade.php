@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">My Prescriptions</h1>
        <a href="{{ route('patient.dashboard') }}" class="text-blue-600 hover:text-blue-800">Back to Dashboard</a>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">Date</th>
                        <th class="w-1/4 text-left py-3 px-4 uppercase font-semibold text-sm">Doctor</th>
                        <th class="w-1/2 text-left py-3 px-4 uppercase font-semibold text-sm">Medications</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 divide-y divide-gray-200">
                    @forelse($prescriptions as $prescription)
                    <tr class="hover:bg-gray-50 transition-colors">
                        {{-- Date: Matches 'IssueDate' column --}}
                        <td class="w-1/6 text-left py-4 px-4 align-top font-medium">
                            {{ \Carbon\Carbon::parse($prescription->IssueDate)->format('d M, Y') }}
                        </td>

                        {{-- Doctor: Matches relationship to 'Doctor' table --}}
                        <td class="w-1/4 text-left py-4 px-4 align-top">
                            <span class="font-bold text-gray-800">Dr. {{ $prescription->doctor->First_Name ?? '' }} {{ $prescription->doctor->Last_Name ?? '' }}</span>
                            <br>
                            <span class="text-xs text-gray-500">{{ $prescription->doctor->Specialization ?? '' }}</span>
                        </td>

                        {{-- Medications: Loop through 'Prescription_Medicine' table --}}
                        <td class="w-1/2 text-left py-4 px-4 align-top">
                            @if($prescription->medicines->isNotEmpty())
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach($prescription->medicines as $med)
                                        <li class="text-sm">
                                            <span class="font-semibold text-gray-700">{{ $med->Medicine_Name }}</span>
                                            <span class="text-gray-500 text-xs"> - {{ $med->Dosage }} ({{ $med->Frequency }})</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-gray-400 italic text-sm">No medicines listed.</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-8 text-gray-500">
                            <i class="fas fa-prescription-bottle-alt fa-2x mb-2 text-gray-300"></i>
                            <p>No prescriptions found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
