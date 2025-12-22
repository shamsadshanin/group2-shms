@extends('layouts.app')

@section('title', 'Reports')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Hospital Reports') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Report Generation Form --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-700">Generate Report</h2>
                </div>

                <form action="{{ route('admin.reports') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    @csrf
                    <div>
                        <label for="report_type" class="block text-gray-700 font-bold mb-2">Report Type:</label>
                        <select name="report_type" id="report_type" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="billing_summary" {{ ($reportType ?? '') == 'billing_summary' ? 'selected' : '' }}>Financial / Billing Summary</option>
                            <option value="appointment_trends" {{ ($reportType ?? '') == 'appointment_trends' ? 'selected' : '' }}>Appointment History</option>
                            <option value="patient_demographics" {{ ($reportType ?? '') == 'patient_demographics' ? 'selected' : '' }}>New Patient Registrations</option>
                        </select>
                    </div>
                    <div>
                        <label for="date_range" class="block text-gray-700 font-bold mb-2">Date Range:</label>
                        <input type="text" name="date_range" id="date_range" value="{{ $dateRange ?? '' }}" placeholder="Select Dates" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" required />
                    </div>
                    <div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded shadow transition duration-200">
                            Generate Report
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Report Results Section --}}
        @if(isset($reportData) && count($reportData) > 0)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">
                        Results: {{ ucwords(str_replace('_', ' ', $reportType)) }}
                        <span class="text-sm font-normal text-gray-500 ml-2">({{ $startDate }} to {{ $endDate }})</span>
                    </h2>
                    <button onclick="window.print()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm font-semibold">
                        Print / PDF
                    </button>
                </div>

                {{-- TABLE: BILLING SUMMARY --}}
                @if($reportType === 'billing_summary')
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-sm text-green-600 font-bold uppercase">Total Revenue</p>
                            <p class="text-2xl font-bold text-gray-800">${{ number_format($reportData->where('Payment_Status', 'Paid')->sum('Total_Amount'), 2) }}</p>
                        </div>
                        <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-sm text-red-600 font-bold uppercase">Unpaid / Pending</p>
                            <p class="text-2xl font-bold text-gray-800">${{ number_format($reportData->where('Payment_Status', '!=', 'Paid')->sum('Total_Amount'), 2) }}</p>
                        </div>
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-600 font-bold uppercase">Total Invoices</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $reportData->count() }}</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($reportData as $row)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row->IssueDate }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-600">{{ $row->InvoicedID }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row->patient->First_Name ?? 'N/A' }} {{ $row->patient->Last_Name ?? '' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">${{ number_format($row->Total_Amount, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-bold rounded-full {{ $row->Payment_Status == 'Paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $row->Payment_Status }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                {{-- TABLE: APPOINTMENT TRENDS --}}
                @elseif($reportType === 'appointment_trends')
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($reportData as $row)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row->Date }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($row->Time)->format('h:i A') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Dr. {{ $row->doctor->Last_Name ?? 'Unknown' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row->patient->First_Name ?? 'N/A' }} {{ $row->patient->Last_Name ?? '' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row->Status }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                {{-- TABLE: PATIENT REGISTRATIONS --}}
                @elseif($reportType === 'patient_demographics')
                    <div class="mb-4">
                        <h3 class="font-bold text-gray-700">Total New Registrations: {{ $reportData->count() }}</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Age</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gender</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registered On</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($reportData as $row)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-600">{{ $row->PatientID }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row->First_Name }} {{ $row->Last_Name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row->Age }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row->Gender }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $row->created_at->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
        @elseif(isset($reportData))
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mt-8" role="alert">
                <p class="font-bold">No Records Found</p>
                <p>No data available for the selected report type and date range.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("#date_range", {
        mode: "range",
        dateFormat: "Y-m-d",
        maxDate: "today"
    });
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush
