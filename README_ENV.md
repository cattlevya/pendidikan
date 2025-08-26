# Environment Variables Setup

## 📁 **File yang Dibuat:**

| File | Deskripsi |
|------|-----------|
| `.env` | Environment variables untuk development |
| `.env.example` | Template untuk environment variables |
| `load_env.php` | Script untuk load environment variables |
| `config_vercel.php` | Konfigurasi yang menggunakan .env |
| `setup_vercel_env.php` | Script untuk setup Vercel env vars |
| `test_env.php` | Test environment variables |

## 🚀 **Quick Start:**

### **1. Copy Template**
```bash
cp .env.example .env
```

### **2. Edit .env File**
```bash
# Edit file .env dan isi dengan credentials Anda
nano .env
```

### **3. Test Environment**
```bash
php test_env.php
```

### **4. Setup Vercel**
```bash
php setup_vercel_env.php
```

## 🔧 **Environment Variables:**

### **Database Configuration:**
```env
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=romantic_web
```

### **Cloudinary Configuration:**
```env
CLOUDINARY_CLOUD_NAME=your_cloud_name_here
CLOUDINARY_API_KEY=your_api_key_here
CLOUDINARY_API_SECRET=your_api_secret_here
CLOUDINARY_UPLOAD_PRESET=romantic_web
```

### **Application Settings:**
```env
SITE_NAME=Portal Akademik
UPLOAD_DIR=photos/
MAX_FILE_SIZE=5242880
ALLOWED_EXTENSIONS=jpg,jpeg,png,gif
SESSION_TIMEOUT=3600
TIMEZONE=Asia/Jakarta
ERROR_REPORTING=1
DISPLAY_ERRORS=1
```

## 🔐 **Setup Cloudinary:**

### **Step 1: Buat Akun**
1. Kunjungi [cloudinary.com](https://cloudinary.com)
2. Sign up for free
3. Login ke dashboard

### **Step 2: Dapatkan Credentials**
Di dashboard Cloudinary, catat:
- **Cloud Name** (contoh: `dx123456`)
- **API Key** (contoh: `123456789012345`)
- **API Secret** (contoh: `abcdefghijklmnop`)

### **Step 3: Buat Upload Preset**
1. Settings → Upload
2. Scroll ke "Upload presets"
3. Add upload preset:
   - **Name**: `romantic_web`
   - **Signing Mode**: `Unsigned`
   - **Folder**: `romantic_web`

### **Step 4: Update .env File**
```env
CLOUDINARY_CLOUD_NAME=dx123456
CLOUDINARY_API_KEY=123456789012345
CLOUDINARY_API_SECRET=abcdefghijklmnop
CLOUDINARY_UPLOAD_PRESET=romantic_web
```

## 🚀 **Setup Vercel:**

### **Cara 1: Via Vercel CLI**
```bash
# Install Vercel CLI
npm install -g vercel

# Login
vercel login

# Set environment variables
vercel env add CLOUDINARY_CLOUD_NAME
vercel env add CLOUDINARY_API_KEY
vercel env add CLOUDINARY_API_SECRET
vercel env add CLOUDINARY_UPLOAD_PRESET
vercel env add DB_HOST
vercel env add DB_USER
vercel env add DB_PASS
vercel env add DB_NAME
```

### **Cara 2: Via Vercel Dashboard**
1. Buka [vercel.com/dashboard](https://vercel.com/dashboard)
2. Pilih project
3. Settings → Environment Variables
4. Tambahkan semua variables dari `.env`

## 🧪 **Testing:**

### **Test Environment Variables:**
```bash
php test_env.php
```

### **Test Database:**
```bash
php test_database.php
```

### **Test Cloudinary:**
```bash
php test_cloudinary.php
```

### **Test Vercel Setup:**
```bash
php setup_vercel_env.php
```

## 📋 **Values untuk Copy:**

### **Database:**
```
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=romantic_web
```

### **Cloudinary:**
```
CLOUDINARY_CLOUD_NAME=your_cloud_name_here
CLOUDINARY_API_KEY=your_api_key_here
CLOUDINARY_API_SECRET=your_api_secret_here
CLOUDINARY_UPLOAD_PRESET=romantic_web
```

### **Application:**
```
SITE_NAME=Portal Akademik
UPLOAD_DIR=photos/
MAX_FILE_SIZE=5242880
ALLOWED_EXTENSIONS=jpg,jpeg,png,gif
SESSION_TIMEOUT=3600
TIMEZONE=Asia/Jakarta
ERROR_REPORTING=1
DISPLAY_ERRORS=1
```

## 🔧 **Troubleshooting:**

### **Error: "Environment variables not set"**
- Pastikan file `.env` ada
- Cek format file (tidak ada spasi di sekitar `=`)
- Restart server setelah edit `.env`

### **Error: "Cloudinary not configured"**
- Pastikan semua 4 Cloudinary variables diset
- Cek credentials di dashboard Cloudinary
- Pastikan upload preset sudah dibuat

### **Error: "Database connection failed"**
- Cek database credentials
- Pastikan database server running
- Cek database name dan user permissions

## 📊 **Environment Status:**

### **Development:**
- ✅ Menggunakan file `.env`
- ✅ Local database
- ✅ Local file storage

### **Production (Vercel):**
- ✅ Menggunakan Vercel environment variables
- ✅ PlanetScale database (jika diset)
- ✅ Cloudinary storage (jika diset)

## 🎯 **Next Steps:**

1. **Update `.env`** dengan credentials Anda
2. **Test environment**: `php test_env.php`
3. **Setup Vercel**: `php setup_vercel_env.php`
4. **Deploy**: `vercel --prod`

## 📞 **Support:**

- **Cloudinary**: [cloudinary.com/documentation](https://cloudinary.com/documentation)
- **Vercel**: [vercel.com/docs](https://vercel.com/docs)
- **Environment Variables**: [12factor.net/config](https://12factor.net/config)