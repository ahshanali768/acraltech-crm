// Landing Page Interactive Features
document.addEventListener('DOMContentLoaded', function() {
    
    // Counter Animation
    function animateCounters() {
        const counters = document.querySelectorAll('.counter');
        const animationDuration = 2000; // 2 seconds
        
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            const increment = target / (animationDuration / 16); // 60fps
            let current = 0;
            
            const updateCounter = () => {
                if (current < target) {
                    current += increment;
                    if (current > target) current = target;
                    counter.textContent = Math.floor(current);
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = target;
                }
            };
            
            updateCounter();
        });
    }
    
    // Intersection Observer for counter animation
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounters();
                counterObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    
    // Observe the first counter element
    const firstCounter = document.querySelector('.counter');
    if (firstCounter) {
        counterObserver.observe(firstCounter.closest('section'));
    }
    
    // Live Activity Feed Animation
    function updateActivityFeed() {
        const feed = document.getElementById('activity-feed');
        if (!feed) return;
        
        const activities = [
            {
                type: 'Auto Insurance',
                location: 'Dallas, TX',
                detail: 'Premium: $180/month',
                color: 'green',
                icon: 'car'
            },
            {
                type: 'Medicare Consultation',
                location: 'Phoenix, AZ',
                detail: 'Age: 67',
                color: 'blue',
                icon: 'health'
            },
            {
                type: 'Credit Repair Lead',
                location: 'Miami, FL',
                detail: 'Score: 580',
                color: 'purple',
                icon: 'credit'
            },
            {
                type: 'Home Insurance',
                location: 'Seattle, WA',
                detail: 'Value: $450K',
                color: 'green',
                icon: 'home'
            },
            {
                type: 'Life Insurance',
                location: 'Atlanta, GA',
                detail: 'Coverage: $250K',
                color: 'blue',
                icon: 'life'
            },
            {
                type: 'Debt Settlement',
                location: 'Chicago, IL',
                detail: 'Amount: $35K',
                color: 'purple',
                icon: 'debt'
            }
        ];
        
        let activityIndex = 0;
        
        setInterval(() => {
            const activity = activities[activityIndex % activities.length];
            const timeAgo = Math.floor(Math.random() * 30) + 1;
            
            // Create new activity element
            const newActivity = document.createElement('div');
            newActivity.className = `flex items-center justify-between p-4 bg-${activity.color}-50 rounded-xl border border-${activity.color}-200`;
            newActivity.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-${activity.color}-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-${activity.color}-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="font-medium text-gray-900">${activity.type} Lead Verified</div>
                        <div class="text-sm text-gray-600">${activity.location} • ${activity.detail}</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm font-medium text-${activity.color}-600">Delivered</div>
                    <div class="text-xs text-gray-500">${timeAgo}s ago</div>
                </div>
            `;
            
            // Add fade-in animation
            newActivity.style.opacity = '0';
            newActivity.style.transform = 'translateY(20px)';
            
            // Insert at the beginning
            feed.insertBefore(newActivity, feed.firstChild);
            
            // Animate in
            setTimeout(() => {
                newActivity.style.transition = 'all 0.5s ease';
                newActivity.style.opacity = '1';
                newActivity.style.transform = 'translateY(0)';
            }, 100);
            
            // Remove oldest activity if more than 3
            const activities_list = feed.children;
            if (activities_list.length > 3) {
                const oldActivity = activities_list[activities_list.length - 1];
                oldActivity.style.transition = 'all 0.5s ease';
                oldActivity.style.opacity = '0';
                oldActivity.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    if (oldActivity.parentNode) {
                        oldActivity.remove();
                    }
                }, 500);
            }
            
            activityIndex++;
        }, 8000); // Update every 8 seconds
    }
    
    // Start activity feed animation after a delay
    setTimeout(updateActivityFeed, 3000);
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const headerOffset = 80; // Account for fixed header
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Fade in animation for elements with fade-in class
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const fadeObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                fadeObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observe elements with fade-in class
    document.querySelectorAll('.fade-in, .slide-up').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
        fadeObserver.observe(el);
    });
    
    // Testimonial slider auto-advance (if Alpine.js data exists)
    setTimeout(() => {
        const slider = document.querySelector('[x-data*="testimonials"]');
        if (slider && window.Alpine) {
            setInterval(() => {
                const data = Alpine.$data(slider);
                if (data) {
                    data.active = (data.active + 1) % data.testimonials;
                }
            }, 5000); // Change every 5 seconds
        }
    }, 1000);
    
    // Form validation enhancements
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                    
                    // Remove error styling on input
                    field.addEventListener('input', function() {
                        field.classList.remove('border-red-500');
                    }, { once: true });
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                // Scroll to first error field
                const firstError = form.querySelector('.border-red-500');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
            }
        });
    });
    
    // Header background opacity on scroll
    const header = document.querySelector('header');
    if (header) {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const opacity = Math.min(scrolled / 100, 0.95);
            header.style.backgroundColor = `rgba(17, 25, 40, ${opacity})`;
        });
    }
    
    // Add loading states to buttons
    document.querySelectorAll('button[type="submit"], a[href="#contact"]').forEach(btn => {
        btn.addEventListener('click', function() {
            if (this.type === 'submit') {
                const originalText = this.innerHTML;
                this.innerHTML = `
                    <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing...
                `;
                this.disabled = true;
                
                // Reset after 3 seconds (for demo purposes)
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 3000);
            }
        });
    });
    
    // Legacy Real-time metrics for backward compatibility
    class RealTimeMetrics {
        constructor() {
            this.init();
            this.startUpdates();
        }

        async init() {
            await this.updateMetrics();
            await this.updateActivity();
        }

        async updateMetrics() {
            try {
                const response = await fetch('/api/metrics/real-time');
                const data = await response.json();
                
                this.animateCounter('leads-counter', data.leads_count, 2000);
                this.animateCounter('conversion-rate', data.conversion_rate, 1500, '%');
                this.animateCounter('response-time', data.response_time, 1000, 's');
                this.animateCounter('client-satisfaction', data.client_satisfaction, 2500, '%');
            } catch (error) {
                console.error('Failed to update metrics:', error);
                // Fallback to demo data
                this.animateCounter('leads-counter', Math.floor(Math.random() * 5000) + 15000, 2000);
                this.animateCounter('conversion-rate', Math.floor(Math.random() * 10) + 18, 1500, '%');
                this.animateCounter('response-time', Math.floor(Math.random() * 30) + 15, 1000, 's');
                this.animateCounter('client-satisfaction', Math.floor(Math.random() * 8) + 92, 2500, '%');
            }
        }

        animateCounter(elementId, targetValue, duration, suffix = '') {
            const element = document.getElementById(elementId);
            if (!element) return;

            let startValue = 0;
            const increment = targetValue / (duration / 16);
            
            const timer = setInterval(() => {
                startValue += increment;
                if (startValue >= targetValue) {
                    startValue = targetValue;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(startValue) + suffix;
            }, 16);
        }

        startUpdates() {
            // Update metrics every 30 seconds
            setInterval(() => {
                this.updateMetrics();
            }, 30000);
            
            // Update activity every 10 seconds
            setInterval(() => {
                this.updateActivity();
            }, 10000);
        }
    }

    // Initialize metrics if elements exist
    if (document.getElementById('leads-counter')) {
        new RealTimeMetrics();
    }
});

// Utility function to format numbers with commas
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Console branding (optional easter egg)
console.log(
    '%c🚀 Acraltech Solutions - Premium Lead Generation Platform',
    'color: #3B82F6; font-size: 16px; font-weight: bold; padding: 10px;'
);
console.log(
    '%cInterested in our technology? Contact us at info@acraltech.com',
    'color: #10B981; font-size: 12px;'
);

// Real-time metrics management
class RealTimeMetrics {
    constructor() {
        this.startUpdates();
    }

    animateCounter(elementId, targetValue, duration, suffix = '') {
        const element = document.getElementById(elementId);
        if (!element) return;

        const startValue = 0;
        const increment = targetValue / (duration / 16);
        let currentValue = startValue;

        const timer = setInterval(() => {
            currentValue += increment;
            if (currentValue >= targetValue) {
                currentValue = targetValue;
                clearInterval(timer);
            }
            
            element.textContent = Math.floor(currentValue).toLocaleString() + suffix;
        }, 16);
    }

    getColorForType(type) {
        const colors = {
            'approved': 'green',
            'pending': 'blue',
            'rejected': 'red',
            'new': 'purple'
        };
        return colors[type] || 'blue';
    }

    startUpdates() {
        // Update metrics every 30 seconds
        setInterval(() => this.updateMetrics(), 30000);
        
        // Update activity every 15 seconds
        setInterval(() => this.updateActivity(), 15000);
    }    updateMetrics() {
        // Simulate real-time metric updates
        // Metrics updated silently
    }

    updateActivity() {
        // Simulate real-time activity updates
        // Activity updated silently
    }
}

// Enhanced landing page interactivity
document.addEventListener('DOMContentLoaded', function() {
    // Initialize real-time metrics
    new RealTimeMetrics();

    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add intersection observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);

    // Observe elements for animation
    document.querySelectorAll('.fade-in, .slide-up, .scale-hover').forEach(el => {
        observer.observe(el);
    });

    // Enhanced form validation
    const contactForm = document.querySelector('form[action*="contact"]');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                    field.addEventListener('input', function() {
                        if (this.value.trim()) {
                            this.classList.remove('border-red-500');
                        }
                    }, { once: true });
                }
            });

            if (!isValid) {
                e.preventDefault();
                const firstError = this.querySelector('.border-red-500');
                if (firstError) {
                    firstError.focus();
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    }

    // Add loading states to buttons
    document.querySelectorAll('.cta-btn, .btn-primary').forEach(button => {
        button.addEventListener('click', function() {
            if (this.type === 'submit') {
                this.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing...
                `;
                this.disabled = true;
            }
        });
    });

    // Add parallax effect to hero section
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const heroElements = document.querySelectorAll('.hero-parallax');
        
        heroElements.forEach(el => {
            const speed = el.dataset.speed || 0.5;
            el.style.transform = `translateY(${scrolled * speed}px)`;
        });
    });

    // Add typing effect to hero text
    const heroTitle = document.querySelector('.hero-title');
    if (heroTitle) {
        const text = heroTitle.textContent;
        heroTitle.textContent = '';
        let i = 0;
        
        function typeWriter() {
            if (i < text.length) {
                heroTitle.textContent += text.charAt(i);
                i++;
                setTimeout(typeWriter, 50);
            }
        }
        
        setTimeout(typeWriter, 500);
    }
});

// Add CSS animations via JavaScript
const style = document.createElement('style');
style.textContent = `
    .animate-in {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .loading-shimmer {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 2s infinite;
    }
    
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
`;
document.head.appendChild(style);
