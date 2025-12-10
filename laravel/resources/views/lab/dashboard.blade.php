@extends('layouts.app')

@section('title', 'Lab Dashboard')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Lab Dashboard') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Lab Technician Dashboard</h3>
                        <a href="{{ route('lab.tests.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300">Create Test</a>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">Welcome, {{ $labTechnician->cName }}. Here is a summary of your current workload.</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Pending Tests -->
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4">
                            <h4 class="text-lg font-bold">Pending Tests</h4>
                            <p class="text-2xl">{{ $pendingTests->count() }}</p>
                        </div>

                        <!-- In-Progress Tests -->
                        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4">
                            <h4 class="text-lg font-bold">In-Progress Tests</h4>
                            <p class="text-2xl">{{ $inProgressTests->count() }}</p>
                        </div>

                        <!-- Completed Tests (Today) -->
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4">
                            <h4 class="text-lg font-bold">Completed Today</h4>
                            <p class="text-2xl">{{ $completedTests->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
