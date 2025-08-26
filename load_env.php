<?php
// Load environment variables from .env file
function loadEnv($path) {
    if (!file_exists($path)) {
        return false;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parse key=value
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove quotes if present
            if (preg_match('/^(["\'])(.*)\1$/', $value, $matches)) {
                $value = $matches[2];
            }
            
            // Set environment variable
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
    
    return true;
}

// Load .env file
loadEnv(__DIR__ . '/.env');

// Set default values if not set
$_ENV['DB_HOST'] = $_ENV['DB_HOST'] ?? 'localhost';
$_ENV['DB_USER'] = $_ENV['DB_USER'] ?? 'root';
$_ENV['DB_PASS'] = $_ENV['DB_PASS'] ?? '';
$_ENV['DB_NAME'] = $_ENV['DB_NAME'] ?? 'romantic_web';

$_ENV['CLOUDINARY_CLOUD_NAME'] = $_ENV['CLOUDINARY_CLOUD_NAME'] ?? '';
$_ENV['CLOUDINARY_API_KEY'] = $_ENV['CLOUDINARY_API_KEY'] ?? '';
$_ENV['CLOUDINARY_API_SECRET'] = $_ENV['CLOUDINARY_API_SECRET'] ?? '';
$_ENV['CLOUDINARY_UPLOAD_PRESET'] = $_ENV['CLOUDINARY_UPLOAD_PRESET'] ?? 'romantic_web';

$_ENV['SITE_NAME'] = $_ENV['SITE_NAME'] ?? 'Portal Akademik';
$_ENV['UPLOAD_DIR'] = $_ENV['UPLOAD_DIR'] ?? 'photos/';
$_ENV['MAX_FILE_SIZE'] = $_ENV['MAX_FILE_SIZE'] ?? 5242880;
$_ENV['ALLOWED_EXTENSIONS'] = $_ENV['ALLOWED_EXTENSIONS'] ?? 'jpg,jpeg,png,gif';
$_ENV['SESSION_TIMEOUT'] = $_ENV['SESSION_TIMEOUT'] ?? 3600;
$_ENV['TIMEZONE'] = $_ENV['TIMEZONE'] ?? 'Asia/Jakarta';
$_ENV['ERROR_REPORTING'] = $_ENV['ERROR_REPORTING'] ?? 1;
$_ENV['DISPLAY_ERRORS'] = $_ENV['DISPLAY_ERRORS'] ?? 1;
?>