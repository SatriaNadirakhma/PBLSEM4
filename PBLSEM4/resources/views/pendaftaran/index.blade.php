<!DOCTYPE html>
<html>
<head>
    <title>Data Pendaftaran</title>
</head>
<body>
    <h1>Daftar Pendaftaran</h1>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Kode Pendaftaran</th>
                <th>Tanggal Pendaftaran</th>
                <th>Scan KTP</th>
                <th>Scan KTM</th>
                <th>Pas Foto</th>
                <th>Mahasiswa ID</th>
                <th>Jadwal ID</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $pendaftaran)
                <tr>
                    <td>{{ $pendaftaran->pendaftaran_id }}</td>
                    <td>{{ $pendaftaran->pendaftaran_kode }}</td>
                    <td>{{ $pendaftaran->tanggal_pendaftaran }}</td>
                    <td>{{ $pendaftaran->scan_ktp }}</td>
                    <td>{{ $pendaftaran->scan_ktm }}</td>
                    <td>{{ $pendaftaran->pas_foto }}</td>
                    <td>{{ $pendaftaran->mahasiswa_id }}</td>
                    <td>{{ $pendaftaran->jadwal_id }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
