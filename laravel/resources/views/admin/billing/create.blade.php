@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Add Billing Record</h1>

    <form action="{{ route('admin.billing.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="mb-4">
                <label for="cBillingID" class="block text-gray-700 font-bold mb-2">Billing ID (Auto):</label>
                <input type="text" name="cBillingID" id="cBillingID"
                       class="bg-gray-100 border rounded w-full py-2 px-3 text-gray-600 font-mono font-bold focus:outline-none"
                       value="{{ $nextId }}" readonly required>
                <p class="text-xs text-gray-500 mt-1">This ID is automatically generated.</p>
            </div>

            <div class="mb-4">
                <label for="cPatientID" class="block text-gray-700 font-bold mb-2">Patient (Search Name or ID):</label>
                <select name="cPatientID" id="cPatientID" class="select2-patient shadow border rounded w-full py-2 px-3" required>
                    <option value="">-- Search Patient --</option>
                    @foreach($patients as $patient)
                        @php
                            $insurance = $patient->insurance;
                            $isInsured = $insurance ? ' (INSURED)' : '';
                            $policyNo = $insurance ? $insurance->cPolicyNumber : 'N/A';
                            $company = $insurance ? $insurance->cInsuranceCompany : 'None';
                        @endphp
                        <option value="{{ $patient->cPatientID }}"
                                data-policy="{{ $policyNo }}"
                                data-company="{{ $company }}">
                            {{ $patient->cName }} - {{ $patient->cPatientID }}{{ $isInsured }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div id="insurance-info" class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-400 rounded hidden">
            <h4 class="text-blue-800 font-bold text-sm uppercase mb-2">Insurance Details</h4>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <p><strong>Policy Number:</strong> <span id="display_policy">N/A</span></p>
                <p><strong>Company:</strong> <span id="display_company">N/A</span></p>
            </div>
        </div>

        <h3 class="text-lg font-semibold mb-4 text-blue-600 border-b pb-2">Test Details</h3>

        <div id="test-container">
            <div class="flex flex-wrap md:flex-nowrap gap-4 mb-4 test-row items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-bold text-gray-600 mb-1">Test Name</label>
                    <input type="text" name="tests[0][name]" class="border rounded w-full py-2 px-3 focus:border-blue-500" placeholder="e.g., CBC" required>
                </div>
                <div class="w-full md:w-32">
                    <label class="block text-sm font-bold text-gray-600 mb-1">Qty</label>
                    <input type="number" name="tests[0][qty]" class="border rounded w-full py-2 px-3 qty" value="1" min="1" required>
                </div>
                <div class="w-full md:w-40">
                    <label class="block text-sm font-bold text-gray-600 mb-1">Price</label>
                    <input type="number" step="0.01" name="tests[0][amount]" class="border rounded w-full py-2 px-3 unit_price" placeholder="0.00" required>
                </div>
                <div class="pb-1">
                    <button type="button" class="remove-test bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded hidden transition duration-200">
                        &times;
                    </button>
                </div>
            </div>
        </div>

        <button type="button" id="add-more-test" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mb-6 text-sm">
            + Add Another Test
        </button>

        <hr class="my-6">

        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-6">
            <div class="flex justify-between mb-2">
                <span class="text-gray-600 font-semibold">Subtotal:</span>
                <span id="display_subtotal" class="font-bold text-lg">$0.00</span>
            </div>

            <div id="insurance_row" class="flex justify-between items-center mb-4 py-2 border-y border-dashed border-gray-300 hidden">
                <span class="text-blue-700 font-bold">Insurance Coverage (-) :</span>
                <div class="w-40">
                    <input type="number" step="0.01" name="fInsuranceCoverage" id="fInsuranceCoverage"
                           class="border-2 border-blue-300 rounded w-full py-1 px-2 text-right text-blue-700 font-bold" value="0.00">
                </div>
            </div>

            <div class="flex justify-between items-center mt-4">
                <span class="text-xl font-bold text-gray-800">Net Payable:</span>
                <div class="relative w-48">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">$</span>
                    <input type="number" step="0.01" name="fAmount" id="fAmount"
                           class="bg-white border-2 border-blue-600 rounded w-full py-3 px-8 font-bold text-2xl text-blue-800"
                           readonly required>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
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

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-800 text-white font-bold py-4 px-4 rounded mt-4 shadow-lg transition duration-300">
            Save Billing Record
        </button>
    </form>
</div>

<script>
    $(document).ready(function() {
        let isInsured = false;

        $('.select2-patient').select2({
            placeholder: "Search Patient...",
            allowClear: true,
            width: '100%'
        });

        $('#cPatientID').on('change', function() {
            const selected = $(this).find(':selected');
            const policy = selected.data('policy');
            const company = selected.data('company');

            if (policy && policy !== 'N/A') {
                isInsured = true;
                $('#display_policy').text(policy);
                $('#display_company').text(company);
                $('#insurance-info').removeClass('hidden').fadeIn();
                $('#insurance_row').removeClass('hidden');
                $('#cStatus').val('Paid');
            } else {
                isInsured = false;
                $('#insurance-info').addClass('hidden');
                $('#insurance_row').addClass('hidden');
                $('#fInsuranceCoverage').val(0);
            }
            calculateTotal();
        });

        let testIndex = 1;
        $('#add-more-test').on('click', function() {
            const firstRow = $('.test-row').first();
            const newRow = firstRow.clone();
            newRow.find('input').each(function() {
                $(this).val($(this).hasClass('qty') ? 1 : '');
                const oldName = $(this).attr('name');
                if (oldName) $(this).attr('name', oldName.replace(/\[\d+\]/, `[${testIndex}]`));
            });
            newRow.find('.remove-test').removeClass('hidden');
            $('#test-container').append(newRow);
            testIndex++;
        });

        $(document).on('click', '.remove-test', function() {
            $(this).closest('.test-row').remove();
            calculateTotal();
        });

        $(document).on('input', '.qty, .unit_price, #fInsuranceCoverage', calculateTotal);

        function calculateTotal() {
            let subtotal = 0;
            $('.test-row').each(function() {
                const qty = parseFloat($(this).find('.qty').val()) || 0;
                const price = parseFloat($(this).find('.unit_price').val()) || 0;
                subtotal += (qty * price);
            });

            $('#display_subtotal').text('$' + subtotal.toFixed(2));

            if (isInsured && (parseFloat($('#fInsuranceCoverage').val()) === 0 || $('#fInsuranceCoverage').data('auto'))) {
                $('#fInsuranceCoverage').val(subtotal.toFixed(2)).data('auto', true);
            }

            let deduction = parseFloat($('#fInsuranceCoverage').val()) || 0;
            let net = subtotal - deduction;
            $('#fAmount').val(net < 0 ? '0.00' : net.toFixed(2));
        }
    });
</script>

<style>
    .select2-container--default .select2-selection--single { height: 42px; padding: 6px; border: 1px solid #d1d5db; border-radius: 0.375rem; }
</style>
@endsection
