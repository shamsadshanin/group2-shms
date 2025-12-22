@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Billing Records</h1>
        <a href="{{ route('admin.billing.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Billing Record</a>
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
                    <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">Invoice ID</th>
                    <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">Patient</th>
                    <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">Amount</th>
                    <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">Date</th>
                    <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">Status</th>
                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @foreach($billings as $billing)
                <tr class="border-b hover:bg-gray-50">
                    {{-- Primary Key: InvoicedID --}}
                    <td class="text-left py-3 px-4 font-mono text-sm">{{ $billing->InvoicedID }}</td>

                    {{-- Patient Name: First_Name + Last_Name --}}
                    <td class="text-left py-3 px-4">
                        {{ $billing->patient->First_Name ?? 'N/A' }} {{ $billing->patient->Last_Name ?? '' }}
                    </td>

                    {{-- Total Amount --}}
                    <td class="text-left py-3 px-4 font-bold text-gray-800">
                        ${{ number_format($billing->Total_Amount, 2) }}
                    </td>

                    {{-- Issue Date --}}
                    <td class="text-left py-3 px-4">
                        {{ \Carbon\Carbon::parse($billing->IssueDate)->format('M d, Y') }}
                    </td>

                    {{-- Payment Status --}}
                    <td class="text-left py-3 px-4">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $billing->Payment_Status === 'Paid' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $billing->Payment_Status === 'Unpaid' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $billing->Payment_Status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                            {{ $billing->Payment_Status }}
                        </span>
                    </td>

                    {{-- Actions --}}
                    <td class="py-3 px-4">
                        <a href="{{ route('admin.billing.edit', $billing->InvoicedID) }}" class="text-blue-500 hover:text-blue-700 font-semibold mr-3">Edit</a>

                        <form action="{{ route('admin.billing.destroy', $billing->InvoicedID) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete Invoice {{ $billing->InvoicedID }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 font-semibold">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
