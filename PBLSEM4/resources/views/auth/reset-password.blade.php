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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * { margin: 0; padding: 0; font-family: 'Inter', sans-serif; box-sizing: border-box; }
        
        body { display: flex; height: 100vh; background-color: #ffffff; flex-wrap: nowrap; overflow-y: hidden;}
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
        }

        .reset-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .reset-subtitle {
            color: #666;
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }

        .form-group { margin-bottom: 1rem; }
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            text-align: left;
            color: #333;
        }

        .form-group label i {
            margin-right: 0.5rem;
            color: #0d6efd;
        }

        .form-group input { 
            width: 100%; 
            padding: 0.75rem; 
            border-radius: 6px; 
            border: 1px solid #ccc;
            font-size: 0.9rem;
        }

        .form-group input:focus {
            outline: none;
            border-color: #0d6efd;
            box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.1);
        }

        .input-group {
            position: relative;
            display: flex;
        }

        .input-group input {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .input-group .toggle-btn {
            background: #f8f9fa;
            border: 1px solid #ccc;
            border-left: none;
            border-top-right-radius: 6px;
            border-bottom-right-radius: 6px;
            padding: 0.75rem;
            cursor: pointer;
            color: #666;
            transition: background-color 0.2s;
        }

        .input-group .toggle-btn:hover {
            background: #e9ecef;
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
            font-size: 1rem;
            transition: background-color 0.2s;
        }

        .form-button:hover {
            background: #0b5ed7;
        }

        .form-button i {
            margin-right: 0.5rem;
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
            transition: color 0.2s;
        }
        .form-links a:hover {
            color: #0b5ed7;
        }
        .form-links a i {
            margin-right: 0.3rem;
        }

        .footer { 
            margin-top: 2.5rem;
      font-size: 0.75rem;
      color: #999;
      text-align: center;
        }

        .alert {
            background: #f8d7da;
            color: #721c24;
            padding: 0.75rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            border: 1px solid #f5c6cb;
        }

        .alert ul {
            margin: 0;
            padding-left: 1.2rem;
        }

        .alert i {
            margin-right: 0.5rem;
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }

        .is-valid {
            border-color: #28a745 !important;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.8rem;
            margin-top: 0.25rem;
            display: block;
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
        <div class="reset-box">
            <img src="{{ asset('img/logo_sipinta.png') }}" alt="Sipinta Logo">
            
            <h3 class="reset-title">Reset Password</h3>
            <p class="reset-subtitle">Masukkan password baru untuk akun Anda</p>

            @if ($errors->any())
                <div class="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ url('/password/reset') }}" method="POST" id="resetForm">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i>
                        Email
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ request()->get('email') ?? old('email') }}" 
                           required 
                           autocomplete="email"
                           placeholder="Masukkan email Anda"
                           class="@error('email') is-invalid @enderror">
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-key"></i>
                        Password Baru
                    </label>
                    <div class="input-group">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required 
                               autocomplete="new-password"
                               placeholder="Masukkan password baru (minimal 5 karakter)"
                               class="@error('password') is-invalid @enderror">
                        <button class="toggle-btn" 
                                type="button" 
                                id="togglePassword"
                                onclick="togglePasswordVisibility('password', 'togglePassword')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">
                        <i class="fas fa-key"></i>
                        Konfirmasi Password
                    </label>
                    <div class="input-group">
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required 
                               autocomplete="new-password"
                               placeholder="Konfirmasi password baru">
                        <button class="toggle-btn" 
                                type="button" 
                                id="togglePasswordConfirmation"
                                onclick="togglePasswordVisibility('password_confirmation', 'togglePasswordConfirmation')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="form-button">
                    <i class="fas fa-save"></i>
                    Reset Password
                </button>

                <div class="form-links">
                    <a href="{{ url('/login') }}">
                        <i class="fas fa-arrow-left"></i>
                        Kembali ke Login
                    </a>
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
        function togglePasswordVisibility(passwordFieldId, buttonId) {
            const passwordField = document.getElementById(passwordFieldId);
            const toggleButton = document.getElementById(buttonId);
            const icon = toggleButton.querySelector('i');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Validasi konfirmasi password real-time
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (password !== confirmPassword) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });

        document.getElementById('password').addEventListener('input', function() {
            const confirmPassword = document.getElementById('password_confirmation').value;
            const confirmField = document.getElementById('password_confirmation');
            
            if (confirmPassword && this.value !== confirmPassword) {
                confirmField.classList.add('is-invalid');
                confirmField.classList.remove('is-valid');
            } else if (confirmPassword && this.value === confirmPassword) {
                confirmField.classList.remove('is-invalid');
                confirmField.classList.add('is-valid');
            }
        });

        // Handle form submission dengan SweetAlert
        $('#resetForm').on('submit', function(e) {
            e.preventDefault();

            const password = $('#password').val();
            const confirmPassword = $('#password_confirmation').val();

            if (password !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Tidak Cocok',
                    text: 'Konfirmasi password harus sama dengan password baru.',
                    heightAuto: false
                });
                return;
            }

            $.ajax({
                url: $(this).attr('action'),
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Password berhasil direset. Silakan login dengan password baru.',
                        showConfirmButton: false,
                        timer: 2000,
                        heightAuto: false
                    }).then(() => {
                        window.location.href = "{{ url('/login') }}";
                    });
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
                }
            });
        });
    </script>

</body>
</html>