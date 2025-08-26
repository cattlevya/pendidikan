# Setup Database MySQL untuk Aplikasi PHP

## 📋 Overview
Aplikasi PHP ini sudah diupdate untuk menggunakan database MySQL. Berikut adalah langkah-langkah untuk setup database.

## 🚀 Langkah-langkah Setup

### 1. Install MySQL Server
```bash
# Ubuntu/Debian
sudo apt update
sudo apt install mysql-server
sudo mysql_secure_installation

# CentOS/RHEL
sudo yum install mysql-server
sudo systemctl start mysqld
sudo mysql_secure_installation
```

### 2. Buat Database
```bash
# Login ke MySQL
sudo mysql -u root -p

# Jalankan script database
mysql -u root -p < database.sql
```

### 3. Konfigurasi Database
Edit file `config.php` dan sesuaikan:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');          // atau user yang Anda buat
define('DB_PASS', 'your_password'); // password MySQL Anda
define('DB_NAME', 'romantic_web');
```

### 4. Test Koneksi Database
```bash
php test_database.php
```

### 5. Migrasi Data (jika ada data JSON)
```bash
php migrate_to_database.php
```

### 6. Update File PHP
```bash
php update_files_for_database.php
```

## 📁 File yang Dibuat

| File | Deskripsi |
|------|-----------|
| `database.sql` | Script untuk membuat database dan tabel |
| `database.php` | Class untuk koneksi dan operasi database |
| `migrate_to_database.php` | Script migrasi data dari JSON ke MySQL |
| `test_database.php` | Script test koneksi database |
| `update_files_for_database.php` | Script update file PHP untuk menggunakan database |
| `DATABASE_SETUP.md` | Panduan lengkap setup database |

## 🗄️ Struktur Database

### Tabel `users`
- Menyimpan data user (username, password, email)

### Tabel `photos`
- Menyimpan data foto (filename, caption, uploaded_by, uploaded_at)

### Tabel `confession_responses`
- Menyimpan response confession (yes/no, username, timestamp)

### Tabel `sessions`
- Menyimpan data session user (opsional)

## 🔧 Troubleshooting

### Error: "Access denied for user"
- Pastikan username dan password benar di `config.php`
- Pastikan user memiliki privilege untuk database

### Error: "Database not found"
- Jalankan `database.sql` terlebih dahulu
- Pastikan nama database sesuai di `config.php`

### Error: "PDO extension not loaded"
```bash
sudo apt install php-mysql  # Ubuntu/Debian
sudo yum install php-mysql  # CentOS/RHEL
```

## 📊 Keuntungan Menggunakan Database

1. **Data Terstruktur**: Data tersimpan dengan struktur yang jelas
2. **Query Fleksibel**: Bisa melakukan query kompleks
3. **Backup Mudah**: Bisa backup/restore dengan mudah
4. **Multi-user**: Mendukung multiple user dengan data terpisah
5. **Keamanan**: Password di-hash, SQL injection protection
6. **Scalability**: Bisa handle data yang besar

## 🔐 Keamanan

- Password user di-hash menggunakan `password_hash()`
- Menggunakan prepared statements untuk mencegah SQL injection
- Session management yang aman
- Input sanitization

## 📝 Catatan

- File JSON lama akan di-backup dengan ekstensi `.backup`
- Data yang sudah ada akan dimigrasi ke database
- Aplikasi akan tetap berfungsi seperti sebelumnya
- Admin user default: `admin` / `admin123` (ganti password di production)

## 🆘 Bantuan

Jika mengalami masalah, cek file `DATABASE_SETUP.md` untuk panduan lengkap atau jalankan `test_database.php` untuk debugging.