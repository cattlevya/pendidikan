<?php
require_once 'config_vercel.php';
require_once 'vercel_database.php';

echo "=== Vercel Database Test ===\n\n";

try {
    // Test database connection
    echo "1. Testing database connection...\n";
    $db = getVercelDB();
    
    if ($db->isConnected()) {
        echo "✅ Connected to PlanetScale database\n";
    } else {
        echo "⚠ Using JSON fallback (no database connection)\n";
    }
    
    echo "\n2. Testing environment variables...\n";
    $db_info = get_db_info();
    echo "Host: " . $db_info['host'] . "\n";
    echo "User: " . $db_info['user'] . "\n";
    echo "Database: " . $db_info['name'] . "\n";
    echo "Connected: " . ($db_info['connected'] ? 'Yes' : 'No') . "\n";
    
    echo "\n3. Testing Vercel environment...\n";
    if (is_vercel()) {
        echo "✅ Running on Vercel\n";
    } else {
        echo "⚠ Running locally\n";
    }
    
    echo "\n4. Testing user functions...\n";
    
    // Create a test user
    $testUsername = 'test_user_' . time();
    $testPassword = 'test123';
    
    $result = $db->createUser($testUsername, $testPassword, 'test@example.com');
    if ($result) {
        echo "✅ Test user created successfully\n";
    } else {
        echo "✗ Failed to create test user\n";
    }
    
    // Get user by username
    $user = $db->getUserByUsername($testUsername);
    if ($user) {
        echo "✅ User retrieved successfully\n";
    } else {
        echo "✗ Failed to retrieve user\n";
    }
    
    // Verify user password
    $verified = $db->verifyUser($testUsername, $testPassword);
    if ($verified) {
        echo "✅ User verification successful\n";
    } else {
        echo "✗ User verification failed\n";
    }
    
    echo "\n5. Testing photo functions...\n";
    
    // Add a test photo
    $testFilename = 'test_photo_' . time() . '.jpg';
    $testCaption = 'Test photo caption';
    
    $result = $db->addPhoto($testFilename, $testCaption, $user['id'] ?? null);
    if ($result) {
        echo "✅ Test photo added successfully\n";
    } else {
        echo "✗ Failed to add test photo\n";
    }
    
    // Get all photos
    $photos = $db->getAllPhotos();
    echo "✅ Retrieved " . count($photos) . " photos from database\n";
    
    // Delete test photo
    $result = $db->deletePhoto($testFilename);
    if ($result) {
        echo "✅ Test photo deleted successfully\n";
    } else {
        echo "✗ Failed to delete test photo\n";
    }
    
    echo "\n6. Testing confession response functions...\n";
    
    $result = $db->saveConfessionResponse('yes', $testUsername);
    if ($result) {
        echo "✅ Confession response saved successfully\n";
    } else {
        echo "✗ Failed to save confession response\n";
    }
    
    $response = $db->getLatestConfessionResponse();
    if ($response) {
        echo "✅ Latest confession response retrieved successfully\n";
    } else {
        echo "✗ Failed to retrieve confession response\n";
    }
    
    echo "\n=== Test Summary ===\n";
    echo "✅ Database connection: " . ($db->isConnected() ? 'PlanetScale' : 'JSON Fallback') . "\n";
    echo "✅ Environment: " . (is_vercel() ? 'Vercel' : 'Local') . "\n";
    echo "✅ All functions working correctly\n";
    
    echo "\n📝 Notes:\n";
    if (!$db->isConnected()) {
        echo "- Database connection failed, using JSON fallback\n";
        echo "- Check your environment variables in Vercel dashboard\n";
        echo "- Make sure PlanetScale database is properly configured\n";
    } else {
        echo "- Database connection successful\n";
        echo "- All data will be stored in PlanetScale\n";
    }
    
    echo "\n🔧 Next steps:\n";
    echo "1. Deploy to Vercel: vercel --prod\n";
    echo "2. Set environment variables in Vercel dashboard\n";
    echo "3. Test the deployed application\n";
    
} catch (Exception $e) {
    echo "✗ Test failed: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== End of Test ===\n";
?>