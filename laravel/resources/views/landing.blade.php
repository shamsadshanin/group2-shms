<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SHMS - Smart Healthcare Management</title>
    <meta name="description" content="Streamline your healthcare services with SHMS.">

    <!-- Fonts: Inter (Body) and Poppins (Headings) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Tailwind CSS (CDN for immediate preview) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js for interactions -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Custom Utilities */
        .glass-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(229, 231, 235, 0.5);
        }
        
        .hero-pattern {
            background-color: #1e40af;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%233b82f6' fill-opacity='0.12'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .blob {
            position: absolute;
            filter: blur(40px);
            z-index: 0;
            opacity: 0.4;
        }

        /* Tech Icon Grayscale to Color transition */
        .tech-icon {
            filter: grayscale(100%);
            opacity: 0.7;
            transition: all 0.3s ease;
        }
        .tech-card:hover .tech-icon {
            filter: grayscale(0%);
            opacity: 1;
            transform: scale(1.1);
        }
    </style>
</head>
<body class="antialiased text-gray-600 font-sans bg-white selection:bg-brand-100 selection:text-brand-900">

    <div id="home" class="relative">
        
        <!-- Navbar -->
        <header 
            x-data="{ scrolled: false, mobileMenu: false }" 
            @scroll.window="scrolled = (window.pageYOffset > 20)" 
            class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
            :class="scrolled ? 'glass-nav py-3 shadow-sm' : 'bg-transparent py-5'"
        >
            <div class="container mx-auto px-6">
                <div class="flex justify-between items-center">
                    <!-- Logo -->
                    <a href="#home" class="flex items-center gap-2 group">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-400 rounded-lg flex items-center justify-center text-white shadow-lg group-hover:shadow-blue-500/30 transition-all">
                            <i class="fas fa-heartbeat text-xl"></i>
                        </div>
                        <span class="text-2xl font-heading font-bold" :class="scrolled ? 'text-gray-900' : 'text-white'">SHMS</span>
                    </a>

                    <!-- Desktop Nav -->
                    <nav class="hidden md:flex items-center space-x-8">
                        <template x-for="item in ['Features', 'Technology', 'Pricing', 'Contact']">
                            <a :href="'#' + item.toLowerCase()" 
                               class="text-sm font-medium transition-colors hover:text-blue-400"
                               :class="scrolled ? 'text-gray-600' : 'text-gray-200'"
                               x-text="item"></a>
                        </template>
                    </nav>

                    <!-- CTA Buttons -->
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium rounded-lg transition-colors" 
                           :class="scrolled ? 'text-gray-600 hover:text-brand-600' : 'text-white hover:text-blue-200'">Log In</a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm font-medium text-white rounded-full bg-brand-600 hover:bg-brand-700 shadow-lg shadow-blue-500/30 transition-all hover:-translate-y-0.5">Sign Up</a>
                    </div>
                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenu = !mobileMenu" class="md:hidden focus:outline-none">
                        <i class="fas fa-bars text-2xl" :class="scrolled ? 'text-gray-800' : 'text-white'"></i>
                    </button>
                </div>

                <!-- Mobile Menu Dropdown -->
                <div x-show="mobileMenu" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     @click.away="mobileMenu = false"
                     class="absolute top-full left-0 right-0 bg-white border-b border-gray-100 shadow-xl p-4 md:hidden flex flex-col space-y-4 text-center mt-1">
                    <a href="#features" class="text-gray-600 font-medium py-2">Features</a>
                    <a href="#technology" class="text-gray-600 font-medium py-2">Technology</a>
                    <a href="#pricing" class="text-gray-600 font-medium py-2">Pricing</a>
                    <div class="h-px bg-gray-100 my-2"></div>
                    <a href="#login" class="text-brand-600 font-bold">Log In</a>
                    <a href="#register" class="bg-brand-600 text-white py-2 rounded-lg font-medium">Sign Up Free</a>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="relative hero-pattern pt-40 pb-32 overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-cyan-500 rounded-full blur-3xl opacity-20"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-purple-500 rounded-full blur-3xl opacity-20"></div>

            <div class="container mx-auto px-6 relative z-10">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div class="text-center lg:text-left">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-800/50 border border-blue-700/50 text-blue-200 text-xs font-semibold uppercase tracking-wide mb-6">
                            <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                            New Version 2.0 Available
                        </div>
                        <h1 class="text-4xl lg:text-6xl font-heading font-extrabold text-white leading-tight mb-6">
                            Healthcare Management <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-cyan-300">Reimagined</span>
                        </h1>
                        <p class="text-lg text-blue-100 mb-8 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                            SHMS integrates patients, doctors, and administration into one seamless ecosystem. Experience the future of hospital efficiency today.
                        </p>
                        <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                            <a href="#features" class="bg-white text-brand-700 font-bold py-3.5 px-8 rounded-full shadow-xl shadow-blue-900/20 hover:bg-gray-50 transition-all transform hover:-translate-y-1">
                                Explore Features
                            </a>
                            <a href="#contact" class="flex items-center justify-center gap-2 border border-white/30 bg-white/10 backdrop-blur-sm text-white font-semibold py-3.5 px-8 rounded-full hover:bg-white/20 transition-all">
                                <span>Watch Demo</span>
                                <i class="fas fa-play-circle"></i>
                            </a>
                        </div>
                    </div>
                    <!-- Hero Image Placeholder -->
                    <div class="relative hidden lg:block">
                        <div class="absolute inset-0 bg-gradient-to-tr from-brand-600 to-cyan-400 rounded-2xl transform rotate-6 opacity-30 blur-lg"></div>
                        <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Doctor with Tablet" class="relative rounded-2xl shadow-2xl border-4 border-white/10 w-full object-cover h-[500px]">
                        
                        <!-- Floating Badge -->
                        <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-xl shadow-xl flex items-center gap-4 animate-bounce" style="animation-duration: 3s;">
                            <div class="bg-green-100 p-3 rounded-full text-green-600">
                                <i class="fas fa-check-circle text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 font-medium">System Status</p>
                                <p class="text-gray-800 font-bold">100% Secure</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wave Separator -->
            <div class="absolute bottom-0 left-0 right-0">
                <svg class="fill-current text-white" viewBox="0 0 1440 100" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0,32L48,37.3C96,43,192,53,288,58.7C384,64,480,64,576,58.7C672,53,768,43,864,42.7C960,43,1056,53,1152,53.3C1248,53,1344,43,1392,37.3L1440,32L1440,100L1392,100C1344,100,1248,100,1152,100C1056,100,960,100,864,100C768,100,672,100,576,100C480,100,384,100,288,100C192,100,96,100,48,100L0,100Z"></path>
                </svg>
            </div>
        </section>

        <main>
            <!-- Introduction Section -->
            <section id="introduction" class="py-20 bg-white">
                <div class="container mx-auto px-6 text-center">
                    <span class="text-brand-600 font-bold tracking-wider uppercase text-sm">About Us</span>
                    <h2 class="font-heading text-3xl md:text-4xl font-bold text-gray-900 mt-2 mb-6">A New Era in Health Management</h2>
                    <p class="text-lg text-gray-600 max-w-3xl mx-auto leading-relaxed">
                        SHMS isn't just software; it's a bridge between technology and care. We simplify the complex daily management of hospitals, creating a transparent communication system between doctors, patients, and administration.
                    </p>
                </div>
            </section>

            <!-- Features Section -->
            <section id="features" class="py-24 bg-gray-50 relative">
                 <div class="absolute top-0 right-0 w-1/3 h-full bg-gray-100 opacity-50 z-0 skew-x-12 pointer-events-none"></div>

                <div class="container mx-auto px-6 relative z-10">
                    <div class="text-center mb-16">
                        <h2 class="font-heading text-3xl md:text-4xl font-bold text-gray-900 mb-4">Powerful Features</h2>
                        <p class="text-gray-500 text-lg">Everything you need to run a modern medical facility.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <!-- Card 1 -->
                        <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 group">
                            <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 text-2xl mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <i class="fas fa-users-cog"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Multi-Role Dashboards</h3>
                            <p class="text-gray-500 leading-relaxed">Dedicated interfaces tailored for Admin, Doctors, Pharmacists, and Lab Technicians.</p>
                        </div>

                        <!-- Card 2 -->
                        <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 group">
                            <div class="w-14 h-14 bg-teal-50 rounded-xl flex items-center justify-center text-teal-600 text-2xl mb-6 group-hover:bg-teal-600 group-hover:text-white transition-colors">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Smart Scheduling</h3>
                            <p class="text-gray-500 leading-relaxed">Patients can book slots seamlessly while doctors manage their availability in real-time.</p>
                        </div>

                        <!-- Card 3 -->
                        <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 group">
                            <div class="w-14 h-14 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 text-2xl mb-6 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                                <i class="fas fa-file-medical"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">E-Prescriptions</h3>
                            <p class="text-gray-500 leading-relaxed">Generate secure digital prescriptions instantly accessible to patients and internal pharmacies.</p>
                        </div>

                        <!-- Card 4 -->
                        <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 group">
                            <div class="w-14 h-14 bg-red-50 rounded-xl flex items-center justify-center text-red-600 text-2xl mb-6 group-hover:bg-red-600 group-hover:text-white transition-colors">
                                <i class="fas fa-heart-pulse"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Unified Records</h3>
                            <p class="text-gray-500 leading-relaxed">A centralized history of patient vitals, test results, and treatments encrypted for security.</p>
                        </div>

                        <!-- Card 5 -->
                        <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 group">
                            <div class="w-14 h-14 bg-yellow-50 rounded-xl flex items-center justify-center text-yellow-600 text-2xl mb-6 group-hover:bg-yellow-600 group-hover:text-white transition-colors">
                                <i class="fas fa-robot"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">AI Symptom Check</h3>
                            <p class="text-gray-500 leading-relaxed">An intelligent triage assistant that suggests potential conditions based on reported symptoms.</p>
                        </div>

                        <!-- Card 6 -->
                        <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 group">
                            <div class="w-14 h-14 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 text-2xl mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Deep Analytics</h3>
                            <p class="text-gray-500 leading-relaxed">Visual data reports for hospital administrators to monitor efficiency and resource allocation.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Tech Stack -->
            <section id="technology" class="py-20 bg-white border-y border-gray-100">
                <div class="container mx-auto px-6">
                    <p class="text-center text-gray-400 font-medium mb-10 uppercase tracking-widest text-sm">Powered by modern technology</p>
                    <div class="flex flex-wrap justify-center items-center gap-12 md:gap-20">
                        <div class="tech-card flex flex-col items-center group cursor-default">
                            <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/laravel/laravel-plain.svg" alt="Laravel" class="h-12 md:h-16 mb-3 tech-icon">
                            <span class="text-sm font-semibold text-gray-400 group-hover:text-gray-800 transition-colors">Laravel</span>
                        </div>
                        <div class="tech-card flex flex-col items-center group cursor-default">
                            <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-plain.svg" alt="PHP" class="h-12 md:h-16 mb-3 tech-icon">
                            <span class="text-sm font-semibold text-gray-400 group-hover:text-gray-800 transition-colors">PHP 8.2</span>
                        </div>
                        <div class="tech-card flex flex-col items-center group cursor-default">
                            <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original.svg" alt="MySQL" class="h-12 md:h-16 mb-3 tech-icon">
                            <span class="text-sm font-semibold text-gray-400 group-hover:text-gray-800 transition-colors">MySQL</span>
                        </div>
                        <div class="tech-card flex flex-col items-center group cursor-default">
                            <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/tailwindcss/tailwindcss-original.svg" alt="Tailwind" class="h-12 md:h-16 mb-3 tech-icon">
                            <span class="text-sm font-semibold text-gray-400 group-hover:text-gray-800 transition-colors">Tailwind</span>
                        </div>
                        <div class="tech-card flex flex-col items-center group cursor-default">
                            <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/alpinejs/alpinejs-original.svg" alt="Alpine" class="h-12 md:h-16 mb-3 tech-icon">
                            <span class="text-sm font-semibold text-gray-400 group-hover:text-gray-800 transition-colors">Alpine.js</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Pricing Section -->
            <section id="pricing" class="py-24 bg-gray-50">
                <div class="container mx-auto px-6">
                    <div class="text-center mb-16">
                        <h2 class="font-heading text-3xl md:text-4xl font-bold text-gray-900 mb-4">Simple, Transparent Pricing</h2>
                        <p class="text-gray-500 text-lg">Choose the perfect plan for your healthcare institution.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto items-center">
                        <!-- Basic Plan -->
                        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:border-gray-300 transition-all">
                            <h3 class="text-lg font-bold text-gray-500 mb-4">Basic Clinic</h3>
                            <div class="flex items-baseline mb-6">
                                <span class="text-4xl font-extrabold text-gray-900">৳30k</span>
                                <span class="text-gray-400 ml-2">/year</span>
                            </div>
                            <p class="text-sm text-gray-500 mb-8">Essential tools for small private practices.</p>
                            
                            <ul class="space-y-4 mb-8">
                                <li class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-check text-green-500 mr-3"></i> 50 Active Patients
                                </li>
                                <li class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-check text-green-500 mr-3"></i> 5 Doctor Accounts
                                </li>
                                <li class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-check text-green-500 mr-3"></i> Basic Scheduling
                                </li>
                                <li class="flex items-center text-sm text-gray-400">
                                    <i class="fas fa-times mr-3"></i> E-Prescriptions
                                </li>
                            </ul>
                            <a href="#contact" class="block w-full py-3 px-6 bg-gray-100 text-gray-800 text-center font-bold rounded-lg hover:bg-gray-200 transition-colors">Contact Sales</a>
                        </div>

                        <!-- Standard Plan (Popular) -->
                        <div class="bg-white p-10 rounded-2xl shadow-xl border-2 border-brand-500 relative transform scale-105 z-10">
                            <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-gradient-to-r from-blue-600 to-cyan-500 text-white px-4 py-1 rounded-full text-xs font-bold tracking-wide shadow-lg">MOST POPULAR</div>
                            
                            <h3 class="text-lg font-bold text-brand-600 mb-4">Standard Hospital</h3>
                            <div class="flex items-baseline mb-6">
                                <span class="text-5xl font-extrabold text-gray-900">৳60k</span>
                                <span class="text-gray-400 ml-2">/year</span>
                            </div>
                            <p class="text-sm text-gray-500 mb-8">Perfect for growing hospitals and clinics.</p>
                            
                            <ul class="space-y-4 mb-8">
                                <li class="flex items-center text-sm text-gray-800 font-medium">
                                    <i class="fas fa-check-circle text-brand-500 mr-3"></i> 500 Active Patients
                                </li>
                                <li class="flex items-center text-sm text-gray-800 font-medium">
                                    <i class="fas fa-check-circle text-brand-500 mr-3"></i> 25 Doctor Accounts
                                </li>
                                <li class="flex items-center text-sm text-gray-800 font-medium">
                                    <i class="fas fa-check-circle text-brand-500 mr-3"></i> E-Prescriptions
                                </li>
                                <li class="flex items-center text-sm text-gray-800 font-medium">
                                    <i class="fas fa-check-circle text-brand-500 mr-3"></i> Billing Module
                                </li>
                            </ul>
                            <a href="#contact" class="block w-full py-4 px-6 bg-gradient-to-r from-blue-600 to-cyan-500 text-white text-center font-bold rounded-lg shadow-lg hover:shadow-xl hover:opacity-90 transition-all">Get Started</a>
                        </div>

                        <!-- Premium Plan -->
                        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:border-gray-300 transition-all">
                            <h3 class="text-lg font-bold text-gray-500 mb-4">Enterprise</h3>
                            <div class="flex items-baseline mb-6">
                                <span class="text-4xl font-extrabold text-gray-900">৳100k</span>
                                <span class="text-gray-400 ml-2">/year</span>
                            </div>
                            <p class="text-sm text-gray-500 mb-8">For large multi-specialty centers.</p>
                            
                            <ul class="space-y-4 mb-8">
                                <li class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-check text-green-500 mr-3"></i> Unlimited Patients
                                </li>
                                <li class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-check text-green-500 mr-3"></i> Unlimited Doctors
                                </li>
                                <li class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-check text-green-500 mr-3"></i> Advanced Analytics
                                </li>
                                <li class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-check text-green-500 mr-3"></i> 24/7 Priority Support
                                </li>
                            </ul>
                            <a href="#contact" class="block w-full py-3 px-6 bg-gray-100 text-gray-800 text-center font-bold rounded-lg hover:bg-gray-200 transition-colors">Contact Sales</a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Call to Action -->
            <section id="contact" class="py-24 bg-brand-900 relative overflow-hidden">
                <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/medical-icons.png')]"></div>
                
                <div class="container mx-auto px-6 text-center relative z-10">
                    <h2 class="text-3xl md:text-5xl font-heading font-bold text-white mb-6">Ready to Digitize Your Hospital?</h2>
                    <p class="text-blue-200 text-lg max-w-2xl mx-auto mb-10">
                        Join over 100+ healthcare institutions using SHMS to save time and save lives. Schedule a demo today.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        <a href="mailto:info@SHMS.com" class="bg-white text-brand-900 font-bold py-4 px-10 rounded-full text-lg hover:bg-blue-50 transition-transform transform hover:-translate-y-1 shadow-2xl">
                            Request Demo
                        </a>
                        <a href="#" class="border-2 border-blue-400 text-blue-100 font-bold py-4 px-10 rounded-full text-lg hover:bg-blue-800 hover:border-blue-300 transition-colors">
                            Talk to Support
                        </a>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-300 py-12 border-t border-gray-800">
            <div class="container mx-auto px-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-6 md:mb-0 text-center md:text-left">
                        <div class="flex items-center justify-center md:justify-start gap-2 mb-2">
                             <i class="fas fa-heartbeat text-brand-500 text-2xl"></i>
                             <span class="text-2xl font-bold text-white">SHMS</span>
                        </div>
                        <p class="text-sm text-gray-500">Smart solutions for modern healthcare.</p>
                    </div>
                    
                    <div class="flex space-x-6 text-2xl">
                        <a href="#" class="hover:text-white transition-colors"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="hover:text-white transition-colors"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="hover:text-white transition-colors"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                
                <div class="border-t border-gray-800 mt-10 pt-6 text-center text-sm text-gray-500">
                    <p>&copy; 2024 SHMS Systems. All Rights Reserved.</p>
                    <p class="mt-2">Made with <i class="fas fa-heart text-red-500 animate-pulse"></i> for better health.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>