<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SIPINTA POLINEMA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * { margin: 0; padding: 0; font-family: 'Inter', sans-serif; box-sizing: border-box; }
        body { display: flex; height: 100vh; background-color: #1a1a1a; }

        .left {
            flex: 1;
            background: url('{{ asset('img/gedung_polinema1.jpg') }}') no-repeat center center;
            background-size: cover;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: flex-end;
            color: white;
            padding: 3rem;
            position: relative;
        }

        .left h2 {
            font-size: 2rem;
            line-height: 1.5;
            font-weight: 600;
            max-width: 70%;
        }

        .logo-container {
            position: absolute;
            top: 1.5rem;
            left: 1.5rem;
        }

        .logo-container img {
            height: 50px;
        }

        .right {
            flex: 1;
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
            margin-top: -30px;
            text-align: center;
        }

        .login-box img {
            height: 60px;
            margin-bottom: 1.5rem;
            display: block;
            margin-left: auto;
            margin-right: auto;
            margin-top: -30px;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            text-align: left;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border-radius: 6px;
            border: 1px solid #ccc;
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

        .footer {
            position: absolute;
            bottom: 1rem;
            font-size: 0.75rem;
            color: #999;
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

    <div class="right">
        <div class="login-box">
            <img src="{{ asset('img/logo_sipinta.png') }}" alt="Logo SIPINTA">
            <form id="loginForm">
                <div class="form-group">
                    <label for="username">Nomor Induk Mahasiswa (username)</label>
                    <input type="text" id="username" name="username" placeholder="Ketik Nama Pengguna" required>
                </div>

                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <input type="password" id="password" name="password" placeholder="Ketik Kata Sandi" required>
                </div>

                <button type="submit" class="form-button">Masuk!</button>

                <div class="form-links">
                    <a href="#">Lupa kata sandi?</a> | 
                    <a href="#">Bantuan lainnya</a>
                </div>
            </form>

            <div class="footer">
                © 2025 SIPINTA (Hak Cipta Dilindungi oleh Undang-Undang).
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
                    if(response.status) {
                        window.location.href = response.redirect;
                    } else {
                        alert(response.message || 'Login gagal!');
                    }
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                }
            });
        });
    </script>

</body>
</html>
