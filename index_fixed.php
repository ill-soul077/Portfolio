<?php
// Include configuration
require_once 'config.php';

// Fetch profile data from database
try {
    $profile_stmt = $pdo->query("SELECT * FROM admin LIMIT 1");
    $profile = $profile_stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $profile = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $profile ? htmlspecialchars($profile['username']) : 'Naquib'; ?> Portfolio - Software Engineer</title>
    <meta name="description" content="Portfolio of <?php echo $profile ? htmlspecialchars($profile['username']) : 'Naquib'; ?> - Aspiring Software Engineer specializing in algorithms, web development, and mobile applications.">
    <meta name="keywords" content="Software Engineer, Portfolio, Programming, Competitive Programming, Web Development">
    <meta name="author" content="<?php echo $profile ? htmlspecialchars($profile['username']) : 'Naquib'; ?>">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo $profile ? htmlspecialchars($profile['username']) : 'Naquib'; ?> Portfolio">
    <meta property="og:description" content="Aspiring software engineer with problem-solving skills in algorithms and strong enthusiasm for technology.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    
    <!-- GitHub Theme Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Preload critical resources -->
    <link rel="preload" href="assets/js/main.js" as="script">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="navbar container">
            <div class="logo">
                <a href="#home"><?php echo $profile ? htmlspecialchars($profile['username']) : 'Naquib'; ?></a>
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
                    <a href="admin/login.php" class="btn btn-secondary">Admin</a>
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
                <div class="hero-content" data-aos="fade-up">
                    <div class="profile-section">
                        <div class="profile-image-container">
                            <img src="assets/images/profile.png" 
                                 alt="<?php echo $profile ? htmlspecialchars($profile['username']) : 'Profile'; ?>" 
                                 class="profile-image" 
                                 id="profile-image"
                                 onerror="this.src='assets/images/profile-placeholder.svg'">
                        </div>
                    </div>
                    
                    <h1 class="hero-title">Hi, I'm <?php echo $profile ? htmlspecialchars($profile['username']) : 'Hassan Mohammed Naquibul Hoque'; ?></h1>
                    <p class="hero-tagline" id="hero-tagline">
                        <?php echo $profile && $profile['tagline'] ? htmlspecialchars($profile['tagline']) : 'Aspiring software engineer with problem-solving skills in algorithms and a strong enthusiasm for technology, eager to contribute to impactful projects.'; ?>
                    </p>
                    
                    <div class="hero-actions">
                        <a href="#contact" class="btn btn-primary">Get In Touch</a>
                        <a href="#projects" class="btn btn-secondary">View Projects</a>
                        <a href="https://github.com/ill-soul077" target="_blank" class="btn btn-outline">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"/>
                            </svg>
                            GitHub
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="section">
            <div class="container">
                <h2 class="section-title" data-aos="fade-up">About Me</h2>
                <div class="about-content">
                    <div class="about-text" data-aos="fade-up" data-aos-delay="100">
                        <div id="about-content">
                            <?php if ($profile && $profile['about_me']): ?>
                                <?php echo nl2br(htmlspecialchars($profile['about_me'])); ?>
                            <?php else: ?>
                                <p>I am a dedicated Computer Science and Engineering student passionate about developing innovative solutions that address real-world challenges. With a strong foundation in programming languages like C, C++, Java, and Python, I continuously strive to expand my technical skills.</p>
                                
                                <p>My journey in competitive programming has sharpened my analytical thinking and problem-solving capabilities. I have hands-on experience in web development, mobile app development, and database management, having worked on projects ranging from Android applications to numerical computation systems.</p>
                                
                                <p>I am always eager to learn new technologies and collaborate on impactful projects that can make a difference in the tech industry.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="quick-facts" data-aos="fade-up" data-aos-delay="200">
                        <h3>Quick Facts</h3>
                        <div class="facts-grid">
                            <div class="fact-item">
                                <div class="fact-icon">🎓</div>
                                <div class="fact-content">
                                    <div class="fact-label">Education</div>
                                    <div class="fact-value">Computer Science & Engineering, KUET</div>
                                </div>
                            </div>
                            <div class="fact-item">
                                <div class="fact-icon">💻</div>
                                <div class="fact-content">
                                    <div class="fact-label">Codeforces Rating</div>
                                    <div class="fact-value">Specialist (Max: 1448)</div>
                                </div>
                            </div>
                            <div class="fact-item">
                                <div class="fact-icon">🏆</div>
                                <div class="fact-content">
                                    <div class="fact-label">Achievement</div>
                                    <div class="fact-value">Dean's Award Winner</div>
                                </div>
                            </div>
                            <div class="fact-item">
                                <div class="fact-icon">🌍</div>
                                <div class="fact-content">
                                    <div class="fact-label">Location</div>
                                    <div class="fact-value">Bangladesh</div>
                                </div>
                            </div>
                            <div class="fact-item">
                                <div class="fact-icon">📧</div>
                                <div class="fact-content">
                                    <div class="fact-label">Email</div>
                                    <div class="fact-value"><a href="mailto:naquib@example.com">naquib@example.com</a></div>
                                </div>
                            </div>
                            <div class="fact-item">
                                <div class="fact-icon">🎂</div>
                                <div class="fact-content">
                                    <div class="fact-label">Experience</div>
                                    <div class="fact-value">5+ Years Programming</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Projects Section -->
        <section id="projects" class="section">
            <div class="container">
                <h2 class="section-title" data-aos="fade-up">Featured Projects</h2>
                <div class="projects-grid">
                    <!-- Project 1: IWKHealth -->
                    <div class="project-card" data-aos="fade-up" data-aos-delay="100">
                        <div class="project-header">
                            <div class="project-icon">🏥</div>
                            <div class="project-links">
                                <a href="https://github.com/ill-soul077/iwkhealth" target="_blank" class="project-link" aria-label="GitHub Repository">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                        <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"/>
                                    </svg>
                                </a>
                                <a href="#" class="project-link" aria-label="Live Demo">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                        <path d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z"/>
                                        <path d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <h3 class="project-title">IWKHealth</h3>
                        <p class="project-description">
                            IWKids is an AI-powered mobile app that enhances the ER experience for kids and families, 
                            built for the Youth AI Pitch Competition where our team placed 2nd out of 25 teams.
                        </p>
                        <div class="project-tech">
                            <span class="tech-tag">React Native</span>
                            <span class="tech-tag">OpenAI API</span>
                            <span class="tech-tag">Firebase</span>
                            <span class="tech-tag">Node.js</span>
                        </div>
                        <div class="project-year">2025</div>
                    </div>

                    <!-- Project 2: BVHS Mobile App -->
                    <div class="project-card" data-aos="fade-up" data-aos-delay="200">
                        <div class="project-header">
                            <div class="project-icon">📱</div>
                            <div class="project-links">
                                <a href="https://github.com/ill-soul077/bvhs-mobile" target="_blank" class="project-link" aria-label="GitHub Repository">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                        <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"/>
                                    </svg>
                                </a>
                                <a href="#" class="project-link" aria-label="Live Demo">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                        <path d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z"/>
                                        <path d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <h3 class="project-title">BVHS Mobile App</h3>
                        <p class="project-description">
                            A mobile app created for my high school for easier distribution of information. 
                            This was created for Harvard University's CS50x course.
                        </p>
                        <div class="project-tech">
                            <span class="tech-tag">React Native</span>
                            <span class="tech-tag">JavaScript</span>
                            <span class="tech-tag">Expo</span>
                            <span class="tech-tag">AsyncStorage</span>
                        </div>
                        <div class="project-year">2024</div>
                    </div>

                    <!-- Project 3: Ben Eater's 6502 Project -->
                    <div class="project-card" data-aos="fade-up" data-aos-delay="300">
                        <div class="project-header">
                            <div class="project-icon">🔧</div>
                            <div class="project-links">
                                <a href="https://github.com/ill-soul077/6502-project" target="_blank" class="project-link" aria-label="GitHub Repository">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                        <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"/>
                                    </svg>
                                </a>
                                <a href="#" class="project-link" aria-label="Live Demo">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                        <path d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z"/>
                                        <path d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <h3 class="project-title">Ben Eater's 6502 Project</h3>
                        <p class="project-description">
                            A series of hardware projects surrounding the 6502 CPU, inspired by Ben Eater. 
                            Includes breadboard computer build and assembly programming.
                        </p>
                        <div class="project-tech">
                            <span class="tech-tag">Arduino</span>
                            <span class="tech-tag">Assembly</span>
                            <span class="tech-tag">Python</span>
                            <span class="tech-tag">Hardware</span>
                        </div>
                        <div class="project-year">2024</div>
                    </div>

                    <!-- Project 4: Competitive Programming Solutions -->
                    <div class="project-card" data-aos="fade-up" data-aos-delay="400">
                        <div class="project-header">
                            <div class="project-icon">🏆</div>
                            <div class="project-links">
                                <a href="https://github.com/ill-soul077/competitive-programming" target="_blank" class="project-link" aria-label="GitHub Repository">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                        <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"/>
                                    </svg>
                                </a>
                                <a href="https://codeforces.com/profile/your-handle" target="_blank" class="project-link" aria-label="Codeforces Profile">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                        <path d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z"/>
                                        <path d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <h3 class="project-title">Competitive Programming Solutions</h3>
                        <p class="project-description">
                            Collection of optimized solutions for competitive programming problems from Codeforces, 
                            AtCoder, and other platforms. Demonstrates algorithmic thinking and problem-solving skills.
                        </p>
                        <div class="project-tech">
                            <span class="tech-tag">C++</span>
                            <span class="tech-tag">Python</span>
                            <span class="tech-tag">Algorithms</span>
                            <span class="tech-tag">Data Structures</span>
                        </div>
                        <div class="project-year">2023-2025</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Skills Section -->
        <section id="skills" class="section">
            <div class="container">
                <h2 class="section-title" data-aos="fade-up">Skills & Technologies</h2>
                <div class="skills-grid" id="skills-grid">
                    <!-- Skills will be loaded dynamically -->
                    <div class="loading-container">
                        <div class="loading-spinner"></div>
                        <p>Loading skills...</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Achievements Section -->
        <section id="achievements" class="section">
            <div class="container">
                <h2 class="section-title" data-aos="fade-up">Achievements</h2>
                <div id="achievements-container">
                    <!-- Achievements will be loaded dynamically -->
                    <div class="loading-container">
                        <div class="loading-spinner"></div>
                        <p>Loading achievements...</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="section">
            <div class="container">
                <h2 class="section-title" data-aos="fade-up">Get In Touch</h2>
                <div class="contact-content">
                    <p class="contact-intro" data-aos="fade-up" data-aos-delay="100">
                        I'm always interested in hearing about new opportunities and projects. 
                        Feel free to reach out if you'd like to connect!
                    </p>
                    
                    <form id="contact-form" class="contact-form" data-aos="fade-up" data-aos-delay="200">
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
                        
                        <div class="form-submit">
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
            <div class="footer-content">
                <div class="social-links">
                    <a href="https://github.com/ill-soul077" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="social-link" 
                       aria-label="GitHub">
                        <svg width="20" height="20" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"/>
                        </svg>
                    </a>
                    <a href="https://codeforces.com/profile/your-handle" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="social-link" 
                       aria-label="Codeforces">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M4.5 7.5C5.328 7.5 6 8.172 6 9s-.672 1.5-1.5 1.5S3 9.828 3 9s.672-1.5 1.5-1.5zm0 9C5.328 16.5 6 17.172 6 18s-.672 1.5-1.5 1.5S3 18.828 3 18s.672-1.5 1.5-1.5zM12 7.5c.828 0 1.5.672 1.5 1.5s-.672 1.5-1.5 1.5-1.5-.672-1.5-1.5.672-1.5 1.5-1.5zm0 9c.828 0 1.5.672 1.5 1.5s-.672 1.5-1.5 1.5-1.5-.672-1.5-1.5.672-1.5 1.5-1.5zm7.5-9c.828 0 1.5.672 1.5 1.5s-.672 1.5-1.5 1.5S18 9.828 18 9s.672-1.5 1.5-1.5zm0 9c.828 0 1.5.672 1.5 1.5s-.672 1.5-1.5 1.5S18 18.828 18 18s.672-1.5 1.5-1.5z"/>
                        </svg>
                    </a>
                    <a href="mailto:naquib@example.com" 
                       class="social-link" 
                       aria-label="Email">
                        <svg width="20" height="20" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z"/>
                        </svg>
                    </a>
                    <a href="https://linkedin.com/in/your-profile" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="social-link" 
                       aria-label="LinkedIn">
                        <svg width="20" height="20" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z"/>
                        </svg>
                    </a>
                </div>
                
                <div class="footer-text">
                    <p>&copy; 2025 Hassan Mohammed Naquibul Hoque. All rights reserved.</p>
                    <p class="footer-sub">Built with HTML, CSS, JavaScript, PHP & MySQL</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
