<?php
// Script untuk setup environment variables di Vercel dari file .env
echo "=== Setup Vercel Environment Variables ===\n\n";

// Load .env file
require_once 'load_env.php';

echo "Environment variables loaded from .env file:\n\n";

// Database variables
echo "Database Variables:\n";
echo "DB_HOST: " . ($_ENV['DB_HOST'] ?? 'not set') . "\n";
echo "DB_USER: " . ($_ENV['DB_USER'] ?? 'not set') . "\n";
echo "DB_PASS: " . ($_ENV['DB_PASS'] ? '***set***' : 'not set') . "\n";
echo "DB_NAME: " . ($_ENV['DB_NAME'] ?? 'not set') . "\n\n";

// Cloudinary variables
echo "Cloudinary Variables:\n";
echo "CLOUDINARY_CLOUD_NAME: " . ($_ENV['CLOUDINARY_CLOUD_NAME'] ? $_ENV['CLOUDINARY_CLOUD_NAME'] : 'not set') . "\n";
echo "CLOUDINARY_API_KEY: " . ($_ENV['CLOUDINARY_API_KEY'] ? '***set***' : 'not set') . "\n";
echo "CLOUDINARY_API_SECRET: " . ($_ENV['CLOUDINARY_API_SECRET'] ? '***set***' : 'not set') . "\n";
echo "CLOUDINARY_UPLOAD_PRESET: " . ($_ENV['CLOUDINARY_UPLOAD_PRESET'] ?? 'not set') . "\n\n";

// Application variables
echo "Application Variables:\n";
echo "SITE_NAME: " . ($_ENV['SITE_NAME'] ?? 'not set') . "\n";
echo "UPLOAD_DIR: " . ($_ENV['UPLOAD_DIR'] ?? 'not set') . "\n";
echo "MAX_FILE_SIZE: " . ($_ENV['MAX_FILE_SIZE'] ?? 'not set') . "\n";
echo "ALLOWED_EXTENSIONS: " . ($_ENV['ALLOWED_EXTENSIONS'] ?? 'not set') . "\n";
echo "SESSION_TIMEOUT: " . ($_ENV['SESSION_TIMEOUT'] ?? 'not set') . "\n";
echo "TIMEZONE: " . ($_ENV['TIMEZONE'] ?? 'not set') . "\n\n";

echo "=== Vercel CLI Commands ===\n\n";

echo "To set these variables in Vercel, run the following commands:\n\n";

// Database commands
echo "# Database Variables\n";
echo "vercel env add DB_HOST\n";
echo "vercel env add DB_USER\n";
echo "vercel env add DB_PASS\n";
echo "vercel env add DB_NAME\n\n";

// Cloudinary commands
echo "# Cloudinary Variables\n";
echo "vercel env add CLOUDINARY_CLOUD_NAME\n";
echo "vercel env add CLOUDINARY_API_KEY\n";
echo "vercel env add CLOUDINARY_API_SECRET\n";
echo "vercel env add CLOUDINARY_UPLOAD_PRESET\n\n";

// Application commands
echo "# Application Variables\n";
echo "vercel env add SITE_NAME\n";
echo "vercel env add UPLOAD_DIR\n";
echo "vercel env add MAX_FILE_SIZE\n";
echo "vercel env add ALLOWED_EXTENSIONS\n";
echo "vercel env add SESSION_TIMEOUT\n";
echo "vercel env add TIMEZONE\n\n";

echo "=== Manual Setup Instructions ===\n\n";

echo "Alternatively, you can set these in Vercel Dashboard:\n";
echo "1. Go to https://vercel.com/dashboard\n";
echo "2. Select your project\n";
echo "3. Go to Settings > Environment Variables\n";
echo "4. Add each variable manually\n\n";

echo "=== Values to Copy ===\n\n";

echo "Copy these values when prompted:\n\n";

echo "DB_HOST: " . ($_ENV['DB_HOST'] ?? 'localhost') . "\n";
echo "DB_USER: " . ($_ENV['DB_USER'] ?? 'root') . "\n";
echo "DB_PASS: " . ($_ENV['DB_PASS'] ?? '') . "\n";
echo "DB_NAME: " . ($_ENV['DB_NAME'] ?? 'romantic_web') . "\n";
echo "CLOUDINARY_CLOUD_NAME: " . ($_ENV['CLOUDINARY_CLOUD_NAME'] ?? 'your_cloud_name_here') . "\n";
echo "CLOUDINARY_API_KEY: " . ($_ENV['CLOUDINARY_API_KEY'] ?? 'your_api_key_here') . "\n";
echo "CLOUDINARY_API_SECRET: " . ($_ENV['CLOUDINARY_API_SECRET'] ?? 'your_api_secret_here') . "\n";
echo "CLOUDINARY_UPLOAD_PRESET: " . ($_ENV['CLOUDINARY_UPLOAD_PRESET'] ?? 'romantic_web') . "\n";
echo "SITE_NAME: " . ($_ENV['SITE_NAME'] ?? 'Portal Akademik') . "\n";
echo "UPLOAD_DIR: " . ($_ENV['UPLOAD_DIR'] ?? 'photos/') . "\n";
echo "MAX_FILE_SIZE: " . ($_ENV['MAX_FILE_SIZE'] ?? '5242880') . "\n";
echo "ALLOWED_EXTENSIONS: " . ($_ENV['ALLOWED_EXTENSIONS'] ?? 'jpg,jpeg,png,gif') . "\n";
echo "SESSION_TIMEOUT: " . ($_ENV['SESSION_TIMEOUT'] ?? '3600') . "\n";
echo "TIMEZONE: " . ($_ENV['TIMEZONE'] ?? 'Asia/Jakarta') . "\n\n";

echo "=== Next Steps ===\n\n";

echo "1. Update your .env file with real values\n";
echo "2. Run: php setup_vercel_env.php (to see the commands)\n";
echo "3. Run the Vercel CLI commands above\n";
echo "4. Deploy: vercel --prod\n\n";

echo "=== Important Notes ===\n\n";

echo "⚠️  Make sure to update these values in .env file:\n";
echo "   - CLOUDINARY_CLOUD_NAME: Your Cloudinary cloud name\n";
echo "   - CLOUDINARY_API_KEY: Your Cloudinary API key\n";
echo "   - CLOUDINARY_API_SECRET: Your Cloudinary API secret\n";
echo "   - DB_PASS: Your database password (if any)\n\n";

echo "⚠️  For production, set ERROR_REPORTING=0 and DISPLAY_ERRORS=0\n\n";
?>