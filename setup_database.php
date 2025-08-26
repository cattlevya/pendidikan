<?php
require_once 'config.php';
require_once 'Database.php';

echo "=== Database Setup for Portal Akademik ===\n\n";

try {
    // Test connection
    $db = new Database();
    $conn = $db->getConnection();
    
    if ($conn) {
        echo "✅ Database connection successful!\n\n";
        
        // Read and execute SQL file
        $sql_file = 'database.sql';
        if (file_exists($sql_file)) {
            $sql = file_get_contents($sql_file);
            
            // Split SQL into individual statements
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            
            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    try {
                        $conn->exec($statement);
                        echo "✅ Executed: " . substr($statement, 0, 50) . "...\n";
                    } catch (PDOException $e) {
                        if (strpos($e->getMessage(), 'already exists') === false) {
                            echo "❌ Error: " . $e->getMessage() . "\n";
                        } else {
                            echo "⚠️  Warning: " . $e->getMessage() . "\n";
                        }
                    }
                }
            }
            
            echo "\n✅ Database setup completed!\n";
            
            // Test queries
            echo "\n=== Testing Database ===\n";
            
            // Test users table
            $result = $db->fetchOne("SELECT COUNT(*) as count FROM users");
            echo "Users in database: " . $result['count'] . "\n";
            
            // Test photos table
            $result = $db->fetchOne("SELECT COUNT(*) as count FROM photos");
            echo "Photos in database: " . $result['count'] . "\n";
            
            // Test confessions table
            $result = $db->fetchOne("SELECT COUNT(*) as count FROM confessions");
            echo "Confessions in database: " . $result['count'] . "\n";
            
        } else {
            echo "❌ SQL file not found: $sql_file\n";
        }
        
    } else {
        echo "❌ Database connection failed!\n";
    }
    
} catch (Exception $e) {
    echo "❌ Setup failed: " . $e->getMessage() . "\n";
    echo "\nTroubleshooting:\n";
    echo "1. Make sure MySQL is running\n";
    echo "2. Check database credentials in config.php\n";
    echo "3. Create database manually: CREATE DATABASE romantic_web;\n";
    echo "4. Create user: CREATE USER 'romantic_user'@'localhost' IDENTIFIED BY 'password123';\n";
    echo "5. Grant privileges: GRANT ALL PRIVILEGES ON romantic_web.* TO 'romantic_user'@'localhost';\n";
}

echo "\n=== Setup Complete ===\n";
echo "You can now run the web application!\n";
echo "Default login: admin / password\n";
?>