<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>SIPINTA POLINEMA</title>

  <!-- Favicons -->
  <link href="{{ asset('img/logo.png') }}" rel="icon">
  <link href="{{ asset('invent/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Poppins&family=Raleway&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('invent/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('invent/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('invent/assets/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('invent/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('invent/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{ asset('invent/assets/css/main.css') }}" rel="stylesheet">
</head>

<body>

  @include('about.headerlanding')

  @yield('content')

  @include('landing.footerlanding')

  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
      <i class="bi bi-arrow-up-short"></i>
  </a>

  <div id="preloader"></div>

  <!-- JS Files -->
  <script src="{{ asset('invent/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('invent/assets/vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ asset('invent/assets/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset('invent/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('invent/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
  <script src="{{ asset('invent/assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
  <script src="{{ asset('invent/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>

  <script src="{{ asset('invent/assets/js/main.js') }}"></script>
</body>
</html>
