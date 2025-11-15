@extends('layouts.admin')
@section('title', 'Pharmacy Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Feature 3: Prescription Management -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pending Prescriptions</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover">
                    <thead>
                        <tr><th>Prescription ID</th><th>Patient</th><th>Doctor</th><th>Status</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>P-1052</td>
                            <td>Kazi Ismat Nahar Epthi</td>
                            <td>Dr. Juhora</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                            <td><button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modal-view-prescription">View & Fulfill</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL for Viewing Prescription -->
<div class="modal fade" id="modal-view-prescription">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Prescription #P-1052</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <dl>
                    <dt>Patient</dt>
                    <dd>Kazi Ismat Nahar Epthi</dd>
                    <dt>Doctor</dt>
                    <dd>Dr. Juhora</dd>
                    <dt>Medicines</dt>
                    <dd>Paracetamol 500mg (1-1-1)</dd>
                </dl>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success">Mark as Fulfilled</button>
            </div>
        </div>
    </div>
</div>
@endSection
