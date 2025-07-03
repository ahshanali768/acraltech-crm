<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Acraltech Solutions - Premium Lead Generation & Digital Marketing Platform</title>
    <meta name="description" content="The #1 lead generation platform for insurance, finance, and home services. Join 1,000+ agents generating verified leads.">
    <meta name="keywords" content="lead generation, performance marketing, insurance leads, financial leads, digital marketing, pay-per-call, conversion optimization">
    <meta name="author" content="Acraltech Solutions">
    <meta property="og:title" content="Acraltech Solutions - Premium Lead Generation Platform">
    <meta property="og:description" content="Join the #1 lead generation network. Verified leads, real-time tracking, performance-based pricing. Trusted by 1,000+ partners worldwide.">
    <meta property="og:image" content="<?php echo e(asset('logo.png')); ?>">
    <meta property="og:url" content="<?php echo e(url('/')); ?>">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="icon" type="image/png" href="/favicon.ico">    <link rel="canonical" href="<?php echo e(url('/')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        * { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Space Grotesk', sans-serif; }
        
        /* Professional color scheme */
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #10b981;
            --accent: #f59e0b;
            --dark: #0f172a;
            --light: #f8fafc;
        }
        
        /* Modern gradient backgrounds */
        .gradient-primary { background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); }
        .gradient-secondary { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .gradient-accent { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        .gradient-dark { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); }
        .gradient-hero { background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #2563eb 100%); }
        
        /* Text gradients */
        .text-gradient-primary { background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .text-gradient-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        
        /* Button styles */
        .btn-primary { 
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 14px 0 rgba(37, 99, 235, 0.3);
        }
        .btn-primary:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 8px 25px 0 rgba(37, 99, 235, 0.4);
        }
        
        .btn-secondary { 
            background: linear-gradient(135deg, #10b981 0%, #059669 100%); 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.3);
        }
        .btn-secondary:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 8px 25px 0 rgba(16, 185, 129, 0.4);
        }
        
        /* Card effects */
        .card-hover { 
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); 
            border: 1px solid rgba(148, 163, 184, 0.1);
        }
        .card-hover:hover { 
            transform: translateY(-8px); 
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            border-color: rgba(37, 99, 235, 0.2);
        }
        
        /* Animations */
        .fade-in { animation: fadeIn 0.8s ease-out; }
        .slide-up { animation: slideUp 1s ease-out; }
        .float { animation: float 6s ease-in-out infinite; }
        .pulse-glow { animation: pulseGlow 2s infinite; }
        
        @keyframes fadeIn { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(60px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
        @keyframes pulseGlow { 0%, 100% { opacity: 1; } 50% { opacity: 0.8; } }
        
        /* Glassmorphism effect */
        .glass { 
            backdrop-filter: blur(20px) saturate(180%); 
            background-color: rgba(255, 255, 255, 0.1); 
            border: 1px solid rgba(255, 255, 255, 0.2); 
        }
        
        /* Professional stats counter */
        .counter { 
            font-variant-numeric: tabular-nums; 
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
        }        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 3px; }
        
        /* Header scroll effect - Improved color transitions */
        #main-header {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        #main-header.scrolled {
            background: rgba(15, 23, 42, 0.98);
            backdrop-filter: blur(24px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        #main-header .logo-text {
            transition: color 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        #main-header.scrolled .logo-text {
            color: white !important;
        }
        
        #main-header .nav-link {
            transition: color 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        #main-header.scrolled .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
        }
        
        #main-header.scrolled .nav-link:hover {
            color: white !important;
        }
        
        /* Mobile menu color transitions */
        #main-header.scrolled + .mobile-menu {
            background: rgba(15, 23, 42, 0.98);
            border-color: rgba(255, 255, 255, 0.15);
        }
        
        .mobile-menu-link {
            transition: color 0.3s ease;
        }
        
        .mobile-menu.scrolled .mobile-menu-link {
            color: rgba(255, 255, 255, 0.9) !important;
        }
        
        .mobile-menu.scrolled .mobile-menu-link:hover {
            color: white !important;
        }
        
        .mobile-menu.scrolled .border-slate-200 {
            border-color: rgba(255, 255, 255, 0.15) !important;
        }
        
        /* Mobile menu button transitions */
        .mobile-menu-btn {
            transition: color 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        #main-header.scrolled .mobile-menu-btn {
            color: rgba(255, 255, 255, 0.9) !important;
        }
        
        #main-header.scrolled .mobile-menu-btn:hover {
            color: white !important;
        }
        
        /* Mobile optimizations */
        @media (max-width: 768px) {
            .mobile-padding { padding-left: 1rem; padding-right: 1rem; }
            .mobile-text-lg { font-size: 1.125rem; line-height: 1.75rem; }
            .mobile-text-2xl { font-size: 1.5rem; line-height: 2rem; }
            .mobile-text-3xl { font-size: 1.875rem; line-height: 2.25rem; }
            .mobile-space-y-4 > * + * { margin-top: 1rem; }
            .mobile-gap-3 { gap: 0.75rem; }
            .mobile-p-3 { padding: 0.75rem; }
            .mobile-py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
            .mobile-px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
            
            /* Better mobile header spacing */
            #main-header {
                height: 64px; /* Fixed height for better consistency */
            }
            
            .mobile-hero-pt {
                padding-top: 80px; /* Ensure proper spacing on mobile */
            }
            
            /* Safe spacing for sections after fixed header */
            .section-safe-top {
                padding-top: 80px;
            }
        }
        
        /* Tablet optimizations */
        @media (min-width: 769px) and (max-width: 1024px) {
            #main-header {
                height: 72px;
            }
            
            .tablet-hero-pt {
                padding-top: 88px;
            }
            
            .section-safe-top {
                padding-top: 88px;
            }
        }
        
        /* Desktop optimizations */
        @media (min-width: 1025px) {
            .section-safe-top {
                padding-top: 96px;
            }
        }
        
        /* Viewport height fix for mobile */
        .min-h-screen {
            min-height: 100vh;
            min-height: calc(var(--vh, 1vh) * 100);
        }
        
        /* Touch improvements */
        @media (hover: none) and (pointer: coarse) {
            .hover\\:scale-105:hover {
                transform: none;
            }
            
            button, .btn-primary, .btn-secondary, a[class*="btn"] {
                -webkit-tap-highlight-color: transparent;
                touch-action: manipulation;
            }
        }
        
        /* Better text rendering on mobile */
        @media (max-width: 768px) {
            body {
                -webkit-text-size-adjust: 100%;
                text-rendering: optimizeLegibility;
            }
        }
        
        /* Industry-specific styling */
        .industry-card {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            transition: all 0.3s ease;
        }
        .industry-card:hover {
            background: linear-gradient(145deg, #f8fafc 0%, #e2e8f0 100%);
            transform: translateY(-4px);
        }
    </style>
</head>
<body class="bg-white font-sans antialiased text-slate-800">    <!-- Professional Header & Navigation -->    <header class="fixed top-0 w-full z-50 transition-all duration-300 ease-in-out" id="main-header" x-data="{ mobileMenu: false, servicesOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mobile-padding">
            <div class="flex justify-between items-center h-16 md:h-18 lg:h-20">
                <!-- Professional Logo -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl gradient-primary flex items-center justify-center shadow-lg">
                        <img src="<?php echo e(asset('logo.png')); ?>" alt="Acraltech Solutions" class="w-6 h-6 sm:w-8 sm:h-8 rounded-lg" />
                    </div>                    <div class="flex flex-col">
                        <span class="text-xl sm:text-2xl font-display font-bold text-slate-900 logo-text">Acraltech</span>
                        <span class="text-xs sm:text-sm text-blue-600 -mt-1 font-medium logo-text">Lead Generation Network</span>
                    </div>
                </div>                <!-- Desktop Navigation -->
                <nav class="hidden lg:flex items-center space-x-8">
                    <a href="#home" class="nav-link text-slate-700 hover:text-blue-600 font-medium transition-colors duration-200">Home</a>
                    
                    <div class="relative" @mouseenter="servicesOpen = true" @mouseleave="servicesOpen = false">
                        <button class="nav-link text-slate-700 hover:text-blue-600 font-medium transition-colors duration-200 flex items-center space-x-1">
                            <span>Industries</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': servicesOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <!-- Industries Dropdown -->
                        <div x-show="servicesOpen" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute top-full left-1/2 transform -translate-x-1/2 w-96 mt-2 bg-white rounded-2xl border border-slate-200 shadow-xl">
                            <div class="p-6">
                                <div class="grid grid-cols-2 gap-6">
                                    <div>
                                        <h3 class="text-sm font-semibold text-slate-900 mb-3 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                                            </svg>
                                            Insurance
                                        </h3>
                                        <div class="space-y-2">
                                            <a href="#" class="block text-sm text-slate-600 hover:text-blue-600 transition-colors">Auto Insurance</a>
                                            <a href="#" class="block text-sm text-slate-600 hover:text-blue-600 transition-colors">Health & Medicare</a>
                                            <a href="#" class="block text-sm text-slate-600 hover:text-blue-600 transition-colors">Life Insurance</a>
                                            <a href="#" class="block text-sm text-slate-600 hover:text-blue-600 transition-colors">Home & Property</a>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-semibold text-slate-900 mb-3 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zM14 6a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2h6zM4 14a2 2 0 002 2h8a2 2 0 002-2v-2H4v2z"/>
                                            </svg>
                                            Financial
                                        </h3>
                                        <div class="space-y-2">
                                            <a href="#" class="block text-sm text-slate-600 hover:text-blue-600 transition-colors">Loans & Mortgages</a>
                                            <a href="#" class="block text-sm text-slate-600 hover:text-blue-600 transition-colors">Credit Services</a>
                                            <a href="#" class="block text-sm text-slate-600 hover:text-blue-600 transition-colors">Debt Solutions</a>
                                            <a href="#" class="block text-sm text-slate-600 hover:text-blue-600 transition-colors">Investment</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                      <a href="#solutions" class="nav-link text-slate-700 hover:text-blue-600 font-medium transition-colors duration-200">Solutions</a>
                    <a href="#pricing" class="nav-link text-slate-700 hover:text-blue-600 font-medium transition-colors duration-200">Pricing</a>
                    <a href="#contact" class="nav-link text-slate-700 hover:text-blue-600 font-medium transition-colors duration-200">Contact</a>
                </nav>

                <!-- CTA Buttons -->
                <div class="hidden lg:flex items-center space-x-4">
                    <?php if(auth()->guard()->check()): ?>
                        <?php
                            $role = auth()->user()->role;
                            $dashboardRoute = match($role) {
                                'admin' => route('admin.dashboard'),
                                'agent' => route('agent.dashboard'),
                                default => '/dashboard',
                            };
                        ?>
                        <a href="<?php echo e($dashboardRoute); ?>" class="px-4 sm:px-6 py-2 sm:py-3 rounded-xl btn-secondary text-white font-semibold text-sm sm:text-base">Dashboard</a>
                        <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="text-slate-600 hover:text-slate-900 font-medium transition-colors text-sm sm:text-base">Logout</button>
                        </form>
                    <?php else: ?>
                        <a href="/login" class="text-slate-600 hover:text-slate-900 font-medium transition-colors text-sm sm:text-base">Sign In</a>
                        <a href="/register" class="px-4 sm:px-6 py-2 sm:py-3 rounded-xl btn-primary text-white font-semibold text-sm sm:text-base">Get Started Free</a>
                    <?php endif; ?>
                </div>

                <!-- Mobile menu button -->
                <button @click="mobileMenu = !mobileMenu" class="lg:hidden mobile-menu-btn text-slate-700 hover:text-slate-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div x-show="mobileMenu" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="lg:hidden mobile-menu bg-white border-t border-slate-200"
             x-data="{ get isScrolled() { return document.getElementById('main-header').classList.contains('scrolled'); } }"
             :class="{ 'scrolled bg-slate-900 border-white/15': isScrolled }">
            <div class="px-4 sm:px-6 py-4 sm:py-6 space-y-3 sm:space-y-4 mobile-padding">
                <a href="#home" class="mobile-menu-link block text-slate-700 hover:text-blue-600 font-medium transition-colors py-2">Home</a>
                <a href="#solutions" class="mobile-menu-link block text-slate-700 hover:text-blue-600 font-medium transition-colors py-2">Solutions</a>
                <a href="#pricing" class="mobile-menu-link block text-slate-700 hover:text-blue-600 font-medium transition-colors py-2">Pricing</a>
                <a href="#contact" class="mobile-menu-link block text-slate-700 hover:text-blue-600 font-medium transition-colors py-2">Contact</a>
                
                <?php if(auth()->guard()->check()): ?>
                    <div class="pt-4 border-t border-slate-200 space-y-3" :class="{ 'border-white/15': isScrolled }">
                        <a href="<?php echo e($dashboardRoute); ?>" class="block w-full px-4 py-3 rounded-xl btn-secondary text-white font-semibold text-center">Dashboard</a>
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="mobile-menu-link block w-full text-slate-600 hover:text-slate-900 font-medium text-center py-2">Logout</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="pt-4 border-t border-slate-200 space-y-3" :class="{ 'border-white/15': isScrolled }">
                        <a href="/login" class="mobile-menu-link block text-slate-600 hover:text-slate-900 font-medium text-center py-2">Sign In</a>
                        <a href="/register" class="block w-full px-4 py-3 rounded-xl btn-primary text-white font-semibold text-center">Get Started Free</a>
                    </div>
                <?php endif; ?>            </div>
        </div>
    </header>

<!-- Hero Section -->
<section class="relative min-h-screen mobile-hero-pt tablet-hero-pt pt-20 sm:pt-24 md:pt-28 lg:pt-32 xl:pt-36 pb-12 sm:pb-16 bg-gradient-to-br from-slate-900 via-gray-900 to-slate-800 text-white overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.05"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>
    
    <!-- Floating geometric shapes - Hidden on mobile for performance -->
    <div class="hidden sm:block absolute top-20 left-10 w-32 h-32 bg-gradient-to-br from-blue-500/20 to-purple-600/20 rounded-3xl rotate-12 animate-float"></div>
    <div class="hidden sm:block absolute top-60 right-20 w-24 h-24 bg-gradient-to-br from-green-400/20 to-blue-500/20 rounded-2xl -rotate-12 animate-float" style="animation-delay: 2s;"></div>
    <div class="hidden sm:block absolute bottom-40 left-20 w-20 h-20 bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-full animate-float" style="animation-delay: 4s;"></div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 mobile-padding">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-8 lg:gap-12 min-h-[80vh]">
            <!-- Left: Hero Content -->
            <div class="lg:w-1/2 space-y-6 sm:space-y-8 fade-in text-center lg:text-left">
                <!-- Status Badge -->
                <div class="inline-flex items-center gap-2 px-3 sm:px-4 py-2 rounded-full bg-green-500/20 border border-green-400/30 backdrop-blur-sm">
                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                    <span class="text-xs sm:text-sm font-medium text-green-300">Generating 50,000+ verified leads monthly</span>
                </div>

                <!-- Main Headline -->
                <div class="space-y-4 sm:space-y-6">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mobile-text-3xl">
                        Transform Your Business with
                        <span class="text-gradient-accent block mt-2">Premium Lead Generation</span>
                    </h1>
                    
                    <p class="text-lg sm:text-xl text-gray-300 max-w-2xl leading-relaxed mx-auto lg:mx-0 mobile-text-lg">
                        Partner with industry leaders who deliver 
                        <span class="text-white font-semibold">verified, high-intent leads</span> 
                        across insurance, finance, and home services. 
                        <span class="text-green-400">No contracts. No setup fees. Just results.</span>
                    </p>
                </div>

                <!-- Trust Indicators -->
                <div class="flex flex-wrap justify-center lg:justify-start gap-3 sm:gap-4 items-center mobile-gap-3">
                    <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-300">
                        <div class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-green-500 flex items-center justify-center">
                            <svg class="w-2 h-2 sm:w-3 sm:h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                        </div>
                        <span>500+ Active Partners</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-300">
                        <div class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-blue-500 flex items-center justify-center">
                            <svg class="w-2 h-2 sm:w-3 sm:h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                        </div>
                        <span>98.5% Uptime</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-300">
                        <div class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-purple-500 flex items-center justify-center">
                            <svg class="w-2 h-2 sm:w-3 sm:h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                        </div>
                        <span>24/7 Support</span>
                    </div>
                </div>

                <!-- Compliance Badges -->
                <div class="flex flex-wrap justify-center lg:justify-start gap-2 sm:gap-3 mobile-gap-3">
                    <div class="px-2 sm:px-3 py-1 sm:py-2 bg-white/10 rounded-lg border border-white/20 backdrop-blur-sm mobile-p-3">
                        <span class="text-xs font-semibold text-white">GDPR</span>
                    </div>
                    <div class="px-2 sm:px-3 py-1 sm:py-2 bg-white/10 rounded-lg border border-white/20 backdrop-blur-sm mobile-p-3">
                        <span class="text-xs font-semibold text-white">CCPA</span>
                    </div>
                    <div class="px-2 sm:px-3 py-1 sm:py-2 bg-white/10 rounded-lg border border-white/20 backdrop-blur-sm mobile-p-3">
                        <span class="text-xs font-semibold text-white">SOC 2</span>
                    </div>
                    <div class="px-2 sm:px-3 py-1 sm:py-2 bg-white/10 rounded-lg border border-white/20 backdrop-blur-sm mobile-p-3">
                        <span class="text-xs font-semibold text-white">ISO 27001</span>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-4 justify-center lg:justify-start mobile-space-y-4">
                    <a href="#contact" class="btn-primary px-6 sm:px-8 py-3 sm:py-4 rounded-xl font-semibold text-base sm:text-lg flex items-center justify-center gap-2 group w-full sm:w-auto mobile-px-6 mobile-py-3">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 group-hover:rotate-12 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                        </svg>
                        Start Generating Leads
                    </a>
                    <a href="#services" class="px-6 sm:px-8 py-3 sm:py-4 rounded-xl font-semibold text-base sm:text-lg border-2 border-white/30 text-white hover:bg-white/10 transition-all duration-300 text-center w-full sm:w-auto mobile-px-6 mobile-py-3">
                        View Our Services
                    </a>
                </div>

                <!-- Social Proof -->
                <div class="pt-6 sm:pt-8 border-t border-white/20 text-center lg:text-left">
                    <p class="text-xs sm:text-sm text-gray-400 mb-3 sm:mb-4">Trusted by industry leaders:</p>
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 sm:gap-8 opacity-60">
                        <!-- Company logos would go here -->
                        <div class="text-white/40 text-xs sm:text-sm font-medium">Fortune 500 Insurers</div>
                        <div class="text-white/40 text-xs sm:text-sm font-medium">Top Financial Firms</div>
                        <div class="text-white/40 text-xs sm:text-sm font-medium">National Brands</div>
                    </div>
                </div>
            </div>

            <!-- Right: Interactive Dashboard Preview -->
            <div class="lg:w-1/2 slide-up w-full">
                <div class="relative max-w-lg mx-auto">
                    <!-- Main Dashboard Card -->
                    <div class="bg-white/10 backdrop-blur-xl rounded-2xl sm:rounded-3xl border border-white/20 p-4 sm:p-6 lg:p-8 shadow-2xl">
                        <div class="text-center mb-6 sm:mb-8">
                            <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-white mb-2 mobile-text-2xl">Live Performance Dashboard</h3>
                            <p class="text-sm sm:text-base text-gray-300">Real-time metrics from our platform</p>
                        </div>

                        <!-- Live Metrics -->
                        <div class="grid grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
                            <div class="text-center">
                                <div class="text-2xl sm:text-3xl font-bold text-green-400 mb-1 counter" data-target="12847">0</div>
                                <div class="text-xs sm:text-sm text-gray-300">Leads This Month</div>
                                <div class="text-xs text-green-400 mt-1">↑ 18% vs last month</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl sm:text-3xl font-bold text-blue-400 mb-1 counter" data-target="94">0%</div>
                                <div class="text-xs sm:text-sm text-gray-300">Quality Score</div>
                                <div class="text-xs text-blue-400 mt-1">Industry leading</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-purple-400 mb-1 counter" data-target="23">0s</div>
                                <div class="text-sm text-gray-300">Avg Response</div>
                                <div class="text-xs text-purple-400 mt-1">Real-time delivery</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-yellow-400 mb-1 counter" data-target="98">0%</div>
                                <div class="text-sm text-gray-300">Client Satisfaction</div>
                                <div class="text-xs text-yellow-400 mt-1">5-star average</div>
                            </div>
                        </div>

                        <!-- Live Activity Feed -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg border border-white/10">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                    <span class="text-sm text-gray-300">Auto Insurance lead verified</span>
                                </div>
                                <span class="text-xs text-green-400">Live</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg border border-white/10">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></div>
                                    <span class="text-sm text-gray-300">Medicare lead delivered</span>
                                </div>
                                <span class="text-xs text-blue-400">3s ago</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg border border-white/10">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 bg-purple-400 rounded-full animate-pulse"></div>
                                    <span class="text-sm text-gray-300">Credit consultation booked</span>
                                </div>
                                <span class="text-xs text-purple-400">12s ago</span>
                            </div>
                        </div>

                        <!-- CTA in Dashboard -->
                        <div class="mt-8 pt-6 border-t border-white/20 text-center">
                            <a href="#contact" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-blue-500 rounded-xl font-semibold text-white hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                                <span>Get Your Dashboard</span>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Floating Elements -->
                    <div class="absolute -top-4 -right-4 w-16 h-16 bg-gradient-to-br from-green-400 to-blue-500 rounded-2xl rotate-12 opacity-80 animate-float"></div>
                    <div class="absolute -bottom-4 -left-4 w-12 h-12 bg-gradient-to-br from-purple-400 to-pink-500 rounded-xl -rotate-12 opacity-80 animate-float" style="animation-delay: 2s;"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Performance Metrics Section -->
<section class="py-20 bg-gradient-to-br from-white via-blue-50 to-purple-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold mb-6 text-gradient-primary">Proven Performance Metrics</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Live data showcasing the quality and scale of our premium lead generation network</p>
        </div>
        
        <!-- Main Stats Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
            <div class="card-hover bg-white rounded-2xl p-8 text-center shadow-lg border border-gray-100">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-4xl font-bold mb-2 text-green-600 counter" data-target="52847">0</div>
                <div class="text-sm font-medium text-gray-600">Verified Leads This Month</div>
                <div class="text-xs text-green-500 mt-2 flex items-center justify-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z"/>
                    </svg>
                    +18% vs last month
                </div>
            </div>
            
            <div class="card-hover bg-white rounded-2xl p-8 text-center shadow-lg border border-gray-100">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                    </svg>
                </div>
                <div class="text-4xl font-bold mb-2 text-blue-600"><span class="counter" data-target="94">0</span>%</div>
                <div class="text-sm font-medium text-gray-600">Average Conversion Rate</div>
                <div class="text-xs text-blue-500 mt-2">Industry Leading Quality</div>
            </div>
            
            <div class="card-hover bg-white rounded-2xl p-8 text-center shadow-lg border border-gray-100">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                    </svg>
                </div>
                <div class="text-4xl font-bold mb-2 text-purple-600"><span class="counter" data-target="23">0</span>s</div>
                <div class="text-sm font-medium text-gray-600">Average Response Time</div>
                <div class="text-xs text-purple-500 mt-2">Real-time Delivery</div>
            </div>
            
            <div class="card-hover bg-white rounded-2xl p-8 text-center shadow-lg border border-gray-100">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-yellow-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <div class="text-4xl font-bold mb-2 text-orange-600"><span class="counter" data-target="98">0</span>%</div>
                <div class="text-sm font-medium text-gray-600">Client Satisfaction</div>
                <div class="text-xs text-orange-500 mt-2">5-Star Average Rating</div>
            </div>
        </div>

        <!-- Secondary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            <div class="text-center">
                <div class="text-3xl font-bold text-gray-900 mb-2"><span class="counter" data-target="500">0</span>+</div>
                <div class="text-gray-600">Active Business Partners</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-gray-900 mb-2"><span class="counter" data-target="14">0</span></div>
                <div class="text-gray-600">Specialized Verticals</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-gray-900 mb-2">$<span class="counter" data-target="25">0</span>M+</div>
                <div class="text-gray-600">Client Revenue Generated</div>
            </div>
        </div>
        
        <!-- Live Activity Feed -->
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-900 to-gray-800 p-6 text-white">
                    <h3 class="text-xl font-bold mb-2 flex items-center gap-3">
                        <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                        Live Activity Feed
                        <span class="text-sm font-normal text-gray-300 ml-auto">Updated every 3 seconds</span>
                    </h3>
                    <p class="text-gray-300 text-sm">Real-time lead generation activity across our network</p>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4" id="activity-feed">
                        <div class="flex items-center justify-between p-4 bg-green-50 rounded-xl border border-green-200">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">Auto Insurance Lead Verified</div>
                                    <div class="text-sm text-gray-600">Dallas, TX • Premium: $180/month</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium text-green-600">Live</div>
                                <div class="text-xs text-gray-500">2 seconds ago</div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-blue-50 rounded-xl border border-blue-200">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">Medicare Consultation Booked</div>
                                    <div class="text-sm text-gray-600">Phoenix, AZ • Age: 67</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium text-blue-600">Delivered</div>
                                <div class="text-xs text-gray-500">8 seconds ago</div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-purple-50 rounded-xl border border-purple-200">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9z"/>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">Credit Repair Lead Qualified</div>
                                    <div class="text-sm text-gray-600">Miami, FL • Score: 580</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium text-purple-600">Verified</div>
                                <div class="text-xs text-gray-500">15 seconds ago</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Industries Section -->
<section id="industries" class="py-20 bg-gradient-to-br from-gray-50 via-blue-50 to-green-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold mb-6 text-gradient-primary">Industries We Serve</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                We work with businesses across 50+ verticals. Our expert team can create custom lead generation solutions for your specific industry and target audience.
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            <!-- Insurance -->
            <div class="industry-card bg-white rounded-2xl p-8 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-4 text-gray-900">Insurance</h3>
                <p class="text-gray-600 mb-6">Auto, health, life, and property insurance leads with verified intent and immediate need.</p>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        Auto Insurance
                    </li>
                    <li class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        Health & Medicare
                    </li>
                    <li class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        Life Insurance
                    </li>
                    <li class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        Home & Property
                    </li>
                </ul>
            </div>

            <!-- Financial Services -->
            <div class="industry-card bg-white rounded-2xl p-8 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zM14 6a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2h6zM4 14a2 2 0 002 2h8a2 2 0 002-2v-2H4v2z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-4 text-gray-900">Financial Services</h3>
                <p class="text-gray-600 mb-6">Loans, mortgages, credit services, and investment leads from qualified prospects.</p>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        Mortgage & Refinancing
                    </li>
                    <li class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        Personal Loans
                    </li>
                    <li class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        Credit Repair
                    </li>
                    <li class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        Investment Services
                    </li>
                </ul>
            </div>

            <!-- Home Services -->
            <div class="industry-card bg-white rounded-2xl p-8 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L9 5.414V17a1 1 0 002 0V5.414l5.293 5.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-4 text-gray-900">Home Services</h3>
                <p class="text-gray-600 mb-6">Solar, roofing, HVAC, and home improvement leads from homeowners ready to buy.</p>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                        Solar Installation
                    </li>
                    <li class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                        Roofing & Siding
                    </li>
                    <li class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                        HVAC Systems
                    </li>
                    <li class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                        Home Security
                    </li>
                </ul>
            </div>

            <!-- Healthcare -->
            <div class="industry-card bg-white rounded-2xl p-8 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-4 text-gray-900">Healthcare</h3>
                <p class="text-gray-600 mb-6">Medicare, health insurance, and medical service leads from qualified patients.</p>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                        Medicare Advantage
                    </li>
                    <li class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                        Health Insurance
                    </li>
                    <li class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                        Medical Devices
                    </li>
                    <li class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                        Wellness Programs
                    </li>
                </ul>
            </div>

            <!-- Business Services -->
            <div class="industry-card bg-white rounded-2xl p-8 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-4 text-gray-900">Business Services</h3>
                <p class="text-gray-600 mb-6">B2B leads for software, consulting, marketing, and professional services.</p>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-indigo-500 rounded-full"></div>
                        SaaS & Software
                    </li>
                    <li class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-indigo-500 rounded-full"></div>
                        Marketing Services
                    </li>
                    <li class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-indigo-500 rounded-full"></div>
                        Business Consulting
                    </li>
                    <li class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-indigo-500 rounded-full"></div>
                        Legal Services
                    </li>
                </ul>
            </div>

            <!-- Automotive -->
            <div class="industry-card bg-white rounded-2xl p-8 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-4 text-gray-900">Automotive</h3>
                <p class="text-gray-600 mb-6">Auto sales, financing, insurance, and service leads from qualified buyers.</p>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
                        Auto Sales
                    </li>
                    <li class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
                        Auto Financing
                    </li>
                    <li class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
                        Auto Insurance
                    </li>
                    <li class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
                        Service & Repair
                    </li>
                </ul>
            </div>
        </div>

        <div class="text-center">
            <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100 max-w-2xl mx-auto">
                <h3 class="text-2xl font-bold mb-4 text-gray-900">Don't See Your Industry?</h3>
                <p class="text-xl text-gray-600 mb-8">
                    We work with businesses across 50+ verticals. Our expert team can create custom lead generation solutions for your specific industry and target audience.
                </p>
                <a href="#contact" class="inline-flex items-center gap-3 btn-primary px-8 py-4 rounded-xl font-semibold text-lg">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                    </svg>
                    Discuss Your Industry
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Enhanced Services Section -->
<section id="services" class="py-20 bg-gradient-to-br from-gray-50 via-blue-50 to-green-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold mb-6 gradient-text">Our Premium Services</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">14 specialized verticals designed to deliver high-converting leads across insurance, financial services, and home improvement industries</p>
        </div>

        <!-- Service Categories -->
        <div class="grid md:grid-cols-3 gap-8 mb-16">
            <!-- Insurance Services -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-300">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Insurance Leads</h3>
                    <p class="text-blue-100">Premium verified leads across all major insurance verticals</p>
                </div>
                <div class="p-6">
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                            <span class="text-gray-700">Auto Insurance</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                            <span class="text-gray-700">Life Insurance</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                            <span class="text-gray-700">Health & Medicare</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                            <span class="text-gray-700">Home & Property</span>
                        </li>
                    </ul>
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Starting at</span>
                            <span class="font-bold text-blue-600">$45/lead</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Services -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-300">
                <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 text-white">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zM14 6a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2h6zM4 14a2 2 0 002 2h8a2 2 0 002-2v-2H4v2z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Financial Leads</h3>
                    <p class="text-green-100">High-intent prospects for loans, mortgages, and financial services</p>
                </div>
                <div class="p-6">
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span class="text-gray-700">Mortgage & Refinancing</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span class="text-gray-700">Personal Loans</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span class="text-gray-700">Credit Repair</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span class="text-gray-700">Debt Consolidation</span>
                        </li>
                    </ul>
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Starting at</span>
                            <span class="font-bold text-green-600">$85/lead</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Home Services -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-300">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 text-white">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L9 5.414V17a1 1 0 002 0V5.414l5.293 5.293a1 1 0 001.414-1.414l-7-7z"/>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Home Service Leads</h3>
                    <p class="text-purple-100">Ready-to-buy homeowners for solar, roofing, and home improvement</p>
                </div>
                <div class="p-6">
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                            <span class="text-gray-700">Solar Installation</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                            <span class="text-gray-700">Roofing & Siding</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                            <span class="text-gray-700">HVAC Systems</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                            <span class="text-gray-700">Home Security</span>
                        </li>
                    </ul>
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Starting at</span>
                            <span class="font-bold text-purple-600">$125/lead</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Features -->
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 mb-16">
            <h3 class="text-3xl font-bold text-center mb-12 text-gray-900">What Makes Our Leads Premium?</h3>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold mb-2 text-gray-900">Verified Quality</h4>
                    <p class="text-gray-600">Multi-step verification process ensures every lead meets your exact criteria</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold mb-2 text-gray-900">Real-Time Delivery</h4>
                    <p class="text-gray-600">Leads delivered instantly to your CRM or preferred system within seconds</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold mb-2 text-gray-900">Complete Data</h4>
                    <p class="text-gray-600">Full contact information, demographics, and intent data included</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold mb-2 text-gray-900">Exclusive Leads</h4>
                    <p class="text-gray-600">Never sold to competitors - you get first contact advantage</p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="text-center">
            <a href="#contact" class="inline-flex items-center gap-3 btn-primary px-8 py-4 rounded-xl font-semibold text-lg">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                </svg>
                Get Started Today
            </a>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section id="faq" class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold mb-6 text-gradient-primary">Frequently Asked Questions</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Get answers to the most common questions about our lead generation services</p>
        </div>

        <div class="max-w-4xl mx-auto" x-data="{ open: null }">
            <div class="space-y-4">
                <!-- FAQ 1 -->
                <div class="bg-gray-50 rounded-2xl border border-gray-200 overflow-hidden">
                    <button @click="open = open === 1 ? null : 1" class="w-full p-6 text-left flex justify-between items-center hover:bg-gray-100 transition-colors">
                        <span class="text-lg font-semibold text-gray-900">How do you verify lead quality?</span>
                        <svg class="w-5 h-5 transform transition-transform" :class="{ 'rotate-180': open === 1 }" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                        </svg>
                    </button>
                    <div x-show="open === 1" class="px-6 pb-6">
                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-gray-700">We use a multi-step verification process including real-time intent validation, duplicate detection, and compliance checks. Every lead is verified for contact information accuracy and genuine interest before delivery.</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="bg-gray-50 rounded-2xl border border-gray-200 overflow-hidden">
                    <button @click="open = open === 2 ? null : 2" class="w-full p-6 text-left flex justify-between items-center hover:bg-gray-100 transition-colors">
                        <span class="text-lg font-semibold text-gray-900">What's your pricing model?</span>
                        <svg class="w-5 h-5 transform transition-transform" :class="{ 'rotate-180': open === 2 }" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                        </svg>
                    </button>
                    <div x-show="open === 2" class="px-6 pb-6">
                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-gray-700">We offer performance-based pricing with no setup fees or long-term contracts. You only pay for qualified leads that meet your criteria. Pricing varies by industry and lead type, starting from $45 per lead.</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="bg-gray-50 rounded-2xl border border-gray-200 overflow-hidden">
                    <button @click="open = open === 3 ? null : 3" class="w-full p-6 text-left flex justify-between items-center hover:bg-gray-100 transition-colors">
                        <span class="text-lg font-semibold text-gray-900">How quickly are leads delivered?</span>
                        <svg class="w-5 h-5 transform transition-transform" :class="{ 'rotate-180': open === 3 }" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                        </svg>
                    </button>
                    <div x-show="open === 3" class="px-6 pb-6">
                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-gray-700">Leads are delivered in real-time, typically within 30 seconds of generation. We integrate with most CRM systems for instant delivery, or can send via email, SMS, or API webhook.</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="bg-gray-50 rounded-2xl border border-gray-200 overflow-hidden">
                    <button @click="open = open === 4 ? null : 4" class="w-full p-6 text-left flex justify-between items-center hover:bg-gray-100 transition-colors">
                        <span class="text-lg font-semibold text-gray-900">Do you offer lead replacement guarantees?</span>
                        <svg class="w-5 h-5 transform transition-transform" :class="{ 'rotate-180': open === 4 }" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                        </svg>
                    </button>
                    <div x-show="open === 4" class="px-6 pb-6">
                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-gray-700">Yes, we offer lead replacement for invalid contact information or leads that don't meet your specified criteria. Our quality guarantee ensures you get the value you pay for.</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ 5 -->
                <div class="bg-gray-50 rounded-2xl border border-gray-200 overflow-hidden">
                    <button @click="open = open === 5 ? null : 5" class="w-full p-6 text-left flex justify-between items-center hover:bg-gray-100 transition-colors">
                        <span class="text-lg font-semibold text-gray-900">How do you ensure lead compliance?</span>
                        <svg class="w-5 h-5 transform transition-transform" :class="{ 'rotate-180': open === 5 }" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                        </svg>
                    </button>
                    <div x-show="open === 5" class="px-6 pb-6">
                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-gray-700">We use multi-step verification, call tracking, and human review to ensure every lead meets your criteria and is genuinely interested in your services.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-20 bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900 text-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold mb-6">Ready to Start Generating Quality Leads?</h2>
            <p class="text-xl text-gray-300 max-w-3xl mx-auto">Get in touch with our lead generation experts and start growing your business today. No setup fees, no long-term contracts.</p>
        </div>

        <div class="grid lg:grid-cols-2 gap-12 max-w-6xl mx-auto">
            <!-- Contact Form -->
            <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20">
                <h3 class="text-3xl font-bold mb-8 text-white">Get Your Free Consultation</h3>
                <form action="<?php echo e(route('contact.store')); ?>" method="POST" class="space-y-6">
                    <?php echo csrf_field(); ?>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-300 mb-2">First Name</label>
                            <input type="text" id="first_name" name="first_name" required class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20 focus:outline-none transition-all">
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-300 mb-2">Last Name</label>
                            <input type="text" id="last_name" name="last_name" required class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20 focus:outline-none transition-all">
                        </div>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                        <input type="email" id="email" name="email" required class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20 focus:outline-none transition-all">
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">Phone Number</label>
                        <input type="tel" id="phone" name="phone" required class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20 focus:outline-none transition-all">
                    </div>
                    
                    <div>
                        <label for="company" class="block text-sm font-medium text-gray-300 mb-2">Company Name</label>
                        <input type="text" id="company" name="company" class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20 focus:outline-none transition-all">
                    </div>
                      <div>
                        <label for="industry" class="block text-sm font-medium text-gray-300 mb-2">Industry</label>
                        <select id="industry" name="industry" required class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20 focus:outline-none transition-all" style="color: white;">
                            <option value="" style="background-color: #1e293b; color: white;">Select Your Industry</option>
                            <option value="insurance" style="background-color: #1e293b; color: white;">Insurance</option>
                            <option value="financial" style="background-color: #1e293b; color: white;">Financial Services</option>
                            <option value="home-services" style="background-color: #1e293b; color: white;">Home Services</option>
                            <option value="healthcare" style="background-color: #1e293b; color: white;">Healthcare</option>
                            <option value="automotive" style="background-color: #1e293b; color: white;">Automotive</option>
                            <option value="business" style="background-color: #1e293b; color: white;">Business Services</option>
                            <option value="other" style="background-color: #1e293b; color: white;">Other</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-300 mb-2">Message</label>
                        <textarea id="message" name="message" rows="4" class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20 focus:outline-none transition-all resize-none" placeholder="Tell us about your lead generation needs..."></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white font-bold py-4 px-8 rounded-xl hover:from-blue-600 hover:to-purple-700 transform hover:scale-105 transition-all duration-300 flex items-center justify-center gap-3">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                        Send Message
                    </button>
                </form>
            </div>

            <!-- Contact Information -->
            <div class="space-y-8">
                <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20">
                    <h3 class="text-2xl font-bold mb-6 text-white">Contact Information</h3>
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-gray-300 text-sm">Phone</div>
                                <div class="text-white font-semibold">+1 (555) 123-4567</div>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-gray-300 text-sm">Email</div>
                                <div class="text-white font-semibold">info@acraltechsolutions.com</div>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-gray-300 text-sm">Address</div>
                                <div class="text-white font-semibold">123 Business Ave, Suite 100<br>New York, NY 10001</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20">
                    <h3 class="text-2xl font-bold mb-6 text-white">Business Hours</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-300">Monday - Friday</span>
                            <span class="text-white font-semibold">8:00 AM - 8:00 PM EST</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-300">Saturday</span>
                            <span class="text-white font-semibold">9:00 AM - 5:00 PM EST</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-300">Sunday</span>
                            <span class="text-white font-semibold">Closed</span>
                        </div>
                    </div>
                    <div class="mt-6 pt-6 border-t border-white/20">
                        <div class="text-sm text-gray-300">Emergency Support Available 24/7</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- JS for interactivity (counters, slider, animations) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Header scroll effect
    const header = document.getElementById('main-header');
    
    function handleScroll() {
        if (window.scrollY > 100) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }
    
    window.addEventListener('scroll', handleScroll);
    
    // Counter Animation
    function animateCounters() {
        const counters = document.querySelectorAll('.counter');
        const observerOptions = {
            threshold: 0.7,
            rootMargin: '0px 0px -10% 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    const target = parseInt(counter.getAttribute('data-target'));
                    const duration = 2000; // 2 seconds
                    const increment = target / (duration / 16); // 60fps
                    let current = 0;

                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= target) {
                            counter.textContent = target.toLocaleString();
                            clearInterval(timer);
                        } else {
                            counter.textContent = Math.floor(current).toLocaleString();
                        }
                    }, 16);

                    observer.unobserve(counter);
                }
            });
        }, observerOptions);

        counters.forEach(counter => observer.observe(counter));
    }    // Initialize counters
    animateCounters();

    // Live activity feed simulation
    function simulateActivityFeed() {
        const feedContainer = document.getElementById('activity-feed');
        if (!feedContainer) return;

        const activities = [
            { type: 'insurance', location: 'Dallas, TX', detail: 'Premium: $180/month', color: 'green' },
            { type: 'medicare', location: 'Phoenix, AZ', detail: 'Age: 67', color: 'blue' },
            { type: 'credit', location: 'Miami, FL', detail: 'Score: 580', color: 'purple' },
            { type: 'solar', location: 'Los Angeles, CA', detail: 'System: 8.5kW', color: 'orange' },
            { type: 'mortgage', location: 'Atlanta, GA', detail: 'Amount: $285k', color: 'indigo' },
            { type: 'auto', location: 'Chicago, IL', detail: 'Coverage: Full', color: 'teal' }
        ];

        const activityTypes = {
            insurance: { title: 'Auto Insurance Lead Verified', icon: 'insurance' },
            medicare: { title: 'Medicare Consultation Booked', icon: 'health' },
            credit: { title: 'Credit Repair Lead Qualified', icon: 'finance' },
            solar: { title: 'Solar Installation Lead', icon: 'solar' },
            mortgage: { title: 'Mortgage Lead Verified', icon: 'home' },
            auto: { title: 'Auto Quote Requested', icon: 'car' }
        };

        function addNewActivity() {
            const activity = activities[Math.floor(Math.random() * activities.length)];
            const typeData = activityTypes[activity.type];
            const timeAgo = Math.floor(Math.random() * 30) + 1;

            const activityHTML = `
                <div class="flex items-center justify-between p-4 bg-${activity.color}-50 rounded-xl border border-${activity.color}-200 activity-item">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-${activity.color}-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-${activity.color}-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">${typeData.title}</div>
                            <div class="text-sm text-gray-600">${activity.location} • ${activity.detail}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium text-${activity.color}-600">Live</div>
                        <div class="text-xs text-gray-500">${timeAgo} seconds ago</div>
                    </div>
                </div>
            `;

            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = activityHTML;
            const newActivity = tempDiv.firstElementChild;

            feedContainer.insertBefore(newActivity, feedContainer.firstChild);            // Remove old activities (keep only 3)
            const existingActivities = feedContainer.querySelectorAll('.activity-item');
            if (existingActivities.length > 3) {
                existingActivities[existingActivities.length - 1].remove();
            }

            // Animate in
            newActivity.style.opacity = '0';
            newActivity.style.transform = 'translateY(-10px)';
            requestAnimationFrame(() => {
                newActivity.style.transition = 'all 0.3s ease-out';
                newActivity.style.opacity = '1';
                newActivity.style.transform = 'translateY(0)';
            });
        }

        // Add new activity every 5-10 seconds
        setInterval(addNewActivity, Math.random() * 5000 + 5000);
    }

    // Initialize activity feed simulation
    setTimeout(simulateActivityFeed, 2000);

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
           
           
            if (target) {
                const offsetTop = target.offsetTop - 80; // Account for fixed header
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Enhanced form validation
    const contactForm = document.querySelector('form[action*="mailto"]');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                const errorMsg = field.parentNode.querySelector('.error-message');
                if (errorMsg) errorMsg.remove();

                if (!field.value.trim()) {
                    isValid = false;
                    const error = document.createElement('div');
                    error.className = 'error-message text-red-500 text-sm mt-1';
                    error.textContent = 'This field is required';
                    field.parentNode.appendChild(error);
                    field.classList.add('border-red-300');
                } else {
                    field.classList.remove('border-red-300');
                }
            });

            if (!isValid) {
                e.preventDefault();
                const firstError = this.querySelector('.error-message');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }        });    }
});
</script>

<script src="<?php echo e(asset('js/landing-page.js')); ?>"></script>

<?php echo $__env->make('components.floating-chat-guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<!-- Footer -->
<footer class="bg-gradient-to-br from-slate-900 via-gray-900 to-slate-800 text-white py-16 border-t border-slate-800">
    <div class="container mx-auto px-4">
        <!-- Main Footer Content -->
        <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-8 mb-12">
            <!-- Company Info -->
            <div class="lg:col-span-1">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-xl gradient-primary flex items-center justify-center shadow-lg">
                        <img src="<?php echo e(asset('logo.png')); ?>" alt="Acraltech Solutions" class="w-8 h-8 rounded-lg" />
                    </div>
                    <div class="flex flex-col">
                        <span class="text-2xl font-display font-bold text-white">Acraltech</span>
                        <span class="text-sm text-blue-400 -mt-1 font-medium">Lead Generation Network</span>
                    </div>
                </div>
                <p class="text-gray-400 mb-6 leading-relaxed">
                    Connecting businesses with high-quality, verified leads across 14+ industries. 
                    Trusted by 500+ partners generating 50,000+ leads monthly.
                </p>
                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-blue-600 transition-colors" aria-label="LinkedIn">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 0h-14c-2.76 0-5 2.24-5 5v14c0 2.76 2.24 5 5 5h14c2.76 0 5-2.24 5-5v-14c0-2.76-2.24-5-5-5zm-11 19h-3v-9h3v9zm-1.5-10.28c-.97 0-1.75-.79-1.75-1.75s.78-1.75 1.75-1.75 1.75.79 1.75 1.75-.78 1.75-1.75 1.75zm13.5 10.28h-3v-4.5c0-1.08-.02-2.47-1.5-2.47-1.5 0-1.73 1.17-1.73 2.39v4.58h-3v-9h2.89v1.23h.04c.4-.75 1.37-1.54 2.82-1.54 3.01 0 3.57 1.98 3.57 4.56v4.75z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-blue-400 transition-colors" aria-label="Twitter">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557a9.93 9.93 0 01-2.828.775 4.932 4.932 0 002.165-2.724c-.951.564-2.005.974-3.127 1.195a4.92 4.92 0 00-8.384 4.482c-4.086-.205-7.713-2.164-10.141-5.144a4.822 4.822 0 00-.666 2.475c0 1.708.87 3.216 2.188 4.099a4.904 4.904 0 01-2.229-.616c-.054 2.281 1.581 4.415 3.949 4.89a4.936 4.936 0 01-2.224.084c.627 1.956 2.444 3.377 4.6 3.417a9.867 9.867 0 01-6.102 2.104c-.396 0-.787-.023-1.175-.069a13.945 13.945 0 007.548 2.212c9.057 0 14.009-7.513 14.009-14.009 0-.213-.005-.425-.014-.636a10.012 10.012 0 002.457-2.548z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-blue-800 transition-colors" aria-label="Facebook">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.675 0h-21.35c-.733 0-1.325.592-1.325 1.325v21.351c0 .732.592 1.324 1.325 1.324h11.495v-9.294h-3.124v-3.622h3.124v-2.672c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.797.143v3.24l-1.918.001c-1.504 0-1.797.715-1.797 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.322-.592 1.322-1.324v-21.35c0-.733-.592-1.325-1.325-1.325z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Services -->
            <div>
                <h4 class="text-lg font-semibold text-white mb-6">Services</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Insurance Leads</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Financial Leads</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Home Service Leads</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Healthcare Leads</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Business Leads</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Automotive Leads</a></li>
                </ul>
            </div>

            <!-- Industries -->
            <div>
                <h4 class="text-lg font-semibold text-white mb-6">Industries</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Auto Insurance</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Health & Medicare</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Solar Installation</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Mortgage & Loans</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Home Security</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">HVAC & Roofing</a></li>
                </ul>
            </div>

            <!-- Contact & Legal -->
            <div>
                <h4 class="text-lg font-semibold text-white mb-6">Contact & Legal</h4>
                <ul class="space-y-3 mb-6">
                    <li><a href="#contact" class="text-gray-400 hover:text-white transition-colors">Contact Us</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Support Center</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Privacy Policy</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Terms of Service</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Cookie Policy</a></li>
                </ul>
                
                <!-- Contact Info -->
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-gray-400">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                        </svg>
                        <span class="text-sm">+1 (555) 123-4567</span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-400">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                        <span class="text-sm">info@acraltechsolutions.com</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="border-t border-white/10 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-gray-400 text-sm">
                    &copy; <?php echo e(date('Y')); ?> Acraltech Solutions. All rights reserved.
                </div>
                
                <!-- Compliance Badges -->
                <div class="flex gap-3">
                    <div class="px-3 py-1 bg-white/10 rounded-lg border border-white/20">
                        <span class="text-xs font-semibold text-white">GDPR</span>
                    </div>
                    <div class="px-3 py-1 bg-white/10 rounded-lg border border-white/20">
                        <span class="text-xs font-semibold text-white">CCPA</span>
                    </div>
                    <div class="px-3 py-1 bg-white/10 rounded-lg border border-white/20">
                        <span class="text-xs font-semibold text-white">SOC 2</span>
                    </div>
                </div>
                
                <div class="text-gray-400 text-sm">
                    Built with <span class="text-red-400">♥</span> for lead generation success
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Mobile-friendly JavaScript improvements -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Header scroll effect
    const header = document.getElementById('main-header');
    let lastScrollY = window.scrollY;
    
    window.addEventListener('scroll', () => {
        const scrollY = window.scrollY;
        
        if (scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
        
        lastScrollY = scrollY;
    });
    
    // Counter animation with intersection observer for better performance
    const counters = document.querySelectorAll('.counter');
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = parseInt(counter.getAttribute('data-target'));
                animateCounter(counter, target);
                counterObserver.unobserve(counter);
            }
        });
    }, observerOptions);
    
    counters.forEach(counter => {
        counterObserver.observe(counter);
    });
    
    function animateCounter(element, target) {
        let current = 0;
        const increment = target / 60; // 60 frames for smooth animation
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = target + (element.textContent.includes('%') ? '%' : '');
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current) + (element.textContent.includes('%') ? '%' : '');
            }
        }, 16); // ~60fps
    }
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const offsetTop = target.offsetTop - 80; // Account for fixed header
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Improved mobile menu handling
    const mobileMenuButton = document.querySelector('[\\@click="mobileMenu = !mobileMenu"]');
    if (mobileMenuButton) {
        // Prevent scrolling when mobile menu is open
        const body = document.body;
        mobileMenuButton.addEventListener('click', function() {
            setTimeout(() => {
                const mobileMenu = document.querySelector('[x-show="mobileMenu"]');
                if (mobileMenu && mobileMenu.style.display !== 'none') {
                    body.style.overflow = 'hidden';
                } else {
                    body.style.overflow = '';
                }
            }, 50);
        });
    }
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const mobileMenu = document.querySelector('[x-show="mobileMenu"]');
        const mobileMenuButton = document.querySelector('[\\@click="mobileMenu = !mobileMenu"]');
        
        if (mobileMenu && !mobileMenu.contains(event.target) && !mobileMenuButton.contains(event.target)) {
            if (mobileMenu.style.display !== 'none') {
                mobileMenuButton.click(); // Trigger Alpine.js to close
            }
        }
    });
    
    // Optimize images loading for mobile
    if ('loading' in HTMLImageElement.prototype) {
        const images = document.querySelectorAll('img[data-src]');
        images.forEach(img => {
            img.src = img.dataset.src;
            img.removeAttribute('data-src');
        });
    }
    
    // Touch-friendly button feedback
    const buttons = document.querySelectorAll('button, .btn-primary, .btn-secondary, a[class*="btn"]');
    buttons.forEach(button => {
        button.addEventListener('touchstart', function() {
            this.style.transform = 'scale(0.98)';
        });
        
        button.addEventListener('touchend', function() {
            this.style.transform = '';
        });
    });
    
    // Viewport height fix for mobile browsers
    const setVH = () => {
        const vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);
    };
    
    setVH();
    window.addEventListener('resize', setVH);
    window.addEventListener('orientationchange', setVH);
});
</script>

</body>
</html>

<?php /**PATH /home/ahshanali768/project-export/resources/views/welcome.blade.php ENDPATH**/ ?>