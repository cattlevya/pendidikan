# Panduan Setup Database MySQL

## Prerequisites
- MySQL/MariaDB server terinstall
- PHP dengan ekstensi PDO dan PDO_MySQL
- Akses ke command line MySQL

## Langkah-langkah Setup Database

### 1. Install MySQL Server (jika belum ada)

**Ubuntu/Debian:**
```bash
sudo apt update
sudo apt install mysql-server
sudo mysql_secure_installation
```

**CentOS/RHEL:**
```bash
sudo yum install mysql-server
sudo systemctl start mysqld
sudo mysql_secure_installation
```

### 2. Login ke MySQL
```bash
sudo mysql -u root -p
```

### 3. Buat Database dan Tabel
Jalankan file `database.sql` yang sudah dibuat:

```bash
mysql -u root -p < database.sql
```

Atau copy-paste isi file `database.sql` langsung di MySQL command line.

### 4. Konfigurasi Database
Edit file `config.php` dan sesuaikan konfigurasi database:

```php
define('DB_HOST', 'localhost');     // Host MySQL
define('DB_USER', 'root');          // Username MySQL
define('DB_PASS', 'your_password'); // Password MySQL
define('DB_NAME', 'romantic_web');  // Nama database
```

### 5. Buat User Database (Opsional, untuk keamanan)
```sql
CREATE USER 'romantic_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON romantic_web.* TO 'romantic_user'@'localhost';
FLUSH PRIVILEGES;
```

Kemudian update `config.php`:
```php
define('DB_USER', 'romantic_user');
define('DB_PASS', 'your_secure_password');
```

### 6. Migrasi Data dari JSON ke Database
Jika Anda sudah memiliki data di file JSON, jalankan script migrasi:

```bash
php migrate_to_database.php
```

### 7. Test Koneksi Database
Buat file test sederhana:

```php
<?php
require_once 'database.php';

try {
    $db = getDB();
    echo "Database connection successful!\n";
    
    // Test query
    $photos = $db->getAllPhotos();
    echo "Found " . count($photos) . " photos in database\n";
    
} catch (Exception $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
}
?>
```

## Struktur Database

### Tabel `users`
- `id` - Primary key
- `username` - Username unik
- `password` - Password yang di-hash
- `email` - Email user (opsional)
- `created_at` - Waktu pembuatan akun
- `updated_at` - Waktu update terakhir

### Tabel `photos`
- `id` - Primary key
- `filename` - Nama file foto
- `caption` - Caption foto
- `uploaded_by` - ID user yang upload (foreign key)
- `uploaded_at` - Waktu upload

### Tabel `confession_responses`
- `id` - Primary key
- `response` - Response ('yes' atau 'no')
- `username` - Username yang memberikan response
- `response_at` - Waktu response

### Tabel `sessions`
- `id` - Session ID
- `user_id` - ID user (foreign key)
- `ip_address` - IP address user
- `user_agent` - User agent browser
- `payload` - Data session
- `last_activity` - Waktu aktivitas terakhir

## Troubleshooting

### Error: "Access denied for user"
- Pastikan username dan password benar di `config.php`
- Pastikan user memiliki privilege untuk database `romantic_web`

### Error: "Database not found"
- Pastikan database `romantic_web` sudah dibuat
- Jalankan script `database.sql` terlebih dahulu

### Error: "PDO extension not loaded"
- Install ekstensi PDO MySQL:
  ```bash
  sudo apt install php-mysql  # Ubuntu/Debian
  sudo yum install php-mysql  # CentOS/RHEL
  ```
- Restart web server setelah install

### Error: "Connection refused"
- Pastikan MySQL server berjalan:
  ```bash
  sudo systemctl status mysql
  sudo systemctl start mysql  # jika tidak running
  ```

## Backup dan Restore

### Backup Database
```bash
mysqldump -u root -p romantic_web > backup_romantic_web.sql
```

### Restore Database
```bash
mysql -u root -p romantic_web < backup_romantic_web.sql
```

## Keamanan
1. Gunakan password yang kuat untuk database
2. Buat user database terpisah (bukan root)
3. Batasi akses database hanya dari localhost
4. Backup database secara berkala
5. Update MySQL server secara rutin