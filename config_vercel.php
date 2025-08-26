<?php
// Load environment variables from .env file
require_once 'load_env.php';

// Database configuration for Vercel
// Uses environment variables from .env file or Vercel

// Get database config from environment variables
$db_host = $_ENV['DB_HOST'] ?? 'localhost';
$db_user = $_ENV['DB_USER'] ?? 'root';
$db_pass = $_ENV['DB_PASS'] ?? '';
$db_name = $_ENV['DB_NAME'] ?? 'romantic_web';

// Database configuration
define('DB_HOST', $db_host);
define('DB_USER', $db_user);
define('DB_PASS', $db_pass);
define('DB_NAME', $db_name);

// Application settings
define('SITE_NAME', $_ENV['SITE_NAME'] ?? 'Portal Akademik');
define('UPLOAD_DIR', $_ENV['UPLOAD_DIR'] ?? 'photos/');
define('MAX_FILE_SIZE', (int)($_ENV['MAX_FILE_SIZE'] ?? 5242880)); // 5MB
define('ALLOWED_EXTENSIONS', explode(',', $_ENV['ALLOWED_EXTENSIONS'] ?? 'jpg,jpeg,png,gif'));

// Security settings
define('SESSION_TIMEOUT', (int)($_ENV['SESSION_TIMEOUT'] ?? 3600)); // 1 hour

// Error reporting (set to 0 for production)
$error_reporting = (int)($_ENV['ERROR_REPORTING'] ?? 1);
$display_errors = (int)($_ENV['DISPLAY_ERRORS'] ?? 1);

if ($error_reporting) {
    error_reporting(E_ALL);
}
if ($display_errors) {
    ini_set('display_errors', 1);
}

// Timezone
$timezone = $_ENV['TIMEZONE'] ?? 'Asia/Jakarta';
date_default_timezone_set($timezone);

// Helper functions
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function is_valid_image($file) {
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    return in_array($extension, ALLOWED_EXTENSIONS) && 
           $file['size'] <= MAX_FILE_SIZE &&
           $file['error'] === UPLOAD_ERR_OK;
}

function create_upload_directory() {
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }
}

// Check if we're on Vercel
function is_vercel() {
    return isset($_ENV['VERCEL']) && $_ENV['VERCEL'] === '1';
}

// Get database connection info
function get_db_info() {
    return [
        'host' => DB_HOST,
        'user' => DB_USER,
        'name' => DB_NAME,
        'connected' => !empty(DB_HOST) && !empty(DB_USER)
    ];
}

// Get Cloudinary config
function get_cloudinary_config() {
    return [
        'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'] ?? '',
        'api_key' => $_ENV['CLOUDINARY_API_KEY'] ?? '',
        'api_secret' => $_ENV['CLOUDINARY_API_SECRET'] ?? '',
        'upload_preset' => $_ENV['CLOUDINARY_UPLOAD_PRESET'] ?? 'romantic_web',
        'configured' => !empty($_ENV['CLOUDINARY_CLOUD_NAME']) && 
                       !empty($_ENV['CLOUDINARY_API_KEY']) && 
                       !empty($_ENV['CLOUDINARY_API_SECRET'])
    ];
}
?>