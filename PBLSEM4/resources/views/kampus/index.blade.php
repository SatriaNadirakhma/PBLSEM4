
<!DOCTYPE html>
<html>
<head>
    <title>Data Kampus</title>
</head>
<body>
    <h1>Daftar Kampus</h1>
    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Kode Kampus</th>
            <th>Nama Kampus</th>
        </tr>
        @foreach($data as $kampus)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $kampus->kampus_kode }}</td>
            <td>{{ $kampus->kampus_nama }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>