<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ 
    darkMode: localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches),
    sidebarOpen: false,
    mobileSidebarOpen: false,
    sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' || false
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
    $watch('sidebarCollapsed', val => {
        localStorage.setItem('sidebarCollapsed', val);
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
    <title>@yield('title', 'Agent Dashboard') - {{ config('app.name', 'CRM System') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Vite compiled CSS -->
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
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        }
        
        .dark .sidebar-gradient {
            background: linear-gradient(135deg, #065f46 0%, #047857 100%);
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
         :class="[sidebarCollapsed ? 'w-20' : 'w-64', mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full', 'lg:translate-x-0']">
        
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between h-16 px-6 sidebar-gradient">
            <div class="flex items-center gap-2 w-full">
                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center transition-all duration-300" 
                     x-show="!sidebarCollapsed" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95">
                    <i class="fas fa-user-tie text-green-600 text-lg"></i>
                </div>
                <h1 class="text-xl font-bold text-white whitespace-nowrap transition-all duration-300" 
                    x-show="!sidebarCollapsed"
                    x-transition:enter="transition ease-out duration-300 delay-100"
                    x-transition:enter-start="opacity-0 translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 translate-x-4">Agent Portal</h1>
                <!-- Sidebar Collapse Toggle (desktop only, burger style, aligned) -->
                <button @click="sidebarCollapsed = !sidebarCollapsed" class="ml-auto text-white focus:outline-none hidden lg:flex flex-col items-center justify-center w-8 h-8 relative hover:bg-white/10 rounded-lg transition-all duration-200" 
                        :title="sidebarCollapsed ? 'Expand Sidebar' : 'Collapse Sidebar'">
                    <span class="block w-4 h-0.5 bg-white mb-1 transition-all duration-300" :class="sidebarCollapsed ? 'rotate-45 translate-y-1.5' : ''"></span>
                    <span class="block w-4 h-0.5 bg-white mb-1 transition-all duration-300" :class="sidebarCollapsed ? 'opacity-0' : ''"></span>
                    <span class="block w-4 h-0.5 bg-white transition-all duration-300" :class="sidebarCollapsed ? '-rotate-45 -translate-y-1.5' : ''"></span>
                </button>
            </div>
            <button @click="mobileSidebarOpen = false" class="lg:hidden text-white hover:text-gray-200 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <!-- Sidebar Navigation -->
        <nav class="mt-6 scrollbar-thin overflow-y-auto h-full pb-20 transition-all duration-200"
            :class="sidebarCollapsed ? 'w-20 px-2' : 'w-64 px-3'">
            <div class="space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('agent.dashboard') }}"
                   class="flex items-center py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('agent.dashboard') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                   :class="sidebarCollapsed ? 'justify-center px-2' : 'px-3'"
                   :title="sidebarCollapsed ? 'Dashboard' : ''">
                    <i class="fas fa-tachometer-alt text-lg w-5 flex-shrink-0 text-blue-600 dark:text-blue-400 transition-all duration-200" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                    <span x-show="!sidebarCollapsed" 
                          x-transition:enter="transition ease-out duration-300 delay-100"
                          x-transition:enter-start="opacity-0 translate-x-4"
                          x-transition:enter-end="opacity-100 translate-x-0"
                          x-transition:leave="transition ease-in duration-200"
                          x-transition:leave-start="opacity-100 translate-x-0"
                          x-transition:leave-end="opacity-0 translate-x-4"
                          class="transition-all duration-200">Dashboard</span>
                </a>
                
                <!-- Attendance -->
                <a href="{{ route('agent.attendance') }}"
                   class="flex items-center py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('agent.attendance.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                   :class="sidebarCollapsed ? 'justify-center px-2' : 'px-3'"
                   :title="sidebarCollapsed ? 'Attendance' : ''">
                    <i class="fas fa-clock text-lg w-5 flex-shrink-0 text-orange-600 dark:text-orange-400 transition-all duration-200" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                    <span x-show="!sidebarCollapsed" 
                          x-transition:enter="transition ease-out duration-300 delay-100"
                          x-transition:enter-start="opacity-0 translate-x-4"
                          x-transition:enter-end="opacity-100 translate-x-0"
                          x-transition:leave="transition ease-in duration-200"
                          x-transition:leave-start="opacity-100 translate-x-0"
                          x-transition:leave-end="opacity-0 translate-x-4"
                          class="transition-all duration-200">Attendance</span>
                </a>
                
                <!-- My Leads -->
                <div x-data="{ open: {{ request()->routeIs('agent.leads.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" type="button"
                            class="w-full flex items-center py-3 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                            :class="sidebarCollapsed ? 'justify-center px-2' : 'px-3'"
                            :title="sidebarCollapsed ? 'My Leads' : ''">
                        <i class="fas fa-clipboard-list text-lg w-5 flex-shrink-0 text-emerald-600 dark:text-emerald-400 transition-all duration-200" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                        <span x-show="!sidebarCollapsed" 
                              x-transition:enter="transition ease-out duration-300 delay-100"
                              x-transition:enter-start="opacity-0 translate-x-4"
                              x-transition:enter-end="opacity-100 translate-x-0"
                              x-transition:leave="transition ease-in duration-200"
                              x-transition:leave-start="opacity-100 translate-x-0"
                              x-transition:leave-end="opacity-0 translate-x-4"
                              class="transition-all duration-200 flex-1 text-left">My Leads</span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open, 'hidden': sidebarCollapsed }" x-show="!sidebarCollapsed"></i>
                    </button>
                    <div x-show="open && !sidebarCollapsed" x-transition class="mt-2 space-y-1 ml-6">
                        <a href="{{ route('agent.leads.add') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                            <i class="fas fa-plus w-4 mr-2 text-green-500"></i>Add New Lead
                        </a>
                        <a href="{{ route('agent.leads.view') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                            <i class="fas fa-eye w-4 mr-2 text-cyan-500"></i>View My Leads
                        </a>
                    </div>
                </div>
                <!-- Performance -->
                <a href="#" class="flex items-center py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                   :class="sidebarCollapsed ? 'justify-center px-2' : 'px-3'"
                   :title="sidebarCollapsed ? 'Performance' : ''">
                    <i class="fas fa-chart-bar text-lg w-5 flex-shrink-0 text-purple-600 dark:text-purple-400 transition-all duration-200" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                    <span x-show="!sidebarCollapsed" 
                          x-transition:enter="transition ease-out duration-300 delay-100"
                          x-transition:enter-start="opacity-0 translate-x-4"
                          x-transition:enter-end="opacity-100 translate-x-0"
                          x-transition:leave="transition ease-in duration-200"
                          x-transition:leave-start="opacity-100 translate-x-0"
                          x-transition:leave-end="opacity-0 translate-x-4"
                          class="flex-1">Performance</span>
                    <span x-show="!sidebarCollapsed" 
                          x-transition:enter="transition ease-out duration-300 delay-150"
                          x-transition:enter-start="opacity-0 scale-95"
                          x-transition:enter-end="opacity-100 scale-100"
                          x-transition:leave="transition ease-in duration-200"
                          x-transition:leave-start="opacity-100 scale-100"
                          x-transition:leave-end="opacity-0 scale-95"
                          class="bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200 text-xs px-2 py-1 rounded-full">Soon</span>
                </a>
                <!-- Revenue -->
                <a href="#" class="flex items-center py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                   :class="sidebarCollapsed ? 'justify-center px-2' : 'px-3'"
                   :title="sidebarCollapsed ? 'Revenue' : ''">
                    <i class="fas fa-dollar-sign text-lg w-5 flex-shrink-0 text-yellow-600 dark:text-yellow-400 transition-all duration-200" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                    <span x-show="!sidebarCollapsed"
                          x-transition:enter="transition ease-out duration-300 delay-100"
                          x-transition:enter-start="opacity-0 translate-x-4"
                          x-transition:enter-end="opacity-100 translate-x-0"
                          x-transition:leave="transition ease-in duration-200"
                          x-transition:leave-start="opacity-100 translate-x-0"
                          x-transition:leave-end="opacity-0 translate-x-4">Revenue</span>
                </a>
                <!-- Verification -->
                <a href="#" class="flex items-center py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                   :class="sidebarCollapsed ? 'justify-center px-2' : 'px-3'"
                   :title="sidebarCollapsed ? 'Verification' : ''">
                    <i class="fas fa-graduation-cap text-lg w-5 flex-shrink-0 text-indigo-600 dark:text-indigo-400 transition-all duration-200" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                    <span x-show="!sidebarCollapsed"
                          x-transition:enter="transition ease-out duration-300 delay-100"
                          x-transition:enter-start="opacity-0 translate-x-4"
                          x-transition:enter-end="opacity-100 translate-x-0"
                          x-transition:leave="transition ease-in duration-200"
                          x-transition:leave-start="opacity-100 translate-x-0"
                          x-transition:leave-end="opacity-0 translate-x-4">Verification</span>
                </a>
                <!-- Chat -->
                <a href="#" class="flex items-center py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                   :class="sidebarCollapsed ? 'justify-center px-2' : 'px-3'"
                   :title="sidebarCollapsed ? 'Chat' : ''">
                    <i class="fas fa-headset text-lg w-5 flex-shrink-0 text-pink-600 dark:text-pink-400 transition-all duration-200" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                    <span x-show="!sidebarCollapsed"
                          x-transition:enter="transition ease-out duration-300 delay-100"
                          x-transition:enter-start="opacity-0 translate-x-4"
                          x-transition:enter-end="opacity-100 translate-x-0"
                          x-transition:leave="transition ease-in duration-200"
                          x-transition:leave-start="opacity-100 translate-x-0"
                          x-transition:leave-end="opacity-0 translate-x-4">Chat</span>
                </a>
            </div>
            <!-- Sidebar Footer (hide when collapsed) -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 transition-all duration-300" 
                 x-show="!sidebarCollapsed"
                 x-transition:enter="transition ease-out duration-300 delay-200"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-4">
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
                                    https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=10b981&color=fff
                                @endif
                            " alt="{{ auth()->user()->name }}">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                Agent
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Attendance Widget -->
                <div class="px-3 py-2">
                    @include('components.attendance-widget')
                </div>
                
                <!-- Quick Actions -->
                <div class="px-3 py-2 space-y-2">
                    <a href="{{ route('agent.leads.add') }}" class="flex items-center w-full px-3 py-2 text-sm text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md transform hover:scale-105">
                        <i class="fas fa-plus mr-2"></i>
                        Quick Add Lead
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="transition-all duration-300 ease-in-out" :class="sidebarCollapsed ? 'lg:pl-20' : 'lg:pl-64'">
        <!-- Top Navigation -->
        <div class="sticky top-0 z-40 bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 transition-all duration-300">
            <div class="flex items-center justify-between px-6 h-16">
                <!-- Mobile menu button -->
                <button @click="mobileSidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                    <i class="fas fa-bars text-xl"></i>
                </button>

                <!-- Breadcrumb -->
                <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                    <i class="fas fa-home text-blue-500"></i>
                    <span>/</span>
                    <span class="text-gray-900 dark:text-white font-medium">@yield('title', 'Agent Dashboard')</span>
                </div>

                <!-- Header Actions (right side) -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="relative p-2 text-gray-500 hover:text-yellow-600 dark:text-gray-400 dark:hover:text-yellow-400 transition-colors">
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
                                    https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=10b981&color=fff
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
                                    <i class="fas fa-user mr-2 text-blue-500"></i>Profile
                                </a>
                                <!-- Neumorphic Dark Mode Toggle -->
                                <div class="px-4 py-2 flex items-center justify-between">
                                    <span class="text-sm text-gray-700 dark:text-gray-300 flex items-center">
                                        <i class="fas fa-moon mr-2 text-indigo-500"></i>Dark Mode
                                    </span>
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
                                        <i class="fas fa-sign-out-alt mr-2 text-red-500"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <main class="flex-1">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
