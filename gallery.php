<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Handle photo upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $upload_dir = 'photos/';
    $caption = $_POST['caption'] ?? '';
    
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $file = $_FILES['photo'];
    $filename = time() . '_' . basename($file['name']);
    $target_path = $upload_dir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        // Save photo info to a simple JSON file
        $photos_file = 'photos_data.json';
        $photos = [];
        
        if (file_exists($photos_file)) {
            $photos = json_decode(file_get_contents($photos_file), true) ?? [];
        }
        
        $photos[] = [
            'filename' => $filename,
            'caption' => $caption,
            'uploaded_at' => date('Y-m-d H:i:s')
        ];
        
        file_put_contents($photos_file, json_encode($photos, JSON_PRETTY_PRINT));
        $success_message = 'Foto berhasil diupload!';
    } else {
        $error_message = 'Gagal mengupload foto.';
    }
}

// Load existing photos
$photos = [];
$photos_file = 'photos_data.json';
if (file_exists($photos_file)) {
    $photos = json_decode(file_get_contents($photos_file), true) ?? [];
}

// Get static photos from photos directory
$static_photos = [];
$photos_dir = 'photos/';
if (is_dir($photos_dir)) {
    $files = scandir($photos_dir);
    foreach ($files as $file) {
        if (in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif'])) {
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
      Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?>! 
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
    <small class="hint">Foto akan disimpan di server dan ditampilkan di galeri.</small>
  </section>

  <main class="px-feed" id="feed">
    <!-- Dynamic photos from database -->
    <?php foreach ($photos as $photo): ?>
    <article class="px-post">
      <figure class="px-card">
        <img src="photos/<?php echo htmlspecialchars($photo['filename']); ?>" alt="Uploaded photo">
      </figure>
      <figcaption class="px-caption"><?php echo htmlspecialchars($photo['caption']); ?></figcaption>
    </article>
    <?php endforeach; ?>
    
    <!-- Static photos -->
    <?php foreach ($static_photos as $index => $photo): ?>
    <article class="px-post">
      <figure class="px-card">
        <img src="photos/<?php echo htmlspecialchars($photo); ?>" alt="pic<?php echo $index + 1; ?>">
      </figure>
      <figcaption class="px-caption">
        <?php 
        // Default captions for static photos
        $captions = [
            'my first move (aslinya first move tu yg minjem helm dan ngasi coklatt, tp tidak potooo)',
            'second move!! waktu km mw belajar strukdat',
            'our first hangout!!',
            'first flower I gave!',
            'soOOOo cuteee',
            'our very first selfie!',
            'jogging pertamaa',
            'lucuuuu bgt sayangggg',
            'fotbar paling aku sukaaa',
            'mam melon',
            'you have me sayanggg, whenever you cry',
            'INIIII LUCUUU BGTTT SAYANG AKK',
            'maaf ya sayang aku belum nemu waktu buat confess langsung. abis ngerjain ini juga aku ada raplenn, dan besok kamu pulangg. jadi aku mutusin buat ini dulu. but hope you like it cantikk'
        ];
        echo htmlspecialchars($captions[$index] ?? 'Beautiful memory');
        ?>
      </figcaption>
    </article>
    <?php endforeach; ?>
  </main>

  <div style="text-align: center; margin: 20px;">
    <a href="confess.php" class="px-btn" style="display: inline-block; text-decoration: none; background: #ff6b6b; color: white; padding: 10px 20px; border-radius: 5px;">
      Lanjut ke Pertanyaan Penting ♡
    </a>
  </div>

  <script src="gallery.js"></script>
</body>
</html>