<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Sipinta</title>
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
            background-size: cover;
            display: flex;
            align-items: flex-end;
            justify-content: left;
            color: white;
            padding: 3rem;
            border-radius: 20px;
            margin: 0.6rem;
        }

        .right { flex: 1; background: white; display: flex; justify-content: center; align-items: center; padding: 2rem; }
        .reset-box {
            width: 100%;
            max-width: 400px;
            margin-top: -30px;
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

        .reset-box img {
            height: 60px;
            margin-bottom: 1.5rem;
            display: block;
            margin-left: auto;
            margin-right: auto;
            margin-top: -30px;
        }

        .reset-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .reset-description {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.4;
        }

        .form-group { margin-bottom: 1rem; }
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            text-align: left;
        }

        .form-group input { width: 100%; padding: 0.75rem; border-radius: 6px; border: 1px solid #ccc; }
        .form-button { width: 100%; background: #0d6efd; color: white; padding: 0.75rem; font-weight: 600; border: none; border-radius: 6px; cursor: pointer; margin-top: 1rem; }
        .form-button:hover { background: #0b5ed7; }
        .form-links { text-align: center; margin-top: 1rem; }
        .form-links a { color: #0d6efd; text-decoration: none; font-size: 0.9rem; margin: 0 5px; }
        .footer { position: absolute; bottom: 1rem; font-size: 0.75rem; color: #999; }
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
        <div class="reset-box">
            <img src="{{ asset('img/logo_sipinta.png') }}" alt="tcToeic Logo">
            
            <h3 class="reset-title">Reset Password</h3>
            <p class="reset-description">
                Masukkan email yang terhubung dengan akun Anda. Kami akan mengirimkan link untuk mereset password.
            </p>
            
            <form id="resetForm">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan alamat email Anda" required>
                </div>

                <button type="submit" class="form-button">Kirim Link Reset</button>

                <div class="form-links">
                    <a href="/login">Kembali ke Login</a> | 
                    <a href="/">Halaman Awal</a>
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
        $('#resetForm').on('submit', function(e) {
            e.preventDefault();

            // Disable button to prevent double submission
            $('#resetForm button').prop('disabled', true).text('Mengirim...');

            $.ajax({
                url: "{{ route('password.email') }}",
                method: "POST",
                data: {
                    email: $('#email').val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Email Terkirim!',
                            text: response.message || 'Link reset password telah dikirim ke email Anda.',
                            showConfirmButton: true,
                            heightAuto: false
                        }).then(() => {
                            $('#email').val('');
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Mengirim',
                            text: response.message || 'Email tidak ditemukan atau terjadi kesalahan.',
                            heightAuto: false
                        });
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: errorMessage,
                        heightAuto: false
                    });
                },
                complete: function() {
                    // Re-enable button
                    $('#resetForm button').prop('disabled', false).text('Kirim Link Reset');
                }
            });
        });
    </script>

</body>
</html>