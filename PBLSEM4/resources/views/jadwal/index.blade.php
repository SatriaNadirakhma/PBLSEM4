<!DOCTYPE html>
<html>
<head>
    <title>Data Jadwal</title>
   
</head>
<body>
    <h1>Daftar Jadwal</h1>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal Pelaksanaan</th>
                <th>Jam Mulai</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jadwals as $jadwal)
                <tr>
                    <td>{{ $jadwal->jadwal_id }}</td>
                    <td>{{ $jadwal->tanggal_pelaksanaan }}</td>
                    <td>{{ $jadwal->jam_mulai }}</td>
                    <td>{{ $jadwal->keterangan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
