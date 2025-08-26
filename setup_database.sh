#!/bin/bash

# Script untuk setup database MySQL secara otomatis
# Jalankan dengan: bash setup_database.sh

echo "=== Setup Database MySQL untuk Aplikasi PHP ==="
echo ""

# Check if MySQL is installed
if ! command -v mysql &> /dev/null; then
    echo "❌ MySQL tidak terinstall. Installing MySQL..."
    
    # Detect OS and install MySQL
    if [[ "$OSTYPE" == "linux-gnu"* ]]; then
        if command -v apt &> /dev/null; then
            # Ubuntu/Debian
            sudo apt update
            sudo apt install -y mysql-server
            sudo systemctl start mysql
            sudo systemctl enable mysql
        elif command -v yum &> /dev/null; then
            # CentOS/RHEL
            sudo yum install -y mysql-server
            sudo systemctl start mysqld
            sudo systemctl enable mysqld
        else
            echo "❌ Package manager tidak dikenali. Install MySQL secara manual."
            exit 1
        fi
    else
        echo "❌ OS tidak didukung. Install MySQL secara manual."
        exit 1
    fi
else
    echo "✅ MySQL sudah terinstall"
fi

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "❌ PHP tidak terinstall. Installing PHP..."
    
    if command -v apt &> /dev/null; then
        sudo apt install -y php php-mysql
    elif command -v yum &> /dev/null; then
        sudo yum install -y php php-mysql
    fi
else
    echo "✅ PHP sudah terinstall"
fi

# Check if PHP PDO extension is loaded
if ! php -m | grep -q pdo; then
    echo "❌ PHP PDO extension tidak terload. Installing..."
    
    if command -v apt &> /dev/null; then
        sudo apt install -y php-mysql
    elif command -v yum &> /dev/null; then
        sudo yum install -y php-mysql
    fi
else
    echo "✅ PHP PDO extension sudah terload"
fi

echo ""
echo "=== Setup Database ==="

# Check if database.sql exists
if [ ! -f "database.sql" ]; then
    echo "❌ File database.sql tidak ditemukan"
    exit 1
fi

# Ask for MySQL root password
echo "Masukkan password MySQL root (tekan Enter jika tidak ada password):"
read -s MYSQL_ROOT_PASSWORD

# Create database
echo "Membuat database..."
if [ -z "$MYSQL_ROOT_PASSWORD" ]; then
    mysql -u root < database.sql
else
    mysql -u root -p"$MYSQL_ROOT_PASSWORD" < database.sql
fi

if [ $? -eq 0 ]; then
    echo "✅ Database berhasil dibuat"
else
    echo "❌ Gagal membuat database"
    exit 1
fi

echo ""
echo "=== Test Database Connection ==="

# Test database connection
echo "Testing koneksi database..."
php test_database.php

if [ $? -eq 0 ]; then
    echo "✅ Koneksi database berhasil"
else
    echo "❌ Koneksi database gagal"
    echo "Pastikan konfigurasi di config.php sudah benar"
    exit 1
fi

echo ""
echo "=== Migrasi Data ==="

# Check if JSON files exist and migrate
if [ -f "photos_data.json" ] || [ -f "confession_response.json" ]; then
    echo "Migrasi data dari JSON ke database..."
    php migrate_to_database.php
else
    echo "Tidak ada file JSON untuk dimigrasi"
fi

echo ""
echo "=== Update PHP Files ==="

# Update PHP files to use database
echo "Updating file PHP untuk menggunakan database..."
php update_files_for_database.php

echo ""
echo "=== Setup Selesai ==="
echo "✅ Database MySQL berhasil disetup!"
echo ""
echo "📝 Langkah selanjutnya:"
echo "1. Edit file config.php jika perlu mengubah konfigurasi database"
echo "2. Akses aplikasi melalui web browser"
echo "3. Login dengan admin/admin123 (ganti password di production)"
echo ""
echo "📚 Dokumentasi lengkap ada di DATABASE_SETUP.md"
echo "🔧 Jika ada masalah, jalankan: php test_database.php"