@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1 class="m-0 text-dark">Dashboard</h1>
        <p class="m-0 text-muted">Welcome, {{ Auth::user()->name }}!</p>
    </div>
@stop

@section('content')
    <div class="row">
        {{-- Admin Specific Cards --}}
        @if(Auth::user()->role == 'admin')
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ DB::table('users')->count() }}</h3>
                        <p>Total Users</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ DB::table('tbldoctor')->count() }}</h3>
                        <p>Total Doctors</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <a href="{{ route('admin.doctors.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ DB::table('tblpatient')->count() }}</h3>
                        <p>Total Patients</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-injured"></i>
                    </div>
                    <a href="{{ route('admin.patients.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ DB::table('tblappointment')->where('cStatus', 'pending')->count() }}</h3>
                        <p>Pending Appointments</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <a href="{{ route('admin.appointments.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        @endif

        {{-- Doctor Specific Cards --}}
        @if(Auth::user()->role == 'doctor')
            <div class="col-lg-4 col-md-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ DB::table('tblappointment')->where('cDoctorID', Auth::user()->id)->whereDate('dAppointmentDateTime', today())->count() }}</h3>
                        <p>Today's Appointments</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <a href="{{ route('doctor.appointments') }}" class="small-box-footer">View All <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
             <div class="col-lg-4 col-md-6">
                <div class="small-box bg-teal">
                    <div class="inner">
                        <h3>{{ DB::table('tblappointment')->where('cDoctorID', Auth::user()->id)->where('cStatus', 'completed')->count() }}</h3>
                        <p>Completed Consultations</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <a href="{{ route('doctor.appointments') }}" class="small-box-footer">View History <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        @endif

        {{-- Patient Specific Cards --}}
        @if(Auth::user()->role == 'patient')
            <div class="col-lg-4 col-md-6">
                <div class="small-box bg-gradient-primary">
                    <div class="inner">
                        <h3>{{ DB::table('tblappointment')->where('cPatientID', Auth::user()->id)->where('cStatus', 'pending')->count() }}</h3>
                        <p>Upcoming Appointments</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <a href="#" class="small-box-footer">View Details <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="small-box bg-gradient-success">
                    <div class="inner">
                        <h3>{{ DB::table('tblprescription')->where('cPatientID', Auth::user()->id)->count() }}</h3>
                        <p>My Prescriptions</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-pills"></i>
                    </div>
                    <a href="{{ route('patient.prescriptions') }}" class="small-box-footer">View All <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
             <div class="col-lg-4 col-md-6">
                <div class="small-box bg-gradient-info">
                    <div class="inner">
                        <h3>Check Now</h3>
                        <p>AI Symptom Checker</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <a href="{{ route('patient.symptom-checker') }}" class="small-box-footer">Start Diagnosis <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        @endif

        {{-- Receptionist Specific Cards --}}
         @if(Auth::user()->role == 'reception')
            <div class="col-lg-4 col-md-6">
                <div class="small-box bg-indigo">
                    <div class="inner">
                        <h3>{{ DB::table('tblappointment')->whereDate('dAppointmentDateTime', today())->count() }}</h3>
                        <p>Today's Total Appointments</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <a href="{{ route('reception.appointments') }}" class="small-box-footer">View All <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="small-box bg-purple">
                    <div class="inner">
                       <h3>{{ DB::table('tblpatient')->whereDate('created_at', today())->count() }}</h3>
                        <p>New Patient Registrations</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <a href="{{ route('reception.patients') }}" class="small-box-footer">View Patients <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        @endif

        {{-- Lab Technician Specific Cards --}}
        @if(Auth::user()->role == 'lab')
            <div class="col-lg-4 col-md-6">
                <div class="small-box bg-olive">
                    <div class="inner">
                        <h3>{{ DB::table('tbllabtest')->where('cStatus', 'pending')->count() }}</h3>
                        <p>Pending Lab Tests</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-flask"></i>
                    </div>
                    <a href="{{ route('lab.tests') }}" class="small-box-footer">View All <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        @endif

        {{-- Pharmacist Specific Cards --}}
        @if(Auth::user()->role == 'pharmacy')
            <div class="col-lg-4 col-md-6">
                <div class="small-box bg-maroon">
                    <div class="inner">
                       <h3>{{ DB::table('tblprescription')->count() }}</h3>
                        <p>Active Prescriptions</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-pills"></i>
                    </div>
                    <a href="{{ route('pharmacy.prescriptions') }}" class="small-box-footer">View All <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        @endif
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Recent Activity</h3>
                </div>
                <div class="card-body">
                    <p>Welcome to the Smart Healthcare Management System. Here you can manage your appointments, view medical records, and much more.</p>
                     <p>Use the sidebar to navigate to different sections of the application.</p>
                </div>
            </div>
        </div>
    </div>
@stop
