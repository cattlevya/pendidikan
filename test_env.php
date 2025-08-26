<?php
// Test environment variables
echo "=== Environment Variables Test ===\n\n";

// Load environment variables
require_once 'load_env.php';

echo "1. Database Configuration:\n";
echo "   Host: " . ($_ENV['DB_HOST'] ?? '❌ Not set') . "\n";
echo "   User: " . ($_ENV['DB_USER'] ?? '❌ Not set') . "\n";
echo "   Password: " . ($_ENV['DB_PASS'] ? '✅ Set' : '❌ Not set') . "\n";
echo "   Database: " . ($_ENV['DB_NAME'] ?? '❌ Not set') . "\n\n";

echo "2. Cloudinary Configuration:\n";
echo "   Cloud Name: " . ($_ENV['CLOUDINARY_CLOUD_NAME'] ? $_ENV['CLOUDINARY_CLOUD_NAME'] : '❌ Not set') . "\n";
echo "   API Key: " . ($_ENV['CLOUDINARY_API_KEY'] ? '✅ Set' : '❌ Not set') . "\n";
echo "   API Secret: " . ($_ENV['CLOUDINARY_API_SECRET'] ? '✅ Set' : '❌ Not set') . "\n";
echo "   Upload Preset: " . ($_ENV['CLOUDINARY_UPLOAD_PRESET'] ?? '❌ Not set') . "\n\n";

echo "3. Application Settings:\n";
echo "   Site Name: " . ($_ENV['SITE_NAME'] ?? '❌ Not set') . "\n";
echo "   Upload Dir: " . ($_ENV['UPLOAD_DIR'] ?? '❌ Not set') . "\n";
echo "   Max File Size: " . ($_ENV['MAX_FILE_SIZE'] ?? '❌ Not set') . "\n";
echo "   Allowed Extensions: " . ($_ENV['ALLOWED_EXTENSIONS'] ?? '❌ Not set') . "\n";
echo "   Session Timeout: " . ($_ENV['SESSION_TIMEOUT'] ?? '❌ Not set') . "\n";
echo "   Timezone: " . ($_ENV['TIMEZONE'] ?? '❌ Not set') . "\n\n";

echo "4. Error Reporting:\n";
echo "   Error Reporting: " . ($_ENV['ERROR_REPORTING'] ?? '❌ Not set') . "\n";
echo "   Display Errors: " . ($_ENV['DISPLAY_ERRORS'] ?? '❌ Not set') . "\n\n";

// Check if Cloudinary is configured
$cloudinary_config = get_cloudinary_config();
echo "5. Cloudinary Status:\n";
if ($cloudinary_config['configured']) {
    echo "   ✅ Cloudinary is properly configured\n";
} else {
    echo "   ❌ Cloudinary is not configured\n";
    echo "   Missing: ";
    $missing = [];
    if (empty($cloudinary_config['cloud_name'])) $missing[] = 'CLOUDINARY_CLOUD_NAME';
    if (empty($cloudinary_config['api_key'])) $missing[] = 'CLOUDINARY_API_KEY';
    if (empty($cloudinary_config['api_secret'])) $missing[] = 'CLOUDINARY_API_SECRET';
    echo implode(', ', $missing) . "\n";
}

echo "\n=== Summary ===\n";

$total_vars = 15;
$set_vars = 0;

foreach ($_ENV as $key => $value) {
    if (!empty($value)) $set_vars++;
}

echo "Environment variables set: $set_vars/$total_vars\n";

if ($set_vars >= 10) {
    echo "✅ Environment is mostly configured\n";
} elseif ($set_vars >= 5) {
    echo "⚠️  Environment is partially configured\n";
} else {
    echo "❌ Environment needs configuration\n";
}

echo "\n=== Next Steps ===\n";

if (!$cloudinary_config['configured']) {
    echo "1. Get Cloudinary credentials from https://cloudinary.com/console\n";
    echo "2. Update .env file with your credentials\n";
    echo "3. Run this test again\n";
}

echo "4. Test database connection: php test_database.php\n";
echo "5. Test Cloudinary: php test_cloudinary.php\n";
echo "6. Deploy to Vercel: vercel --prod\n";
?>