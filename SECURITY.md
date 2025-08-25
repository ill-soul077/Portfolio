# Portfolio Security Notes

## Security Measures Implemented

### Authentication & Authorization
- **Password Hashing**: All passwords are hashed using PHP's `password_hash()` with default algorithm (currently bcrypt)
- **Session Management**: Secure session handling with regeneration on login and timeout protection
- **CSRF Protection**: All admin forms include CSRF tokens to prevent cross-site request forgery
- **Input Validation**: Both client-side and server-side validation for all user inputs

### Database Security
- **Prepared Statements**: All database queries use PDO prepared statements to prevent SQL injection
- **Parameter Binding**: User inputs are properly bound to prevent malicious SQL execution
- **Connection Security**: Database connection uses proper PDO error handling and configuration

### File Upload Security
- **MIME Type Validation**: Only allows image files (JPEG, PNG, WebP)
- **File Size Limits**: Maximum upload size of 5MB per file
- **Unique Filenames**: Uploaded files are renamed with unique identifiers to prevent conflicts
- **Path Validation**: File paths are validated and sanitized

### Rate Limiting & Spam Protection
- **Contact Form Rate Limiting**: Maximum 5 submissions per hour per IP address
- **Spam Keyword Detection**: Basic spam filtering on contact form submissions
- **Input Length Limits**: All text inputs have maximum length restrictions

### XSS Prevention
- **Output Escaping**: All dynamic content is escaped using `htmlspecialchars()`
- **HTML Sanitization**: User inputs are sanitized before display
- **Content-Type Headers**: Proper content-type headers for API responses

## Changing Admin Credentials

### Method 1: Using Admin Panel (Recommended)
1. Log in to the admin panel: `/admin/login.php`
2. Navigate to "Change Password"
3. Enter current password and new secure password
4. Confirm the change

### Method 2: Direct Database Update (Emergency)
If you're locked out of the admin panel:

1. Access phpMyAdmin or MySQL command line
2. Navigate to the `portfolio_db` database
3. Find the `admin` table
4. Generate a new password hash:
   ```php
   <?php
   echo password_hash('your_new_password', PASSWORD_DEFAULT);
   ?>
   ```
5. Update the admin record:
   ```sql
   UPDATE admin SET password_hash = 'your_generated_hash' WHERE id = 1;
   ```

### Method 3: Reset Script (Advanced)
Create a temporary reset script:

```php
<?php
require_once 'config.php';

$new_password = 'your_new_secure_password';
$new_hash = password_hash($new_password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE admin SET password_hash = ? WHERE id = 1");
$stmt->execute([$new_hash]);

echo "Password updated successfully!";
// DELETE THIS FILE AFTER USE
?>
```

## Recommended Security Practices

### Strong Password Requirements
- Minimum 8 characters
- Mix of uppercase and lowercase letters
- At least one number
- At least one special character
- Avoid personal information
- Use unique passwords (not used elsewhere)

### Server Configuration
For production deployment:

1. **Enable HTTPS**: Use SSL/TLS certificates
2. **Update PHP**: Keep PHP version current with security patches
3. **Configure Apache/Nginx**: Proper security headers and configurations
4. **Disable Debug Mode**: Remove error display in production
5. **File Permissions**: Restrict file permissions appropriately

### Additional Hardening
1. **Change Default Paths**: Consider moving admin panel to non-standard location
2. **IP Whitelisting**: Restrict admin access to specific IP addresses if possible
3. **Two-Factor Authentication**: Consider implementing 2FA for admin access
4. **Regular Backups**: Automated database and file backups
5. **Monitor Logs**: Regular review of access and error logs

### Environment Variables
For enhanced security, consider moving sensitive configuration to environment variables:

```php
// Example: Using environment variables
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'portfolio_db');
```

## Security Checklist

### Initial Setup
- [ ] Change default admin credentials
- [ ] Update database credentials if needed
- [ ] Set proper file permissions
- [ ] Configure upload directory
- [ ] Test CSRF protection
- [ ] Verify input validation

### Regular Maintenance
- [ ] Update admin password regularly
- [ ] Review contact form submissions for spam
- [ ] Monitor error logs
- [ ] Check file upload directory
- [ ] Backup database regularly
- [ ] Update PHP/MySQL when available

### Production Deployment
- [ ] Enable HTTPS
- [ ] Configure security headers
- [ ] Disable debug mode
- [ ] Set up monitoring
- [ ] Configure automated backups
- [ ] Review server security settings

## Incident Response

If you suspect a security breach:

1. **Immediate Actions**:
   - Change admin password immediately
   - Check recent admin activity
   - Review contact form submissions
   - Check uploaded files

2. **Investigation**:
   - Review server access logs
   - Check database for unauthorized changes
   - Examine file system for suspicious files
   - Monitor for unusual network activity

3. **Recovery**:
   - Restore from clean backup if necessary
   - Update all credentials
   - Patch any discovered vulnerabilities
   - Document the incident

## Contact for Security Issues

If you discover a security vulnerability, please:
1. Do not publicly disclose the issue
2. Document the vulnerability clearly
3. Contact the system administrator
4. Allow time for proper remediation

---

**Remember**: Security is an ongoing process, not a one-time setup. Regularly review and update your security measures.
