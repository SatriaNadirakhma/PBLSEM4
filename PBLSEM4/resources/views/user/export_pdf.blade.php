<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 6px 20px 5px 20px;
            line-height: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td, th {
            padding: 4px 3px;
            font-size: 10pt;
        }
        th {
            text-align: center;
            font-weight: bold;
        }
        .d-block {
            display: block;
        }
        img.image {
            width: auto;
            height: 60px;
            max-width: 100px;
            max-height: 100px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .p-1 {
            padding: 5px 1px 5px 1px;
        }
        .font-10 {
            font-size: 10pt;
        }
        .font-11 {
            font-size: 11pt;
        }
        .font-13 {
            font-size: 13pt;
        }
        .border-bottom-header {
            border-bottom: 1px solid;
        }
        .border-all, .border-all th, .border-all td {
            border: 1px solid;
        }
    </style>
</head>
<body>
    <table class="border-bottom-header">
        <tr>
            <td width="15%" class="text-center">
                <img src="{{ public_path('img/polinema-bw.png') }}" class="image">
            </td>
            <td width="85%">
                <span class="text-center d-block font-11 font-bold mb-1">
                    KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI
                </span>
                <span class="text-center d-block font-13 font-bold mb-1">
                    POLITEKNIK NEGERI MALANG
                </span>
                <span class="text-center d-block font-10">
                    Jl. Soekarno-Hatta No. 9 Malang 65141
                </span>
                <span class="text-center d-block font-10">
                    Telepon (0341) 404424 Pes. 101-105, 0341-404420, Fax. (0341) 404420
                </span>
                <span class="text-center d-block font-10">
                    Laman: www.polinema.ac.id
                </span>
            </td>
        </tr>
    </table>

    <h3 class="text-center">LAPORAN DATA USER</h3>

    <table class="border-all">
        <thead>
            <tr>
                <th>No</th>
                <th>Email</th>
                <th>Username</th>
                <th>Foto Profil</th>
                <th>Role</th>
                <th>Admin ID</th>
                <th>Mahasiswa ID</th>
                <th>Dosen ID</th>
                <th>Tendik ID</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $u)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->username }}</td>
                <td class="text-center">
                    @php
                        $profilePath = $u->profile ? public_path('storage/' . $u->profile) : null;
                        $imgSrc = ($profilePath && file_exists($profilePath))
                            ? $profilePath
                            : public_path('img/default-profile.png');
                    @endphp
                    <img src="{{ $imgSrc }}" class="image">
                </td>
                <td>{{ $u->role }}</td>
                <td>{{ $u->admin_id ?? '-' }}</td>
                <td>{{ $u->mahasiswa_id ?? '-' }}</td>
                <td>{{ $u->dosen_id ?? '-' }}</td>
                <td>{{ $u->tendik_id ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
