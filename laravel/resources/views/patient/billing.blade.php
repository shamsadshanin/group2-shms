@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">My Billing & Invoices</h1>
        <a href="{{ route('patient.dashboard') }}" class="text-blue-600 hover:text-blue-800">Back to Dashboard</a>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="w-1/6 text-left py-3 px-6 uppercase font-semibold text-sm">Invoice ID</th>
                        <th class="w-1/6 text-left py-3 px-6 uppercase font-semibold text-sm">Date</th>
                        <th class="w-1/6 text-left py-3 px-6 uppercase font-semibold text-sm">Amount</th>
                        <th class="w-1/6 text-left py-3 px-6 uppercase font-semibold text-sm">Status</th>
                        <th class="text-right py-3 px-6 uppercase font-semibold text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 divide-y divide-gray-200">
                    @forelse($billings as $billing)
                    <tr class="hover:bg-gray-50 transition-colors">
                        {{-- ID: Matches 'InvoicedID' --}}
                        <td class="text-left py-4 px-6 font-mono font-medium text-gray-800">
                            {{ $billing->InvoicedID }}
                        </td>

                        {{-- Date: Matches 'IssueDate' --}}
                        <td class="text-left py-4 px-6">
                            {{ \Carbon\Carbon::parse($billing->IssueDate)->format('d M, Y') }}
                        </td>

                        {{-- Amount: Matches 'Total_Amount' --}}
                        <td class="text-left py-4 px-6 font-bold text-gray-800">
                            ${{ number_format($billing->Total_Amount, 2) }}
                        </td>

                        {{-- Status: Matches 'Payment_Status' --}}
                        <td class="text-left py-4 px-6">
                            @if($billing->Payment_Status === 'Paid')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Paid
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Unpaid
                                </span>
                            @endif
                        </td>

                        {{-- Pay Action --}}
                        <td class="py-4 px-6 text-right">
                            @if($billing->Payment_Status !== 'Paid')
                            <form action="{{ route('patient.pay-bill', $billing->InvoicedID) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2 px-4 rounded-lg shadow-sm transition-transform transform hover:scale-105">
                                    Pay Now
                                </button>
                            </form>
                            @else
                                <button disabled class="bg-gray-200 text-gray-400 text-xs font-bold py-2 px-4 rounded-lg cursor-not-allowed">
                                    Paid
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-500">
                            <i class="fas fa-file-invoice-dollar fa-2x mb-2 text-gray-300"></i>
                            <p>No billing records found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
