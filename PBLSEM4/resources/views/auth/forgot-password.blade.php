<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lupa Password | Sipinta</title>
  <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }

    html, body {
      height: 100%;
      overflow-x: hidden;
    }

    body {
      display: flex;
      background-color: #ffffff;
      min-height: 100vh;
    }

    .left {
      flex: 1;
      background: url('{{ asset('img/gedung_polinema1.jpg') }}') no-repeat center center;
      background-size: cover;
      display: flex;
      align-items: flex-end;
      justify-content: left;
      color: white;
      padding: 3rem;
      border-radius: 20px;
      margin: 0.6rem;
    }

    .right {
      flex: 1;
      background: white;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 2rem;
    }

    .forgot-password-box {
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    .left h2 {
      font-size: 2.10rem;
      line-height: 1.15;
      font-weight: 600;
      max-width: 70%;
    }

    .logo-container {
      position: absolute;
      top: 2rem;
      left: 2rem;
    }

    .logo-container img {
      height: 30px;
    }

    .forgot-password-box img {
      height: 60px;
      margin-bottom: 1.5rem;
      display: block;
      margin-left: auto;
      margin-right: auto;
      margin-top: -30px;
    }

    .form-group {
      margin-bottom: 1rem;
      text-align: left;
    }

    .form-group label {
      display: block;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .form-group input {
      width: 100%;
      padding: 0.75rem;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    .form-group input.is-invalid {
      border-color: #dc3545;
    }

    .invalid-feedback {
      display: block;
      font-size: 0.875em;
      color: #dc3545;
      margin-top: 0.25rem;
    }

    .form-button {
      width: 100%;
      background: #0d6efd;
      color: white;
      padding: 0.75rem;
      font-weight: 600;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 1rem;
    }

    .form-button:hover {
      background: #0b5ed7;
    }

    .form-links {
      text-align: center;
      margin-top: 1rem;
    }

    .form-links a {
      color: #0d6efd;
      text-decoration: none;
      font-size: 0.9rem;
      margin: 0 5px;
    }

    .form-links a:hover {
      text-decoration: underline;
    }

    .footer {
      margin-top: 2.5rem;
      font-size: 0.75rem;
      color: #999;
      text-align: center;
    }

    .description {
      text-align: center;
      color: #6c757d;
      font-size: 0.9rem;
      margin-bottom: 1.5rem;
      line-height: 1.4;
    }

    .alert {
      padding: 0.75rem 1rem;
      margin-bottom: 1rem;
      border: 1px solid transparent;
      border-radius: 6px;
      position: relative;
      text-align: left;
    }

    .alert-success {
      color: #155724;
      background-color: #d4edda;
      border-color: #c3e6cb;
    }

    .alert-danger {
      color: #721c24;
      background-color: #f8d7da;
      border-color: #f5c6cb;
    }

    .alert-dismissible .btn-close {
      position: absolute;
      top: 0;
      right: 0;
      z-index: 2;
      padding: 0.75rem 1rem;
      background: none;
      border: none;
      font-size: 1.25rem;
      cursor: pointer;
    }

    .alert ul {
      margin: 0;
      padding-left: 1.2rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      body {
        flex-direction: column;
      }

      .left, .right {
        flex: none;
        width: 100%;
        border-radius: 0;
        padding: 1.5rem;
      }

      .left {
        min-height: 250px;
        align-items: flex-end;
        justify-content: center;
        text-align: center;
      }

      .left h2 {
        max-width: 100%;
      }

      .logo-container {
        position: static;
        margin-bottom: 1rem;
      }
    }
  </style>
</head>
<body>

  <div class="left">
    <div class="logo-container">
      <img src="{{ asset('img/logowhite.png') }}" alt="Logo SIPINTA" />
    </div>
    <h2>English Starts Now.<br />With TOEIC, Future Become Must!</h2>
  </div>

  <div class="right">
    <div class="forgot-password-box">
      <img src="{{ asset('img/logo_sipinta.png') }}" alt="tcToeic Logo" />

      @if (session('status'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert">&times;</button>
      </div>
      @endif

      @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul>
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert">&times;</button>
      </div>
      @endif

      <div class="description">
        Masukkan username dan email Anda untuk mendapatkan link reset password
      </div>

      <form action="{{ url('/password/email') }}" method="POST" id="forgotPasswordForm">
        @csrf
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" class="@error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="Masukkan username Anda" required autocomplete="username" autofocus />
          @error('username')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" class="@error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Masukkan email Anda" required autocomplete="email" />
          @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <button type="submit" class="form-button">Kirim Link Reset Password</button>

        <div class="form-links">
          <a href="{{ url('/login') }}">Kembali ke Login</a> |
          <a href="{{ url('/') }}">Kembali ke Halaman Awal</a>
        </div>
      </form>

      <div class="footer">
        © 2025 siPinta (Hak Cipta Dilindungi oleh Undang-Undang).
      </div>
    </div>
  </div>

  <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/jquery-validation/additional-methods.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

  <script>
    // Auto dismiss alerts
    $('.alert-dismissible .btn-close').on('click', function () {
      $(this).closest('.alert').fadeOut();
    });

    // Optional: Auto hide success alerts after 5 seconds
    setTimeout(function () {
      $('.alert-success').fadeOut();
    }, 5000);
  </script>
</body>
</html>
