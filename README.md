# 📝 Sistem Informasi Pendaftaran & Integrasi TOEIC Polinema - SIPINTA

Sistem ini dibuat untuk mempermudah proses **pendaftaran TOEIC mahasiswa Polinema** sekaligus terintegrasi ke seluruh pengguna civitas akademika Politeknik Negeri Malang.

<p align="center">
  <img src="PBLSEM4/public/img/logo_sipinta.png" alt="Logo SIPINTA" width="200" />
</p>

<p align="center">
  <img src="https://media.giphy.com/media/qgQUggAC3Pfv687qPC/giphy.gif" alt="Demo" />
</p>

---

## 🚀 Fitur Utama

- 🧾 Pendaftaran peserta TOEIC secara online berbasis Website
- 📤 Upload foto (Foto Profil, KTP, KTM, dsb.) 
- 🗓️ Menampilkan informasi lebih lengkap melalui dashboard
- 📲 Menambah Jadwal Ujian TOEIC
- 📥 Input dan integrasi nilai TOEIC
- 🔐 Role pengguna: Admin (UPA Bahasa), Mahasiswa, Dosen, Tenaga Pendidikan

---

## 🛠️ Teknologi yang Digunakan

- Framework: Laravel v.10
- Database: MySQL
- Autentikasi: SMTP (email verification)

---

## 📁 Struktur Folder

```
📁 PBLSEM4/
├── app/
├── public/
├── resources/
│   └── views/
├── routes/
│   └── web.php
├── .env
└── README.md
```

---

## 📋 Cara Menjalankan

```bash
# Clone project
git clone https://github.com/namamu/toeic-system.git
cd toeic-system

# Install dependency
composer install

# Salin file environment dan buat kunci aplikasi
cp .env.example .env
php artisan key:generate

# Jalankan migrasi database
php artisan migrate

# Jalankan server
php artisan serve
```

---

## 👤 Kontributor

- **Satria Rakhmadani**         – Project Manager, Full-Stack Developer, and UI/UX Designer
- **Aqila Nur Azza**            – Full-Stack Developer, and Database Administrator
- **Faiza Anathasya Eka Falen** – Back-End Developer, and Database Administrator
- **Lyra Faiqah Bilqis**        – Front-End Developer, and UI/UX Designer
- **Muhammad Reishi Fauzi**     – Full-Stack Developer 

---

> "Digitalisasi bukan masa depan, tapi kebutuhan saat ini."
