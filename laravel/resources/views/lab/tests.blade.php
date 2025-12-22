@extends('layouts.app')

@section('title', 'All Investigations')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('All Investigations') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    {{-- Action Bar --}}
                    <div class="flex justify-between items-center mb-6">
                        <p class="text-sm text-gray-500">List of all investigations assigned to you.</p>
                        <a href="{{ route('lab.tests.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 shadow-sm text-sm">
                            <i class="fas fa-plus mr-2"></i> Create New
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Patient Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Test Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Type
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">View</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($tests as $test)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        {{-- Investigation ID --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">
                                            {{ $test->InvestigationID }}
                                        </td>

                                        {{-- Patient Name --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $test->patient->First_Name ?? 'Unknown' }} {{ $test->patient->Last_Name ?? '' }}
                                        </td>

                                        {{-- Test Name --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-medium">
                                            {{ $test->Test }}
                                        </td>

                                        {{-- Test Type --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $test->TestType }}
                                        </td>

                                        {{-- Status Logic: Check if Result_Summary exists --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($test->Result_Summary)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Completed
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            @endif
                                        </td>

                                        {{-- View Action --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('lab.tests.show', $test->InvestigationID) }}" class="text-blue-600 hover:text-blue-900 font-bold">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-10 whitespace-nowrap text-sm text-gray-500 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class="fas fa-microscope fa-2x mb-2 text-gray-300"></i>
                                                <p>No investigations found.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
