<header id="header" class="header d-flex align-items-center fixed-top bg-white">
    <div class="container position-relative d-flex align-items-center justify-content-between">

      <a href="index.html" class="logo d-flex align-items-center me-auto me-xl-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <img src="{{ asset('img/logo_sipinta.png') }}" alt="">
        <!-- <h1 class="sitename">Invent</h1><span>.</span> -->
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
<li><a href="{{ route('landingpage') }}#hero">Halaman Awal</a></li>
  <li><a href="#body" class="active">Tentang Kami</a></li>
  <li><a href="{{ route('landingpage') }}#Fitur">Fitur</a></li>
  <li><a href="{{ route('landingpage') }}#faq">FAQ</a></li>
  <li><a href="{{ route('landingpage') }}#benefit">Benefit</a></li>
  <li><a href="{{ route('landingpage') }}#overview">Overview</a></li>
  <li><a href="{{ route('landingpage') }}#team">Team</a></li>
</ul>

        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-getstarted" href="/login">Masuk!</a>

    </div>
</header>