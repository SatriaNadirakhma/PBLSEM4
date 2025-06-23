@extends('about.templateabout')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tentang Sipinta</title>
  <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
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
  <section class="mt-18 pb-16 px-4 md:px-8 max-w-full mx-auto"  style="max-width: 95%"> <!-- max-w-7xl diganti max-w-full -->
    <div class="custom-box p-6 md:p-12 lg:p-16"> <!-- padding disesuaikan -->
      <div class="grid md:grid-cols-2 gap-10 items-center">
        <div>
          <h3 class="text-purple-200 font-semibold text-sm mb-3">Tentang Kami</h3>
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
       <div class="text-center md:text-right md:pl-50">
  <img src="img/ilustrasiAbout.png" alt="Sipinta Illustration" class="w-full max-w-md mx-auto md:ml-auto rounded-xl" />
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

<!-- Benefit Section -->
<section id="benefit" class="relative position-relative" style="min-height: 450px; overflow: hidden;">
  <!-- Background Image -->
  <div style="background: url('{{ asset('img/gedung_polinema1.jpg') }}') center/cover no-repeat; 
              position: absolute; 
              inset: 0; 
              z-index: 1;">
  </div>

  <!-- Overlay putih transparan -->
  <div style="background: rgba(255, 255, 255, 0.28); position: absolute; inset: 0; z-index: 2;"></div>

  <!-- Content -->
  <div class="container h-100 d-flex align-items-center justify-content-center position-relative" style="z-index: 3;">
    <div class="text-white text-center px-4 py-10" style="background: rgba(0, 0, 0, 0.6); max-width: 1000px; border-radius: 16px;">
      <h2 class="fw-bold mb-4" style="color: #fff;">Apa <em>Benefit</em> dari TOEIC?</h2>
      <p class="mb-0 fs-5" style="color: #f1f1f1;">
        TOEIC membantu Anda meningkatkan kemampuan bahasa Inggris dalam konteks profesional. 
        Dengan skor TOEIC, Anda dapat membuka peluang karier internasional, memperkuat CV, 
        dan memenuhi persyaratan bahasa di berbagai institusi dan perusahaan global.
      </p>
    </div>
  </div>
</section>




<!-- Team Section -->
<section id="team" class="team section light-background">

  <!-- Custom CSS for 5-Column Layout -->
  <style>
    .team-row {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
    }
    .team-column {
      flex: 0 0 20%;
      max-width: 20%;
    }

    @media (max-width: 992px) {
      .team-column {
        flex: 0 0 50%;
        max-width: 50%;
      }
    }

    @media (max-width: 576px) {
      .team-column {
        flex: 0 0 100%;
        max-width: 100%;
      }
    }
  </style>

  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <h2>Tim</h2>
    <p>Kolaborasi, inovasi, dan semangat kerja adalah kekuatan tim kami.</p>
  </div><!-- End Section Title -->

  <div class="container" data-aos="fade-up" data-aos-delay="100">

    <div class="row team-row">
<!-- Member 1 -->
<div class="team-column" data-aos="zoom-in" data-aos-delay="100">
  <div class="team-card">
    <div class="team-image" style="overflow: hidden;">
      <img src="{{ asset('img/Satria.png') }}"
           class="img-fluid w-100 h-100 object-fit-cover"
           style="aspect-ratio: 3/4;"
           alt="Foto Satria">
      
        
      </div>
    <div class="team-content">
      <h4>Satria Rakhmadani</h4>
      <span class="position">Project Manager, UI/UX Designer, & Full-Stack</span>
    </div>
  </div>
</div><!-- End Team Member -->


   <!-- Member 2 -->
<div class="team-column" data-aos="zoom-in" data-aos-delay="200">
  <div class="team-card">
    <div class="team-image" style="overflow: hidden;">
            <img src="{{ asset('img/aqila.png') }}" class="img-fluid w-100 h-100 object-fit-cover" style="aspect-ratio: 3/4;" alt="Foto Aqila">
     
    </div>
    <div class="team-content">
      <h4>Aqila Nur Azza</h4>
      <span class="position">Database Administrator & Full-Stack Developer</span>
    </div>
  </div>
</div><!-- End Team Member -->

<!-- Member 3 -->
<div class="team-column" data-aos="zoom-in" data-aos-delay="300">
  <div class="team-card">
    <div class="team-image" style="overflow: hidden;">
            <img src="{{ asset('img/Faiza .png') }}" class="img-fluid w-100 h-100 object-fit-cover" style="aspect-ratio: 3/4;" alt="Foto Faiza">
      
    </div>
    <div class="team-content">
      <h4>Faiza Anasthasya Eka Valen</h4>
      <span class="position">Database Administrator & Back-End Developer</span>
    </div>
  </div>
</div><!-- End Team Member -->

<!-- Member 4 -->
<div class="team-column" data-aos="zoom-in" data-aos-delay="400">
  <div class="team-card">
    <div class="team-image" style="overflow: hidden;">
            <img src="{{ asset('img/Lyra.png') }}" class="img-fluid w-100 h-100 object-fit-cover" style="aspect-ratio: 3/4;" alt="Foto Lyra">
     
    </div>
    <div class="team-content">
      <h4>Lyra Faiqah Bilqis</h4>
      <span class="position">UI/UX Designer & Front-End Developer</span>
    </div>
  </div>
</div><!-- End Team Member -->

<!-- Member 5 -->
<div class="team-column" data-aos="zoom-in" data-aos-delay="500">
  <div class="team-card">
    <div class="team-image" style="overflow: hidden;">
            <img src="{{ asset('img/Fauzi.png') }}" class="img-fluid w-100 h-100 object-fit-cover" style="aspect-ratio: 3/4;" alt="Foto Reishi">
      
    </div>
    <div class="team-content">
      <h4>M. Reishi Fauzi</h4>
      <span class="position">Full-Stack Developer</span>
    </div>
  </div>
</div><!-- End Team Member -->


    </div>

  </div>

</section>
<!-- /Team Section -->




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
