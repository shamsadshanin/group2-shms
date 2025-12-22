@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit User: {{ $user->name }}</h1>
        <span class="bg-gray-200 text-gray-700 py-1 px-3 rounded text-sm font-semibold uppercase tracking-wide">
            Role: {{ $user->role }}
        </span>
    </div>

    {{-- Error Display --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="bg-white p-8 rounded-lg shadow-lg border border-gray-100">
        @csrf
        @method('PUT')

        {{-- Hidden Input for Role (needed for Controller logic) --}}
        <input type="hidden" name="role" value="{{ $user->role }}">

        <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Account Credentials</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            {{-- Email --}}
            <div>
                <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
                <input type="email" name="email" id="email" value="{{ $user->email }}" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>

            {{-- Role (Disabled) --}}
            <div>
                <label class="block text-gray-700 font-bold mb-2">Role:</label>
                <select disabled class="bg-gray-100 shadow border rounded w-full py-2 px-3 text-gray-500 cursor-not-allowed">
                    <option value="{{ $user->role }}" selected>{{ ucfirst($user->role) }}</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Role cannot be changed directly.</p>
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-gray-700 font-bold mb-2">New Password (Optional):</label>
                <input type="password" name="password" id="password" placeholder="Leave blank to keep current" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="password_confirmation" class="block text-gray-700 font-bold mb-2">Confirm Password:</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
        </div>

        <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Profile Details</h3>

        {{-- Split Name Fields --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-700 font-bold mb-2">First Name:</label>
                {{-- Try to get First Name from profile data, fallback to splitting user name --}}
                <input type="text" name="first_name" value="{{ $profile->First_Name ?? explode(' ', $user->name)[0] }}" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Last Name:</label>
                <input type="text" name="last_name" value="{{ $profile->Last_Name ?? (explode(' ', $user->name, 2)[1] ?? '') }}" class="shadow border rounded w-full py-2 px-3 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
        </div>

        {{-- ROLE SPECIFIC FIELDS --}}

        {{-- DOCTOR --}}
        @if($user->role === 'doctor')
        <div class="p-4 bg-blue-50 rounded border border-blue-200 mb-6">
            <h4 class="text-blue-800 font-bold mb-3 uppercase text-sm">Doctor Specifics</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-1">Specialization:</label>
                    <input type="text" name="specialization" value="{{ $profile->Specialization ?? '' }}" class="border rounded w-full py-2 px-3">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-1">Contact Number:</label>
                    <input type="text" name="doctor_contact" value="{{ $contact->Contact_Number ?? '' }}" class="border rounded w-full py-2 px-3">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-600 mb-1">Available Days:</label>
                    <input type="text" name="available_days" value="{{ $availability->Available_Days ?? '' }}" class="border rounded w-full py-2 px-3">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-1">Start Time:</label>
                    <input type="time" name="start_time" value="{{ $availability->Start_Time ?? '' }}" class="border rounded w-full py-2 px-3">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-1">End Time:</label>
                    <input type="time" name="end_time" value="{{ $availability->End_Time ?? '' }}" class="border rounded w-full py-2 px-3">
                </div>
            </div>
        </div>
        @endif

        {{-- PATIENT --}}
        @if($user->role === 'patient')
        <div class="p-4 bg-green-50 rounded border border-green-200 mb-6">
            <h4 class="text-green-800 font-bold mb-3 uppercase text-sm">Patient Specifics</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-1">Age:</label>
                    <input type="number" name="age" value="{{ $profile->Age ?? '' }}" class="border rounded w-full py-2 px-3">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-1">Gender:</label>
                    <select name="gender" class="border rounded w-full py-2 px-3 bg-white">
                        <option value="Male" {{ ($profile->Gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ ($profile->Gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ ($profile->Gender ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-1">Contact Number:</label>
                    <input type="text" name="patient_contact" value="{{ $contact->Contact_Number ?? '' }}" class="border rounded w-full py-2 px-3">
                </div>
            </div>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" name="street" value="{{ $profile->Street ?? '' }}" placeholder="Street" class="border rounded w-full py-2 px-3">
                <input type="text" name="city" value="{{ $profile->City ?? '' }}" placeholder="City" class="border rounded w-full py-2 px-3">
                <input type="text" name="zip" value="{{ $profile->Zip ?? '' }}" placeholder="Zip" class="border rounded w-full py-2 px-3">
            </div>
        </div>
        @endif

        {{-- LAB / PHARMACY / RECEPTION --}}
        @if(in_array($user->role, ['lab', 'pharmacy', 'reception']))
        <div class="p-4 bg-gray-50 rounded border border-gray-200 mb-6">
            <h4 class="text-gray-800 font-bold mb-3 uppercase text-sm">{{ ucfirst($user->role) }} Details</h4>

            @if($user->role == 'lab')
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-600 mb-1">Department:</label>
                <input type="text" name="department" value="{{ $profile->Department ?? '' }}" class="border rounded w-full py-2 px-3">
            </div>
            @endif

            @if($user->role == 'reception')
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-600 mb-1">Shift:</label>
                <input type="text" name="reception_shift" value="{{ $profile->Shift ?? '' }}" class="border rounded w-full py-2 px-3">
            </div>
            @endif

            <div>
                <label class="block text-sm font-bold text-gray-600 mb-1">Contact Number:</label>
                <input type="text" name="staff_contact" value="{{ $profile->Contact_Number ?? '' }}" class="border rounded w-full py-2 px-3">
            </div>
        </div>
        @endif

        <div class="flex items-center space-x-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-3 px-8 rounded shadow-lg transition duration-200">
                Update User
            </button>
            <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-800 font-semibold underline">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
