@extends('layouts.admin')
@section('title', 'Administrator Dashboard')

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>150</h3>
                <p>Total Patients</p>
            </div>
            <div class="icon"><i class="fas fa-user-injured"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>15</h3>
                <p>Total Doctors</p>
            </div>
            <div class="icon"><i class="fas fa-user-md"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>44</h3>
                <p>Appointments Today</p>
            </div>
            <div class="icon"><i class="fas fa-calendar-alt"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>$12,500</h3>
                <p>Revenue Today</p>
            </div>
            <div class="icon"><i class="fas fa-dollar-sign"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <section class="col-lg-6">
        <!-- Feature 1: Patient Management -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Manage Patients</h3>
                <div class="card-tools">
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-add-patient">
                        <i class="fas fa-plus"></i> Add Patient
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Action</th></tr></thead>
                    <tbody>
                        <tr>
                            <td>P-101</td>
                            <td>Kazi Ismat Nahar Epthi</td>
                            <td>epthi@iub.edu.bd</td>
                            <td><button class="btn btn-xs btn-info">Edit</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <section class="col-lg-6">
        <!-- Feature 2: Doctor Management -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Manage Doctors</h3>
                <div class="card-tools">
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-add-doctor">
                        <i class="fas fa-plus"></i> Add Doctor
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead><tr><th>ID</th><th>Name</th><th>Specialization</th><th>Action</th></tr></thead>
                    <tbody>
                        <tr>
                            <td>D-201</td>
                            <td>Fatema Tug Juhora</td>
                            <td>Cardiology</td>
                            <td><button class="btn btn-xs btn-info">Edit</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endSection
