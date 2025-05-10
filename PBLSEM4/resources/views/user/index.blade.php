<!DOCTYPE html>
<html>
<head>
    <title>Data User</title>
</head>
<body>
    <h1>Daftar User</h1>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Username</th>
                <th>Role</th>
                <th>Admin ID</th>
                <th>Mahasiswa ID</th>
                <th>Dosen ID</th>
                <th>Tendik ID</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $user)
            <tr>
                <td>{{ $user->user_id }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->role }}</td>
                <td>{{ $user->admin_id }}</td>
                <td>{{ $user->mahasiswa_id }}</td>
                <td>{{ $user->dosen_id }}</td>
                <td>{{ $user->tendik_id }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
