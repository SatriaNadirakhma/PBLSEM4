<style>
  .footer {
    background-color: #0074E0;
    color: white;
    padding: 40px 0;
    font-family: Arial, sans-serif;
  }

  .footer a,
  .footer p,
  .footer h4,
  .footer span,
  .footer li,
  .footer .sitename {
    color: #ffffff !important;
  }

  .footer a {
    text-decoration: none;
  }

  .footer a:hover {
    color: #cccccc !important;
    text-decoration: underline;
  }

  .footer h4 {
    font-weight: bold;
    margin-bottom: 15px;
  }

  .footer-top {
    padding-bottom: 30px;
  }

  .footer .social-links a {
    font-size: 18px;
    display: inline-block;
    margin-right: 10px;
    color: white;
    position: relative;
  }

  .footer .social-links a:hover {
    color: #cccccc;
  }

  .footer .copyright {
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    padding-top: 20px;
    font-size: 14px;
  }

  .footer ul {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .footer ul li {
    margin-bottom: 8px;
  }
</style>

<footer id="footer" class="footer">
  <div class="container footer-top">
    <div class="row gy-4">
      <!-- Kolom Kiri - Info Polinema -->
      <div class="col-lg-4 col-md-6 footer-about">
        <img src="{{ asset('img/logo_polinema.png') }}" alt="Logo Polinema" style="max-width: 100px; margin-bottom: 28px;">
        <a href="index.html" class="logo d-flex align-items-center">
          <span class="sitename">Sipinta <br> Polinema</span>
        </a>
        <div class="footer-contact pt-3">
          <p>Kantor UPA Bahasa, Graha Polinema, Lantai 3</p>
          <p>Jl. Soekarno-Hatta No. 9 Malang 65141</p>
          <p class="mt-3"><strong>Telepon:</strong> <span>(0341) 404424</span></p>
          <p><strong>Website:</strong> <span>polinema.ac.id</span></p>
        </div>
        <div class="social-links d-flex mt-4">
          <a href=""><i class="bi bi-twitter-x"></i></a>
          <a href=""><i class="bi bi-facebook"></i></a>
          <a href=""><i class="bi bi-instagram"></i></a>
          <a href=""><i class="bi bi-linkedin"></i></a>
        </div>
      </div>

      <!-- Kolom Dukungan -->
      <div class="col-lg-2 col-md-3 footer-links">
        <h4>Dukungan</h4>
        <ul>
          <li><a href="https://www.polinema.ac.id/" target="_blank" rel="noopener">Polinema</a></li>
          <li><a href="https://www.instagram.com/upabahasa/" target="_blank" rel="noopener">UPA Bahasa</a></li>
          <li><a href="https://itc-indonesia.com/" target="_blank" rel="noopener">ITC</a></li>
        </ul>
      </div>

      <!-- Kolom PSDKU -->
      <div class="col-lg-3 col-md-3 footer-links">
        <h4>PSDKU Polinema</h4>
        <ul>
          <li><a href="https://opac-pamekasan.polinema.ac.id/" target="_blank" rel="noopener">PSDKU Pamekasan</a></li>
          <li><a href="https://opac-kediri.polinema.ac.id/" target="_blank" rel="noopener">PSDKU Kediri</a></li>
          <li><a href="https://psdkulumajang.polinema.ac.id/" target="_blank" rel="noopener">PSDKU Lumajang</a></li>
        </ul>
      </div>

      <!-- Kolom Learning -->
      <div class="col-lg-3 col-md-3 footer-links">
        <h4>Learning</h4>
        <ul>
          <li><a href="https://lmsslc.polinema.ac.id/">LMS</a></li>
          <li><a href="https://okipolinema.wordpress.com/about/">OKI Polinema</a></li>
          <li><a href="#">Cookies Policy</a></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="container copyright text-center mt-4">
    <p>© <span>Copyright</span> <strong class="px-1 sitename">Sipinta Polinema</strong> <span>All Rights Reserved</span></p>
  </div>
</footer>
