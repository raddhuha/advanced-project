# 🎯 Presentation App - Laravel Project

Aplikasi manajemen dan publikasi **tutorial presentasi** yang terintegrasi dengan **webservice otentikasi** dan sistem **PDF export**.  
User dapat login, membuat tutorial, menampilkannya secara publik, dan mengekspor sebagai PDF.

---

## 📦 Fitur Utama

- ✅ Login via Webservice (email & password)
- ✅ CRUD Master Tutorial
- ✅ CRUD Detail Tutorial
- ✅ Public Preview: Tutorial dapat diakses publik via `url_presentation`
- ✅ PDF Export via `url_finished`
- ✅ DataTable Dashboard dengan data mata kuliah dari webservice
- ✅ Status Show/Hide per detail tutorial
- ✅ Validasi URL unik (presentation & finished)

## 🚀 Cara Menjalankan
### 1. Clone & Install
git clone https://github.com/your-repo/presentation-app.git
cd presentation-app
composer install
npm install && npm run dev
cp .env.example .env
php artisan key:generate

### 2. Setup Database
Atur koneksi database di .env

DB_DATABASE=presentation
DB_USERNAME=root
DB_PASSWORD=

Jalankan migrasi
php artisan migrate

## 🔐 Akun Login Webservice
Gunakan akun berikut untuk login:

Email    : aprilyani.safitri@gmail.com  
Password : 123456
Autentikasi via endpoint:
https://jwt-auth-eight-neon.vercel.app/login

## 📄 API Webservice
POST /login → Mendapatkan refreshToken
GET /getMakul → Mendapatkan data mata kuliah (dengan refreshToken)

## 🛠 Teknologi
Laravel 12.
Blade Templates
Laravel DomPDF
Laravel Breeze
HTTP Client (untuk akses Webservice)
