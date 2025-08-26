<?php
session_start();

// Simple authentication logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Simple authentication - you can modify this as needed
    if ($username === 'admin' && $password === 'password') {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        header('Location: gallery.php');
        exit;
    } else {
        $error_message = 'Username atau password salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Akademik</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="flower-animations.css">
    <link rel="stylesheet" href="popup-style.css">
</head>
<body class="not-loaded">
    <div class="night"></div>
    <div class="container">
        <div class="login-form">
            <h2>Portal Akademik</h2>
            <p>Silakan login untuk melanjutkan</p>
            
            <?php if (isset($error_message)): ?>
                <div class="error-message" style="color: #ff6b6b; background: rgba(255, 107, 107, 0.1); padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="login-btn">Login</button>
            </form>
            
            <div class="footer">
                <p>© 2024 Portal Akademik. Semua hak dilindungi.</p>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>