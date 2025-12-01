@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="flex h-screen bg-gray-50">
    <!-- Sidebar -->
    <div class="w-64 bg-gray-900 text-white shadow-lg">
        <div class="p-6 border-b border-gray-800">
            <h1 class="text-2xl font-bold text-red-500 flex items-center">
                <i class="fas fa-heartbeat mr-2"></i> SmartHealth
            </h1>
            <p class="text-sm text-gray-400 mt-1">Admin Portal</p>
        </div>
        <nav class="mt-6">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center py-3 px-6 bg-gray-800 text-red-400 border-r-4 border-red-500">
                <i class="fas fa-tachometer-alt mr-3"></i> Overview
            </a>
            <a href="{{ route('admin.users') }}" class="flex items-center py-3 px-6 text-gray-400 hover:bg-gray-800 hover:text-white transition">
                <i class="fas fa-users-cog mr-3"></i> User Management
            </a>
            <a href="{{ route('admin.financials') }}" class="flex items-center py-3 px-6 text-gray-400 hover:bg-gray-800 hover:text-white transition">
                <i class="fas fa-file-invoice-dollar mr-3"></i> Financials
            </a>
            <a href="#" class="flex items-center py-3 px-6 text-gray-400 hover:bg-gray-800 hover:text-white transition">
                <i class="fas fa-chart-bar mr-3"></i> Analytics
            </a>
            <a href="#" class="flex items-center py-3 px-6 text-gray-400 hover:bg-gray-800 hover:text-white transition">
                <i class="fas fa-cog mr-3"></i> System Settings
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto">
        <div class="p-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">System Overview</h2>
                    <p class="text-gray-600">Comprehensive analytics and system monitoring</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="font-medium">{{ auth()->user()->name }}</p>
                        <p class="text-sm text-gray-500">Administrator</p>
                    </div>
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-shield text-red-600"></i>
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

            <!-- System Alerts -->
            @if($systemAlerts['overdue_bills'] > 0 || $systemAlerts['pending_appointments'] > 50 || $systemAlerts['inactive_users'] > 0)
            <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-yellow-500 mr-3"></i>
                    <div>
                        <h4 class="font-medium text-yellow-800">System Alerts</h4>
                        <div class="text-sm text-yellow-700 mt-1 space-y-1">
                            @if($systemAlerts['overdue_bills'] > 0)
                                <p>• {{ $systemAlerts['overdue_bills'] }} overdue bill(s) requiring attention</p>
                            @endif
                            @if($systemAlerts['pending_appointments'] > 50)
                                <p>• High number of pending appointments ({{ $systemAlerts['pending_appointments'] }})</p>
                            @endif
                            @if($systemAlerts['inactive_users'] > 0)
                                <p>• {{ $systemAlerts['inactive_users'] }} inactive user account(s)</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Total Patients</h3>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalPatients }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-users text-blue-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Active Doctors</h3>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalDoctors }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-md text-green-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Lab Technicians</h3>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalLabTechnicians }}</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-flask text-purple-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Total Appointments</h3>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalAppointments }}</p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar-check text-red-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Total Revenue</h3>
                            <p class="text-2xl font-bold text-gray-800 mt-2">${{ number_format($totalRevenue, 2) }}</p>
                        </div>
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-dollar-sign text-green-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Monthly Revenue</h3>
                            <p class="text-2xl font-bold text-gray-800 mt-2">${{ number_format($monthlyRevenue, 2) }}</p>
                        </div>
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-chart-line text-blue-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Pending Payments</h3>
                            <p class="text-2xl font-bold text-gray-800 mt-2">${{ number_format($pendingPayments, 2) }}</p>
                        </div>
                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Overdue Payments</h3>
                            <p class="text-2xl font-bold text-gray-800 mt-2">${{ number_format($overduePayments, 2) }}</p>
                        </div>
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Appointments Chart -->
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <h3 class="text-lg font-bold mb-4 text-gray-800">Appointments Trend ({{ date('Y') }})</h3>
                    <div class="h-64">
                        <canvas id="appointmentsChart"></canvas>
                    </div>
                </div>

                <!-- Revenue Chart -->
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <h3 class="text-lg font-bold mb-4 text-gray-800">Revenue Trend (Last 6 Months)</h3>
                    <div class="h-64">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Users -->
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Recently Registered Users</h3>
                        <a href="{{ route('admin.users') }}" class="text-sm text-red-600 hover:text-red-800 font-medium">
                            View All →
                        </a>
                    </div>
                    <div class="space-y-4">
                        @foreach($recentUsers as $user)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if($user->role == 'admin') bg-red-100 text-red-800
                                    @elseif($user->role == 'doctor') bg-green-100 text-green-800
                                    @elseif($user->role == 'patient') bg-blue-100 text-blue-800
                                    @else bg-purple-100 text-purple-800 @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                                <p class="text-xs text-gray-500 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <h3 class="text-lg font-bold mb-4 text-gray-800">Recent System Activity</h3>
                    <div class="space-y-3 max-h-80 overflow-y-auto">
                        @foreach($recentActivity as $activity)
                        <div class="flex items-start space-x-3 p-2 hover:bg-gray-50 rounded">
                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <i class="fas fa-history text-gray-500 text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">
                                    <span class="font-medium">{{ $activity->user->name }}</span>
                                    {{ $activity->description }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $activity->performed_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Appointments Chart
    const appointmentsCtx = document.getElementById('appointmentsChart');
    const appointmentsData = @json($appointmentsChart);

    new Chart(appointmentsCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(appointmentsData),
            datasets: [{
                label: 'Number of Appointments',
                data: Object.values(appointmentsData),
                backgroundColor: 'rgba(59, 130, 246, 0.6)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart');
    const revenueData = @json($revenueChart);

    // Format month labels
    const monthLabels = Object.keys(revenueData).map(month => {
        const date = new Date(month + '-01');
        return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
    });

    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Revenue ($)',
                data: Object.values(revenueData),
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                borderColor: 'rgba(34, 197, 94, 1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
