@extends('layouts.app')

@section('title', 'Reports')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Reports') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-lg font-semibold mb-4">Generate Report</h2>
                <form action="{{ route('admin.reports') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="report_type" class="block text-gray-700 font-bold mb-2">Report Type:</label>
                        <select name="report_type" id="report_type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="patient_demographics">Patient Demographics</option>
                            <option value="appointment_trends">Appointment Trends</option>
                            <option value="billing_summary">Billing Summary</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="date_range" class="block text-gray-700 font-bold mb-2">Date Range:</label>
                        <input type="text" name="date_range" id="date_range" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                    </div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Generate</button>
                </form>
            </div>
        </div>

        @if(isset($reportData))
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-8">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-lg font-semibold mb-4">Report Results</h2>
                <pre class="bg-gray-100 p-4 rounded">{{ json_encode($reportData, JSON_PRETTY_PRINT) }}</pre>
            </div>
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
    });
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush
