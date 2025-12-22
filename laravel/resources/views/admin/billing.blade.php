@extends('layouts.app')

@section('title', 'Manage Billing')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Manage Billing</h1>
        <p class="mt-2 text-gray-600">Oversee all financial transactions.</p>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">All Billing Records</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($billings as $billing)
                    <tr class="hover:bg-gray-50">
                        {{-- Patient Name (First_Name + Last_Name) --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $billing->patient->First_Name ?? 'Unknown' }} {{ $billing->patient->Last_Name ?? '' }}
                        </td>

                        {{-- Total Amount --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${{ number_format($billing->Total_Amount, 2) }}
                        </td>

                        {{-- Payment Status with Colors --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $billing->Payment_Status === 'Paid' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $billing->Payment_Status === 'Unpaid' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $billing->Payment_Status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                {{ $billing->Payment_Status }}
                            </span>
                        </td>

                        {{-- Issue Date --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($billing->IssueDate)->format('M d, Y') }}
                        </td>

                        {{-- Actions (Using InvoicedID) --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.billing.edit', $billing->InvoicedID) }}" class="text-blue-600 hover:text-blue-900">View/Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No billing records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
