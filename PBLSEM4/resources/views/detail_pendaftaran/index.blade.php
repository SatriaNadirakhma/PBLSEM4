<!DOCTYPE html>
<html>
<head>
    <title>Data Detail Pendaftaran</title>
</head>
<body>
    <h1>Daftar Detail Pendaftaran</h1>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pendaftaran ID</th>
                <th>Status</th>
                <th>Catatan</th>
                <th>Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
            <tr>
                <td>{{ $item->detail_id }}</td>
                <td>{{ $item->pendaftaran_id }}</td>
                <td>{{ $item->status }}</td>
                <td>{{ $item->catatan ?? '-' }}</td>
                <td>{{ $item->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
