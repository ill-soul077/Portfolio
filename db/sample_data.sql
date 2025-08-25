-- Sample data for portfolio
-- Run this after creating the schema

-- Insert default admin (password: changeMe123)
INSERT INTO admin (username, password_hash, tagline, about_me) VALUES 
(
  'naquib_admin', 
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
  'Aspiring software engineer with mediocore problem-solving skills in algorithms and a strong enthusiasm for technology, eager to contribute to impactful projects in software engineering and research.',
  'I am Hassan Mohammed Naquibul Hoque, a dedicated Computer Science and Engineering student at Khulna University of Engineering & Technology (KUET). With a strong foundation in programming languages like C, C++, Java, and Python, I am passionate about developing innovative solutions that address real-world challenges.\n\nMy journey in competitive programming has earned me the Specialist rank on Codeforces with a maximum rating of 1448, reflecting my analytical thinking and problem-solving capabilities. I have hands-on experience in web development, mobile app development, and database management, having worked on projects ranging from Android applications to numerical computation systems.\n\nI am always eager to learn new technologies and collaborate on impactful projects that can make a difference in the tech industry.'
);

-- Insert sample projects
INSERT INTO projects (title, description, tech_stack, github_link, demo_link) VALUES 
(
  'Android Time Tracker App',
  'Designed and developed an Android application to track time usage efficiently. The app features a user-friendly interface with intuitive navigation and implements database-backed storage for session logging. Users can categorize activities, view detailed reports, and analyze their time management patterns through comprehensive data visualization.',
  'Android, Java, SQLite, Room Database, Material Design',
  'https://github.com/ill-soul077/AndroidProject-Timetracker',
  NULL
),
(
  'Console Application Development Using Numerical Methods',
  'Implemented various numerical algorithms including Gauss-Seidel method, Newton-Raphson method, and Runge-Kutta methods in C++ to solve linear and non-linear equations as well as ordinary differential equations. The console application provides accurate computational solutions with error analysis and convergence monitoring.',
  'C++, Numerical Methods, Algorithms, Mathematical Computing',
  'https://github.com/ill-soul077/Numerical-Lab',
  NULL
);

-- Insert skills
INSERT INTO skills (skill_name, skill_category, proficiency_level) VALUES 
('C', 'Programming', 'Advanced'),
('C++', 'Programming', 'Advanced'),
('Java', 'Programming', 'Intermediate'),
('JavaScript', 'Programming', 'Intermediate'),
('Python', 'Programming', 'Intermediate'),
('PHP', 'Web', 'Intermediate'),
('HTML', 'Web', 'Advanced'),
('CSS', 'Web', 'Advanced'),
('ASP.NET', 'Web', 'Beginner'),
('Pandas', 'Programming', 'Intermediate'),
('NumPy', 'Programming', 'Intermediate'),
('Matplotlib', 'Programming', 'Intermediate'),
('Android', 'Programming', 'Intermediate'),
('iOS', 'Programming', 'Beginner'),
('Oracle', 'Database', 'Intermediate'),
('Firebase', 'Database', 'Intermediate'),
('Git/GitHub', 'Tools', 'Advanced');

-- Insert achievements
INSERT INTO achievements (title, description, date_achieved, category) VALUES 
(
  'Codeforces Specialist',
  'Achieved Specialist rank on Codeforces competitive programming platform with a maximum rating of 1448, demonstrating strong problem-solving skills and algorithmic thinking.',
  '2024-01-01',
  'Competitive Programming'
),
(
  'Dean\'s Award - KUET',
  'Received Dean\'s Award for academic excellence and outstanding performance in Computer Science and Engineering at Khulna University of Engineering & Technology.',
  '2023-12-01',
  'Academic Achievement'
);
