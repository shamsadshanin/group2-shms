@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Add Billing Record</h1>

    <form action="{{ route('admin.billing.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="mb-4">
                <label for="cBillingID" class="block text-gray-700 font-bold mb-2">Billing ID:</label>
                <input type="text" name="cBillingID" id="cBillingID" class="shadow border rounded w-full py-2 px-3" required>
            </div>

            <div class="mb-4">
                <label for="cPatientID" class="block text-gray-700 font-bold mb-2">Patient:</label>
                <select name="cPatientID" id="cPatientID" class="shadow border rounded w-full py-2 px-3" required>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->cPatientID }}">{{ $patient->cName }} ({{ $patient->cPatientID }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <h3 class="text-lg font-semibold mb-4 text-blue-600 border-b pb-2">Test Details (Composite Attributes)</h3>
        
        <div id="test-container">
            <div class="flex gap-4 mb-4 test-row">
                <div class="flex-1">
                    <label class="block text-sm font-bold">Test Name</label>
                    <input type="text" name="tests[0][name]" class="border rounded w-full py-2 px-3" placeholder="Enter Test Name" required>
                </div>
                <div class="w-32">
                    <label class="block text-sm font-bold">Quantity</label>
                    <input type="number" name="tests[0][qty]" class="border rounded w-full py-2 px-3 qty" value="1" min="1" required>
                </div>
                <div class="w-40">
                    <label class="block text-sm font-bold">Amount (Per Unit)</label>
                    <input type="number" step="0.01" name="tests[0][amount]" class="border rounded w-full py-2 px-3 unit_price" required>
                </div>
                <div class="flex items-end">
                    <button type="button" class="remove-test bg-red-500 text-white px-3 py-2 rounded hidden">X</button>
                </div>
            </div>
        </div>

        <button type="button" id="add-more-test" class="bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-4 rounded mb-6 text-sm">
            + Add Another Test
        </button>

        <hr class="my-6">

        <div class="mb-4">
            <label for="fAmount" class="block text-gray-700 font-bold mb-2">Total Grand Amount:</label>
            <input type="number" step="0.01" name="fAmount" id="fAmount" class="bg-gray-100 border rounded w-full py-2 px-3 font-bold text-xl" readonly required>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="dBillingDate" class="block text-gray-700 font-bold mb-2">Billing Date:</label>
                <input type="date" name="dBillingDate" id="dBillingDate" class="shadow border rounded w-full py-2 px-3" value="{{ date('Y-m-d') }}" required>
            </div>
            <div>
                <label for="cStatus" class="block text-gray-700 font-bold mb-2">Status:</label>
                <select name="cStatus" id="cStatus" class="shadow border rounded w-full py-2 px-3">
                    <option value="Paid">Paid</option>
                    <option value="Unpaid">Unpaid</option>
                    <option value="Pending">Pending</option>
                </select>
            </div>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-800 text-white font-bold py-3 px-4 rounded mt-4">
            Save Billing Record
        </button>
    </form>
</div>

<script>
    let testIndex = 1;
    const container = document.getElementById('test-container');
    const totalInput = document.getElementById('fAmount');

    // Add More Test
    document.getElementById('add-more-test').addEventListener('click', () => {
        const firstRow = document.querySelector('.test-row');
        const newRow = firstRow.cloneNode(true);
        
        // Update input names and clear values
        newRow.querySelectorAll('input').forEach(input => {
            input.value = input.classList.contains('qty') ? 1 : '';
            const name = input.name.replace(/\[\d+\]/, `[${testIndex}]`);
            input.name = name;
        });

        newRow.querySelector('.remove-test').classList.remove('hidden');
        container.appendChild(newRow);
        testIndex++;
    });

    // Remove Test
    container.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-test')) {
            e.target.closest('.test-row').remove();
            calculateTotal();
        }
    });

    // Calculate Grand Total
    container.addEventListener('input', calculateTotal);

    function calculateTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.test-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty').value) || 0;
            const price = parseFloat(row.querySelector('.unit_price').value) || 0;
            grandTotal += (qty * price);
        });
        totalInput.value = grandTotal.toFixed(2);
    }
</script>
@endsection