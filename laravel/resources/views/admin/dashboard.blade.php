@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div>
    <div class="mb-10">
        <h1 class="text-4xl font-bold text-gray-800">Welcome, {{ auth()->user()->name }}!</h1>
        <p class="mt-2 text-lg text-gray-600">Here's a snapshot of your hospital's activities.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-8 mb-10">
        <div class="glass-card p-6 glow-on-hover bg-white rounded-xl shadow-md border border-gray-100">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-500/10 rounded-xl p-4">
                    <i class="fas fa-users text-blue-600 text-3xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Patients</dt>
                        <dd class="text-3xl font-bold text-gray-800">{{ $totalPatients }}</dd>
                        <dd class="text-sm {{ $patientGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} font-semibold flex items-center mt-1">
                            <i class="fas fa-arrow-{{ $patientGrowth >= 0 ? 'up' : 'down' }} mr-1"></i>
                            {{ abs(round($patientGrowth, 1)) }}% from last month
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="glass-card p-6 glow-on-hover bg-white rounded-xl shadow-md border border-gray-100">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-500/10 rounded-xl p-4">
                    <i class="fas fa-user-md text-green-600 text-3xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Active Doctors</dt>
                        <dd class="text-3xl font-bold text-gray-800">{{ $activeDoctors }}</dd>
                        <dd class="text-sm text-gray-500 font-semibold mt-1">
                            <i class="fas fa-check-circle mr-1"></i> Registered Specialists
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="glass-card p-6 glow-on-hover bg-white rounded-xl shadow-md border border-gray-100">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-500/10 rounded-xl p-4">
                    <i class="fas fa-dollar-sign text-yellow-600 text-3xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                        <dd class="text-3xl font-bold text-gray-800">${{ number_format($totalRevenue, 0) }}</dd>
                        <dd class="text-sm {{ $revenueGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} font-semibold flex items-center mt-1">
                            <i class="fas fa-arrow-{{ $revenueGrowth >= 0 ? 'up' : 'down' }} mr-1"></i>
                            {{ abs(round($revenueGrowth, 1)) }}% from last month
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="glass-card p-6 glow-on-hover bg-white rounded-xl shadow-md border border-gray-100">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-500/10 rounded-xl p-4">
                    <i class="fas fa-calendar-check text-purple-600 text-3xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Today's Appointments</dt>
                        <dd class="text-3xl font-bold text-gray-800">{{ $todayAppointments }}</dd>
                        <dd class="text-sm text-orange-600 font-semibold mt-1">
                            <i class="fas fa-clock mr-1"></i> {{ $pendingAppointments }} Pending
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <div class="lg:col-span-3 space-y-8">
            <div class="glass-card p-6 bg-white rounded-xl shadow-md border border-gray-100">
                <h3 class="text-lg leading-6 font-semibold text-gray-700 mb-4">Revenue Overview (Last 7 Days)</h3>
                <div class="h-80 relative w-full">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <div class="glass-card p-6 bg-white rounded-xl shadow-md border border-gray-100">
                <h3 class="text-lg leading-6 font-semibold text-gray-700 mb-4">Appointments by Specialization</h3>
                <div class="space-y-4">
                     @forelse($departmentStats as $dept)
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $dept['name'] }}</span>
                            <span class="text-sm text-gray-500">{{ $dept['count'] }} appointments</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-gradient-to-r from-cyan-400 to-blue-600 h-2.5 rounded-full" style="width: {{ $dept['percentage'] }}%"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm">No appointment data available yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-8">
            <div class="glass-card p-6 bg-white rounded-xl shadow-md border border-gray-100">
                 <h3 class="text-lg leading-6 font-semibold text-gray-700 mb-4">Patient Demographics</h3>
                <div class="h-64 flex justify-center items-center relative w-full">
                    <canvas id="demographicsChart"></canvas>
                </div>
            </div>

            <div class="glass-card p-6 bg-white rounded-xl shadow-md border border-gray-100">
                <h3 class="text-lg leading-6 font-semibold text-gray-700 mb-4">Recent Activities</h3>
                <div class="space-y-4 max-h-80 overflow-y-auto pr-2">
                    @forelse($recentActivities as $activity)
                    <div class="flex items-start space-x-3 border-b border-gray-50 pb-3 last:border-0">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-{{ $activity->icon }} text-blue-500"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800">{{ $activity->description }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $activity->time }}</p>
                        </div>
                    </div>
                    @empty
                        <p class="text-sm text-gray-500">No recent activities found.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="mt-10">
        <h3 class="text-2xl font-semibold text-gray-700 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
             <a href="{{ route('admin.users.index') }}" class="glass-card p-6 bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col items-center justify-center hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                <i class="fas fa-users-cog text-blue-600 text-3xl mb-3"></i>
                <span class="text-sm font-semibold text-gray-700">User Management</span>
            </a>
            <a href="{{ route('admin.reports') }}" class="glass-card p-6 bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col items-center justify-center hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                <i class="fas fa-chart-line text-green-600 text-3xl mb-3"></i>
                <span class="text-sm font-semibold text-gray-700">Reports</span>
            </a>
            <a href="{{ route('admin.billing.index') }}" class="glass-card p-6 bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col items-center justify-center hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                <i class="fas fa-file-invoice-dollar text-yellow-600 text-3xl mb-3"></i>
                <span class="text-sm font-semibold text-gray-700">Billing</span>
            </a>
            <a href="{{ route('admin.appointments.create') }}" class="glass-card p-6 bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col items-center justify-center hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                <i class="fas fa-plus-circle text-purple-600 text-3xl mb-3"></i>
                <span class="text-sm font-semibold text-gray-700">New Appointment</span>
            </a>
        </div>
    </div>
</div>

{{-- Load Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Shared Options
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { labels: { color: '#4b5563' } }
        }
    };

    // 1. Revenue Chart Configuration
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        new Chart(revenueCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: @json($revenueChartData['labels']),
                datasets: [{
                    label: 'Revenue ($)',
                    data: @json($revenueChartData['data']),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#3b82f6',
                    pointRadius: 4
                }]
            },
            options: {
                ...chartOptions,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(209, 213, 219, 0.3)' },
                        ticks: { callback: (val) => '$' + val }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // 2. Demographics Chart Configuration
    const demographicsCtx = document.getElementById('demographicsChart');
    if (demographicsCtx) {
        new Chart(demographicsCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: @json($demographicsChartData['labels']),
                datasets: [{
                    data: @json($demographicsChartData['data']),
                    backgroundColor: [
                        '#3b82f6', // Male (Blue)
                        '#ec4899', // Female (Pink)
                        '#10b981', // Other (Green)
                        '#9ca3af'  // Unknown (Gray)
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                ...chartOptions,
                cutout: '70%',
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
});
</script>
@endsection
