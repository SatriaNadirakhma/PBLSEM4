<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Informasi untuk Mahasiswa</title>
</head>
<body>
    <h2>Halo, {{ $nama }} (NIM: {{ $nim }})</h2>
    <p>
        Berikut informasi yang ingin kami sampaikan:<br>
        {{-- Agar newline (\n) menjadi <br> --}}
        {!! nl2br(e($pesan)) !!}
    </p>
    <hr>
    <p>
        Terima kasih,<br>
        Tim Administrasi SIPINTA POLINEMA
    </p>
</body>
</html>
