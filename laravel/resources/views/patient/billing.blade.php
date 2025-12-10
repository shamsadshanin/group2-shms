@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">My Billing</h1>

    <div class="bg-white rounded-lg shadow-md">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="w-1/4 text-left py-3 px-4 uppercase font-semibold text-sm">Invoice ID</th>
                    <th class="w-1/4 text-left py-3 px-4 uppercase font-semibold text-sm">Date</th>
                    <th class="w-1/4 text-left py-3 px-4 uppercase font-semibold text-sm">Amount</th>
                    <th class="w-1/4 text-left py-3 px-4 uppercase font-semibold text-sm">Status</th>
                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @forelse($billings as $billing)
                <tr>
                    <td class="w-1/4 text-left py-3 px-4">{{ $billing->cBillingID }}</td>
                    <td class="w-1/4 text-left py-3 px-4">{{ $billing->dBillingDate }}</td>
                    <td class="w-1/4 text-left py-3 px-4">${{ $billing->fAmount }}</td>
                    <td class="w-1/4 text-left py-3 px-4">{{ $billing->cStatus }}</td>
                    <td class="py-3 px-4">
                        @if($billing->cStatus !== 'Paid')
                        <form action="{{ route('patient.pay-bill', $billing->cBillingID) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Pay Now</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4">No billing records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
