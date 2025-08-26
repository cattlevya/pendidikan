# Setup Aplikasi PHP di Vercel dengan Database

## 🚫 **Mengapa MySQL Tidak Bisa di Vercel?**
- Vercel adalah platform serverless, bukan VPS
- Tidak ada persistent storage untuk MySQL server
- Vercel dirancang untuk aplikasi statis dan serverless functions

## ✅ **Solusi Database untuk Vercel:**

### **1. PlanetScale (Recommended)**
- MySQL compatible
- Serverless database
- Free tier tersedia
- Auto-scaling

### **2. Supabase**
- PostgreSQL database
- Real-time features
- Auth system built-in

### **3. Neon**
- PostgreSQL database
- Serverless
- Branching feature

## 🚀 **Setup PlanetScale Database:**

### **Step 1: Buat Akun PlanetScale**
1. Kunjungi [planetscale.com](https://planetscale.com)
2. Sign up dengan GitHub
3. Buat database baru

### **Step 2: Buat Database**
```bash
# Install PlanetScale CLI
npm install -g pscale

# Login
pscale auth login

# Buat database
pscale database create romantic-web

# Buat branch
pscale branch create romantic-web main
```

### **Step 3: Jalankan Migration**
```bash
# Dapatkan connection string
pscale connect romantic-web main

# Jalankan SQL migration
mysql -h aws.connect.psdb.cloud -u [username] -p [database] < database.sql
```

## 🔧 **Setup Vercel:**

### **Step 1: Install Vercel CLI**
```bash
npm install -g vercel
```

### **Step 2: Login ke Vercel**
```bash
vercel login
```

### **Step 3: Set Environment Variables**
```bash
# Set database credentials
vercel env add DB_HOST
vercel env add DB_USER
vercel env add DB_PASS
vercel env add DB_NAME
```

### **Step 4: Deploy**
```bash
vercel --prod
```

## 📁 **File yang Dibuat untuk Vercel:**

| File | Deskripsi |
|------|-----------|
| `vercel.json` | Konfigurasi Vercel |
| `config_vercel.php` | Konfigurasi dengan environment variables |
| `vercel_database.php` | Database class dengan fallback JSON |
| `update_for_vercel.php` | Script update file PHP |

## 🔄 **Cara Kerja Hybrid System:**

### **Database Priority:**
1. **PlanetScale** (jika tersedia)
2. **JSON Files** (fallback)

### **Keuntungan:**
- ✅ Bisa deploy ke Vercel
- ✅ Data tetap tersimpan
- ✅ Fallback otomatis
- ✅ Tidak perlu server

## 📋 **Langkah-langkah Setup Lengkap:**

### **1. Update File PHP**
```bash
php update_for_vercel.php
```

### **2. Buat PlanetScale Database**
```bash
# Install CLI
npm install -g pscale

# Login dan buat database
pscale auth login
pscale database create romantic-web
pscale branch create romantic-web main
```

### **3. Dapatkan Connection String**
```bash
pscale connect romantic-web main
```

### **4. Set Environment Variables di Vercel**
```bash
vercel env add DB_HOST aws.connect.psdb.cloud
vercel env add DB_USER [your_username]
vercel env add DB_PASS [your_password]
vercel env add DB_NAME romantic-web
```

### **5. Deploy ke Vercel**
```bash
vercel --prod
```

## 🔐 **Environment Variables:**

### **Di Vercel Dashboard:**
```
DB_HOST=aws.connect.psdb.cloud
DB_USER=your_planetscale_username
DB_PASS=your_planetscale_password
DB_NAME=romantic-web
```

### **Di Local Development:**
```bash
# Buat file .env
echo "DB_HOST=aws.connect.psdb.cloud" > .env
echo "DB_USER=your_username" >> .env
echo "DB_PASS=your_password" >> .env
echo "DB_NAME=romantic-web" >> .env
```

## 🧪 **Test Database Connection:**

### **Test Script:**
```php
<?php
require_once 'config_vercel.php';
require_once 'vercel_database.php';

$db = getVercelDB();

if ($db->isConnected()) {
    echo "✅ Connected to PlanetScale";
} else {
    echo "⚠ Using JSON fallback";
}
?>
```

## 📊 **Monitoring:**

### **Di Admin Panel:**
- Status database ditampilkan
- Indikator koneksi
- Fallback notification

### **Di Vercel Dashboard:**
- Function logs
- Environment variables
- Deployment status

## 🔧 **Troubleshooting:**

### **Error: "Database connection failed"**
- Cek environment variables di Vercel
- Pastikan PlanetScale database aktif
- Cek connection string

### **Error: "PDO not available"**
- Vercel PHP runtime sudah include PDO
- Tidak perlu install manual

### **Error: "File upload failed"**
- Vercel tidak support file upload ke filesystem
- Gunakan cloud storage (AWS S3, Cloudinary)

## 💡 **Tips untuk Vercel:**

### **1. File Upload**
```php
// Gunakan cloud storage untuk upload file
// Contoh: AWS S3, Cloudinary, atau Supabase Storage
```

### **2. Session Management**
```php
// Vercel tidak support file-based sessions
// Gunakan database sessions atau Redis
```

### **3. Performance**
```php
// Cache static files
// Optimize database queries
// Use CDN for assets
```

## 🎯 **Alternatif Lain:**

### **1. Supabase + Vercel**
```bash
# Buat project di supabase.com
# Set environment variables
# Deploy ke Vercel
```

### **2. Neon + Vercel**
```bash
# Buat database di neon.tech
# Set connection string
# Deploy ke Vercel
```

### **3. MongoDB Atlas + Vercel**
```bash
# Buat cluster di mongodb.com
# Set connection string
# Deploy ke Vercel
```

## 🎉 **Hasil Akhir:**

Setelah setup selesai, aplikasi Anda akan:
- ✅ Berjalan di Vercel (serverless)
- ✅ Menggunakan PlanetScale database
- ✅ Fallback ke JSON jika database down
- ✅ Tidak perlu server maintenance
- ✅ Auto-scaling
- ✅ Global CDN

## 📞 **Support:**

- **PlanetScale Docs**: [planetscale.com/docs](https://planetscale.com/docs)
- **Vercel Docs**: [vercel.com/docs](https://vercel.com/docs)
- **PHP Runtime**: [github.com/vercel-community/php](https://github.com/vercel-community/php)