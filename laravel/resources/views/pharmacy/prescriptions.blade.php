@extends('layouts.app')
@section('title', 'All Prescriptions')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Prescriptions') }}
        </h2>
        <a href="{{ route('pharmacy.prescriptions.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
            Create New
        </a>
    </div>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Prescription ID
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Patient Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Doctor Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Issue Date
                                    </th>
                                    {{-- Status Column Removed (Not in SQL) --}}
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">View</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($prescriptions as $prescription)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        {{-- Prescription ID --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $prescription->PrescriptionID }}
                                        </td>

                                        {{-- Patient Name --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $prescription->patient->First_Name ?? 'Unknown' }} {{ $prescription->patient->Last_Name ?? '' }}
                                        </td>

                                        {{-- Doctor Name --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            Dr. {{ $prescription->doctor->First_Name ?? 'Unknown' }} {{ $prescription->doctor->Last_Name ?? '' }}
                                        </td>

                                        {{-- Issue Date --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($prescription->IssueDate)->format('F j, Y') }}
                                        </td>

                                        {{-- View Action --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('pharmacy.prescription-detail', $prescription->PrescriptionID) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 whitespace-nowrap text-sm text-gray-500 text-center">
                                            <div class="flex flex-col items-center">
                                                <i class="fas fa-prescription-bottle-alt fa-2x mb-2 text-gray-300"></i>
                                                <p>No prescriptions found.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination (if you implement it in controller) --}}
                    {{-- <div class="mt-4">
                        {{ $prescriptions->links() }}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
