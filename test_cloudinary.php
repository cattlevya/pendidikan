<?php
require_once 'cloud_storage.php';

echo "=== Cloudinary Setup Test ===\n\n";

// Test environment variables
echo "1. Checking environment variables...\n";
$cloud_name = $_ENV['CLOUDINARY_CLOUD_NAME'] ?? '';
$api_key = $_ENV['CLOUDINARY_API_KEY'] ?? '';
$api_secret = $_ENV['CLOUDINARY_API_SECRET'] ?? '';
$upload_preset = $_ENV['CLOUDINARY_UPLOAD_PRESET'] ?? '';

echo "Cloud Name: " . ($cloud_name ? $cloud_name : '❌ Not set') . "\n";
echo "API Key: " . ($api_key ? '✅ Set' : '❌ Not set') . "\n";
echo "API Secret: " . ($api_secret ? '✅ Set' : '❌ Not set') . "\n";
echo "Upload Preset: " . ($upload_preset ? $upload_preset : '❌ Not set') . "\n";

if (!$cloud_name || !$api_key || !$api_secret) {
    echo "\n❌ Environment variables not set!\n";
    echo "Please set the following in Vercel:\n";
    echo "- CLOUDINARY_CLOUD_NAME\n";
    echo "- CLOUDINARY_API_KEY\n";
    echo "- CLOUDINARY_API_SECRET\n";
    echo "- CLOUDINARY_UPLOAD_PRESET\n";
    exit;
}

echo "\n2. Testing Cloudinary connection...\n";
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
        echo "\n3. Testing delete...\n";
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

echo "\n=== Test completed ===\n";
?>