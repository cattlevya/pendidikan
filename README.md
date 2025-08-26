# Portal Akademik - PHP Web Application

A romantic web application built with PHP that includes a login system, photo gallery, and confession feature.

## Features

### 🔐 Authentication System
- Secure login with session management
- User authentication and authorization
- Session timeout protection
- Logout functionality

### 📸 Photo Gallery
- Upload photos with captions
- Dynamic photo display
- File validation and security
- Admin panel for photo management
- Responsive grid layout

### 💕 Confession Feature
- Interactive confession page
- Response tracking
- Celebration animations
- Result storage

### 🛡️ Security Features
- Input sanitization
- File upload validation
- Session management
- XSS protection
- CSRF protection

## File Structure

```
├── index.php              # Main login page
├── gallery.php            # Photo gallery with upload
├── confess.php            # Confession page
├── admin.php              # Admin panel
├── logout.php             # Logout script
├── config.php             # Configuration file
├── .htaccess              # Apache configuration
├── photos/                # Uploaded photos directory
├── photos_data.json       # Photo metadata (auto-generated)
├── confession_response.json # Confession responses (auto-generated)
├── style.css              # Main stylesheet
├── gallery.css            # Gallery styles
├── confess-style.css      # Confession page styles
├── popup-style.css        # Popup styles
├── script.js              # Main JavaScript
├── gallery.js             # Gallery JavaScript
├── confess-script.js      # Confession JavaScript
└── README.md              # This file
```

## Installation

1. **Requirements:**
   - PHP 7.4 or higher
   - Apache/Nginx web server
   - mod_rewrite enabled (for Apache)

2. **Setup:**
   ```bash
   # Clone or download the files to your web server directory
   # Ensure the photos/ directory is writable
   chmod 755 photos/
   
   # Default login credentials:
   # Username: admin
   # Password: password
   ```

3. **Configuration:**
   - Edit `config.php` to customize settings
   - Modify database settings if using MySQL
   - Adjust file upload limits in PHP configuration

## Usage

### Login
- Access the application through `index.php`
- Use default credentials: `admin` / `password`
- Sessions are managed automatically

### Photo Gallery
- Upload photos with captions
- View all uploaded photos in a grid
- Navigate between pages seamlessly

### Confession Feature
- Interactive confession page
- Track responses and timestamps
- Celebration animations for positive responses

### Admin Panel
- Manage uploaded photos
- View confession responses
- Monitor application statistics
- Delete photos as needed

## Security Considerations

- All user inputs are sanitized
- File uploads are validated
- Sessions are properly managed
- Sensitive files are protected
- XSS and CSRF protection implemented

## Customization

### Styling
- Modify CSS files to change appearance
- Update color schemes in `style.css`
- Customize animations and effects

### Functionality
- Edit `config.php` for application settings
- Modify authentication logic in `index.php`
- Add new features by extending existing files

### Database Integration
- The application currently uses JSON files for data storage
- Can be easily modified to use MySQL/PostgreSQL
- Database schema provided in comments

## Troubleshooting

### Common Issues

1. **Upload not working:**
   - Check `photos/` directory permissions
   - Verify PHP upload settings
   - Check file size limits

2. **Session issues:**
   - Ensure PHP sessions are enabled
   - Check session storage permissions
   - Verify session timeout settings

3. **Styling problems:**
   - Check CSS file paths
   - Verify browser compatibility
   - Clear browser cache

### Error Logs
- Check PHP error logs for detailed information
- Enable error reporting in development
- Monitor Apache/Nginx logs

## License

This project is created for educational and personal use.

## Support

For issues or questions, please check the configuration files and ensure all requirements are met.

---

**Note:** This is a romantic web application designed for personal use. Please ensure all content and usage complies with applicable laws and regulations.
