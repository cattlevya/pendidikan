# Setup Cloudinary untuk File Upload di Vercel

## 🎯 **Mengapa Perlu Cloudinary?**

Vercel adalah platform serverless yang **TIDAK SUPPORT** file upload ke filesystem:
- File yang diupload akan **HILANG** setelah function selesai
- Tidak ada persistent storage
- Cloudinary menyediakan cloud storage untuk file

## 🚀 **Setup Cloudinary Step by Step:**

### **Step 1: Buat Akun Cloudinary**
1. Kunjungi [cloudinary.com](https://cloudinary.com)
2. Klik **"Sign Up For Free"**
3. Buat akun baru (email + password)
4. Login ke dashboard

### **Step 2: Dapatkan Credentials**
Setelah login, di dashboard Cloudinary Anda akan melihat:

```
Cloud Name: dx123456 (contoh)
API Key: 123456789012345 (contoh)
API Secret: abcdefghijklmnop (contoh)
```

**Catat ketiga informasi ini!**

### **Step 3: Buat Upload Preset**
1. Di dashboard Cloudinary, klik **Settings** → **Upload**
2. Scroll ke bagian **"Upload presets"**
3. Klik **"Add upload preset"**
4. Isi form:
   - **Preset name**: `romantic_web`
   - **Signing Mode**: `Unsigned`
   - **Folder**: `romantic_web`
5. Klik **Save**

### **Step 4: Set Environment Variables di Vercel**

#### **Cara 1: Via Vercel CLI**
```bash
# Install Vercel CLI
npm install -g vercel

# Login ke Vercel
vercel login

# Set environment variables
vercel env add CLOUDINARY_CLOUD_NAME
# Masukkan cloud name Anda (contoh: dx123456)

vercel env add CLOUDINARY_API_KEY
# Masukkan API key Anda

vercel env add CLOUDINARY_API_SECRET
# Masukkan API secret Anda

vercel env add CLOUDINARY_UPLOAD_PRESET
# Masukkan: romantic_web
```

#### **Cara 2: Via Vercel Dashboard**
1. Buka [vercel.com/dashboard](https://vercel.com/dashboard)
2. Pilih project Anda
3. Klik tab **Settings**
4. Klik **Environment Variables**
5. Tambahkan variables berikut:

```
Name: CLOUDINARY_CLOUD_NAME
Value: [cloud_name_anda]
Environment: Production, Preview, Development

Name: CLOUDINARY_API_KEY
Value: [api_key_anda]
Environment: Production, Preview, Development

Name: CLOUDINARY_API_SECRET
Value: [api_secret_anda]
Environment: Production, Preview, Development

Name: CLOUDINARY_UPLOAD_PRESET
Value: romantic_web
Environment: Production, Preview, Development
```

### **Step 5: Update Aplikasi**
```bash
# Update file PHP untuk menggunakan Cloudinary
php update_gallery_cloudinary.php
```

### **Step 6: Test Setup**
```bash
# Test Cloudinary connection
php test_cloudinary.php
```

### **Step 7: Deploy ke Vercel**
```bash
# Deploy aplikasi
vercel --prod
```

## 📁 **File yang Dibuat:**

| File | Deskripsi |
|------|-----------|
| `cloud_storage.php` | Class untuk handle upload ke Cloudinary |
| `test_cloudinary.php` | Script test koneksi Cloudinary |
| `update_gallery_cloudinary.php` | Script update gallery.php |

## 🔧 **Cara Kerja:**

### **Upload Process:**
1. User upload foto
2. File dikirim ke Cloudinary
3. Cloudinary mengembalikan URL
4. URL disimpan di database/JSON
5. Foto ditampilkan dari URL Cloudinary

### **Display Process:**
1. Ambil data foto dari database/JSON
2. Tampilkan foto dari URL Cloudinary
3. File tidak perlu disimpan di Vercel

## 💰 **Pricing Cloudinary:**

### **Free Tier:**
- **25GB storage**
- **25GB bandwidth/month**
- **25 credits/month**
- **Perfect untuk aplikasi kecil**

### **Paid Plans:**
- Mulai dari $89/month
- Unlimited storage & bandwidth
- Advanced features

## 🔐 **Keamanan:**

### **Upload Preset:**
- Set **Signing Mode** ke `Unsigned`
- Ini memungkinkan upload tanpa signature
- Aman untuk aplikasi client-side

### **Environment Variables:**
- Jangan hardcode credentials
- Gunakan environment variables
- Vercel encrypts environment variables

## 🧪 **Testing:**

### **Test Local:**
```bash
php test_cloudinary.php
```

### **Test Deployed:**
```bash
curl https://your-app.vercel.app/test_cloudinary.php
```

## 🔧 **Troubleshooting:**

### **Error: "Environment variables not set"**
- Cek environment variables di Vercel dashboard
- Pastikan semua 4 variables sudah diset
- Redeploy aplikasi setelah set variables

### **Error: "Upload failed"**
- Cek Cloudinary credentials
- Pastikan upload preset sudah dibuat
- Cek folder permissions di Cloudinary

### **Error: "Invalid signature"**
- Pastikan upload preset set ke `Unsigned`
- Cek API key dan secret
- Regenerate API credentials jika perlu

## 📊 **Monitoring:**

### **Di Cloudinary Dashboard:**
- Upload statistics
- Storage usage
- Bandwidth usage
- Error logs

### **Di Vercel Dashboard:**
- Function logs
- Environment variables
- Deployment status

## 🎯 **Alternatif Cloud Storage:**

### **1. Supabase Storage**
- 1GB free storage
- PostgreSQL database included
- Real-time features

### **2. AWS S3**
- Pay per use
- Highly scalable
- Enterprise features

### **3. Google Cloud Storage**
- 5GB free storage
- Global CDN
- Advanced features

## 🎉 **Hasil Akhir:**

Setelah setup Cloudinary, aplikasi Anda akan:
- ✅ Upload foto ke cloud storage
- ✅ File tersimpan permanen
- ✅ URL foto bisa diakses dari mana saja
- ✅ Tidak ada batasan filesystem Vercel
- ✅ Auto-scaling storage

## 📞 **Support:**

- **Cloudinary Docs**: [cloudinary.com/documentation](https://cloudinary.com/documentation)
- **Vercel Docs**: [vercel.com/docs](https://vercel.com/docs)
- **PHP cURL**: [php.net/manual/en/book.curl.php](https://php.net/manual/en/book.curl.php)