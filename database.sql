-- Database creation script for Romantic Web Application
-- Created for Portal Akademik

-- Create database
CREATE DATABASE IF NOT EXISTS romantic_web CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Use the database
USE romantic_web;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create photos table
CREATE TABLE IF NOT EXISTS photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    caption TEXT,
    uploaded_by INT,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Create confession_responses table
CREATE TABLE IF NOT EXISTS confession_responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    response ENUM('yes', 'no') NOT NULL,
    username VARCHAR(50),
    response_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create sessions table (optional, for better session management)
CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(128) PRIMARY KEY,
    user_id INT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    payload TEXT,
    last_activity INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert default admin user (password: admin123)
-- You should change this password in production
INSERT INTO users (username, password, email) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com')
ON DUPLICATE KEY UPDATE username=username;

-- Create indexes for better performance
CREATE INDEX idx_photos_uploaded_at ON photos(uploaded_at);
CREATE INDEX idx_confession_responses_response_at ON confession_responses(response_at);
CREATE INDEX idx_sessions_last_activity ON sessions(last_activity);