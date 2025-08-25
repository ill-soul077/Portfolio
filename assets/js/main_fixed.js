/**
 * Portfolio Website JavaScript
 * GitHub-inspired portfolio with smooth animations and dynamic content
 */

class Portfolio {
    constructor() {
        this.currentTheme = 'light';
        this.mobileMenuOpen = false;
        this.scrollPosition = 0;
        this.isLoading = false;
        
        this.init();
    }

    /**
     * Initialize all functionality
     */
    init() {
        this.initEventListeners();
        this.initTheme();
        this.initScrollAnimations();
        this.loadDynamicContent();
        this.initContactForm();
        this.initSmoothScrolling();
        this.initNavigation();
        
        // Initialize AOS animations
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 800,
                easing: 'ease-out-cubic',
                once: true,
                offset: 100,
                disable: 'mobile'
            });
        }
    }

    /**
     * Set up event listeners
     */
    initEventListeners() {
        // Theme toggle
        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => this.toggleTheme());
        }

        // Mobile menu toggle
        const mobileToggle = document.getElementById('mobile-toggle');
        const navMenu = document.getElementById('nav-menu');
        
        if (mobileToggle && navMenu) {
            mobileToggle.addEventListener('click', () => this.toggleMobileMenu());
            
            // Close mobile menu when clicking on links
            navMenu.addEventListener('click', (e) => {
                if (e.target.classList.contains('nav-link')) {
                    this.closeMobileMenu();
                }
            });
        }

        // Scroll events
        window.addEventListener('scroll', this.throttle(() => {
            this.handleScroll();
        }, 16));

        // Resize events
        window.addEventListener('resize', this.debounce(() => {
            this.handleResize();
        }, 250));

        // Page load
        window.addEventListener('load', () => {
            this.handlePageLoad();
        });

        // Escape key for mobile menu
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.mobileMenuOpen) {
                this.closeMobileMenu();
            }
        });
    }

    /**
     * Initialize theme system
     */
    initTheme() {
        // Check for saved theme or default to light
        const savedTheme = localStorage.getItem('portfolio-theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        this.currentTheme = savedTheme || (prefersDark ? 'dark' : 'light');
        this.applyTheme(this.currentTheme);
        
        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('portfolio-theme')) {
                this.currentTheme = e.matches ? 'dark' : 'light';
                this.applyTheme(this.currentTheme);
            }
        });
    }

    /**
     * Apply theme to document
     */
    applyTheme(theme) {
        const themeToggle = document.getElementById('theme-toggle');
        
        if (theme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
            if (themeToggle) themeToggle.textContent = '🌙';
        } else {
            document.documentElement.removeAttribute('data-theme');
            if (themeToggle) themeToggle.textContent = '☀️';
        }
        
        this.currentTheme = theme;
        localStorage.setItem('portfolio-theme', theme);
    }

    /**
     * Toggle between light and dark themes
     */
    toggleTheme() {
        const newTheme = this.currentTheme === 'light' ? 'dark' : 'light';
        this.applyTheme(newTheme);
        
        // Add animation class
        document.body.style.transition = 'background-color 0.3s ease, color 0.3s ease';
        setTimeout(() => {
            document.body.style.transition = '';
        }, 300);
    }

    /**
     * Initialize scroll-based animations and effects
     */
    initScrollAnimations() {
        // Parallax effect for hero section
        const hero = document.querySelector('.hero');
        if (hero) {
            window.addEventListener('scroll', this.throttle(() => {
                const scrolled = window.pageYOffset;
                const rate = scrolled * -0.5;
                hero.style.transform = `translateY(${rate}px)`;
            }, 16));
        }

        // Animate skill progress bars on scroll
        this.initSkillBars();
    }

    /**
     * Initialize skill progress bar animations
     */
    initSkillBars() {
        const skillBars = document.querySelectorAll('.skill-progress-bar');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const bar = entry.target;
                    const progress = bar.getAttribute('data-progress') || '0';
                    bar.style.width = `${progress}%`;
                }
            });
        }, { threshold: 0.5 });

        skillBars.forEach(bar => observer.observe(bar));
    }

    /**
     * Handle scroll events
     */
    handleScroll() {
        const scrolled = window.pageYOffset;
        const header = document.querySelector('.header');
        
        // Add scrolled class to header
        if (header) {
            if (scrolled > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        }

        // Update navigation active state
        this.updateActiveNavLink();
        
        this.scrollPosition = scrolled;
    }

    /**
     * Update active navigation link based on scroll position
     */
    updateActiveNavLink() {
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link');
        
        let current = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 150;
            const sectionHeight = section.clientHeight;
            
            if (this.scrollPosition >= sectionTop && this.scrollPosition < sectionTop + sectionHeight) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            const href = link.getAttribute('href');
            if (href === `#${current}`) {
                link.classList.add('active');
            }
        });
    }

    /**
     * Initialize smooth scrolling for navigation links
     */
    initSmoothScrolling() {
        const navLinks = document.querySelectorAll('a[href^="#"]');
        
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = link.getAttribute('href').slice(1);
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    const headerHeight = document.querySelector('.header').offsetHeight;
                    const targetPosition = targetElement.offsetTop - headerHeight - 20;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    /**
     * Initialize navigation functionality
     */
    initNavigation() {
        // Handle navigation link hover effects
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            link.addEventListener('mouseenter', () => {
                link.style.transform = 'translateY(-2px)';
            });
            
            link.addEventListener('mouseleave', () => {
                link.style.transform = 'translateY(0)';
            });
        });
    }

    /**
     * Toggle mobile menu
     */
    toggleMobileMenu() {
        const navMenu = document.getElementById('nav-menu');
        const mobileToggle = document.getElementById('mobile-toggle');
        
        if (navMenu && mobileToggle) {
            this.mobileMenuOpen = !this.mobileMenuOpen;
            
            if (this.mobileMenuOpen) {
                navMenu.classList.add('active');
                mobileToggle.textContent = '✕';
                document.body.style.overflow = 'hidden';
            } else {
                navMenu.classList.remove('active');
                mobileToggle.textContent = '☰';
                document.body.style.overflow = '';
            }
        }
    }

    /**
     * Close mobile menu
     */
    closeMobileMenu() {
        if (this.mobileMenuOpen) {
            this.toggleMobileMenu();
        }
    }

    /**
     * Handle window resize
     */
    handleResize() {
        // Close mobile menu on resize to desktop
        if (window.innerWidth > 768 && this.mobileMenuOpen) {
            this.closeMobileMenu();
        }
        
        // Refresh AOS on resize
        if (typeof AOS !== 'undefined') {
            AOS.refresh();
        }
    }

    /**
     * Handle page load
     */
    handlePageLoad() {
        // Remove loading states
        document.body.classList.add('loaded');
        
        // Initialize any final animations
        this.initLoadAnimations();
    }

    /**
     * Initialize load animations
     */
    initLoadAnimations() {
        // Animate profile image
        const profileImage = document.getElementById('profile-image');
        if (profileImage) {
            profileImage.style.opacity = '0';
            profileImage.style.transform = 'scale(0.8)';
            
            setTimeout(() => {
                profileImage.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                profileImage.style.opacity = '1';
                profileImage.style.transform = 'scale(1)';
            }, 500);
        }
    }

    /**
     * Load dynamic content from API
     */
    async loadDynamicContent() {
        try {
            await Promise.all([
                this.loadSkills(),
                this.loadAchievements(),
                this.loadProfile()
            ]);
        } catch (error) {
            console.error('Error loading dynamic content:', error);
            this.handleContentLoadError();
        }
    }

    /**
     * Load skills from API
     */
    async loadSkills() {
        try {
            const response = await fetch('api/get_skills.php');
            const skills = await response.json();
            
            if (skills && skills.length > 0) {
                this.renderSkills(skills);
            } else {
                this.renderDefaultSkills();
            }
        } catch (error) {
            console.error('Error loading skills:', error);
            this.renderDefaultSkills();
        }
    }

    /**
     * Render skills to the page
     */
    renderSkills(skills) {
        const skillsGrid = document.getElementById('skills-grid');
        if (!skillsGrid) return;

        // Group skills by category
        const groupedSkills = this.groupSkillsByCategory(skills);
        
        const skillsHTML = Object.entries(groupedSkills).map(([category, categorySkills]) => {
            const icon = this.getSkillCategoryIcon(category);
            const skillsListHTML = categorySkills.map(skill => `
                <div class="skill-item">
                    <div>
                        <div class="skill-name">${this.escapeHtml(skill.name)}</div>
                        <div class="skill-progress">
                            <div class="skill-progress-bar" data-progress="${skill.level}" style="width: 0%"></div>
                        </div>
                    </div>
                    <div class="skill-level">${skill.level}%</div>
                </div>
            `).join('');

            return `
                <div class="skill-category" data-aos="fade-up">
                    <h3><span class="skill-icon">${icon}</span>${this.escapeHtml(category)}</h3>
                    <div class="skills-list">
                        ${skillsListHTML}
                    </div>
                </div>
            `;
        }).join('');

        skillsGrid.innerHTML = skillsHTML;
        
        // Re-initialize skill bar animations
        this.initSkillBars();
    }

    /**
     * Render default skills if API fails
     */
    renderDefaultSkills() {
        const defaultSkills = {
            'Programming Languages': [
                { name: 'C++', level: 90 },
                { name: 'Python', level: 85 },
                { name: 'Java', level: 80 },
                { name: 'JavaScript', level: 85 },
                { name: 'C', level: 75 }
            ],
            'Web Development': [
                { name: 'HTML/CSS', level: 90 },
                { name: 'React', level: 80 },
                { name: 'Node.js', level: 75 },
                { name: 'PHP', level: 70 },
                { name: 'Express.js', level: 75 }
            ],
            'Mobile Development': [
                { name: 'React Native', level: 80 },
                { name: 'Android (Java)', level: 70 },
                { name: 'Flutter', level: 65 }
            ],
            'Tools & Technologies': [
                { name: 'Git', level: 85 },
                { name: 'MySQL', level: 80 },
                { name: 'Firebase', level: 75 },
                { name: 'VS Code', level: 90 },
                { name: 'Linux', level: 70 }
            ]
        };
        
        this.renderSkills(this.convertDefaultSkillsFormat(defaultSkills));
    }

    /**
     * Convert default skills format
     */
    convertDefaultSkillsFormat(defaultSkills) {
        const skills = [];
        Object.entries(defaultSkills).forEach(([category, categorySkills]) => {
            categorySkills.forEach(skill => {
                skills.push({
                    name: skill.name,
                    category: category,
                    level: skill.level
                });
            });
        });
        return skills;
    }

    /**
     * Group skills by category
     */
    groupSkillsByCategory(skills) {
        return skills.reduce((groups, skill) => {
            const category = skill.category || 'Other';
            if (!groups[category]) {
                groups[category] = [];
            }
            groups[category].push(skill);
            return groups;
        }, {});
    }

    /**
     * Get icon for skill category
     */
    getSkillCategoryIcon(category) {
        const icons = {
            'Programming Languages': '💻',
            'Web Development': '🌐',
            'Mobile Development': '📱',
            'Tools & Technologies': '🛠️',
            'Databases': '🗄️',
            'Other': '⚡'
        };
        return icons[category] || '⚡';
    }

    /**
     * Load achievements from API
     */
    async loadAchievements() {
        try {
            const response = await fetch('api/get_achievements.php');
            const achievements = await response.json();
            
            if (achievements && achievements.length > 0) {
                this.renderAchievements(achievements);
            } else {
                this.renderDefaultAchievements();
            }
        } catch (error) {
            console.error('Error loading achievements:', error);
            this.renderDefaultAchievements();
        }
    }

    /**
     * Render achievements to the page
     */
    renderAchievements(achievements) {
        const achievementsContainer = document.getElementById('achievements-container');
        if (!achievementsContainer) return;

        const achievementsHTML = `
            <div class="achievements-grid">
                ${achievements.map(achievement => `
                    <div class="achievement-card" data-aos="fade-up">
                        <div class="achievement-header">
                            <div class="achievement-icon">🏆</div>
                            <div class="achievement-title">${this.escapeHtml(achievement.title)}</div>
                        </div>
                        <div class="achievement-description">
                            ${this.escapeHtml(achievement.description)}
                        </div>
                        ${achievement.date ? `<div class="achievement-date">${this.formatDate(achievement.date)}</div>` : ''}
                    </div>
                `).join('')}
            </div>
        `;

        achievementsContainer.innerHTML = achievementsHTML;
    }

    /**
     * Render default achievements if API fails
     */
    renderDefaultAchievements() {
        const defaultAchievements = [
            {
                title: "Dean's Award Winner",
                description: "Received Dean's Award for academic excellence in Computer Science and Engineering.",
                date: "2024"
            },
            {
                title: "Youth AI Pitch Competition - 2nd Place",
                description: "Our team placed 2nd out of 25 teams in the Youth AI Pitch Competition with the IWKHealth project.",
                date: "2025"
            },
            {
                title: "Codeforces Specialist",
                description: "Achieved Specialist ranking on Codeforces with a maximum rating of 1448.",
                date: "2024"
            },
            {
                title: "CS50x Certificate",
                description: "Completed Harvard University's CS50x Introduction to Computer Science course.",
                date: "2024"
            }
        ];
        
        this.renderAchievements(defaultAchievements);
    }

    /**
     * Load profile information
     */
    async loadProfile() {
        try {
            const response = await fetch('api/get_profile.php');
            const profile = await response.json();
            
            if (profile) {
                this.updateProfileContent(profile);
            }
        } catch (error) {
            console.error('Error loading profile:', error);
        }
    }

    /**
     * Update profile content on the page
     */
    updateProfileContent(profile) {
        // Update tagline
        const heroTagline = document.getElementById('hero-tagline');
        if (heroTagline && profile.tagline) {
            heroTagline.textContent = profile.tagline;
        }

        // Update about content
        const aboutContent = document.getElementById('about-content');
        if (aboutContent && profile.about_me) {
            aboutContent.innerHTML = profile.about_me.split('\n').map(paragraph => 
                `<p>${this.escapeHtml(paragraph)}</p>`
            ).join('');
        }

        // Update profile image
        const profileImage = document.getElementById('profile-image');
        if (profileImage && profile.profile_image) {
            profileImage.src = profile.profile_image;
        }
    }

    /**
     * Initialize contact form
     */
    initContactForm() {
        const contactForm = document.getElementById('contact-form');
        if (!contactForm) return;

        contactForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            await this.handleContactFormSubmit(contactForm);
        });

        // Add real-time validation
        const inputs = contactForm.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
            input.addEventListener('input', () => this.clearFieldError(input));
        });
    }

    /**
     * Handle contact form submission
     */
    async handleContactFormSubmit(form) {
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;
        
        // Validate form
        if (!this.validateForm(form)) {
            return;
        }

        try {
            // Show loading state
            submitButton.textContent = 'Sending...';
            submitButton.disabled = true;

            const formData = new FormData(form);
            const response = await fetch('contact_submit.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification('Message sent successfully!', 'success');
                form.reset();
            } else {
                this.showNotification(result.message || 'Failed to send message. Please try again.', 'error');
            }
        } catch (error) {
            console.error('Error submitting form:', error);
            this.showNotification('Failed to send message. Please try again.', 'error');
        } finally {
            // Restore button state
            submitButton.textContent = originalText;
            submitButton.disabled = false;
        }
    }

    /**
     * Validate entire form
     */
    validateForm(form) {
        const inputs = form.querySelectorAll('input[required], textarea[required]');
        let isValid = true;

        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isValid = false;
            }
        });

        return isValid;
    }

    /**
     * Validate individual field
     */
    validateField(field) {
        const value = field.value.trim();
        const type = field.type;
        let isValid = true;
        let errorMessage = '';

        // Check if required field is empty
        if (field.hasAttribute('required') && !value) {
            isValid = false;
            errorMessage = 'This field is required.';
        }
        // Validate email
        else if (type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                errorMessage = 'Please enter a valid email address.';
            }
        }
        // Validate message length
        else if (field.name === 'message' && value && value.length < 10) {
            isValid = false;
            errorMessage = 'Message must be at least 10 characters long.';
        }

        // Show/hide error
        if (isValid) {
            this.clearFieldError(field);
        } else {
            this.showFieldError(field, errorMessage);
        }

        return isValid;
    }

    /**
     * Show field error
     */
    showFieldError(field, message) {
        field.classList.add('error');
        
        let errorElement = field.parentNode.querySelector('.field-error');
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = 'field-error';
            field.parentNode.appendChild(errorElement);
        }
        
        errorElement.textContent = message;
    }

    /**
     * Clear field error
     */
    clearFieldError(field) {
        field.classList.remove('error');
        
        const errorElement = field.parentNode.querySelector('.field-error');
        if (errorElement) {
            errorElement.remove();
        }
    }

    /**
     * Show notification
     */
    showNotification(message, type = 'info') {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.notification');
        existingNotifications.forEach(notification => notification.remove());

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-icon">${type === 'success' ? '✓' : type === 'error' ? '✗' : 'ℹ'}</span>
                <span class="notification-message">${message}</span>
                <button class="notification-close" aria-label="Close notification">✕</button>
            </div>
        `;

        // Add to page
        document.body.appendChild(notification);

        // Add click handler for close button
        const closeButton = notification.querySelector('.notification-close');
        closeButton.addEventListener('click', () => {
            notification.remove();
        });

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }
        }, 5000);

        // Animate in
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);
    }

    /**
     * Handle content load error
     */
    handleContentLoadError() {
        // Remove loading states and show default content
        const loadingContainers = document.querySelectorAll('.loading-container');
        loadingContainers.forEach(container => {
            container.style.display = 'none';
        });
    }

    /**
     * Utility function to escape HTML
     */
    escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    /**
     * Format date string
     */
    formatDate(dateString) {
        try {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long'
            });
        } catch (error) {
            return dateString;
        }
    }

    /**
     * Throttle function
     */
    throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    /**
     * Debounce function
     */
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
}

// Initialize portfolio when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new Portfolio();
});

// Add additional CSS for notifications and form validation
const additionalStyles = `
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
        max-width: 400px;
        background-color: var(--bg-tertiary);
        border: 1px solid var(--border-primary);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-lg);
        transform: translateX(100%);
        opacity: 0;
        transition: all var(--transition-smooth);
    }

    .notification.show {
        transform: translateX(0);
        opacity: 1;
    }

    .notification-success {
        border-left: 4px solid var(--success-color);
    }

    .notification-error {
        border-left: 4px solid var(--error-color);
    }

    .notification-content {
        display: flex;
        align-items: center;
        gap: var(--spacing-sm);
        padding: var(--spacing-md);
    }

    .notification-icon {
        font-weight: bold;
        color: var(--accent-primary);
    }

    .notification-success .notification-icon {
        color: var(--success-color);
    }

    .notification-error .notification-icon {
        color: var(--error-color);
    }

    .notification-message {
        flex: 1;
        color: var(--text-primary);
    }

    .notification-close {
        background: none;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        padding: var(--spacing-xs);
        border-radius: var(--radius-sm);
        transition: color var(--transition-fast);
    }

    .notification-close:hover {
        color: var(--text-primary);
    }

    .form-input.error,
    .form-textarea.error {
        border-color: var(--error-color);
        box-shadow: 0 0 0 3px rgba(207, 34, 46, 0.1);
    }

    .field-error {
        color: var(--error-color);
        font-size: 0.875rem;
        margin-top: var(--spacing-xs);
        display: flex;
        align-items: center;
        gap: var(--spacing-xs);
    }

    .field-error::before {
        content: '⚠';
        font-size: 0.75rem;
    }

    @media (max-width: 768px) {
        .notification {
            top: 10px;
            right: 10px;
            left: 10px;
            max-width: none;
        }
    }
`;

// Inject additional styles
const styleSheet = document.createElement('style');
styleSheet.textContent = additionalStyles;
document.head.appendChild(styleSheet);
