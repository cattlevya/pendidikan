<?php
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
</html>