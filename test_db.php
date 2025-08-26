<?php
require_once 'config.php';
require_once 'Database.php';

echo "<h2>Database Connection Test</h2>";

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    if ($conn) {
        echo "<p style='color: green;'>✅ Database connected successfully!</p>";
        
        // Test queries
        $users = $db->fetchAll("SELECT * FROM users");
        echo "<h3>Users:</h3>";
        echo "<ul>";
        foreach ($users as $user) {
            echo "<li>" . htmlspecialchars($user['username']) . " - " . htmlspecialchars($user['email']) . "</li>";
        }
        echo "</ul>";
        
        $photos = $db->fetchAll("SELECT * FROM photos");
        echo "<h3>Photos:</h3>";
        echo "<p>Total photos: " . count($photos) . "</p>";
        
        $confessions = $db->fetchAll("SELECT * FROM confessions");
        echo "<h3>Confessions:</h3>";
        echo "<p>Total confessions: " . count($confessions) . "</p>";
        
    } else {
        echo "<p style='color: red;'>❌ Database connection failed!</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>