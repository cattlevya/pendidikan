<?php
require_once 'database.php';

echo "=== Database Connection Test ===\n\n";

try {
    // Test database connection
    echo "1. Testing database connection...\n";
    $db = getDB();
    echo "✓ Database connection successful!\n\n";
    
    // Test user functions
    echo "2. Testing user functions...\n";
    
    // Create a test user
    $testUsername = 'test_user_' . time();
    $testPassword = 'test123';
    
    $result = $db->createUser($testUsername, $testPassword, 'test@example.com');
    if ($result) {
        echo "✓ Test user created successfully\n";
    } else {
        echo "✗ Failed to create test user\n";
    }
    
    // Get user by username
    $user = $db->getUserByUsername($testUsername);
    if ($user) {
        echo "✓ User retrieved successfully\n";
    } else {
        echo "✗ Failed to retrieve user\n";
    }
    
    // Verify user password
    $verified = $db->verifyUser($testUsername, $testPassword);
    if ($verified) {
        echo "✓ User verification successful\n";
    } else {
        echo "✗ User verification failed\n";
    }
    
    echo "\n";
    
    // Test photo functions
    echo "3. Testing photo functions...\n";
    
    // Add a test photo
    $testFilename = 'test_photo_' . time() . '.jpg';
    $testCaption = 'Test photo caption';
    
    $result = $db->addPhoto($testFilename, $testCaption, $user['id']);
    if ($result) {
        echo "✓ Test photo added successfully\n";
    } else {
        echo "✗ Failed to add test photo\n";
    }
    
    // Get all photos
    $photos = $db->getAllPhotos();
    echo "✓ Retrieved " . count($photos) . " photos from database\n";
    
    // Delete test photo
    $result = $db->deletePhoto($testFilename);
    if ($result) {
        echo "✓ Test photo deleted successfully\n";
    } else {
        echo "✗ Failed to delete test photo\n";
    }
    
    echo "\n";
    
    // Test confession response functions
    echo "4. Testing confession response functions...\n";
    
    $result = $db->saveConfessionResponse('yes', $testUsername);
    if ($result) {
        echo "✓ Confession response saved successfully\n";
    } else {
        echo "✗ Failed to save confession response\n";
    }
    
    $response = $db->getLatestConfessionResponse();
    if ($response) {
        echo "✓ Latest confession response retrieved successfully\n";
    } else {
        echo "✗ Failed to retrieve confession response\n";
    }
    
    echo "\n";
    
    // Test session functions
    echo "5. Testing session functions...\n";
    
    $sessionId = 'test_session_' . time();
    $sessionData = ['test' => 'data'];
    
    $result = $db->saveSession($sessionId, $user['id'], '127.0.0.1', 'Test Browser', json_encode($sessionData));
    if ($result) {
        echo "✓ Session saved successfully\n";
    } else {
        echo "✗ Failed to save session\n";
    }
    
    $session = $db->getSession($sessionId);
    if ($session) {
        echo "✓ Session retrieved successfully\n";
    } else {
        echo "✗ Failed to retrieve session\n";
    }
    
    $result = $db->deleteSession($sessionId);
    if ($result) {
        echo "✓ Session deleted successfully\n";
    } else {
        echo "✗ Failed to delete session\n";
    }
    
    echo "\n";
    
    // Cleanup test data
    echo "6. Cleaning up test data...\n";
    
    // Note: We'll leave the test user in the database for inspection
    // You can manually delete it later if needed
    
    echo "✓ Test completed successfully!\n";
    echo "\nTest user created: $testUsername (password: $testPassword)\n";
    echo "You can manually delete this user from the database if needed.\n";
    
} catch (Exception $e) {
    echo "✗ Test failed: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== End of Test ===\n";
?>