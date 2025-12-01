@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">User Management</h1>
            <p class="mb-0">Manage system users, roles, and permissions</p>
        </div>
        <div>
            <button class="btn btn-primary" data-toggle="modal" data-target="#createUserModal">
                <i class="fas fa-user-plus fa-sm"></i> Add New User
            </button>
        </div>
    </div>

    <!-- User Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-6 mb-4">
            <div class="card border-left-primary h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $userStats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-6 mb-4">
            <div class="card border-left-success h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Administrators</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $userStats['admins'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-6 mb-4">
            <div class="card border-left-info h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Doctors</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $userStats['doctors'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-md fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-6 mb-4">
            <div class="card border-left-warning h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Patients</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $userStats['patients'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-injured fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-6 mb-4">
            <div class="card border-left-secondary h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Lab Technicians</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $userStats['lab_technicians'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-vial fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users') }}" method="GET" class="form-inline">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text"
                               name="search"
                               class="form-control w-100"
                               placeholder="Search by name or email..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="role" class="form-control w-100">
                            <option value="all" {{ request('role') == 'all' ? 'selected' : '' }}>All Roles</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="doctor" {{ request('role') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                            <option value="patient" {{ request('role') == 'patient' ? 'selected' : '' }}>Patient</option>
                            <option value="lab_technician" {{ request('role') == 'lab_technician' ? 'selected' : '' }}>Lab Technician</option>
                            <option value="receptionist" {{ request('role') == 'receptionist' ? 'selected' : '' }}>Receptionist</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control w-100">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">User List</h6>
            <span class="badge badge-primary">Total: {{ $users->total() }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Last Login</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <div class="avatar-circle">
                                            <span class="initials">{{ substr($user->name, 0, 2) }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold">{{ $user->name }}</div>
                                        <div class="text-muted small">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-{{ $user->getRoleBadgeClass() }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                                @if($user->getProfile())
                                <div class="small text-muted mt-1">
                                    {{ $user->getProfile()->getTypeSpecificInfo() }}
                                </div>
                                @endif
                            </td>
                            <td>
                                @if($user->is_active)
                                <span class="badge badge-success">
                                    <i class="fas fa-check-circle"></i> Active
                                </span>
                                @else
                                <span class="badge badge-danger">
                                    <i class="fas fa-times-circle"></i> Inactive
                                </span>
                                @endif
                            </td>
                            <td>
                                @if($user->last_login_at)
                                <span class="text-muted">
                                    {{ $user->last_login_at->diffForHumans() }}
                                </span>
                                <div class="small text-muted">
                                    {{ $user->last_login_at->format('M d, Y h:i A') }}
                                </div>
                                @else
                                <span class="text-muted">Never</span>
                                @endif
                            </td>
                            <td>
                                {{ $user->created_at->format('M d, Y') }}
                                <div class="small text-muted">
                                    {{ $user->created_at->diffForHumans() }}
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button"
                                            class="btn btn-sm btn-info"
                                            data-toggle="modal"
                                            data-target="#viewUserModal{{ $user->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <button type="button"
                                            class="btn btn-sm btn-warning"
                                            data-toggle="modal"
                                            data-target="#editUserModal{{ $user->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    @if($user->id != auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user->id) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this user?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                    <button type="button"
                                            class="btn btn-sm {{ $user->is_active ? 'btn-secondary' : 'btn-success' }}"
                                            onclick="toggleUserStatus({{ $user->id }})">
                                        <i class="fas {{ $user->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- View User Modal -->
                        <div class="modal fade" id="viewUserModal{{ $user->id }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">User Details</h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-4 text-center">
                                                <div class="avatar-circle-lg mb-3">
                                                    <span class="initials-lg">{{ substr($user->name, 0, 2) }}</span>
                                                </div>
                                                <h5>{{ $user->name }}</h5>
                                                <p class="text-muted">{{ $user->email }}</p>

                                                <span class="badge badge-{{ $user->getRoleBadgeClass() }} p-2">
                                                    {{ ucfirst($user->role) }}
                                                </span>

                                                @if($user->is_active)
                                                <span class="badge badge-success p-2 mt-2">
                                                    <i class="fas fa-check-circle"></i> Active
                                                </span>
                                                @else
                                                <span class="badge badge-danger p-2 mt-2">
                                                    <i class="fas fa-times-circle"></i> Inactive
                                                </span>
                                                @endif
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="font-weight-bold">User Information</h6>
                                                <table class="table table-sm">
                                                    <tr>
                                                        <td><strong>User ID:</strong></td>
                                                        <td>{{ $user->id }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Timezone:</strong></td>
                                                        <td>{{ $user->timezone }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Last Login:</strong></td>
                                                        <td>
                                                            @if($user->last_login_at)
                                                            {{ $user->last_login_at->format('M d, Y h:i A') }}
                                                            @else
                                                            Never
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Registered:</strong></td>
                                                        <td>{{ $user->created_at->format('M d, Y h:i A') }}</td>
                                                    </tr>
                                                </table>

                                                @if($user->getProfile())
                                                <h6 class="font-weight-bold mt-3">Profile Details</h6>
                                                <div class="card">
                                                    <div class="card-body">
                                                        {!! $user->getProfile()->getProfileDetails() !!}
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit User Modal -->
                        <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit User: {{ $user->name }}</h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="name{{ $user->id }}">Full Name</label>
                                                <input type="text"
                                                       class="form-control"
                                                       id="name{{ $user->id }}"
                                                       name="name"
                                                       value="{{ $user->name }}"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label for="email{{ $user->id }}">Email Address</label>
                                                <input type="email"
                                                       class="form-control"
                                                       id="email{{ $user->id }}"
                                                       name="email"
                                                       value="{{ $user->email }}"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label for="role{{ $user->id }}">Role</label>
                                                <select class="form-control"
                                                        id="role{{ $user->id }}"
                                                        name="role"
                                                        required
                                                        {{ $user->id == auth()->id() ? 'disabled' : '' }}>
                                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                                                    <option value="doctor" {{ $user->role == 'doctor' ? 'selected' : '' }}>Doctor</option>
                                                    <option value="patient" {{ $user->role == 'patient' ? 'selected' : '' }}>Patient</option>
                                                    <option value="lab_technician" {{ $user->role == 'lab_technician' ? 'selected' : '' }}>Lab Technician</option>
                                                    <option value="receptionist" {{ $user->role == 'receptionist' ? 'selected' : '' }}>Receptionist</option>
                                                </select>
                                                @if($user->id == auth()->id())
                                                <small class="form-text text-warning">
                                                    You cannot change your own role.
                                                </small>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="timezone{{ $user->id }}">Timezone</label>
                                                <select class="form-control"
                                                        id="timezone{{ $user->id }}"
                                                        name="timezone"
                                                        required>
                                                    <option value="UTC" {{ $user->timezone == 'UTC' ? 'selected' : '' }}>UTC</option>
                                                    <option value="America/New_York" {{ $user->timezone == 'America/New_York' ? 'selected' : '' }}>Eastern Time</option>
                                                    <option value="America/Chicago" {{ $user->timezone == 'America/Chicago' ? 'selected' : '' }}>Central Time</option>
                                                    <option value="America/Denver" {{ $user->timezone == 'America/Denver' ? 'selected' : '' }}>Mountain Time</option>
                                                    <option value="America/Los_Angeles" {{ $user->timezone == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time</option>
                                                    <option value="Asia/Dhaka" {{ $user->timezone == 'Asia/Dhaka' ? 'selected' : '' }}>Bangladesh Time</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox"
                                                           class="custom-control-input"
                                                           id="is_active{{ $user->id }}"
                                                           name="is_active"
                                                           value="1"
                                                           {{ $user->is_active ? 'checked' : '' }}
                                                           {{ $user->id == auth()->id() ? 'disabled' : '' }}>
                                                    <label class="custom-control-label" for="is_active{{ $user->id }}">
                                                        Active Account
                                                    </label>
                                                    @if($user->id == auth()->id())
                                                    <small class="form-text text-warning">
                                                        You cannot deactivate your own account.
                                                    </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Update User</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h4>No users found</h4>
                                    <p class="text-muted">No users match your search criteria.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
                </div>
                <div>
                    {{ $users->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New User</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <small class="form-text text-muted">Minimum 8 characters</small>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password *</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role *</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="">Select Role</option>
                            <option value="admin">Administrator</option>
                            <option value="doctor">Doctor</option>
                            <option value="patient">Patient</option>
                            <option value="lab_technician">Lab Technician</option>
                            <option value="receptionist">Receptionist</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="timezone">Timezone</label>
                        <select class="form-control" id="timezone" name="timezone">
                            <option value="UTC">UTC</option>
                            <option value="America/New_York">Eastern Time</option>
                            <option value="America/Chicago">Central Time</option>
                            <option value="America/Denver">Mountain Time</option>
                            <option value="America/Los_Angeles">Pacific Time</option>
                            <option value="Asia/Dhaka">Bangladesh Time</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create User</button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    background-color: #4e73df;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-circle-lg {
    width: 100px;
    height: 100px;
    background-color: #4e73df;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.initials {
    color: white;
    font-weight: bold;
    font-size: 16px;
}

.initials-lg {
    color: white;
    font-weight: bold;
    font-size: 36px;
}

.badge-admin { background-color: #6610f2; }
.badge-doctor { background-color: #20c9a6; }
.badge-patient { background-color: #fd7e14; }
.badge-lab_technician { background-color: #6f42c1; }
.badge-receptionist { background-color: #36b9cc; }

.empty-state {
    text-align: center;
    padding: 40px 0;
}
</style>

<script>
function toggleUserStatus(userId) {
    if (confirm('Are you sure you want to change this user\'s status?')) {
        fetch(`/admin/users/${userId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to update user status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating user status');
        });
    }
}

// Auto-focus search input
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.focus();
    }
});
</script>
@endsection
