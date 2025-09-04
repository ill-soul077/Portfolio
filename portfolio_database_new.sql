-- ===============================================
-- PORTFOLIO DATABASE SETUP - COMPLETE VERSION
-- Created: September 4, 2025
-- Author: Naquib Hassan Portfolio System
-- ===============================================

-- Drop database if exists and create fresh
DROP DATABASE IF EXISTS portfolio_db;
CREATE DATABASE portfolio_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE portfolio_db;

-- ===============================================
-- TABLE STRUCTURES
-- ===============================================

-- Admin users table for authentication
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    full_name VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email)
);

-- Portfolio basics information
CREATE TABLE portfolio_basics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    label VARCHAR(100),
    image VARCHAR(255),
    summary TEXT,
    summary_tr TEXT,
    email VARCHAR(100),
    phone VARCHAR(20),
    city VARCHAR(50),
    country_code VARCHAR(5),
    website VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Social media profiles
CREATE TABLE social_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    platform VARCHAR(50) NOT NULL,
    username VARCHAR(100),
    url VARCHAR(255),
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_platform (platform),
    INDEX idx_order (display_order)
);

-- Skills and technologies
CREATE TABLE skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    situation VARCHAR(100) NOT NULL,
    keywords JSON,
    level VARCHAR(50),
    proficiency_percentage INT DEFAULT 0,
    years_experience INT DEFAULT 0,
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_situation (situation),
    INDEX idx_level (level),
    INDEX idx_order (display_order)
);

-- Projects and repositories
CREATE TABLE repositories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    explanation TEXT,
    tags JSON,
    best_lang VARCHAR(50),
    date DATE,
    link VARCHAR(255),
    view_link VARCHAR(255),
    github_url VARCHAR(255),
    demo_url VARCHAR(255),
    image VARCHAR(255),
    status VARCHAR(20) DEFAULT 'completed',
    is_featured BOOLEAN DEFAULT FALSE,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_date (date),
    INDEX idx_featured (is_featured),
    INDEX idx_status (status),
    INDEX idx_order (display_order)
);

-- Work experience
CREATE TABLE work_experience (
    id INT AUTO_INCREMENT PRIMARY KEY,
    position VARCHAR(100) NOT NULL,
    company_name VARCHAR(100) NOT NULL,
    start_date VARCHAR(50),
    end_date VARCHAR(50),
    location VARCHAR(100),
    summary TEXT,
    achievements JSON,
    technologies JSON,
    is_current BOOLEAN DEFAULT FALSE,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_company (company_name),
    INDEX idx_position (position),
    INDEX idx_current (is_current),
    INDEX idx_order (display_order)
);

-- Education details
CREATE TABLE education (
    id INT AUTO_INCREMENT PRIMARY KEY,
    institution VARCHAR(200) NOT NULL,
    area VARCHAR(100),
    study_type VARCHAR(50),
    start_date VARCHAR(10),
    end_date VARCHAR(10),
    gpa VARCHAR(20),
    achievement VARCHAR(200),
    location VARCHAR(100),
    description TEXT,
    is_current BOOLEAN DEFAULT FALSE,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_institution (institution),
    INDEX idx_study_type (study_type),
    INDEX idx_current (is_current),
    INDEX idx_order (display_order)
);

-- Academic highlights and achievements
CREATE TABLE academic_highlights (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    date VARCHAR(20),
    category VARCHAR(50),
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_order (display_order)
);

-- Personal interests
CREATE TABLE interests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    interest VARCHAR(100) NOT NULL,
    description TEXT,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_order (display_order)
);

-- Awards and certifications
CREATE TABLE awards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    awarder VARCHAR(100),
    date VARCHAR(20),
    description TEXT,
    certificate_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Contact messages from portfolio visitors
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200),
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    is_replied BOOLEAN DEFAULT FALSE,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_read (is_read),
    INDEX idx_date (created_at)
);

-- Site settings and configuration
CREATE TABLE site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_key (setting_key)
);

-- ===============================================
-- DEFAULT DATA INSERTION
-- ===============================================

-- Insert default admin user
-- Username: admin, Password: admin123 (hashed)
INSERT INTO admin_users (username, password, email, full_name) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@portfolio.com', 'Portfolio Administrator'),
('naquib', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'naquib.hassan@example.com', 'Naquib Hassan');

-- Insert portfolio basics
INSERT INTO portfolio_basics (name, label, image, summary, summary_tr, email, city, country_code, website) VALUES 
(
    'Naquib Hassan', 
    'Software Developer', 
    'ILLSOUL.jpg', 
    'Passionate software developer with expertise in modern web technologies, mobile development, and numerical analysis. Experienced in developing Android applications, web solutions, and contributing to open-source projects. Strong background in computer science with a focus on creating efficient and user-friendly applications.',
    'Software Developer alanında gelişimim devam ediyor. Modern web teknolojileri, mobil geliştirme ve sayısal analiz konularında uzmanım. Android uygulamaları, web çözümleri geliştirmekte ve açık kaynak projelere katkıda bulunmaktayım.',
    'naquib.hassan@example.com', 
    'Dhaka', 
    'BD', 
    '#'
);

-- Insert social profiles
INSERT INTO social_profiles (platform, username, url, display_order) VALUES 
('twitter', 'naquib_dev', 'https://twitter.com/naquib_dev', 1),
('github', 'ill-soul077', 'https://github.com/ill-soul077', 2),
('linkedin', 'naquib-hassan', 'https://www.linkedin.com/in/naquib-hassan', 3);

-- Insert comprehensive skills data
INSERT INTO skills (situation, keywords, level, proficiency_percentage, years_experience, display_order) VALUES 
(
    'Programming Languages', 
    '["JavaScript", "Python", "Java", "PHP", "TypeScript", "C++"]', 
    'Expert', 
    90, 
    4, 
    1
),
(
    'Frontend Development', 
    '["React.js", "Vue.js", "HTML5", "CSS3", "Bootstrap", "Tailwind CSS"]', 
    'Advanced', 
    85, 
    3, 
    2
),
(
    'Backend Development', 
    '["Node.js", "Express.js", "Django", "Laravel", "REST APIs", "GraphQL"]', 
    'Advanced', 
    80, 
    3, 
    3
),
(
    'Database Technologies', 
    '["MySQL", "MongoDB", "PostgreSQL", "SQLite", "Redis"]', 
    'Intermediate', 
    75, 
    2, 
    4
),
(
    'Mobile Development', 
    '["Android Development", "React Native", "Flutter", "Ionic"]', 
    'Intermediate', 
    70, 
    2, 
    5
),
(
    'Tools & Technologies', 
    '["Git", "Docker", "AWS", "Jenkins", "Webpack", "Linux"]', 
    'Intermediate', 
    75, 
    2, 
    6
);

-- Insert projects/repositories
INSERT INTO repositories (name, explanation, tags, best_lang, date, link, github_url, is_featured, display_order) VALUES 
(
    'Android Project - Time Tracker',
    'A comprehensive time tracking application for Android devices with modern UI and efficient performance tracking capabilities.',
    '["android", "java", "sqlite", "material-design"]',
    'java',
    '2024-01-15',
    'https://github.com/ill-soul077/AndroidProject-Timetracker',
    'https://github.com/ill-soul077/AndroidProject-Timetracker',
    TRUE,
    1
),
(
    'Numerical Lab',
    'A collection of numerical analysis algorithms and mathematical computations implemented for educational and research purposes.',
    '["python", "mathematics", "algorithms", "numpy"]',
    'python',
    '2023-12-10',
    'https://github.com/ill-soul077/Numerical-Lab',
    'https://github.com/ill-soul077/Numerical-Lab',
    TRUE,
    2
),
(
    'Portfolio Website',
    'Personal portfolio website showcasing projects, skills, and professional experience with responsive design and interactive features.',
    '["html", "css", "javascript", "responsive", "portfolio"]',
    'javascript',
    '2024-09-01',
    'https://github.com/ill-soul077/Portfolio',
    'https://github.com/ill-soul077/Portfolio',
    TRUE,
    3
),
(
    'Web Development Projects',
    'Collection of various web development projects including e-commerce sites, blogs, and interactive applications.',
    '["php", "mysql", "javascript", "bootstrap", "web-development"]',
    'php',
    '2023-08-20',
    '#',
    '#',
    FALSE,
    4
);

-- Insert work experience
INSERT INTO work_experience (position, company_name, start_date, end_date, location, summary, technologies, is_current, display_order) VALUES 
(
    'Software Developer',
    'Tech Solutions Ltd.',
    '2023-01',
    'Present',
    'Dhaka, Bangladesh',
    'Developing web and mobile applications using modern technologies. Collaborated with cross-functional teams to deliver high-quality software solutions. Implemented automated testing and deployment processes.',
    '["React.js", "Node.js", "MySQL", "Docker", "AWS"]',
    TRUE,
    1
),
(
    'Junior Developer',
    'Digital Innovations Inc.',
    '2022-06',
    '2022-12',
    'Dhaka, Bangladesh',
    'Worked on frontend development using React.js and backend APIs. Participated in code reviews and contributed to improving development processes.',
    '["React.js", "JavaScript", "HTML5", "CSS3", "Git"]',
    FALSE,
    2
);

-- Insert education
INSERT INTO education (institution, area, study_type, start_date, end_date, gpa, achievement, location, is_current, display_order) VALUES 
(
    'Khulna University of Engineering and Technology (KUET)',
    'Computer Science & Engineering',
    'B.Sc.',
    '2023',
    'present',
    '3.72',
    'Dean\'s Award recipient',
    'Khulna, Bangladesh',
    TRUE,
    1
),
(
    'Notre Dame College, Dhaka',
    'Science',
    'Higher Secondary Certificate (HSC)',
    '2019',
    '2022',
    '5.00',
    'Perfect GPA',
    'Dhaka, Bangladesh',
    FALSE,
    2
),
(
    'Cox\'s Bazar Government High School',
    'Science',
    'Secondary School Certificate (SSC)',
    '2014',
    '2019',
    '5.00',
    'Perfect GPA',
    'Cox\'s Bazar, Bangladesh',
    FALSE,
    3
);

-- Insert academic highlights
INSERT INTO academic_highlights (title, description, date, category, display_order) VALUES 
('B.Sc. in Computer Science & Engineering', 'Currently pursuing Bachelor\'s degree at KUET with focus on software development and algorithms', '2023-present', 'degree', 1),
('CGPA: 3.72 (Current)', 'Maintaining excellent academic performance throughout the program', '2023-present', 'grade', 2),
('Dean\'s Award Recipient', 'Recognized for outstanding academic achievement and consistent performance', '2024', 'award', 3),
('Higher Secondary Certificate (HSC)', 'Achieved perfect GPA of 5.00 in Science group', '2022', 'grade', 4),
('Secondary School Certificate (SSC)', 'Achieved perfect GPA of 5.00 in Science group', '2019', 'grade', 5);

-- Insert interests
INSERT INTO interests (interest, description, display_order) VALUES 
('Web Development', 'Creating modern, responsive websites and web applications', 1),
('Mobile App Development', 'Building native and cross-platform mobile applications', 2),
('Machine Learning', 'Exploring AI/ML algorithms and their practical applications', 3),
('Open Source Contributing', 'Contributing to open source projects and community development', 4),
('Technology Blogging', 'Writing about latest technologies and development practices', 5),
('Gaming', 'Playing strategic and puzzle games for relaxation', 6),
('Photography', 'Capturing moments and exploring creative photography techniques', 7);

-- Insert site settings
INSERT INTO site_settings (setting_key, setting_value, description) VALUES 
('site_title', 'Naquib Hassan - Portfolio', 'Main title of the portfolio website'),
('site_description', 'Software Developer Portfolio showcasing projects, skills, and experience', 'Meta description for SEO'),
('contact_email', 'naquib.hassan@example.com', 'Primary contact email'),
('theme_color', '#041C32', 'Primary theme color'),
('secondary_color', '#04293A', 'Secondary theme color'),
('accent_color', '#ECB365', 'Accent color for highlights'),
('enable_contact_form', 'true', 'Enable/disable contact form'),
('enable_blog', 'false', 'Enable/disable blog section'),
('analytics_id', '', 'Google Analytics tracking ID'),
('social_sharing', 'true', 'Enable social media sharing buttons');

-- ===============================================
-- SAMPLE CONTACT MESSAGES
-- ===============================================

INSERT INTO contact_messages (name, email, subject, message, ip_address) VALUES 
('John Doe', 'john.doe@example.com', 'Project Inquiry', 'Hi Naquib, I came across your portfolio and I\'m impressed with your work. I have a project that might interest you.', '192.168.1.100'),
('Sarah Smith', 'sarah.smith@company.com', 'Job Opportunity', 'Hello, we have an exciting opportunity at our company and would like to discuss it with you.', '192.168.1.101'),
('Mike Johnson', 'mike@startup.com', 'Collaboration', 'Love your Android project! Would you be interested in collaborating on a similar project?', '192.168.1.102');

-- ===============================================
-- CREATE VIEWS FOR EASIER DATA ACCESS
-- ===============================================

-- View for active skills with formatted data
CREATE VIEW v_active_skills AS
SELECT 
    id,
    situation,
    keywords,
    level,
    proficiency_percentage,
    years_experience,
    display_order
FROM skills 
WHERE is_active = TRUE 
ORDER BY display_order ASC;

-- View for featured projects
CREATE VIEW v_featured_projects AS
SELECT 
    id,
    name,
    explanation,
    tags,
    best_lang,
    date,
    link,
    github_url,
    demo_url
FROM repositories 
WHERE is_featured = TRUE 
ORDER BY display_order ASC;

-- View for current work experience
CREATE VIEW v_current_work AS
SELECT 
    position,
    company_name,
    start_date,
    location,
    summary,
    technologies
FROM work_experience 
WHERE is_current = TRUE;

-- View for unread contact messages
CREATE VIEW v_unread_messages AS
SELECT 
    id,
    name,
    email,
    subject,
    message,
    created_at
FROM contact_messages 
WHERE is_read = FALSE 
ORDER BY created_at DESC;

-- ===============================================
-- STORED PROCEDURES
-- ===============================================

DELIMITER //

-- Procedure to get portfolio summary data
CREATE PROCEDURE GetPortfolioSummary()
BEGIN
    SELECT 
        (SELECT COUNT(*) FROM skills WHERE is_active = TRUE) as total_skills,
        (SELECT COUNT(*) FROM repositories WHERE is_featured = TRUE) as featured_projects,
        (SELECT COUNT(*) FROM work_experience) as work_experiences,
        (SELECT COUNT(*) FROM education) as education_records,
        (SELECT COUNT(*) FROM contact_messages WHERE is_read = FALSE) as unread_messages;
END //

-- Procedure to mark message as read
CREATE PROCEDURE MarkMessageAsRead(IN message_id INT)
BEGIN
    UPDATE contact_messages 
    SET is_read = TRUE, updated_at = CURRENT_TIMESTAMP 
    WHERE id = message_id;
END //

DELIMITER ;

-- ===============================================
-- INDEXES FOR PERFORMANCE
-- ===============================================

-- Additional indexes for better performance
CREATE INDEX idx_skills_active ON skills(is_active, display_order);
CREATE INDEX idx_repos_featured ON repositories(is_featured, display_order);
CREATE INDEX idx_work_current ON work_experience(is_current, display_order);
CREATE INDEX idx_education_current ON education(is_current, display_order);
CREATE INDEX idx_messages_status ON contact_messages(is_read, created_at);

-- ===============================================
-- GRANTS AND PERMISSIONS
-- ===============================================

-- Create a user for the application (optional)
-- CREATE USER 'portfolio_user'@'localhost' IDENTIFIED BY 'secure_password_here';
-- GRANT SELECT, INSERT, UPDATE, DELETE ON portfolio_db.* TO 'portfolio_user'@'localhost';
-- FLUSH PRIVILEGES;

-- ===============================================
-- DATABASE SETUP COMPLETE
-- ===============================================

