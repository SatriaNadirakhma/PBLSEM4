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
        .separator {
            border: none;
            border-top: 3px solid #060505; /* Warna dan ketebalan garis */
            margin: 30px 0; /* Jarak atas dan bawah garis */
        }
    </style>
</head>
<body>
    <table class="border-bottom-header">
        <tr>
            <td width="15%" class="text-center">
                @php
                    $logoPath = public_path('img/polinema-bw.png');
                    $logoBase64 = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : '';
                @endphp
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" class="image">
                @endif
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

    @foreach($pendaftaran as $p)
        <table class="border-all" style="margin-bottom: 20px;">
            <tr>
                <th width="30%">Nama Mahasiswa</th>
                <td>{{ $p->mahasiswa->mahasiswa_nama }}</td>
            </tr>
            <tr>
                <th>NIM</th>
                <td>{{ $p->mahasiswa->nim }}</td>
            </tr>
            <tr>
                <th>NIK</th>
                <td>{{ $p->mahasiswa->nik }}</td>
            </tr>
            <tr>
                <th>No Telp</th>
                <td>{{ $p->mahasiswa->no_telp }}</td>
            </tr>
            <tr>
                <th>Alamat Asal</th>
                <td>{{ $p->mahasiswa->alamat_asal }}</td>
            </tr>
            <tr>
                <th>Alamat Sekarang</th>
                <td>{{ $p->mahasiswa->alamat_sekarang }}</td>
            </tr>
            <tr>
                <th>Program Studi</th>
                <td>{{ $p->mahasiswa->prodi->prodi_nama }}</td>
            </tr>
            <tr>
                <th>Jurusan</th>
                <td>{{ $p->mahasiswa->prodi->jurusan->jurusan_nama }}</td>
            </tr>
            <tr>
                <th>Kampus</th>
                <td>{{ $p->mahasiswa->prodi->jurusan->kampus->kampus_nama }}</td>
            </tr>
           <tr>
                <th>Scan KTP</th>
                <td class="text-left">
                    @php
                        $ktpBase64 = '';
                        if (!empty($p->scan_ktp)) {
                            $ktpPath = public_path('storage/' . $p->scan_ktp);
                            if (file_exists($ktpPath)) {
                                $ktpBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($ktpPath));
                            }
                        }
                    @endphp
                    @if($ktpBase64)
                        <img src="{{ $ktpBase64 }}" class="image">
                    @else
                        -
                    @endif
                </td>
            </tr>
           <tr>
                <th>Scan KTM</th>
                <td class="text-left">
                    @php
                        $ktmBase64 = '';
                        if (!empty($p->scan_ktm)) {
                            $ktmPath = public_path('storage/' . $p->scan_ktm);
                            if (file_exists($ktmPath)) {
                                $ktmBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($ktmPath));
                            }
                        }
                    @endphp
                    @if($ktmBase64)
                        <img src="{{ $ktmBase64 }}" class="image">
                    @else
                        -
                    @endif
                </td>
            </tr>
           <tr>
                <th>Pas Foto</th>
                <td class="text-left">
                    @php
                        $pas_fotoBase64 = '';
                        if (!empty($p->pas_foto)) {
                            $pas_fotoPath = public_path('storage/' . $p->pas_foto);
                            if (file_exists($pas_fotoPath)) {
                                $pas_fotoBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($pas_fotoPath));
                            }
                        }
                    @endphp
                    @if($pas_fotoBase64)
                        <img src="{{ $pas_fotoBase64 }}" class="image">
                    @else
                        -
                    @endif
                </td>
            </tr>
                <th>Jadwal</th>
                <td>{{ $p->jadwal->tanggal_pelaksanaan . ' - ' . $p->jadwal->jam_mulai }}</td>
            </tr>
        </table>
        @if (!$loop->last)
            <hr class="separator">
        @endif
    @endforeach

</body>
</html>