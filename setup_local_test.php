<?php
/**
 * Setup Local Testing Environment untuk Cloudinary
 * 
 * Script ini membantu setup environment lokal untuk testing Cloudinary
 * tanpa perlu deploy ke Vercel
 */

echo "=== Setup Local Testing Environment ===\n\n";

// Check if .env file exists
if (!file_exists('.env')) {
    echo "1. Creating .env file from .env.example...\n";
    if (file_exists('.env.example')) {
        copy('.env.example', '.env');
        echo "✅ .env file created\n";
    } else {
        echo "❌ .env.example not found\n";
        exit;
    }
} else {
    echo "1. .env file already exists\n";
}

// Check if load_env.php exists
if (!file_exists('load_env.php')) {
    echo "2. Creating load_env.php...\n";
    $load_env_content = '<?php
// Load environment variables from .env file
if (file_exists(".env")) {
    $lines = file(".env", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, "=") !== false && strpos($line, "#") !== 0) {
            list($key, $value) = explode("=", $line, 2);
            $_ENV[trim($key)] = trim($value);
            putenv(trim($key) . "=" . trim($value));
        }
    }
}
?>';
    
    file_put_contents('load_env.php', $load_env_content);
    echo "✅ load_env.php created\n";
} else {
    echo "2. load_env.php already exists\n";
}

// Test environment loading
echo "\n3. Testing environment loading...\n";
require_once 'load_env.php';

$cloud_name = $_ENV['CLOUDINARY_CLOUD_NAME'] ?? '';
$api_key = $_ENV['CLOUDINARY_API_KEY'] ?? '';
$api_secret = $_ENV['CLOUDINARY_API_SECRET'] ?? '';
$upload_preset = $_ENV['CLOUDINARY_UPLOAD_PRESET'] ?? '';

echo "Cloud Name: " . ($cloud_name ? $cloud_name : '❌ Not set') . "\n";
echo "API Key: " . ($api_key ? '✅ Set' : '❌ Not set') . "\n";
echo "API Secret: " . ($api_secret ? '✅ Set' : '❌ Not set') . "\n";
echo "Upload Preset: " . ($upload_preset ? $upload_preset : '❌ Not set') . "\n";

if (!$cloud_name || !$api_key || !$api_secret) {
    echo "\n❌ Please update .env file with your Cloudinary credentials!\n";
    echo "\nEdit .env file and add:\n";
    echo "CLOUDINARY_CLOUD_NAME=your_cloud_name\n";
    echo "CLOUDINARY_API_KEY=your_api_key\n";
    echo "CLOUDINARY_API_SECRET=your_api_secret\n";
    echo "CLOUDINARY_UPLOAD_PRESET=romantic_web\n";
} else {
    echo "\n✅ Environment variables loaded successfully!\n";
}

// Test Cloudinary connection
echo "\n4. Testing Cloudinary connection...\n";
if (file_exists('cloud_storage.php')) {
    require_once 'cloud_storage.php';
    
    $cloudinary = new CloudStorage('cloudinary');
    
    // Create a test image (1x1 pixel PNG)
    $test_image_data = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
    $temp_file = tempnam(sys_get_temp_dir(), 'test_');
    file_put_contents($temp_file, $test_image_data);
    
    // Create fake file array
    $test_file = [
        'name' => 'test.png',
        'type' => 'image/png',
        'tmp_name' => $temp_file,
        'error' => 0,
        'size' => strlen($test_image_data)
    ];
    
    echo "Uploading test image...\n";
    $result = $cloudinary->uploadFile($test_file, 'test');
    
    if ($result['success']) {
        echo "✅ Upload successful!\n";
        echo "URL: " . $result['url'] . "\n";
        echo "Filename: " . $result['filename'] . "\n";
        
        if (isset($result['public_id'])) {
            echo "Public ID: " . $result['public_id'] . "\n";
            
            // Test delete
            echo "\n5. Testing delete...\n";
            $delete_result = $cloudinary->deleteFile($result['filename'], $result['public_id']);
            if ($delete_result['success']) {
                echo "✅ Delete successful!\n";
            } else {
                echo "❌ Delete failed\n";
            }
        }
    } else {
        echo "❌ Upload failed: " . ($result['error'] ?? 'Unknown error') . "\n";
    }
    
    // Clean up
    unlink($temp_file);
} else {
    echo "❌ cloud_storage.php not found\n";
}

echo "\n=== Setup completed ===\n";
echo "\nNext steps:\n";
echo "1. Update .env file with your Cloudinary credentials\n";
echo "2. Run: php test_cloudinary.php\n";
echo "3. Test upload in browser\n";
echo "4. Deploy to Vercel when ready\n";
?>