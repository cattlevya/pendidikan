<?php
// Test Database Connection on Hosting
// Upload file ini ke hosting untuk test koneksi

require_once 'config.php';
require_once 'Database.php';

echo "<h2>Hosting Database Test</h2>";

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    if ($conn) {
        echo "<p style='color: green;'>✅ Database connected successfully!</p>";
        
        // Test basic queries
        $result = $db->fetchOne("SELECT COUNT(*) as count FROM users");
        echo "<p>Users in database: " . $result['count'] . "</p>";
        
        $result = $db->fetchOne("SELECT COUNT(*) as count FROM photos");
        echo "<p>Photos in database: " . $result['count'] . "</p>";
        
        $result = $db->fetchOne("SELECT COUNT(*) as count FROM confessions");
        echo "<p>Confessions in database: " . $result['count'] . "</p>";
        
        echo "<p style='color: green;'>✅ All tests passed! Database is ready.</p>";
        
    } else {
        echo "<p style='color: red;'>❌ Database connection failed!</p>";
        echo "<p>Check your database credentials in config.php</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<h3>Troubleshooting:</h3>";
    echo "<ul>";
    echo "<li>Check database credentials</li>";
    echo "<li>Make sure database exists</li>";
    echo "<li>Verify user permissions</li>";
    echo "<li>Check hosting MySQL version</li>";
    echo "</ul>";
}

// Show PHP info for debugging
echo "<h3>PHP Info:</h3>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>PDO MySQL: " . (extension_loaded('pdo_mysql') ? '✅ Enabled' : '❌ Disabled') . "</p>";
echo "<p>MySQL: " . (extension_loaded('mysqli') ? '✅ Enabled' : '❌ Disabled') . "</p>";
?>