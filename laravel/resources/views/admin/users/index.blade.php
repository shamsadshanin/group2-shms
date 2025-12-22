@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">User Management</h1>
        <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded transition duration-200">
            + Add New User
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-800 text-white">
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">Name</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">Email</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">Role</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">Joined Date</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50 border-b border-gray-200">
                    {{-- Name --}}
                    <td class="px-5 py-5 text-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-10 h-10">
                                <img class="w-full h-full rounded-full"
                                     src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF"
                                     alt="" />
                            </div>
                            <div class="ml-3">
                                <p class="text-gray-900 whitespace-no-wrap font-semibold">
                                    {{ $user->name }}
                                </p>
                            </div>
                        </div>
                    </td>

                    {{-- Email --}}
                    <td class="px-5 py-5 text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">{{ $user->email }}</p>
                    </td>

                    {{-- Role Badge --}}
                    <td class="px-5 py-5 text-sm">
                        @php
                            $roleClasses = [
                                'admin'     => 'bg-gray-200 text-gray-800',
                                'doctor'    => 'bg-blue-100 text-blue-800',
                                'patient'   => 'bg-green-100 text-green-800',
                                'lab'       => 'bg-purple-100 text-purple-800',
                                'pharmacy'  => 'bg-yellow-100 text-yellow-800',
                                'reception' => 'bg-pink-100 text-pink-800',
                            ];
                            $currentClass = $roleClasses[$user->role] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="relative inline-block px-3 py-1 font-semibold leading-tight {{ $currentClass }} rounded-full text-xs uppercase">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>

                    {{-- Created At (Fixed Null Check) --}}
                    <td class="px-5 py-5 text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">
                            {{-- Check if created_at exists before formatting --}}
                            {{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}
                        </p>
                    </td>

                    {{-- Actions --}}
                    <td class="px-5 py-5 text-sm">
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-900 font-bold transition duration-150">
                                Edit
                            </a>

                            {{-- Prevent Admin from deleting themselves --}}
                            @if(auth()->id() !== $user->id)
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block"
                                  onsubmit="return confirm('Are you sure you want to delete this user? This will also remove their associated profile data.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold transition duration-150">
                                    Delete
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
