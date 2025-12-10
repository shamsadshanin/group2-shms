@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div>
    <!-- Welcome Header -->
    <div class="mb-10">
        <h1 class="text-4xl font-bold text-gray-800">Welcome, {{ auth()->user()->name }}!</h1>
        <p class="mt-2 text-lg text-gray-600">Here's a snapshot of your hospital's activities.</p>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-8 mb-10">
        <div class="glass-card p-6 glow-on-hover">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-500/20 rounded-xl p-4">
                    <i class="fas fa-users text-blue-500 text-3xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Patients</dt>
                        <dd class="text-3xl font-bold text-gray-800">{{ $totalPatients }}</dd>
                        <dd class="text-sm text-green-600 font-semibold">
                            <i class="fas fa-arrow-up"></i> {{ round($patientGrowth) }}% 
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="glass-card p-6 glow-on-hover">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-500/20 rounded-xl p-4">
                    <i class="fas fa-user-md text-green-500 text-3xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Active Doctors</dt>
                        <dd class="text-3xl font-bold text-gray-800">{{ $activeDoctors }}</dd>
                        <dd class="text-sm text-gray-500 font-semibold">
                            <i class="fas fa-check-circle"></i> All available
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="glass-card p-6 glow-on-hover">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-500/20 rounded-xl p-4">
                    <i class="fas fa-dollar-sign text-yellow-500 text-3xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                        <dd class="text-3xl font-bold text-gray-800">৳{{ number_format($totalRevenue, 0) }}</dd>
                        <dd class="text-sm text-green-600 font-semibold">
                            <i class="fas fa-arrow-up"></i> {{ round($revenueGrowth) }}%
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="glass-card p-6 glow-on-hover">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-500/20 rounded-xl p-4">
                    <i class="fas fa-calendar-check text-purple-500 text-3xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Today's Appointments</dt>
                        <dd class="text-3xl font-bold text-gray-800">{{ $todayAppointments }}</dd>
                        <dd class="text-sm text-orange-600 font-semibold">
                            <i class="fas fa-clock"></i> {{ $pendingAppointments }} pending
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <!-- Main Charts -->
        <div class="lg:col-span-3 space-y-8">
            <div class="glass-card p-6">
                <h3 class="text-lg leading-6 font-semibold text-gray-700 mb-4">Revenue Overview (Last 7 Days)</h3>
                <div class="h-80">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
            <div class="glass-card p-6">
                <h3 class="text-lg leading-6 font-semibold text-gray-700 mb-4">Department Performance</h3>
                <div class="space-y-4">
                     @foreach($departmentStats as $dept)
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $dept->name }}</span>
                            <span class="text-sm text-gray-500">{{ $dept->appointments_count }} appointments</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-gradient-to-r from-cyan-400 to-blue-600 h-2.5 rounded-full" style="width: {{ $dept->performance }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Side Cards -->
        <div class="lg:col-span-2 space-y-8">
            <div class="glass-card p-6">
                 <h3 class="text-lg leading-6 font-semibold text-gray-700 mb-4">Patient Demographics</h3>
                <div class="h-56 flex justify-center items-center">
                    <canvas id="demographicsChart"></canvas>
                </div>
            </div>
             <div class="glass-card p-6">
                <h3 class="text-lg leading-6 font-semibold text-gray-700 mb-4">Recent Activities</h3>
                <div class="space-y-4 max-h-56 overflow-y-auto pr-2">
                    @forelse($recentActivities as $activity)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-500/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-{{ $activity->icon }} text-blue-500"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-800">{{ $activity->description }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $activity->time }}</p>
                        </div>
                    </div>
                    @empty
                        <p class="text-sm text-gray-500">No recent activities.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-10">
        <h3 class="text-2xl font-semibold text-gray-700 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
             <a href="{{ route('admin.users.index') }}" class="glass-card p-6 flex flex-col items-center justify-center glow-on-hover transform hover:-translate-y-1 transition-transform duration-300">
                <i class="fas fa-users-cog text-blue-600 text-4xl mb-3"></i>
                <span class="text-md font-semibold text-gray-800">User Management</span>
            </a>
            <a href="{{ route('admin.reports') }}" class="glass-card p-6 flex flex-col items-center justify-center glow-on-hover transform hover:-translate-y-1 transition-transform duration-300">
                <i class="fas fa-chart-line text-green-600 text-4xl mb-3"></i>
                <span class="text-md font-semibold text-gray-800">Reports</span>
            </a>
            <a href="#" class="glass-card p-6 flex flex-col items-center justify-center glow-on-hover transform hover:-translate-y-1 transition-transform duration-300">
                <i class="fas fa-cog text-purple-600 text-4xl mb-3"></i>
                <span class="text-md font-semibold text-gray-800">Settings</span>
            </a>
            <a href="#" class="glass-card p-6 flex flex-col items-center justify-center glow-on-hover transform hover:-translate-y-1 transition-transform duration-300">
                <i class="fas fa-database text-yellow-600 text-4xl mb-3"></i>
                <span class="text-md font-semibold text-gray-800">Backup</span>
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    color: '#4b5563' // gray-600
                }
            }
        },
        scales: {
            y: {
                ticks: { color: '#6b7280' }, // gray-500
                grid: { color: 'rgba(209, 213, 219, 0.3)' }
            },
            x: {
                ticks: { color: '#6b7280' }, // gray-500
                grid: { display: false }
            }
        }
    };

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart')?.getContext('2d');
    if (revenueCtx) {
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: @json($revenueChartData['labels']),
                datasets: [{
                    label: 'Revenue',
                    data: @json($revenueChartData['data']),
                    borderColor: '#3b82f6', // blue-500
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                ...chartOptions,
                scales: {
                     y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#6b7280',
                            callback: function(value) {
                                return '৳' + value.toLocaleString();
                            }
                        },
                        grid: { color: 'rgba(209, 213, 219, 0.3)' }
                    },
                     x: {
                        ticks: { color: '#6b7280' },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // Demographics Chart
    const demographicsCtx = document.getElementById('demographicsChart')?.getContext('2d');
    if (demographicsCtx) {
        new Chart(demographicsCtx, {
            type: 'doughnut',
            data: {
                labels: @json($demographicsChartData['labels']),
                datasets: [{
                    data: @json($demographicsChartData['data']),
                    backgroundColor: [
                        '#3b82f6', // Male
                        '#ec4899', // Female
                        '#9ca3af'  // Other
                    ],
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 4
                }]
            },
            options: {
                ...chartOptions,
                cutout: '80%',
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
});
</script>
@endsection
