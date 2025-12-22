@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Patients Management</h1>
        <a href="{{ route('admin.patients.create') }}" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded transition duration-200">
            + Add New Patient
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-800 text-white">
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">Patient ID</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">Name</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">Email</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">Phone</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">Type</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @foreach($patients as $patient)
                <tr class="hover:bg-gray-50">
                    {{-- Patient ID --}}
                    <td class="px-5 py-5 border-b border-gray-200 text-sm">
                        <p class="whitespace-no-wrap font-mono">{{ $patient->PatientID }}</p>
                    </td>

                    {{-- Name (First + Last) --}}
                    <td class="px-5 py-5 border-b border-gray-200 text-sm">
                        <p class="whitespace-no-wrap font-bold">{{ $patient->First_Name }} {{ $patient->Last_Name }}</p>
                    </td>

                    {{-- Email --}}
                    <td class="px-5 py-5 border-b border-gray-200 text-sm">
                        <p class="whitespace-no-wrap">{{ $patient->Email }}</p>
                    </td>

                    {{-- Phone (Fetched from Patient_Number) --}}
                    <td class="px-5 py-5 border-b border-gray-200 text-sm">
                        <p class="whitespace-no-wrap">{{ $patient->Contact_Number ?? 'N/A' }}</p>
                    </td>

                    {{-- Type (Insured/Non-Insured) --}}
                    <td class="px-5 py-5 border-b border-gray-200 text-sm">
                        {{-- Checked via Insured_Patient table existence --}}
                        @if($patient->is_insured)
                            <span class="relative inline-block px-3 py-1 font-semibold text-blue-900 leading-tight">
                                <span aria-hidden class="absolute inset-0 bg-blue-200 opacity-50 rounded-full"></span>
                                <span class="relative text-xs">Insured</span>
                            </span>
                        @else
                            <span class="relative inline-block px-3 py-1 font-semibold text-gray-900 leading-tight">
                                <span aria-hidden class="absolute inset-0 bg-gray-200 opacity-50 rounded-full"></span>
                                <span class="relative text-xs">Non-Insured</span>
                            </span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td class="px-5 py-5 border-b border-gray-200 text-sm">
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('admin.patients.edit', $patient->PatientID) }}" class="text-blue-600 hover:text-blue-900 font-bold">
                                Edit
                            </a>
                            <form action="{{ route('admin.patients.destroy', $patient->PatientID) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete Patient {{ $patient->PatientID }}? This cannot be undone.');"
                                  class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
