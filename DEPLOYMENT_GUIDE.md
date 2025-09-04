# 🚀 Deployment Guide

This guide provides step-by-step instructions for deploying Hassan Naquib's Portfolio to production.

## 📋 Pre-Deployment Checklist

### ✅ Security Preparations
- [ ] Change default admin credentials
- [ ] Update database passwords
- [ ] Remove debug files and logs
- [ ] Enable HTTPS/SSL certificates
- [ ] Configure proper file permissions
- [ ] Set up firewall rules

### ✅ Performance Optimization
- [ ] Enable PHP OPcache
- [ ] Configure MySQL query cache
- [ ] Optimize images and assets
- [ ] Set up proper caching headers
- [ ] Minify CSS/JS files (optional)

### ✅ Configuration Updates
- [ ] Update database connection settings
- [ ] Configure email settings for contact form
- [ ] Set production error handling
- [ ] Update file paths and URLs
- [ ] Configure backup systems

## 🌐 Production Server Requirements

### Minimum Requirements
- **PHP**: 7.4 or higher (8.1+ recommended)
- **MySQL**: 5.7 or higher (8.0+ recommended)
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **Storage**: 500MB minimum (1GB recommended)
- **RAM**: 512MB minimum (1GB recommended)

### Recommended Extensions
```php
php-pdo
php-pdo-mysql
php-json
php-session
php-filter
php-gd (for image handling)
php-curl (for external API calls)
```

## 📂 File Structure for Production

```
/var/www/html/portfolio/    # or your web root
├── admin/                  # Admin dashboard (secure this directory)
├── api/                    # API endpoints
├── assets/                 # Static files
├── config/                 # Configuration files (secure this directory)
├── admin_login.php
├── contact_handler.php
├── Naquib.htm             # Main portfolio page
├── resume.json            # Fallback data
├── .htaccess              # Apache configuration
└── README.md
```

## 🔒 Security Configuration

### 1. Admin Directory Protection

Create `.htaccess` in `/admin/` directory:
```apache
# Restrict admin access to specific IPs (optional)
<RequireAll>
    Require ip 192.168.1.0/24
    Require ip YOUR_IP_ADDRESS
</RequireAll>

# Additional security headers
Header always set X-Frame-Options DENY
Header always set X-Content-Type-Options nosniff
Header always set X-XSS-Protection "1; mode=block"
```

### 2. Config Directory Protection

Create `.htaccess` in `/config/` directory:
```apache
# Deny all access to config files
<Files "*">
    Require all denied
</Files>
```

### 3. Root .htaccess Configuration

Create `.htaccess` in root directory:
```apache
# Enable rewrite engine
RewriteEngine On

# Force HTTPS (uncomment in production)
# RewriteCond %{HTTPS} off
# RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Security headers
Header always set X-Frame-Options DENY
Header always set X-Content-Type-Options nosniff
Header always set X-XSS-Protection "1; mode=block"
Header always set Referrer-Policy "strict-origin-when-cross-origin"

# Cache static assets
<IfModule mod_expires.c>
    ExpiresActive on
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType image/png "access plus 1 month"
    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 month"
    ExpiresByType image/gif "access plus 1 month"
    ExpiresByType image/svg+xml "access plus 1 month"
</IfModule>

# Compress files
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>
```

## 🗄️ Database Setup for Production

### 1. Create Production Database
```sql
CREATE DATABASE portfolio_production;
CREATE USER 'portfolio_user'@'localhost' IDENTIFIED BY 'STRONG_PASSWORD_HERE';
GRANT ALL PRIVILEGES ON portfolio_production.* TO 'portfolio_user'@'localhost';
FLUSH PRIVILEGES;
```

### 2. Import Database Schema
```bash
mysql -u portfolio_user -p portfolio_production < portfolio_database_new.sql
```

### 3. Update Admin Credentials
```sql
USE portfolio_production;
UPDATE admin_users SET 
    username = 'your_new_username',
    password = MD5('your_strong_password'),
    email = 'your_admin_email@domain.com'
WHERE id = 1;
```

## ⚙️ Configuration Updates

### 1. Database Configuration (`config/database.php`)
```php
<?php
class Database {
    private $host = "localhost";
    private $db_name = "portfolio_production";
    private $username = "portfolio_user";
    private $password = "STRONG_PASSWORD_HERE";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch(PDOException $exception) {
            error_log("Connection error: " . $exception->getMessage());
        }
        return $this->conn;
    }
}
?>
```

### 2. PHP Configuration (php.ini updates)
```ini
# Security settings
expose_php = Off
display_errors = Off
log_errors = On
error_log = /var/log/php_errors.log

# Session security
session.cookie_httponly = 1
session.cookie_secure = 1
session.use_strict_mode = 1

# File upload limits
upload_max_filesize = 5M
post_max_size = 5M

# Performance settings
opcache.enable = 1
opcache.memory_consumption = 128
opcache.max_accelerated_files = 4000
opcache.validate_timestamps = 0
```

## 📧 Email Configuration

### Contact Form Setup
Update `contact_handler.php` with your SMTP settings:
```php
// Email configuration
$smtp_host = 'smtp.yourdomain.com';
$smtp_port = 587;
$smtp_username = 'noreply@yourdomain.com';
$smtp_password = 'your_email_password';
$admin_email = 'admin@yourdomain.com';
```

## 🔄 Backup Strategy

### 1. Database Backup Script
Create `backup_database.sh`:
```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u portfolio_user -p portfolio_production > /backups/portfolio_${DATE}.sql
# Keep only last 30 days of backups
find /backups/ -name "portfolio_*.sql" -mtime +30 -delete
```

### 2. File Backup Script
Create `backup_files.sh`:
```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
tar -czf /backups/portfolio_files_${DATE}.tar.gz /var/www/html/portfolio/
# Keep only last 30 days of backups
find /backups/ -name "portfolio_files_*.tar.gz" -mtime +30 -delete
```

### 3. Setup Cron Jobs
```bash
# Edit crontab
crontab -e

# Add these lines for daily backups at 2 AM
0 2 * * * /path/to/backup_database.sh
30 2 * * * /path/to/backup_files.sh
```

## 🌐 Domain and SSL Setup

### 1. DNS Configuration
Point your domain to your server's IP address:
```
A record: @ -> YOUR_SERVER_IP
A record: www -> YOUR_SERVER_IP
```

### 2. SSL Certificate (Let's Encrypt)
```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache

# Generate SSL certificate
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com

# Auto-renewal
sudo crontab -e
# Add: 0 3 * * * certbot renew --quiet
```

## 🚀 Deployment Steps

### 1. Upload Files
```bash
# Using SCP
scp -r portfolio/ user@yourserver:/var/www/html/

# Or using Git
cd /var/www/html/
git clone https://github.com/ill-soul077/Portfolio.git portfolio
```

### 2. Set Permissions
```bash
sudo chown -R www-data:www-data /var/www/html/portfolio/
sudo chmod -R 755 /var/www/html/portfolio/
sudo chmod -R 644 /var/www/html/portfolio/config/
```

### 3. Test Installation
1. Visit your domain to check frontend
2. Test admin login at `yourdomain.com/admin_login.php`
3. Verify contact form functionality
4. Check all admin dashboard features

## 📊 Monitoring

### 1. Error Monitoring
Monitor PHP error logs:
```bash
tail -f /var/log/php_errors.log
```

### 2. Performance Monitoring
Use tools like:
- Google PageSpeed Insights
- GTmetrix
- Pingdom
- New Relic (for advanced monitoring)

### 3. Uptime Monitoring
Set up monitoring services:
- UptimeRobot
- Pingdom
- StatusCake

## 🔧 Troubleshooting

### Common Issues

#### Database Connection Errors
- Check credentials in `config/database.php`
- Verify MySQL service is running
- Check firewall settings

#### File Permission Issues
```bash
sudo chmod -R 755 /var/www/html/portfolio/
sudo chown -R www-data:www-data /var/www/html/portfolio/
```

#### SSL Certificate Issues
```bash
sudo certbot renew --dry-run
sudo systemctl restart apache2
```

#### Performance Issues
- Enable PHP OPcache
- Optimize MySQL queries
- Use CDN for static assets
- Enable gzip compression

## 📞 Support

For deployment assistance:
- **Email**: hassannaquib014@gmail.com
- **GitHub**: [ill-soul077](https://github.com/ill-soul077)

---

*Last updated: September 2025*
