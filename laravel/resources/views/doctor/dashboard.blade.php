@extends('layouts.admin')
@section('title', 'Doctor Dashboard')

@section('content')
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>12</h3>
                <p>Appointments Today</p>
            </div>
            <div class="icon"><i class="fas fa-calendar-alt"></i></div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>3</h3>
                <p>Pending Lab Reports</p>
            </div>
            <div class="icon"><i class="fas fa-flask"></i></div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>250</h3>
                <p>Total Patients</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <section class="col-lg-12">
        <!-- Feature 2: Appointment Management -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Today's Appointments</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover">
                    <thead>
                        <tr><th>Time</th><th>Patient</th><th>Reason</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>09:00 AM</td>
                            <td>Kazi Ismat Nahar Epthi</td>
                            <td>Follow-up Checkup</td>
                            <td>
                                <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modal-create-prescription">Prescribe</button>
                                <button class="btn btn-xs btn-info">View History</button>
                            </td>
                        </tr>
                        <tr>
                            <td>09:30 AM</td>
                            <td>MD ABDUS SADIK</td>
                            <td>New Patient Consultation</td>
                            <td>
                                <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modal-create-prescription">Prescribe</button>
                                <button class="btn btn-xs btn-info">View History</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!-- MODAL for Feature 3: Create Prescription -->
<div class="modal fade" id="modal-create-prescription">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create Prescription for (Patient Name)</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label>Diagnosis</label>
                        <input type="text" class="form-control" placeholder="e.g., Seasonal Flu">
                    </div>
                    <div class="form-group">
                        <label>Medicines</label>
                        <textarea class="form-control" rows="4" placeholder="e.g., Paracetamol 500mg (1-1-1) after meal"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Lab Tests Suggested</label>
                        <input type="text" class="form-control" placeholder="e.g., CBC, Blood Sugar Fasting">
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save and Issue Prescription</button>
            </div>
        </div>
    </div>
</div>
@endSection
