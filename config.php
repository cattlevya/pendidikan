<?php
// Database configuration (if using MySQL later)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'romantic_web');

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
?>