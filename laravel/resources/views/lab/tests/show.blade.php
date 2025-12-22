@extends('layouts.app')

@section('title', 'Investigation Details')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Investigation Details') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-900">
                            Investigation #{{ $test->InvestigationID }}
                        </h3>
                        <a href="{{ route('lab.tests') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                            Back to Tests
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="border-b pb-2">
                            <p class="text-sm text-gray-500 font-semibold uppercase">Patient Name</p>
                            <p class="text-lg font-medium text-gray-900">
                                {{ $test->patient->First_Name ?? 'Unknown' }} {{ $test->patient->Last_Name ?? '' }}
                            </p>
                        </div>

                        <div class="border-b pb-2">
                            <p class="text-sm text-gray-500 font-semibold uppercase">Test Name</p>
                            <p class="text-lg font-medium text-gray-900">{{ $test->Test }}</p>
                        </div>

                        <div class="border-b pb-2">
                            <p class="text-sm text-gray-500 font-semibold uppercase">Test Type</p>
                            <p class="text-lg font-medium text-gray-900">{{ $test->TestType }}</p>
                        </div>

                        <div class="border-b pb-2">
                            <p class="text-sm text-gray-500 font-semibold uppercase">Status</p>
                            <p class="text-lg font-medium">
                                @if($test->Result_Summary)
                                    <span class="text-green-600 font-bold">Completed</span>
                                @else
                                    <span class="text-yellow-600 font-bold">Pending</span>
                                @endif
                            </p>
                        </div>

                        <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm text-gray-500 font-semibold uppercase mb-2">Result Summary</p>
                            <p class="text-lg text-gray-900 whitespace-pre-line font-mono">
                                {{ $test->Result_Summary ?? 'Result is pending entry.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
