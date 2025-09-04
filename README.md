# 🌟 Hassan Naquib's Portfolio

A modern, dynamic portfolio website built with PHP, MySQL, HTML5, CSS3, and JavaScript. Features a comprehensive admin dashboard for content management and a responsive, interactive frontend.

## 📋 Table of Contents

- [Features](#features)
- [Technology Stack](#technology-stack)
- [Project Structure](#project-structure)
- [Installation](#installation)
- [Usage](#usage)
- [Admin Dashboard](#admin-dashboard)
- [API Endpoints](#api-endpoints)
- [Database Schema](#database-schema)
- [License](#license)

## ✨ Features

### Frontend Features
- **Interactive Typewriter Effect** - Dynamic text animation showing "Software Developer" and "Competitive Programmer"
- **Responsive Design** - Mobile-first approach with smooth animations
- **Social Media Integration** - GitHub, LinkedIn, Twitter/X, Codeforces profiles
- **Dynamic Content Loading** - API-driven content with fallback to JSON
- **Smooth Animations** - CSS transitions and hover effects
- **Modern UI/UX** - Clean, professional design with golden accent colors

### Backend Features
- **Complete Admin Dashboard** - Full CRUD operations for all content
- **Database-Driven** - MySQL backend with proper relationships
- **RESTful API** - JSON endpoints for dynamic content loading
- **Security Features** - SQL injection protection, input validation
- **Session Management** - Secure admin authentication system

### Content Management
- **Basic Information** - Personal details, contact info, summary
- **Skills Management** - Programming languages, frameworks, tools
- **Project Portfolio** - GitHub repositories with live demos
- **Work Experience** - Professional and co-curricular activities
- **Education Records** - Academic background and achievements
- **Achievements System** - Awards, competitions, certifications
- **Academic Highlights** - Research, publications, honors
- **Interests** - Personal hobbies and activities
- **Social Profiles** - Professional and social media links
- **Contact Messages** - Visitor inquiries management

## 🛠️ Technology Stack

### Frontend
- **HTML5** - Semantic markup with modern standards
- **CSS3** - Custom styling with flexbox/grid layouts
- **JavaScript** - Vanilla JS for dynamic interactions
- **Font Awesome** - Professional icon library
- **Google Fonts** - Custom typography

### Backend
- **PHP 7.4+** - Server-side scripting
- **MySQL 8.0** - Relational database management
- **PDO** - Database abstraction layer for security

### Development Tools
- **XAMPP** - Local development environment
- **Git** - Version control system

## 📁 Project Structure

```
Portfolio/
├── 📁 admin/                    # Admin Dashboard
│   ├── auth.php                 # Authentication logic
│   ├── auth_check.php           # Session validation
│   ├── dashboard.php            # Main admin dashboard
│   ├── manage_basics.php        # Basic info management
│   ├── manage_skills.php        # Skills management
│   ├── manage_projects.php      # Projects management
│   ├── manage_work.php          # Work experience management
│   ├── manage_education.php     # Education management
│   ├── manage_achievements.php  # Achievements management
│   ├── manage_highlights.php    # Academic highlights management
│   ├── manage_interests.php     # Interests management
│   ├── manage_social.php        # Social profiles management
│   ├── manage_messages.php      # Contact messages management
│   ├── sidebar.php              # Navigation sidebar
│   └── logout.php               # Logout functionality
├── 📁 api/                      # API Endpoints
│   └── portfolio.php            # Main portfolio data API
├── 📁 assets/                   # Static Assets
│   ├── 📁 css/
│   │   └── style6.css           # Main stylesheet
│   ├── 📁 js/
│   │   └── script.js            # Interactive functionality
│   └── 📁 images/               # Image assets
├── 📁 config/                   # Configuration
│   └── database.php             # Database connection
├── admin_login.php              # Admin login page
├── contact_handler.php          # Contact form processor
├── Naquib.htm                   # Main portfolio page
├── resume.json                  # Fallback data source
├── portfolio_database_new.sql   # Database schema
├── DATABASE_SETUP.md            # Database setup guide
└── README.md                    # This file
```

## 🚀 Installation

### Prerequisites
- XAMPP (Apache + MySQL + PHP)
- Web browser (Chrome, Firefox, Safari, Edge)

### Setup Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/ill-soul077/Portfolio.git
   cd Portfolio
   ```

2. **Setup XAMPP**
   - Install XAMPP and start Apache & MySQL services
   - Place project in `C:\xampp\htdocs\f\` (or appropriate web directory)

3. **Database Setup**
   - Import `portfolio_database_new.sql` into MySQL
   - Or follow detailed instructions in `DATABASE_SETUP.md`

4. **Configure Database**
   - Update database credentials in `config/database.php` if needed
   - Default settings work with standard XAMPP installation

5. **Access the Portfolio**
   - Frontend: `http://localhost/f/Naquib.htm`
   - Admin: `http://localhost/f/admin_login.php`

### Default Admin Credentials
- **Username**: `admin`
- **Password**: `admin123`

## 💻 Usage

### Viewing the Portfolio
Navigate to `http://localhost/f/Naquib.htm` to view the complete portfolio with:
- Interactive typewriter effect
- Responsive navigation
- Dynamic content sections
- Contact form functionality

### Admin Dashboard Access
1. Go to `http://localhost/f/admin_login.php`
2. Login with admin credentials
3. Access comprehensive content management system

## 🎛️ Admin Dashboard

The admin dashboard provides complete control over portfolio content:

### Dashboard Overview
- **Statistics Cards** - Quick overview of all content counts
- **Navigation Sidebar** - Easy access to all management sections
- **Recent Messages** - Latest contact form submissions

### Content Management Sections

#### Basic Information
- Personal details (name, email, location)
- Professional summary
- Profile image management
- Contact information

#### Skills Management
- Programming languages and frameworks
- Proficiency levels and experience years
- Skill categorization and ordering

#### Projects Portfolio
- GitHub repository integration
- Project descriptions and technologies
- Live demo links and screenshots
- Featured project highlighting

#### Work Experience
- Professional positions and companies
- Co-curricular activities and leadership roles
- Achievement highlights and technologies used
- Timeline management

#### Education Records
- Academic institutions and degrees
- GPA tracking and achievements
- Course highlights and descriptions

#### Achievements System
- Awards and recognitions
- Competition results
- Certification management
- Category-based organization

#### Academic Highlights
- Research projects and publications
- Academic honors and distinctions
- Special recognitions

#### Interests Management
- Personal hobbies and activities
- Interest descriptions
- Visual preview of portfolio display

#### Social Profiles
- Professional and social media links
- Platform-specific icons and colors
- Active/inactive status management

#### Contact Messages
- Visitor inquiry management
- Message reading and response tracking
- Contact information extraction

## 🔌 API Endpoints

### Portfolio Data API
**Endpoint**: `GET /api/portfolio.php`

**Response Structure**:
```json
{
  "meta": {
    "theme": "actual"
  },
  "basics": {
    "name": "Hassan Naquib",
    "label": "Software Developer & Competitive Programmer",
    "email": "hassannaquib014@gmail.com",
    "summary": "...",
    "location": {
      "city": "Khulna",
      "countryCode": "BD"
    },
    "profiles": {
      "GitHub": {
        "username": "ill-soul077",
        "url": "https://github.com/ill-soul077"
      },
      "Codeforces": {
        "username": "ill.soul",
        "url": "https://codeforces.com/profile/ill.soul"
      }
    }
  },
  "skills": [...],
  "repository": [...],
  "work": [...],
  "education": [...],
  "achievements": [...],
  "academic_highlights": [...],
  "interests": [...]
}
```

## 🗄️ Database Schema

### Core Tables
- **portfolio_basics** - Personal information and contact details
- **skills** - Programming skills and proficiency levels
- **repositories** - Project portfolio and GitHub integration
- **work_experience** - Professional and co-curricular experience
- **education** - Academic background and achievements
- **achievements** - Awards, competitions, and certifications
- **academic_highlights** - Research and academic distinctions
- **interests** - Personal hobbies and activities
- **social_profiles** - Social media and professional profiles
- **contact_messages** - Visitor inquiries and contact form submissions

### System Tables
- **admin_users** - Admin authentication and user management
- **site_settings** - Application configuration settings

### Views
- **v_active_skills** - Active skills with proficiency data
- **v_current_work** - Current employment information
- **v_featured_projects** - Highlighted portfolio projects
- **v_unread_messages** - New contact form submissions

## 🚀 Deployment

For production deployment:

1. **Server Requirements**
   - PHP 7.4 or higher
   - MySQL 5.7 or higher
   - Apache/Nginx web server

2. **Security Considerations**
   - Change default admin credentials
   - Update database connection settings
   - Enable HTTPS
   - Configure proper file permissions

3. **Performance Optimization**
   - Enable PHP OPcache
   - Configure MySQL query cache
   - Optimize images and assets
   - Set up proper caching headers

## 🤝 Contributing

This is a personal portfolio project. For suggestions or improvements:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## 📧 Contact

- **Name**: Hassan Naquib
- **Email**: hassannaquib014@gmail.com
- **GitHub**: [@ill-soul077](https://github.com/ill-soul077)
- **Codeforces**: [@ill.soul](https://codeforces.com/profile/ill.soul)
- **Twitter/X**: [@illsoul058](https://x.com/illsoul058)

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

---


