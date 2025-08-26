# Summary: Setup Database MySQL untuk Aplikasi PHP

## 🎯 Tujuan
Mengubah aplikasi PHP yang menggunakan file JSON menjadi menggunakan database MySQL untuk performa dan keamanan yang lebih baik.

## 📁 File yang Dibuat

### 1. **database.sql**
- Script SQL untuk membuat database dan tabel
- Berisi struktur tabel: `users`, `photos`, `confession_responses`, `sessions`
- Menambahkan user admin default (admin/admin123)
- Membuat index untuk performa optimal

### 2. **database.php**
- Class Database dengan pattern Singleton
- Fungsi-fungsi untuk operasi CRUD
- Menggunakan PDO untuk keamanan (prepared statements)
- Password hashing dengan `password_hash()`

### 3. **migrate_to_database.php**
- Script untuk migrasi data dari JSON ke MySQL
- Backup file JSON lama dengan ekstensi `.backup`
- Migrasi data foto dan confession response

### 4. **test_database.php**
- Script test untuk memverifikasi koneksi database
- Test semua fungsi database
- Membuat user test untuk validasi

### 5. **update_files_for_database.php**
- Script untuk mengupdate file PHP yang ada
- Mengubah `gallery.php`, `admin.php`, `confess.php`
- Mengganti penggunaan JSON dengan database

### 6. **setup_database.sh**
- Script bash untuk setup otomatis
- Install MySQL dan PHP jika belum ada
- Jalankan semua script setup secara berurutan

### 7. **DATABASE_SETUP.md**
- Panduan lengkap setup database
- Troubleshooting guide
- Penjelasan struktur database

### 8. **README_DATABASE.md**
- Panduan singkat dan cepat
- Langkah-langkah setup step-by-step
- Overview keuntungan menggunakan database

## 🗄️ Struktur Database

### Tabel `users`
```sql
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- username (VARCHAR(50), UNIQUE)
- password (VARCHAR(255)) - di-hash
- email (VARCHAR(100))
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

### Tabel `photos`
```sql
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- filename (VARCHAR(255))
- caption (TEXT)
- uploaded_by (INT, FOREIGN KEY ke users.id)
- uploaded_at (TIMESTAMP)
```

### Tabel `confession_responses`
```sql
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- response (ENUM('yes', 'no'))
- username (VARCHAR(50))
- response_at (TIMESTAMP)
```

### Tabel `sessions`
```sql
- id (VARCHAR(128), PRIMARY KEY)
- user_id (INT, FOREIGN KEY ke users.id)
- ip_address (VARCHAR(45))
- user_agent (TEXT)
- payload (TEXT)
- last_activity (INT)
```

## 🚀 Cara Penggunaan

### Setup Otomatis (Recommended)
```bash
bash setup_database.sh
```

### Setup Manual
1. **Install MySQL dan PHP**
2. **Buat database**: `mysql -u root -p < database.sql`
3. **Test koneksi**: `php test_database.php`
4. **Migrasi data**: `php migrate_to_database.php`
5. **Update files**: `php update_files_for_database.php`

## 🔐 Keamanan

- **Password Hashing**: Menggunakan `password_hash()` dan `password_verify()`
- **SQL Injection Protection**: Prepared statements dengan PDO
- **Input Sanitization**: Fungsi `sanitize_input()` di config.php
- **Session Security**: Session management yang aman

## 📊 Keuntungan

1. **Data Terstruktur**: Relational database dengan foreign keys
2. **Query Fleksibel**: Bisa melakukan JOIN, WHERE, ORDER BY, dll
3. **Backup/Restore**: Mudah backup dengan `mysqldump`
4. **Multi-user**: Support multiple user dengan data terpisah
5. **Performance**: Index untuk query yang cepat
6. **Scalability**: Bisa handle data besar

## 🔧 Troubleshooting

### Error Umum
- **"Access denied"**: Cek username/password di config.php
- **"Database not found"**: Jalankan database.sql terlebih dahulu
- **"PDO not loaded"**: Install php-mysql extension

### Debug
```bash
php test_database.php  # Test koneksi dan fungsi
mysql -u root -p       # Akses database langsung
```

## 📝 Catatan Penting

- File JSON lama akan di-backup dengan `.backup`
- Data yang ada akan dimigrasi ke database
- Admin default: `admin` / `admin123` (ganti di production)
- Aplikasi tetap berfungsi seperti sebelumnya
- Semua fitur tetap sama, hanya backend yang berubah

## 🎉 Hasil Akhir

Setelah setup selesai, aplikasi PHP Anda akan:
- ✅ Menggunakan database MySQL
- ✅ Data tersimpan dengan struktur yang jelas
- ✅ Lebih aman dengan password hashing
- ✅ Lebih cepat dengan index database
- ✅ Mudah di-maintain dan di-backup
- ✅ Siap untuk scaling ke level yang lebih besar