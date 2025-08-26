<?php
require_once 'config.php';

class Database {
    private $connection;
    private static $instance = null;
    
    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    // User functions
    public function createUser($username, $password, $email = null) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->connection->prepare(
            "INSERT INTO users (username, password, email) VALUES (?, ?, ?)"
        );
        return $stmt->execute([$username, $hashedPassword, $email]);
    }
    
    public function getUserByUsername($username) {
        $stmt = $this->connection->prepare(
            "SELECT * FROM users WHERE username = ?"
        );
        $stmt->execute([$username]);
        return $stmt->fetch();
    }
    
    public function verifyUser($username, $password) {
        $user = $this->getUserByUsername($username);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
    
    // Photo functions
    public function addPhoto($filename, $caption, $uploadedBy = null) {
        $stmt = $this->connection->prepare(
            "INSERT INTO photos (filename, caption, uploaded_by) VALUES (?, ?, ?)"
        );
        return $stmt->execute([$filename, $caption, $uploadedBy]);
    }
    
    public function getAllPhotos() {
        $stmt = $this->connection->prepare(
            "SELECT p.*, u.username as uploaded_by_name 
             FROM photos p 
             LEFT JOIN users u ON p.uploaded_by = u.id 
             ORDER BY p.uploaded_at DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function deletePhoto($filename) {
        $stmt = $this->connection->prepare(
            "DELETE FROM photos WHERE filename = ?"
        );
        return $stmt->execute([$filename]);
    }
    
    // Confession response functions
    public function saveConfessionResponse($response, $username) {
        $stmt = $this->connection->prepare(
            "INSERT INTO confession_responses (response, username) VALUES (?, ?)"
        );
        return $stmt->execute([$response, $username]);
    }
    
    public function getLatestConfessionResponse() {
        $stmt = $this->connection->prepare(
            "SELECT * FROM confession_responses ORDER BY response_at DESC LIMIT 1"
        );
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Session functions
    public function saveSession($sessionId, $userId, $ipAddress, $userAgent, $payload) {
        $stmt = $this->connection->prepare(
            "INSERT INTO sessions (id, user_id, ip_address, user_agent, payload, last_activity) 
             VALUES (?, ?, ?, ?, ?, ?) 
             ON DUPLICATE KEY UPDATE 
             payload = VALUES(payload), 
             last_activity = VALUES(last_activity)"
        );
        return $stmt->execute([$sessionId, $userId, $ipAddress, $userAgent, $payload, time()]);
    }
    
    public function getSession($sessionId) {
        $stmt = $this->connection->prepare(
            "SELECT * FROM sessions WHERE id = ?"
        );
        $stmt->execute([$sessionId]);
        return $stmt->fetch();
    }
    
    public function deleteSession($sessionId) {
        $stmt = $this->connection->prepare(
            "DELETE FROM sessions WHERE id = ?"
        );
        return $stmt->execute([$sessionId]);
    }
    
    public function cleanupOldSessions($timeout = 3600) {
        $stmt = $this->connection->prepare(
            "DELETE FROM sessions WHERE last_activity < ?"
        );
        return $stmt->execute([time() - $timeout]);
    }
}

// Helper function to get database instance
function getDB() {
    return Database::getInstance();
}
?>