@extends('about.templateabout')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tentang Sipinta</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />

  <style>
    body {
      background-color: #ffffff;
    }

    .custom-box {
      background-color: #1e3a8a;
      border-radius: 24px;
    }
  </style>
</head>
<body>

  <!-- SECTION ABOUT -->
  <section id="body">
  <section class="mt-18 pb-16 px-4 md:px-16 max-w-7xl mx-auto">
    <div class="custom-box p-10 md:p-14">
      <div class="grid md:grid-cols-2 gap-10 items-center">
        <div>
          <h3 class="text-purple-200 font-semibold text-sm mb-3">About Us</h3>
          <h2 class="text-3xl md:text-4xl font-bold text-white leading-tight mb-5">
            Solusi Digital untuk Pendaftaran TOEIC yang Lebih Rapi dan Cepat
          </h2>
           <p class="text-purple-100 mb-5">
            <strong>SIPINTA</strong> (Sistem Informasi Pendaftaran TOEIC Polinema) adalah platform digital yang dirancang khusus untuk mempermudah proses pendaftaran dan manajemen data TOEIC di Politeknik Negeri Malang.
          </p>
          <p class="text-purple-100 mb-5">
            Sipinta Polinema dikembangkan sebagai respons atas proses pendaftaran TOEIC yang masih manual dan tersebar di berbagai platform seperti WhatsApp, Google Form, dan Google Drive.
          </p>
          <p class="text-purple-100 mb-5">
            Dengan sistem ini, mahasiswa dan admin dapat mengakses informasi secara real-time, mulai dari status pendaftaran, jadwal ujian, hingga pengumuman hasil tes, semua dalam satu platform terpusat yang efisien dan aman.
          </p>
          <p class="text-purple-100">
            Kami percaya bahwa transformasi digital bukan hanya soal teknologi, tapi juga tentang menghadirkan layanan yang lebih cepat, akurat, dan mudah diakses oleh semua pihak.
          </p>
        </div>
        <div class="text-center">
          <img src="img/ilustrasi.jpg" alt="Sipinta Illustration" class="w-full max-w-md mx-auto rounded-xl" />
        </div>
      </div>

      <div class="grid md:grid-cols-3 text-center mt-12 gap-6">
  <div>
    <p class="text-5xl font-extrabold text-white">10k+</p>
    <p class="text-sm text-purple-200 mt-1">Pengguna Aktif</p>
  </div>
  <div>
    <p class="text-5xl font-extrabold text-white">5+</p>
    <p class="text-sm text-purple-200 mt-1">Jurusan Terintegrasi</p>
  </div>
  <div>
    <p class="text-5xl font-extrabold text-white">98%</p>
    <p class="text-sm text-purple-200 mt-1">Kepuasan Pengguna</p>
  </div>
</div>

    </div>
  </section>
</section>


   <!-- Visi & Misi -->
<section id="visi-misi" class="bg-white -mt-20 py-20 px-4 md:px-16 max-w-7xl mx-auto">
  <div class="text-center mb-12">
    <h3 class="text-purple-600 font-semibold text-sm mb-2">Visi & Misi</h3>
    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 leading-tight">
  Komitmen Kami untuk Transformasi Layanan Bahasa di Polinema
</h2>

    <p class="text-gray-600 mt-4 max-w-2xl mx-auto">
      Kami bertekad menyediakan sistem informasi yang modern, efisien, dan aman demi mendukung layanan TOEIC bagi mahasiswa dan alumni.
    </p>
  </div>

  <div class="grid md:grid-cols-2 gap-12 items-start text-center">
    <div>
      <h4 class="text-xl font-bold text-purple-700 mb-3">Visi</h4>
      <p class="text-gray-600">
        Menjadi solusi digital utama dalam penyelenggaraan layanan TOEIC yang terintegrasi, transparan, dan mudah diakses seluruh civitas akademika Polinema.
      </p>
    </div>

    <div>
      <h4 class="text-xl font-bold text-purple-700 mb-3">Misi</h4>
      <ul class="text-gray-600 space-y-2 text-left list-disc list-inside max-w-md mx-auto">
        <li>Mengganti proses manual menjadi sistem otomatis dan real-time</li>
        <li>Menjadikan satu platform sebagai pusat informasi pendaftaran TOEIC</li>
        <li>Meningkatkan keamanan dan kerahasiaan data peserta</li>
        <li>Mempermudah komunikasi antara UPA Bahasa, admin jurusan, dan peserta</li>
        <li>Menyediakan akses nilai dan sertifikat secara efisien</li>
      </ul>
    </div>
  </div>
</section>


   <!-- Lokasi -->
<div class="mt-16 mb-20 text-center">
  <h3 class="text-2xl font-semibold text-gray-800 mb-3">Lokasi Kami</h3>
  <p class="text-gray-600 mb-5">
   Kantor UPA Bahasa, Graha Polinema, Lantai 3.<br>
   Jl. Soekarno Hatta No.9, Jatimulyo, Kec. Lowokwaru, Kota Malang, Jawa Timur 65141
  </p>
  <iframe 
    class="w-full max-w-2xl h-64 mx-auto rounded-lg shadow-md" 
    src="https://maps.google.com/maps?q=polinema&t=&z=15&ie=UTF8&iwloc=&output=embed" 
    frameborder="0" 
    allowfullscreen
    loading="lazy">
  </iframe>
</div>



</body>
</html>
