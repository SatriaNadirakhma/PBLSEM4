<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - siPinta</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            margin: 20px 0;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .email-header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .email-body {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #333;
        }
        .message {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
            color: #555;
        }
        .reset-button {
            text-align: center;
            margin: 30px 0;
        }
        .reset-button a {
            display: inline-block;
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            color: white;
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
        }
        .reset-button a:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(13, 110, 253, 0.4);
        }
        .security-notice {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 6px;
            padding: 15px;
            margin: 25px 0;
            font-size: 14px;
        }
        .security-notice strong {
            color: #856404;
        }
        .alternative-link {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            font-size: 14px;
            word-break: break-all;
        }
        .alternative-link strong {
            display: block;
            margin-bottom: 8px;
            color: #495057;
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 25px 30px;
            text-align: center;
            border-top: 1px solid #dee2e6;
        }
        .email-footer p {
            margin: 5px 0;
            font-size: 14px;
            color: #6c757d;
        }
        .expire-info {
            color: #dc3545;
            font-weight: 600;
            font-size: 14px;
            margin-top: 15px;
        }
        .logo {
            width: 60px;
            height: 60px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>🔐 Reset Password</h1>
            <p>siPinta - Sistem Informasi TOEIC</p>
        </div>
        
        <div class="email-body">
            <div class="greeting">
                Halo, {{ $name }}!
            </div>
            
            <div class="message">
                Kami menerima permintaan untuk mereset password akun siPinta Anda yang terdaftar dengan email <strong>{{ $email }}</strong>.
            </div>
            
            <div class="message">
                Untuk melanjutkan proses reset password, silakan klik tombol di bawah ini:
            </div>
            
            <div class="reset-button">
                <a href="{{ url('/password/reset/' . $token . '?email=' . urlencode($email)) }}">
                    Ganti Password Sekarang
                </a>
            </div>
            
            <div class="security-notice">
                <strong>⚠️ Penting untuk Keamanan:</strong><br>
                • Link ini hanya berlaku selama 1 jam setelah email ini dikirim<br>
                • Jika Anda tidak meminta reset password, abaikan email ini<br>
                • Jangan bagikan link ini kepada siapa pun
            </div>
            
            <div class="alternative-link">
                <strong>Jika tombol di atas tidak berfungsi, salin dan tempel link berikut ke browser Anda:</strong>
                {{ url('/password/reset/' . $token . '?email=' . urlencode($email)) }}
            </div>
            
            <div class="expire-info">
                ⏰ Link ini akan kedaluwarsa dalam 1 jam
            </div>
        </div>
        
        <div class="email-footer">
            <p><strong>siPinta (Sistem Informasi TOEIC)</strong></p>
            <p>English Starts Now. With TOEIC, Future Become Must!</p>
            <p style="margin-top: 15px; font-size: 12px;">
                © 2025 siPinta. Hak Cipta Dilindungi oleh Undang-Undang.
            </p>
            <p style="font-size: 12px; color: #999;">
                Email ini dikirim secara otomatis, mohon jangan membalas email ini.
            </p>
        </div>
    </div>
</body>
</html>