<!-- Logo Section -->
<section class="py-5 position-relative text-center text-dark" style="background: url('{{ asset('img/gedung_background.png') }}') center center / cover no-repeat;">
  <div class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(255, 255, 255, 0.57); z-index: 1;"></div>

  <div class="container position-relative" style="z-index: 2;">
    <p class="fw-semibold">Dukungan Institusi & Eksternal</p>
    <div class="row justify-content-center align-items-center text-center">
      
      <!-- Logo Kiri (Polinema) -->
      <div class="col-6 col-md-3 d-flex justify-content-center">
        <img src="{{ asset('img/logo_polinema.png') }}" alt="Logo Polinema" class="img-fluid" style="max-height: 110px;">
      </div>

      <!-- Logo Tengah (Unit Bahasa) - tetap, disejajarkan -->
      <div class="col-12 col-md-4 d-flex flex-column align-items-center">
        <img src="{{ asset('img/logo_polinema.png') }}" alt="Logo Unit Bahasa" class="img-fluid" style="max-height: 80px;">
        <p class="mt-2 mb-0 small text-center" style="color: #4B0082;">
          Unit Pelaksana Akademik Bahasa<br><strong>Politeknik Negeri Malang</strong>
        </p>
      </div>

      <!-- Logo Kanan (ITC) -->
      <div class="col-6 col-md-3 d-flex justify-content-center">
        <img src="{{ asset('img/logo_itc.png') }}" alt="Logo ITC" class="img-fluid" style="max-height: 300px;">
      </div>

    </div>
  </div>
</section>




<!-- Overview Section -->
<section id="overview" class="py-5 position-relative" style="background-color: #007bff; height: 70vh; overflow: hidden;">
  <div class="container h-100">
    <div class="row h-100">

      <!-- Kolom Foto -->
      <div class="col-md-4 position-relative d-flex align-items-end " style="padding-bottom: 10px;">
        <!-- Lingkaran & Foto -->
        <div style="width: 430px; height: 400px; border-radius: 50%; border: 12px solid white; background-color: white; position: relative; left: -25%;">
        <img src="{{ asset('img/atiqah.png') }}" alt="Foto Dosen"
     style="width: 410px; height: 580px; object-fit: cover; border-radius: 12px; position: absolute; top: 55%; left: 85%; transform: translate(-85%, -50%);">

        </div>

        <!-- Nama & Jabatan -->
        <div class="position-absolute" style="bottom: 30px; text-align: center;">
          <div style="background-color:rgb(221, 177, 56); color: white; font-weight: bold; font-size: 1.30rem; padding: 6px 20px; border-radius: 6px;">
            Atiqah Nurul Asri, S.Pd., M.Pd.
          </div>
</div>
<div class="position-absolute" style="bottom: 0px; text-align: center;">
          <div style="background-color: #1d4ed8; color: white; font-size: 0.85rem; padding: 4px 12px; margin-right: 50px; border-radius: 5px;">
    Ketua UPA Bahasa
  </div>
        </div>
      
</div>
      <!-- Kolom Teks -->
<div class="col-md-8 d-flex flex-column justify-content-center text-white">
  <div class="mb-2">
    <span class="badge bg-white text-primary mb-3" style="font-size: 1.5rem; margin-bottom: 20px;">● Overview</span>
  </div>

  <h4 class="fw-bold text-white">Selamat datang di Sistem Informasi Pendaftaran dan Integrasi TOEIC Politeknik Negeri Malang!</h4>

  <p style="text-align: justify;">
    <strong>UPA Bahasa</strong> sebagai unit pelaksana akademik yang bertanggung jawab dalam pengembangan kemampuan bahasa asing sivitas akademika, terus berupaya memberikan layanan terbaik dalam mendukung kesiapan mahasiswa menghadapi tantangan global.
    <span class="collapse fade" id="moreText" style="padding-top: 20px;">
      Salah satu bentuk komitmen tersebut adalah melalui penyelenggaraan tes TOEIC (Test of English for International Communication) secara berkala. Dengan adanya sistem informasi ini, kami berharap proses pendaftaran TOEIC menjadi lebih mudah, cepat, dan transparan.
      Mahasiswa dapat melakukan pendaftaran, melihat jadwal tes, serta memantau status pendaftaran secara daring kapan saja dan di mana saja. Kami mengajak seluruh mahasiswa untuk memanfaatkan fasilitas ini sebaik mungkin sebagai bagian dari persiapan menghadapi dunia kerja maupun studi lanjut.
      Terima kasih atas kepercayaan dan partisipasi Anda. Mari bersama kita tingkatkan kompetensi bahasa untuk masa depan yang lebih baik.
    </span>
    <!-- Tombol Selengkapnya di tengah paragraf -->
    <div class="text-center my-2">
      <a class="text-white d-inline-flex align-items-center" data-bs-toggle="collapse" href="#moreText" role="button" aria-expanded="false" aria-controls="moreText" style="cursor: pointer; text-decoration: underline; transition: color 0.2s;">
      <span id="arrowIcon" style="display: inline-block; transition: transform 0.3s;">
        <svg width="18" height="18" viewBox="0 0 20 20" fill="none">
        <path d="M5 8l5 5 5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </span>
      <span class="ms-1">baca selengkapnya</span>
      </a>
    </div>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
      var moreText = document.getElementById('moreText');
      var arrowIcon = document.getElementById('arrowIcon');
      var link = document.querySelector('[href="#moreText"]');
      link.addEventListener('click', function() {
        setTimeout(function() {
        if (moreText.classList.contains('show')) {
          arrowIcon.style.transform = 'rotate(180deg)';
        } else {
          arrowIcon.style.transform = 'rotate(0deg)';
        }
        }, 350); // match Bootstrap collapse transition
      });
      });
    </script>

  
  </p>
</div>



    </div>
  </div>
</section>
