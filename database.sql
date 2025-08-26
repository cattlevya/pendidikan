-- Database untuk Portal Akademik (Romantic Web)
-- Created by: AI Assistant
-- Date: 2024

-- Buat database
CREATE DATABASE IF NOT EXISTS romantic_web;
USE romantic_web;

-- Tabel users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    full_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel photos
CREATE TABLE photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    caption TEXT,
    file_size INT,
    file_type VARCHAR(50),
    uploaded_by INT,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Tabel confessions
CREATE TABLE confessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    response ENUM('yes', 'no') NOT NULL,
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Tabel sessions
CREATE TABLE sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    session_id VARCHAR(255) UNIQUE NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert default admin user
INSERT INTO users (username, password, email, full_name) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com', 'Administrator');

-- Insert sample photos (jika ada)
-- INSERT INTO photos (filename, original_name, caption, uploaded_by) VALUES 
-- ('pic1.jpg', 'pic1.jpg', 'my first move', 1),
-- ('pic2.jpg', 'pic2.jpg', 'second move!!', 1);

-- Buat indexes untuk performa
CREATE INDEX idx_photos_uploaded_by ON photos(uploaded_by);
CREATE INDEX idx_photos_uploaded_at ON photos(uploaded_at);
CREATE INDEX idx_confessions_user_id ON confessions(user_id);
CREATE INDEX idx_sessions_user_id ON sessions(user_id);
CREATE INDEX idx_sessions_expires_at ON sessions(expires_at);

-- Buat view untuk statistik
CREATE VIEW photo_stats AS
SELECT 
    COUNT(*) as total_photos,
    COUNT(DISTINCT uploaded_by) as unique_uploaders,
    MAX(uploaded_at) as latest_upload
FROM photos;

-- Buat view untuk user activity
CREATE VIEW user_activity AS
SELECT 
    u.username,
    u.full_name,
    COUNT(p.id) as photo_count,
    COUNT(c.id) as confession_count,
    u.created_at as joined_at
FROM users u
LEFT JOIN photos p ON u.id = p.uploaded_by
LEFT JOIN confessions c ON u.id = c.user_id
GROUP BY u.id, u.username, u.full_name, u.created_at;