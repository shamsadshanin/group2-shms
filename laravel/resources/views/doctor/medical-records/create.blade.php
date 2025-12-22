@extends('layouts.app')

@section('title', 'Add Medical Record')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="glass-card p-8 bg-white rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">New Medical Record</h2>
        <p class="text-gray-600 mb-6">Patient: {{ $patient->First_Name }} {{ $patient->Last_Name }}</p>

        <form action="{{ route('doctor.medical-records.store', $patient->PatientID) }}" method="POST">
            @csrf

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" name="Date" value="{{ date('Y-m-d') }}" class="form-input w-full rounded-lg border-gray-300">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Diagnosis</label>
                    <textarea name="Diagnosis" rows="3" class="form-textarea w-full rounded-lg border-gray-300" placeholder="Enter detailed diagnosis..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Treatment / Plan</label>
                    <textarea name="Treatment" rows="4" class="form-textarea w-full rounded-lg border-gray-300" placeholder="Enter treatment plan, notes..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Follow Up Date (Optional)</label>
                    <input type="date" name="FollowUpDate" class="form-input w-full rounded-lg border-gray-300">
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-4">
                <a href="{{ route('doctor.patients.history', $patient->PatientID) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Save Record</button>
            </div>
        </form>
    </div>
</div>
@endsection
