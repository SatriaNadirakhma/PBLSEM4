<!DOCTYPE html>
<html>
<head>
    <title>Data Admin</title>
</head>
<body>
    <h1>Daftar Admin</h1>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Admin</th>
                <th>No Telp</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $admin)
            <tr>
                <td>{{ $admin->admin_id }}</td>
                <td>{{ $admin->admin_nama }}</td>
                <td>{{ $admin->no_telp }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
