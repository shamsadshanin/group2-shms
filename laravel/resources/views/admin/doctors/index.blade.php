@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Doctors</h1>
        <a href="{{ route('admin.doctors.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Doctor</a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">ID</th>
                    <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">Name</th>
                    <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">Specialization</th>
                    <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">Email</th>
                    <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">Contact Number</th>
                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @foreach($doctors as $doctor)
                <tr>
                    <td class="w-1/6 text-left py-3 px-4">{{ $doctor->cDoctorID }}</td>
                    <td class="w-1/6 text-left py-3 px-4">{{ $doctor->cName }}</td>
                    <td class="w-1/6 text-left py-3 px-4">{{ $doctor->cSpecialization }}</td>
                    <td class="w-1/6 text-left py-3 px-4">{{ $doctor->cEmail }}</td>
                    <td class="w-1/6 text-left py-3 px-4">{{ $doctor->cContactNumber }}</td>
                    <td class="py-3 px-4">
                        <a href="{{ route('admin.doctors.edit', $doctor->cDoctorID) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                        <form action="{{ route('admin.doctors.destroy', $doctor->cDoctorID) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 ml-4">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
