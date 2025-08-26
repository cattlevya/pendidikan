<?php
// Database configuration for Vercel
// Uses environment variables from Vercel

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
define('SITE_NAME', 'Portal Akademik');
define('UPLOAD_DIR', 'photos/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);

// Security settings
define('SESSION_TIMEOUT', 3600); // 1 hour

// Error reporting (set to 0 for production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Asia/Jakarta');

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
?>