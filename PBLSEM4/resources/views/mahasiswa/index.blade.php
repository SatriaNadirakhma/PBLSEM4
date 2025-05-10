<!DOCTYPE html>
<html>
<head>
    <title>Data Mahasiswa</title>
</head>
<body>
    <h1>Daftar Mahasiswa</h1>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>NIM</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>Angkatan</th>
            <th>No Telp</th>
            <th>Alamat Asal</th>
            <th>Alamat Sekarang</th>
            <th>Jenis Kelamin</th>
            <th>Status</th>
            <th>Keterangan</th>
            <th>Prodi ID</th>
        </tr>
        @foreach($data as $mhs)
        <tr>
            <td>{{ $mhs->mahasiswa_id }}</td>
            <td>{{ $mhs->nim }}</td>
            <td>{{ $mhs->nik }}</td>
            <td>{{ $mhs->mahasiswa_nama }}</td>
            <td>{{ $mhs->angkatan }}</td>
            <td>{{ $mhs->no_telp }}</td>
            <td>{{ $mhs->alamat_asal }}</td>
            <td>{{ $mhs->alamat_sekarang }}</td>
            <td>{{ $mhs->jenis_kelamin }}</td>
            <td>{{ $mhs->status }}</td>
            <td>{{ $mhs->keterangan }}</td>
            <td>{{ $mhs->prodi_id }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
