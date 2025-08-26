<?php
require_once "config_vercel.php";

class VercelDatabase {
    private $connection;
    private static $instance = null;
    
    private function __construct() {
        try {
            // PlanetScale connection (MySQL compatible)
            $this->connection = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4;sslmode=require",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_SSL_CA => true
                ]
            );
        } catch (PDOException $e) {
            // Fallback to JSON if database connection fails
            error_log("Database connection failed: " . $e->getMessage());
            $this->connection = null;
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function isConnected() {
        return $this->connection !== null;
    }
    
    // User functions
    public function createUser($username, $password, $email = null) {
        if (!$this->isConnected()) {
            return $this->createUserJSON($username, $password, $email);
        }
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->connection->prepare(
            "INSERT INTO users (username, password, email) VALUES (?, ?, ?)"
        );
        return $stmt->execute([$username, $hashedPassword, $email]);
    }
    
    public function getUserByUsername($username) {
        if (!$this->isConnected()) {
            return $this->getUserByUsernameJSON($username);
        }
        
        $stmt = $this->connection->prepare(
            "SELECT * FROM users WHERE username = ?"
        );
        $stmt->execute([$username]);
        return $stmt->fetch();
    }
    
    public function verifyUser($username, $password) {
        $user = $this->getUserByUsername($username);
        if ($user && password_verify($password, $user["password"])) {
            return $user;
        }
        return false;
    }
    
    // Photo functions
    public function addPhoto($filename, $caption, $uploadedBy = null, $url = null, $public_id = null) {
        if (!$this->isConnected()) {
            return $this->addPhotoJSON($filename, $caption, $uploadedBy, $url, $public_id);
        }
        
        $stmt = $this->connection->prepare(
            "INSERT INTO photos (filename, caption, uploaded_by, url, public_id) VALUES (?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$filename, $caption, $uploadedBy, $url, $public_id]);
    }
    
    public function getAllPhotos() {
        if (!$this->isConnected()) {
            return $this->getAllPhotosJSON();
        }
        
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
        if (!$this->isConnected()) {
            return $this->deletePhotoJSON($filename);
        }
        
        $stmt = $this->connection->prepare(
            "DELETE FROM photos WHERE filename = ?"
        );
        return $stmt->execute([$filename]);
    }
    
    // Confession response functions
    public function saveConfessionResponse($response, $username) {
        if (!$this->isConnected()) {
            return $this->saveConfessionResponseJSON($response, $username);
        }
        
        $stmt = $this->connection->prepare(
            "INSERT INTO confession_responses (response, username) VALUES (?, ?)"
        );
        return $stmt->execute([$response, $username]);
    }
    
    public function getLatestConfessionResponse() {
        if (!$this->isConnected()) {
            return $this->getLatestConfessionResponseJSON();
        }
        
        $stmt = $this->connection->prepare(
            "SELECT * FROM confession_responses ORDER BY response_at DESC LIMIT 1"
        );
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // JSON Fallback Functions
    private function createUserJSON($username, $password, $email = null) {
        $users_file = "users_data.json";
        $users = [];
        
        if (file_exists($users_file)) {
            $users = json_decode(file_get_contents($users_file), true) ?? [];
        }
        
        // Check if user already exists
        foreach ($users as $user) {
            if ($user["username"] === $username) {
                return false;
            }
        }
        
        $users[] = [
            "id" => count($users) + 1,
            "username" => $username,
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "email" => $email,
            "created_at" => date("Y-m-d H:i:s")
        ];
        
        return file_put_contents($users_file, json_encode($users, JSON_PRETTY_PRINT)) !== false;
    }
    
    private function getUserByUsernameJSON($username) {
        $users_file = "users_data.json";
        if (!file_exists($users_file)) {
            return false;
        }
        
        $users = json_decode(file_get_contents($users_file), true) ?? [];
        foreach ($users as $user) {
            if ($user["username"] === $username) {
                return $user;
            }
        }
        return false;
    }
    
    private function addPhotoJSON($filename, $caption, $uploadedBy = null, $url = null, $public_id = null) {
        $photos_file = "photos_data.json";
        $photos = [];
        
        if (file_exists($photos_file)) {
            $photos = json_decode(file_get_contents($photos_file), true) ?? [];
        }
        
        $photos[] = [
            "id" => count($photos) + 1,
            "filename" => $filename,
            "caption" => $caption,
            "uploaded_by" => $uploadedBy,
            "url" => $url,
            "public_id" => $public_id,
            "uploaded_at" => date("Y-m-d H:i:s")
        ];
        
        return file_put_contents($photos_file, json_encode($photos, JSON_PRETTY_PRINT)) !== false;
    }
    
    private function getAllPhotosJSON() {
        $photos_file = "photos_data.json";
        if (!file_exists($photos_file)) {
            return [];
        }
        
        return json_decode(file_get_contents($photos_file), true) ?? [];
    }
    
    private function deletePhotoJSON($filename) {
        $photos_file = "photos_data.json";
        if (!file_exists($photos_file)) {
            return false;
        }
        
        $photos = json_decode(file_get_contents($photos_file), true) ?? [];
        $photos = array_filter($photos, function($photo) use ($filename) {
            return $photo["filename"] !== $filename;
        });
        
        return file_put_contents($photos_file, json_encode(array_values($photos), JSON_PRETTY_PRINT)) !== false;
    }
    
    private function saveConfessionResponseJSON($response, $username) {
        $response_file = "confession_response.json";
        $response_data = [
            "response" => $response,
            "username" => $username,
            "response_at" => date("Y-m-d H:i:s")
        ];
        
        return file_put_contents($response_file, json_encode($response_data, JSON_PRETTY_PRINT)) !== false;
    }
    
    private function getLatestConfessionResponseJSON() {
        $response_file = "confession_response.json";
        if (!file_exists($response_file)) {
            return false;
        }
        
        return json_decode(file_get_contents($response_file), true);
    }
}

// Helper function to get database instance
function getVercelDB() {
    return VercelDatabase::getInstance();
}
?>