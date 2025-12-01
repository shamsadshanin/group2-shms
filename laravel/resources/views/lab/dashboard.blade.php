@extends('layouts.app')

@section('title', 'Lab Technician Dashboard')

@section('content')
<div class="flex h-screen bg-gray-50">
    <!-- Sidebar -->
    <div class="w-64 bg-white shadow-lg">
        <div class="p-6 border-b">
            <h1 class="text-2xl font-bold text-pink-600 flex items-center">
                <i class="fas fa-flask mr-2"></i> SmartHealth
            </h1>
            <p class="text-sm text-gray-500 mt-1">Lab Portal</p>
        </div>
        <nav class="mt-6">
            <a href="{{ route('lab.dashboard') }}"
               class="flex items-center py-3 px-6 bg-pink-50 text-pink-700 border-r-4 border-pink-700">
                <i class="fas fa-vial mr-3"></i> Investigations
            </a>
            <a href="{{ route('lab.history') }}" class="flex items-center py-3 px-6 text-gray-600 hover:bg-gray-50 hover:text-pink-600 transition">
                <i class="fas fa-history mr-3"></i> History
            </a>
            <a href="#" class="flex items-center py-3 px-6 text-gray-600 hover:bg-gray-50 hover:text-pink-600 transition">
                <i class="fas fa-chart-bar mr-3"></i> Analytics
            </a>
            <a href="#" class="flex items-center py-3 px-6 text-gray-600 hover:bg-gray-50 hover:text-pink-600 transition">
                <i class="fas fa-cog mr-3"></i> Settings
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto">
        <div class="p-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Welcome, {{ $technician->Name }}</h2>
                    <p class="text-gray-600">{{ $technician->Department }} • {{ $technician->Qualification }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="font-medium">{{ $technician->Name }}</p>
                        <p class="text-sm text-gray-500">Lab Technician</p>
                    </div>
                    <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-flask text-pink-600"></i>
                    </div>
                </div>
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <p class="text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                        <p class="text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Pending Tests</h3>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['pending'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">In Progress</h3>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['processing'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-spinner text-blue-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Completed Today</h3>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['completed_today'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">High Priority</h3>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['high_priority'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="bg-white p-6 rounded-xl shadow-sm mb-6">
                <div class="flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
                    <h3 class="text-xl font-bold text-gray-800">Investigation Queue</h3>

                    <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                        <form method="GET" class="flex flex-col md:flex-row gap-3">
                            <input type="text" name="search" placeholder="Search patients or tests..."
                                   value="{{ request('search') }}"
                                   class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent">

                            <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Assigned" {{ request('status') == 'Assigned' ? 'selected' : '' }}>Assigned</option>
                                <option value="Processing" {{ request('status') == 'Processing' ? 'selected' : '' }}>Processing</option>
                            </select>

                            <select name="priority" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                                <option value="all" {{ request('priority') == 'all' ? 'selected' : '' }}>All Priority</option>
                                <option value="Low" {{ request('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                                <option value="Normal" {{ request('priority') == 'Normal' ? 'selected' : '' }}>Normal</option>
                                <option value="High" {{ request('priority') == 'High' ? 'selected' : '' }}>High</option>
                                <option value="Critical" {{ request('priority') == 'Critical' ? 'selected' : '' }}>Critical</option>
                            </select>

                            <select name="test_type" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                                <option value="all" {{ request('test_type') == 'all' ? 'selected' : '' }}>All Test Types</option>
                                @foreach($testTypes as $testType)
                                    <option value="{{ $testType->TestTypeID }}" {{ request('test_type') == $testType->TestTypeID ? 'selected' : '' }}>
                                        {{ $testType->TestName }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="submit" class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 transition font-medium flex items-center">
                                <i class="fas fa-filter mr-2"></i> Filter
                            </button>

                            <a href="{{ route('lab.dashboard') }}"
                               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition font-medium flex items-center">
                                <i class="fas fa-redo mr-2"></i> Reset
                            </a>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Investigations Table -->
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Test Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($investigations as $investigation)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                                    {{ $investigation->getFormattedId() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $investigation->patient->Name }}</div>
                                    <div class="text-sm text-gray-500">Age: {{ $investigation->patient->Age }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $investigation->testType->TestName }}</div>
                                    <div class="text-sm text-gray-500">{{ $investigation->testType->Category }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($investigation->Priority == 'Critical') bg-red-100 text-red-800
                                        @elseif($investigation->Priority == 'High') bg-orange-100 text-orange-800
                                        @elseif($investigation->Priority == 'Normal') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $investigation->Priority }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($investigation->Status == 'Completed') bg-green-100 text-green-800
                                        @elseif($investigation->Status == 'Processing') bg-blue-100 text-blue-800
                                        @elseif($investigation->Status == 'Assigned') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $investigation->Status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $investigation->created_at->format('M j, Y') }}<br>
                                    <span class="text-xs">{{ $investigation->created_at->format('g:i A') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    @if($investigation->Status == 'Pending')
                                        <form action="{{ route('lab.investigation.assign', $investigation->InvestigationID) }}"
                                              method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="text-pink-600 hover:text-pink-900 bg-pink-50 px-3 py-1 rounded-lg transition flex items-center">
                                                <i class="fas fa-hand-paper mr-1"></i> Assign to Me
                                            </button>
                                        </form>
                                    @elseif(in_array($investigation->Status, ['Assigned', 'Processing']))
                                        <button onclick="openUpdateModal('{{ $investigation->InvestigationID }}', '{{ $investigation->patient->Name }}', '{{ $investigation->testType->TestName }}', '{{ $investigation->Status }}')"
                                                class="text-green-600 hover:text-green-900 bg-green-50 px-3 py-1 rounded-lg transition flex items-center">
                                            <i class="fas fa-edit mr-1"></i> Update
                                        </button>
                                    @endif

                                    @if($investigation->Status == 'Completed' && $investigation->DigitalReport)
                                        <a href="{{ route('lab.report.download', $investigation->InvestigationID) }}"
                                           class="text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1 rounded-lg transition flex items-center">
                                            <i class="fas fa-download mr-1"></i> Report
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    <i class="fas fa-vial text-4xl text-gray-300 mb-2"></i>
                                    <p>No investigations found matching your criteria.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($investigations->hasPages())
                <div class="mt-6">
                    {{ $investigations->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Update Investigation Modal -->
<div id="updateModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Update Investigation</h3>
                <button onclick="closeUpdateModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <p class="text-sm text-gray-500 mb-4">For <span id="modalPatient" class="font-medium"></span> - <span id="modalTest" class="font-medium"></span></p>

            <form id="updateForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                    <select name="Status" id="modalStatus"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                            onchange="toggleDetailedResults()">
                        <option value="Processing">Processing</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Result Summary *</label>
                    <textarea name="ResultSummary" rows="3" placeholder="Brief summary of test results..."
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                              required></textarea>
                </div>

                <div id="detailedResultsSection" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Detailed Results *</label>
                    <textarea name="DetailedResults" rows="4" placeholder="Detailed test results, observations, and findings..."
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Digital Report *</label>
                    <input type="file" name="DigitalReport"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                           accept=".pdf,.jpg,.jpeg,.png" required>
                    <p class="text-xs text-gray-500 mt-1">Accepted formats: PDF, JPG, PNG (Max: 5MB)</p>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button type="button" onclick="closeUpdateModal()"
                            class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition font-medium">
                        Cancel
                    </button>
                    <button type="submit"
                            class="bg-pink-600 text-white px-6 py-2 rounded-lg hover:bg-pink-700 transition font-medium flex items-center">
                        <i class="fas fa-save mr-2"></i> Update Investigation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openUpdateModal(investigationId, patientName, testName, currentStatus) {
        document.getElementById('modalPatient').textContent = patientName;
        document.getElementById('modalTest').textContent = testName;
        document.getElementById('modalStatus').value = currentStatus;

        // Set form action dynamically
        document.getElementById('updateForm').action = "/lab/investigation/" + investigationId;

        // Show/hide detailed results based on initial status
        toggleDetailedResults();

        document.getElementById('updateModal').classList.remove('hidden');
    }

    function closeUpdateModal() {
        document.getElementById('updateModal').classList.add('hidden');
    }

    function toggleDetailedResults() {
        const status = document.getElementById('modalStatus').value;
        const detailedResultsSection = document.getElementById('detailedResultsSection');

        if (status === 'Completed') {
            detailedResultsSection.classList.remove('hidden');
            document.querySelector('textarea[name="DetailedResults"]').required = true;
        } else {
            detailedResultsSection.classList.add('hidden');
            document.querySelector('textarea[name="DetailedResults"]').required = false;
        }
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('updateModal');
        if (event.target === modal) {
            closeUpdateModal();
        }
    }
</script>
@endsection
