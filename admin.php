<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Handle photo deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_photo'])) {
    $filename = $_POST['delete_photo'];
    $photos_file = 'photos_data.json';
    
    if (file_exists($photos_file)) {
        $photos = json_decode(file_get_contents($photos_file), true) ?? [];
        $photos = array_filter($photos, function($photo) use ($filename) {
            return $photo['filename'] !== $filename;
        });
        file_put_contents($photos_file, json_encode(array_values($photos), JSON_PRETTY_PRINT));
    }
    
    // Delete the actual file
    $file_path = UPLOAD_DIR . $filename;
    if (file_exists($file_path)) {
        unlink($file_path);
    }
    
    header('Location: admin.php?success=1');
    exit;
}

// Load photos data
$photos = [];
$photos_file = 'photos_data.json';
if (file_exists($photos_file)) {
    $photos = json_decode(file_get_contents($photos_file), true) ?? [];
}

// Load confession response
$confession_response = null;
$response_file = 'confession_response.json';
if (file_exists($response_file)) {
    $confession_response = json_decode(file_get_contents($response_file), true);
}
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
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
        }
        .photo-item p {
            margin: 10px 0;
            font-size: 14px;
            color: #666;
        }
        .delete-btn {
            background: #ff4757;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
        }
        .delete-btn:hover {
            background: #ff3742;
        }
        .confession-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .success-message {
            background: #51cf66;
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Panel</h1>
        
        <div class="nav">
            <a href="gallery.php">← Kembali ke Galeri</a>
            <a href="logout.php">Logout</a>
        </div>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">
                Foto berhasil dihapus!
            </div>
        <?php endif; ?>
        
        <div class="section">
            <h2>Foto yang Diupload (<?php echo count($photos); ?>)</h2>
            <?php if (empty($photos)): ?>
                <p>Belum ada foto yang diupload.</p>
            <?php else: ?>
                <div class="photo-grid">
                    <?php foreach ($photos as $photo): ?>
                        <div class="photo-item">
                            <img src="<?php echo UPLOAD_DIR . htmlspecialchars($photo['filename']); ?>" alt="Photo">
                            <p><?php echo htmlspecialchars($photo['caption']); ?></p>
                            <p><small>Uploaded: <?php echo $photo['uploaded_at']; ?></small></p>
                            <form method="POST" style="display: inline;">
                                <button type="submit" name="delete_photo" value="<?php echo htmlspecialchars($photo['filename']); ?>" 
                                        class="delete-btn" onclick="return confirm('Yakin ingin menghapus foto ini?')">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="section">
            <h2>Status Confession</h2>
            <?php if ($confession_response): ?>
                <div class="confession-info">
                    <p><strong>Response:</strong> <?php echo $confession_response['response'] === 'yes' ? 'YES ♡' : 'NO'; ?></p>
                    <p><strong>Timestamp:</strong> <?php echo $confession_response['timestamp']; ?></p>
                    <p><strong>User:</strong> <?php echo htmlspecialchars($confession_response['username']); ?></p>
                </div>
            <?php else: ?>
                <p>Belum ada response confession.</p>
            <?php endif; ?>
        </div>
        
        <div class="section">
            <h2>Statistik</h2>
            <p><strong>Total Foto:</strong> <?php echo count($photos); ?></p>
            <p><strong>User Login:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <p><strong>Session Start:</strong> <?php echo date('Y-m-d H:i:s', $_SESSION['start_time'] ?? time()); ?></p>
        </div>
    </div>
</body>
</html>