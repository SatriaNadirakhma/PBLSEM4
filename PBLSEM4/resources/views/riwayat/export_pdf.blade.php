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
        }
        th {
            text-align: left;
        }
        .d-block {
            display: block;
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
        .font-12 {
            font-size: 12pt;
        }
        .font-13 {
            font-size: 13pt;
        }
        .font-bold {
            font-weight: bold;
        }
        .mb-1 {
            margin-bottom: 4px;
        }
        .border-bottom-header {
            border-bottom: 1px solid;
        }
        .border-all, .border-all th, .border-all td {
            border: 1px solid;
        }
        img.image {
            width: auto;
            height: 80px;
            max-width: 150px;
            max-height: 150px;
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

    <h3 class="text-center">LAPORAN DATA PENDAFTARAN</h3>

    <table class="border-all">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Nama Mahasiswa</th>
                <th>NIM</th>
                <th>NIK</th>
                <th>No Telp</th>
                <th>Alamat Asal</th>
                <th>Alamat Sekarang</th>
                <th>Program Studi</th>
                <th>Jurusan</th>
                <th>Kampus</th>
                <th>Scan KTP</th>
                <th>Scan KTM</th>
                <th>Pas Foto</th>
                <th>Jadwal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendaftaran as $p)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $p->mahasiswa->mahasiswa_nama }}</td>
                <td>{{ $p->mahasiswa->nim }}</td>
                <td>{{ $p->mahasiswa->nik }}</td>
                <td>{{ $p->mahasiswa->no_telp }}</td>
                <td>{{ $p->mahasiswa->alamat_asal }}</td>
                <td>{{ $p->mahasiswa->alamat_sekarang }}</td>
                <td>{{ $p->mahasiswa->prodi->prodi_nama }}</td>
                <td>{{ $p->mahasiswa->prodi->jurusan->jurusan_nama }}</td>
                <td>{{ $p->mahasiswa->prodi->jurusan->kampus->kampus_nama }}</td>
                <td>
                    @if($p->mahasiswa->scan_ktp)
                        <a href="{{ asset('storage/pendaftaran/scan_ktp/' . $p->mahasiswa->scan_ktp) }}" target="_blank">
                            <img src="{{ asset('storage/pendaftaran/scan_ktp/' . $p->mahasiswa->scan_ktp) }}" class="img-thumbnail" width="150">
                        </a>
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($p->mahasiswa->scan_ktm)
                        <a href="{{ asset('storage/pendaftaran/scan_ktm/' . $p->mahasiswa->scan_ktm) }}" target="_blank">
                            <img src="{{ asset('storage/pendaftaran/scan_ktm/' . $p->mahasiswa->scan_ktm) }}" class="img-thumbnail" width="150">
                        </a>
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($p->mahasiswa->pas_foto)
                        <a href="{{ asset('storage/pendaftaran/pas_foto/' . $p->mahasiswa->pas_foto) }}" target="_blank">
                            <img src="{{ asset('storage/pendaftaran/pas_foto/' . $p->mahasiswa->pas_foto) }}" class="img-thumbnail" width="150">
                        </a>
                    @else
                        -
                    @endif
                </td>
                <td>{{ $p->jadwal->tanggal_pelaksanaan . ' - ' . $p->jadwal->jam_mulai }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
