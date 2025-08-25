// Portfolio JavaScript - Main functionality
class Portfolio {
    constructor() {
        this.init();
    }

    init() {
        this.setupThemeToggle();
        this.setupMobileMenu();
        this.setupSmoothScrolling();
        this.setupScrollAnimations();
        this.loadProfileData();
        this.loadProjects();
        this.loadSkills();
        this.loadAchievements();
        this.setupContactForm();
        this.setupActiveNavigation();
    }

    // Theme Toggle Functionality
    setupThemeToggle() {
        const themeToggle = document.getElementById('theme-toggle');
        const currentTheme = localStorage.getItem('theme') || 'light';
        
        document.documentElement.setAttribute('data-theme', currentTheme);
        this.updateThemeIcon(currentTheme);

        themeToggle?.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            this.updateThemeIcon(newTheme);
        });
    }

    updateThemeIcon(theme) {
        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) {
            themeToggle.innerHTML = theme === 'dark' ? '☀️' : '🌙';
            themeToggle.setAttribute('aria-label', `Switch to ${theme === 'dark' ? 'light' : 'dark'} theme`);
        }
    }

    // Mobile Menu Toggle
    setupMobileMenu() {
        const mobileToggle = document.getElementById('mobile-toggle');
        const navMenu = document.getElementById('nav-menu');

        mobileToggle?.addEventListener('click', () => {
            navMenu?.classList.toggle('active');
        });

        // Close mobile menu when clicking on a nav link
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                navMenu?.classList.remove('active');
            });
        });
    }

    // Smooth Scrolling for Navigation Links
    setupSmoothScrolling() {
        const navLinks = document.querySelectorAll('a[href^="#"]');
        
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = link.getAttribute('href').substring(1);
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

    // Scroll Animations using Intersection Observer
    setupScrollAnimations() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        const sections = document.querySelectorAll('.section');
        sections.forEach(section => {
            observer.observe(section);
        });
    }

    // Active Navigation Highlighting
    setupActiveNavigation() {
        const sections = document.querySelectorAll('.section');
        const navLinks = document.querySelectorAll('.nav-link');

        const observerOptions = {
            threshold: 0.3,
            rootMargin: '-100px 0px -50% 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.getAttribute('id');
                    
                    // Remove active class from all nav links
                    navLinks.forEach(link => link.classList.remove('active'));
                    
                    // Add active class to current nav link
                    const activeLink = document.querySelector(`.nav-link[href="#${id}"]`);
                    activeLink?.classList.add('active');
                }
            });
        }, observerOptions);

        sections.forEach(section => {
            observer.observe(section);
        });
    }

    // Load Profile Data from API
    async loadProfileData() {
        try {
            const response = await fetch('/portfolio/api/get_profile.php');
            const data = await response.json();
            
            if (data.success) {
                this.updateProfileData(data.profile);
            }
        } catch (error) {
            console.error('Error loading profile data:', error);
        }
    }

    updateProfileData(profile) {
        // Update profile image
        const profileImg = document.getElementById('profile-image');
        if (profileImg && profile.profile_pic) {
            profileImg.src = `/portfolio/uploads/${profile.profile_pic}`;
            profileImg.alt = profile.username || 'Profile Picture';
        }

        // Update tagline
        const taglineElement = document.getElementById('hero-tagline');
        if (taglineElement && profile.tagline) {
            taglineElement.textContent = profile.tagline;
        }

        // Update about me
        const aboutElement = document.getElementById('about-content');
        if (aboutElement && profile.about_me) {
            aboutElement.innerHTML = profile.about_me.replace(/\n/g, '<br>');
        }
    }

    // Load Projects from API
    async loadProjects() {
        try {
            const response = await fetch('/portfolio/api/get_projects.php');
            const data = await response.json();
            
            if (data.success) {
                this.renderProjects(data.projects);
            }
        } catch (error) {
            console.error('Error loading projects:', error);
            this.showProjectsError();
        }
    }

    renderProjects(projects) {
        const projectsGrid = document.getElementById('projects-grid');
        if (!projectsGrid) return;

        if (projects.length === 0) {
            projectsGrid.innerHTML = '<p class="text-center">No projects available.</p>';
            return;
        }

        projectsGrid.innerHTML = projects.map(project => `
            <div class="project-card">
                ${project.image ? `
                    <img src="/portfolio/uploads/${project.image}" 
                         alt="${this.escapeHtml(project.title)}" 
                         class="project-image">
                ` : `
                    <div class="project-image" style="display: flex; align-items: center; justify-content: center; background-color: var(--bg-tertiary); color: var(--text-muted);">
                        <span>No Image</span>
                    </div>
                `}
                <div class="project-content">
                    <h3 class="project-title">${this.escapeHtml(project.title)}</h3>
                    <p class="project-description">${this.escapeHtml(project.description)}</p>
                    ${project.tech_stack ? `
                        <div class="project-tech">
                            ${project.tech_stack.split(',').map(tech => 
                                `<span class="tech-tag">${this.escapeHtml(tech.trim())}</span>`
                            ).join('')}
                        </div>
                    ` : ''}
                    <div class="project-links">
                        ${project.github_link ? `
                            <a href="${this.escapeHtml(project.github_link)}" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="project-link">
                                <span>📂</span> GitHub
                            </a>
                        ` : ''}
                        ${project.demo_link ? `
                            <a href="${this.escapeHtml(project.demo_link)}" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="project-link">
                                <span>🔗</span> Demo
                            </a>
                        ` : ''}
                    </div>
                </div>
            </div>
        `).join('');
    }

    showProjectsError() {
        const projectsGrid = document.getElementById('projects-grid');
        if (projectsGrid) {
            projectsGrid.innerHTML = `
                <div class="alert alert-error">
                    <p>Unable to load projects. Please try again later.</p>
                </div>
            `;
        }
    }

    // Load Skills from API
    async loadSkills() {
        try {
            const response = await fetch('/portfolio/api/get_skills.php');
            const data = await response.json();
            
            if (data.success) {
                this.renderSkills(data.skills);
            }
        } catch (error) {
            console.error('Error loading skills:', error);
        }
    }

    renderSkills(skills) {
        const skillsGrid = document.getElementById('skills-grid');
        if (!skillsGrid) return;

        // Group skills by category
        const skillsByCategory = skills.reduce((acc, skill) => {
            if (!acc[skill.skill_category]) {
                acc[skill.skill_category] = [];
            }
            acc[skill.skill_category].push(skill);
            return acc;
        }, {});

        skillsGrid.innerHTML = Object.entries(skillsByCategory).map(([category, categorySkills]) => `
            <div class="skill-category">
                <h3>${this.escapeHtml(category)}</h3>
                <div class="skill-list">
                    ${categorySkills.map(skill => `
                        <span class="skill-item" title="Proficiency: ${skill.proficiency_level}">
                            ${this.escapeHtml(skill.skill_name)}
                        </span>
                    `).join('')}
                </div>
            </div>
        `).join('');
    }

    // Load Achievements from API
    async loadAchievements() {
        try {
            const response = await fetch('/portfolio/api/get_achievements.php');
            const data = await response.json();
            
            if (data.success) {
                this.renderAchievements(data.achievements);
            }
        } catch (error) {
            console.error('Error loading achievements:', error);
        }
    }

    renderAchievements(achievements) {
        const achievementsContainer = document.getElementById('achievements-container');
        if (!achievementsContainer) return;

        achievementsContainer.innerHTML = achievements.map(achievement => `
            <div class="achievement-card card">
                <div class="achievement-icon">
                    🏆
                </div>
                <div class="achievement-content">
                    <h3>${this.escapeHtml(achievement.title)}</h3>
                    ${achievement.date_achieved ? `
                        <div class="achievement-date">
                            ${new Date(achievement.date_achieved).toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'long'
                            })}
                        </div>
                    ` : ''}
                    <p>${this.escapeHtml(achievement.description)}</p>
                </div>
            </div>
        `).join('');
    }

    // Contact Form Functionality
    setupContactForm() {
        const contactForm = document.getElementById('contact-form');
        if (!contactForm) return;

        contactForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(contactForm);
            const submitButton = contactForm.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            // Show loading state
            submitButton.textContent = 'Sending...';
            submitButton.disabled = true;
            
            try {
                const response = await fetch('/portfolio/contact_submit.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showAlert('Message sent successfully! Thank you for contacting me.', 'success');
                    contactForm.reset();
                } else {
                    this.showAlert(data.message || 'Failed to send message. Please try again.', 'error');
                }
            } catch (error) {
                console.error('Error sending message:', error);
                this.showAlert('An error occurred. Please try again later.', 'error');
            } finally {
                submitButton.textContent = originalText;
                submitButton.disabled = false;
            }
        });
    }

    // Show Alert Messages
    showAlert(message, type = 'info') {
        const alertContainer = document.getElementById('alert-container') || this.createAlertContainer();
        
        const alert = document.createElement('div');
        alert.className = `alert alert-${type}`;
        alert.textContent = message;
        
        alertContainer.appendChild(alert);
        
        // Auto-remove alert after 5 seconds
        setTimeout(() => {
            alert.remove();
        }, 5000);
        
        // Scroll to alert
        alert.scrollIntoView({ behavior: 'smooth' });
    }

    createAlertContainer() {
        const container = document.createElement('div');
        container.id = 'alert-container';
        container.style.position = 'fixed';
        container.style.top = '100px';
        container.style.right = '20px';
        container.style.zIndex = '9999';
        container.style.maxWidth = '400px';
        document.body.appendChild(container);
        return container;
    }

    // Utility function to escape HTML
    escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Format date for display
    formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }

    // Debounce function for performance
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

// Form validation utilities
class FormValidator {
    static validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    static validateRequired(value) {
        return value && value.trim().length > 0;
    }

    static validateMinLength(value, minLength) {
        return value && value.trim().length >= minLength;
    }

    static validateMaxLength(value, maxLength) {
        return !value || value.trim().length <= maxLength;
    }
}

// Initialize portfolio when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new Portfolio();
    
    // Add form validation to contact form
    const contactForm = document.getElementById('contact-form');
    if (contactForm) {
        const nameInput = contactForm.querySelector('[name="name"]');
        const emailInput = contactForm.querySelector('[name="email"]');
        const messageInput = contactForm.querySelector('[name="message"]');
        
        // Real-time validation
        nameInput?.addEventListener('blur', function() {
            this.setCustomValidity(
                FormValidator.validateRequired(this.value) ? '' : 'Name is required'
            );
        });
        
        emailInput?.addEventListener('blur', function() {
            if (!FormValidator.validateRequired(this.value)) {
                this.setCustomValidity('Email is required');
            } else if (!FormValidator.validateEmail(this.value)) {
                this.setCustomValidity('Please enter a valid email address');
            } else {
                this.setCustomValidity('');
            }
        });
        
        messageInput?.addEventListener('blur', function() {
            if (!FormValidator.validateRequired(this.value)) {
                this.setCustomValidity('Message is required');
            } else if (!FormValidator.validateMinLength(this.value, 10)) {
                this.setCustomValidity('Message must be at least 10 characters long');
            } else {
                this.setCustomValidity('');
            }
        });
    }
});

// Add scroll event listener for header background
window.addEventListener('scroll', () => {
    const header = document.querySelector('.header');
    if (header) {
        if (window.scrollY > 100) {
            header.style.backgroundColor = 'var(--bg-secondary)';
            header.style.backdropFilter = 'blur(10px)';
        } else {
            header.style.backgroundColor = 'var(--bg-secondary)';
            header.style.backdropFilter = 'none';
        }
    }
});

// Export for testing purposes
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { Portfolio, FormValidator };
}
