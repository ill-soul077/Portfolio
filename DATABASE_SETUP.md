# Portfolio Database Setup Guide

## 🚀 Quick Setup Instructions

### 1. **Import Database**
1. Start XAMPP (Apache + MySQL)
2. Open phpMyAdmin: `http://localhost/phpmyadmin`
3. Click "Import" tab
4. Choose file: `portfolio_database_new.sql`
5. Click "Go" to import

### 2. **Verify Installation**
- Visit: `http://localhost/f/test_database.php`
- Check all tables show ✅ OK status
- Note any errors and fix them

### 3. **Test API**
- Visit: `http://localhost/f/api/portfolio.php`
- Should return JSON data with your portfolio information

### 4. **Access Admin Panel**
- URL: `http://localhost/f/admin_login.php`
- **Username:** `admin`
- **Password:** `admin123`

---

## 📊 Database Structure

### Core Tables:
- **`admin_users`** - Admin authentication
- **`portfolio_basics`** - Personal information
- **`social_profiles`** - Social media links
- **`skills`** - Technical skills with proficiency
- **`repositories`** - Projects and GitHub repos
- **`work_experience`** - Job history
- **`education`** - Educational background
- **`academic_highlights`** - Achievements
- **`interests`** - Personal interests
- **`contact_messages`** - Contact form submissions
- **`site_settings`** - Website configuration

### New Features:
- ✅ **Performance optimized** with indexes
- ✅ **Display ordering** for custom sorting
- ✅ **Active/inactive status** for content management
- ✅ **Proficiency percentages** for skills
- ✅ **Contact form** with message management
- ✅ **UTF8MB4 encoding** for full Unicode support

---

## 🔧 API Endpoints

### GET `/api/portfolio.php`
Returns complete portfolio data in JSON format compatible with the frontend.

**Response Structure:**
```json
{
  "meta": {"theme": "actual"},
  "basics": {
    "name": "Naquib Hassan",
    "label": "Software Developer",
    "image": "ILLSOUL.jpg",
    "summary": "...",
    "email": "...",
    "profiles": {...}
  },
  "skills": [...],
  "repository": [...],
  "work": [...],
  "education": [...],
  "academic_highlights": [...],
  "interests": [...]
}
```

### POST `/contact_handler.php`
Handles contact form submissions.

**Required Fields:**
- `name` (string)
- `email` (valid email)
- `message` (string)
- `subject` (optional)

---

## 🛠️ Configuration

### Database Settings (`config/database.php`)
```php
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'portfolio_db');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### Security Notes:
1. **Change default admin password** after first login
2. **Update database credentials** for production
3. **Enable HTTPS** for production deployment
4. **Backup database** regularly

---

## 🔍 Troubleshooting

### Common Issues:

1. **Database Connection Failed**
   - Ensure XAMPP MySQL is running
   - Check database credentials in `config/database.php`
   - Verify database `portfolio_db` exists

2. **API Returns Empty Data**
   - Import the SQL file completely
   - Check for SQL errors in phpMyAdmin
   - Verify tables have data

3. **Admin Login Fails**
   - Default credentials: admin/admin123
   - Check `admin_users` table exists
   - Verify password hashing

4. **Skills Filter Not Working**
   - Check JavaScript console for errors
   - Verify API returns skills with 'situation' field
   - Ensure database has skills data

### Debug Tools:
- **Database Test:** `http://localhost/f/test_database.php`
- **API Test:** `http://localhost/f/api/portfolio.php`
- **Error Logs:** Check XAMPP error logs

---

## 📈 Admin Features

### Dashboard (`/admin/dashboard.php`)
- Overview of all portfolio data
- Quick statistics
- Recent contact messages

### Skills Management (`/admin/manage_skills.php`)
- Add/edit/delete skill categories
- Set proficiency percentages
- Manage display order
- Toggle active status

### Other Management Pages:
- `/admin/manage_basics.php` - Personal information
- `/admin/manage_projects.php` - Project management
- `/admin/manage_work.php` - Work experience
- `/admin/manage_education.php` - Education records
- `/admin/manage_messages.php` - Contact messages

---

## 🚀 Deployment Notes

For production deployment:
1. Change database credentials
2. Update admin passwords
3. Enable SSL/HTTPS
4. Configure proper error handling
5. Set up database backups
6. Update API URLs if needed

---

## 💡 Tips

1. **Regular Backups:** Export database regularly via phpMyAdmin
2. **Performance:** Database includes proper indexes for fast queries
3. **Customization:** Modify `site_settings` table for easy configuration
4. **Security:** Change default passwords immediately
5. **Updates:** Test all changes on local environment first

---

**Need Help?** Check the test page first: `http://localhost/f/test_database.php`
