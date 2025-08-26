<?php
// Simple test untuk debugging
echo "=== Simple Cloudinary Test ===\n\n";

// Test 1: Check if PHP is working
echo "1. PHP Version: " . phpversion() . "\n";
echo "2. Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "\n";

// Test 2: Check environment variables
echo "\n3. Environment Variables:\n";
$env_vars = [
    'CLOUDINARY_CLOUD_NAME',
    'CLOUDINARY_API_KEY', 
    'CLOUDINARY_API_SECRET',
    'CLOUDINARY_UPLOAD_PRESET'
];

foreach ($env_vars as $var) {
    $value = $_ENV[$var] ?? getenv($var) ?? 'NOT SET';
    echo "   $var: " . ($value === 'NOT SET' ? '❌ NOT SET' : '✅ Set') . "\n";
}

// Test 3: Check if cloud_storage.php exists
echo "\n4. File Check:\n";
if (file_exists('cloud_storage.php')) {
    echo "   cloud_storage.php: ✅ Exists\n";
} else {
    echo "   cloud_storage.php: ❌ Not found\n";
}

// Test 4: Try to include cloud_storage.php
echo "\n5. Include Test:\n";
try {
    require_once 'cloud_storage.php';
    echo "   cloud_storage.php: ✅ Successfully included\n";
} catch (Exception $e) {
    echo "   cloud_storage.php: ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== Test completed ===\n";
?>