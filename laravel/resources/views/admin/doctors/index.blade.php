@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Doctors List</h1>
        <a href="{{ route('admin.doctors.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Doctor</a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">Doctor ID</th>
                    <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">Name</th>
                    <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">Specialization</th>
                    <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">Email</th>
                    <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">Contact</th>
                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @foreach($doctors as $doctor)
                <tr class="border-b hover:bg-gray-50">
                    {{-- Doctor ID --}}
                    <td class="text-left py-3 px-4 font-mono text-sm">{{ $doctor->DoctorID }}</td>

                    {{-- Full Name (First + Last) --}}
                    <td class="text-left py-3 px-4 font-semibold">
                        {{ $doctor->First_Name }} {{ $doctor->Last_Name }}
                    </td>

                    {{-- Specialization --}}
                    <td class="text-left py-3 px-4">{{ $doctor->Specialization }}</td>

                    {{-- Email --}}
                    <td class="text-left py-3 px-4">{{ $doctor->Email }}</td>

                    {{-- Contact Number (Fetched via Join in Controller) --}}
                    <td class="text-left py-3 px-4">{{ $doctor->Contact_Number ?? 'N/A' }}</td>

                    {{-- Actions --}}
                    <td class="py-3 px-4 flex gap-2">
                        <a href="{{ route('admin.doctors.edit', $doctor->DoctorID) }}" class="text-blue-500 hover:text-blue-700 font-semibold">Edit</a>

                        <form action="{{ route('admin.doctors.destroy', $doctor->DoctorID) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete Doctor {{ $doctor->DoctorID }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 font-semibold ml-2">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
