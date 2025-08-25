# Uploads Directory

This directory stores uploaded files such as:
- Profile pictures
- Project images
- Other user-uploaded content

## Important Notes

1. **Permissions**: This directory must be writable by the web server
2. **Security**: Only image files (JPG, PNG, WebP) are allowed
3. **Size Limits**: Maximum file size is 5MB per upload
4. **Naming**: Files are automatically renamed with unique identifiers

## Setting Permissions

### Windows (XAMPP)
1. Right-click on the uploads folder
2. Select "Properties"
3. Go to "Security" tab
4. Give "Users" group full control

### Linux/Mac
```bash
chmod 755 uploads/
chown www-data:www-data uploads/  # For Apache
```

## File Structure
```
uploads/
├── profile_images/     # Profile pictures (auto-created)
├── project_images/     # Project screenshots (auto-created)
└── temp/              # Temporary files (auto-created)
```

The application will automatically create subdirectories as needed.
