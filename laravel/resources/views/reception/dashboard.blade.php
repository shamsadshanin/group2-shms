@extends('layouts.admin')
@section('title', 'Receptionist Dashboard')

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Feature 2: Patient Check-in -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Patient Check-in</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Search Patient (by ID, Name, or Phone)</label>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Enter Patient ID...">
                        <div class="input-group-append">
                            <button class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </div>
                <hr>
                <h4>Search Results</h4>
                <table class="table">
                    <tbody>
                    <tr>
                        <td>Kazi Ismat Nahar Epthi (P-101)</td>
                        <td>Appointment at 09:00 AM</td>
                        <td><span class="badge bg-warning">Not Checked-in</span></td>
                        <td><button class="btn btn-sm btn-success">Check-in</button></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body">
                <button class="btn btn-primary btn-block">Book New Appointment</button>
            </div>
        </div>
    </div>
</div>
@endSection
