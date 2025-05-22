<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 6px 20px 5px 20px;
            line-height: 1.3;
            font-size: 9pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td, th {
            padding: 2px;
            border: 1px solid #000;
        }

        th {
            text-align: left;
            background-color: #f2f2f2;
        }

        .d-block {
            display: block;
        }

        img.image {
            width: auto;
            height: 80px;
            max-width: 150px;
            max-height: 150px;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .font-10 { font-size: 10pt; }
        .font-11 { font-size: 11pt; }
        .font-12 { font-size: 12pt; }
        .font-bold { font-weight: bold; }

        .border-bottom-header {
            border-bottom: 1px solid;
        }

        .no-border td {
            border: none !important;
        }
    </style>
</head>
<body>

    <table class="no-border">
        <tr>
            <td width="15%" class="text-center">
                <img src="{{ public_path('img/polinema-bw.png') }}" class="image">
            </td>
            <td width="85%">
                <span class="text-center d-block font-11 font-bold">
                    KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI
                </span>
                <span class="text-center d-block font-12 font-bold">
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
    <hr class="separator">

    <h3 class="text-center">LAPORAN DATA MAHASISWA</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
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
                <th>Prodi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mahasiswa as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $item->nim }}</td>
                <td>{{ $item->nik }}</td>
                <td>{{ $item->mahasiswa_nama }}</td>
                <td class="text-center">{{ $item->angkatan }}</td>
                <td>{{ $item->no_telp }}</td>
                <td>{{ $item->alamat_asal }}</td>
                <td>{{ $item->alamat_sekarang }}</td>
                <td class="text-center">{{ $item->jenis_kelamin }}</td>
                <td class="text-center">{{ $item->status }}</td>
                <td>{{ $item->keterangan }}</td>
                <td>{{ $item->prodi->prodi_nama ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
