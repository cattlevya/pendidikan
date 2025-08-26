<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Handle confession response
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = $_POST['response'] ?? '';
    
    // Save the response to a file
    $response_file = 'confession_response.json';
    $response_data = [
        'response' => $response,
        'timestamp' => date('Y-m-d H:i:s'),
        'username' => $_SESSION['username']
    ];
    
    file_put_contents($response_file, json_encode($response_data, JSON_PRETTY_PRINT));
    
    if ($response === 'yes') {
        header('Location: confess.php?result=yes');
        exit;
    } else {
        header('Location: confess.php?result=no');
        exit;
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
            <?php if (isset($_GET['result']) && $_GET['result'] === 'yes'): ?>
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
            <?php elseif (isset($_GET['result']) && $_GET['result'] === 'no'): ?>
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
</html>