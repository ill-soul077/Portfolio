# Portfolio Website with Admin Panel

# Naquib Hassan - Portfolio Website

A modern, responsive portfolio website showcasing professional experience, education, and projects.

## 🚀 Features

- **Responsive Design**: Works perfectly on all devices
- **Dynamic Content**: JSON-driven content management
- **Admin Dashboard**: Update portfolio content through admin panel
- **Multiple Themes**: Switch between different visual themes
- **Education Management**: Display education history and achievements
- **Project Showcase**: Display repositories with QR codes and links

## 📁 Project Structure

```
/
├── admin/                  # Admin panel files
│   ├── auth.php           # Authentication logic
│   ├── auth_check.php     # Auth middleware
│   ├── dashboard.php      # Admin dashboard
│   ├── generate_resume.php # Resume generation
│   ├── logout.php         # Logout functionality
│   ├── manage_basics.php  # Basic info management
│   ├── manage_education.php # Education management
│   └── sidebar.php        # Admin sidebar component
├── assets/                 # Static assets
│   ├── css/               # Stylesheets
│   │   ├── all.min.css    # Font Awesome
│   │   ├── css2.css       # Google Fonts
│   │   ├── style6.css     # Main theme
│   │   └── styleMain.css  # Base styles
│   ├── images/            # Images and media
│   │   ├── ILLSOUL.jpg    # Profile picture
│   │   └── *.png          # Other images
│   └── js/                # JavaScript files
│       └── script.js      # Main application script
├── config/                 # Configuration files
│   └── database.php       # Database configuration
├── docs/                   # Documentation
│   ├── database_setup.sql # Database schema
│   └── README.md          # This file
├── includes/               # PHP includes and utilities
│   ├── update_academic_highlights.php
│   ├── update_education.php
│   └── update_resume_data.php
├── admin_login.php         # Admin login page
├── create_admin.php        # Admin user creation
├── Naquib.htm             # Main portfolio page
├── resume.json            # Portfolio data (JSON format)
└── test_db.php            # Database connection test
```

## 🛠️ Installation & Setup

### Prerequisites
- XAMPP with Apache and MySQL
- PHP 7.4 or higher
- MySQL/MariaDB

### Installation Steps

1. **Clone/Download** the project to your XAMPP htdocs folder
2. **Start XAMPP** services (Apache + MySQL on port 3306)
3. **Create Database**: Import `docs/database_setup.sql` via phpMyAdmin
4. **Configure Database**: Ensure `config/database.php` uses correct settings
5. **Access Portfolio**: Open `http://localhost/f/Naquib.htm`

### Admin Access
- **URL**: `http://localhost/f/admin_login.php`
- **Username**: `admin`
- **Password**: `admin123`

## 🎓 Education Information

The portfolio displays education from:
- **Khulna University of Engineering and Technology (KUET)**
  - B.Sc. in Computer Science & Engineering (2023-present)
  - CGPA: 3.72 | Dean's Award recipient

- **Notre Dame College, Dhaka**
  - Higher Secondary Certificate (HSC) (2019-2022)
  - GPA: 5.00

- **Cox's Bazar Government High School**
  - Secondary School Certificate (SSC) (2014-2019)
  - GPA: 5.00

## 🔧 Configuration

### Database Settings
```php
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'portfolio_db');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### File Paths
- Main portfolio: `Naquib.htm`
- Admin panel: `admin_login.php`
- Database test: `test_db.php`

## 📊 Admin Features

- ✅ **Education Management**: Add/edit/delete education entries
- ✅ **Academic Highlights**: Manage achievements and awards
- ✅ **Basic Information**: Update personal details
- ✅ **Real-time Updates**: Changes reflect immediately on portfolio

## 🎨 Themes

The portfolio includes multiple visual themes accessible via the theme switcher:
- Default theme (style6.css)
- Alternative themes (style2-5.css)

## 🔒 Security

- Password-protected admin area
- Session-based authentication
- SQL injection prevention with prepared statements
- XSS protection

## 📱 Responsive Design

- Mobile-first approach
- Tablet and desktop optimized
- Touch-friendly navigation
- Cross-browser compatibility

## 🚀 Performance

- Optimized images
- Minified CSS/JS
- Fast JSON data loading
- Efficient database queries

---

**Developed by Naquib Hassan** | [GitHub](https://github.com/ill-soul077) | [LinkedIn](https://linkedin.com/in/naquib-hassan)

## Features

- **Frontend**: Responsive portfolio website with dynamic content loading
- **Backend**: PHP/MySQL admin panel for content management
- **Authentication**: Secure admin login with session management and 7-day remember me cookies
- **Profile Picture**: Uses ILLSOUL.jpg as the profile image
- **Dynamic Content**: All portfolio data is stored in MySQL and can be updated via admin panel

## Setup Instructions

### Prerequisites
- XAMPP with Apache and MySQL
- MySQL running on port 4306

### Database Setup

1. Start XAMPP and ensure MySQL is running on port 4306
2. Open phpMyAdmin or MySQL command line
3. Run the SQL script in `database_setup.sql` to create the database and tables
4. The script will create a default admin user:
   - Username: `admin`
   - Password: `admin123`

### File Structure
```
portfolio/
├── Naquib.htm                # Main portfolio page
├── resume.json                # Portfolio data (auto-generated from DB)
├── ILLSOUL.jpg               # Profile picture
├── admin_login.php           # Admin login page
├── database_setup.sql        # Database creation script
├── config/
│   └── database.php          # Database configuration
└── admin/
    ├── auth.php              # Authentication handler
    ├── auth_check.php        # Auth verification helper
    ├── dashboard.php         # Admin dashboard
    ├── manage_basics.php     # Basic info management
    ├── generate_resume.php   # JSON generator
    ├── logout.php            # Logout handler
    └── sidebar.php           # Navigation sidebar
```

### Access Points

1. **Portfolio Website**: `Naquib.htm`
2. **Admin Login**: `admin_login.php`
3. **Admin Dashboard**: `admin/dashboard.php` (after login)

### Admin Panel Features

- **Dashboard**: Overview of portfolio data
- **Basic Info Management**: Edit personal information, contact details
- **Skills Management**: Add/edit/delete skill categories
- **Projects Management**: Manage repository/project listings
- **Work Experience**: Add/edit work history
- **Education Management**: Update education details
- **Interests Management**: Manage interests list
- **JSON Generator**: Auto-generate resume.json from database

### Database Configuration

Edit `config/database.php` to match your MySQL settings:

```php
define('DB_HOST', 'localhost');
define('DB_PORT', '4306');        // Your MySQL port
define('DB_NAME', 'portfolio_db');
define('DB_USER', 'root');        // Your MySQL username
define('DB_PASS', '');            // Your MySQL password
```

### Security Features

- Password hashing using PHP's `password_hash()`
- Session management for admin authentication
- 7-day remember me cookies with secure validation
- CSRF protection through session verification
- SQL injection prevention using prepared statements

### Usage

1. Visit the portfolio website to see your public portfolio
2. Click "Admin" in the navigation to access the admin login
3. Login with username: `admin`, password: `admin123`
4. Use the admin dashboard to manage all portfolio content
5. Click "Generate Resume JSON" to update the portfolio data
6. All changes are immediately reflected in the frontend

### Customization

- Replace `ILLSOUL.jpg` with your own profile picture
- Update the default data in `database_setup.sql` before running it
- Modify the CSS styles in the admin panel to match your preferences
- Add more management pages for additional data types as needed

### Troubleshooting

1. **Database Connection Issues**: Check MySQL port and credentials in `config/database.php`
2. **Login Issues**: Verify the admin user exists in the database
3. **Permission Issues**: Ensure write permissions for `resume.json` file
4. **Session Issues**: Check PHP session configuration in your server

## Default Admin Credentials

- **Username**: admin
- **Password**: admin123

*Please change the default password after first login for security.*
