# 🚀 Deploy Aplikasi PHP ke Vercel dengan Database

## 📋 **Overview**
Aplikasi PHP Anda sudah diupdate untuk berjalan di Vercel dengan database PlanetScale. Sistem ini menggunakan **hybrid approach** - jika database tersedia, gunakan database; jika tidak, fallback ke JSON files.

## ⚡ **Quick Start**

### **1. Update File PHP**
```bash
php update_for_vercel.php
```

### **2. Test Setup**
```bash
php test_vercel.php
```

### **3. Deploy ke Vercel**
```bash
vercel --prod
```

## 🗄️ **Database Options**

### **Option 1: PlanetScale (Recommended)**
- ✅ MySQL compatible
- ✅ Free tier
- ✅ Auto-scaling
- ✅ Serverless

### **Option 2: JSON Files (Fallback)**
- ✅ Tidak perlu setup database
- ✅ Data tersimpan di file
- ✅ Bisa langsung deploy

## 📁 **File yang Dibuat**

| File | Deskripsi |
|------|-----------|
| `vercel.json` | Konfigurasi Vercel |
| `config_vercel.php` | Konfigurasi dengan env vars |
| `vercel_database.php` | Database class dengan fallback |
| `update_for_vercel.php` | Script update file PHP |
| `test_vercel.php` | Test koneksi database |

## 🔧 **Setup PlanetScale (Optional)**

### **Step 1: Buat Akun**
1. Kunjungi [planetscale.com](https://planetscale.com)
2. Sign up dengan GitHub
3. Buat database baru

### **Step 2: Install CLI**
```bash
npm install -g pscale
pscale auth login
```

### **Step 3: Buat Database**
```bash
pscale database create romantic-web
pscale branch create romantic-web main
```

### **Step 4: Dapatkan Connection String**
```bash
pscale connect romantic-web main
```

### **Step 5: Set Environment Variables**
```bash
vercel env add DB_HOST aws.connect.psdb.cloud
vercel env add DB_USER [your_username]
vercel env add DB_PASS [your_password]
vercel env add DB_NAME romantic-web
```

## 🚀 **Deploy ke Vercel**

### **Step 1: Install Vercel CLI**
```bash
npm install -g vercel
```

### **Step 2: Login**
```bash
vercel login
```

### **Step 3: Deploy**
```bash
vercel --prod
```

## 🔄 **Cara Kerja Hybrid System**

### **Database Priority:**
1. **PlanetScale** (jika environment variables tersedia)
2. **JSON Files** (fallback otomatis)

### **Keuntungan:**
- ✅ Bisa deploy tanpa database
- ✅ Fallback otomatis
- ✅ Tidak ada downtime
- ✅ Mudah maintenance

## 📊 **Monitoring**

### **Di Admin Panel:**
- Status database ditampilkan
- Indikator koneksi
- Fallback notification

### **Di Vercel Dashboard:**
- Function logs
- Environment variables
- Deployment status

## 🔐 **Environment Variables**

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

## 🧪 **Testing**

### **Test Local:**
```bash
php test_vercel.php
```

### **Test Deployed:**
```bash
curl https://your-app.vercel.app/test_vercel.php
```

## 🔧 **Troubleshooting**

### **Error: "Database connection failed"**
- Cek environment variables di Vercel
- Pastikan PlanetScale database aktif
- Aplikasi akan fallback ke JSON

### **Error: "File upload failed"**
- Vercel tidak support file upload ke filesystem
- Gunakan cloud storage (AWS S3, Cloudinary)

### **Error: "Session not working"**
- Vercel tidak support file-based sessions
- Gunakan database sessions

## 💡 **Tips**

### **1. File Upload**
```php
// Gunakan cloud storage untuk upload file
// Contoh: AWS S3, Cloudinary, atau Supabase Storage
```

### **2. Performance**
```php
// Cache static files
// Optimize database queries
// Use CDN for assets
```

### **3. Security**
```php
// Set environment variables di Vercel
// Jangan hardcode credentials
// Use HTTPS
```

## 🎯 **Alternatif Database**

### **1. Supabase**
```bash
# Buat project di supabase.com
# Set environment variables
# Deploy ke Vercel
```

### **2. Neon**
```bash
# Buat database di neon.tech
# Set connection string
# Deploy ke Vercel
```

### **3. MongoDB Atlas**
```bash
# Buat cluster di mongodb.com
# Set connection string
# Deploy ke Vercel
```

## 🎉 **Hasil Akhir**

Setelah deploy, aplikasi Anda akan:
- ✅ Berjalan di Vercel (serverless)
- ✅ Menggunakan database (jika tersedia)
- ✅ Fallback ke JSON (jika database down)
- ✅ Tidak perlu server maintenance
- ✅ Auto-scaling
- ✅ Global CDN

## 📞 **Support**

- **Vercel Docs**: [vercel.com/docs](https://vercel.com/docs)
- **PlanetScale Docs**: [planetscale.com/docs](https://planetscale.com/docs)
- **PHP Runtime**: [github.com/vercel-community/php](https://github.com/vercel-community/php)

## 🚀 **Quick Commands**

```bash
# Update files untuk Vercel
php update_for_vercel.php

# Test setup
php test_vercel.php

# Deploy ke Vercel
vercel --prod

# Set environment variables
vercel env add DB_HOST aws.connect.psdb.cloud
vercel env add DB_USER your_username
vercel env add DB_PASS your_password
vercel env add DB_NAME romantic-web
```