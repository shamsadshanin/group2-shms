@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Billing</h1>
        <a href="{{ route('admin.billing.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Billing Record</a>
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
                    <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">Billing ID</th>
                    <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">Patient</th>
                    <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">Amount</th>
                    <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">Billing Date</th>
                    <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">Status</th>
                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @foreach($billings as $billing)
                <tr>
                    <td class="w-1/5 text-left py-3 px-4">{{ $billing->cBillingID }}</td>
                    <td class="w-1/5 text-left py-3 px-4">{{ $billing->patient->cName }}</td>
                    <td class="w-1/5 text-left py-3 px-4">{{ $billing->fAmount }}</td>
                    <td class="w-1/5 text-left py-3 px-4">{{ $billing->dBillingDate }}</td>
                    <td class="w-1/5 text-left py-3 px-4">{{ $billing->cStatus }}</td>
                    <td class="py-3 px-4">
                        <a href="{{ route('admin.billing.edit', ['billing' => $billing->cBillingID]) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                        <form action="{{ route('admin.billing.destroy', ['billing' => $billing->cBillingID]) }}" method="POST" class="inline-block">
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
