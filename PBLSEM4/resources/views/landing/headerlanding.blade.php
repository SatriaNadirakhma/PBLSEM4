<header id="header" class="header d-flex align-items-center fixed-top bg-white">
  <style>
  .btn-gradient {
    display: inline-block;
    padding: 12px 32px;
    background: linear-gradient(to right, #007bff, #1e90ff);
    color: white;
    text-decoration: none;
    border: none;
    border-radius: 999px; /* pill shape */
    font-weight: bold;
    font-size: 16px;
    font-family: sans-serif;
    transition: background 0.3s ease;
    outline: 1px solid #1e90ff; /* outline color */
    outline-offset: 4px; /* space around the button */
  }

  .btn-white {
    display: inline-block;
    padding: 12px 32px;
    background: #fff;
    color: #1e90ff;
    text-decoration: none;
    border: none;
    border-radius: 999px; /* pill shape */
    font-weight: bold;
    font-size: 16px;
    font-family: sans-serif;
    transition: background 0.3s ease, color 0.3s ease;
    outline: 1px solid #ffffff; /* outline color */
    outline-offset: 4px; /* space around the button */
  }

  .btn-white:hover {
    background: #f0f8ff;
    color: #0056b3;
    text-decoration: none;
    outline: 3px solid #187bcd;
  }

  .btn-gradient:hover {
    background: linear-gradient(to right, #0056b3, #187bcd);
    color: white;
    text-decoration: none;
    outline: 3px solid #187bcd;
  }
  </style>

<div class="container position-relative d-flex align-items-center justify-content-between">

      <a href="index.html" class="logo d-flex align-items-center me-auto me-xl-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <img src="{{ asset('img/logo_sipinta.png') }}" alt="">
        <!-- <h1 class="sitename">Invent</h1><span>.</span> -->
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Halaman Awal</a></li>
          <li><a href="#overview">Overview</a></li>
           <li><a href="#about">Tentang Kami</a></li>
          <li><a href="#faq">FAQ</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-gradient" href="/login">Masuk!</a>
      
    </div>
</header>