@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Appointments</h1>
        <a href="{{ route('admin.appointments.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Appointment</a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">ID</th>
                    <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">Patient</th>
                    <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">Doctor</th>
                    <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">Date</th>
                    <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">Time</th>
                    <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">Status</th>
                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @foreach($appointments as $appointment)
                <tr class="border-b">
                    <td class="text-left py-3 px-4">{{ $appointment->AppointmentID }}</td>
                    <td class="text-left py-3 px-4">
                        {{-- Schema uses First_Name and Last_Name --}}
                        {{ $appointment->patient->First_Name ?? 'N/A' }} {{ $appointment->patient->Last_Name ?? '' }}
                    </td>
                    <td class="text-left py-3 px-4">
                        {{-- Schema uses First_Name and Last_Name --}}
                        {{ $appointment->doctor->First_Name ?? 'N/A' }} {{ $appointment->doctor->Last_Name ?? '' }}
                    </td>
                    <td class="text-left py-3 px-4">{{ $appointment->Date }}</td>
                    <td class="text-left py-3 px-4">{{ $appointment->Time }}</td>
                    <td class="text-left py-3 px-4">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $appointment->Status === 'Scheduled' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $appointment->Status === 'Checked-in' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $appointment->Status === 'Cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ $appointment->Status }}
                        </span>
                    </td>
                    <td class="py-3 px-4 flex gap-2">
                        <a href="{{ route('admin.appointments.edit', $appointment->AppointmentID) }}" class="text-blue-500 hover:text-blue-700 font-semibold">Edit</a>
                        <form action="{{ route('admin.appointments.destroy', $appointment->AppointmentID) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this appointment?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 ml-2 font-semibold">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
