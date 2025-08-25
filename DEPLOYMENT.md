# Deployment Guide

## Local Development (XAMPP)

### Prerequisites
- XAMPP 8.0+ (with PHP 8.0+, MySQL 5.7+, Apache 2.4+)
- Modern web browser
- Text editor (optional)

### Setup Steps
1. **Install XAMPP**
   - Download from [https://www.apachefriends.org/](https://www.apachefriends.org/)
   - Install and start Apache + MySQL services

2. **Deploy Files**
   ```bash
   # Copy portfolio folder to htdocs
   cp -r portfolio/ C:/xampp/htdocs/
   ```

3. **Setup Database**
   - Open phpMyAdmin: http://localhost/phpmyadmin
   - Create database: `portfolio_db`
   - Import: `db/schema.sql` then `db/sample_data.sql`

4. **Configure**
   - Check `config.php` settings
   - Set uploads folder permissions
   - Test: http://localhost/portfolio/

## Production Deployment

### Shared Hosting
1. **Upload Files**
   - Upload all files to your domain's public_html folder
   - Ensure uploads folder is writable (755 permissions)

2. **Database Setup**
   - Create MySQL database via cPanel
   - Import schema.sql and sample_data.sql
   - Update config.php with your database credentials

3. **SSL Configuration**
   - Enable SSL/HTTPS in your hosting panel
   - Update any hardcoded HTTP links to HTTPS

### VPS/Dedicated Server

#### Requirements
- Ubuntu 20.04+ / CentOS 8+ / Similar Linux distribution
- Apache 2.4+ or Nginx 1.18+
- PHP 8.0+ with extensions: PDO, MySQL, GD, OpenSSL
- MySQL 8.0+ or MariaDB 10.5+
- SSL certificate (Let's Encrypt recommended)

#### Apache Setup
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/portfolio
    
    # Redirect to HTTPS
    Redirect permanent / https://yourdomain.com/
</VirtualHost>

<VirtualHost *:443>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/portfolio
    
    # SSL Configuration
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    
    # Security Headers
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
    
    # PHP Configuration
    <Directory /var/www/portfolio>
        AllowOverride All
        Require all granted
    </Directory>
    
    # Protect sensitive files
    <Files ~ "\.(env|conf|config)$">
        Require all denied
    </Files>
    
    # Error and Access Logs
    ErrorLog ${APACHE_LOG_DIR}/portfolio_error.log
    CustomLog ${APACHE_LOG_DIR}/portfolio_access.log combined
</VirtualHost>
```

#### Nginx Setup
```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/portfolio;
    index index.php index.html;

    # SSL Configuration
    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;
    
    # Security Headers
    add_header X-Content-Type-Options nosniff;
    add_header X-Frame-Options DENY;
    add_header X-XSS-Protection "1; mode=block";
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains";

    # PHP Processing
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Static Files
    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg|webp)$ {
        expires 1y;
        add_header Cache-Control "public, no-transform";
    }

    # Security
    location ~ /\. {
        deny all;
    }
    
    location ~ \.(env|conf|config)$ {
        deny all;
    }
}
```

### Database Configuration

#### Production Database Settings
```php
// config.php for production
define('DB_HOST', 'your-db-host');
define('DB_USER', 'your-db-user');
define('DB_PASS', 'your-secure-password');
define('DB_NAME', 'your-db-name');

// Production optimizations
define('UPLOAD_PATH', '/var/www/portfolio/uploads/');
define('UPLOAD_URL', 'https://yourdomain.com/uploads/');
```

### Security Hardening

#### File Permissions
```bash
# Set proper ownership
chown -R www-data:www-data /var/www/portfolio

# Set directory permissions
find /var/www/portfolio -type d -exec chmod 755 {} \;

# Set file permissions
find /var/www/portfolio -type f -exec chmod 644 {} \;

# Make uploads writable
chmod 755 /var/www/portfolio/uploads
```

#### Environment Variables (Recommended)
Create `.env` file (not in web root):
```bash
DB_HOST=localhost
DB_USER=portfolio_user
DB_PASS=secure_random_password
DB_NAME=portfolio_db
ADMIN_EMAIL=admin@yourdomain.com
```

Update config.php:
```php
// Load environment variables
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'portfolio_db');
```

### Performance Optimization

#### PHP Configuration
```ini
; php.ini optimizations
memory_limit = 256M
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 30
session.gc_maxlifetime = 3600

; OPcache
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=4000
opcache.revalidate_freq=60
```

#### Database Optimization
```sql
-- Add indexes for better performance
CREATE INDEX idx_messages_created_at ON messages(created_at);
CREATE INDEX idx_projects_created_at ON projects(created_at);
CREATE INDEX idx_skills_category ON skills(skill_category);
CREATE INDEX idx_achievements_date ON achievements(date_achieved);
```

### Monitoring & Maintenance

#### Log Monitoring
```bash
# Monitor error logs
tail -f /var/log/apache2/portfolio_error.log

# Monitor access logs
tail -f /var/log/apache2/portfolio_access.log
```

#### Backup Script
```bash
#!/bin/bash
# backup.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/var/backups/portfolio"

# Create backup directory
mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u username -p password portfolio_db > $BACKUP_DIR/db_$DATE.sql

# Files backup
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/portfolio

# Keep only last 30 days of backups
find $BACKUP_DIR -name "*.sql" -mtime +30 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +30 -delete

echo "Backup completed: $DATE"
```

#### Cron Jobs
```bash
# Add to crontab (crontab -e)

# Daily backup at 2 AM
0 2 * * * /path/to/backup.sh

# Weekly log rotation
0 0 * * 0 logrotate /etc/logrotate.d/portfolio
```

### Troubleshooting

#### Common Issues
1. **Permission Denied**: Check file/folder permissions
2. **Database Connection Failed**: Verify credentials and host
3. **File Upload Issues**: Check upload directory permissions
4. **SSL Certificate Problems**: Verify certificate installation

#### Debug Mode
For troubleshooting, temporarily enable debug mode:
```php
// Add to config.php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

**Remember to disable debug mode in production!**

### Updates & Maintenance

#### Regular Tasks
- Update PHP and server software
- Monitor disk space and performance
- Review security logs
- Test backup restoration
- Update admin passwords
- Check for broken links

#### Version Updates
1. Backup current installation
2. Test updates in staging environment
3. Deploy updates during low-traffic periods
4. Monitor for issues post-deployment

---

This deployment guide covers both development and production scenarios. Choose the appropriate section based on your needs.
