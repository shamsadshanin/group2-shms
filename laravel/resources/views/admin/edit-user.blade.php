@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit User</h1>
        <p class="mt-2 text-gray-600">Update user details for {{ $user->name }}</p>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">User Information</h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form id="editUserForm" action="{{ route('admin.api.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" value="{{ $user->name }}" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md p-3" required>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ $user->email }}" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md p-3" required>
                    </div>
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                        <select name="role" id="role" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md p-3" required>
                            <option value="admin" @if($user->role == 'admin') selected @endif>Admin</option>
                            <option value="doctor" @if($user->role == 'doctor') selected @endif>Doctor</option>
                            <option value="patient" @if($user->role == 'patient') selected @endif>Patient</option>
                            <option value="lab" @if($user->role == 'lab') selected @endif>Lab Technician</option>
                            <option value="pharmacy" @if($user->role == 'pharmacy') selected @endif>Pharmacy</option>
                            <option value="reception" @if($user->role == 'reception') selected @endif>Receptionist</option>
                        </select>
                    </div>
                </div>
                <div class="mt-8 flex justify-end">
                    <a href="{{ route('admin.users') }}" class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="ml-3 inline-flex justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('editUserForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const url = form.action;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('User updated successfully');
            window.location.href = "{{ route('admin.users') }}";
        } else {
             let errorMessage = 'Error: ' + (data.message || 'An unknown error occurred.');
            if (data.errors) {
                errorMessage += '\n' + Object.values(data.errors).map(e => e.join(', ')).join('\n');
            }
            alert(errorMessage);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        let errorMessage = 'An unexpected error occurred. Please try again.';
        if(error && error.message) {
            errorMessage = 'Error: ' + error.message;
        }
        if(error && error.errors) {
             errorMessage += '\n' + Object.values(error.errors).map(e => e.join(', ')).join('\n');
        }
        alert(errorMessage);
    });
});
</script>
@endsection
