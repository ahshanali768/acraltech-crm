<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ 
    darkMode: localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches),
    sidebarOpen: false,
    mobileSidebarOpen: false,
    sidebarCollapsed: localStorage.getItem('publisherSidebarCollapsed') === 'true' 
}" 
x-init="
    $watch('darkMode', val => {
        localStorage.setItem('darkMode', val);
        if (val) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    });
    $watch('sidebarCollapsed', value => localStorage.setItem('publisherSidebarCollapsed', value));
    if (darkMode) {
        document.documentElement.classList.add('dark');
    }
" 
:class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Publisher Dashboard') - {{ config('app.name', 'CRM System') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'ui-sans-serif', 'system-ui'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-in': 'slideIn 0.3s ease-out',
                        'pulse-glow': 'pulseGlow 2s infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        slideIn: {
                            '0%': { transform: 'translateX(-100%)' },
                            '100%': { transform: 'translateX(0)' },
                        },
                        pulseGlow: {
                            '0%, 100%': { boxShadow: '0 0 20px rgba(59, 130, 246, 0.5)' },
                            '50%': { boxShadow: '0 0 30px rgba(59, 130, 246, 0.8)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @stack('styles')
    
    <style>
        [x-cloak] { display: none !important; }
        
        .glass-morphism {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        .dark .glass-morphism {
            background: rgba(15, 23, 42, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-gradient {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .dark .sidebar-gradient {
            background: linear-gradient(135deg, #047857 0%, #065f46 100%);
        }
        
        .scrollbar-thin::-webkit-scrollbar {
            width: 4px;
        }
        
        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 2px;
        }
        
        .dark .scrollbar-thin::-webkit-scrollbar-track {
            background: #1e293b;
        }
        
        .dark .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #475569;
        }
        
        .metric-card {
            transition: all 0.3s ease;
        }
        
        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .dark .metric-card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }
        
        .notification-dot {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-sans antialiased">
    <!-- Mobile Sidebar Overlay -->
    <div x-show="mobileSidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 lg:hidden">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="mobileSidebarOpen = false"></div>
    </div>
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 bg-white dark:bg-gray-800 shadow-xl transform transition-all duration-300 lg:translate-x-0"
         :class="[sidebarCollapsed ? 'w-20' : 'w-64', 'transition-all', 'duration-300', mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full', 'lg:translate-x-0']">
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between h-16 px-6 sidebar-gradient">
            <div class="flex items-center gap-2 w-full">
                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center" x-show="!sidebarCollapsed" style="transition:all .2s">
                    <i class="fas fa-phone-volume text-green-600 text-lg"></i>
                </div>
                <h1 class="text-xl font-bold text-white whitespace-nowrap ml-2" x-show="!sidebarCollapsed" style="transition:all .2s">Publisher Portal</h1>
                <!-- Sidebar Collapse Toggle (desktop only, burger style, aligned) -->
                <button @click="sidebarCollapsed = !sidebarCollapsed" class="ml-2 text-white focus:outline-none hidden lg:flex flex-col items-center justify-center w-8 h-8 relative" title="Collapse Sidebar">
                    <span class="block w-5 h-0.5 bg-white mb-1 transition-all duration-200"></span>
                    <span class="block w-5 h-0.5 bg-white mb-1 transition-all duration-200"></span>
                    <span class="block w-5 h-0.5 bg-white transition-all duration-200"></span>
                </button>
            </div>
            <button @click="mobileSidebarOpen = false" class="lg:hidden text-white hover:text-gray-200">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <!-- Sidebar Navigation -->
        <nav class="mt-6 scrollbar-thin overflow-y-auto h-full pb-20 transition-all duration-200"
            :class="sidebarCollapsed ? 'w-20 px-0' : 'w-64 px-3'">
            <div class="space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('publisher.dashboard') }}" 
                   class="flex items-center py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('publisher.dashboard') ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                   :class="sidebarCollapsed ? 'justify-center' : 'px-3'">
                    <i class="fas fa-tachometer-alt text-lg" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                    <span x-show="!sidebarCollapsed" class="transition-all duration-200">Dashboard</span>
                </a>

                <!-- Analytics -->
                <a href="{{ route('publisher.analytics') }}" 
                   class="flex items-center py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('publisher.analytics') ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                   :class="sidebarCollapsed ? 'justify-center' : 'px-3'">
                    <i class="fas fa-chart-bar text-lg" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                    <span x-show="!sidebarCollapsed" class="transition-all duration-200">Analytics & Reports</span>
                </a>

                <!-- Call History -->
                <a href="{{ route('publisher.call-history') }}" 
                   class="flex items-center py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('publisher.call-history') ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                   :class="sidebarCollapsed ? 'justify-center' : 'px-3'">
                    <i class="fas fa-phone text-lg" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                    <span x-show="!sidebarCollapsed" class="transition-all duration-200">Call History</span>
                </a>

                <!-- Profile Settings -->
                <a href="{{ route('profile.edit') }}" 
                   class="flex items-center py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('profile.edit') ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                   :class="sidebarCollapsed ? 'justify-center' : 'px-3'">
                    <i class="fas fa-user text-lg" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                    <span x-show="!sidebarCollapsed" class="transition-all duration-200">Profile Settings</span>
                </a>
            </div>
            <!-- Sidebar Footer (hide when collapsed) -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700" x-show="!sidebarCollapsed">
                <div class="px-3 py-2">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center text-white font-medium">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                {{ Auth::user()->name }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                Publisher Account
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div :class="sidebarCollapsed ? 'lg:pl-20' : 'lg:pl-64'" class="flex flex-col min-h-screen">
        <!-- Top Header -->
        <div class="sticky top-0 z-40 bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between px-6 h-16">
                <!-- Mobile menu button -->
                <button @click="mobileSidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <!-- Breadcrumb/Page Title (centered like agent) -->
                <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                    <i class="fas fa-home"></i>
                    <span>/</span>
                    <span class="text-gray-900 dark:text-white font-medium">@yield('title', 'Publisher Dashboard')</span>
                </div>
                <!-- Header Actions (right side) -->
                <div class="flex items-center space-x-4">
                    <!-- Quick Stats -->
                    <div class="hidden lg:flex items-center space-x-6 text-sm">
                        <div class="text-center">
                            <div class="font-medium text-gray-900 dark:text-white">
                                {{ (Auth::user()->publisherProfile && method_exists(Auth::user()->publisherProfile, 'getTotalCallsToday')) ? Auth::user()->publisherProfile->getTotalCallsToday() : 0 }}
                            </div>
                            <div class="text-gray-500 dark:text-gray-400">Calls Today</div>
                        </div>
                        <div class="text-center">
                            <div class="font-medium text-gray-900 dark:text-white">
                                ${{ number_format((Auth::user()->publisherProfile && method_exists(Auth::user()->publisherProfile, 'getEarningsThisMonth')) ? Auth::user()->publisherProfile->getEarningsThisMonth() : 0, 2) }}
                            </div>
                            <div class="text-gray-500 dark:text-gray-400">This Month</div>
                        </div>
                    </div>
                    
                    <!-- Notifications -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="relative p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-green-400 notification-dot"></span>
                        </button>
                        <div x-show="open" 
                             x-transition
                             @click.away="open = false"
                             class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white">Notifications</h3>
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                <div class="p-4 text-sm text-gray-500 dark:text-gray-400 text-center">
                                    No new notifications
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- User Menu -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center text-white font-medium">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="hidden md:block text-sm font-medium text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                        </button>
                        <div x-show="open" 
                             x-transition
                             @click.away="open = false"
                             class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-user mr-2"></i>Profile
                                </a>
                                <!-- Neumorphic Dark Mode Toggle -->
                                <div class="px-4 py-2 flex items-center justify-between">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Dark Mode</span>
                                    <button @click="darkMode = !darkMode" class="relative w-12 h-6 focus:outline-none" :aria-pressed="darkMode">
                                        <span class="absolute inset-0 rounded-full transition bg-gray-200 dark:bg-gray-700 shadow-inner"></span>
                                        <span class="absolute left-0 top-0 w-6 h-6 rounded-full bg-white dark:bg-gray-900 shadow-md transform transition-transform duration-300"
                                              :class="darkMode ? 'translate-x-6' : 'translate-x-0'">
                                            <svg x-show="!darkMode" class="w-4 h-4 m-1 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 3v1m0 16v1m8.66-8.66l-.71.71M4.05 19.07l-.71.71M21 12h-1M4 12H3m16.95 7.07l-.71-.71M6.34 6.34l-.71-.71"/></svg>
                                            <svg x-show="darkMode" class="w-4 h-4 m-1 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z"/></svg>
                                        </span>
                                    </button>
                                </div>
                                <hr class="my-1 border-gray-200 dark:border-gray-700">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page Content -->
        <main class="flex-1 bg-gray-50 dark:bg-gray-900">
            <div class="p-4 sm:p-6 lg:p-8">
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 text-green-700 dark:text-green-300 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-400 text-red-700 dark:text-red-300 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
            <div class="px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0">
                    <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                        <span>&copy; {{ date('Y') }} CRM Pro. All rights reserved.</span>
                        <span class="hidden sm:inline">•</span>
                        <span class="hidden sm:inline">Publisher Portal</span>
                    </div>
                    <div class="flex items-center space-x-4 text-sm">
                        <span class="flex items-center text-green-600 dark:text-green-400">
                            <i class="fas fa-circle text-xs mr-2"></i>
                            Active
                        </span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
