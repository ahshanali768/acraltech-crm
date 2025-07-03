<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ 
    darkMode: localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches),
    sidebarOpen: false,
    mobileSidebarOpen: false,
    sidebarCollapsed: false 
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
    if (darkMode) {
        document.documentElement.classList.add('dark');
    }
" 
:class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name', 'CRM System') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .dark .sidebar-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #3730a3 100%);
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
                    <i class="fas fa-user-shield text-blue-600 text-lg"></i>
                </div>
                <h1 class="text-xl font-bold text-white whitespace-nowrap ml-2" x-show="!sidebarCollapsed" style="transition:all .2s">Admin Portal</h1>
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
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                   :class="sidebarCollapsed ? 'justify-center' : 'px-3'">
                    <i class="fas fa-tachometer-alt text-lg" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                    <span x-show="!sidebarCollapsed" class="transition-all duration-200">Dashboard</span>
                </a>
                <!-- Leads Management -->
                <div x-data="{ open: {{ request()->routeIs('admin.view_leads', 'admin.import.*', 'admin.leads.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" 
                            class="w-full flex items-center py-3 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                            :class="sidebarCollapsed ? 'justify-center' : 'px-3'">
                        <i class="fas fa-users text-lg" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                        <span x-show="!sidebarCollapsed" class="transition-all duration-200">Leads Management</span>
                        <i class="fas fa-chevron-down ml-auto transition-transform duration-200" :class="{ 'rotate-180': open, 'hidden': sidebarCollapsed }"></i>
                    </button>
                    <div x-show="open && !sidebarCollapsed" x-transition class="mt-2 space-y-1 ml-6">
                        <a href="{{ route('admin.view_leads') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                            <i class="fas fa-eye mr-2"></i>View All Leads
                        </a>
                        <a href="{{ route('admin.leads.import') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                            <i class="fas fa-upload mr-2"></i>Import Leads
                        </a>
                        <a href="{{ route('admin.leads.export') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                            <i class="fas fa-download mr-2"></i>Export Leads
                        </a>
                    </div>
                </div>
                <!-- Payments -->
                <a href="{{ route('admin.payment') }}" 
                   class="flex items-center py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.payment') ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                   :class="sidebarCollapsed ? 'justify-center' : 'px-3'">
                    <i class="fas fa-credit-card text-lg" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                    <span x-show="!sidebarCollapsed">Payments</span>
                </a>
                <!-- Pay Per Call -->
                <a href="{{ route('admin.pay-per-call.dashboard') }}" 
                   class="flex items-center py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.pay-per-call.*', 'admin.dids.*', 'admin.call-analytics.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                   :class="sidebarCollapsed ? 'justify-center' : 'px-3'">
                    <i class="fas fa-phone text-lg" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                    <span x-show="!sidebarCollapsed">Pay Per Call</span>
                </a>
                <!-- Attendance -->
                <a href="{{ route('admin.attendance.index') }}" 
                   class="flex items-center py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.attendance.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                   :class="sidebarCollapsed ? 'justify-center' : 'px-3'">
                    <i class="fas fa-clock text-lg" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                    <span x-show="!sidebarCollapsed">Attendance</span>
                </a>
                <!-- Revenue -->
                <a href="#" class="flex items-center py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                   :class="sidebarCollapsed ? 'justify-center' : 'px-3'">
                    <i class="fas fa-chart-line text-lg" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                    <span x-show="!sidebarCollapsed">Revenue</span>
                </a>
                <!-- Settings -->
                <a href="{{ route('admin.settings') }}" 
                   class="flex items-center py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.settings') ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                   :class="sidebarCollapsed ? 'justify-center' : 'px-3'">
                    <i class="fas fa-cog text-lg" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                    <span x-show="!sidebarCollapsed">Settings</span>
                </a>
            </div>
            <!-- Sidebar Footer (hide when collapsed) -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700" x-show="!sidebarCollapsed">
                <div class="px-3 py-2">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <img class="w-8 h-8 rounded-full" src="
                                @if(auth()->user()->profile_picture)
                                    {{ asset('storage/' . auth()->user()->profile_picture) . '?v=' . time() }}
                                @elseif(auth()->user()->avatar_style === 'multiavatar' && auth()->user()->avatar_seed)
                                    {{ url('/multiavatar/' . auth()->user()->avatar_seed . '.svg') . '?v=' . time() }}
                                @elseif(auth()->user()->avatar_style && auth()->user()->avatar_seed)
                                    https://api.dicebear.com/7.x/{{ auth()->user()->avatar_style }}/svg?seed={{ auth()->user()->avatar_seed }}&r={{ uniqid() }}
                                @else
                                    https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=3b82f6&color=fff
                                @endif
                            " alt="{{ auth()->user()->name }}">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                {{ ucfirst(auth()->user()->role) }}
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
                    <span class="text-gray-900 dark:text-white font-medium">@yield('title', 'Admin Dashboard')</span>
                </div>
                <!-- Header Actions (right side) -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="relative p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400 notification-dot"></span>
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
                            <img class="w-8 h-8 rounded-full" src="
                                @if(auth()->user()->profile_picture)
                                    {{ asset('storage/' . auth()->user()->profile_picture) . '?v=' . time() }}
                                @elseif(auth()->user()->avatar_style === 'multiavatar' && auth()->user()->avatar_seed)
                                    {{ url('/multiavatar/' . auth()->user()->avatar_seed . '.svg') . '?v=' . time() }}
                                @elseif(auth()->user()->avatar_style && auth()->user()->avatar_seed)
                                    https://api.dicebear.com/7.x/{{ auth()->user()->avatar_style }}/svg?seed={{ auth()->user()->avatar_seed }}&r={{ uniqid() }}
                                @else
                                    https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=3b82f6&color=fff
                                @endif
                            " alt="{{ auth()->user()->name }}">
                            <span class="hidden md:block text-sm font-medium text-gray-700 dark:text-gray-300">{{ auth()->user()->name }}</span>
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
                        <span class="hidden sm:inline">Version 2.0</span>
                    </div>
                    <div class="flex items-center space-x-4 text-sm">
                        <span class="flex items-center text-green-600 dark:text-green-400">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                            System Online
                        </span>
                        <span class="text-gray-500 dark:text-gray-400">
                            Last updated: <span id="last-updated">{{ now()->format('H:i:s') }}</span>
                        </span>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')
    
    <script>
        // Update timestamp every minute
        setInterval(() => {
            document.getElementById('last-updated').textContent = new Date().toLocaleTimeString();
        }, 60000);
        
        // Close mobile sidebar when clicking outside
        document.addEventListener('click', function(event) {
            if (window.innerWidth < 1024) {
                const sidebar = document.querySelector('[x-data*="mobileSidebarOpen"]');
                if (sidebar && !sidebar.contains(event.target)) {
                    Alpine.store('mobileSidebarOpen', false);
                }
            }
        });
    </script>
</body>
</html>
