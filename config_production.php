<?php
// Production Database Configuration
// Update these values sesuai dengan hosting provider Anda

// === cPanel Hosting ===
define('DB_HOST', 'localhost'); // atau IP server database
define('DB_USER', 'romantic_user'); // username dari cPanel
define('DB_PASS', 'password123'); // password yang Anda set
define('DB_NAME', 'romantic_web'); // nama database
define('USE_DATABASE', true);

// === PlanetScale ===
// define('DB_HOST', 'aws.connect.psdb.cloud');
// define('DB_USER', 'your_username');
// define('DB_PASS', 'your_password');
// define('DB_NAME', 'romantic_web');

// === Railway ===
// define('DB_HOST', 'containers-us-west-1.railway.app');
// define('DB_USER', 'postgres');
// define('DB_PASS', 'your_password');
// define('DB_NAME', 'railway');

// === Heroku ===
// define('DB_HOST', 'your-heroku-host.compute.amazonaws.com');
// define('DB_USER', 'your_username');
// define('DB_PASS', 'your_password');
// define('DB_NAME', 'your_database');

// Application settings
define('SITE_NAME', 'Portal Akademik');
define('UPLOAD_DIR', 'photos/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);

// Security settings
define('SESSION_TIMEOUT', 3600); // 1 hour

// Error reporting (set to 0 for production)
error_reporting(0);
ini_set('display_errors', 0);

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