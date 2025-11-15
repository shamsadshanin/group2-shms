@extends('layouts.admin')
@section('title', 'Patient Dashboard')

@section('content')
<!-- Small boxes (Stat boxes) -->
<div class="row">
    <div class="col-lg-4 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3>2</h3>
                <p>Upcoming Appointments</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3>5</h3>
                <p>Medical Records</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-medical"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>1</h3>
                <p>Unpaid Bills</p>
            </div>
            <div class="icon">
                <i class="fas fa-receipt"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>
<!-- /.row -->

<!-- Main row -->
<div class="row">
    <!-- Left col -->
    <section class="col-lg-7 connectedSortable">
        <!-- Feature 2: Book Appointment -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Book a New Appointment</h3>
            </div>
            <form>
                <div class="card-body">
                    <div class="form-group">
                        <label>Select Department</label>
                        <select class="form-control">
                            <option>Cardiology</option>
                            <option>Dermatology</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Select Doctor</label>
                        <select class="form-control">
                            <option>Dr. Fatema Tug Juhora</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Select Date</label>
                        <input type="date" class="form-control">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Book Appointment</button>
                </div>
            </form>
        </div>
        
        <!-- Feature 5: AI Symptom Checker -->
        <div class="card card-danger">
            <div class="card-header">
                <h3 class="card-title">AI Symptom Checker</h3>
            </div>
            <form>
                <div class="card-body">
                    <div class="form-group">
                        <label>Enter your symptoms (comma separated)</label>
                        <textarea class="form-control" rows="3" placeholder="e.g., fever, cough, headache..."></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-danger">Get Prediction</button>
                </div>
            </form>
        </div>
    </section>
    
    <!-- right col -->
    <section class="col-lg-5 connectedSortable">
        <!-- Feature 6: Smart Recommendations -->
        <div class="card bg-gradient-success">
            <div class="card-header border-0">
                <h3 class="card-title"><i class="fas fa-heartbeat"></i> Smart Health Recommendations</h3>
            </div>
            <div class="card-body">
                <p>Based on your history, we recommend:</p>
                <ul>
                    <li>Book a follow-up consultation.</li>
                    <li>Complete your annual blood test.</li>
                </ul>
            </div>
        </div>
    </section>
</div>
@endSection
