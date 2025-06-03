<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Fitur Kartu</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- AOS CSS -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <style>
    body {
      background-color: #f9f9f9;
      font-family: sans-serif;
    }

    .feature-card {
      overflow: hidden;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .image-wrapper {
      position: relative;
      overflow: hidden;
      border-radius: 15px;
    }

    .image-wrapper img {
      transition: transform 0.5s ease;
      width: 100%;
      height: auto;
    }

    .image-wrapper:hover img {
      transform: scale(1.1);
    }

    .overlay-text {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      padding: 1rem;
      background: rgba(0, 0, 0, 0.6);
      color: #fff;
      text-align: center;
      font-weight: 500;
      font-size: 1rem;
      border-bottom-left-radius: 15px;
      border-bottom-right-radius: 15px;
    }
  </style>
</head>
<body>

<!-- Call To Action 2 Section -->
<section id="Fitur" id="call-to-action-2" class="py-5">
  <div class="container" data-aos="fade-up" data-aos-delay="100">

    <div class="text-center mb-5">
      <h2>Beberapa Fitur Untuk Mempermudah Anda</h2>
    </div>

    <div class="row g-4 justify-content-center">
      <!-- Card 1 -->
      <div class="col-md-4" data-aos="zoom-in" data-aos-delay="200">
        <div class="card feature-card">
          <div class="image-wrapper">
             <img src="{{ asset('img/book_background.png') }}" class="card-img" alt="Feature 1">
            <div class="overlay-text">
              Semua Informasi Hanya Dalam Satu Peramban
            </div>
          </div>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="col-md-4" data-aos="zoom-in" data-aos-delay="300">
        <div class="card feature-card">
          <div class="image-wrapper">
             <img src="{{ asset('img/book_background.png') }}" class="card-img" alt="Feature 2">
            <div class="overlay-text">
              Terkoneksi dari Pusat Hingga Seluruh Mahasiswa
            </div>
          </div>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="col-md-4" data-aos="zoom-in" data-aos-delay="400">
        <div class="card feature-card">
          <div class="image-wrapper">
             <img src="{{ asset('img/book_background.png') }}" class="card-img" alt="Feature 3">
            <div class="overlay-text">
              Mudah dan Nyaman Digunakan
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- AOS JS -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init();
</script>

<!-- Bootstrap JS (optional, untuk komponen interaktif) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
