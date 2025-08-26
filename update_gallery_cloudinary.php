<?php
// Script untuk mengupdate gallery.php agar menggunakan Cloudinary
echo "=== Updating gallery.php for Cloudinary ===\n\n";

$gallery_content = '<?php
session_start();
require_once "config_vercel.php";
require_once "vercel_database.php";
require_once "cloud_storage.php";

// Check if user is logged in
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: index.php");
    exit;
}

$db = getVercelDB();
$cloudStorage = getCloudStorage("cloudinary");

// Handle photo upload
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["photo"])) {
    $caption = $_POST["caption"] ?? "";
    $file = $_FILES["photo"];
    
    // Validate file
    if (!is_valid_image($file)) {
        $error_message = "File tidak valid atau terlalu besar.";
    } else {
        // Upload to Cloudinary
        $upload_result = $cloudStorage->uploadFile($file, "romantic_web");
        
        if ($upload_result["success"]) {
            // Save photo info to database
            $userId = $_SESSION["user_id"] ?? null;
            $filename = $upload_result["filename"];
            $url = $upload_result["url"];
            $public_id = $upload_result["public_id"] ?? null;
            
            if ($db->addPhoto($filename, $caption, $userId, $url, $public_id)) {
                $success_message = "Foto berhasil diupload!";
            } else {
                $error_message = "Gagal menyimpan data foto ke database.";
            }
        } else {
            $error_message = "Gagal mengupload foto: " . ($upload_result["error"] ?? "Unknown error");
        }
    }
}

// Load existing photos from database
$photos = $db->getAllPhotos();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Our Pixel Gallery</title>
  <link rel="stylesheet" href="gallery.css">
</head>
<body>
  <header class="px-header">
    <div class="brand">♡ Pixelgram</div>
    <div class="user-info">
      Selamat datang, <?php echo htmlspecialchars($_SESSION["username"]); ?>! 
      <a href="logout.php" style="color: #ff6b6b; text-decoration: none; margin-left: 10px;">Logout</a>
    </div>
  </header>

  <section class="px-uploader">
    <?php if (isset($success_message)): ?>
        <div class="success-message" style="color: #51cf66; background: rgba(81, 207, 102, 0.1); padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($error_message)): ?>
        <div class="error-message" style="color: #ff6b6b; background: rgba(255, 107, 107, 0.1); padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data">
      <label class="px-file">
        <input type="file" name="photo" accept="image/*" required>
        <span>Select Photo</span>
      </label>
      <input type="text" name="caption" class="px-text" placeholder="Write a caption..." maxlength="140">
      <button type="submit" class="px-btn">Upload</button>
    </form>
  </section>

  <section class="px-gallery">
    <div class="px-grid">
      <?php foreach ($photos as $photo): ?>
        <div class="px-item">
          <img src="<?php echo htmlspecialchars($photo["url"] ?? "photos/" . $photo["filename"]); ?>" alt="Photo">
          <?php if (!empty($photo["caption"])): ?>
            <div class="px-caption"><?php echo htmlspecialchars($photo["caption"]); ?></div>
          <?php endif; ?>
          <div class="px-meta">
            <small>Uploaded: <?php echo date("M d, Y", strtotime($photo["uploaded_at"])); ?></small>
            <?php if (!empty($photo["uploaded_by_name"])): ?>
              <small>by <?php echo htmlspecialchars($photo["uploaded_by_name"]); ?></small>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <script src="gallery.js"></script>
</body>
</html>';

file_put_contents("gallery.php", $gallery_content);
echo "✓ gallery.php updated for Cloudinary\n";

// Update vercel_database.php to support URL and public_id
echo "2. Updating vercel_database.php...\n";
$database_content = '<?php
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
?>';

file_put_contents("vercel_database.php", $database_content);
echo "✓ vercel_database.php updated\n";

echo "\n=== Update completed ===\n";
echo "Gallery now supports Cloudinary upload!\n";
echo "\nNext steps:\n";
echo "1. Set Cloudinary environment variables in Vercel\n";
echo "2. Test upload: php test_cloudinary.php\n";
echo "3. Deploy: vercel --prod\n";
?>