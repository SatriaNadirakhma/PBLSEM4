<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
        }
        .email-header .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .email-body {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        .content {
            font-size: 16px;
            margin-bottom: 30px;
            line-height: 1.8;
        }
        .reset-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            transition: transform 0.3s ease;
        }
        .reset-button:hover {
            transform: translateY(-2px);
            color: white;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .security-notice {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .security-notice h3 {
            color: #856404;
            margin-top: 0;
            font-size: 16px;
        }
        .security-notice p {
            color: #856404;
            margin: 0;
            font-size: 14px;
        }
        .alternative-link {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            word-break: break-all;
            font-size: 14px;
            color: #6c757d;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 30px 20px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 5px 0;
            font-size: 14px;
            color: #6c757d;
        }
        .footer .company-name {
            font-weight: bold;
            color: #495057;
        }
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e9ecef, transparent);
            margin: 30px 0;
        }
        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }
            .email-body {
                padding: 20px 15px;
            }
            .email-header {
                padding: 20px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="icon">🔐</div>
            <h1>Reset Password</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <div class="greeting">
                Halo, <strong>{{ $user->username }}</strong>!
            </div>

            <div class="content">
                <p>Kami menerima permintaan untuk mereset password akun Anda. Jangan khawatir, hal ini terjadi pada banyak orang.</p>
                
                <p>Untuk melanjutkan proses reset password, silakan klik tombol di bawah ini:</p>
            </div>

            <div class="button-container">
                <a href="{{ $resetUrl }}" class="reset-button">
                    🔑 Reset Password Saya
                </a>
            </div>

            <div class="security-notice">
                <h3>⚠️ Penting untuk Keamanan Anda:</h3>
                <p>• Link ini hanya berlaku selama <strong>24 jam</strong></p>
                <p>• Jika Anda tidak meminta reset password, abaikan email ini</p>
                <p>• Jangan bagikan link ini kepada siapa pun</p>
            </div>

            <div class="divider"></div>

            <p style="font-size: 14px; color: #6c757d;">
                <strong>Tidak bisa mengklik tombol di atas?</strong><br>
                Salin dan tempel link berikut ke browser Anda:
            </p>
            <div class="alternative-link">
                {{ $resetUrl }}
            </div>

            <div class="divider"></div>

            <p style="font-size: 14px; color: #6c757d; font-style: italic;">
                Email ini dikirim secara otomatis, mohon jangan membalas email ini. Jika Anda membutuhkan bantuan, silakan hubungi administrator sistem.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="company-name">{{ config('app.name') }}</p>
            <p>{{ date('Y') }} - Sistem Manajemen User</p>
            <p>Email dikirim pada: {{ now()->format('d F Y, H:i') }} WIB</p>
        </div>
    </div>
</body>
</html>