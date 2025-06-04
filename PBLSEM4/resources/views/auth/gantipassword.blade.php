<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password | Sipinta</title>
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
        .password-box {
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

        .password-box img {
            height: 60px;
            margin-bottom: 1.5rem;
            display: block;
            margin-left: auto;
            margin-right: auto;
            margin-top: -30px;
        }

        .password-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .password-description {
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
        
        .password-strength {
            font-size: 0.8rem;
            margin-top: 0.5rem;
            text-align: left;
        }
        .strength-weak { color: #dc3545; }
        .strength-medium { color: #ffc107; }
        .strength-strong { color: #28a745; }
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
        <div class="password-box">
            <img src="{{ asset('img/logo_sipinta.png') }}" alt="tcToeic Logo">
            
            <h3 class="password-title">Ganti Password</h3>
            <p class="password-description">
                Masukkan password baru Anda. Pastikan password yang kuat untuk keamanan akun.
            </p>
            
            <form id="newPasswordForm">
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">
                
                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password baru" required>
                    <div id="password-strength" class="password-strength"></div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru" required>
                </div>

                <button type="submit" class="form-button">Ganti Password</button>

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
        // Password strength checker
        $('#password').on('input', function() {
            const password = $(this).val();
            const strengthDiv = $('#password-strength');
            
            if (password.length === 0) {
                strengthDiv.text('');
                return;
            }
            
            let strength = 0;
            let strengthText = '';
            let strengthClass = '';
            
            // Check password strength
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            if (strength <= 2) {
                strengthText = 'Password lemah';
                strengthClass = 'strength-weak';
            } else if (strength <= 3) {
                strengthText = 'Password sedang';
                strengthClass = 'strength-medium';
            } else {
                strengthText = 'Password kuat';
                strengthClass = 'strength-strong';
            }
            
            strengthDiv.text(strengthText).removeClass('strength-weak strength-medium strength-strong').addClass(strengthClass);
        });

        $('#newPasswordForm').on('submit', function(e) {
            e.preventDefault();

            const password = $('#password').val();
            const confirmPassword = $('#password_confirmation').val();

            // Check if passwords match
            if (password !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Tidak Cocok',
                    text: 'Password dan konfirmasi password harus sama.',
                    heightAuto: false
                });
                return;
            }

            // Check minimum password length
            if (password.length < 8) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Terlalu Pendek',
                    text: 'Password minimal 8 karakter.',
                    heightAuto: false
                });
                return;
            }

            // Disable button to prevent double submission
            $('#newPasswordForm button').prop('disabled', true).text('Mengganti...');

            $.ajax({
                url: "{{ route('password.update') }}",
                method: "POST",
                data: {
                    token: $('input[name="token"]').val(),
                    email: $('input[name="email"]').val(),
                    password: password,
                    password_confirmation: confirmPassword,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Password Berhasil Diubah!',
                            text: response.message || 'Password Anda telah berhasil diubah.',
                            showConfirmButton: true,
                            heightAuto: false
                        }).then(() => {
                            window.location.href = '/login';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Mengubah Password',
                            text: response.message || 'Terjadi kesalahan saat mengubah password.',
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
                    $('#newPasswordForm button').prop('disabled', false).text('Ganti Password');
                }
            });
        });
    </script>

</body>
</html>