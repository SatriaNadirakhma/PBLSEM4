<!DOCTYPE html>
<html>
<head>
    <title>Data Dosen</title>
</head>
<body>
    <h1>Daftar Dosen</h1>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>NIDN</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>No Telp</th>
                <th>Alamat Asal</th>
                <th>Alamat Sekarang</th>
                <th>Jenis Kelamin</th>
                <th>Jurusan ID</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $dosen)
            <tr>
                <td>{{ $dosen->dosen_id }}</td>
                <td>{{ $dosen->nidn }}</td>
                <td>{{ $dosen->nik }}</td>
                <td>{{ $dosen->dosen_nama }}</td>
                <td>{{ $dosen->no_telp }}</td>
                <td>{{ $dosen->alamat_asal }}</td>
                <td>{{ $dosen->alamat_sekarang }}</td>
                <td>{{ $dosen->jenis_kelamin }}</td>
                <td>{{ $dosen->jurusan_id }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
