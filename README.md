# Naquib Portfolio - Complete Portfolio Website with Admin Panel

A responsive, modern portfolio website built with HTML, CSS, JavaScript, PHP, and MySQL. Features a Codeforces-inspired design with a full admin panel for content management.

## 🌟 Features

### Frontend
- **Single-page responsive design** with smooth scrolling navigation
- **Light/Dark theme toggle** with localStorage persistence
- **Animated sections** with intersection observer
- **Contact form** with client-side validation
- **Dynamic content loading** from database
- **Mobile-friendly** responsive design
- **Semantic HTML** with accessibility features

### Backend & Admin Panel
- **Secure admin authentication** with password hashing
- **CSRF protection** for all admin forms
- **Complete content management** for:
  - Profile information (tagline, about me, profile picture)
  - Projects (title, description, tech stack, links, images)
  - Skills (categorized with proficiency levels)
  - Achievements (with dates and categories)
  - Contact messages (view, mark as read, delete)
- **File upload handling** with validation
- **Rate limiting** for contact form
- **Session management** with timeout

### Security Features
- Password hashing with PHP's `password_hash()`
- Prepared statements to prevent SQL injection
- CSRF token validation
- File upload validation
- Input sanitization and validation
- Session regeneration on login
- Rate limiting for contact submissions

## 🚀 Quick Start with XAMPP

### Prerequisites
- XAMPP (includes Apache, MySQL, PHP)
- Web browser
- Text editor (optional, for customization)

### Installation Steps

1. **Download and Install XAMPP**
   - Download from [https://www.apachefriends.org/](https://www.apachefriends.org/)
   - Install and start Apache and MySQL services

2. **Setup Project Files**
   ```bash
   # Copy the portfolio folder to XAMPP's htdocs directory
   # Default location: C:\xampp\htdocs\portfolio
   ```

3. **Create Database**
   - Open phpMyAdmin: http://localhost/phpmyadmin
   - Create a new database named `portfolio_db`
   - Import the schema: Import `db/schema.sql`
   - Import sample data: Import `db/sample_data.sql`

4. **Configure Database Connection**
   - Open `config.php`
   - Update database credentials if needed (default works with XAMPP):
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     define('DB_NAME', 'portfolio_db');
     ```

5. **Set Permissions**
   - Ensure `uploads/` folder is writable by Apache
   - On Windows: Right-click uploads folder → Properties → Security → Give full control to "Users"

6. **Access the Portfolio**
   - Frontend: http://localhost/portfolio/
   - Admin Panel: http://localhost/portfolio/admin/login.php

## 🔐 Default Admin Credentials

**⚠️ IMPORTANT: Change these immediately after first login!**

- **Username:** `naquib_admin`
- **Password:** `changeMe123`

### First Login Steps:
1. Go to http://localhost/portfolio/admin/login.php
2. Log in with the default credentials above
3. Navigate to "Change Password" in the admin panel
4. Set a strong, unique password
5. Update your profile information

## 📁 Project Structure

```
portfolio/
├── index.php                 # Main portfolio page
├── config.php               # Database and app configuration
├── contact_submit.php        # Contact form handler
├── assets/
│   ├── css/
│   │   └── style.css        # Main stylesheet
│   └── js/
│       └── main.js          # Frontend JavaScript
├── admin/                   # Admin panel files
│   ├── login.php           # Admin login
│   ├── dashboard.php       # Admin dashboard
│   ├── profile.php         # Profile management
│   ├── projects.php        # Projects management
│   ├── skills.php          # Skills management
│   ├── achievements.php    # Achievements management
│   ├── messages.php        # Contact messages
│   ├── change_password.php # Password change
│   └── logout.php          # Logout handler
├── api/                    # API endpoints
│   ├── get_profile.php     # Get profile data
│   ├── get_projects.php    # Get projects
│   ├── get_skills.php      # Get skills
│   └── get_achievements.php # Get achievements
├── db/                     # Database files
│   ├── schema.sql          # Database structure
│   └── sample_data.sql     # Sample data
└── uploads/                # File uploads directory
```

## 🎨 Customization

### Colors and Themes
Edit CSS variables in `assets/css/style.css`:
```css
:root {
  --accent-primary: #3d7cce;    /* Primary color */
  --accent-hover: #2b6cb0;      /* Hover color */
  --bg-primary: #f9f9f9;        /* Background */
  --text-primary: #333333;      /* Text color */
}
```

### Content Management
Use the admin panel to update:
- **Profile:** Tagline, about me, profile picture
- **Projects:** Add/edit/delete projects with images and links
- **Skills:** Organize by categories with proficiency levels
- **Achievements:** Add accomplishments with dates
- **Messages:** View and manage contact form submissions

### Adding New Sections
1. Add HTML section to `index.php`
2. Add corresponding styles to `style.css`
3. Update navigation in the header
4. Add JavaScript functionality if needed

## 🔧 Configuration Options

### File Upload Settings
In `config.php`:
```php
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp']);
```

### Session Settings
```php
define('SESSION_TIMEOUT', 3600); // 1 hour
```

### Security Settings
- Change `CSRF_TOKEN_NAME` for additional security
- Update `ADMIN_EMAIL` for notifications
- Modify rate limiting in `contact_submit.php`

## 🐛 Troubleshooting

### Common Issues

**Database Connection Failed**
- Check if MySQL is running in XAMPP
- Verify database credentials in `config.php`
- Ensure `portfolio_db` database exists

**Upload Directory Not Writable**
- Check folder permissions for `uploads/`
- On Windows: Give "Users" full control
- On Linux/Mac: `chmod 755 uploads/`

**Admin Login Not Working**
- Verify sample data was imported
- Check database has admin record
- Try resetting password directly in database

**CSS/JS Not Loading**
- Check file paths in `index.php`
- Ensure Apache is serving the directory correctly
- Clear browser cache

**Contact Form Not Working**
- Check PHP error logs
- Verify database connection
- Ensure `tmp/` directory exists and is writable

### Debug Mode
Add to `config.php` for development:
```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

## 📱 Browser Support

- Chrome (recommended)
- Firefox
- Safari
- Edge
- Mobile browsers (iOS Safari, Chrome Mobile)

## 🚀 Performance Tips

1. **Optimize Images:** Use WebP format when possible
2. **Enable Gzip:** Configure Apache compression
3. **Use CDN:** For production deployment
4. **Minify Assets:** Use build tools for CSS/JS
5. **Database Indexes:** Add indexes for frequently queried columns

## 🔒 Security Best Practices

1. **Change Default Credentials:** Immediately after installation
2. **Regular Updates:** Keep PHP and MySQL updated
3. **Backup Database:** Regular automated backups
4. **SSL Certificate:** Use HTTPS in production
5. **File Permissions:** Restrict unnecessary access
6. **Error Logging:** Monitor PHP error logs

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📞 Support

For issues or questions:
1. Check the troubleshooting section above
2. Review the code comments for guidance
3. Create an issue in the repository

## 🎯 Future Enhancements

- Blog system with markdown support
- Advanced analytics dashboard
- Email notifications for contact forms
- Multi-language support
- Dark/light theme for admin panel
- Advanced file management
- API rate limiting
- Two-factor authentication

---

**Built with ❤️ for Hassan Mohammed Naquibul Hoque (Naquib)**

Last updated: August 25, 2025
