@extends('layouts.app')

@section('title', 'Register New Patient')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Register New Patient') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{-- Alpine JS Data Scope --}}
                    <form x-data="{ patientType: '{{ old('PatientType', 'General') }}' }"
                          action="{{ route('reception.patients.store') }}" method="POST">
                        @csrf

                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Personal Information</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">First Name</label>
                                <input type="text" name="First_Name" value="{{ old('First_Name') }}" required
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Last Name</label>
                                <input type="text" name="Last_Name" value="{{ old('Last_Name') }}" required
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input type="tel" name="Contact_Number" value="{{ old('Contact_Number') }}" required
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email (Optional)</label>
                                <input type="email" name="Email" value="{{ old('Email') }}"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Age</label>
                                <input type="number" name="Age" value="{{ old('Age') }}" required min="0"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Gender</label>
                                <select name="Gender" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                                    <option value="" disabled selected>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <input type="text" name="Street" placeholder="Street Address" required class="md:col-span-3 px-3 py-2 border rounded-md">
                                <input type="text" name="City" placeholder="City" required class="px-3 py-2 border rounded-md">
                                <input type="text" name="Zip" placeholder="Zip Code" required class="px-3 py-2 border rounded-md">
                            </div>
                        </div>

                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Patient Type & Insurance</h3>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Patient Category</label>
                            <select name="PatientType" x-model="patientType" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="General">General / Non-Insured</option>
                                <option value="Insured">Insured Patient</option>
                            </select>
                        </div>

                        <div x-show="patientType === 'Insured'" x-transition class="bg-blue-50 p-4 rounded-md border border-blue-200 mb-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-blue-800">Policy Number</label>
                                    <input type="text" name="Policy_Number" placeholder="e.g. 0291212812"
                                           class="mt-1 block w-full px-3 py-2 border border-blue-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-blue-800">Provider Name</label>
                                    <input type="text" name="Provider_Name" placeholder="e.g. Mustain BD"
                                           class="mt-1 block w-full px-3 py-2 border border-blue-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-blue-800">Coverage Limit</label>
                                    <input type="number" step="0.01" name="Coverage_Limit" placeholder="e.g. 50000.00"
                                           class="mt-1 block w-full px-3 py-2 border border-blue-300 rounded-md shadow-sm">
                                </div>
                            </div>
                            @if ($errors->any())
                                <div class="mt-2 text-red-600 text-sm">
                                    Please check insurance fields if you selected 'Insured'.
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('reception.patients') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 shadow-md transition">
                                Register Patient
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="//unpkg.com/alpinejs" defer></script>
@endsection
