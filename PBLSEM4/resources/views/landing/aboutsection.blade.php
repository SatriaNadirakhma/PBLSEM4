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




<style>
  /* Efek hover zoom pada gambar */
  .img-hover-zoom img {
    transition: transform 0.4s ease;
  }

  .img-hover-zoom:hover img {
    transform: scale(1.08);
  }
</style>

<section id="about" class="about section bg-primary text-white py-5">

  <div class="container" data-aos="fade-up">

    <div class="row justify-content-center mb-4">
      <div class="col-lg-10 text-center">
        <h2 class="text-white fw-bold">Tentang Kami</h2>
        <p class="mt-3 fs-5">
          Kami membuat peramban untuk mahasiswa dalam mendaftar uji TOEIC di Civitas Akademika Politeknik Negeri Malang.
        </p>
        <a href="#about" class="btn btn-outline-light mt-3">Tentang Kami</a>
      </div>
    </div>

    <div class="row g-4 mt-4">
      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
        <div class="img-hover-zoom rounded-4 shadow overflow-hidden">
          <img src="{{ asset('img/about_section1.png') }}" class="img-fluid w-100 h-100 object-fit-cover" style="aspect-ratio: 4/3;" alt="Graduation Image">
        </div>
      </div>
      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
        <div class="img-hover-zoom rounded-4 shadow overflow-hidden">
          <img src="{{ asset('img/about_section2.jpg') }}" class="img-fluid w-100 h-100 object-fit-cover" style="aspect-ratio: 4/3;" alt="Students Gathering">
        </div>
      </div>
      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
        <div class="img-hover-zoom rounded-4 shadow overflow-hidden">
          <img src="{{ asset('img/about_section3.jpg') }}" class="img-fluid w-100 h-100 object-fit-cover" style="aspect-ratio: 4/3;" alt="Writing Test">
        </div>
      </div>
    </div>

  </div>

</section>
