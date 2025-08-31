# Portfolio Website

A dynamic portfolio website with admin dashboard built using PHP, MySQL, JavaScript, and modern web technologies.

## 🚀 Features

### Frontend
- **Responsive Design**: Clean, modern interface that works on all devices
- **Typewriter Effect**: Animated text showing "Software Developer" and "Competitive Programmer"
- **Dynamic Content**: All content loaded from database via API
- **Skills Showcase**: Organized skill categories with styled tags
- **Social Links**: Direct links to GitHub, LinkedIn, and Twitter profiles

### Admin Dashboard
- **Complete CRUD Operations**: Manage all portfolio content
- **Skills Management**: Add/edit/delete skill categories and keywords
- **Project Management**: Manage repositories and project information
- **Work Experience**: Add and update work history
- **Education**: Manage educational background
- **Basic Info**: Update personal information and contact details

### Technical Features
- **API-Driven**: RESTful API for data management
- **Database Integration**: MySQL/MariaDB with PDO
- **Admin Authentication**: Secure login system
- **Responsive CSS**: Mobile-first design approach
- **Error Handling**: Robust error handling and fallback mechanisms

## 🛠️ Technology Stack

- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Backend**: PHP 8.1+, MySQL/MariaDB
- **Database**: PDO for secure database operations
- **Server**: Apache (XAMPP)
- **Version Control**: Git

## 📁 Project Structure

```
Portfolio/
├── admin/                 # Admin dashboard files
│   ├── dashboard.php     # Main admin dashboard
│   ├── manage_skills.php # Skills management
│   ├── manage_projects.php # Project management
│   └── ...
├── api/                  # API endpoints
│   └── portfolio.php     # Main API endpoint
├── assets/              # Static assets
│   ├── css/             # Stylesheets
│   ├── js/              # JavaScript files
│   └── images/          # Image assets
├── config/              # Configuration files
│   └── database.php     # Database connection
├── Naquib.htm          # Main portfolio page
└── admin_login.php     # Admin login
```

## 🚀 Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/ill-soul077/Portfolio.git
   ```

2. **Setup XAMPP**:
   - Install XAMPP with Apache and MySQL
   - Place project in `C:\xampp\htdocs\`

3. **Database Setup**:
   - Import `database_setup.sql` into MySQL
   - Update database credentials in `config/database.php`

4. **Admin Setup**:
   - Run `create_admin.php` to set up admin credentials
   - Access admin panel at `/admin_login.php`

## 📊 Database Schema

- **portfolio_basics**: Personal information and contact details
- **skills**: Skill categories and keywords (JSON format)
- **repositories**: Project and repository information
- **work_experience**: Employment history
- **education**: Educational background
- **social_profiles**: Social media links
- **academic_highlights**: Academic achievements

## 🎨 Features Showcase

### Typewriter Effect
- Animated typing of role descriptions
- Smooth character-by-character animation
- Customizable text content

### Skills Management
- Categorized skill display
- Tag-based keyword system
- Admin CRUD operations

### Responsive Design
- Mobile-first approach
- Flexible grid layouts
- Cross-browser compatibility

## 🔧 Configuration

### Database Configuration
Update `config/database.php` with your database credentials:
```php
$host = 'localhost';
$port = '3306';
$dbname = 'portfolio_db';
$username = 'your_username';
$password = 'your_password';
```

### API Endpoints
- `GET /api/portfolio.php` - Retrieve all portfolio data
- Admin endpoints for CRUD operations

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Open a Pull Request

## 📝 License

This project is open source and available under the [MIT License](LICENSE).

## 👨‍💻 Developer

**Hassan Mohammed Naquibul Hoque**
- GitHub: [@ill-soul077](https://github.com/ill-soul077)
- LinkedIn: [HASSAN](https://www.linkedin.com/in/hassan-mohammed-naquibul-hoque-1b11701b6)

---

⭐ Star this repository if you found it helpful!
