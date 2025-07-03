<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Acraltech Solutions')</title>
    <meta name="description" content="The #1 lead generation platform for insurance, finance, and home services. Join 1,000+ agents generating verified leads.">
    <meta name="keywords" content="lead generation, performance marketing, insurance leads, financial leads, digital marketing, pay-per-call, conversion optimization">
    <meta name="author" content="Acraltech Solutions">
    <meta property="og:title" content="Acraltech Solutions - Premium Lead Generation Platform">
    <meta property="og:description" content="Join the #1 lead generation network. Verified leads, real-time tracking, performance-based pricing. Trusted by 1,000+ partners worldwide.">
    <meta property="og:image" content="{{ asset('logo.png') }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="icon" type="image/png" href="/favicon.ico">
    <link rel="canonical" href="{{ url('/') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        * { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Space Grotesk', sans-serif; }
        
        /* Sellora-inspired design variables */
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #10b981;
            --accent: #f59e0b;
            --dark: #0f172a;
            --light: #f8fafc;
            --purple: #8b5cf6;
            --purple-light: #a78bfa;
        }
        
        .gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .gradient-modern { background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%); }
        .text-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        
        /* Sellora-style animations - Enhanced */
        .fade-in { animation: fadeIn 0.8s ease-out; }
        .slide-in-right { animation: slideInRight 0.8s ease-out; }
        .slide-in-left { animation: slideInLeft 0.8s ease-out; }
        .float { animation: float 3s ease-in-out infinite; }
        
        @keyframes fadeIn { 
            0% { opacity: 0; transform: translateY(30px); } 
            100% { opacity: 1; transform: translateY(0); } 
        }
        @keyframes slideInRight { 
            0% { opacity: 0; transform: translateX(30px); } 
            100% { opacity: 1; transform: translateX(0); } 
        }
        @keyframes slideInLeft { 
            0% { opacity: 0; transform: translateX(-30px); } 
            100% { opacity: 1; transform: translateX(0); } 
        }
        @keyframes float { 
            0%, 100% { transform: translateY(0px) scale(1); } 
            50% { transform: translateY(-10px) scale(1.05); } 
        }
        
        /* Enhanced blob animations */
        .blob { 
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; 
            animation: blob-morph 8s ease-in-out infinite;
        }
        .blob-1 { background: linear-gradient(45deg, #667eea33, #764ba233); }
        .blob-2 { background: linear-gradient(45deg, #f093fb33, #764ba233); }
        
        @keyframes blob-morph {
            0%, 100% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
            25% { border-radius: 50% 50% 80% 20% / 25% 75% 25% 75%; }
            50% { border-radius: 80% 20% 20% 80% / 50% 50% 50% 50%; }
            75% { border-radius: 20% 80% 50% 50% / 75% 25% 75% 25%; }
        }
        
        /* Sellora-style blob backgrounds - Enhanced */
        .blob { 
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; 
            animation: blob-morph 8s ease-in-out infinite;
        }
        .blob-1 { background: linear-gradient(45deg, #667eea33, #764ba233); }
        .blob-2 { background: linear-gradient(45deg, #f093fb33, #764ba233); }
        
        @keyframes blob-morph {
            0%, 100% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
            25% { border-radius: 50% 50% 80% 20% / 25% 75% 25% 75%; }
            50% { border-radius: 80% 20% 20% 80% / 50% 50% 50% 50%; }
            75% { border-radius: 20% 80% 50% 50% / 75% 25% 75% 25%; }
        }
        
        /* Modern card styling */
        .modern-card { 
            background: rgba(255, 255, 255, 0.95); 
            backdrop-filter: blur(15px); 
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .dark .modern-card { 
            background: rgba(17, 24, 39, 0.95); 
            border: 1px solid rgba(75, 85, 99, 0.2); 
        }
        
        /* Modern Dark Mode Toggle - Dribbble Inspired */
        .theme-toggle {
            position: relative;
            width: 80px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            cursor: pointer;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .theme-toggle.dark {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            box-shadow: 0 4px 20px rgba(30, 41, 59, 0.4);
        }
        
        .theme-toggle::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 4px;
            width: 32px;
            height: 32px;
            background: white;
            border-radius: 50%;
            transform: translateY(-50%);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 2;
        }
        
        .theme-toggle.dark::before {
            left: 44px;
            background: #1e293b;
        }
        
        .theme-toggle-icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1;
        }
        
        .theme-toggle .sun-icon {
            left: 10px;
            color: #fbbf24;
            opacity: 1;
        }
        
        .theme-toggle .moon-icon {
            right: 10px;
            color: #e2e8f0;
            opacity: 0;
        }
        
        .theme-toggle.dark .sun-icon {
            opacity: 0;
            transform: translateY(-50%) rotate(180deg);
        }
        
        .theme-toggle.dark .moon-icon {
            opacity: 1;
            transform: translateY(-50%) rotate(0deg);
        }
        
        .theme-toggle:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 30px rgba(102, 126, 234, 0.4);
        }
        
        .theme-toggle.dark:hover {
            box-shadow: 0 8px 30px rgba(30, 41, 59, 0.5);
        }
        
        /* Ripple effect for toggle */
        .theme-toggle::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: all 0.3s ease;
            pointer-events: none;
        }
        
        .theme-toggle:active::after {
            width: 100px;
            height: 100px;
        }
    </style>
</head>

<body class="antialiased min-h-screen" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
    <!-- Sellora-inspired Split Screen Layout -->
    <div class="min-h-screen flex">
        <!-- Left Panel - Branding & Illustration -->
        <div class="hidden lg:flex lg:w-1/2 gradient-modern relative overflow-hidden">
            <!-- Floating Blobs - Better positioned for centered content -->
            <div class="absolute top-16 left-16 w-32 h-32 blob blob-1 float opacity-60"></div>
            <div class="absolute bottom-16 right-16 w-24 h-24 blob blob-2 float opacity-40" style="animation-delay: -1s;"></div>
            <div class="absolute top-1/2 left-8 w-16 h-16 blob blob-1 float opacity-30" style="animation-delay: -2s;"></div>
            <div class="absolute top-1/4 right-8 w-20 h-20 blob blob-2 float opacity-25" style="animation-delay: -3s;"></div>
            
            <!-- Content - Perfectly centered -->
            <div class="flex flex-col justify-center items-center text-white p-8 lg:p-12 relative z-10 slide-in-left w-full h-full">
                <!-- Logo - Centered with better spacing -->
                <div class="mb-12 flex justify-center w-full">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            <img src="{{ asset('logo.png') }}" alt="Acraltech Solutions" class="w-10 h-10 rounded-xl" />
                        </div>
                        <div class="flex flex-col">
                            <span class="text-2xl font-bold font-display">Acraltech</span>
                            <span class="text-sm text-white/80">Lead Generation Platform</span>
                        </div>
                    </div>
                </div>
                
                <!-- Illustration Area - Perfectly centered -->
                <div class="mb-12 flex justify-center w-full">
                    <div class="w-80 h-80 rounded-3xl bg-white/10 backdrop-blur-sm flex items-center justify-center relative">
                        <!-- Abstract illustration - Centered -->
                        <div class="relative flex items-center justify-center">
                            <div class="w-48 h-48 rounded-full bg-white/20 flex items-center justify-center">
                                <span class="material-icons text-6xl text-white/80">analytics</span>
                            </div>
                            <!-- Floating elements - Better positioned around center -->
                            <div class="absolute -top-6 -right-6 w-12 h-12 rounded-full bg-yellow-400/80 flex items-center justify-center float">
                                <span class="material-icons text-white text-sm">trending_up</span>
                            </div>
                            <div class="absolute -bottom-6 -left-6 w-10 h-10 rounded-full bg-green-400/80 flex items-center justify-center float" style="animation-delay: -1s;">
                                <span class="material-icons text-white text-xs">check</span>
                            </div>
                            <div class="absolute top-1/2 -left-10 w-8 h-8 rounded-full bg-blue-400/80 flex items-center justify-center float" style="animation-delay: -2s;">
                                <span class="material-icons text-white text-xs">person</span>
                            </div>
                            <div class="absolute -top-2 left-1/2 transform -translate-x-1/2 w-6 h-6 rounded-full bg-purple-400/80 flex items-center justify-center float" style="animation-delay: -3s;">
                                <span class="material-icons text-white text-xs">star</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial/Feature - Centered content -->
                <div class="text-center max-w-md mx-auto px-4">
                    <h2 class="text-2xl font-bold mb-4 font-display">@yield('panel-title', 'Transform Your Business')</h2>
                    <p class="text-white/80 leading-relaxed mb-8">@yield('panel-description', 'Join thousands of businesses generating quality leads with our premium platform. Real-time tracking, verified contacts, and performance-based results.')</p>
                    
                    <!-- Stats - Better centered grid -->
                    <div class="grid grid-cols-3 gap-6 mt-8">
                        <div class="text-center">
                            <div class="text-2xl font-bold mb-1">1K+</div>
                            <div class="text-xs text-white/70">Active Partners</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold mb-1">$50M+</div>
                            <div class="text-xs text-white/70">Generated Revenue</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold mb-1">99%</div>
                            <div class="text-xs text-white/70">Lead Quality</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Panel - Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50 dark:bg-gray-900 relative">
            <!-- Dark Mode Toggle - Modern Dribbble Style -->
            <div class="absolute top-6 right-6">
                <div class="theme-toggle" 
                     :class="{ 'dark': darkMode }"
                     @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                     x-data="{ ripple: false }"
                     @click="ripple = true; setTimeout(() => ripple = false, 300)">
                    <span class="theme-toggle-icon sun-icon material-icons">light_mode</span>
                    <span class="theme-toggle-icon moon-icon material-icons">dark_mode</span>
                </div>
            </div>
            
            <!-- Back to Home (Mobile) -->
            <div class="absolute top-6 left-6 lg:hidden">
                <a href="{{ url('/') }}" class="p-3 rounded-xl bg-white dark:bg-gray-800 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                    <span class="material-icons">arrow_back</span>
                </a>
            </div>
            
            <!-- Form Container -->
            <div class="w-full max-w-md slide-in-right">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Additional Scripts -->
    @stack('scripts')
    
    <script>
        // Dark mode persistence
        document.addEventListener('alpine:init', () => {
            Alpine.store('darkMode', {
                on: localStorage.getItem('darkMode') === 'true',
                toggle() {
                    this.on = !this.on;
                    localStorage.setItem('darkMode', this.on);
                }
            });
        });
    </script>
</body>
</html>
