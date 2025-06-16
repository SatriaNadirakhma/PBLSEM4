<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sipinta</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * { margin: 0; padding: 0; font-family: 'Inter', sans-serif; box-sizing: border-box; }
        
        body { display: flex; height: 100vh; background-color: #ffffff; }
        .left {
            flex: 1;
            background: url('{{ asset('img/gedung_polinema1.jpg') }}') no-repeat center center;
            background-size: cover; /* isi penuh, tanpa space hitam */
            display: flex;
            align-items: flex-end;
            justify-content: left;
            color: white;
            padding: 3rem;
            border-radius: 20px;
            margin: 0.6rem;
        }

        .right { flex: 1; background: white; display: flex; justify-content: center; align-items: center; padding: 2rem; }
        .login-box {
            width: 100%;
            max-width: 400px;
            margin-top: -30px; /* ini menggeser semua isi ke atas sedikit */
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
        
        .logo-container img{
        height: 30px;
        }

        .description {
            text-align: center;
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
            line-height: 1.4;
        }


        .login-box img {
            height: 60px;
            margin-bottom: 1.5rem;
            display: block;
            margin-left: auto;
            margin-right: auto;
            margin-top: -30px; /* supaya naik sedikit */
        }

        .form-group { margin-bottom: 1rem; }
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            text-align: left; /* tambahkan ini */
        }

        .form-group input { width: 100%; padding: 0.75rem; border-radius: 6px; border: 1px solid #ccc; }
        .form-button { width: 100%; background: #0d6efd; color: white; padding: 0.75rem; font-weight: 600; border: none; border-radius: 6px; cursor: pointer; margin-top: 1rem; }
        .form-links { text-align: center; margin-top: 1rem; }
        .form-links a { color: #0d6efd; text-decoration: none; font-size: 0.9rem; margin: 0 5px; }
        .footer { 
            position: static;
            margin-top: 2.5rem;
            font-size: 0.75rem; 
            color: #999; 
            text-align: center;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: flex-end;
        }
    </style>
</head>
<body>

    <div class="left">
            <div class="logo-container">
                <img src="{{ asset('img/logowhite.png') }}" alt="Logo SIPINTA">
            </div>
        <h2>English Starts Now.<br>With TOEIC, Future Become Must!</h2>
            
    </div>
        
    </div>

    <div class="right">
        <div class="login-box">
            <img src="{{ asset('img/logo_sipinta.png') }}" alt="tcToeic Logo">

            <div class="description">
                Masukkan username dan password Anda untuk login ke Dashboard Sipinta.
            </div>
            <form id="loginForm">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Ketik Nama Pengguna" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <input type="password" id="password" name="password" placeholder="Ketik Kata Sandi" required>
                </div>

                <button type="submit" class="form-button">Masuk!</button>

                <div class="form-links">
                    <a href="/password/reset">Lupa Password?</a> | 
                    <a href="/">Kembali ke Halaman Awal</a>
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
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('login') }}",
                method: "POST",
                data: {
                    username: $('#username').val(),
                    password: $('#password').val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message || 'Login berhasil!',
                            showConfirmButton: false,
                            timer: 1500,
                            heightAuto: false
                        }).then(() => {
                            window.location.href = response.redirect;
                        });

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Login Gagal',
                            text: response.message || 'Username atau password salah.',
                            heightAuto: false
                        });
                    }

                },
                error: function(xhr) {
                    Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan. Silakan coba lagi.'
                    });
                }

            });
        });
    </script>

</body>
</html>  