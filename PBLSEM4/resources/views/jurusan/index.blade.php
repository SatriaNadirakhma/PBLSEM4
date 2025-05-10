
<!DOCTYPE html>
<html>
<head>
    <title>Data Jurusan</title>
</head>
<body>
    <h1>Daftar Jurusan</h1>
    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Kode Jurusan</th>
            <th>Nama Jurusan</th>
            <th>ID Kampus</th>
        </tr>
        @foreach($data as $jurusan)
        <tr>
            <td>{{ $jurusan->jurusan_id }}</td>
            <td>{{ $jurusan->jurusan_kode }}</td>
            <td>{{ $jurusan->jurusan_nama }}</td>
            <td>{{ $jurusan->kampus_id }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>