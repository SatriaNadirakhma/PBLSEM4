<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi untuk Mahasiswa - SIPINTA POLINEMA</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', Arial, sans-serif;
            line-height: 1.6;
            color: #2c3e50;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            position: relative;
        }
        
        .email-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4);
        }
        
        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="%23ffffff" opacity="0.05"/><circle cx="10" cy="60" r="0.5" fill="%23ffffff" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
            animation: float 20s infinite linear;
        }
        
        @keyframes float {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }
        
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 700;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
        }
        
        .header h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }
        
        .header .subtitle {
            font-size: 16px;
            opacity: 0.9;
            font-weight: 300;
            position: relative;
            z-index: 1;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .greeting-card {
            background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }
        
        .greeting-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: rotate(45deg);
        }
        
        .greeting-card h2 {
            font-size: 24px;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }
        
        .nim-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            position: relative;
            z-index: 1;
        }
        
        .message-box {
            background: #f8f9fa;
            border-left: 5px solid #3498db;
            padding: 25px;
            border-radius: 0 15px 15px 0;
            margin: 25px 0;
            position: relative;
        }
        
        .message-box::before {
            content: '💬';
            position: absolute;
            top: -10px;
            left: 20px;
            background: #3498db;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }
        
        .message-content {
            font-size: 16px;
            line-height: 1.7;
            color: #2c3e50;
            margin-top: 10px;
        }
        
        .footer {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 30px;
            text-align: center;
            border-top: 1px solid #dee2e6;
        }
        
        .signature {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .signature-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #28a745, #20c997);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }
        
        .signature-text {
            text-align: left;
        }
        
        .signature-text .title {
            font-weight: 600;
            color: #2c3e50;
            font-size: 16px;
        }
        
        .signature-text .org {
            font-size: 14px;
            color: #6c757d;
            margin-top: 2px;
        }
        
        .divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, #3498db, transparent);
            margin: 25px 0;
            border-radius: 1px;
        }
        
        .footer-note {
            font-size: 12px;
            color: #6c757d;
            font-style: italic;
        }
        
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            
            .content {
                padding: 25px 20px;
            }
            
            .header {
                padding: 30px 20px;
            }
            
            .header h1 {
                font-size: 24px;
            }
            
            .greeting-card h2 {
                font-size: 20px;
            }
            
            .signature {
                flex-direction: column;
                text-align: center;
            }
            
            .signature-text {
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">SP</div>
            <h1>SIPINTA POLINEMA</h1>
            <div class="subtitle">Sistem Informasi Penjadwalan Mata Kuliah</div>
        </div>
        
        <div class="content">
            <div class="greeting-card">
                <h2>Halo, {{ $nama }}!</h2>
                <div class="nim-badge">NIM: {{ $nim }}</div>
            </div>
            
            <div class="message-box">
                <div class="message-content">
                    {!! nl2br(e($pesan)) !!}
                </div>
            </div>
            
            <div class="divider"></div>
        </div>
        
        <div class="footer">
            <div class="signature">
                <div class="signature-icon">👥</div>
                <div class="signature-text">
                    <div class="title">Tim Administrasi</div>
                    <div class="org">SIPINTA POLINEMA</div>
                </div>
            </div>
            
            <div class="footer-note">
                Email ini dikirim secara otomatis oleh sistem SIPINTA. Harap tidak membalas email ini.
            </div>
        </div>
    </div>
</body>
</html>