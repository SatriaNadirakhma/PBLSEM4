<style>
  /* Efek hover zoom pada gambar */
  .img-hover-zoom img {
    transition: transform 0.4s ease;
  }

  .img-hover-zoom:hover img {
    transform: scale(1.08);
  }


  /* Warna teks */
  .text-primary-custom {
    color: #0062FF;
  
  }

  .text-secondary-custom {
    color: #4B91F1;
  }

.btn-rounded-blue {
  background-color: #007bff;     /* Biru utama */
  color: #ffffff;
  padding: 10px 30px;            /* Biar lebih gendut */
  border: 2px solid #007bff;     /* Border biru */
  border-radius: 999px;
  font-weight: 600;
  font-size: 1rem;
  display: inline-block;
  position: relative;
  z-index: 1;
  text-decoration: none;
  transition: all 0.3s ease;
}

/* Border biru luar (lebih gelap biar kelihatan) */
.btn-rounded-blue::after {
  content: "";
  position: absolute;
  top: -5px;
  left: -5px;
  right: -5px;
  bottom: -5px;
  border-radius: 999px;
  border: 2px solid #0056b3;  /* Biru lebih tua dari tombol */
  z-index: -1;
}

.btn-rounded-blue:hover {
  background-color: #0056b3;
  border-color: #0056b3;
  color: #ffffff;
}

.about_section h2 {
  font-size: 2.5rem;   /* Default biasanya 2rem, ini kita besarkan */
  font-weight: 700;
  color: #0d6efd;      /* Kalau text-primary-custom → pastikan warnanya sesuai */
}

.about_section {
  position: relative;
  background-color: #fff;
  overflow: hidden;
}

.about_section::before {
  content: "";
  position: absolute;
  top: 60%;
  right: -100px; /* Geser lebih ke kiri */
  width: 700px;
  height: 550px;
  border: 2px solid #0d6efd;
  border-radius: 20%;
  border-right: none;
  border-bottom: none;
  transform: translateY(-50%);
  z-index: 0;
}


.about_section .container, 
.about_section h2, 
.about_section p {
  position: relative;
  z-index: 1; /* Supaya konten di atas garis */
}



</style>


<section id="about" class="about_section bg-white py-5 pt-5" style="height: 100vh; padding-top: 19rem;">
   <!-- Header -->
    <div class="row justify-content-center mb-4">
   <div class="col-lg-10 ps-0" style="margin-left: -13px;">
        <h2 class="fw-bold text-primary-custom">Tentang Kami</h2>
        <p class="mt-2 fs-5 text-secondary-custom">
          Kami membuat peramban untuk mahasiswa dalam <br> mendaftar uji TOEIC di Civitas Akademika Politeknik Negeri Malang.
        </p>

        <!-- Tombol di bawah teks, rata kanan -->
        <div class="text-end mt-3">
          <a href="{{ route('aboutpage') }}#body" class="btn-rounded-blue">Selengkapnya</a>
        </div>
      </div>
  

    <div class="container row justify-content-center mb-4" data-aos="fade-up" >
    <!-- Gambar -->
    <div class="row g-4 mt-2">
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
