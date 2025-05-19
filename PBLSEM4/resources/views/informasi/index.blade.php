<!DOCTYPE html>
<html>
<head>
    <title>Data Informasi</title>
</head>
<body>
    <h1>Daftar Informasi</h1>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Isi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $informasi)
                <tr>
                    <td>{{ $informasi->informasi_id }}</td>
                    <td>{{ $informasi->judul }}</td>
                    <td>{{ $informasi->isi }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
