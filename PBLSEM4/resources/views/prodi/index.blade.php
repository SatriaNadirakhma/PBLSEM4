
<!DOCTYPE html>
<html>
<head>
    <title>Data Prodi</title>
</head>
<body>
    <h1>Daftar prodi</h1>
    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Kode Prodi</th>
            <th>Nama Prodi</th>
            <th>ID Jurusan</th>
        </tr>
        @foreach($data as $prodi)
        <tr>
            <td>{{ $prodi->prodi_id }}</td>
            <td>{{ $prodi->prodi_kode }}</td>
            <td>{{ $prodi->prodi_nama }}</td>
            <td>{{ $prodi->jurusan_id }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>