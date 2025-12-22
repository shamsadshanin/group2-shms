@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Hospital Analytics</h1>
            <p class="text-gray-500 text-sm mt-1">Real-time overview of hospital performance</p>
        </div>
        <button onclick="window.print()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm font-semibold flex items-center shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print Report
        </button>
    </div>

    {{-- Key Metrics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-xl shadow-lg text-white">
            <h2 class="text-blue-100 text-xs font-bold uppercase tracking-wider">Total Patients</h2>
            <p class="text-4xl font-extrabold mt-2">{{ $analyticsData['patients'] }}</p>
            <div class="mt-4 text-sm text-blue-100 flex items-center">
                <span class="bg-blue-400 bg-opacity-30 rounded px-2 py-1">Registered</span>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 p-6 rounded-xl shadow-lg text-white">
            <h2 class="text-green-100 text-xs font-bold uppercase tracking-wider">Total Appointments</h2>
            <p class="text-4xl font-extrabold mt-2">{{ $analyticsData['appointments'] }}</p>
            <div class="mt-4 text-sm text-green-100 flex items-center">
                <span class="bg-green-400 bg-opacity-30 rounded px-2 py-1">All Time</span>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-6 rounded-xl shadow-lg text-white">
            <h2 class="text-purple-100 text-xs font-bold uppercase tracking-wider">Total Revenue</h2>
            <p class="text-4xl font-extrabold mt-2">${{ $analyticsData['revenue'] }}</p>
            <div class="mt-4 text-sm text-purple-100 flex items-center">
                <span class="bg-purple-400 bg-opacity-30 rounded px-2 py-1">Paid Invoices</span>
            </div>
        </div>
    </div>

    {{-- Detailed Segmentation --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <span class="w-1 h-6 bg-blue-500 rounded mr-2"></span>
                Patient Segmentation
            </h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-200 rounded-full mr-3 text-blue-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-gray-700 font-medium">Insured Patients</span>
                    </div>
                    <span class="font-bold text-blue-700 text-xl">{{ $analyticsData['insured'] }}</span>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-2 bg-gray-200 rounded-full mr-3 text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <span class="text-gray-700 font-medium">Non-Insured Patients</span>
                    </div>
                    <span class="font-bold text-gray-700 text-xl">{{ $analyticsData['non_insured'] }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 flex flex-col justify-center items-center text-center">
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mb-4 text-yellow-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-700 mb-2">System Insight</h3>
            <p class="text-gray-500 text-sm px-6 leading-relaxed">
                "Revenue calculation is strictly based on the <span class="font-mono text-xs bg-gray-100 px-1 py-0.5 rounded text-gray-700">Total_Amount</span> column where <span class="font-mono text-xs bg-gray-100 px-1 py-0.5 rounded text-gray-700">Payment_Status</span> is 'Paid'."
            </p>
        </div>
    </div>
</div>
@endsection
