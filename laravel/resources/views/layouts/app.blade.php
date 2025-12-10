<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name', 'SHMS') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('{{ asset("images/background.svg") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.5);
            -webkit-backdrop-filter: blur(10px);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }

        .sidebar {
            background: rgba(17, 25, 40, 0.85);
            -webkit-backdrop-filter: blur(15px);
            backdrop-filter: blur(15px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-link {
            transition: all 0.3s ease;
            color: #e5e7eb; /* gray-200 */
        }
        
        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            transform: translateX(5px);
            box-shadow: 0 0 15px rgba(52, 152, 219, 0.4);
        }

        .text-logo {
            font-weight: 700;
            font-size: 1.75rem;
            color: #ffffff;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .top-bar {
             background: rgba(255, 255, 255, 0.5);
            -webkit-backdrop-filter: blur(10px);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .glow-on-hover {
            transition: box-shadow 0.3s ease;
        }

        .glow-on-hover:hover {
            box-shadow: 0 0 20px rgba(74, 144, 226, 0.5);
        }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased">
    <div x-data="{
            sidebarOpen: window.innerWidth > 768,
            isMobile() { return window.innerWidth <= 768 },
            handleResize() { this.sidebarOpen = window.innerWidth > 768 }
         }" 
         x-init="() => window.addEventListener('resize', () => handleResize())"
         class="relative min-h-screen md:flex bg-gray-100/50">
        
        @auth
        <!-- Overlay for mobile -->
        <div x-show="sidebarOpen && isMobile()" @click="sidebarOpen = false" class="fixed inset-0 z-20 bg-black/50 md:hidden" x-cloak></div>

        <!-- Sidebar -->
        <aside 
            x-show="sidebarOpen"
            x-cloak
            @click.outside="if(isMobile()) sidebarOpen = false"
            class="sidebar text-gray-200 w-72 space-y-6 py-6 px-4 fixed inset-y-0 left-0 transform md:relative md:translate-x-0 transition-transform duration-300 ease-in-out z-30"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            
            <!-- Logo -->
            <a href="{{ route('dashboard') }}" class="text-logo px-4 flex items-center justify-center space-x-2">
                <i class="fas fa-heartbeat text-blue-400"></i>
                <span>SHMS</span>
            </a>

            <!-- Navigation Links -->
            <nav class="pt-8">
                {{-- Generic Dashboard Link --}}
                <a href="{{ route('dashboard') }}" class="block py-3 px-4 rounded-lg transition duration-200 sidebar-link {{ request()->routeIs('dashboard', 'admin.dashboard', 'doctor.dashboard', 'patient.dashboard', 'reception.dashboard', 'lab.dashboard', 'pharmacy.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt mr-3 w-5 text-center"></i>Dashboard
                </a>

                {{-- Admin Links --}}
                @if(Auth::user()->hasRole('admin'))
                    <h3 class="px-4 mt-6 mb-2 text-xs uppercase text-gray-400 font-bold tracking-wider">Management</h3>
                    <a href="{{ route('admin.users.index') }}" class="block py-3 px-4 rounded-lg transition duration-200 sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users-cog mr-3 w-5 text-center"></i>Users
                    </a>
                    <a href="{{ route('admin.doctors.index') }}" class="block py-3 px-4 rounded-lg transition duration-200 sidebar-link {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}">
                        <i class="fas fa-user-md mr-3 w-5 text-center"></i>Doctors
                    </a>
                    <a href="{{ route('admin.patients.index') }}" class="block py-3 px-4 rounded-lg transition duration-200 sidebar-link {{ request()->routeIs('admin.patients.*') ? 'active' : '' }}">
                        <i class="fas fa-user-injured mr-3 w-5 text-center"></i>Patients
                    </a>
                    <a href="{{ route('admin.appointments.index') }}" class="block py-3 px-4 rounded-lg transition duration-200 sidebar-link {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check mr-3 w-5 text-center"></i>Appointments
                    </a>
                    <a href="{{ route('admin.billing.index') }}" class="block py-3 px-4 rounded-lg transition duration-200 sidebar-link {{ request()->routeIs('admin.billing.*') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice-dollar mr-3 w-5 text-center"></i>Billing
                    </a>
                    <h3 class="px-4 mt-6 mb-2 text-xs uppercase text-gray-400 font-bold tracking-wider">Analytics</h3>
                     <a href="{{ route('admin.analytics') }}" class="block py-3 px-4 rounded-lg transition duration-200 sidebar-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                        <i class="fas fa-chart-line mr-3 w-5 text-center"></i>Analytics
                    </a>
                     <a href="{{ route('admin.reports') }}" class="block py-3 px-4 rounded-lg transition duration-200 sidebar-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie mr-3 w-5 text-center"></i>Reports
                    </a>
                @endif

                {{-- Patient Links --}}
                @if(Auth::user()->hasRole('patient'))
                    <a href="{{ route('patient.book-appointment') }}" class="block py-3 px-4 rounded-lg transition duration-200 sidebar-link {{ request()->routeIs('patient.book-appointment') ? 'active' : '' }}">
                        <i class="fas fa-calendar-plus mr-3 w-5 text-center"></i>Book Appointment
                    </a>
                    <a href="{{ route('patient.medical-history') }}" class="block py-3 px-4 rounded-lg transition duration-200 sidebar-link {{ request()->routeIs('patient.medical-history') ? 'active' : '' }}">
                        <i class="fas fa-file-medical-alt mr-3 w-5 text-center"></i>Medical History
                    </a>
                    <a href="{{ route('patient.prescriptions') }}" class="block py-3 px-4 rounded-lg transition duration-200 sidebar-link {{ request()->routeIs('patient.prescriptions') ? 'active' : '' }}">
                        <i class="fas fa-prescription mr-3 w-5 text-center"></i>Prescriptions
                    </a>
                    <a href="{{ route('patient.billing') }}" class="block py-3 px-4 rounded-lg transition duration-200 sidebar-link {{ request()->routeIs('patient.billing') ? 'active' : '' }}">
                        <i class="fas fa-receipt mr-3 w-5 text-center"></i>Billing
                    </a>
                     <a href="{{ route('patient.symptom-checker') }}" class="block py-3 px-4 rounded-lg transition duration-200 sidebar-link {{ request()->routeIs('patient.symptom-checker') ? 'active' : '' }}">
                        <i class="fas fa-diagnoses mr-3 w-5 text-center"></i>Symptom Checker
                    </a>
                @endif

                {{-- Doctor Links --}}
                @if(Auth::user()->hasRole('doctor'))
                    <a href="{{ route('doctor.appointments') }}" class="block py-3 px-4 rounded-lg transition duration-200 sidebar-link {{ request()->routeIs('doctor.appointments*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt mr-3 w-5 text-center"></i>Appointments
                    </a>
                    <a href="{{ route('doctor.prescriptions') }}" class="block py-3 px-4 rounded-lg transition duration-200 sidebar-link {{ request()->routeIs('doctor.prescriptions*') ? 'active' : '' }}">
                        <i class="fas fa-file-prescription mr-3 w-5 text-center"></i>Prescriptions
                    </a>
                @endif

                {{-- Reception Links --}}
                @if(Auth::user()->hasRole('reception'))
                     <a href="{{ route('reception.appointments') }}" class="block py-3 px-4 rounded-lg transition duration-200 sidebar-link {{ request()->routeIs('reception.appointments') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check mr-3 w-5 text-center"></i>Appointments
                    </a>
                     <a href="{{ route('reception.patients') }}" class="block py-3 px-4 rounded-lg transition duration-200 sidebar-link {{ request()->routeIs('reception.patients*') ? 'active' : '' }}">
                        <i class="fas fa-users mr-3 w-5 text-center"></i>Patients
                    </a>
                @endif

                {{-- Lab Technician Links --}}
                @if(Auth::user()->hasRole('lab'))
                    <a href="{{ route('lab.tests') }}" class="block py-3 px-4 rounded-lg transition duration-200 sidebar-link {{ request()->routeIs('lab.tests*') ? 'active' : '' }}">
                        <i class="fas fa-vial mr-3 w-5 text-center"></i>Lab Tests
                    </a>
                @endif
                
                {{-- Pharmacy Links --}}
                @if(Auth::user()->hasRole('pharmacy'))
                    <a href="{{ route('pharmacy.prescriptions') }}" class="block py-3 px-4 rounded-lg transition duration-200 sidebar-link {{ request()->routeIs('pharmacy.prescriptions*') ? 'active' : '' }}">
                        <i class="fas fa-pills mr-3 w-5 text-center"></i>Prescriptions
                    </a>
                @endif
            </nav>
        </aside>
        @endauth

        <!-- Main Content -->
        <div class="flex-1 flex flex-col w-full">
            <!-- Top Bar -->
            <header class="top-bar sticky top-0 z-10">
                <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-20">
                        <!-- Hamburger -->
                        @auth
                        <div class="flex items-center">
                            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 focus:outline-none focus:text-gray-800">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>
                        @endauth
                        
                        <div class="flex-grow pl-4">
                           <h1 class="text-xl font-semibold text-gray-700">@yield('title')</h1>
                        </div>

                        <!-- User Dropdown -->
                        @auth
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-4 font-medium rounded-full text-gray-600 bg-white/70 hover:text-gray-800 focus:outline-none transition ease-in-out duration-150 glow-on-hover">
                                        <div>{{ Auth::user()->name }}</div>
                                        <div class="ml-2">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>
                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                            </x-dropdown>
                        </div>
                        @else
                         <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a>
                            @endif
                        </div>
                        @endauth
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6 md:p-8">
                 @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
