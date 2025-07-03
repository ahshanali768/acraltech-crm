<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Acraltech Solutions'); ?></title>
    <meta name="description" content="The #1 lead generation platform for insurance, finance, and home services. Join 1,000+ agents generating verified leads.">
    <meta name="keywords" content="lead generation, performance marketing, insurance leads, financial leads, digital marketing, pay-per-call, conversion optimization">
    <meta name="author" content="Acraltech Solutions">
    <meta property="og:title" content="Acraltech Solutions - Premium Lead Generation Platform">
    <meta property="og:description" content="Join the #1 lead generation network. Verified leads, real-time tracking, performance-based pricing. Trusted by 1,000+ partners worldwide.">
    <meta property="og:image" content="<?php echo e(asset('logo.png')); ?>">
    <meta property="og:url" content="<?php echo e(url('/')); ?>">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="icon" type="image/png" href="/favicon.ico">
    <link rel="canonical" href="<?php echo e(url('/')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
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
            0% { opacity: 0; transform: translateX(50px); } 
            100% { opacity: 1; transform: translateX(0); } 
        }
        
        @keyframes slideInLeft { 
            0% { opacity: 0; transform: translateX(-50px); } 
            100% { opacity: 1; transform: translateX(0); } 
        }
        
        @keyframes float { 
            0%, 100% { transform: translateY(0px); } 
            50% { transform: translateY(-10px); } 
        }
        
        /* Enhanced Glass Effect - Sellora Style */
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
        }
        
        /* Modern blob shapes */
        .blob {
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
        }
        
        .blob-1 { background: linear-gradient(45deg, rgba(255, 255, 255, 0.25), rgba(132, 90, 223, 0.3)); }
        .blob-2 { background: linear-gradient(45deg, rgba(255, 182, 193, 0.3), rgba(255, 255, 255, 0.2)); }
        
        /* Enhanced card styling - Sellora inspired */
        .modern-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
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
        
        .sun-icon {
            left: 8px;
            color: #fbbf24;
            opacity: 1;
        }
        
        .moon-icon {
            right: 8px;
            color: #64748b;
            opacity: 0;
        }
        
        .theme-toggle.dark .sun-icon {
            opacity: 0;
            transform: translateY(-50%) rotate(180deg);
        }
        
        .theme-toggle.dark .moon-icon {
            opacity: 1;
            color: #e2e8f0;
            transform: translateY(-50%) rotate(0deg);
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
            transition: width 0.3s, height 0.3s;
            pointer-events: none;
        }
        
        .theme-toggle:active::after {
            width: 100px;
            height: 100px;
        }
    </style>
</head>

<body class="antialiased min-h-screen bg-gray-50 dark:bg-gray-900" 
      x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" 
      :class="{ 'dark': darkMode }"
      x-init="
        if (darkMode) document.documentElement.classList.add('dark');
        $watch('darkMode', value => {
          if (value) {
            document.documentElement.classList.add('dark');
          } else {
            document.documentElement.classList.remove('dark');
          }
          localStorage.setItem('darkMode', value);
        })
      ">
    <!-- Full Width Layout without Left Animation Panel -->
    <div class="min-h-screen">
        <!-- Header with Logo and Dark Mode Toggle -->
        <header class="relative bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <!-- Logo -->
                    <a href="<?php echo e(route('welcome')); ?>" class="flex items-center space-x-4 hover:opacity-80 transition-opacity">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 flex items-center justify-center">
                            <img src="<?php echo e(asset('logo.png')); ?>" alt="Acraltech Solutions" class="w-8 h-8 rounded-lg" />
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl font-bold font-display text-gray-900 dark:text-white">Acraltech</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Lead Generation Platform</span>
                        </div>
                    </a>
                    
                    <!-- Dark Mode Toggle -->
                    <div class="theme-toggle" 
                         :class="{ 'dark': darkMode }"
                         @click="darkMode = !darkMode">
                        <span class="theme-toggle-icon sun-icon material-icons">light_mode</span>
                        <span class="theme-toggle-icon moon-icon material-icons">dark_mode</span>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Main Content Area - Full Width -->
        <main class="flex-1 py-8 sm:py-12">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="fade-in">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>
        </main>
        
        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center text-sm text-gray-500 dark:text-gray-400">
                    © <?php echo e(date('Y')); ?> Acraltech Solutions. All rights reserved.
                </div>
            </div>
        </footer>
    </div>

    <!-- Additional Scripts -->
    <?php echo $__env->yieldPushContent('scripts'); ?>
    
    <script>
        // Initialize dark mode on page load
        document.addEventListener('DOMContentLoaded', function() {
            const isDark = localStorage.getItem('darkMode') === 'true';
            if (isDark) {
                document.documentElement.classList.add('dark');
            }
        });
    </script>
</body>
</html>
<?php /**PATH /home/ahshanali768/project-export/resources/views/layouts/auth-fullwidth.blade.php ENDPATH**/ ?>