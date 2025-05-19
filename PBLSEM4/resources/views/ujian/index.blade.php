<!DOCTYPE html>
<html>
<head>
    <title>Data Ujian</title>
</head>
<body>
    <h1>Daftar Ujian</h1>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Kode Ujian</th>
                <th>Jadwal ID</th>
                <th>Pendaftaran ID</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $ujian)
                <tr>
                    <td>{{ $ujian->ujian_id }}</td>
                    <td>{{ $ujian->ujian_kode }}</td>
                    <td>{{ $ujian->jadwal_id }}</td>
                    <td>{{ $ujian->pendaftaran_id }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
