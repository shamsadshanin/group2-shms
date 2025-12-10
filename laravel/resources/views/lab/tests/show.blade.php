@extends('layouts.app')

@section('title', 'Lab Test Details')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Lab Test Details') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Test Details - {{ $test->cLabTestID }}</h3>
                        <a href="{{ route('lab.tests') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300">Back to Tests</a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">Patient Name</p>
                            <p class="text-lg font-medium text-gray-900">{{ $test->patient->cName }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Test Name</p>
                            <p class="text-lg font-medium text-gray-900">{{ $test->cTestName }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Test Date</p>
                            <p class="text-lg font-medium text-gray-900">{{ $test->dTestDate->format('Y-m-d H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <p class="text-lg font-medium text-gray-900">{{ $test->cStatus }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Result</p>
                            <p class="text-lg font-medium text-gray-900">{{ $test->cResult ?? 'Not yet available' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
