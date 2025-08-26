<?php
// Script untuk mengupdate file PHP agar menggunakan database
// Jalankan script ini setelah database sudah setup

echo "=== Updating PHP files to use database ===\n\n";

// Update gallery.php
echo "1. Updating gallery.php...\n";
$gallery_content = '<?php
session_start();
require_once "database.php";

// Check if user is logged in
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: index.php");
    exit;
}

$db = getDB();

// Handle photo upload
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["photo"])) {
    $upload_dir = "photos/";
    $caption = $_POST["caption"] ?? "";
    
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $file = $_FILES["photo"];
    $filename = time() . "_" . basename($file["name"]);
    $target_path = $upload_dir . $filename;
    
    if (move_uploaded_file($file["tmp_name"], $target_path)) {
        // Save photo info to database
        $userId = $_SESSION["user_id"] ?? null;
        if ($db->addPhoto($filename, $caption, $userId)) {
            $success_message = "Foto berhasil diupload!";
        } else {
            $error_message = "Gagal menyimpan data foto ke database.";
        }
    } else {
        $error_message = "Gagal mengupload foto.";
    }
}

// Load existing photos from database
$photos = $db->getAllPhotos();

// Get static photos from photos directory
$static_photos = [];
$photos_dir = "photos/";
if (is_dir($photos_dir)) {
    $files = scandir($photos_dir);
    foreach ($files as $file) {
        if (in_array(pathinfo($file, PATHINFO_EXTENSION), ["jpg", "jpeg", "png", "gif"])) {
            $static_photos[] = $file;
        }
    }
}
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
          <img src="photos/<?php echo htmlspecialchars($photo["filename"]); ?>" alt="Photo">
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
echo "✓ gallery.php updated\n";

// Update admin.php
echo "2. Updating admin.php...\n";
$admin_content = '<?php
session_start();
require_once "config.php";
require_once "database.php";

// Check if user is logged in
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: index.php");
    exit;
}

$db = getDB();

// Handle photo deletion
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_photo"])) {
    $filename = $_POST["delete_photo"];
    
    if ($db->deletePhoto($filename)) {
        // Delete the actual file
        $file_path = UPLOAD_DIR . $filename;
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        header("Location: admin.php?success=1");
        exit;
    } else {
        $error_message = "Gagal menghapus foto dari database.";
    }
}

// Load photos data from database
$photos = $db->getAllPhotos();

// Load confession response from database
$confession_response = $db->getLatestConfessionResponse();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - <?php echo SITE_NAME; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .nav {
            text-align: center;
            margin-bottom: 30px;
        }
        .nav a {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            background: #ff6b6b;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .nav a:hover {
            background: #ff5252;
        }
        .section {
            margin-bottom: 40px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .section h2 {
            color: #555;
            margin-top: 0;
        }
        .photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .photo-item {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
        }
        .photo-item img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .photo-item .caption {
            margin: 10px 0;
            font-size: 14px;
            color: #666;
        }
        .delete-btn {
            background: #ff6b6b;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }
        .delete-btn:hover {
            background: #ff5252;
        }
        .confession-response {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Panel</h1>
        
        <?php if (isset($_GET["success"])): ?>
            <div class="success-message">
                Foto berhasil dihapus!
            </div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        
        <div class="nav">
            <a href="gallery.php">Galeri</a>
            <a href="confess.php">Confession</a>
            <a href="logout.php">Logout</a>
        </div>
        
        <div class="section">
            <h2>Foto yang Diupload (<?php echo count($photos); ?>)</h2>
            <div class="photo-grid">
                <?php foreach ($photos as $photo): ?>
                    <div class="photo-item">
                        <img src="photos/<?php echo htmlspecialchars($photo["filename"]); ?>" alt="Photo">
                        <?php if (!empty($photo["caption"])): ?>
                            <div class="caption"><?php echo htmlspecialchars($photo["caption"]); ?></div>
                        <?php endif; ?>
                        <div style="font-size: 12px; color: #999; margin: 5px 0;">
                            <?php echo date("d/m/Y H:i", strtotime($photo["uploaded_at"])); ?>
                            <?php if (!empty($photo["uploaded_by_name"])): ?>
                                <br>by <?php echo htmlspecialchars($photo["uploaded_by_name"]); ?>
                            <?php endif; ?>
                        </div>
                        <form method="POST" style="margin-top: 10px;">
                            <button type="submit" name="delete_photo" value="<?php echo htmlspecialchars($photo["filename"]); ?>" 
                                    class="delete-btn" onclick="return confirm(\'Yakin ingin menghapus foto ini?\')">
                                Hapus
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="section">
            <h2>Confession Response</h2>
            <?php if ($confession_response): ?>
                <div class="confession-response">
                    <strong>Response:</strong> <?php echo strtoupper($confession_response["response"]); ?><br>
                    <strong>Username:</strong> <?php echo htmlspecialchars($confession_response["username"]); ?><br>
                    <strong>Time:</strong> <?php echo date("d/m/Y H:i:s", strtotime($confession_response["response_at"])); ?>
                </div>
            <?php else: ?>
                <p>Belum ada response confession.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>';

file_put_contents("admin.php", $admin_content);
echo "✓ admin.php updated\n";

// Update confess.php
echo "3. Updating confess.php...\n";
$confess_content = '<?php
session_start();
require_once "database.php";

// Check if user is logged in
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: index.php");
    exit;
}

$db = getDB();

// Handle confession response
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $response = $_POST["response"] ?? "";
    
    // Save the response to database
    if ($db->saveConfessionResponse($response, $_SESSION["username"])) {
        if ($response === "yes") {
            header("Location: confess.php?result=yes");
            exit;
        } else {
            header("Location: confess.php?result=no");
            exit;
        }
    } else {
        $error_message = "Gagal menyimpan response.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pertanyaan Penting</title>
    <link rel="stylesheet" href="confess-style.css">
</head>
<body>
    <div class="confess-container">
        <div class="confess-content">
            <?php if (isset($_GET["result"]) && $_GET["result"] === "yes"): ?>
                <!-- Celebration result -->
                <div class="celebration">
                    🐱 😺 😻 😸 😽 🐾
                </div>
                <h1 class="question">IHHH REAL KAHHHH, HAII PACAR</h1>
                <div style="text-align: center; margin-top: 30px;">
                    <a href="gallery.php" style="color: #ff6b6b; text-decoration: none; font-size: 18px;">
                        ← Kembali ke Galeri
                    </a>
                </div>
            <?php elseif (isset($_GET["result"]) && $_GET["result"] === "no"): ?>
                <!-- No result -->
                <h1 class="question">😢 Aku mengerti...</h1>
                <p style="text-align: center; margin-top: 20px; font-size: 18px;">
                    Terima kasih sudah jujur. Aku tetap akan selalu ada untukmu.
                </p>
                <div style="text-align: center; margin-top: 30px;">
                    <a href="gallery.php" style="color: #ff6b6b; text-decoration: none; font-size: 18px;">
                        ← Kembali ke Galeri
                    </a>
                </div>
            <?php else: ?>
                <!-- Original question -->
                <h1 class="question">Warning: saying yes will make you the happiest girl alive (and me the luckiest). Do you accept?</h1>
                
                <?php if (isset($error_message)): ?>
                    <div style="color: #ff6b6b; text-align: center; margin: 20px 0;">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" style="text-align: center;">
                    <div class="buttons-container">
                        <button type="submit" name="response" value="yes" class="yes-btn">YES</button>
                        <button type="submit" name="response" value="no" class="no-btn">NO</button>
                    </div>
                </form>
            <?php endif; ?>
            
            <div class="floating-hearts" id="floatingHearts"></div>
        </div>
    </div>

    <script src="confess-script.js"></script>
</body>
</html>';

file_put_contents("confess.php", $confess_content);
echo "✓ confess.php updated\n";

echo "\n=== File updates completed ===\n";
echo "All PHP files have been updated to use the MySQL database.\n";
echo "Make sure to run the migration script first: php migrate_to_database.php\n";
?>