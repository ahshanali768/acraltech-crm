// Mobile Navigation Enhancement
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle functionality
    const mobileMenuToggle = document.createElement('button');
    mobileMenuToggle.className = 'mobile-nav-toggle md:hidden';
    mobileMenuToggle.innerHTML = `
        <span></span>
        <span></span>
        <span></span>
    `;
    
    // Mobile menu overlay
    const mobileMenu = document.createElement('div');
    mobileMenu.className = 'mobile-menu';
    mobileMenu.innerHTML = `
        <div class="mobile-menu-content">
            <div class="flex justify-between items-center mb-6">
                <span class="font-bold text-xl">Menu</span>
                <button class="mobile-menu-close text-2xl">&times;</button>
            </div>
            <nav class="space-y-4">
                <a href="#home" class="block py-2 text-lg hover:text-green-600">Home</a>
                <div class="space-y-2">
                    <div class="font-semibold text-gray-800">Services</div>
                    <div class="pl-4 space-y-2">
                        <div class="font-medium text-gray-700">Insurance</div>
                        <div class="pl-4 space-y-1 text-sm">
                            <a href="/services/auto-insurance" class="block py-1 hover:text-green-600">Auto Insurance</a>
                            <a href="/services/home-insurance" class="block py-1 hover:text-green-600">Home Insurance</a>
                            <a href="/services/medicare-insurance" class="block py-1 hover:text-green-600">Medicare</a>
                            <a href="/services/obamacare" class="block py-1 hover:text-green-600">Obamacare</a>
                            <a href="/services/u65-health-insurance" class="block py-1 hover:text-green-600">U65 Health</a>
                            <a href="/services/final-expense" class="block py-1 hover:text-green-600">Final Expense</a>
                            <a href="/services/ssdi" class="block py-1 hover:text-green-600">SSDI</a>
                        </div>
                        <div class="font-medium text-gray-700 pt-2">Financial</div>
                        <div class="pl-4 space-y-1 text-sm">
                            <a href="/services/credit-repair" class="block py-1 hover:text-green-600">Credit Repair</a>
                            <a href="/services/debt-settlement" class="block py-1 hover:text-green-600">Debt Settlement</a>
                            <a href="/services/tax-debt" class="block py-1 hover:text-green-600">Tax Debt Relief</a>
                            <a href="/services/student-loan" class="block py-1 hover:text-green-600">Student Loan Forgiveness</a>
                        </div>
                        <div class="font-medium text-gray-700 pt-2">Other</div>
                        <div class="pl-4 space-y-1 text-sm">
                            <a href="/services/pest-control" class="block py-1 hover:text-green-600">Pest Control</a>
                            <a href="/services/flight-booking" class="block py-1 hover:text-green-600">Flight Booking</a>
                        </div>
                    </div>
                </div>
                <a href="#contact" class="block py-2 text-lg hover:text-green-600">Contact</a>
                <a href="/login" class="block py-2 text-lg text-green-600 font-medium">Partner Login</a>
            </nav>
        </div>
    `;
    
    // Insert mobile elements
    const header = document.querySelector('header .container');
    if (header) {
        header.appendChild(mobileMenuToggle);
        document.body.appendChild(mobileMenu);
    }
    
    // Mobile menu event listeners
    function toggleMobileMenu() {
        mobileMenu.classList.toggle('open');
        mobileMenuToggle.classList.toggle('active');
        document.body.style.overflow = mobileMenu.classList.contains('open') ? 'hidden' : '';
    }
    
    function closeMobileMenu() {
        mobileMenu.classList.remove('open');
        mobileMenuToggle.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    mobileMenuToggle.addEventListener('click', toggleMobileMenu);
    
    const mobileMenuClose = mobileMenu.querySelector('.mobile-menu-close');
    if (mobileMenuClose) {
        mobileMenuClose.addEventListener('click', closeMobileMenu);
    }
    
    // Close menu when clicking overlay
    mobileMenu.addEventListener('click', function(e) {
        if (e.target === mobileMenu) {
            closeMobileMenu();
        }
    });
    
    // Close menu when clicking navigation links
    const mobileNavLinks = mobileMenu.querySelectorAll('a');
    mobileNavLinks.forEach(link => {
        link.addEventListener('click', closeMobileMenu);
    });
    
    // Touch-friendly interactions
    function addTouchFeedback() {
        const buttons = document.querySelectorAll('button, .cta-btn, .service-card');
        buttons.forEach(button => {
            button.classList.add('touch-button');
            
            button.addEventListener('touchstart', function() {
                this.style.transform = 'scale(0.98)';
            }, { passive: true });
            
            button.addEventListener('touchend', function() {
                this.style.transform = '';
            }, { passive: true });
        });
    }
    
    // Responsive image loading
    function optimizeImages() {
        const images = document.querySelectorAll('img');
        images.forEach(img => {
            img.loading = 'lazy';
            img.style.maxWidth = '100%';
            img.style.height = 'auto';
        });
    }
    
    // Mobile form optimizations
    function optimizeForms() {
        const inputs = document.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.classList.add('mobile-input');
            
            // Prevent zoom on iOS
            if (input.type === 'text' || input.type === 'email' || input.type === 'tel') {
                input.style.fontSize = '16px';
            }
        });
    }
    
    // Smooth scroll for mobile
    function enableSmoothScroll() {
        const links = document.querySelectorAll('a[href^="#"]');
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const headerHeight = document.querySelector('header').offsetHeight;
                    const targetPosition = target.offsetTop - headerHeight - 20;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }
    
    // Mobile viewport height fix
    function fixViewportHeight() {
        const setVH = () => {
            const vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty('--vh', `${vh}px`);
        };
        
        setVH();
        window.addEventListener('resize', setVH);
        window.addEventListener('orientationchange', setVH);
    }
    
    // Progressive enhancement
    function progressiveEnhancement() {
        // Add mobile-optimized classes
        document.body.classList.add('mobile-optimized');
        
        // Add intersection observer for animations
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('fade-in');
                    }
                });
            }, { threshold: 0.1 });
            
            const animatedElements = document.querySelectorAll('.service-card, .metric-card, .testimonial-card');
            animatedElements.forEach(el => {
                observer.observe(el);
            });
        }
    }
    
    // Performance monitoring
    function addPerformanceMonitoring() {
        // Monitor Core Web Vitals
        if ('web-vitals' in window) {
            window.webVitals.getCLS(console.log);
            window.webVitals.getFID(console.log);
            window.webVitals.getLCP(console.log);
        }
        
        // Add loading states
        window.addEventListener('beforeunload', function() {
            document.body.classList.add('loading');
        });
    }
    
    // Initialize all mobile optimizations
    addTouchFeedback();
    optimizeImages();
    optimizeForms();
    enableSmoothScroll();
    fixViewportHeight();
    progressiveEnhancement();
    addPerformanceMonitoring();
    
    // Resize handler for responsive adjustments
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            // Recalculate layouts on orientation change
            if (window.innerHeight !== window.outerHeight) {
                document.body.style.minHeight = `${window.innerHeight}px`;
            }
        }, 250);
    });
    
    console.log('Mobile optimizations loaded successfully');
});
