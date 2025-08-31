-- Portfolio Database Setup
-- Run this script to create the database and tables

CREATE DATABASE IF NOT EXISTS portfolio_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE portfolio_db;

-- Admin users table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Portfolio data table (for basics information)
CREATE TABLE IF NOT EXISTS portfolio_basics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    label VARCHAR(100),
    image VARCHAR(255),
    summary TEXT,
    email VARCHAR(100),
    city VARCHAR(50),
    country_code VARCHAR(5),
    website VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Social profiles table
CREATE TABLE IF NOT EXISTS social_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    platform VARCHAR(50) NOT NULL,
    username VARCHAR(100),
    url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Skills table
CREATE TABLE IF NOT EXISTS skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    situation VARCHAR(100) NOT NULL,
    keywords JSON,
    level VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Repository/Projects table
CREATE TABLE IF NOT EXISTS repositories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    explanation TEXT,
    tags JSON,
    best_lang VARCHAR(50),
    date DATE,
    link VARCHAR(255),
    view_link VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Work experience table
CREATE TABLE IF NOT EXISTS work_experience (
    id INT AUTO_INCREMENT PRIMARY KEY,
    position VARCHAR(100) NOT NULL,
    company_name VARCHAR(100) NOT NULL,
    start_date VARCHAR(50),
    end_date VARCHAR(50),
    location VARCHAR(100),
    summary TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Education table
CREATE TABLE IF NOT EXISTS education (
    id INT AUTO_INCREMENT PRIMARY KEY,
    institution VARCHAR(200) NOT NULL,
    area VARCHAR(100),
    study_type VARCHAR(50),
    start_date VARCHAR(10),
    end_date VARCHAR(10),
    gpa VARCHAR(20),
    achievement VARCHAR(200),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Academic highlights table
CREATE TABLE IF NOT EXISTS academic_highlights (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Interests table
CREATE TABLE IF NOT EXISTS interests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    interest VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO admin_users (username, password, email) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@portfolio.com');

-- Insert default portfolio data based on Naquib's CV
INSERT INTO portfolio_basics (name, label, image, summary, email, city, country_code, website) VALUES 
('Naquib Hassan', 'Software Developer', 'ILLSOUL.jpg', 'Passionate software developer with expertise in modern web technologies and mobile development.', 'naquib@example.com', 'Dhaka', 'BD', '#');

-- Insert social profiles
INSERT INTO social_profiles (platform, username, url) VALUES 
('twitter', 'naquib_dev', 'https://twitter.com/naquib_dev'),
('github', 'ill-soul077', 'https://github.com/ill-soul077'),
('linkedin', 'naquib-hassan', 'https://www.linkedin.com/in/naquib-hassan');

-- Insert default skills
INSERT INTO skills (situation, keywords) VALUES 
('Programming Languages', '["JavaScript", "Python", "Java", "PHP", "TypeScript"]'),
('Frontend Development', '["React.js", "Vue.js", "HTML5", "CSS3", "Bootstrap", "Tailwind CSS"]'),
('Backend Development', '["Node.js", "Express.js", "Django", "Laravel", "REST APIs"]'),
('Database Technologies', '["MySQL", "MongoDB", "PostgreSQL", "Redis"]'),
('Mobile Development', '["Android", "React Native", "Flutter"]'),
('Tools & Technologies', '["Git", "Docker", "AWS", "Jenkins", "Webpack"]');

-- Insert repositories from CV
INSERT INTO repositories (name, explanation, tags, best_lang, date, link, view_link) VALUES 
('Android Project - Time Tracker', 'A comprehensive time tracking application for Android devices with modern UI and efficient performance.', '["Android", "Java", "SQLite", "Material Design"]', 'Java', '2024-01-15', 'https://github.com/ill-soul077/AndroidProject-Timetracker', '#'),
('Numerical Lab', 'A collection of numerical analysis algorithms and mathematical computations implemented for educational purposes.', '["Python", "Mathematics", "Algorithms", "NumPy"]', 'Python', '2023-12-10', 'https://github.com/ill-soul077/Numerical-Lab', '#');

-- Insert work experience
INSERT INTO work_experience (position, company_name, start_date, end_date, location, summary) VALUES 
('Software Developer', 'Tech Solutions Ltd.', '2023-01', 'Present', 'Dhaka, Bangladesh', 'Developing web applications using modern technologies and maintaining existing systems.');

-- Insert education
INSERT INTO education (institution, area, study_type, start_date, end_date, gpa, achievement) VALUES 
('Khulna University of Engineering and Technology (KUET)', 'Computer Science & Engineering', 'B.Sc.', '2023', 'present', '3.72', 'Dean\'s Award recipient'),
('Notre Dame College, Dhaka', 'Science', 'Higher Secondary Certificate (HSC)', '2019', '2022', '5.00', ''),
('Cox\'s Bazar Government High School', 'Science', 'Secondary School Certificate (SSC)', '2014', '2019', '5.00', '');

-- Insert academic highlights
INSERT INTO academic_highlights (title, description) VALUES 
('B.Sc. in Computer Science & Engineering', 'Currently pursuing Bachelor\'s degree at KUET'),
('CGPA: 3.72 (till present)', 'Maintaining excellent academic performance'),
('Dean\'s Award recipient', 'Recognized for outstanding academic achievement'),
('Higher Secondary Certificate (HSC), GPA: 5.00', 'Perfect score in Higher Secondary examination'),
('Secondary School Certificate (SSC), GPA: 5.00', 'Perfect score in Secondary School examination');

-- Insert interests
INSERT INTO interests (interest) VALUES 
('Web Development'),
('Mobile App Development'),
('Machine Learning'),
('Open Source Contributing'),
('Technology Blogging'),
('Gaming');
