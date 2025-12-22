@extends('layouts.app')

@section('title', 'Create New Prescription')

@section('content')
<div x-data="prescriptionForm()">
    <div class="mb-8">
        <a href="{{ route('doctor.prescriptions') }}" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Prescriptions
        </a>
        <h1 class="text-4xl font-bold text-gray-800 mt-2">Create Prescription & Lab Order</h1>
    </div>

    <div class="max-w-5xl mx-auto">
        <div class="glass-card p-8 bg-white rounded-xl shadow-lg">
            <form action="{{ route('doctor.prescriptions.store') }}" method="POST">
                @csrf

                {{-- Patient & Date Section --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 border-b border-gray-100 pb-8">
                    <div>
                        <label for="PatientID" class="block text-sm font-medium text-gray-700 mb-2">Patient</label>
                        <select id="PatientID" name="PatientID" class="form-input w-full px-4 py-3 bg-white border border-gray-300 rounded-xl" required>
                            <option value="" disabled selected>Select a patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->PatientID }}">{{ $patient->First_Name }} {{ $patient->Last_Name }} (ID: {{ $patient->PatientID }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="IssueDate" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                        <input type="date" id="IssueDate" name="IssueDate" value="{{ date('Y-m-d') }}" class="form-input w-full px-4 py-3 bg-white border border-gray-300 rounded-xl" required>
                    </div>
                </div>

                {{-- Medicines Section --}}
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Medicines</h3>
                        <button type="button" @click="addMedicine()" class="text-sm bg-blue-50 text-blue-600 px-3 py-1 rounded-lg hover:bg-blue-100 font-semibold transition"><i class="fas fa-plus mr-1"></i> Add Medicine</button>
                    </div>
                    <template x-for="(medicine, index) in medicines" :key="'med-' + index">
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 mb-3 relative grid grid-cols-1 md:grid-cols-3 gap-4">
                            <input type="text" :name="`medicines[${index}][name]`" x-model="medicine.name" placeholder="Medicine Name" class="rounded-lg border-gray-300 text-sm" required>
                            <input type="text" :name="`medicines[${index}][dosage]`" x-model="medicine.dosage" placeholder="Dosage (e.g. 500mg)" class="rounded-lg border-gray-300 text-sm" required>
                            <div class="flex gap-2">
                                <input type="text" :name="`medicines[${index}][frequency]`" x-model="medicine.frequency" placeholder="Frequency" class="rounded-lg border-gray-300 text-sm w-full" required>
                                <button x-show="medicines.length > 0" type="button" @click="removeMedicine(index)" class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- NEW: Lab Tests Section --}}
                <div class="mb-6 border-t border-gray-100 pt-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Recommended Investigations (Lab Tests)</h3>
                        <button type="button" @click="addTest()" class="text-sm bg-purple-50 text-purple-600 px-3 py-1 rounded-lg hover:bg-purple-100 font-semibold transition"><i class="fas fa-flask mr-1"></i> Add Test</button>
                    </div>

                    <template x-for="(test, index) in tests" :key="'test-' + index">
                        <div class="bg-purple-50 p-4 rounded-xl border border-purple-100 mb-3 relative grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase">Test Name</label>
                                <input type="text" :name="`lab_tests[${index}][name]`" x-model="test.name" placeholder="e.g. CBC, X-Ray Chest" class="w-full rounded-lg border-gray-300 text-sm">
                            </div>
                            <div class="flex gap-2 items-end">
                                <div class="w-full">
                                    <label class="text-xs font-bold text-gray-500 uppercase">Type</label>
                                    <input type="text" :name="`lab_tests[${index}][type]`" x-model="test.type" placeholder="e.g. Blood, Imaging" class="w-full rounded-lg border-gray-300 text-sm">
                                </div>
                                <button type="button" @click="removeTest(index)" class="text-red-500 hover:text-red-700 mb-2"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    </template>
                    <p x-show="tests.length === 0" class="text-sm text-gray-400 italic">No lab tests added.</p>
                </div>

                <div class="mt-10 pt-6 border-t border-gray-200 flex justify-end">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg transform hover:scale-105 transition">
                        Save Prescription & Order Tests
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
<script>
    function prescriptionForm() {
        return {
            medicines: [{ name: '', dosage: '', frequency: '' }],
            tests: [], // Start empty, doctor adds if needed

            addMedicine() { this.medicines.push({ name: '', dosage: '', frequency: '' }); },
            removeMedicine(index) { this.medicines.splice(index, 1); },

            addTest() { this.tests.push({ name: '', type: '' }); },
            removeTest(index) { this.tests.splice(index, 1); }
        }
    }
</script>
@endsection
