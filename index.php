<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Naquib Portfolio - Hassan Mohammed Naquibul Hoque</title>
    <meta name="description" content="Portfolio of Hassan Mohammed Naquibul Hoque (Naquib) - Aspiring Software Engineer specializing in algorithms, web development, and mobile applications.">
    <meta name="keywords" content="Naquib, Hassan Mohammed Naquibul Hoque, Software Engineer, Portfolio, Programming, Competitive Programming, Codeforces">
    <meta name="author" content="Hassan Mohammed Naquibul Hoque">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Naquib Portfolio - Hassan Mohammed Naquibul Hoque">
    <meta property="og:description" content="Aspiring software engineer with problem-solving skills in algorithms and strong enthusiasm for technology.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="http://localhost/portfolio/">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/portfolio/assets/favicon.ico">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="/portfolio/assets/css/style.css">
    
    <!-- Preload critical resources -->
    <link rel="preload" href="/portfolio/assets/js/main.js" as="script">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="navbar container">
            <div class="logo">
                <a href="#home">Naquib</a>
            </div>
            
            <ul class="nav-menu" id="nav-menu">
                <li><a href="#home" class="nav-link active">Home</a></li>
                <li><a href="#about" class="nav-link">About</a></li>
                <li><a href="#projects" class="nav-link">Projects</a></li>
                <li><a href="#skills" class="nav-link">Skills</a></li>
                <li><a href="#achievements" class="nav-link">Achievements</a></li>
                <li><a href="#contact" class="nav-link">Contact</a></li>
                <li>
                    <button id="theme-toggle" class="theme-toggle" aria-label="Toggle theme">
                        🌙
                    </button>
                </li>
                <li>
                    <a href="/portfolio/admin/login.php" class="btn btn-secondary">Admin</a>
                </li>
            </ul>
            
            <button class="mobile-toggle" id="mobile-toggle" aria-label="Toggle mobile menu">
                ☰
            </button>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Home Section -->
        <section id="home" class="section hero">
            <div class="container">
                <img src="/portfolio/assets/images/profile-placeholder.svg" 
                     alt="Hassan Mohammed Naquibul Hoque" 
                     class="profile-image" 
                     id="profile-image">
                
                <h1 class="hero-title">Hassan Mohammed Naquibul Hoque</h1>
                <p class="hero-tagline" id="hero-tagline">
                    Aspiring software engineer with mediocore problem-solving skills in algorithms and a strong enthusiasm for technology, eager to contribute to impactful projects in software engineering and research.
                </p>
                
                <div style="margin-top: 2rem;">
                    <a href="#contact" class="btn btn-primary">Get In Touch</a>
                    <a href="#projects" class="btn btn-secondary">View Projects</a>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="section">
            <div class="container">
                <h2 class="section-title">About Me</h2>
                <div class="grid grid-2">
                    <div class="card">
                        <div id="about-content">
                            <p>I am Hassan Mohammed Naquibul Hoque, a dedicated Computer Science and Engineering student at Khulna University of Engineering & Technology (KUET). With a strong foundation in programming languages like C, C++, Java, and Python, I am passionate about developing innovative solutions that address real-world challenges.</p>
                            
                            <p>My journey in competitive programming has earned me the Specialist rank on Codeforces with a maximum rating of 1448, reflecting my analytical thinking and problem-solving capabilities. I have hands-on experience in web development, mobile app development, and database management, having worked on projects ranging from Android applications to numerical computation systems.</p>
                            
                            <p>I am always eager to learn new technologies and collaborate on impactful projects that can make a difference in the tech industry.</p>
                        </div>
                    </div>
                    
                    <div class="card">
                        <h3>Quick Facts</h3>
                        <ul style="list-style: none; padding: 0;">
                            <li style="margin-bottom: 0.75rem;">
                                <strong>🎓 Education:</strong> Computer Science & Engineering, KUET
                            </li>
                            <li style="margin-bottom: 0.75rem;">
                                <strong>💻 Codeforces Rating:</strong> Specialist (Max: 1448)
                            </li>
                            <li style="margin-bottom: 0.75rem;">
                                <strong>🏆 Achievement:</strong> Dean's Award Winner
                            </li>
                            <li style="margin-bottom: 0.75rem;">
                                <strong>🌍 Location:</strong> Bangladesh
                            </li>
                            <li style="margin-bottom: 0.75rem;">
                                <strong>📧 Email:</strong> <a href="mailto:naquib@example.com">naquib@example.com</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Projects Section -->
        <section id="projects" class="section">
            <div class="container">
                <h2 class="section-title">Projects</h2>
                <div class="grid grid-2" id="projects-grid">
                    <!-- Projects will be loaded dynamically -->
                    <div style="text-align: center; grid-column: 1 / -1;">
                        <div class="loading"></div>
                        <p>Loading projects...</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Skills Section -->
        <section id="skills" class="section">
            <div class="container">
                <h2 class="section-title">Skills & Technologies</h2>
                <div class="skills-grid" id="skills-grid">
                    <!-- Skills will be loaded dynamically -->
                    <div style="text-align: center;">
                        <div class="loading"></div>
                        <p>Loading skills...</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Achievements Section -->
        <section id="achievements" class="section">
            <div class="container">
                <h2 class="section-title">Achievements</h2>
                <div id="achievements-container">
                    <!-- Achievements will be loaded dynamically -->
                    <div style="text-align: center;">
                        <div class="loading"></div>
                        <p>Loading achievements...</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="section">
            <div class="container">
                <h2 class="section-title">Get In Touch</h2>
                <div class="contact-form">
                    <p style="text-align: center; margin-bottom: 2rem; color: var(--text-secondary);">
                        I'm always interested in hearing about new opportunities and projects. 
                        Feel free to reach out if you'd like to connect!
                    </p>
                    
                    <form id="contact-form" class="form">
                        <div class="form-group">
                            <label for="name" class="form-label">Name *</label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   class="form-input" 
                                   required 
                                   aria-describedby="name-error">
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   class="form-input" 
                                   required 
                                   aria-describedby="email-error">
                        </div>
                        
                        <div class="form-group">
                            <label for="message" class="form-label">Message *</label>
                            <textarea id="message" 
                                      name="message" 
                                      class="form-textarea" 
                                      required 
                                      rows="5" 
                                      minlength="10"
                                      aria-describedby="message-error"
                                      placeholder="Tell me about your project or just say hello..."></textarea>
                        </div>
                        
                        <div style="text-align: center;">
                            <button type="submit" class="btn btn-primary">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="social-links">
                <a href="https://github.com/ill-soul077" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   class="social-link" 
                   aria-label="GitHub">
                    📂
                </a>
                <a href="https://codeforces.com/profile/your-handle" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   class="social-link" 
                   aria-label="Codeforces">
                    🏆
                </a>
                <a href="mailto:naquib@example.com" 
                   class="social-link" 
                   aria-label="Email">
                    📧
                </a>
                <a href="https://linkedin.com/in/your-profile" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   class="social-link" 
                   aria-label="LinkedIn">
                    💼
                </a>
            </div>
            
            <p>&copy; 2025 Hassan Mohammed Naquibul Hoque. All rights reserved.</p>
            <p style="font-size: 0.9rem; margin-top: 0.5rem;">
                Built with HTML, CSS, JavaScript, PHP & MySQL
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="/portfolio/assets/js/main.js"></script>
    
    <!-- Google Analytics (Optional) -->
    <!-- 
    <script async src="https://www.googletagmanager.com/gtag/js?id=GA_MEASUREMENT_ID"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'GA_MEASUREMENT_ID');
    </script>
    -->
</body>
</html>
