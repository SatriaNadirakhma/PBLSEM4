<!DOCTYPE html>
<html>
<head>
    <title>Data Hasil Ujian</title>
</head>
<body>
    <h1>Daftar Hasil Ujian</h1>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nilai Listeninng</th>
                <th>Nilai Reading</th>
                <th>Nilai Total</th>
                <th>Status</th>
                <th>Catatan</th>
                <th>Jadwal ID</th>
                <th>User ID</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $hasil_ujian)
                <tr>
                    <td>{{ $hasil_ujian->hasil_id }}</td>
                    <td>{{ $hasil_ujian->nilai_listening }}</td>
                    <td>{{ $hasil_ujian->nilai_reading }}</td>
                    <td>{{ $hasil_ujian->nilai_total }}</td>
                    <td>{{ $hasil_ujian->status_lulus }}</td>
                    <td>{{ $hasil_ujian->catatan }}</td>
                    <td>{{ $hasil_ujian->jadwal_id }}</td>
                    <td>{{ $hasil_ujian->user_id }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
