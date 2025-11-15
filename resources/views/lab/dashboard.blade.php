@extends('layouts.admin')
@section('title', 'Lab Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Feature 3: Investigation Management -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pending Investigation Requests</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover">
                    <thead>
                        <tr><th>Test ID</th><th>Patient</th><th>Test Name</th><th>Status</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>L-123</td>
                            <td>Kazi Ismat Nahar Epthi</td>
                            <td>Blood Test (CBC)</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                            <td><button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modal-upload-report">Upload Report</button></td>
                        </tr>
                        <tr>
                            <td>L-124</td>
                            <td>MD ABDUS SADIK</td>
                            <td>X-Ray (Chest)</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                            <td><button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modal-upload-report">Upload Report</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL for Uploading Report -->
<div class="modal fade" id="modal-upload-report">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload Report (Test ID: L-123)</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="reportFile">Upload Report File (PDF/Image)</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="reportFile">
                                <label class="custom-file-label" for="reportFile">Choose file</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Upload and Complete</button>
            </div>
        </div>
    </div>
</div>
@endSection
