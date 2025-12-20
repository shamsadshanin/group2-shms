@extends('layouts.app')

@section('title', 'Create Prescription')

@section('content')
<div class="container">
    <h1>Create Prescription</h1>

    <form action="{{ route('pharmacy.prescriptions.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="cPatientID">Patient</label>
            <select name="cPatientID" id="cPatientID" class="form-control">
                @foreach($patients as $patient)
                    <option value="{{ $patient->cPatientID }}">{{ $patient->cName }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="cDoctorID">Doctor</label>
            <select name="cDoctorID" id="cDoctorID" class="form-control">
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->cDoctorID }}">{{ $doctor->cName }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="dPrescriptionDate">Prescription Date</label>
            <input type="date" name="dPrescriptionDate" id="dPrescriptionDate" class="form-control">
        </div>

        <div class="form-group">
            <label for="cMedication">Medication</label>
            <textarea name="cMedication" id="cMedication" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="cDosage">Dosage</label>
            <input type="text" name="cDosage" id="cDosage" class="form-control">
        </div>

        <div class="form-group">
            <label for="cInstructions">Instructions</label>
            <textarea name="cInstructions" id="cInstructions" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Create Prescription</button>
    </form>
</div>
@endsection
